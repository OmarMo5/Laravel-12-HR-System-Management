<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class PermissionController extends Controller
{
    /**
     * Display all permissions for HR/Admin/Manager.
     */
    public function index(Request $request)
    {
        // Role check (HR, Admin, Manager)
        if (!Auth::user()->hasAnyRole(['admin', 'hr', 'manager'])) {
            return redirect()->route('permissions.my')->with('error', 'Unauthorized access.');
        }

        $query = Permission::with('user')->orderBy('date', 'desc');

        // Filter by employee
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // Filter by date
        if ($request->filled('date')) {
            $query->whereDate('date', $request->date);
        }

        // Search by user name
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('id', $searchTerm)
                  ->orWhereHas('user', function ($uq) use ($searchTerm) {
                      $uq->where('name', 'like', '%' . $searchTerm . '%');
                  });
            });
        }

        $permissions = $query->paginate(10);
        $users = User::all();

        return view('HR.Permissions.index', compact('permissions', 'users'));
    }

    /**
     * Display user's own permissions.
     */
    public function myPermissions()
    {
        $permissions = Permission::where('user_id', Auth::id())
            ->orderBy('date', 'desc')
            ->paginate(10);
            
        $monthlyCount = Permission::where('user_id', Auth::id())
            ->whereYear('date', Carbon::now()->year)
            ->whereMonth('date', Carbon::now()->month)
            ->where('status', '!=', 'rejected')
            ->count();

        return view('HR.Permissions.my', compact('permissions', 'monthlyCount'));
    }

    /**
     * Store a newly created permission.
     */
    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|in:early_departure,late_arrival,mid_day_outing',
            'reason' => 'required|in:personal,work,both',
            'from_time' => 'required',
            'to_time' => 'required',
            'date' => 'required|date',
        ]);

        // Check monthly limit
        $monthlyCount = Permission::where('user_id', Auth::id())
            ->whereYear('date', Carbon::parse($request->date)->year)
            ->whereMonth('date', Carbon::parse($request->date)->month)
            ->where('status', '!=', 'rejected')
            ->count();

        if ($monthlyCount >= 2) {
            return redirect()->back()->with('error', __('messages.limit_reached'));
        }

        $permission = Permission::create([
            'user_id' => Auth::id(),
            'type' => $request->type,
            'reason' => $request->reason,
            'from_time' => $request->from_time,
            'to_time' => $request->to_time,
            'date' => $request->date,
            'status' => 'pending',
            'manager_status' => 'Pending',
        ]);

        // Notify Manager, HR, and Admin
        $recipients = User::whereIn('role_name', ['Manager', 'HR', 'Admin'])->get();
        foreach ($recipients as $recipient) {
            \App\Models\Notification::create([
                'user_id' => $recipient->user_id,
                'type' => 'permission_new',
                'title' => app()->getLocale() === 'ar' ? 'طلب إذن جديد' : 'New Permission Request',
                'message' => app()->getLocale() === 'ar' 
                    ? "طلب إذن جديد من الموظف: " . Auth::user()->name 
                    : "New permission request from: " . Auth::user()->name,
                'leave_id' => $permission->id, // Store permission ID here
                'is_read' => false,
            ]);
        }

        return redirect()->route('permissions.my')->with('success', __('messages.permission_submitted'));
    }

    /**
     * Update the status of a permission.
     */
    public function updateStatus(Request $request, $id)
    {
        $user = Auth::user();
        if (!$user->hasAnyRole(['admin', 'hr', 'manager'])) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $permission = Permission::with('user')->findOrFail($id);
        $newStatus = $request->status; // 'Approved' or 'Rejected'

        if ($user->role_name === 'Manager') {
            $permission->manager_status = $newStatus;
            $permission->approved_by = $user->name;

            if ($newStatus === 'Rejected') {
                $permission->status = 'rejected';
                // Notify Employee, HR, and Admin about rejection
                $recipients = User::whereIn('role_name', ['HR', 'Admin'])->get();
                foreach ($recipients as $recipient) {
                    \App\Models\Notification::create([
                        'user_id' => $recipient->user_id,
                        'type' => 'permission_rejected',
                        'title' => app()->getLocale() === 'ar' ? 'رفض إذن (مدير)' : 'Permission Rejected (Manager)',
                        'message' => app()->getLocale() === 'ar' 
                            ? "تم رفض إذن {$permission->user->name} بواسطة المدير" 
                            : "Permission for {$permission->user->name} was rejected by Manager",
                        'leave_id' => $permission->id,
                        'is_read' => false,
                    ]);
                }
                // Notify Employee
                \App\Models\Notification::create([
                    'user_id' => $permission->user->user_id,
                    'type' => 'permission_rejected',
                    'title' => app()->getLocale() === 'ar' ? 'تم رفض طلب الإذن' : 'Permission Request Rejected',
                    'message' => app()->getLocale() === 'ar' 
                        ? "عزيزي {$permission->user->name}، تم رفض طلب الإذن الخاص بك بواسطة المدير" 
                        : "Dear {$permission->user->name}, your permission request was rejected by Manager",
                    'leave_id' => $permission->id,
                    'is_read' => false,
                ]);
            } else {
                // Notify HR and Admin that it's now their turn
                $recipients = User::whereIn('role_name', ['HR', 'Admin'])->get();
                foreach ($recipients as $recipient) {
                    \App\Models\Notification::create([
                        'user_id' => $recipient->user_id,
                        'type' => 'permission_manager_approved',
                        'title' => app()->getLocale() === 'ar' ? 'موافقة مدير على إذن' : 'Manager Approved Permission',
                        'message' => app()->getLocale() === 'ar' 
                            ? "وافق المدير على إذن {$permission->user->name}، بانتظار قرارك النهائي" 
                            : "Manager approved permission for {$permission->user->name}, awaiting your final decision",
                        'leave_id' => $permission->id,
                        'is_read' => false,
                    ]);
                }
            }
            $permission->save();

        } elseif ($user->role_name === 'HR' || $user->role_name === 'Admin') {
            if ($permission->manager_status !== 'Approved' && $newStatus === 'Approved') {
                return redirect()->back()->with('error', 'Manager must approve this permission first.');
            }

            $permission->status = strtolower($newStatus);
            $permission->approved_by = $user->name;
            if ($request->filled('admin_notes')) {
                $permission->admin_notes = $request->admin_notes;
            }
            $permission->save();

            // Notify Employee of final decision
            \App\Models\Notification::create([
                'user_id' => $permission->user->user_id,
                'type' => $permission->status === 'approved' ? 'permission_approved' : 'permission_rejected',
                'title' => app()->getLocale() === 'ar' ? 'تحديث حالة الإذن' : 'Permission Status Update',
                'message' => app()->getLocale() === 'ar' 
                    ? "تم تحديث حالة طلب الإذن الخاص بك إلى: " . ($permission->status === 'approved' ? 'مقبول' : 'مرفوض') 
                    : "Your permission request status has been updated to: " . ucfirst($permission->status),
                'leave_id' => $permission->id,
                'is_read' => false,
            ]);
        }

        return redirect()->back()->with('success', __('messages.status_updated'));
    }
    /**
     * Update the specified permission.
     */
    public function update(Request $request, $id)
    {
        $permission = Permission::findOrFail($id);
        
        // Authorization: Owner or HR/Admin
        if ($permission->user_id !== Auth::id() && !Auth::user()->hasAnyRole(['Admin', 'HR'])) {
            return redirect()->back()->with('error', 'Unauthorized action.');
        }

        // Only allow editing if pending
        if ($permission->status !== 'pending' && !Auth::user()->hasAnyRole(['Admin', 'HR'])) {
            return redirect()->back()->with('error', 'Cannot edit a processed request.');
        }

        $request->validate([
            'type' => 'required|in:early_departure,late_arrival,mid_day_outing',
            'reason' => 'required|in:personal,work,both',
            'from_time' => 'required',
            'to_time' => 'required',
            'date' => 'required|date',
        ]);

        $permission->update([
            'type' => $request->type,
            'reason' => $request->reason,
            'from_time' => $request->from_time,
            'to_time' => $request->to_time,
            'date' => $request->date,
        ]);

        return redirect()->back()->with('success', __('messages.status_updated'));
    }

    /**
     * Remove the specified permission.
     */
    public function destroy($id)
    {
        $permission = Permission::findOrFail($id);
        
        // Authorization: Owner or HR/Admin
        if ($permission->user_id !== Auth::id() && !Auth::user()->hasAnyRole(['Admin', 'HR'])) {
            return redirect()->back()->with('error', 'Unauthorized action.');
        }

        $permission->delete();

        return redirect()->back()->with('success', 'Deleted successfully');
    }
}

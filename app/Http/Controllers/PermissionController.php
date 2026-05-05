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
            $query->whereHas('user', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
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

        Permission::create([
            'user_id' => Auth::id(),
            'type' => $request->type,
            'reason' => $request->reason,
            'from_time' => $request->from_time,
            'to_time' => $request->to_time,
            'date' => $request->date,
            'status' => 'pending',
        ]);

        return redirect()->route('permissions.my')->with('success', __('messages.permission_submitted'));
    }

    /**
     * Update the status of a permission.
     */
    public function updateStatus(Request $request, $id)
    {
        if (!Auth::user()->hasAnyRole(['admin', 'hr', 'manager'])) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $permission = Permission::findOrFail($id);
        $permission->update([
            'status' => $request->status,
            'admin_notes' => $request->admin_notes,
        ]);

        return redirect()->back()->with('success', __('messages.status_updated'));
    }
}

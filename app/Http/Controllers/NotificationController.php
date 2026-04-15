<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Notification::where('user_id', Auth::user()->user_id)
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        return view('notifications.index', compact('notifications'));
    }

    public function show($id)
    {
        $notification = Notification::where('user_id', Auth::user()->user_id)
            ->where('id', $id)
            ->firstOrFail();
        
        $notification->is_read = true;
        $notification->save();
        
        return redirect()->route('hr/view/detail/leave', $notification->leave_id);
    }

    public function markAsRead($id)
    {
        try {
            $notification = Notification::where('user_id', Auth::user()->user_id)
                ->where('id', $id)
                ->firstOrFail();
            
            $notification->is_read = true;
            $notification->save();
            
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function markAllRead()
    {
        try {
            Notification::where('user_id', Auth::user()->user_id)
                ->where('is_read', false)
                ->update(['is_read' => true]);
            
            if (request()->ajax()) {
                return response()->json(['success' => true]);
            }
            
            flash()->success('All notifications marked as read');
            return redirect()->back();
        } catch (\Exception $e) {
            if (request()->ajax()) {
                return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
            }
            flash()->error('Failed to mark notifications as read');
            return redirect()->back();
        }
    }
}
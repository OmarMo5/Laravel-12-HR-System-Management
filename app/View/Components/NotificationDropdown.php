<?php

namespace App\View\Components;

use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\View\Component;

class NotificationDropdown extends Component
{
    public $notifications;
    public $unreadCount;

    public function __construct()
    {
        if (Auth::check()) {
            $userId = Auth::user()->user_id;
            $this->notifications = Notification::where('user_id', $userId)
                ->orderBy('created_at', 'desc')
                ->limit(20)
                ->get();
            $this->unreadCount = Notification::where('user_id', $userId)
                ->where('is_read', false)
                ->count();
        } else {
            $this->notifications = collect();
            $this->unreadCount = 0;
        }
    }

    public function render(): View
    {
        return view('components.notification-dropdown');
    }
}
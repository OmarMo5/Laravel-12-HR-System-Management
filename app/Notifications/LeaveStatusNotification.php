<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class LeaveStatusNotification extends Notification
{
    use Queueable;

    protected string $status;
    protected string $leaveType;

    public function __construct(string $status, string $leaveType)
    {
        $this->status    = $status;
        $this->leaveType = $leaveType;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        $message = $this->status === 'Approved'
            ? "تمت الموافقة على طلب الإجازة الخاص بك ({$this->leaveType})"
            : "تم رفض طلب الإجازة الخاص بك ({$this->leaveType})";

        return [
            'message'    => $message,
            'status'     => $this->status,
            'leave_type' => $this->leaveType,
        ];
    }
}
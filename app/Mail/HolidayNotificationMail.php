<?php

namespace App\Mail;

use App\Models\Holiday;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class HolidayNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $holiday;

    public function __construct(Holiday $holiday)
    {
        $this->holiday = $holiday;
    }

    public function envelope(): Envelope
    {
        $startDate = \Carbon\Carbon::parse($this->holiday->start_date);
        $endDate = \Carbon\Carbon::parse($this->holiday->end_date);
        
        if ($startDate->format('Y-m-d') == $endDate->format('Y-m-d')) {
            $subject = '📢 إجازة رسمية: ' . $this->holiday->holiday_name . ' - يوم ' . $startDate->format('d/m/Y');
        } else {
            $subject = '📢 إجازة رسمية: ' . $this->holiday->holiday_name . ' - من ' . $startDate->format('d/m/Y') . ' إلى ' . $endDate->format('d/m/Y');
        }
        
        return new Envelope(
            subject: $subject,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.holiday-notification',
        );
    }
}
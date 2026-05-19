<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Holiday;
use App\Models\User;
use App\Mail\HolidayNotificationMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SendHolidayNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:send-holiday {id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send holiday notification email to all active employees';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $holidayId = $this->argument('id');
        $holiday = Holiday::find($holidayId);

        if (!$holiday) {
            $this->error("Holiday with ID {$holidayId} not found.");
            return Command::FAILURE;
        }

        $this->info("Sending notifications for holiday: {$holiday->holiday_name}");

        // Get all active users with a valid email
        $users = User::where('status', 'Active')
            ->whereNotNull('email')
            ->get();

        $sentCount = 0;
        $failedCount = 0;
        $skippedCount = 0;

        foreach ($users as $user) {
            $email = trim($user->email);
            
            if (!$this->isRealEmail($email)) {
                Log::info("Skipped fake/dummy email: " . $email);
                $skippedCount++;
                continue;
            }

            try {
                Mail::to($email)->send(new HolidayNotificationMail($holiday));
                $sentCount++;
                $this->info("Sent to: {$email}");
                Log::info("Holiday email sent successfully to: " . $email);
                // Slight delay to be gentle on the mail server (0.2 seconds)
                usleep(200000); 
            } catch (\Exception $e) {
                $failedCount++;
                $this->error("Failed to send to {$email}: " . $e->getMessage());
                Log::error("Failed to send holiday notification to {$email}: " . $e->getMessage());
            }
        }

        $this->info("Done! Sent: {$sentCount}, Failed: {$failedCount}, Skipped: {$skippedCount}");
        return Command::SUCCESS;
    }

    /**
     * Check if email is from a real domain (excluding dummy ones)
     */
    private function isRealEmail($email)
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return false;
        }

        $parts = explode('@', $email);
        if (count($parts) < 2) {
            return false;
        }

        $domain = strtolower(trim($parts[1]));
        $dummyDomains = [
            'test.com',
            'example.com',
            'example.org',
            'example.net',
            'localhost',
            'temp.com',
            'mailinator.com',
            'test.org',
            'test.net'
        ];

        return !in_array($domain, $dummyDomains);
    }
}

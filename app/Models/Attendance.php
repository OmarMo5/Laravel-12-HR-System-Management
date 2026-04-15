<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Attendance extends Model
{
    use HasFactory;

    protected $table = 'attendances';

    protected $fillable = [
        'user_id',
        'date',
        'check_in',
        'check_out',
        'status',
        'working_hours',
        'overtime_hours',
        'late_minutes',
        'early_departure_minutes',
        'notes'
    ];

    protected $casts = [
        'date' => 'date',
    ];

    // Accessors to ensure correct timezone
    public function getCheckInAttribute($value)
    {
        if ($value) {
            return Carbon::parse($value)->setTimezone(config('app.timezone'))->format('H:i:s');
        }
        return null;
    }

    public function getCheckOutAttribute($value)
    {
        if ($value) {
            return Carbon::parse($value)->setTimezone(config('app.timezone'))->format('H:i:s');
        }
        return null;
    }

    public function getFormattedCheckInAttribute()
    {
        if ($this->check_in) {
            return Carbon::parse($this->check_in)->format('h:i A');
        }
        return '—';
    }

    public function getFormattedCheckOutAttribute()
    {
        if ($this->check_out) {
            return Carbon::parse($this->check_out)->format('h:i A');
        }
        return '—';
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }


    /**
     * Check if a date is weekend (Friday or Saturday)
    */
    public static function isWeekend($date)
    {
        $dayOfWeek = Carbon::parse($date)->dayOfWeek;
        // 5 = Friday, 6 = Saturday (Carbon: 0=Sunday, 1=Monday, ..., 6=Saturday)
        return $dayOfWeek == 5 || $dayOfWeek == 6;
    }

    /**
     * Get working days between two dates (excluding Fridays and Saturdays)
     */
    public static function getWorkingDays($startDate, $endDate)
    {
        $workingDays = 0;
        $current = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);
        
        while ($current <= $end) {
            if (!self::isWeekend($current)) {
                $workingDays++;
            }
            $current->addDay();
        }
        
        return $workingDays;
    }

}
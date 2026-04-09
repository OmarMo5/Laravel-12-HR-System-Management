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
}
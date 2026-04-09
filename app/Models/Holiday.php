<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Holiday extends Model
{
    use HasFactory;

    protected $fillable = [
        'holiday_type',
        'holiday_name',
        'start_date',
        'end_date',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    // دالة مساعدة: ترجع عدد الأيام في الإجازة
    public function getDaysCountAttribute()
    {
        if ($this->start_date && $this->end_date) {
            return $this->start_date->diffInDays($this->end_date) + 1;
        }
        return 1;
    }

    // دالة مساعدة: ترجع نص التاريخ بشكل جميل
    public function getDateDisplayAttribute()
    {
        if (!$this->start_date || !$this->end_date) {
            return __('messages.na');
        }
        
        if ($this->start_date->equalTo($this->end_date)) {
            return $this->start_date->format('d M, Y');
        }
        return $this->start_date->format('d M') . ' - ' . $this->end_date->format('d M, Y');
    }
}
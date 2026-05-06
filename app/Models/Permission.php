<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    protected $fillable = [
        'user_id',
        'type',
        'reason',
        'personal_reason',
        'from_time',
        'to_time',
        'date',
        'status',
        'manager_status',
        'approved_by',
        'admin_notes',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

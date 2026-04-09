<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'department',
        'head_of',
        'phone_number',
        'email',
        'total_employee'
    ];

    /**
     * Relationship with Users - get count of employees in this department
     */
    public function users()
    {
        return $this->hasMany(User::class, 'department', 'department');
    }

    /**
     * Get employee count for this department
     */
    public function getEmployeeCountAttribute()
    {
        return $this->users()->count();
    }
}
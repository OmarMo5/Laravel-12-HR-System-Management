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
        return $this->hasManyThrough(
            User::class,
            JobInformation::class,
            'department_id', // Foreign key on the job_information table
            'id',            // Foreign key on the users table
            'id',            // Local key on the departments table
            'user_id'        // Local key on the job_information table
        );
    }

    /**
     * Get employee count for this department
     */
    public function getEmployeeCountAttribute()
    {
        return $this->users()->count();
    }
}
<?php
// app/Models/EmployeeProfile.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeProfile extends Model
{
    protected $table = 'employee_profiles';
    protected $fillable = [
        'user_id', 'national_id', 'address', 'gender', 'experience_years', 'location'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
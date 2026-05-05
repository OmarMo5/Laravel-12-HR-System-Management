<?php
// app/Models/JobInformation.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobInformation extends Model
{
    protected $table = 'job_information';
    protected $fillable = [
        'user_id', 'job_title_id', 'department_id', 'manager_id', 'work_type', 'work_location'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function jobTitle()
    {
        return $this->belongsTo(PositionType::class, 'job_title_id');
    }
}
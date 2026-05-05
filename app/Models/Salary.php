<?php
// app/Models/Salary.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Salary extends Model
{
    protected $table = 'salaries';
    protected $fillable = [
        'user_id', 'base_salary', 'advances', 'deductions', 
        'allowances', 'overtime', 'total_salary', 'payment_type'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
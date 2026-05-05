<?php
// app/Models/EmployeeDocument.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeDocument extends Model
{
    protected $table = 'employee_documents';
    protected $fillable = ['user_id', 'cv_file_path'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
<?php
// app/Models/ManagerEvaluation.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ManagerEvaluation extends Model
{
    protected $table = 'manager_evaluations';
    protected $fillable = ['user_id', 'manager_id', 'rating'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }
}
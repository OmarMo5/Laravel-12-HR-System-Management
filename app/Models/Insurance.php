<?php
// app/Models/Insurance.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Insurance extends Model
{
    protected $table = 'insurance';
    
    protected $fillable = [
        'user_id',
        'insurance_number',
        'insurance_start_date',
        'insurance_status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
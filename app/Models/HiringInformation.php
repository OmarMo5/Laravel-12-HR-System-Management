<?php
// app/Models/HiringInformation.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HiringInformation extends Model
{
    protected $table = 'hiring_information';
    protected $fillable = ['user_id', 'join_date', 'contract_type'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
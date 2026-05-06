<?php
// app/Models/User.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'user_id', 'name', 'email', 'company_email', 'phone_number', 'password',
        'role_name', 'role_id', 'status', 'avatar'
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // Auto-generate user_id like K-0001
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $lastUser = self::orderBy('id', 'desc')->first();
            $lastId = $lastUser ? (int) substr($lastUser->user_id, 2) : 0;
            $model->user_id = 'K-' . str_pad($lastId + 1, 4, '0', STR_PAD_LEFT);
        });
    }

    // ========== NEW RELATIONSHIPS ==========
    public function profile()
    {
        return $this->hasOne(EmployeeProfile::class);
    }

    public function jobInfo()
    {
        return $this->hasOne(JobInformation::class);
    }

    public function hiringInfo()
    {
        return $this->hasOne(HiringInformation::class);
    }

    public function salary()
    {
        return $this->hasOne(Salary::class);
    }

    // تأكد إن العلاقة كده بالظبط
public function insurance()
{
    return $this->hasOne(Insurance::class, 'user_id', 'id');
}

    public function documents()
    {
        return $this->hasOne(EmployeeDocument::class);
    }


    public function hasAnyRole($roles)
    {
        if (is_array($roles)) {
            return in_array(strtolower($this->role_name), array_map('strtolower', $roles));
        }
        return strtolower($this->role_name) == strtolower($roles);
    }
}
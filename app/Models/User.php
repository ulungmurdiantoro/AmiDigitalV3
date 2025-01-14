<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'users_code',
        'user_id',
        'user_nama',
        'user_jabatan',
        'user_penempatan',
        'user_fakultas',
        'user_akses',
        'user_pelatihan',
        'user_sertfikat',
        'user_sk',
        'username',
        'password',
        'user_level',
        'user_status',
    ];

    protected $hidden = [
        'users_code',
        'password', 
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function penjadwalan() { 
        return $this->hasMany(PenjadwalanAmi::class); 
    }

    public function auditorAmis() { 
        return $this->hasMany(AuditorAmi::class, 'users_code', 'users_code');
    }
}

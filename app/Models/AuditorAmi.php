<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuditorAmi extends Model
{
    use HasFactory;

    protected $fillable = [
        'auditor_kode',
        'users_kode',
        'tim_ami',
    ];

    public function user() { 
        return $this->belongsTo(User::class, 'users_kode', 'users_code'); 
    }

    public function auditorAmi() 
    { 
        return $this->hasMany(TransaksiAmi::class, 'auditor_kode', 'auditor_kode');
    }
}

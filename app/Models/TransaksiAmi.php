<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User;

class TransaksiAmi extends Model
{
    use HasFactory;

    protected $fillable = [
        'ami_kode',
        'auditor_kode',
        'informasi_tambahan',
        'prodi',
        'fakultas',
        'standar_akreditasi',
        'periode',
        'status',
        'alasan'
    ];

    public function auditorAmi() 
    { 
        return $this->hasMany(AuditorAmi::class, 'auditor_kode', 'auditor_kode');
    }

    public function penempatanUser() 
    { 
        return $this->belongsTo(User::class, 'prodi', 'user_penempatan');
    }


}

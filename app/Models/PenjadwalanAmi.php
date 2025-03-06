<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenjadwalanAmi extends Model
{
    use HasFactory;

    protected $fillable = [
        'jadwal_kode',
        'auditor_kode',
        'informasi_tambahan',
        'prodi',
        'fakultas',
        'standar_akreditasi',
        'periode',
        'opening_ami',
        'pengisian_dokumen',
        'deskevaluasion',
        'assessment',
        'tindakan_koreksi',
        'laporan_ami',
        'rtm'
    ];

    
    public function user() { 
        return $this->hasOneThrough(User::class, AuditorAmi::class, 'auditor_kode', 'users_code', 'auditor_kode', 'users_kode'); 
    }
    
    public function users() { 
        return $this->hasMany(User::class, 'users_code', 'users_code'); 
    }

    public function auditor_ami() { 
        return $this->hasMany(AuditorAmi::class, 'auditor_kode', 'auditor_kode'); 
    }
    
}

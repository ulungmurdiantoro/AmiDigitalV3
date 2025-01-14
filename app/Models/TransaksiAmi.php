<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        return $this->belongsTo(AuditorAmi::class, 'auditor_kode', 'auditor_kode');
    }
}

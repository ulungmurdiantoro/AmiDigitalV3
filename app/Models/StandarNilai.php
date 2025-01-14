<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StandarNilai extends Model
{
    use HasFactory;

    protected $fillable = [
        'ami_kode',
        'indikator_kode',
        'mandiri_nilai',
        'hasil_nilai',
        'bobot',
        'hasil_kriteria',
        'hasil_deskripsi',
        'jenis_temuan',
        'hasil_akibat',
        'hasil_masalah',
        'hasil_rekomendasi',
        'hasil_rencana_perbaikan',
        'hasil_jadwal_perbaikan',
        'hasil_perbaikan_penanggung',
        'hasil_rencana_pencegahan',
        'hasil_jadwal_pencegahan',
        'hasil_rencana_penanggung',
        'status_akhir',
        'prodi',
        'periode',
    ];
}

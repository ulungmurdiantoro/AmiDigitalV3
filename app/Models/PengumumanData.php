<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengumumanData extends Model
{
    use HasFactory;

    protected $fillable = [
        'pengumuman_kode',
        'pengumuman_judul',
        'pengumuman_informasi',
    ];
}

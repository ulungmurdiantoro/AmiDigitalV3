<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LkpsSnapshot extends Model
{
    protected $fillable = [
        'prodi',
        'prodi_kode',
        'periode',
        'data',
        'created_by',
    ];

    protected $casts = [
        'data' => 'array',
    ];
}

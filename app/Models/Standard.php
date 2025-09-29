<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Standard extends Model
{
    use HasFactory;

    protected $fillable = [
        'standar_akreditasi_id',
        'jenjang_id',
        'nama',
    ];

    public function elements() {
        return $this->hasMany(Element::class);
    }

    public function akreditasi() {
        return $this->belongsTo(StandarAkreditasi::class, 'standar_akreditasi_id');
    }

    public function jenjang() {
        return $this->belongsTo(Jenjang::class);
    }

}

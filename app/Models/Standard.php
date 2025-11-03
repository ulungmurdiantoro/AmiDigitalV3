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

    public function buktiStandar() {
        return $this->hasMany(BuktiStandar::class);
    }

    public function akreditasi() {
        return $this->belongsTo(StandarAkreditasi::class, 'standar_akreditasi_id');
    }

    public function jenjang() {
        return $this->belongsTo(Jenjang::class);
    }

    public function standarTargets()
    {
        return $this->hasMany(StandarTarget::class, 'indikator_id', 'indikator_id');
    }

    public function standarCapaians()
    {
        return $this->hasMany(StandarCapaian::class, 'indikator_id', 'indikator_id');
    }

    public function standarNilais()
    {
        return $this->hasOne(StandarNilai::class, 'indikator_id', 'indikator_id');
    }

    public function standarNilaisNotSesuai()
    {
        return $this->hasOne(StandarNilai::class, 'indikator_id', 'indikator_id')
                    ->where('jenis_temuan', '!=', 'Sesuai');
    }


}

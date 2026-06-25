<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StandarOutput extends Model
{
    use HasFactory;

    protected $fillable = [
        'ami_kode',
        'indikator_id',
        'tertimbang',
    ];

    public function indikator()
    {
        return $this->belongsTo(Indikator::class, 'indikator_id', 'id');
    }
}

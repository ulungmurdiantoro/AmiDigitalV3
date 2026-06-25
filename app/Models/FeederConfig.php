<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class FeederConfig extends Model
{
    protected $fillable = [
        'feeder_url',
        'feeder_username',
        'feeder_password',
        'feeder_kode_pt',
    ];

    public function setFeederPasswordAttribute(string $value): void
    {
        $this->attributes['feeder_password'] = Crypt::encryptString($value);
    }

    public function getFeederPasswordAttribute(string $value): string
    {
        return Crypt::decryptString($value);
    }

    public static function instance(): static
    {
        return static::firstOrNew(['id' => 1]);
    }
}

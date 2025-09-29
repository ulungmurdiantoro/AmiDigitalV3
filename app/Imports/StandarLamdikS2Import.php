<?php

namespace App\Imports;

use App\Models\StandarElemenLamdikS2;
use Maatwebsite\Excel\Concerns\ToModel;

class StandarLamdikS2Import implements ToModel
{
    private $truncateFlag = false;

    public function model(array $row)
    {
        if (!$this->truncateFlag) {
            StandarElemenLamdikS2::truncate();
            $this->truncateFlag = true;
        }

        return new StandarElemenLamdikS2([
            'indikator_id'  => $row['indikator_id'],
            'standar_nama'    => $row['standar_nama'],
            'elemen_nama'     => $row['elemen_nama'],
            'indikator_nama'  => $row['indikator_nama'],
            'indikator_info'  => $row['indikator_info'],
            'indikator_lkps'  => $row['indikator_lkps'],
            'indikator_bobot' => $row['indikator_bobot'],
        ]);
    }
}

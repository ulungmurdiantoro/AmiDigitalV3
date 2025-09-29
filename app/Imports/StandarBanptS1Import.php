<?php

namespace App\Imports;

use App\Models\StandarElemenBanptS1;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class StandarBanptS1Import implements ToModel, WithHeadingRow
{
    // A flag to ensure truncate() is called only once
    private $truncateFlag = false;

    public function model(array $row)
    {
        // Only truncate the table once, when processing the first row
        if (!$this->truncateFlag) {
            StandarElemenBanptS1::truncate();
            $this->truncateFlag = true;
        }

        return new StandarElemenBanptS1([
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

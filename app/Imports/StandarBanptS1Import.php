<?php

namespace App\Imports;

use App\Models\StandarElemenBanptS1;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class StandarBanptS1Import implements ToModel, WithHeadingRow, WithValidation
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        // Find an existing standar by `indikator_kode`
        return StandarElemenBanptS1::updateOrCreate(
            ['indikator_kode' => $row['indikator_kode']], // Search criteria
            [ // Values to update or insert
                'standar_nama'          => $row['standar_nama'],
                'elemen_nama'           => $row['elemen_nama'],
                'indikator_nama'        => $row['indikator_nama'],
                'indikator_info'        => $row['indikator_info'],
                'indikator_lkps'        => $row['indikator_lkps'],
                'indikator_bobot'       => $row['indikator_bobot'],
            ]
        );
    }
        
    /**
     * Validation rules.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'indikator_kode' => 'required',
        ];
    }
}

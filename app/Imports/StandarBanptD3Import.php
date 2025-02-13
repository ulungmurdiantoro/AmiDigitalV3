<?php

namespace App\Imports;

use App\Models\StandarElemenBanptS1;
use Maatwebsite\Excel\Concerns\ToModel;

class StandarBanptD3Import implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new StandarElemenBanptS1([
            //
        ]);
    }
}

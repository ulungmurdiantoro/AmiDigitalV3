<?php

namespace App\View\Components\Auditor\DataTable;

use Illuminate\View\Component;

class konfirmasiPengajuanLamdik extends Component
{
    public $id;
    public $standards;
    public $transkasis;
    public $prodis;
    public $periodes;
    public $standarTargetsRelations;
    public $standarCapaiansRelations;
    public $standarNilaisRelations;

    public function __construct(string $id, $standards, $transkasis, $prodis, $periodes, $standarTargetsRelations, $standarCapaiansRelations, $standarNilaisRelations)
    {
        // dd($id, $standards);
        $this->id = $id;
        $this->standards = $standards;
        $this->transkasis = $transkasis;
        $this->prodis = $prodis;
        $this->periodes = $periodes;
        $this->standarTargetsRelations = $standarTargetsRelations;
        $this->standarCapaiansRelations = $standarCapaiansRelations;
        $this->standarNilaisRelations = $standarNilaisRelations;
    }
    
    public function render()
    {
        return view('components.auditor.data-table.konfirmasi-pengajuan-lamdik');
    }
}

<?php

namespace App\View\Components;

use Illuminate\View\Component;

class DataTableRekapNilaiLamdik extends Component
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
        // dd($periodes, $prodis);
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
        return view('components.data-table-rekap-nilai-lamdik');
    }
}

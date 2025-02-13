<?php

namespace App\View\Components;

use Illuminate\View\Component;

class HasilForcasting extends Component
{
    public $tableTerakreditasis;
    public $tablePeringkatUngguls;
    public $tableBaikSekalis;
    public $totals;
    public $h2s;
    public $h3s;
    public $h4s;
    public $h5s;
    public $h6s;

    public function __construct($tableTerakreditasis, $tablePeringkatUngguls, $tableBaikSekalis, $totals, $h2s, $h3s, $h4s, $h5s, $h6s)
    {
        $this->tableTerakreditasis = $tableTerakreditasis;
        $this->tablePeringkatUngguls = $tablePeringkatUngguls;
        $this->tableBaikSekalis = $tableBaikSekalis;
        $this->totals = $totals;
        $this->h2s = $h2s;
        $this->h3s = $h3s;
        $this->h4s = $h4s;
        $this->h5s = $h5s;
        $this->h6s = $h6s;
    }

    public function render()
    {
        return view('components.hasil-forcasting');
    }
}


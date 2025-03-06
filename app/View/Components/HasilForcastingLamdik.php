<?php

namespace App\View\Components;

use Illuminate\View\Component;

class HasilForcastingLamdik extends Component
{
    public $tablePeringkatUngguls;
    public $totals;
    public $h2s;
    public $h3s;

    public function __construct($tablePeringkatUngguls,  $totals, $h2s, $h3s)
    {
        $this->tablePeringkatUngguls = $tablePeringkatUngguls;
        $this->totals = $totals;
        $this->h2s = $h2s;
        $this->h3s = $h3s;
    }

    public function render()
    {
        return view('components.hasil-forcasting-lamdik');
    }
}

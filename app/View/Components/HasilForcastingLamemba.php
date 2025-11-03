<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\Support\Collection;

class HasilForcastingLamemba extends Component
{
    public Collection $standards;
    // public $elements;
    public $transkasis;
    public $prodis;
    public $periodes;

    public function __construct(Collection $standards, $transkasis, $prodis, $periodes)
    {
        $this->standards = $standards;
        // $this->elements = $elements;
        $this->transkasis = $transkasis;
        $this->prodis = $prodis;
        $this->periodes = $periodes;
    }

    public function render()
    {
        return view('components.hasil-forcasting-lamemba');
    }
}

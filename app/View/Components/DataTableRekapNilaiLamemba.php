<?php

namespace App\View\Components;

use Illuminate\View\Component;

class DataTableRekapNilaiLamemba extends Component
{
    public $id;
    public $standards;
    public $elements;
    public $transkasis;
    public $prodis;
    public $periodes;
    public $showImportData;
    public $importTitle;

    public function __construct(string $id, $standards, $elements, $transkasis, $prodis, $periodes, $showImportData, $importTitle)
    {
        $this->id = $id;
        $this->standards = $standards;
        $this->elements = $elements;
        $this->transkasis = $transkasis;
        $this->prodis = $prodis;
        $this->periodes = $periodes;
        $this->showImportData = $showImportData;
        $this->importTitle = $importTitle;
    }
    
    public function render()
    {
        return view('components.data-table-rekap-nilai-lamemba');
    }
}

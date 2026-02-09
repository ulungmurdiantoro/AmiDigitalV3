<?php

namespace App\View\Components;

use Illuminate\View\Component;

class DataTableRekapNilai extends Component
{
    public $id;
    public $standards;
    public $prodis;
    public $periodes;
    public $transaksis;
    public $showImportData;
    public $importTitle;

    public function __construct($id, $standards, $prodis, $periodes, $transaksis, $showImportData, $importTitle)
    {
        $this->id = $id;
        $this->standards = $standards;
        $this->prodis = $prodis;
        $this->periodes = $periodes;
        $this->transaksis = $transaksis;
        $this->showImportData = $showImportData;
        $this->importTitle = $importTitle;
    }
    
    public function render()
    {
        return view('components.data-table-rekap-nilai');
    }
}

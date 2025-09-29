<?php

namespace App\View\Components\Admin;

use Illuminate\View\Component;

class KriteriaDokumenLamembaDataTable extends Component
{
    public $id;
    public $standards;
    public $showImportData;
    public $importTitle;
    // public $standarTargetsRelations;

    public function __construct($id, $standards, $showImportData, $importTitle)
    {
        $this->id = $id;
        $this->standards = $standards;
        $this->showImportData = $showImportData;
        $this->importTitle = $importTitle;
        // $this->standarTargetsRelations = $standarTargetsRelations;
    }
    
    public function render()
    {
        return view('components.admin.kriteria-dokumen-lamemba-data-table');
    }
}

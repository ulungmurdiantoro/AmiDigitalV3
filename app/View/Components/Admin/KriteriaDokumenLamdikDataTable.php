<?php

namespace App\View\Components\Admin;

use Illuminate\View\Component;

class KriteriaDokumenLamdikDataTable extends Component
{
    public $id;
    public $standards;
    public $showImportData;
    public $importTitle;
    public $standarTargetsRelations;

    public function __construct($id, $standards, $showImportData, $importTitle, $standarTargetsRelations)
    {
        $this->id = $id;
        $this->standards = $standards;
        $this->showImportData = $showImportData;
        $this->importTitle = $importTitle;
        $this->standarTargetsRelations = $standarTargetsRelations;
    }
    
    public function render()
    {
        return view('components.admin.kriteria-dokumen-lamdik-data-table');
    }
}

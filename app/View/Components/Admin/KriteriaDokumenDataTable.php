<?php

namespace App\View\Components\Admin;

use Illuminate\View\Component;

class KriteriaDokumenDataTable extends Component
{
    public $id;
    public $standards;
    public $showImportData;
    public $importTitle;

    public function __construct($id, $standards, $showImportData, $importTitle)
    {
        $this->id = $id;
        $this->standards = $standards;
        $this->showImportData = $showImportData;
        $this->importTitle = $importTitle;
    }
    
    public function render()
    {
        return view('components.admin.kriteria-dokumen-data-table');
    }
}

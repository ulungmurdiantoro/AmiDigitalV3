<?php

namespace App\View\Components\User\DataTable;

use Illuminate\View\Component;

class PemenuhanDokumen extends Component
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
        return view('components.user.data-table.pemenuhan-dokumen');
    }
}


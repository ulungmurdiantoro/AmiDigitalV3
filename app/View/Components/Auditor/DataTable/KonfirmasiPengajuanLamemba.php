<?php

namespace App\View\Components\Auditor\DataTable;

use Illuminate\View\Component;

class KonfirmasiPengajuanLamemba extends Component
{
    public $id;
    public $standards;
    public $showImportData;
    public $importTitle;

    public function __construct(string $id, $standards, $showImportData, $importTitle)
    {
        $this->id = $id;
        $this->standards = $standards;
        $this->showImportData = $showImportData;
        $this->importTitle = $importTitle;
    }
    
    public function render()
    {
        return view('components.auditor.data-table.konfirmasi-pengajuan-lamemba');
    }
}

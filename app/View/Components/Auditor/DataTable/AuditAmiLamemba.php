<?php

namespace App\View\Components\Auditor\DataTable;

use Illuminate\View\Component;

class AuditAmiLamemba extends Component
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
        return view('components.auditor.data-table.audit-ami-lamemba');
    }
}

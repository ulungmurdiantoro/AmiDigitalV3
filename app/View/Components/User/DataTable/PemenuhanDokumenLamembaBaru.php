<?php

namespace App\View\Components\User\DataTable;

use Illuminate\View\Component;

class PemenuhanDokumenLamembaBaru extends Component
{
    public $id;
    public $bukti;
    public $standards;
    public $editRouteName;
    public $importTitle;
    // public $standarTargetsRelations;

    public function __construct($id, $bukti, $editRouteName, $standards, $importTitle)
    {
        $this->id = $id;
        $this->bukti = $bukti;
        $this->standards = $standards;
        $this->editRouteName = $editRouteName;
        $this->importTitle = $importTitle;
        // $this->standarTargetsRelations = $standarTargetsRelations;
    }
    
    public function render()
    {
        return view('components.user.data-table.pemenuhan-dokumen-lamemba-baru');
    }
}

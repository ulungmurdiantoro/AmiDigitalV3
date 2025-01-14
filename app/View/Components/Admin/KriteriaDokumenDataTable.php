<?php

namespace App\View\Components\Admin;

use Illuminate\View\Component;

class KriteriaDokumenDataTable extends Component
{
    public $id;
    public $standards;

    public function __construct($id, $standards)
    {
        $this->id = $id;
        $this->standards = $standards;
    }
    
    public function render()
    {
        return view('components.admin.kriteria-dokumen-data-table');
    }
}

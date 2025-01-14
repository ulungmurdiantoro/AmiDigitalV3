<?php

namespace App\View\Components\Admin;

use Illuminate\View\Component;

class KriteriaDokumenModal extends Component
{
    public $id;
    public $standards;
    public $title; // Add the title property

    public function __construct($id, $standards, $title) // Add the title parameter
    {
        $this->id = $id;
        $this->standards = $standards;
        $this->title = $title; // Initialize the title property
    }
    
    public function render()
    {
        return view('components.admin.kriteria-dokumen-modal');
    }
}

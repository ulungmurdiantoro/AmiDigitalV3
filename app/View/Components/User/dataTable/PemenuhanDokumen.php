<?php

namespace App\View\Components\User\DataTable;

use Illuminate\View\Component;

class PemenuhanDokumen extends Component
{
    public $id;
    public $standards;
    public $standarTargetsRelations;
    public $standarCapaiansRelations;

    public function __construct(string $id, $standards, $standarTargetsRelations, $standarCapaiansRelations)
    {
        $this->id = $id;
        $this->standards = $standards;
        $this->standarTargetsRelations = $standarTargetsRelations;
        $this->standarCapaiansRelations = $standarCapaiansRelations;
    }

    public function render()
    {
        return view('components.user.data-table.pemenuhan-dokumen');
    }
}


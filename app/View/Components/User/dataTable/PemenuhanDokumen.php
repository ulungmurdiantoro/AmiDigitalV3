<?php

namespace App\View\Components\User\DataTable;

use Illuminate\View\Component;

class PemenuhanDokumen extends Component
{
    public $id;
    public $standards;

    public function __construct(string $id, $standards)
    {
        // dd($id, $standards);
        $this->id = $id;
        $this->standards = $standards;
    }

    public function render()
    {
        return view('components.user.data-table.pemenuhan-dokumen');
    }
}


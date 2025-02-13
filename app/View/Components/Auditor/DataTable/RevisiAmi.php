<?php

namespace App\View\Components\Auditor\DataTable;

use Illuminate\View\Component;

class RevisiAmi extends Component
{
    public $id;
    public $standards;
    public $transkasis;
    public $prodis;
    public $periodes;

    public function __construct(string $id, $standards, $transkasis, $prodis, $periodes)
    {
        // dd($periodes, $prodis);
        $this->id = $id;
        $this->standards = $standards;
        $this->transkasis = $transkasis;
        $this->prodis = $prodis;
        $this->periodes = $periodes;
    }
    
    public function render()
    {
        return view('components.auditor.data-table.revisi-ami');
    }
}

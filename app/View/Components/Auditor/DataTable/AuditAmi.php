<?php

namespace App\View\Components\Auditor\DataTable;

use Illuminate\View\Component;

class AuditAmi extends Component
{
    public $id;
    public $standards;
    public $transkasis;
    public $prodis;
    public $periodes;

    public function __construct(string $id, $standards, $transkasis)
    {
        // dd($id, $standards);
        $this->id = $id;
        $this->standards = $standards;
        $this->transkasis = $transkasis;
        // $this->prodis = $prodis;
        // $this->periodes = $periodes;
    }
    public function render()
    {
        return view('components.auditor.data-table.audit-ami');
    }
}

<?php

namespace App\View\Components\User\DataTable;

use Illuminate\View\Component;

class InputAmi extends Component
{
    public $id;
    public $standards;
    public $transkasis;
    public $prodis;
    public $periodes;

    public function __construct(string $id, $standards, $transkasis, $prodis, $periodes)
    {
        // dd($id, $standards);
        $this->id = $id;
        $this->standards = $standards;
        $this->transkasis = $transkasis;
        $this->prodis = $prodis;
        $this->periodes = $periodes;
    }
    
    public function render()
    {
        return view('components.user.data-table.input-ami');
    }
}

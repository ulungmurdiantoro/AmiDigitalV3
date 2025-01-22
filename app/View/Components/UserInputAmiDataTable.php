<?php

namespace App\View\Components;

use Illuminate\View\Component;

class UserInputAmiDataTable extends Component
{
    public $id;
    public $transaksi_ami;
    public $standards;
    public $periode;
    public $prodi;
    
    public function __construct(string $id, $standards,  $periode, $prodi, $transaksi_ami)
    {
        // dd($periode);
        $this->id = $id;
        // $this->transaksi_ami = $transaksi_ami;
        // $this->standards = $standards;
        // $this->periode = $periode;
        // $this->prodi = $prodi;
    }
    
    public function render()
    {
        return view('components.user-input-ami-data-table');
    }
}

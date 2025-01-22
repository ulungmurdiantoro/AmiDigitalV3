<?php

namespace App\View\Components\User;

use Illuminate\View\Component;

class InputAmiDataTable extends Component
{
    public $id;
    public $transaksi_ami;
    public $standards;
    public $periode;
    public $prodi;
    
    public function __construct(string $id , $standards,  $periode, $prodi, $transaksi_ami)
    {
        $this->id = $id ;
        $this->standards = $standards;
        $this->transaksi_ami = $transaksi_ami;
        $this->periode = $periode;
        $this->prodi = $prodi;
    }

    public function render()
    {
        return view('components.user.input-ami-data-table');
    }
}

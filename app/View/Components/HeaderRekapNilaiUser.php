<?php

namespace App\View\Components;

use Illuminate\View\Component;

class HeaderRekapNilaiUser extends Component
{
    public $prodi;
    public $periode;

    public function __construct($prodi, $periode)
    {
        $this->prodi = $prodi;
        $this->periode = $periode;
    }
    
    public function render()
    {
        return view('components.header-rekap-nilai-user');
    }
}

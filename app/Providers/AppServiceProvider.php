<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use App\View\Components\User\DataTable\PemenuhanDokumen;
use App\View\Components\User\InputAmiDataTable;
use App\View\Components\Admin\KriteriaDokumenDataTable;
use App\View\Components\Admin\KriteriaDokumenModal; 

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        $this->app->bind(PemenuhanDokumen::class, function ($app) {
            return new PemenuhanDokumen(request()->get('id'), request()->get('standards'));
        });

        $this->app->bind(InputAmiDataTable::class, function ($app) {
            return new InputAmiDataTable(request()->get('id'), request()->get('standards'), request()->get('periode'), request()->get('prodi'), request()->get('transaksi_ami'));
        });

        $this->app->bind(KriteriaDokumenDataTable::class, function ($app) {
            return new KriteriaDokumenDataTable(request()->get('id'), request()->get('standards'));
        });
        
        $this->app->bind(KriteriaDokumenModal::class, function ($app) {
            return new KriteriaDokumenModal(request()->get('id'), request()->get('standards'), request()->get('title'));
        });

        Blade::component('user.data-table.pemenuhan-dokumen', PemenuhanDokumen::class);
        Blade::component('user.input-ami-data-table', InputAmiDataTable::class);
        Blade::component('admin.kriteria-dokumen-data-table', KriteriaDokumenDataTable::class);
        Blade::component('admin.kriteria-dokumen-modal', KriteriaDokumenModal::class);
    }
}

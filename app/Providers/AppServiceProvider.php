<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use App\View\Components\User\DataTable\PemenuhanDokumen;
use App\View\Components\User\DataTable\InputAmi;
use App\View\Components\Auditor\DataTable\KonfirmasiPengajuan;
use App\View\Components\Auditor\DataTable\AuditAmi;
use App\View\Components\User\InputAmiDataTable;
use App\View\Components\UserInputAmiDataTable;
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
        $this->app->bind(AuditAmi::class, function ($app) {
            return new AuditAmi(request()->get('id'), request()->get('standards'), request()->get('transaksis'), request()->get('prodis'), request()->get('periodes'));
        });

        $this->app->bind(KonfirmasiPengajuan::class, function ($app) {
            return new KonfirmasiPengajuan(request()->get('id'), request()->get('standards'), request()->get('transaksis'), request()->get('prodis'), request()->get('periodes'));
        });

        $this->app->bind(PemenuhanDokumen::class, function ($app) {
            return new PemenuhanDokumen(request()->get('id'), request()->get('standards'));
        });

        $this->app->bind(InputAmi::class, function ($app) {
            return new InputAmi(request()->get('id'), request()->get('standards'), request()->get('transaksis'), request()->get('prodis'), request()->get('periodes'));
        });

        $this->app->bind(InputAmiDataTable::class, function ($app) {
            return new InputAmiDataTable(request()->get('id'), request()->get('standards'), request()->get('periode'), request()->get('prodi'), request()->get('transaksi_ami'));
        });

        $this->app->bind(UserInputAmiDataTable::class, function ($app) {
            return new UserInputAmiDataTable(request()->get('id'), request()->get('standards'), request()->get('periode'), request()->get('prodi'), request()->get('transaksi_ami'));
        });

        $this->app->bind(KriteriaDokumenDataTable::class, function ($app) {
            return new KriteriaDokumenDataTable(request()->get('id'), request()->get('standards'));
        });
        
        $this->app->bind(KriteriaDokumenModal::class, function ($app) {
            return new KriteriaDokumenModal(request()->get('id'), request()->get('standards'), request()->get('title'));
        });

        Blade::component('auditor.data-table.audit-ami', AuditAmi::class);
        Blade::component('auditor.data-table.konfirmasi-pengajuan', KonfirmasiPengajuan::class);
        Blade::component('user.data-table.input-ami', InputAmi::class);
        Blade::component('user.data-table.pemenuhan-dokumen', PemenuhanDokumen::class);
        Blade::component('user.input-ami-data-table', InputAmiDataTable::class);
        Blade::component('userinput-ami-data-table', UserInputAmiDataTable::class);
        Blade::component('admin.kriteria-dokumen-data-table', KriteriaDokumenDataTable::class);
        Blade::component('admin.kriteria-dokumen-modal', KriteriaDokumenModal::class);
    }
}

<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use App\View\Components\User\DataTable\PemenuhanDokumen;
use App\View\Components\User\DataTable\InputAmi;
use App\View\Components\User\DataTable\RevisiProdi;
use App\View\Components\User\InputAmiDataTable;
use App\View\Components\UserInputAmiDataTable;
use App\View\Components\Admin\KriteriaDokumenDataTable;
use App\View\Components\Admin\KriteriaDokumenLamdikDataTable;
use App\View\Components\Admin\KriteriaDokumenModal;
use App\View\Components\Auditor\DataTable\KonfirmasiPengajuan;
use App\View\Components\Auditor\DataTable\AuditAmi;
use App\View\Components\Auditor\DataTable\RevisiAmi;
use App\View\Components\HeaderRekapNilai;
use App\View\Components\DataTableRekapNilai;
use App\View\Components\HasilForcasting;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        
        $this->app->bind(KonfirmasiPengajuan::class, function ($app) {
            return new KonfirmasiPengajuan(request()->get('id'), request()->get('standards'), request()->get('transaksis'), request()->get('prodis'), request()->get('periodes'));
        });
        
        $this->app->bind(AuditAmi::class, function ($app) {
            return new AuditAmi(request()->get('id'), request()->get('standards'), request()->get('transaksis'), request()->get('prodis'), request()->get('periodes'));
        });

        $this->app->bind(RevisiAmi::class, function ($app) {
            return new RevisiAmi(request()->get('id'), request()->get('standards'), request()->get('transaksis'), request()->get('prodis'), request()->get('periodes'));
        });

        $this->app->bind(RevisiProdi::class, function ($app) {
            return new RevisiProdi(request()->get('id'), request()->get('standards'), request()->get('transaksis'), request()->get('prodis'), request()->get('periodes'));
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
            return new KriteriaDokumenDataTable(request()->get('id'), request()->get('standards'), request()->get('showImportData'), request()->get('importTitle'), request()->get('standarTargetsRelations'));
        });

        $this->app->bind(KriteriaDokumenLamdikDataTable::class, function ($app) {
            return new KriteriaDokumenLamdikDataTable(request()->get('id'), request()->get('standards'), request()->get('showImportData'), request()->get('importTitle'), request()->get('standarTargetsRelations'));
        });
        
        $this->app->bind(KriteriaDokumenModal::class, function ($app) {
            return new KriteriaDokumenModal(request()->get('id'), request()->get('standards'), request()->get('title'));
        });

        $this->app->bind(HeaderRekapNilai::class, function ($app) {
            return new HeaderRekapNilai(request()->get('periode'), request()->get('prodi'));
        });

        $this->app->bind(DataTableRekapNilai::class, function ($app) {
            return new DataTableRekapNilai(request()->get('id'), request()->get('standards'), request()->get('periode'), request()->get('prodi'), request()->get('transaksi_ami'));
        });

        $this->app->bind(HasilForcasting::class, function ($app) {
            return new HasilForcasting(
                request()->get('tableTerakreditasis'), 
                request()->get('tablePeringkatUngguls'), 
                request()->get('tableBaikSekalis'), 
                request()->get('totals'),
                request()->get('h2s'),
                request()->get('h3s'),
                request()->get('h4s'),
                request()->get('h5s'),
                request()->get('h6s'));
        });

        Blade::component('admin.kriteria-dokumen-data-table', KriteriaDokumenDataTable::class);
        Blade::component('admin.kriteria-dokumen-lamdik-data-table', KriteriaDokumenLamdikDataTable::class);
        Blade::component('admin.kriteria-dokumen-modal', KriteriaDokumenModal::class);
        Blade::component('user.data-table.input-ami', InputAmi::class);
        Blade::component('user.data-table.pemenuhan-dokumen', PemenuhanDokumen::class);
        Blade::component('user.data-table.revisi-prodi', RevisiProdi::class);
        Blade::component('user.input-ami-data-table', InputAmiDataTable::class);
        Blade::component('userinput-ami-data-table', UserInputAmiDataTable::class);
        Blade::component('auditor.data-table.konfirmasi-pengajuan', KonfirmasiPengajuan::class);
        Blade::component('auditor.data-table.audit-ami', AuditAmi::class);
        Blade::component('auditor.data-table.revisi-ami', RevisiAmi::class);
        Blade::component('header-rekap-nilai', HeaderRekapNilai::class);
        Blade::component('data-table-rekap-nilai', DataTableRekapNilai::class);
        Blade::component('hasil-forcasting', HasilForcasting::class);

    }
}

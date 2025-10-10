<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        $this->registerComponentBindings();
        $this->registerBladeComponents();
    }

    protected function registerComponentBindings()
    {
        $bindings = [
            // Auditor Components
            \App\View\Components\Auditor\DataTable\KonfirmasiPengajuan::class => ['id', 'standards', 'transaksis', 'prodis', 'periodes', 'standarTargetsRelations', 'standarCapaiansRelations', 'standarNilaisRelations'],
            \App\View\Components\Auditor\DataTable\KonfirmasiPengajuanLamdik::class => ['id', 'standards', 'transaksis', 'prodis', 'periodes', 'standarTargetsRelations', 'standarCapaiansRelations', 'standarNilaisRelations'],
            \App\View\Components\Auditor\DataTable\AuditAmi::class => ['id', 'standards', 'transaksis', 'prodis', 'standarTargetsRelations', 'standarCapaiansRelations', 'standarNilaisRelations', 'periodes'],
            \App\View\Components\Auditor\DataTable\AuditAmiLamdik::class => ['id', 'standards', 'transaksis', 'prodis', 'standarTargetsRelations', 'standarCapaiansRelations', 'standarNilaisRelations', 'periodes'],
            \App\View\Components\Auditor\DataTable\RevisiAmi::class => ['id', 'standards', 'transaksis', 'prodis', 'periodes', 'standarTargetsRelations', 'standarCapaiansRelations', 'standarNilaisRelations'],
            \App\View\Components\Auditor\DataTable\RevisiAmiLamdik::class => ['id', 'standards', 'transaksis', 'prodis', 'periodes', 'standarTargetsRelations', 'standarCapaiansRelations', 'standarNilaisRelations'],
            
            // User Components
            \App\View\Components\User\DataTable\RevisiProdi::class => ['id', 'standards', 'transaksis', 'prodis', 'periodes', 'standarTargetsRelations', 'standarCapaiansRelations', 'standarNilaisRelations'],
            \App\View\Components\User\DataTable\RevisiProdiLamdik::class => ['id', 'standards', 'transaksis', 'prodis', 'periodes', 'standarTargetsRelations', 'standarCapaiansRelations', 'standarNilaisRelations'],
            \App\View\Components\User\DataTable\PemenuhanDokumen::class => ['id', 'standards', 'showImportData', 'importTitle'],
            \App\View\Components\User\DataTable\PemenuhanDokumenLamemba::class => ['id', 'standards', 'showImportData', 'importTitle'],
            \App\View\Components\User\DataTable\PemenuhanDokumenLamemba2::class => ['id', 'standards', 'bukti', 'editRouteName', 'importTitle'],
            \App\View\Components\User\DataTable\PemenuhanDokumenLamembaBaru::class => ['id', 'standards', 'bukti', 'editRouteName', 'importTitle'],
            \App\View\Components\User\DataTable\PemenuhanDokumenLamembaNew::class => ['id', 'standards', 'bukti', 'editRouteName', 'importTitle'],
            \App\View\Components\User\DataTable\PemenuhanDokumenLamdik::class => ['id', 'standards', 'standarTargetsRelations', 'standarCapaiansRelations'],
            \App\View\Components\User\DataTable\InputAmi::class => ['id', 'standards', 'transaksis', 'prodis', 'standarTargetsRelations', 'standarCapaiansRelations', 'standarNilaisRelations', 'periodes'],
            \App\View\Components\User\DataTable\InputAmiLamdik::class => ['id', 'standards', 'transaksis', 'prodis', 'standarTargetsRelations', 'standarCapaiansRelations', 'standarNilaisRelations', 'periodes'],
            \App\View\Components\User\DataTable\InputAmiLamemba::class => ['id', 'standards', 'bukti', 'editRouteName', 'importTitle'],
            \App\View\Components\User\InputAmiDataTable::class => ['id', 'standards', 'periode', 'prodi', 'transaksi_ami'],
            \App\View\Components\UserInputAmiDataTable::class => ['id', 'standards', 'periode', 'prodi', 'transaksi_ami'],

            // Admin Components
            \App\View\Components\Admin\KriteriaDokumenDataTable::class => ['id', 'standards', 'showImportData', 'importTitle'],
            \App\View\Components\Admin\KriteriaDokumenLamdikDataTable::class => ['id', 'standards', 'showImportData', 'importTitle', 'standarTargetsRelations'],
            \App\View\Components\Admin\KriteriaDokumenLamembaDataTable::class => ['id', 'standards', 'showImportData', 'importTitle'],
            \App\View\Components\Admin\KriteriaDokumenModal::class => ['id', 'standards', 'title'],

            // Other Components
            \App\View\Components\HeaderRekapNilai::class => ['periode', 'prodi'],
            \App\View\Components\HeaderRekapNilaiUser::class => ['periode', 'prodi'],
            \App\View\Components\HeaderRekapNilaiAuditor::class => ['periode', 'prodi'],
            \App\View\Components\DataTableRekapNilai::class => ['id', 'standards', 'periode', 'prodi', 'transaksi_ami', 'standarTargetsRelations', 'standarCapaiansRelations', 'standarNilaisRelations'],
            \App\View\Components\DataTableRekapNilaiLamdik::class => ['id', 'standards', 'periode', 'prodi', 'transaksi_ami', 'standarTargetsRelations', 'standarCapaiansRelations', 'standarNilaisRelations'],
            \App\View\Components\HasilForcasting::class => ['tableTerakreditasis', 'tablePeringkatUngguls', 'tableBaikSekalis', 'totals', 'h2s', 'h3s', 'h4s', 'h5s', 'h6s'],
            \App\View\Components\HasilForcastingLamdik::class => ['tablePeringkatUngguls', 'totals', 'h2s', 'h3s'],
        ];

        foreach ($bindings as $class => $params) {
            $this->app->bind($class, function ($app) use ($class, $params) {
                $args = array_map(fn($key) => request()->get($key), $params);
                return new $class(...$args);
            });
        }
    }

    protected function registerBladeComponents()
    {
        Blade::component('admin.kriteria-dokumen-data-table', \App\View\Components\Admin\KriteriaDokumenDataTable::class);
        Blade::component('admin.kriteria-dokumen-lamdik-data-table', \App\View\Components\Admin\KriteriaDokumenLamdikDataTable::class);
        Blade::component('admin.kriteria-dokumen-modal', \App\View\Components\Admin\KriteriaDokumenModal::class);
        Blade::component('user.data-table.input-ami', \App\View\Components\User\DataTable\InputAmi::class);
        Blade::component('user.data-table.input-ami-lamdik', \App\View\Components\User\DataTable\InputAmiLamdik::class);
        Blade::component('user.data-table.input-ami-lamemba', \App\View\Components\User\DataTable\InputAmiLamemba::class);
        Blade::component('user.data-table.pemenuhan-dokumen', \App\View\Components\User\DataTable\PemenuhanDokumen::class);
        Blade::component('user.data-table.pemenuhan-dokumen-lamdik', \App\View\Components\User\DataTable\PemenuhanDokumenLamdik::class);
        Blade::component('user.data-table.pemenuhan-dokumen-lamemba-baru', \App\View\Components\User\DataTable\PemenuhanDokumenLamembaBaru::class);
        Blade::component('user.data-table.pemenuhan-dokumen-lamemba-new', \App\View\Components\User\DataTable\PemenuhanDokumenLamembaNew::class);
        Blade::component('user.data-table.revisi-prodi', \App\View\Components\User\DataTable\RevisiProdi::class);
        Blade::component('user.data-table.revisi-prodi-lamdik', \App\View\Components\User\DataTable\RevisiProdiLamdik::class);
        Blade::component('user.input-ami-data-table', \App\View\Components\User\InputAmiDataTable::class);
        Blade::component('userinput-ami-data-table', \App\View\Components\UserInputAmiDataTable::class);
        Blade::component('auditor.data-table.konfirmasi-pengajuan', \App\View\Components\Auditor\DataTable\KonfirmasiPengajuan::class);
        Blade::component('auditor.data-table.konfirmasi-pengajuan-lamdik', \App\View\Components\Auditor\DataTable\KonfirmasiPengajuanLamdik::class);
        Blade::component('auditor.data-table.audit-ami', \App\View\Components\Auditor\DataTable\AuditAmi::class);
        Blade::component('auditor.data-table.audit-ami-lamdik', \App\View\Components\Auditor\DataTable\AuditAmiLamdik::class);
        Blade::component('auditor.data-table.revisi-ami', \App\View\Components\Auditor\DataTable\RevisiAmi::class);
        Blade::component('auditor.data-table.revisi-ami-lamdik', \App\View\Components\Auditor\DataTable\RevisiAmiLamdik::class);
        Blade::component('header-rekap-nilai', \App\View\Components\HeaderRekapNilai::class);
        Blade::component('header-rekap-nilai-user', \App\View\Components\HeaderRekapNilaiUser::class);
        Blade::component('header-rekap-nilai-auditor', \App\View\Components\HeaderRekapNilaiAuditor::class);
        Blade::component('data-table-rekap-nilai', \App\View\Components\DataTableRekapNilai::class);
        Blade::component('data-table-rekap-nilai-lamdik', \App\View\Components\DataTableRekapNilaiLamdik::class);
        Blade::component('hasil-forcasting', \App\View\Components\HasilForcasting::class);
        Blade::component('hasil-forcasting-lamdik', \App\View\Components\HasilForcastingLamdik::class);
    }
}

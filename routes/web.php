<?php
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

use App\Http\Controllers\Admin\DashboardAdminController;
use App\Http\Controllers\Admin\AktivitasProdiController;
use App\Http\Controllers\Admin\BantuanController;
use App\Http\Controllers\Admin\DokumenSpmiAmiController;
use App\Http\Controllers\Admin\ForcastingController;
use App\Http\Controllers\Admin\KriteriaDokumenController;
use App\Http\Controllers\Admin\NewKriteriaDokumenController;
use App\Http\Controllers\Admin\NilaiEvaluasiDiriController;
use App\Http\Controllers\Admin\PenggunaAuditorController;
use App\Http\Controllers\Admin\PenggunaProdiController;
use App\Http\Controllers\Admin\PengumumanController;
use App\Http\Controllers\Admin\PenjadwalanAmiController;
use App\Http\Controllers\Admin\ProgramStudiController;
use App\Http\Controllers\Admin\StatistikElemenController;
use App\Http\Controllers\Admin\StatistikTotalController;
use App\Http\Controllers\Auditor\AuditorForcastingController;
use App\Http\Controllers\Auditor\AuditorNilaiEvaluasiDiriController;
use App\Http\Controllers\Auditor\AuditorStatistikElemenController;
use App\Http\Controllers\Auditor\AuditorStatistikTotalController;
use App\Http\Controllers\User\DashboardUserController;
use App\Http\Controllers\User\DokumenSpmiAmiUserController;
use App\Http\Controllers\User\PemenuhanDokumenController;
use App\Http\Controllers\User\NewPemenuhanDokumenController;
use App\Http\Controllers\User\DokumenAktifUserController;
use App\Http\Controllers\User\DokumenKadaluarsaUserController;
use App\Http\Controllers\User\PengajuanAmiUserController;
use App\Http\Controllers\User\InputAmiUserController;
use App\Http\Controllers\User\KoreksiAmiUserController;
use App\Http\Controllers\Auditor\DashboardAuditorController;
use App\Http\Controllers\Auditor\DokumenSpmiAmiauditorController;
use App\Http\Controllers\Auditor\KonfirmasiPengajuanController;
use App\Http\Controllers\Auditor\EvaluasiAmiAuditorController;
use App\Http\Controllers\Auditor\InputAmiAuditorController;
use App\Http\Controllers\Auditor\EditAmiAuditorController;
use App\Http\Controllers\Auditor\KoreksiAmiAuditorController;
use App\Http\Controllers\User\UserForcastingController;
use App\Http\Controllers\User\UserNilaiEvaluasiDiriController;
use App\Http\Controllers\User\UserStatistikElemenController;
use App\Http\Controllers\User\UserStatistikTotalController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [UserController::class, 'showLoginForm'])->name('login');
Route::post('/', [UserController::class, 'login'])->name('login.post');
Route::post('/logout', [UserController::class, 'logout'])->name('logout');

Route::group(['prefix' => 'admin'], function(){
    Route::group(['middleware' => ['auth', 'admin']], function () {

        Route::resource('dashboard', DashboardAdminController::class)->names([
            'index' => 'admin.dashboard.index',
        ]);

        Route::get('/aktivitas-prodi/{periode}/{prodi}/show-pengajuan', [AktivitasProdiController::class, 'showPengajuan'])->where('periode', '.*')->where('prodi', '.*')->name('auditor.aktivitas-prodi.show-pengajuan');

        Route::resource('aktivitas-prodi', AktivitasProdiController::class)->names([
            'index' => 'admin.aktivitas-prodi.index',
        ]);
        
        Route::resource('bantuan', BantuanController::class)->names([
            'index' => 'admin.bantuan.index',
            'create' => 'admin.bantuan.create',
            'store' => 'admin.bantuan.store',
            'show' => 'admin.bantuan.show',
            'edit' => 'admin.bantuan.edit',
            'update' => 'admin.bantuan.update',
            'destroy' => 'admin.bantuan.destroy',
        ]);
        
        Route::resource('dokumen-spmi-ami', DokumenSpmiAmiController::class)->names([
            'index' => 'admin.dokumen-spmi-ami.index',
            'create' => 'admin.dokumen-spmi-ami.create',
            'store' => 'admin.dokumen-spmi-ami.store',
            'show' => 'admin.dokumen-spmi-ami.show',
            'edit' => 'admin.dokumen-spmi-ami.edit',
            'update' => 'admin.dokumen-spmi-ami.update',
            'destroy' => 'admin.dokumen-spmi-ami.destroy',
        ]);

        // Route::get('/kriteria-dokumen/import', [KriteriaDokumenController::class, 'import'])->name('admin.kriteria-dokumen.import');
        // Route::get('/kriteria-dokumen/{importTitle}/{indikator_id}/kelola-target', [KriteriaDokumenController::class, 'kelolaTarget'])->name('admin.kriteria-dokumen.kelola-target');
        // Route::get('/kriteria-dokumen/{importTitle}/{indikator_id}/kelola-target/create', [KriteriaDokumenController::class, 'kelolaTargetCreate'])->name('admin.kriteria-dokumen.kelola-target.create');

        // Route::post('/kriteria-dokumen/kelola-target/store', [KriteriaDokumenController::class, 'kelolaTargetStore'])->name('admin.kriteria-dokumen.kelola-target.store');
        // Route::get('/kriteria-dokumen/kelola-target/{indikator_id}/edit', [KriteriaDokumenController::class, 'kelolaTargetEdit'])->name('admin.kriteria-dokumen.kelola-target.edit');
        // Route::put('/kriteria-dokumen/kelola-target/{id}/update', [KriteriaDokumenController::class, 'kelolaTargetUpdate'])->name('admin.kriteria-dokumen.kelola-target.update');
        // Route::delete('/kriteria-dokumen/kelola-target/{id}', [KriteriaDokumenController::class, 'kelolaTargetDestroy'])->name('admin.kriteria-dokumen.kelola-target.destroy');
        // Route::post('/kriteria-dokumen/kelola-target/TipeDokumenStore', [KriteriaDokumenController::class, 'tipeDokumenStore'])->name('admin.kriteria-dokumen.kelola-target.tipedokumenstore');
        // Route::delete('/kriteria-dokumen/kelola-target/TipeDokumenDestroy', [KriteriaDokumenController::class, 'tipeDokumenDestroy'])->name('admin.kriteria-dokumen.kelola-target.tipedokumendestroy');

        // Route::post('/kriteria-dokumen/storeImport', [KriteriaDokumenController::class, 'storeImport'])->name('admin.kriteria-dokumen.storeImport');
        // Route::get('/kriteria-dokumen/{degree}/import', [KriteriaDokumenController::class, 'import'])->name('kriteria-dokumen.import');
        // Route::get('/kriteria-dokumen/{degree}/create', [KriteriaDokumenController::class, 'create'])->name('kriteria-dokumen.create');

        // Route::resource('kriteria-dokumen', KriteriaDokumenController::class)->names([
        //     'index' => 'admin.kriteria-dokumen.index',
        //     'store' => 'admin.kriteria-dokumen.store',
        //     'show' => 'admin.kriteria-dokumen.show',
        //     'edit' => 'admin.kriteria-dokumen.edit',
        //     'update' => 'admin.kriteria-dokumen.update',
        //     'destroy' => 'admin.kriteria-dokumen.destroy',
        // ]);

        Route::get('/kriteria-dokumen/import', [NewKriteriaDokumenController::class, 'import'])->name('admin.kriteria-dokumen.import');
        Route::get('/kriteria-dokumen/{importTitle}/{indikator_id}/kelola-target', [NewKriteriaDokumenController::class, 'kelolaTarget'])->name('admin.kriteria-dokumen.kelola-target');
        Route::get('/kriteria-dokumen/{importTitle}/{indikator_id}/kelola-target/create', [NewKriteriaDokumenController::class, 'kelolaTargetCreate'])->name('admin.kriteria-dokumen.kelola-target.create');
        Route::post('/kriteria-dokumen/kelola-target/store', [NewKriteriaDokumenController::class, 'kelolaTargetStore'])->name('admin.kriteria-dokumen.kelola-target.store');
        Route::get('/kriteria-dokumen/kelola-target/{indikator_id}/edit', [NewKriteriaDokumenController::class, 'kelolaTargetEdit'])->name('admin.kriteria-dokumen.kelola-target.edit');
        Route::put('/kriteria-dokumen/kelola-target/{id}/update', [NewKriteriaDokumenController::class, 'kelolaTargetUpdate'])->name('admin.kriteria-dokumen.kelola-target.update');
        Route::delete('/kriteria-dokumen/kelola-target/{id}', [NewKriteriaDokumenController::class, 'kelolaTargetDestroy'])->name('admin.kriteria-dokumen.kelola-target.destroy');
        Route::post('/kriteria-dokumen/kelola-target/TipeDokumenStore', [NewKriteriaDokumenController::class, 'tipeDokumenStore'])->name('admin.kriteria-dokumen.kelola-target.tipedokumenstore');
        Route::delete('/kriteria-dokumen/kelola-target/TipeDokumenDestroy', [NewKriteriaDokumenController::class, 'tipeDokumenDestroy'])->name('admin.kriteria-dokumen.kelola-target.tipedokumendestroy');

        Route::get('/kriteria-dokumen/{importTitle}/{id}/kelola-bukti/create', [NewKriteriaDokumenController::class, 'kelolabuktiCreate'])->name('admin.kriteria-dokumen.kelola-bukti.create');
        Route::post('/kriteria-dokumen/kelola-bukti/store', [NewKriteriaDokumenController::class, 'kelolaBuktiStore'])->name('admin.kriteria-dokumen.kelola-bukti.store');
        Route::put('/kriteria-dokumen/kelola-bukti/{id}/update', [NewKriteriaDokumenController::class, 'kelolaBuktiUpdate'])->name('admin.kriteria-dokumen.kelola-bukti.update');
        Route::delete('/kriteria-dokumen/kelola-bukti/destroy/{id}', [NewKriteriaDokumenController::class, 'kelolaBuktiDestroy'])->name('admin.kriteria-dokumen.kelola-bukti.destroy');

        Route::get('/kriteria-dokumen/{importTitle}/{id}/kelola-indikator/create', [NewKriteriaDokumenController::class, 'kelolaIndikatorCreate'])->name('admin.kriteria-dokumen.kelola-indikator.create');
        Route::post('/kriteria-dokumen/kelola-indikator/store', [NewKriteriaDokumenController::class, 'kelolaIndikatorStore'])->name('admin.kriteria-dokumen.kelola-indikator.store');
        Route::put('/kriteria-dokumen/kelola-indikator/{id}/update', [NewKriteriaDokumenController::class, 'kelolaIndikatorUpdate'])->name('admin.kriteria-dokumen.kelola-indikator.update');
        Route::delete('/kriteria-dokumen/kelola-indikator/destroy/{id}', [NewKriteriaDokumenController::class, 'kelolaIndikatorDestroy'])->name('admin.kriteria-dokumen.kelola-indikator.destroy');

        Route::post('/kriteria-dokumen/storeImport', [NewKriteriaDokumenController::class, 'storeImport'])->name('admin.kriteria-dokumen.storeImport');
        Route::get('/kriteria-dokumen/{degree}/import', [NewKriteriaDokumenController::class, 'import'])->name('kriteria-dokumen.import');
        Route::get('/kriteria-dokumen/{degree}/create', [NewKriteriaDokumenController::class, 'create'])->name('kriteria-dokumen.create');

        Route::resource('kriteria-dokumen', NewKriteriaDokumenController::class)->names([
            'index' => 'admin.kriteria-dokumen.index',
            'store' => 'admin.kriteria-dokumen.store',
            'show' => 'admin.kriteria-dokumen.show',
            'edit' => 'admin.kriteria-dokumen.edit',
            'update' => 'admin.kriteria-dokumen.update',
            'destroy' => 'admin.kriteria-dokumen.destroy',
        ]);

        Route::get('/nilai-evaluasi-diri/{periode}/{prodi}/rekap-nilai', [NilaiEvaluasiDiriController::class, 'rekapNilai'])->where('periode', '.*')->where('prodi', '.*')->name('admin.nilai-evaluasi-diri.rekap-nilai');
        Route::get('/nilai-evaluasi-diri/{periode}/{prodi}/rekap-nilai/report-lha', [NilaiEvaluasiDiriController::class, 'reportLha'])->where('periode', '.*')->where('prodi', '.*')->name('admin.nilai-evaluasi-diri.rekap-nilai.report-lha');
        Route::get('/nilai-evaluasi-diri/{periode}/{prodi}/rekap-nilai/report-rtm', [NilaiEvaluasiDiriController::class, 'reportRtm'])->where('periode', '.*')->where('prodi', '.*')->name('admin.nilai-evaluasi-diri.rekap-nilai.report-rtm');

        Route::resource('nilai-evaluasi-diri', NilaiEvaluasiDiriController::class)->names([
            'index' => 'admin.nilai-evaluasi-diri.index',
        ]);
        
        Route::resource('pengguna-auditor', PenggunaAuditorController::class)->names([
            'index' => 'admin.pengguna-auditor.index',
            'create' => 'admin.pengguna-auditor.create',
            'store' => 'admin.pengguna-auditor.store',
            'show' => 'admin.pengguna-auditor.show',
            'edit' => 'admin.pengguna-auditor.edit',
            'update' => 'admin.pengguna-auditor.update',
            'destroy' => 'admin.pengguna-auditor.destroy',
        ]);
        
        Route::resource('pengguna-prodi', PenggunaProdiController::class)->names([
            'index' => 'admin.pengguna-prodi.index',
            'create' => 'admin.pengguna-prodi.create',
            'store' => 'admin.pengguna-prodi.store',
            'show' => 'admin.pengguna-prodi.show',
            'edit' => 'admin.pengguna-prodi.edit',
            'update' => 'admin.pengguna-prodi.update',
            'destroy' => 'admin.pengguna-prodi.destroy',
        ]);
        
        Route::resource('pengumuman', PengumumanController::class)->names([
            'index' => 'admin.pengumuman.index',
            'create' => 'admin.pengumuman.create',
            'store' => 'admin.pengumuman.store',
            'show' => 'admin.pengumuman.show',
            'edit' => 'admin.pengumuman.edit',
            'update' => 'admin.pengumuman.update',
            'destroy' => 'admin.pengumuman.destroy',
        ]);
        
        Route::post('penjadwalan-ami/storeauditor', [PenjadwalanAmiController::class, 'storeauditor'])->name('admin.penjadwalan-ami.storeauditor');
        Route::delete('penjadwalan-ami/destroyauditor', [PenjadwalanAmiController::class, 'destroyAuditor'])->name('admin.penjadwalan-ami.destroyauditor');

        Route::resource('penjadwalan-ami', PenjadwalanAmiController::class)->names([
            'index' => 'admin.penjadwalan-ami.index',
            'create' => 'admin.penjadwalan-ami.create',
            'store' => 'admin.penjadwalan-ami.store',
            'show' => 'admin.penjadwalan-ami.show',
            'edit' => 'admin.penjadwalan-ami.edit',
            'update' => 'admin.penjadwalan-ami.update',
            'destroy' => 'admin.penjadwalan-ami.destroy',
        ]);
        
        Route::resource('program-studi', ProgramStudiController::class)->names([
            'index' => 'admin.program-studi.index',
            'create' => 'admin.program-studi.create',
            'store' => 'admin.program-studi.store',
            'show' => 'admin.program-studi.show',
            'edit' => 'admin.program-studi.edit',
            'update' => 'admin.program-studi.update',
            'destroy' => 'admin.program-studi.destroy',
        ]);        
        
        Route::post('/program-studi/storejurusan', [ProgramStudiController::class, 'storejurusan'])->name('admin.program-studi.storejurusan');
        Route::post('/program-studi/storefakultas', [ProgramStudiController::class, 'storefakultas'])->name('admin.program-studi.storefakultas');
        
        Route::get('/statistik-elemen/{periode}/{prodi}/chart-elemen', [StatistikElemenController::class, 'chartElemen'])->where('periode', '.*')->where('prodi', '.*')->name('admin.statistik-elemen.chart-elemen');

        Route::resource('statistik-elemen', StatistikElemenController::class)->names([
            'index' => 'admin.statistik-elemen.index',
        ]);
        
        Route::get('/statistik-total/{periode}/{prodi}/chart-total', [StatistikTotalController::class, 'chartTotal'])->where('periode', '.*')->where('prodi', '.*')->name('admin.statistik-total.chart-total');

        Route::resource('statistik-total', StatistikTotalController::class)->names([
            'index' => 'admin.statistik-total.index',
        ]);

        Route::get('/forcasting/{periode}/{prodi}/hasil-forcasting', [ForcastingController::class, 'hasilForcasting'])->where('periode', '.*')->where('prodi', '.*')->name('admin.forcasting.hasil-forcasting');

        Route::resource('forcasting', ForcastingController::class)->names([
            'index' => 'admin.forcasting.index',
        ]);
    });
});

Route::group(['prefix' => 'user'], function(){
    Route::group(['middleware' => ['auth', 'user']], function () {
        Route::resource('dashboard', DashboardUserController::class)->names([
            'index' => 'user.dashboard.index',
        ]);

        Route::resource('dokumen-spmi-ami', DokumenSpmiAmiUserController::class)->names([
            'index' => 'user.dokumen-spmi-ami.index',
            'create' => 'user.dokumen-spmi-ami.create',
            'store' => 'user.dokumen-spmi-ami.store',
            'show' => 'user.dokumen-spmi-ami.show',
            'edit' => 'user.dokumen-spmi-ami.edit',
            'update' => 'user.dokumen-spmi-ami.update',
            'destroy' => 'user.dokumen-spmi-ami.destroy',
        ]);
        
        // Route::get('/pemenuhan-dokumen/{indikator_id}/input-capaian', [PemenuhanDokumenController::class, 'pemenuhanDokumen'])->name('user.pemenuhan-dokumen.input-capaian');
        // Route::get('/pemenuhan-dokumen/input-capaian/{indikator_id}/create', [PemenuhanDokumenController::class, 'pemenuhanDokumenCreate'])->name('user.pemenuhan-dokumen.input-capaian.create');
        // Route::post('/pemenuhan-dokumen/input-capaian/store', [PemenuhanDokumenController::class, 'pemenuhanDokumenStore'])->name('user.pemenuhan-dokumen.input-capaian.store');
        // Route::get('/pemenuhan-dokumen/input-capaian/{id}/edit', [PemenuhanDokumenController::class, 'pemenuhanDokumenEdit'])->name('user.pemenuhan-dokumen.input-capaian.edit');
        // Route::put('/pemenuhan-dokumen/input-capaian/{id}/update', [PemenuhanDokumenController::class, 'pemenuhanDokumenUpdate'])->name('user.pemenuhan-dokumen.input-capaian.update');
        // Route::delete('/pemenuhan-dokumen/input-capaian/{id}', [PemenuhanDokumenController::class, 'pemenuhanDokumenDestroy'])->name('user.pemenuhan-dokumen.input-capaian.destroy');

        // Route::get('/get-dokumen-details/{dokumen_nama}', [PemenuhanDokumenController::class, 'getDokumenDetails'])->name('getDokumenDetails');

        Route::get('/pemenuhan-dokumen', [NewPemenuhanDokumenController::class, 'index'])->name('user.pemenuhan-dokumen.index');
        Route::get('/pemenuhan-dokumen/{indikator_id}/input-capaian', [NewPemenuhanDokumenController::class, 'pemenuhanDokumen'])->name('user.pemenuhan-dokumen.input-capaian');
        Route::get('/pemenuhan-dokumen/input-capaian/{indikator_id}/create', [NewPemenuhanDokumenController::class, 'pemenuhanDokumenCreate'])->name('user.pemenuhan-dokumen.input-capaian.create');
        Route::post('/pemenuhan-dokumen/input-capaian/store', [NewPemenuhanDokumenController::class, 'pemenuhanDokumenStore'])->name('user.pemenuhan-dokumen.input-capaian.store');
        Route::post('/pemenuhan-dokumen/input-bukti-capaian/store', [NewPemenuhanDokumenController::class, 'pemenuhanBuktiStore'])->name('user.pemenuhan-dokumen.input-bukti.store');
        Route::get('/pemenuhan-dokumen/input-capaian/{id}/edit', [NewPemenuhanDokumenController::class, 'pemenuhanDokumenEdit'])->name('user.pemenuhan-dokumen.input-capaian.edit');
        Route::put('/pemenuhan-dokumen/input-capaian/{id}/update', [NewPemenuhanDokumenController::class, 'pemenuhanDokumenUpdate'])->name('user.pemenuhan-dokumen.input-capaian.update');
        Route::delete('/pemenuhan-dokumen/input-capaian/{id}', [NewPemenuhanDokumenController::class, 'pemenuhanDokumenDestroy'])->name('user.pemenuhan-dokumen.input-capaian.destroy');


            // Edit/Upload bukti: halaman baru
            Route::get('/kriteria-dokumen/kelola-bukti/{bukti}/edit', [NewPemenuhanDokumenController::class, 'kelolaBuktiEdit'])
                ->name('kriteria-dokumen.kelola-bukti.edit');

            Route::put('/kriteria-dokumen/kelola-bukti/{bukti}', [NewPemenuhanDokumenController::class, 'kelolaBuktiUpdate'])
                ->name('kriteria-dokumen.kelola-bukti.update');

        // Route::resource('pemenuhan-dokumen', PemenuhanDokumenController::class)->names([
        //     'index' => 'user.pemenuhan-dokumen.index',
        //     'create' => 'user.pemenuhan-dokumen.create',
        //     'store' => 'user.pemenuhan-dokumen.store',
        //     'show' => 'user.pemenuhan-dokumen.show',
        //     'edit' => 'user.pemenuhan-dokumen.edit',
        //     'update' => 'user.pemenuhan-dokumen.update',
        //     'destroy' => 'user.pemenuhan-dokumen.destroy',
        // ]);

        Route::resource('dokumen-aktif', DokumenAktifUserController::class)->names([
            'index' => 'user.dokumen-aktif.index',
        ]);

        Route::resource('dokumen-kadaluarsa', DokumenKadaluarsaUserController::class)->names([
            'index' => 'user.dokumen-kadaluarsa.index',
        ]);

        Route::get('/pengajuan-ami/{periode}/{prodi}/input-ami', [PengajuanAmiUserController::class, 'inputAmi'])->where('periode', '.*')->where('prodi', '.*')->name('user.pengajuan-ami.input-ami');
        Route::post('/pengajuan-ami/input-ami/store', [PengajuanAmiUserController::class, 'inputAmiStore'])->where('periode', '.*')->where('prodi', '.*')->name('user.pengajuan-ami.input-ami.store');
        Route::post('/pengajuan-ami/input-ami/update', [PengajuanAmiUserController::class, 'inputAmiUpdate'])->where('periode', '.*')->where('prodi', '.*')->name('user.pengajuan-ami.input-ami.update');
    
        Route::resource('pengajuan-ami', PengajuanAmiUserController::class)->names([
            'index' => 'user.pengajuan-ami.index',
            'create' => 'user.pengajuan-ami.create',
            'store' => 'user.pengajuan-ami.store',
            'show' => 'user.pengajuan-ami.show',
            'edit' => 'user.pengajuan-ami.edit',
            'update' => 'user.pengajuan-ami.update',
            'destroy' => 'user.pengajuan-ami.destroy',
        ]);

        Route::resource('input-ami', InputAmiUserController::class)->names([
            'index' => 'user.input-ami.index',
            'create' => 'user.input-ami.create',
            'store' => 'user.input-ami.store',
            'show' => 'user.input-ami.show',
            'edit' => 'user.input-ami.edit',
            'update' => 'user.input-ami.update',
            'destroy' => 'user.input-ami.destroy',
        ]);

        Route::get('/koreksi-ami/{periode}/{prodi}/revisi-prodi', [KoreksiAmiUserController::class, 'revisiProdi'])->where('periode', '.*')->where('prodi', '.*')->name('user.koreksi-ami.revisi-prodi');
        Route::post('/koreksi-ami/revisi-prodi/store', [KoreksiAmiUserController::class, 'RevisiProdiStore'])->where('periode', '.*')->where('prodi', '.*')->name('user.koreksi-ami.revisi-prodi.store');
        Route::post('/koreksi-ami/revisi-prodi/update', [KoreksiAmiUserController::class, 'RevisiProdiUpdate'])->where('periode', '.*')->where('prodi', '.*')->name('user.koreksi-ami.revisi-prodi.update');
    
        Route::resource('koreksi-ami', KoreksiAmiUserController::class)->names([
            'index' => 'user.koreksi-ami.index',
            'store' => 'user.koreksi-ami.store',
        ]);

        Route::get('/nilai-evaluasi-diri/{periode}/{prodi}/rekap-nilai', [UserNilaiEvaluasiDiriController::class, 'rekapNilai'])->where('periode', '.*')->where('prodi', '.*')->name('user.nilai-evaluasi-diri.rekap-nilai');
        Route::get('/nilai-evaluasi-diri/{periode}/{prodi}/rekap-nilai/report-lha', [UserNilaiEvaluasiDiriController::class, 'reportLha'])->where('periode', '.*')->where('prodi', '.*')->name('user.nilai-evaluasi-diri.rekap-nilai.report-lha');
        Route::get('/nilai-evaluasi-diri/{periode}/{prodi}/rekap-nilai/report-rtm', [UserNilaiEvaluasiDiriController::class, 'reportRtm'])->where('periode', '.*')->where('prodi', '.*')->name('user.nilai-evaluasi-diri.rekap-nilai.report-rtm');

        Route::resource('nilai-evaluasi-diri', UserNilaiEvaluasiDiriController::class)->names([
            'index' => 'user.nilai-evaluasi-diri.index',
        ]);

        Route::get('/statistik-elemen/{periode}/{prodi}/chart-elemen', [UserStatistikElemenController::class, 'chartElemen'])->where('periode', '.*')->where('prodi', '.*')->name('user.statistik-elemen.chart-elemen');

        Route::resource('statistik-elemen', UserStatistikElemenController::class)->names([
            'index' => 'user.statistik-elemen.index',
        ]);
        
        Route::get('/statistik-total/{periode}/{prodi}/chart-total', [UserStatistikTotalController::class, 'chartTotal'])->where('periode', '.*')->where('prodi', '.*')->name('user.statistik-total.chart-total');

        Route::resource('statistik-total', UserStatistikTotalController::class)->names([
            'index' => 'user.statistik-total.index',
        ]);

        Route::get('/forcasting/{periode}/{prodi}/hasil-forcasting', [UserForcastingController::class, 'hasilForcasting'])->where('periode', '.*')->where('prodi', '.*')->name('user.forcasting.hasil-forcasting');

        Route::resource('forcasting', UserForcastingController::class)->names([
            'index' => 'user.forcasting.index',
        ]);

        Route::resource('bantuan', BantuanController::class)->names([
            'index' => 'user.bantuan.index',
        ]);
        
    });
});

Route::group(['prefix' => 'auditor'], function(){
    Route::group(['middleware' => ['auth', 'auditor']], function () {
        Route::resource('dashboard', DashboardAuditorController::class)->names([
            'index' => 'auditor.dashboard.index',
        ]);

        Route::resource('dokumen-spmi-ami', DokumenSpmiAmiauditorController::class)->names([
            'index' => 'auditor.dokumen-spmi-ami.index',
        ]);

        Route::get('/konfirmasi-pengajuan/{periode}/{prodi}/show-pengajuan', [KonfirmasiPengajuanController::class, 'showPengajuan'])->where('periode', '.*')->where('prodi', '.*')->name('auditor.konfirmasi-pengajuan.show-pengajuan');

        Route::resource('konfirmasi-pengajuan', KonfirmasiPengajuanController::class)->names([
            'index' => 'auditor.konfirmasi-pengajuan.index',
            'create' => 'auditor.konfirmasi-pengajuan.create',
            'store' => 'auditor.konfirmasi-pengajuan.store',
            'show' => 'auditor.konfirmasi-pengajuan.show',
            'edit' => 'auditor.konfirmasi-pengajuan.edit',
            'update' => 'auditor.konfirmasi-pengajuan.update',
            'destroy' => 'auditor.konfirmasi-pengajuan.destroy',
        ]);

        Route::get('/evaluasi-ami/{periode}/{prodi}/audit-ami', [EvaluasiAmiAuditorController::class, 'auditAmi'])->where('periode', '.*')->where('prodi', '.*')->name('auditor.evaluasi-ami.audit-ami');

        Route::resource('evaluasi-ami', EvaluasiAmiAuditorController::class)->names([
            'index' => 'auditor.evaluasi-ami.index',
            'create' => 'auditor.evaluasi-ami.create',
            'store' => 'auditor.evaluasi-ami.store',
            'show' => 'auditor.evaluasi-ami.show',
            'edit' => 'auditor.evaluasi-ami.edit',
            'update' => 'auditor.evaluasi-ami.update',
            'destroy' => 'auditor.evaluasi-ami.destroy',
        ]);

        Route::get('/koreksi-ami/{periode}/{prodi}/revisi-ami', [KoreksiAmiAuditorController::class, 'revisiAmi'])->where('periode', '.*')->where('prodi', '.*')->name('auditor.koreksi-ami.revisi-ami');

        Route::resource('koreksi-ami', KoreksiAmiAuditorController::class)->names([
            'index' => 'auditor.koreksi-ami.index',
            'create' => 'auditor.koreksi-ami.create',
            'store' => 'auditor.koreksi-ami.store',
            'show' => 'auditor.koreksi-ami.show',
            'edit' => 'auditor.koreksi-ami.edit',
            'update' => 'auditor.koreksi-ami.update',
            'destroy' => 'auditor.koreksi-ami.destroy',
        ]);

        Route::get('/nilai-evaluasi-diri/{periode}/{prodi}/rekap-nilai', [AuditorNilaiEvaluasiDiriController::class, 'rekapNilai'])->where('periode', '.*')->where('prodi', '.*')->name('auditor.nilai-evaluasi-diri.rekap-nilai');
        Route::get('/nilai-evaluasi-diri/{periode}/{prodi}/rekap-nilai/report-lha', [AuditorNilaiEvaluasiDiriController::class, 'reportLha'])->where('periode', '.*')->where('prodi', '.*')->name('auditor.nilai-evaluasi-diri.rekap-nilai.report-lha');
        Route::get('/nilai-evaluasi-diri/{periode}/{prodi}/rekap-nilai/report-rtm', [AuditorNilaiEvaluasiDiriController::class, 'reportRtm'])->where('periode', '.*')->where('prodi', '.*')->name('auditor.nilai-evaluasi-diri.rekap-nilai.report-rtm');

        Route::resource('nilai-evaluasi-diri', AuditorNilaiEvaluasiDiriController::class)->names([
            'index' => 'auditor.nilai-evaluasi-diri.index',
        ]);

        Route::get('/statistik-elemen/{periode}/{prodi}/chart-elemen', [AuditorStatistikElemenController::class, 'chartElemen'])->where('periode', '.*')->where('prodi', '.*')->name('auditor.statistik-elemen.chart-elemen');

        Route::resource('statistik-elemen', AuditorStatistikElemenController::class)->names([
            'index' => 'auditor.statistik-elemen.index',
        ]);

        Route::get('/statistik-total/{periode}/{prodi}/chart-total', [AuditorStatistikTotalController::class, 'chartTotal'])->where('periode', '.*')->where('prodi', '.*')->name('auditor.statistik-total.chart-total');

        Route::resource('statistik-total', AuditorStatistikTotalController::class)->names([
            'index' => 'auditor.statistik-total.index',
        ]);

        Route::get('/forcasting/{periode}/{prodi}/hasil-forcasting', [AuditorForcastingController::class, 'hasilForcasting'])->where('periode', '.*')->where('prodi', '.*')->name('auditor.forcasting.hasil-forcasting');

        Route::resource('forcasting', AuditorForcastingController::class)->names([
            'index' => 'auditor.forcasting.index',
        ]);

        Route::resource('bantuan', BantuanController::class)->names([
            'index' => 'auditor.bantuan.index',
        ]);
    });
});

Route::get('preview', 'PDFController@preview');
Route::get('download', 'PDFController@download')->name('download');


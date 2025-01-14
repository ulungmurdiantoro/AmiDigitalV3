<?php
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

use App\Http\Controllers\Admin\DashboardAdminController;
use App\Http\Controllers\Admin\AktivitasProdiController;
use App\Http\Controllers\Admin\BantuanController;
use App\Http\Controllers\Admin\DokumenSpmiAmiController;
use App\Http\Controllers\Admin\ForcastingController;
use App\Http\Controllers\Admin\KriteriaDokumenController;
use App\Http\Controllers\Admin\NilaiEvaluasiDiriController;
use App\Http\Controllers\Admin\PenggunaAuditorController;
use App\Http\Controllers\Admin\PenggunaProdiController;
use App\Http\Controllers\Admin\PengumumanController;
use App\Http\Controllers\Admin\PenjadwalanAmiController;
use App\Http\Controllers\Admin\ProgramStudiController;
use App\Http\Controllers\Admin\StatistikElemenController;
use App\Http\Controllers\Admin\StatistikTotalController;
use App\Http\Controllers\User\DashboardUserController;
use App\Http\Controllers\User\DokumenSpmiAmiUserController;
use App\Http\Controllers\User\PemenuhanDokumenController;
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
            'create' => 'admin.dashboard.create',
            'store' => 'admin.dashboard.store',
            'show' => 'admin.dashboard.show',
            'edit' => 'admin.dashboard.edit',
            'update' => 'admin.dashboard.update',
            'destroy' => 'admin.dashboard.destroy',
        ]);
        Route::resource('aktivitas-prodi', AktivitasProdiController::class)->names([
            'index' => 'admin.aktivitas-prodi.index',
            'create' => 'admin.aktivitas-prodi.create',
            'store' => 'admin.aktivitas-prodi.store',
            'show' => 'admin.aktivitas-prodi.show',
            'edit' => 'admin.aktivitas-prodi.edit',
            'update' => 'admin.aktivitas-prodi.update',
            'destroy' => 'admin.aktivitas-prodi.destroy',
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
        
        Route::get('/kriteria-dokumen/import', [KriteriaDokumenController::class, 'import'])->name('admin.kriteria-dokumen.import');
        Route::get('/kriteria-dokumen/{indikator_kode}/kelola-target', [KriteriaDokumenController::class, 'kelolaTarget'])->name('admin.kriteria-dokumen.kelola-target');
        Route::get('/kriteria-dokumen/kelola-target/{indikator_kode}/create', [KriteriaDokumenController::class, 'kelolaTargetCreate'])->name('admin.kriteria-dokumen.kelola-target.create');
        Route::post('/kriteria-dokumen/kelola-target/store', [KriteriaDokumenController::class, 'kelolaTargetStore'])->name('admin.kriteria-dokumen.kelola-target.store');
        Route::get('/kriteria-dokumen/kelola-target/{indikator_kode}/edit', [KriteriaDokumenController::class, 'kelolaTargetEdit'])->name('admin.kriteria-dokumen.kelola-target.edit');
        Route::put('/kriteria-dokumen/kelola-target/{id}/update', [KriteriaDokumenController::class, 'kelolaTargetUpdate'])->name('admin.kriteria-dokumen.kelola-target.update');
        Route::delete('/kriteria-dokumen/kelola-target/{id}', [KriteriaDokumenController::class, 'kelolaTargetDestroy'])->name('admin.kriteria-dokumen.kelola-target.destroy');
        Route::post('/kriteria-dokumen/kelola-target/TipeDokumenStore', [KriteriaDokumenController::class, 'tipeDokumenStore'])->name('admin.kriteria-dokumen.kelola-target.tipedokumenstore');
        Route::delete('/kriteria-dokumen/kelola-target/TipeDokumenDestroy', [KriteriaDokumenController::class, 'tipeDokumenDestroy'])->name('admin.kriteria-dokumen.kelola-target.tipedokumendestroy');

        Route::resource('kriteria-dokumen', KriteriaDokumenController::class)->names([
            'index' => 'admin.kriteria-dokumen.index',
            'create' => 'admin.kriteria-dokumen.create',
            'store' => 'admin.kriteria-dokumen.store',
            'show' => 'admin.kriteria-dokumen.show',
            'edit' => 'admin.kriteria-dokumen.edit',
            'update' => 'admin.kriteria-dokumen.update',
            'destroy' => 'admin.kriteria-dokumen.destroy',
        ]);

        Route::post('kriteria-dokumen/import', [KriteriaDokumenController::class, 'storeImport'])->name('admin.kriteria-dokumen.storeImport');

        Route::resource('nilai-evaluasi-diri', NilaiEvaluasiDiriController::class)->names([
            'index' => 'admin.nilai-evaluasi-diri.index',
            'create' => 'admin.nilai-evaluasi-diri.create',
            'store' => 'admin.nilai-evaluasi-diri.store',
            'show' => 'admin.nilai-evaluasi-diri.show',
            'edit' => 'admin.nilai-evaluasi-diri.edit',
            'update' => 'admin.nilai-evaluasi-diri.update',
            'destroy' => 'admin.nilai-evaluasi-diri.destroy',
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
        
        Route::resource('statistik-elemen', StatistikElemenController::class)->names([
            'index' => 'admin.statistik-elemen.index',
            'create' => 'admin.statistik-elemen.create',
            'store' => 'admin.statistik-elemen.store',
            'show' => 'admin.statistik-elemen.show',
            'edit' => 'admin.statistik-elemen.edit',
            'update' => 'admin.statistik-elemen.update',
            'destroy' => 'admin.statistik-elemen.destroy',
        ]);
        
        Route::resource('statistik-total', StatistikTotalController::class)->names([
            'index' => 'admin.statistik-total.index',
            'create' => 'admin.statistik-total.create',
            'store' => 'admin.statistik-total.store',
            'show' => 'admin.statistik-total.show',
            'edit' => 'admin.statistik-total.edit',
            'update' => 'admin.statistik-total.update',
            'destroy' => 'admin.statistik-total.destroy',
        ]);

        Route::resource('forcasting', ForcastingController::class)->names([
            'index' => 'admin.forcasting.index',
            'create' => 'admin.forcasting.create',
            'store' => 'admin.forcasting.store',
            'show' => 'admin.forcasting.show',
            'edit' => 'admin.forcasting.edit',
            'update' => 'admin.forcasting.update',
            'destroy' => 'admin.forcasting.destroy',
        ]);
    });
});
Route::group(['prefix' => 'user'], function(){
    Route::group(['middleware' => ['auth', 'user']], function () {
        Route::resource('dashboard', DashboardUserController::class)->names([
            'index' => 'user.dashboard.index',
            'create' => 'user.dashboard.create',
            'store' => 'user.dashboard.store',
            'show' => 'user.dashboard.show',
            'edit' => 'user.dashboard.edit',
            'update' => 'user.dashboard.update',
            'destroy' => 'user.dashboard.destroy',
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
        
        Route::get('/pemenuhan-dokumen/{indikator_kode}/input-capaian', [PemenuhanDokumenController::class, 'pemenuhanDokumen'])->name('user.pemenuhan-dokumen.input-capaian');
        Route::get('/pemenuhan-dokumen/input-capaian/{indikator_kode}/create', [PemenuhanDokumenController::class, 'pemenuhanDokumenCreate'])->name('user.pemenuhan-dokumen.input-capaian.create');
        Route::post('/pemenuhan-dokumen/input-capaian/store', [PemenuhanDokumenController::class, 'pemenuhanDokumenStore'])->name('user.pemenuhan-dokumen.input-capaian.store');
        Route::get('/pemenuhan-dokumen/input-capaian/{id}/edit', [PemenuhanDokumenController::class, 'pemenuhanDokumenEdit'])->name('user.pemenuhan-dokumen.input-capaian.edit');
        Route::put('/pemenuhan-dokumen/input-capaian/{id}/update', [PemenuhanDokumenController::class, 'pemenuhanDokumenUpdate'])->name('user.pemenuhan-dokumen.input-capaian.update');
        Route::delete('/pemenuhan-dokumen/input-capaian/{id}', [PemenuhanDokumenController::class, 'pemenuhanDokumenDestroy'])->name('user.pemenuhan-dokumen.input-capaian.destroy');

        Route::get('/get-dokumen-details/{dokumen_nama}', [PemenuhanDokumenController::class, 'getDokumenDetails'])->name('getDokumenDetails');

        Route::resource('pemenuhan-dokumen', PemenuhanDokumenController::class)->names([
            'index' => 'user.pemenuhan-dokumen.index',
            'create' => 'user.pemenuhan-dokumen.create',
            'store' => 'user.pemenuhan-dokumen.store',
            'show' => 'user.pemenuhan-dokumen.show',
            'edit' => 'user.pemenuhan-dokumen.edit',
            'update' => 'user.pemenuhan-dokumen.update',
            'destroy' => 'user.pemenuhan-dokumen.destroy',
        ]);

        Route::resource('dokumen-aktif', DokumenAktifUserController::class)->names([
            'index' => 'user.dokumen-aktif.index',
            'create' => 'user.dokumen-aktif.create',
            'store' => 'user.dokumen-aktif.store',
            'show' => 'user.dokumen-aktif.show',
            'edit' => 'user.dokumen-aktif.edit',
            'update' => 'user.dokumen-aktif.update',
            'destroy' => 'user.dokumen-aktif.destroy',
        ]);

        Route::resource('dokumen-kadaluarsa', DokumenKadaluarsaUserController::class)->names([
            'index' => 'user.dokumen-kadaluarsa.index',
            'create' => 'user.dokumen-kadaluarsa.create',
            'store' => 'user.dokumen-kadaluarsa.store',
            'show' => 'user.dokumen-kadaluarsa.show',
            'edit' => 'user.dokumen-kadaluarsa.edit',
            'update' => 'user.dokumen-kadaluarsa.update',
            'destroy' => 'user.dokumen-kadaluarsa.destroy',
        ]);

        Route::get('/pengajuan-ami/{periode}/{prodi}/input-ami', [PengajuanAmiUserController::class, 'inputAmi'])->where('periode', '.*')->where('prodi', '.*')->name('user.pengajuan-ami.input-ami');
        Route::post('/pengajuan-ami/input-ami/store', [PengajuanAmiUserController::class, 'inputAmiStore'])->where('periode', '.*')->where('prodi', '.*')->name('user.pengajuan-ami.input-ami-store');
    
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

        Route::resource('koreksi-ami', KoreksiAmiUserController::class)->names([
            'index' => 'user.koreksi-ami.index',
            'create' => 'user.koreksi-ami.create',
            'store' => 'user.koreksi-ami.store',
            'show' => 'user.koreksi-ami.show',
            'edit' => 'user.koreksi-ami.edit',
            'update' => 'user.koreksi-ami.update',
            'destroy' => 'user.koreksi-ami.destroy',
        ]);

        Route::resource('nilai-evaluasi-diri', NilaiEvaluasiDiriController::class)->names([
            'index' => 'user.nilai-evaluasi-diri.index',
            'create' => 'user.nilai-evaluasi-diri.create',
            'store' => 'user.nilai-evaluasi-diri.store',
            'show' => 'user.nilai-evaluasi-diri.show',
            'edit' => 'user.nilai-evaluasi-diri.edit',
            'update' => 'user.nilai-evaluasi-diri.update',
            'destroy' => 'user.nilai-evaluasi-diri.destroy',
        ]);

        Route::resource('statistik-elemen', StatistikElemenController::class)->names([
            'index' => 'user.statistik-elemen.index',
            'create' => 'user.statistik-elemen.create',
            'store' => 'user.statistik-elemen.store',
            'show' => 'user.statistik-elemen.show',
            'edit' => 'user.statistik-elemen.edit',
            'update' => 'user.statistik-elemen.update',
            'destroy' => 'user.statistik-elemen.destroy',
        ]);
        
        Route::resource('statistik-total', StatistikTotalController::class)->names([
            'index' => 'user.statistik-total.index',
            'create' => 'user.statistik-total.create',
            'store' => 'user.statistik-total.store',
            'show' => 'user.statistik-total.show',
            'edit' => 'user.statistik-total.edit',
            'update' => 'user.statistik-total.update',
            'destroy' => 'user.statistik-total.destroy',
        ]);

        Route::resource('forcasting', ForcastingController::class)->names([
            'index' => 'user.forcasting.index',
            'create' => 'user.forcasting.create',
            'store' => 'user.forcasting.store',
            'show' => 'user.forcasting.show',
            'edit' => 'user.forcasting.edit',
            'update' => 'user.forcasting.update',
            'destroy' => 'user.forcasting.destroy',
        ]);

        Route::resource('bantuan', BantuanController::class)->names([
            'index' => 'user.bantuan.index',
            'create' => 'user.bantuan.create',
            'store' => 'user.bantuan.store',
            'show' => 'user.bantuan.show',
            'edit' => 'user.bantuan.edit',
            'update' => 'user.bantuan.update',
            'destroy' => 'user.bantuan.destroy',
        ]);
        
    });
});
Route::group(['prefix' => 'auditor'], function(){
    Route::group(['middleware' => ['auth', 'auditor']], function () {
        Route::resource('dashboard', DashboardAuditorController::class)->names([
            'index' => 'auditor.dashboard.index',
            'create' => 'auditor.dashboard.create',
            'store' => 'auditor.dashboard.store',
            'show' => 'auditor.dashboard.show',
            'edit' => 'auditor.dashboard.edit',
            'update' => 'auditor.dashboard.update',
            'destroy' => 'auditor.dashboard.destroy',
        ]);

        Route::resource('dokumen-spmi-ami', DokumenSpmiAmiauditorController::class)->names([
            'index' => 'auditor.dokumen-spmi-ami.index',
            'create' => 'auditor.dokumen-spmi-ami.create',
            'store' => 'auditor.dokumen-spmi-ami.store',
            'show' => 'auditor.dokumen-spmi-ami.show',
            'edit' => 'auditor.dokumen-spmi-ami.edit',
            'update' => 'auditor.dokumen-spmi-ami.update',
            'destroy' => 'auditor.dokumen-spmi-ami.destroy',
        ]);

        Route::resource('konfirmasi-pengajuan', KonfirmasiPengajuanController::class)->names([
            'index' => 'auditor.konfirmasi-pengajuan.index',
            'create' => 'auditor.konfirmasi-pengajuan.create',
            'store' => 'auditor.konfirmasi-pengajuan.store',
            'show' => 'auditor.konfirmasi-pengajuan.show',
            'edit' => 'auditor.konfirmasi-pengajuan.edit',
            'update' => 'auditor.konfirmasi-pengajuan.update',
            'destroy' => 'auditor.konfirmasi-pengajuan.destroy',
        ]);

        Route::resource('evaluasi-ami', EvaluasiAmiAuditorController::class)->names([
            'index' => 'auditor.evaluasi-ami.index',
            'create' => 'auditor.evaluasi-ami.create',
            'store' => 'auditor.evaluasi-ami.store',
            'show' => 'auditor.evaluasi-ami.show',
            'edit' => 'auditor.evaluasi-ami.edit',
            'update' => 'auditor.evaluasi-ami.update',
            'destroy' => 'auditor.evaluasi-ami.destroy',
        ]);

        Route::get('/input-ami/{indikator_kode}/nilai-ami', [InputAmiAuditorController::class, 'nilaiAmi'])->name('auditor.input-ami.nilai-ami');

        Route::resource('input-ami', InputAmiAuditorController::class)->names([
            'index' => 'auditor.input-ami.index',
            'create' => 'auditor.input-ami.create',
            'store' => 'auditor.input-ami.store',
            'show' => 'auditor.input-ami.show',
            'edit' => 'auditor.input-ami.edit',
            'update' => 'auditor.input-ami.update',
            'destroy' => 'auditor.input-ami.destroy',
        ]);

        Route::resource('koreksi-ami', KoreksiAmiAuditorController::class)->names([
            'index' => 'auditor.koreksi-ami.index',
            'create' => 'auditor.koreksi-ami.create',
            'store' => 'auditor.koreksi-ami.store',
            'show' => 'auditor.koreksi-ami.show',
            'edit' => 'auditor.koreksi-ami.edit',
            'update' => 'auditor.koreksi-ami.update',
            'destroy' => 'auditor.koreksi-ami.destroy',
        ]);

        Route::resource('edit-ami', EditAmiAuditorController::class)->names([
            'index' => 'auditor.edit-ami.index',
            'create' => 'auditor.edit-ami.create',
            'store' => 'auditor.edit-ami.store',
            'show' => 'auditor.edit-ami.show',
            'edit' => 'auditor.edit-ami.edit',
            'update' => 'auditor.edit-ami.update',
            'destroy' => 'auditor.edit-ami.destroy',
        ]);

        Route::resource('nilai-evaluasi-diri', NilaiEvaluasiDiriController::class)->names([
            'index' => 'auditor.nilai-evaluasi-diri.index',
            'create' => 'auditor.nilai-evaluasi-diri.create',
            'store' => 'auditor.nilai-evaluasi-diri.store',
            'show' => 'auditor.nilai-evaluasi-diri.show',
            'edit' => 'auditor.nilai-evaluasi-diri.edit',
            'update' => 'auditor.nilai-evaluasi-diri.update',
            'destroy' => 'auditor.nilai-evaluasi-diri.destroy',
        ]);

        Route::resource('statistik-elemen', StatistikElemenController::class)->names([
            'index' => 'auditor.statistik-elemen.index',
            'create' => 'auditor.statistik-elemen.create',
            'store' => 'auditor.statistik-elemen.store',
            'show' => 'auditor.statistik-elemen.show',
            'edit' => 'auditor.statistik-elemen.edit',
            'update' => 'auditor.statistik-elemen.update',
            'destroy' => 'auditor.statistik-elemen.destroy',
        ]);
        
        Route::resource('statistik-total', StatistikTotalController::class)->names([
            'index' => 'auditor.statistik-total.index',
            'create' => 'auditor.statistik-total.create',
            'store' => 'auditor.statistik-total.store',
            'show' => 'auditor.statistik-total.show',
            'edit' => 'auditor.statistik-total.edit',
            'update' => 'auditor.statistik-total.update',
            'destroy' => 'auditor.statistik-total.destroy',
        ]);

        Route::resource('forcasting', ForcastingController::class)->names([
            'index' => 'auditor.forcasting.index',
            'create' => 'auditor.forcasting.create',
            'store' => 'auditor.forcasting.store',
            'show' => 'auditor.forcasting.show',
            'edit' => 'auditor.forcasting.edit',
            'update' => 'auditor.forcasting.update',
            'destroy' => 'auditor.forcasting.destroy',
        ]);

        Route::resource('bantuan', BantuanController::class)->names([
            'index' => 'auditor.bantuan.index',
            'create' => 'auditor.bantuan.create',
            'store' => 'auditor.bantuan.store',
            'show' => 'auditor.bantuan.show',
            'edit' => 'auditor.bantuan.edit',
            'update' => 'auditor.bantuan.update',
            'destroy' => 'auditor.bantuan.destroy',
        ]);
    });
});



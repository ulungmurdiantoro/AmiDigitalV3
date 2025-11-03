<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StandarCapaian;
use App\Models\StandarNilai;
use App\Models\PenjadwalanAmi;
use App\Models\StandarAkreditasi;
use App\Models\Jenjang;
use App\Models\TransaksiAmi;
use App\Models\Standard;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class AktivitasProdiController extends Controller
{
    public function index()
    {
        $statusToProgress = [
            'Draft' => 0,
            'Diajukan' => 25,
            'Diterima' => 50,
            'Ditolak' => 0,
            'Koreksi' => 75,
            'Selesai' => 100,
        ];

        $transaksi_ami = TransaksiAmi::with('auditorAmi')
            ->latest()
            ->paginate(10);

        Carbon::setLocale('id');

        foreach ($transaksi_ami as $transaksi) {
            $transaksi->formatted_created_at = Carbon::parse($transaksi->created_at)->isoFormat('D MMMM Y');
            $transaksi->progress = $statusToProgress[$transaksi->status] ?? 0;
            $transaksi->progressColor = match (true) {
                $transaksi->progress <= 25 => 'bg-danger',
                $transaksi->progress <= 50 => 'bg-warning',
                $transaksi->progress <= 75 => 'bg-info',
                default => 'bg-success',
            };
        }
        
        return view('pages.admin.aktivitas-prodi.index', [
            'transaksi_ami' => $transaksi_ami,
        ]);
    }

    public function showPengajuan(Request $request, $periode, $prodi)
    {
        $transaksi_ami = TransaksiAmi::where('periode', $periode)
        ->where('prodi', $prodi)
        ->with('auditorAmi.user') 
        ->first();

        $akreditasi_kode  =$transaksi_ami->standar_akreditasi;       
        $jenjang_raw      = session('user_penempatan');       

        $jenjang_nama = trim(explode(' - ', (string)$jenjang_raw, 2)[0]);
        if ($jenjang_nama === '') $jenjang_nama = 'S1';

        $validAkreditasi = StandarAkreditasi::pluck('nama')->toArray();
        $validJenjang    = Jenjang::pluck('nama')->toArray();

        if (!in_array($akreditasi_kode, $validAkreditasi, true)) {
            Log::warning('Nilai akreditasi sesi tidak valid, fallback ke BAN-PT', ['session' => $akreditasi_kode]);
            $akreditasi_kode = 'BAN-PT';
        }
        if (!in_array($jenjang_nama, $validJenjang, true)) {
            Log::warning('Nilai jenjang sesi tidak valid, fallback ke S1', ['session' => $jenjang_nama]);
            $jenjang_nama = 'S1';
        }

        $akreditasi = Cache::remember("akreditasi_{$akreditasi_kode}", 3600, function () use ($akreditasi_kode) {
            return StandarAkreditasi::where('nama', $akreditasi_kode)->firstOrFail();
        });

        $jenjang = Cache::remember("jenjang_{$jenjang_nama}", 3600, function () use ($jenjang_nama) {
            return Jenjang::where('nama', $jenjang_nama)->firstOrFail();
        });

        $standardsQuery = Standard::query()
            ->with(['elements.indicators', 'buktiStandar'])
            ->where('standar_akreditasi_id', $akreditasi->id)
            ->where('jenjang_id', $jenjang->id);

        $standards = $standardsQuery->get();

        $penjadwalan_ami = PenjadwalanAmi::with(['auditor_ami.user'])
            ->when($request->q, function ($query) use ($request) {
                $query->whereHas('auditor_ami.user', function ($q) use ($request) {
                    $q->where('user_nama', 'like', '%' . $request->q . '%');
                })
                ->orWhere('prodi_nama', 'like', '%' . $request->q . '%');
            })
            ->where('prodi', $jenjang_raw)
            ->latest()
            ->get();

        $auditors = User::where('user_level', 'auditor')->get();

        $akreditasi = Cache::remember("akreditasi_{$akreditasi_kode}", 3600, function () use ($akreditasi_kode) {
            return StandarAkreditasi::where('nama', $akreditasi_kode)->firstOrFail();
        });

        $jenjang = Cache::remember("jenjang_{$jenjang_nama}", 3600, function () use ($jenjang_nama) {
            return Jenjang::where('nama', $jenjang_nama)->firstOrFail();
        });

        return view('pages.admin.aktivitas-prodi.show', [
            'akreditasi' => $akreditasi,
            'jenjang'    => $jenjang,
            'standards'  => $standards,
            'periode' => $periode,
            'prodi' => $jenjang_raw,
            'penjadwalan_ami' => $penjadwalan_ami,
            'transaksi_ami' => $transaksi_ami,
            'auditors' => $auditors,
        ]);
    }

}

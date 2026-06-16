<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Standard;
use App\Models\StandarAkreditasi;
use App\Models\Jenjang;
use App\Models\TransaksiAmi;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Carbon\Carbon;

class UserStatistikElemenController extends Controller
{
    public function index()
    {
        $prodi = Session::get('user_penempatan');

        $transaksi_ami = TransaksiAmi::with('auditorAmi')
            ->where('status', 'Selesai')
            ->where('prodi', $prodi)
            ->latest()
            ->paginate(10);

        foreach ($transaksi_ami as $item) {
            Carbon::setLocale('id');
            $item->formatted_created_at = Carbon::parse($item->created_at)->isoFormat('D MMMM Y');
        }

        return view('pages.user.statistik-elemen.index', [
            'transaksi_ami' => $transaksi_ami,
        ]);
    }

    public function chartElemen(Request $request, $periode, $prodi)
    {
        $transaksi_ami = TransaksiAmi::where('periode', $periode)
            ->where('prodi', $prodi)
            ->with('auditorAmi.user')
            ->first();

        $akreditasi_kode = $transaksi_ami->standar_akreditasi ?? 'BAN-PT';
        $jenjang_nama = trim(explode(' - ', (string) $prodi, 2)[0]);
        if ($jenjang_nama === '') {
            $jenjang_nama = 'S1';
        }

        if (!in_array($akreditasi_kode, StandarAkreditasi::pluck('nama')->toArray(), true)) {
            Log::warning('Nilai akreditasi tidak valid, fallback ke BAN-PT', ['nilai' => $akreditasi_kode]);
            $akreditasi_kode = 'BAN-PT';
        }
        if (!in_array($jenjang_nama, Jenjang::pluck('nama')->toArray(), true)) {
            Log::warning('Nilai jenjang tidak valid, fallback ke S1', ['nilai' => $jenjang_nama]);
            $jenjang_nama = 'S1';
        }

        $akreditasi = StandarAkreditasi::where('nama', $akreditasi_kode)->firstOrFail();
        $jenjang = Jenjang::where('nama', $jenjang_nama)->firstOrFail();

        $standards = Standard::query()
            ->with([
                'elements.indicators.dokumen_nilais' => function ($q) use ($periode, $prodi) {
                    $q->where('periode', $periode)->where('prodi', $prodi);
                },
                'buktiStandar',
            ])
            ->where('standar_akreditasi_id', $akreditasi->id)
            ->where('jenjang_id', $jenjang->id)
            ->get();

        return view('pages.user.statistik-elemen.chart-elemen.index', [
            'akreditasi' => $akreditasi,
            'jenjang' => $jenjang,
            'standards' => $standards,
            'periode' => $periode,
            'prodi' => $prodi,
            'transaksi_ami' => $transaksi_ami,
        ]);
    }
}

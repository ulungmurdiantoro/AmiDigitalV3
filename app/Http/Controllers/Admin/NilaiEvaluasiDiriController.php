<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Concerns\AccreditationCalculator;
use App\Models\StandarNilai;
use App\Models\PenjadwalanAmi;
use App\Models\StandarAkreditasi;
use App\Models\Jenjang;
use App\Models\TransaksiAmi;
use App\Models\User;
use App\Models\Standard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Mpdf\Mpdf;
use Carbon\Carbon;

class NilaiEvaluasiDiriController extends Controller
{
    use AccreditationCalculator;
    public function index()
    {
        $transaksi_ami = TransaksiAmi::with('auditorAmi')
            ->where('status', 'Selesai')
            ->latest()
            ->paginate(10);

        foreach ($transaksi_ami as $item) {
            Carbon::setLocale('id');
            $item->formatted_created_at = Carbon::parse($item->created_at)->isoFormat('D MMMM Y');
        }

        return view('pages.admin.nilai-evaluasi-diri.index', [
            'transaksi_ami' => $transaksi_ami,
        ]);
    }

    public function rekapNilai(Request $request, $periode, $prodi)
    {
        $transaksi_ami = TransaksiAmi::where('periode', $periode)
            ->where('prodi', $prodi)
            ->with('auditorAmi.user')
            ->first();

        $akreditasi_kode = $transaksi_ami->standar_akreditasi ?? 'BAN-PT';
        $jenjang_raw     = $prodi;
        $jenjang_nama    = trim(explode(' - ', (string) $jenjang_raw, 2)[0]) ?: 'S1';

        $validAkreditasi = StandarAkreditasi::pluck('nama')->toArray();
        $validJenjang    = Jenjang::pluck('nama')->toArray();

        if (!in_array($akreditasi_kode, $validAkreditasi, true)) {
            Log::warning('Nilai akreditasi tidak valid, fallback ke BAN-PT', ['nilai' => $akreditasi_kode]);
            $akreditasi_kode = 'BAN-PT';
        }
        if (!in_array($jenjang_nama, $validJenjang, true)) {
            Log::warning('Nilai jenjang tidak valid, fallback ke S1', ['nilai' => $jenjang_nama]);
            $jenjang_nama = 'S1';
        }

        $akreditasi = Cache::remember("akreditasi_{$akreditasi_kode}", 3600, function () use ($akreditasi_kode) {
            return StandarAkreditasi::where('nama', $akreditasi_kode)->firstOrFail();
        });

        $jenjang = Cache::remember("jenjang_{$jenjang_nama}", 3600, function () use ($jenjang_nama) {
            return Jenjang::where('nama', $jenjang_nama)->firstOrFail();
        });

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

        return view('pages.admin.nilai-evaluasi-diri.rekap-nilai.index', [
            'akreditasi'      => $akreditasi,
            'jenjang'         => $jenjang,
            'standards'       => $standards,
            'periode'         => $periode,
            'prodi'           => $jenjang_raw,
            'penjadwalan_ami' => $penjadwalan_ami,
            'transaksi_ami'   => $transaksi_ami,
            'auditors'        => $auditors,
        ]);
    }

    public function reportLha(Request $request, $periode, $prodi)
    {
        $transaksi_ami = TransaksiAmi::where('periode', $periode)
            ->where('prodi', $prodi)
            ->with('auditorAmi.user')
            ->first();

        $akreditasi_kode = $transaksi_ami->standar_akreditasi ?? 'BAN-PT';
        $jenjang_raw     = $prodi;
        $jenjang_nama    = trim(explode(' - ', (string) $jenjang_raw, 2)[0]) ?: 'S1';

        $validAkreditasi = StandarAkreditasi::pluck('nama')->toArray();
        $validJenjang    = Jenjang::pluck('nama')->toArray();

        if (!in_array($akreditasi_kode, $validAkreditasi, true)) {
            $akreditasi_kode = 'BAN-PT';
        }
        if (!in_array($jenjang_nama, $validJenjang, true)) {
            $jenjang_nama = 'S1';
        }

        $akreditasi = Cache::remember("akreditasi_{$akreditasi_kode}", 3600, function () use ($akreditasi_kode) {
            return StandarAkreditasi::where('nama', $akreditasi_kode)->firstOrFail();
        });

        $jenjang = Cache::remember("jenjang_{$jenjang_nama}", 3600, function () use ($jenjang_nama) {
            return Jenjang::where('nama', $jenjang_nama)->firstOrFail();
        });

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

        $penjadwalan_ami = PenjadwalanAmi::with(['auditor_ami.user'])
            ->where('prodi', $jenjang_raw)
            ->latest()
            ->get();

        $auditors      = User::where('user_level', 'auditor')->get();
        $tanggal       = now()->format('d-m-Y');
        $tanggal_audit = optional($transaksi_ami->updated_at)->format('d-m-Y') ?? $tanggal;

        $newLams = ['LAMSAMA', 'LAMTEKNIK', 'LAMINFOKOM', 'LAMPTKES'];
        if ($akreditasi_kode === 'LAMEMBA') {
            $totalResult = $this->calculateTotalLamemeba($periode, $prodi, "{$akreditasi_kode} {$jenjang_nama}");
        } elseif (in_array($akreditasi_kode, $newLams, true)) {
            $prodiParts  = explode(' - ', $prodi);
            $totalResult = [
                'total'       => $this->computeNaFromStandards($standards, $akreditasi_kode),
                'prodiPrefix' => trim($prodiParts[0] ?? $prodi),
            ];
        } else {
            $totalResult = $this->calculateTotal($periode, $prodi, "{$akreditasi_kode} {$jenjang_nama}");
        }

        $totalNilai  = $totalResult['total'];
        $prodiPrefix = $totalResult['prodiPrefix'];
        $forecast    = $this->calculateForecast($akreditasi_kode, $totalNilai, $standards);

        $html = view('pages.report.report-lha', [
            'akreditasi'      => $akreditasi,
            'jenjang'         => $jenjang,
            'standards'       => $standards,
            'periode'         => $periode,
            'prodi'           => $jenjang_raw,
            'penjadwalan_ami' => $penjadwalan_ami,
            'transaksi_ami'   => $transaksi_ami,
            'auditors'        => $auditors,
            'tanggal'         => $tanggal,
            'tanggal_audit'   => $tanggal_audit,
            'totalNilai'      => $totalNilai,
            'prodiPrefix'     => $prodiPrefix,
            'akreditasiKode'  => $akreditasi_kode,
            'forecast'        => $forecast,
        ])->render();

        $mpdf = new \Mpdf\Mpdf([
            'format'        => 'A4',
            'orientation'   => 'P',
            'margin_top'    => 10,
            'margin_bottom' => 15,
        ]);
        $mpdf->SetTitle('Laporan Hasil Audit');
        $mpdf->SetFooter('{DATE j-m-Y}||Page {PAGENO}');
        $mpdf->WriteHTML($html);
        $pdfContent = $mpdf->Output('', 'S');

        return response($pdfContent)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="laporan_hasil_audit.pdf"');
    }

    public function reportRtm(Request $request, $periode, $prodi)
    {
        if (strpos($periode, '/') !== false) {
            [$tahunAwal, $tahunAkhir] = explode('/', $periode);
            $periode = (int) $tahunAwal;
        }

        $periodeSebelumnya  = $periode - 1;
        $periodeSelanjutnya = $periode + 1;

        $tahunAjaran            = $periode . '/' . ($periode + 1);
        $tahunAjaranSebelumnya  = $periodeSebelumnya . '/' . ($periode);
        $tahunAjaranSelanjutnya = $periodeSelanjutnya . '/' . ($periodeSelanjutnya + 1);

        $transaksi_ami = TransaksiAmi::where('periode', $periode)
            ->where('prodi', $prodi)
            ->with('auditorAmi.user')
            ->first();

        $transaksi_ami_sebelumnya = TransaksiAmi::where('periode', $periodeSebelumnya)
            ->where('prodi', $prodi)
            ->with('auditorAmi.user')
            ->first();

        $akreditasi_kode = $transaksi_ami->standar_akreditasi ?? 'BAN-PT';
        $jenjang_raw     = $prodi;
        $jenjang_nama    = trim(explode(' - ', (string) $jenjang_raw, 2)[0]) ?: 'S1';

        $validAkreditasi = StandarAkreditasi::pluck('nama')->toArray();
        $validJenjang    = Jenjang::pluck('nama')->toArray();

        if (!in_array($akreditasi_kode, $validAkreditasi, true)) {
            $akreditasi_kode = 'BAN-PT';
        }
        if (!in_array($jenjang_nama, $validJenjang, true)) {
            $jenjang_nama = 'S1';
        }

        $akreditasi = Cache::remember("akreditasi_{$akreditasi_kode}", 3600, function () use ($akreditasi_kode) {
            return StandarAkreditasi::where('nama', $akreditasi_kode)->firstOrFail();
        });

        $jenjang = Cache::remember("jenjang_{$jenjang_nama}", 3600, function () use ($jenjang_nama) {
            return Jenjang::where('nama', $jenjang_nama)->firstOrFail();
        });

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

        $standards_sebelumnya = Standard::with(['elements.indicators.dokumen_nilais', 'buktiStandar'])
            ->where('standar_akreditasi_id', $akreditasi->id)
            ->where('jenjang_id', $jenjang->id)
            ->get();

        $penjadwalan_ami = PenjadwalanAmi::with(['auditor_ami.user'])
            ->where('prodi', $jenjang_raw)
            ->latest()
            ->get();

        $auditors      = User::where('user_level', 'auditor')->get();
        $tanggal       = now()->format('d-m-Y');
        $tanggal_audit = optional($transaksi_ami->updated_at)->format('d-m-Y') ?? $tanggal;

        $totalResult = ($akreditasi_kode === 'LAMEMBA')
            ? $this->calculateTotalLamemeba($periode, $prodi, "{$akreditasi_kode} {$jenjang_nama}")
            : $this->calculateTotal($periode, $prodi, "{$akreditasi_kode} {$jenjang_nama}");

        $totalNilai  = $totalResult['total'];
        $prodiPrefix = $totalResult['prodiPrefix'];

        $html = view('pages.report.report-rtm', [
            'akreditasi'               => $akreditasi,
            'jenjang'                  => $jenjang,
            'standards'                => $standards,
            'periode'                  => $tahunAjaran,
            'prodi'                    => $jenjang_raw,
            'penjadwalan_ami'          => $penjadwalan_ami,
            'transaksi_ami'            => $transaksi_ami,
            'auditors'                 => $auditors,
            'tanggal'                  => $tanggal,
            'tanggal_audit'            => $tanggal_audit,
            'totalNilai'               => $totalNilai,
            'prodiPrefix'              => $prodiPrefix,
            'standards_sebelumnya'     => $standards_sebelumnya,
            'periodeSebelumnya'        => $tahunAjaranSebelumnya,
            'periodeSelanjutnya'       => $tahunAjaranSelanjutnya,
            'transaksi_ami_sebelumnya' => $transaksi_ami_sebelumnya,
        ])->render();

        $mpdf = new \Mpdf\Mpdf([
            'format'        => 'A4',
            'orientation'   => 'P',
            'margin_top'    => 10,
            'margin_bottom' => 15,
        ]);
        $mpdf->SetTitle('Hasil Rapat Tinjauan Manajemen');
        $mpdf->SetFooter('{DATE j-m-Y}||Page {PAGENO}');
        $mpdf->WriteHTML($html);
        $pdfContent = $mpdf->Output('', 'S');

        return response($pdfContent)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="hasil_rapat_tinjauan_manajemen.pdf"');
    }
}

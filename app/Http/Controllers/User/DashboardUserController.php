<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\FeederMahasiswa;
use App\Models\PenjadwalanAmi;
use App\Models\ProgramStudi;
use App\Models\StandarCapaian;
use App\Models\StandarTarget;
use App\Models\TransaksiAmi;
use App\Models\PengumumanData;
use App\Services\NeoFeeder\NeoFeederService;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardUserController extends Controller
{
    public function index()
    {
        $prodi = session('user_penempatan');
        $user_akses = session('user_akses');

        $currentDate = Carbon::now();
        $currentYear = $currentDate->year;
        $currentMonth = $currentDate->month;

        if ($currentMonth >= 7) {
            $startYear = $currentYear;
            $endYear = $currentYear + 1;
        } else {
            $startYear = $currentYear - 1;
            $endYear = $currentYear;
        }

        $periode = "$startYear/$endYear";

        $statusToProgress = [
            'Draft' => 0,
            'Diajukan' => 25,
            'Diterima' => 50,
            'Ditolak' => 0,
            'Koreksi' => 75,
            'Selesai' => 100,
        ];
        
        $prodi_prefix = substr($prodi, 0, 4);
        if ($prodi_prefix == 'S1 -' && $user_akses == 'BAN-PT') {
            $jenjang = 'BAN-PT S1';
        } else {
            $jenjang = 'Other Jenjang';
        }

        $totalTarget = StandarTarget::where('jenjang', $jenjang)->latest()->count();

        $totalCapaian = StandarCapaian::where('periode', $periode)
            ->where('prodi', $prodi)
            ->where('dokumen_kadaluarsa', '>', Carbon::now())
            ->latest()
            ->count();

        $totalKadaluarsa = StandarCapaian::where('periode', $periode)
            ->where('prodi', $prodi)
            ->where('dokumen_kadaluarsa', '<', Carbon::now())
            ->latest()
            ->count();
        
        $transaksi_ami = TransaksiAmi::where('periode', $periode)
            ->where('prodi', $prodi)
            ->latest()
            ->get();

        foreach ($transaksi_ami as $transaksi) {
            $transaksi->progress = $statusToProgress[$transaksi->status] ?? 0;
            if ($transaksi->progress <= 25) {
                $transaksi->progressColor = 'bg-danger';
            } elseif ($transaksi->progress <= 50) {
                $transaksi->progressColor = 'bg-warning';
            } elseif ($transaksi->progress <= 75) {
                $transaksi->progressColor = 'bg-info';
            } else {
                $transaksi->progressColor = 'bg-success';
            }
        }

        $jadwalAmi = PenjadwalanAmi::where('periode', $periode)
        ->where('prodi', $prodi)
        ->latest()
        ->get();

        foreach ($jadwalAmi as $item) {
            $item->formatted_opening_ami = $this->formatDateRange($item->opening_ami);
            $item->formatted_pengisian_dokumen = $this->formatDateRange($item->pengisian_dokumen);
            $item->formatted_deskevaluasion = $this->formatDateRange($item->deskevaluasion);
            $item->formatted_assessment = $this->formatDateRange($item->assessment);
            $item->formatted_tindakan_koreksi = $this->formatDateRange($item->tindakan_koreksi);
            $item->formatted_laporan_ami = $this->formatDateRange($item->laporan_ami);
            $item->formatted_rtm = $this->formatDateRange($item->rtm);
        }

        $pengumuman = PengumumanData::latest()->get();

        // Neo Feeder — filtered by prodi user
        $prodiModel = ProgramStudi::whereRaw("CONCAT(prodi_jenjang, ' - ', prodi_nama) = ?", [$prodi])
            ->whereNotNull('feeder_kode_prodi')
            ->first();

        $feederSynced = $prodiModel && FeederMahasiswa::where('prodi_kode', $prodiModel->feeder_kode_prodi)->exists();

        $feederData = null;
        if ($feederSynced) {
            $feeder      = (new NeoFeederService())->forProdi($prodiModel);
            $lastSync    = FeederMahasiswa::where('prodi_kode', $prodiModel->feeder_kode_prodi)->latest('synced_at')->value('synced_at');
            $feederData  = [
                'mahasiswa_aktif'   => $feeder->jumlahMahasiswaAktif(),
                'dpr'               => $feeder->jumlahDpr(),
                'dtt'               => $feeder->jumlahDtt(),
                'rasio'             => $feeder->rasioMahasiswaDosen(),
                'ipk_lulusan'       => $feeder->ipkRataRataLulusan(),
                'kelulusan_tepat'   => $feeder->kelulusanTepetWaktuPersen(),
                'is_fake'           => $feeder->isFakeMode(),
                'last_sync'         => $lastSync,
            ];
        }

        return view('pages.user.dashboard.index', [
            'periode'          => $periode,
            'totalTarget'      => $totalTarget,
            'totalCapaian'     => $totalCapaian,
            'totalKadaluarsa'  => $totalKadaluarsa,
            'transaksi_ami'    => $transaksi_ami,
            'jadwalAmi'        => $jadwalAmi,
            'pengumuman'       => $pengumuman,
            'feederData'       => $feederData,
            'feederSynced'     => $feederSynced,
            'prodiTerhubung'   => $prodiModel !== null,
        ]);
    }

    private function formatDateRange($dateRange)
    {
        $dates = explode(' to ', $dateRange);
        $formattedDates = array_map(function ($date) {
            return Carbon::parse($date)->isoFormat('D MMMM Y');
        }, $dates);

        return implode(' - ', $formattedDates);
    }

}

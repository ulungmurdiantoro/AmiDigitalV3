<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\PenjadwalanAmi;
use App\Models\StandarCapaian;
use App\Models\StandarTarget;
use App\Models\TransaksiAmi;
use App\Models\PengumumanData;
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

        return view('pages.user.dashboard.index', [
            'periode' => $periode,
            'totalTarget' => $totalTarget,
            'totalCapaian' => $totalCapaian,
            'totalKadaluarsa' => $totalKadaluarsa,
            'transaksi_ami' => $transaksi_ami,
            'jadwalAmi' => $jadwalAmi,
            'pengumuman' => $pengumuman,
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

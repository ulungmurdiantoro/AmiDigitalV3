<?php

namespace App\Http\Controllers\Auditor;

use App\Http\Controllers\Controller;
use App\Models\PengumumanData;
use App\Models\PenjadwalanAmi;
use App\Models\TransaksiAmi;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardAuditorController extends Controller
{
    public function index()
    {
        $user_kode = session('user_kode');

        $prodi = session('user_prodi');
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

        $count_diajukan = TransaksiAmi::where('status', 'Diajukan')
            ->whereHas('auditorAmi', function($query) use ($user_kode) {
                $query->where('users_kode', $user_kode);
            })
            ->count();

        $count_proses = TransaksiAmi::where('status', ['Diterima', 'Koreksi'])
            ->whereHas('auditorAmi', function($query) use ($user_kode) {
                $query->where('users_kode', $user_kode);
            })
            ->count();

        $count_selesai = TransaksiAmi::where('status', 'Selesai')
            ->whereHas('auditorAmi', function($query) use ($user_kode) {
                $query->where('users_kode', $user_kode);
            })
            ->count();

        $count_selesai = TransaksiAmi::where('status', 'Selesai')
            ->whereHas('auditorAmi', function($query) use ($user_kode) {
                $query->where('users_kode', $user_kode);
            })
            ->count();

        $jadwalAmi = PenjadwalanAmi::where('periode', $periode)
            ->whereHas('user', function($query) use ($user_kode) {
                $query->where('users_kode', $user_kode);
            })
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
            // dd($jadwalAmi);
        return view('pages.auditor.dashboard.index', compact('count_diajukan', 'count_proses', 'count_selesai', 'jadwalAmi', 'pengumuman'));
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

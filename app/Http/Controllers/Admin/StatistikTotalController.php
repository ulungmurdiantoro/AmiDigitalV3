<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StandarCapaian;
use App\Models\StandarElemenBanptS1;
use App\Models\StandarNilai;
use App\Models\PenjadwalanAmi;
use App\Models\TransaksiAmi;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Carbon\Carbon;

class StatistikTotalController extends Controller
{
    public function index()
    {
        $transaksi_ami = TransaksiAmi::with('auditorAmi')
            ->latest()
            ->paginate(10);

        foreach ($transaksi_ami as $item) {
            Carbon::setLocale('id');
            $item->formatted_created_at = Carbon::parse($item->created_at)->isoFormat('D MMMM Y');
        }

        return view('pages.admin.statistik-total.index', [
            'transaksi_ami' => $transaksi_ami,
        ]);
    }

    public function chartTotal(Request $request, $periode, $prodi)
    {
        $data_standar = StandarElemenBanptS1::with([
            'standarNilaiS1' => function ($query) use ($periode, $prodi) {
                $query->select('id', 'indikator_kode', 'hasil_nilai')
                    ->where('periode', $periode)
                    ->where('prodi', $prodi);
            }
        ])
        ->when($request->q, function ($query) use ($request) {
            $query->where('elemen_nama', 'like', '%' . $request->q . '%');
        })
        ->select('id', 'indikator_kode', 'elemen_nama')
        ->latest()
        ->get();

        // Debugging: Log the raw data_standar
        Log::info($data_standar);

        // Prepare data for the charts
        $categories = [];
        $averages = [];

        foreach ($data_standar as $standar) {
            $categories[] = $standar->indikator_kode;

            if ($standar->standarNilaiS1 && $standar->standarNilaiS1->count() > 0) {
                // Find the first matching standarNilaiS1 for the same indikator_kode
                $matchingNilai = $standar->standarNilaiS1->firstWhere('indikator_kode', $standar->indikator_kode);
                if ($matchingNilai) {
                    $averages[] = $matchingNilai->hasil_nilai;
                } else {
                    $averages[] = null;
                }
            } else {
                $averages[] = null;
            }
        }

        // Debugging: Log the processed categories and averages
        Log::info($categories);
        Log::info($averages);

        return view('pages.admin.statistik-total.chart-total.index', [
            'periode' => $periode,
            'prodi' => $prodi,
            'categories' => $categories,
            'averages' => $averages,
        ]);
    }

}

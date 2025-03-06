<?php

namespace App\Http\Controllers\Auditor;

use App\Http\Controllers\Controller;
use App\Models\StandarElemenBanptS1;
use App\Models\StandarElemenBanptD3;
use App\Models\StandarElemenLamdikS1;
use App\Models\TransaksiAmi;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AuditorStatistikTotalController extends Controller
{
    public function index()
    {
        $transaksi_ami = TransaksiAmi::whereHas('auditorAmi', function($query) {
            $query->where('users_kode', session('user_kode'));
        })
        ->where('status', 'Selesai')
        ->latest()
        ->get();

        foreach ($transaksi_ami as $item) {
            Carbon::setLocale('id');
            $item->formatted_created_at = Carbon::parse($item->created_at)->isoFormat('D MMMM Y');
        }

        return view('pages.auditor.statistik-total.index', [
            'transaksi_ami' => $transaksi_ami,
        ]);
    }

    public function chartTotal(Request $request, $periode, $prodi)
    {
        $transaksi_ami = TransaksiAmi::where('periode', $periode)
        ->where('prodi', $prodi)
        ->with('auditorAmi.user') 
        ->first();

        $akses = $transaksi_ami->standar_akreditasi;

        preg_match('/\b(S[0-9]+|D[0-9]+)\b/', $prodi, $matches);
        $degree = $matches[0] ?? 'S1'; 

        $key = trim($akses . ' ' . $degree);

        $degreeMappings = [
            'BAN-PT D3' => [
                'modelClass' => StandarElemenBanptD3::class,
                'standarNilaisRelation' => 'standarNilaisD3',
            ],
            'BAN-PT S1' => [
                'modelClass' => StandarElemenBanptS1::class,
                'standarNilaisRelation' => 'standarNilaisBanptS1',
            ],
            'LAMDIK S1' => [
                'modelClass' => StandarElemenLamdikS1::class,
                'standarNilaisRelation' => 'standarNilaisLamdikS1',
            ],
        ];

        if (!isset($degreeMappings[$key])) {
            Log::warning("Unknown degree key: {$key}, falling back to BAN-PT S1");
        }
        $degreeInfo = $degreeMappings[$key] ?? $degreeMappings['BAN-PT S1'];

        $modelClass = $degreeInfo['modelClass'];
        $standarNilaisRelation = $degreeInfo['standarNilaisRelation'];

        $data_standar = [];
        $degree = trim($degree);

        $data_standar = $modelClass::with([
            $standarNilaisRelation => function ($query) use ($periode, $prodi) {
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

        Log::info($data_standar);

        $categories = [];
        $averages = [];

        foreach ($data_standar as $standar) {
            $categories[] = $standar->indikator_kode;

            if ($standar->$standarNilaisRelation && $standar->$standarNilaisRelation->count() > 0) {
                $matchingNilai = $standar->$standarNilaisRelation->firstWhere('indikator_kode', $standar->indikator_kode);
                if ($matchingNilai) {
                    $averages[] = $matchingNilai->hasil_nilai;
                } else {
                    $averages[] = null;
                }
            } else {
                $averages[] = null;
            }
        }

        Log::info($categories);
        Log::info($averages);

        return view('pages.auditor.statistik-total.chart-total.index', [
            'periode' => $periode,
            'prodi' => $prodi,
            'categories' => $categories,
            'averages' => $averages,
            'key' => $key,
        ]);
    }
}

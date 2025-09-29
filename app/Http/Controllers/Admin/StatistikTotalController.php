<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StandarElemenBanptD3;
use App\Models\StandarElemenBanptS1;
use App\Models\StandarElemenBanptS2;
use App\Models\StandarElemenBanptS3;
use App\Models\StandarElemenBanptTerapanS1;
use App\Models\StandarElemenBanptTerapanS2;
use App\Models\StandarElemenBanptTerapanS3;
use App\Models\StandarElemenLamdikD3;
use App\Models\StandarElemenLamdikS1;
use App\Models\StandarElemenLamdikS2;
use App\Models\StandarElemenLamdikS3;
use App\Models\StandarElemenLamdikTerapanS1;
use App\Models\StandarElemenLamdikTerapanS2;
use App\Models\StandarElemenLamdikTerapanS3;
use App\Models\TransaksiAmi;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Carbon\Carbon;

class StatistikTotalController extends Controller
{
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

        return view('pages.admin.statistik-total.index', [
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

        preg_match('/\b(S[0-9]+(?: Terapan)?|D[0-9]+|PPG)\b/', $prodi, $matches);
        $degree = $matches[0] ?? 'PPG';

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
            'LAMDIK PPG' => [
                'modelClass' => StandarElemenLamdikD3::class,
                'standarNilaisRelation' => 'standarNilaisLamdikD3',
            ],
            'LAMDIK S1' => [
                'modelClass' => StandarElemenLamdikS1::class,
                'standarNilaisRelation' => 'standarNilaisLamdikS1',
            ],
            'LAMDIK S2' => [
                'modelClass' => StandarElemenLamdikS2::class,
                'standarNilaisRelation' => 'standarNilaisLamdikS2',
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
                $query->select('id', 'indikator_id', 'hasil_nilai')
                    ->where('periode', $periode)
                    ->where('prodi', $prodi);
            }
        ])
        ->when($request->q, function ($query) use ($request) {
            $query->where('elemen_nama', 'like', '%' . $request->q . '%');
        })
        ->select('id', 'indikator_id', 'elemen_nama')
        ->latest()
        ->get();

        Log::info($data_standar);

        $categories = [];
        $averages = [];

        foreach ($data_standar as $standar) {
            $categories[] = $standar->indikator_id;

            if ($standar->$standarNilaisRelation && $standar->$standarNilaisRelation->count() > 0) {
                $matchingNilai = $standar->$standarNilaisRelation->firstWhere('indikator_id', $standar->indikator_id);
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

        return view('pages.admin.statistik-total.chart-total.index', [
            'periode' => $periode,
            'prodi' => $prodi,
            'categories' => $categories,
            'averages' => $averages,
            'key' => $key,
        ]);
    }

}

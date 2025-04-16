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
use Illuminate\Http\Request;
use Carbon\Carbon;

class StatistikElemenController extends Controller
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

        return view('pages.admin.statistik-elemen.index', [
            'transaksi_ami' => $transaksi_ami,
        ]);
    }

    public function chartElemen(Request $request, $periode, $prodi)
    {
        $transaksi_ami = TransaksiAmi::where('periode', $periode)
        ->where('prodi', $prodi)
        ->with('auditorAmi.user') 
        ->first();

        $akses = $transaksi_ami->standar_akreditasi;

        preg_match('/\b(S[0-9]+(?: Terapan)?|D[0-9]+|PPG)\b/', $prodi, $matches);
        $degree = $matches[0] ?? 'PPG';

        $key = trim($akses . ' ' . $degree);

        $standar_names_banpt = [
            'Kondisi Eksternal',
            'Profil Unit Pengelola Program Studi',
            '1. Visi, Misi, Tujuan dan Strategi',
            '2. Tata Pamong dan Kerjasama',
            '3. Mahasiswa',
            '4. Sumber Daya Manusia',
            '5. Keuangan, Sarana dan Prasarana',
            '6. Pendidikan',
            '7. Penelitian',
            '8. Pengabdian Kepada Masyarakat',
            '9. Luaran dan Capaian Tridharma',
            'Analisis dan Penetapan Program Pengembangan'
        ];

        $standar_names_lamdik = [
            'Visi Keilmuan',
            'Tata Pamong dan Tata Kelola',
            'Mahasiswa',
            'Dosen dan Tenaga Kependidikan',
            'Keuangan, Sarana dan Prasarana Pendidikan',
            'Pendidikan',
            'Pengabdian Kepada Masyarakat',
            'Penjaminan Mutu',
        ];

        $short_standar_names_banpt = [
            'KE', 'P UPPS', 'STD 1', 'STD 2', 'STD 3', 'STD 4', 'STD 5', 'STD 6', 'STD 7', 'STD 8', 'STD 9', 'A PPP'
        ];

        $short_standar_names_lamdik = [
            'STD VK', 'STD TK', 'STD MHS', 'STD DTK', 'STD KEU', 'STD PDDK', 'STD PKM', 'STD PM'
        ];

        $degreeMappings = [
            'BAN-PT D3' => [
                'modelClass' => StandarElemenBanptD3::class,
                'standarNilaisRelation' => 'standarNilaisD3',
                'standarNames' => $standar_names_banpt,
                'shortStandarNames' => $short_standar_names_banpt,
            ],
            'BAN-PT S1' => [
                'modelClass' => StandarElemenBanptS1::class,
                'standarNilaisRelation' => 'standarNilaisBanptS1',
                'standarNames' => $standar_names_banpt,
                'shortStandarNames' => $short_standar_names_banpt,
            ],
            'LAMDIK PPG' => [
                'modelClass' => StandarElemenLamdikD3::class,
                'standarNilaisRelation' => 'standarNilaisLamdikD3',
                'standarNames' => $standar_names_lamdik,
                'shortStandarNames' => $short_standar_names_lamdik,
            ],
            'LAMDIK S1' => [
                'modelClass' => StandarElemenLamdikS1::class,
                'standarNilaisRelation' => 'standarNilaisLamdikS1',
                'standarNames' => $standar_names_lamdik,
                'shortStandarNames' => $short_standar_names_lamdik,
            ],
            'LAMDIK S2' => [
                'modelClass' => StandarElemenLamdikS2::class,
                'standarNilaisRelation' => 'standarNilaisLamdikS2',
                'standarNames' => $standar_names_lamdik,
                'shortStandarNames' => $short_standar_names_lamdik,
            ],
        ];

        if (!isset($degreeMappings[$key])) {
            Log::warning("Unknown degree key: {$key}, falling back to BAN-PT S1");
        }
        $degreeInfo = $degreeMappings[$key] ?? $degreeMappings['BAN-PT S1'];

        $modelClass = $degreeInfo['modelClass'];
        $standarNilaisRelation = $degreeInfo['standarNilaisRelation'];
        $standarNames = $degreeInfo['standarNames'];
        $shortStandarNames = $degreeInfo['shortStandarNames']; 

        $data_standar = [];
        $degree = trim($degree);
        $averages = [];
        $standar_data = []; 

        foreach ($standarNames as $index => $name) {
            $data = $modelClass::with([
                $standarNilaisRelation => function ($query) use ($periode, $prodi) {
                    $query->where('periode', $periode)
                        ->where('prodi', $prodi);
                }
            ])
            ->when($request->q, function ($query) use ($request) {
                $query->where('elemen_nama', 'like', '%' . $request->q . '%');
            })
            ->where('standar_nama', $name)
            ->latest()
            ->get();

            $data_standar['data_standar_k' . ($index + 1)] = $data;

            $totalScore = 0;
            $count = 0;

            foreach ($data as $item) {
                $nilai = $item->$standarNilaisRelation; 
                
                if ($nilai && $nilai->hasil_nilai !== null) {
                    $totalScore += $nilai->hasil_nilai;
                    $count++;
                }
            }
            
            $average = $count > 0 ? $totalScore / $count : 0;
            $averages[$name] = round($average, 2);
            
            $nilai_data = [];

            foreach ($data as $item) {
                $nilai = $item->$standarNilaisRelation;
                $nilai_data[] = $nilai && $nilai->hasil_nilai !== null ? $nilai->hasil_nilai : 0;
            }

            $standar_data[$index] = array_map(function($nilai) {
                return $nilai === null ? 0 : $nilai; 
            }, $nilai_data);
        }

        return view('pages.admin.statistik-elemen.chart-elemen.index', [
            'nama_data_standar' => $standarNames,
            'data_standar' => $data_standar,
            'periode' => $periode,
            'prodi' => $prodi,
            'averages' => $averages,
            'short_standar_names' => $shortStandarNames,
            'standar_data' => $standar_data 
        ]);
    }

}

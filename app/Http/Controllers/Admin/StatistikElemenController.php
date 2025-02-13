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

class StatistikElemenController extends Controller
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

        return view('pages.admin.statistik-elemen.index', [
            'transaksi_ami' => $transaksi_ami,
        ]);
    }

    public function chartElemen(Request $request, $periode, $prodi)
    {
        $standar_names = [
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
        
        $data_standar = [];
        $averages = [];
        $standar_data = []; // Initialize the array

        foreach ($standar_names as $index => $name) {
            $data = StandarElemenBanptS1::with([
                'standarTargetsS1',
                'standarCapaiansS1',
                'standarNilaiS1' => function ($query) use ($periode, $prodi) {
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
                $nilai = $item->standarNilaiS1; 
                
                if ($nilai && $nilai->hasil_nilai !== null) {
                    $totalScore += $nilai->hasil_nilai;
                    $count++;
                }
            }
            
            $average = $count > 0 ? $totalScore / $count : 0;
            $averages[$name] = round($average, 2);
            
            $nilai_data = [];

            foreach ($data as $item) {
                $nilai = $item->standarNilaiS1;
                $nilai_data[] = $nilai && $nilai->hasil_nilai !== null ? $nilai->hasil_nilai : 0;
            }

            $standar_data[$index] = array_map(function($nilai) {
                return $nilai === null ? 0 : $nilai; 
            }, $nilai_data);
        }
        
        $short_standar_names = [
            'KE', 'P UPPS', 'STD 1', 'STD 2', 'STD 3', 'STD 4', 'STD 5', 'STD 6', 'STD 7', 'STD 8', 'STD 9', 'A PPP'
        ];

        return view('pages.admin.statistik-elemen.chart-elemen.index', [
            'nama_data_standar' => $standar_names,
            'data_standar' => $data_standar,
            'periode' => $periode,
            'prodi' => $prodi,
            'averages' => $averages,
            'short_standar_names' => $short_standar_names,
            'standar_data' => $standar_data 
        ]);
    }


}

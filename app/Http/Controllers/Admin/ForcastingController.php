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

class ForcastingController extends Controller
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
        
        return view('pages.admin.forcasting.index', [
            'transaksi_ami' => $transaksi_ami,
        ]);
    }

    public function hasilForcasting(Request $request, $periode, $prodi)
    {
        $standarNilai = new StandarNilai();

        $criteriaStatus = $standarNilai->evaluateCriteria($periode, $prodi);

        $total = StandarNilai::calculateTotal($periode, $prodi);

        $tableTerakreditasi = [
            [
                'syarat' => 'Skor butir penilaian Penjaminan Mutu (keterlaksanaan Sistem Penjaminan Mutu Internal, akademik dan non akademik) ≥ 2,0. <br>Skor S1-12',
                'status' => $criteriaStatus['a1'],
            ],
            [
                'syarat' => 'Skor butir penilaian Kecukupan Jumlah DTPS ≥ 2,0. <br>Skor S1-17',
                'status' => $criteriaStatus['a2'],
            ],
            [
                'syarat' => 'Skor butir penilaian Kurikulum (keterlibatan pemangku kepentingan dalam proses evaluasi dan pemutakhiran kurikulum, kesesuaian capaian <br>
                            pembelajaran dengan profil lulusan dan jenjang KKNI/SKKNI, ketepatan struktur kurikulum dalam pembentukan capaian pembelajaran) ≥ 2,0. <br>Skor S1-38',
                'status' => $criteriaStatus['a3'],
            ],
        ];

        $tablePeringkatUnggul = [
            [
                'syarat' => 'Skor butir penilaian Kualifikasi Akademik DTPS (dosen tetap perguruan tinggi yang ditugaskan sebagai pengampu mata kuliah <br>
                            dengan bidang keahlian yang sesuai dengan kompetensi inti program studi yang diakreditasi) ≥ 3,5. <br>
                            Skor S1-18',
                'status' => $criteriaStatus['b1'],
            ],
            [
                'syarat' => 'Skor butir penilaian Jabatan Akademik DTPS (dosen tetap perguruan tinggi yang ditugaskan sebagai pengampu mata kuliah <br>
                            dengan bidang keahlian yang sesuai dengan kompetensi inti program studi yang diakreditasi) ≥ 3,5. <br>
                            Skor S1-19',
                'status' => $criteriaStatus['b2'],
            ],
            [
                'syarat' => 'Skor butir penilaian Waktu Tunggu ≥ 3,5. <br>
                            Skor S1-60',
                'status' => $criteriaStatus['b3'],
            ],
            [
                'syarat' => 'Skor butir penilaian Kesesuaian Bidang Kerja ≥ 3,5. <br>
                            Skor S1-61',
                'status' => $criteriaStatus['b4'],
            ],
        ];

        $tableBaikSekali = [
            [
                'syarat' => 'Skor butir penilaian Kualifikasi Akademik DTPS (dosen tetap perguruan tinggi yang ditugaskan sebagai pengampu mata kuliah <br>
                            dengan bidang keahlian yang sesuai dengan kompetensi inti program studi yang diakreditasi) ≥ 3,0. <br>
                            Skor S1-18',
                'status' => $criteriaStatus['c1'],
            ],
            [
                'syarat' => 'Skor butir penilaian Jabatan Akademik DTPS (dosen tetap perguruan tinggi yang ditugaskan sebagai pengampu mata kuliah <br>
                            dengan bidang keahlian yang sesuai dengan kompetensi inti program studi yang diakreditasi) ≥ 3,0. <br>
                            Skor S1-19',
                'status' => $criteriaStatus['c2'],
            ],
            [
                'syarat' => 'Skor butir penilaian Waktu Tunggu ≥ 3,0. <br>
                            Skor S1-60',
                'status' => $criteriaStatus['c3'],
            ],
            [
                'syarat' => 'Skor butir penilaian Kesesuaian Bidang Kerja ≥ 3,0. <br>
                            Skor S1-61',
                'status' => $criteriaStatus['c4'],
            ],
        ];

        $h2 = ($criteriaStatus['a1'] === 'Terpenuhi' && $criteriaStatus['a2'] === 'Terpenuhi' && $criteriaStatus['a3'] === 'Terpenuhi') ? 'Terpenuhi' : 'Tidak Terpenuhi';
        $h3 = ($criteriaStatus['b1'] === 'Terpenuhi' && $criteriaStatus['b2'] === 'Terpenuhi' && $criteriaStatus['b3'] === 'Terpenuhi' && $criteriaStatus['b4'] === 'Terpenuhi') ? 'Terpenuhi' : 'Tidak Terpenuhi';
        $h4 = ($criteriaStatus['c1'] === 'Terpenuhi' && $criteriaStatus['c2'] === 'Terpenuhi' && $criteriaStatus['c3'] === 'Terpenuhi' && $criteriaStatus['c4'] === 'Terpenuhi') ? 'Terpenuhi' : 'Tidak Terpenuhi';
        $h5 = ($h2 === 'Terpenuhi') ? 'Terakreditasi' : 'Tidak Terakreditasi';

        $h6 = '-';
        if ($total >= 361 && $h2 === 'Terpenuhi' && $h3 === 'Terpenuhi' && $h5 === 'Terakreditasi') {
            $h6 = 'Unggul';
        } else if ($total >= 361 && $h2 === 'Terpenuhi' && $h3 === 'Tidak Terpenuhi' && $h5 === 'Terakreditasi') {
            $h6 = 'Baik Sekali';
        } else if ($total >= 301 && $total < 361 && $h2 === 'Terpenuhi' && $h4 === 'Terpenuhi' && $h5 === 'Terakreditasi') {
            $h6 = 'Baik Sekali';
        } else if ($total >= 301 && $total < 361 && $h2 === 'Terpenuhi' && $h4 === 'Tidak Terpenuhi' && $h5 === 'Terakreditasi') {
            $h6 = 'Baik';
        } else if ($total >= 200 && $total < 301 && $h2 === 'Terpenuhi') {
            $h6 = 'Baik';
        }
        // dd($h2);

        return view('pages.admin.forcasting.hasil-forcasting.index', [
            'periode' => $periode,
            'prodi' => $prodi,
            'tableTerakreditasi' => $tableTerakreditasi,
            'tablePeringkatUnggul' => $tablePeringkatUnggul,
            'tableBaikSekali' => $tableBaikSekali,
            'total' => $total,
            'h2' => $h2,
            'h3' => $h3,
            'h4' => $h4,
            'h5' => $h5,
            'h6' => $h6,
        ]);
    }

}

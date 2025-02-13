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
use Mpdf\Mpdf;
use Carbon\Carbon;
class NilaiEvaluasiDiriController extends Controller
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

        return view('pages.admin.nilai-evaluasi-diri.index', [
            'transaksi_ami' => $transaksi_ami,
        ]);
    }

    
    public function rekapNilai(Request $request, $periode, $prodi)
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
        foreach ($standar_names as $index => $name) {
            $data_standar['data_standar_k' . ($index + 1)] = StandarElemenBanptS1::with(['standarTargetsS1', 'standarCapaiansS1', 'standarNilaiS1' => function ($query) use ($periode, $prodi) {
                $query->where('periode', $periode)
                        ->where('prodi', $prodi);
            }])
            ->when(request()->q, function ($query) {
                $query->where('elemen_nama', 'like', '%' . request()->q . '%');
            })
            ->where('standar_nama', $name)
            ->latest()
            ->paginate(30)
            ->appends(['q' => request()->q]);
        }
        
        $penjadwalan_ami = PenjadwalanAmi::with(['auditor_ami.user'])
            ->latest()
            ->get();
    
        $auditors = User::where('user_level', 'auditor')->get();

        $transaksi_ami = TransaksiAmi::where('periode', $periode)
            ->where('prodi', $prodi)
            ->with('auditorAmi.user')  // Eager load the auditorAmi relationship
            ->first();

        return view('pages.admin.nilai-evaluasi-diri.rekap-nilai.index', [
            'nama_data_standar' => $standar_names,
            'data_standar' => $data_standar,
            'periode' => $request->periode,
            'prodi' => $prodi,
            'penjadwalan_ami' => $penjadwalan_ami,
            'transaksi_ami' => $transaksi_ami,
            'auditors' => $auditors,
        ]);
    }

    public function reportLha(Request $request, $periode, $prodi)
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

        foreach ($standar_names as $index => $name) {
            $data_standar['data_standar_k' . ($index + 1)] = StandarElemenBanptS1::with([
                'standarTargetsS1',
                'standarCapaiansS1',
                'standarNilaiS1' => function ($query) use ($periode, $prodi) {
                    $query->where('periode', $periode)
                        ->where('prodi', $prodi);
                }
            ])
            ->where('standar_nama', $name)
            ->latest()
            ->get(); 
        }        

        $penjadwalan_ami = PenjadwalanAmi::with(['auditor_ami.user'])
            ->latest()
            ->get();

        $auditors = User::where('user_level', 'auditor')->get();

        $transaksi_ami = TransaksiAmi::where('periode', $periode)
            ->where('prodi', $prodi)
            ->with(['auditorAmi.user', 'penempatanUser']) 
            ->first();

        $prodiParts = explode(' - ', $prodi);
        $prodiPrefix = trim($prodiParts[0] ?? $prodi);

        $total = StandarNilai::calculateTotal($periode, $prodi);

        Carbon::setLocale('id');
        $tanggal = Carbon::now()->isoFormat('dddd, D MMMM Y');
        $tanggal_audit = $transaksi_ami->updated_at->isoFormat('dddd, D MMMM Y');

        $html = view('pages.report.report-lha', [
            'nama_data_standar' => $standar_names,
            'data_standar' => $data_standar,
            'periode' => $request->periode,
            'prodi' => $prodi,
            'penjadwalan_ami' => $penjadwalan_ami,
            'transaksi_ami' => $transaksi_ami,
            'auditors' => $auditors,
            'tanggal' => $tanggal, 
            'tanggal_audit' => $tanggal_audit, 
            'total' => $total,
            'prodiPrefix' => $prodiPrefix,
        ])->render();

        $mpdf = new Mpdf();

        $mpdf->WriteHTML($html);

        $mpdf->Output('rekap_nilai.pdf', 'I');
    }

    public function reportRtm(Request $request, $periode, $prodi)
    {
        // Calculate the previous and next periods
        list($startYear, $endYear) = explode('/', $periode);
        $previousStartYear = $startYear - 1;
        $previousEndYear = $endYear - 1;
        $previousPeriode = "{$previousStartYear}/{$previousEndYear}";

        $nextStartYear = $startYear + 1;
        $nextEndYear = $endYear + 1;
        $nextPeriode = "{$nextStartYear}/{$nextEndYear}";

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
        $previous_data_standar = [];

        foreach ($standar_names as $index => $name) {
            // Current period data
            $data_standar['data_standar_k' . ($index + 1)] = StandarElemenBanptS1::with([
                'standarTargetsS1',
                'standarCapaiansS1',
                'standarNilaiS1' => function ($query) use ($periode, $prodi) {
                    $query->where('periode', $periode)
                        ->where('prodi', $prodi);
                }
            ])
            ->where('standar_nama', $name)
            ->latest()
            ->get();
            
            // Previous period data
            $previous_data_standar['data_standar_k' . ($index + 1)] = StandarElemenBanptS1::with([
                'standarTargetsS1',
                'standarCapaiansS1',
                'standarNilaiS1' => function ($query) use ($previousPeriode, $prodi) {
                    $query->where('periode', $previousPeriode)
                        ->where('prodi', $prodi);
                }
            ])
            ->where('standar_nama', $name)
            ->latest()
            ->get();
        }

        $penjadwalan_ami = PenjadwalanAmi::with(['auditor_ami.user'])
            ->latest()
            ->get();

        $auditors = User::where('user_level', 'auditor')->get();

        $transaksi_ami = TransaksiAmi::where('periode', $periode)
            ->where('prodi', $prodi)
            ->with(['auditorAmi.user', 'penempatanUser'])
            ->first();

        $prodiParts = explode(' - ', $prodi);
        $prodiPrefix = trim($prodiParts[0] ?? $prodi);

        $total = StandarNilai::calculateTotal($periode, $prodi);

        Carbon::setLocale('id');
        $tanggal = Carbon::now()->isoFormat('dddd, D MMMM Y');
        $tanggal_audit = $transaksi_ami->updated_at->isoFormat('dddd, D MMMM Y');

        $html = view('pages.report.report-rtm', [
            'nama_data_standar' => $standar_names,
            'data_standar' => $data_standar,
            'previous_data_standar' => $previous_data_standar,
            'periode' => $request->periode,
            'previousPeriode' => $previousPeriode,
            'nextPeriode' => $nextPeriode,
            'prodi' => $prodi,
            'penjadwalan_ami' => $penjadwalan_ami,
            'transaksi_ami' => $transaksi_ami,
            'auditors' => $auditors,
            'tanggal' => $tanggal,
            'tanggal_audit' => $tanggal_audit,
            'total' => $total,
            'prodiPrefix' => $prodiPrefix,
        ])->render();

        $mpdf = new Mpdf();
        $mpdf->WriteHTML($html);
        $mpdf->Output('rekap_nilai.pdf', 'I');
    }

}

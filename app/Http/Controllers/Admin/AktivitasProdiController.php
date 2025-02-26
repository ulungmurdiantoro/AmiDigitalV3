<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TransaksiAmi;
use App\Models\StandarElemenBanptS1;
use App\Models\PenjadwalanAmi;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AktivitasProdiController extends Controller
{
    public function index()
    {
        $statusToProgress = [
            'Draft' => 0,
            'Diajukan' => 25,
            'Diterima' => 50,
            'Ditolak' => 0,
            'Koreksi' => 75,
            'Selesai' => 100,
        ];

        $transaksi_ami = TransaksiAmi::with('auditorAmi')
            ->latest()
            ->paginate(10);

        Carbon::setLocale('id');

        foreach ($transaksi_ami as $transaksi) {
            $transaksi->formatted_created_at = Carbon::parse($transaksi->created_at)->isoFormat('D MMMM Y');
            $transaksi->progress = $statusToProgress[$transaksi->status] ?? 0;
            $transaksi->progressColor = match (true) {
                $transaksi->progress <= 25 => 'bg-danger',
                $transaksi->progress <= 50 => 'bg-warning',
                $transaksi->progress <= 75 => 'bg-info',
                default => 'bg-success',
            };
        }
        
        return view('pages.admin.aktivitas-prodi.index', [
            'transaksi_ami' => $transaksi_ami,
        ]);
    }

    public function showPengajuan(Request $request, $periode, $prodi)
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
            $data_standar['data_standar_k' . ($index + 1)] = StandarElemenBanptS1::with(['standarTargetsBanptS1', 'standarCapaiansBanptS1', 'standarNilaisBanptS1' => function ($query) use ($periode, $prodi) {
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
            ->when($request->q, function($query) use ($request) {
                $query->whereHas('auditor_ami.user', function($q) use ($request) {
                    $q->where('user_nama', 'like', '%' . $request->q . '%');
                })
                ->orWhere('prodi_nama', 'like', '%' . $request->q . '%');
            })
            ->latest()
            ->get(); 
    
        $auditors = User::where('user_level', 'auditor')->get();

        $transaksi_ami = TransaksiAmi::where('periode', $periode)
            ->where('prodi', $prodi)
            ->with('auditorAmi.user')  // Eager load the auditorAmi relationship
            ->first();

            return view('pages.admin.aktivitas-prodi.show', [
                'nama_data_standar' => $standar_names,
                'data_standar' => $data_standar,
                'periode' => $request->periode,
                'prodi' => $prodi,
                'penjadwalan_ami' => $penjadwalan_ami,
                'transaksi_ami' => $transaksi_ami,
                'auditors' => $auditors,
            ]);
    }

}

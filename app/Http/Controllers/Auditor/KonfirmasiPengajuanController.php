<?php

namespace App\Http\Controllers\Auditor;

use App\Http\Controllers\Controller;
use App\Models\KategoriDokumen;
use App\Models\StandarCapaian;
use App\Models\StandarElemenBanptS1;
use App\Models\StandarNilai;
use App\Models\PenjadwalanAmi;
use App\Models\TransaksiAmi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class KonfirmasiPengajuanController extends Controller
{
    public function index()
    {
        $Pengajuan = TransaksiAmi::when(request()->q, function($Pengajuan) {
            $Pengajuan = $Pengajuan->where('status', 'like', '%'. request()->q . '%');
        })->latest()->paginate(10);

        //append query string to pagination links
        $Pengajuan->appends(['q' => request()->q]);

        return view('pages.auditor.konfirmasi-pengajuan.index', [
            'Pengajuan' => $Pengajuan,
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

            return view('pages.auditor.konfirmasi-pengajuan.show', [
                'nama_data_standar' => $standar_names,
                'data_standar' => $data_standar,
                'periode' => $request->periode,
                'prodi' => $prodi,
                'penjadwalan_ami' => $penjadwalan_ami,
                'transaksi_ami' => $transaksi_ami,
                'auditors' => $auditors,
            ]);
    }

    public function show($id)
    {
        $pengajuan = TransaksiAmi::findOrFail($id);
        return view('pages.auditor.konfirmasi-pengajuan.show', compact('pengajuan'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'id' => 'required',
            'status' => 'required',
        ]);

        $pengajuan = TransaksiAmi::findOrFail($request->id);
        $pengajuan->status = $request->status; // Update status to "Diterima"
        $pengajuan->save();

        return redirect()->back()->with('success', 'Pengajuan updated successfully.');
    }

}

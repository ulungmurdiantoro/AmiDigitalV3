<?php

namespace App\Http\Controllers\Auditor;

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

class EvaluasiAmiAuditorController extends Controller
{
    public function index()
    {
        $data_kesiapan = StandarCapaian::with('standarCapaiansS1')
            ->select('periode', 'prodi')
            ->groupBy('periode', 'prodi')
            ->latest()
            ->paginate(10);

        return view('pages.auditor.evaluasi-ami.index', [
            'data_kesiapan' => $data_kesiapan,
        ]);
    }

    public function auditAmi(Request $request, $periode, $prodi)
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

        return view('pages.auditor.evaluasi-ami.audit-ami.index', [
            'nama_data_standar' => $standar_names,
            'data_standar' => $data_standar,
            'periode' => $request->periode,
            'prodi' => $prodi,
            'penjadwalan_ami' => $penjadwalan_ami,
            'transaksi_ami' => $transaksi_ami,
            'auditors' => $auditors,
        ]);
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'ami_kodes' => 'required|string',
            'indikator_kodes' => 'required|string',
            'hasil_nilais' => 'required|numeric|min:0|max:4',
            'hasil_kriterias' => 'nullable|string',
            'hasil_deskripsis' => 'nullable|string',
            'jenis_temuans' => 'required|string',
            'hasil_akibats' => 'nullable|string',
            'hasil_masalahs' => 'nullable|string',
            'hasil_rekomendasis' => 'nullable|string',
        ]);

        $transkasi = StandarNilai::where('ami_kode', $request->ami_kodes)->first();
        $standard = StandarNilai::where('indikator_kode', $request->indikator_kodes)->first();

        if ($transkasi && $standard) {
            $standard->standarNilaiS1()->where('id', $standard->id)->update([
                'hasil_nilai' => $request->hasil_nilais,
                'hasil_kriteria' => $request->hasil_kriterias,
                'hasil_deskripsi' => $request->hasil_deskripsis,
                'jenis_temuan' => $request->jenis_temuans,
                'hasil_akibat' => $request->hasil_akibats,
                'hasil_masalah' => $request->hasil_masalahs,
                'hasil_rekomendasi' => $request->hasil_rekomendasis,
            ]);
        }

        return redirect()->back()->with('success', 'Data berhasil diperbarui!');
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'id' => 'required',
            'status' => 'required',
        ]);

        $pengajuan = TransaksiAmi::findOrFail($id);
        $pengajuan->status = $request->status;
        $pengajuan->save();

        return redirect()->route('auditor.evaluasi-ami.index')->with('success', 'Pengajuan updated successfully.');
    }

    public function destroy($id)
    {
        //
    }
}

<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
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

class PengajuanAmiUserController extends Controller
{
    public function index()
    {
        $prodi = Session::get('user_penempatan');

        $data_kesiapan = StandarCapaian::with('standarCapaiansS1')
            ->select('periode', 'prodi')
            ->where('prodi', $prodi)
            ->groupBy('periode', 'prodi')
            ->latest()
            ->paginate(10);

        return view('pages.user.pengajuan-ami.index', [
            'data_kesiapan' => $data_kesiapan,
        ]);
    }

    public function inputAmi(Request $request, $periode, $prodi)
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
            $data_standar['data_standar_k' . ($index + 1)] = StandarElemenBanptS1::with(['standarTargetsS1', 'standarCapaiansS1' => function ($query) use ($periode, $prodi) {
                $query->where('periode', $periode);
                $query->where('prodi', $prodi);
            }])
            ->when(request()->q, function ($query) {
                $query->where('elemen_nama', 'like', '%' . request()->q . '%');
            })
            ->latest()
            ->get(); // Changed from paginate() to get()
        }
        
        // Fetch all data from penjadwalan_ami table without pagination
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

        if ($transaksi_ami) {
            return view('pages.user.pengajuan-ami.input-ami.index', [
                'nama_data_standar' => $standar_names,
                'data_standar' => $data_standar,
                'periode' => $request->periode,
                'prodi' => $prodi,
                'penjadwalan_ami' => $penjadwalan_ami,
                'transaksi_ami' => $transaksi_ami,
                'auditors' => $auditors,
            ]);
        } else {
            return view('pages.user.pengajuan-ami.input-ami.empty', [
                'nama_data_standar' => $standar_names,
                'data_standar' => $data_standar,
                'periode' => $request->periode,
                'prodi' => $prodi,
                'penjadwalan_ami' => $penjadwalan_ami,
                'transaksi_ami' => $transaksi_ami,
                'auditors' => $auditors
            ]);
        }
    }

    public function inputAmiStore(Request $request)
    {
        // Validate the incoming request data
        $validator = Validator::make($request->all(), [
            'periode' => 'required|string',
            'prodi' => 'required|string',
            'indikator_kode' => 'required|string',
            'nilai_mandiri' => 'required|numeric|min:0|max:4',
            'ami_kode' => 'required|string' // Ensure ami_kode is required
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Find the record based on periode, prodi, and indikator_kode
        $record = StandarNilai::where('periode', $request->periode)
            ->where('prodi', $request->prodi)
            ->where('indikator_kode', $request->indikator_kode)
            ->first();

        // If record exists, update it
        if ($record) {
            $record->nilai_mandiri = $request->nilai_mandiri;
            $record->save();
        } else {
            // If record does not exist, create a new one
            StandarNilai::create([
                'periode' => $request->periode,
                'prodi' => $request->prodi,
                'indikator_kode' => $request->indikator_kode,
                'nilai_mandiri' => $request->nilai_mandiri,
                'ami_kode' => $request->ami_kode, // Include ami_kode
            ]);
        }

        return redirect()->back()->with('success', 'Data saved successfully!');
    }


    public function store(Request $request)
    {
        // Validate request data
        $request->validate([
            'periode' => 'required|string|max:255',
            'status' => 'required|string|max:255',
        ]);
    
        // Get the session data
        $prodi = session('user_penempatan');
        $fakultas = session('user_fakultas');
        $standar_akreditasi = session('user_akses');
    
        // Insert data into TransaksiAmi table
        TransaksiAmi::create([
            'ami_kode' => 'ami-' . Str::uuid() . uniqid(),
            'auditor_kode' => $request->auditor_kode,
            'prodi' => $prodi,
            'fakultas' => $fakultas,
            'standar_akreditasi' => $standar_akreditasi,
            'periode' => $request->periode,
            'status' => $request->status, // Setting status to "Draft"
        ]);
    
        // Redirect or return response
        return redirect()->back()->with('success', 'Data Pengajuan AMI successfully submitted.');
    }

}

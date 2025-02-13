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
class KoreksiAmiUserController extends Controller
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

        return view('pages.user.koreksi-ami.index', [
            'data_kesiapan' => $data_kesiapan,
        ]);
    }

    public function revisiProdi(Request $request, $periode, $prodi)
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
                            // ->where('jenis_temuan', '!=', 'Sesuai');
                }
            ])
            ->when(request()->q, function ($query) {
                $query->where('elemen_nama', 'like', '%' . request()->q . '%');
            })
            ->where('standar_nama', $name)
            ->whereDoesntHave('standarNilaiS1', function ($query) use ($periode, $prodi) {
                $query->where('periode', $periode)
                        ->where('prodi', $prodi)
                        ->where('jenis_temuan', 'Sesuai');
            })
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

        return view('pages.user.koreksi-ami.revisi-prodi.index', [
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
        // Log incoming request data
        Log::info('Incoming Data:', $request->all());

        // Validate the input data
        $validatedData = $request->validate([
            'ami_kodes' => 'required|string|max:255',
            'indikator_kodes' => 'required|string|max:255',
            'indikator_bobots' => 'required',
            'prodis' => 'required|string|max:255',
            'periodes' => 'required|string|max:255',
            'hasil_rencana_perbaikan' => 'required|string|max:255',
            'hasil_jadwal_perbaikan' => 'required|string|max:255',
            'hasil_perbaikan_penanggung' => 'required|string|max:255',
            'hasil_rencana_pencegahan' => 'required|string|max:255',
            'hasil_jadwal_pencegahan' => 'required|string|max:255',
            'hasil_rencana_penanggung' => 'required|string|max:255',
        ]);

        try {
            // Check if the record exists based on the unique combination of indikator_kode, periode, and prodi
            $amiInput = StandarNilai::where('indikator_kode', $validatedData['indikator_kodes'])
                ->where('periode', $validatedData['periodes'])
                ->where('prodi', $validatedData['prodis'])
                ->first();

            if ($amiInput) {
                // Update existing record
                $amiInput->hasil_rencana_perbaikan = $validatedData['hasil_rencana_perbaikan'];
                $amiInput->hasil_jadwal_perbaikan = $validatedData['hasil_jadwal_perbaikan'];
                $amiInput->hasil_perbaikan_penanggung = $validatedData['hasil_perbaikan_penanggung'];
                $amiInput->hasil_rencana_pencegahan = $validatedData['hasil_rencana_pencegahan'];
                $amiInput->hasil_jadwal_pencegahan = $validatedData['hasil_jadwal_pencegahan'];
                $amiInput->hasil_rencana_penanggung = $validatedData['hasil_rencana_penanggung'];

                if ($amiInput->save()) {
                    Log::info('Data updated successfully:', $amiInput->toArray());
                    return redirect()->back()->with('success', 'Data successfully updated!');
                } else {
                    Log::error('Failed to update data.');
                    return redirect()->back()->with('error', 'Failed to update data.');
                }
            } else {
                // Create a new record
                $amiInput = new StandarNilai();
                $amiInput->ami_kode = $validatedData['ami_kodes'];
                $amiInput->indikator_kode = $validatedData['indikator_kodes'];
                $amiInput->bobot = $validatedData['indikator_bobots'];
                $amiInput->prodi = $validatedData['prodis'];
                $amiInput->periode = $validatedData['periodes'];
                $amiInput->hasil_rencana_perbaikan = $validatedData['hasil_rencana_perbaikan'];
                $amiInput->hasil_jadwal_perbaikan = $validatedData['hasil_jadwal_perbaikan'];
                $amiInput->hasil_perbaikan_penanggung = $validatedData['hasil_perbaikan_penanggung'];
                $amiInput->hasil_rencana_pencegahan = $validatedData['hasil_rencana_pencegahan'];
                $amiInput->hasil_jadwal_pencegahan = $validatedData['hasil_jadwal_pencegahan'];
                $amiInput->hasil_rencana_penanggung = $validatedData['hasil_rencana_penanggung'];

                if ($amiInput->save()) {
                    Log::info('Data saved successfully:', $amiInput->toArray());
                    return redirect()->back()->with('success', 'Data successfully saved!');
                } else {
                    Log::error('Failed to save data.');
                    return redirect()->back()->with('error', 'Failed to save data.');
                }
            }
        } catch (\Exception $e) {
            // Log the error for debugging
            Log::error('Error saving or updating data:', ['message' => $e->getMessage()]);
            return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}

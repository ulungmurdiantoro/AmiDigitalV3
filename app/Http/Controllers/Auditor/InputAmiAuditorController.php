<?php

namespace App\Http\Controllers\Auditor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
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
use App\Models\StandarTarget;
use App\Imports\StandarBanptS1Import;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class InputAmiAuditorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $nama_data_standar_k1 = 'Kondisi Eksternal';
        $nama_data_standar_k2 = 'Profil Unit Pengelola Program Studi';
        $nama_data_standar_k3 = 'Kriteria 1. Visi, Misi, Tujuan dan Strategi';
        $nama_data_standar_k4 = 'Kriteria 2. Tata Pamong dan Kerjasama';
        $nama_data_standar_k5 = 'Kriteria 3. Mahasiswa';
        $nama_data_standar_k6 = 'Kriteria 4. Sumber Daya Manusia';
        $nama_data_standar_k7 = 'Kriteria 5. Keuangan, Sarana dan Prasarana';
        $nama_data_standar_k8 = 'Kriteria 6. Pendidikan';
        $nama_data_standar_k9 = 'Kriteria 7. Penelitian';
        $nama_data_standar_k10 = 'Kriteria 8. Pengabdian Kepada Masyarakat';
        $nama_data_standar_k11 = 'Kriteria 9. Luaran dan Capaian Tridharma';
        $nama_data_standar_k12 = 'Analisis dan Penetapan Program Pengembangan';
        // dd($nama_data_standar_k1);
        $data_standar_k1 = StandarElemenBanptS1::when(request()->q, function($query) {
            $query->where('elemen_nama', 'like', '%' . request()->q . '%');
        })->where('standar_nama', $nama_data_standar_k1)->latest()->paginate(30);
        
        $data_standar_k2 = StandarElemenBanptS1::when(request()->q, function($query) {
            $query->where('elemen_nama', 'like', '%' . request()->q . '%');
        })->where('standar_nama', 'Profil Unit Pengelola Program Studi')->latest()->paginate(30);
        
        $data_standar_k3 = StandarElemenBanptS1::when(request()->q, function($query) {
            $query->where('elemen_nama', 'like', '%' . request()->q . '%');
        })->where('standar_nama', '1. Visi, Misi, Tujuan dan Strategi')->latest()->paginate(30);
        
        $data_standar_k4 = StandarElemenBanptS1::when(request()->q, function($query) {
            $query->where('elemen_nama', 'like', '%' . request()->q . '%');
        })->where('standar_nama', '2. Tata Pamong dan Kerjasama')->latest()->paginate(30);
        
        $data_standar_k5 = StandarElemenBanptS1::when(request()->q, function($query) {
            $query->where('elemen_nama', 'like', '%' . request()->q . '%');
        })->where('standar_nama', '3. Mahasiswa')->latest()->paginate(30);
        
        $data_standar_k6 = StandarElemenBanptS1::when(request()->q, function($query) {
            $query->where('elemen_nama', 'like', '%' . request()->q . '%');
        })->where('standar_nama', '4. Sumber Daya Manusia')->latest()->paginate(30);
        
        $data_standar_k7 = StandarElemenBanptS1::when(request()->q, function($query) {
            $query->where('elemen_nama', 'like', '%' . request()->q . '%');
        })->where('standar_nama', '5. Keuangan, Sarana dan Prasarana')->latest()->paginate(30);
        
        $data_standar_k8 = StandarElemenBanptS1::when(request()->q, function($query) {
            $query->where('elemen_nama', 'like', '%' . request()->q . '%');
        })->where('standar_nama', '6. Pendidikan')->latest()->paginate(30);
        
        $data_standar_k9 = StandarElemenBanptS1::when(request()->q, function($query) {
            $query->where('elemen_nama', 'like', '%' . request()->q . '%');
        })->where('standar_nama', '7. Penelitian')->latest()->paginate(30);
        
        $data_standar_k10 = StandarElemenBanptS1::when(request()->q, function($query) {
            $query->where('elemen_nama', 'like', '%' . request()->q . '%');
        })->where('standar_nama', '8. Pengabdian Kepada Masyarakat')->latest()->paginate(30);
        
        $data_standar_k11 = StandarElemenBanptS1::when(request()->q, function($query) {
            $query->where('elemen_nama', 'like', '%' . request()->q . '%');
        })->where('standar_nama', '9. Luaran dan Capaian Tridharma')->latest()->paginate(30);
        
        $data_standar_k12 = StandarElemenBanptS1::when(request()->q, function($query) {
            $query->where('elemen_nama', 'like', '%' . request()->q . '%');
        })->where('standar_nama', 'Analisis dan Penetapan Program Pengembangan')->latest()->paginate(30);
        
        $data_standar_k1->appends(['q' => request()->q]);
        $data_standar_k2->appends(['q' => request()->q]);
        $data_standar_k3->appends(['q' => request()->q]);
        $data_standar_k4->appends(['q' => request()->q]);
        $data_standar_k5->appends(['q' => request()->q]);
        $data_standar_k6->appends(['q' => request()->q]);
        $data_standar_k7->appends(['q' => request()->q]);
        $data_standar_k8->appends(['q' => request()->q]);
        $data_standar_k9->appends(['q' => request()->q]);
        $data_standar_k10->appends(['q' => request()->q]);
        $data_standar_k11->appends(['q' => request()->q]);
        $data_standar_k12->appends(['q' => request()->q]);
        
        return view('pages.auditor.input-ami.index', [
            'nama_data_standar_k1' => $nama_data_standar_k1,
            'nama_data_standar_k2' => $nama_data_standar_k2, 
            'nama_data_standar_k3' => $nama_data_standar_k3,
            'nama_data_standar_k4' => $nama_data_standar_k4,
            'nama_data_standar_k5' => $nama_data_standar_k5,
            'nama_data_standar_k6' => $nama_data_standar_k6,
            'nama_data_standar_k7' => $nama_data_standar_k7,
            'nama_data_standar_k8' => $nama_data_standar_k8,
            'nama_data_standar_k9' => $nama_data_standar_k9,
            'nama_data_standar_k10' => $nama_data_standar_k10,
            'nama_data_standar_k11' => $nama_data_standar_k11,
            'nama_data_standar_k12' => $nama_data_standar_k12,
            'data_standar_k1' => $data_standar_k1,
            'data_standar_k2' => $data_standar_k2,
            'data_standar_k3' => $data_standar_k3,
            'data_standar_k4' => $data_standar_k4,
            'data_standar_k5' => $data_standar_k5,
            'data_standar_k6' => $data_standar_k6,
            'data_standar_k7' => $data_standar_k7,
            'data_standar_k8' => $data_standar_k8,
            'data_standar_k9' => $data_standar_k9,
            'data_standar_k10' => $data_standar_k10,
            'data_standar_k11' => $data_standar_k11,
            'data_standar_k12' => $data_standar_k12,
        ]);
    }

    // public function nilaiAmi($indikator_id)
    // {
    //     // Your logic for handling the request goes here
    //     // For example, you could retrieve some data based on the $indikator_id
    //     // and return a view or JSON response.

    //     // Example:
    //     // $data = YourModel::where('indikator_id', $indikator_id)->first();
    //     // return view('your_view', compact('data'));

    //     return view('pages.auditor.input-ami.nilai-ami.index');
    // }
    public function nilaiAmi(Request $request, $indikator_id)
    {
        // Validate if indikator_id exists
        $standarElemen = StandarElemenBanptS1::where('indikator_id', $indikator_id)->firstOrFail();

        // Fetch StandarTarget data
        $standarTarget = StandarTarget::where('indikator_id', $indikator_id)->when($request->q, function ($query, $q) {
            $query->where('id', 'like', "%{$q}%"); // Update the field if needed
        })->latest()->paginate(10);

        // Return the view
        return view('pages.auditor.input-ami.nilai-ami.index', [
            'indikator_id' => $indikator_id,
            'standarTarget' => $standarTarget,
            'standarElemen' => $standarElemen,
        ]);
    }

    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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

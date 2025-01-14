<?php

namespace App\Http\Controllers\User;

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
use App\Models\StandarCapaian;
use App\Models\DokumenTipe;
use App\Models\StandarTarget;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PemenuhanDokumenController extends Controller
{
    public function index()
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
            $data_standar['data_standar_k' . ($index + 1)] = StandarElemenBanptS1::with('standarTargetsS1', 'standarCapaiansS1')
            ->when(request()->q, function ($query) {
                $query->where('elemen_nama', 'like', '%' . request()->q . '%');
            })->where('standar_nama', $name)->latest()->paginate(30)->appends(['q' => request()->q]);
        }
        
        return view('pages.user.pemenuhan-dokumen.index', [
            'nama_data_standar' => $standar_names,
            'data_standar' => $data_standar
        ]);
        
    }

    public function pemenuhanDokumen(Request $request, $indikator_kode)
    {
        // Validate if indikator_kode exists
        $standarElemen = StandarElemenBanptS1::where('indikator_kode', $indikator_kode)->firstOrFail();

        // Fetch StandarTarget data
        $standarCapaian = StandarCapaian::where('indikator_kode', $indikator_kode)
        ->when($request->q, function ($query, $q) {
            $query->where('id', 'like', "%{$q}%"); // Update the field if needed
        })->latest()->paginate(10);

        // Return the view
        return view('pages.user.pemenuhan-dokumen.input-capaian.index', [
            'indikator_kode' => $indikator_kode,
            'standarCapaian' => $standarCapaian,
            'standarElemen' => $standarElemen,
        ]);
    }

    public function pemenuhanDokumenCreate(Request $request, $indikator_kode)
    {
        // Validate if indikator_kode exists
        $standarElemen = StandarElemenBanptS1::where('indikator_kode', $indikator_kode)->firstOrFail();
        $standarTargets = StandarTarget::where('indikator_kode', $indikator_kode)->get();


        return view('pages.user.pemenuhan-dokumen.input-capaian.create', [
            'indikator_kode' => $indikator_kode,
            'standarElemen' => $standarElemen,
            'standarTargets' => $standarTargets,
        ]);
    }

    public function pemenuhanDokumenStore(Request $request)
    {
        // Validate the request data
        $request->validate([
            'indikator_kode' => 'required|string',
            'dokumen_nama' => 'required|string',
            'pertanyaan_nama' => 'required|string',
            'dokumen_tipe' => 'required|string',
            'dokumen_keterangan' => 'nullable|string',
            'dokumen_file' => 'required|file|mimes:pdf,doc,docx,xlsx,png,jpg,jpeg',
            'periode' => 'required|string',
            'dokumen_kadaluarsa' => 'required|date',
            'informasi' => 'nullable|string',
        ]);
        
        try {
            $fileName = time() . '.' . $request->dokumen_file->extension();
            $filePath = $request->file('dokumen_file')->storeAs('uploads/capaian/prodi', $fileName, 'public');
        } catch (\Exception $e) {
            Log::error('File upload failed: ' . $e->getMessage());
            return back()->withErrors(['dokumen_file' => 'File upload failed. Please try again.']);
        }

        StandarCapaian::create([
            'capaian_kode' => 'cpn-' . Str::uuid() . uniqid(),
            'indikator_kode' => $request->input('indikator_kode'),
            'dokumen_nama' => $request->input('dokumen_nama'),
            'pertanyaan_nama' => $request->input('pertanyaan_nama'),
            'dokumen_tipe' => $request->input('dokumen_tipe'),
            'dokumen_keterangan' => $request->input('dokumen_keterangan'),
            'dokumen_file' => '/storage/' . $filePath, // Save the file path
            'periode' => $request->input('periode'),
            'dokumen_kadaluarsa' => $request->input('dokumen_kadaluarsa'),
            'informasi' => $request->input('informasi'),
            'prodi' => session('user_penempatan'),
        ]);

        // Redirect back with a success message
        return redirect()->route('user.pemenuhan-dokumen.input-capaian', ['indikator_kode' => $request->indikator_kode])
        ->with([
            'success' => 'Tipe Dokumen created successfully.',
        ]);    
    }

    public function pemenuhanDokumenEdit($id)
    {
        $standarCapaian = StandarCapaian::findOrFail($id);

        $indikator_kode = $standarCapaian->indikator_kode;

        // Pass the data to the view
        return view('pages.user.pemenuhan-dokumen.input-capaian.edit', [
            'standarCapaian' => $standarCapaian,
            'indikator_kode' => $indikator_kode,
        ]);    
    }

    public function pemenuhanDokumenUpdate(Request $request, $id)
    {
        $request->validate([
            'dokumen_file' => 'file|mimes:pdf,doc,docx,xlsx,xls|max:2048',
            'periode' => 'required|string',
            'dokumen_kadaluarsa' => 'required|date',
            'informasi' => 'nullable|string',
        ]);

        $standarCapaian = StandarCapaian::findOrFail($id);
        $standarCapaian->periode = $request->periode;
        $standarCapaian->dokumen_kadaluarsa = $request->dokumen_kadaluarsa;
        $standarCapaian->informasi = $request->informasi;

        if ($request->hasFile('dokumen_file')) {
            // Delete old file if it exists
            if ($standarCapaian->dokumen_file) {
                Storage::disk('public')->delete($standarCapaian->dokumen_file);
            }

            // Store the new file
            $fileName = time() . '.' . $request->dokumen_file->extension();
            $filePath = $request->file('dokumen_file')->storeAs('uploads/capaian/prodi', $fileName, 'public');
            $standarCapaian->dokumen_file = '/storage/' . $filePath;
        }

        $standarCapaian->save();

        return redirect()->route('user.pemenuhan-dokumen.input-capaian', ['indikator_kode' => $request->indikator_kode])
        ->with([
            'success' => 'Tipe Dokumen updated successfully.',
        ]);    
    }

    public function pemenuhanDokumenDestroy($id)
    {
        $standarCapaian = StandarCapaian::findOrFail($id);

        // Delete the file from storage if it exists
        if ($standarCapaian->dokumen_file) {
            Storage::disk('public')->delete($standarCapaian->dokumen_file);
        }

        // Get indikator_kode before deleting the record
        $indikator_kode = $standarCapaian->indikator_kode;

        // Delete the document record
        $standarCapaian->delete();

        return redirect()->route('user.pemenuhan-dokumen.input-capaian', ['indikator_kode' => $indikator_kode])
            ->with([
                'success' => 'Tipe Dokumen deleted successfully.',
            ]);
    }

}

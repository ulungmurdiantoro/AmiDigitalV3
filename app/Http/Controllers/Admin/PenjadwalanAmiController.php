<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\ProgramStudi;
use App\Models\PenjadwalanAmi;
use App\Models\AuditorAmi;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class PenjadwalanAmiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Query for PenjadwalanAmi with related auditor_ami and user data 
        $PenjadwalanAmis = PenjadwalanAmi::with(['auditor_ami.user']) 
            ->when($request->q, function($query) use ($request) { 
                $query->whereHas('auditor_ami.user', function($q) use ($request) { 
                    $q->where('user_nama', 'like', '%' . $request->q . '%'); 
                }) 
                ->orWhere('prodi_nama', 'like', '%' . $request->q . '%'); 
            }) 
            ->latest() 
            ->paginate(10); 
        
        // Append query string to pagination links 
        $PenjadwalanAmis->appends(['q' => $request->q]); 
        
        // Query for Users with user_level 'auditor' 
        $auditors = User::where('user_level', 'auditor')->get(); 
        
        // Return view with the data 
        return view('pages.admin.penjadwalan-ami.index', [ 
            'PenjadwalanAmis' => $PenjadwalanAmis, 
            'auditors' => $auditors,
        ]);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $users = User::where('user_level', 'user')->get();
        $auditors = User::where('user_level', 'auditor')->get();
        return view('pages.admin.penjadwalan-ami.create', [
            'users' => $users, 
            'auditors' => $auditors, 
        ]);
    }

    public function store(Request $request)
    {
        // Validate request
        $request->validate([
            'prodi' => 'required|string',
            'fakultas' => 'required|string',
            'standar_akreditasi' => 'required|string',
            'auditor_kode' => 'required|string',
            'opening_ami' => 'required',
            'pengisian_dokumen' => 'required',
            'deskevaluasion' => 'required',
            'assessment' => 'required',
            'tindakan_koreksi' => 'required',
            'laporan_ami' => 'required',
            'rtm' => 'required',
        ]);

        try {
            $jadwalKode = 'jdw-ami-' . Str::uuid() . uniqid(); 
            $auditorKode = 'adtr-' . Str::uuid() . uniqid();

            PenjadwalanAmi::create([
                'jadwal_kode' => $jadwalKode,
                'auditor_kode' => $auditorKode,
                'prodi' => $request->prodi,
                'fakultas' => $request->fakultas,
                'standar_akreditasi' => $request->standar_akreditasi,
                'periode' => $request->periode,
                'opening_ami' => $request->opening_ami,
                'pengisian_dokumen' => $request->pengisian_dokumen,
                'deskevaluasion' => $request->deskevaluasion,
                'assessment' => $request->assessment,
                'tindakan_koreksi' => $request->tindakan_koreksi,
                'laporan_ami' => $request->laporan_ami,
                'rtm' => $request->rtm,
            ]);
            AuditorAmi::create([
                'auditor_kode' => $auditorKode,
                'users_kode' => $request->auditor_kode,
                'tim_ami' => 'Ketua',
            ]);
        } catch (\Exception $e) {
            Log::error('Database insertion failed: ' . $e->getMessage());
            return back()->withErrors(['database' => 'Failed to save data. Please try again.']);
        }

        // Redirect
        return redirect()->route('admin.penjadwalan-ami.index');
    }

    public function storeauditor(Request $request)
    {
        // Validate request
        $request->validate([ 
            'auditor_kode' => 'required', 
            'auditorName' => 'required', 
            'tim_ami' => 'required', 
        ]); 

        try {
            AuditorAmi::create([
                'auditor_kode' => $request->auditor_kode,
                'users_kode' => $request->auditorName,
                'tim_ami' => $request->tim_ami,
            ]);
        } catch (\Exception $e) {
            Log::error('Database insertion failed: ' . $e->getMessage());
            return back()->withErrors(['database' => 'Failed to save data. Please try again.']);
        }

        // Redirect
        return redirect()->route('admin.penjadwalan-ami.index');
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
        $users = User::where('user_level', 'user')->get();
        $auditors = User::where('user_level', 'auditor')->get();
        $penjadwalan_ami = PenjadwalanAmi::findOrFail($id);
        
        return view('pages.admin.penjadwalan-ami.edit', compact('users', 'auditors', 'penjadwalan_ami'));
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
    // Validate the request data
    $request->validate([
        'prodi' => 'required',
        'auditor_kode' => 'required',
        'periode' => 'required',
        'opening_ami' => 'required|date',
        'pengisian_dokumen' => 'required|date',
        'deskevaluasion' => 'required|date',
        'assessment' => 'required|date',
        'tindakan_koreksi' => 'required|date',
        'laporan_ami' => 'required|date',
        'rtm' => 'required|date',
    ]);

    // Find the schedule by ID
    $penjadwalan_ami = PenjadwalanAmi::findOrFail($id);

    // Update the schedule with the request data
    $penjadwalan_ami->update([
        'prodi' => $request->input('prodi'),
        'fakultas' => $request->input('fakultas'),
        'standar_akreditasi' => $request->input('standar_akreditasi'),
        'auditor_kode' => $request->input('auditor_kode'),
        'periode' => $request->input('periode'),
        'opening_ami' => $request->input('opening_ami'),
        'pengisian_dokumen' => $request->input('pengisian_dokumen'),
        'deskevaluasion' => $request->input('deskevaluasion'),
        'assessment' => $request->input('assessment'),
        'tindakan_koreksi' => $request->input('tindakan_koreksi'),
        'laporan_ami' => $request->input('laporan_ami'),
        'rtm' => $request->input('rtm'),
    ]);

    // Redirect or return response
    return redirect()->route('admin.penjadwalan-ami.index')->with('success', 'Penjadwalan AMI berhasil diupdate.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $penjadwalanAmi = PenjadwalanAmi::findOrFail($id);
            $penjadwalanAmi->delete();
            
            return redirect()->route('admin.penjadwalan-ami.index')->with('success', 'Penjadwalan AMI berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('admin.penjadwalan-ami.index')->with('error', 'Terjadi kesalahan saat menghapus Penjadwalan AMI.');
        }
    }

    public function destroyAuditor(Request $request) {
        // Validate the request 
        $request->validate([ 
            'auditorName' => 'required' 
        ]); 
        // Find the auditor by code and delete 
        $auditor = AuditorAmi::where('users_kode', $request->auditorName)->first(); 
        if ($auditor) { 
            $auditor->delete(); 
            
            return redirect()->back()->with('success', 'Auditor berhasil dihapus.'); 
        } else { 
            return redirect()->back()->with('error', 'Auditor tidak ditemukan.'); 
        }
    }
}

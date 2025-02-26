<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProgramStudi;
use App\Models\TransaksiAmi;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardAdminController extends Controller
{
    public function index()
    {
        $currentDate = Carbon::now();
        $currentYear = $currentDate->year;
        $currentMonth = $currentDate->month;

        if ($currentMonth >= 7) {
            $startYear = $currentYear;
            $endYear = $currentYear + 1;
        } else {
            $startYear = $currentYear - 1;
            $endYear = $currentYear;
        }

        $periode = "$startYear/$endYear";

        $penggunaAdmin = User::where('user_level', 'admin')->latest()->count();
        $penggunaProdi = User::where('user_level', 'user')->latest()->count();
        $penggunaAuditor = User::where('user_level', 'auditor')->latest()->count();

        $prodiS1 = ProgramStudi::where('prodi_jenjang', 'S1')->latest()->count();
        $prodiS2 = ProgramStudi::where('prodi_jenjang', 'S2')->latest()->count();
        $prodiS3 = ProgramStudi::where('prodi_jenjang', 'S3')->latest()->count();
        $prodiD3 = ProgramStudi::where('prodi_jenjang', 'D3')->latest()->count();
        $prodiS1T = ProgramStudi::where('prodi_jenjang', 'S1 Terapan')->latest()->count();
        $prodiS2T = ProgramStudi::where('prodi_jenjang', 'S2 Terapan')->latest()->count();
        $prodiS3T = ProgramStudi::where('prodi_jenjang', 'S3 Terapan')->latest()->count();
        $prodiPPG = ProgramStudi::where('prodi_jenjang', 'PPG')->latest()->count();

        $Diajukanami = TransaksiAmi::where('status', 'Diajukan')->where('periode', $periode)->latest()->count();
        $Diterimaami = TransaksiAmi::where('status', 'Diterima')->where('periode', $periode)->latest()->count();
        $Koreksiami = TransaksiAmi::where('status', 'Koreksi')->where('periode', $periode)->latest()->count();
        $Selesaiami = TransaksiAmi::where('status', 'Selesai')->where('periode', $periode)->latest()->count();
        
        $amiD3Diajukan = TransaksiAmi::where('status', 'Diajukan')->where('periode', $periode)->where('prodi', 'like', 'D3 -%')->latest()->count();
        $amiS1Diajukan = TransaksiAmi::where('status', 'Diajukan')->where('periode', $periode)->where('prodi', 'like', 'S1 -%')->latest()->count();
        $amiS2Diajukan = TransaksiAmi::where('status', 'Diajukan')->where('periode', $periode)->where('prodi', 'like', 'S2 -%')->latest()->count();
        $amiS3Diajukan = TransaksiAmi::where('status', 'Diajukan')->where('periode', $periode)->where('prodi', 'like', 'S3 -%')->latest()->count();
        $amiS1TDiajukan = TransaksiAmi::where('status', 'Diajukan')->where('periode', $periode)->where('prodi', 'like', 'S1T -%')->latest()->count();
        $amiS2TDiajukan = TransaksiAmi::where('status', 'Diajukan')->where('periode', $periode)->where('prodi', 'like', 'S2T -%')->latest()->count();
        $amiS3TDiajukan = TransaksiAmi::where('status', 'Diajukan')->where('periode', $periode)->where('prodi', 'like', 'S3T -%')->latest()->count();
        $amiPPGDiajukan = TransaksiAmi::where('status', 'Diajukan')->where('periode', $periode)->where('prodi', 'like', 'PPG -%')->latest()->count();
    
        $amiD3Diterima = TransaksiAmi::where('status', 'Diterima')->where('periode', $periode)->where('prodi', 'like', 'D3 -%')->latest()->count();
        $amiS1Diterima = TransaksiAmi::where('status', 'Diterima')->where('periode', $periode)->where('prodi', 'like', 'S1 -%')->latest()->count();
        $amiS2Diterima = TransaksiAmi::where('status', 'Diterima')->where('periode', $periode)->where('prodi', 'like', 'S2 -%')->latest()->count();
        $amiS3Diterima = TransaksiAmi::where('status', 'Diterima')->where('periode', $periode)->where('prodi', 'like', 'S3 -%')->latest()->count();
        $amiS1TDiterima = TransaksiAmi::where('status', 'Diterima')->where('periode', $periode)->where('prodi', 'like', 'S1T -%')->latest()->count();
        $amiS2TDiterima = TransaksiAmi::where('status', 'Diterima')->where('periode', $periode)->where('prodi', 'like', 'S2T -%')->latest()->count();
        $amiS3TDiterima = TransaksiAmi::where('status', 'Diterima')->where('periode', $periode)->where('prodi', 'like', 'S3T -%')->latest()->count();
        $amiPPGDiterima = TransaksiAmi::where('status', 'Diterima')->where('periode', $periode)->where('prodi', 'like', 'PPG -%')->latest()->count();
    
        $amiD3Koreksi = TransaksiAmi::where('status', 'Koreksi')->where('periode', $periode)->where('prodi', 'like', 'D3 -%')->latest()->count();
        $amiS1Koreksi = TransaksiAmi::where('status', 'Koreksi')->where('periode', $periode)->where('prodi', 'like', 'S1 -%')->latest()->count();
        $amiS2Koreksi = TransaksiAmi::where('status', 'Koreksi')->where('periode', $periode)->where('prodi', 'like', 'S2 -%')->latest()->count();
        $amiS3Koreksi = TransaksiAmi::where('status', 'Koreksi')->where('periode', $periode)->where('prodi', 'like', 'S3 -%')->latest()->count();
        $amiS1TKoreksi = TransaksiAmi::where('status', 'Koreksi')->where('periode', $periode)->where('prodi', 'like', 'S1T -%')->latest()->count();
        $amiS2TKoreksi = TransaksiAmi::where('status', 'Koreksi')->where('periode', $periode)->where('prodi', 'like', 'S2T -%')->latest()->count();
        $amiS3TKoreksi = TransaksiAmi::where('status', 'Koreksi')->where('periode', $periode)->where('prodi', 'like', 'S3T -%')->latest()->count();
        $amiPPGKoreksi = TransaksiAmi::where('status', 'Koreksi')->where('periode', $periode)->where('prodi', 'like', 'PPG -%')->latest()->count();
    
        $amiD3Selesai = TransaksiAmi::where('status', 'Selesai')->where('periode', $periode)->where('prodi', 'like', 'D3 -%')->latest()->count();
        $amiS1Selesai = TransaksiAmi::where('status', 'Selesai')->where('periode', $periode)->where('prodi', 'like', 'S1 -%')->latest()->count();
        $amiS2Selesai = TransaksiAmi::where('status', 'Selesai')->where('periode', $periode)->where('prodi', 'like', 'S2 -%')->latest()->count();
        $amiS3Selesai = TransaksiAmi::where('status', 'Selesai')->where('periode', $periode)->where('prodi', 'like', 'S3 -%')->latest()->count();
        $amiS1TSelesai = TransaksiAmi::where('status', 'Selesai')->where('periode', $periode)->where('prodi', 'like', 'S1T -%')->latest()->count();
        $amiS2TSelesai = TransaksiAmi::where('status', 'Selesai')->where('periode', $periode)->where('prodi', 'like', 'S2T -%')->latest()->count();
        $amiS3TSelesai = TransaksiAmi::where('status', 'Selesai')->where('periode', $periode)->where('prodi', 'like', 'S3T -%')->latest()->count();
        $amiPPGSelesai = TransaksiAmi::where('status', 'Selesai')->where('periode', $periode)->where('prodi', 'like', 'PPG -%')->latest()->count();
    

        return view('pages.admin.dashboard.index', [
            'periode' => $periode,
            'penggunaAdmin' => $penggunaAdmin,
            'penggunaProdi' => $penggunaProdi,
            'penggunaAuditor' => $penggunaAuditor,
            'prodiS1' => $prodiS1,
            'prodiS2' => $prodiS2,
            'prodiS3' => $prodiS3,
            'prodiD3' => $prodiD3,
            'prodiS1T' => $prodiS1T,
            'prodiS2T' => $prodiS2T,
            'prodiS3T' => $prodiS3T,
            'prodiPPG' => $prodiPPG,
            'Diajukanami' => $Diajukanami,
            'Diterimaami' => $Diterimaami,
            'Koreksiami' => $Koreksiami,
            'Selesaiami' => $Selesaiami,
            'amiD3Diajukan' => $amiD3Diajukan,
            'amiS1Diajukan' =>$amiS1Diajukan,
            'amiS2Diajukan' =>$amiS2Diajukan,
            'amiS3Diajukan' =>$amiS3Diajukan,
            'amiS1TDiajukan' =>$amiS1TDiajukan, 
            'amiS2TDiajukan' =>$amiS2TDiajukan,
            'amiS3TDiajukan' =>$amiS3TDiajukan,
            'amiPPGDiajukan' =>$amiPPGDiajukan,
            'amiD3Diterima' => $amiD3Diterima,
            'amiS1Diterima' =>$amiS1Diterima,
            'amiS2Diterima' =>$amiS2Diterima,
            'amiS3Diterima' =>$amiS3Diterima,
            'amiS1TDiterima' =>$amiS1TDiterima, 
            'amiS2TDiterima' =>$amiS2TDiterima,
            'amiS3TDiterima' =>$amiS3TDiterima,
            'amiPPGDiterima' =>$amiPPGDiterima,
            'amiD3Koreksi' => $amiD3Koreksi,
            'amiS1Koreksi' =>$amiS1Koreksi,
            'amiS2Koreksi' =>$amiS2Koreksi,
            'amiS3Koreksi' =>$amiS3Koreksi,
            'amiS1TKoreksi' =>$amiS1TKoreksi, 
            'amiS2TKoreksi' =>$amiS2TKoreksi,
            'amiS3TKoreksi' =>$amiS3TKoreksi,
            'amiPPGKoreksi' =>$amiPPGKoreksi,
            'amiD3Selesai' => $amiD3Selesai,
            'amiS1Selesai' =>$amiS1Selesai,
            'amiS2Selesai' =>$amiS2Selesai,
            'amiS3Selesai' =>$amiS3Selesai,
            'amiS1TSelesai' =>$amiS1TSelesai, 
            'amiS2TSelesai' =>$amiS2TSelesai,
            'amiS3TSelesai' =>$amiS3TSelesai,
            'amiPPGSelesai' =>$amiPPGSelesai,
        ]);
    }
}

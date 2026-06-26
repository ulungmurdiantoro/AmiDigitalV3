<?php

namespace App\Http\Controllers;

use App\Models\ProgramStudi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function showLoginForm()
    {
        return view('pages.auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('username', 'password');

        if (Auth::attempt($credentials)) {
            $authUser = Auth::user();

            $userAkses = $authUser->user_akses;
            if ($authUser->user_level === 'user' && $authUser->user_penempatan) {
                $prodi = ProgramStudi::whereRaw(
                    "CONCAT(prodi_jenjang, ' - ', prodi_nama) = ?",
                    [$authUser->user_penempatan]
                )->first();
                if ($prodi && $prodi->standar_akreditasi) {
                    $userAkses = $prodi->standar_akreditasi;
                }
            }

            session([
                'user_id' => $authUser->user_id,
                'user_nama' => $authUser->user_nama,
                'user_kode' => $authUser->users_code,
                'user_jabatan' => $authUser->user_jabatan,
                'user_penempatan' => $authUser->user_penempatan,
                'user_akses' => $userAkses,
                'user_fakultas' => $authUser->user_fakultas,
                'user_level' => $authUser->user_level,
            ]);

            $role = Auth::user()->user_level;
            if ($role == 'admin') {
                return redirect()->route('admin.dashboard.index');
            } elseif ($role == 'user') {
                return redirect()->route('user.dashboard.index');
            } elseif ($role == 'auditor') {
                return redirect()->route('auditor.dashboard.index');
            }
        }

        return redirect()->back()->withErrors(['login' => 'Invalid credentials']);
    }

    public function logout()
    {
        Auth::logout();
        session()->flush(); // Clear all session data
        return redirect()->route('login');
    }

    public function showAdminDashboard()
    {
        return view('admin.dashboard');
    }

    public function showUserDashboard()
    {
        return view('user.dashboard');
    }

    public function showAuditorDashboard()
    {
        return view('auditor.dashboard');
    }
}


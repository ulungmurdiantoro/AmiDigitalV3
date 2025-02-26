<?php

namespace App\Http\Controllers;

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
            // Save data to session
            session([
                'user_id' => Auth::user()->user_id,
                'user_nama' => Auth::user()->user_nama,
                'user_jabatan' => Auth::user()->user_jabatan,
                'user_penempatan' => Auth::user()->user_penempatan,
                'user_akses' => Auth::user()->user_akses,
                'user_fakultas' => Auth::user()->user_fakultas,
                'user_akses' => Auth::user()->user_akses,
                'user_level' => Auth::user()->user_level,
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


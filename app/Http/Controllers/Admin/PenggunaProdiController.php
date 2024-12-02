<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\ProgramStudi;
use App\Models\StandarAkreditasi;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash; 
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PenggunaProdiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::where('user_level', 'user') // Always filter by user_level
            ->when(request()->q, function ($query) {
                return $query->where('user_nama', 'like', '%' . request()->q . '%');
            })
            ->latest()
            ->paginate(10);

        return view('pages.admin.pengguna-prodi.index', [
            'users' => $users,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $StandarAkreditasis = StandarAkreditasi::all();
        $ProgramStudis = ProgramStudi::all();
        return view('pages.admin.pengguna-prodi.create', [
            'StandarAkreditasis' => $StandarAkreditasis,
            'ProgramStudis' => $ProgramStudis,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validate the incoming request data
        $request->validate([
            'user_id' => 'required|string', // Ensures NIP/NIK is unique
            'user_nama' => 'required|string|max:255',
            'user_jabatan' => 'required|string|max:255',
            'user_penempatan' => 'required|string', // Program Study responsibility
            'user_fakultas' => 'required|string', // Program Study responsibility
            'user_akses' => 'required|string', // Access rights based on accreditation standard
            'username' => 'required|string|unique:users', // Username must be unique
             // Require at least 8 characters for the password
        ]);

        try {
            // Store user data into the database
            User::create([
                'users_code' => 'usr-' .Str::uuid() . uniqid(),
                'user_id' => $request->user_id,
                'user_nama' => $request->user_nama,
                'user_jabatan' => $request->user_jabatan,
                'user_penempatan' => $request->user_penempatan, // Prodi responsibility (jenjang - nama)
                'user_fakultas' => $request->user_fakultas, // Prodi responsibility (jenjang - nama)
                'user_akses' => $request->user_akses, // Standard access rights
                'username' => $request->username,
                'password' => Hash::make($request->password), // Hashing the password before saving
                'user_level' => 'user',
                'user_status' => 'aktif',
            ]);

        } catch (\Exception $e) {
            // Log the error and return back with a message
            Log::error('Error creating pengguna prodi: ' . $e->getMessage());
            return back()->withErrors(['database' => 'Failed to save data. Please try again.']);
        }

        // Redirect to a specific route with a success message
        return redirect()->route('admin.pengguna-prodi.index') // Change to where you want to redirect
                        ->with('success', 'Pengguna Prodi successfully created!');
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
        $StandarAkreditasis = StandarAkreditasi::all();
        $ProgramStudis = ProgramStudi::all();
        $users = User::findOrFail($id);
        return view('pages.admin.pengguna-prodi.edit', compact('users', 'StandarAkreditasis', 'ProgramStudis'));
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
        $request->validate([
            'user_id' => 'required|string', // Ensures NIP/NIK is unique
            'user_nama' => 'required|string|max:255',
            'user_jabatan' => 'required|string|max:255',
            'user_penempatan' => 'required|string', // Program Study responsibility
            'user_fakultas' => 'required|string', // Program Study responsibility
            'user_akses' => 'required|string', // Access rights based on accreditation standard
            'username' => 'required|string', // Username must be unique
            'password' => '', // Username must be unique
        ]);

        try {
            // Find the user record by ID
            $user = User::findOrFail($id);

            // Update user data in the database
            $user->update([
                'user_id' => $request->user_id,
                'user_nama' => $request->user_nama,
                'user_jabatan' => $request->user_jabatan,
                'user_penempatan' => $request->user_penempatan, // Prodi responsibility (jenjang - nama)
                'user_fakultas' => $request->user_fakultas, // Prodi responsibility (jenjang - nama)
                'user_akses' => $request->user_akses, // Standard access rights
                'username' => $request->username,
                'password' => $request->password ? Hash::make($request->password) : $user->password, // Hash password only if it is provided
                'user_level' => 'user', // Adjust if user level can be changed
                'user_status' => 'aktif', // Adjust if user status can change
            ]);

        } catch (\Exception $e) {
            // Log the error and return back with a message
            Log::error('Error updating pengguna prodi: ' . $e->getMessage());
            return back()->withErrors(['database' => 'Failed to update data. Please try again.']);
        }

        // Redirect to a specific route with a success message
        return redirect()->route('admin.pengguna-prodi.index') // Change to where you want to redirect
                        ->with('success', 'Pengguna Prodi successfully updated!');
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
            $user = User::findOrFail($id);
            $user->delete();
            
            return redirect()->route('admin.pengguna-prodi.index')->with('success', 'Program Studi deleted successfully');
        } catch (\Exception $e) {
            return redirect()->route('admin.pengguna-prodi.index')->with('error', 'There was an error deleting the Program Studi');
        }
    }
}

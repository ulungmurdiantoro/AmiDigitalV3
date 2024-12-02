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

class PenggunaAuditorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::where('user_level', 'auditor') // Always filter by user_level
            ->when(request()->q, function ($query) {
                return $query->where('user_nama', 'like', '%' . request()->q . '%');
            })
            ->latest()
            ->paginate(10);

        return view('pages.admin.pengguna-auditor.index', [
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
        return view('pages.admin.pengguna-auditor.create', [
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
            'user_id' => 'required|string|unique:users', // Ensures user_id is unique
            'user_nama' => 'required|string|max:255',
            'user_jabatan' => 'required|string|max:255',
            'user_pelatihan' => 'required|string|max:255',
            'user_sertfikat' => 'mimes:jpg,png,pdf,doc,docx|max:2048', // Sertifikat file validation
            'user_sk' => 'mimes:jpg,png,pdf,doc,docx|max:2048', // SK file validation
            'username' => 'required|string|unique:users', // Ensure username is unique
            'password' => 'required|string|min:8', // Minimum password length
        ]);

        // File uploads
        $sertifikatPath = $this->uploadFile($request->file('user_sertfikat'), 'uploads/user/sertifikat');
        if (!$sertifikatPath) {
            return back()->withErrors(['user_sertfikat' => 'File upload failed. Please try again.']);
        }

        $skPath = $this->uploadFile($request->file('user_sk'), 'uploads/user/sk');
        if (!$skPath) {
            return back()->withErrors(['user_sk' => 'File upload failed. Please try again.']);
        }

        try {
            // Store user data into the database
            User::create([
                'users_code' => 'usr-' . Str::uuid() . uniqid(),
                'user_id' => $request->user_id,
                'user_nama' => $request->user_nama,
                'user_jabatan' => $request->user_jabatan,
                'user_penempatan' => 'Auditor',
                'user_pelatihan' => $request->user_pelatihan,
                'user_sertfikat' => $sertifikatPath,
                'user_sk' => $skPath,
                'username' => $request->username,
                'password' => Hash::make($request->password), // Hash password before saving
                'user_level' => 'auditor',
                'user_status' => 'aktif',
            ]);

        } catch (\Exception $e) {
            // Log the error and return back with a message
            Log::error('Error creating pengguna auditor: ' . $e->getMessage());
            return back()->withErrors(['database' => 'Failed to save data. Please try again.']);
        }

        // Redirect to a specific route with a success message
        return redirect()->route('admin.pengguna-auditor.index')
                        ->with('success', 'Pengguna auditor successfully created!');
    }

    /**
     * Helper function to handle file uploads
     */
    private function uploadFile($file, $directory)
    {
        try {
            $fileName = uniqid() . '.' . $file->getClientOriginalExtension();
            $filePath = $file->storeAs($directory, $fileName, 'public');
            return '/storage/' . $filePath;
        } catch (\Exception $e) {
            Log::error('File upload failed: ' . $e->getMessage());
            return false;
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
        $StandarAkreditasis = StandarAkreditasi::all();
        $ProgramStudis = ProgramStudi::all();
        $users = User::findOrFail($id);
        return view('pages.admin.pengguna-auditor.edit', [
            'StandarAkreditasis' => $StandarAkreditasis,
            'ProgramStudis' => $ProgramStudis,
            'users' => $users,
        ]);
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
            'user_id' => 'required|string|unique:users,user_id,' . $id, // Ensure user_id is unique, ignoring current user
            'user_nama' => 'required|string|max:255',
            'user_jabatan' => 'required|string|max:255',
            'user_pelatihan' => 'required|string|max:255',
            'user_sertfikat' => '', // Optional file validation
            'user_sk' => '', // Optional file validation
            'username' => 'required|string|unique:users,username,' . $id, // Ensure username is unique, ignoring current user
            'password' => '', // Password is optional during update
        ]);

        try {
            // Find the user record by ID
            $user = User::findOrFail($id);

            // Handle file uploads
            $sertifikatPath = $user->user_sertfikat;
            if ($request->hasFile('user_sertfikat')) {
                $sertifikatPath = $this->uploadFile($request->file('user_sertfikat'), 'uploads/user/sertifikat');
                if (!$sertifikatPath) {
                    return back()->withErrors(['user_sertfikat' => 'File upload failed. Please try again.']);
                }
            }

            $skPath = $user->user_sk;
            if ($request->hasFile('user_sk')) {
                $skPath = $this->uploadFile($request->file('user_sk'), 'uploads/user/sk');
                if (!$skPath) {
                    return back()->withErrors(['user_sk' => 'File upload failed. Please try again.']);
                }
            }

            // Update user data in the database
            $user->update([
                'user_id' => $request->user_id,
                'user_nama' => $request->user_nama,
                'user_jabatan' => $request->user_jabatan,
                'user_penempatan' => 'Auditor',
                'user_pelatihan' => $request->user_pelatihan,
                'user_sertfikat' => $sertifikatPath,
                'user_sk' => $skPath,
                'username' => $request->username,
                'password' => $request->password ? Hash::make($request->password) : $user->password, // Hash password if provided
                'user_level' => 'auditor', // Adjust if user level can change
                'user_status' => 'aktif', // Adjust if user status can change
            ]);

        } catch (\Exception $e) {
            // Log the error and return back with a message
            Log::error('Error updating pengguna auditor: ' . $e->getMessage());
            return back()->withErrors(['database' => 'Failed to update data. Please try again.']);
        }

        // Redirect to a specific route with a success message
        return redirect()->route('admin.pengguna-auditor.index')
                        ->with('success', 'Pengguna auditor successfully updated!');
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
            
            return redirect()->route('admin.pengguna-auditor.index')->with('success', 'Program Studi deleted successfully');
        } catch (\Exception $e) {
            return redirect()->route('admin.pengguna-auditor.index')->with('error', 'There was an error deleting the Program Studi');
        }
    }
}

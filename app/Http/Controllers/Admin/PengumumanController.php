<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PengumumanData;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PengumumanController extends Controller
{
    public function index()
    {
        $pengumuman = PengumumanData::latest()->get();

        return view('pages.admin.pengumuman.index', [
            'pengumuman' => $pengumuman,
        ]);
    }

    public function create()
    {
        return view('pages.admin.pengumuman.create');
    }

    public function store(Request $request)
    {
         // Validate the form data
        $request->validate([
            'pengumuman_judul' => 'required|string|max:255',
            'pengumuman_informasi' => 'required|string',
        ]);

        // Create a new Pengumuman instance
        $pengumuman = new PengumumanData();
        $pengumuman->pengumuman_kode = 'pngg-' . Str::uuid();
        $pengumuman->pengumuman_judul = $request->input('pengumuman_judul');
        $pengumuman->pengumuman_informasi = $request->input('pengumuman_informasi');
        $pengumuman->save();

        // Redirect or return a response
        return redirect()->route('admin.pengumuman.index')->with('success', 'Pengumuman berhasil dibuat!');
    }

    public function edit($id)
    {
        $pengumuman = PengumumanData::findOrFail($id);
        return view('pages.admin.pengumuman.edit', compact('pengumuman'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'pengumuman_judul' => 'required|string|max:255',
            'pengumuman_informasi' => 'required|string',
        ]);

        // Find the Pengumuman instance by ID
        $pengumuman = PengumumanData::findOrFail($id);
        $pengumuman->pengumuman_judul = $request->input('pengumuman_judul');
        $pengumuman->pengumuman_informasi = $request->input('pengumuman_informasi');
        $pengumuman->save();

        // Redirect or return a response
        return redirect()->route('admin.pengumuman.index')->with('success', 'Pengumuman berhasil diperbarui!');
    }

    public function destroy($id)
    {
        // Find the Pengumuman instance by ID
        $pengumuman = PengumumanData::findOrFail($id);
        $pengumuman->delete();

        // Redirect or return a response
        return redirect()->route('admin.pengumuman.index')->with('success', 'Pengumuman berhasil dihapus!');
    }

}

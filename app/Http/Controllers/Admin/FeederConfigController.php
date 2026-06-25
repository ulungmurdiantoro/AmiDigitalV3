<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FeederConfig;
use App\Services\NeoFeeder\NeoFeederService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class FeederConfigController extends Controller
{
    public function show()
    {
        $config = FeederConfig::instance();
        return view('pages.admin.feeder-config.show', compact('config'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'feeder_url'      => 'required|url|max:255',
            'feeder_username' => 'required|string|max:100',
            'feeder_password' => 'nullable|string|min:4|max:255',
            'feeder_kode_pt'  => 'required|string|max:20',
        ]);

        $config = FeederConfig::instance();
        $config->feeder_url      = $validated['feeder_url'];
        $config->feeder_username = $validated['feeder_username'];
        $config->feeder_kode_pt  = $validated['feeder_kode_pt'];

        // Hanya update password jika diisi
        if (!empty($validated['feeder_password'])) {
            $config->feeder_password = $validated['feeder_password'];
        }

        $config->save();

        return redirect()->route('admin.feeder-config.show')
            ->with('success', 'Konfigurasi Neo Feeder berhasil disimpan.');
    }

    public function sync(): JsonResponse
    {
        try {
            $result = (new NeoFeederService())->syncAll();
            $total  = collect($result)->sum(fn($r) => $r['status'] === 'ok' ? $r['total'] : 0);
            return response()->json(['success' => true, 'message' => "Sinkronisasi selesai. Total {$total} record.", 'detail' => $result]);
        } catch (\Throwable $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function test(): JsonResponse
    {
        $config = FeederConfig::instance();

        if (!$config->exists) {
            return response()->json(['success' => false, 'message' => 'Konfigurasi belum disimpan.']);
        }

        $result = (new NeoFeederService())->testConnection();
        return response()->json($result);
    }
}

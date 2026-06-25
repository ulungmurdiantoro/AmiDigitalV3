<?php

namespace App\Http\Controllers\User;

use App\Exports\LkpsExport;
use App\Http\Controllers\Controller;
use App\Models\LkpsSnapshot;
use App\Services\LkpsComputeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class UserLkpsController extends Controller
{
    public function index()
    {
        $prodi  = session('user_penempatan');
        $periode = $this->currentPeriode();

        $service    = LkpsComputeService::forProdi($prodi);
        $terhubung  = $service !== null;
        $tersync    = $terhubung && $service->hasData();
        $lkpsData   = $tersync ? $service->compute() : null;

        $snapshots = LkpsSnapshot::where('prodi', $prodi)
            ->orderByDesc('created_at')
            ->get();

        return view('pages.user.lkps.index', compact(
            'prodi', 'periode', 'terhubung', 'tersync', 'lkpsData', 'snapshots'
        ));
    }

    public function snapshot(): JsonResponse
    {
        $prodi  = session('user_penempatan');
        $periode = $this->currentPeriode();

        $service = LkpsComputeService::forProdi($prodi);

        if (!$service || !$service->hasData()) {
            return response()->json(['success' => false, 'message' => 'Data Feeder belum tersedia untuk prodi ini.']);
        }

        $data = $service->compute();

        LkpsSnapshot::create([
            'prodi'      => $prodi,
            'prodi_kode' => $this->prodiKode($prodi),
            'periode'    => $periode,
            'data'       => $data,
            'created_by' => session('user_nama') ?? session('user_kode'),
        ]);

        return response()->json(['success' => true, 'message' => 'Snapshot LKPS berhasil disimpan.']);
    }

    public function export(Request $request)
    {
        $prodi   = session('user_penempatan');
        $periode = $this->currentPeriode();

        // Export specific snapshot if id provided, else latest, else compute fresh
        if ($request->filled('snapshot')) {
            $snapshot = LkpsSnapshot::find($request->integer('snapshot'));
            if (!$snapshot || $snapshot->prodi !== $prodi) {
                return back()->with('error', 'Snapshot tidak ditemukan.');
            }
        } else {
            $snapshot = LkpsSnapshot::where('prodi', $prodi)
                ->where('periode', $periode)
                ->latest()
                ->first();
        }

        $service = LkpsComputeService::forProdi($prodi);

        if (!$snapshot && (!$service || !$service->hasData())) {
            return back()->with('error', 'Belum ada data LKPS untuk diekspor.');
        }

        $data     = $snapshot ? $snapshot->data : $service->compute();
        $snapshotPeriode = $snapshot ? $snapshot->periode : $periode;
        $filename = 'LKPS_' . str_replace([' ', '/'], ['_', '-'], $prodi) . '_' . str_replace('/', '-', $snapshotPeriode) . '.xlsx';

        return Excel::download(new LkpsExport($data, $prodi, $snapshotPeriode), $filename);
    }

    public function destroySnapshot(LkpsSnapshot $snapshot): JsonResponse
    {
        if ($snapshot->prodi !== session('user_penempatan')) {
            return response()->json(['success' => false, 'message' => 'Tidak diizinkan.'], 403);
        }

        $snapshot->delete();
        return response()->json(['success' => true, 'message' => 'Snapshot dihapus.']);
    }

    private function currentPeriode(): string
    {
        $now   = now();
        $tahun = $now->year;
        return $now->month >= 7 ? "{$tahun}/" . ($tahun + 1) : ($tahun - 1) . "/{$tahun}";
    }

    private function prodiKode(string $prodi): ?string
    {
        return \App\Models\ProgramStudi::whereRaw("CONCAT(prodi_jenjang, ' - ', prodi_nama) = ?", [$prodi])
            ->value('feeder_kode_prodi');
    }
}

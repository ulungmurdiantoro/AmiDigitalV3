<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Services\NeoFeeder\NeoFeederService;
use Illuminate\Http\JsonResponse;

class UserFeederController extends Controller
{
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
}

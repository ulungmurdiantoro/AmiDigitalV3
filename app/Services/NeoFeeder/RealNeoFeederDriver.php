<?php

namespace App\Services\NeoFeeder;

use App\Models\FeederConfig;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class RealNeoFeederDriver implements NeoFeederDriverInterface
{
    private string $baseUrl;
    private string $username;
    private string $password;
    private string $kodePt;

    public function __construct()
    {
        $config = FeederConfig::instance();

        if (!$config->exists) {
            throw new \RuntimeException('Konfigurasi Neo Feeder belum disimpan. Silakan isi di menu Konfigurasi Neo Feeder.');
        }

        $this->baseUrl  = rtrim($config->feeder_url, '/');
        $this->username = $config->feeder_username;
        $this->password = $config->feeder_password;
        $this->kodePt   = $config->feeder_kode_pt;
    }

    public function testConnection(): array
    {
        try {
            // TODO: sesuaikan endpoint saat dokumentasi Neo Feeder API tersedia
            $response = Http::timeout(10)->post("{$this->baseUrl}/api/token", [
                'username' => $this->username,
                'password' => $this->password,
            ]);

            if ($response->successful()) {
                return ['success' => true, 'message' => 'Koneksi ke Neo Feeder berhasil.'];
            }

            return ['success' => false, 'message' => 'Server merespons dengan status ' . $response->status()];
        } catch (\Exception $e) {
            Log::error('NeoFeeder testConnection: ' . $e->getMessage());
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    public function fetchMahasiswa(): array
    {
        // TODO: implementasi setelah endpoint Neo Feeder API dikonfirmasi
        throw new \RuntimeException('RealNeoFeederDriver::fetchMahasiswa() belum diimplementasikan.');
    }

    public function fetchDosen(): array
    {
        // TODO: implementasi setelah endpoint Neo Feeder API dikonfirmasi
        throw new \RuntimeException('RealNeoFeederDriver::fetchDosen() belum diimplementasikan.');
    }

    public function fetchKelulusan(): array
    {
        // TODO: implementasi setelah endpoint Neo Feeder API dikonfirmasi
        throw new \RuntimeException('RealNeoFeederDriver::fetchKelulusan() belum diimplementasikan.');
    }
}

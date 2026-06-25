<?php

namespace App\Services\NeoFeeder;

interface NeoFeederDriverInterface
{
    /** Uji koneksi ke Neo Feeder. Return ['success' => bool, 'message' => string] */
    public function testConnection(): array;

    /** Ambil data mahasiswa. Return array of assoc arrays. */
    public function fetchMahasiswa(): array;

    /** Ambil data dosen. Return array of assoc arrays. */
    public function fetchDosen(): array;

    /** Ambil data kelulusan. Return array of assoc arrays. */
    public function fetchKelulusan(): array;
}

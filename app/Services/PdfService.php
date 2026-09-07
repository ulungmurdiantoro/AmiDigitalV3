<?php

namespace App\Services;

use Mpdf\Mpdf;

class PdfService
{
    /**
     * Buat instance Mpdf dengan direktori sementara yang dijamin bisa ditulis
     * oleh proses PHP (PHP-FPM = www-data di server).
     *
     * Secara default mPDF menulis cache & font ke vendor/mpdf/mpdf/tmp. Di
     * server produksi folder vendor/ dimiliki user deploy, sehingga www-data
     * tidak bisa mkdir di sana:
     *
     *   ErrorException: mkdir(): Permission denied
     *     vendor/mpdf/mpdf/src/Cache.php:47  -> HTTP 500
     *
     * storage/ sudah di-chmod ug+rwX oleh deploy.sh, jadi dipakai lebih dulu;
     * kalau tetap tidak writable, jatuh ke temp dir sistem (/tmp) yang selalu
     * bisa ditulis oleh user PHP-FPM.
     *
     * @param  array<string,mixed>  $config  Konfigurasi tambahan Mpdf.
     */
    public static function make(array $config = []): Mpdf
    {
        return new Mpdf(array_merge(['tempDir' => self::tempDir()], $config));
    }

    private static function tempDir(): string
    {
        $preferred = storage_path('app/mpdf');

        if (! is_dir($preferred)) {
            @mkdir($preferred, 0775, true);
        }

        if (is_dir($preferred) && is_writable($preferred)) {
            return $preferred;
        }

        return sys_get_temp_dir();
    }
}

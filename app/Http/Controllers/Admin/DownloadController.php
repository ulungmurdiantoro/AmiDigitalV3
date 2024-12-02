<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DownloadController extends Controller
{
    public function download()
    {
        $filePath = storage_path('app/public/storage/uploads/akreditasi/prodi/1728379851.pdf');
        $fileName = '1728379851.pdf';

        return response()->download($filePath, $fileName);
    }
}


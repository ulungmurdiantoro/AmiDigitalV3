<?php
/**
 * Parser PDF LAMINFOKOM → JSON
 *
 * Jalankan:  cd database/data/LAMINFOKOM && php _parse_pdf.php
 *
 * Output: satu file JSON per PDF, mis. "Sarjana.json", "Magister.json", dst.
 * File JSON digunakan oleh KriteriaLaminfokomSeeder.php.
 *
 * Struktur JSON:
 * [
 *   {
 *     "kriteria": "Kriteria 1 Budaya Mutu",
 *     "no_butir": "1.1 A",
 *     "bobot": "3",
 *     "elemen": "1.1 [PENETAPAN] A. Kebijakan, standar, dan ...",
 *     "deskriptor": "...",
 *     "skor4": "...",
 *     "skor3": "...",
 *     "skor2": "...",
 *     "skor1": "..."
 *   },
 *   ...
 * ]
 */

$dir = __DIR__;

// Semua PDF di folder ini
$pdfFiles = glob($dir . DIRECTORY_SEPARATOR . '*.pdf');

foreach ($pdfFiles as $pdfPath) {
    $basename  = basename($pdfPath, '.pdf');
    // Ambil jenjang dari nama file: "Sarjana - Matriks Penilaian" → "Sarjana"
    $jenjang   = trim(explode(' - ', $basename)[0]);
    $jsonPath  = $dir . DIRECTORY_SEPARATOR . strtolower(str_replace(' ', '_', $jenjang)) . '.json';

    echo "=== [{$jenjang}] Parsing {$basename}.pdf ...\n";

    $pdftotext = 'C:\\Program Files\\Git\\mingw64\\bin\\pdftotext.exe';
    $cmd = '"' . $pdftotext . '" -layout ' . escapeshellarg($pdfPath) . ' -';
    $raw = shell_exec($cmd);
    if (!$raw) {
        echo "  [ERROR] Gagal ekstrak teks.\n";
        continue;
    }

    $rows = parsePdf($raw);
    file_put_contents($jsonPath, json_encode($rows, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    echo "  → {$jsonPath}: " . count($rows) . " indikator\n\n";
}

// ─── Parser utama ─────────────────────────────────────────────────────────────
function parsePdf(string $raw): array
{
    $lines = explode("\n", $raw);

    // Deteksi posisi kolom dari baris header (mengandung "Elemen Penilaian")
    $colPos = detectColumnPositions($lines);
    if (empty($colPos)) {
        echo "  [WARN] Kolom header tidak ditemukan, pakai default.\n";
        $colPos = defaultColPos();
    }

    $indicators   = [];
    $currentKrit  = 'Pendahuluan';
    $current      = null;

    $i = 0;
    $n = count($lines);

    while ($i < $n) {
        $line = $lines[$i];

        // Skip baris footer/header halaman
        if (preg_match('/Matriks Penilaian Kinerja Program Studi/i', $line)) { $i++; continue; }
        if (preg_match('/^Jenis\s+No\.\s+No\./i', $line))                    { $i++; continue; }
        if (preg_match('/^\s*dari\s*$/i', $line))                             { $i++; continue; }
        if (preg_match('/^\s*400\s*$/i', $line))                              { $i++; continue; }
        if (preg_match('/^\s*Urut\s*$/i', $line))                             { $i++; continue; }
        if (preg_match('/^\s*Butir\s*$/i', $line))                            { $i++; continue; }
        if (preg_match('/^\s*\d+\s*$/', trim($line)) && strlen(trim($line)) <= 3) { $i++; continue; }

        // Baris Kriteria header (misal: "Kriteria 1 Budaya Mutu")
        if (preg_match('/^Kriteria\s+\d+\s+\S/i', trim($line))) {
            if ($current !== null) {
                $indicators[] = finalizeRow($current);
                $current = null;
            }
            $currentKrit = trim($line);
            $i++;
            continue;
        }

        // Baris data baru: mulai dengan jenis (I/II/III) lalu nomor
        // Contoh: "I      1.    A      4      Kondisi Eksternal   ..."
        // Atau: "   I   4. 1.1 B  2,5  B. Kebijakan ..."
        if (preg_match('/^\s*(I{1,3}|IV|V{1,3}|VI{0,3}|IX|X)\s+\d+\.\s+/u', $line)) {
            if ($current !== null) {
                $indicators[] = finalizeRow($current);
            }
            $current = [
                'kriteria'   => $currentKrit,
                'jenis'      => '',
                'no_urut'    => '',
                'no_butir'   => '',
                'bobot'      => '',
                'elemen'     => '',
                'deskriptor' => '',
                'skor4'      => '',
                'skor3'      => '',
                'skor2'      => '',
                'skor1'      => '',
            ];
            extractCells($line, $colPos, $current);
            $i++;
            continue;
        }

        // Baris continuation (data lanjutan baris sebelumnya)
        if ($current !== null && trim($line) !== '') {
            extractCells($line, $colPos, $current);
        }

        $i++;
    }

    if ($current !== null) {
        $indicators[] = finalizeRow($current);
    }

    return $indicators;
}

// ─── Deteksi posisi kolom dari baris header ───────────────────────────────────
function detectColumnPositions(array $lines): array
{
    foreach ($lines as $line) {
        if (!preg_match('/Elemen Penilaian/i', $line)) continue;
        if (!preg_match('/Deskriptor/i', $line)) continue;
        if (!preg_match('/Sangat baik/i', $line)) continue;

        // Baris header ditemukan – ukur posisi tiap kolom
        $pos = [];
        $pos['jenis']   = 0;
        $pos['no_urut'] = findKeyPos($line, 'No.', 0);
        $pos['no_butir']= findKeyPos($line, 'No.', $pos['no_urut'] + 1);
        $pos['bobot']   = findKeyPos($line, 'Bobot');
        if ($pos['bobot'] < 0) $pos['bobot'] = findKeyPos($line, 'dari');
        $pos['elemen']  = findKeyPos($line, 'Elemen');
        $pos['desk']    = findKeyPos($line, 'Deskriptor');
        $pos['skor4']   = findKeyPos($line, 'Sangat');
        $pos['skor3']   = findKeyPos($line, 'Baik = 3');
        if ($pos['skor3'] < 0) $pos['skor3'] = $pos['skor4'] + 35;
        $pos['skor2']   = findKeyPos($line, 'Cukup');
        if ($pos['skor2'] < 0) $pos['skor2'] = $pos['skor3'] + 20;
        $pos['skor1']   = findKeyPos($line, 'Kurang');
        if ($pos['skor1'] < 0) $pos['skor1'] = $pos['skor2'] + 20;

        // Validasi semua positif
        foreach (['elemen','desk','skor4','skor3','skor2','skor1'] as $k) {
            if ($pos[$k] < 0) return [];
        }
        return $pos;
    }
    return [];
}

function findKeyPos(string $line, string $key, int $from = 0): int
{
    $p = mb_strpos($line, $key, $from);
    return $p !== false ? (int)$p : -1;
}

function defaultColPos(): array
{
    return [
        'jenis'   => 0,
        'no_urut' => 7,
        'no_butir'=> 14,
        'bobot'   => 22,
        'elemen'  => 28,
        'desk'    => 55,
        'skor4'   => 80,
        'skor3'   => 110,
        'skor2'   => 135,
        'skor1'   => 158,
    ];
}

// ─── Ekstrak sel dari satu baris teks berdasar posisi kolom ───────────────────
function extractCells(string $line, array $p, array &$row): void
{
    $len = mb_strlen($line);

    $segments = [
        'jenis'      => substr($line, $p['jenis'],   max(0, $p['no_urut'] - $p['jenis'])),
        'no_urut'    => substr($line, $p['no_urut'],  max(0, $p['no_butir'] - $p['no_urut'])),
        'no_butir'   => substr($line, $p['no_butir'], max(0, $p['bobot'] - $p['no_butir'])),
        'bobot'      => substr($line, $p['bobot'],    max(0, $p['elemen'] - $p['bobot'])),
        'elemen'     => substr($line, $p['elemen'],   max(0, $p['desk'] - $p['elemen'])),
        'deskriptor' => substr($line, $p['desk'],     max(0, $p['skor4'] - $p['desk'])),
        'skor4'      => substr($line, $p['skor4'],    max(0, $p['skor3'] - $p['skor4'])),
        'skor3'      => substr($line, $p['skor3'],    max(0, $p['skor2'] - $p['skor3'])),
        'skor2'      => substr($line, $p['skor2'],    max(0, $p['skor1'] - $p['skor2'])),
        'skor1'      => $len > $p['skor1'] ? substr($line, $p['skor1']) : '',
    ];

    foreach ($segments as $key => $val) {
        $val = trim($val);
        if ($val === '') continue;

        // Bersihkan karakter halaman (angka standalone)
        if (preg_match('/^\d{1,3}$/', $val) && in_array($key, ['skor4','skor3','skor2','skor1'])) continue;

        // Hindari duplikasi kata yang sama persis
        if ($row[$key] === '' || $row[$key] === $val) {
            $row[$key] = $val;
        } else {
            $row[$key] .= ' ' . $val;
        }
    }
}

// ─── Finalize dan bersihkan row ────────────────────────────────────────────────
function finalizeRow(array $row): array
{
    foreach ($row as $k => $v) {
        // Normalisasi spasi berlebih
        $v = preg_replace('/\s+/', ' ', $v);
        // Hilangkan tanda "Syarat Unggul ..." dari deskriptor (masuk ke skor4 biasanya)
        $row[$k] = trim($v);
    }

    // no_butir dari kolom bisa berisi "1.1 A" atau "A" saja
    $row['no_butir'] = preg_replace('/\s+/', ' ', trim($row['no_butir'] . ' ' . $row['jenis']));
    // Bersihkan nomor urut dari titik ganda
    $row['no_urut']  = trim(ltrim($row['no_urut'], ' '));

    // Hapus kolom internal
    unset($row['jenis']);

    return $row;
}

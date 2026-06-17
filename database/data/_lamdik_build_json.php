<?php
/**
 * Bangun database/data/lamdik.json dari dokumen LAMDIK Buku 4.
 * - Jika tersedia versi Excel (lebih akurat) -> dipakai.
 * - Selain itu pakai PDF (best-effort).
 * Output: jenjang -> kriteria -> [ {kode, bobot, elemen, indikator} ]
 * Jalankan: php database/data/_lamdik_build_json.php   (butuh pdftotext utk PDF)
 */
require __DIR__ . '/../../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

$dir = __DIR__;

// Sumber per jenjang. Excel diutamakan; PDF fallback.
// Varian TU/PT/OT isinya sama untuk kriteria/elemen/indikator; beda hanya harkat. Pakai TU.
$excelFiles = [
    'S1'     => 'Buku-4-TU-S1-3.0.xlsx',
    'S1 PJJ' => 'Buku-4-TU-S1-PJJ-3.0.xlsx',
    'S2'     => 'Buku-4-TU-S2-3.0.xlsx',
    'S2 PJJ' => 'Buku-4-TU-S2-PJJ-3.0.xlsx',
    'S3'     => 'Buku-4-TU-S3-3.0.xlsx',
    'S3 PJJ' => 'Buku-4-TU-S3-PJJ-3.0.xlsx',
    'PPG'    => 'Buku-4-TU-PPG-3.0.xlsx',
];
$pdfFiles = [
    'S1'     => 'Buku-4-PT-S1-3.0.pdf',
    'S1 PJJ' => 'Buku-4-PT-S1-PJJ-3.0.pdf',
    'S2'     => 'Buku-4-PT-S2-3.0.pdf',
    'S2 PJJ' => 'Buku-4-PT-S2-PJJ-3.0.pdf',
    'S3'     => 'Buku-4-PT-S3-3.0.pdf',
    'S3 PJJ' => 'Buku-4-PT-S3-PJJ-3.0.pdf',
    'PPG'    => 'Buku-4-PT-PPG-3.0.pdf',
];

$kriteriaOrder = [
    'Visi Keilmuan Program Studi',
    'Tata Pamong dan Tata Kelola UPPS',
    'Mahasiswa',
    'Dosen dan Tenaga Kependidikan',
    'Keuangan, Sarana, dan Prasarana Pendidikan',
    'Pendidikan',
    'Penelitian',
    'Pengabdian Kepada Masyarakat',
    'Penjaminan Mutu',
];

function canonKriteria(?string $s): ?string
{
    $t = mb_strtolower(trim(preg_replace('/\s+/', ' ', (string) $s)));
    if ($t === '' || $t === 'kriteria') return null;
    if (mb_strpos($t, 'visi keilmuan') !== false) return 'Visi Keilmuan Program Studi';
    if (mb_strpos($t, 'tata pamong') !== false)   return 'Tata Pamong dan Tata Kelola UPPS';
    if (mb_strpos($t, 'mahasiswa') !== false)      return 'Mahasiswa';
    if (mb_strpos($t, 'dosen') !== false)          return 'Dosen dan Tenaga Kependidikan';
    if (mb_strpos($t, 'keuangan') !== false)       return 'Keuangan, Sarana, dan Prasarana Pendidikan';
    if (mb_strpos($t, 'penelitian') !== false)     return 'Penelitian';
    if (mb_strpos($t, 'pengabdian') !== false || $t === 'pkm') return 'Pengabdian Kepada Masyarakat';
    if (mb_strpos($t, 'penjam') !== false)         return 'Penjaminan Mutu'; // tangkap typo "penjamiinan"
    if (mb_strpos($t, 'pendidikan') !== false)     return 'Pendidikan';
    return null;
}

function groupByKriteria(array $flat, array $order): array
{
    $by = array_fill_keys($order, []);
    foreach ($flat as $it) {
        $k = $it['kriteria'];
        if (!isset($by[$k])) $by[$k] = [];
        $by[$k][] = [
            'kode'      => (string) $it['no'],
            'bobot'     => $it['bobot'] ?? null,
            'elemen'    => $it['elemen'] ?? '',
            'indikator' => $it['indikator'],
            'info'      => $it['info'] ?? '',
        ];
    }
    return array_filter($by, fn($v) => count($v) > 0);
}

/**
 * Parser Excel dinamis: cari sheet+baris header (KRITERIA/ELEMEN/INDIKATOR),
 * deteksi kolom otomatis (layout beda antar file), harkat dari kolom berlabel 4/3/2/1.
 */
function parseExcel(string $path): array
{
    $reader = IOFactory::createReaderForFile($path);
    $reader->setReadDataOnly(true);
    $ss = $reader->load($path);

    $sheet = null; $hdrRow = null; $kCol = $eCol = $iCol = null;
    foreach ($ss->getAllSheets() as $sh) {
        $maxR = min(60, $sh->getHighestDataRow());
        $maxC = Coordinate::columnIndexFromString($sh->getHighestDataColumn());
        for ($r = 1; $r <= $maxR; $r++) {
            $k = $e = $i = null;
            for ($ci = 1; $ci <= $maxC; $ci++) {
                $L = Coordinate::stringFromColumnIndex($ci);
                $u = strtoupper(trim((string) $sh->getCell($L . $r)->getValue()));
                if ($u === 'KRITERIA') $k = $L;
                elseif ($u === 'ELEMEN') $e = $L;
                elseif ($u === 'INDIKATOR') $i = $L;
            }
            if ($k && $e && $i) { $sheet = $sh; $hdrRow = $r; $kCol = $k; $eCol = $e; $iCol = $i; break 2; }
        }
    }
    if (!$sheet) return [];

    // Kolom skor harkat (4/3/2/1) pada baris label di bawah header.
    $maxC = Coordinate::columnIndexFromString($sheet->getHighestDataColumn());
    $skor = [];
    $labelRow = $hdrRow;
    for ($r = $hdrRow; $r <= $hdrRow + 2; $r++) {
        for ($ci = 1; $ci <= $maxC; $ci++) {
            $L = Coordinate::stringFromColumnIndex($ci);
            $v = trim((string) $sheet->getCell($L . $r)->getValue());
            if (in_array($v, ['4', '3', '2', '1'], true) && !isset($skor[$v])) { $skor[$v] = $L; $labelRow = max($labelRow, $r); }
        }
    }

    $get = fn($col, $r) => $col ? trim(preg_replace('/\s+/', ' ', (string) $sheet->getCell($col . $r)->getValue())) : '';

    $flat = []; $cur = null; $curIdx = -1; $seen = [];
    $maxR = $sheet->getHighestDataRow();
    for ($r = $labelRow + 1; $r <= $maxR; $r++) {
        $A = $get($kCol, $r); $E = $get($eCol, $r); $I = $get($iCol, $r);
        $h4 = $get($skor['4'] ?? null, $r); $h3 = $get($skor['3'] ?? null, $r);
        $h2 = $get($skor['2'] ?? null, $r); $h1 = $get($skor['1'] ?? null, $r);

        $ck = canonKriteria($A);
        if ($ck !== null) $cur = $ck;
        if (strcasecmp($A, 'KRITERIA') === 0) continue;

        if ($cur !== null && preg_match('/^(\d+)\.\s+(.+)$/u', $I, $m)) {
            $no = (int) $m[1];
            if (isset($seen[$no])) continue;
            $seen[$no] = true;
            $bobot = null; $elemen = $E;
            if (preg_match('/\((\d+\.\d{2})\)/', $E, $bm)) {
                $bobot = (float) $bm[1];
                $elemen = trim(preg_replace('/\(\d+\.\d{2}\)/', '', $E));
            }
            $flat[] = ['no' => $no, 'kriteria' => $cur, 'elemen' => $elemen, 'bobot' => $bobot,
                       'indikator' => trim($m[2]), 'h4' => $h4, 'h3' => $h3, 'h2' => $h2, 'h1' => $h1];
            $curIdx = count($flat) - 1;
        } elseif ($curIdx >= 0) {
            if ($h4 !== '') $flat[$curIdx]['h4'] .= ' ' . $h4;
            if ($h3 !== '') $flat[$curIdx]['h3'] .= ' ' . $h3;
            if ($h2 !== '') $flat[$curIdx]['h2'] .= ' ' . $h2;
            if ($h1 !== '') $flat[$curIdx]['h1'] .= ' ' . $h1;
            if ($I !== '' && !preg_match('/^\d+\./', $I)) $flat[$curIdx]['indikator'] .= ' ' . $I;
        }
    }

    foreach ($flat as &$it) {
        $parts = [];
        foreach (['h4' => 'Skor 4', 'h3' => 'Skor 3', 'h2' => 'Skor 2', 'h1' => 'Skor 1'] as $k => $lbl) {
            $val = trim(preg_replace('/\s+/', ' ', $it[$k] ?? ''));
            if ($val !== '') $parts[] = "$lbl: $val";
            unset($it[$k]);
        }
        $it['info'] = implode("\n", $parts);
        $it['indikator'] = trim(preg_replace('/\s+/', ' ', $it['indikator']));
    }
    unset($it);

    return $flat;
}

/** Parser PDF (best-effort) via pdftotext mode alur. */
function parsePdf(string $path, array $order): array
{
    $tmp = tempnam(sys_get_temp_dir(), 'lamdik') . '.txt';
    shell_exec('pdftotext ' . escapeshellarg($path) . ' ' . escapeshellarg($tmp));
    $raw = @file($tmp, FILE_IGNORE_NEW_LINES) ?: [];
    @unlink($tmp);
    $lines = array_values(array_filter(array_map(fn($l) => trim(preg_replace('/\s+/', ' ', $l)), $raw), fn($l) => $l !== ''));

    $flat = [];
    $bobots = [];
    $seen = [];
    $started = false;
    foreach ($lines as $l) {
        if (!$started) { if (preg_match('/MATRIKS PENILAIAN/i', $l)) $started = true; continue; }
        if (in_array($l, ['ELEMEN', 'INDIKATOR', 'HARKAT PENSKORAN'], true)) continue;
        if (preg_match('/^Buku 4 \|/', $l)) continue;
        if (preg_match_all('/\((\d+\.\d{2})\)/', $l, $mm)) foreach ($mm[1] as $b) $bobots[] = (float) $b;
        if (preg_match('/(?:^|\s)(\d{1,2})\.\s+([A-Z(].*)$/u', $l, $m)) {
            $no = (int) $m[1];
            if ($no >= 1 && $no <= 99 && !isset($seen[$no])) { $seen[$no] = true; $flat[] = ['no' => $no, 'stmt' => trim($m[2])]; }
        }
    }

    // Visi = sebelum indikator pertama yang menyebut "tata pamong"
    $idxTP = null;
    foreach ($flat as $i => $it) { if (mb_stripos($it['stmt'], 'tata pamong') !== false) { $idxTP = $i; break; } }

    $kIndex = array_flip($order);
    $enderKriteria = function (string $s): ?string {
        $t = mb_strtolower($s);
        if (mb_strpos($t, 'evaluasi dan refleksi') === false && !preg_match('/melakukan evaluasi dan\b/u', $t)) return null;
        if (mb_strpos($t, 'tata pamong') !== false) return 'Tata Pamong dan Tata Kelola UPPS';
        if (mb_strpos($t, 'mahasiswa') !== false)    return 'Mahasiswa';
        if (mb_strpos($t, 'dosen') !== false)        return 'Dosen dan Tenaga Kependidikan';
        if (mb_strpos($t, 'keuangan') !== false || mb_strpos($t, 'sarpras') !== false) return 'Keuangan, Sarana, dan Prasarana Pendidikan';
        if (mb_strpos($t, 'penelitian') !== false)   return 'Penelitian';
        if (mb_strpos($t, 'pkm') !== false || mb_strpos($t, 'pengabdian') !== false) return 'Pengabdian Kepada Masyarakat';
        if (mb_strpos($t, 'penjaminan mutu') !== false) return 'Penjaminan Mutu';
        if (mb_strpos($t, 'pendidikan') !== false)   return 'Pendidikan';
        return '';
    };

    $result = [];
    $buf = [];
    $expectIdx = 1;
    foreach ($flat as $i => $it) {
        $entry = ['no' => $it['no'], 'elemen' => '', 'bobot' => $bobots[$i] ?? null, 'indikator' => $it['stmt']];
        if ($idxTP !== null && $i < $idxTP) { $entry['kriteria'] = $order[0]; $result[] = $entry; continue; }
        $buf[] = $entry;
        $ek = $enderKriteria($it['stmt']);
        if ($ek === null) continue;
        $kr = $ek !== '' ? $ek : $order[min($expectIdx, 8)];
        foreach ($buf as $b) { $b['kriteria'] = $kr; $result[] = $b; }
        $buf = [];
        $expectIdx = ($kIndex[$kr] ?? $expectIdx) + 1;
    }
    foreach ($buf as $b) { $b['kriteria'] = $order[8]; $result[] = $b; }
    return $result;
}

$out = [];
foreach ($pdfFiles as $jenjang => $pdf) {
    if (isset($excelFiles[$jenjang]) && is_file($dir . '/' . $excelFiles[$jenjang])) {
        $flat = parseExcel($dir . '/' . $excelFiles[$jenjang]);
        $src = 'XLSX';
    } elseif (is_file($dir . '/' . $pdf)) {
        $flat = parsePdf($dir . '/' . $pdf, $kriteriaOrder);
        $src = 'PDF';
    } else {
        fwrite(STDERR, "skip $jenjang (tidak ada sumber)\n");
        continue;
    }
    $out[$jenjang] = groupByKriteria($flat, $kriteriaOrder);
    $tot = array_sum(array_map('count', $out[$jenjang]));
    fwrite(STDERR, sprintf("%-8s [%s]: %d indikator, %d kriteria\n", $jenjang, $src, $tot, count($out[$jenjang])));
}

file_put_contents($dir . '/lamdik.json', json_encode($out, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
fwrite(STDERR, "Tersimpan: database/data/lamdik.json\n");

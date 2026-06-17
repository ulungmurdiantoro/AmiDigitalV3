<?php
/**
 * Bangun database/data/lamdik.json dari 7 PDF LAMDIK Buku 4 (varian PT).
 * Memetakan: jenjang -> kriteria -> indikator [{kode, bobot, indikator}]
 * Jalankan SEKALI di mesin yang punya pdftotext: php database/data/_lamdik_build_json.php
 */
$dir = __DIR__;

$files = [
    'Buku-4-PT-S1-3.0.pdf'      => 'S1',
    'Buku-4-PT-S1-PJJ-3.0.pdf'  => 'S1 PJJ',
    'Buku-4-PT-S2-3.0.pdf'      => 'S2',
    'Buku-4-PT-S2-PJJ-3.0.pdf'  => 'S2 PJJ',
    'Buku-4-PT-S3-3.0.pdf'      => 'S3',
    'Buku-4-PT-S3-PJJ-3.0.pdf'  => 'S3 PJJ',
    'Buku-4-PT-PPG-3.0.pdf'     => 'PPG',
];

// 9 kriteria urut resmi
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

function isEnder(string $stmt, int $ptr): bool
{
    $s = mb_strtolower($stmt);
    if (mb_strpos($s, 'evaluasi dan refleksi') !== false) return true;
    // kriteria 1 (Visi) sering terpotong jadi "...melakukan evaluasi dan ..."
    if ($ptr === 0 && preg_match('/melakukan evaluasi dan\b/u', $s)) return true;
    return false;
}

$out = [];

foreach ($files as $file => $jenjang) {
    $path = $dir . DIRECTORY_SEPARATOR . $file;
    if (!is_file($path)) { fwrite(STDERR, "skip (tidak ada): $file\n"); continue; }

    $tmp = tempnam(sys_get_temp_dir(), 'lamdik') . '.txt';
    shell_exec('pdftotext ' . escapeshellarg($path) . ' ' . escapeshellarg($tmp));
    $raw = @file($tmp, FILE_IGNORE_NEW_LINES) ?: [];
    @unlink($tmp);

    $lines = array_values(array_filter(array_map(
        fn($l) => trim(preg_replace('/\s+/', ' ', $l)), $raw
    ), fn($l) => $l !== ''));

    // Satu lintasan: kumpulkan indikator (flat, urut, dedupe nomor) + bobot, setelah MATRIKS.
    $flat = [];
    $bobots = [];
    $seen = [];
    $started = false;
    foreach ($lines as $l) {
        if (!$started) {
            if (preg_match('/MATRIKS PENILAIAN/i', $l)) $started = true;
            continue;
        }
        if (in_array($l, ['ELEMEN', 'INDIKATOR', 'HARKAT PENSKORAN'], true)) continue;
        if (preg_match('/^Buku 4 \|/', $l)) continue;

        if (preg_match_all('/\((\d+\.\d{2})\)/', $l, $mm)) foreach ($mm[1] as $b) $bobots[] = (float) $b;

        if (preg_match('/(?:^|\s)(\d{1,2})\.\s+([A-Z(].*)$/u', $l, $m)) {
            $no = (int) $m[1];
            if ($no >= 1 && $no <= 99 && !isset($seen[$no])) {
                $seen[$no] = true;
                $flat[] = ['no' => $no, 'stmt' => trim($m[2])];
            }
        }
    }

    // Kriteria dari penanda ender (indikator terakhir tiap kriteria menyebut namanya).
    $kIndex = array_flip($kriteriaOrder);
    $enderKriteria = function (string $s): ?string {
        $t = mb_strtolower($s);
        if (mb_strpos($t, 'evaluasi dan refleksi') === false && !preg_match('/melakukan evaluasi dan\b/u', $t)) return null;
        if (mb_strpos($t, 'tata pamong') !== false)  return 'Tata Pamong dan Tata Kelola UPPS';
        if (mb_strpos($t, 'mahasiswa') !== false)     return 'Mahasiswa';
        if (mb_strpos($t, 'dosen') !== false)         return 'Dosen dan Tenaga Kependidikan';
        if (mb_strpos($t, 'keuangan') !== false || mb_strpos($t, 'sarpras') !== false) return 'Keuangan, Sarana, dan Prasarana Pendidikan';
        if (mb_strpos($t, 'penelitian') !== false)    return 'Penelitian';
        if (mb_strpos($t, 'pkm') !== false || mb_strpos($t, 'pengabdian') !== false) return 'Pengabdian Kepada Masyarakat';
        if (mb_strpos($t, 'penjaminan mutu') !== false) return 'Penjaminan Mutu';
        if (mb_strpos($t, 'pendidikan') !== false)    return 'Pendidikan';
        return ''; // ender tanpa kata kunci (mis. #4 terpotong)
    };

    // Visi Keilmuan = indikator sebelum indikator pertama yang menyebut "tata pamong".
    $idxTP = null;
    foreach ($flat as $i => $it) {
        if (mb_stripos($it['stmt'], 'tata pamong') !== false) { $idxTP = $i; break; }
    }

    $byKriteria = array_fill_keys($kriteriaOrder, []);
    $buf = [];
    $expectIdx = 1; // mulai dari Tata Pamong (Visi ditangani khusus)
    foreach ($flat as $i => $it) {
        $entry = ['kode' => (string) $it['no'], 'bobot' => $bobots[$i] ?? null, 'indikator' => $it['stmt']];

        if ($idxTP !== null && $i < $idxTP) {
            $byKriteria['Visi Keilmuan Program Studi'][] = $entry;
            continue;
        }

        $buf[] = $entry;
        $ek = $enderKriteria($it['stmt']);
        if ($ek === null) continue;
        $kr = $ek !== '' ? $ek : $kriteriaOrder[min($expectIdx, 8)];
        foreach ($buf as $b) $byKriteria[$kr][] = $b;
        $buf = [];
        $expectIdx = ($kIndex[$kr] ?? $expectIdx) + 1;
    }
    if ($buf) foreach ($buf as $b) $byKriteria[$kriteriaOrder[8]][] = $b; // sisa -> Penjaminan Mutu

    // buang kriteria kosong
    $byKriteria = array_filter($byKriteria, fn($v) => count($v) > 0);
    $out[$jenjang] = $byKriteria;

    $tot = array_sum(array_map('count', $byKriteria));
    fwrite(STDERR, sprintf("%-8s: %d indikator, %d kriteria\n", $jenjang, $tot, count($byKriteria)));
}

file_put_contents($dir . '/lamdik.json', json_encode($out, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
fwrite(STDERR, "Tersimpan: database/data/lamdik.json\n");

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Indikator;
use App\Models\Standard;
use App\Models\Element;
use App\Models\Jenjang;
use App\Models\StandarAkreditasi;
use App\Models\StandarTarget;

/**
 * Seed standar_targets untuk LAMEMBA — 1 set (jenjang = "LAMEMBA").
 * 29 indikator × 2-4 dokumen = ±87 targets.
 */
class KriteriaLamembaTargetSeeder extends Seeder
{
    // keyword (lowercase) → array of [dokumen_nama, tipe, pertanyaan, keterangan]
    protected array $mapping = [
        'struktur dan proses tata pamong' => [
            ['Statuta dan Dokumen OTK', 'Kebijakan',
             'Apakah UPPS/PS memiliki dokumen struktur tata pamong yang lengkap?',
             'Mencakup statuta, OTK, dan prosedur operasional'],
            ['SK Pengangkatan Pimpinan UPPS/PS', 'SK',
             'Apakah pimpinan UPPS/PS diangkat secara sah?',
             'SK Rektor/Yayasan; menunjukkan legalitas kepemimpinan'],
            ['Deskripsi Jabatan (Job Description)', 'Dokumen',
             'Apakah tersedia deskripsi tugas dan wewenang setiap jabatan?', null],
        ],
        'proses pengawasan, pembentukan sinergi' => [
            ['Laporan Pengawasan Internal UPPS/PS', 'Laporan',
             'Apakah tersedia laporan pelaksanaan pengawasan berkala?',
             'Notulen rapat pengawasan; laporan temuan dan tindak lanjut'],
            ['Notulen Rapat Koordinasi', 'Dokumen',
             'Apakah tercipta sinergi antar unit melalui koordinasi rutin?', null],
        ],
        'perencanaan, pengorganisasian, pengarahan' => [
            ['Renstra UPPS/PS', 'Dokumen',
             'Apakah UPPS/PS memiliki Rencana Strategis yang valid?',
             'Renstra minimal 5 tahun; selaras dengan visi PT'],
            ['Rencana Operasional (Renop) Tahunan', 'Dokumen',
             'Apakah terdapat Renop sebagai penjabaran Renstra?', null],
            ['Laporan Capaian Renstra/Renop', 'Laporan',
             'Apakah terdapat laporan ketercapaian target Renstra/Renop?', null],
        ],
        'sistem tata kelola mampu mendorong' => [
            ['Laporan Kinerja Tata Kelola', 'Laporan',
             'Apakah sistem tata kelola mampu mendorong keunggulan UPPS/PS?',
             'Laporan capaian indikator kinerja utama'],
            ['Dokumen Good University Governance (GUG)', 'Dokumen',
             'Apakah terdapat dokumen kebijakan GUG yang diterapkan?', null],
        ],
        'sistem manajemen mutu internal' => [
            ['Kebijakan SPMI', 'Kebijakan',
             'Apakah tersedia kebijakan SPMI yang disahkan pimpinan?',
             'SK Rektor tentang SPMI; komitmen pimpinan'],
            ['Manual SPMI', 'Dokumen',
             'Apakah Manual SPMI mencakup siklus PPEPP?',
             'Penetapan, Pelaksanaan, Evaluasi, Pengendalian, Peningkatan'],
            ['Laporan Audit Mutu Internal (AMI)', 'Laporan',
             'Apakah SPMI dijalankan secara konsisten dan terdokumentasi?',
             'Laporan AMI terakhir; temuan dan tindak lanjut'],
        ],
        'kriteria dalam menentukan kualifikasi' => [
            ['Panduan/Kriteria Seleksi dan Rekrutmen Dosen', 'Pedoman',
             'Apakah tersedia kriteria penetapan kualifikasi dosen?',
             'Kualifikasi akademik, kompetensi, dan jabatan dosen'],
            ['SK Penetapan Kriteria Dosen', 'SK',
             'Apakah kriteria dosen ditetapkan secara resmi?', null],
        ],
        'matriks yang menggambarkan rencana dan pelaksa' => [
            ['Matriks Beban Kerja Dosen (BKD)', 'Data LKPS',
             'Apakah tersedia matriks rencana dan realisasi BKD?',
             'Rencana vs realisasi BKD per semester'],
            ['Laporan Realisasi BKD', 'Laporan BKD',
             'Apakah realisasi BKD sesuai dengan perencanaan?', null],
        ],
        'beban kerja dosen yang konsisten' => [
            ['Laporan BKD per Semester', 'Laporan BKD',
             'Apakah beban kerja dosen terdistribusi sesuai Tridharma?',
             'BKD mencakup pendidikan, penelitian, dan PkM'],
            ['Rekap Distribusi BKD Seluruh Dosen', 'Data LKPS',
             'Apakah distribusi BKD merata dan sesuai standar?', null],
        ],
        'rencana rekrutmen dan pengembangan dosen' => [
            ['Rencana Rekrutmen Dosen', 'Dokumen',
             'Apakah tersedia rencana rekrutmen dosen jangka menengah?', null],
            ['SK Pengangkatan Dosen Tetap', 'SK',
             'Apakah dosen tetap diangkat secara sah?',
             'SK Yayasan/PT; kontrak dosen tetap'],
            ['Dokumen Seleksi Dosen Baru', 'Dokumen',
             'Apakah proses seleksi dosen baru terdokumentasi?', null],
        ],
        'dukungan dan fasilitas secara terstruktur d' => [
            ['Laporan Pengembangan Kompetensi Dosen', 'Laporan',
             'Apakah UPPS memberi dukungan pengembangan dosen secara terstruktur?',
             'Bukti pelatihan, seminar, studi lanjut yang difasilitasi UPPS'],
            ['Bukti Pembiayaan Pengembangan Dosen', 'Dokumen',
             'Apakah terdapat alokasi anggaran untuk pengembangan dosen?', null],
        ],
        'evaluasi proses secara sistematik, terencana' => [
            ['Instrumen Evaluasi Kinerja Dosen', 'Dokumen',
             'Apakah tersedia instrumen evaluasi kinerja dosen yang valid?', null],
            ['Laporan Evaluasi Kinerja Dosen', 'Laporan',
             'Apakah evaluasi kinerja dosen dilakukan secara berkala?',
             'Hasil evaluasi per semester; tindak lanjut perbaikan'],
        ],
        'kriteria untuk menentukan kualifikasi tenaga kependidikan' => [
            ['Panduan Seleksi Tenaga Kependidikan', 'Pedoman',
             'Apakah tersedia kriteria kualifikasi tenaga kependidikan?',
             'Standar pendidikan, keahlian, dan kompetensi tendik'],
            ['SK Kriteria Tendik', 'SK',
             'Apakah kriteria tendik ditetapkan secara resmi?', null],
        ],
        'kualifikasi tenaga kependidikan (pendidikan dan keahlian)' => [
            ['Data Kualifikasi Tenaga Kependidikan', 'Data LKPS',
             'Apakah kualifikasi tendik memenuhi standar yang ditetapkan?',
             'Data pendidikan, sertifikasi, dan jabatan fungsional tendik'],
            ['Sertifikat Kompetensi Tendik', 'Sertifikat',
             'Apakah tendik memiliki sertifikat kompetensi yang relevan?', null],
        ],
        'rencana rekrutmen dan pengembangan tendik' => [
            ['Rencana Rekrutmen dan Pengembangan Tendik', 'Dokumen',
             'Apakah tersedia rencana rekrutmen tendik jangka menengah?', null],
            ['Laporan Pengembangan Kompetensi Tendik', 'Laporan',
             'Apakah terdapat bukti pengembangan kompetensi tendik?', null],
        ],
        'tenaga kependidikan dengan jumlah dan kualifika' => [
            ['Data Jumlah dan Kualifikasi Tenaga Kependidikan', 'Data LKPS',
             'Apakah jumlah tendik memadai sesuai rasio standar?',
             'Rasio tendik terhadap mahasiswa; kesesuaian kompetensi'],
            ['Struktur Organisasi dan Daftar Tendik', 'Dokumen',
             'Apakah posisi tendik terdefinisi dalam struktur organisasi?', null],
        ],
        'merencanakan penerimaan dan pengeluaran' => [
            ['Rencana Kegiatan dan Anggaran Tahunan (RKAT)', 'Dokumen',
             'Apakah tersedia RKAT yang terencana dan transparan?',
             'RKAT selaras dengan Renstra; disetujui pimpinan PT'],
            ['Laporan Realisasi Anggaran', 'Laporan',
             'Apakah terdapat laporan realisasi anggaran yang akuntabel?', null],
        ],
        'usaha dan menunjukkan hasil-hasilnya untuk meningkatkan' => [
            ['Laporan Keuangan Tahunan', 'Laporan',
             'Apakah laporan keuangan disusun secara transparan dan akuntabel?',
             'Laporan pertanggungjawaban; audited financial statement'],
            ['Data Sumber dan Penggunaan Dana', 'Data LKPS',
             'Apakah terdapat diversifikasi sumber pendanaan?',
             'Proporsi SPP vs sumber lain; trend 3 tahun'],
        ],
        'penyediaan dan pengelolaan serta rencana pengembangan sarana' => [
            ['Data Inventaris Sarana dan Prasarana', 'Data',
             'Apakah sarpras tersedia dan diinventarisasi dengan baik?',
             'Daftar aset; kondisi dan nilai sarpras'],
            ['Rencana Pengembangan Sarana Prasarana', 'Dokumen',
             'Apakah terdapat rencana pengembangan sarpras yang terprogram?', null],
        ],
        'sarana dan prasarana memenuhi standar kebersihan' => [
            ['Laporan Pemeliharaan dan Kondisi Sarpras', 'Laporan',
             'Apakah sarpras memenuhi standar K3 (kebersihan, kesehatan, keselamatan)?',
             'Jadwal pemeliharaan rutin; hasil inspeksi'],
            ['Foto/Dokumentasi Kondisi Sarpras', 'Screenshot',
             'Apakah terdapat dokumentasi visual kondisi sarpras?', null],
        ],
        'penggunaan peta kurikulum untuk menjamin struktur' => [
            ['Dokumen Kurikulum Program Studi', 'Dokumen',
             'Apakah tersedia dokumen kurikulum yang lengkap dan terkini?',
             'Mencakup profil lulusan, CPL, mata kuliah, dan beban studi'],
            ['Peta Kurikulum (Curriculum Map)', 'Dokumen',
             'Apakah peta kurikulum menggambarkan kaitan CPL dan MK secara jelas?', null],
        ],
        'implementasi kurikulum mampu memfasilitasi keterlibatan' => [
            ['RPS Mata Kuliah (minimal 5 sampel)', 'Dokumen',
             'Apakah RPS mencerminkan pembelajaran yang memfasilitasi keterlibatan aktif?',
             'Metode active learning; tugas berbasis proyek/kasus'],
            ['Laporan Pelaksanaan Pembelajaran', 'Laporan',
             'Apakah implementasi kurikulum berjalan sesuai RPS?', null],
        ],
        'materi dan metoda pembelajaran yang mutakhir' => [
            ['Silabus/RPS dengan Referensi 5 Tahun Terakhir', 'Dokumen',
             'Apakah materi pembelajaran menggunakan referensi mutakhir?',
             'Referensi terbaru; metode inovatif (PBL, flipped classroom, dll)'],
            ['Bukti Penggunaan Teknologi dalam Pembelajaran', 'Screenshot',
             'Apakah pembelajaran memanfaatkan teknologi terkini?', null],
        ],
        'evaluasi, perbaikan, dan pengembangan kurikulum' => [
            ['Laporan Evaluasi Kurikulum', 'Laporan',
             'Apakah evaluasi kurikulum dilakukan secara berkala?',
             'Melibatkan stakeholder; berbasis capaian dan tracer study'],
            ['Notulen/Berita Acara Rapat Kurikulum', 'Berita Acara',
             'Apakah perubahan kurikulum didokumentasikan secara resmi?', null],
            ['Dokumen Revisi Kurikulum', 'Dokumen',
             'Apakah terdapat bukti perbaikan kurikulum yang konkret?', null],
        ],
        'analisis dan evaluasi hasil pembelajaran' => [
            ['Laporan Analisis Capaian Pembelajaran per MK', 'Laporan',
             'Apakah terdapat analisis ketercapaian CPL per MK?',
             'Data nilai; analisis kelulusan; mapping CPL-MK'],
            ['Rekap Nilai Mahasiswa per Semester', 'Data LKPS',
             'Apakah data nilai mahasiswa terdokumentasi dan dianalisis?', null],
        ],
        'intervensi sebagai tindak lanjut hasil analisis' => [
            ['Laporan Tindak Lanjut Evaluasi Pembelajaran', 'Laporan',
             'Apakah terdapat tindak lanjut konkret dari hasil analisis?',
             'Remedial, pembimbingan tambahan, penyesuaian metode'],
            ['Bukti Perbaikan Proses Pembelajaran', 'Dokumen',
             'Apakah perbaikan pembelajaran didokumentasikan?', null],
        ],
        'kegiatan dan hasil penelitiannya mampu berkontribusi' => [
            ['Laporan Penelitian Dosen (3 tahun terakhir)', 'Laporan',
             'Apakah penelitian dosen berkontribusi pada pengembangan keilmuan?',
             'Daftar judul, dana, luaran; relevansi dengan visi PS'],
            ['Data Publikasi Ilmiah Dosen', 'Data LKPS',
             'Apakah hasil penelitian dipublikasikan di jurnal/prosiding?',
             'Jurnal nasional/internasional; prosiding; buku'],
        ],
        'pengintegrasian kegiatan, hasil, dan kontribusi peneliti' => [
            ['RPS yang Mencantumkan Referensi Penelitian Dosen', 'Dokumen',
             'Apakah hasil penelitian dosen diintegrasikan ke pembelajaran?',
             'RPS mencantumkan publikasi dosen sebagai referensi'],
            ['Modul/Bahan Ajar Berbasis Penelitian', 'Dokumen',
             'Apakah terdapat modul atau bahan ajar dari hasil penelitian?', null],
        ],
        'kegiatan dan hasil pkm mampu berkontribusi' => [
            ['Laporan PkM Dosen (3 tahun terakhir)', 'Laporan',
             'Apakah PkM dosen berdampak bagi masyarakat dan keilmuan?',
             'Daftar PkM; dana; luaran; bukti dampak di masyarakat'],
            ['Data Luaran PkM (Artikel/Produk/Hak Cipta)', 'Data LKPS',
             'Apakah PkM menghasilkan luaran yang terukur?', null],
        ],
        'pengintegrasian kegiatan, hasil, dan kontribusi pkm' => [
            ['RPS/Modul Berbasis Hasil PkM', 'Dokumen',
             'Apakah hasil PkM diintegrasikan ke dalam proses pembelajaran?',
             'MK berbasis PkM; modul atau bahan ajar dari kegiatan PkM'],
            ['Laporan Integrasi PkM ke Pembelajaran', 'Laporan',
             'Apakah terdapat bukti nyata integrasi PkM ke kurikulum?', null],
        ],
    ];

    public function run(): void
    {
        $akr = StandarAkreditasi::where('nama', 'LAMEMBA')->first();
        if (!$akr) {
            $this->command?->warn('StandarAkreditasi "LAMEMBA" tidak ditemukan.');
            return;
        }

        $j = Jenjang::where('nama', 'S1')->first();
        if (!$j) {
            $this->command?->warn('Jenjang S1 tidak ditemukan.');
            return;
        }

        $indikators = Indikator::whereIn('elemen_id',
            Element::whereIn('standard_id',
                Standard::where('standar_akreditasi_id', $akr->id)
                        ->where('jenjang_id', $j->id)
                        ->pluck('id')
            )->pluck('id')
        )->get(['id', 'nama_indikator']);

        StandarTarget::where('jenjang', 'LAMEMBA')->delete();

        $seq = $created = $fallback = 0;

        foreach ($indikators as $ind) {
            $docs = $this->findDocs(mb_strtolower($ind->nama_indikator));

            if (empty($docs)) {
                $fallback++;
                $seq++;
                StandarTarget::create([
                    'jenjang'            => 'LAMEMBA',
                    'target_kode'        => 'LAMEMBA-' . str_pad($seq, 3, '0', STR_PAD_LEFT),
                    'indikator_id'       => $ind->id,
                    'pertanyaan_nama'    => mb_substr('Apakah ' . $ind->nama_indikator . '?', 0, 255),
                    'dokumen_nama'       => mb_substr($ind->nama_indikator, 0, 100),
                    'dokumen_tipe'       => 'Dokumen',
                    'dokumen_keterangan' => null,
                ]);
                $created++;
                continue;
            }

            foreach ($docs as [$dokNama, $tipe, $pertNama, $ket]) {
                $seq++;
                StandarTarget::create([
                    'jenjang'            => 'LAMEMBA',
                    'target_kode'        => 'LAMEMBA-' . str_pad($seq, 3, '0', STR_PAD_LEFT),
                    'indikator_id'       => $ind->id,
                    'pertanyaan_nama'    => $pertNama,
                    'dokumen_nama'       => $dokNama,
                    'dokumen_tipe'       => $tipe,
                    'dokumen_keterangan' => $ket,
                ]);
                $created++;
            }
        }

        $this->command?->info("LAMEMBA: {$created} targets ({$fallback} indikator pakai fallback).");
    }

    protected function findDocs(string $namaLower): array
    {
        foreach ($this->mapping as $keyword => $docs) {
            if (str_contains($namaLower, mb_strtolower($keyword))) {
                return $docs;
            }
        }
        return [];
    }
}

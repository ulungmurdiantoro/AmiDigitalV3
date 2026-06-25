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
 * Seed standar_targets untuk LAMDIK, LAMINFOKOM, LAMTEKNIK.
 *
 * Setiap indikator menghasilkan 2-4 dokumen target (multi-doc per indikator).
 * Jalankan setelah seeder kriteria masing-masing standar.
 */
class KriteriaOtherTargetSeeder extends Seeder
{
    protected array $akreditasiList = ['LAMDIK', 'LAMINFOKOM', 'LAMTEKNIK'];

    // Keyword (regex) → array of [dokumen_nama, tipe, pertanyaan, keterangan]
    protected array $dokMap = [

        // ── VMTS ────────────────────────────────────────────────────────────────
        'vmts|visi keilmuan|visi dan misi' => [
            ['Dokumen Visi, Misi, Tujuan, dan Sasaran (VMTS)',
             'Dokumen',
             'Apakah tersedia dokumen VMTS yang telah disahkan pimpinan?',
             'SK Penetapan VMTS; selaras dengan visi PT'],
            ['SK Penetapan VMTS',
             'SK',
             'Apakah VMTS ditetapkan secara resmi oleh pimpinan?',
             null],
            ['Bukti Sosialisasi dan Pemahaman VMTS',
             'Laporan',
             'Apakah VMTS telah disosialisasikan kepada sivitas akademika?',
             'Notulen sosialisasi; survei pemahaman VMTS'],
        ],

        // ── Tata Pamong ──────────────────────────────────────────────────────────
        'tata pamong' => [
            ['Statuta dan Dokumen Tata Pamong (OTK)',
             'Kebijakan',
             'Apakah tersedia dokumen tata pamong yang lengkap?',
             'Mencakup statuta, OTK, job description pimpinan dan staf'],
            ['SK Pengangkatan Pimpinan UPPS/PS',
             'SK',
             'Apakah pimpinan UPPS/PS diangkat secara sah?',
             'SK Rektor/Yayasan; menunjukkan legalitas kepemimpinan'],
            ['Laporan Pelaksanaan Tata Pamong',
             'Laporan',
             'Apakah tata pamong berjalan secara kredibel, transparan, dan akuntabel?',
             'Laporan tahunan; rapat pimpinan; audit internal'],
        ],

        // ── Tata Kelola ──────────────────────────────────────────────────────────
        'tata kelola' => [
            ['Renstra UPPS/PS',
             'Dokumen',
             'Apakah tersedia Renstra yang memuat arah pengembangan UPPS/PS?',
             'Renstra minimal 5 tahun; selaras visi PT'],
            ['Rencana Operasional (Renop) Tahunan',
             'Dokumen',
             'Apakah terdapat Renop sebagai penjabaran Renstra tahunan?',
             null],
            ['Laporan Capaian Kinerja Tata Kelola',
             'Laporan',
             'Apakah tata kelola menghasilkan capaian yang terukur?',
             'Laporan IKU; capaian renstra; benchmark'],
        ],

        // ── Kepemimpinan ─────────────────────────────────────────────────────────
        'kepemimpinan' => [
            ['Profil Pimpinan UPPS/PS',
             'Dokumen',
             'Apakah pimpinan memiliki kompetensi kepemimpinan yang memadai?',
             'Kualifikasi akademik, pengalaman manajerial, track record'],
            ['Bukti Keterlibatan Pimpinan dalam Pengambilan Keputusan',
             'Dokumen',
             'Apakah pimpinan aktif memimpin pengambilan keputusan strategis?',
             'Notulen rapat pimpinan; SK kebijakan yang ditandatangani'],
        ],

        // ── SPMI / Penjaminan Mutu ───────────────────────────────────────────────
        'spmi|penjaminan mutu|manajemen mutu|audit mutu' => [
            ['Kebijakan SPMI',
             'Kebijakan',
             'Apakah tersedia kebijakan SPMI yang disahkan pimpinan?',
             'SK Rektor tentang SPMI; komitmen mutu pimpinan'],
            ['Manual SPMI',
             'Dokumen',
             'Apakah Manual SPMI mencakup siklus PPEPP secara lengkap?',
             'Penetapan, Pelaksanaan, Evaluasi, Pengendalian, Peningkatan'],
            ['Standar Mutu UPPS/PS',
             'Dokumen',
             'Apakah tersedia standar mutu yang ditetapkan UPPS/PS?',
             'Standar pendidikan, penelitian, PkM, dan penunjang'],
            ['Laporan Audit Mutu Internal (AMI)',
             'Laporan',
             'Apakah SPMI dijalankan dan dievaluasi secara konsisten?',
             'Laporan AMI terakhir; temuan dan tindak lanjut'],
        ],

        // ── Kerjasama ────────────────────────────────────────────────────────────
        'kerjasama|mou|pks|kerja sama' => [
            ['Dokumen Kerjasama (MoU/PKS) yang Aktif',
             'MoU/PKS',
             'Apakah UPPS/PS memiliki kerjasama yang aktif dan relevan?',
             'Minimal 3 MoU aktif dalam 3 tahun terakhir'],
            ['Laporan Implementasi Kerjasama',
             'Laporan',
             'Apakah kerjasama diimplementasikan secara konkret?',
             'Bukti kegiatan; jumlah mahasiswa/dosen yang terlibat'],
            ['Bukti Manfaat Kerjasama bagi PS',
             'Dokumen',
             'Apakah kerjasama memberikan manfaat nyata bagi PS?',
             'Peningkatan kompetensi; fasilitas; penelitian bersama'],
        ],

        // ── Kurikulum ────────────────────────────────────────────────────────────
        'kurikulum' => [
            ['Dokumen Kurikulum Program Studi',
             'Dokumen',
             'Apakah tersedia dokumen kurikulum yang lengkap dan terkini?',
             'Profil lulusan, CPL, struktur MK, beban studi'],
            ['Peta Kurikulum (Curriculum Map)',
             'Dokumen',
             'Apakah peta kurikulum menggambarkan keterkaitan CPL dan MK?',
             null],
            ['Berita Acara Pengesahan Kurikulum',
             'Berita Acara',
             'Apakah kurikulum disahkan melalui proses yang melibatkan stakeholder?',
             'Notulen workshop; keterlibatan industri/alumni'],
        ],

        // ── RPS ─────────────────────────────────────────────────────────────────
        'rps|rencana pembelajaran semester' => [
            ['RPS Mata Kuliah (sampel representatif)',
             'Dokumen',
             'Apakah RPS disusun sesuai standar dan mencakup komponen lengkap?',
             'CPL-MK, metode, penilaian, referensi mutakhir'],
            ['Laporan Evaluasi RPS',
             'Laporan',
             'Apakah RPS dievaluasi dan diperbarui setiap semester?',
             null],
        ],

        // ── Beban Kerja Dosen ────────────────────────────────────────────────────
        'beban kerja dosen|bkd' => [
            ['Laporan BKD per Semester',
             'Laporan BKD',
             'Apakah beban kerja dosen terdistribusi sesuai Tridharma?',
             'BKD mencakup pendidikan, penelitian, dan PkM'],
            ['Rekap Distribusi BKD Seluruh DTPS',
             'Data LKPS',
             'Apakah distribusi BKD merata dan sesuai standar minimal?',
             'Standar minimal: 12 SKS; maksimal 16 SKS per semester'],
        ],

        // ── Rekrutmen Dosen ──────────────────────────────────────────────────────
        'rekrutmen dosen' => [
            ['Panduan Rekrutmen dan Seleksi Dosen',
             'Pedoman',
             'Apakah tersedia panduan rekrutmen dosen yang mengatur kualifikasi?',
             'Persyaratan kualifikasi akademik dan kompetensi'],
            ['SK Pengangkatan Dosen Tetap',
             'SK',
             'Apakah dosen tetap diangkat secara sah dan terdokumentasi?',
             'SK Rektor/Yayasan; kontrak dosen tetap'],
            ['Dokumen Proses Seleksi Dosen',
             'Dokumen',
             'Apakah proses seleksi dosen terdokumentasi dan transparan?',
             null],
        ],

        // ── Pengembangan Dosen ───────────────────────────────────────────────────
        'pengembangan dosen|sertifikas' => [
            ['Rencana Pengembangan Dosen',
             'Dokumen',
             'Apakah tersedia rencana pengembangan dosen jangka menengah?',
             'Studi lanjut, sertifikasi, pelatihan, jabatan fungsional'],
            ['Laporan Pengembangan Kompetensi Dosen',
             'Laporan',
             'Apakah UPPS memfasilitasi pengembangan kompetensi dosen?',
             'Bukti pelatihan, seminar, workshop yang diikuti DTPS'],
            ['Bukti Pembiayaan Pengembangan Dosen',
             'Dokumen',
             'Apakah tersedia alokasi anggaran pengembangan dosen?',
             null],
        ],

        // ── Kualifikasi Dosen ────────────────────────────────────────────────────
        'dosen tetap|kualifikasi dosen|dtps' => [
            ['Data Kualifikasi Akademik Dosen Tetap (DTPS)',
             'Data LKPS',
             'Apakah DTPS memenuhi kualifikasi akademik minimal (S2/S3)?',
             'Minimal 2 dosen bergelar S3; sesuai bidang keahlian PS'],
            ['Data Jabatan Fungsional DTPS',
             'Data LKPS',
             'Apakah DTPS memiliki jabatan fungsional yang memadai?',
             'Lektor, Lektor Kepala, atau Guru Besar'],
            ['Sertifikat Pendidik (Serdos) Dosen',
             'Sertifikat',
             'Apakah dosen tetap telah tersertifikasi?',
             'Proporsi dosen bersertifikat dari total DTPS'],
        ],

        // ── Tenaga Kependidikan ──────────────────────────────────────────────────
        'tendik|tenaga kependidikan' => [
            ['Data Kualifikasi dan Jumlah Tenaga Kependidikan',
             'Data LKPS',
             'Apakah kualifikasi tendik memenuhi standar kompetensi?',
             'Kualifikasi pendidikan, sertifikasi, jabatan fungsional'],
            ['Laporan Pengembangan Kompetensi Tendik',
             'Laporan',
             'Apakah terdapat program pengembangan kompetensi tendik?',
             null],
        ],

        // ── Penerimaan Mahasiswa ─────────────────────────────────────────────────
        'mahasiswa baru|penerimaan' => [
            ['Data Penerimaan Mahasiswa Baru (3 tahun terakhir)',
             'Data LKPS',
             'Apakah tren penerimaan mahasiswa baru meningkat atau stabil?',
             'Jumlah pendaftar, diterima, dan registrasi per tahun'],
            ['Laporan Seleksi Penerimaan Mahasiswa Baru',
             'Laporan',
             'Apakah proses seleksi PMB berjalan transparan dan bermutu?',
             null],
        ],

        // ── Mahasiswa Aktif ──────────────────────────────────────────────────────
        'mahasiswa aktif|jumlah mahasiswa' => [
            ['Data Mahasiswa Aktif per Angkatan',
             'Data LKPS',
             'Apakah jumlah mahasiswa aktif memenuhi rasio standar?',
             'Tren 3 tahun; rasio per dosen tetap'],
            ['Laporan Monitoring Kemajuan Studi Mahasiswa',
             'Laporan',
             'Apakah kemajuan studi mahasiswa dipantau secara berkala?',
             null],
        ],

        // ── Kelulusan ────────────────────────────────────────────────────────────
        'kelulusan|lulus|tepat waktu' => [
            ['Data Kelulusan Mahasiswa (3 tahun terakhir)',
             'Data LKPS',
             'Apakah angka kelulusan tepat waktu memenuhi standar?',
             'Target minimal 50% lulus tepat waktu'],
            ['Data Lama Studi Rata-Rata',
             'Data LKPS',
             'Apakah lama studi rata-rata sesuai dengan masa studi normatif?',
             null],
        ],

        // ── IPK / Prestasi ───────────────────────────────────────────────────────
        'ipk|indeks prestasi' => [
            ['Data IPK Lulusan (3 tahun terakhir)',
             'Data LKPS',
             'Apakah IPK rata-rata lulusan memenuhi standar kualitas?',
             'Target IPK rata-rata minimal 3.00; tren 3 tahun'],
            ['Distribusi IPK Lulusan',
             'Data LKPS',
             'Apakah distribusi IPK lulusan menunjukkan kualitas akademik yang baik?',
             null],
        ],

        // ── Tracer Study / Alumni ────────────────────────────────────────────────
        'tracer|alumni|pengguna lulusan' => [
            ['Laporan Tracer Study Lulusan',
             'Laporan',
             'Apakah tracer study dilakukan secara rutin dan komprehensif?',
             'Masa tunggu kerja; kesesuaian bidang; kepuasan pengguna'],
            ['Instrumen Tracer Study',
             'Dokumen',
             'Apakah terdapat instrumen tracer study yang valid dan reliabel?',
             null],
            ['Laporan Kepuasan Pengguna Lulusan',
             'Laporan',
             'Apakah pengguna lulusan memberikan penilaian yang baik?',
             'Skala 1-4; aspek: integritas, profesionalisme, kompetensi'],
        ],

        // ── Penelitian ───────────────────────────────────────────────────────────
        'penelitian' => [
            ['Laporan Penelitian Dosen (3 tahun terakhir)',
             'Laporan',
             'Apakah penelitian dosen berkontribusi pada pengembangan keilmuan PS?',
             'Jumlah, dana, sumber dana, relevansi dengan PS'],
            ['Data Publikasi Ilmiah Dosen',
             'Data LKPS',
             'Apakah hasil penelitian dipublikasikan di jurnal/prosiding bereputasi?',
             'Jurnal nasional terakreditasi / internasional bereputasi'],
            ['Rekap Dana dan Sumber Penelitian',
             'Data LKPS',
             'Apakah penelitian mendapatkan dukungan dana yang memadai?',
             null],
        ],

        // ── PkM ─────────────────────────────────────────────────────────────────
        'pkm|pengabdian kepada masyarakat' => [
            ['Laporan PkM Dosen (3 tahun terakhir)',
             'Laporan',
             'Apakah PkM dosen relevan dengan keahlian dan berdampak bagi masyarakat?',
             'Jumlah PkM, mitra, dana, bukti dampak'],
            ['Data Luaran PkM (Artikel, Produk, HaKI)',
             'Data LKPS',
             'Apakah PkM menghasilkan luaran yang terukur dan bermanfaat?',
             null],
        ],

        // ── Publikasi / HaKI ─────────────────────────────────────────────────────
        'publikasi|artikel|jurnal|prosiding' => [
            ['Data Publikasi Ilmiah Dosen 3 Tahun Terakhir',
             'Data LKPS',
             'Apakah dosen aktif mempublikasikan hasil penelitian?',
             'Jurnal nasional terakreditasi dan internasional bereputasi'],
            ['Data Sitasi Publikasi Dosen',
             'Data LKPS',
             'Apakah publikasi dosen mendapat sitasi dari peneliti lain?',
             'Google Scholar, Scopus, SINTA'],
        ],

        // ── HaKI / Paten ─────────────────────────────────────────────────────────
        'haki|paten|karya ilmiah' => [
            ['Data HaKI dan Paten Dosen',
             'Data LKPS',
             'Apakah dosen menghasilkan karya ber-HaKI atau paten?',
             'Hak cipta, paten, merek, desain industri'],
            ['Sertifikat HaKI/Paten',
             'Sertifikat',
             'Apakah HaKI/paten telah terdaftar secara resmi?',
             null],
        ],

        // ── Sitasi / Indeksasi ───────────────────────────────────────────────────
        'sitasi|h-index|scopus|sinta' => [
            ['Data Sitasi dan H-Index Dosen',
             'Data LKPS',
             'Apakah profil dosen di Scopus/SINTA/Scholar menunjukkan impact yang baik?',
             'H-Index, jumlah sitasi, indeksasi internasional'],
            ['Screenshot Profil Dosen di Scopus/SINTA',
             'Screenshot',
             'Apakah data sitasi dosen terdokumentasi dan dapat diverifikasi?',
             null],
        ],

        // ── Sarana & Prasarana ───────────────────────────────────────────────────
        'sarana|prasarana|fasilitas|laborator' => [
            ['Data Sarana dan Prasarana PS',
             'Data',
             'Apakah sarana dan prasarana mencukupi untuk proses pembelajaran?',
             'Ruang kuliah, laboratorium, perpustakaan, sistem IT'],
            ['Laporan Kondisi dan Pemeliharaan Sarpras',
             'Laporan',
             'Apakah sarpras dipelihara secara rutin dan memenuhi standar K3?',
             'Jadwal pemeliharaan; hasil inspeksi'],
            ['Rencana Pengembangan Sarpras',
             'Dokumen',
             'Apakah tersedia rencana pengembangan sarpras jangka menengah?',
             null],
        ],

        // ── Keuangan / Anggaran ──────────────────────────────────────────────────
        'keuangan|anggaran|rkat' => [
            ['Rencana Kegiatan dan Anggaran Tahunan (RKAT)',
             'Dokumen',
             'Apakah tersedia RKAT yang terencana dan selaras Renstra?',
             'Breakdown anggaran per kegiatan Tridharma'],
            ['Laporan Realisasi Anggaran Tahunan',
             'Laporan',
             'Apakah realisasi anggaran terdokumentasi dan akuntabel?',
             null],
            ['Data Sumber Pendanaan UPPS/PS',
             'Data LKPS',
             'Apakah terdapat diversifikasi sumber pendanaan?',
             'SPP, hibah, kerjasama, mandiri; tren 3 tahun'],
        ],

        // ── Evaluasi / Monitoring ────────────────────────────────────────────────
        'evaluasi|monitoring' => [
            ['Laporan Evaluasi Proses Pembelajaran',
             'Laporan',
             'Apakah proses pembelajaran dievaluasi secara berkala?',
             'Evaluasi per semester; umpan balik mahasiswa'],
            ['Laporan Monitoring Ketercapaian IKU/IKT',
             'Laporan',
             'Apakah terdapat sistem monitoring capaian indikator kinerja?',
             null],
        ],

        // ── Perencanaan / Renstra ────────────────────────────────────────────────
        'perencanaan|renstra|renop' => [
            ['Renstra UPPS/PS',
             'Dokumen',
             'Apakah Renstra PS memuat arah pengembangan yang jelas?',
             'Visi, misi, tujuan, sasaran, strategi; 5 tahun'],
            ['Rencana Operasional (Renop)',
             'Dokumen',
             'Apakah Renop disusun sebagai penjabaran tahunan Renstra?',
             null],
            ['Laporan Capaian Renstra',
             'Laporan',
             'Apakah terdapat laporan ketercapaian target Renstra secara berkala?',
             null],
        ],

        // ── Kebijakan / SK ───────────────────────────────────────────────────────
        'kebijakan|peraturan|regulasi' => [
            ['Dokumen Kebijakan UPPS/PS',
             'Kebijakan',
             'Apakah tersedia dokumen kebijakan yang mengatur tata laksana PS?',
             null],
            ['SK dan Peraturan Institusional yang Relevan',
             'SK',
             'Apakah kebijakan ditetapkan melalui SK resmi pimpinan?',
             null],
        ],

        // ── Capaian Pembelajaran / CPL ───────────────────────────────────────────
        'capaian pembelajaran|cpl|kompetensi lulusan' => [
            ['Dokumen CPL Program Studi',
             'Dokumen',
             'Apakah CPL dirumuskan sesuai KKNI dan kebutuhan stakeholder?',
             'CPL mencakup sikap, keahlian umum, keahlian khusus, pengetahuan'],
            ['Matriks Pemetaan CPL-MK',
             'Dokumen',
             'Apakah setiap MK berkontribusi pada pencapaian CPL?',
             null],
            ['Laporan Analisis Ketercapaian CPL',
             'Laporan',
             'Apakah ketercapaian CPL dianalisis dan ditindaklanjuti?',
             'Analisis per MK; gap CPL; perbaikan kurikulum'],
        ],

        // ── Tugas Akhir / Skripsi ────────────────────────────────────────────────
        'tugas akhir|skripsi|tesis|disertasi' => [
            ['Data Tugas Akhir Mahasiswa (3 tahun terakhir)',
             'Data LKPS',
             'Apakah topik tugas akhir relevan dengan bidang keilmuan PS?',
             'Judul, pembimbing, penguji, lama penyelesaian'],
            ['Panduan Penulisan Tugas Akhir',
             'Pedoman',
             'Apakah tersedia panduan tugas akhir yang lengkap?',
             null],
        ],

        // ── Praktik / Magang ─────────────────────────────────────────────────────
        'praktik|magang|pkl|pkn' => [
            ['Laporan Pelaksanaan Praktik Kerja/Magang',
             'Laporan',
             'Apakah program magang/PKL terlaksana sesuai kurikulum?',
             'Tempat magang, durasi, pembimbing, nilai'],
            ['Pedoman Praktik Kerja/Magang',
             'Pedoman',
             'Apakah tersedia panduan pelaksanaan magang yang komprehensif?',
             null],
            ['Data Mitra Industri Tempat Magang',
             'Data LKPS',
             'Apakah terdapat kemitraan industri yang mendukung program magang?',
             null],
        ],

        // ── Luaran / Output ──────────────────────────────────────────────────────
        'luaran|capaian|output tridharma' => [
            ['Rekap Luaran Tridharma (3 tahun terakhir)',
             'Laporan',
             'Apakah UPPS/PS menghasilkan luaran Tridharma yang terukur?',
             'Publikasi, paten, buku, hibah, penghargaan'],
            ['Data Prestasi Mahasiswa dan Dosen',
             'Data LKPS',
             'Apakah terdapat prestasi mahasiswa/dosen di tingkat nasional/internasional?',
             null],
        ],

        // ── Pedoman / SOP ────────────────────────────────────────────────────────
        'pedoman|panduan|prosedur|sop' => [
            ['Dokumen SOP Pelayanan Akademik',
             'Pedoman',
             'Apakah tersedia SOP yang mengatur proses akademik secara lengkap?',
             null],
            ['Bukti Implementasi SOP',
             'Laporan',
             'Apakah SOP diterapkan secara konsisten dalam operasional PS?',
             null],
        ],

        // ── Proses Pembelajaran ──────────────────────────────────────────────────
        'pembelajaran|proses belajar|proses pembelajaran' => [
            ['Laporan Pelaksanaan Pembelajaran per Semester',
             'Laporan',
             'Apakah proses pembelajaran terlaksana sesuai RPS?',
             'Daftar hadir; berita acara perkuliahan; evaluasi dosen'],
            ['Data Kehadiran Dosen dan Mahasiswa',
             'Data LKPS',
             'Apakah tingkat kehadiran dosen dan mahasiswa memenuhi standar?',
             'Minimum kehadiran dosen 80%; mahasiswa 75%'],
        ],

        // ── Nilai / Prestasi ─────────────────────────────────────────────────────
        'nilai|prestasi akademik|hasil belajar' => [
            ['Rekap Nilai Mahasiswa per Semester',
             'Data LKPS',
             'Apakah distribusi nilai mahasiswa mencerminkan kualitas pembelajaran?',
             'Distribusi A, B, C, D, E; tidak tumpuk di nilai rendah'],
            ['Laporan Analisis Ketercapaian Nilai',
             'Laporan',
             'Apakah hasil belajar mahasiswa dievaluasi dan ditindaklanjuti?',
             null],
        ],

    ];

    public function run(): void
    {
        foreach ($this->akreditasiList as $namaAkr) {
            $akr = StandarAkreditasi::where('nama', $namaAkr)->first();
            if (!$akr) {
                $this->command?->warn("'{$namaAkr}' tidak ditemukan, lewati.");
                continue;
            }

            $this->command?->info(PHP_EOL . "=== {$namaAkr} ===");
            $this->seedForAkreditasi($akr, $namaAkr);
        }
    }

    protected function seedForAkreditasi(StandarAkreditasi $akr, string $namaAkr): void
    {
        $jenjangIds = Standard::where('standar_akreditasi_id', $akr->id)
            ->distinct()
            ->pluck('jenjang_id');

        foreach ($jenjangIds as $jenjangId) {
            $jenjang = Jenjang::find($jenjangId);
            if (!$jenjang) continue;

            $jenjangKey = $namaAkr . ' ' . $jenjang->nama;
            $prefix     = mb_substr(strtoupper(str_replace([' ', '-'], '', $namaAkr . $jenjang->nama)), 0, 20);

            $indikators = Indikator::whereIn('elemen_id',
                Element::whereIn('standard_id',
                    Standard::where('standar_akreditasi_id', $akr->id)
                            ->where('jenjang_id', $jenjangId)
                            ->pluck('id')
                )->pluck('id')
            )->get(['id', 'nama_indikator']);

            StandarTarget::where('jenjang', $jenjangKey)->delete();

            $seq = $created = 0;
            foreach ($indikators as $ind) {
                $docs = $this->matchDocs($ind->nama_indikator);

                foreach ($docs as [$dokNama, $tipe, $pertNama, $ket]) {
                    $seq++;
                    StandarTarget::create([
                        'jenjang'            => $jenjangKey,
                        'target_kode'        => $prefix . '-' . str_pad($seq, 3, '0', STR_PAD_LEFT),
                        'indikator_id'       => $ind->id,
                        'pertanyaan_nama'    => $pertNama ?? mb_substr('Apakah ' . $ind->nama_indikator . '?', 0, 255),
                        'dokumen_nama'       => mb_substr($dokNama, 0, 255),
                        'dokumen_tipe'       => $tipe,
                        'dokumen_keterangan' => $ket,
                    ]);
                    $created++;
                }
            }

            $this->command?->info("  {$jenjangKey}: {$created} targets.");
        }
    }

    // Kembalikan array of [dokumen_nama, tipe, pertanyaan, keterangan]
    protected function matchDocs(string $nama): array
    {
        $nl = mb_strtolower($nama);
        foreach ($this->dokMap as $pattern => $docs) {
            if (preg_match('/' . $pattern . '/ui', $nl)) {
                return $docs;
            }
        }

        // Fallback 1 dokumen
        $tipe = 'Dokumen';
        if (preg_match('/laporan|evaluasi|hasil|kinerja/ui', $nl))   $tipe = 'Laporan';
        if (preg_match('/data |jumlah |rasio|rekapitulas/ui', $nl))  $tipe = 'Data LKPS';
        if (preg_match('/kebijakan|sk |surat keputusan/ui', $nl))    $tipe = 'Kebijakan';
        if (preg_match('/sertifikat/ui', $nl))                        $tipe = 'Sertifikat';

        return [[
            mb_substr($nama, 0, 100),
            $tipe,
            mb_substr('Apakah ' . $nama . '?', 0, 255),
            null,
        ]];
    }
}

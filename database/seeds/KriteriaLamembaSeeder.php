<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\StandarAkreditasi;
use App\Models\Jenjang;
use App\Models\Standard;
use App\Models\Element;
use App\Models\Indikator;
use App\Models\BuktiStandar;

/**
 * Kriteria akreditasi LAMEMBA (Instrumen untuk Status Terakreditasi).
 * Sumber: database/data/Panduan Penilaian untuk Status Terakreditasi (280126).pdf
 *
 * LAMEMBA hanya punya SATU instrumen untuk SEMUA jenjang, jadi strukturnya
 * ditanam sekali di sini lalu otomatis direplikasi ke setiap jenjang —
 * tidak perlu menyiapkan dokumen/seeder terpisah per jenjang.
 *
 * 5 kriteria, 12 dimensi, 29 indikator.
 */
class KriteriaLamembaSeeder extends Seeder
{
    /**
     * Jenjang tujuan. KOSONG = semua jenjang yang ada di tabel `jenjangs`.
     * Isi mis. ['S1','S2','S3','D3','S1 Terapan'] kalau ingin dibatasi.
     */
    protected array $jenjangs = [];

    /** Kriteria => Dimensi => daftar Indikator */
    protected array $structure = [
        'Tata Pamong dan Tata Kelola' => [
            'Tata Pamong' => [
                'UPPS/PS menunjukkan struktur dan proses tata pamong.',
                'UPPS/PS menunjukkan bukti dilaksanakannya proses pengawasan, pembentukan sinergi, penyediaan sumber daya, penjagaan dan penguatan nilai-nilai yang mengacu pada misi dan visi institusi dengan efektif dan efisien.',
            ],
            'Tata Kelola' => [
                'UPPS/PS menunjukkan bukti pelaksanaan perencanaan, pengorganisasian, pengarahan, dan pengendalian usaha untuk mendapatkan, mengembangkan, dan memanfaatkan sumber daya untuk mewujudkan visi, mencapai tujuan dan sasarannya.',
                'UPPS/PS menunjukkan bukti bahwa sistem tata kelola mampu mendorong UPPS/PS menjalankan tugas dan kewajibannya secara efektif, efisien, akuntabel, bertanggung jawab, transparan, adil, dan terhindar dari konflik kepentingan.',
                'UPPS/PS menjalankan sistem manajemen mutu internal yang diimplementasikan secara konsisten, efektif dan efisien serta melaporkan hasil penjaminan mutu secara berkala untuk tindak lanjut peningkatan mutu UPPS dan PS dalam menjalankan Tridharma.',
            ],
        ],
        'Pengelolaan Dosen dan Tenaga Kependidikan' => [
            'Kecukupan dan Kualifikasi Dosen' => [
                'UPPS/PS menunjukkan bukti penetapan dan penggunaan kriteria dalam menentukan kualifikasi dosen untuk mendukung fokus Tridharma dengan memerhatikan SN Dikti, SAN-Dikti, misi, visi, tujuan dan sasaran, serta strategi UPPS/PS yang berkaitan dengan tingkat pendidikan, jenjang jabatan akademik, bidang keilmuan, kepakaran, dan rekognisi dosen dengan jumlah yang cukup (minimal lima dosen tetap) sesuai fokus Tridharma Perguruan Tinggi.',
                'UPPS menunjukkan bukti penggunaan matriks yang menggambarkan rencana dan pelaksanaan penugasan dosen di berbagai PS yang dikelolanya.',
                'UPPS/PS menerapkan beban kerja dosen yang konsisten dengan fokus Tridharma.',
            ],
            'Pengelolaan Dosen' => [
                'UPPS/PS menunjukkan bukti pelaksanaan rencana rekrutmen dan pengembangan dosen secara terstruktur dan berkelanjutan, sehingga memiliki dosen dengan jumlah dan kualifikasi sesuai dengan kebutuhan UPPS/PS dan misi, visi, tujuan dan sasaran, serta strategi.',
                'UPPS menunjukkan bukti telah memberi dukungan dan fasilitas secara terstruktur dan berkelanjutan kepada dosen untuk memajukan pendidikan, ilmu pengetahuan, praktik profesional, kerjasama/keterlibatan dan rekognisi di bidang EMBA.',
                'UPPS/PS menunjukkan bukti telah melakukan evaluasi proses secara sistematik, terstruktur, dan berkelanjutan terhadap rekrutmen, pengembangan dosen, dan bukti pengukuran kinerja dosen dalam bidang pendidikan, penelitian, dan PKM.',
            ],
            'Kecukupan dan Kualifikasi Tenaga Kependidikan' => [
                'UPPS/PS menunjukkan bukti telah memiliki dan menggunakan kriteria untuk menentukan kualifikasi dan jumlah tenaga kependidikan (minimal dua orang untuk layanan dan satu orang untuk perpustakaan) dengan memerhatikan SN Dikti untuk mendukung kegiatan UPPS/PS dalam mencapai misi, visi, tujuan dan sasaran, serta strategi.',
                'UPPS/PS menunjukkan bukti bahwa kualifikasi tenaga kependidikan (pendidikan dan kompetensi) sesuai dengan tugas yang diembannya.',
            ],
            'Pengelolaan Tenaga Kependidikan' => [
                'UPPS/PS menunjukkan bukti memiliki dan melaksanakan rencana rekrutmen dan pengembangan tenaga kependidikan secara sistematik, terstruktur, dan berkelanjutan.',
                'UPPS/PS menunjukkan bukti memiliki tenaga kependidikan dengan jumlah dan kualifikasi sesuai dengan kebutuhan UPPS/PS dan selaras dengan misi, visi, tujuan dan sasaran, serta strategi.',
            ],
        ],
        'Keuangan dan Sarana Prasarana' => [
            'Keuangan' => [
                'UPPS/PS menunjukkan bukti telah merencanakan penerimaan dan pengeluaran/pemanfaatan sumber keuangan untuk mendukung, mempertahankan, dan meningkatkan kualitas layanan, terutama yang berkaitan dengan pemenuhan kebutuhan operasional pendidikan, penelitian dan pengabdian kepada masyarakat serta investasi yang selaras dengan misi, visi, tujuan dan sasaran, serta strategi.',
                'UPPS/PS menunjukkan bukti telah melakukan usaha dan menunjukkan hasil-hasilnya untuk menjamin keberlanjutan sumber daya keuangan.',
            ],
            'Sarana Prasarana' => [
                'UPPS/PS menunjukkan bukti penyediaan dan pengelolaan serta rencana pengembangan sarana dan prasarana yang dapat dimanfaatkan oleh mahasiswa dan dosen untuk kegiatan pendidikan, penelitian, pengabdian kepada masyarakat dan oleh tenaga kependidikan untuk mendukung kegiatan pendidikan, penelitian dan pengabdian kepada masyarakat.',
                'UPPS/PS menunjukkan bukti bahwa sarana dan prasarana memenuhi standar kebersihan, kesehatan, keamanan, dan keselamatan, serta memerhatikan kesetaraan gender dan ramah difabel.',
            ],
        ],
        'Pendidikan dan Pengajaran' => [
            'Kurikulum' => [
                'UPPS/PS menunjukkan bukti penggunaan peta kurikulum untuk menjamin struktur mata kuliah dan kegiatan pembelajaran konsisten dan relevan dengan kompetensi (CPL) yang diharapkan dan selaras dengan misi, visi, tujuan dan sasaran, serta strategi.',
                'UPPS/PS menunjukkan bukti implementasi kurikulum mampu memfasilitasi keterlibatan aktif mahasiswa dan dosen dalam kegiatan belajar mengajar.',
                'UPPS/PS menunjukkan bukti penggunaan materi dan metoda pembelajaran yang mutakhir dan relevan dengan kebutuhan EMBA saat ini dan di masa depan, selaras dengan misi, visi, tujuan dan sasaran, serta strategi untuk mencapai kompetensi (CPL) yang ditetapkan.',
                'UPPS/PS menunjukkan bukti evaluasi, perbaikan, dan pengembangan kurikulum agar sesuai dan relevan dengan perkembangan ilmu pengetahuan, praktik profesional, dan tantangan di masa yang akan datang dengan melibatkan pemangku kepentingan.',
            ],
            'Jaminan Pembelajaran' => [
                'UPPS/PS menunjukkan bukti analisis dan evaluasi hasil pembelajaran.',
                'UPPS/PS menunjukkan bukti intervensi sebagai tindak lanjut hasil analisis dan evaluasi hasil pembelajaran.',
            ],
        ],
        'Penelitian dan Pengabdian kepada Masyarakat' => [
            'Penelitian' => [
                'UPPS/PS menunjukkan bukti bahwa kegiatan dan hasil penelitiannya mampu berkontribusi dalam meningkatkan kompetensi dosen.',
                'UPPS/PS menunjukkan bukti pengintegrasian kegiatan, hasil, dan kontribusi penelitian dalam evaluasi kinerja dosen.',
            ],
            'Pengabdian kepada Masyarakat' => [
                'UPPS/PS menunjukkan bukti bahwa kegiatan dan hasil PKM mampu berkontribusi dalam meningkatkan kompetensi dosen dan masyarakat.',
                'UPPS/PS menunjukkan bukti pengintegrasian kegiatan, hasil, dan kontribusi PKM dalam evaluasi kinerja dosen.',
            ],
        ],
    ];

    /** Deskripsi tiap dimensi -> kolom elements.deskripsi. Sumber: PDF LAMEMBA. */
    protected array $dimensiDeskripsi = [
        'Tata Pamong' => 'Dimensi tata pamong mendeskripsikan proses dan hasil pengasuhan (parenting), yaitu pengawasan, pembentukan sinergi, penyediaan sumber daya, penjagaan dan penguatan nilai-nilai yang mengacu pada misi dan visi institusi.',
        'Tata Kelola' => 'Dimensi tata kelola mendeskripsikan proses perencanaan, pengorganisasian, pengarahan, dan pengendalian usaha untuk mendapatkan, mengembangkan, dan memanfaatkan sumber daya sehingga program studi dapat menjalankan tugas dan kewajibannya secara efektif, efisien, akuntabel, bertanggung jawab, transparan, adil, dan terhindar dari konflik kepentingan.',
        'Kecukupan dan Kualifikasi Dosen' => 'Dimensi kecukupan dan kualifikasi dosen mendeskripsikan kemampuan UPPS/PS dalam menyediakan dosen dengan jumlah dan kualifikasi yang sesuai dengan fokus Tridharma Perguruan Tinggi.',
        'Pengelolaan Dosen' => 'Dimensi pengelolaan dosen mendeskripsikan proses yang sistematis, terstruktur, dan berkelanjutan yang dilakukan oleh UPPS/PS untuk mendapatkan, mengembangkan, dan menugaskan dosen untuk mendukung kegiatan pendidikan, penelitian, dan PKM yang sesuai dengan misi, visi, tujuan dan sasaran, serta strategi UPPS/PS.',
        'Kecukupan dan Kualifikasi Tenaga Kependidikan' => 'Dimensi kecukupan dan kualifikasi tenaga kependidikan mendeskripsikan kemampuan UPPS/PS dalam menyediakan tenaga kependidikan dengan jumlah dan kualifikasi yang sesuai dengan tugas pekerjaan untuk mendukung misi, visi, tujuan dan sasaran, serta strategi UPPS/PS.',
        'Pengelolaan Tenaga Kependidikan' => 'Dimensi pengelolaan tenaga kependidikan mendeskripsikan proses yang sistematis, terstruktur, dan berkelanjutan yang dilakukan oleh UPPS/PS untuk mendapatkan, mengembangkan, dan menugaskan tenaga kependidikan untuk mendukung kegiatan UPPS/PS dalam mencapai misi, visi, tujuan dan sasaran, serta strategi.',
        'Keuangan' => 'Dimensi keuangan mendeskripsikan proses yang sistematis, terstruktur, dan berkelanjutan yang dilakukan oleh UPPS/PS untuk mendapatkan dan memanfaatkan sumber keuangan untuk mendukung kegiatan UPPS/PS dalam mencapai misi, visi, tujuan dan sasaran, serta strategi.',
        'Sarana Prasarana' => 'Dimensi sarana dan prasarana mendeskripsikan proses yang sistematis, terstruktur, dan berkelanjutan yang dilakukan oleh UPPS/PS untuk mendapatkan dan memanfaatkan sarana dan prasarana untuk mendukung kegiatan UPPS/PS dalam mencapai misi, visi, tujuan dan sasaran, serta strategi.',
        'Kurikulum' => 'Dimensi kurikulum mendeskripsikan pengelolaan kurikulum yang dilakukan oleh UPPS/PS yang mencakup perencanaan, pelaksanaan, evaluasi, perbaikan, dan pengembangan kurikulum agar relevan dengan lingkungan internal, eksternal, dan sesuai dengan CPL dan misi, visi, tujuan dan sasaran, serta strategi.',
        'Jaminan Pembelajaran' => 'Dimensi jaminan pembelajaran mendeskripsikan proses yang sistematis, terstruktur, dan berkelanjutan yang dilakukan oleh UPPS/PS untuk menjamin mahasiswa mendapatkan dan mampu mengembangkan kompetensi (CPL) yang diharapkan.',
        'Penelitian' => 'Dimensi penelitian mendeskripsikan kegiatan dan hasil penelitian UPPS/PS bagi peningkatan kompetensi dosen.',
        'Pengabdian kepada Masyarakat' => 'Dimensi PKM mendeskripsikan kegiatan dan hasil PKM UPPS/PS bagi peningkatan kompetensi dosen.',
    ];

    /** Bukti pemeriksaan per kriteria -> tabel bukti_standars. Sumber: PDF LAMEMBA. */
    protected array $buktiPerKriteria = [
        'Tata Pamong dan Tata Kelola' => [
            'Statuta atau pedoman dasar penyelenggaraan kegiatan',
            'Profil dan kebijakan Perguruan Tinggi',
            'Rencana Induk Pengembangan (RIP)',
            'Rencana Strategis UPPS',
            'Susunan Organisasi dan Tata Kerja (SOTK)',
            'Bukti pelaksanaan Good University Governance (contoh: panduan kode etik dosen, tenaga kependidikan dan mahasiswa)',
            'Bukti terkait Sistem Penjaminan Mutu Internal (SPMI)',
        ],
        'Pengelolaan Dosen dan Tenaga Kependidikan' => [
            'Data profil dosen tetap dan tenaga kependidikan',
            'Data profil dosen tidak tetap',
            'Bukti perencanaan sumber daya manusia, baik dosen maupun tenaga kependidikan, yang memuat kriteria kualifikasi dan kecukupan SDM, rencana pengembangan SDM, pemetaan jenjang karir SDM sesuai fokus Tridharma',
            'Bukti matriks penugasan dosen sesuai kebutuhan dan kelayakan serta pemenuhan beban kerja dan pengelolaan kinerja dosen',
        ],
        'Keuangan dan Sarana Prasarana' => [
            'Rencana kerja dan anggaran tahunan',
            'Laporan realisasi keuangan tahunan',
            'Bukti perencanaan pengembangan sarana dan prasarana',
            'Bukti pengelolaan dan pemanfaatan sarana dan prasarana',
        ],
        'Pendidikan dan Pengajaran' => [
            'Penjelasan yang memuat Tujuan Pendidikan Program Studi, profil lulusan, dan capaian pembelajaran',
            'Pengembangan Kurikulum untuk mencapai Tujuan Pendidikan Program Studi, Profil lulusan, rubrik dan pengukuran capaian pembelajaran',
            'Hasil pembahasan kurikulum dengan semua pemangku kepentingan (pimpinan UPPS, dosen PS, mahasiswa, alumni, industri)',
            'Hasil survey pengguna, FGD dengan alumni/industri, atau tracer study, dan metode lainnya',
            'Hasil evaluasi, implementasi, dan intervensi perbaikan kurikulum',
        ],
        'Penelitian dan Pengabdian kepada Masyarakat' => [
            'Penjelasan terkait keterlibatan dosen pada penelitian sesuai bidang ilmu',
            'Penjelasan terkait keterlibatan dosen pada kegiatan PkM',
            'Bukti sumber pendanaan penelitian dan PkM',
            'Bukti hasil penelitian dan PkM digunakan untuk mendukung proses belajar mengajar',
            'Bukti kerjasama terkait penelitian dan PkM',
        ],
    ];

    public function run(): void
    {
        $akreditasi = StandarAkreditasi::where('nama', 'LAMEMBA')->first();
        if (!$akreditasi) {
            $this->command?->warn('StandarAkreditasi "LAMEMBA" belum ada. Jalankan StandarAkreditasiSeeder dulu. Dilewati.');
            return;
        }

        $jenjangs = empty($this->jenjangs)
            ? Jenjang::all()
            : Jenjang::whereIn('nama', $this->jenjangs)->get();

        if ($jenjangs->isEmpty()) {
            $this->command?->warn('Belum ada data jenjang. Jalankan JenjangSeeder dulu. Dilewati.');
            return;
        }

        foreach ($jenjangs as $jenjang) {
            $cStd = $cEl = $cInd = $cBukti = 0;

            foreach ($this->structure as $kriteria => $dimensiList) {
                $standard = Standard::updateOrCreate(
                    [
                        'standar_akreditasi_id' => $akreditasi->id,
                        'jenjang_id'            => $jenjang->id,
                        'nama'                  => $kriteria,
                    ],
                    ['deskripsi' => 'Kriteria ' . $kriteria . ' mencakup dimensi: ' . implode(', ', array_keys($dimensiList)) . '.']
                );
                if ($standard->wasRecentlyCreated) $cStd++;

                foreach ($dimensiList as $dimensi => $indikators) {
                    $element = Element::updateOrCreate(
                        ['standard_id' => $standard->id, 'nama' => $dimensi],
                        ['deskripsi'   => $this->dimensiDeskripsi[$dimensi] ?? null]
                    );
                    if ($element->wasRecentlyCreated) $cEl++;

                    foreach (array_values($indikators) as $i => $teks) {
                        $ind = Indikator::firstOrCreate(
                            ['elemen_id' => $element->id, 'nama_indikator' => $teks],
                            ['indikator_kode' => (string) ($i + 1), 'kategori' => $dimensi]
                        );
                        if ($ind->wasRecentlyCreated) $cInd++;
                    }
                }

                foreach ($this->buktiPerKriteria[$kriteria] ?? [] as $buktiNama) {
                    $bukti = BuktiStandar::updateOrCreate(
                        ['standard_id' => $standard->id, 'nama' => $buktiNama],
                        []
                    );
                    if ($bukti->wasRecentlyCreated) $cBukti++;
                }
            }

            $this->command?->info("  LAMEMBA {$jenjang->nama}: +{$cStd} standar, +{$cEl} elemen, +{$cInd} indikator, +{$cBukti} bukti");
        }
    }
}

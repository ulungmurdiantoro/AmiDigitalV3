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
 * Kriteria akreditasi LAMEMBA — Instrumen Akreditasi Unggul (IAU).
 * Sumber: database/data/LAMEMBA/DL - 9 Panduan Penilaian Akreditasi Unggul (611).pdf
 *
 * Berbeda dengan instrumen Terakreditasi (5 kriteria, 12 dimensi, 29 indikator),
 * instrumen Unggul memiliki:
 *   - 7 kriteria (tambahan: Orientasi Strategis & Pengelolaan Mahasiswa)
 *   - 21 dimensi
 *   - 58 indikator (penilaian: melampaui / tidak melampaui SN Dikti)
 *
 * Syarat Terakreditasi Unggul 2 tahun : ≥70% (40) indikator melampaui + 8 syarat perlu
 * Syarat Terakreditasi Unggul 5 tahun : ≥90% (52) indikator melampaui + 8 syarat perlu
 */
class KriteriaLamembaUnggulSeeder extends Seeder
{
    /** Jenjang tujuan. KOSONG = semua jenjang yang ada di tabel `jenjangs`. */
    protected array $jenjangs = [];

    /** Kriteria => Dimensi => daftar Indikator */
    protected array $structure = [
        'Orientasi Strategis' => [
            'Misi' => [
                'UPPS/PS menunjukkan bukti pencapaian misinya yang sesuai dengan pemangku kepentingan yang dilayani, cakupan layanan yang disediakan, hasil dan kontribusi yang diharapkan berdasar nilai-nilai dan keyakinan yang menjadi landasan moral bagi keputusan, kegiatan, dan kontribusi UPPS/PS.',
                'UPPS/PS menunjukkan bukti bahwa misi disusun dan ditetapkan dengan melibatkan pemangku kepentingan.',
                'UPPS/PS menunjukkan bukti bahwa misi ditinjau dan dievaluasi agar tetap relevan dengan kebutuhan pemangku kepentingan pada saat ini dan di masa datang.',
                'UPPS/PS menunjukkan bukti bahwa misi telah digunakan sebagai landasan dan pedoman bagi kebijakan, keputusan, kegiatan, hasil, dan kontribusinya.',
            ],
            'Visi' => [
                'UPPS/PS menunjukkan bukti pencapaian visi yang selaras dengan visi institusi.',
                'UPPS/PS merumuskan visi dengan jelas, realistis, kredibel, dan selaras dengan visi institusi.',
                'UPPS/PS menunjukkan bukti bahwa visi mampu menjadi standar kinerja UPPS/PS, dosen, tenaga kependidikan, dan mahasiswa.',
                'UPPS/PS menunjukkan bukti proses dan hasil evaluasi relevansi visi yang memerhatikan arah perkembangan lingkungan internal dan eksternal dengan melibatkan pemangku kepentingan.',
                'UPPS/PS menunjukkan bukti bahwa visi telah digunakan sebagai landasan dan pedoman atas kebijakan, keputusan, kegiatan, hasil, dan kontribusinya.',
            ],
            'Tujuan dan Sasaran' => [
                'UPPS/PS menunjukkan bukti pencapaian tujuan yang diturunkan dari misi dan visi serta dievaluasi dan ditinjau ulang secara berkala agar relevan dengan kebutuhan pemangku kepentingan, serta selaras dengan arah perkembangan lingkungan internal dan eksternal.',
                'UPPS/PS menunjukkan bukti pencapaian sasaran yang diturunkan dari tujuan dan dinyatakan secara spesifik, yaitu dengan menetapkan ukuran pencapaian, waktu, dan pemangku kepentingan sasaran.',
                'UPPS/PS menunjukkan upaya dan tingkat pencapaian tujuan dan sasaran.',
            ],
            'Strategi' => [
                'UPPS/PS menunjukkan bukti dalam menjalankan strateginya yang sesuai dengan misi, visi, tujuan dan sasarannya serta mengintegrasikan manajemen risiko.',
                'UPPS/PS menunjukkan bukti bahwa strategi ditetapkan dan dilaksanakan dengan mengintegrasikan manajemen risiko.',
                'UPPS/PS menunjukkan bukti bahwa perancangan dan pelaksanaan strategi melibatkan pemangku kepentingan dalam mendapatkan, mengembangkan, dan memanfaatkan sumber daya dengan memerhatikan keefektifan dan efisiensi.',
            ],
        ],
        'Tata Pamong dan Tata Kelola' => [
            'Tata Pamong' => [
                'UPPS/PS menunjukkan struktur dan proses tata pamong.',
                'UPPS/PS menunjukkan bukti dilaksanakannya proses pengawasan, pembentukan sinergi, penyediaan sumber daya, penjagaan, dan penguatan nilai-nilai yang mengacu pada misi dan visi institusi dengan efektif dan efisien.',
            ],
            'Tata Kelola' => [
                'UPPS/PS menunjukkan bukti pelaksanaan perencanaan, pengorganisasian, pengarahan, dan pengendalian usaha untuk mendapatkan, mengembangkan, dan memanfaatkan sumber daya untuk mewujudkan visi, mencapai tujuan dan sasarannya.',
                'UPPS/PS menunjukkan bukti bahwa sistem tata kelola mampu mendorong UPPS/PS menjalankan tugas dan kewajibannya secara efektif, efisien, akuntabel, bertanggung jawab, transparan, adil, dan terhindar dari konflik kepentingan.',
                'UPPS/PS menjalankan sistem manajemen mutu internal yang diimplementasikan secara konsisten, efektif, dan efisien, serta melaporkan hasil penjaminan mutu secara berkala untuk tindak lanjut peningkatan mutu UPPS dan PS dalam menjalankan Tridharma.',
            ],
        ],
        'Pengelolaan Mahasiswa' => [
            'Penerimaan Mahasiswa' => [
                'UPPS/PS menunjukkan bukti bahwa penerimaan mahasiswa dilaksanakan secara transparan dan selaras dengan misi, visi, tujuan dan sasaran, strategi, nilai-nilai dan profil/kompetensi lulusan yang diharapkan.',
                'UPPS/PS menunjukkan bukti bahwa pelaksanaan dan hasil penerimaan mahasiswa bersifat inklusif, afirmatif, adil, dan mempertimbangkan asas pemerataan.',
            ],
            'Layanan Akademik Mahasiswa' => [
                'UPPS/PS menunjukkan bukti tingkat penggunaan (partisipasi pengguna) modalitas dan pedagogi yang sesuai dengan kompetensi/CPL mahasiswa, serta penggunaan teknologi dan AI.',
                'UPPS/PS menunjukkan bukti tingkat penggunaan (partisipasi pengguna) fasilitas/dukungan pada kegiatan unit mahasiswa yang selaras dengan misi, visi, tujuan dan sasaran, serta strategi UPPS/PS.',
            ],
            'Kinerja Akademik Mahasiswa' => [
                'UPPS/PS menunjukkan bukti kinerja akademik mahasiswa yang selaras dengan tujuan pendidikan Program Studi dan Standar Pendidikan Tinggi UPPS/PS, yang diukur dengan berbagai indikator, antara lain: IPK, masa studi, dan hasil keterlibatan mahasiswa dalam kegiatan intrakurikuler maupun ekstrakurikuler yang menunjang pengembangan kompetensi mahasiswa.',
            ],
            'Kesejahteraan Mahasiswa' => [
                'UPPS/PS menunjukkan bukti pemanfaatan layanan kesehatan fisik dan mental serta fasilitas belajar dan proses belajar yang memerhatikan kesejahteraan fisik dan mental mahasiswa.',
                'UPPS/PS menunjukkan bukti pemanfaatan fasilitas belajar, olahraga, kesehatan, kesenian, kantin, dan/atau fasilitas lainnya yang sesuai misi, visi, tujuan dan sasaran, serta strategi, yang memenuhi standar kebersihan, kesehatan, keamanan, dan keselamatan, serta memerhatikan kesetaraan gender dan ramah difabel.',
                'UPPS/PS menunjukkan bukti ketersediaan kebijakan, peraturan, dan tindakan yang menjamin lingkungan belajar terbebas dari berbagai tindak diskriminasi, pelecehan, perundungan, dan kekerasan.',
            ],
            'Pengembangan Karir Mahasiswa' => [
                'UPPS/PS menunjukkan bukti memiliki rencana dan melaksanakan program yang mendukung pengembangan karir mahasiswa, yang antara lain, dapat berupa pembekalan bagi mahasiswa untuk memasuki dunia kerja, pelaksanaan bursa kerja, dan penyaluran lulusan.',
            ],
        ],
        'Pengelolaan Dosen dan Tenaga Kependidikan' => [
            'Kecukupan dan Kualifikasi Dosen' => [
                'UPPS/PS menunjukkan bukti penetapan dan penggunaan kriteria dalam menentukan kualifikasi dosen untuk mendukung fokus Tridharma dengan memerhatikan SN Dikti, SAN-Dikti, misi, visi, tujuan dan sasaran, serta strategi UPPS/PS yang berkaitan dengan tingkat pendidikan, jenjang jabatan akademik, bidang keilmuan, kepakaran, dan rekognisi dosen dengan jumlah yang cukup sesuai fokus Tridharma Perguruan Tinggi.',
                'UPPS menunjukkan bukti penggunaan matriks yang menggambarkan rencana dan pelaksanaan penugasan dosen di berbagai PS yang dikelolanya.',
                'UPPS/PS menerapkan beban kerja dosen (dosen tetap, dosen tidak tetap/praktisi) yang konsisten dengan fokus Tridharma.',
            ],
            'Pengelolaan Dosen' => [
                'UPPS/PS menunjukkan bukti pelaksanaan rencana rekrutmen dan pengembangan dosen secara terstruktur, dan berkelanjutan, sehingga memiliki dosen dengan jumlah dan kualifikasi sesuai dengan kebutuhan UPPS/PS dan misi, visi, tujuan dan sasaran, serta strategi.',
                'UPPS menunjukkan bukti telah memberi dukungan dan fasilitas secara terstruktur dan berkelanjutan kepada dosen untuk memajukan pendidikan, ilmu pengetahuan, praktik profesional, kerjasama/keterlibatan, dan rekognisi di bidang EMBA.',
                'UPPS/PS menunjukkan bukti pengembangan dosen secara sistematik, terstruktur, dan berkelanjutan dalam bidang pendidikan.',
            ],
            'Kecukupan dan Kualifikasi Tenaga Kependidikan' => [
                'UPPS/PS menunjukkan bukti telah memiliki dan menggunakan kriteria untuk menentukan kualifikasi dan jumlah tenaga kependidikan dengan memerhatikan SN Dikti untuk mendukung kegiatan UPPS/PS dalam mencapai misi, visi, tujuan dan sasaran, serta strategi.',
                'UPPS/PS menunjukkan bukti bahwa kualifikasi tenaga kependidikan (pendidikan dan kompetensi) sesuai dengan tugas yang diembannya.',
            ],
            'Pengelolaan Tenaga Kependidikan' => [
                'UPPS/PS menunjukkan bukti memiliki dan melaksanakan rencana rekrutmen dan pengembangan tenaga kependidikan secara sistematik, terstruktur, dan berkelanjutan.',
                'UPPS/PS menunjukkan bukti memiliki tenaga kependidikan dengan jumlah dan kualifikasi sesuai dengan kebutuhan UPPS/PS dan selaras dengan misi, visi, tujuan dan sasaran, serta strategi.',
            ],
        ],
        'Keuangan dan Sarana Prasarana' => [
            'Keuangan' => [
                'UPPS/PS menunjukkan bukti telah merencanakan penerimaan dan pengeluaran/pemanfaatan sumber keuangan untuk mendukung, mempertahankan, dan meningkatkan kualitas layanan, terutama yang berkaitan dengan pemenuhan kebutuhan operasional pendidikan, penelitian, dan pengabdian kepada masyarakat serta investasi yang selaras dengan misi, visi, tujuan dan sasaran, serta strategi.',
                'UPPS/PS menunjukkan bukti telah melakukan usaha dan menunjukkan hasil-hasilnya untuk menjamin keberlanjutan sumber daya keuangan.',
            ],
            'Sarana Prasarana' => [
                'UPPS/PS menunjukkan bukti penyediaan dan pengelolaan serta rencana pengembangan sarana dan prasarana yang dapat dimanfaatkan oleh mahasiswa dan dosen untuk kegiatan pendidikan, penelitian, pengabdian kepada masyarakat dan oleh tenaga kependidikan untuk mendukung kegiatan pendidikan, penelitian, dan pengabdian kepada masyarakat.',
                'UPPS/PS menunjukkan bukti bahwa sarana dan prasarana memenuhi standar kebersihan, kesehatan, keamanan, dan keselamatan, serta memerhatikan kesetaraan gender dan ramah difabel.',
            ],
        ],
        'Pendidikan dan Pengajaran' => [
            'Kurikulum' => [
                'UPPS/PS menunjukkan bukti penggunaan peta kurikulum untuk menjamin struktur mata kuliah dan kegiatan pembelajaran konsisten dan relevan dengan kompetensi (CPL) yang diharapkan dan selaras dengan misi, visi, tujuan dan sasaran, serta strategi.',
                'UPPS/PS menunjukkan bukti implementasi kurikulum mampu memfasilitasi keterlibatan aktif mahasiswa dalam proses pembelajaran, interaksi produktif antara mahasiswa, dosen, praktisi, dan masyarakat umum untuk mencapai tujuan pembelajaran, dengan memanfaatkan kerjasama dengan mitra yang dievaluasi dan ditindaklanjuti secara berkala.',
                'UPPS/PS menunjukkan bukti penggunaan materi dan metoda pembelajaran yang mutakhir dan relevan dengan kebutuhan EMBA saat ini dan di masa depan, memiliki perspektif global, selaras dengan misi, visi, tujuan dan sasaran, serta strategi untuk mencapai kompetensi (CPL) yang ditetapkan.',
                'UPPS/PS menunjukkan bukti evaluasi, perbaikan, dan pengembangan kurikulum agar sesuai dan relevan dengan perkembangan ilmu pengetahuan, praktik profesional, dan tantangan di masa yang akan datang dengan melibatkan pemangku kepentingan.',
            ],
            'Jaminan Pembelajaran' => [
                'UPPS/PS menunjukkan bukti pengukuran langsung atas ketercapaian kompetensi (CPL) mahasiswa dengan menggunakan pedoman standar pemenuhan capaian pembelajaran (rubrik) dan instrumen yang valid dan handal dengan metode yang relevan dalam mengukur ketercapaian kompetensi (CPL) mahasiswa.',
                'UPPS/PS melakukan pengukuran tidak langsung atas ketercapaian kompetensi (CPL) mahasiswa, antara lain melalui survey pengguna maupun studi pelacakan lulusan (tracer study) dan mempertimbangkan masukan dari hasil pengukuran tersebut ke dalam intervensi perbaikan kualitas pembelajaran.',
                'UPPS/PS menunjukkan bukti intervensi sebagai tindak lanjut hasil pengukuran ketercapaian kompetensi (CPL) mahasiswa, untuk perbaikan kualitas pembelajaran dan tingkat pemenuhan CPL.',
            ],
        ],
        'Penelitian dan Pengabdian kepada Masyarakat' => [
            'Penelitian' => [
                'UPPS/PS menunjukkan bukti perencanaan strategis pada kegiatan, hasil, dan kontribusi penelitiannya dalam memajukan pendidikan, ilmu pengetahuan, dan praktik profesional bagi pemangku kepentingan.',
                'UPPS/PS menunjukkan bukti bahwa kegiatan dan hasil penelitiannya mampu berkontribusi dalam memajukan ilmu pengetahuan, pendidikan, dan praktik profesional pemangku kepentingan.',
                'UPPS dan PS menunjukkan bukti kegiatan dan hasil kerja sama/keterlibatan penelitian (rekognisi) dengan para mitranya di bidang penelitian dan/atau praktik profesional telah mendukung dan selaras dengan misi, visi, tujuan dan sasaran, serta strategi UPPS/PS.',
                'UPPS/PS menunjukkan bukti pengintegrasian kegiatan, hasil, dan kontribusi penelitian dalam evaluasi kinerja dosen.',
            ],
            'Pengabdian kepada Masyarakat' => [
                'UPPS/PS menunjukkan bukti perencanaan strategis pada kegiatan, hasil, dan kontribusi PKM dalam memajukan pendidikan, ilmu pengetahuan, dan praktik profesional bagi pemangku kepentingan.',
                'UPPS/PS menunjukkan bukti bahwa kegiatan dan hasil PKM mampu berkontribusi dalam memajukan ilmu pengetahuan, pendidikan, dan praktik profesional pemangku kepentingan.',
                'UPPS/PS menunjukkan bukti kegiatan dan hasil kerja sama/keterlibatan pengabdian kepada masyarakat (rekognisi) dengan para mitranya di bidang pengabdian kepada masyarakat dan/atau praktik profesional mendukung dan selaras dengan misi, visi, tujuan dan sasaran, serta strategi UPPS/PS.',
                'UPPS/PS menunjukkan bukti pengintegrasian kegiatan, hasil, dan kontribusi PKM dalam evaluasi kinerja dosen.',
            ],
        ],
    ];

    /** Deskripsi tiap dimensi */
    protected array $dimensiDeskripsi = [
        'Misi'                                  => 'Dimensi misi mendeskripsikan penggunaan misi oleh UPPS/PS sebagai landasan filosofis visi, tujuan, dan strategi.',
        'Visi'                                  => 'Dimensi visi mendeskripsikan aspirasi dan arah yang dituju oleh UPPS/PS di masa yang akan datang.',
        'Tujuan dan Sasaran'                    => 'Dimensi tujuan dan sasaran mendeskripsikan proses perumusan dan pencapaian kinerja UPPS/PS.',
        'Strategi'                              => 'Dimensi strategi mendeskripsikan upaya UPPS/PS dalam mengemban misi dan mewujudkan visi melalui pencapaian tujuan dan sasaran strategis.',
        'Tata Pamong'                           => 'Dimensi tata pamong mendeskripsikan proses dan hasil pengasuhan (parenting), yaitu pengawasan, pembentukan sinergi, penyediaan sumber daya, penjagaan, dan penguatan nilai-nilai yang mengacu pada misi dan visi institusi.',
        'Tata Kelola'                           => 'Dimensi tata kelola mendeskripsikan proses perencanaan, pengorganisasian, pengarahan, dan pengendalian usaha untuk mendapatkan, mengembangkan, dan memanfaatkan sumber daya.',
        'Penerimaan Mahasiswa'                  => 'Dimensi penerimaan mahasiswa mendeskripsikan kebijakan, pelaksanaan, dan hasil penerimaan mahasiswa baru dan transfer kredit oleh UPPS/PS.',
        'Layanan Akademik Mahasiswa'            => 'Dimensi layanan akademik mahasiswa mendeskripsikan usaha yang dilakukan oleh UPPS/PS untuk menjamin kinerja akademik mahasiswa dalam menuntaskan proses pembelajaran.',
        'Kinerja Akademik Mahasiswa'            => 'Dimensi kinerja akademik mahasiswa mendeskripsikan kemampuan mahasiswa dalam menuntaskan proses belajar.',
        'Kesejahteraan Mahasiswa'               => 'Dimensi kesejahteraan mahasiswa mendeskripsikan layanan yang disediakan oleh UPPS/PS untuk menjamin kesejahteraan mental dan fisik mahasiswa.',
        'Pengembangan Karir Mahasiswa'          => 'Dimensi pengembangan karir mahasiswa mendeskripsikan layanan yang diberikan kepada mahasiswa yang mendukung mahasiswa untuk dapat bekerja dan mengembangkan karir.',
        'Kecukupan dan Kualifikasi Dosen'       => 'Dimensi kecukupan dan kualifikasi dosen mendeskripsikan kemampuan UPPS/PS dalam menyediakan dosen dengan jumlah dan kualifikasi yang sesuai dengan fokus Tridharma Perguruan Tinggi.',
        'Pengelolaan Dosen'                     => 'Dimensi pengelolaan dosen mendeskripsikan proses sistematis, terstruktur, dan berkelanjutan yang dilakukan UPPS/PS untuk mendapatkan, mengembangkan, dan menugaskan dosen.',
        'Kecukupan dan Kualifikasi Tenaga Kependidikan' => 'Dimensi kecukupan dan kualifikasi tenaga kependidikan mendeskripsikan kemampuan UPPS/PS dalam menyediakan tenaga kependidikan dengan jumlah dan kualifikasi yang sesuai.',
        'Pengelolaan Tenaga Kependidikan'       => 'Dimensi pengelolaan tenaga kependidikan mendeskripsikan proses sistematis, terstruktur, dan berkelanjutan yang dilakukan UPPS/PS untuk mendapatkan, mengembangkan, dan menugaskan tenaga kependidikan.',
        'Keuangan'                              => 'Dimensi keuangan mendeskripsikan proses sistematis, terstruktur, dan berkelanjutan yang dilakukan oleh UPPS/PS untuk mendapatkan dan memanfaatkan sumber keuangan.',
        'Sarana Prasarana'                      => 'Dimensi sarana dan prasarana mendeskripsikan proses sistematis, terstruktur, dan berkelanjutan yang dilakukan oleh UPPS/PS untuk mendapatkan dan memanfaatkan sarana dan prasarana.',
        'Kurikulum'                             => 'Dimensi kurikulum mendeskripsikan pengelolaan kurikulum oleh UPPS/PS yang mencakup perencanaan, pelaksanaan, evaluasi, perbaikan, dan pengembangan kurikulum.',
        'Jaminan Pembelajaran'                  => 'Dimensi jaminan pembelajaran mendeskripsikan proses sistematis, terstruktur, dan berkelanjutan yang dilakukan oleh UPPS/PS untuk menjamin mahasiswa mendapatkan dan mampu mengembangkan kompetensi (CPL).',
        'Penelitian'                            => 'Dimensi penelitian mendeskripsikan kegiatan, hasil, dan kontribusi penelitian UPPS/PS bagi pemajuan ilmu pengetahuan, pendidikan, dan praktik profesional.',
        'Pengabdian kepada Masyarakat'          => 'Dimensi PKM mendeskripsikan kegiatan, hasil, dan kontribusi PKM UPPS/PS bagi pemajuan ilmu pengetahuan, pendidikan, dan praktik profesional.',
    ];

    /** Bukti pemeriksaan per kriteria */
    protected array $buktiPerKriteria = [
        'Orientasi Strategis' => [
            'Statuta atau pedoman dasar penyelenggaraan kegiatan',
            'Profil dan kebijakan Perguruan Tinggi',
            'Rencana Induk Pengembangan (RIP)',
            'Rencana Strategis UPPS',
        ],
        'Tata Pamong dan Tata Kelola' => [
            'Susunan Organisasi dan Tata Kerja (SOTK)',
            'Bukti pelaksanaan Good University Governance (contoh: panduan kode etik dosen, tenaga kependidikan dan mahasiswa)',
            'Bukti survei kepuasan pemangku kepentingan internal dan eksternal',
            'Bukti terkait Sistem Penjaminan Mutu Internal (SPMI)',
            'Bukti kerjasama pembinaan kepada kampus-kampus kecil (tukar menukar dosen/mahasiswa, pemanfaatan bersama sarpras, dll)',
        ],
        'Pengelolaan Mahasiswa' => [
            'Bukti kebijakan dan pelaksanaan penerimaan mahasiswa',
            'Bukti berbagai layanan akademik (sistem informasi akademik, e-learning, e-library, laboratorium, magang, company visit, student mobility, dll)',
            'Bukti kerjasama dengan DUDI dan dunia kerja (MoU) untuk magang, company visit, atau rekrutmen',
            'Bukti berbagai fasilitas untuk mendukung kesejahteraan mahasiswa (fasilitas kesehatan, beasiswa, asrama, dll)',
            'Bukti kinerja mahasiswa (rata-rata IPK, masa studi, prestasi, sertifikat kompetensi, dll)',
            'Bukti program dan pelaksanaan kegiatan pengembangan karir mahasiswa (pusat karir, job fair, magang, sertifikasi, dll)',
        ],
        'Pengelolaan Dosen dan Tenaga Kependidikan' => [
            'Data profil dosen tetap dan tenaga kependidikan',
            'Data profil dosen tidak tetap',
            'Bukti perencanaan sumber daya manusia (kriteria kualifikasi, rencana pengembangan, pemetaan jenjang karir)',
            'Bukti matriks penugasan dosen sesuai kebutuhan dan kelayakan serta pemenuhan beban kerja',
            'Bukti dosen tidak tetap/praktisi yang mengajar untuk meningkatkan kompetensi mahasiswa sesuai CPL',
        ],
        'Keuangan dan Sarana Prasarana' => [
            'Rencana kerja dan anggaran tahunan',
            'Laporan realisasi keuangan tahunan',
            'Bukti perencanaan pengembangan sarana dan prasarana',
            'Bukti pengelolaan dan pemanfaatan sarana dan prasarana',
        ],
        'Pendidikan dan Pengajaran' => [
            'Penjelasan yang memuat Tujuan Pendidikan Program Studi, profil lulusan, dan capaian pembelajaran',
            'Pengembangan Kurikulum untuk mencapai Tujuan Pendidikan Program Studi, Profil lulusan, dan capaian pembelajaran',
            'Rubrik dan instrumen pengukuran capaian pembelajaran',
            'Hasil pengukuran, analisis, dan pelaporan pengukuran capaian pembelajaran',
            'Hasil pembahasan kurikulum dengan semua pemangku kepentingan (pimpinan UPPS, dosen PS, mahasiswa, alumni, industri)',
            'Hasil survey pengguna, FGD dengan alumni/industri, atau tracer study, dan metode lainnya',
            'Hasil evaluasi, implementasi, dan intervensi perbaikan kurikulum',
        ],
        'Penelitian dan Pengabdian kepada Masyarakat' => [
            'Penjelasan terkait keterlibatan dosen pada penelitian sesuai bidang ilmu',
            'Bukti sumber pendanaan penelitian',
            'Bukti hasil penelitian digunakan untuk mendukung proses belajar mengajar',
            'Penjelasan terkait keterlibatan dosen pada kegiatan PkM',
            'Sumber pendanaan PkM',
            'Bukti hasil PkM digunakan untuk mendukung proses belajar mengajar',
            'Bukti rekognisi hasil dari penelitian dan PkM',
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
                // Nama standar diberi prefix "[Unggul]" agar tidak bentrok dengan standar Terakreditasi
                $namaStandar = '[Unggul] ' . $kriteria;

                $standard = Standard::updateOrCreate(
                    [
                        'standar_akreditasi_id' => $akreditasi->id,
                        'jenjang_id'            => $jenjang->id,
                        'nama'                  => $namaStandar,
                    ],
                    [
                        'deskripsi' => 'Instrumen Akreditasi Unggul LAMEMBA — Kriteria ' . $kriteria
                            . ' (melampaui SN Dikti). Mencakup dimensi: '
                            . implode(', ', array_keys($dimensiList)) . '.',
                    ]
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

            $this->command?->info(
                "  LAMEMBA Unggul {$jenjang->nama}: +{$cStd} standar, +{$cEl} elemen, +{$cInd} indikator, +{$cBukti} bukti"
            );
        }
    }
}

<div class="row g-4">

  {{-- Ringkasan Peran --}}
  <div class="col-12">
    <div class="alert alert-primary d-flex gap-3 align-items-start">
      <i data-feather="shield" style="width:22px;height:22px;flex-shrink:0;margin-top:2px"></i>
      <div>
        <strong>Peran Admin</strong> — Mengelola seluruh konfigurasi sistem: pengguna, standar akreditasi,
        penjadwalan AMI, penugasan auditor, dan memantau seluruh aktivitas prodi maupun auditor.
      </div>
    </div>
  </div>

  {{-- Langkah 1: Kelola Program Studi --}}
  <div class="col-12">
    <div class="card">
      <div class="card-header d-flex align-items-center gap-2">
        <span class="badge bg-primary rounded-circle d-flex align-items-center justify-content-center" style="width:28px;height:28px;font-size:.85rem">1</span>
        <span class="fw-bold">Kelola Program Studi</span>
        <span class="text-muted small ms-1">— Daftarkan prodi sebelum membuat akun pengguna</span>
      </div>
      <div class="card-body">
        <p class="small text-muted mb-3">Program Studi harus didaftarkan terlebih dahulu karena akun Pengguna Prodi akan dikaitkan ke data prodi yang ada.</p>
        <ol class="small mb-0 ps-3">
          <li>Buka menu <strong>Program Studi</strong></li>
          <li>Klik <strong>Tambah Program Studi</strong></li>
          <li>Isi nama prodi, jenjang, jurusan, dan fakultas</li>
          <li>Pilih <strong>Status Akreditasi</strong> (Unggul / Baik Sekali / A / B / C) dan <strong>Standar Akreditasi</strong> (BAN-PT / LAMDIK / LAMSAMA / dll)</li>
          <li>Isi tanggal kadaluarsa akreditasi dan upload bukti akreditasi</li>
          <li>Isi <strong>Kode Prodi PDDikti</strong> jika ingin menggunakan sinkronisasi Neo Feeder</li>
          <li>Simpan — prodi siap dikaitkan ke akun pengguna</li>
        </ol>
      </div>
    </div>
  </div>

  {{-- Langkah 2: Kelola Pengguna --}}
  <div class="col-12">
    <div class="card">
      <div class="card-header d-flex align-items-center gap-2">
        <span class="badge bg-primary rounded-circle d-flex align-items-center justify-content-center" style="width:28px;height:28px;font-size:.85rem">2</span>
        <span class="fw-bold">Kelola Pengguna</span>
        <span class="text-muted small ms-1">— Pengguna Prodi &amp; Pengguna Auditor</span>
      </div>
      <div class="card-body">
        <p class="small text-muted mb-3">Tambah, edit, dan hapus akun untuk Program Studi (role <code>user</code>) dan Auditor (role <code>auditor</code>).</p>
        <div class="row g-3">
          <div class="col-md-6">
            <div class="border rounded p-3 h-100">
              <div class="fw-semibold small mb-2"><i data-feather="user-plus" style="width:14px;height:14px"></i> Tambah Akun Program Studi</div>
              <ol class="small mb-0 ps-3">
                <li>Buka menu <strong>Pengguna → Prodi</strong></li>
                <li>Klik tombol <strong>Tambah Pengguna</strong></li>
                <li>Isi nama, NIP/NIK, jabatan, username, dan password</li>
                <li>Pilih <strong>Program Studi</strong> — fakultas dan standar akreditasi terisi otomatis</li>
                <li>Simpan — akun langsung aktif</li>
              </ol>
            </div>
          </div>
          <div class="col-md-6">
            <div class="border rounded p-3 h-100">
              <div class="fw-semibold small mb-2"><i data-feather="user-check" style="width:14px;height:14px"></i> Tambah Akun Auditor</div>
              <ol class="small mb-0 ps-3">
                <li>Buka menu <strong>Pengguna → Auditor</strong></li>
                <li>Klik tombol <strong>Tambah Auditor</strong></li>
                <li>Isi nama, email, password</li>
                <li>Role otomatis diset sebagai <code>auditor</code></li>
                <li>Simpan</li>
              </ol>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  {{-- Langkah 3: Dokumen SPMI & AMI --}}
  <div class="col-12">
    <div class="card">
      <div class="card-header d-flex align-items-center gap-2">
        <span class="badge bg-primary rounded-circle d-flex align-items-center justify-content-center" style="width:28px;height:28px;font-size:.85rem">3</span>
        <span class="fw-bold">Upload Dokumen SPMI &amp; AMI</span>
        <span class="text-muted small ms-1">— Referensi bagi Prodi &amp; Auditor</span>
      </div>
      <div class="card-body">
        <p class="small text-muted mb-3">Menu <strong>Dokumen SPMI &amp; AMI</strong> digunakan untuk mengunggah dokumen referensi yang dapat diakses oleh Prodi dan Auditor selama proses AMI.</p>
        <ol class="small mb-0 ps-3">
          <li>Buka menu <strong>Dokumen SPMI &amp; AMI</strong></li>
          <li>Klik <strong>Upload Dokumen</strong></li>
          <li>Pilih kategori dokumen (SPMI / AMI / Kebijakan / dsb.)</li>
          <li>Upload file (PDF / Word) dan isi keterangan dokumen</li>
          <li>Simpan — dokumen langsung dapat diakses oleh Prodi dan Auditor sebagai bahan referensi</li>
        </ol>
      </div>
    </div>
  </div>

  {{-- Langkah 4: Penjadwalan AMI --}}
  <div class="col-12">
    <div class="card">
      <div class="card-header d-flex align-items-center gap-2">
        <span class="badge bg-primary rounded-circle d-flex align-items-center justify-content-center" style="width:28px;height:28px;font-size:.85rem">4</span>
        <span class="fw-bold">Penjadwalan AMI &amp; Penugasan Auditor</span>
      </div>
      <div class="card-body">
        <p class="small text-muted mb-3">Buat jadwal siklus AMI per prodi dan tentukan tim auditor yang bertugas.</p>
        <ol class="small mb-3 ps-3">
          <li>Buka menu <strong>Penjadwalan AMI</strong></li>
          <li>Klik <strong>Tambah Jadwal</strong></li>
          <li>Pilih <strong>Prodi</strong>, <strong>Standar Akreditasi</strong>, dan <strong>Periode</strong></li>
          <li>Isi 7 tanggal milestone siklus AMI:</li>
        </ol>
        <div class="table-responsive mb-3">
          <table class="table table-sm table-bordered small mb-0">
            <thead class="table-light">
              <tr><th>#</th><th>Milestone</th><th>Keterangan</th></tr>
            </thead>
            <tbody>
              <tr><td>1</td><td>Opening AMI</td><td>Pembukaan siklus, penjelasan teknis kepada prodi</td></tr>
              <tr><td>2</td><td>Pengisian Dokumen</td><td>Batas waktu prodi mengisi nilai mandiri &amp; upload dokumen</td></tr>
              <tr><td>3</td><td>Desk Evaluation</td><td>Auditor mereview kelengkapan dokumen dari meja</td></tr>
              <tr><td>4</td><td>Assessment</td><td>Auditor melakukan penilaian indikator</td></tr>
              <tr><td>5</td><td>Tindakan Koreksi</td><td>Prodi mengisi rencana perbaikan atas temuan KTS/OB</td></tr>
              <tr><td>6</td><td>Laporan AMI</td><td>Auditor menyusun dan menyerahkan laporan final</td></tr>
              <tr><td>7</td><td>RTM</td><td>Rapat Tinjauan Manajemen membahas hasil AMI</td></tr>
            </tbody>
          </table>
        </div>
        <ol class="small mb-0 ps-3" start="5">
          <li>Pilih <strong>Ketua Tim Auditor</strong> (wajib) dan <strong>Anggota</strong> (opsional)</li>
          <li>Simpan — jadwal otomatis terlihat oleh prodi &amp; auditor yang ditunjuk</li>
        </ol>
      </div>
    </div>
  </div>

  {{-- Langkah 5: Neo Feeder --}}
  <div class="col-12">
    <div class="card">
      <div class="card-header d-flex align-items-center gap-2">
        <span class="badge bg-primary rounded-circle d-flex align-items-center justify-content-center" style="width:28px;height:28px;font-size:.85rem">5</span>
        <span class="fw-bold">Konfigurasi Neo Feeder (PDDikti)</span>
      </div>
      <div class="card-body">
        <p class="small text-muted mb-3">Hubungkan sistem dengan PDDikti untuk sinkronisasi data mahasiswa, dosen, dan kelulusan secara otomatis.</p>
        <ol class="small mb-0 ps-3">
          <li>Buka menu <strong>Konfigurasi Feeder</strong></li>
          <li>Isi <strong>Username</strong> dan <strong>Password</strong> akun PDDikti institusi</li>
          <li>Set <strong>Kode Perguruan Tinggi</strong></li>
          <li>Untuk setiap Program Studi, isi <strong>Kode Prodi Feeder</strong> pada halaman Program Studi</li>
          <li>Klik <strong>Sinkronisasi Sekarang</strong> untuk menarik data dari server PDDikti</li>
          <li>Data yang tersinkronisasi: Mahasiswa, Dosen, Kelulusan — digunakan untuk halaman LKPS PDDikti</li>
        </ol>
        <div class="alert alert-warning mt-3 small mb-0">
          <i data-feather="alert-triangle" style="width:14px;height:14px"></i>
          <strong>Catatan:</strong> Konfigurasi Feeder hanya bisa diakses oleh Admin. Prodi &amp; Auditor tidak dapat mengubah pengaturan ini.
        </div>
      </div>
    </div>
  </div>

  {{-- Langkah 6: Monitoring --}}
  <div class="col-12">
    <div class="card">
      <div class="card-header d-flex align-items-center gap-2">
        <span class="badge bg-primary rounded-circle d-flex align-items-center justify-content-center" style="width:28px;height:28px;font-size:.85rem">6</span>
        <span class="fw-bold">Monitoring &amp; Statistik</span>
      </div>
      <div class="card-body">
        <div class="row g-3">
          <div class="col-md-4">
            <div class="border rounded p-3 h-100">
              <div class="fw-semibold small mb-1"><i data-feather="bar-chart-2" style="width:13px;height:13px"></i> Statistik AMI</div>
              <p class="small text-muted mb-0">Lihat rekap nilai per elemen dan total semua prodi. Buka <strong>Statistik AMI → Statistik Elemen / Total</strong>.</p>
            </div>
          </div>
          <div class="col-md-4">
            <div class="border rounded p-3 h-100">
              <div class="fw-semibold small mb-1"><i data-feather="activity" style="width:13px;height:13px"></i> Aktivitas Prodi</div>
              <p class="small text-muted mb-0">Pantau progres pengisian dokumen dan status pengajuan AMI setiap prodi.</p>
            </div>
          </div>
          <div class="col-md-4">
            <div class="border rounded p-3 h-100">
              <div class="fw-semibold small mb-1"><i data-feather="trending-up" style="width:13px;height:13px"></i> Forecasting</div>
              <p class="small text-muted mb-0">Prediksi status akreditasi prodi berdasarkan nilai AMI yang sudah diinput.</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  {{-- Alur Status AMI --}}
  <div class="col-12">
    <div class="card border-0 bg-light">
      <div class="card-body">
        <div class="fw-bold small mb-3">Alur Status Siklus AMI</div>
        <div class="d-flex flex-wrap align-items-center gap-2 small">
          <span class="badge bg-secondary px-3 py-2">Diajukan (Prodi)</span>
          <i data-feather="arrow-right" style="width:14px;height:14px"></i>
          <span class="badge bg-info text-dark px-3 py-2">Diterima (Auditor)</span>
          <i data-feather="arrow-right" style="width:14px;height:14px"></i>
          <span class="badge bg-warning text-dark px-3 py-2">Koreksi (Prodi)</span>
          <i data-feather="arrow-right" style="width:14px;height:14px"></i>
          <span class="badge bg-success px-3 py-2">Selesai (Auditor)</span>
        </div>
      </div>
    </div>
  </div>

</div>

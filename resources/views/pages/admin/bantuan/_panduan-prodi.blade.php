<div class="row g-4">

  <div class="col-12">
    <div class="alert alert-success d-flex gap-3 align-items-start">
      <i data-feather="users" style="width:22px;height:22px;flex-shrink:0;margin-top:2px"></i>
      <div>
        <strong>Peran Program Studi (User)</strong> — Mengisi nilai mandiri, mengunggah dokumen capaian,
        mengajukan AMI ke auditor, dan mengisi tindak lanjut koreksi atas temuan auditor.
      </div>
    </div>
  </div>

  {{-- Langkah 1 --}}
  <div class="col-12">
    <div class="card">
      <div class="card-header d-flex align-items-center gap-2">
        <span class="badge bg-success rounded-circle d-flex align-items-center justify-content-center" style="width:28px;height:28px;font-size:.85rem">1</span>
        <span class="fw-bold">Login &amp; Dashboard</span>
      </div>
      <div class="card-body small">
        <ol class="ps-3 mb-0">
          <li>Login menggunakan akun yang diberikan Admin</li>
          <li>Dashboard menampilkan status koneksi PDDikti (Neo Feeder) dan ringkasan data prodi</li>
          <li>Jika Neo Feeder terhubung, klik <strong>Sinkronisasi Sekarang</strong> untuk memperbarui data PDDikti</li>
        </ol>
      </div>
    </div>
  </div>

  {{-- Langkah 2 --}}
  <div class="col-12">
    <div class="card">
      <div class="card-header d-flex align-items-center gap-2">
        <span class="badge bg-success rounded-circle d-flex align-items-center justify-content-center" style="width:28px;height:28px;font-size:.85rem">2</span>
        <span class="fw-bold">Upload Dokumen Capaian</span>
        <span class="text-muted small ms-1">— Dokumen SPMI &amp; AMI</span>
      </div>
      <div class="card-body small">
        <p class="text-muted mb-3">Unggah dokumen yang dibutuhkan sesuai standar akreditasi prodi Anda.</p>
        <ol class="ps-3 mb-0">
          <li>Buka menu <strong>Dokumen SPMI &amp; AMI</strong></li>
          <li>Pilih <strong>Standar → Elemen → Indikator</strong> yang ingin dilengkapi</li>
          <li>Lihat daftar <strong>Dokumen Target</strong> (dokumen yang harus ada)</li>
          <li>Klik <strong>Upload Dokumen</strong> dan unggah file (PDF/Word)</li>
          <li>Isi keterangan dokumen dan tanggal kadaluarsa (jika ada)</li>
          <li>Ulangi untuk semua indikator yang relevan</li>
        </ol>
      </div>
    </div>
  </div>

  {{-- Langkah 3 --}}
  <div class="col-12">
    <div class="card">
      <div class="card-header d-flex align-items-center gap-2">
        <span class="badge bg-success rounded-circle d-flex align-items-center justify-content-center" style="width:28px;height:28px;font-size:.85rem">3</span>
        <span class="fw-bold">Pengajuan AMI — Isi Nilai Mandiri</span>
      </div>
      <div class="card-body small">
        <p class="text-muted mb-3">Self-assessment: prodi menilai dirinya sendiri sebelum dievaluasi auditor.</p>
        <ol class="ps-3 mb-3">
          <li>Buka menu <strong>Pengajuan AMI</strong></li>
          <li>Pilih <strong>Periode</strong> yang sedang berjalan</li>
          <li>Untuk setiap indikator, isi <strong>Nilai Mandiri</strong> (0–4):</li>
        </ol>
        <div class="table-responsive mb-3">
          <table class="table table-sm table-bordered small mb-0">
            <thead class="table-light">
              <tr><th>Nilai</th><th>Makna</th></tr>
            </thead>
            <tbody>
              <tr><td><strong>4</strong></td><td>Melampaui standar / sangat baik</td></tr>
              <tr><td><strong>3</strong></td><td>Memenuhi standar / baik</td></tr>
              <tr><td><strong>2</strong></td><td>Sebagian memenuhi / cukup (OB)</td></tr>
              <tr><td><strong>1</strong></td><td>Tidak memenuhi / kurang (KTS)</td></tr>
              <tr><td><strong>0</strong></td><td>Tidak ada bukti sama sekali</td></tr>
            </tbody>
          </table>
        </div>
        <ol class="ps-3 mb-0" start="4">
          <li>Setelah semua indikator terisi, klik <strong>Ajukan ke Auditor</strong></li>
          <li>Status pengajuan berubah menjadi <span class="badge bg-secondary">Diajukan</span></li>
        </ol>
      </div>
    </div>
  </div>

  {{-- Langkah 4 --}}
  <div class="col-12">
    <div class="card">
      <div class="card-header d-flex align-items-center gap-2">
        <span class="badge bg-success rounded-circle d-flex align-items-center justify-content-center" style="width:28px;height:28px;font-size:.85rem">4</span>
        <span class="fw-bold">Perbaikan AMI — Tindak Lanjut Temuan</span>
      </div>
      <div class="card-body small">
        <p class="text-muted mb-3">Setelah auditor menyelesaikan evaluasi, prodi mengisi rencana perbaikan atas temuan <strong>KTS</strong> (Ketidaksesuaian) dan <strong>OB</strong> (Observasi).</p>
        <ol class="ps-3 mb-3">
          <li>Buka menu <strong>Perbaikan AMI</strong> (muncul saat status = <span class="badge bg-warning text-dark">Koreksi</span>)</li>
          <li>Pilih periode yang perlu dikoreksi</li>
          <li>Untuk setiap indikator dengan temuan KTS/OB, isi:</li>
        </ol>
        <div class="table-responsive mb-0">
          <table class="table table-sm table-bordered small mb-0">
            <thead class="table-light">
              <tr><th>Field</th><th>Keterangan</th></tr>
            </thead>
            <tbody>
              <tr><td>Rencana Perbaikan</td><td>Apa yang akan diperbaiki</td></tr>
              <tr><td>Jadwal Perbaikan</td><td>Kapan perbaikan dilakukan</td></tr>
              <tr><td>Penanggung Jawab Perbaikan</td><td>Siapa yang bertanggung jawab</td></tr>
              <tr><td>Rencana Pencegahan</td><td>Apa yang dilakukan agar tidak terulang</td></tr>
              <tr><td>Jadwal Pencegahan</td><td>Kapan pencegahan dilaksanakan</td></tr>
              <tr><td>Penanggung Jawab Pencegahan</td><td>Siapa yang mengawasi pencegahan</td></tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  {{-- Langkah 5 --}}
  <div class="col-12">
    <div class="card">
      <div class="card-header d-flex align-items-center gap-2">
        <span class="badge bg-success rounded-circle d-flex align-items-center justify-content-center" style="width:28px;height:28px;font-size:.85rem">5</span>
        <span class="fw-bold">Melihat Hasil AMI &amp; Forecasting</span>
      </div>
      <div class="card-body">
        <div class="row g-3 small">
          <div class="col-md-4">
            <div class="border rounded p-3 h-100">
              <div class="fw-semibold mb-1"><i data-feather="file-text" style="width:13px;height:13px"></i> Nilai Evaluasi Diri</div>
              <p class="text-muted mb-0">Rekap nilai mandiri vs nilai auditor per indikator untuk melihat kesenjangan (gap).</p>
            </div>
          </div>
          <div class="col-md-4">
            <div class="border rounded p-3 h-100">
              <div class="fw-semibold mb-1"><i data-feather="bar-chart-2" style="width:13px;height:13px"></i> Statistik AMI</div>
              <p class="text-muted mb-0">Visualisasi nilai per elemen dan total nilai AMI prodi dalam grafik.</p>
            </div>
          </div>
          <div class="col-md-4">
            <div class="border rounded p-3 h-100">
              <div class="fw-semibold mb-1"><i data-feather="trending-up" style="width:13px;height:13px"></i> Forecasting</div>
              <p class="text-muted mb-0">Prediksi status akreditasi (Terakreditasi / Unggul) berdasarkan nilai AMI saat ini.</p>
            </div>
          </div>
          <div class="col-md-4">
            <div class="border rounded p-3 h-100">
              <div class="fw-semibold mb-1"><i data-feather="database" style="width:13px;height:13px"></i> LKPS PDDikti</div>
              <p class="text-muted mb-0">Data mahasiswa, dosen, dan kelulusan dari PDDikti dalam format tabel LKPS (Tabel 4.a, 6.a, 6.b, 6.d). Bisa disimpan sebagai snapshot dan diekspor ke Excel.</p>
            </div>
          </div>
          <div class="col-md-4">
            <div class="border rounded p-3 h-100">
              <div class="fw-semibold mb-1"><i data-feather="layers" style="width:13px;height:13px"></i> Rekap Dokumen</div>
              <p class="text-muted mb-0">Pantau dokumen yang masih aktif dan dokumen yang sudah kadaluarsa.</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

</div>

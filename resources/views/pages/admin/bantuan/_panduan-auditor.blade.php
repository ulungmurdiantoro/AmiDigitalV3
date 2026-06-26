<div class="row g-4">

  <div class="col-12">
    <div class="alert alert-warning d-flex gap-3 align-items-start">
      <i data-feather="search" style="width:22px;height:22px;flex-shrink:0;margin-top:2px"></i>
      <div>
        <strong>Peran Auditor</strong> — Menerima pengajuan AMI dari prodi, melakukan evaluasi per indikator
        (manual atau dibantu AI), dan menutup siklus setelah prodi menyelesaikan koreksi.
      </div>
    </div>
  </div>

  {{-- Langkah 1 --}}
  <div class="col-12">
    <div class="card">
      <div class="card-header d-flex align-items-center gap-2">
        <span class="badge bg-warning text-dark rounded-circle d-flex align-items-center justify-content-center" style="width:28px;height:28px;font-size:.85rem">1</span>
        <span class="fw-bold">Konfirmasi Pengajuan AMI</span>
      </div>
      <div class="card-body small">
        <p class="text-muted mb-3">Saat prodi mengajukan AMI, notifikasi muncul di menu <strong>Konfirmasi Pengajuan</strong>.</p>
        <ol class="ps-3 mb-0">
          <li>Buka menu <strong>Konfirmasi Pengajuan</strong></li>
          <li>Daftar menampilkan semua pengajuan dengan status <span class="badge bg-secondary">Diajukan</span> yang ditugaskan ke Anda</li>
          <li>Klik pengajuan untuk melihat detail: kelengkapan dokumen prodi, nilai mandiri yang sudah diisi</li>
          <li>Review apakah dokumen cukup untuk dilanjutkan ke evaluasi</li>
          <li>Klik <strong>Terima Pengajuan</strong> → status berubah menjadi <span class="badge bg-info text-dark">Diterima</span></li>
        </ol>
      </div>
    </div>
  </div>

  {{-- Langkah 2 --}}
  <div class="col-12">
    <div class="card">
      <div class="card-header d-flex align-items-center gap-2">
        <span class="badge bg-warning text-dark rounded-circle d-flex align-items-center justify-content-center" style="width:28px;height:28px;font-size:.85rem">2</span>
        <span class="fw-bold">Evaluasi AMI — Penilaian per Indikator</span>
      </div>
      <div class="card-body small">
        <p class="text-muted mb-3">Pengajuan yang sudah <span class="badge bg-info text-dark">Diterima</span> masuk ke daftar <strong>Evaluasi AMI</strong>.</p>
        <ol class="ps-3 mb-3">
          <li>Buka menu <strong>Evaluasi AMI</strong></li>
          <li>Pilih prodi &amp; periode yang akan dievaluasi</li>
          <li>Sistem menampilkan semua standar, elemen, dan indikator beserta:
            <ul class="mt-1">
              <li>Dokumen Target (yang seharusnya ada)</li>
              <li>Dokumen Capaian (yang sudah diupload prodi)</li>
              <li>Nilai Mandiri prodi</li>
            </ul>
          </li>
          <li>Untuk setiap indikator, isi hasil penilaian:</li>
        </ol>
        <div class="table-responsive mb-3">
          <table class="table table-sm table-bordered small mb-0">
            <thead class="table-light">
              <tr><th>Field</th><th>Keterangan</th></tr>
            </thead>
            <tbody>
              <tr><td>Hasil Nilai</td><td>0–4 sesuai skala akreditasi</td></tr>
              <tr><td>Jenis Temuan</td><td><strong>Sesuai</strong> (3–4) / <strong>OB</strong> (2) / <strong>KTS</strong> (0–1)</td></tr>
              <tr><td>Kriteria</td><td>Standar/pasal yang dijadikan acuan</td></tr>
              <tr><td>Deskripsi Temuan</td><td>Apa yang sudah terpenuhi dan apa yang belum</td></tr>
              <tr><td>Akibat</td><td>Dampak terhadap status akreditasi jika tidak diperbaiki</td></tr>
              <tr><td>Akar Masalah</td><td>Penyebab ketidaksesuaian</td></tr>
              <tr><td>Rekomendasi</td><td>Saran perbaikan konkret</td></tr>
            </tbody>
          </table>
        </div>
        <div class="alert alert-info small mb-3">
          <i data-feather="cpu" style="width:13px;height:13px"></i>
          <strong>Fitur Nilai AI:</strong> Klik tombol <strong>"Nilai dengan AI"</strong> pada setiap indikator.
          Sistem akan mengirim konteks (indikator, dokumen target vs capaian, nilai mandiri) ke Gemini AI
          dan mengisi otomatis semua field penilaian. Auditor tetap bisa mengedit sebelum menyimpan.
        </div>
        <ol class="ps-3 mb-0" start="5">
          <li>Simpan hasil penilaian per indikator</li>
          <li>Setelah semua indikator selesai, klik <strong>Selesai Evaluasi</strong> → status berubah menjadi <span class="badge bg-warning text-dark">Koreksi</span></li>
        </ol>
      </div>
    </div>
  </div>

  {{-- Langkah 3 --}}
  <div class="col-12">
    <div class="card">
      <div class="card-header d-flex align-items-center gap-2">
        <span class="badge bg-warning text-dark rounded-circle d-flex align-items-center justify-content-center" style="width:28px;height:28px;font-size:.85rem">3</span>
        <span class="fw-bold">Review Koreksi &amp; Tutup Siklus</span>
      </div>
      <div class="card-body small">
        <p class="text-muted mb-3">Setelah prodi mengisi rencana perbaikan, siklus masuk ke tahap akhir.</p>
        <ol class="ps-3 mb-3">
          <li>Buka menu <strong>Perbaikan AMI</strong></li>
          <li>Daftar menampilkan pengajuan dengan status <span class="badge bg-warning text-dark">Koreksi</span></li>
          <li>Review rencana perbaikan dan pencegahan yang diisi prodi</li>
          <li>Untuk setiap indikator, update <strong>Status Akhir</strong> (apakah koreksi sudah diterima)</li>
          <li>Jika perlu, revisi nilai dan jenis temuan berdasarkan perbaikan prodi</li>
          <li>Klik <strong>Tutup Siklus AMI</strong> → status berubah menjadi <span class="badge bg-success">Selesai</span></li>
        </ol>
        <div class="alert alert-success small mb-0">
          <i data-feather="check-circle" style="width:13px;height:13px"></i>
          Siklus AMI selesai. Hasil evaluasi tersimpan dan dapat dilihat di halaman
          <strong>Nilai Evaluasi Diri</strong>, <strong>Statistik AMI</strong>, dan <strong>Forecasting</strong> oleh prodi maupun admin.
        </div>
      </div>
    </div>
  </div>

  {{-- Langkah 4 --}}
  <div class="col-12">
    <div class="card">
      <div class="card-header d-flex align-items-center gap-2">
        <span class="badge bg-warning text-dark rounded-circle d-flex align-items-center justify-content-center" style="width:28px;height:28px;font-size:.85rem">4</span>
        <span class="fw-bold">Forecasting &amp; Nilai Evaluasi Diri</span>
      </div>
      <div class="card-body small">
        <div class="row g-3">
          <div class="col-md-6">
            <div class="border rounded p-3 h-100">
              <div class="fw-semibold mb-1"><i data-feather="trending-up" style="width:13px;height:13px"></i> Forecasting</div>
              <p class="text-muted mb-0">Lihat prediksi status akreditasi prodi yang Anda audit. Sistem menghitung NA berdasarkan nilai AMI dan menampilkan apakah prodi berpotensi Terakreditasi, Unggul, atau Tidak Terakreditasi sesuai formula masing-masing LAM.</p>
            </div>
          </div>
          <div class="col-md-6">
            <div class="border rounded p-3 h-100">
              <div class="fw-semibold mb-1"><i data-feather="file-text" style="width:13px;height:13px"></i> Nilai Evaluasi Diri</div>
              <p class="text-muted mb-0">Rekap perbandingan nilai mandiri prodi vs nilai auditor per indikator. Berguna untuk melihat objektivitas self-assessment prodi.</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  {{-- Jenis Temuan --}}
  <div class="col-12">
    <div class="card border-0 bg-light">
      <div class="card-body">
        <div class="fw-bold small mb-3">Panduan Jenis Temuan AMI</div>
        <div class="row g-2 small">
          <div class="col-md-4">
            <div class="card border-success h-100">
              <div class="card-body py-2 px-3">
                <div class="fw-bold text-success mb-1">Sesuai</div>
                <div class="text-muted">Nilai 3–4. Memenuhi atau melampaui standar. Tidak ada tindak lanjut wajib.</div>
              </div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="card border-warning h-100">
              <div class="card-body py-2 px-3">
                <div class="fw-bold text-warning mb-1">OB — Observasi</div>
                <div class="text-muted">Nilai 2. Ada peluang perbaikan, belum menjadi ketidaksesuaian. Prodi perlu memperhatikan.</div>
              </div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="card border-danger h-100">
              <div class="card-body py-2 px-3">
                <div class="fw-bold text-danger mb-1">KTS — Ketidaksesuaian</div>
                <div class="text-muted">Nilai 0–1. Tidak memenuhi standar. Prodi <strong>wajib</strong> mengisi rencana perbaikan.</div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

</div>

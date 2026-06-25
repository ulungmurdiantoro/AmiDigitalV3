@props(['standards', 'importTitle', 'id', 'prodis', 'periodes', 'transaksis'])
<div class="table-responsive">
  <table id="{{ $id }}" class="col-md-12 table-striped table-hover" style="table-layout: fixed; width: 100%;">
    <thead class="text-bg-secondary">
      <tr class="text-white text-center">
        <th class="col-md-1 text-center" style="padding: 0.5rem;">Kode</th>
        <th class="col-md-2" style="padding: 0.5rem;">Elemen</th>
        <th class="col-md-4" style="padding: 0.5rem;">Indikator</th>
        <th class="col-md-1 text-center" style="padding: 0.5rem;">Informasi</th>
        <th class="col-md-1 text-center" style="padding: 0.5rem;">Target</th>
        <th class="col-md-1 text-center" style="padding: 0.5rem;">Capaian</th>
        <th class="col-md-1 text-center" style="padding: 0.5rem;">Nilai</th>
        <th class="col-md-1 text-center" style="padding: 0.5rem;">Aksi</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($standards as $standard)
        @foreach ($standard->indicators as $indikator)
          @php
            $nilai    = $indikator->dokumen_nilais->hasil_nilai ?? 0;
            $mandiri  = $indikator->dokumen_nilais->mandiri_nilai ?? 0;
            $amiKode  = $transaksis->ami_kode ?? '';
          @endphp
          <tr style="{{ $nilai == 0 ? 'background-color: rgba(140, 18, 61, .85); color: white;' : '' }}">
            <td class="text-center" style="vertical-align: top; padding: 5px 1px;">{{ $indikator->indikator_kode }}</td>
            <td style="vertical-align: top; padding: 5px 1px;">{{ $standard->nama }}</td>
            <td style="padding: 5px 1px;">{!! nl2br(e($indikator->nama_indikator)) !!}</td>
            <td class="text-center">
              <a href="#" data-bs-toggle="modal" data-bs-target="#infoModal{{ $indikator->id }}" class="btn btn-warning btn-icon">
                <i data-feather="info"></i>
              </a>
            </td>
            <td class="text-center">
              <a href="#" data-bs-toggle="modal" data-bs-target="#targetModal{{ $indikator->id }}" class="btn btn-primary btn-icon">
                {{ $indikator->dokumen_targets->count() }}
              </a>
            </td>
            <td class="text-center">
              <a href="#" data-bs-toggle="modal" data-bs-target="#capaianModal{{ $indikator->id }}" class="btn btn-warning btn-icon">
                {{ $indikator->dokumen_capaians->count() }}
              </a>
            </td>
            <td class="text-center">{{ $nilai }}</td>
            <td class="text-center" style="white-space: nowrap;">
              {{-- Tombol AI --}}
              <button type="button"
                class="btn btn-success btn-icon btn-ai-assess"
                data-indikator-id="{{ $indikator->id }}"
                data-ami-kode="{{ $amiKode }}"
                data-prodi="{{ $prodis }}"
                data-periode="{{ $periodes }}"
                data-target-modal="editModal-indikator-{{ $indikator->id }}"
                title="Isi dengan AI"
              >
                <i data-feather="cpu"></i>
              </button>
              {{-- Tombol Edit Manual --}}
              <a href="#" data-bs-toggle="modal" data-bs-target="#editModal-indikator-{{ $indikator->id }}" class="btn btn-info btn-icon" title="Edit Manual">
                <i data-feather="edit"></i>
              </a>
            </td>
          </tr>

          {{-- Info Modal --}}
          <div class="modal fade" id="infoModal{{ $indikator->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title">Informasi Indikator</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                  <p><b>Indikator</b>: {!! nl2br(e($indikator->nama_indikator)) !!}</p>
                  <br>
                  <p>{!! nl2br(e($indikator->info)) !!}</p>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
              </div>
            </div>
          </div>

          {{-- Target Modal --}}
          <div class="modal fade" id="targetModal{{ $indikator->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title">Target / Kebutuhan Dokumen</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                  <div class="mb-2"><b>Indikator</b>: {{ $indikator->nama_indikator }}</div>
                  @forelse ($indikator->dokumen_targets as $target)
                    <div class="mb-1">
                      {{ $loop->iteration }}. {{ $target->dokumen_nama }}
                      <span class="badge bg-secondary">{{ $target->dokumen_tipe }}</span>
                      — {{ $target->dokumen_keterangan }}
                    </div>
                  @empty
                    <p class="text-muted">Belum ada target dokumen.</p>
                  @endforelse
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
              </div>
            </div>
          </div>

          {{-- Capaian Modal --}}
          <div class="modal fade" id="capaianModal{{ $indikator->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title">Capaian Dokumen</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                  <div class="mb-2"><b>Indikator</b>: {{ $indikator->nama_indikator }}</div>
                  @forelse ($indikator->dokumen_capaians as $capaian)
                    <div class="mb-2 border-bottom pb-1">
                      {{ $loop->iteration }}. {{ $capaian->dokumen_nama }}
                      <span class="badge bg-secondary">{{ $capaian->dokumen_tipe }}</span>
                      — {{ $capaian->dokumen_keterangan }}<br>
                      @if($capaian->dokumen_file)
                        <a href="{{ asset($capaian->dokumen_file) }}" target="_blank" class="btn btn-sm btn-warning mt-1" rel="noopener noreferrer">
                          <i data-feather="download"></i> Lihat
                        </a>
                      @endif
                      @if($capaian->dokumen_kadaluarsa)
                        <span class="text-danger"><i>Kadaluarsa: {{ $capaian->dokumen_kadaluarsa }}</i></span>
                      @endif
                    </div>
                  @empty
                    <p class="text-muted">Belum ada dokumen yang diunggah.</p>
                  @endforelse
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
              </div>
            </div>
          </div>

          {{-- Edit / Verifikasi Modal --}}
          <div class="modal fade" id="editModal-indikator-{{ $indikator->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
              <form action="{{ route('auditor.evaluasi-ami.store') }}" method="POST" id="InputAmiForm-{{ $indikator->id }}">
                @csrf
                <div class="modal-content" style="position:relative;">
                  <div class="modal-header">
                    <h5 class="modal-title">Verifikasi AMI — {{ $indikator->indikator_kode }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                  </div>
                  <div class="modal-body">
                    {{-- AI loading overlay --}}
                    <div class="ai-loading-overlay" id="aiLoading-{{ $indikator->id }}"
                         style="display:none;position:absolute;inset:0;background:rgba(255,255,255,.92);z-index:20;border-radius:inherit;flex-direction:column;align-items:center;justify-content:center;">
                      <div class="text-center p-4">
                        <div class="spinner-border text-success mb-3" style="width:3rem;height:3rem;" role="status"></div>
                        <div class="fw-bold text-success fs-6" id="aiLoadingText-{{ $indikator->id }}">AI sedang membaca dokumen...</div>
                        <div class="text-muted small mt-1">Mohon tunggu, proses ini memerlukan beberapa detik</div>
                      </div>
                    </div>

                    <input type="hidden" name="ami_kodes"        value="{{ $amiKode }}">
                    <input type="hidden" name="indikator_ids"    value="{{ $indikator->id }}">
                    <input type="hidden" name="indikator_bobots" value="{{ $indikator->bobot ?? '' }}">
                    <input type="hidden" name="prodis"           value="{{ $prodis ?? '' }}">
                    <input type="hidden" name="periodes"         value="{{ $periodes ?? '' }}">

                    <div class="mb-1"><span class="fw-bold">Standar</span>: {{ $standard->standard->nama ?? '' }}</div>
                    <div class="mb-1"><span class="fw-bold">Elemen</span>: {{ $standard->nama ?? '' }}</div>
                    <div class="mb-2"><span class="fw-bold">Indikator</span>: {{ $indikator->nama_indikator }}</div>

                    <div class="row mb-2">
                      <div class="col-6">
                        <label class="form-label mb-0">Nilai Mandiri Prodi</label>
                        <input type="number" name="mandiri_nilais" class="form-control" value="{{ $mandiri }}" readonly>
                      </div>
                      <div class="col-6">
                        <label class="form-label mb-0">Verifikasi Nilai Auditor <span class="text-danger">*</span></label>
                        <input type="number" min="0" max="4" step="0.01" name="hasil_nilais"
                               id="f-hasilNilai-{{ $indikator->id }}"
                               class="form-control"
                               value="{{ $indikator->dokumen_nilais->hasil_nilai ?? '' }}"
                               required>
                      </div>
                    </div>

                    <label class="form-label mb-0">Jenis Temuan <span class="text-danger">*</span></label>
                    <select name="jenis_temuans" id="f-jenisTem-{{ $indikator->id }}" class="form-select mb-2" required>
                      @php $jenis = $indikator->dokumen_nilais->jenis_temuan ?? ''; @endphp
                      <option value="" @selected(!$jenis)>Pilih...</option>
                      <option value="Sesuai" @selected($jenis === 'Sesuai')>Sesuai</option>
                      <option value="OB"     @selected($jenis === 'OB')>OB (Observasi)</option>
                      <option value="KTS"    @selected($jenis === 'KTS')>KTS (Ketidaksesuaian)</option>
                    </select>

                    <label class="form-label mb-0">Kriteria</label>
                    <textarea name="hasil_kriterias" id="f-kriteria-{{ $indikator->id }}" class="form-control mb-2" rows="2"
                              placeholder="Kriteria yang dijadikan acuan...">{{ $indikator->dokumen_nilais->hasil_kriteria ?? '' }}</textarea>

                    <label class="form-label mb-0">Deskripsi Temuan</label>
                    <textarea name="hasil_deskripsis" id="f-deskripsi-{{ $indikator->id }}" class="form-control mb-2" rows="3"
                              placeholder="Deskripsikan temuan...">{{ $indikator->dokumen_nilais->hasil_deskripsi ?? '' }}</textarea>

                    <label class="form-label mb-0">Akibat</label>
                    <textarea name="hasil_akibats" id="f-akibat-{{ $indikator->id }}" class="form-control mb-2" rows="2"
                              placeholder="Akibat jika tidak terpenuhi...">{{ $indikator->dokumen_nilais->hasil_akibat ?? '' }}</textarea>

                    <label class="form-label mb-0">Akar Masalah</label>
                    <textarea name="hasil_masalahs" id="f-masalah-{{ $indikator->id }}" class="form-control mb-2" rows="2"
                              placeholder="Akar masalah yang teridentifikasi...">{{ $indikator->dokumen_nilais->hasil_masalah ?? '' }}</textarea>

                    <label class="form-label mb-0">Rekomendasi</label>
                    <textarea name="hasil_rekomendasis" id="f-rekomen-{{ $indikator->id }}" class="form-control mb-2" rows="2"
                              placeholder="Rekomendasi perbaikan...">{{ $indikator->dokumen_nilais->hasil_rekomendasi ?? '' }}</textarea>

                    {{-- AI badge --}}
                    <div id="ai-badge-{{ $indikator->id }}" class="d-none">
                      <span class="badge bg-success">
                        <i data-feather="cpu" style="width:12px;height:12px;"></i> Diisi oleh AI — harap review sebelum submit
                      </span>
                    </div>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                  </div>
                </div>
              </form>
            </div>
          </div>

        @endforeach
      @endforeach
    </tbody>
  </table>
</div>

<script>
(function () {
  // Guard: jalankan setup hanya sekali meski component di-render berkali-kali
  if (window._aiAssessReady) return;
  window._aiAssessReady = true;

  const CSRF   = document.querySelector('meta[name="csrf-token"]')?.content ?? '';
  const AI_URL = "{{ route('auditor.evaluasi-ami.ai-assess') }}";

  // ── Toast container ────────────────────────────────────────────
  const tc = document.createElement('div');
  tc.id = 'ai-toast-container';
  tc.style.cssText = 'position:fixed;bottom:1.5rem;right:1.5rem;z-index:9999;min-width:300px;';
  document.body.appendChild(tc);

  function showToast(msg, type) {
    const colors = { info:'#0dcaf0', success:'#198754', danger:'#dc3545' };
    const icons  = { info:'⏳', success:'✅', danger:'❌' };
    const t = document.createElement('div');
    t.style.cssText = 'background:#fff;border-left:4px solid ' + (colors[type]||colors.info)
      + ';box-shadow:0 4px 12px rgba(0,0,0,.15);border-radius:6px'
      + ';padding:.75rem 1rem;margin-top:.5rem;font-size:.875rem'
      + ';display:flex;align-items:flex-start;gap:.5rem;';
    t.innerHTML = '<span style="font-size:1.1rem;flex-shrink:0">' + (icons[type]||'ℹ️') + '</span>'
                + '<span>' + msg + '</span>';
    tc.appendChild(t);
    const delay = type === 'danger' ? 6000 : 4000;
    setTimeout(function () {
      t.style.transition = 'opacity .4s'; t.style.opacity = '0';
      setTimeout(function () { t.remove(); }, 450);
    }, delay);
  }

  function showOverlay(id, msg) {
    const el  = document.getElementById('aiLoading-' + id);
    const txt = document.getElementById('aiLoadingText-' + id);
    if (el)  el.style.display = 'flex';
    if (txt && msg) txt.textContent = msg;
  }
  function hideOverlay(id) {
    const el = document.getElementById('aiLoading-' + id);
    if (el) el.style.display = 'none';
  }

  // ── Event delegation — satu listener di document ──────────────
  document.addEventListener('click', function (e) {
    const btn = e.target.closest('.btn-ai-assess');
    if (!btn) return;

    const indikatorId = btn.dataset.indikatorId;
    const amiKode     = btn.dataset.amiKode;
    const prodi       = btn.dataset.prodi;
    const periode     = btn.dataset.periode;
    const modalId     = btn.dataset.targetModal;

    const modalEl = document.getElementById(modalId);
    if (!modalEl) { showToast('Modal tidak ditemukan.', 'danger'); return; }
    bootstrap.Modal.getOrCreateInstance(modalEl).show();

    showOverlay(indikatorId, 'AI sedang membaca dokumen...');
    showToast('AI sedang menganalisis indikator — mohon tunggu...', 'info');
    btn.disabled = true;

    fetch(AI_URL, {
      method : 'POST',
      headers: { 'Content-Type':'application/json', 'X-CSRF-TOKEN':CSRF, 'Accept':'application/json' },
      body   : JSON.stringify({ indikator_id:parseInt(indikatorId), ami_kode:amiKode, prodi:prodi, periode:periode }),
    })
    .then(function (res) {
      if (!res.ok) {
        return res.text().then(function (text) {
          let msg = 'Server error ' + res.status;
          try { const j = JSON.parse(text); msg = j.error || j.message || msg; } catch(e2) {}
          throw new Error(msg);
        });
      }
      showOverlay(indikatorId, 'Memproses hasil AI...');
      return res.json();
    })
    .then(function (data) {
      if (data.error) throw new Error(data.error);

      setVal   ('f-hasilNilai-' + indikatorId, data.hasil_nilai       ?? '');
      setSelect('f-jenisTem-'   + indikatorId, data.jenis_temuan      ?? '');
      setVal   ('f-kriteria-'   + indikatorId, data.hasil_kriteria    ?? '');
      setVal   ('f-deskripsi-'  + indikatorId, data.hasil_deskripsi   ?? '');
      setVal   ('f-akibat-'     + indikatorId, data.hasil_akibat      ?? '');
      setVal   ('f-masalah-'    + indikatorId, data.hasil_masalah     ?? '');
      setVal   ('f-rekomen-'    + indikatorId, data.hasil_rekomendasi ?? '');

      const badge = document.getElementById('ai-badge-' + indikatorId);
      if (badge) {
        badge.classList.remove('d-none');
        const modelLabel = data._model_used ? ' (' + data._model_used + ')' : '';
        badge.innerHTML = '<span class="badge bg-success"><i data-feather="cpu" style="width:12px;height:12px;"></i>'
                        + ' Diisi oleh AI' + modelLabel + ' — harap review sebelum submit</span>';
        if (window.feather) feather.replace();
      }

      const modelMsg = data._model_used ? ' via <b>' + data._model_used + '</b>' : '';
      showToast('Data berhasil diisi oleh AI' + modelMsg + '. Harap review sebelum menyimpan.', 'success');
    })
    .catch(function (err) {
      showToast('Gagal: ' + err.message, 'danger');
      console.error('[AI Assess]', err);
    })
    .finally(function () {
      hideOverlay(indikatorId);
      btn.disabled = false;
    });
  });

  function setVal(id, val) {
    const el = document.getElementById(id);
    if (el) el.value = val;
  }
  function setSelect(id, val) {
    const el = document.getElementById(id);
    if (!el) return;
    for (let i = 0; i < el.options.length; i++)
      el.options[i].selected = (el.options[i].value === String(val));
  }
}());
</script>

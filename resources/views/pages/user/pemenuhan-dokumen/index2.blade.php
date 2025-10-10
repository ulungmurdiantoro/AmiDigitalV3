@extends('layout.master-user')

@push('plugin-styles')
  <link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
@endpush

@section('content')
<div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
  <div>
    <h4 class="mb-3 mb-md-0">Pemenuhan Dokumen {{ $akreditasi->nama }} {{ $jenjang->nama }}</h4>
  </div>
</div>
{{-- @dd($akreditasi); --}}
@foreach($standards as $index => $standard)
  <div class="row mb-4">
    <div class="col-md-12 grid-margin stretch-card">
      <div class="card">
        <div class="card-header bg-primary text-white">
          <h6 id="dataTitle{{ $index + 1 }}" class="mb-0">
            @if($akreditasi->nama === 'LAMEMBA')
              <div class="d-flex align-items-center gap-2">
                <button type="button" class="btn btn-warning btn-sm btn-icon" data-bs-toggle="modal" data-bs-target="#infoModal{{ $standard->id }}">
                  <i data-feather="info"></i>
                </button>
                <span>{{ $standard->nama }} - {{ $akreditasi->nama }} {{ $jenjang->nama }}</span>
              </div>
            @else
              {{ $standard->nama }} - {{ $akreditasi->nama }} {{ $jenjang->nama }}
            @endif
          </h6>
        </div>

        <div class="modal fade" id="infoModal{{ $standard->id }}" tabindex="-1" aria-labelledby="infoModalLabel{{ $standard->id }}" aria-hidden="true">
					<div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
						<div class="modal-content">
							<div class="modal-header justify-content-between">
								<h5 class="modal-title" id="infoModalLabel{{ $standard->id }}">Informasi Kriteria</h5>
								<button type="button" class="btn-close ms-1" data-bs-dismiss="modal" aria-label="Tutup"></button>
							</div>

							<div class="modal-header d-flex justify-content-between align-items-center">
								<div>
									<p class="mb-0"><strong>Kriteria:</strong> {!! nl2br(e($standard->nama)) !!}</p>
								</div>
							</div>

							<div class="modal-body">
								<p>{!! nl2br(e($standard->deskripsi)) !!}</p>

								@foreach ($standard->buktiStandar as $i => $bukti)
									<div class="d-flex justify-content-between align-items-center mb-3">
										<div>
                      <strong>{{ $i + 1 }}.</strong> {{ $bukti->nama }}
                      @if ($bukti->deskripsi) - {{ $bukti->deskripsi }} @endif
                    </div>

										<div class="btn-group">
											<button
												type="button"
												class="btn btn-sm btn-outline-info"
												data-bs-toggle="modal"
												data-bs-target="#buktiInfoModal"
												title="Lihat informasi bukti"
												data-nama="{{ $bukti->nama }}"
												data-deskripsi="{{ $bukti->deskripsi }}"
												data-tipe="{{ $bukti->tipe ?? '' }}"
												data-required="{{ isset($bukti->required) ? (int) $bukti->required : '' }}"
												data-catatan="{{ $bukti->catatan ?? '' }}"
												data-link="{{ $bukti->link ?? '' }}"
											>
												<i data-feather="eye"></i>
											</button>

											<button
                        type="button"
                        class="btn btn-sm btn-outline-primary btn-edit-bukti"
                        data-source-modal="#infoModal{{ $standard->id }}"
                        data-update-url="{{ route('admin.kriteria-dokumen.kelola-bukti.update', $bukti->id) }}"
                        data-periode="2025/2026">
                        <i data-feather="upload"></i>
                      </button>
										</div>
									</div>
								@endforeach
							</div>

							<div class="modal-footer">
								<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
							</div>
						</div>
					</div>
				</div>

        {{-- END INFO MODAL --}}

        <div class="card-body">
          @if($akreditasi->nama === 'LAMEMBA')
            <x-user.data-table.pemenuhan-dokumen-lamemba
              id="dataTableExample{{ $index + 1 }}"
              :standards="$standard->elements"
              :showImportData="$index === 0"
              importTitle="{{ $akreditasi->nama }} {{ $jenjang->nama }}"
            />
          @else
            <x-user.data-table.pemenuhan-dokumen
              id="dataTableExample{{ $index + 1 }}"
              :standards="$standard->elements"
              :showImportData="$index === 0"
              importTitle="{{ $akreditasi->nama }} {{ $jenjang->nama }}"
            />
          @endif
        </div>
      </div>
    </div>
  </div>
@endforeach

<nav class="settings-sidebar">
  <div class="sidebar-body">
    <a href="#" class="settings-sidebar-toggler"><i data-feather="settings"></i></a>
    <h6 class="text-muted mb-2">Daftar Kriteria:</h6>
    <ul class="breadcrumb breadcrumb-dot mb-3 pb-3 border-bottom">
      @foreach($standards as $index => $standard)
        <li class="breadcrumb-item"><a href="#dataTitle{{ $index + 1 }}">{{ $standard->nama }}</a></li>
      @endforeach
    </ul>
  </div>
</nav>

{{-- REUSABLE MODALS (diletakkan SEKALI SAJA, di luar loop) --}}
<div class="modal fade" id="editBuktiModal" tabindex="-1" aria-labelledby="editBuktiModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <form id="editBuktiForm" action="#" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="modal-header">
          <h5 class="modal-title" id="editBuktiModalLabel">Upload Dokumen Bukti</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
        </div>

        <div class="modal-body">
          {{-- File dokumen --}}
          <div class="mb-3 text-start">
            <label for="edit_file" class="form-label">File Dokumen</label>
            <input type="file" class="form-control" id="edit_file" name="file"
                   accept=".pdf,.doc,.docx,.xls,.xlsx,.png,.jpg,.jpeg" required>
            <div class="form-text">Format: PDF/DOC/XLS/IMG. Maksimal sesuai kebijakan server.</div>
          </div>

          {{-- Periode (format 2025/2026) --}}
          <div class="mb-3 text-start">
            <label for="edit_periode" class="form-label">Periode</label>
            <input type="text" class="form-control" id="edit_periode" name="periode"
                   value="2025/2026" placeholder="2025/2026"
                   pattern="^\d{4}\/\d{4}$" title="Gunakan format 2025/2026" required>
            <div class="form-text">Gunakan format <strong>YYYY/YYYY</strong>, contoh: 2025/2026.</div>
          </div>

          {{-- Tanggal Kadaluarsa (dd/mm/yyyy) --}}
          <div class="mb-3 text-start">
            <label for="edit_expired" class="form-label">Tanggal Kadaluarsa</label>
            <input type="date" class="form-control" id="edit_expired" name="expired_at"
                   placeholder="dd/mm/yyyy" pattern="^\d{2}\/\d{2}\/\d{4}$"
                   title="Gunakan format dd/mm/yyyy, contoh: 31/12/2025">
            <div class="form-date">Format <strong>dd/mm/yyyy</strong>, contoh: 31/12/2025. Kosongkan jika tidak berlaku.</div>
          </div>

          {{-- Tambahan Informasi --}}
          <div class="mb-3 text-start">
            <label for="edit_info" class="form-label">Tambahan Informasi</label>
            <textarea class="form-control" id="edit_info" name="info" rows="3" placeholder="Catatan tambahan (opsional)"></textarea>
          </div>

          {{-- Context opsional jika diperlukan server-side --}}
          <input type="hidden" name="importTitle" value="{{ $akreditasi->nama . ' ' . $jenjang->nama }}">
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary">Unggah Dokumen</button>
        </div>
      </form>
    </div>
  </div>
</div>

{{-- REUSABLE: Info Bukti --}}
<div class="modal fade" id="buktiInfoModal" tabindex="-1" aria-labelledby="buktiInfoLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h6 class="modal-title" id="buktiInfoLabel">Detail Bukti</h6>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
      </div>
      <div class="modal-body">
        <dl class="row mb-0">
          <dt class="col-sm-3">Nama Bukti</dt>
          <dd class="col-sm-9" id="bi_nama">—</dd>

          <dt class="col-sm-3">Deskripsi</dt>
          <dd class="col-sm-9" id="bi_deskripsi">—</dd>

          <dt class="col-sm-3">Tipe</dt>
          <dd class="col-sm-9" id="bi_tipe">—</dd>

          <dt class="col-sm-3">Wajib?</dt>
          <dd class="col-sm-9" id="bi_required">—</dd>

          <dt class="col-sm-3">Catatan</dt>
          <dd class="col-sm-9" id="bi_catatan">—</dd>

          <dt class="col-sm-3">Lampiran/Link</dt>
          <dd class="col-sm-9" id="bi_link">—</dd>
        </dl>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
      </div>
    </div>
  </div>
</div>

@endsection

@push('plugin-scripts')
  <script src="{{ asset('assets/plugins/datatables-net/jquery.dataTables.js') }}"></script>
  <script src="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.js') }}"></script>
  <script src="{{ asset('assets/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
@endpush

@push('custom-scripts')
<script>
  // Inisialisasi DataTables (sesuaikan selector bila perlu)
  $(function () {
    $('.table').DataTable();
  });

  document.addEventListener('DOMContentLoaded', function () {
    if (window.feather) feather.replace();

    const editForm = document.getElementById('editBuktiForm');

    // helper: tutup modal sumber lalu buka target
    function openAfterHidingSource(sourceSelector, openFn) {
      const srcEl = document.querySelector(sourceSelector);
      if (!srcEl) { openFn(); return; }
      const srcModal = bootstrap.Modal.getOrCreateInstance(srcEl);
      const handler = function () {
        srcEl.removeEventListener('hidden.bs.modal', handler);
        openFn();
      };
      srcEl.addEventListener('hidden.bs.modal', handler);
      srcModal.hide();
    }

    // ===== EDIT/UPLOAD handler (isi periode, expired, info) =====
    document.querySelectorAll('.btn-edit-bukti').forEach(btn => {
      btn.addEventListener('click', function () {
        const updateUrl = this.dataset.updateUrl;
        const source    = this.dataset.sourceModal || null;

        // nilai default dari data-attribute tombol
        const periode   = this.dataset.periode || '2025/2026';
        const expired   = this.dataset.expired || '';
        const info      = this.dataset.info || '';

        // set action & isian form
        editForm.setAttribute('action', updateUrl);
        document.getElementById('edit_periode').value = periode;
        // catatan: field file tidak bisa di-set via JS (security)

        openAfterHidingSource(source, () => {
          const target = document.getElementById('editBuktiModal');
          bootstrap.Modal.getOrCreateInstance(target).show();
        });
      });
    });

    // ===== INFO BUKTI handler (modal reusable) =====
    const infoModalEl = document.getElementById('buktiInfoModal');
    infoModalEl.addEventListener('show.bs.modal', function (event) {
      const button = event.relatedTarget;
      if (!button) return;

      const get = (attr, fallback='—') => (button.getAttribute(attr) || '').trim() || fallback;

      const nama      = get('data-nama');
      const deskripsi = get('data-deskripsi');
      const tipe      = get('data-tipe');
      const required  = button.getAttribute('data-required'); // bisa '', '0', '1'
      const catatan   = get('data-catatan');
      const link      = (button.getAttribute('data-link') || '').trim();

      infoModalEl.querySelector('#bi_nama').textContent       = nama;
      infoModalEl.querySelector('#bi_deskripsi').textContent  = deskripsi;
      infoModalEl.querySelector('#bi_tipe').textContent       = tipe;
      infoModalEl.querySelector('#bi_catatan').textContent    = catatan;

      // Required → Ya/Tidak/— jika kosong
      const reqEl = infoModalEl.querySelector('#bi_required');
      if (required === '' || required === null) {
        reqEl.textContent = '—';
      } else {
        reqEl.textContent = (required === '1' || required === 'true') ? 'Ya' : 'Tidak';
      }

      // Link
      const linkEl = infoModalEl.querySelector('#bi_link');
      if (link) {
        linkEl.innerHTML = `<a href="${link}" target="_blank" rel="noopener">Buka Lampiran</a>`;
      } else {
        linkEl.textContent = '—';
      }

      if (window.feather) feather.replace();
    });

    // (Opsional) jika kamu TIDAK punya modal delete, hapus handler/hook delete berikut:
    // - const deleteForm ...
    // - semua '.btn-delete-bukti' listener
    // - event 'shown.bs.modal' untuk #deleteBuktiModal
  });
</script>
@endpush




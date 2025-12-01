@extends('layout.master-user')

@push('plugin-styles')
  <link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
@endpush

@section('content')
<div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
  <div>
    <h4 class="mb-3 mb-md-0">
      Pemenuhan Dokumen {{ session('user_akses') ?? '-' }} {{ session('user_penempatan') ?? '-' }}
    </h4>
  </div>
</div>

<div class="row">
  <div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
          <h4 class="card-title">Data Pengajuan AMI (Audit Mutu Internal)</h4>
        </div>

        <div class="row">
          <div class="col-sm-6">
            <div class="mb-3">
              <label for="periode" class="form-label">Periode</label>
              <input name="periode" type="text" class="form-control @error('periode') is-invalid @enderror"
                value="{{ $transaksi_ami->periode ?? '-' }}" disabled readonly/>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-sm-6">
            <div class="mb-3">
              <label for="status" class="form-label">Status</label>
              <input name="status" type="text" class="form-control @error('status') is-invalid @enderror"
                value="{{ $transaksi_ami->status ?? '-' }}" disabled readonly/>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-sm-6">
            <div class="mb-3">
              <label for="auditor" class="form-label">Daftar Auditor</label>
              @forelse(($transaksi_ami->auditorAmi ?? []) as $auditor)
                <input name="auditor" type="text" class="form-control @error('auditor') is-invalid @enderror mb-2"
                  value="{{ $auditor->user->user_nama ?? '-' }}" disabled readonly/>
              @empty
                <input type="text" class="form-control" value="Belum ada auditor" disabled readonly/>
              @endforelse
            </div>
          </div>
        </div>

        @if (($transaksi_ami->status ?? null) === 'Draft')
          <form action="{{ route('user.pengajuan-ami.input-ami.update') }}" method="POST" enctype="multipart/form-data" id="PenggunaAuditorForm">
            @csrf
            <input type="hidden" name="id" value="{{ $transaksi_ami->id }}">
            <input type="hidden" name="status" value="Diajukan">
            <input class="btn btn-success" type="submit" value="Ajukan AMI">
          </form>
        @endif
      </div>
    </div>
  </div>
</div>

@forelse(($standards ?? []) as $index => $standard)
  @php
    $stdId      = $standard->id ?? ('x'.$index);
    $akreNama   = $akreditasi->nama ?? null;
    $jenjangNama= $jenjang->nama ?? '-';
    $stdNama    = $standard->nama ?? 'Tanpa Nama';
  @endphp

  <div class="row mb-4">
    <div class="col-md-12 grid-margin stretch-card">
      <div class="card">
        <div class="card-header bg-primary text-white">
          <h6 id="dataTitle{{ $index + 1 }}" class="mb-0">
            @if($akreNama === 'LAMEMBA')
              <div class="d-flex align-items-center gap-2">
                <button type="button" class="btn btn-warning btn-sm btn-icon" data-bs-toggle="modal" data-bs-target="#infoModal{{ $stdId }}">
                  <i data-feather="info"></i>
                </button>
                <span>{{ $stdNama }} - {{ $akreNama }} {{ $jenjangNama }}</span>
              </div>
            @else
              {{ $stdNama }} - {{ $akreNama ?? '-' }} {{ $jenjangNama }}
            @endif
          </h6>
        </div>

        @if($akreNama === 'LAMEMBA')
          <div class="modal fade" id="infoModal{{ $stdId }}" tabindex="-1" aria-labelledby="infoModalLabel{{ $stdId }}" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
              <div class="modal-content">
                <div class="modal-header justify-content-between">
                  <h5 class="modal-title" id="infoModalLabel{{ $stdId }}">Informasi Kriteria</h5>
                  <button type="button" class="btn-close ms-1" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>

                <div class="modal-header d-flex justify-content-between align-items-center">
                  <div>
                    <p class="mb-0"><strong>Kriteria:</strong> {!! nl2br(e($standard->nama ?? '-')) !!}</p>
                  </div>
                </div>

                <div class="modal-body">
                  <p>{!! nl2br(e($standard->deskripsi ?? '-')) !!}</p>

                  @foreach(($standard->buktiStandar ?? []) as $i => $bukti)
                    <div class="d-flex justify-content-between align-items-center mb-2">
                      <div>
                        <strong>{{ $i + 1 }}.</strong> {{ $bukti->nama ?? '-' }}
                        @if (!empty($bukti->deskripsi))
                          - {{ $bukti->deskripsi }}
                        @endif
                      </div>
                      @php $hasDokumen = $bukti->dokumenCapaian->isNotEmpty(); @endphp
                      <div class="btn-group">
                        <button
                          type="button"
                          class="btn btn-sm {{ $hasDokumen ? 'btn-outline-success' : 'btn-outline-danger' }} btn-view-bukti"
                          data-source-modal="#infoModal{{ $stdId }}"
                          data-tpl="#buktiTpl{{ $bukti->id ?? ('x'.$i) }}"
                        >
                          <i data-feather="eye"></i>
                        </button>
                      </div>
                    </div>

                    <template id="buktiTpl{{ $bukti->id ?? ('x'.$i) }}">
                      <div class="mb-3">
                        <p class="mb-1"><strong>Nama Bukti:</strong></p>
                        <div>{{ $bukti->nama ?? '—' }}</div>
                      </div>

                      <div class="mb-3">
                        <p class="mb-1"><strong>Deskripsi:</strong></p>
                        <div>{!! nl2br(e($bukti->deskripsi ?? '—')) !!}</div>
                      </div>

                      @if(($bukti->dokumenCapaian ?? null) && $bukti->dokumenCapaian->count())
                        <hr>
                        <h6 class="mb-2">Dokumen Terunggah</h6>
                        <ul class="list-group">
                          @foreach($bukti->dokumenCapaian as $dokumen)
                            <li class="list-group-item">
                              <div class="fw-bold">{{ $dokumen->dokumen_nama }}</div>
                              <small class="text-muted d-block">
                                Periode: {{ $dokumen->periode ?? '—' }}
                                @if(!empty($dokumen->dokumen_kadaluarsa))
                                  | Kadaluarsa: {{ $dokumen->dokumen_kadaluarsa }}
                                @endif
                              </small>
                              @if(!empty($dokumen->informasi))
                                <small class="d-block">Info: {{ $dokumen->informasi }}</small>
                              @endif
                              @if(!empty($dokumen->dokumen_file))
                                <a href="{{ asset($dokumen->dokumen_file) }}" target="_blank">Lihat Dokumen</a>
                              @endif
                            </li>
                          @endforeach
                        </ul>
                      @else
                        <p class="text-muted">Belum ada dokumen yang diunggah.</p>
                      @endif
                    </template>
                  @endforeach
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
              </div>
            </div>
          </div>
        @endif
        <div class="card-body">
          @if($akreNama === 'LAMEMBA')
            <x-user.data-table.input-ami-lamemba
              id="dataTableExample{{ $index + 1 }}"
              :standards="($standard->elements ?? collect())"
              :prodis="($prodi)"
              :periodes="($periode)"
              :transaksis="($transaksi_ami)"
              :showImportData="$index === 0"
              importTitle="{{ ($akreNama ?? '-') . ' ' . $jenjangNama }}"
              class="datatable"
            />
          @else
            <x-user.data-table.input-ami
              id="dataTableExample{{ $index + 1 }}"
              :standards="($standard->elements ?? collect())"
              :prodis="($prodi)"
              :periodes="($periode)"
              :transaksis="($transaksi_ami)"
              :showImportData="$index === 0"
              importTitle="{{ ($akreNama ?? '-') . ' ' . $jenjangNama }}"
              class="datatable"
            />
          @endif
        </div>
      </div>
    </div>
  </div>
@empty
  <div class="alert alert-warning">
    Belum ada kriteria/standar yang dapat ditampilkan.
  </div>
@endforelse

<nav class="settings-sidebar">
  <div class="sidebar-body">
    <a href="#" class="settings-sidebar-toggler">
      <i data-feather="settings"></i>
    </a>
    <h6 class="text-muted mb-2">Daftar Kriteria:</h6>
    <div class="mb-3 pb-3 border-bottom">
      <ul class="breadcrumb breadcrumb-dot">
        @foreach(($standards ?? []) as $i => $std)
          <li class="breadcrumb-item">
            <a href="#dataTitle{{ $i + 1 }}">{{ $std->nama ?? 'Kriteria '.($i+1) }}</a>
          </li>
        @endforeach
      </ul>
    </div>
  </div>
</nav>

<div class="modal fade" id="viewBuktiModal" tabindex="-1" aria-labelledby="viewBuktiModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="viewBuktiModalLabel">Informasi Bukti</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
      </div>
      <div class="modal-body" id="viewBuktiBody">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Tutup</button>
      </div>
    </div>
  </div>
</div>
@endsection

@push('plugin-scripts')
  <script src="{{ asset('assets/plugins/jquery/jquery.min.js') }}"></script>
  <script src="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.js') }}"></script>
@endpush

@push('custom-scripts')
  <script>
    $(function () {
      $('.datatable').DataTable();
    });
  </script>

  <script>
    document.addEventListener('DOMContentLoaded', function () {
      if (window.feather) feather.replace();

      let lastSourceModal = null;

      function openAfterHidingSource(sourceSelector, openFn) {
        const srcEl = sourceSelector ? document.querySelector(sourceSelector) : null;
        if (!srcEl) { openFn(); return; }
        const srcModal = bootstrap.Modal.getOrCreateInstance(srcEl);
        const handler = function () {
          srcEl.removeEventListener('hidden.bs.modal', handler);
          openFn();
        };
        srcEl.addEventListener('hidden.bs.modal', handler);
        srcModal.hide();
      }

      document.querySelectorAll('.btn-view-bukti').forEach(btn => {
        btn.addEventListener('click', function () {
          const tplSel = this.dataset.tpl;
          const source = this.dataset.sourceModal || null;
          lastSourceModal = source;

          const tpl  = tplSel ? document.querySelector(tplSel) : null;
          const wrap = document.getElementById('viewBuktiBody');
          if (!wrap) return;

          const openModal = () => {
            wrap.innerHTML = '';
            if (tpl && 'content' in tpl) {
              wrap.appendChild(tpl.content.cloneNode(true));
            } else {
              wrap.innerHTML = '<p class="text-muted">Tidak ada data untuk ditampilkan.</p>';
            }
            const modalEl = document.getElementById('viewBuktiModal');
            const modal   = bootstrap.Modal.getOrCreateInstance(modalEl);
            modal.show();
            if (window.feather) feather.replace();
          };

          openAfterHidingSource(source, openModal);
        });
      });

      const vb = document.getElementById('viewBuktiModal');
      if (vb) {
        vb.addEventListener('shown.bs.modal', () => {
          if (window.feather) feather.replace();
        });

        vb.addEventListener('hidden.bs.modal', () => {
          if (lastSourceModal) {
            const srcEl = document.querySelector(lastSourceModal);
            if (srcEl) {
              const srcModal = bootstrap.Modal.getOrCreateInstance(srcEl);
              srcModal.show();
            }
            lastSourceModal = null;
          }
        });
      }
    });
</script>

@endpush

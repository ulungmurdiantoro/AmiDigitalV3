@extends('layout.master-auditor')

@push('plugin-styles')
  <link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
@endpush

@section('content')
<div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
  <div>
    <h4 class="mb-3 mb-md-0">Pemenuhan Dokumen {{ session('user_akses') }} {{ session('user_penempatan') }}</h4>
  </div>
</div>

<div class="row">
  <div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
          <h4 class="card-title">Data Kesiapan Mutu {{ $transaksi_ami->prodi }} tahun {{ $transaksi_ami->periode }} </h4>
        </div>
        <div><b>Informasi tambahan :</b> </div>
        <div><i>Diajukan oleh {{ $transaksi_ami->prodi }} pada {{ $transaksi_ami->updated_at }} </i></div><br>
        <a href="#" data-bs-toggle="modal" data-bs-target="#selesaiModal" class="btn btn-success btn-icon-text mb-2 mb-md-0" rel="noopener noreferrer">
          <i class="link-icon" data-feather="check-circle"></i> <b>Koreksi AMI</b>
        </a>
        <div class="modal fade" id=selesaiModal tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <form action="{{ route('auditor.evaluasi-ami.update', ['evaluasi_ami' => $transaksi_ami->id]) }}" method="POST" enctype="multipart/form-data" id="PenggunaAuditorForm">
                @csrf
                @method('PUT')
                <div class="modal-header">
                  <h4 class="modal-title" id="exampleModalLabel"><b>Koreksi AMI</b></h4>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                  <input type="hidden" name="ami_kode" class="form-control" value="" hidden>
                  <span>Jika Anda memutuskan untuk mengubah status menjadi "koreksi", maka semua indikator yang berstatus 
                      KTS (Ketidaksesuaian) atau OB (Observasi) harus dikoreksi oleh prodi. <br><br>
                      Harap pastikan bahwa semua indikator yang terkait sudah diperiksa dan disesuaikan sesuai dengan prosedur yang berlaku.</span>
                  <input type="hidden" name="id" value="{{ $transaksi_ami->id }}">
                  <input type="hidden" name="status" value="Koreksi">
                </div>
                <div class="modal-footer">
                  <button type="submit" class="btn btn-success" data-bs-dismiss="modal">Submit</button>
                </div>
              </form>
            </div>
          </div>
        </div>
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
{{-- @dd($akreNama) --}}

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
                      <div class="btn-group">
                        {{-- Hanya tombol LIHAT --}}
                        <button
                          type="button"
                          class="btn btn-sm btn-outline-success btn-view-bukti"
                          data-source-modal="#infoModal{{ $stdId }}"
                          data-tpl="#buktiTpl{{ $bukti->id ?? ('x'.$i) }}"
                        >
                          <i data-feather="eye"></i>
                        </button>
                      </div>
                    </div>

                    {{-- Template konten modal untuk bukti ini --}}
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
{{-- @dd($standard) --}}
        <div class="card-body">
          @if($akreNama === 'LAMEMBA')
            <x-auditor.data-table.audit-ami-lamemba
              id="dataTableExample{{ $index + 1 }}"
              :standards="$standard"
              :elements="$standard->elements ?? collect()"
              :transkasis="$transaksi_ami"
              :periodes="$periode" 
              :prodis="$prodi"
              :showImportData="$index === 0"
              importTitle="{{ ($akreNama ?? '-') . ' ' . $jenjangNama }}"
              class="datatable"
          />

          @else
            <x-auditor.data-table.audit-ami
              id="dataTableExample{{ $index + 1 }}"
              :standards="($standard->elements ?? collect())"
              :transkasis="$transaksi_ami"
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

{{-- MODAL GENERIK: VIEW BUKTI (1x saja di halaman) --}}
<div class="modal fade" id="viewBuktiModal" tabindex="-1" aria-labelledby="viewBuktiModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="viewBuktiModalLabel">Informasi Bukti</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
      </div>
      <div class="modal-body" id="viewBuktiBody">
        <!-- Konten dari <template> akan disisipkan via JS -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Tutup</button>
      </div>
    </div>
  </div>
</div>
@endsection

@push('plugin-scripts')
  {{-- jQuery + DataTables core terlebih dulu, lalu adapter BS5 --}}
  <script src="{{ asset('assets/plugins/jquery/jquery.min.js') }}"></script>
  {{-- <script src="{{ asset('assets/plugins/datatables-net/jquery.dataTables.js') }}"></script> --}}
  <script src="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.js') }}"></script>
@endpush

@push('custom-scripts')
  {{-- Inisialisasi DataTables ke tabel yang memang dipakai --}}
  <script>
    $(function () {
      $('.datatable').DataTable();
    });
  </script>

  {{-- Handler "Lihat Bukti" --}}
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      if (window.feather) feather.replace();

      // helper: tutup modal sumber lalu buka target
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

      // VIEW handler (satu-satunya yang dipakai)
      document.querySelectorAll('.btn-view-bukti').forEach(btn => {
        btn.addEventListener('click', function () {
          const tplSel = this.dataset.tpl;
          const source = this.dataset.sourceModal || null;

          const tpl  = tplSel ? document.querySelector(tplSel) : null;
          const wrap = document.getElementById('viewBuktiBody');
          if (!wrap) return;

          const openModal = () => {
            wrap.innerHTML = ''; // reset isi modal
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

      // Feather re-render saat modal tampil
      const vb = document.getElementById('viewBuktiModal');
      if (vb) vb.addEventListener('shown.bs.modal', () => { if (window.feather) feather.replace(); });
    });
  </script>
@endpush

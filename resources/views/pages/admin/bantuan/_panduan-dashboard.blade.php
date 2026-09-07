{{--
  Panduan ringkas yang tampil di dashboard tiap peran.
  Parameter (@include('pages.admin.bantuan._panduan-dashboard', [...])):
    - roleLabel     : label peran, mis. 'Admin' / 'Auditor' / 'Program Studi'
    - panduanView   : view partial isi panduan, mis. 'pages.admin.bantuan._panduan-admin'
    - bantuanRoute  : (opsional) nama route halaman Bantuan lengkap
    - collapseId    : (opsional) id unik elemen collapse, default 'panduanDashboardCollapse'
--}}
@php
  $roleLabel    = $roleLabel    ?? 'Pengguna';
  $panduanView  = $panduanView  ?? 'pages.admin.bantuan._panduan-prodi';
  $bantuanRoute = $bantuanRoute ?? null;
  $collapseId   = $collapseId   ?? 'panduanDashboardCollapse';
@endphp

<div class="row">
  <div class="col-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
          <div class="d-flex align-items-center gap-2">
            <i data-feather="book-open" style="width:18px;height:18px"></i>
            <h6 class="card-title mb-0">Panduan Penggunaan &mdash; {{ $roleLabel }}</h6>
          </div>
          <div class="d-flex align-items-center gap-2">
            @if ($bantuanRoute && \Illuminate\Support\Facades\Route::has($bantuanRoute))
              <a href="{{ route($bantuanRoute) }}" class="btn btn-sm btn-outline-secondary">
                <i data-feather="external-link" style="width:14px;height:14px"></i> Panduan Lengkap
              </a>
            @endif
            <button class="btn btn-sm btn-primary" type="button" data-bs-toggle="collapse"
                    data-bs-target="#{{ $collapseId }}" aria-expanded="false" aria-controls="{{ $collapseId }}">
              <i data-feather="chevron-down" style="width:14px;height:14px"></i> Lihat Panduan
            </button>
          </div>
        </div>
        <p class="text-muted tx-13 mt-2 mb-0">
          Ringkasan langkah kerja utama Anda pada sistem AMI Digital. Klik <strong>Lihat Panduan</strong> untuk membuka rinciannya.
        </p>

        <div class="collapse mt-3" id="{{ $collapseId }}">
          <hr class="mt-0">
          @include($panduanView)
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    if (typeof feather !== 'undefined') feather.replace();
  });
</script>

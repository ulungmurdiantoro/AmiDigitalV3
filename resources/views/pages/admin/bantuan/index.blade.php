@extends('layout.master')

@section('content')
<div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
  <div>
    <h4 class="mb-1">Panduan Penggunaan Sistem AMI Digital</h4>
    <p class="text-muted tx-13 mb-0">Panduan lengkap untuk seluruh peran — Admin, Program Studi, dan Auditor</p>
  </div>
</div>

<ul class="nav nav-tabs mb-4" id="panduanTab" role="tablist">
  <li class="nav-item">
    <button class="nav-link active fw-semibold" data-bs-toggle="tab" data-bs-target="#tab-admin" type="button">
      <i data-feather="shield" style="width:14px;height:14px" class="me-1"></i>Admin
    </button>
  </li>
  <li class="nav-item">
    <button class="nav-link fw-semibold" data-bs-toggle="tab" data-bs-target="#tab-prodi" type="button">
      <i data-feather="users" style="width:14px;height:14px" class="me-1"></i>Program Studi
    </button>
  </li>
  <li class="nav-item">
    <button class="nav-link fw-semibold" data-bs-toggle="tab" data-bs-target="#tab-auditor" type="button">
      <i data-feather="search" style="width:14px;height:14px" class="me-1"></i>Auditor
    </button>
  </li>
</ul>

<div class="tab-content">
  <div class="tab-pane fade show active" id="tab-admin">
    @include('pages.admin.bantuan._panduan-admin')
  </div>
  <div class="tab-pane fade" id="tab-prodi">
    @include('pages.admin.bantuan._panduan-prodi')
  </div>
  <div class="tab-pane fade" id="tab-auditor">
    @include('pages.admin.bantuan._panduan-auditor')
  </div>
</div>

<script>document.addEventListener('DOMContentLoaded',()=>{ if(typeof feather!=='undefined') feather.replace(); });</script>
@endsection

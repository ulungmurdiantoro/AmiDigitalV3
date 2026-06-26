@extends('layout.master-auditor')

@section('content')
<div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
  <div>
    <h4 class="mb-1">Panduan Penggunaan</h4>
    <p class="text-muted tx-13 mb-0">Panduan untuk Auditor AMI</p>
  </div>
</div>

@include('pages.admin.bantuan._panduan-auditor')

<script>document.addEventListener('DOMContentLoaded',()=>{ if(typeof feather!=='undefined') feather.replace(); });</script>
@endsection

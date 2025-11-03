@extends('layout.master-user')

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
          <h4 class="card-title">Data Pengajuan AMI (Audit Mutu Internal)</h4>
        </div>
        <form action="{{ route('user.pengajuan-ami.store') }}" method="POST" enctype="multipart/form-data">
          @csrf
					@foreach($penjadwalan_ami as $item)
						<div class="row">
							<div class="col-sm-6">
								<div class="mb-3">
									<label for="periode" class="form-label">Periode</label>
									<input type="hidden" name="auditor_kode" value="{{ $item->auditor_kode }}"/>
									<input name="periode" type="text" class="form-control @error('periode') is-invalid @enderror" value="{{ $periode }}" readonly/>
								</div>
							</div><!-- Col -->
						</div>
						<div class="row">
							<div class="col-sm-6">
								<div class="mb-3">
									<label for="status" class="form-label">Status</label>
									<input name="status" type="text" class="form-control @error('periode') is-invalid @enderror" value="Draft" readonly/>
								</div>
							</div><!-- Col -->
						</div>
						<div class="row">
							<div class="col-sm-6">
								<div class="mb-3">
									<label class="form-label">Daftar Auditor</label>
									@php
										$auditor_names = '';
									@endphp
									@foreach($item->auditor_ami as $auditor)
										@foreach($auditors as $auditor_user)
											@if($auditor_user->users_code == $auditor->users_kode)
												<input type="text" class="form-control @error('periode') is-invalid @enderror" value="{{ $auditor_user->user_nama }} ({{ $auditor->tim_ami }})" readonly/>
											@endif
										@endforeach
									@endforeach
								</div>
							</div><!-- Col -->
						</div>
						<input class="btn btn-primary" type="submit" value="Mulai AMI" style="font-size: 16px; padding: 8px 15px;">
					@endforeach
        </form>
      </div>
    </div>
  </div>
</div>

@endsection

@push('plugin-scripts')
  <script src="{{ asset('assets/plugins/datatables-net/jquery.dataTables.js') }}"></script>
  <script src="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.js') }}"></script>
@endpush

@push('custom-scripts')
  <script src="{{ asset('assets/js/data-table.js') }}"></script>
@endpush
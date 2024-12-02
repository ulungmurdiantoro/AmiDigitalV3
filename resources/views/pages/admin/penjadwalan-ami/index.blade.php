@extends('layout.master')

@push('plugin-styles')
  <link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
@endpush

@section('content')
<nav class="page-breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="#">Pengguna Sistem</a></li>
    <li class="breadcrumb-item active" aria-current="page">Penjadwalan AMI</li>
  </ol>
</nav>

<div class="row">
  <div class="col-md-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
          <div>
            <h4 class="mb-3 mb-md-0">Daftar Jadwal AMI</h4>
          </div>
          <div class="d-flex align-items-center flex-wrap text-nowrap">
            <a href="{{ url('/admin/penjadwalan-ami/create') }}" type="button" class="btn btn-primary btn-icon-text mb-2 mb-md-0">
              <i class="btn-icon-prepend" data-feather="plus-circle"></i>
                Tambah Data
            </a>
          </div>
        </div>
        <div class="table-responsive">
          <table id="dataTableExample"  class="table table-striped">
            <thead>
              <tr>
                <th class="text-bg-secondary">Program Studi</th>
                <th class="text-bg-secondary">Fakultas</th>
                <th class="text-bg-secondary">Standar Akreditasi</th>
                <th class="text-bg-secondary">Periode</th>
                <th class="text-bg-secondary">Jadwal AMI</th>
                <th class="text-bg-secondary">TIM Auditor</th>
                <th class="text-bg-secondary">Aksi</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($PenjadwalanAmis as $PenjadwalanAmi)
                <tr>
                  <td>{{ $PenjadwalanAmi->prodi }}</td>
                  <td>{{ $PenjadwalanAmi->fakultas }}</td>
                  <td>{{ $PenjadwalanAmi->standar_akreditasi }}</td>
                  <td>{{ $PenjadwalanAmi->periode }}</td>
                  <td>
                    <!-- Calendar Button with Modal Trigger -->
                    <a href="#" data-bs-toggle="modal" data-bs-target="#calendarModal{{ $PenjadwalanAmi->id }}" class="btn btn-warning btn-icon" rel="noopener noreferrer">
                      <i data-feather="calendar"></i>
                    </a>
                  </td>
                  <td>
                    <!-- User Button with Modal Trigger -->
                    <a href="#" data-bs-toggle="modal" data-bs-target="#userModal{{ $PenjadwalanAmi->id }}" class="btn btn-info btn-icon" rel="noopener noreferrer">
                      <i data-feather="user"></i>
                    </a>
                  </td>
                  <td>
                    <a href="{{ url('/admin/penjadwalan-ami/' . $PenjadwalanAmi->id . '/edit/') }}" class="btn btn-primary btn-icon" rel="noopener noreferrer">
                      <i data-feather="edit"></i>
                    </a>
                    <a href="#" class="btn btn-danger btn-icon" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $PenjadwalanAmi->id }}" rel="noopener noreferrer">
                      <i data-feather="delete"></i>
                    </a> 
                  </td>
                </tr>
                <!-- Calendar Modal for each PenjadwalanAmi item -->
                <div class="modal fade" id="calendarModal{{ $PenjadwalanAmi->id }}" tabindex="-1" aria-labelledby="calendarModal{{ $PenjadwalanAmi->id }}Label" aria-hidden="true">
                  <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title" id="calendarModal{{ $PenjadwalanAmi->id }}Label">Jadwal AMI</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <div class="modal-body">
                        <ul>
                          <li><strong>Opening Meeting AMI:</strong><br> {{ $PenjadwalanAmi->opening_ami }} </li> <hr>
                          <li><strong>Pengisian Dokumen:</strong><br> {{ $PenjadwalanAmi->pengisian_dokumen }}</li> <hr>
                          <li><strong>Deskevaluasi:</strong><br> {{ $PenjadwalanAmi->deskevaluasion }}</li> <hr>
                          <li><strong>Audit Lapang:</strong><br> {{ $PenjadwalanAmi->assessment }}</li> <hr>
                          <li><strong>Penyusunan Tindakan Perbaikan dan Pencegahan:</strong><br> {{ $PenjadwalanAmi->tindakan_koreksi }} </li> <hr>
                          <li><strong>Penyusunan Laporan AMI:</strong><br> {{ $PenjadwalanAmi->laporan_ami }} </li> <hr>
                          <li><strong>RTM:</strong><br> {{ $PenjadwalanAmi->rtm }} </li>
                        </ul>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- User Modal for each PenjadwalanAmi item -->
                <div class="modal fade" id="userModal{{ $PenjadwalanAmi->id }}" tabindex="-1" aria-labelledby="userModal{{ $PenjadwalanAmi->id }}Label" aria-hidden="true">
                  <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title" id="userModal{{ $PenjadwalanAmi->id }}Label">User Information</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <div class="modal-body">
                        <span style="float: right;">
                          <a href="#" data-bs-toggle="modal" data-bs-target="#tambahAuditor{{ $PenjadwalanAmi->auditor_kode }}">
                            <i class="fas fa-plus-circle"></i> Tambah Auditor
                          </a>
                          <a href="#" data-bs-toggle="modal" data-bs-target="#hapusAuditor{{ $PenjadwalanAmi->auditor_kode }}" style="color: red;">
                            <i class="fas fa-minus-circle"></i> Hapus Auditor
                          </a>
                        </span>
                        @foreach($PenjadwalanAmi->auditor_ami as $auditor)
                          @foreach($auditors as $auditor_user)
                            @if($auditor_user->users_code == $auditor->users_kode)
                              {{ $loop->iteration }}. {{ $auditor_user->user_nama }} ( {{ $auditor->tim_ami }} )<br>
                            @endif
                          @endforeach
                        @endforeach
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Delete Modal -->
                <div class="modal fade" id="deleteModal{{ $PenjadwalanAmi->id }}" tabindex="-1" aria-labelledby="deleteModalLabel{{ $PenjadwalanAmi->id }}" aria-hidden="true">
                  <div class="modal-dialog">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title" id="deleteModalLabel{{ $PenjadwalanAmi->id }}">Hapus Penjadwalan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <div class="modal-body">
                          <p>Apakah Anda yakin ingin menghapus penjadwalan ini?</p>
                      </div>
                      <div class="modal-footer">
                        <form action="{{ route('admin.penjadwalan-ami.destroy', $PenjadwalanAmi->id) }}" method="POST">
                          @csrf
                          @method('DELETE')
                          <button type="submit" class="btn btn-danger">Hapus</button>
                          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        </form>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Tambah Auditor Modal -->
                <div class="modal fade" id="tambahAuditor{{ $PenjadwalanAmi->auditor_kode }}" tabindex="-1" aria-labelledby="tambahAuditor{{ $PenjadwalanAmi->auditor_kode }}Label" aria-hidden="true">
                  <div class="modal-dialog">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title" id="tambahAuditor{{ $PenjadwalanAmi->auditor_kode }}Label">Tambah Auditor</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <div class="modal-body">
                        <form action="{{ route('admin.penjadwalan-ami.storeauditor') }}" method="POST">
                          @csrf
                          <div class="mb-3">
                            <input type="text" class="form-control" id="auditorKode" name="auditor_kode" value="{{ $PenjadwalanAmi->auditor_kode }}" hidden>
                            <label for="auditorName" class="form-label">Nama Auditor</label>
                            <select class="form-select @error('auditorName') is-invalid @enderror" name="auditorName">
                              <option selected disabled>-</option>
                              @foreach($auditors as $auditor)
                                <option value="{{ $auditor->users_code }}">{{$auditor->user_nama }}</option>
                              @endforeach
                            </select>
                            @error('auditorName')
                              <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                          </div>
                          <div class="mb-3">
                            <label for="tim_ami" class="form-label">Keanggotaan</label>
                            <select class="form-select @error('tim_ami') is-invalid @enderror" name="tim_ami">
                                <option selected disabled>-</option>
                                <option value="Ketua">Ketua</option>
                                <option value="Anggota">Anggota</option>
                            </select>
                            @error('tim_ami')
                              <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                          </div>
                          <button type="submit" class="btn btn-primary">Tambah</button>
                        </form>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Delete Modal for each PenjadwalanAmi item -->
                <div class="modal fade" id="hapusAuditor{{ $PenjadwalanAmi->auditor_kode }}" tabindex="-1" aria-labelledby="tambahAuditor{{ $PenjadwalanAmi->auditor_kode }}Label" aria-hidden="true">
                  <div class="modal-dialog">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title" id="hapusAuditor{{ $PenjadwalanAmi->auditor_kode }}Label">Tambah Auditor</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <div class="modal-body">
                        <form action="{{ route('admin.penjadwalan-ami.destroyauditor') }}" method="POST">
                          @csrf
                          @method('DELETE')
                          <label for="auditorName" class="form-label">Pilih Auditor</label>
                          <select class="form-select @error('auditorName') is-invalid @enderror" name="auditorName">
                            <option selected disabled>-</option>
                            @foreach($PenjadwalanAmi->auditor_ami as $auditor)
                              @foreach($auditors as $auditor_user)
                                @if($auditor_user->users_code == $auditor->users_kode)
                                  <option value="{{ $auditor->users_kode }}">
                                    {{ $loop->iteration }}. {{ $auditor_user->user_nama }} ({{ $auditor->tim_ami }})
                                  </option>
                                @endif
                              @endforeach
                            @endforeach
                          </select>
                          @error('auditorName')
                            <div class="invalid-feedback">{{ $message }}</div>
                          @enderror
                          <br>
                          <p>Apakah Anda yakin ingin menghapus auditor ini?</p>
                          <br>
                          <button type="submit" class="btn btn-danger">Hapus</button>
                          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        </form>
                      </div>
                    </div>
                  </div>
                </div>

              @endforeach
            </tbody>

          </table>
        </div>
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
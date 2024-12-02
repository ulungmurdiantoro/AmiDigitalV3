@extends('layout.master-auditor')

@push('plugin-styles')
  <link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
@endpush

@section('content')
<nav class="page-breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="#">Evaluasi Mutu</a></li>
    <li class="breadcrumb-item active" aria-current="page">Input Ami</li>
  </ol>
</nav>

<div class="row">
  <div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
          <h4 class="card-title">Data Kesiapan Mutu S1-Teknik Informatika tahun 2024</h4>
        </div>
        <div><b>Informasi tambahan :</b> </div>
        <div><i>Diajukan oleh  pada </i></div>
        <a href="#" data-bs-toggle="modal" data-bs-target="#selesaiModal" class="btn btn-success btn-icon-text mb-2 mb-md-0" rel="noopener noreferrer">
          <i class="link-icon" data-feather="check-circle"></i> <b>Selesaikan AMI</b>
        </a>
        <div class="modal fade" id=selesaiModal tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <form action="{{ route('admin.kriteria-dokumen.storeImport') }}" method="POST" enctype="multipart/form-data" id="PenggunaAuditorForm">
              @csrf
                <div class="modal-header">
                  <h4 class="modal-title" id="exampleModalLabel"><b>Menyelesaikan dan menyudahi AMI</b></h4>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                  <input type="hidden" name="ami_kode" class="form-control" value="" hidden>
                  <span>Apakah Anda yakin akan menyelesaikan dan menyudahi aktivitas AMI (Audit Mutu Internal) dari periode </span>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-success" data-bs-dismiss="modal">Submit</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-md-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
          <div>
            <p class="mb-3">
              <b>
                {{ $nama_data_standar_k1 }}
              </b>
            </p>
          </div>
        </div>
        <div class="table-responsive">
          <table id="dataTableExample1" class="display table table-striped table-hover">
            <thead>
              <tr>
                <th class="text-bg-secondary">Kode</th>
                <th class="text-bg-secondary">Elemen</th>
                <th class="text-bg-secondary">Indikator</th>
                <th class="text-bg-secondary">Informasi</th>
                <th class="text-bg-secondary">Target</th>
                <th class="text-bg-secondary">Capaian</th>
                <th class="text-bg-secondary">Nilai</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($data_standar_k1 as $standard)
                <tr>
                  <td style="text-align: center;">{{ $standard->indikator_kode }}</td>
                  <td>{{ $standard->elemen_nama }}</td>
                  <td>
                    @php
                      $lines = explode("\n", $standard->indikator_nama);
                    @endphp
                    @foreach ($lines as $line)
                      {{ $line }}<br>
                    @endforeach
                  </td>
                  <td>
                    <a href="#" data-bs-toggle="modal" data-bs-target="#infoModal{{ $standard->id }}" class="btn btn-warning btn-icon" rel="noopener noreferrer">
                      <i data-feather="info"></i>
                    </a>
                  </td>
                  <td>
                    <a href="#" data-bs-toggle="modal" data-bs-target="#targetModal{{ $standard->id }}" class="btn btn-primary btn-icon" rel="noopener noreferrer">
                      <i data-feather="info"></i>
                    </a>
                  </td>
                  <td>
                    <a href="#" data-bs-toggle="modal" data-bs-target="#capaianModal{{ $standard->id }}" class="btn btn-secondary btn-icon" rel="noopener noreferrer">
                      <i data-feather="info"></i>
                    </a>
                  </td>
                  <td>
                    <input type='text' readonly value="" name='nilai_mandiri' style='background-color: transparent; border: 0px solid; width: 100%; line-height: 100px; display:none;'>
                    <a data-toggle="modal" data-target='#editmodal"{{ $standard->id }}"' style="color:royalblue;">[Edit]</a>
                  </td>
                </tr>
                <div class="modal fade" id="infoModal{{ $standard->id }}" tabindex="-1" aria-labelledby="infoModal{{ $standard->id }}Label" aria-hidden="true">
                  <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title" id="calendarModal{{ $standard->id }}Label">Informasi Penilaian</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <div class="modal-body">
                      @php
                        $lines = explode("\n", $standard->indikator_info);
                      @endphp
                      @foreach ($lines as $line)
                        {{ $line }}<br>
                      @endforeach
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
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

<div class="row">
  <div class="col-md-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <p class="mb-3">
          <b>
            {{ $nama_data_standar_k2 }}
          </b>
        </p>
        <div class="table-responsive">
          <table id="dataTableExample2" class="display table table-striped table-hover">
            <thead>
              <tr>
                <th class="text-bg-secondary">Kode</th>
                <th class="text-bg-secondary">Elemen</th>
                <th class="text-bg-secondary">Indikator</th>
                <th class="text-bg-secondary">Informasi</th>
                <th class="text-bg-secondary">Target</th>
                <th class="text-bg-secondary">Capaian</th>
                <th class="text-bg-secondary">Nilai</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($data_standar_k2 as $standard)
                <tr>
                  <td style="text-align: center;">{{ $standard->indikator_kode }}</td>
                  <td>{{ $standard->elemen_nama }}</td>
                  <td>
                    @php
                      $lines = explode("\n", $standard->indikator_nama);
                    @endphp
                    @foreach ($lines as $line)
                      {{ $line }}<br>
                    @endforeach
                  </td>
                  <td>
                    <a href="#" data-bs-toggle="modal" data-bs-target="#infoModal{{ $standard->id }}" class="btn btn-warning btn-icon" rel="noopener noreferrer">
                      <i data-feather="info"></i>
                    </a>
                  </td>
                  <td>
                    <a href="#" data-bs-toggle="modal" data-bs-target="#targetModal{{ $standard->id }}" class="btn btn-primary btn-icon" rel="noopener noreferrer">
                      <i data-feather="info"></i>
                    </a>
                  </td>
                  <td>
                    <a href="#" data-bs-toggle="modal" data-bs-target="#capaianModal{{ $standard->id }}" class="btn btn-secondary btn-icon" rel="noopener noreferrer">
                      <i data-feather="info"></i>
                    </a>
                  </td>
                  <td>
                    <input type='text' readonly value="" name='nilai_mandiri' style='background-color: transparent; border: 0px solid; width: 100%; line-height: 100px; display:none;'>
                    <a data-toggle="modal" data-target='#editmodal"{{ $standard->id }}"' style="color:royalblue;">[Edit]</a>
                  </td>
                </tr>
                <div class="modal fade" id="infoModal{{ $standard->id }}" tabindex="-1" aria-labelledby="infoModal{{ $standard->id }}Label" aria-hidden="true">
                  <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title" id="calendarModal{{ $standard->id }}Label">Informasi Penilaian</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <div class="modal-body">
                      @php
                        $lines = explode("\n", $standard->indikator_info);
                      @endphp
                      @foreach ($lines as $line)
                        {{ $line }}<br>
                      @endforeach
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
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

<div class="row">
  <div class="col-md-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <p class="mb-3">
          <b>
            {{ $nama_data_standar_k3 }}
          </b>
        </p>
        <div class="table-responsive">
          <table id="dataTableExample3" class="display table table-striped table-hover">
            <thead>
              <tr>
                <th class="text-bg-secondary">Kode</th>
                <th class="text-bg-secondary">Elemen</th>
                <th class="text-bg-secondary">Indikator</th>
                <th class="text-bg-secondary">Informasi</th>
                <th class="text-bg-secondary">Target</th>
                <th class="text-bg-secondary">Capaian</th>
                <th class="text-bg-secondary">Nilai</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($data_standar_k3 as $standard)
                <tr>
                  <td style="text-align: center;">{{ $standard->indikator_kode }}</td>
                  <td>{{ $standard->elemen_nama }}</td>
                  <td>
                    @php
                      $lines = explode("\n", $standard->indikator_nama);
                    @endphp
                    @foreach ($lines as $line)
                      {{ $line }}<br>
                    @endforeach
                  </td>
                  <td>
                    <a href="#" data-bs-toggle="modal" data-bs-target="#infoModal{{ $standard->id }}" class="btn btn-warning btn-icon" rel="noopener noreferrer">
                      <i data-feather="info"></i>
                    </a>
                  </td>
                  <td>
                    <a href="#" data-bs-toggle="modal" data-bs-target="#targetModal{{ $standard->id }}" class="btn btn-primary btn-icon" rel="noopener noreferrer">
                      <i data-feather="info"></i>
                    </a>
                  </td>
                  <td>
                    <a href="#" data-bs-toggle="modal" data-bs-target="#capaianModal{{ $standard->id }}" class="btn btn-secondary btn-icon" rel="noopener noreferrer">
                      <i data-feather="info"></i>
                    </a>
                  </td>
                  <td>
                    <input type='text' readonly value="" name='nilai_mandiri' style='background-color: transparent; border: 0px solid; width: 100%; line-height: 100px; display:none;'>
                    <a data-toggle="modal" data-target='#editmodal"{{ $standard->id }}"' style="color:royalblue;">[Edit]</a>
                  </td>
                </tr>
                <div class="modal fade" id="infoModal{{ $standard->id }}" tabindex="-1" aria-labelledby="infoModal{{ $standard->id }}Label" aria-hidden="true">
                  <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title" id="calendarModal{{ $standard->id }}Label">Informasi Penilaian</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <div class="modal-body">
                      @php
                        $lines = explode("\n", $standard->indikator_info);
                      @endphp
                      @foreach ($lines as $line)
                        {{ $line }}<br>
                      @endforeach
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
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

<div class="row">
  <div class="col-md-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <p class="mb-3">
          <b>
            {{ $nama_data_standar_k4 }}
          </b>
        </p>
        <div class="table-responsive">
          <table id="dataTableExample4" class="display table table-striped table-hover">
            <thead>
              <tr>
                <th class="text-bg-secondary">Kode</th>
                <th class="text-bg-secondary">Elemen</th>
                <th class="text-bg-secondary">Indikator</th>
                <th class="text-bg-secondary">Informasi</th>
                <th class="text-bg-secondary">Target</th>
                <th class="text-bg-secondary">Capaian</th>
                <th class="text-bg-secondary">Nilai</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($data_standar_k4 as $standard)
                <tr>
                  <td style="text-align: center;">{{ $standard->indikator_kode }}</td>
                  <td>{{ $standard->elemen_nama }}</td>
                  <td>
                    @php
                      $lines = explode("\n", $standard->indikator_nama);
                    @endphp
                    @foreach ($lines as $line)
                      {{ $line }}<br>
                    @endforeach
                  </td>
                  <td>
                    <a href="#" data-bs-toggle="modal" data-bs-target="#infoModal{{ $standard->id }}" class="btn btn-warning btn-icon" rel="noopener noreferrer">
                      <i data-feather="info"></i>
                    </a>
                  </td>
                  <td>
                    <a href="#" data-bs-toggle="modal" data-bs-target="#targetModal{{ $standard->id }}" class="btn btn-primary btn-icon" rel="noopener noreferrer">
                      <i data-feather="info"></i>
                    </a>
                  </td>
                  <td>
                    <a href="#" data-bs-toggle="modal" data-bs-target="#capaianModal{{ $standard->id }}" class="btn btn-secondary btn-icon" rel="noopener noreferrer">
                      <i data-feather="info"></i>
                    </a>
                  </td>
                  <td>
                    <input type='text' readonly value="" name='nilai_mandiri' style='background-color: transparent; border: 0px solid; width: 100%; line-height: 100px; display:none;'>
                    <a data-toggle="modal" data-target='#editmodal"{{ $standard->id }}"' style="color:royalblue;">[Edit]</a>
                  </td>
                </tr>
                <div class="modal fade" id="infoModal{{ $standard->id }}" tabindex="-1" aria-labelledby="infoModal{{ $standard->id }}Label" aria-hidden="true">
                  <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title" id="calendarModal{{ $standard->id }}Label">Informasi Penilaian</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <div class="modal-body">
                      @php
                        $lines = explode("\n", $standard->indikator_info);
                      @endphp
                      @foreach ($lines as $line)
                        {{ $line }}<br>
                      @endforeach
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
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

<div class="row">
  <div class="col-md-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <p class="mb-3">
          <b>
            {{ $nama_data_standar_k5 }}
          </b>
        </p>
        <div class="table-responsive">
          <table id="dataTableExample5" class="display table table-striped table-hover">
            <thead>
              <tr>
                <th class="text-bg-secondary">Kode</th>
                <th class="text-bg-secondary">Elemen</th>
                <th class="text-bg-secondary">Indikator</th>
                <th class="text-bg-secondary">Informasi</th>
                <th class="text-bg-secondary">Target</th>
                <th class="text-bg-secondary">Capaian</th>
                <th class="text-bg-secondary">Nilai</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($data_standar_k5 as $standard)
                <tr>
                  <td style="text-align: center;">{{ $standard->indikator_kode }}</td>
                  <td>{{ $standard->elemen_nama }}</td>
                  <td>
                    @php
                      $lines = explode("\n", $standard->indikator_nama);
                    @endphp
                    @foreach ($lines as $line)
                      {{ $line }}<br>
                    @endforeach
                  </td>
                  <td>
                    <a href="#" data-bs-toggle="modal" data-bs-target="#infoModal{{ $standard->id }}" class="btn btn-warning btn-icon" rel="noopener noreferrer">
                      <i data-feather="info"></i>
                    </a>
                  </td>
                  <td>
                    <a href="#" data-bs-toggle="modal" data-bs-target="#targetModal{{ $standard->id }}" class="btn btn-primary btn-icon" rel="noopener noreferrer">
                      <i data-feather="info"></i>
                    </a>
                  </td>
                  <td>
                    <a href="#" data-bs-toggle="modal" data-bs-target="#capaianModal{{ $standard->id }}" class="btn btn-secondary btn-icon" rel="noopener noreferrer">
                      <i data-feather="info"></i>
                    </a>
                  </td>
                  <td>
                    <input type='text' readonly value="" name='nilai_mandiri' style='background-color: transparent; border: 0px solid; width: 100%; line-height: 100px; display:none;'>
                    <a data-toggle="modal" data-target='#editmodal"{{ $standard->id }}"' style="color:royalblue;">[Edit]</a>
                  </td>
                </tr>
                <div class="modal fade" id="infoModal{{ $standard->id }}" tabindex="-1" aria-labelledby="infoModal{{ $standard->id }}Label" aria-hidden="true">
                  <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title" id="calendarModal{{ $standard->id }}Label">Informasi Penilaian</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <div class="modal-body">
                      @php
                        $lines = explode("\n", $standard->indikator_info);
                      @endphp
                      @foreach ($lines as $line)
                        {{ $line }}<br>
                      @endforeach
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
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

<div class="row">
  <div class="col-md-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <p class="mb-3">
          <b>
            {{ $nama_data_standar_k6 }}
          </b>
        </p>
        <div class="table-responsive">
          <table id="dataTableExample6" class="display table table-striped table-hover">
            <thead>
              <tr>
                <th class="text-bg-secondary">Kode</th>
                <th class="text-bg-secondary">Elemen</th>
                <th class="text-bg-secondary">Indikator</th>
                <th class="text-bg-secondary">Informasi</th>
                <th class="text-bg-secondary">Target</th>
                <th class="text-bg-secondary">Capaian</th>
                <th class="text-bg-secondary">Nilai</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($data_standar_k6 as $standard)
                <tr>
                  <td style="text-align: center;">{{ $standard->indikator_kode }}</td>
                  <td>{{ $standard->elemen_nama }}</td>
                  <td>
                    @php
                      $lines = explode("\n", $standard->indikator_nama);
                    @endphp
                    @foreach ($lines as $line)
                      {{ $line }}<br>
                    @endforeach
                  </td>
                  <td>
                    <a href="#" data-bs-toggle="modal" data-bs-target="#infoModal{{ $standard->id }}" class="btn btn-warning btn-icon" rel="noopener noreferrer">
                      <i data-feather="info"></i>
                    </a>
                  </td>
                  <td>
                    <a href="#" data-bs-toggle="modal" data-bs-target="#targetModal{{ $standard->id }}" class="btn btn-primary btn-icon" rel="noopener noreferrer">
                      <i data-feather="info"></i>
                    </a>
                  </td>
                  <td>
                    <a href="#" data-bs-toggle="modal" data-bs-target="#capaianModal{{ $standard->id }}" class="btn btn-secondary btn-icon" rel="noopener noreferrer">
                      <i data-feather="info"></i>
                    </a>
                  </td>
                  <td>
                    <input type='text' readonly value="" name='nilai_mandiri' style='background-color: transparent; border: 0px solid; width: 100%; line-height: 100px; display:none;'>
                    <a data-toggle="modal" data-target='#editmodal"{{ $standard->id }}"' style="color:royalblue;">[Edit]</a>
                  </td>
                </tr>
                <div class="modal fade" id="infoModal{{ $standard->id }}" tabindex="-1" aria-labelledby="infoModal{{ $standard->id }}Label" aria-hidden="true">
                  <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title" id="calendarModal{{ $standard->id }}Label">Informasi Penilaian</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <div class="modal-body">
                      @php
                        $lines = explode("\n", $standard->indikator_info);
                      @endphp
                      @foreach ($lines as $line)
                        {{ $line }}<br>
                      @endforeach
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
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

<div class="row">
  <div class="col-md-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <p class="mb-3">
          <b>
            {{ $nama_data_standar_k7 }}
          </b>
        </p>
        <div class="table-responsive">
          <table id="dataTableExample7" class="display table table-striped table-hover">
            <thead>
              <tr>
                <th class="text-bg-secondary">Kode</th>
                <th class="text-bg-secondary">Elemen</th>
                <th class="text-bg-secondary">Indikator</th>
                <th class="text-bg-secondary">Informasi</th>
                <th class="text-bg-secondary">Target</th>
                <th class="text-bg-secondary">Capaian</th>
                <th class="text-bg-secondary">Nilai</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($data_standar_k7 as $standard)
                <tr>
                  <td style="text-align: center;">{{ $standard->indikator_kode }}</td>
                  <td>{{ $standard->elemen_nama }}</td>
                  <td>
                    @php
                      $lines = explode("\n", $standard->indikator_nama);
                    @endphp
                    @foreach ($lines as $line)
                      {{ $line }}<br>
                    @endforeach
                  </td>
                  <td>
                    <a href="#" data-bs-toggle="modal" data-bs-target="#infoModal{{ $standard->id }}" class="btn btn-warning btn-icon" rel="noopener noreferrer">
                      <i data-feather="info"></i>
                    </a>
                  </td>
                  <td>
                    <a href="#" data-bs-toggle="modal" data-bs-target="#targetModal{{ $standard->id }}" class="btn btn-primary btn-icon" rel="noopener noreferrer">
                      <i data-feather="info"></i>
                    </a>
                  </td>
                  <td>
                    <a href="#" data-bs-toggle="modal" data-bs-target="#capaianModal{{ $standard->id }}" class="btn btn-secondary btn-icon" rel="noopener noreferrer">
                      <i data-feather="info"></i>
                    </a>
                  </td>
                  <td>
                    <input type='text' readonly value="" name='nilai_mandiri' style='background-color: transparent; border: 0px solid; width: 100%; line-height: 100px; display:none;'>
                    <a data-toggle="modal" data-target='#editmodal"{{ $standard->id }}"' style="color:royalblue;">[Edit]</a>
                  </td>
                </tr>
                <div class="modal fade" id="infoModal{{ $standard->id }}" tabindex="-1" aria-labelledby="infoModal{{ $standard->id }}Label" aria-hidden="true">
                  <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title" id="calendarModal{{ $standard->id }}Label">Informasi Penilaian</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <div class="modal-body">
                      @php
                        $lines = explode("\n", $standard->indikator_info);
                      @endphp
                      @foreach ($lines as $line)
                        {{ $line }}<br>
                      @endforeach
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
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

<div class="row">
  <div class="col-md-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <p class="mb-3">
          <b>
            {{ $nama_data_standar_k8 }}
          </b>
        </p>
        <div class="table-responsive">
          <table id="dataTableExample8" class="display table table-striped table-hover">
            <thead>
              <tr>
                <th class="text-bg-secondary">Kode</th>
                <th class="text-bg-secondary">Elemen</th>
                <th class="text-bg-secondary">Indikator</th>
                <th class="text-bg-secondary">Informasi</th>
                <th class="text-bg-secondary">Target</th>
                <th class="text-bg-secondary">Capaian</th>
                <th class="text-bg-secondary">Nilai</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($data_standar_k8 as $standard)
                <tr>
                  <td style="text-align: center;">{{ $standard->indikator_kode }}</td>
                  <td>{{ $standard->elemen_nama }}</td>
                  <td>
                    @php
                      $lines = explode("\n", $standard->indikator_nama);
                    @endphp
                    @foreach ($lines as $line)
                      {{ $line }}<br>
                    @endforeach
                  </td>
                  <td>
                    <a href="#" data-bs-toggle="modal" data-bs-target="#infoModal{{ $standard->id }}" class="btn btn-warning btn-icon" rel="noopener noreferrer">
                      <i data-feather="info"></i>
                    </a>
                  </td>
                  <td>
                    <a href="#" data-bs-toggle="modal" data-bs-target="#targetModal{{ $standard->id }}" class="btn btn-primary btn-icon" rel="noopener noreferrer">
                      <i data-feather="info"></i>
                    </a>
                  </td>
                  <td>
                    <a href="#" data-bs-toggle="modal" data-bs-target="#capaianModal{{ $standard->id }}" class="btn btn-secondary btn-icon" rel="noopener noreferrer">
                      <i data-feather="info"></i>
                    </a>
                  </td>
                  <td>
                    <input type='text' readonly value="" name='nilai_mandiri' style='background-color: transparent; border: 0px solid; width: 100%; line-height: 100px; display:none;'>
                    <a data-toggle="modal" data-target='#editmodal"{{ $standard->id }}"' style="color:royalblue;">[Edit]</a>
                  </td>
                </tr>
                <div class="modal fade" id="infoModal{{ $standard->id }}" tabindex="-1" aria-labelledby="infoModal{{ $standard->id }}Label" aria-hidden="true">
                  <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title" id="calendarModal{{ $standard->id }}Label">Informasi Penilaian</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <div class="modal-body">
                      @php
                        $lines = explode("\n", $standard->indikator_info);
                      @endphp
                      @foreach ($lines as $line)
                        {{ $line }}<br>
                      @endforeach
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
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

<div class="row">
  <div class="col-md-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <p class="mb-3">
          <b>
            {{ $nama_data_standar_k9 }}
          </b>
        </p>
        <div class="table-responsive">
          <table id="dataTableExample9" class="display table table-striped table-hover">
            <thead>
              <tr>
                <th class="text-bg-secondary">Kode</th>
                <th class="text-bg-secondary">Elemen</th>
                <th class="text-bg-secondary">Indikator</th>
                <th class="text-bg-secondary">Informasi</th>
                <th class="text-bg-secondary">Target</th>
                <th class="text-bg-secondary">Capaian</th>
                <th class="text-bg-secondary">Nilai</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($data_standar_k9 as $standard)
                <tr>
                  <td style="text-align: center;">{{ $standard->indikator_kode }}</td>
                  <td>{{ $standard->elemen_nama }}</td>
                  <td>
                    @php
                      $lines = explode("\n", $standard->indikator_nama);
                    @endphp
                    @foreach ($lines as $line)
                      {{ $line }}<br>
                    @endforeach
                  </td>
                  <td>
                    <a href="#" data-bs-toggle="modal" data-bs-target="#infoModal{{ $standard->id }}" class="btn btn-warning btn-icon" rel="noopener noreferrer">
                      <i data-feather="info"></i>
                    </a>
                  </td>
                  <td>
                    <a href="#" data-bs-toggle="modal" data-bs-target="#targetModal{{ $standard->id }}" class="btn btn-primary btn-icon" rel="noopener noreferrer">
                      <i data-feather="info"></i>
                    </a>
                  </td>
                  <td>
                    <a href="#" data-bs-toggle="modal" data-bs-target="#capaianModal{{ $standard->id }}" class="btn btn-secondary btn-icon" rel="noopener noreferrer">
                      <i data-feather="info"></i>
                    </a>
                  </td>
                  <td>
                    <input type='text' readonly value="" name='nilai_mandiri' style='background-color: transparent; border: 0px solid; width: 100%; line-height: 100px; display:none;'>
                    <a data-toggle="modal" data-target='#editmodal"{{ $standard->id }}"' style="color:royalblue;">[Edit]</a>
                  </td>
                </tr>
                <div class="modal fade" id="infoModal{{ $standard->id }}" tabindex="-1" aria-labelledby="infoModal{{ $standard->id }}Label" aria-hidden="true">
                  <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title" id="calendarModal{{ $standard->id }}Label">Informasi Penilaian</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <div class="modal-body">
                      @php
                        $lines = explode("\n", $standard->indikator_info);
                      @endphp
                      @foreach ($lines as $line)
                        {{ $line }}<br>
                      @endforeach
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
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

<div class="row">
  <div class="col-md-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <p class="mb-3">
          <b>
            {{ $nama_data_standar_k10 }}
          </b>
        </p>
        <div class="table-responsive">
          <table id="dataTableExample10" class="display table table-striped table-hover">
            <thead>
              <tr>
                <th class="text-bg-secondary">Kode</th>
                <th class="text-bg-secondary">Elemen</th>
                <th class="text-bg-secondary">Indikator</th>
                <th class="text-bg-secondary">Informasi</th>
                <th class="text-bg-secondary">Target</th>
                <th class="text-bg-secondary">Capaian</th>
                <th class="text-bg-secondary">Nilai</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($data_standar_k10 as $standard)
                <tr>
                  <td style="text-align: center;">{{ $standard->indikator_kode }}</td>
                  <td>{{ $standard->elemen_nama }}</td>
                  <td>
                    @php
                      $lines = explode("\n", $standard->indikator_nama);
                    @endphp
                    @foreach ($lines as $line)
                      {{ $line }}<br>
                    @endforeach
                  </td>
                  <td>
                    <a href="#" data-bs-toggle="modal" data-bs-target="#infoModal{{ $standard->id }}" class="btn btn-warning btn-icon" rel="noopener noreferrer">
                      <i data-feather="info"></i>
                    </a>
                  </td>
                  <td>
                    <a href="#" data-bs-toggle="modal" data-bs-target="#targetModal{{ $standard->id }}" class="btn btn-primary btn-icon" rel="noopener noreferrer">
                      <i data-feather="info"></i>
                    </a>
                  </td>
                  <td>
                    <a href="#" data-bs-toggle="modal" data-bs-target="#capaianModal{{ $standard->id }}" class="btn btn-secondary btn-icon" rel="noopener noreferrer">
                      <i data-feather="info"></i>
                    </a>
                  </td>
                  <td>
                    <input type='text' readonly value="" name='nilai_mandiri' style='background-color: transparent; border: 0px solid; width: 100%; line-height: 100px; display:none;'>
                    <a data-toggle="modal" data-target='#editmodal"{{ $standard->id }}"' style="color:royalblue;">[Edit]</a>
                  </td>
                </tr>
                <div class="modal fade" id="infoModal{{ $standard->id }}" tabindex="-1" aria-labelledby="infoModal{{ $standard->id }}Label" aria-hidden="true">
                  <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title" id="calendarModal{{ $standard->id }}Label">Informasi Penilaian</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <div class="modal-body">
                      @php
                        $lines = explode("\n", $standard->indikator_info);
                      @endphp
                      @foreach ($lines as $line)
                        {{ $line }}<br>
                      @endforeach
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
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

<div class="row">
  <div class="col-md-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <p class="mb-3">
          <b>
            {{ $nama_data_standar_k11 }}
          </b>
        </p>
        <div class="table-responsive">
          <table id="dataTableExample11" class="display table table-striped table-hover">
            <thead>
              <tr>
                <th class="text-bg-secondary">Kode</th>
                <th class="text-bg-secondary">Elemen</th>
                <th class="text-bg-secondary">Indikator</th>
                <th class="text-bg-secondary">Informasi</th>
                <th class="text-bg-secondary">Target</th>
                <th class="text-bg-secondary">Capaian</th>
                <th class="text-bg-secondary">Nilai</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($data_standar_k11 as $standard)
                <tr>
                  <td style="text-align: center;">{{ $standard->indikator_kode }}</td>
                  <td>{{ $standard->elemen_nama }}</td>
                  <td>
                    @php
                      $lines = explode("\n", $standard->indikator_nama);
                    @endphp
                    @foreach ($lines as $line)
                      {{ $line }}<br>
                    @endforeach
                  </td>
                  <td>
                    <a href="#" data-bs-toggle="modal" data-bs-target="#infoModal{{ $standard->id }}" class="btn btn-warning btn-icon" rel="noopener noreferrer">
                      <i data-feather="info"></i>
                    </a>
                  </td>
                  <td>
                    <a href="#" data-bs-toggle="modal" data-bs-target="#targetModal{{ $standard->id }}" class="btn btn-primary btn-icon" rel="noopener noreferrer">
                      <i data-feather="info"></i>
                    </a>
                  </td>
                  <td>
                    <a href="#" data-bs-toggle="modal" data-bs-target="#capaianModal{{ $standard->id }}" class="btn btn-secondary btn-icon" rel="noopener noreferrer">
                      <i data-feather="info"></i>
                    </a>
                  </td>
                  <td>
                    <input type='text' readonly value="" name='nilai_mandiri' style='background-color: transparent; border: 0px solid; width: 100%; line-height: 100px; display:none;'>
                    <a data-toggle="modal" data-target='#editmodal"{{ $standard->id }}"' style="color:royalblue;">[Edit]</a>
                  </td>
                </tr>
                <div class="modal fade" id="infoModal{{ $standard->id }}" tabindex="-1" aria-labelledby="infoModal{{ $standard->id }}Label" aria-hidden="true">
                  <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title" id="calendarModal{{ $standard->id }}Label">Informasi Penilaian</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <div class="modal-body">
                      @php
                        $lines = explode("\n", $standard->indikator_info);
                      @endphp
                      @foreach ($lines as $line)
                        {{ $line }}<br>
                      @endforeach
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
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

<div class="row">
  <div class="col-md-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <p class="mb-3">
          <b>
            {{ $nama_data_standar_k12 }}
          </b>
        </p>
        <div class="table-responsive">
          <table id="dataTableExample12" class="display table table-striped table-hover">
            <thead>
              <tr>
                <th class="text-bg-secondary">Kode</th>
                <th class="text-bg-secondary">Elemen</th>
                <th class="text-bg-secondary">Indikator</th>
                <th class="text-bg-secondary">Informasi</th>
                <th class="text-bg-secondary">Target</th>
                <th class="text-bg-secondary">Capaian</th>
                <th class="text-bg-secondary">Nilai</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($data_standar_k12 as $standard)
                <tr>
                  <td style="text-align: center;">{{ $standard->indikator_kode }}</td>
                  <td>{{ $standard->elemen_nama }}</td>
                  <td>
                    @php
                      $lines = explode("\n", $standard->indikator_nama);
                    @endphp
                    @foreach ($lines as $line)
                      {{ $line }}<br>
                    @endforeach
                  </td>
                  <td>
                    <a href="#" data-bs-toggle="modal" data-bs-target="#infoModal{{ $standard->id }}" class="btn btn-warning btn-icon" rel="noopener noreferrer">
                      <i data-feather="info"></i>
                    </a>
                  </td>
                  <td>
                    <a href="#" data-bs-toggle="modal" data-bs-target="#targetModal{{ $standard->id }}" class="btn btn-primary btn-icon" rel="noopener noreferrer">
                      <i data-feather="info"></i>
                    </a>
                  </td>
                  <td>
                    <a href="#" data-bs-toggle="modal" data-bs-target="#capaianModal{{ $standard->id }}" class="btn btn-secondary btn-icon" rel="noopener noreferrer">
                      <i data-feather="info"></i>
                    </a>
                  </td>
                  <td>
                    <input type='text' readonly value="" name='nilai_mandiri' style='background-color: transparent; border: 0px solid; width: 100%; line-height: 100px; display:none;'>
                    <a data-toggle="modal" data-target='#editmodal"{{ $standard->id }}"' style="color:royalblue;">[Edit]</a>
                  </td>
                </tr>
                <div class="modal fade" id="infoModal{{ $standard->id }}" tabindex="-1" aria-labelledby="infoModal{{ $standard->id }}Label" aria-hidden="true">
                  <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title" id="calendarModal{{ $standard->id }}Label">Informasi Penilaian</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <div class="modal-body">
                      @php
                        $lines = explode("\n", $standard->indikator_info);
                      @endphp
                      @foreach ($lines as $line)
                        {{ $line }}<br>
                      @endforeach
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
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

<nav class="settings-sidebar">
  <div class="sidebar-body">
    <a href="#" class="settings-sidebar-toggler">
      <i data-feather="settings"></i>
    </a>
    <h6 class="text-muted mb-2">Daftar Kriteria:</h6>
    <div class="mb-3 pb-3 border-bottom">
      <ul class="breadcrumb breadcrumb-dot">
        <li class="breadcrumb-item"><a href="#"></a></li>
        <li class="breadcrumb-item"><a href="#dataTableExample1">Kondisi Eksternal</a></li>
        <li class="breadcrumb-item"><a href="#dataTableExample2">Profil UPPS</a></li>
        <li class="breadcrumb-item"><a href="#dataTableExample3">Kriteria 1</a></li>
        <li class="breadcrumb-item"><a href="#dataTableExample4">Kriteria 2</a></li>
        <li class="breadcrumb-item"><a href="#dataTableExample5">Kriteria 3</a></li>
        <li class="breadcrumb-item"><a href="#dataTableExample6">Kriteria 4</a></li>
        <li class="breadcrumb-item"><a href="#dataTableExample7">Kriteria 5</a></li>
        <li class="breadcrumb-item"><a href="#dataTableExample8">Kriteria 6</a></li>
        <li class="breadcrumb-item"><a href="#dataTableExample9">Kriteria 7</a></li>
        <li class="breadcrumb-item"><a href="#dataTableExample10">Kriteria 8</a></li>
        <li class="breadcrumb-item"><a href="#dataTableExample11">Kriteria 9</a></li>
        <li class="breadcrumb-item"><a href="#dataTableExample12">Analisis dan Penetapan Program Pengembangan</a></li>
      </ul>
    </div>
  </div>
</nav>
@endsection

@push('plugin-scripts')
  <script src="{{ asset('assets/plugins/datatables-net/jquery.dataTables.js') }}"></script>
  <script src="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.js') }}"></script>
@endpush

@push('custom-scripts')
  <script src="{{ asset('assets/js/data-table.js') }}"></script>
@endpush
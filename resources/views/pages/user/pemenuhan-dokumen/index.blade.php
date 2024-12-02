@extends('layout.master-user')

@push('plugin-styles')
  <link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
@endpush

@section('content')
<div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
  <div>
    <h4 class="mb-3 mb-md-0">Pemenuhan Dokumen</h4>
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
                <th class="text-bg-secondary">Kebutuhan Dokumen</th>
                <th class="text-bg-secondary">Kelola Kebutuhan</th>
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
                  <td>1</td>
                  <td>
                    <button type="button" class="btn btn-primary btn-icon">
                      <i data-feather="plus-square"></i>
                    </button>
                  </td>
                </tr>
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
                <th class="text-bg-secondary">Kebutuhan Dokumen</th>
                <th class="text-bg-secondary">Kelola Kebutuhan</th>
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
                  <td>1</td>
                  <td>
                    <button type="button" class="btn btn-primary btn-icon">
                      <i data-feather="plus-square"></i>
                    </button>
                  </td>
                </tr>
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
                <th class="text-bg-secondary">Kebutuhan Dokumen</th>
                <th class="text-bg-secondary">Kelola Kebutuhan</th>
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
                  <td>1</td>
                  <td>
                    <button type="button" class="btn btn-primary btn-icon">
                      <i data-feather="plus-square"></i>
                    </button>
                  </td>
                </tr>
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
                <th class="text-bg-secondary">Kebutuhan Dokumen</th>
                <th class="text-bg-secondary">Kelola Kebutuhan</th>
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
                  <td>1</td>
                  <td>
                    <button type="button" class="btn btn-primary btn-icon">
                      <i data-feather="plus-square"></i>
                    </button>
                  </td>
                </tr>
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
                <th class="text-bg-secondary">Kebutuhan Dokumen</th>
                <th class="text-bg-secondary">Kelola Kebutuhan</th>
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
                  <td>1</td>
                  <td>
                    <button type="button" class="btn btn-primary btn-icon">
                      <i data-feather="plus-square"></i>
                    </button>
                  </td>
                </tr>
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
                <th class="text-bg-secondary">Kebutuhan Dokumen</th>
                <th class="text-bg-secondary">Kelola Kebutuhan</th>
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
                  <td>1</td>
                  <td>
                    <button type="button" class="btn btn-primary btn-icon">
                      <i data-feather="plus-square"></i>
                    </button>
                  </td>
                </tr>
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
                <th class="text-bg-secondary">Kebutuhan Dokumen</th>
                <th class="text-bg-secondary">Kelola Kebutuhan</th>
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
                  <td>1</td>
                  <td>
                    <button type="button" class="btn btn-primary btn-icon">
                      <i data-feather="plus-square"></i>
                    </button>
                  </td>
                </tr>
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
                <th class="text-bg-secondary">Kebutuhan Dokumen</th>
                <th class="text-bg-secondary">Kelola Kebutuhan</th>
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
                  <td>1</td>
                  <td>
                    <button type="button" class="btn btn-primary btn-icon">
                      <i data-feather="plus-square"></i>
                    </button>
                  </td>
                </tr>
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
                <th class="text-bg-secondary">Kebutuhan Dokumen</th>
                <th class="text-bg-secondary">Kelola Kebutuhan</th>
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
                  <td>1</td>
                  <td>
                    <button type="button" class="btn btn-primary btn-icon">
                      <i data-feather="plus-square"></i>
                    </button>
                  </td>
                </tr>
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
                <th class="text-bg-secondary">Kebutuhan Dokumen</th>
                <th class="text-bg-secondary">Kelola Kebutuhan</th>
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
                  <td>1</td>
                  <td>
                    <button type="button" class="btn btn-primary btn-icon">
                      <i data-feather="plus-square"></i>
                    </button>
                  </td>
                </tr>
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
                <th class="text-bg-secondary">Kebutuhan Dokumen</th>
                <th class="text-bg-secondary">Kelola Kebutuhan</th>
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
                  <td>1</td>
                  <td>
                    <button type="button" class="btn btn-primary btn-icon">
                      <i data-feather="plus-square"></i>
                    </button>
                  </td>
                </tr>
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
                <th class="text-bg-secondary">Kebutuhan Dokumen</th>
                <th class="text-bg-secondary">Kelola Kebutuhan</th>
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
                  <td>1</td>
                  <td>
                    <button type="button" class="btn btn-primary btn-icon">
                      <i data-feather="plus-square"></i>
                    </button>
                  </td>
                </tr>
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
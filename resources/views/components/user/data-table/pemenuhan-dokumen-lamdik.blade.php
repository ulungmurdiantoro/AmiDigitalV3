{{-- resources/views/components/user/pemenuhan-dokumen/data-table.blade.php --}}
<div class="table-responsive">
  <table id="{{ $id }}" class="col-md-12 table-striped table-hover">
    <thead class="text-bg-secondary">
      <tr>
        <th class="col-md-1 text-center">Kode</th>
        <th class="col-md-2">Elemen</th>
        <th class="col-md-5">Indikator</th>
        <th class="col-md-1">Kategori</th>
        <th class="col-md-1 text-center">Kebutuhan Dokumen</th>
        <th class="col-md-1 text-center">Capaian Dokumen</th>
        <th class="col-md-1 text-center">Kelola Kebutuhan</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($standards as $standard)
        <tr>
          <td class="text-center">{{ $standard->indikator_id }}</td>
          <td>{{ $standard->elemen_nama }}</td>                 
          <td>{!! nl2br(e($standard->indikator_nama)) !!}</td>
          <td>
            {{ $standard->kategori }}<br>
          </td>
          <td class="text-center">
            {{ $standard->$standarTargetsRelations->count() }}<br>
          </td>
          <td class="text-center">
            {{ $standard->$standarCapaiansRelations->count() }}<br>
          </td>
          <td>
            <a href="{{ route('user.pemenuhan-dokumen.input-capaian', $standard->indikator_id) }}" class="btn btn-primary btn-icon" title="Manage Target">
              <i data-feather="plus-square"></i>
            </a>            
          </td>
        </tr>
      @endforeach
    </tbody>
  </table>
</div>

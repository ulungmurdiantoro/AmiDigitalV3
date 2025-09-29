
<div class="table-responsive">
  @props(['id', 'standards', 'showImportData', 'importTitle'])
  <table id="{{ $id }}" class="col-md-12 table-striped table-hover" style="table-layout: fixed; width: 100%;">
    <thead class="text-bg-secondary">
      <tr class="text-center text-white">
        <th class="text-center" style="width: 5%; padding: 7px 0;">No</th>
        <th class="text-center" style="width: 15%; padding: 7px 0;">Elemen</th>
        <th class="text-center" style="width: 50%; padding: 7px 0;">Indikator</th>
        <th class="text-center" style="width: 10%; padding: 7px 0;">Kebutuhan Dokumen</th>
        <th class="text-center" style="width: 10%; padding: 7px 0;">Capaian Dokumen</th>
        <th class="text-center" style="width: 10%; padding: 7px 0;">Kelola Kebutuhan</th>
      </tr>
    </thead>
    <tbody>
      @php $nomor = 1; @endphp
      @foreach ($standards as $standard)
        @foreach ($standard->indicators as $indikator)
          @php $isEmptyTarget = $indikator->dokumen_capaians->count() === 0; @endphp
          <tr @if($isEmptyTarget) style="background-color: rgba(140, 18, 61, .85); color: white;" @endif>
            <td class="text-center" style="padding: 5px 0;">{{ $nomor++ }}</td>
            <td style="padding: 5px 0;">{{ $standard->nama }}</td>
            <td class="indikator-cell" style="padding: 5px 0;">{!! nl2br(e($indikator->nama_indikator)) !!}</td>
            <td class="text-center" style="padding: 5px 0;">
              {{ $indikator->dokumen_targets->count() ?? 0 }}
            </td>
            <td class="text-center" style="padding: 5px 0;">
              {{ $indikator->dokumen_capaians->count() ?? 0 }}
            </td>
            <td class="text-center" style="padding: 5px 0;">
              <a href="{{ route('user.pemenuhan-dokumen.input-capaian', $indikator->id) }}" class="btn btn-primary btn-icon" title="Manage Target">
                <i data-feather="plus-square"></i>
              </a>
            </td>
          </tr>
        @endforeach
      @endforeach
    </tbody>
  </table>
</div>
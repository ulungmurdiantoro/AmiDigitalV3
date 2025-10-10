@props(['id','standards','editRouteName' => 'admin.kriteria-dokumen.kelola-bukti.edit','importTitle' => null])

@php
  use Illuminate\Support\Facades\Storage;

  // Kumpulkan bukti dari beragam level:
  $rows = collect();

  // 1) level standards
  if ($standards->relationLoaded('buktiStandar') && $standards->buktiStandar) {
      $rows = $rows->merge($standards->buktiStandar);
  }

  // 2) level elements
  if ($standards->relationLoaded('elements') && $standards->elements) {
      $rows = $rows->merge(
          $standards->elements->flatMap(fn($el) => $el->buktiStandar ?? collect())
      );

      // 3) level indicators
      $rows = $rows->merge(
          $standards->elements
              ->flatMap(fn($el) => $el->indicators ?? collect())
              ->flatMap(function ($ind) {
                  // Ganti nama relasi sesuai modelmu: 'buktiStandar' atau 'dokumen_targets'
                  return $ind->buktiStandar
                      ?? ($ind->dokumen_targets ?? collect());
              })
      );
  }

  // Hilangkan duplikat berdasarkan id (jaga-jaga)
  $rows = $rows->filter()->unique('id')->values();
@endphp

<div class="table-responsive" id="bukti-table-wrap-{{ $id }}">
  <table class="table table-striped table-hover w-100" style="table-layout: fixed;">
    <thead class="text-bg-secondary">
      <tr class="text-white text-center">
        <th style="width:6%;padding:7px 0;">No</th>
        <th style="width:24%;padding:7px 0;">Nama Bukti</th>
        <th style="width:30%;padding:7px 0;">Deskripsi</th>
        <th style="width:10%;padding:7px 0;">Tipe</th>
        <th style="width:8%;padding:7px 0;">Wajib?</th>
        <th style="width:12%;padding:7px 0;">Catatan</th>
        <th style="width:10%;padding:7px 0;">Lampiran</th>
        <th style="width:10%;padding:7px 0;">Aksi</th>
      </tr>
    </thead>
    <tbody>
      @php $no = 1; @endphp
      @forelse ($rows as $row)
        @php
          $lampiran = $row->link ?? (!empty($row->file_path) ? Storage::url($row->file_path) : null);
        @endphp
        <tr>
          <td class="text-center" style="padding:5px 0;">{{ $no++ }}</td>
          <td style="padding:5px 0;">{{ $row->nama ?? '—' }}</td>
          <td style="padding:5px 0;">{{ $row->deskripsi ?? '—' }}</td>
          <td class="text-center" style="padding:5px 0;">{{ $row->tipe ?? '—' }}</td>
          <td class="text-center" style="padding:5px 0;">
            {{ isset($row->required) ? ($row->required ? 'Ya' : 'Tidak') : '—' }}
          </td>
          <td style="padding:5px 0;">{{ $row->catatan ?? '—' }}</td>
          <td class="text-center" style="padding:5px 0;">
            @if ($lampiran)
              <a href="{{ $lampiran }}" target="_blank" rel="noopener">Buka</a>
            @else
              —
            @endif
          </td>
          <td class="text-center" style="padding:5px 0;">
            <a href="{{ route($editRouteName, $row->id) }}" class="btn btn-outline-primary btn-sm">
              <i data-feather="upload"></i> <span class="d-none d-sm-inline">Upload/Edit</span>
            </a>
          </td>
        </tr>
      @empty
        <tr>
          <td colspan="8" class="text-center"><em>Belum ada bukti.</em></td>
        </tr>
      @endforelse
    </tbody>
  </table>
</div>

@extends('layout.master')

@section('content')
<nav class="page-breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="#">Pengaturan</a></li>
    <li class="breadcrumb-item active" aria-current="page">Konfigurasi Neo Feeder</li>
  </ol>
</nav>

<div class="row">
  <div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <h4 class="card-title">Konfigurasi Neo Feeder</h4>

        @if(session('success'))
          <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
        @endif

        <form action="{{ route('admin.feeder-config.update') }}" method="POST" id="feederForm">
          @csrf
          @method('PUT')

          <div class="row">
            <div class="col-sm-8">
              <div class="mb-3">
                <label for="feeder_url" class="form-label">URL Neo Feeder</label>
                <input
                  type="url"
                  id="feeder_url"
                  name="feeder_url"
                  class="form-control @error('feeder_url') is-invalid @enderror"
                  value="{{ old('feeder_url', $config->feeder_url ?? '') }}"
                  placeholder="https://feeder.pddikti.kemdikbud.go.id"
                  required>
                @error('feeder_url')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div>
            <div class="col-sm-4">
              <div class="mb-3">
                <label for="feeder_kode_pt" class="form-label">Kode Perguruan Tinggi</label>
                <input
                  type="text"
                  id="feeder_kode_pt"
                  name="feeder_kode_pt"
                  class="form-control @error('feeder_kode_pt') is-invalid @enderror"
                  value="{{ old('feeder_kode_pt', $config->feeder_kode_pt ?? '') }}"
                  placeholder="Contoh: 001234"
                  maxlength="20"
                  required>
                @error('feeder_kode_pt')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-sm-6">
              <div class="mb-3">
                <label for="feeder_username" class="form-label">Username</label>
                <input
                  type="text"
                  id="feeder_username"
                  name="feeder_username"
                  class="form-control @error('feeder_username') is-invalid @enderror"
                  value="{{ old('feeder_username', $config->feeder_username ?? '') }}"
                  placeholder="Username Neo Feeder"
                  autocomplete="off"
                  required>
                @error('feeder_username')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div>
            <div class="col-sm-6">
              <div class="mb-3">
                <label for="feeder_password" class="form-label">Password</label>
                @if($config->exists)
                  <div class="form-text mb-1">Password sudah tersimpan. Kosongkan jika tidak ingin mengubah.</div>
                @endif
                <div class="input-group">
                  <input
                    type="password"
                    id="feeder_password"
                    name="feeder_password"
                    class="form-control @error('feeder_password') is-invalid @enderror"
                    placeholder="{{ $config->exists ? '••••••••' : 'Masukkan password Neo Feeder' }}"
                    autocomplete="new-password">
                  <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                    <i data-feather="eye" id="eyeIcon"></i>
                  </button>
                  @error('feeder_password')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>
              </div>
            </div>
          </div>

          <input class="btn btn-primary me-2" type="submit" value="Simpan Konfigurasi">
          @if($config->exists)
            <button type="button" class="btn btn-outline-primary btn-icon-text" id="btnTest">
              <i class="btn-icon-prepend" data-feather="wifi"></i>
              Uji Koneksi
            </button>
          @endif

        </form>

        @if($config->exists)
          <hr class="mt-4">
          <p class="mb-1 text-muted">
            <strong>Terakhir diperbarui:</strong>
            {{ $config->updated_at ? $config->updated_at->translatedFormat('d F Y, H:i') : '-' }}
          </p>
          <p class="mb-0 text-muted">
            <strong>Status password:</strong>
            <span class="badge bg-success">Terenkripsi (AES-256)</span>
          </p>
        @endif

      </div>
    </div>
  </div>
</div>
@endsection

@push('custom-scripts')
<script>
  document.getElementById('togglePassword').addEventListener('click', function () {
    const input = document.getElementById('feeder_password');
    const icon  = document.getElementById('eyeIcon');
    if (input.type === 'password') {
      input.type = 'text';
      icon.setAttribute('data-feather', 'eye-off');
    } else {
      input.type = 'password';
      icon.setAttribute('data-feather', 'eye');
    }
    feather.replace();
  });

  const btnTest = document.getElementById('btnTest');
  if (btnTest) {
    btnTest.addEventListener('click', function () {
      btnTest.disabled = true;
      btnTest.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Menguji...';
      fetch('{{ route("admin.feeder-config.test") }}', {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
      })
      .then(r => r.json())
      .then(data => {
        alert(data.success ? '✓ Koneksi berhasil: ' + data.message : '✗ Koneksi gagal: ' + data.message);
      })
      .catch(() => alert('✗ Tidak dapat terhubung ke server.'))
      .finally(() => {
        btnTest.disabled = false;
        btnTest.innerHTML = '<i class="btn-icon-prepend" data-feather="wifi"></i> Uji Koneksi';
        feather.replace();
      });
    });
  }
</script>
@endpush

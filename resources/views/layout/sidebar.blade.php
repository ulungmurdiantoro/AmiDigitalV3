<nav class="sidebar">
  <div class="sidebar-header">
    <a href="#" class="sidebar-brand">
      <img src="{{ url('assets/images/logo/AMI-Digital-logo.png') }}" style="width: 100%; height:42px" alt="navbar brand" class="navbar-brand">
    </a>
    <div class="sidebar-toggler not-active">
      <span></span>
      <span></span>
      <span></span>
    </div>
  </div>
  <div class="sidebar-body">
    <ul class="nav">
      <li class="nav-item nav-category">Main</li>
      <li class="nav-item {{ active_class(['admin.dashboard.*']) }}">
        <a href="{{ route('admin.dashboard.index') }}" class="nav-link">
          <i class="link-icon" data-feather="box"></i>
          <span class="link-title">Dashboard</span>
        </a>
      </li>
      <li class="nav-item nav-category">master data</li>
      <li class="nav-item {{ active_class(['admin.program-studi.*', 'admin.pengguna-prodi.*', 'admin.pengguna-auditor.*', 'admin.penjadwalan-ami.*']) }}">
        <a class="nav-link" data-bs-toggle="collapse" href="#email" role="button" aria-expanded="{{ is_active_route(['admin.program-studi.*', 'admin.pengguna-prodi.*', 'admin.pengguna-auditor.*', 'admin.penjadwalan-ami.*']) }}" aria-controls="email">
          <i class="link-icon" data-feather="users"></i>
          <span class="link-title">Pengguna Sistem</span>
          <i class="link-arrow" data-feather="chevron-down"></i>
        </a>
        <div class="collapse {{ show_class(['admin.program-studi.*', 'admin.pengguna-prodi.*', 'admin.pengguna-auditor.*', 'admin.penjadwalan-ami.*']) }}" id="email">
          <ul class="nav sub-menu">
            <li class="nav-item">
              <a href="{{ route('admin.program-studi.index') }}" class="nav-link {{ active_class(['admin.program-studi.*']) }}">Program Studi</a>
            </li>
            <li class="nav-item">
              <a href="{{ route('admin.pengguna-prodi.index') }}" class="nav-link {{ active_class(['admin.pengguna-prodi.*']) }}">Pengguna Prodi</a>
            </li>
            <li class="nav-item">
              <a href="{{ route('admin.pengguna-auditor.index') }}" class="nav-link {{ active_class(['admin.pengguna-auditor.*']) }}">Pengguna Auditor</a>
            </li>
            <li class="nav-item">
              <a href="{{ route('admin.penjadwalan-ami.index') }}" class="nav-link {{ active_class(['admin.penjadwalan-ami.*']) }}">Penjadwalan AMI</a>
            </li>
          </ul>
        </div>
      </li>
      <li class="nav-item {{ active_class(['admin.dokumen-spmi-ami.*']) }}">
        <a href="{{ route('admin.dokumen-spmi-ami.index') }}" class="nav-link">
          <i class="link-icon" data-feather="database"></i>
          <span class="link-title">Dokumen SPMI & AMI</span>
        </a>
      </li>
      <li class="nav-item {{ active_class(['admin.kriteria-dokumen.*']) }}">
        <a href="{{ route('admin.kriteria-dokumen.index') }}" class="nav-link">
          <i class="link-icon" data-feather="layers"></i>
          <span class="link-title">Kriteria Dokumen</span>
        </a>
      </li>
      <li class="nav-item nav-category">Program Studi</li>
      <li class="nav-item {{ active_class(['admin.pengumuman.*']) }}">
        <a href="{{ route('admin.pengumuman.index') }}" class="nav-link">
          <i class="link-icon" data-feather="send"></i>
          <span class="link-title">Pengumuman</span>
        </a>
      </li>
      <li class="nav-item {{ active_class(['admin.aktivitas-prodi.*']) }}">
        <a href="{{ route('admin.aktivitas-prodi.index') }}" class="nav-link">
          <i class="link-icon" data-feather="monitor"></i>
          <span class="link-title">Aktivitas Prodi</span>
        </a>
      </li>
      <li class="nav-item {{ active_class(['admin.nilai-evaluasi-diri.*']) }}">
        <a href="{{ route('admin.nilai-evaluasi-diri.index') }}" class="nav-link">
          <i class="link-icon" data-feather="file-text"></i>
          <span class="link-title">Nilai Evaluasi Diri</span>
        </a>
      </li>
      <li class="nav-item {{ active_class(['admin.statistik-elemen.*', 'admin.statistik-total.*']) }}">
        <a class="nav-link" data-bs-toggle="collapse" href="#error" role="button" aria-expanded="{{ is_active_route(['admin.statistik-elemen.*', 'admin.statistik-total.*']) }}" aria-controls="error">
          <i class="link-icon" data-feather="bar-chart-2"></i>
          <span class="link-title">Statistik AMI</span>
          <i class="link-arrow" data-feather="chevron-down"></i>
        </a>
        <div class="collapse {{ show_class(['admin.statistik-elemen.*', 'admin.statistik-total.*']) }}" id="error">
          <ul class="nav sub-menu">
            <li class="nav-item">
              <a href="{{ route('admin.statistik-elemen.index') }}" class="nav-link {{ active_class(['admin.statistik-elemen']) }}">Statistik Elemen</a>
            </li>
            <li class="nav-item">
              <a href="{{ route('admin.statistik-total.index') }}" class="nav-link {{ active_class(['admin.statistik-total']) }}">Statistik Total</a>
            </li>
          </ul>
        </div>
      </li>
      <li class="nav-item {{ active_class(['admin.forcasting.*']) }}">
        <a href="{{ route('admin.forcasting.index') }}" class="nav-link">
          <i class="link-icon" data-feather="trending-up"></i>
          <span class="link-title">Forcasting</span>
        </a>
      </li>
      <li class="nav-item nav-category">bantuan</li>
      <li class="nav-item {{ active_class(['admin.bantuan.*']) }}">
        <a href="{{ route('admin.bantuan.index') }}" class="nav-link">
          <i class="link-icon" data-feather="help-circle"></i>
          <span class="link-title">Bantuan</span>
        </a>
      </li>
    </ul>
  </div>
</nav>

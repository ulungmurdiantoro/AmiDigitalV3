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
      <li class="nav-item {{ active_class(['user.dashboard.*']) }}">
        <a href="{{ route('user.dashboard.index') }}" class="nav-link">
          <i class="link-icon" data-feather="box"></i>
          <span class="link-title">Dashboard</span>
        </a>
      </li>
      <li class="nav-item nav-category">Implementasi</li>
      <li class="nav-item {{ active_class(['user.dokumen-spmi-ami.*']) }}">
        <a href="{{ route('user.dokumen-spmi-ami.index') }}" class="nav-link">
          <i class="link-icon" data-feather="database"></i>
          <span class="link-title">Dokumen SPMI & AMI</span>
        </a>
      </li>
      <li class="nav-item {{ active_class(['user.pemenuhan-dokumen.*']) }}">
        <a href="{{ route('user.pemenuhan-dokumen.index') }}" class="nav-link">
          <i class="link-icon" data-feather="layers"></i>
          <span class="link-title">Pemenuhan Dokumen</span>
        </a>
      </li>
      <li class="nav-item {{ active_class(['user.dokumen-aktif.*', 'user.dokumen-kadaluarsa.*']) }}">
        <a class="nav-link" data-bs-toggle="collapse" href="#email" role="button" aria-expanded="{{ is_active_route(['user.dokumen-aktif.*', 'user.dokumen-kadaluarsa.*']) }}" aria-controls="email">
          <i class="link-icon" data-feather="users"></i>
            <span class="link-title">Rekap Dokumen</span>
          <i class="link-arrow" data-feather="chevron-down"></i>
        </a>
        <div class="collapse {{ show_class(['user.dokumen-aktif.*', 'user.dokumen-kadaluarsa.*']) }}" id="email">
          <ul class="nav sub-menu">
            <li class="nav-item">
              <a href="{{ route('user.dokumen-aktif.index') }}" class="nav-link {{ active_class(['user.dokumen-aktif.*']) }}">Dokumen Aktif</a>
            </li>
            <li class="nav-item">
              <a href="{{ route('user.dokumen-kadaluarsa.index') }}" class="nav-link {{ active_class(['user.dokumen-kadaluarsa.*']) }}">Dokumen Kadaluarsa</a>
            </li>
          </ul>
        </div>
      </li>
      <li class="nav-item {{ active_class(['user.pengajuan-ami.*']) }}">
        <a href="{{ route('user.pengajuan-ami.index') }}" class="nav-link">
          <i class="link-icon" data-feather="package"></i>
          <span class="link-title">Pengajuan AMI</span>
        </a>
      </li>
      <li class="nav-item {{ active_class(['user.koreksi-ami.*']) }}">
        <a href="{{ route('user.koreksi-ami.index') }}" class="nav-link">
          <i class="link-icon" data-feather="pen-tool"></i>
          <span class="link-title">Koreksi AMI</span>
        </a>
      </li>
      <li class="nav-item nav-category">Hasil AMI</li>
      <li class="nav-item {{ active_class(['user.nilai-evaluasi-diri.*']) }}">
        <a href="{{ route('user.nilai-evaluasi-diri.index') }}" class="nav-link">
          <i class="link-icon" data-feather="file-text"></i>
          <span class="link-title">Nilai Evaluasi Diri</span>
        </a>
      </li>
      <li class="nav-item {{ active_class(['user.statistik-elemen.*', 'user.statistik-total.*']) }}">
        <a class="nav-link" data-bs-toggle="collapse" href="#error" role="button" aria-expanded="{{ is_active_route(['user.statistik-elemen.*', 'user.statistik-total.*']) }}" aria-controls="error">
          <i class="link-icon" data-feather="bar-chart-2"></i>
          <span class="link-title">Statistik AMI</span>
          <i class="link-arrow" data-feather="chevron-down"></i>
        </a>
        <div class="collapse {{ show_class(['user.statistik-elemen.*', 'user.statistik-total.*']) }}" id="error">
          <ul class="nav sub-menu">
            <li class="nav-item">
              <a href="{{ route('user.statistik-elemen.index') }}" class="nav-link {{ active_class(['user.statistik-elemen']) }}">Statistik Elemen</a>
            </li>
            <li class="nav-item">
              <a href="{{ route('user.statistik-total.index') }}" class="nav-link {{ active_class(['user.statistik-total']) }}">Statistik Total</a>
            </li>
          </ul>
        </div>
      </li>
      <li class="nav-item {{ active_class(['user.forcasting.*']) }}">
        <a href="{{ route('user.forcasting.index') }}" class="nav-link">
          <i class="link-icon" data-feather="trending-up"></i>
          <span class="link-title">Forcasting</span>
        </a>
      </li>
      <li class="nav-item nav-category">bantuan</li>
      <li class="nav-item {{ active_class(['user.bantuan.*']) }}">
        <a href="{{ route('user.bantuan.index') }}" class="nav-link">
          <i class="link-icon" data-feather="help-circle"></i>
          <span class="link-title">Bantuan</span>
        </a>
      </li>
    </ul>
  </div>
</nav>

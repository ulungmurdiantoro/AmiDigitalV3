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
      <li class="nav-item {{ active_class(['auditor.dashboard.*']) }}">
        <a href="{{ route('auditor.dashboard.index') }}" class="nav-link">
          <i class="link-icon" data-feather="box"></i>
          <span class="link-title">Dashboard</span>
        </a>
      </li>
      <li class="nav-item nav-category">Audit Internal</li>
      <li class="nav-item {{ active_class(['auditor.dokumen-spmi-ami.*']) }}">
        <a href="{{ route('auditor.dokumen-spmi-ami.index') }}" class="nav-link">
          <i class="link-icon" data-feather="database"></i>
          <span class="link-title">Dokumen SPMI & AMI</span>
        </a>
      </li>
      <li class="nav-item {{ active_class(['auditor.konfirmasi-pengajuan.*']) }}">
        <a href="{{ route('auditor.konfirmasi-pengajuan.index') }}" class="nav-link">
          <i class="link-icon" data-feather="check-circle"></i>
          <span class="link-title">Konfirmasi Pengajuan</span>
        </a>
      </li>
      <li class="nav-item {{ active_class(['auditor.evaluasi-ami.*']) }}">
        <a href="{{ route('auditor.evaluasi-ami.index') }}" class="nav-link">
          <i class="link-icon" data-feather="package"></i>
          <span class="link-title">Evaluasi AMI</span>
        </a>
      </li>
      <li class="nav-item {{ active_class(['auditor.koreksi-ami.*']) }}">
        <a href="{{ route('auditor.koreksi-ami.index') }}" class="nav-link">
          <i class="link-icon" data-feather="pen-tool"></i>
          <span class="link-title">Koreksi AMI</span>
        </a>
      </li>
      <li class="nav-item nav-category">Hasil AMI</li>
      <li class="nav-item {{ active_class(['auditor.nilai-evaluasi-diri.*']) }}">
        <a href="{{ route('auditor.nilai-evaluasi-diri.index') }}" class="nav-link">
          <i class="link-icon" data-feather="file-text"></i>
          <span class="link-title">Nilai Evaluasi Diri</span>
        </a>
      </li>
      <li class="nav-item {{ active_class(['auditor.statistik-elemen.*', 'auditor.statistik-total.*']) }}">
        <a class="nav-link" data-bs-toggle="collapse" href="#error" role="button" aria-expanded="{{ is_active_route(['auditor.statistik-elemen.*', 'auditor.statistik-total.*']) }}" aria-controls="error">
          <i class="link-icon" data-feather="bar-chart-2"></i>
          <span class="link-title">Statistik AMI</span>
          <i class="link-arrow" data-feather="chevron-down"></i>
        </a>
        <div class="collapse {{ show_class(['auditor.statistik-elemen.*', 'auditor.statistik-total.*']) }}" id="error">
          <ul class="nav sub-menu">
            <li class="nav-item">
              <a href="{{ route('auditor.statistik-elemen.index') }}" class="nav-link {{ active_class(['auditor.statistik-elemen']) }}">Statistik Elemen</a>
            </li>
            <li class="nav-item">
              <a href="{{ route('auditor.statistik-total.index') }}" class="nav-link {{ active_class(['auditor.statistik-total']) }}">Statistik Total</a>
            </li>
          </ul>
        </div>
      </li>
      <li class="nav-item {{ active_class(['auditor.forcasting.*']) }}">
        <a href="{{ route('auditor.forcasting.index') }}" class="nav-link">
          <i class="link-icon" data-feather="trending-up"></i>
          <span class="link-title">Forcasting</span>
        </a>
      </li>
      <li class="nav-item nav-category">bantuan</li>
      <li class="nav-item {{ active_class(['auditor.bantuan.*']) }}">
        <a href="{{ route('auditor.bantuan.index') }}" class="nav-link">
          <i class="link-icon" data-feather="help-circle"></i>
          <span class="link-title">Bantuan</span>
        </a>
      </li>
    </ul>
  </div>
</nav>

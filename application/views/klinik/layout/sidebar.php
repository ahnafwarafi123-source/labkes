<style>
  /* Adjust sidebar layout to be fixed and scrollable independently */
  #sidebar {
    position: fixed !important;
    top: 0;
    left: 0;
    height: 100vh !important;
    z-index: 1040;
    overflow-y: hidden !important;
  }

  /* Adjust main content wrapper positioning to prevent overlapping */
  #content-wrapper {
    margin-left: 260px !important;
    width: calc(100% - 260px) !important;
    transition: margin-left 0.3s ease, width 0.3s ease !important;
  }

  /* Handle style changes when sidebar is collapsed in desktop mode */
  #sidebar.collapsed~#content-wrapper {
    margin-left: 0 !important;
    width: 100% !important;
  }

  /* Responsive styling for mobile devices */
  @media (max-width: 768px) {
    #sidebar {
      margin-left: -260px !important;
      height: 100vh !important;
    }

    #sidebar.active {
      margin-left: 0 !important;
    }

    #content-wrapper {
      margin-left: 0 !important;
      width: 100% !important;
    }
  }

  /* Clean, minimalist scrollbar styling for sidebar menu */
  #sidebar .overflow-auto::-webkit-scrollbar {
    width: 5px;
  }

  #sidebar .overflow-auto::-webkit-scrollbar-track {
    background: transparent;
  }

  #sidebar .overflow-auto::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 4px;
  }

  #sidebar .overflow-auto::-webkit-scrollbar-thumb:hover {
    background: #a0aec0;
  }
</style>

<?php
$seg1 = $this->uri->segment(1);
$seg2 = $this->uri->segment(2);
$seg3 = $this->uri->segment(3);

// Identifikasi user, modul, dan role
$user_session = current_user();
$current_mod  = $user_session['module'] ?? '';
$current_role = strtolower(trim((string)($user_session['role'] ?? '')));

// Flag Role Modul Klinik
$is_klinik_admin       = ($current_mod === 'klinik' && $current_role === 'admin');
$is_klinik_pendaftaran = ($current_mod === 'klinik' && $current_role === 'petugas pendaftaran');
$is_klinik_sampel      = ($current_mod === 'klinik' && $current_role === 'pemeriksa sampel');
$is_klinik_rm          = ($current_mod === 'klinik' && $current_role === 'petugas rm');
$is_klinik             = ($is_klinik_admin || $is_klinik_pendaftaran || $is_klinik_sampel || $is_klinik_rm);

// Flag Role Modul Kesmas
$is_kesmas_admin   = ($current_mod === 'kesmas' && $current_role === 'admin');
$is_kesmas_petugas = ($current_mod === 'kesmas' && $current_role === 'petugas');
$is_kesmas         = ($is_kesmas_admin || $is_kesmas_petugas);

// Status aktif grup menu Kesmas
$kes_sample_active = in_array($seg2, ['pendaftaran', 'kaji_ulang', 'kaji_ulang_detail', 'permintaan_sample', 'uji', 'laporan_pelanggan', 'kwitansi_kesmas']) || ($seg2 === 'laporan');
$kes_lab_active    = in_array($seg2, ['master_pemeriksaan', 'Puskesmas_wilayah_kesmas']);
$kes_report_active = in_array($seg2, ['survei', 'security']);
$kes_staff_active  = in_array($seg2, ['petugas', 'verifikator', 'penanggung_jawab_teknis']);

// Status aktif grup menu Klinik
$klinik_lab_active = in_array($seg2, ['form_permintaan_klinik', 'uji_klinik', 'laporan_uji_klinik', 'kwitansi_klinik']);
$klinik_rm_active  = in_array($seg2, ['form_permintaan_rm', 'uji_rekam_medis', 'uji_rekam_klinik', 'hasil_laporan']);
$klinik_lap_active = in_array($seg2, ['form_laporan_akhir', 'puskesmas_wilayah']);
$klinik_staf_active= in_array($seg2, ['petugas_sampel', 'petugas_verifikasi', 'petugas_validasi', 'verifikator', 'penanggung_teknis', 'petugas_dokter']);

$greeting_role = current_role_label();
?>

<!-- Sidebar -->
<nav id="sidebar" class="d-flex flex-column shadow-sm">
  <!-- Brand Logo -->
  <div class="d-flex align-items-center justify-content-center py-4 border-bottom">
    <a href="<?= site_url('dashboard') ?>" class="text-decoration-none text-dark d-flex align-items-center">
      <img src="<?= base_url('assets/img/logolabkes.png') ?>" alt="Logo" class="rounded-circle shadow-sm me-2" style="width: 45px; height: 45px;">
      <span class="h5 fw-bold mb-0 d-none d-md-block">UPTD LABKES</span>
    </a>
  </div>

  <!-- Search Menu -->
  <div class="px-3 pt-3 pb-2">
    <div class="input-group input-group-sm">
      <input type="text" class="form-control border-end-0 bg-light" placeholder="Cari menu..." onkeyup="searchSidebar(this.value)">
      <span class="input-group-text bg-light"><i class="fas fa-search text-muted"></i></span>
    </div>
  </div>

  <!-- Sidebar Menu -->
  <div class="flex-grow-1 overflow-auto pb-4">
    <ul class="nav flex-column mb-auto" id="sidebarMenu">

      <!-- BLOK 1: MENU UTAMA -->
      <li class="nav-header">Menu Utama</li>
      <li class="nav-item">
        <a href="<?= site_url('dashboard') ?>" class="nav-link <?= ($seg1 == 'dashboard') ? 'active' : '' ?>">
          <i class="nav-icon fas fa-th-large text-primary"></i>
          <span>Dashboard</span>
        </a>
      </li>

      <?php if ($is_kesmas): ?>
      <!-- ========================================== -->
      <!-- MODUL KESMAS                               -->
      <!-- ========================================== -->

      <!-- BLOK 2: OPERASIONAL KESMAS -->
      <li class="nav-header">Operasional Kesmas</li>

      <!-- Layanan Sampel -->
      <li class="nav-item">
        <a class="nav-link <?= $kes_sample_active ? 'active' : 'collapsed' ?>" data-bs-toggle="collapse" href="#collapseKesSample" role="button" aria-expanded="<?= $kes_sample_active ? 'true' : 'false' ?>">
          <i class="nav-icon fas fa-flask text-info"></i>
          <span>Layanan Sampel</span>
          <i class="fas fa-chevron-down arrow-icon"></i>
        </a>
        <div class="collapse <?= $kes_sample_active ? 'show' : '' ?>" id="collapseKesSample" data-bs-parent="#sidebarMenu">
          <ul class="nav flex-column ms-3 mt-1">
            <li class="nav-item">
              <a href="<?= site_url('k-panel/pendaftaran') ?>" class="nav-link <?= in_array($seg2, ['pendaftaran', 'kaji_ulang', 'kaji_ulang_detail']) ? 'active' : '' ?>">
                <i class="nav-icon far fa-circle" style="font-size: 0.5rem;"></i> Pendaftaran
              </a>
            </li>
            <li class="nav-item">
              <a href="<?= site_url('k-panel/permintaan_sample') ?>" class="nav-link <?= $seg2 == 'permintaan_sample' ? 'active' : '' ?>">
                <i class="nav-icon far fa-circle" style="font-size: 0.5rem;"></i> Terima Sample
              </a>
            </li>
            <li class="nav-item">
              <a href="<?= site_url('k-panel/uji') ?>" class="nav-link <?= $seg2 == 'uji' ? 'active' : '' ?>">
                <i class="nav-icon far fa-circle" style="font-size: 0.5rem;"></i> Input Hasil
              </a>
            </li>
            <li class="nav-item">
              <a href="<?= site_url('k-panel/laporan') ?>" class="nav-link <?= ($seg2 == 'laporan' && $seg3 !== 'parameter') ? 'active' : '' ?>">
                <i class="nav-icon far fa-circle" style="font-size: 0.5rem;"></i> Laporan Uji
              </a>
            </li>
            <li class="nav-item">
              <a href="<?= site_url('k-panel/laporan/parameter') ?>" class="nav-link <?= ($seg2 == 'laporan' && $seg3 == 'parameter') ? 'active' : '' ?>">
                <i class="nav-icon far fa-circle" style="font-size: 0.5rem;"></i> Lap. Parameter
              </a>
            </li>
            <li class="nav-item">
              <a href="<?= site_url('k-panel/laporan_pelanggan') ?>" class="nav-link <?= $seg2 == 'laporan_pelanggan' ? 'active' : '' ?>">
                <i class="nav-icon far fa-circle" style="font-size: 0.5rem;"></i> Lap. Pelanggan
              </a>
            </li>
            <li class="nav-item">
              <a href="<?= site_url('k-panel/kwitansi_kesmas') ?>" class="nav-link <?= $seg2 == 'kwitansi_kesmas' ? 'active' : '' ?>">
                <i class="nav-icon far fa-circle" style="font-size: 0.5rem;"></i> Kwitansi Kesmas
              </a>
            </li>
          </ul>
        </div>
      </li>

      <!-- Fasilitas Lab -->
      <li class="nav-item">
        <a class="nav-link <?= $kes_lab_active ? 'active' : 'collapsed' ?>" data-bs-toggle="collapse" href="#collapseKesLab" role="button" aria-expanded="<?= $kes_lab_active ? 'true' : 'false' ?>">
          <i class="nav-icon fas fa-building-shield text-secondary"></i>
          <span>Lab Management</span>
          <i class="fas fa-chevron-down arrow-icon"></i>
        </a>
        <div class="collapse <?= $kes_lab_active ? 'show' : '' ?>" id="collapseKesLab" data-bs-parent="#sidebarMenu">
          <ul class="nav flex-column ms-3 mt-1">
            <li class="nav-item">
              <a href="<?= site_url('k-panel/master_pemeriksaan') ?>" class="nav-link <?= $seg2 == 'master_pemeriksaan' ? 'active' : '' ?>">
                <i class="nav-icon far fa-circle" style="font-size: 0.5rem;"></i> Master Parameter
              </a>
            </li>
            <li class="nav-item">
              <a href="<?= site_url('k-panel/Puskesmas_wilayah_kesmas') ?>" class="nav-link <?= $seg2 == 'Puskesmas_wilayah_kesmas' ? 'active' : '' ?>">
                <i class="nav-icon far fa-circle" style="font-size: 0.5rem;"></i> Wilayah Puskesmas
              </a>
            </li>
          </ul>
        </div>
      </li>

      <!-- Survei & Evaluasi -->
      <li class="nav-item">
        <a href="<?= site_url('k-panel/survei/laporan') ?>" class="nav-link <?= $seg2 == 'survei' ? 'active' : '' ?>">
          <i class="nav-icon fas fa-chart-line text-success"></i>
          <span>Survei Kepuasan</span>
        </a>
      </li>

      <!-- BLOK 3: SISTEM & MANAJEMEN KESMAS -->
      <li class="nav-header">Sistem &amp; Manajemen</li>

      <!-- Tim Internal Kesmas -->
      <li class="nav-item">
        <a class="nav-link <?= $kes_staff_active ? 'active' : 'collapsed' ?>" data-bs-toggle="collapse" href="#collapseKesStaff" role="button" aria-expanded="<?= $kes_staff_active ? 'true' : 'false' ?>">
          <i class="nav-icon fas fa-people-group text-danger"></i>
          <span>Tim Internal</span>
          <i class="fas fa-chevron-down arrow-icon"></i>
        </a>
        <div class="collapse <?= $kes_staff_active ? 'show' : '' ?>" id="collapseKesStaff" data-bs-parent="#sidebarMenu">
          <ul class="nav flex-column ms-3 mt-1">
            <li class="nav-item">
              <a href="<?= site_url('k-panel/petugas') ?>" class="nav-link <?= $seg2 == 'petugas' ? 'active' : '' ?>">
                <i class="nav-icon far fa-circle" style="font-size: 0.5rem;"></i> Daftar Petugas
              </a>
            </li>
            <li class="nav-item">
              <a href="<?= site_url('k-panel/verifikator') ?>" class="nav-link <?= $seg2 == 'verifikator' ? 'active' : '' ?>">
                <i class="nav-icon far fa-circle" style="font-size: 0.5rem;"></i> Verifikator
              </a>
            </li>
            <li class="nav-item">
              <a href="<?= site_url('k-panel/penanggung_jawab_teknis') ?>" class="nav-link <?= $seg2 == 'penanggung_jawab_teknis' ? 'active' : '' ?>">
                <i class="nav-icon far fa-circle" style="font-size: 0.5rem;"></i> Penanggung Jawab Teknis
              </a>
            </li>
          </ul>
        </div>
      </li>

      <?php if ($is_kesmas_admin): ?>
      <!-- Khusus Admin Kesmas: Keamanan & Audit -->
      <li class="nav-item">
        <a href="<?= site_url('k-panel/security') ?>" class="nav-link <?= $seg2 == 'security' ? 'active' : '' ?>">
          <i class="nav-icon fas fa-shield-alt text-info"></i>
          <span>Security Dashboard</span>
        </a>
      </li>
      <?php endif; ?>

      <?php endif; // Akhir $is_kesmas ?>


      <?php if ($is_klinik): ?>
      <!-- ========================================== -->
      <!-- MODUL KLINIK & REKAM MEDIS                 -->
      <!-- ========================================== -->

      <!-- BLOK 2: PELAYANAN MEDIS -->
      <li class="nav-header">Pelayanan Medis</li>

      <!-- Layanan Laboratorium Klinik (Admin, Petugas Pendaftaran, Pemeriksa Sampel) -->
      <?php if ($is_klinik_admin || $is_klinik_pendaftaran || $is_klinik_sampel): ?>
      <li class="nav-item">
        <a class="nav-link <?= $klinik_lab_active ? 'active' : 'collapsed' ?>" data-bs-toggle="collapse" href="#collapseKlinik" role="button" aria-expanded="<?= $klinik_lab_active ? 'true' : 'false' ?>">
          <i class="nav-icon fas fa-clinic-medical text-danger"></i>
          <span>Klinik</span>
          <i class="fas fa-chevron-down arrow-icon"></i>
        </a>
        <div class="collapse <?= $klinik_lab_active ? 'show' : '' ?>" id="collapseKlinik" data-bs-parent="#sidebarMenu">
          <ul class="nav flex-column ms-3 mt-1">
            <?php if ($is_klinik_admin || $is_klinik_pendaftaran): ?>
            <li class="nav-item">
              <a href="<?= site_url('klinik/form_permintaan_klinik') ?>" class="nav-link <?= $seg2 == 'form_permintaan_klinik' ? 'active' : '' ?>">
                <i class="nav-icon far fa-circle" style="font-size: 0.5rem;"></i> Form Pendaftaran Klinik
              </a>
            </li>
            <?php endif; ?>

            <?php if ($is_klinik_admin || $is_klinik_sampel): ?>
            <li class="nav-item">
              <a href="<?= site_url('klinik/uji_klinik') ?>" class="nav-link <?= $seg2 == 'uji_klinik' ? 'active' : '' ?>">
                <i class="nav-icon far fa-circle" style="font-size: 0.5rem;"></i> Uji Lab Klinik
              </a>
            </li>
            <?php endif; ?>

            <?php if ($is_klinik_admin || $is_klinik_pendaftaran || $is_klinik_sampel): ?>
            <li class="nav-item">
              <a href="<?= site_url('klinik/laporan_uji_klinik') ?>" class="nav-link <?= $seg2 == 'laporan_uji_klinik' ? 'active' : '' ?>">
                <i class="nav-icon far fa-circle" style="font-size: 0.5rem;"></i> Laporan Uji Lab
              </a>
            </li>
            <li class="nav-item">
              <a href="<?= site_url('klinik/kwitansi_klinik') ?>" class="nav-link <?= $seg2 == 'kwitansi_klinik' ? 'active' : '' ?>">
                <i class="nav-icon far fa-circle" style="font-size: 0.5rem;"></i> Kwitansi Klinik
              </a>
            </li>
            <?php endif; ?>
          </ul>
        </div>
      </li>
      <?php endif; ?>

      <!-- Rekam Medis Pasien (Admin, Petugas Pendaftaran, Petugas RM) -->
      <?php if ($is_klinik_admin || $is_klinik_pendaftaran || $is_klinik_rm): ?>
      <li class="nav-item">
        <a class="nav-link <?= $klinik_rm_active ? 'active' : 'collapsed' ?>" data-bs-toggle="collapse" href="#collapseRM" role="button" aria-expanded="<?= $klinik_rm_active ? 'true' : 'false' ?>">
          <i class="nav-icon fas fa-notes-medical text-warning"></i>
          <span>Rekam Medis</span>
          <i class="fas fa-chevron-down arrow-icon"></i>
        </a>
        <div class="collapse <?= $klinik_rm_active ? 'show' : '' ?>" id="collapseRM" data-bs-parent="#sidebarMenu">
          <ul class="nav flex-column ms-3 mt-1">
            <?php if ($is_klinik_admin || $is_klinik_pendaftaran || $is_klinik_rm): ?>
            <li class="nav-item">
              <a href="<?= site_url('klinik/form_permintaan_rm') ?>" class="nav-link <?= $seg2 == 'form_permintaan_rm' ? 'active' : '' ?>">
                <i class="nav-icon far fa-circle" style="font-size: 0.5rem;"></i> Pendaftaran Pasien RM
              </a>
            </li>
            <li class="nav-item">
              <a href="<?= site_url('klinik/uji_rekam_medis') ?>" class="nav-link <?= $seg2 == 'uji_rekam_medis' ? 'active' : '' ?>">
                <i class="nav-icon far fa-circle" style="font-size: 0.5rem;"></i> Detail Kunjungan SOAP
              </a>
            </li>
            <?php endif; ?>

            <?php if ($is_klinik_admin || $is_klinik_rm): ?>
            <li class="nav-item">
              <a href="<?= site_url('klinik/uji_rekam_klinik') ?>" class="nav-link <?= $seg2 == 'uji_rekam_klinik' ? 'active' : '' ?>">
                <i class="nav-icon far fa-circle" style="font-size: 0.5rem;"></i> Detail Rekam Klinik
              </a>
            </li>
            <?php endif; ?>
          </ul>
        </div>
      </li>
      <?php endif; ?>

      <!-- Laporan & Wilayah (Admin, Petugas RM) -->
      <?php if ($is_klinik_admin || $is_klinik_rm): ?>
      <li class="nav-header">Laporan &amp; Wilayah</li>
      <li class="nav-item">
        <a href="<?= site_url('klinik/form_laporan_akhir') ?>" class="nav-link <?= $seg2 == 'form_laporan_akhir' ? 'active' : '' ?>">
          <i class="nav-icon far fa-file-alt text-primary"></i>
          <span>Form Laporan Akhir</span>
        </a>
      </li>
      <li class="nav-item">
        <a href="<?= site_url('klinik/puskesmas_wilayah') ?>" class="nav-link <?= $seg2 == 'puskesmas_wilayah' ? 'active' : '' ?>">
          <i class="fas fa-map nav-icon text-success"></i>
          <span>Manajemen Lokasi</span>
        </a>
      </li>
      <?php endif; ?>

      <!-- BLOK 3: SISTEM & PENGATURAN (Khusus Admin Klinik) -->
      <?php if ($is_klinik_admin): ?>
      <li class="nav-header">Sistem &amp; Pengaturan</li>

      <li class="nav-item">
        <a href="<?= site_url('klinik/user') ?>" class="nav-link <?= $seg2 == 'user' ? 'active' : '' ?>">
          <i class="nav-icon fas fa-users-cog text-warning"></i>
          <span>Manajemen Akun User</span>
        </a>
      </li>
      <li class="nav-item">
        <a href="<?= site_url('klinik/login_logs') ?>" class="nav-link <?= $seg2 == 'login_logs' ? 'active' : '' ?>">
          <i class="nav-icon fas fa-history text-info"></i>
          <span>Log Login User</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link <?= $klinik_staf_active ? 'active' : 'collapsed' ?>" data-bs-toggle="collapse" href="#collapseManajemen" role="button" aria-expanded="<?= $klinik_staf_active ? 'true' : 'false' ?>">
          <i class="nav-icon fas fa-cogs text-secondary"></i>
          <span>Manajemen Petugas</span>
          <i class="fas fa-chevron-down arrow-icon"></i>
        </a>
        <div class="collapse <?= $klinik_staf_active ? 'show' : '' ?>" id="collapseManajemen" data-bs-parent="#sidebarMenu">
          <ul class="nav flex-column ms-3 mt-1">
            <li class="nav-item"><a href="<?= site_url('klinik/petugas_sampel') ?>" class="nav-link <?= $seg2 == 'petugas_sampel' ? 'active' : '' ?>"><i class="nav-icon far fa-circle" style="font-size: 0.5rem;"></i> Petugas Klinik</a></li>
            <li class="nav-item"><a href="<?= site_url('klinik/petugas_verifikasi') ?>" class="nav-link <?= $seg2 == 'petugas_verifikasi' ? 'active' : '' ?>"><i class="nav-icon far fa-circle" style="font-size: 0.5rem;"></i> Petugas Verifikasi</a></li>
            <li class="nav-item"><a href="<?= site_url('klinik/petugas_validasi') ?>" class="nav-link <?= $seg2 == 'petugas_validasi' ? 'active' : '' ?>"><i class="nav-icon far fa-circle" style="font-size: 0.5rem;"></i> Petugas Validasi</a></li>
            <li class="nav-item"><a href="<?= site_url('klinik/verifikator') ?>" class="nav-link <?= $seg2 == 'verifikator' ? 'active' : '' ?>"><i class="nav-icon far fa-circle" style="font-size: 0.5rem;"></i> Petugas Verifikator Hasil</a></li>
            <li class="nav-item"><a href="<?= site_url('klinik/penanggung_teknis') ?>" class="nav-link <?= $seg2 == 'penanggung_teknis' ? 'active' : '' ?>"><i class="nav-icon far fa-circle" style="font-size: 0.5rem;"></i> Petugas Penanggung Jawab</a></li>
            <li class="nav-item"><a href="<?= site_url('klinik/petugas_dokter') ?>" class="nav-link <?= $seg2 == 'petugas_dokter' ? 'active' : '' ?>"><i class="nav-icon far fa-circle" style="font-size: 0.5rem;"></i> Dokter Pemeriksa Rekam Medis</a></li>
          </ul>
        </div>
      </li>
      <?php endif; ?>

      <?php endif; // Akhir $is_klinik ?>

    </ul>
  </div>
</nav>

<script>
  function searchSidebar(keyword) {
    const items = document.querySelectorAll('#sidebarMenu .nav-item');
    const headers = document.querySelectorAll('#sidebarMenu .nav-header');
    const term = keyword.trim().toLowerCase();

    // Saring setiap menu item
    items.forEach(item => {
      const span = item.querySelector('span');
      if (span) {
        const text = span.innerText.toLowerCase();
        const matches = text.includes(term);
        item.style.display = matches ? '' : 'none';
      }
    });

    // Otomatis buka accordion jika sedang mencari keyword tertentu
    const collapses = document.querySelectorAll('#sidebarMenu .collapse');
    collapses.forEach(collapse => {
      if (term.length > 0) {
        const visibleSubItems = collapse.querySelectorAll('.nav-item:not([style*="display: none"])');
        if (visibleSubItems.length > 0) {
          collapse.classList.add('show');
        }
      }
    });
  }
</script>

<!-- Main Content Wrapper -->
<div id="content-wrapper">
  <!-- Navbar Top -->
  <nav class="navbar navbar-expand top-navbar px-4 py-3">
    <div class="container-fluid">
      <!-- Sidebar Toggle -->
      <button type="button" id="sidebarCollapse" class="btn btn-light shadow-sm text-success me-3 rounded-circle" style="width:40px; height:40px; display:flex; align-items:center; justify-content:center;">
        <i class="fas fa-bars"></i>
      </button>

      <div class="d-flex align-items-center text-dark">
        <span class="fw-bold mb-0 d-none d-md-block ms-2 h5 mb-0">
          Halo <?= html_escape($greeting_role) ?>
        </span>
      </div>

      <!-- Center App Name -->
      <div class="position-absolute w-100 d-flex justify-content-center" style="left: 0; pointer-events: none;">
        <span class="h5 fw-bold text-white d-none d-lg-block mb-0" style="letter-spacing: 0.5px;">Laboratorium Kesehatan Kota Pangkal Pinang</span>
      </div>

      <div class="ms-auto">
        <a href="<?= site_url('logout') ?>" class="btn rounded-pill px-4 py-2 shadow-sm d-flex align-items-center" style="background-color: #bce9dc; color: #1c3c34; font-weight: 600; font-size: 0.9rem;">
          <i class="fas fa-sign-out-alt me-2"></i> <span>Sign Out</span>
        </a>
      </div>
    </div>
  </nav>

  <!-- Page Content -->
  <main id="main-content" class="<?= ($this->session->userdata('module') === 'kesmas') ? 'p-4' : 'p-0' ?> flex-grow-1">
    <?php if ($this->session->userdata('module') === 'kesmas') { $this->load->view('partials/flash'); } ?>

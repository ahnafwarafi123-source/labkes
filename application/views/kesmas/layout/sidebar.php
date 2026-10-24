<!-- Optional (kalau belum ada di layout/header): Bootstrap Icons -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

<style>
  :root {
    --sb-ink: #1e293b;
    --sb-muted: #64748b;
    --sb-brand: #2563eb;
    --sb-bg: #ffffff;
    --sb-border: #e2e8f0;
    --sb-hover: #f8fafc;
    --sb-active-bg: #eff6ff;
  }

  /* Sidebar Core */
  .sidebar {
    position: sticky;
    top: 42px; /* Disesuaikan agar bisa naik lebih dekat dengan navbar yang ukurannya sekarang mengecil */
    height: calc(100vh - 42px); 
    overflow-y: auto;
    overflow-x: hidden;
    background: var(--sb-bg) !important;
    border-right: 1px solid var(--sb-border) !important;
    transition: all 0.3s ease;
    display: flex;
    flex-direction: column;
    z-index: 1020;
  }
  
  .sidebar-inner {
    padding: 1.5rem 1.25rem; /* Dikembalikan ke 1.5rem agar sejajar (sama rata) persis dengan padding konten utama (p-4) */
    display: flex;
    flex-direction: column;
    min-height: 100%;
  }

  /* Brand Logo */
  .sb-brand {
    display: flex;
    align-items: center;
    gap: 0.85rem;
    padding: 0.75rem 1rem;
    margin-bottom: 0.5rem; /* Dikurangi secara signifikan dari 2rem menjadi 0.5rem agar tombol Dashboard naik */
    text-decoration: none;
    transition: opacity 0.2s ease;
  }

  .sb-brand:hover {
    opacity: 0.8;
  }
  
  .sb-badge {
    width: 36px;
    height: 36px;
    border-radius: 8px;
    background: var(--sb-brand);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.15rem;
    flex-shrink: 0;
  }
  
  .sb-text-wrap {
    display: flex;
    flex-direction: column;
    justify-content: center;
  }
  
  .sb-title {
    font-weight: 800;
    font-size: 1.15rem;
    color: var(--sb-ink);
    letter-spacing: -0.2px;
    line-height: 1.1;
  }
  .sb-sub {
    font-size: 0.7rem;
    color: var(--sb-muted);
    font-weight: 600;
    line-height: 1.2;
    margin-top: 2px;
  }

  /* Section Titles */
  .sb-section {
    font-size: 0.7rem;
    font-weight: 700;
    color: #94a3b8;
    text-transform: uppercase;
    letter-spacing: 1px;
    margin: 1.5rem 0 0.5rem 0.5rem;
  }

  /* Navigation Links */
  .sidebar .nav-pills {
    display: flex;
    flex-direction: column;
    gap: 0.2rem;
  }
  .sidebar .nav-link {
    display: flex;
    align-items: center;
    gap: 0.85rem; 
    padding: 0.65rem 1rem; 
    border-radius: 12px; /* Lebih membulat agar terlihat modern */
    color: var(--sb-muted);
    font-weight: 600;
    font-size: 0.9rem;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    text-decoration: none;
    margin-bottom: 0.15rem; /* Memberi sedikit jarak antar menu agar seperti floating pill */
    border: 1px solid transparent;
  }
  .sidebar .nav-link i {
    font-size: 1.15rem;
    color: #94a3b8;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    display: flex;
    align-items: center;
    justify-content: center;
    width: 22px;
  }

  .sidebar .nav-link:hover {
    background: var(--sb-hover);
    color: var(--sb-ink);
    transform: translateX(4px); /* Animasi geser elegan saat dihover */
    border-color: var(--sb-border);
  }
  .sidebar .nav-link:hover i {
    color: var(--sb-brand);
    transform: scale(1.1); /* Ikon sedikit membesar */
  }

  /* Active State Premium */
  .sidebar .nav-link.active {
    background: linear-gradient(135deg, var(--sb-brand), #4f46e5); /* Gradien biru ke nila (indigo) yang mewah */
    color: white;
    font-weight: 700;
    box-shadow: 0 4px 15px rgba(37, 99, 235, 0.3); /* Efek glowing halus */
    transform: translateX(2px);
    border-color: transparent;
  }

  .sidebar .nav-link.active i {
    color: white;
    filter: drop-shadow(0 2px 4px rgba(255,255,255,0.3)); /* Ikon menyala */
  }


  /* Submenu Styles */
  .sidebar-parent-toggle::after {
    content: '\F282'; /* bootstrap-icons chevron-down */
    font-family: 'bootstrap-icons';
    margin-left: auto;
    font-size: 0.8rem;
    transition: transform 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
    color: var(--sb-muted);
    z-index: 1;
  }
  .sidebar-parent-toggle.expanded::after {
    transform: rotate(-180deg);
  }
  
  .sidebar-submenu {
    display: none;
    padding-left: 2rem;
    margin-top: 0.25rem;
    margin-bottom: 0.5rem;
    animation: slideDown 0.3s ease-out forwards;
  }
  @keyframes slideDown {
    from { opacity: 0; transform: translateY(-10px); }
    to { opacity: 1; transform: translateY(0); }
  }
  .sidebar-submenu.show {
    display: block;
  }
  
  .sidebar-submenu .nav-link {
    padding: 0.65rem 1rem;
    font-size: 0.85rem;
    font-weight: 500;
    border-radius: 10px;
    margin-bottom: 0.2rem;
  }
  .sidebar-submenu .nav-link i {
    font-size: 1.05rem;
    width: 20px;
  }
  .sidebar-submenu .nav-link.active::after {
    display: none;
  }
  .sidebar-submenu .nav-link.active {
    box-shadow: none;
    background: rgba(37, 99, 235, 0.08); /* Background aktif untuk submenu lebih lembut */
    color: var(--sb-brand);
    font-weight: 700;
    transform: none;
  }
  .sidebar-submenu .nav-link.active i {
    color: var(--sb-brand);
    filter: none;
  }

  /* Bottom Actions */
  .sb-bottom {
    margin-top: auto;
    padding-top: 2rem;
  }
  
  .sb-card {
    background: linear-gradient(135deg, rgba(79, 70, 229, 0.08), rgba(147, 51, 234, 0.08));
    border: 1px solid rgba(79, 70, 229, 0.2);
    border-radius: 18px;
    padding: 1.25rem;
    text-align: center;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
  }

  .sb-card:hover {
    box-shadow: 0 10px 25px -5px rgba(79, 70, 229, 0.2);
    transform: translateY(-2px);
    border-color: rgba(79, 70, 229, 0.4);
  }
  
  .sb-card-icon {
    width: 40px;
    height: 40px;
    background: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 0.75rem auto;
    color: var(--sb-brand);
    font-size: 1.2rem;
    box-shadow: 0 4px 12px rgba(0,0,0,0.05);
  }


  .sb-card-title {
    font-size: 0.85rem;
    font-weight: 700;
    color: var(--sb-ink);
    margin-bottom: 0.25rem;
  }
  .sb-card-text {
    font-size: 0.75rem;
    color: var(--sb-muted);
    margin-bottom: 1rem;
    line-height: 1.4;
  }

  .btn-sb-action {
    background: linear-gradient(135deg, var(--sb-brand), #9333ea);
    color: white;
    border: none;
    border-radius: 12px;
    padding: 0.7rem 1rem;
    font-weight: 600;
    font-size: 0.85rem;
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    transition: all 0.3s ease;
    cursor: pointer;
  }
  .btn-sb-action:hover {
    box-shadow: 0 8px 20px rgba(79, 70, 229, 0.4);
    transform: scale(1.03);
    color: white;
  }

  /* Scrollbar */
  .sidebar::-webkit-scrollbar {
    width: 6px;
  }
  .sidebar::-webkit-scrollbar-track {
    background: transparent;
  }
  .sidebar::-webkit-scrollbar-thumb {
    background: var(--sb-border);
    border-radius: 10px;
  }
  .sidebar::-webkit-scrollbar-thumb:hover {
    background: var(--sb-muted);
  }

  main#main-content {
    background: transparent;
  }
</style>

<aside id="sidebar" class="sidebar">
  <div class="sidebar-inner">
    <?php
      $uri = uri_string();
      $is_kesmas_active = strpos($uri,'kesmas/') !== false && strpos($uri,'kesmas/survei') === false && strpos($uri,'kesmas/master_pemeriksaan') === false && strpos($uri,'kesmas/security') === false && strpos($uri,'kesmas/dashboard') === false && strpos($uri,'kesmas/petugas') === false && strpos($uri,'kesmas/verifikator') === false && strpos($uri,'kesmas/penanggung') === false;
      $is_lab_management_active = strpos($uri,'kesmas/master_pemeriksaan') !== false;
      $is_report_active = strpos($uri,'kesmas/survei') !== false || strpos($uri,'kesmas/security') !== false;
      $is_petugas_active = strpos($uri,'petugas') !== false || strpos($uri,'verifikator') !== false || strpos($uri,'penanggung_jawab_teknis') !== false;
    ?>

    <!-- Brand -->
    <a href="<?= site_url('k-panel/dashboard') ?>" class="sb-brand">
      <div class="sb-badge">
        <i class="bi bi-activity"></i>
      </div>
      <div class="sb-text-wrap">
        <div class="sb-title">KESMAS</div>
        <div class="sb-sub">Smart Portal</div>
      </div>
    </a>

    <!-- Menu -->
    <div class="nav flex-column nav-pills">
      
      <a class="nav-link <?= ($uri=='' || $uri=='dashboard' || $uri=='kesmas/dashboard') ? 'active' : '' ?>" href="<?= site_url('k-panel/dashboard') ?>">
        <i class="bi bi-grid-1x2-fill"></i>
        <span>Dashboard</span>
      </a>

      <div class="sb-section">Layanan Kesmas</div>

      <a href="#" class="nav-link sidebar-parent-toggle <?= $is_kesmas_active ? 'active expanded' : '' ?>" data-target="kesmasSubmenu">
        <i class="bi bi-folder-fill"></i>
        <span>Manajemen Sample</span>
      </a>
      <div id="kesmasSubmenu" class="sidebar-submenu <?= $is_kesmas_active ? 'show' : '' ?>">
        <a class="nav-link <?= (strpos($uri,'kesmas/form_permintaan_kesmas')!==false && strpos($uri,'uji_')===false) ? 'active' : '' ?>" href="<?= site_url('k-panel/pendaftaran') ?>">
          <i class="bi bi-clipboard2-check"></i>
          <span>Pendaftaran</span>
        </a>
        <a class="nav-link <?= (strpos($uri,'kesmas/permintaan_sample')!==false) ? 'active' : '' ?>" href="<?= site_url('k-panel/permintaan_sample') ?>">
          <i class="bi bi-inbox-fill"></i>
          <span>Terima Sample</span>
        </a>
        <a class="nav-link <?= (strpos($uri,'kesmas/form_permintaan_kesmas/uji')!==false) ? 'active' : '' ?>" href="<?= site_url('k-panel/uji') ?>">
          <i class="bi bi-ui-checks-grid"></i>
          <span>Input Hasil</span>
        </a>
        <a class="nav-link <?= (strpos($uri,'kesmas/laporan_uji_kesmas')!==false) ? 'active' : '' ?>" href="<?= site_url('k-panel/laporan') ?>">
          <i class="bi bi-bar-chart-line-fill"></i>
          <span>Laporan Uji</span>
        </a>
        <a class="nav-link <?= (strpos($uri,'kesmas/laporan/parameter')!==false) ? 'active' : '' ?>" href="<?= site_url('k-panel/laporan/parameter') ?>">
          <i class="bi bi-list-columns"></i>
          <span>Lap. Parameter</span>
        </a>
        <a class="nav-link <?= (strpos($uri,'kesmas/laporan_pelanggan')!==false) ? 'active' : '' ?>" href="<?= site_url('k-panel/laporan_pelanggan') ?>">
          <i class="bi bi-person-lines-fill"></i>
          <span>Lap. Pelanggan</span>
        </a>
      </div>

      <div class="sb-section">Fasilitas Lab</div>

      <a href="#" class="nav-link sidebar-parent-toggle <?= $is_lab_management_active ? 'active expanded' : '' ?>" data-target="labSubmenu">
        <i class="bi bi-building-fill"></i>
        <span>Lab Management</span>
      </a>
      <div id="labSubmenu" class="sidebar-submenu <?= $is_lab_management_active ? 'show' : '' ?>">

        <a class="nav-link <?= (strpos($uri,'kesmas/master_pemeriksaan')!==false) ? 'active' : '' ?>" href="<?= site_url('k-panel/master_pemeriksaan') ?>">
          <i class="bi bi-vials"></i>
          <span>Master Parameter</span>
        </a>
      </div>

      <div class="sb-section">Pantauan & Audit</div>

      <a href="#" class="nav-link sidebar-parent-toggle <?= $is_report_active ? 'active expanded' : '' ?>" data-target="reportSubmenu">
        <i class="bi bi-journal-text"></i>
        <span>Survei & Log</span>
      </a>
      <div id="reportSubmenu" class="sidebar-submenu <?= $is_report_active ? 'show' : '' ?>">
        <a class="nav-link <?= (strpos($uri,'kesmas/survei')!==false) ? 'active' : '' ?>" href="<?= site_url('k-panel/survei/laporan') ?>">
          <i class="bi bi-chat-left-quote-fill"></i>
          <span>Survei Kepuasan</span>
        </a>
      
      <?php if ($this->session->userdata('role') === 'admin'): ?>
        <a class="nav-link <?= ($uri=='security' || strpos($uri,'security/')===0 || $uri=='kesmas/security' || strpos($uri,'kesmas/security/')===0) ? 'active' : '' ?>" href="<?= site_url('k-panel/security') ?>">
          <i class="bi bi-shield-lock-fill"></i>
          <span>Security Dashboard</span>
        </a>
      <?php endif; ?>
      </div>

      <div class="sb-section">Sumber Daya</div>

      <a href="#" class="nav-link sidebar-parent-toggle <?= $is_petugas_active ? 'active expanded' : '' ?>" data-target="staffSubmenu">
        <i class="bi bi-people-fill"></i>
        <span>Tim Internal</span>
      </a>
      <div id="staffSubmenu" class="sidebar-submenu <?= $is_petugas_active ? 'show' : '' ?>">
        <a class="nav-link <?= (strpos($uri,'petugas')!==false) ? 'active' : '' ?>" href="<?= site_url('k-panel/petugas') ?>">
          <i class="bi bi-people-fill"></i>
          <span>Daftar Petugas</span>
        </a>
        <a class="nav-link <?= (strpos($uri,'verifikator')!==false) ? 'active' : '' ?>" href="<?= site_url('k-panel/verifikator') ?>">
          <i class="bi bi-person-check-fill"></i>
          <span>Verifikator</span>
        </a>
        <a class="nav-link <?= (strpos($uri,'penanggung_jawab_teknis')!==false) ? 'active' : '' ?>" href="<?= site_url('k-panel/penanggung_jawab_teknis') ?>">
          <i class="bi bi-person-badge"></i>
          <span>PJT</span>
        </a>
      </div>

    </div>


    
    <!-- Load Premium JS -->
    <script src="<?= base_url('assets/js/premium.js') ?>"></script> 
  </div>
</aside>
<div id="sidebar-resizer"></div>
<main id="main-content" class="p-4">
  <?php $this->load->view('partials/flash'); ?>
  <script>
  (function(){
    try {
      const storageKey = 'sidebar_active_href';
      const stored = localStorage.getItem(storageKey);
      
      if (stored) {
        try {
          document.querySelectorAll('.sidebar .nav-link.active').forEach(function(a){ a.classList.remove('active'); });
          const match = document.querySelector('.sidebar .nav-link[href="' + stored + '"]');
          if (match) {
            match.classList.add('active');
            var submenu = match.closest('.sidebar-submenu');
            if (submenu) {
              submenu.classList.add('show');
              var parentToggle = document.querySelector('.sidebar-parent-toggle[data-target="' + submenu.id + '"]');
              if (parentToggle) parentToggle.classList.add('active','expanded');
            }
          }
        } catch (e) {}
      }

      document.querySelectorAll('.sidebar .nav-link').forEach(function(el){
        el.addEventListener('click', function(e){
          var href = el.getAttribute('href');
          if (href && href !== '#') {
            try { localStorage.setItem(storageKey, href); } catch(_){}
          }
          if(!el.classList.contains('sidebar-parent-toggle')) {
             try {
               document.querySelectorAll('.sidebar .nav-link.active').forEach(function(a){ a.classList.remove('active'); });
             } catch(_){}
             el.classList.add('active');
          }
        });
      });

      document.querySelectorAll('.sidebar-parent-toggle').forEach(function(toggle){
        var targetId = toggle.getAttribute('data-target');
        var submenu = document.getElementById(targetId);
        if (!submenu) return;

        var submenuKey = 'sidebar_' + targetId + '_open';
        var storedOpen = localStorage.getItem(submenuKey);
        if (storedOpen === 'true' || submenu.classList.contains('show')) {
          submenu.classList.add('show');
          toggle.classList.add('expanded');
        }

        toggle.addEventListener('click', function(e){
          e.preventDefault();
          var opened = submenu.classList.toggle('show');
          toggle.classList.toggle('expanded', opened);
          try { localStorage.setItem(submenuKey, opened ? 'true' : 'false'); } catch(_){}
        });
      });
    } catch (err) {}
  })();
  </script>

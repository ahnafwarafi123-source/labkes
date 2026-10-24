<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="color-scheme" content="light">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= isset($title) ? htmlspecialchars($title) . ' - KESMAS' : 'KESMAS - Health Management' ?></title>
  <link rel="icon" type="image/png" href="<?= base_url('assets/img/logo.png') ?>">
  <link rel="apple-touch-icon" href="<?= base_url('assets/img/logo.png') ?>">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  
  <!-- DataTables & Select2 CSS -->
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">
  
  <!-- Optional: Icons + Font (UI only) -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

  <!-- Custom UI Polish -->
  <link rel="stylesheet" href="<?= base_url('assets/css/ui-polish.css?v=') . time() ?>">

  <style>
    :root{
      --ink:#0b1220;
      --muted:#667085;
      --line: rgba(15,23,42,.10);
      --bg:
        radial-gradient(900px 420px at 14% 0%, rgba(13,110,253,.12), transparent 62%),
        radial-gradient(900px 420px at 92% 10%, rgba(34,197,94,.08), transparent 60%),
        linear-gradient(180deg, #f7f8fc 0%, #eef2f7 100%);
      --glass: rgba(255,255,255,.72);
      --shadow: 0 22px 70px rgba(2,6,23,.12);
      --shadow2: 0 12px 30px rgba(2,6,23,.10);
      --brand:#0d6efd;
      --brand2:#0b5ed7;
    }

    html {
      font-size: 11.7px; /* Dikecilkan 10% lagi dari 13px (13 * 0.9 = 11.7px) */
    }

    body{
      background: var(--bg);
      color: var(--ink);
      font-family: "Inter", system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif;
    }

    /* Navbar */
    .navbar-wow{
      position: sticky;
      top: 0;
      z-index: 1030;
      border-bottom: 1px solid rgba(255, 255, 255, 0.4);
      background: rgba(255, 255, 255, 0.7);
      backdrop-filter: blur(12px) saturate(150%);
      -webkit-backdrop-filter: blur(12px) saturate(150%);
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
      padding: 0.8rem 1rem;
      transition: all 0.3s ease;
    }


    .navbar-wow:hover {
      background: rgba(255, 255, 255, 0.85);
    }


    .navbar-brand{
      font-weight: 900;
      font-size: 1.25rem;
      color: var(--ink) !important;
      letter-spacing: -0.5px;
      display:flex;
      align-items:center;
    }

    .brand-sub{
      display:block;
      font-size: 0.7rem;
      font-weight: 700;
      color: #4f46e5;
      text-transform: uppercase;
      letter-spacing: 1px;
      margin-top: 2px;
    }


    .user-pill{
      display:flex;
      align-items:center;
      gap:.75rem;
      padding: 0.4rem 0.6rem 0.4rem 0.4rem;
      border-radius: 50px;
      background: #ffffff;
      border: 1px solid rgba(0,0,0,0.05);
      box-shadow: 0 4px 15px rgba(0,0,0,0.03);
      transition: all 0.3s ease;
    }
    .user-pill:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 20px rgba(0,0,0,0.08);
      border-color: rgba(79, 70, 229, 0.3);
    }


    .avatar{
      width: 40px;
      height: 40px;
      border-radius: 50%;
      display:flex;
      align-items:center;
      justify-content:center;
      background: linear-gradient(135deg, #4f46e5, #9333ea);
      border: 2px solid #fff;
      font-weight: 800;
      font-size: 1rem;
      user-select:none;
      color: white;
      box-shadow: 0 4px 10px rgba(79, 70, 229, 0.3);
    }


    .user-meta{
      line-height: 1.1;
    }
    .user-name{
      font-weight: 700;
      font-size: .9rem;
      color: var(--ink);
      max-width: 150px;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
    }

    .user-role{
      font-size: 0.75rem;
      font-weight: 600;
      color: #64748b;
      margin-top: 2px;
      display: flex;
      align-items: center;
    }


    .btn-logout{
      border-radius: 20px;
      font-weight: 600;
      padding: 0.4rem 1rem;
      border: 1px solid rgba(239, 68, 68, 0.2);
      background: rgba(239, 68, 68, 0.05);
      color: #ef4444;
      transition: all 0.3s ease;
      font-size: 0.875rem;
      display: flex;
      align-items: center;
      margin-left: 0.5rem;
    }

    .btn-logout:hover {
      background: #ef4444;
      color: white;
      border-color: #ef4444;
      transform: translateY(-1px);
      box-shadow: 0 4px 12px rgba(239, 68, 68, 0.2);
    }
    
    @media (max-width: 767.98px) {
        .user-pill { padding: 0.3rem; }
        .btn-logout { padding: 0.4rem; margin-left: 0; border-radius: 50%; }
        .btn-logout span { display: none; }
        .btn-logout i { margin-right: 0 !important; font-size: 1.1rem; }
    }

/* GLOBAL UI polish */
    .sidebar { 
      min-height: 100vh; 
      border-right: none !important; 
    }
    .nav-link.active { font-weight: 800; }

    /* --- Professional Table Styling --- */
    .table {
      font-size: 0.9rem;
      margin-bottom: 0;
      color: var(--ink);
    }
    
    .table th, .table td {
      padding: 0.5rem 0.75rem !important; /* Padding sangat ringkas agar tabel tidak bengkak */
      vertical-align: middle;
      white-space: nowrap !important; /* Mencegah teks terpotong ke bawah, biarkan tabel scroll jika panjang */
      border-bottom: 1px solid rgba(15,23,42,.06);
    }

    .table thead th{
      background: #f8fafc;
      border-bottom: 2px solid rgba(15,23,42,.10);
      color: #475569;
      font-weight: 700;
      text-transform: uppercase;
      font-size: 0.75rem;
      letter-spacing: 0.5px;
      white-space: nowrap !important;
    }

    .badge-soft{
      background: rgba(13,110,253,.10);
      color: #0b5ed7;
      border: 1px solid rgba(13,110,253,.18);
    }

    /* Cards look consistent and premium */
    .card{
      border-radius: 16px;
      border: 1px solid rgba(15,23,42,.06);
      box-shadow: 0 4px 20px rgba(0,0,0,.03);
      background: #ffffff;
      margin-bottom: 1.5rem;
    }

    /* Professional layout boundaries */
    .container-fluid{
      padding-left: 0 !important;
      padding-right: 0 !important;
      overflow-x: hidden;
    }

    /* Resizable sidebar layout */
    :root {
      --sidebar-width: 200px; /* Lebar sidebar disesuaikan agar proporsional dengan skala UI yang baru (dikecilkan) */
    }
    .container-fluid > .row {
      flex-wrap: nowrap;
      margin-left: 0 !important;
      margin-right: 0 !important;
    }
    #sidebar {
      width: var(--sidebar-width);
      flex-shrink: 0;
      transition: width 0.3s cubic-bezier(0.4, 0, 0.2, 1), transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
      background: white;
    }
    #sidebar.collapsed {
      width: 0 !important;
      overflow: hidden;
      border-right: none !important;
    }
    #sidebar-resizer {
      flex-shrink: 0;
      width: 5px;
      cursor: col-resize;
      background-color: transparent;
      transition: background-color 0.2s ease;
      position: sticky;
      top: 42px; /* Disesuaikan dengan navbar yang mengecil */
      height: calc(100vh - 42px);
      z-index: 10;
    }
    #sidebar-resizer:hover,
    #sidebar-resizer.is-resizing {
      background-color: transparent;
    }
    #main-content {
      flex-grow: 1;
      width: 1px; /* Prevents flexbox from overflowing when content is wide */
      overflow-x: auto;
      transition: margin 0.3s ease;
    }

    /* Mobile Sidebar Styles */
    #sidebar-backdrop {
      position: fixed;
      top: 0;
      left: 0;
      width: 100vw;
      height: 100vh;
      background: rgba(15,23,42,0.4);
      backdrop-filter: blur(4px);
      z-index: 1035;
      opacity: 0;
      visibility: hidden;
      transition: all 0.3s ease;
    }
    #sidebar-backdrop.show {
      opacity: 1;
      visibility: visible;
    }
    
    @media (max-width: 991.98px) {
      #sidebar {
        position: fixed;
        top: 0;
        left: 0;
        height: 100vh;
        z-index: 1040;
        box-shadow: 4px 0 24px rgba(0,0,0,0.1);
        transform: translateX(-100%);
        width: 260px !important; 
      }
      #sidebar.show-mobile {
        transform: translateX(0);
      }
      #sidebar-resizer {
        display: none !important;
      }
    }
    
    .btn-sidebar-toggle {
        border-radius: 12px;
        padding: 0.35rem 0.6rem;
        border: 1px solid rgba(15,23,42,.1);
        background: #fff;
        color: var(--ink);
        transition: all 0.3s ease;
    }
    .btn-sidebar-toggle:hover {
        background: #f8fafc;
        transform: scale(1.05);
    }


  </style>
</head>
<body>
<script>
  // Apply stored sidebar width from localStorage to a CSS variable on page load
  (function() {
    const storedWidth = localStorage.getItem('sidebarWidthV2');
    if (storedWidth) {
      document.documentElement.style.setProperty('--sidebar-width', storedWidth);
    }
  })();
</script>

<?php
  // UI-only: inisial avatar (tanpa ubah logic)
  $current_user = $current_user ?? current_user();
  $nm = trim((string)($current_user['nama'] ?? ''));
  $initial = $nm !== '' ? mb_strtoupper(mb_substr($nm, 0, 1)) : 'U';
?>

<div id="sidebar-backdrop"></div>
<nav class="navbar navbar-expand-lg navbar-wow">
  <div class="container-fluid">
    <div class="d-flex align-items-center gap-2">
      <button class="btn btn-sidebar-toggle me-2 shadow-sm" type="button" id="sidebarToggle" aria-label="Toggle Sidebar">
        <i class="bi bi-list fs-5"></i>
      </button>
      <a class="navbar-brand" href="<?= site_url('k-panel/dashboard') ?>">
        <span>
          KESMAS
          <span class="brand-sub">Health Management</span>
        </span>
      </a>
    </div>

    <div class="d-flex align-items-center gap-2">
      <div class="user-pill">
        <div class="avatar" aria-hidden="true"><?= htmlspecialchars($initial) ?></div>
        <div class="user-meta d-none d-md-block">
          <div class="user-name"><?= htmlspecialchars($current_user['nama'] ?? '') ?></div>
          <div class="user-role">
            <i class="bi bi-person-badge me-1" aria-hidden="true"></i>
            <?= htmlspecialchars($current_user['role'] ?? '') ?>
          </div>
        </div>
        <a class="btn-logout" href="<?= site_url('kesmas/auth/logout') ?>" title="Keluar">
          <i class="bi bi-box-arrow-right me-1" aria-hidden="true"></i><span>Logout</span>
        </a>
      </div>
    </div>
  </div>
</nav>

<div class="container-fluid">
  <div class="row">

<script>
document.addEventListener("DOMContentLoaded", function() {
    var toggleBtn = document.getElementById('sidebarToggle');
    var sidebar = document.getElementById('sidebar');
    var backdrop = document.getElementById('sidebar-backdrop');
    var mainContent = document.getElementById('main-content');
    
    // Sesuaikan padding main-content untuk mobile
    if(mainContent && window.innerWidth < 768) {
        mainContent.classList.remove('p-4');
        mainContent.classList.add('p-2');
    }

    if(toggleBtn && sidebar) {
        function toggleMobileSidebar() {
            var isShowing = sidebar.classList.toggle('show-mobile');
            if (backdrop) {
                if (isShowing) backdrop.classList.add('show');
                else backdrop.classList.remove('show');
            }
        }

        toggleBtn.addEventListener('click', function(e) {
            e.preventDefault();
            if(window.innerWidth <= 991.98) {
                // Mobile
                toggleMobileSidebar();
            } else {
                // Desktop
                sidebar.classList.toggle('collapsed');
                try {
                  localStorage.setItem('sidebar_collapsed', sidebar.classList.contains('collapsed') ? '1' : '0');
                } catch(err){}
            }
        });

        if (backdrop) {
            backdrop.addEventListener('click', toggleMobileSidebar);
        }

        // Restore desktop preference
        if(window.innerWidth > 991.98) {
            try {
              if (localStorage.getItem('sidebar_collapsed') === '1') {
                  sidebar.classList.add('collapsed');
              }
            } catch(err){}
        }
    }
});
</script>

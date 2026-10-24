<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title><?= $title ?? 'UPTD LABKES | E-Labkes' ?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap 5 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

  <!-- DataTables Bootstrap 5 CSS -->
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">
  <!-- Select2 CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">

  <link rel="icon" href="<?= base_url('assets/img/logolabkes.png') ?>" type="image/png">
  
  <style>
    body { font-family: 'Inter', sans-serif; background-color: #f8f9fe; overflow-x: hidden; }
    .wrapper { display: flex; width: 100%; align-items: stretch; min-height: 100vh; }
    
    /* Sidebar */
    #sidebar { min-width: 260px; max-width: 260px; background: #ffffff; transition: all 0.3s; z-index: 1040; border-right: 1px solid #e2e8f0; }
    #sidebar.collapsed { margin-left: -260px; }
    
    .nav-link { color: #4a5568; font-weight: 500; border-radius: 8px; margin: 0.2rem 0.8rem; padding: 0.6rem 1rem; transition: all 0.2s; display: flex; align-items: center; }
    .nav-link:hover { background-color: #f1f5f9; color: #2d3748; }
    .nav-link.active { background-color: #d1f2eb !important; color: #0d3b30 !important; font-weight: 600; }
    .nav-link i.nav-icon { width: 25px; text-align: center; margin-right: 10px; font-size: 1.1rem; }
    .nav-link .arrow-icon { margin-left: auto; transition: transform 0.3s; font-size: 0.8rem; }
    .nav-link[aria-expanded="true"] .arrow-icon { transform: rotate(180deg); }
    
    .nav-header { font-size: 0.75rem; font-weight: 700; color: #a0aec0; text-transform: uppercase; padding: 1rem 1.2rem 0.5rem 1.2rem; margin-top: 5px; }
    
    /* Content Wrapper */
    #content-wrapper { width: 100%; display: flex; flex-direction: column; transition: all 0.3s; }
    
    /* Navbar */
    .top-navbar { background: linear-gradient(to right, #72dfbe, #5bc4cb); border-bottom: none; box-shadow: 0 2px 10px rgba(0,0,0,0.05); z-index: 1030; }
    
    @media (max-width: 768px) {
        #sidebar { margin-left: -260px; position: fixed; height: 100%; }
        #sidebar.active { margin-left: 0; box-shadow: 5px 0 15px rgba(0,0,0,0.05); }
        .overlay { display: none; position: fixed; width: 100vw; height: 100vh; background: rgba(0,0,0,0.5); z-index: 1035; opacity: 0; transition: all 0.3s; }
        .overlay.active { display: block; opacity: 1; }
    }
  </style>
</head>
<body>
<div class="wrapper">
  <!-- Overlay for mobile sidebar -->
  <div class="overlay" id="sidebarOverlay"></div>
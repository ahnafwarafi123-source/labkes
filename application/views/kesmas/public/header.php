<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="color-scheme" content="light">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= isset($title) ? htmlspecialchars($title) : 'KESMASNEW - Public' ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>


  <style>
    :root{
      --ink:#0b1220;
      --muted:#667085;
      --bg:
        radial-gradient(900px 420px at 14% 0%, rgba(13,110,253,.12), transparent 62%),
        radial-gradient(900px 420px at 92% 10%, rgba(34,197,94,.08), transparent 60%),
        linear-gradient(180deg, #f7f8fc 0%, #eef2f7 100%);
      --glass: rgba(255,255,255,.85);
      --shadow: 0 10px 40px rgba(2,6,23,.08);
      --shadow2: 0 8px 24px rgba(2,6,23,.06);
      --brand: #0d6efd;
      --brand2: #0a58ca;
    }

    body{
      background: var(--bg);
      color: var(--ink);
      font-family: "Inter", system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
    }

    .navbar-wow{
      position: sticky;
      top: 0;
      z-index: 1030;
      border-bottom: 1px solid rgba(15,23,42,.08);
      background: #ffffff;
      box-shadow: var(--shadow);
    }

    .brand-badge{
      width: 42px;
      height: 42px;
      border-radius: 14px;
      background: rgba(13,110,253,.1);
      border: 1px solid rgba(13,110,253,.2);
      display:flex;
      align-items:center;
      justify-content:center;
      box-shadow: 0 8px 24px rgba(2,6,23,.15);
      transition: transform 0.3s ease;
    }
    .brand-badge i{ font-size: 1.2rem; color: var(--brand); }

    .navbar-brand{
      font-weight: 900;
      letter-spacing: .2px;
      display:flex;
      align-items:center;
      gap:.6rem;
      color: var(--ink) !important;
      transition: opacity 0.3s ease;
    }
    .navbar-brand:hover .brand-badge { transform: scale(1.05) rotate(-5deg); }
    .navbar-brand:hover { opacity: 0.95; }

    .brand-sub{
      display:block;
      font-size: .78rem;
      opacity: .88;
      font-weight: 600;
      letter-spacing: .3px;
    }

    .main-content {
      flex: 1;
      padding-top: 2rem;
      padding-bottom: 2rem;
    }

    /* Global Animations */
    @keyframes fadeInUp {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-up { animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; opacity: 0; }
    .delay-1 { animation-delay: 0.1s; }
    .delay-2 { animation-delay: 0.2s; }
    .delay-3 { animation-delay: 0.3s; }
    .delay-4 { animation-delay: 0.4s; }

    .hover-bg-glass { transition: all 0.3s ease; border: 1px solid transparent; }
    .hover-bg-glass:hover { background: rgba(15,23,42,0.05); border: 1px solid rgba(15,23,42,0.05); transform: translateY(-1px); }
    .navbar-toggler:focus { box-shadow: none; }
    @media (max-width: 575.98px){
      .brand-sub{ display:none; }
    }
  </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-wow py-2 py-lg-3">
  <div class="container-fluid">
    <a class="navbar-brand" href="<?= site_url('kesmas') ?>">
      <span class="brand-badge" aria-hidden="true"><i class="bi bi-shield-check"></i></span>
      <span>
        KESMASNEW
        <span class="brand-sub">Layanan Pendaftaran Sampel</span>
      </span>
    </a>

    <button class="navbar-toggler border-0 shadow-none text-dark px-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <i class="bi bi-list" style="font-size: 2rem;"></i>
    </button>

    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto gap-2 align-items-lg-center mt-3 mt-lg-0">
        <li class="nav-item">
          <a class="nav-link fw-semibold px-3 py-2 rounded-pill hover-bg-glass <?= current_url() == site_url() || current_url() == site_url('kesmas') ? 'bg-primary bg-opacity-10 text-primary border-primary border-opacity-25' : 'text-dark' ?>" href="<?= site_url('kesmas') ?>"><i class="bi bi-house-door me-1"></i>Beranda</a>
        </li>
        <li class="nav-item">
          <?php if (is_logged_in()): ?>
            <span class="nav-link fw-semibold text-dark px-3 py-2"><i class="bi bi-person-circle me-1"></i><?= html_escape(current_user()['nama']) ?></span>
            <a class="btn btn-sm btn-primary rounded-pill px-4 py-2 fw-semibold shadow-sm" href="<?= site_url('dashboard') ?>"><i class="bi bi-speedometer2 me-1"></i>Dashboard</a>
          <?php else: ?>
            <a class="btn btn-sm btn-primary rounded-pill px-4 py-2 fw-semibold shadow-sm" href="<?= site_url('login') ?>"><i class="bi bi-box-arrow-in-right me-1"></i>Masuk</a>
          <?php endif; ?>
        </li>
      </ul>
    </div>
  </div>
</nav>

<div class="main-content container-fluid">
    <?php $this->load->view('partials/flash'); ?>

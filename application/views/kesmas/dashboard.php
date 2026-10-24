<?php
/**
 * Dashboard View
 * 
 * @var string $title - Page title
 * @var string $chart_labels - JSON encoded chart labels
 * @var string $chart_totals - JSON encoded chart totals
 * @var string $chart_bg_colors - JSON encoded background colors
 * @var string $chart_border_colors - JSON encoded border colors
 * @var int $total_permintaan - Total permintaan count
 * @var int $total_diterima - Total diterima count
 * @var int $total_pending - Total pending count
 * @var int $total_laporan - Total laporan count
 * @var int $lingkungan_count - Lingkungan sample count
 * @var int $air_minum_count - Air minum sample count
 * @var int $air_bersih_count - Air bersih sample count
 * @var int $makanan_count - Makanan sample count
 * @var array $recent_activities - Recent activities
 * @var array $recent_permintaan - Recent permintaan
 */
?>
<!-- Optional (kalau belum ada di layout/header): Bootstrap Icons -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

<style>
  /* --- Base Variables --- */
  :root{
    --ink:#0b1220;
    --muted:#667085;
    --line: rgba(15,23,42,.12);
    --glass: rgba(255,255,255,.74);
    --glass2: rgba(255,255,255,.58);
    --shadow: 0 28px 80px rgba(2,6,23,.16);
    --shadow2: 0 14px 38px rgba(2,6,23,.10);
    --ring: rgba(13,110,253,.20);
    --brand:#0d6efd;
    --brand2:#0b5ed7;
    --green:#22c55e;
    --amber:#f59e0b;
    --violet:#6366f1;
  }
  
  body {
    background-color: #f8fafc;
  }

  /* HERO - Premium Version */
  .dash-hero-wow{
    position: relative;
    overflow: hidden;
    border-radius: 32px;
    border: 1px solid rgba(255,255,255,0.18);
    box-shadow: var(--shadow);
    background:
      radial-gradient(900px 380px at 12% 10%, rgba(102,126,234,.3), transparent 60%),
      radial-gradient(820px 360px at 92% 20%, rgba(79,172,254,.25), transparent 62%),
      radial-gradient(900px 380px at 55% 110%, rgba(99,102,241,.2), transparent 62%),
      linear-gradient(135deg, rgba(255,255,255,.9), rgba(255,255,255,.7));
    padding: 1.75rem 2rem;
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
  }

  .dash-hero-wow:before{
    content:"";
    position:absolute;
    inset:-2px;
    background:
      linear-gradient(120deg,
        rgba(102,126,234,.0) 0%,
        rgba(102,126,234,.15) 20%,
        rgba(79,172,254,.15) 50%,
        rgba(99,102,241,.12) 80%,
        rgba(102,126,234,.0) 100%);
    filter: blur(24px);
    opacity:.8;
    pointer-events:none;
    animation: heroGlow 4s ease-in-out infinite alternate;
  }

  @keyframes heroGlow {
    0% { opacity: 0.6; }
    100% { opacity: 1; }
  }

  .dash-hero-wow .title{
    position: relative;
    z-index: 1;
    margin: 0;
    font-weight: 950;
    letter-spacing: -.25px;
    font-size: 1.35rem;
    color: var(--ink);
  }
  .dash-hero-wow .sub{
    position: relative;
    z-index: 1;
    color: var(--muted);
    margin-top: .25rem;
    font-size: 0.95rem;
  }

  /* CARDS WOW - Premium Version */
  .wow-card{
    position: relative;
    overflow: hidden;
    border-radius: 24px;
    border: 1px solid rgba(255,255,255,0.18);
    background: rgba(255,255,255,0.25);
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
    box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
  }
  .wow-card:hover{
    transform: translateY(-8px) scale(1.02);
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
    border-color: rgba(102,126,234,0.4);
  }

  /* Premium shimmer effect */
  .wow-card:before{
    content:"";
    position:absolute;
    top:-60%;
    left:-60%;
    width: 220%;
    height: 220%;
    background: linear-gradient(120deg,
      rgba(255,255,255,0) 30%,
      rgba(255,255,255,.4) 50%,
      rgba(255,255,255,0) 70%);
    transform: rotate(18deg);
    opacity: 0;
    transition: opacity .4s ease;
    pointer-events:none;
  }
  .wow-card:hover:before{ opacity: 1; }

  .wow-body{
    padding: 1.25rem;
  }

  .icon-badge{
    width: 48px;
    height: 48px;
    border-radius: 18px;
    display:flex;
    align-items:center;
    justify-content:center;
    box-shadow: 0 12px 28px rgba(2,6,23,.12);
    border: 1px solid rgba(255,255,255,.55);
    background: rgba(255,255,255,.65);
    flex: 0 0 auto;
  }
  .icon-badge i{ font-size: 1.35rem; }

  .k1{ color: var(--brand); }
  .k2{ color: #16a34a; }
  .k3{ color: #d97706; }
  .k4{ color: var(--violet); }

  .wow-title{
    margin: 0;
    font-weight: 900;
    letter-spacing: -.35px;
    color: var(--ink);
    font-size: 2rem;
  }
  .wow-desc{
    margin: 0;
    color: var(--muted);
    font-size: .9rem;
  }

  .list-group-item {
    background: transparent;
    border-color: rgba(15,23,42,.08);
  }

  .activity-icon {
    width: 36px;
    height: 36px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(15,23,42,.05);
    color: var(--muted);
    flex-shrink: 0;
  }

  .activity-time {
    font-size: 0.8rem;
    color: var(--muted);
    white-space: nowrap;
  }

  /* subtle entrance */
  @keyframes floatIn {
    from { transform: translateY(10px); opacity: 0; }
    to { transform: translateY(0); opacity: 1; }
  }
  .fade-in-up{ animation: floatIn .45s ease both; }

  @media (max-width: 767.98px){
    .dash-hero-wow{ padding: 1.05rem 1.05rem; border-radius: 22px; }
    .wow-card{ border-radius: 18px; }
    .wow-title { font-size: 1.5rem; }
  }
</style>

<!-- HERO -->
<div class="dash-hero-wow mb-3 fade-in">
  <div class="d-flex align-items-start justify-content-between gap-3 flex-wrap position-relative" style="z-index:1;">
    <div>
      <h3 class="title">Dashboard</h3>
      <div class="sub">Selamat datang di <strong>KESMAS</strong> • Portal Manajemen Kesehatan Masyarakat</div>
    </div>

    <div class="d-flex align-items-center gap-2 flex-wrap mt-2 mt-md-0">
      <a class="btn btn-sm btn-primary" href="<?= site_url('k-panel/form_permintaan_kesmas/create') ?>">
        <i class="bi bi-plus-lg me-1" aria-hidden="true"></i> Pendaftaran Baru
      </a>
      <a class="btn btn-sm btn-outline-secondary" href="<?= site_url('k-panel/laporan_uji_kesmas') ?>">
        <i class="bi bi-file-earmark-text me-1" aria-hidden="true"></i> Laporan
      </a>
    </div>
  </div>
</div>

<!-- STATISTICS ROW -->
<div class="row g-3">
  <!-- Total Permintaan -->
  <div class="col-md-6 col-lg-3">
    <a href="<?= site_url('k-panel/form_permintaan_kesmas') ?>" class="text-decoration-none">
      <div class="card wow-card fade-in-up" style="animation-delay: 0.1s;">
        <div class="wow-body">
          <div class="d-flex align-items-start gap-3">
            <div class="icon-badge" style="background: linear-gradient(135deg, rgba(13,110,253,0.15) 0%, rgba(13,110,253,0.08) 100%); border-color: rgba(13,110,253,0.3);">
              <i class="bi bi-file-earmark-text k1" aria-hidden="true"></i>
            </div>
            <div class="flex-grow-1">
              <div class="wow-desc">Total Permintaan</div>
              <div class="wow-title"><?= $total_permintaan ?></div>
            </div>
          </div>
        </div>
      </div>
    </a>
  </div>

  <!-- Diterima -->
  <div class="col-md-6 col-lg-3">
    <a href="<?= site_url('k-panel/form_permintaan_kesmas') ?>" class="text-decoration-none">
      <div class="card wow-card fade-in-up" style="animation-delay: 0.2s;">
        <div class="wow-body">
          <div class="d-flex align-items-start gap-3">
            <div class="icon-badge" style="background: linear-gradient(135deg, rgba(34,197,94,0.15) 0%, rgba(34,197,94,0.08) 100%); border-color: rgba(34,197,94,0.3);">
              <i class="bi bi-check-circle k2" aria-hidden="true"></i>
            </div>
            <div class="flex-grow-1">
              <div class="wow-desc">Sudah Diterima</div>
              <div class="wow-title"><?= $total_diterima ?></div>
            </div>
          </div>
        </div>
      </div>
    </a>
  </div>

  <!-- Pending -->
  <div class="col-md-6 col-lg-3">
    <a href="<?= site_url('k-panel/permintaan_sample') ?>" class="text-decoration-none">
      <div class="card wow-card fade-in-up" style="animation-delay: 0.3s;">
        <div class="wow-body">
          <div class="d-flex align-items-start gap-3">
            <div class="icon-badge" style="background: linear-gradient(135deg, rgba(245,158,11,0.15) 0%, rgba(245,158,11,0.08) 100%); border-color: rgba(245,158,11,0.3);">
              <i class="bi bi-hourglass-split k3" aria-hidden="true"></i>
            </div>
            <div class="flex-grow-1">
              <div class="wow-desc">Menunggu</div>
              <div class="wow-title"><?= $total_pending ?></div>
            </div>
          </div>
        </div>
      </div>
    </a>
  </div>

  <!-- Laporan Selesai -->
  <div class="col-md-6 col-lg-3">
    <a href="<?= site_url('k-panel/laporan_uji_kesmas') ?>" class="text-decoration-none">
      <div class="card wow-card fade-in-up" style="animation-delay: 0.4s;">
        <div class="wow-body">
          <div class="d-flex align-items-start gap-3">
            <div class="icon-badge" style="background: linear-gradient(135deg, rgba(99,102,241,0.15) 0%, rgba(99,102,241,0.08) 100%); border-color: rgba(99,102,241,0.3);">
              <i class="bi bi-file-pdf k4" aria-hidden="true"></i>
            </div>
            <div class="flex-grow-1">
              <div class="wow-desc">Laporan Selesai</div>
              <div class="wow-title"><?= $total_laporan ?></div>
            </div>
          </div>
        </div>
      </div>
    </a>
  </div>
</div>

<!-- CHART & RECENT ACTIVITY ROW -->
<div class="row mt-4 g-3">
  <!-- Chart -->
  <div class="col-lg-7">
    <div class="card wow-card fade-in-up" style="animation-delay: 0.5s;">
      <div class="wow-body">
        <h5 class="fw-bold mb-3"><i class="bi bi-pie-chart-fill text-primary me-2"></i>Komposisi Jenis Sampel</h5>
        <div style="height: 350px;">
          <canvas id="jenisSampelChart"></canvas>
        </div>
      </div>
    </div>
  </div>

  <!-- Recent Activities & Pendaftaran -->
  <div class="col-lg-5">
    <!-- Pendaftaran Terbaru -->
    <div class="card wow-card fade-in-up" style="animation-delay: 0.6s;">
      <div class="wow-body">
        <h6 class="fw-bold mb-3"><i class="bi bi-journal-plus text-success me-2"></i>Pendaftaran Terbaru</h6>
        <ul class="list-group list-group-flush">
          <?php if (empty($recent_permintaan)): ?>
            <li class="list-group-item text-muted text-center">Belum ada pendaftaran.</li>
          <?php else: ?>
            <?php foreach($recent_permintaan as $p): ?>
              <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                <div>
                  <a href="<?= site_url('k-panel/form_permintaan_kesmas/detail/'.$p['id_permintaan']) ?>" class="fw-bold text-decoration-none"><?= htmlspecialchars($p['nama_sampel'] ?? 'Tanpa Nama') ?></a>
                  <div class="small text-muted">
                    oleh <?= htmlspecialchars($p['user_nama'] ?? 'Publik') ?> &bull; <?= htmlspecialchars($p['no_registrasi'] ?? 'Belum ada No. Reg') ?>
                  </div>
                </div>
                <div class="activity-time"><?= time_ago($p['created_at']) ?></div>
              </li>
            <?php endforeach; ?>
          <?php endif; ?>
        </ul>
      </div>
    </div>

    <!-- Aktivitas Terbaru -->
    <div class="card wow-card fade-in-up mt-3" style="animation-delay: 0.7s;">
      <div class="wow-body">
        <h6 class="fw-bold mb-3"><i class="bi bi-activity text-danger me-2"></i>Aktivitas Terbaru</h6>
        <ul class="list-group list-group-flush">
          <?php if (empty($recent_activities)): ?>
            <li class="list-group-item text-muted text-center">Belum ada aktivitas.</li>
          <?php else: ?>
            <?php foreach($recent_activities as $act): ?>
              <li class="list-group-item d-flex align-items-center px-0">
                <div class="activity-icon me-3"><i class="bi bi-bell"></i></div>
                <div class="flex-grow-1">
                  <div class="small"><?= htmlspecialchars($act['description']) ?></div>
                  <div class="small text-muted">
                    <?= htmlspecialchars($act['user_nama'] ?? 'Sistem') ?> &bull; <?= time_ago($act['created_at']) ?>
                  </div>
                </div>
              </li>
            <?php endforeach; ?>
          <?php endif; ?>
        </ul>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    const ctx = document.getElementById('jenisSampelChart');
    if (ctx) {
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: <?= $chart_labels ?>,
                datasets: [{
                    label: 'Jumlah Sampel',
                    data: <?= $chart_totals ?>,
                    backgroundColor: <?= $chart_bg_colors ?>,
                    borderColor: <?= $chart_border_colors ?>,
                    borderWidth: 2,
                    hoverOffset: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '65%',
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 20,
                            font: {
                                weight: 'bold'
                            }
                        }
                    }
                }
            }
        });
    }
});
</script>

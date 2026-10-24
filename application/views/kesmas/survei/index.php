<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

<style>
  :root {
    --brand: #0d6efd;
    --brand2: #0b5ed7;
    --ink: #0b1220;
    --muted: #667085;
    --success: #22c55e;
    --warning: #f59e0b;
    --danger: #ef4444;
    --shadow2: 0 12px 30px rgba(2,6,23,.10);
  }

  /* ===== HERO ===== */
  .page-hero {
    border-radius: 26px;
    border: 1px solid rgba(15,23,42,.10);
    background:
      radial-gradient(900px 340px at 12% 10%, rgba(13,110,253,.18), transparent 60%),
      radial-gradient(900px 340px at 90% 20%, rgba(34,197,94,.10), transparent 60%),
      linear-gradient(180deg, rgba(255,255,255,.95), rgba(255,255,255,.75));
    box-shadow: var(--shadow2);
    padding: 1.4rem 1.6rem;
    margin-bottom: 1.5rem;
  }
  .page-hero h3 { font-weight: 950; letter-spacing: -.5px; color: var(--ink); margin: 0; }
  .page-hero .sub { color: var(--muted); margin-top: .25rem; font-size: .95rem; }

  /* ===== STAT CARDS ===== */
  .stat-card {
    border-radius: 22px;
    border: 1px solid rgba(15,23,42,.09);
    background: rgba(255,255,255,.88);
    box-shadow: var(--shadow2);
    padding: 1.3rem 1.4rem;
    display: flex;
    align-items: center;
    gap: 1rem;
    height: 100%;
  }
  .stat-icon {
    width: 54px; height: 54px;
    border-radius: 16px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.5rem;
    flex-shrink: 0;
  }
  .stat-label { font-size: .8rem; font-weight: 700; color: var(--muted); text-transform: uppercase; letter-spacing: .5px; }
  .stat-value { font-size: 1.75rem; font-weight: 950; color: var(--ink); line-height: 1.1; }
  .stat-sub { font-size: .82rem; font-weight: 600; color: var(--muted); margin-top: .15rem; }

  /* ===== MUTU BADGE ===== */
  .badge-mutu {
    display: inline-block;
    padding: .35rem .85rem;
    border-radius: 20px;
    font-size: .78rem;
    font-weight: 800;
    letter-spacing: .3px;
  }
  .badge-mutu.sangat-baik { background: rgba(34,197,94,.15); color: #15803d; }
  .badge-mutu.baik        { background: rgba(13,110,253,.12); color: #1d4ed8; }
  .badge-mutu.kurang-baik { background: rgba(245,158,11,.15); color: #b45309; }
  .badge-mutu.tidak-baik  { background: rgba(239,68,68,.13); color: #b91c1c; }

  /* ===== IKM SCALE ===== */
  .ikm-scale {
    height: 10px;
    border-radius: 6px;
    background: linear-gradient(90deg, #ef4444 0%, #f59e0b 30%, #0d6efd 65%, #22c55e 100%);
    position: relative;
    margin: .5rem 0;
  }
  .ikm-marker {
    position: absolute;
    top: 50%;
    width: 16px; height: 16px;
    border-radius: 50%;
    background: #fff;
    border: 3px solid var(--ink);
    transform: translate(-50%, -50%);
    box-shadow: 0 0 0 3px rgba(0,0,0,.12);
  }

  /* ===== TABLE ===== */
  .table-card {
    border-radius: 22px;
    border: 1px solid rgba(15,23,42,.09);
    background: rgba(255,255,255,.88);
    box-shadow: var(--shadow2);
    overflow: hidden;
  }
  .table-card .card-header {
    background: rgba(255,255,255,.85);
    border-bottom: 1px solid rgba(15,23,42,.08);
    padding: 1rem 1.3rem;
    display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: .75rem;
  }
  .table-card .card-header-title {
    font-weight: 950; font-size: 1rem; color: var(--ink);
    display: flex; align-items: center; gap: .5rem;
  }
  .table-card .card-header-title i { color: var(--brand); }

  table.survei-table th {
    font-size: .75rem;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: .5px;
    color: var(--muted);
    border-bottom: 2px solid rgba(15,23,42,.08);
    padding: .75rem 1rem;
    white-space: nowrap;
    background: rgba(248,250,252,.85);
  }
  table.survei-table td {
    padding: .85rem 1rem;
    vertical-align: middle;
    border-bottom: 1px solid rgba(15,23,42,.06);
    font-size: .9rem;
    color: var(--ink);
  }
  table.survei-table tr:last-child td { border-bottom: none; }
  table.survei-table tr:hover td { background: rgba(13,110,253,.03); }

  /* Skor bintang */
  .stars { color: #f59e0b; letter-spacing: 2px; font-size: .85rem; }
  .stars .empty { color: #e2e8f0; }

  /* Skor badge */
  .skor-badge {
    display: inline-flex; align-items: center; gap: .4rem;
    border-radius: 20px; padding: .3rem .75rem;
    font-weight: 900; font-size: .85rem;
  }
  .skor-badge.tinggi  { background: rgba(34,197,94,.13); color: #15803d; }
  .skor-badge.sedang  { background: rgba(245,158,11,.13); color: #b45309; }
  .skor-badge.rendah  { background: rgba(239,68,68,.13); color: #b91c1c; }

  /* Action button */
  .btn-act {
    border-radius: 12px;
    font-weight: 800;
    padding: .4rem .75rem;
    font-size: .82rem;
    display: inline-flex; align-items: center; gap: .3rem;
    border: 1px solid transparent;
    transition: .15s;
  }
  .btn-detail { background: rgba(13,110,253,.1); color: var(--brand); border-color: rgba(13,110,253,.18); }
  .btn-detail:hover { background: var(--brand); color: #fff; }
  .btn-edit   { background: rgba(245,158,11,.1); color: #b45309; border-color: rgba(245,158,11,.18); }
  .btn-edit:hover { background: #f59e0b; color: #fff; }
  .btn-hapus  { background: rgba(239,68,68,.1); color: #b91c1c; border-color: rgba(239,68,68,.18); }
  .btn-hapus:hover { background: #ef4444; color: #fff; }

  /* Empty state */
  .empty-state { text-align: center; padding: 3.5rem 1rem; }
  .empty-state .icon { font-size: 3.5rem; color: #cbd5e1; margin-bottom: 1rem; }
  .empty-state h5 { color: var(--muted); font-weight: 700; }

  /* Alert */
  .alert-flash {
    border-radius: 16px;
    font-weight: 700;
    border: none;
    margin-bottom: 1.2rem;
  }

  @keyframes fadeInUp {
    from { opacity: 0; transform: translateY(18px); }
    to   { opacity: 1; transform: translateY(0); }
  }
  .fade-up { animation: fadeInUp .45s ease-out; }

  /* Navigasi laporan */
  .btn-laporan {
    border-radius: 14px;
    font-weight: 800;
    padding: .5rem 1rem;
    font-size: .88rem;
    display: inline-flex; align-items: center; gap: .4rem;
    border: 1.5px solid rgba(13,110,253,.25);
    background: rgba(13,110,253,.07);
    color: var(--brand);
    transition: .15s;
  }
  .btn-laporan:hover { background: var(--brand); color: #fff; border-color: var(--brand); }
</style>

<div class="container-fluid fade-up" style="padding: 0;">

  <!-- HERO -->
  <div class="page-hero">
    <div class="d-flex align-items-start justify-content-between gap-3 flex-wrap">
      <div>
        <h3><i class="bi bi-emoji-smile me-2" style="color:var(--brand);"></i>Survei Kepuasan Masyarakat</h3>
        <div class="sub">Monitor dan kelola data survei kepuasan pelanggan laboratorium</div>
      </div>
      <div class="d-flex gap-2 flex-wrap">
        <a href="<?= site_url('k-panel/survei/laporan') ?>" class="btn-laporan">
          <i class="bi bi-bar-chart-line-fill"></i> Laporan SKM
        </a>
        <a href="<?= site_url('k-panel/survei/laporan_by_date') ?>" class="btn-laporan">
          <i class="bi bi-calendar-range"></i> Laporan per Tanggal
        </a>
      </div>
    </div>
  </div>

  <!-- STATISTIK RINGKASAN -->
  <?php
    $ikm = $stats['ikm_score'];
    if ($ikm >= 88.31)      { $mutu_label = 'SANGAT BAIK'; $mutu_cls = 'sangat-baik'; $mutu_icon = 'bi-stars'; $mutu_color = '#15803d'; }
    elseif ($ikm >= 76.61)  { $mutu_label = 'BAIK';        $mutu_cls = 'baik';        $mutu_icon = 'bi-hand-thumbs-up-fill'; $mutu_color = '#1d4ed8'; }
    elseif ($ikm >= 65.00)  { $mutu_label = 'KURANG BAIK'; $mutu_cls = 'kurang-baik'; $mutu_icon = 'bi-exclamation-circle-fill'; $mutu_color = '#b45309'; }
    else                    { $mutu_label = 'TIDAK BAIK';  $mutu_cls = 'tidak-baik';  $mutu_icon = 'bi-x-circle-fill'; $mutu_color = '#b91c1c'; }
    $ikm_pct = min(100, max(0, (($ikm - 25) / 75) * 100));
  ?>

  <div class="row g-3 mb-4">
    <!-- Total Responden -->
    <div class="col-sm-6 col-lg-3">
      <div class="stat-card">
        <div class="stat-icon" style="background:rgba(13,110,253,.10); color:var(--brand);">
          <i class="bi bi-people-fill"></i>
        </div>
        <div>
          <div class="stat-label">Total Responden</div>
          <div class="stat-value"><?= number_format($stats['total_survei']) ?></div>
          <div class="stat-sub">Survei terkumpul</div>
        </div>
      </div>
    </div>

    <!-- IKM Score -->
    <div class="col-sm-6 col-lg-3">
      <div class="stat-card">
        <div class="stat-icon" style="background:rgba(34,197,94,.10); color:var(--success);">
          <i class="bi bi-graph-up-arrow"></i>
        </div>
        <div style="flex:1;">
          <div class="stat-label">Skor IKM</div>
          <div class="stat-value"><?= number_format($ikm, 2) ?></div>
          <div class="ikm-scale"><div class="ikm-marker" style="left:<?= $ikm_pct ?>%;"></div></div>
          <div class="stat-sub">Skala 0 – 100</div>
        </div>
      </div>
    </div>

    <!-- Mutu Pelayanan -->
    <div class="col-sm-6 col-lg-3">
      <div class="stat-card">
        <div class="stat-icon" style="background:rgba(<?= $mutu_cls === 'sangat-baik' ? '34,197,94' : ($mutu_cls === 'baik' ? '13,110,253' : ($mutu_cls === 'kurang-baik' ? '245,158,11' : '239,68,68')) ?>,.12); color:<?= $mutu_color ?>;">
          <i class="bi <?= $mutu_icon ?>"></i>
        </div>
        <div>
          <div class="stat-label">Mutu Pelayanan</div>
          <div class="stat-value" style="font-size:1.2rem; color:<?= $mutu_color ?>;"><?= $mutu_label ?></div>
          <div class="stat-sub">Berdasarkan nilai IKM</div>
        </div>
      </div>
    </div>

    <!-- Rata-rata per Unsur Terendah -->
    <div class="col-sm-6 col-lg-3">
      <?php
        $avg_unsur = [];
        for ($i = 1; $i <= 9; $i++) $avg_unsur[$i] = $stats['avg_q'.$i] ?? 0;
        $min_unsur_idx = array_keys($avg_unsur, min($avg_unsur))[0];
        $unsur_labels = [
          1=>'Persyaratan', 2=>'Prosedur', 3=>'Waktu', 4=>'Biaya', 5=>'Produk',
          6=>'Kompetensi', 7=>'Perilaku', 8=>'Sarana', 9=>'Pengaduan'
        ];
        $min_val = min($avg_unsur);
      ?>
      <div class="stat-card">
        <div class="stat-icon" style="background:rgba(245,158,11,.10); color:var(--warning);">
          <i class="bi bi-exclamation-diamond-fill"></i>
        </div>
        <div>
          <div class="stat-label">Unsur Terendah</div>
          <div class="stat-value" style="font-size:1.25rem;"><?= number_format($min_val, 2) ?>/4</div>
          <div class="stat-sub"><i class="bi bi-arrow-right me-1"></i><?= $unsur_labels[$min_unsur_idx] ?> (U<?= $min_unsur_idx ?>)</div>
        </div>
      </div>
    </div>
  </div>

  <!-- RATA-RATA PER UNSUR -->
  <div class="table-card mb-4">
    <div class="card-header">
      <div class="card-header-title">
        <i class="bi bi-bar-chart-steps"></i> Rata-rata Nilai Per Unsur Pelayanan
      </div>
      <small class="text-muted">Skala penilaian: 1 (Tidak Baik) – 4 (Sangat Baik)</small>
    </div>
    <div class="p-3">
      <?php
        $q_names = [
          1=>'Kesesuaian Persyaratan',
          2=>'Kemudahan Prosedur',
          3=>'Kecepatan Waktu',
          4=>'Kewajaran Biaya/Tarif',
          5=>'Kesesuaian Produk',
          6=>'Kompetensi Petugas',
          7=>'Perilaku Petugas',
          8=>'Kualitas Sarana & Prasarana',
          9=>'Penanganan Pengaduan',
        ];
        for ($i = 1; $i <= 9; $i++):
          $v = round($avg_unsur[$i], 2);
          $pct = ($v / 4) * 100;
          if ($v >= 3.5)      $bar_color = 'var(--success)';
          elseif ($v >= 2.5)  $bar_color = '#0d6efd';
          elseif ($v >= 1.5)  $bar_color = 'var(--warning)';
          else                $bar_color = 'var(--danger)';
      ?>
      <div class="d-flex align-items-center gap-3 mb-2">
        <div style="width:30px;font-size:.78rem;font-weight:800;color:var(--muted);flex-shrink:0;">U<?= $i ?></div>
        <div style="flex:1;">
          <div class="d-flex justify-content-between mb-1">
            <span style="font-size:.82rem;font-weight:700;color:var(--ink);"><?= $q_names[$i] ?></span>
            <span style="font-size:.82rem;font-weight:900;color:<?= $bar_color ?>;"><?= number_format($v,2) ?></span>
          </div>
          <div style="height:10px;border-radius:8px;background:rgba(15,23,42,.07);overflow:hidden;">
            <div style="height:100%;width:<?= $pct ?>%;background:<?= $bar_color ?>;border-radius:8px;transition:.4s;"></div>
          </div>
        </div>
      </div>
      <?php endfor; ?>
    </div>
  </div>

  <!-- DAFTAR SURVEI -->
  <div class="table-card">
    <div class="card-header">
      <div class="card-header-title">
        <i class="bi bi-list-ul"></i> Daftar Survei Masuk
        <span class="badge rounded-pill" style="background:rgba(13,110,253,.12);color:var(--brand);font-weight:900;font-size:.78rem;"><?= count($survei) ?> data</span>
      </div>
    </div>
    <div class="table-responsive">
      <?php if (empty($survei)): ?>
      <div class="empty-state">
        <div class="icon"><i class="bi bi-inbox"></i></div>
        <h5>Belum ada data survei</h5>
        <p class="text-muted">Survei dari masyarakat akan muncul di sini secara otomatis.</p>
      </div>
      <?php else: ?>
      <table class="table survei-table mb-0">
        <thead>
          <tr>
            <th style="width:50px">#</th>
            <th>Responden</th>
            <th>Layanan Dinilai</th>
            <th>Penilaian per Unsur</th>
            <th>Rata-rata Skor</th>
            <th>Komentar/Saran</th>
            <th>Tgl Survei</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($survei as $idx => $s):
            $vals = [];
            for ($i = 1; $i <= 9; $i++) $vals[] = (int)$s['q'.$i];
            $total = array_sum($vals);
            $avg_s = $total / 9;
            $skor_cls = $avg_s >= 3.5 ? 'tinggi' : ($avg_s >= 2.5 ? 'sedang' : 'rendah');
            $skor_icon = $avg_s >= 3.5 ? 'bi-emoji-smile-fill' : ($avg_s >= 2.5 ? 'bi-emoji-neutral-fill' : 'bi-emoji-frown-fill');
            // Stars
            $stars_full = floor($avg_s);
            $stars_empty = 4 - $stars_full;
          ?>
          <tr>
            <!-- No -->
            <td><span style="color:var(--muted);font-weight:700;"><?= $idx + 1 ?></span></td>

            <!-- Responden -->
            <td>
              <div class="fw-bold" style="font-size:.88rem;">
                <?= $s['usia'] ? $s['usia'].' Thn' : '-' ?>
                · <?= $s['jenis_kelamin'] === 'L' ? 'L' : ($s['jenis_kelamin'] === 'P' ? 'P' : '-') ?>
              </div>
              <div class="text-muted" style="font-size:.78rem;"><?= htmlspecialchars($s['pendidikan'] ?: '-') ?></div>
              <div class="text-muted" style="font-size:.78rem;"><?= htmlspecialchars($s['pekerjaan'] ?: '-') ?></div>
            </td>

            <!-- Layanan -->
            <td>
              <span class="badge rounded-pill" style="background:rgba(99,102,241,.1);color:#4f46e5;font-size:.78rem;font-weight:800;padding:.35rem .75rem;">
                <?= htmlspecialchars($s['jenis_layanan'] ?: '-') ?>
              </span>
            </td>

            <!-- Mini bar per unsur -->
            <td style="min-width:160px;">
              <div class="d-flex gap-1 flex-wrap">
                <?php for ($i = 1; $i <= 9; $i++):
                  $v = (int)$s['q'.$i];
                  $c = $v >= 4 ? '#22c55e' : ($v >= 3 ? '#0d6efd' : ($v >= 2 ? '#f59e0b' : '#ef4444'));
                  $q_short = ['','Syarat','Prsd','Waktu','Biaya','Prod','Komp','Prlt','Srn','Pgdn'];
                ?>
                <span title="U<?= $i ?> - <?= $q_short[$i] ?>: <?= $v ?>/4"
                      style="display:inline-flex;align-items:center;justify-content:center;width:22px;height:22px;border-radius:6px;font-size:.7rem;font-weight:900;background:<?= $c ?>1a;color:<?= $c ?>;border:1px solid <?= $c ?>33;">
                  <?= $v ?>
                </span>
                <?php endfor; ?>
              </div>
            </td>

            <!-- Rata-rata -->
            <td>
              <span class="skor-badge <?= $skor_cls ?>">
                <i class="bi <?= $skor_icon ?>"></i>
                <?= number_format($avg_s, 2) ?>/4
              </span>
            </td>

            <!-- Komentar -->
            <td style="max-width:180px;">
              <?php $komentar = trim($s['komentar_saran'] ?? ''); ?>
              <?php if ($komentar): ?>
              <span class="text-muted fst-italic" style="font-size:.82rem;">
                "<?= strlen($komentar) > 60 ? htmlspecialchars(substr($komentar, 0, 60)) . '...' : htmlspecialchars($komentar) ?>"
              </span>
              <?php else: ?>
              <span class="text-muted" style="font-size:.8rem;">— Tidak ada komentar —</span>
              <?php endif; ?>
            </td>

            <!-- Tanggal -->
            <td style="white-space:nowrap;">
              <div style="font-weight:700;font-size:.85rem;"><?= date('d M Y', strtotime($s['tgl_survei'])) ?></div>
              <div class="text-muted" style="font-size:.78rem;"><?= $s['jam_survei'] ?: date('H:i', strtotime($s['tgl_survei'])) ?></div>
            </td>

            <!-- Aksi -->
            <td style="white-space:nowrap;">
              <div class="d-flex gap-1 flex-wrap">
                <a href="<?= site_url('k-panel/survei/detail/'.$s['id_survei']) ?>" class="btn-act btn-detail" title="Lihat Detail">
                  <i class="bi bi-eye"></i> Detail
                </a>
                <a href="<?= site_url('k-panel/survei/edit/'.$s['id_survei']) ?>" class="btn-act btn-edit" title="Edit Survei">
                  <i class="bi bi-pencil"></i>
                </a>
                <a href="<?= site_url('k-panel/survei/delete/'.$s['id_survei']) ?>"
                   class="btn-act btn-hapus" title="Hapus"
                   onclick="return confirm('Yakin hapus data survei ini?')">
                  <i class="bi bi-trash3"></i>
                </a>
              </div>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
      <?php endif; ?>
    </div>
  </div>

  <!-- KETERANGAN SKALA -->
  <div class="d-flex flex-wrap gap-3 mt-3 px-1">
    <small class="fw-bold text-muted">Keterangan Skor:</small>
    <span style="font-size:.8rem;font-weight:700;color:#b91c1c;"><i class="bi bi-square-fill me-1"></i>1 – Tidak Baik</span>
    <span style="font-size:.8rem;font-weight:700;color:#b45309;"><i class="bi bi-square-fill me-1"></i>2 – Kurang Baik</span>
    <span style="font-size:.8rem;font-weight:700;color:#1d4ed8;"><i class="bi bi-square-fill me-1"></i>3 – Baik</span>
    <span style="font-size:.8rem;font-weight:700;color:#15803d;"><i class="bi bi-square-fill me-1"></i>4 – Sangat Baik</span>
  </div>

</div>

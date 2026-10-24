<?php
/**
 * Permintaan List View
 * 
 * @var string $title - Page title
 * @var array $rows - List of permintaan data
 * @var array $filters - Filter parameters
 */
?>
<?php $this->load->view('kesmas/premium_list_style'); ?>

<!-- HERO HEADER -->
<div class="page-hero mb-4">
  <div class="d-flex align-items-start justify-content-between gap-3 flex-wrap">
    <div>
      <a class="btn btn-outline-secondary btn-sm me-2" href="javascript:history.back();" style="border-radius: 8px; padding: 0.3rem 0.6rem; font-weight: 600; transition: all 0.3s ease;">
        <i class="bi bi-arrow-left"></i> <span class="d-none d-md-inline">Kembali</span>
      </a>
      <h3 style="display:inline-block; margin:0; font-size: 1.8rem;"><?= htmlspecialchars($title) ?></h3>
      <div class="sub">📋 Kelola dan pantau semua permintaan pendaftaran sampel</div>
    </div>
    <?php if ($title !== 'Terima Sample'): ?>
    <a class="btn btn-primary btn-add" href="<?= site_url('k-panel/form_permintaan_kesmas/create') ?>">
      <i class="bi bi-plus-lg me-md-1" aria-hidden="true"></i> <span class="d-none d-md-inline">Tambah Baru</span>
    </a>
    <?php endif; ?>
  </div>
</div>

<!-- FILTER -->
<div class="filter-shell mb-4">
  <form class="row g-2" method="get">
    <div class="col-12 col-md-4">
      <div class="input-group">
        <span class="input-group-text bg-transparent border-0" style="color:#0d6efd; font-weight: 700;"><i class="bi bi-search"></i></span>
        <input class="form-control" name="q" placeholder="🔍 Cari no. reg / sampel / jenis / pengirim..."
               value="<?= htmlspecialchars($filters['q'] ?? '') ?>">
      </div>
    </div>
    <div class="col-6 col-md-2">
      <label class="form-label small fw-bold d-none d-md-block" style="color: #64748b; margin-bottom: 0.3rem;">Dari</label>
      <input class="form-control" type="date" name="dari" value="<?= htmlspecialchars($filters['dari'] ?? '') ?>">
    </div>
    <div class="col-6 col-md-2">
      <label class="form-label small fw-bold d-none d-md-block" style="color: #64748b; margin-bottom: 0.3rem;">Sampai</label>
      <input class="form-control" type="date" name="sampai" value="<?= htmlspecialchars($filters['sampai'] ?? '') ?>">
    </div>
    <div class="col-6 col-md-2">
      <label class="form-label small fw-bold d-none d-md-block" style="color: #64748b; margin-bottom: 0.3rem;">&nbsp;</label>
      <button class="btn btn-outline-primary btn-filter w-100" style="font-weight: 700;">
        <i class="bi bi-funnel me-1" aria-hidden="true"></i> Filter
      </button>
    </div>

    <!-- UI only: quick reset -->
    <div class="col-6 col-md-2">
      <label class="form-label small fw-bold d-none d-md-block" style="color: #64748b; margin-bottom: 0.3rem;">&nbsp;</label>
      <a class="btn btn-outline-secondary btn-filter w-100" href="<?= current_url() ?>" style="font-weight: 700;">
        <i class="bi bi-arrow-counterclockwise me-1" aria-hidden="true"></i> Reset
      </a>
    </div>
  </form>
</div>

<!-- TABLE -->
<div class="card card-wow">
  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table table-sm table-hover mb-0 table-mobile-cards">
        <thead>
          <tr>
            <th>📌 No Registrasi</th>
            <th>🧪 Nama Sampel</th>
            <th>🏷️ Jenis</th>
            <th>📅 Tgl Permintaan</th>
            <th>📋 Jml Pemeriksaan</th>
            <th>📊 Status</th>
            <th class="text-end">⚙️ Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($rows as $r): ?>
            <?php
              $jenis = strtolower(trim((string)($r['jenis_sampel'] ?? '')));
              $badgeClass = 'badge-soft';
              if (strpos($jenis, 'air') !== false) $badgeClass .= ' badge-air';
              else if (strpos($jenis, 'makan') !== false) $badgeClass .= ' badge-makanan';
              else if (strpos($jenis, 'lingkung') !== false) $badgeClass .= ' badge-lingkungan';
            ?>
            <tr class="row-hover">
              <td class="no-reg-text" data-label="📌 No Registrasi"><?= htmlspecialchars($r['no_registrasi'] ?? '') ?></td>
              <td class="sampel-text" style="font-weight: 600;" data-label="🧪 Nama Sampel"><?= htmlspecialchars($r['nama_sampel'] ?? '') ?></td>
              <td data-label="🏷️ Jenis">
                <span class="<?= $badgeClass ?>">
                  <i class="bi bi-tag" aria-hidden="true"></i>
                  <?= htmlspecialchars($r['jenis_sampel'] ?? '') ?>
                </span>
              </td>
              <td data-label="📅 Tgl Permintaan">
                <span class="date-badge">
                  <i class="bi bi-calendar3" aria-hidden="true"></i>
                  <?= htmlspecialchars($r['tgl_permintaan'] ?? '-') ?>
                </span>
              </td>
            <td data-label="📋 Jml Pemeriksaan">
              <span class="badge bg-secondary" style="border-radius: 8px;"><i class="bi bi-list-check me-1"></i><?= $r['jumlah_pemeriksaan'] ?? 0 ?> Item</span>
            </td>
            <td data-label="📊 Status">
              <?php if (isset($r['status_kelayakan']) && strtolower(trim($r['status_kelayakan'])) === 'tidak layak'): ?>
                <span class="badge bg-danger" style="border-radius: 8px;"><i class="bi bi-x-circle me-1"></i>Ditolak</span>
              <?php elseif (!empty($r['no_registrasi'])): ?>
                <span class="badge bg-success" style="border-radius: 8px;"><i class="bi bi-check-circle me-1"></i>Diterima</span>
              <?php else: ?>
                <span class="badge bg-warning text-dark" style="border-radius: 8px;"><i class="bi bi-hourglass-split me-1"></i>Menunggu</span>
              <?php endif; ?>
            </td>
              <td class="text-end">
                <div class="btn-group" role="group" aria-label="Aksi">
                  <a class="btn btn-sm btn-outline-primary btn-action"
                     href="<?= site_url('k-panel/form_permintaan_kesmas/detail/'.$r['id_permintaan']) ?>"
                     title="Lihat detail permintaan">
                    <i class="bi bi-zoom-in"></i><span class="d-none d-md-inline">Detail</span>
                  </a>
                  
                  <?php if (isset($title) && $title === 'Terima Sample'): ?>
                  <a class="btn btn-sm btn-danger btn-action"
                     href="<?= site_url('k-panel/permintaan_sample/tolak/'.$r['id_permintaan']) ?>"
                     onclick="return confirm('Apakah Anda yakin ingin menolak sample ini?');"
                     title="Tolak sample">
                    <i class="bi bi-hand-thumbs-down"></i><span class="d-none d-md-inline">Tolak</span>
                  </a>
                  <a class="btn btn-sm btn-success btn-action"
                     href="<?= site_url('k-panel/form_permintaan_kesmas/edit/'.$r['id_permintaan'].'?accept=1') ?>"
                     title="Verifikasi dan terima sample">
                    <i class="bi bi-hand-thumbs-up"></i><span class="d-none d-md-inline">Terima</span>
                  </a>
                  <?php else: ?>
                  <a class="btn btn-sm btn-outline-warning btn-action"
                     href="<?= site_url('k-panel/form_permintaan_kesmas/kaji_ulang/'.$r['id_permintaan']) ?>"
                     title="Kaji ulang permintaan">
                    <i class="bi bi-clipboard-check"></i><span class="d-none d-md-inline">Kaji</span>
                  </a>
                  <a class="btn btn-sm btn-outline-secondary btn-action"
                     href="<?= site_url('k-panel/form_permintaan_kesmas/edit/'.$r['id_permintaan']) ?>"
                     title="Edit permintaan">
                    <i class="bi bi-pencil"></i><span class="d-none d-md-inline">Edit</span>
                  </a>
                  <form action="<?= site_url('k-panel/form_permintaan_kesmas/delete/'.$r['id_permintaan']) ?>"
                        method="post"
                        class="d-inline"
                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini? Tindakan ini tidak dapat dibatalkan.');">
                    <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
                    <button type="submit" class="btn btn-sm btn-outline-danger btn-action" title="Hapus permintaan">
                      <i class="bi bi-trash"></i><span class="d-none d-md-inline">Hapus</span>
                    </button>
                  </form>
                  <?php endif; ?>
                </div>
              </td>
            </tr>
          <?php endforeach; ?>

          <?php if (empty($rows)): ?>
            <tr>
              <td colspan="6" class="text-center">
                <div class="empty-state">
                  <div class="empty-icon"><i class="bi bi-inbox"></i></div>
                  <div class="fw-bold" style="color:#334155; font-size: 1.05rem;">📭 Belum ada data</div>
                  <?php if (isset($title) && $title === 'Terima Sample'): ?>
                  <div class="small text-muted mb-3">Belum ada permintaan sample baru yang masuk. Menungkan pelanggan untuk mendaftar...</div>
                  <?php else: ?>
                  <div class="small text-muted mb-3">Klik tombol <strong>Tambah Baru</strong> untuk membuat permintaan pertama Anda.</div>
                  <a class="btn btn-primary btn-add" href="<?= site_url('k-panel/form_permintaan_kesmas/create') ?>" style="margin-top: 1rem;">
                    <i class="bi bi-plus-lg me-1" aria-hidden="true"></i> Buat Permintaan Baru
                  </a>
                  <?php endif; ?>
                </div>
              </td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

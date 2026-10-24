<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php
/**
 * Daftar pendaftaran + status kwitansi (dipakai Kesmas, Klinik, Rekam Medis).
 * @var array $cfg, $rows; @var int $total, $page, $limit; @var string $q
 */
$base = $cfg['base'];
$total_pages = max(1, (int)ceil($total / $limit));
$rp = function ($v) { return 'Rp ' . number_format((float)$v, 0, ',', '.'); };
?>
<div class="content-wrapper px-4 pt-4">
  <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
    <div>
      <h3 class="text-dark fw-bold mb-1"><?= html_escape(strtoupper($cfg['judul_menu'])) ?></h3>
      <div class="text-muted small">Pilih pendaftaran untuk membuat kwitansi, lalu ekspor sebagai PDF atau cetak.</div>
    </div>
    <a href="<?= site_url($base . '/tarif') ?>" class="btn btn-outline-primary btn-sm">
      <i class="fas fa-tags me-1"></i> Tarif Pemeriksaan
    </a>
  </div>
  <hr style="border-top: 2px solid #1e3a8a; margin-bottom: 20px;">

  <form method="get" action="<?= site_url($base) ?>" class="row g-2 mb-3">
    <div class="col-12 col-md-6 col-lg-4">
      <div class="input-group input-group-sm">
        <input type="text" name="q" class="form-control" placeholder="Cari <?= html_escape(strtolower($cfg['label_kode'])) ?>, nama, no. kwitansi..." value="<?= html_escape($q) ?>">
        <button class="btn btn-primary" type="submit"><i class="fas fa-search"></i></button>
        <?php if ($q !== ''): ?>
          <a href="<?= site_url($base) ?>" class="btn btn-outline-secondary" title="Reset"><i class="fas fa-times"></i></a>
        <?php endif; ?>
      </div>
    </div>
  </form>

  <div class="card shadow-sm">
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
          <thead class="table-light">
            <tr>
              <th style="width:50px" class="text-center">No</th>
              <th><?= html_escape($cfg['label_kode']) ?></th>
              <th><?= html_escape($cfg['label_nama']) ?></th>
              <th>Tanggal</th>
              <th>Kwitansi</th>
              <th class="text-end">Total</th>
              <th class="text-end" style="width:220px">Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php if (empty($rows)): ?>
              <tr><td colspan="7" class="text-center text-muted py-4">Belum ada data pendaftaran.</td></tr>
            <?php endif; ?>
            <?php $no = ($page - 1) * $limit + 1; foreach ($rows as $r): ?>
              <tr>
                <td class="text-center"><?= $no++ ?></td>
                <td class="fw-semibold"><?= html_escape($r['kode'] ?: '-') ?></td>
                <td>
                  <?= html_escape($r['nama'] ?: '-') ?>
                  <?php if (!empty($r['keterangan'])): ?>
                    <div class="small text-muted"><?= html_escape($r['keterangan']) ?></div>
                  <?php endif; ?>
                </td>
                <td><?= $r['tanggal'] ? date('d-m-Y', strtotime($r['tanggal'])) : '-' ?></td>
                <td>
                  <?php if ($r['no_kwitansi']): ?>
                    <span class="badge bg-success"><?= html_escape($r['no_kwitansi']) ?></span>
                  <?php else: ?>
                    <span class="badge bg-secondary">Belum dibuat</span>
                  <?php endif; ?>
                </td>
                <td class="text-end"><?= $r['no_kwitansi'] ? $rp($r['kwitansi_total']) : '-' ?></td>
                <td class="text-end">
                  <a href="<?= site_url($base . '/form/' . (int)$r['ref_id']) ?>" class="btn btn-sm <?= $r['no_kwitansi'] ? 'btn-outline-primary' : 'btn-primary' ?>">
                    <i class="fas <?= $r['no_kwitansi'] ? 'fa-edit' : 'fa-plus' ?> me-1"></i><?= $r['no_kwitansi'] ? 'Ubah' : 'Buat' ?>
                  </a>
                  <?php if ($r['no_kwitansi']): ?>
                    <div class="dropdown d-inline-block">
                      <button class="btn btn-sm btn-outline-success dropdown-toggle" type="button" data-bs-toggle="dropdown" data-bs-popper-config='{"strategy":"fixed"}' aria-expanded="false">
                        <i class="fas fa-file-export me-1"></i>Export
                      </button>
                      <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="<?= site_url($base . '/pdf/' . (int)$r['ref_id']) ?>" target="_blank" rel="noopener"><i class="fas fa-file-pdf text-danger me-2"></i>PDF</a></li>
                        <li><a class="dropdown-item" href="<?= site_url($base . '/print/' . (int)$r['ref_id']) ?>" target="_blank" rel="noopener"><i class="fas fa-print me-2"></i>Print</a></li>
                      </ul>
                    </div>
                  <?php endif; ?>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <div class="d-flex flex-wrap justify-content-between align-items-center mt-3 mb-4 gap-2">
    <div class="text-muted small">Total <?= (int)$total ?> data</div>
    <?php if ($total_pages > 1): ?>
      <nav>
        <ul class="pagination pagination-sm mb-0">
          <?php for ($i = 1; $i <= $total_pages; $i++): ?>
            <li class="page-item <?= $i === $page ? 'active' : '' ?>">
              <a class="page-link" href="<?= site_url($base . '?page=' . $i . ($q !== '' ? '&q=' . urlencode($q) : '')) ?>"><?= $i ?></a>
            </li>
          <?php endfor; ?>
        </ul>
      </nav>
    <?php endif; ?>
  </div>
</div>

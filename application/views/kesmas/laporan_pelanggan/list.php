<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Laporan Per Pelanggan - Daftar Pelanggan
 * @var array $rows    - Daftar pelanggan dengan ringkasan permintaan
 * @var array $filters - Filter yang aktif
 */
?>
<?php $this->load->view('kesmas/premium_list_style'); ?>

<!-- HERO HEADER -->
<div class="page-hero mb-4">
  <div class="d-flex align-items-start justify-content-between gap-3 flex-wrap">
    <div>
      <a class="btn btn-outline-secondary btn-sm me-2" href="javascript:history.back();" style="border-radius:8px;padding:0.3rem 0.6rem;font-weight:600;transition:all .3s ease;">
        <i class="bi bi-arrow-left"></i> <span class="d-none d-md-inline">Kembali</span>
      </a>
      <div class="mt-3">
        <h3>Laporan Per Pelanggan</h3>
        <p class="sub">Lihat riwayat permintaan &amp; cetak laporan per pelanggan</p>
      </div>
    </div>
  </div>
</div>

<!-- FILTER -->
<div class="filter-shell mb-4">
  <form class="row g-2 align-items-center" method="get">
    <div class="col-12 col-md-4">
      <input class="form-control" name="q" placeholder="Cari nama / instansi pelanggan..."
             value="<?= htmlspecialchars($filters['q'] ?? '') ?>">
    </div>
    <div class="col-6 col-md-2">
      <input class="form-control" type="date" name="dari" value="<?= htmlspecialchars($filters['dari'] ?? '') ?>">
    </div>
    <div class="col-6 col-md-2">
      <input class="form-control" type="date" name="sampai" value="<?= htmlspecialchars($filters['sampai'] ?? '') ?>">
    </div>
    <div class="col-12 col-md-3">
      <select class="form-select" name="jenis_sampel">
        <option value="">-- Semua Jenis Sampel --</option>
        <?php
        $jenis_options = ['Air Minum','Air Bersih','Makanan','Minuman','Lingkungan'];
        foreach ($jenis_options as $j):
          $sel = ($filters['jenis_sampel'] ?? '') === $j ? 'selected' : '';
        ?>
          <option value="<?= htmlspecialchars($j) ?>" <?= $sel ?>><?= htmlspecialchars($j) ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="col-12 col-md-1">
      <button class="btn btn-outline-primary w-100 btn-filter">Filter</button>
    </div>
  </form>
</div>

<!-- TABEL PELANGGAN -->
<div class="card-wow">
  <div class="p-0">
    <div class="table-responsive">
      <table class="table table-sm table-hover mb-0 table-mobile-cards">
        <thead>
          <tr>
            <th>Nama Pelanggan</th>
            <th>NIK</th>
            <th>Instansi</th>
            <th class="text-center">Jml Permintaan</th>
            <th class="text-center">Jml Pemeriksaan</th>
            <th>Tgl Terakhir</th>
            <th class="text-end">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($rows as $r): ?>
            <tr class="row-hover">
              <td data-label="Nama Pelanggan">
                <span class="fw-semibold"><?= htmlspecialchars($r['nama']) ?></span><br>
                <span class="text-muted small"><?= htmlspecialchars($r['alamat'] ?: '-') ?></span>
              </td>
              <td data-label="NIK">
                <?php if (!empty($r['nik'])): ?>
                  <span class="badge" style="background:#eff6ff;color:#1d4ed8;border:1px solid #bfdbfe;border-radius:6px;font-family:monospace;font-size:.8rem;">
                    <?= htmlspecialchars($r['nik']) ?>
                  </span>
                <?php else: ?>
                  <span class="text-muted small">-</span>
                <?php endif; ?>
              </td>
              <td data-label="Instansi">
                <span class="text-muted"><?= htmlspecialchars($r['instansi'] ?: '-') ?></span>
              </td>
              <td class="text-center" data-label="Jml Permintaan">
                <span class="badge bg-primary rounded-pill"><?= (int)$r['jumlah_permintaan'] ?></span>
              </td>
              <td class="text-center" data-label="Jml Pemeriksaan">
                <span class="badge bg-secondary rounded-pill"><?= (int)$r['jumlah_item'] ?></span>
              </td>
              <td data-label="Tgl Terakhir">
                <span class="date-badge">
                  <i class="bi bi-calendar-check"></i>
                  <?= $r['tgl_terakhir'] ? date('d-m-Y', strtotime($r['tgl_terakhir'])) : '-' ?>
                </span>
              </td>
              <td class="text-end">
                <?php
                  $qs  = '?dari='   . urlencode($filters['dari'] ?? '');
                  $qs .= '&sampai=' . urlencode($filters['sampai'] ?? '');
                  $qs .= '&jenis_sampel=' . urlencode($filters['jenis_sampel'] ?? '');
                ?>
                <a class="btn btn-sm btn-outline-primary btn-action"
                   href="<?= site_url('k-panel/laporan_pelanggan/detail/'.$r['id_pelanggan']) . $qs ?>">
                  <i class="bi bi-eye"></i> Detail
                </a>
                <a class="btn btn-sm btn-outline-success btn-action ms-1"
                   href="<?= site_url('k-panel/laporan_pelanggan/print_laporan/'.$r['id_pelanggan']) . $qs ?>" target="_blank" rel="noopener">
                  <i class="bi bi-printer"></i> Cetak
                </a>
              </td>
            </tr>
          <?php endforeach; ?>
          <?php if (empty($rows)): ?>
            <tr><td colspan="7" class="text-center text-muted py-4">Belum ada data pelanggan dengan permintaan.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
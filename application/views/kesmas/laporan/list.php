<?php
/**
 * Laporan List View
 * 
 * @var string $title - Page title
 * @var array $rows - List of laporan data
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
      <div class="mt-3">
        <h3>Laporan Uji</h3>
        <p class="sub">Filter dan buka detail</p>
      </div>
    </div>
    <div class="d-flex gap-2">
      <a class="btn-add" href="<?= site_url('k-panel/laporan_uji_kesmas/parameter') ?>" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); box-shadow: 0 12px 24px rgba(16,185,129,.25);">
        <i class="bi bi-list-columns me-md-1" aria-hidden="true"></i> <span class="d-none d-md-inline">Lap Parameter</span>
      </a>
    </div>
  </div>
</div>

<div class="filter-shell mb-4">
  <form class="row g-2 align-items-center" method="get">
    <div class="col-12 col-md-5">
      <input class="form-control" name="q" placeholder="Cari no. registrasi / nama sampel..." value="<?= htmlspecialchars($filters['q'] ?? '') ?>">
    </div>
    <div class="col-6 col-md-3">
      <input class="form-control" type="date" name="dari" value="<?= htmlspecialchars($filters['dari'] ?? '') ?>">
    </div>
    <div class="col-6 col-md-2">
      <input class="form-control" type="date" name="sampai" value="<?= htmlspecialchars($filters['sampai'] ?? '') ?>">
    </div>
    <div class="col-12 col-md-2 mt-3 mt-md-0">
      <button class="btn btn-outline-primary w-100 btn-filter">Filter</button>
    </div>
  </form>
</div>

<div class="card-wow">
  <div class="p-0">
    <div class="table-responsive">
      <table class="table table-sm table-hover mb-0 table-mobile-cards">
        <thead>
          <tr>
            <th>📌 No Registrasi</th>
            <th>🧪 Nama Sampel</th>
            <th>🏷️ Jenis</th>
            <th>📅 Tgl Permintaan</th>
            <th>📋 Jml Pemeriksaan</th>
            <th>🧑‍🔬 Petugas Pengambil</th>
            <th class="text-end">⚙️ Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($rows as $r): ?>
            <tr class="row-hover">
              <td data-label="📌 No Registrasi">
                <span class="no-reg-text"><?= htmlspecialchars($r['no_registrasi']) ?></span>
              </td>
              <td data-label="🧪 Nama Sampel">
                <span class="sampel-text"><?= htmlspecialchars($r['nama_sampel']) ?></span>
              </td>
              <td data-label="🏷️ Jenis">
                <?php
                    $kategori_badge = 'secondary';
                    $kategori_text = strtolower(trim((string)($r['kategori_sampel'] ?? '')));
                    if (str_contains($kategori_text, 'air minum')) {
                        $kategori_badge = 'air';
                    } elseif (str_contains($kategori_text, 'air bersih') || str_contains($kategori_text, 'air sanitasi')) {
                        $kategori_badge = 'warning';
                    } elseif (str_contains($kategori_text, 'makanan')) {
                        $kategori_badge = 'makanan';
                    } elseif (str_contains($kategori_text, 'lingkungan')) {
                        $kategori_badge = 'success';
                    }
                ?>
                <span class="badge-soft badge-<?= $kategori_badge ?>">
                  <?= htmlspecialchars($r['jenis_sampel']) ?>
                </span>
              </td>
              <td data-label="📅 Tgl Permintaan">
                <span class="date-badge">
                  <i class="bi bi-calendar-check"></i> <?= htmlspecialchars($r['tgl_permintaan'] ?? '-') ?>
                </span>
              </td>
              <td data-label="📋 Jml Pemeriksaan">
                <span class="badge bg-secondary" style="border-radius: 8px;"><i class="bi bi-list-check me-1"></i><?= htmlspecialchars($r['jumlah_pemeriksaan'] ?? 0) ?> Item</span>
              </td>
              <td class="text-muted fw-semibold" data-label="🧑‍🔬 Petugas Pengambil">
                <?= htmlspecialchars($r['petugas_pengambil_nama'] ?? '-') ?>
              </td>
              <td class="text-end">
                <a class="btn btn-sm btn-outline-primary btn-action" href="<?= site_url('k-panel/laporan_uji_kesmas/detail/'.$r['id_permintaan']) ?>">
                  Buka Detail
                </a>
                <div class="dropdown d-inline-block ms-1">
                  <button class="btn btn-sm btn-outline-success btn-action dropdown-toggle" type="button" data-bs-toggle="dropdown" data-bs-popper-config='{"strategy":"fixed"}' aria-expanded="false">
                    <i class="bi bi-box-arrow-up"></i> Export
                  </button>
                  <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                      <a class="dropdown-item" href="<?= site_url('k-panel/laporan_uji_kesmas/pdf/'.(int)$r['id_permintaan']) ?>" target="_blank" rel="noopener">
                        <i class="bi bi-file-earmark-pdf text-danger me-2"></i> PDF
                      </a>
                    </li>
                    <li>
                      <a class="dropdown-item" href="<?= site_url('k-panel/laporan_uji_kesmas/print/'.(int)$r['id_permintaan']) ?>" target="_blank" rel="noopener">
                        <i class="bi bi-printer me-2"></i> Print
                      </a>
                    </li>
                  </ul>
                </div>
              </td>
            </tr>
          <?php endforeach; ?>
          <?php if (empty($rows)): ?>
            <tr><td colspan="7" class="text-center text-muted py-4">Belum ada data</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<?php
/**
 * Uji List View
 * 
 * @var string $title - Page title
 * @var array $rows - List of permintaan data
 * @var array $filters - Filter parameters
 */
?>
<?php $this->load->view('kesmas/premium_list_style'); ?>
<style>
  .alert-info {
    border-radius: 14px;
    border: 1px solid rgba(13,110,253,.2);
    background: rgba(13,110,253,.05);
    color: #0b5ed7;
  }
</style>

<div class="page-hero mb-4">
  <div class="d-flex align-items-start justify-content-between gap-3 flex-wrap">
    <div>
      <a class="btn btn-outline-secondary btn-sm me-2" href="javascript:history.back();" style="border-radius: 8px; padding: 0.3rem 0.6rem; font-weight: 600;">
        <i class="bi bi-arrow-left"></i> <span class="d-none d-md-inline">Kembali</span>
      </a>
      <h3 style="display:inline-block; margin:0;">Input Hasil 📝</h3>
      <div class="sub">Pilih permintaan untuk input hasil pemeriksaan</div>
    </div>
  </div>
</div>

<div class="filter-shell mb-4">
  <form class="row g-2" method="get">

    <div class="col-12 col-md-4">
      <div class="input-group">
        <span class="input-group-text bg-transparent border-0" style="color:#0d6efd; font-weight: 700;"><i class="bi bi-search"></i></span>
        <input class="form-control" name="q" placeholder="🔍 Cari no. registrasi / nama sampel" value="<?= htmlspecialchars($filters['q'] ?? '') ?>">
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
  </form>
</div>

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
            <th class="text-end">⚙️ Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($rows as $r): ?>
            <tr class="row-hover">
              <td data-label="No Reg" class="fw-semibold" style="color: #0b1220;"><?= htmlspecialchars($r['no_registrasi']) ?></td>
              <td data-label="Nama" style="color: #475569; font-weight: 600;"><?= htmlspecialchars($r['nama_sampel']) ?></td>
              <td data-label="Jenis"><?= htmlspecialchars($r['jenis_sampel']) ?></td>
              <td data-label="Tanggal">
                <span style="display: inline-flex; align-items: center; gap: 0.35rem; color: #64748b; font-size: 0.85rem; padding: 0.3rem 0.6rem; background: rgba(100,112,179,.06); border-radius: 8px; border: 1px solid rgba(100,112,179,.15);">
                  <i class="bi bi-calendar3" aria-hidden="true"></i>
                  <?= htmlspecialchars($r['tgl_permintaan'] ?? '-') ?>
                </span>
              </td>
              <td data-label="Jumlah">
                <span class="badge bg-secondary" style="border-radius: 8px;"><i class="bi bi-list-check me-1"></i><?= $r['jumlah_pemeriksaan'] ?? 0 ?> Item</span>
              </td>
              <td data-label="Aksi" class="text-end">
                <a class="btn btn-sm btn-action btn-primary" href="<?= site_url('k-panel/uji/input/'.$r['id_permintaan']) ?>" title="Input hasil pemeriksaan">
                  <i class="bi bi-pencil"></i><span class="d-none d-md-inline">Input</span>
                </a>
              </td>
            </tr>
          <?php endforeach; ?>
          <?php if (empty($rows)): ?>
            <tr>
              <td colspan="5" class="text-center">
                <div class="empty-state">
                  <div style="width: 64px; height: 64px; border-radius: 20px; display: inline-flex; align-items: center; justify-content: center; background: linear-gradient(135deg, rgba(13,110,253,.15) 0%, rgba(100,200,255,.08) 100%); border: 2px solid rgba(13,110,253,.25); color: #0b5ed7; box-shadow: 0 12px 30px rgba(13,110,253,.12); margin-bottom: 1rem; font-size: 1.8rem;">
                    <i class="bi bi-inbox"></i>
                  </div>
                  <div class="fw-bold" style="color:#334155; font-size: 1.05rem;">📭 Belum ada data</div>
                  <div class="small text-muted">Tidak ada permintaan yang siap untuk input hasil.</div>
                </div>
              </td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

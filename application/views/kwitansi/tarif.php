<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php
/**
 * Kelola tarif pemeriksaan (Kesmas atau Klinik; Rekam Medis memakai tarif Klinik).
 * @var array $cfg, $tarif
 */
$base = $cfg['base'];
$csrf_name = $this->security->get_csrf_token_name();
$csrf_hash = $this->security->get_csrf_hash();
?>
<div class="content-wrapper px-4 pt-4 pb-5">
  <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
    <div>
      <h3 class="text-dark fw-bold mb-1">TARIF PEMERIKSAAN</h3>
      <div class="text-muted small">
        <?= html_escape($cfg['judul_menu']) ?> &middot; <?= html_escape(ucfirst(strtolower($cfg['judul_dasar']))) ?>.
      </div>
    </div>
    <div class="d-flex flex-wrap gap-2">
      <a href="<?= site_url($base) ?>" class="btn btn-outline-secondary btn-sm"><i class="fas fa-arrow-left me-1"></i> Kembali</a>
      <?php if (!empty($cfg['sync_master'])): ?>
        <form method="post" action="<?= site_url($base . '/tarif_sync') ?>">
          <input type="hidden" name="<?= $csrf_name ?>" value="<?= $csrf_hash ?>">
          <button type="submit" class="btn btn-outline-primary btn-sm"><i class="fas fa-sync me-1"></i> Ambil dari Master Pemeriksaan</button>
        </form>
      <?php endif; ?>
    </div>
  </div>
  <hr style="border-top: 2px solid #1e3a8a; margin-bottom: 20px;">

  <div class="alert alert-info small">
    Perubahan tarif hanya berlaku untuk kwitansi yang disimpan berikutnya. Kwitansi yang sudah tersimpan tetap memakai harga saat itu.
    Item yang dinonaktifkan tidak tampil di form dan cetakan.
  </div>

  <div class="card shadow-sm mb-4">
    <div class="card-header bg-white fw-semibold"><i class="fas fa-plus me-1 text-primary"></i> Tambah Tarif</div>
    <div class="card-body">
      <form method="post" action="<?= site_url($base . '/tarif_tambah') ?>" class="row g-2 align-items-end">
        <input type="hidden" name="<?= $csrf_name ?>" value="<?= $csrf_hash ?>">
        <div class="col-12 col-md-3">
          <label class="form-label small mb-1">Kelompok</label>
          <input type="text" name="kelompok" class="form-control form-control-sm" list="daftar-kelompok" required maxlength="100">
          <datalist id="daftar-kelompok">
            <?php foreach (array_keys($tarif) as $k): ?><option value="<?= html_escape($k) ?>"><?php endforeach; ?>
          </datalist>
        </div>
        <div class="col-12 col-md-3">
          <label class="form-label small mb-1">Nama Pemeriksaan</label>
          <input type="text" name="nama_item" class="form-control form-control-sm" required maxlength="255">
        </div>
        <div class="col-12 col-md-3">
          <label class="form-label small mb-1">Keterangan</label>
          <input type="text" name="keterangan" class="form-control form-control-sm" maxlength="255">
        </div>
        <div class="col-8 col-md-2">
          <label class="form-label small mb-1">Harga (Rp)</label>
          <input type="number" name="harga" class="form-control form-control-sm" min="0" step="500" value="0" required>
        </div>
        <div class="col-4 col-md-1">
          <button type="submit" class="btn btn-primary btn-sm w-100">Tambah</button>
        </div>
      </form>
    </div>
  </div>

  <form method="post" action="<?= site_url($base . '/tarif_simpan') ?>">
    <input type="hidden" name="<?= $csrf_name ?>" value="<?= $csrf_hash ?>">
    <?php if (empty($tarif)): ?>
      <div class="text-muted">Belum ada tarif.</div>
    <?php endif; ?>
    <?php foreach ($tarif as $kelompok => $items): ?>
      <div class="card shadow-sm mb-3">
        <div class="card-header bg-light fw-bold text-uppercase small"><?= html_escape($kelompok) ?></div>
        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table table-sm align-middle mb-0">
              <thead>
                <tr class="small text-muted">
                  <th style="width:35%">Nama Pemeriksaan</th>
                  <th>Keterangan</th>
                  <th style="width:150px">Harga (Rp)</th>
                  <th style="width:70px" class="text-center">Aktif</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($items as $t): $id = (int)$t['id_tarif']; ?>
                  <tr class="<?= $t['aktif'] ? '' : 'table-secondary' ?>">
                    <td><input type="text" name="tarif[<?= $id ?>][nama_item]" class="form-control form-control-sm" maxlength="255" value="<?= html_escape($t['nama_item']) ?>"></td>
                    <td><input type="text" name="tarif[<?= $id ?>][keterangan]" class="form-control form-control-sm" maxlength="255" value="<?= html_escape($t['keterangan'] ?? '') ?>"></td>
                    <td><input type="number" name="tarif[<?= $id ?>][harga]" class="form-control form-control-sm text-end" min="0" step="500" value="<?= (int)$t['harga'] ?>"></td>
                    <td class="text-center"><input type="checkbox" class="form-check-input" name="tarif[<?= $id ?>][aktif]" value="1" <?= $t['aktif'] ? 'checked' : '' ?>></td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
    <?php if (!empty($tarif)): ?>
      <div class="text-end">
        <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i> Simpan Perubahan Tarif</button>
      </div>
    <?php endif; ?>
  </form>
</div>

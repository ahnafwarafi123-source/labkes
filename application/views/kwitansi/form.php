<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php
/**
 * Form buat/ubah kwitansi.
 * @var array $cfg, $sumber, $saran, $terpilih, $tarif; @var array|null $kwitansi; @var string $default_penerima
 */
$base = $cfg['base'];
$ref_id = (int)$sumber['ref_id'];
$csrf_name = $this->security->get_csrf_token_name();
$csrf_hash = $this->security->get_csrf_hash();
$terpilih = array_flip($terpilih);
$metode_list = ['Tunai', 'Transfer', 'QRIS', 'BPJS', 'Lain-lain'];
$metode_now = $kwitansi['metode_pembayaran'] ?? ($sumber['metode_terdaftar'] ?: 'Tunai');
?>
<div class="content-wrapper px-4 pt-4 pb-5">
  <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
    <div>
      <h3 class="text-dark fw-bold mb-1"><?= $kwitansi ? 'UBAH KWITANSI' : 'BUAT KWITANSI' ?></h3>
      <div class="text-muted small"><?= html_escape($cfg['judul_menu']) ?></div>
    </div>
    <div class="d-flex flex-wrap gap-2">
      <a href="<?= site_url($base) ?>" class="btn btn-outline-secondary btn-sm"><i class="fas fa-arrow-left me-1"></i> Kembali</a>
      <?php if ($kwitansi): ?>
        <div class="dropdown">
          <button class="btn btn-success btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="fas fa-file-export me-1"></i> Export
          </button>
          <ul class="dropdown-menu dropdown-menu-end">
            <li><a class="dropdown-item" href="<?= site_url($base . '/pdf/' . $ref_id) ?>" target="_blank" rel="noopener"><i class="fas fa-file-pdf text-danger me-2"></i>PDF</a></li>
            <li><a class="dropdown-item" href="<?= site_url($base . '/print/' . $ref_id) ?>" target="_blank" rel="noopener"><i class="fas fa-print me-2"></i>Print</a></li>
          </ul>
        </div>
      <?php endif; ?>
    </div>
  </div>
  <hr style="border-top: 2px solid #1e3a8a; margin-bottom: 20px;">

  <form method="post" action="<?= site_url($base . '/simpan/' . $ref_id) ?>" id="form-kwitansi">
    <input type="hidden" name="<?= $csrf_name ?>" value="<?= $csrf_hash ?>">

    <div class="row g-3">
      <!-- Kolom kiri: data pendaftaran & pembayaran -->
      <div class="col-12 col-lg-4">
        <div class="card shadow-sm mb-3">
          <div class="card-header bg-white fw-semibold"><i class="fas fa-id-card me-1 text-primary"></i> Data Pendaftaran</div>
          <div class="card-body small">
            <div class="row mb-1"><div class="col-5 text-muted"><?= html_escape($cfg['label_kode']) ?></div><div class="col-7 fw-semibold"><?= html_escape($sumber['kode'] ?: '-') ?></div></div>
            <div class="row mb-1"><div class="col-5 text-muted"><?= html_escape($cfg['label_nama']) ?></div><div class="col-7"><?= html_escape($sumber['nama'] ?: '-') ?></div></div>
            <div class="row mb-1"><div class="col-5 text-muted">Tanggal</div><div class="col-7"><?= $sumber['tanggal'] ? date('d-m-Y', strtotime($sumber['tanggal'])) : '-' ?></div></div>
            <?php if (!empty($sumber['keterangan'])): ?>
              <div class="row mb-1"><div class="col-5 text-muted">Keterangan</div><div class="col-7"><?= html_escape($sumber['keterangan']) ?></div></div>
            <?php endif; ?>
            <?php if ((float)$sumber['biaya_terdaftar'] > 0): ?>
              <div class="row mb-1"><div class="col-5 text-muted">Biaya di pendaftaran</div><div class="col-7">Rp <?= number_format((float)$sumber['biaya_terdaftar'], 0, ',', '.') ?></div></div>
            <?php endif; ?>
            <?php if ($kwitansi): ?>
              <div class="row mt-2"><div class="col-5 text-muted">No. Kwitansi</div><div class="col-7"><span class="badge bg-success"><?= html_escape($kwitansi['no_kwitansi']) ?></span></div></div>
            <?php endif; ?>
          </div>
        </div>

        <div class="card shadow-sm mb-3">
          <div class="card-header bg-white fw-semibold"><i class="fas fa-receipt me-1 text-primary"></i> Data Pembayaran</div>
          <div class="card-body">
            <div class="mb-2">
              <label class="form-label small mb-1">Telah terima dari</label>
              <input type="text" name="nama_pembayar" class="form-control form-control-sm" maxlength="255"
                value="<?= html_escape($kwitansi['nama_pembayar'] ?? $sumber['nama'] ?? '') ?>">
            </div>
            <div class="row g-2 mb-2">
              <div class="col-6">
                <label class="form-label small mb-1">Tanggal</label>
                <input type="date" name="tanggal" class="form-control form-control-sm" required
                  value="<?= html_escape($kwitansi['tanggal'] ?? date('Y-m-d')) ?>">
              </div>
              <div class="col-6">
                <label class="form-label small mb-1">Metode</label>
                <select name="metode_pembayaran" class="form-select form-select-sm">
                  <?php foreach ($metode_list as $m): ?>
                    <option value="<?= $m ?>" <?= strcasecmp($m, (string)$metode_now) === 0 ? 'selected' : '' ?>><?= $m ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>
            <div class="mb-2">
              <label class="form-label small mb-1">Yang menerima (petugas)</label>
              <input type="text" name="nama_penerima" class="form-control form-control-sm" maxlength="150"
                value="<?= html_escape($default_penerima) ?>">
            </div>
            <div class="mb-0">
              <label class="form-label small mb-1">Catatan (opsional)</label>
              <textarea name="keterangan" rows="2" class="form-control form-control-sm"><?= html_escape($kwitansi['keterangan'] ?? '') ?></textarea>
            </div>
          </div>
        </div>

        <div class="card shadow-sm mb-3 border-primary">
          <div class="card-body">
            <div class="text-muted small">Jumlah</div>
            <div class="fs-4 fw-bold text-primary" id="kw-total">Rp 0</div>
            <div class="small fst-italic" id="kw-terbilang">-</div>
            <div class="small text-muted mt-1">Total dihitung ulang oleh sistem dari tarif saat disimpan.</div>
          </div>
        </div>

        <?php if (!empty($saran['terdaftar'])): ?>
          <div class="card shadow-sm mb-3">
            <div class="card-header bg-white fw-semibold small"><i class="fas fa-vials me-1 text-primary"></i> Pemeriksaan di pendaftaran</div>
            <div class="card-body small">
              <div class="d-flex flex-wrap gap-1">
                <?php foreach ($saran['terdaftar'] as $t): ?>
                  <span class="badge <?= in_array($t, $saran['tidak_cocok'], true) ? 'bg-warning text-dark' : 'bg-light text-dark border' ?>"><?= html_escape($t) ?></span>
                <?php endforeach; ?>
              </div>
              <?php if (!empty($saran['tidak_cocok'])): ?>
                <div class="text-muted mt-2">Label kuning tidak ada padanannya di daftar tarif, pilih manual bila perlu.</div>
              <?php endif; ?>
            </div>
          </div>
        <?php endif; ?>
      </div>

      <!-- Kolom kanan: daftar tarif -->
      <div class="col-12 col-lg-8">
        <div class="card shadow-sm">
          <div class="card-header bg-white d-flex flex-wrap justify-content-between align-items-center gap-2">
            <span class="fw-semibold"><i class="fas fa-list-check me-1 text-primary"></i> Pilih Pemeriksaan</span>
            <span class="small text-muted"><?= $kwitansi ? 'Pilihan dari kwitansi tersimpan' : 'Tercentang otomatis dari pendaftaran' ?></span>
          </div>
          <div class="card-body">
            <?php if (empty($tarif)): ?>
              <div class="text-muted">Belum ada tarif aktif. <a href="<?= site_url($base . '/tarif') ?>">Kelola tarif</a>.</div>
            <?php endif; ?>
            <div class="row g-3">
              <?php foreach ($tarif as $kelompok => $items): ?>
                <div class="col-12 col-md-6">
                  <div class="border rounded p-2 h-100">
                    <div class="fw-bold small text-uppercase text-primary mb-1"><?= html_escape($kelompok) ?></div>
                    <?php foreach ($items as $t): $id = (int)$t['id_tarif']; ?>
                      <div class="form-check d-flex justify-content-between gap-2 py-1 border-bottom">
                        <div>
                          <input class="form-check-input kw-item" type="checkbox" name="tarif[]" value="<?= $id ?>"
                            id="tarif-<?= $id ?>" data-harga="<?= (float)$t['harga'] ?>" <?= isset($terpilih[$id]) ? 'checked' : '' ?>>
                          <label class="form-check-label small" for="tarif-<?= $id ?>">
                            <?= html_escape($t['nama_item']) ?>
                            <?php if (!empty($t['keterangan'])): ?><span class="text-muted">(<?= html_escape($t['keterangan']) ?>)</span><?php endif; ?>
                          </label>
                        </div>
                        <span class="small text-nowrap <?= (float)$t['harga'] > 0 ? '' : 'text-danger' ?>"><?= number_format((float)$t['harga'], 0, ',', '.') ?></span>
                      </div>
                    <?php endforeach; ?>
                  </div>
                </div>
              <?php endforeach; ?>
            </div>
          </div>
          <div class="card-footer bg-white d-flex flex-wrap justify-content-end gap-2">
            <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i> Simpan Kwitansi</button>
          </div>
        </div>
      </div>
    </div>
  </form>

  <?php if ($kwitansi): ?>
    <form method="post" action="<?= site_url($base . '/hapus/' . $ref_id) ?>" class="mt-3 text-end"
      onsubmit="return confirm('Hapus kwitansi <?= html_escape($kwitansi['no_kwitansi']) ?>? Data kwitansi akan dihapus permanen.');">
      <input type="hidden" name="<?= $csrf_name ?>" value="<?= $csrf_hash ?>">
      <button type="submit" class="btn btn-outline-danger btn-sm"><i class="fas fa-trash me-1"></i> Hapus Kwitansi</button>
    </form>
  <?php endif; ?>
</div>

<script>
(function () {
  function terbilang(n) {
    n = Math.floor(Math.abs(n));
    var s = ['', 'Satu', 'Dua', 'Tiga', 'Empat', 'Lima', 'Enam', 'Tujuh', 'Delapan', 'Sembilan', 'Sepuluh', 'Sebelas'];
    if (n < 12) return s[n];
    if (n < 20) return terbilang(n - 10) + ' Belas';
    if (n < 100) return (terbilang(Math.floor(n / 10)) + ' Puluh ' + terbilang(n % 10)).trim();
    if (n < 200) return ('Seratus ' + terbilang(n - 100)).trim();
    if (n < 1000) return (terbilang(Math.floor(n / 100)) + ' Ratus ' + terbilang(n % 100)).trim();
    if (n < 2000) return ('Seribu ' + terbilang(n - 1000)).trim();
    var u = [[1e12, 'Triliun'], [1e9, 'Miliar'], [1e6, 'Juta'], [1e3, 'Ribu']];
    for (var i = 0; i < u.length; i++) {
      if (n >= u[i][0]) return (terbilang(Math.floor(n / u[i][0])) + ' ' + u[i][1] + ' ' + terbilang(n % u[i][0])).trim();
    }
    return '';
  }
  function hitung() {
    var total = 0;
    document.querySelectorAll('.kw-item:checked').forEach(function (el) { total += parseFloat(el.dataset.harga) || 0; });
    document.getElementById('kw-total').textContent = 'Rp ' + total.toLocaleString('id-ID');
    document.getElementById('kw-terbilang').textContent = total > 0 ? terbilang(total) + ' Rupiah' : '-';
  }
  document.querySelectorAll('.kw-item').forEach(function (el) { el.addEventListener('change', hitung); });
  document.getElementById('form-kwitansi').addEventListener('submit', function (e) {
    if (!document.querySelector('.kw-item:checked')) {
      e.preventDefault();
      alert('Pilih minimal satu pemeriksaan.');
    }
  });
  hitung();
})();
</script>

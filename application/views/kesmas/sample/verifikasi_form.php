<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php
/**
 * Verifikasi Sample Form View
 * 
 * @var string $title - Page title
 * @var array $permintaan - Permintaan data array
 */
?>
<div class="container-fluid">
  <div class="row mb-3 align-items-center">
    <div class="col">
      <h3 class="fw-bold mb-0">Verifikasi & Terima Sample</h3>
      <div class="text-muted small">Lengkapi informasi Nomor Registrasi resmi sebelum menerima sample.</div>
    </div>
    <div class="col-auto">
      <a class="btn btn-outline-secondary" href="<?= site_url('k-panel/permintaan_sample') ?>">
        <i class="bi bi-arrow-left me-1"></i>Kembali
      </a>
    </div>
  </div>

  <div class="card shadow-sm border-0" style="border-radius: 16px;">
    <div class="card-body p-4">
      <div class="row mb-4">
        <div class="col-md-6">
          <table class="table table-borderless table-sm">
            <tr>
              <th style="width: 150px;" class="text-muted">Nama Sampel</th>
              <td class="fw-semibold">: <?= htmlspecialchars($permintaan['nama_sampel']) ?></td>
            </tr>
            <tr>
              <th class="text-muted">Jenis Sampel</th>
              <td>: <?= htmlspecialchars($permintaan['jenis_sampel']) ?></td>
            </tr>
            <tr>
              <th class="text-muted">Kategori</th>
              <td>: <?= htmlspecialchars($permintaan['kategori_sampel'] ?? '-') ?></td>
            </tr>
            <tr>
              <th class="text-muted">Pengirim</th>
              <td>: <?= htmlspecialchars($permintaan['nama'] ?? '-') ?></td>
            </tr>
          </table>
        </div>
        <div class="col-md-6">
          <div class="alert alert-info border-0" style="background: rgba(13,110,253,.05); border-radius: 12px;">
            <i class="bi bi-info-circle-fill me-2 text-primary"></i>
            Silakan masukkan <strong>Nomor Registrasi</strong> resmi yang terdapat di buku register laboratorium untuk sample ini.
          </div>
        </div>
      </div>

      <hr class="mb-4 text-muted">

      <form method="post" action="<?= site_url('k-panel/permintaan_sample/verifikasi/' . $permintaan['id_permintaan']) ?>" id="formVerifikasi" novalidate>
        <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">

        <div class="row mb-4">
          <div class="col-md-12 mb-4">
            <h5 class="fw-bold mb-3"><i class="bi bi-list-check me-2 text-primary"></i>Pilih Parameter Pemeriksaan</h5>
            <div class="alert alert-secondary" style="font-size: 0.9rem;">
              Silakan sesuaikan parameter pemeriksaan jika ada perubahan dari permintaan awal.
            </div>

            <div class="row g-3">
              <?php
              $kategori = $permintaan['kategori_sampel'] ?? $permintaan['jenis_sampel'] ?? '';
              $master_group = [];
              if (stripos($kategori, 'air minum') !== false) $master_group = $master_air_minum;
              elseif (stripos($kategori, 'air bersih') !== false) $master_group = $master_air_bersih;
              elseif (stripos($kategori, 'makanan') !== false) $master_group = $master_makanan;
              elseif (stripos($kategori, 'lingkungan') !== false) $master_group = $master_lingkungan;
              else {
                // Merge all if category is unknown or "Lainnya"
                $master_group = array_merge($master_air_minum, $master_air_bersih, $master_makanan, $master_lingkungan);
              }

              if (!empty($master_group)) {
                foreach ($master_group as $kelompok => $rows) {
                  echo '<div class="col-12 mt-3"><h6 class="fw-bold text-muted border-bottom pb-2">Klp. ' . htmlspecialchars(normalize_kelompok($kelompok, false)) . '</h6></div>';
                  foreach ($rows as $r) {
                    $id = (int)$r['id_master_pemeriksaan'];
                    $is_checked = isset($selected[$id]) ? 'checked' : '';
                    $nama = htmlspecialchars($r['nama_pemeriksaan'] ?? $r['nama']);
                    echo '
                        <div class="col-lg-3 col-md-4 col-sm-6">
                            <div class="form-check p-3 border rounded h-100 bg-light" style="cursor: pointer;" onclick="var cb = document.getElementById(\'param_' . $id . '\'); if(event.target.tagName !== \'INPUT\') { cb.checked = !cb.checked; }">
                                <input class="form-check-input ms-0 me-2" type="checkbox" name="pemeriksaan[]" value="' . $id . '" id="param_' . $id . '" ' . $is_checked . '>
                                <label class="form-check-label" for="param_' . $id . '" style="cursor: pointer;">' . $nama . '</label>
                            </div>
                        </div>';
                  }
                }
              } else {
                echo '<div class="col-12"><div class="alert alert-warning">Tidak ada parameter ditemukan untuk kategori ini.</div></div>';
              }
              ?>
            </div>
          </div>
        </div>

        <hr class="mb-4 text-muted">

        <div class="row mb-4">
          <div class="col-md-6">
            <label class="form-label fw-bold">Nomor Registrasi</label>
            <input type="text" class="form-control form-control-lg" name="no_registrasi" value="<?= htmlspecialchars($permintaan['no_registrasi'] ?? '') ?>" placeholder="Ketik No. Registrasi (Opsional)" autofocus autocomplete="off">
            <div class="form-text mt-2">Nomor registrasi sementara dari pendaftaran publik akan ditimpa dengan nomor ini.</div>
          </div>
        </div>

        <div class="d-flex gap-2">
          <button type="button" class="btn btn-primary" style="border-radius: 12px; font-weight: 700; padding: 0.6rem 1.5rem;" onclick="this.innerHTML='<i class=\'bi bi-hourglass-split me-1\'></i>Menyimpan...'; this.style.pointerEvents='none'; document.getElementById('formVerifikasi').submit();">
            <i class="bi bi-check2-circle me-1"></i>Simpan & Terima Sample
          </button>
          <a href="<?= site_url('k-panel/permintaan_sample') ?>" class="btn btn-light" style="border-radius: 12px; font-weight: 600; padding: 0.6rem 1.5rem;">
            Batal
          </a>
        </div>
      </form>
    </div>
  </div>
</div>
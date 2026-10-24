<?php

/**
 * Permintaan Form View
 * 
 * @var string $mode - 'create' or 'edit'
 * @var array|null $permintaan - Permintaan data array (null for create, array for edit)
 * @var string $no_registrasi - Registration number
 * @var array $petugas - List of petugas
 * @var array $tindakan_sampel - List of tindakan sampel
 * @var array $master_air_minum - Master air minum grouped
 * @var array $master_air_bersih - Master air bersih grouped
 * @var array $master_makanan - Master makanan grouped
 * @var array $master_lingkungan - Master lingkungan grouped
 * @var array $selected_ids - Selected master IDs
 * @var string $title - Page title
 */

// =============================
// UI WOW + tetap logic sama
// =============================

$action = ($mode === 'edit')
  ? site_url('k-panel/form_permintaan_kesmas/update/' . (int)$permintaan['id_permintaan'])
  : site_url('k-panel/form_permintaan_kesmas/store');
?>

<!-- Optional (kalau belum ada di layout/header): Bootstrap Icons -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

<style>
  :root {
    --ink: #0b1220;
    --muted: #667085;
    --line: rgba(15, 23, 42, .10);
    --glass: rgba(255, 255, 255, .72);
    --glass2: rgba(255, 255, 255, .58);
    --shadow: 0 26px 80px rgba(2, 6, 23, .14);
    --shadow2: 0 14px 36px rgba(2, 6, 23, .10);
    --ring: rgba(13, 110, 253, .20);
    --brand: #0d6efd;
    --brand2: #0b5ed7;
    --green: #22c55e;
    --amber: #f59e0b;
    --violet: #6366f1;
  }

  .ui-polish-container {
    padding: 0;
  }

  .page-hero {
    border-radius: 26px;
    border: 1px solid rgba(15, 23, 42, .10);
    background:
      radial-gradient(900px 340px at 12% 10%, rgba(13, 110, 253, .20), transparent 62%),
      radial-gradient(900px 340px at 92% 20%, rgba(34, 197, 94, .10), transparent 62%),
      radial-gradient(900px 340px at 55% 110%, rgba(99, 102, 241, .12), transparent 62%),
      linear-gradient(180deg, rgba(255, 255, 255, .92), rgba(255, 255, 255, .70));
    box-shadow: var(--shadow);
    padding: 1.25rem 1.35rem;
    position: relative;
    overflow: hidden;
    margin-bottom: 1rem;
  }

  .page-hero h3 {
    margin: 0;
    font-weight: 950;
    letter-spacing: -.5px;
    color: var(--ink);
  }

  .page-hero .sub {
    color: var(--muted);
    margin-top: .25rem;
  }

  .btn-back {
    border-radius: 16px;
    font-weight: 900;
    padding: .60rem .9rem;
  }

  .card-wow {
    border-radius: 22px;
    border: 1px solid rgba(15, 23, 42, .10);
    background: var(--glass);
    backdrop-filter: blur(12px);
    box-shadow: var(--shadow2);
    overflow: hidden;
  }

  .card-wow .card-header {
    background:
      linear-gradient(180deg, rgba(255, 255, 255, .86), rgba(255, 255, 255, .72));
    border-bottom: 1px solid rgba(15, 23, 42, .08);
    padding: .95rem 1.05rem;
  }

  .card-wow .card-body {
    padding: 1.05rem 1.05rem;
  }

  .section-title {
    display: flex;
    align-items: center;
    gap: .6rem;
    font-weight: 950;
    letter-spacing: -.3px;
    color: var(--ink);
    margin: 0;
  }

  .section-title i {
    color: var(--brand);
    font-size: 1.1rem;
  }

  .form-label {
    font-weight: 800;
    color: #334155;
  }

  .form-control,
  .form-select {
    border-radius: 12px;
    border: 1px solid #94a3b8;
    padding: .78rem .9rem;
  }

  .form-control:not([readonly]),
  .form-select:not([disabled]) {
    background: #ffffff;
  }

  .form-control[readonly],
  .form-select[disabled] {
    background: #f1f5f9;
    color: #475569;
  }

  .form-control:focus,
  .form-select:focus {
    border-color: rgba(13, 110, 253, .45);
    box-shadow: 0 0 0 .25rem var(--ring);
  }

  .divider-soft {
    height: 1px;
    background: rgba(15, 23, 42, .10);
    margin: 1.1rem 0;
  }

  /* Tabs WOW */
  .tabs-wow {
    border-radius: 18px;
    border: 1px solid rgba(15, 23, 42, .10);
    background: rgba(255, 255, 255, .62);
    padding: .55rem;
    gap: .4rem;
  }

  .tabs-wow .nav-link {
    border: 1px solid transparent;
    border-radius: 14px;
    font-weight: 900;
    color: #334155;
    padding: .55rem .85rem;
  }

  .tabs-wow .nav-link:hover {
    background: rgba(13, 110, 253, .06);
    border-color: rgba(13, 110, 253, .10);
  }

  .tabs-wow .nav-link.active {
    background: linear-gradient(180deg, var(--brand), var(--brand2));
    color: #fff;
    box-shadow: 0 16px 32px rgba(13, 110, 253, .18);
  }

  .tab-shell {
    margin-top: .75rem;
    border-radius: 18px;
    border: 1px solid rgba(15, 23, 42, .10);
    background: rgba(248, 250, 252, .72);
    padding: 1rem;
  }

  .group-head {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: .75rem;
    margin-bottom: .5rem;
  }

  .group-title {
    font-weight: 950;
    color: #334155;
    font-size: .9rem;
    letter-spacing: .2px;
  }

  .btn-mini {
    border-radius: 14px;
    font-weight: 900;
    padding: .35rem .55rem;
  }

  .check-tile {
    border-radius: 16px;
    border: 1px solid rgba(15, 23, 42, .10);
    background: rgba(255, 255, 255, .68);
    padding: .65rem .75rem;
    transition: .14s ease;
  }

  .check-tile:hover {
    transform: translateY(-1px);
    box-shadow: 0 14px 28px rgba(2, 6, 23, .08);
  }

  .check-tile .form-check-input {
    cursor: pointer;
  }

  .check-tile .form-check-label {
    cursor: pointer;
    font-weight: 700;
    color: #334155;
  }

  .sticky-save {
    position: sticky;
    bottom: .75rem;
    z-index: 2;
    border-radius: 22px;
    border: 1px solid rgba(15, 23, 42, .10);
    background: rgba(255, 255, 255, .72);
    backdrop-filter: blur(12px);
    box-shadow: var(--shadow2);
    padding: .85rem;
    margin-top: 1rem;
  }

  .btn-save {
    border-radius: 18px;
    font-weight: 950;
    letter-spacing: .2px;
    padding: .9rem 1rem;
    border: none;
    background: linear-gradient(180deg, var(--brand), var(--brand2));
    box-shadow: 0 18px 34px rgba(13, 110, 253, .18);
  }

  .btn-save:hover {
    transform: translateY(-1px);
  }

  .btn-save:active {
    transform: translateY(0);
  }

  @media (max-width: 767.98px) {
    .page-hero {
      padding: 1.05rem;
      border-radius: 22px;
    }

    .card-wow {
      border-radius: 18px;
    }

    .tab-shell {
      padding: .85rem;
    }
  }
</style>

<div class="container-fluid ui-polish-container">

  <!-- HERO -->
  <div class="page-hero">
    <div class="d-flex align-items-start justify-content-between gap-3 flex-wrap">
      <div>
        <h3><?= ($mode === 'edit') ? 'Edit' : 'Tambah' ?> Permintaan</h3>
        <div class="sub">Formulir untuk pengajuan permintaan kesmas.</div>
      </div>
      <a class="btn btn-outline-secondary btn-back" href="<?= site_url('k-panel/form_permintaan_kesmas') ?>">
        <i class="bi bi-arrow-left me-1" aria-hidden="true"></i>Kembali
      </a>
    </div>
  </div>

  <form method="post" action="<?= $action ?>" id="formPermintaan" novalidate>
    <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
    <?php if (isset($is_accept) && $is_accept): ?>
      <input type="hidden" name="accept" value="1">
    <?php endif; ?>

    <div class="row g-3">
      <!-- Identitas Sampel (Kiri Atas) -->
      <div class="col-lg-6">
        <div class="card card-wow h-100">
          <div class="card-header">
            <div class="section-title">
              <i class="bi bi-droplet-half" aria-hidden="true"></i>
              <span>1. Detail Sampel</span>
            </div>
          </div>
          <div class="card-body">
            <div class="row g-2">
              <div class="col-md-6">
                <label class="form-label">Nomor Registrasi</label>
                <input class="form-control" name="no_registrasi" value="<?= htmlspecialchars($no_registrasi ?? '') ?>">
              </div>

              <div class="col-md-6">
                <label class="form-label">Kategori Sampel</label>
                <?php
                $ks = (string)($permintaan['id_kategori_sampel'] ?? '');
                if ($ks === '1' || $ks === '2') $ks_view = 'Air';
                elseif ($ks === '3') $ks_view = 'Makanan';
                elseif ($ks === '4') $ks_view = 'Lingkungan';
                else $ks_view = $ks;

                $kategori_options = ['Air' => 'Air', 'Makanan' => 'Makanan', 'Lingkungan' => 'Lingkungan'];
                ?>
                <select class="form-select" name="id_kategori_sampel" required>
                  <option value="">- Pilih Kategori -</option>
                  <?php foreach ($kategori_options as $key => $label): ?>
                    <option value="<?= htmlspecialchars($key) ?>" <?= ($ks_view === (string)$key) ? 'selected' : '' ?>><?= htmlspecialchars($label) ?></option>
                  <?php endforeach; ?>
                </select>
              </div>

              <div class="col-md-6">
                <label class="form-label">Nama Sampel</label>
                <input class="form-control" name="nama_sampel" value="<?= htmlspecialchars($permintaan['nama_sampel'] ?? '') ?>">
              </div>

              <div class="col-md-6">
                <label class="form-label">Jenis Sampel</label>
                <?php
                $js = $permintaan['jenis_sampel'] ?? '';
                $is_custom = true;
                if (empty($js)) {
                  $is_custom = false;
                } else {
                  foreach ($jenis_sampel_master ?? [] as $row) {
                    if ($js === $row['nama']) {
                      $is_custom = false;
                      break;
                    }
                  }
                }
                ?>
                <select class="form-select" name="jenis_sampel" id="jenis_sampel_select" required>
                  <option value="">- Pilih Jenis -</option>
                  <?php if (isset($jenis_sampel_master)): foreach ($jenis_sampel_master as $row):
                      $kat_group = '';
                      if ($row['nama'] === 'Air Minum' || $row['nama'] === 'Air Bersih / Air Sanitasi') $kat_group = 'Air';
                      elseif ($row['nama'] === 'Makanan') $kat_group = 'Makanan';
                      elseif ($row['nama'] === 'Lingkungan') $kat_group = 'Lingkungan';
                      else $kat_group = 'Lainnya';
                  ?>
                      <option value="<?= htmlspecialchars($row['nama']) ?>" data-kategori="<?= $kat_group ?>" <?= ($js === $row['nama']) ? 'selected' : '' ?>><?= htmlspecialchars($row['nama']) ?></option>
                  <?php endforeach;
                  endif; ?>
                  <option value="__lainnya__" <?= $is_custom ? 'selected' : '' ?>>Lainnya (Isi Manual)</option>
                </select>
                <input class="form-control mt-2" type="text" id="jenis_sampel_manual" name="jenis_sampel_manual"
                  value="<?= $is_custom ? htmlspecialchars($js) : '' ?>"
                  style="display: <?= $is_custom ? 'block' : 'none' ?>;">
              </div>

              <div class="col-md-4">
                <label class="form-label">Volume (ml)</label>
                <input class="form-control" type="number" name="volume_ml" value="<?= htmlspecialchars($permintaan['volume_ml'] ?? '') ?>">
              </div>
              <div class="col-md-4">
                <label class="form-label">Tgl Pengambilan</label>
                <input class="form-control" type="date" name="tgl_pengambilan" value="<?= htmlspecialchars($permintaan['tgl_pengambilan'] ?? '') ?>">
              </div>
              <div class="col-md-4">
                <label class="form-label">Jam Pengambilan</label>
                <input class="form-control" type="time" name="jam_pengambilan" value="<?= htmlspecialchars($permintaan['jam_pengambilan'] ?? '') ?>">
              </div>

              <div class="col-md-12">
                <label class="form-label">Lokasi Pengambilan</label>
                <input class="form-control" name="lokasi_pengambilan" value="<?= htmlspecialchars($permintaan['lokasi_pengambilan'] ?? '') ?>">
              </div>

              <div class="col-md-6">
                <div class="d-flex justify-content-between align-items-center mb-1">
                  <label class="form-label mb-0">Petugas Pendaftaran</label>
                </div>
                <input class="form-control" type="text" value="<?= htmlspecialchars($current_user['nama'] ?? $this->session->userdata('nama') ?? 'Otomatis (Sesuai Akun Login)') ?>" readonly disabled>
                <div class="form-text small text-muted">Petugas pengambil akan ditentukan di tahap kaji ulang.</div>
              </div>

              <div class="col-md-6">
                <label class="form-label">Tindakan Sampel</label>
                <?php $ts = $permintaan['tindakan_sampel'] ?? ''; ?>
                <select class="form-select" name="tindakan_sampel" required>
                  <option value="">- Pilih Tindakan -</option>
                  <?php if (isset($tindakan_sampel) && is_array($tindakan_sampel)): ?>
                    <?php foreach ($tindakan_sampel as $t): ?>
                      <option value="<?= htmlspecialchars($t['nama']) ?>" <?= ($ts === $t['nama']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($t['nama']) ?>
                      </option>
                    <?php endforeach; ?>
                  <?php endif; ?>
                </select>
              </div>

              <div class="col-md-12">
                <label class="form-label">Informasi Tambahan</label>
                <textarea class="form-control" name="info_tambahan" rows="1"><?= htmlspecialchars($permintaan['info_tambahan'] ?? '') ?></textarea>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Identitas Pengirim (Kanan Atas) -->
      <div class="col-lg-6">
        <div class="card card-wow h-100">
          <div class="card-header">
            <div class="section-title">
              <i class="bi bi-person-vcard" aria-hidden="true"></i>
              <span>2. Identitas Pengirim</span>
            </div>
          </div>
          <div class="card-body">
            <div class="row g-2">
              <div class="col-md-6">
                <label class="form-label">NIK</label>
                <input class="form-control" name="nik" maxlength="20" value="<?= htmlspecialchars($permintaan['nik'] ?? '') ?>">
              </div>
              <div class="col-md-6">
                <label class="form-label">Nama Pengirim</label>
                <input class="form-control" name="nama" value="<?= htmlspecialchars($permintaan['nama'] ?? '') ?>">
              </div>
              <div class="col-md-6">
                <label class="form-label">No. Telp / HP</label>
                <input class="form-control" name="telp" value="<?= htmlspecialchars($permintaan['telp'] ?? '') ?>">
              </div>
              <div class="col-md-6">
                <label class="form-label">Instansi / Perusahaan</label>
                <input class="form-control" name="instansi" value="<?= htmlspecialchars($permintaan['instansi'] ?? '') ?>">
              </div>
              <div class="col-md-6">
                <label class="form-label">Kecamatan</label>
                <select class="form-select" name="kecamatan" id="kec_select">
                  <option value="">- Pilih Kecamatan -</option>
                  <?php foreach (($data_wilayah ?? []) as $kec => $kels): ?>
                    <option value="<?= htmlspecialchars($kec) ?>" <?= (($permintaan['kecamatan'] ?? '') === $kec) ? 'selected' : '' ?>><?= htmlspecialchars($kec) ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div class="col-md-6">
                <label class="form-label">Kelurahan</label>
                <select class="form-select" name="kelurahan" id="kel_select">
                  <option value="">- Pilih Kelurahan -</option>
                </select>
              </div>
              <div class="col-md-12">
                <label class="form-label">Detail Jalan / RT / RW</label>
                <input class="form-control" id="detail_jalan" value="" placeholder="Contoh: Jl. Sudirman No. 10">
              </div>
              <div class="col-md-12">
                <label class="form-label">Alamat Lengkap (Tersimpan)</label>
                <textarea class="form-control" name="alamat" id="alamat_lengkap" rows="2"><?= htmlspecialchars($permintaan['alamat'] ?? '') ?></textarea>
              </div>
              <div class="col-md-6">
                <label class="form-label">Objek Pemeriksaan</label>
                <select class="form-select" name="objek">
                  <option value="">- Pilih Objek -</option>
                  <?php foreach (['Depot Air Minum (DAM)', 'PDAM', 'Masyarakat', 'Rumah Sakit/Klinik', 'Industri', 'Restoran/Rumah Makan/Catering', 'Hotel', 'Lainnya'] as $ob): ?>
                    <option value="<?= htmlspecialchars($ob) ?>" <?= (($permintaan['objek'] ?? '') === $ob) ? 'selected' : '' ?>><?= htmlspecialchars($ob) ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div class="col-md-6">
                <label class="form-label">Tanggal Permintaan</label>
                <input class="form-control" type="date" name="tgl_permintaan" value="<?= htmlspecialchars($permintaan['tgl_permintaan'] ?? date('Y-m-d')) ?>">
              </div>

            </div>
          </div>
        </div>
      </div>

      <!-- Full Width: Pemeriksaan -->
      <div class="col-lg-12">
        <div class="card card-wow">
          <div class="card-header">
            <div class="section-title">
              <i class="bi bi-list-check" aria-hidden="true"></i>
              <span>3. Pemeriksaan (Checklist Parameter)</span>
            </div>
          </div>
          <div class="card-body">

            <ul class="nav nav-pills tabs-wow" role="tablist">
              <li class="nav-item">
                <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tab-airminum" type="button">
                  <i class="bi bi-droplet me-1" aria-hidden="true"></i>Air Minum
                </button>
              </li>
              <li class="nav-item">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-airbersih" type="button">
                  <i class="bi bi-water me-1" aria-hidden="true"></i>Air Bersih
                </button>
              </li>
              <li class="nav-item">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-makanan" type="button">
                  <i class="bi bi-basket2 me-1" aria-hidden="true"></i>Makanan
                </button>
              </li>
              <li class="nav-item">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-lingkungan" type="button">
                  <i class="bi bi-house-heart me-1" aria-hidden="true"></i>Lingkungan
                </button>
              </li>
            </ul>

            <div class="tab-content tab-shell">
              <?php
              $selected = array_flip(array_map('intval', $selected_ids ?? array()));

              $render_group = function ($groups, $selected, $category_slug) {
                $i = 0;
                foreach ($groups as $kelompok => $rows) {
                  $i++;
                  $cls = 'ui-polish-checkbox-' . $category_slug . '-g' . $i; // tanpa dot untuk class
                  echo '<div class="mb-3 group-container">';
                  echo '  <div class="group-head">';
                  echo '    <div class="group-title">' . htmlspecialchars(kesmas_kelompok_title($category_slug, $kelompok)) . '</div>';
                  echo '    <div class="d-flex gap-2 flex-wrap">
                                <button type="button" class="btn btn-sm btn-outline-primary btn-mini" data-check-all=".' . $cls . '">
                                  <i class="bi bi-check-all me-1" aria-hidden="true"></i>Pilih Semua
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-secondary btn-mini" data-uncheck-all=".' . $cls . '">
                                  <i class="bi bi-x me-1" aria-hidden="true"></i>Kosongkan
                                </button>
                              </div>';
                  echo '  </div>';

                  // Karena full width, kita gunakan col-lg-3 agar kolom checklist lebih proporsional
                  echo '  <div class="row g-2">';
                  foreach ($rows as $r) {
                    $id = (int)$r['id_master_pemeriksaan'];
                    $checked = isset($selected[$id]) ? 'checked' : '';
                    echo '    <div class="col-lg-3 col-md-4 col-sm-6 check-item-col">
                                  <div class="check-tile h-100">
                                    <div class="form-check mb-0">
                                      <input class="form-check-input ' . $cls . '" type="checkbox" name="pemeriksaan[]" value="' . $id . '" id="pemeriksaan_' . $id . '" ' . $checked . '>
                                      <label class="form-check-label d-block" for="pemeriksaan_' . $id . '">' . htmlspecialchars($r['nama_pemeriksaan'] ?? $r['nama']) . '</label>
                                    </div>
                                  </div>
                                </div>';
                  }
                  echo '  </div>';
                  echo '</div>';
                }
              };
              ?>

              <div class="tab-pane fade show active" id="tab-airminum">
                <?php $render_group($master_air_minum, $selected, 'air_minum'); ?>
              </div>

              <div class="tab-pane fade" id="tab-airbersih">
                <?php $render_group($master_air_bersih, $selected, 'air_bersih'); ?>
              </div>

              <div class="tab-pane fade" id="tab-makanan">
                <?php $render_group($master_makanan, $selected, 'makanan'); ?>
              </div>

              <div class="tab-pane fade" id="tab-lingkungan">
                <?php $render_group($master_lingkungan, $selected, 'lingkungan'); ?>
              </div>

            </div>
          </div>
        </div>
      </div>

      <div class="col-lg-12">
        <div class="card card-wow h-100">
          <div class="card-header">
            <div class="section-title">
              <i class="bi bi-journal-text" aria-hidden="true"></i>
              <span>4. Catatan Tambahan</span>
            </div>
          </div>
          <div class="card-body">
            <textarea class="form-control h-100" name="catatan" rows="4"><?= htmlspecialchars($permintaan['catatan'] ?? '') ?></textarea>
          </div>
        </div>
      </div>

    </div>

    <!-- Sticky save bar -->
    <div class="sticky-save d-flex align-items-center justify-content-between gap-3 flex-wrap">
      <div class="text-muted small">
      </div>
      <div class="d-flex gap-2">
        <a class="btn btn-outline-secondary btn-back" href="<?= site_url('k-panel/form_permintaan_kesmas') ?>">
          <i class="bi bi-x-lg me-1" aria-hidden="true"></i>Batal
        </a>
        <button type="button" class="btn btn-primary btn-save" onclick="this.innerHTML='<i class=\'bi bi-hourglass-split me-1\'></i>Menyimpan...'; this.style.pointerEvents='none'; document.getElementById('formPermintaan').submit();">
          <i class="bi bi-save2 me-1" aria-hidden="true"></i>Simpan Permintaan
        </button>
      </div>
    </div>

  </form>
</div> <!-- Close ui-polish-container -->

<script>
  (function() {
    var sel = document.getElementById('jenis_sampel_select');
    var manual = document.getElementById('jenis_sampel_manual');
    if (!sel || !manual) return;

    function toggleManual() {
      if (sel.value === '__lainnya__') {
        manual.style.display = 'block';
        manual.required = true;
      } else {
        manual.style.display = 'none';
        manual.required = false;
      }
    }
    sel.addEventListener('change', toggleManual);

    // Saat submit: jika "Lainnya", gunakan nilai manual sebagai jenis_sampel
    var form = document.getElementById('formPermintaan');
    if (form) {
      form.addEventListener('submit', function(e) {
        if (sel.value === '__lainnya__') {
          var manualVal = manual.value.trim();
          if (!manualVal) {
            e.preventDefault();
            manual.focus();
            alert('Harap isi jenis sampel terlebih dahulu.');
            return;
          }
          // Ubah value select supaya dikirim sebagai nilai manual
          // Tambah hidden input dengan name "jenis_sampel" yang menimpa select
          var h = document.createElement('input');
          h.type = 'hidden';
          h.name = 'jenis_sampel';
          h.value = manualVal;
          form.appendChild(h);
          // Nonaktifkan select supaya tidak double-submit
          sel.disabled = true;
        }
      });
    }

    // Handle auto-check & disable checkboxes by jenis sampel
    function handleJenisSampelChange(e) {
      var isUserAction = e ? true : false;
      var val = sel.value;

      // Jika tidak ada atau 'lainnya', tampilkan semua tab dan aktifkan semua checkbox
      if (val === '__lainnya__' || val === '') {
        document.querySelectorAll('.tab-shell .tab-pane').forEach(function(pane) {
          pane.querySelectorAll('.form-check-input').forEach(function(cb) {
            cb.disabled = false;
          });
          var navBtn = document.querySelector('button[data-bs-target="#' + pane.id + '"]');
          if (navBtn) navBtn.parentElement.style.display = '';
        });
        // Keyword mapping untuk jenis manual: arahkan tab aktif TANPA mengunci tab lain
        // (loose mode — parameter Air Minum yang sah utk Air Bersih, mis. Kromium, tetap tersedia)
        var manualText = (manual && manual.value ? manual.value : '');
        var looseBtnSelector = '';
        if (/(bersih|sanitasi|higiene)/i.test(manualText)) {
          looseBtnSelector = 'button[data-bs-target="#tab-airbersih"]';
        } else if (/(minum|pdam|tirta)/i.test(manualText)) {
          looseBtnSelector = 'button[data-bs-target="#tab-airminum"]';
        }
        if (looseBtnSelector) {
          var looseBtn = document.querySelector(looseBtnSelector);
          if (looseBtn) {
            if (typeof bootstrap !== 'undefined' && bootstrap.Tab) {
              new bootstrap.Tab(looseBtn).show();
            } else {
              looseBtn.click();
            }
          }
        }
        return;
      }

      // Uncheck all first when changing type (only if user triggered it)
      if (isUserAction) {
        document.querySelectorAll('.tab-shell .form-check-input').forEach(function(cb) {
          cb.checked = false;
        });
      }

      var targetTabId = '';
      var targetTabBtnSelector = '';

      if (val === 'Air Minum') {
        targetTabId = '#tab-airminum';
        targetTabBtnSelector = 'button[data-bs-target="#tab-airminum"]';
      } else if (val === 'Air Bersih / Air Sanitasi' || val === 'Air Bersih') {
        targetTabId = '#tab-airbersih';
        targetTabBtnSelector = 'button[data-bs-target="#tab-airbersih"]';
      } else if (val === 'Makanan') {
        targetTabId = '#tab-makanan';
        targetTabBtnSelector = 'button[data-bs-target="#tab-makanan"]';
      } else if (val === 'Lingkungan') {
        targetTabId = '#tab-lingkungan';
        targetTabBtnSelector = 'button[data-bs-target="#tab-lingkungan"]';
      }

      if (targetTabId) {
        // Switch tab
        var tabBtn = document.querySelector(targetTabBtnSelector);
        if (tabBtn) {
          if (typeof bootstrap !== 'undefined' && bootstrap.Tab) {
            var tab = new bootstrap.Tab(tabBtn);
            tab.show();
          } else {
            tabBtn.click();
          }
        }

        var formMode = '<?= $mode ?? 'create' ?>';
        // Disable checkboxes and hide non-matching tabs
        document.querySelectorAll('.tab-shell .tab-pane').forEach(function(pane) {
          var isTarget = ('#' + pane.id === targetTabId);

          pane.querySelectorAll('.form-check-input').forEach(function(cb) {
            if (!isTarget) {
              cb.disabled = true;
            } else {
              cb.disabled = false;
            }
          });

          // Action buttons should be hidden if restricted
          pane.querySelectorAll('.group-container').forEach(function(grp) {
            var btnGroup = grp.querySelector('.d-flex.gap-2');
            if (btnGroup) {
              btnGroup.style.display = '';
            }
          });

          var navBtn = document.querySelector('button[data-bs-target="#' + pane.id + '"]');
          if (navBtn) navBtn.parentElement.style.display = isTarget ? '' : 'none';
        });
      }
    }

    sel.addEventListener('change', handleJenisSampelChange);

    // Saat mengetik jenis manual, arahkan tab aktif sesuai keyword (hanya bila mode 'lainnya')
    if (manual) {
      manual.addEventListener('input', function() {
        if (sel.value === '__lainnya__') handleJenisSampelChange(false);
      });
    }

    // Run on page load for Edit mode (so it hides tabs correctly)
    handleJenisSampelChange(false);

    // Handle Pilih Semua / Kosongkan per grup
    document.querySelectorAll('[data-check-all]').forEach(function(btn) {
      btn.addEventListener('click', function(e) {
        e.preventDefault();
        var targetSelector = this.getAttribute('data-check-all');
        document.querySelectorAll(targetSelector).forEach(function(cb) {
          cb.checked = true;
        });
      });
    });

    document.querySelectorAll('[data-uncheck-all]').forEach(function(btn) {
      btn.addEventListener('click', function(e) {
        e.preventDefault();
        var targetSelector = this.getAttribute('data-uncheck-all');
        document.querySelectorAll(targetSelector).forEach(function(cb) {
          cb.checked = false;
        });
      });
    });

    // Filter Jenis Sampel based on Kategori
    var selKategori = document.querySelector('select[name="id_kategori_sampel"]');
    if (selKategori && sel) { // sel is jenis_sampel_select
      function filterJenis() {
        var kat = selKategori.value;
        var options = sel.querySelectorAll('option[data-kategori]');
        options.forEach(function(opt) {
          if (kat === '' || opt.getAttribute('data-kategori') === kat) {
            opt.style.display = '';
          } else {
            opt.style.display = 'none';
          }
        });
        if (sel.selectedIndex > 0) {
          var selectedOpt = sel.options[sel.selectedIndex];
          if (selectedOpt.style.display === 'none' && selectedOpt.value !== '__lainnya__') {
            sel.value = '';
            sel.dispatchEvent(new Event('change'));
          }
        }
      }
      selKategori.addEventListener('change', filterJenis);
      filterJenis();
    }

    // Handle NIK autofill
    const nikInput = document.querySelector('input[name="nik"]');
    if (nikInput) {
      nikInput.addEventListener('blur', function() {
        const nik = this.value.trim();
        if (nik.length > 5) {
          fetch('<?= site_url('k-panel/form_permintaan_kesmas/check_nik') ?>', {
              method: 'POST',
              credentials: 'same-origin',
              headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-Requested-With': 'XMLHttpRequest'
              },
              body: 'nik=' + encodeURIComponent(nik) + '&<?= $this->security->get_csrf_token_name() ?>=' + encodeURIComponent('<?= $this->security->get_csrf_hash() ?>')
            })
            .then(response => response.json())
            .then(res => {
              if (res.status === 'success' && res.data) {
                const fName = document.querySelector('input[name="nama"]');
                const fAlamat = document.querySelector('[name="alamat"]');
                const fTelp = document.querySelector('input[name="telp"]');
                const fInstansi = document.querySelector('input[name="instansi"]');

                if (fName && !fName.value) fName.value = res.data.nama;
                if (fAlamat && !fAlamat.value) fAlamat.value = res.data.alamat;
                if (fTelp && !fTelp.value) fTelp.value = res.data.telp;
                if (fInstansi && !fInstansi.value) fInstansi.value = res.data.instansi;
                if (typeof applyWilayah === 'function') {
                  applyWilayah(res.data.kecamatan, res.data.kelurahan, false);
                }
              } else if (res.status === 'not_found') {
                console.log('NIK tidak ditemukan dalam sistem.');
              } else {
                console.error('Check NIK Error:', res.message);
              }
            })
            .catch(err => {
              console.error('Fetch NIK gagal:', err);
              alert('Gagal mengambil data pelanggan: ' + err.message);
            });
        }
      });
    }
  })();
</script>

<script>
(function () {
  const dataWilayah = <?= json_encode($data_wilayah ?? []) ?>;
  const kecSelect = document.getElementById('kec_select');
  const kelSelect = document.getElementById('kel_select');
  const alamatEl = document.getElementById('alamat_lengkap');
  const detailEl = document.getElementById('detail_jalan');
  if (!kecSelect || !kelSelect || !alamatEl) return;

  function updateKelurahan() {
    const kec = kecSelect.value;
    kelSelect.innerHTML = '<option value="">- Pilih Kelurahan -</option>';
    if (kec && dataWilayah[kec]) {
      dataWilayah[kec].forEach(function (kel) {
        const opt = document.createElement('option');
        opt.value = kel;
        opt.textContent = kel;
        kelSelect.appendChild(opt);
      });
    }
    updateAlamatLengkap();
  }

  function updateAlamatLengkap() {
    const kec = kecSelect.value;
    const kel = kelSelect.value;
    const detail = detailEl ? detailEl.value.trim() : '';
    const hasil = [];
    if (detail !== '') hasil.push(detail);
    if (kel !== '') hasil.push('Kel. ' + kel);
    if (kec !== '') hasil.push('Kec. ' + kec);
    alamatEl.value = hasil.join(', ');
  }

  window.applyWilayah = function (kec, kel, compose) {
    if (kec) {
      kecSelect.value = kec;
      updateKelurahan();
    }
    if (kel) kelSelect.value = kel;
    if (compose !== false) updateAlamatLengkap();
  };

  kecSelect.addEventListener('change', updateKelurahan);
  kelSelect.addEventListener('change', updateAlamatLengkap);
  if (detailEl) detailEl.addEventListener('input', updateAlamatLengkap);

  // Isi sesuai data tersimpan (mode edit)
  updateKelurahan();
  const kecTersimpan = <?= json_encode($permintaan['kecamatan'] ?? '') ?>;
  const kelTersimpan = <?= json_encode($permintaan['kelurahan'] ?? '') ?>;
  if (kecTersimpan) {
    kecSelect.value = kecTersimpan;
    updateKelurahan();
  }
  if (kelTersimpan) kelSelect.value = kelTersimpan;

  // Pecah alamat lama menjadi detail jalan (format "... Kel. X, Kec. Y")
  if (alamatEl.value) {
    let alamat = alamatEl.value;
    const kec = kecSelect.value;
    const kel = kelSelect.value;
    if (kel) alamat = alamat.replace(', Kel. ' + kel, '');
    if (kec) alamat = alamat.replace(', Kec. ' + kec, '');
    if (detailEl) detailEl.value = alamat.trim();
  }
})();
</script>

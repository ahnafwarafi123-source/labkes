<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * View untuk form kaji ulang sampel
 * @var array $permintaan - Data permintaan sampel dengan fields: id, no_registrasi, nama_sampel, jenis_sampel
 * @var array $petugas - Array petugas untuk dropdown
 */
?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

<style>
  :root {
    --ink: #0b1220;
    --muted: #667085;
    --shadow2: 0 12px 30px rgba(2,6,23,.10);
    --brand: #0d6efd;
    --brand2: #0b5ed7;
    --warning: #f59e0b;
  }

  .page-hero {
    border-radius: 26px;
    border: 1px solid rgba(15,23,42,.10);
    background: radial-gradient(900px 340px at 12% 10%, rgba(13,110,253,.20), transparent 62%),
                radial-gradient(900px 340px at 92% 20%, rgba(34,197,94,.10), transparent 62%),
                linear-gradient(180deg, rgba(255,255,255,.92), rgba(255,255,255,.70));
    box-shadow: var(--shadow2);
    padding: 1.25rem 1.35rem;
  }

  .page-hero h3 {
    margin: 0;
    font-weight: 950;
    color: var(--ink);
  }

  .card-section {
    border-radius: 18px;
    border: 1px solid rgba(15,23,42,.10);
    background: rgba(255,255,255,.92);
    margin-bottom: 1.5rem;
    box-shadow: var(--shadow2);
  }

  .card-section .card-title {
    border-bottom: 2px solid var(--brand);
    padding: 1rem;
    font-weight: 900;
    color: var(--ink);
    display: flex;
    align-items: center;
    gap: 0.5rem;
  }

  .card-section .card-body {
    padding: 1.5rem;
  }

  .form-group {
    margin-bottom: 1.25rem;
  }

  .form-label {
    font-weight: 800;
    color: var(--ink);
    margin-bottom: 0.5rem;
    display: block;
    font-size: 0.95rem;
  }

  .form-control, .form-select {
    border-radius: 12px;
    border: 1px solid rgba(15,23,42,.15);
    padding: 0.75rem 1rem;
    font-size: 0.95rem;
  }

  .form-control:focus, .form-select:focus {
    border-color: var(--brand);
    box-shadow: 0 0 0 0.25rem rgba(13,110,253,.20);
  }

  .checkbox-group {
    display: grid;
    gap: 0.75rem;
  }

  .checkbox-item {
    display: flex;
    align-items: center;
  }

  .checkbox-item input[type="checkbox"] {
    width: 20px;
    height: 20px;
    margin-right: 0.75rem;
    cursor: pointer;
    accent-color: var(--brand);
  }

  .checkbox-item label {
    cursor: pointer;
    margin: 0;
    font-weight: 600;
    color: var(--ink);
  }

  .radio-group {
    display: flex;
    gap: 2rem;
    margin-bottom: 1rem;
  }

  .radio-item {
    display: flex;
    align-items: center;
  }

  .radio-item input[type="radio"] {
    width: 20px;
    height: 20px;
    margin-right: 0.75rem;
    cursor: pointer;
    accent-color: var(--brand);
  }

  .radio-item label {
    cursor: pointer;
    margin: 0;
    font-weight: 600;
    color: var(--ink);
  }

  .grid-2 {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1.5rem;
  }

  .info-box {
    background: rgba(13,110,253,.08);
    border-left: 4px solid var(--brand);
    border-radius: 8px;
    padding: 1rem;
    margin-bottom: 1.5rem;
  }

  .info-box strong {
    color: var(--ink);
  }

  .btn-submit {
    background: linear-gradient(180deg, var(--brand), var(--brand2));
    border: none;
    color: #2d3748;
    font-weight: 900;
    padding: 0.75rem 2rem;
    border-radius: 12px;
    box-shadow: 0 12px 28px rgba(13,110,253,.18);
  }

  .btn-submit:hover {
    transform: translateY(-1px);
    color: #2d3748;
  }

  .btn-back {
    border-radius: 12px;
    font-weight: 900;
    padding: 0.65rem 1.5rem;
  }

  @media (max-width: 767.98px) {
    .grid-2 {
      grid-template-columns: 1fr;
    }
    .radio-group {
      flex-direction: column;
      gap: 0.75rem;
    }
  }
</style>

<!-- HERO HEADER -->
<div class="page-hero mb-4">
  <div class="d-flex align-items-start justify-content-between gap-3 flex-wrap">
    <div>
      <h3><i class="bi bi-check-circle me-2"></i>Kaji Ulang Permintaan Pemeriksaan</h3>
      <div class="text-muted small mt-2">No. Registrasi: <strong><?= htmlspecialchars($permintaan['no_registrasi']) ?></strong></div>
    </div>
    <a class="btn btn-outline-secondary btn-back" href="<?= site_url('k-panel/form_permintaan_kesmas') ?>">
      <i class="bi bi-arrow-left me-1"></i>Kembali
    </a>
  </div>
</div>

<!-- INFO SAMPEL -->
<div class="card-section">
  <div class="card-title">
    <i class="bi bi-capsule"></i> Identitas Sampel
  </div>
  <div class="card-body">
    <div class="grid-2">
      <div>
        <strong>Nama Sampel:</strong> <?= htmlspecialchars($permintaan['nama_sampel']) ?><br>
        <strong>Jenis Sampel:</strong> <?= htmlspecialchars($permintaan['jenis_sampel']) ?><br>
        <strong>Volume (ml):</strong> <?= htmlspecialchars($permintaan['volume_ml'] ?? '-') ?>
      </div>
      <div>
        <strong>Lokasi Pengambilan:</strong> <?= htmlspecialchars($permintaan['lokasi_pengambilan'] ?? '-') ?><br>
        <strong>Petugas Pengambil 1:</strong> <?= htmlspecialchars($permintaan['petugas_pengambil_name'] ?? '-') ?><br>
        <strong>Petugas Pengambil 2:</strong> <?= htmlspecialchars($permintaan['petugas_pengambil_2_name'] ?? '-') ?><br>
        <strong>Tindakan Sampel:</strong> <?= htmlspecialchars($permintaan['tindakan_sampel'] ?? '-') ?>
      </div>
    </div>
  </div>
</div>

<!-- FORM KAJI ULANG -->
<?php
  // Prepare selected alasan array for checkboxes
  $selected_alasan = [];
  $catatan_kaji = '';
  
  if (!empty($permintaan['catatan'])) {
    if (is_array($permintaan['catatan'])) {
      $selected_alasan = $permintaan['catatan'];
    } else {
      // It is stored as a comma-separated string now
      $parts = array_map('trim', explode(',', $permintaan['catatan']));
      $valid_reasons = ['Tidak Steril', 'Volume Tidak Mencukupi', 'Bahan Tidak Sesuai Permintaan', 'Waktu Tunggu Tidak Sesuai'];
      
      $is_reasons = false;
      foreach ($parts as $pt) {
          if (in_array($pt, $valid_reasons)) {
              $is_reasons = true;
              break;
          }
      }
      
      if ($is_reasons) {
          $selected_alasan = $parts;
      } else {
          $catatan_kaji = $permintaan['catatan'];
      }
    }
  }
?>
<form method="post" action="<?= site_url('k-panel/form_permintaan_kesmas/kaji_ulang_update/'.$permintaan['id_permintaan']) ?>" id="formKajiUlang" novalidate>
  <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">

  <!-- KELAYAKAN SAMPEL -->
  <div class="card-section">
    <div class="card-title">
      <i class="bi bi-exclamation-circle"></i> Kelayakan Sampel
    </div>
    <div class="card-body">
      <div class="radio-group mb-3">
        <div class="radio-item">
          <input type="radio" id="layak" name="status_kelayakan" value="Layak" <?= (isset($permintaan['status_kelayakan']) && $permintaan['status_kelayakan'] === 'Layak') ? 'checked' : '' ?>>
          <label for="layak">
            <i class="bi bi-check-circle-fill" style="color:#10b981;"></i> Layak
          </label>
        </div>
        <div class="radio-item">
          <input type="radio" id="tidak_layak" name="status_kelayakan" value="Tidak Layak" <?= (isset($permintaan['status_kelayakan']) && $permintaan['status_kelayakan'] === 'Tidak Layak') ? 'checked' : '' ?>>
          <label for="tidak_layak">
            <i class="bi bi-x-circle-fill" style="color:#ef4444;"></i> Tidak Layak
          </label>
        </div>
      </div>

      <div id="alasan_section" style="display: none;">
        <div id="alasan_alert" class="alert alert-danger" style="display:none;">
          <h5 class="mb-1">Sampel Tidak Layak untuk Pemeriksaan</h5>
          <p class="mb-1 small text-muted">Sampel ini dinyatakan tidak memenuhi persyaratan minimum untuk dilakukan pemeriksaan laboratorium. Mohon catat alasan dan tindak lanjut yang direkomendasikan.</p>
          <ul id="alasan_list_preview" style="margin-bottom:0; padding-left:1.2rem;"></ul>
        </div>

        <div class="form-label mb-3">Alasan Tidak Layak (Pilih yang sesuai):</div>
        <div class="checkbox-group">
          <div class="checkbox-item">
            <input type="checkbox" id="alasan1" name="alasan[]" value="Tidak Steril" <?= in_array('Tidak Steril', $selected_alasan) ? 'checked' : '' ?> >
            <label for="alasan1">Tidak Steril</label>
          </div>
          <div class="checkbox-item">
            <input type="checkbox" id="alasan2" name="alasan[]" value="Volume Tidak Mencukupi" <?= in_array('Volume Tidak Mencukupi', $selected_alasan) ? 'checked' : '' ?> >
            <label for="alasan2">Volume Tidak Mencukupi</label>
          </div>
          <div class="checkbox-item">
            <input type="checkbox" id="alasan3" name="alasan[]" value="Bahan Tidak Sesuai Permintaan" <?= in_array('Bahan Tidak Sesuai Permintaan', $selected_alasan) ? 'checked' : '' ?> >
            <label for="alasan3">Bahan Tidak Sesuai Permintaan</label>
          </div>
          <div class="checkbox-item">
            <input type="checkbox" id="alasan4" name="alasan[]" value="Waktu Tunggu Tidak Sesuai" <?= in_array('Waktu Tunggu Tidak Sesuai', $selected_alasan) ? 'checked' : '' ?> >
            <label for="alasan4">Waktu Tunggu Tidak Sesuai</label>
          </div>
        </div>
        
        <div class="form-group mt-3">
          <label class="form-label">Solusi / Catatan Penolakan (opsional, tampilkan pada laporan):</label>
          <textarea name="catatan" class="form-control" rows="3"><?= htmlspecialchars($catatan_kaji) ?></textarea>
        </div>
      </div>
    </div>
  </div>

  <!-- WAKTU PENGAMBILAN -->
  <div class="card-section">
    <div class="card-title">
      <i class="bi bi-calendar-event"></i> Waktu Pengambilan Sampel
    </div>
    <div class="card-body">
      <div class="grid-2">
        <div class="form-group">
          <label class="form-label">Tanggal Pengambilan</label>
          <input type="date" class="form-control" name="tgl_pengambilan" 
                 value="<?= isset($permintaan['tgl_pengambilan']) ? htmlspecialchars($permintaan['tgl_pengambilan']) : '' ?>">
        </div>
        <div class="form-group">
          <label class="form-label">Jam Pengambilan</label>
          <input type="time" class="form-control" name="jam_pengambilan"
                 value="<?= isset($permintaan['jam_pengambilan']) ? htmlspecialchars($permintaan['jam_pengambilan']) : '' ?>">
        </div>
      </div>
    </div>
  </div>

  <!-- PETUGAS KAJI ULANG -->
  <div class="card-section">
    <div class="card-title">
      <i class="bi bi-person-badge"></i> Penanggung Jawab & Verifikasi
    </div>
    <div class="card-body">
      <div class="grid-2">
        <div class="form-group">
          <label class="form-label">Petugas Pengambil Sampel 1</label>
          <select class="form-select" name="id_petugas">
            <option value="">- Pilih Petugas -</option>
            <?php foreach ($petugas as $pt): ?>
              <option value="<?= htmlspecialchars($pt['id_petugas']) ?>" <?= (isset($permintaan['id_petugas']) && $permintaan['id_petugas'] == $pt['id_petugas']) ? 'selected' : '' ?>>
                <?= htmlspecialchars($pt['nama']) ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="form-group">
          <label class="form-label">Petugas Pengambil Sampel 2 (Opsional)</label>
          <select class="form-select" name="id_petugas_2">
            <option value="">- Pilih Petugas -</option>
            <?php foreach ($petugas as $pt): ?>
              <option value="<?= htmlspecialchars($pt['id_petugas']) ?>" <?= (isset($permintaan['id_petugas_2']) && $permintaan['id_petugas_2'] == $pt['id_petugas']) ? 'selected' : '' ?>>
                <?= htmlspecialchars($pt['nama']) ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="form-group">
          <label class="form-label">Petugas Pengambil TTD</label>
          <select class="form-select" name="petugas_pengambil_ttd_id">
            <option value="">- Pilih Petugas -</option>
            <?php foreach ($petugas as $pt): ?>
              <option value="<?= htmlspecialchars($pt['id_petugas']) ?>" <?= (isset($permintaan['petugas_pengambil_ttd_id']) && $permintaan['petugas_pengambil_ttd_id'] == $pt['id_petugas']) ? 'selected' : '' ?>>
                <?= htmlspecialchars($pt['nama']) ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>
      <div class="form-group">
        <label class="form-label">Petugas Validasi (PJ Teknis)</label>
        <select class="form-select" name="petugas_validasi_id">
          <option value="">- Pilih PJ Teknis -</option>
          <?php foreach ($penanggung_jawab_teknis as $pj): ?>
            <option value="<?= htmlspecialchars($pj['id_penanggung_jawab']) ?>" <?= (isset($permintaan['petugas_validasi_id']) && $permintaan['petugas_validasi_id'] == $pj['id_penanggung_jawab']) ? 'selected' : '' ?>>
              <?= htmlspecialchars($pj['nama']) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>
    </div>
  </div>

  <!-- PEMBAYARAN -->
  <div class="card-section">
    <div class="card-title">
      <i class="bi bi-wallet2"></i> Pembayaran <small class="text-muted" style="font-weight:normal; font-size:0.85rem; margin-left:0.5rem;">(Pencatatan Manual)</small>
    </div>
    <div class="card-body">
      <div class="grid-2">
        <div class="form-group">
          <label class="form-label">Jumlah Biaya</label>
          <input class="form-control" type="number" name="jumlah_biaya" value="<?= htmlspecialchars($permintaan['jumlah_biaya'] ?? 0) ?>">
        </div>
        <div class="form-group">
          <label class="form-label">Cara Bayar</label>
          <?php $cb = $permintaan['cara_bayar'] ?? ''; ?>
          <select class="form-select" name="cara_bayar">
            <option value="">-</option>
            <option value="Tunai" <?= ($cb === 'Tunai') ? 'selected' : '' ?>>Tunai</option>
            <option value="Non Tunai" <?= ($cb === 'Non Tunai') ? 'selected' : '' ?>>Non Tunai</option>
            <option value="Lain-lain" <?= ($cb === 'Lain-lain') ? 'selected' : '' ?>>Lain-lain</option>
          </select>
        </div>
      </div>
      <div class="form-group">
        <label class="form-label">Jika lain-lain</label>
        <input class="form-control" name="keterangan_bayar" value="<?= htmlspecialchars($permintaan['keterangan_bayar'] ?? '') ?>">
      </div>
    </div>
  </div>


  <!-- INFORMASI TAMBAHAN -->
  <div class="card-section">
    <div class="card-title">
      <i class="bi bi-info-square"></i> Informasi Tambahan
    </div>
    <div class="card-body">
      <div class="form-group">
        <label class="form-label">Informasi Tambahan</label>
        <textarea class="form-control" name="info_tambahan" rows="4" 
                  placeholder="Tuliskan informasi tambahan sampel jika ada"><?= isset($permintaan['info_tambahan']) ? htmlspecialchars($permintaan['info_tambahan']) : '' ?></textarea>
      </div>
    </div>
  </div>

  <!-- BUTTON AKSI -->
  <div class="d-flex gap-2 mb-4">
    <button type="button" class="btn btn-submit" onclick="this.innerHTML='<i class=\'bi bi-hourglass-split me-1\'></i>Menyimpan...'; this.style.pointerEvents='none'; document.getElementById('formKajiUlang').submit();">
      <i class="bi bi-check-lg me-1"></i> Simpan Kaji Ulang
    </button>
    <a href="<?= site_url('k-panel/form_permintaan_kesmas') ?>" class="btn btn-outline-secondary btn-back">
      <i class="bi bi-x-lg me-1"></i> Batal
    </a>
  </div>
</form>

<!-- JAVASCRIPT UNTUK SHOW/HIDE ALASAN -->
<script>
  document.addEventListener('DOMContentLoaded', function() {
    const radioLayak = document.getElementById('layak');
    const radioTidakLayak = document.getElementById('tidak_layak');
    const alasanSection = document.getElementById('alasan_section');
    const alasanCheckboxes = document.querySelectorAll('input[name="alasan[]"]');

    function updateAlasanPreview() {
      const alertBox = document.getElementById('alasan_alert');
      const list = document.getElementById('alasan_list_preview');
      list.innerHTML = '';
      const selected = [];
      alasanCheckboxes.forEach(cb => { if (cb.checked) selected.push(cb.value); });
      if (selected.length > 0) {
        alertBox.style.display = 'block';
        selected.forEach(s => {
          const li = document.createElement('li');
          li.textContent = s;
          list.appendChild(li);
        });
      } else {
        alertBox.style.display = 'none';
      }
    }

    function toggleAlasanSection() {
      if (radioTidakLayak.checked) {
        alasanSection.style.display = 'block';
        updateAlasanPreview();
      } else {
        alasanSection.style.display = 'none';
        alasanCheckboxes.forEach(cb => cb.checked = false);
        updateAlasanPreview();
      }
    }

    radioLayak.addEventListener('change', toggleAlasanSection);
    radioTidakLayak.addEventListener('change', toggleAlasanSection);
    alasanCheckboxes.forEach(cb => cb.addEventListener('change', updateAlasanPreview));

    // Set initial state
    toggleAlasanSection();
  });
</script>

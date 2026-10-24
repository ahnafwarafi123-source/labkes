<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Laporan Per Pelanggan - Detail Permintaan
 * @var array  $pelanggan       - Data pelanggan
 * @var array  $permintaan_list - Semua permintaan beserta items
 * @var array  $filters         - Filter aktif
 */
?>
<?php $this->load->view('kesmas/premium_list_style'); ?>

<!-- HERO HEADER -->
<div class="page-hero mb-4">
  <div class="d-flex align-items-start justify-content-between gap-3 flex-wrap">
    <div>
      <a class="btn btn-outline-secondary btn-sm me-2" href="<?= site_url('k-panel/laporan_pelanggan') ?>" style="border-radius:8px;padding:0.3rem 0.6rem;font-weight:600;">
        <i class="bi bi-arrow-left"></i> <span class="d-none d-md-inline">Kembali</span>
      </a>
      <div class="mt-3">
        <h3><?= htmlspecialchars($pelanggan['nama']) ?></h3>
        <p class="sub">
          <?php if (!empty($pelanggan['nik'])): ?>
            <span class="badge" style="background:#eff6ff;color:#1d4ed8;border:1px solid #bfdbfe;border-radius:6px;font-family:monospace;">NIK: <?= htmlspecialchars($pelanggan['nik']) ?></span> &bull;
          <?php endif; ?>
          <?= htmlspecialchars($pelanggan['instansi'] ?: '-') ?> &bull;
          <?= htmlspecialchars($pelanggan['alamat'] ?: '-') ?> &bull;
          <?php if (!empty($pelanggan['kecamatan'])): ?>
            <span class="badge bg-light text-dark border"><?= htmlspecialchars($pelanggan['kecamatan']) ?></span> &bull;
          <?php endif; ?>
          <?php if (!empty($pelanggan['kelurahan'])): ?>
            <span class="badge bg-light text-dark border"><?= htmlspecialchars($pelanggan['kelurahan']) ?></span> &bull;
          <?php endif; ?>
          <?php if (!empty($pelanggan['objek'])): ?>
            <span class="badge bg-light text-dark border"><?= htmlspecialchars($pelanggan['objek']) ?></span> &bull;
          <?php endif; ?>
          <?= htmlspecialchars($pelanggan['telp'] ?: '-') ?>
        </p>
      </div>
    </div>
    <div class="d-flex gap-2 align-items-start flex-wrap">
      <?php
        $qs  = '?dari='   . urlencode($filters['dari'] ?? '');
        $qs .= '&sampai=' . urlencode($filters['sampai'] ?? '');
        $qs .= '&jenis_sampel=' . urlencode($filters['jenis_sampel'] ?? '');
      ?>
      <a class="btn-add" href="#printForm"
         style="background:linear-gradient(135deg,#10b981,#059669);box-shadow:0 12px 24px rgba(16,185,129,.25);">
        <i class="bi bi-printer me-md-1"></i>
        <span class="d-none d-md-inline">Ke Opsi Cetak</span>
      </a>
    </div>
  </div>
</div>

<!-- FILTER -->
<div class="filter-shell mb-4">
  <form class="row g-2 align-items-center" method="get">
    <div class="col-6 col-md-3">
      <label class="form-label small fw-semibold mb-1">Dari Tanggal</label>
      <input class="form-control" type="date" name="dari" value="<?= htmlspecialchars($filters['dari'] ?? '') ?>">
    </div>
    <div class="col-6 col-md-3">
      <label class="form-label small fw-semibold mb-1">Sampai Tanggal</label>
      <input class="form-control" type="date" name="sampai" value="<?= htmlspecialchars($filters['sampai'] ?? '') ?>">
    </div>
    <div class="col-12 col-md-4">
      <label class="form-label small fw-semibold mb-1">Jenis Sampel</label>
      <select class="form-select" name="jenis_sampel">
        <option value="">-- Semua Jenis --</option>
        <?php
        $jenis_options = ['Air Minum','Air Bersih','Makanan','Minuman','Lingkungan'];
        foreach ($jenis_options as $j):
          $sel = ($filters['jenis_sampel'] ?? '') === $j ? 'selected' : '';
        ?>
          <option value="<?= htmlspecialchars($j) ?>" <?= $sel ?>><?= htmlspecialchars($j) ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="col-12 col-md-2 d-flex align-items-end">
      <button class="btn btn-outline-primary w-100 btn-filter mt-3 mt-md-0">Filter</button>
    </div>
  </form>
</div>

<!-- RINGKASAN -->
<div class="row g-3 mb-4">
  <div class="col-md-4">
    <div class="card h-100" style="border:none;border-radius:12px;box-shadow:0 4px 16px rgba(0,0,0,0.06);">
      <div class="card-body text-center">
        <div style="font-size:2rem;font-weight:800;color:#2563eb;"><?= count($permintaan_list) ?></div>
        <div class="text-muted small fw-semibold">Total Permintaan</div>
      </div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="card h-100" style="border:none;border-radius:12px;box-shadow:0 4px 16px rgba(0,0,0,0.06);">
      <div class="card-body text-center">
        <?php $total_item = array_sum(array_map(fn($p) => count($p['items']), $permintaan_list)); ?>
        <div style="font-size:2rem;font-weight:800;color:#10b981;"><?= $total_item ?></div>
        <div class="text-muted small fw-semibold">Total Item Pemeriksaan</div>
      </div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="card h-100" style="border:none;border-radius:12px;box-shadow:0 4px 16px rgba(0,0,0,0.06);">
      <div class="card-body text-center">
        <?php
          $tms_count = 0;
          foreach ($permintaan_list as $p) {
            foreach ($p['items'] as $it) {
              if (strtoupper(trim($it['tms_status'] ?? '')) === 'TMS') $tms_count++;
            }
          }
        ?>
        <div style="font-size:2rem;font-weight:800;color:<?= $tms_count > 0 ? '#ef4444' : '#10b981' ?>;"><?= $tms_count ?></div>
        <div class="text-muted small fw-semibold">Item TMS (Tidak Memenuhi Syarat)</div>
      </div>
    </div>
  </div>
</div>

<!-- DAFTAR PERMINTAAN -->
<?php if (empty($permintaan_list)): ?>
  <div class="card-wow text-center py-5 text-muted">Belum ada permintaan untuk pelanggan ini dengan filter yang dipilih.</div>
<?php else: ?>
  <?php foreach ($permintaan_list as $idx => $p): ?>
    <div class="card-wow mb-4" style="border-radius:12px;overflow:hidden;">
      <div style="background:linear-gradient(135deg,#1e3a5f,#2563eb);color:white;padding:0.85rem 1.25rem;display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:.5rem;">
        <div>
          <?php 
             $no_reg_raw = $p['no_registrasi'] ?: '-';
             $no_reg_short = trim(explode('/', $no_reg_raw)[0]);
          ?>
          <span style="font-weight:700;font-size:1rem;">&#128204; <?= htmlspecialchars($no_reg_short) ?></span>
          <span class="ms-3 small opacity-75"><?= $p['tgl_permintaan'] ? date('d/m/Y', strtotime($p['tgl_permintaan'])) : '-' ?></span>
        </div>
        <div class="d-flex gap-2 align-items-center">
          <span class="badge" style="background:rgba(255,255,255,0.2);font-size:.75rem;">
            <?= htmlspecialchars($p['jenis_sampel'] ?: '-') ?>
          </span>
          <?php $kondisi = $p['status_kelayakan'] ?? '-'; ?>
          <span class="badge" style="background:<?= $kondisi === 'Layak' ? '#22c55e' : ($kondisi === 'Tidak Layak' ? '#ef4444' : '#f59e0b') ?>;">
            <?= htmlspecialchars($kondisi) ?>
          </span>
          <a href="<?= site_url('k-panel/laporan_uji_kesmas/detail/'.$p['id_permintaan']) ?>"
             class="btn btn-sm" style="background:rgba(255,255,255,0.2);color:white;border:1px solid rgba(255,255,255,0.4);font-size:.75rem;" target="_blank">
            <i class="bi bi-box-arrow-up-right"></i> Buka Detail
          </a>
        </div>
      </div>

      <div style="padding:.75rem 1.25rem;border-bottom:1px solid #f1f5f9;background:#f8fafc;font-size:.82rem;color:#475569;">
        <strong>Sampel:</strong> <?= htmlspecialchars($p['nama_sampel'] ?: '-') ?> &bull;
        <strong>Lokasi:</strong> <?= htmlspecialchars($p['lokasi_pengambilan'] ?: '-') ?> &bull;
        <strong>Tgl Pengambilan:</strong> <?= $p['tgl_pengambilan'] ? date('d/m/Y', strtotime($p['tgl_pengambilan'])) : '-' ?>
      </div>

      <?php if (!empty($p['items'])): ?>
        <div class="table-responsive">
          <table class="table table-sm mb-0" style="font-size:.82rem;">
            <thead style="background:#f1f5f9;">
              <tr>
                <th style="width:4%">#</th>
                <th>Jenis Pemeriksaan</th>
                <th style="width:9%">Satuan</th>
                <th style="width:16%">Baku Mutu</th>
                <th style="width:14%">Hasil</th>
                <th style="width:8%" class="text-center">Ket</th>
              </tr>
            </thead>
            <tbody>
              <?php $no = 1; foreach ($p['items'] as $it): ?>
                <?php $sts = strtoupper(trim($it['keterangan'] ?? '')); ?>
                <tr>
                  <td class="text-center text-muted"><?= $no++ ?></td>
                  <td><?= htmlspecialchars($it['nama_pemeriksaan'] ?: '-') ?></td>
                  <td class="text-center"><?= htmlspecialchars($it['satuan'] ?: '-') ?></td>
                  <?php $baku = !empty(trim($it['baku_mutu_override'] ?? '')) ? $it['baku_mutu_override'] : $it['baku_mutu']; ?>
                  <td><?= htmlspecialchars($baku ?: '-') ?></td>
                  <td class="fw-semibold"><?= htmlspecialchars($it['hasil'] ?: '-') ?></td>
                  <td class="text-center">
                    <?php if ($sts === 'MS'): ?>
                      <span class="badge" style="background:#dcfce7;color:#166534;border-radius:6px;">MS</span>
                    <?php elseif ($sts === 'TMS'): ?>
                      <span class="badge" style="background:#fee2e2;color:#991b1b;border-radius:6px;">TMS</span>
                    <?php else: ?>
                      <span class="text-muted">-</span>
                    <?php endif; ?>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      <?php else: ?>
        <div class="text-center text-muted py-3 small">Belum ada data pemeriksaan.</div>
      <?php endif; ?>
    </div>
  <?php endforeach; ?>
<?php endif; ?>

<div class="card mb-4 d-print-none" style="border: none; box-shadow: 0 10px 30px rgba(15,23,42,0.05); border-radius: 12px; overflow: hidden;">
    <div class="card-header py-3 bg-white" style="border-bottom: 1px solid rgba(0,0,0,0.05);">
        <h5 class="mb-0 text-dark fw-bold"><i class="bi bi-printer me-2 text-success"></i>Opsi Cetak Laporan Per Pelanggan</h5>
    </div>
    <div class="card-body">
        <form id="printForm" action="<?= site_url('k-panel/laporan_pelanggan/print_laporan/'.$pelanggan['id_pelanggan']) ?>" method="get" target="_blank">
            <input type="hidden" name="dari" value="<?= htmlspecialchars($filters['dari'] ?? '') ?>">
            <input type="hidden" name="sampai" value="<?= htmlspecialchars($filters['sampai'] ?? '') ?>">
            <input type="hidden" name="jenis_sampel" value="<?= htmlspecialchars($filters['jenis_sampel'] ?? '') ?>">
            <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">

            <div class="row align-items-end g-3">
                <div class="col-md-4">
                    <label for="petugas_id" class="form-label fw-bold">Petugas</label>
                    <select name="petugas_id" id="petugas_id" class="form-select" required>
                        <option value="">-- Pilih Petugas --</option>
                        <?php if (isset($petugas_list) && is_array($petugas_list)): foreach($petugas_list as $pt): ?>
                            <option value="<?= $pt['id_petugas'] ?>"><?= htmlspecialchars($pt['nama']) ?></option>
                        <?php endforeach; endif; ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="verifikator_id" class="form-label fw-bold">Verifikator</label>
                    <select name="verifikator_id" id="verifikator_id" class="form-select" required>
                        <option value="">-- Pilih Verifikator --</option>
                        <?php if (isset($verifikator_list) && is_array($verifikator_list)): foreach($verifikator_list as $v): ?>
                            <option value="<?= $v['id_penanggung_jawab'] ?>"><?= htmlspecialchars($v['nama']) ?></option>
                        <?php endforeach; endif; ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="penanggung_jawab_id" class="form-label fw-bold">Penanggung Jawab Teknis</label>
                    <select name="penanggung_jawab_id" id="penanggung_jawab_id" class="form-select" required>
                        <option value="">-- Pilih Penanggung Jawab --</option>
                        <?php if (isset($penanggung_jawab_teknis_list) && is_array($penanggung_jawab_teknis_list)): foreach($penanggung_jawab_teknis_list as $pjt): ?>
                            <option value="<?= $pjt['id_penanggung_jawab'] ?>"><?= htmlspecialchars($pjt['nama']) ?></option>
                        <?php endforeach; endif; ?>
                    </select>
                </div>

                <div class="col-md-12 mt-3">
                    <?php
                        $default_catatan_kaki = "Hasil analisa berlaku hanya untuk sampel yang diuji.\nPengambilan sampel diluar tanggung jawab UPTD. Laboratorium Kesehatan Kota Pangkal Pinang.";
                        $val_catatan_kaki = !empty($pelanggan['catatan_kaki']) ? $pelanggan['catatan_kaki'] : $default_catatan_kaki;
                    ?>
                    <label for="keterangan_cetak" class="form-label fw-bold">
                        Catatan Kaki
                        <small class="text-muted fw-normal">(bisa diedit &mdash; disimpan ke database per pelanggan)</small>
                        <?php if (!empty($pelanggan['catatan_kaki'])): ?>
                            <span class="badge bg-success ms-1" style="font-size:.65rem;vertical-align:middle;"><i class="bi bi-database-check"></i> Tersimpan di DB</span>
                        <?php endif; ?>
                    </label>
                    <textarea name="keterangan_cetak" id="keterangan_cetak" class="form-control" rows="3"><?= htmlspecialchars($val_catatan_kaki) ?></textarea>
                    <div class="d-flex justify-content-between align-items-center mt-1">
                        <small class="text-muted"><i class="bi bi-database me-1"></i>Klik <strong>Simpan Catatan</strong> untuk menyimpan ke database &mdash; berlaku semua sesi &amp; perangkat.</small>
                        <button type="button" id="btn_reset_catatan" class="btn btn-sm btn-outline-secondary" style="font-size:.75rem;padding:2px 10px;border-radius:6px;">
                            <i class="bi bi-arrow-counterclockwise me-1"></i>Reset ke Default
                        </button>
                    </div>
                </div>

                <div class="col-md-12 mt-3 d-flex justify-content-end gap-2">
                    <!-- POST ke save_catatan_kaki, buka di tab sama (_self) -->
                    <button type="submit"
                            formaction="<?= site_url('k-panel/laporan_pelanggan/save_catatan_kaki/'.$pelanggan['id_pelanggan']) ?>"
                            formmethod="post"
                            formtarget="_self"
                            class="btn btn-secondary px-4">
                        <i class="bi bi-floppy me-2"></i>Simpan Catatan
                    </button>
                    <!-- GET ke print_laporan, buka tab baru -->
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="fa fa-file-pdf me-2"></i>Cetak Laporan PDF
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
  (function() {
    const DEFAULT_CATATAN = <?= json_encode($default_catatan_kaki ?? "Hasil analisa berlaku hanya untuk sampel yang diuji.\nPengambilan sampel diluar tanggung jawab UPTD. Laboratorium Kesehatan Kota Pangkal Pinang.") ?>;

    document.addEventListener('DOMContentLoaded', function() {
      const printForm = document.getElementById('printForm');
      const catatanEl = document.getElementById('keterangan_cetak');
      const btnReset  = document.getElementById('btn_reset_catatan');

      if (!printForm) return;

      // ── Restore dropdown tersimpan (localStorage) ─────────────────────────
      const pId  = localStorage.getItem('laporan_pelanggan_petugas_id');
      const vId  = localStorage.getItem('laporan_pelanggan_verifikator_id');
      const pjId = localStorage.getItem('laporan_pelanggan_penanggung_jawab_id');

      if (pId)  document.getElementById('petugas_id').value          = pId;
      if (vId)  document.getElementById('verifikator_id').value      = vId;
      if (pjId) document.getElementById('penanggung_jawab_id').value = pjId;

      // ── Simpan dropdown saat form disubmit ────────────────────────────────
      printForm.addEventListener('submit', function() {
        localStorage.setItem('laporan_pelanggan_petugas_id',          document.getElementById('petugas_id').value);
        localStorage.setItem('laporan_pelanggan_verifikator_id',      document.getElementById('verifikator_id').value);
        localStorage.setItem('laporan_pelanggan_penanggung_jawab_id', document.getElementById('penanggung_jawab_id').value);
      });

      // ── Tombol Reset ke teks default ──────────────────────────────────────
      if (btnReset) {
        btnReset.addEventListener('click', function() {
          catatanEl.value = DEFAULT_CATATAN;
          btnReset.textContent = '✓ Direset!';
          btnReset.classList.replace('btn-outline-secondary', 'btn-outline-success');
          setTimeout(function() {
            btnReset.innerHTML = '<i class="bi bi-arrow-counterclockwise me-1"></i>Reset ke Default';
            btnReset.classList.replace('btn-outline-success', 'btn-outline-secondary');
          }, 1500);
        });
      }
    });
  })();
</script>

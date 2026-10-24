<?php
defined('BASEPATH') or exit('No direct script access allowed');
/**
 * View untuk detail laporan hasil uji kesmas
 * @var array $permintaan - Data permintaan sampel dengan fields: id, no_registrasi, nama_pengirim, nama_sampel, kategori_sample, jenis_sampel, volume_ml, lokasi_pengambilan, tgl_permintaan, status_kelayakan
 * @var string $petugas_pengambil_spesimen_nama - Nama petugas pengambil spesimen
 * @var string $tgl_jam_pengambilan_display - Tanggal dan jam pengambilan sampel
 * @var string $tgl_jam_pemeriksaan_display - Tanggal dan jam pemeriksaan
 * @var string $tgl_jam_selesai_display - Tanggal dan jam pemeriksaan selesai
 * @var string $tgl_jam_lapor_display - Tanggal dan jam pelaporan hasil
 */
?>
<div class="d-flex justify-content-between align-items-center mb-3 d-print-none">
  <h3 class="fw-bold mb-0">Laporan Hasil Uji</h3>
  <a class="btn btn-outline-secondary" href="<?= site_url('k-panel/laporan_uji_kesmas') ?>"><i class="fa fa-arrow-left me-2"></i> Kembali</a>
</div>


<!-- CARD: IDENTITAS SAMPEL & SPESIMEN -->
<div class="card mb-4" style="border: none; box-shadow: 0 10px 30px rgba(15,23,42,0.05); border-radius: 12px; overflow: hidden;">
  <div class="card-header py-3 bg-white" style="border-bottom: 1px solid rgba(0,0,0,0.05);">
    <h5 class="mb-0 text-dark fw-bold"><i class="bi bi-card-list me-2 text-primary"></i>Identitas Sampel & Spesimen</h5>
  </div>
  <div class="card-body">
    <div class="row">
      <div class="col-md-6">
        <table class="table table-borderless table-sm mb-0">
          <tbody>
            <tr>
              <td class="fw-semibold" style="width: 150px;">No. Registrasi</td>
              <td>: <?= htmlspecialchars($permintaan['no_registrasi'] ?: '-') ?></td>
            </tr>
            <tr>
              <td class="fw-semibold">Nama Pengirim</td>
              <td>: <?= htmlspecialchars($permintaan['nama'] ?: '-') ?></td>
            </tr>
            <tr>
              <td class="fw-semibold">Nama Sampel</td>
              <td>: <?= htmlspecialchars($permintaan['nama_sampel'] ?: '-') ?></td>
            </tr>
            <tr>
              <td class="fw-semibold">Kategori Sampel</td>
              <td>: <?= htmlspecialchars($permintaan['kategori_sampel'] ?? '-') ?></td>
            </tr>
            <tr>
              <td class="fw-semibold">Jenis Sampel</td>
              <td>: <?= htmlspecialchars($permintaan['jenis_sampel'] ?: '-') ?></td>
            </tr>
            <tr>
              <td class="fw-semibold">Volume Sampel</td>
              <td>: <?= htmlspecialchars($permintaan['volume_ml'] ?: '-') ?> ml</td>
            </tr>
            <tr>
              <td class="fw-semibold">Lokasi Pengambilan</td>
              <td>: <?= htmlspecialchars($permintaan['lokasi_pengambilan'] ?: '-') ?></td>
            </tr>
            <tr>
              <td class="fw-semibold">Tanggal Pengiriman</td>
              <td>: <?= $permintaan['tgl_permintaan'] ? date('d-m-Y', strtotime($permintaan['tgl_permintaan'])) : '-' ?></td>
            </tr>
          </tbody>
        </table>
      </div>
      <div class="col-md-6">
        <table class="table table-borderless table-sm mb-0">
          <tbody>
            <tr>
              <td class="fw-semibold" style="width: 200px;">Kondisi Sampel</td>
              <td>: <?= htmlspecialchars($permintaan['status_kelayakan'] ?: '-') ?></td>
            </tr>
            <tr>
              <td class="fw-semibold">Petugas Pengambil Sampel</td>
              <td>: <?= htmlspecialchars($petugas_pengambil_spesimen_nama ?: '-') ?></td>
            </tr>
            <tr>
              <td class="fw-semibold">Tgl / Jam Pengambilan</td>
              <td>: <?= $tgl_jam_pengambilan_display ? date('d-m-Y H:i', strtotime($tgl_jam_pengambilan_display)) . ' WIB' : '-' ?></td>
            </tr>
            <tr>
              <td class="fw-semibold">Tgl / Jam Pemeriksaan</td>
              <td>: <?= $tgl_jam_pemeriksaan_display ? date('d-m-Y H:i', strtotime($tgl_jam_pemeriksaan_display)) . ' WIB' : '-' ?></td>
            </tr>
            <tr>
              <td class="fw-semibold">Tgl / Jam Selesai Pemeriksaan</td>
              <td>: <?= $tgl_jam_selesai_display ? date('d-m-Y H:i', strtotime($tgl_jam_selesai_display)) . ' WIB' : '-' ?></td>
            </tr>
            <tr>
              <td class="fw-semibold">Tgl / Jam Pelaporan Hasil</td>
              <td>: <?= $tgl_jam_lapor_display ? date('d-m-Y H:i', strtotime($tgl_jam_lapor_display)) . ' WIB' : '-' ?></td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<!-- CARD: HASIL PEMERIKSAAN -->
<div class="card mb-4" style="border: none; box-shadow: 0 10px 30px rgba(15,23,42,0.05); border-radius: 12px; overflow: hidden;">
  <div class="card-header py-3 bg-white" style="border-bottom: 1px solid rgba(0,0,0,0.05);">
    <h5 class="mb-0 text-dark fw-bold"><i class="bi bi-clipboard2-data me-2 text-primary"></i>Hasil Pemeriksaan</h5>
  </div>
  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table table-sm table-bordered mb-0">
        <thead class="table-light">
          <tr>
            <th class="text-center" style="width: 5%;">No</th>
            <th>Nama Pemeriksaan</th>
            <th class="text-center" style="width: 15%;">Hasil</th>
            <th class="text-center" style="width: 15%;">Baku Mutu</th>
            <th class="text-center" style="width: 10%;">Satuan</th>
            <th class="text-center" style="width: 20%;">Metode</th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($grouped_items)): ?>
            <tr>
              <td colspan="6" class="text-center text-muted py-4">Belum ada item pemeriksaan dipilih</td>
            </tr>
          <?php else: ?>
            <?php
            // Determine category for prefix mapping
            $kategori_sampel = strtolower(str_replace(' ', '_', $permintaan['jenis_sampel'] ?? ''));

            foreach ($grouped_items as $kelompok => $items_in_kelompok):
              // Reset numbering for each group so numbering starts at 1 per kelompok
              $item_no = 1;
              // Prefix logic removed as per user request
            ?>
              <tr style="background: linear-gradient(90deg, #f8fafc, #f1f5f9);">
                <td colspan="6" class="fw-bold px-3 py-2 text-primary" style="font-size: 0.95rem; text-transform: uppercase; letter-spacing: 0.5px; border-left: 4px solid #2563eb;">
                  <i class="bi bi-tag-fill me-2 opacity-50"></i><?= htmlspecialchars(normalize_kelompok($kelompok)) ?>
                </td>
              </tr>
              <?php foreach ($items_in_kelompok as $item): ?>
                <?php $pid = (int)($item['id_permintaan_item'] ?? 0); ?>
                <tr data-id-permintaan-item="<?= $pid ?>">
                  <td class="text-center"><?= $item_no++ ?></td>
                  <td><?= e_html($item['nama_pemeriksaan']) ?></td>
                  <td class="text-center result-cell"><?= e_html($item['hasil'] ?? null) ?></td>
                  <td class="text-center baku-cell"><?= e_html(!empty($item['baku_mutu_override']) ? $item['baku_mutu_override'] : $item['baku_mutu']) ?></td>
                  <td class="text-center"><?= e_html($item['satuan']) ?></td>
                  <td class="text-center metode-cell"><?= e_html(!empty($item['metode_override']) ? $item['metode_override'] : $item['metode']) ?></td>
                </tr>
              <?php endforeach; ?>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<div class="card mb-4 d-print-none" style="border: none; box-shadow: 0 10px 30px rgba(15,23,42,0.05); border-radius: 12px; overflow: hidden;">
  <div class="card-header py-3 bg-white" style="border-bottom: 1px solid rgba(0,0,0,0.05);">
    <h5 class="mb-0 text-dark fw-bold"><i class="bi bi-printer me-2 text-success"></i>Opsi Cetak Laporan</h5>
  </div>
  <div class="card-body">
    <form id="printForm" action="<?= site_url('k-panel/laporan_uji_kesmas/print/' . ($permintaan['id_permintaan'] ?? '')) ?>" method="post" target="_blank">
      <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
      <div class="row align-items-end g-3">
        <div class="col-md-3">
          <label for="petugas_id" class="form-label fw-bold">Petugas</label>
          <select name="petugas_id" id="petugas_id" class="form-select" required>
            <option value="">-- Pilih Petugas --</option>
            <?php if (isset($petugas_list) && is_array($petugas_list)): foreach ($petugas_list as $p): ?>
                <option value="<?= $p['id_petugas'] ?>"><?= htmlspecialchars($p['nama']) ?></option>
            <?php endforeach;
            endif; ?>
          </select>
        </div>
        <div class="col-md-3">
          <label for="verifikator_id" class="form-label fw-bold">Verifikator</label>
          <select name="verifikator_id" id="verifikator_id" class="form-select" required>
            <option value="">-- Pilih Verifikator --</option>
            <?php if (isset($verifikator_list) && is_array($verifikator_list)): foreach ($verifikator_list as $v): ?>
                <option value="<?= $v['id_penanggung_jawab'] ?>"><?= htmlspecialchars($v['nama']) ?></option>
            <?php endforeach;
            endif; ?>
          </select>
        </div>
        <div class="col-md-4">
          <label for="penanggung_jawab_id" class="form-label fw-bold">Penanggung Jawab Teknis</label>
          <select name="penanggung_jawab_id" id="penanggung_jawab_id" class="form-select" required>
            <option value="">-- Pilih Penanggung Jawab --</option>
            <?php if (isset($penanggung_jawab_teknis_list) && is_array($penanggung_jawab_teknis_list)): foreach ($penanggung_jawab_teknis_list as $pjt): ?>
                <option value="<?= $pjt['id_penanggung_jawab'] ?>"><?= htmlspecialchars($pjt['nama']) ?></option>
            <?php endforeach;
            endif; ?>
          </select>
        </div>
        <div class="col-md-12 mt-3">
          <label for="keterangan_cetak" class="form-label fw-bold">Keterangan / Catatan Kaki (Bisa Diedit)</label>
          <?php
          $val_ket = !empty($permintaan['catatan_kaki']) ? $permintaan['catatan_kaki'] : ($default_ket ?? '');
          ?>
          <textarea name="keterangan_cetak" id="keterangan_cetak" class="form-control" rows="5" placeholder="Masukkan keterangan yang akan muncul di bagian bawah laporan..."><?= htmlspecialchars($val_ket) ?></textarea>
          <div class="form-text text-muted">Teks di atas adalah keterangan standar sesuai jenis sampel. Anda bebas mengubahnya sebelum mencetak.</div>
        </div>
        <div class="col-md-12 mt-3 text-end">
          <button type="submit" formaction="<?= site_url('k-panel/laporan_uji_kesmas/save_keterangan/' . ($permintaan['id_permintaan'] ?? '')) ?>" formtarget="_self" class="btn btn-secondary px-4 me-2"><i class="fa fa-save me-2"></i>Simpan Catatan</button>
          <button type="submit" class="btn btn-primary px-4"><i class="fa fa-file-pdf me-2"></i>Cetak Laporan PDF</button>
        </div>
      </div>
    </form>
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Load and save print form selections untuk menyimpan riwayat pilihan petugas cetak
    const printForm = document.getElementById('printForm');
    if (printForm) {
      const pId = localStorage.getItem('laporan_petugas_id');
      const vId = localStorage.getItem('laporan_verifikator_id');
      const pjId = localStorage.getItem('laporan_penanggung_jawab_id');

      if (pId) document.getElementById('petugas_id').value = pId;
      if (vId) document.getElementById('verifikator_id').value = vId;
      if (pjId) document.getElementById('penanggung_jawab_id').value = pjId;

      printForm.addEventListener('submit', function() {
        localStorage.setItem('laporan_petugas_id', document.getElementById('petugas_id').value);
        localStorage.setItem('laporan_verifikator_id', document.getElementById('verifikator_id').value);
        localStorage.setItem('laporan_penanggung_jawab_id', document.getElementById('penanggung_jawab_id').value);
      });
    }
  });
</script>
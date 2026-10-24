<div class="content-wrapper px-4 pt-4">
  <h3 class="fw-bold text-center mb-4">INPUT HASIL PEMERIKSAAN LABORATORIUM</h3>

  <div class="row g-3 mb-3">
    <!-- Card Identitas Pasien -->
    <div class="col-md-4">
      <div class="card h-100 border border-dark shadow-sm">
        <div class="card-header bg-secondary text-white fw-bold text-center fs-5 py-2">IDENTITAS PASIEN</div>
        <div class="card-body p-3 bg-white">
          <table class="table table-borderless align-middle mb-0 fs-6 text-dark">
            <tbody>
              <tr><td class="fw-semibold text-secondary"><strong>No. Register</strong></td><td>: <?= $form->no_register ?></td></tr>
              <tr><td class="fw-semibold text-secondary"><strong>Nama Pasien</strong></td><td>: <?= $form->nama_pasien ?></td></tr>
              <tr><td class="fw-semibold text-secondary"><strong>NIK</strong></td><td>: <?= $form->nik ?></td></tr>
              <tr><td class="fw-semibold text-secondary"><strong>Jenis Kelamin</strong></td><td>: <?= $form->gender ?></td></tr>
              <tr><td class="fw-semibold text-secondary"><strong>Tgl Lahir / Umur</strong></td><td>: <?= $form->tgl_lahir ?> / <?= $form->umur ?></td></tr>
              <tr><td class="fw-semibold text-secondary"><strong>No. Telp</strong></td><td>: <?= $form->no_telp_pasien ?></td></tr>
              <tr><td class="fw-semibold text-secondary"><strong>Alamat</strong></td><td>: <?= nl2br($form->alamat_pasien) ?></td></tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- Card Identitas Pengirim -->
    <div class="col-md-4">
      <div class="card h-100 border border-dark shadow-sm">
        <div class="card-header bg-secondary text-white fw-bold text-center fs-5 py-2">IDENTITAS PENGIRIM</div>
        <div class="card-body p-3 bg-white">
          <table class="table table-borderless align-middle mb-0 fs-6 text-dark">
            <tbody>
              <tr><td class="fw-semibold text-secondary"><strong>Nama Dokter</strong></td><td>: <?= $form->nama_dokter ?></td></tr>
              <tr><td class="fw-semibold text-secondary"><strong>No. Telp Dokter</strong></td><td>: <?= $form->telp_pengirim ?></td></tr>
              <tr><td class="fw-semibold text-secondary"><strong>Alamat Dokter</strong></td><td>: <?= nl2br($form->alamat_pengirim) ?></td></tr>
              <tr><td class="fw-semibold text-secondary"><strong>Tanggal Form</strong></td><td>: <?= $form->tgl_form ?></td></tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- Card Diagnosa Klinis -->
    <div class="col-md-4">
      <div class="card h-100 border border-dark shadow-sm">
        <div class="card-header bg-secondary text-white fw-bold text-center fs-5 py-2">DIAGNOSA KLINIS</div>
        <div class="card-body p-3 bg-white">
          <table class="table table-borderless align-middle mb-0 fs-6 text-dark">
            <tbody>
              <tr><td class="fw-semibold text-secondary"><strong>Diagnosa / Keterangan Klinik</strong></td><td>: <?= nl2br($form->diagnosa_klinis) ?></td></tr>
              <tr><td class="fw-semibold text-secondary"><strong>Obat yang Dikonsumsi</strong></td><td>: <?= nl2br($form->obat_dikonsumsi) ?></td></tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <!-- Card Data Spesimen -->
  <div class="card shadow mb-4 border border-dark">
    <div class="card-header bg-secondary text-white text-center fw-bold fs-5 py-2">DATA SPESIMEN</div>
    <div class="card-body fs-6 bg-white">
      <div class="row mb-3">
        <div class="col-md-6"><span class="fw-semibold text-secondary">Asal Sampel</span><div class="border rounded px-3 py-2 bg-light"><?= $form->asal_sampel ?></div></div>
        <div class="col-md-6"><span class="fw-semibold text-secondary">Status Puasa</span><div class="border rounded px-3 py-2 bg-light"><?= $form->puasa ?></div></div>
      </div>
      <div class="mb-3"><span class="fw-semibold text-secondary">Lokasi Pengambilan</span><div class="border rounded px-3 py-2 bg-light"><?= $form->lokasi_pengambilan ?><?= $form->lokasi_lainnya ? ', ' . $form->lokasi_lainnya : '' ?></div></div>
      <div class="mb-3"><span class="fw-semibold text-secondary">Jenis Spesimen</span><div class="border rounded px-3 py-2 bg-light"><?= $form->jenis_spesimen ?><?= $form->spesimen_lainnya ? ', ' . $form->spesimen_lainnya : '' ?></div></div>
      <div class="row mb-3">
        <div class="col-md-4"><span class="fw-semibold text-secondary">Tanggal Permintaan</span><div class="border rounded px-3 py-2 bg-light"><?= $form->tgl_permintaan ?></div></div>
        <div class="col-md-4"><span class="fw-semibold text-secondary">Tanggal Pengambilan</span><div class="border rounded px-3 py-2 bg-light"><?= $form->tgl_pengambilan ?></div></div>
        <div class="col-md-4"><span class="fw-semibold text-secondary">Jam Pengambilan</span><div class="border rounded px-3 py-2 bg-light"><?= $form->jam_pengambilan ?></div></div>
      </div>
      <div class="row mb-3">
        <div class="col-md-6"><span class="fw-semibold text-secondary">Volume Spesimen</span><div class="border rounded px-3 py-2 bg-light"><?= $form->volume_spesimen ?></div></div>
        <div class="col-md-6"><span class="fw-semibold text-secondary">Prioritas</span><div class="border rounded px-3 py-2 bg-light"><?= $form->prioritas ?></div></div>
      </div>
      <div class="mb-2"><span class="fw-semibold text-secondary">Informasi Tambahan</span><div class="border rounded px-3 py-2 bg-light"><?= nl2br($form->info_tambahan) ?></div></div>
    </div>
  </div>

  <!-- Card Daftar Pemeriksaan -->
  <div class="card shadow p-4 border border-dark mb-5">
    <h5 class="fw-bold mb-3 text-center text-secondary">DAFTAR PEMERIKSAAN</h5>
    <form method="post" action="<?= site_url('uji_klinik/simpan_hasil') ?>">
      <input type="hidden" name="id_form" value="<?= $form->id ?>">
      <input type="hidden" name="id_pasien" value="<?= $id_pasien ?>">

      <table class="table table-bordered table-striped align-middle">
        <thead class="table-secondary text-center">
          <tr>
            <th>No</th>
            <th>Nama Pemeriksaan</th>
            <th width="20%">Hasil</th>
            <th width="15%">Satuan</th>
            <th width="20%">Nilai Rujukan</th>
            <th width="20%">Metode</th>
          </tr>
        </thead>
        <tbody>
          <?php $no=1; foreach ($detail as $d): ?>
          <tr>
            <td class="text-center"><?= $no++ ?></td>
            <td><?= $d->nama_jenis ?></td>
            <td><input type="text" name="hasil[<?= $d->id_detail ?>]" class="form-control form-control-sm" value="<?= $d->hasil ?? '' ?>"></td>
            <td><input type="text" name="satuan[<?= $d->id_detail ?>]" class="form-control form-control-sm bg-light" value="<?= $d->satuan ?>" readonly></td>
            <td><input type="text" name="nilai_rujukan[<?= $d->id_detail ?>]" class="form-control form-control-sm bg-light" value="<?= $d->nilai_rujukan ?>" readonly></td>
            <td><input type="text" name="metode[<?= $d->id_detail ?>]" class="form-control form-control-sm bg-light" value="<?= $d->metode ?>" readonly></td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>

      <div class="card shadow mb-4 border border-dark">
      <div class="card-header bg-secondary text-white text-center fw-bold fs-5 py-2">
        CATATAN & PETUGAS LABORATORIUM
      </div>
      <div class="card-body bg-white fs-6">
        <!-- Note -->
        <div class="mb-3">
          <label class="fw-semibold">Catatan / Note</label>
          <textarea name="note" class="form-control" rows="2"
            placeholder="Isi catatan (boleh dikosongkan)"><?= isset($form->note) ? $form->note : '' ?></textarea>
        </div>

        <!-- Petugas Hasil -->
        <div class="mb-3">
          <label class="fw-semibold">Petugas</label>
          <select name="petugas_hasil" id="petugas_hasil" class="form-control form-control-sm" required>
            <option value="">-- Pilih Petugas --</option>
            <?php foreach ($daftar_petugas as $pt): ?>
              <option value="<?= $pt->nama_petugas ?>"
                <?= isset($form->petugas_hasil) && $form->petugas_hasil == $pt->nama_petugas ? 'selected' : '' ?>>
                <?= $pt->nama_petugas ?><?= $pt->jabatan ? " ({$pt->jabatan})" : "" ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <!-- Verifikator -->
        <div class="mb-3">
          <label class="fw-semibold">Verifikator</label>
          <?php if (!empty($daftar_verifikator)): 
              $vf = $daftar_verifikator[0]; // Ambil data pertama
          ?>
            <input type="hidden" name="verifikator" value="<?= $vf->nama_petugas ?>">
            <input type="text" class="form-control form-control-sm" value="<?= $vf->nama_petugas ?><?= $vf->jabatan ? " ({$vf->jabatan})" : "" ?>" readonly>
          <?php else: ?>
            <input type="text" class="form-control form-control-sm" value="-" readonly>
          <?php endif; ?>
        </div>

        <!-- Penanggung Jawab Teknis -->
        <div class="mb-3">
          <label class="fw-semibold">Penanggung Jawab Teknis</label>
          <?php if (!empty($daftar_penanggung_teknis)):
              $pt = $daftar_penanggung_teknis[0]; // Ambil data pertama
          ?>
            <input type="hidden" name="penanggung_jawab_teknis" value="<?= $pt->nama_petugas ?>">
            <input type="text" class="form-control form-control-sm" value="<?= $pt->nama_petugas ?><?= $pt->jabatan ? " ({$pt->jabatan})" : "" ?>" readonly>
          <?php else: ?>
            <input type="text" class="form-control form-control-sm" value="-" readonly>
          <?php endif; ?>
        </div>

        <div class="mb-3">
          <label class="fw-semibold">Nomor SIP</label>
          <input type="text" name="sip_penanggung" id="sip_penanggung" class="form-control form-control-sm"
                value="<?= isset($pt->sip) ? $pt->sip : '' ?>" readonly>
        </div>
      <div class="card shadow p-4 border border-dark mb-5">
        <div class="card shadow mb-4 border border-dark">
        <div class="card-header bg-secondary text-white text-center fw-bold fs-5 py-2">
          INFORMASI PENGAMBILAN & PEMERIKSAAN
        </div>
        <div class="card-body bg-white fs-6">
          <div class="mb-3">
            <label class="fw-semibold">Petugas Pengambilan Spesimen</label>
            <select name="id_petugas" class="form-control form-control-sm" required>
            <option value="">-- Pilih Petugas --</option>
            <?php foreach($daftar_petugas as $p): ?>
              <option value="<?= $p->id_petugas ?>">
                <?= $p->nama_petugas ?><?= $p->jabatan ? " ({$p->jabatan})" : "" ?>
              </option>
            <?php endforeach; ?>
          </select>
          </div>
          <div class="mb-3">
            <label class="fw-semibold">Tanggal / Jam Pengambilan</label>
            <input type="datetime-local" name="tgl_jam_pengambilan" class="form-control form-control-sm">
          </div>
          <div class="mb-3">
            <label class="fw-semibold">Tanggal / Jam Pemeriksaan</label>
            <input type="datetime-local" name="tgl_jam_pemeriksaan" class="form-control form-control-sm">
          </div>
          <div class="mb-3">
            <label class="fw-semibold">Tanggal / Jam Selesai Pemeriksaan</label>
            <input type="datetime-local" name="tgl_jam_selesai" class="form-control form-control-sm">
          </div>
          <div class="mb-3">
            <label class="fw-semibold">Tanggal / Jam Pelaporan Hasil</label>
            <input type="datetime-local" name="tgl_jam_pelaporan" class="form-control form-control-sm">
          </div>
        </div>
      </div>
      <div class="text-end mt-3">
        <button type="submit" class="btn btn-primary px-4">Simpan Hasil</button>
        <a href="<?= site_url('uji_klinik') ?>" class="btn btn-secondary px-4">Kembali</a>
      </div>
    </form>
  </div>
</div>

            <!-- JS -->
             <script>
              document.addEventListener("DOMContentLoaded", function() {
                const selectTeknis = document.getElementById("penanggung_jawab_teknis");
                const sipInput = document.getElementById("sip_penanggung");

                selectTeknis.addEventListener("change", function() {
                  const selectedOption = selectTeknis.options[selectTeknis.selectedIndex];
                  const sip = selectedOption.getAttribute("data-sip") || "";
                  sipInput.value = sip;
                });
              });
            </script>
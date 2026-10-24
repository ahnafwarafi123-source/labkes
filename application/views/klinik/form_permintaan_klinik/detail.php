<div class="content-wrapper px-4 pt-4">
  <h3 class="text-center fw-bold mb-4">
    FORMULIR PERMINTAAN PEMERIKSAAN LABORATORIUM KLINIK
  </h3>

  <!-- ====== BARIS ATAS (3 CARD) ====== -->
  <div class="row mb-4 g-3">

    <!-- Card Identitas Pasien -->
    <div class="col-md-4">
      <div class="card h-100 border border-dark shadow-sm">
        <div class="card-header bg-secondary text-white fw-bold text-center fs-5 py-2">
          IDENTITAS PASIEN
        </div>
        <div class="card-body p-3 bg-white">
          <table class="table table-borderless align-middle mb-0 fs-6 text-dark">
            <tbody>
              <tr>
                <td class="fw-semibold text-secondary" style="width:45%;">No. Register</td>
                <td>: <?= $form->no_register ?></td>
              </tr>
              <tr>
                <td class="fw-semibold text-secondary">Nama Pasien</td>
                <td>: <?= !empty($form->nama_pasien) ? $form->nama_pasien : ($form->nama_pasien ?? '-') ?></td>
              </tr>
              <tr>
                <td class="fw-semibold text-secondary">NIK</td>
                <td>: <?= $form->nik ?></td>
              </tr>
              <tr>
                <td class="fw-semibold text-secondary">Jenis Kelamin</td>
                <td>: <?= $form->gender ?></td>
              </tr>
              <tr>
                <td class="fw-semibold text-secondary">Tgl Lahir / Umur</td>
                <td>: <?= $form->tgl_lahir ?> / <?= $form->umur ?></td>
              </tr>
              <tr>
                <td class="fw-semibold text-secondary">No. Telp</td>
                <td>: <?= $form->no_telp ?></td>
              </tr>
              <tr>
                <td class="fw-semibold text-secondary">Alamat</td>
                <td>: <?= nl2br($form->alamat) ?></td>
              </tr>
              <tr>
                <td class="fw-semibold text-secondary">Puskesmas Wilayah</td>
                <td>: <span class="badge bg-info text-dark"><?= !empty($form->puskesmas_wilayah) ? $form->puskesmas_wilayah : 'Belum Dipetakan' ?></span></td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- Card Identitas Pengirim -->
    <div class="col-md-4">
      <div class="card h-100 border border-dark shadow-sm">
        <div class="card-header bg-secondary text-white fw-bold text-center fs-5 py-2">
          IDENTITAS PENGIRIM
        </div>
        <div class="card-body p-3 bg-white">
          <table class="table table-borderless align-middle mb-0 fs-6 text-dark">
            <tbody>
              <tr>
                <td class="fw-semibold text-secondary" style="width:45%;">Nama Dokter</td>
                <td>: <?= $form->nama_dokter ?></td>
              </tr>
              <tr>
                <td class="fw-semibold text-secondary">No. Telp Dokter</td>
                <td>: <?= $form->telp_pengirim ?></td>
              </tr>
              <tr>
                <td class="fw-semibold text-secondary">Alamat Dokter</td>
                <td>: <?= nl2br($form->alamat_pengirim) ?></td>
              </tr>
              <tr>
                <td class="fw-semibold text-secondary">Tanggal Form</td>
                <td>: <?= $form->tgl_form ?></td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- Card Diagnosa Klinis -->
    <div class="col-md-4">
      <div class="card h-100 border border-dark shadow-sm">
        <div class="card-header bg-secondary text-white fw-bold text-center fs-5 py-2">
          DIAGNOSA KLINIS
        </div>
        <div class="card-body p-3 bg-white">
          <table class="table table-borderless align-middle mb-0 fs-6 text-dark">
            <tbody>
              <tr>
                <td class="fw-semibold text-secondary" style="width:45%;">Diagnosa</td>
                <td>: <?= nl2br($form->diagnosa_klinis) ?></td>
              </tr>
              <tr>
                <td class="fw-semibold text-secondary">Obat yang Dikonsumsi</td>
                <td>: <?= nl2br($form->obat_dikonsumsi) ?></td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

  </div>

  <!-- ====== CARD DATA SPESIMEN ====== -->
  <div class="card shadow mb-4 border border-dark">
    <div class="card-header bg-secondary text-white text-center fw-bold fs-5 py-2">
      DATA SPESIMEN
    </div>

    <div class="card-body fs-6 bg-white">

      <!-- Asal & Status Puasa -->
      <div class="row mb-3">
        <div class="col-md-6">
          <span class="fw-semibold text-secondary d-block mb-1">Asal Sampel</span>
          <div class="border rounded px-3 py-2 bg-light"><?= $form->asal_sampel ?></div>
        </div>
        <div class="col-md-6">
          <span class="fw-semibold text-secondary d-block mb-1">
            Status Puasa
          </span>

          <div class="border rounded px-3 py-2 bg-light">

            <?= $form->puasa ?>

            <?php if (!empty($form->keterangan_puasa)) : ?>
              - <?= $form->keterangan_puasa ?>
            <?php endif; ?>

          </div>
        </div>
      </div>

      <!-- Lokasi -->
      <div class="mb-3">
        <span class="fw-semibold text-secondary d-block mb-1">Lokasi Pengambilan</span>
        <div class="border rounded px-3 py-2 bg-light">
          <?= $form->lokasi_pengambilan ?>
          <?= $form->lokasi_lainnya ? ', ' . $form->lokasi_lainnya : '' ?>
        </div>
      </div>

      <!-- Jenis -->
      <div class="mb-3">
        <span class="fw-semibold text-secondary d-block mb-1">Jenis Spesimen</span>
        <div class="border rounded px-3 py-2 bg-light">
          <?= $form->jenis_spesimen ?>
          <?= $form->spesimen_lainnya ? ', ' . $form->spesimen_lainnya : '' ?>
        </div>
      </div>

      <!-- Tanggal & Jam -->
      <div class="row mb-3">
        <div class="col-md-4"><span class="fw-semibold text-secondary d-block mb-1">Tanggal Permintaan</span>
          <div class="border rounded px-3 py-2 bg-light"><?= $form->tgl_permintaan ?></div>
        </div>
        <div class="col-md-4"><span class="fw-semibold text-secondary d-block mb-1">Tanggal Pengambilan</span>
          <div class="border rounded px-3 py-2 bg-light"><?= $form->tgl_pengambilan ?></div>
        </div>
        <div class="col-md-4"><span class="fw-semibold text-secondary d-block mb-1">Jam Pengambilan</span>
          <div class="border rounded px-3 py-2 bg-light"><?= $form->jam_pengambilan ?></div>
        </div>
      </div>

      <!-- Volume & Prioritas -->
      <div class="row mb-3">
        <div class="col-md-6"><span class="fw-semibold text-secondary d-block mb-1">Volume Spesimen</span>
          <div class="border rounded px-3 py-2 bg-light"><?= $form->volume_spesimen ?></div>
        </div>
        <div class="col-md-6"><span class="fw-semibold text-secondary d-block mb-1">Prioritas</span>
          <div class="border rounded px-3 py-2 bg-light"><?= $form->prioritas ?></div>
        </div>
      </div>

      <!-- Tambahan -->
      <div class="mb-2">
        <span class="fw-semibold text-secondary d-block mb-1">Informasi Tambahan</span>
        <div class="border rounded px-3 py-2 bg-light"><?= nl2br(html_escape($form->info_tambahan)) ?></div>
      </div>

    </div>
  </div>

  <!-- ====== CARD DAFTAR PEMERIKSAAN ====== -->
  <div class="card shadow mb-4 border border-dark">
    <div class="card-header bg-secondary text-white text-center fw-bold fs-5 py-2">
      DAFTAR PEMERIKSAAN
    </div>

    <div class="card-body bg-white">

      <?php if (!empty($detail)): ?>
        <table class="table table-bordered table-striped table-sm align-middle">
          <thead class="table-secondary text-center">
            <tr>
              <th width="5%">No</th>
              <th width="45%">Jenis Pemeriksaan</th>
              <th width="25%">Subkategori</th>
              <th width="25%">Kategori</th>
            </tr>
          </thead>

          <tbody>
            <?php
            $no = 1;
            $kategori_sebelumnya = "";
            $sub_sebelumnya = "";
            ?>

            <?php foreach ($detail as $d): ?>

              <?php if ($kategori_sebelumnya != $d->kategori): ?>
                <tr class="table-primary">
                  <td colspan="4" class="fw-bold text-uppercase"><?= $d->kategori ?></td>
                </tr>
                <?php
                $kategori_sebelumnya = $d->kategori;
                $sub_sebelumnya = "";
                $no = 1;
                ?>
              <?php endif; ?>

              <?php if ($sub_sebelumnya != $d->sub_kategori): ?>
                <tr class="table-info">
                  <td colspan="4" class="fw-bold ps-4 text-uppercase"><?= $d->sub_kategori ?></td>
                </tr>
                <?php $sub_sebelumnya = $d->sub_kategori; ?>
              <?php endif; ?>

              <tr>
                <td class="text-center"><?= $no++ ?></td>
                <td><?= $d->nama_jenis ?></td>
                <td><?= $d->sub_kategori ?></td>
                <td><?= $d->kategori ?></td>
              </tr>

            <?php endforeach; ?>
          </tbody>
        </table>

      <?php else: ?>
        <p class="text-muted text-center">Tidak ada data pemeriksaan.</p>
      <?php endif; ?>

    </div>
  </div>

  <!-- Lain-lain -->
  <div class="col-md-12">
    <div class="card h-100 border border-dark shadow-sm">
      <div class="card-header bg-secondary text-white fw-bold text-center fs-5 py-2">Informasi Tambahan</div>
      <div class="card-body p-3">
        <div class="mb-2">
          <div class="border rounded px-3 py-2 bg-light"><?= nl2br(html_escape($form->informasi_lain)) ?></div>
        </div>
      </div>
    </div>
  </div>

  <!-- ====== KELAYAKAN SAMPEL + PEMBAYARAN ====== -->
  <div class="row mb-4 g-3">

    <!-- Kelayakan -->
    <div class="col-md-6">
      <div class="card h-100 border border-dark shadow-sm">
        <div class="card-header bg-secondary text-white fw-bold text-center fs-5 py-2">KELAYAKAN SAMPEL</div>
        <div class="card-body p-3">
          <table class="table table-borderless mb-0">
            <tbody>
              <tr>
                <td class="fw-semibold text-secondary" style="width:45%;">Status</td>
                <td>: <?= $kelayakan['status'] ?></td>
              </tr>

              <?php if ($kelayakan['status'] == 'Tidak Layak'): ?>
                <tr>
                  <td class="fw-semibold text-secondary">Alasan</td>
                  <td>: <?= $kelayakan['alasan'] ?></td>
                </tr>
              <?php endif; ?>

              <tr>
                <td class="fw-semibold text-secondary">Volume Sampel Kaji Ulang</td>
                <td>: <?= $form->volume_sampel_kaji_ulang ?: '-' ?></td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- Pembayaran -->
    <div class="col-md-6">
      <div class="card h-100 border border-dark shadow-sm">
        <div class="card-header bg-secondary text-white fw-bold text-center fs-5 py-2">INFORMASI PEMBAYARAN</div>
        <div class="card-body p-3">
          <?php if (!empty($pembayaran->metode_pembayaran)): ?>
            <table class="table table-borderless mb-0">
              <tbody>
                <tr>
                  <td class="fw-semibold text-secondary" style="width:45%;">Metode Pembayaran</td>
                  <td>: <?= $pembayaran->metode_pembayaran ?></td>
                </tr>
                <tr>
                  <td class="fw-semibold text-secondary">Jumlah Biaya</td>
                  <td>: Rp <?= number_format($pembayaran->jumlah_biaya ?? 0, 0, ',', '.') ?></td>
                </tr>
              </tbody>
            </table>
          <?php else: ?>
            <p class="text-muted fst-italic">Belum ada data pembayaran.</p>
          <?php endif; ?>
        </div>
      </div>
    </div>

  </div>

  <!-- ====== PETUGAS TERLIBAT ====== -->
  <div class="card border border-dark shadow-sm mb-4">
    <div class="card-header bg-secondary text-white fw-bold text-center fs-5 py-2">PETUGAS TERLIBAT</div>

    <div class="card-body bg-white fs-6">
      <table class="table table-borderless mb-0">
        <tbody>
          <tr>
            <td class="fw-semibold text-secondary" style="width:40%;">Petugas Pendaftaran</td>
            <td>: <?= $form->petugas_pendaftaran ?: '-' ?></td>
          </tr>
          <tr>
            <td class="fw-semibold text-secondary">Petugas Pengambil Sampel</td>
            <td>: <?= $form->petugas_pengambil ?: '-' ?></td>
          </tr>
          <tr>
            <td class="fw-semibold text-secondary">Petugas Verifikasi</td>
            <td>: <?= $form->petugas_verifikasi ?: '-' ?></td>
          </tr>
          <tr>
            <td class="fw-semibold text-secondary">Petugas Validasi</td>
            <td>: <?= $form->petugas_validasi ?: '-' ?></td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>

  <!-- Buttons -->
  <div class="mt-3 mb-5">
    <a href="<?= site_url('klinik/form_permintaan_klinik') ?>" class="btn btn-secondary px-4">Kembali</a>
    <a href="<?= site_url('klinik/form_permintaan_klinik/print/' . $form->id) ?>" class="btn btn-danger px-4" target="_blank">Cetak PDF</a>
  </div>
</div>
<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <title>Formulir Permintaan Pemeriksaan Laboratorium Klinik</title>
  <style>
    body {
      font-family: 'Arial', sans-serif;
      font-size: 7pt;
      color: #000;
      line-height: 1.1;
      margin: 0;
      padding: 0;
    }

    /* ===== TITLE ===== */
    .title {
      text-align: center;
      font-weight: bold;
      font-size: 8.5pt;
      text-decoration: underline;
      margin: 2px 0 4px;
    }

    /* ===== TABLES ===== */
    table {
      width: 100%;
      border-collapse: collapse;
      font-size: 7pt;
    }

    th,
    td {
      padding: 1.5px 3px;
      vertical-align: top;
    }

    .bordered {
      border: 1px solid #000;
    }

    .bordered td {
      border: 1px solid #000;
      vertical-align: top;
      padding: 1.5px 3px;
    }

    .section-title {
      background: #e6e6e6;
      font-weight: bold;
      text-align: center;
      border: 1px solid #000;
      padding: 1.5px 0;
      font-size: 7.5pt;
      text-transform: uppercase;
    }

    .triple-col {
      width: 33.33%;
      border: 1px solid #000;
    }

    .double-col {
      width: 50%;
    }

    .col-title {
      background: #e6e6e6;
      text-align: center;
      font-weight: bold;
      font-size: 7.5pt;
      border-bottom: 1px solid #000;
      padding: 1.5px 0;
    }

    .content {
      padding: 2px 4px;
    }

    .subtable {
      width: 100%;
      border-collapse: collapse;
      font-size: 7pt;
    }

    .subtable td {
      border: none !important;
      padding: 0.5px 2px;
      vertical-align: top;
    }

    .mt-1 {
      margin-top: 2px;
    }

    .mt-2 {
      margin-top: 4px;
    }

    .mt-3 {
      margin-top: 6px;
    }

    .center {
      text-align: center;
    }

    .bold {
      font-weight: bold;
    }

    .underline {
      text-decoration: underline;
    }

    /* Tabel pemeriksaan */
    .tbl-pemeriksaan th,
    .tbl-pemeriksaan td {
      border: 1px solid #000;
      vertical-align: middle;
      font-size: 7pt;
      line-height: 1.1;
    }

    .tbl-pemeriksaan th {
      padding: 2px 0;
    }

    .tbl-pemeriksaan td {
      padding: 1.5px 0 1.5px 8px;
    }

    /* Tabel spesimen */
    .tbl-spesimen {
      width: 100%;
      border: 1px solid #000;
      border-collapse: collapse;
      margin-top: 2px;
    }

    .tbl-spesimen td {
      border: none !important;
      padding: 1px 4px !important;
      line-height: 1.1;
      font-size: 7pt;
    }

    .tbl-spesimen .label {
      width: 25%;
      font-weight: bold;
      color: #000;
    }

    /* === Print-friendly === */
    @media print {

      .bordered,
      .bordered td {
        border: 1px solid #000;
      }

      .section-title {
        background: #f0f0f0 !important;
      }
    }
  </style>
</head>

<body>

  <!-- HEADER -->
  <table style="width: 100%; border-bottom: 1.5px solid #000; margin-bottom: 4px; padding-bottom: 2px;">
    <tr>
      <!-- KIRI: LOGO -->
      <td style="width: 100px; text-align: center; vertical-align: middle; padding: 0;">
        <img src="<?= base_url('assets/img/logolabkes.png') ?>" alt="Logo" style="width: 100px; height: auto;">
      </td>

      <!-- TENGAH: TEKS HEADER -->
      <td style="text-align: center; vertical-align: middle; padding: 0 10px;">
        <h2 style="margin: 0; font-size: 11pt; font-weight: bold; line-height: 1.2;">PEMERINTAH KOTA PANGKALPINANG</h2>
        <h3 style="margin: 0; font-size: 9pt; font-weight: bold; line-height: 1.2;">DINAS KESEHATAN</h3>
        <p style="margin: 0; font-size: 8pt; font-weight: bold; line-height: 1.2;">UPTD LABORATORIUM KESEHATAN</p>
        <p style="margin: 0; font-size: 7.5pt; line-height: 1.2;">Jl. Delima Siam II, Sriwijaya, Kec. Girimaya, Kota Pangkal Pinang, Kepulauan Bangka Belitung 33684 - Telp (0717) 9120759</p>
      </td>

      <!-- KANAN: BALANCE BOX -->
      <td style="width: 100px; padding: 0;"></td>
    </tr>
  </table>

  <!-- TITLE -->
  <div class="title">
    FORMULIR PERMINTAAN PEMERIKSAAN LABORATORIUM KLINIK
  </div>

  <!-- IDENTITAS -->
  <table class="bordered" style="width: 100%; border-collapse: collapse;">
    <tr>
      <!-- === IDENTITAS PASIEN === -->
      <td class="triple-col" style="padding: 0; width: 33.3%;">
        <div class="col-title">IDENTITAS PASIEN</div>
        <div class="content">
          <table class="subtable">
            <tr>
              <td style="width: 40%;">No. Register</td>
              <td>: <?= html_escape($form->no_register) ?></td>
            </tr>
            <tr>
              <td>Nama Pasien</td>
              <td>: <?= html_escape($form->nama_pasien) ?></td>
            </tr>
            <tr>
              <td>NIK</td>
              <td>: <?= html_escape($form->nik) ?></td>
            </tr>
            <tr>
              <td>Jenis Kelamin</td>
              <td>: <?= html_escape($form->gender) ?></td>
            </tr>
            <tr>
              <td>Tgl Lahir / Umur</td>
              <td>: <?= html_escape($form->tgl_lahir) ?> / <?= html_escape($form->umur) ?> Th </td>
            </tr>
            <tr>
              <td>No. Telp</td>
              <td>: <?= html_escape($form->no_telp) ?></td>
            </tr>
            <tr>
              <td>Alamat</td>
              <td>: <?= nl2br(html_escape($form->alamat)) ?></td>
            </tr>
          </table>
        </div>
      </td>

      <!-- === IDENTITAS PENGIRIM === -->
      <td class="triple-col" style="padding: 0; width: 33.3%;">
        <div class="col-title">IDENTITAS PENGIRIM</div>
        <div class="content">
          <table class="subtable">
            <tr>
              <td style="width: 40%;">Nama Dokter</td>
              <td>: <?= html_escape($form->nama_dokter) ?></td>
            </tr>
            <tr>
              <td>No. Telp Dokter</td>
              <td>: <?= html_escape($form->telp_pengirim) ?></td>
            </tr>
            <tr>
              <td>Alamat Dokter</td>
              <td>: <?= nl2br(html_escape($form->alamat_pengirim)) ?></td>
            </tr>
            <tr>
              <td>Tanggal Form</td>
              <td>: <?= html_escape($form->tgl_form) ?></td>
            </tr>
          </table>
        </div>
      </td>

      <!-- === DIAGNOSA KLINIS === -->
      <td class="triple-col" style="padding: 0; width: 33.3%;">
        <div class="col-title">DIAGNOSA KLINIS</div>
        <div class="content">
          <table class="subtable">
            <tr>
              <td style="width: 40%;">Diagnosa Klinik</td>
              <td>: <?= nl2br(html_escape($form->diagnosa_klinis)) ?></td>
            </tr>
            <tr>
              <td>Obat Dikonsumsi</td>
              <td>: <?= nl2br(html_escape($form->obat_dikonsumsi)) ?></td>
            </tr>
            <tr>
              <td>Tanda Tangan Pengirim</td>
              <td>: </td>
            </tr>
          </table>
        </div>
      </td>
    </tr>
  </table>

  <!-- DATA SPESIMEN -->
  <div class="mt-2">
    <div class="section-title">DATA SPESIMEN</div>
    <table class="tbl-spesimen">
      <tr>
        <td class="label">Asal Sampel</td>
        <td>: <?= html_escape($form->asal_sampel) ?></td>
        <td class="label">Status Puasa</td>
        <td>: <?= html_escape($form->puasa) ?> - <?= html_escape($form->keterangan_puasa) ?></td>
      </tr>
      <tr>
        <td class="label">Lokasi Pengambilan</td>
        <td>: <?= html_escape($form->lokasi_pengambilan) ?><?= $form->lokasi_lainnya ? ', ' . html_escape($form->lokasi_lainnya) : '' ?></td>
        <td class="label">Jenis Spesimen</td>
        <td>: <?= html_escape($form->jenis_spesimen) ?><?= $form->spesimen_lainnya ? ', ' . html_escape($form->spesimen_lainnya) : '' ?></td>
      </tr>
      <tr>
        <td class="label">Tanggal Permintaan</td>
        <td>: <?= html_escape($form->tgl_permintaan) ?></td>
        <td class="label">Prioritas</td>
        <td>: <?= html_escape($form->prioritas) ?></td>
      </tr>
      <tr>
        <td class="label">Tanggal Pengambilan</td>
        <td>: <?= html_escape($form->tgl_pengambilan) ?></td>
        <td class="label">Volume Spesimen</td>
        <td>: <?= html_escape($form->volume_spesimen) ?></td>
      </tr>
      <tr>
        <td class="label">Jam Pengambilan</td>
        <td>: <?= html_escape($form->jam_pengambilan) ?></td>
        <td class="label">Informasi Tambahan</td>
        <td>: <?= nl2br(html_escape($form->info_tambahan)) ?></td>
      </tr>
    </table>
  </div>
  
  <!-- TABEL PEMERIKSAAN -->
  <div class="mt-2">
    <table class="tbl-pemeriksaan" style="width:100%; margin-top:2px;">
      <thead>
        <tr>
          <th style="width:5%; text-align:center; padding:2px 0;">No</th>
          <th style="width:95%; text-align:left; padding-left:8px; padding-top:2px; padding-bottom:2px;">Jenis Pemeriksaan</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $no = 1;
        $kategori = '';
        $sub = '';

        foreach ($detail as $d):
          // === HEADER KATEGORI ===
          if ($kategori != $d->kategori):
            $kategori = $d->kategori;
            $sub = '';
            $no = 1;
        ?>
            <tr style="background:#e6e6e6; font-weight:bold;">
              <td colspan="2" style="padding:2px 4px;">
                <?= html_escape(strtoupper($kategori)) ?>
              </td>
            </tr>
          <?php endif; ?>

          <?php
          // === HEADER SUBKATEGORI ===
          if ($sub != $d->sub_kategori && $d->sub_kategori != ''):
            $sub = $d->sub_kategori;
          ?>
            <tr style="background:#f7f7f7; font-weight:bold;">
              <td colspan="2" style="padding:1.5px 4px 1.5px 15px;">
                <?= html_escape(strtoupper($sub)) ?>
              </td>
            </tr>
          <?php endif; ?>

          <!-- === ROW JENIS PEMERIKSAAN === -->
          <tr>
            <td style="text-align:center; padding:1.5px 4px;"><?= $no++ ?></td>
            <td style="padding:1.5px 4px 1.5px 8px;">
              <?= html_escape($d->nama_jenis) ?>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>

<div class="mt-2">
    <div class="section-title"> informasi lain-lain </div>
    <table class="bordered" style="width: 100%; border-collapse: collapse; margin-top: 0;">
      <tr>
        <!-- === KELAYAKAN SAMPEL === -->
        <td class="double-col">
          <div class="content" style="padding: 0;">
            <table class="subtable">
              <tr>
                <td>: <?= $form->informasi_lain ? html_escape($form->informasi_lain) : '-' ?></td>
              </tr>
            </table>
          </div>
        </td>
      </tr>
    </table>
  </div>

  <!-- KAJI ULANG PERMINTAAN PEMERIKSAAN -->
  <div class="mt-2">
    <div class="section-title">KAJI ULANG PERMINTAAN PEMERIKSAAN</div>
    <table class="bordered" style="width: 100%; border-collapse: collapse; margin-top: 0;">
      <tr>
        <!-- === KELAYAKAN SAMPEL === -->
        <td class="double-col">
          <div style="font-weight: bold; font-size: 7.5pt; border-bottom: 1px solid #e0e0e0; padding-bottom: 1px; margin-bottom: 3px;">KELAYAKAN SAMPEL</div>
          <div class="content" style="padding: 0;">
            <table class="subtable">
              <tr>
                <td style="width: 40%;">Status</td>
                <td>: <?= html_escape($kelayakan['status']) ?></td>
              </tr>
              <?php if ($kelayakan['status'] == 'Tidak Layak'): ?>
                <tr>
                  <td>Alasan</td>
                  <td>: <?= html_escape($kelayakan['alasan']) ?></td>
                </tr>
              <?php endif; ?>
              <tr>
                <td>Volume Sampel</td>
                <td>: <?= $form->volume_sampel_kaji_ulang ? html_escape($form->volume_sampel_kaji_ulang) : '-' ?></td>
              </tr>
            </table>
          </div>
        </td>

        <!-- === INFORMASI PEMBAYARAN === -->
        <td class="double-col">
          <div style="font-weight: bold; font-size: 7.5pt; border-bottom: 1px solid #e0e0e0; padding-bottom: 1px; margin-bottom: 3px;">INFORMASI PEMBAYARAN</div>
          <div class="content" style="padding: 0;">
            <table class="subtable">
              <tr>
                <td style="width: 40%;">Jumlah Biaya</td>
                <td>: Rp <?= number_format($pembayaran->jumlah_biaya, 0, ',', '.') ?></td>
              </tr>
              <tr>
                <td>Metode Pembayaran</td>
                <td>: <?= html_escape($pembayaran->metode_pembayaran) ?></td>
              </tr>
            </table>
          </div>
        </td>
      </tr>
    </table>
  </div>

  <!-- PERNYATAAN PASIEN & PETUGAS -->
  <div class="mt-2">
    <table style="width:100%; border:1px solid #000; border-collapse:collapse; font-size:7pt;">
      <tr>
        <td style="padding:3px; line-height:1.1;">
          <p style="margin:0;">
            Dengan ini saya menyatakan bahwa informasi yang saya berikan adalah benar dan saya memahami serta menyetujui
            untuk dilakukan pengambilan, penyimpanan, dan tindakan pemeriksaan yang dilakukan oleh
            UPTD Labkes Kota Pangkalpinang untuk pemeriksaan saya.
          </p>
        </td>
        <td style="width:25%; vertical-align:top; text-align:center; padding:3px;">
          <p style="margin:0;">Tgl: <?= date('d-m-Y', strtotime($form->tgl_form)) ?></p>
          <p style="margin:1px 0 0 0;"><strong>Tanda Tangan Pasien</strong></p><br><br>
          <p style="margin:0;"> <?= html_escape($form->nama_pasien ?? '-') ?> </p>
        </td>
      </tr>
    </table>

    <table style="width:100%; border:1px solid #000; border-collapse:collapse; font-size:7pt; margin-top:3px;">
      <tr style="text-align:center; font-weight:bold;">
        <td style="width:25%; vertical-align:top; padding:3px; border-right: 1px solid #000;">
          <strong>Petugas Pendaftaran</strong>
          <br><br>
          <p style="margin:0; font-weight:normal; font-size:7pt;">
            <?= !empty($form->petugas_pendaftaran) ? html_escape($form->petugas_pendaftaran) : '_______________________' ?>
          </p>
        </td>
        <td style="width:25%; vertical-align:top; padding:3px; border-right: 1px solid #000;">
          <strong>Petugas Pengambil Sampel</strong>
          <br><br>
          <p style="margin:0; font-weight:normal; font-size:7pt;">
            <?= !empty($form->petugas_pengambil) ? html_escape($form->petugas_pengambil) : '_______________________' ?>
          </p>
        </td>
        <td style="width:25%; vertical-align:top; padding:3px; border-right: 1px solid #000;">
          <strong>Petugas Verifikasi</strong>
          <br><br>
          <p style="margin:0; font-weight:normal; font-size:7pt;">
            <?= !empty($form->petugas_validasi) ? html_escape($form->petugas_validasi) : '_______________________' ?>
          </p>
        </td>
        <td style="width:25%; vertical-align:top; padding:3px;">
          <strong>Petugas Validasi</strong>
          <br><br>
          <p style="margin:0; font-weight:normal; font-size:7pt;">
            <?= !empty($form->petugas_verifikasi) ? html_escape($form->petugas_verifikasi) : '_______________________' ?>
          </p>
        </td>
      </tr>
    </table>
  </div>

  <!-- KARTU KENDALI WAKTU PEMERIKSAAN LABORATORIUM -->
  <div class="mt-2">
    <div class="section-title" style="font-weight:bold; margin-bottom:1px; font-size:7.5pt; padding:1.5px 0;">
      KARTU KENDALI WAKTU PEMERIKSAAN LABORATORIUM
    </div>

    <table style="width:100%; border:1px solid #000; border-collapse:collapse; font-size:7pt; text-align:center;">
      <thead style="font-weight:bold; background:#f2f2f2;">
        <tr>
          <td style="width:16%; border:1px solid #000; padding:1.5px;">Pengambilan Sampel</td>
          <td style="width:4%; border:1px solid #000; padding:1.5px;">Paraf</td>
          <td style="width:16%; border:1px solid #000; padding:1.5px;">Sampel Diterima Lab</td>
          <td style="width:4%; border:1px solid #000; padding:1.5px;">Paraf</td>
          <td style="width:16%; border:1px solid #000; padding:1.5px;">Pengerjaan Sampel</td>
          <td style="width:4%; border:1px solid #000; padding:1.5px;">Paraf</td>
          <td style="width:16%; border:1px solid #000; padding:1.5px;">Input Hasil</td>
          <td style="width:4%; border:1px solid #000; padding:1.5px;">Paraf</td>
          <td style="width:16%; border:1px solid #000; padding:1.5px;">Cetak Hasil Uji</td>
          <td style="width:4%; border:1px solid #000; padding:1.5px;">Paraf</td>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td style="border:1px solid #000; height:30px; padding:1.5px;"></td>
          <td style="border:1px solid #000; padding:1.5px;"></td>
          <td style="border:1px solid #000; padding:1.5px;"></td>
          <td style="border:1px solid #000; padding:1.5px;"></td>
          <td style="border:1px solid #000; padding:1.5px;"></td>
          <td style="border:1px solid #000; padding:1.5px;"></td>
          <td style="border:1px solid #000; padding:1.5px;"></td>
          <td style="border:1px solid #000; padding:1.5px;"></td>
          <td style="border:1px solid #000; padding:1.5px;"></td>
          <td style="border:1px solid #000; padding:1.5px;"></td>
        </tr>
        <tr>
          <td colspan="10" style="border-top:1.5px solid #000; font-weight:bold; text-align:left; padding:1.5px 4px;">CATATAN :</td>
        </tr>
      </tbody>
    </table>
  </div>

  <script>
    window.onload = function() {
      window.print();
    }
  </script>
</body>

</html>
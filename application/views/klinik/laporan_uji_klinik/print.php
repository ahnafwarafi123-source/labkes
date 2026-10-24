<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="utf-8">
  <title>Laporan Hasil Uji</title>
  <style>
    body {
      font-family: 'Arial', sans-serif;
      font-size: 10pt;
      color: #000;
    }

    /* Warna kategori (lebih gelap) */
    .row-kategori {
      background: #d0d0d0 !important;
      font-weight: bold;
    }

    /* Warna subkategori (lebih terang) */
    .row-subkategori {
      background: #e8e8e8 !important;
      font-weight: bold;
    }


    /* ===== HEADER ===== */
    .header {
      border-bottom: 1.5px solid #000;
      margin-bottom: 8px;
      padding-bottom: 4px;
      position: relative;
    }

    .header-content {
      display: flex;
      align-items: center;
      justify-content: center;
      position: relative;
    }

    /* 🔹 Logo di kiri atas */
    .header-content img {
      position: absolute;
      left: 0;
      top: 0;
      width: 55px;
      /* ukuran kecil dan proporsional */
      height: auto;
    }

    .header-text {
      text-align: center;
      width: 100%;
    }

    .header-text h2,
    .header-text h3,
    .header-text p {
      margin: 0;
      line-height: 1.3;
    }

    .header-text h2 {
      font-size: 12pt;
      font-weight: bold;
    }

    .header-text h3 {
      font-size: 10pt;
      font-weight: bold;
    }

    .header-text p {
      font-size: 9pt;
    }

    h3.title {
      text-align: center;
      font-weight: bold;
      font-size: 11pt;
      text-decoration: underline;
      margin: 8px 0;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      font-size: 9pt;
      margin-bottom: 8px;
    }

    table,
    th,
    td {
      border: 1px solid #000;
      padding: 4px 6px;
    }

    th {
      background-color: #f2f2f2;
      text-align: center;
    }

    td {
      vertical-align: top;
    }

    .no-border,
    .no-border td {
      border: none !important;
    }

    .no-border td {
      padding: 2px 4px;
    }

    .sub-header {
      font-weight: bold;
      background: #fafafa;
    }

    .footer-table td {
      border: none;
      font-size: 9pt;
      vertical-align: top;
    }

    .footer-left {
      width: 60%;
    }

    .footer-right {
      width: 40%;
      text-align: center;
      padding-left: 60px;
    }

    /* Tambahan dari form pendaftaran */
    .section-title {
      background: #e6e6e6;
      font-weight: bold;
      text-align: center;
      border: 1px solid #000;
      padding: 2px 0;
      font-size: 8pt;
      text-transform: uppercase;
      margin-bottom: 2px;
    }

    .tbl-spesimen {
      width: 100%;
      border: 1px solid #000;
      border-collapse: collapse;
      margin-top: 2px;
      margin-bottom: 8px;
    }

    .tbl-spesimen td {
      border: none !important;
      padding: 4px 6px !important;
      line-height: 1.2;
      font-size: 8.5pt;
    }

    .tbl-spesimen .label {
      width: 25%;
      font-weight: bold;
      color: #000;
    }
  </style>
</head>

<body>

  <!-- HEADER -->
  <table style="width:100%; border-bottom:1.5px solid #000; margin-bottom:6px; padding-bottom:4px;">
    <tr>
      <!-- KIRI: LOGO -->
      <td style="width:100px; text-align:center; vertical-align:middle; border:none;">
        <img src="<?= base_url('assets/img/logo.png') ?>" alt="Logo" style="width:100px; height:auto;">
      </td>

      <!-- TENGAH: TEKS HEADER (BENAR-BENAR DI TENGAH HALAMAN) -->
      <td style="text-align:center; vertical-align:middle; border:none;">
        <h2 style="margin:0; font-size:12pt; font-weight:bold;">PEMERINTAH KOTA PANGKALPINANG</h2>
        <h3 style="margin:0; font-size:10pt; font-weight:bold;">DINAS KESEHATAN</h3>
        <p style="margin:0; font-size:9pt;"><strong>UPTD LABORATORIUM KESEHATAN</strong></p>
        <p style="margin:0; font-size:8pt;">Jl. Delima Siam II, Sriwijaya, Kec. Girimaya, Kota Pangkal Pinang, Kepulauan Bangka Belitung 33684 - Telp (0717) 9120759</p>
      </td>

      <!-- KANAN: KOTAK KOSONG UNTUK BALANCE -->
      <td style="width:100px; border:none;"></td>
    </tr>
  </table>



  <!-- Judul -->
  <div style="text-align:center; font-weight:bold; font-size:10pt; text-decoration:underline; margin:6px 0 8px;">
    LAPORAN HASIL UJI
  </div>



  <!-- Data Pasien & Spesimen -->
  <table class="no-border">
    <tr>
      <td>No. Registrasi</td>
      <td>:</td>
      <td><?= $form->no_register ?? '-' ?></td>
      <td>Kondisi Spesimen</td>
      <td>:</td>
      <td><?= $form->kelayakan ?? '-' ?></td>
    </tr>
    <tr>
      <td>Nama</td>
      <td>:</td>
      <td><?= $form->nama_pasien ?? '-' ?></td>
      <td>Petugas Pengambil Spesimen</td>
      <td>:</td>
      <td><?= $pengambilan->petugas_pengambilan ?? '-' ?></td>
    </tr>
    <tr>
      <td>Jenis Kelamin</td>
      <td>:</td>
      <td><?= $form->gender ?? '-' ?></td>
      <td>Tgl/Jam Pengambilan</td>
      <td>:</td>
      <td>
        <?php
        if (!empty($form->tgl_pengambilan)) {
          $tgl = date('d-m-Y', strtotime($form->tgl_pengambilan));
          $jam = !empty($form->jam_pengambilan) ? date('H.i', strtotime($form->jam_pengambilan)) : '00.00';
          echo $tgl . ' / ' . $jam . ' WIB';
        } else {
          echo '-';
        }
        ?>
      </td>
    </tr>
    <tr>
      <td>Tanggal Lahir / Usia</td>
      <td>:</td>
      <td><?= ($form->tgl_lahir ? date('d-m-Y', strtotime($form->tgl_lahir)) : '-') . ' / ' . ($form->umur ?? '-') ?> Th </td>
      <td>Tgl/Jam Pemeriksaan</td>
      <td>:</td>
      <td><?= $pengambilan ? date('d-m-Y / H.i', strtotime($pengambilan->tgl_jam_pemeriksaan)) . ' WIB' : '-' ?></td>
    </tr>
    <tr>
      <td>Dokter Pengirim</td>
      <td>:</td>
      <td><?= $form->nama_dokter ?? '-' ?></td>
      <td>Tgl/Jam Selesai Pemeriksaan</td>
      <td>:</td>
      <td><?= $pengambilan ? date('d-m-Y / H.i', strtotime($pengambilan->tgl_jam_selesai)) . ' WIB' : '-' ?></td>
    </tr>
    <tr>
      <td>Tanggal Pengiriman</td>
      <td>:</td>
      <td><?= $form->tgl_form ? date('d-m-Y', strtotime($form->tgl_form)) : '-' ?></td>
      <td>Tgl/Jam Pelaporan Hasil</td>
      <td>:</td>
      <td><?= $pengambilan ? date('d-m-Y / H.i', strtotime($pengambilan->tgl_jam_pelaporan)) . ' WIB' : '-' ?></td>
    </tr>
  </table>

  <!-- Tabel Hasil Pemeriksaan -->
  <table>
    <thead>
      <tr>
        <th width="4%">No</th>
        <th width="13%">Jenis Pemeriksaan</th>
        <th width="15%">Hasil</th>
        <th width="10%">Satuan</th>
        <th width="32%">Nilai Rujukan</th>
        <th width="20%">Metode Pemeriksaan</th>
      </tr>
    </thead>

    <tbody>
      <?php
      $no = 1;
      $kategori = '';
      $sub = '';

      foreach ($detail as $d):

        // HEADER KATEGORI
        if ($kategori != $d->kategori):

          $kategori = $d->kategori;
          $sub = '';

          // reset nomor
          $no = 1;
      ?>
          <tr class="row-kategori">
            <td colspan="6"><?= strtoupper($kategori) ?></td>
          </tr>
        <?php endif; ?>

        <?php
        // === HEADER SUB KATEGORI ===
        if ($sub != $d->sub_kategori):
          $sub = $d->sub_kategori;
        ?>
          <tr style="font-weight:bold; background:#f7f7f7;">
            <td colspan="6" style="padding-left:15px;"><?= strtoupper($sub) ?></td>
          </tr>
        <?php endif; ?>

        <?php

        $is_abnormal = false;

        $hasil = trim($d->hasil);
        $rujukan = trim($d->nilai_rujukan);
        $gender = strtolower(trim($form->gender));

        // ubah koma menjadi titik
        $hasil = str_replace(',', '.', $hasil);
        $rujukan = str_replace(',', '.', $rujukan);

        // hasil angka
        $hasil_angka = (float) preg_replace('/[^0-9\.]/', '', $hasil);
        if (stripos($rujukan, 'Laki-laki') !== false || stripos($rujukan, 'Perempuan') !== false) {

          if ($gender == 'laki-laki') {

            preg_match('/Laki-laki\s*:\s*([^\n,]+)/i', $rujukan, $match);
          } else {

            preg_match('/Perempuan\s*:\s*([^\n,]+)/i', $rujukan, $match);
          }

          if (!empty($match[1])) {
            $rujukan = trim($match[1]);
          }
        }
        $hasil_text = strtolower(trim($hasil));
        $rujukan_text = strtolower(trim($rujukan));

        if (
          strpos($rujukan_text, 'negatif') !== false ||
          strpos($rujukan_text, 'non reaktif') !== false ||
          strpos($rujukan_text, 'normal') !== false
        ) {

          if ($hasil_text != $rujukan_text) {
            $is_abnormal = true;
          }
        } elseif (preg_match('/<\s*([0-9\.]+)/', $rujukan, $match)) {

          $max = (float)$match[1];

          if ($hasil_angka >= $max) {
            $is_abnormal = true;
          }
        } elseif (preg_match('/([0-9\.]+)\s*[–-]\s*([0-9\.]+)/', $rujukan, $match)) {

          $min = (float)$match[1];
          $max = (float)$match[2];

          if ($hasil_angka < $min || $hasil_angka > $max) {
            $is_abnormal = true;
          }
        }

        ?>

        <!-- DATA ITEM PEMERIKSAAN -->
        <?php

        $is_abnormal = false;

        $hasil = trim(strtolower($d->hasil));
        $rujukan = trim(strtolower($d->nilai_rujukan));

        $gender = trim(strtolower($form->gender));
        $hasil = str_replace(',', '.', $hasil);
        $rujukan = str_replace(',', '.', $rujukan);
        $rujukan = str_replace(['–', '—'], '-', $rujukan);

        $rujukan_gender = $rujukan;

        // perempuan
        if (
          ($gender == 'perempuan' || $gender == 'wanita' || $gender == 'p')
          &&
          preg_match('/perempuan\s*:\s*([^,]+)/', $rujukan, $match)
        ) {
          $rujukan_gender = trim($match[1]);
        }

        // laki-laki
        elseif (
          ($gender == 'laki-laki' || $gender == 'l')
          &&
          preg_match('/laki-laki\s*:\s*([^,]+)/', $rujukan, $match)
        ) {
          $rujukan_gender = trim($match[1]);
        }

        $hasil_angka = (float) preg_replace('/[^0-9\.]/', '', $hasil);

        // --- CUSTOM LOGIC UNTUK eGFR/eLFG, HbA1c, Tubex TF ---
        if (stripos($d->nama_jenis, 'egfr') !== false || stripos($d->nama_jenis, 'elfg') !== false) {
          if ($hasil_angka < 90) {
            $is_abnormal = true;
          } else {
            $is_abnormal = false;
          }
        } elseif (stripos($d->nama_jenis, 'hba1c') !== false) {
          if ($hasil_angka >= 5.7) {
            $is_abnormal = true;
          } else {
            $is_abnormal = false;
          }
        } elseif (stripos($d->nama_jenis, 'tubex') !== false) {
          if ($hasil_angka < 0 || $hasil_angka > 2) {
            $is_abnormal = true;
          } else {
            $is_abnormal = false;
          }
        } else {
          // --- LOGIKA NORMAL ---
          if (preg_match('/([0-9\.]+)\s*-\s*([0-9\.]+)/', $rujukan_gender, $match)) {

            $min = (float)$match[1];
            $max = (float)$match[2];

            if ($hasil_angka < $min || $hasil_angka > $max) {
              $is_abnormal = true;
            }
          } elseif (preg_match('/<\s*([0-9\.]+)/', $rujukan_gender, $match)) {

            $max = (float)$match[1];

            if ($hasil_angka >= $max) {
              $is_abnormal = true;
            }
          } elseif (preg_match('/>\s*([0-9\.]+)/', $rujukan_gender, $match)) {

            $min = (float)$match[1];

            if ($hasil_angka <= $min) {
              $is_abnormal = true;
            }
          } elseif (
            strpos($rujukan_gender, 'negatif') !== false ||
            strpos($rujukan_gender, 'normal') !== false ||
            strpos($rujukan_gender, 'non reaktif') !== false
          ) {

            if (
              $hasil != 'negatif' &&
              $hasil != 'normal' &&
              $hasil != 'non reaktif'
            ) {
              $is_abnormal = true;
            }
          } else {

            if (!empty($rujukan_gender) && $hasil != $rujukan_gender) {
              $is_abnormal = true;
            }
          }
        }

        ?>

        <tr>

          <td style="text-align:center;">
            <?= $no++ ?>
          </td>

          <td>
            <?= $d->nama_jenis ?>
          </td>

          <!-- HASIL -->
          <td style="text-align:center;">

            <?php if ($is_abnormal): ?>

              <span style="color:black; font-weight:bold; font-size:11pt;">
                <?= $d->hasil ?> *
              </span>

            <?php else: ?>

              <?= $d->hasil ?>

            <?php endif; ?>

          </td>

          <!-- SATUAN -->
          <td style="text-align:center;">
            <?= $d->satuan ?? '-' ?>
          </td>

          <!-- NILAI RUJUKAN -->
          <td style="text-align:center;">
            <?= $d->nilai_rujukan ?? '-' ?>
          </td>

          <!-- METODE -->
          <td style="text-align:center;">
            <?= $d->metode ?? '-' ?>
          </td>

        </tr>

      <?php endforeach; ?>
    </tbody>
  </table>

  <?php if (!empty($form->info_tambahan)): ?>
    <div style="margin-top: 5px; font-size: 9pt;">
      <strong>Informasi Tambahan :</strong> <?= nl2br(html_escape($form->info_tambahan)) ?>
    </div>
  <?php endif; ?>

  <?php if (!empty($form->informasi_lain)): ?>
   <!-- Lain-lain -->
    <div class="mt-2">
      <div class="section-title">INFORMASI TAMBAHAN</div>
      <table class="tbl-spesimen">
        <tr>
          <td class="label">Informasi Tambahan</td>
          <td colspan="3">: <?= nl2br(html_escape($form->informasi_lain)) ?></td>
        </tr>
      </table>
    </div>
  <?php endif; ?>

  <div style="margin-top:5px; color:black; font-weight:bold;">
    * Menunjukkan hasil di luar nilai rujukan
  </div>
  <strong>* Note : <?= $pengambilan->note ?? 'Pemeriksaan dilakukan >2 jam setelah sampel diambil' ?></strong>
  <table class="no-border footer-table" style="margin-top:40px;">
    <tr>
      <td class="footer-left">
        Petugas : <strong><?= $pengambilan->petugas_hasil ?? '-' ?></strong> <br><br><br><br>
        Verifikator : <strong><?= $pengambilan->verifikator_hasil ?? '-' ?></strong>
      </td>
      <td class="footer-right"><br><br><br><br><br><br>
        Pangkalpinang, <?= date('d F Y') ?><br>
        Penanggung Jawab Teknis Laboratorium Klinik<br><br><br><br><br><br><br>
        <strong><?= $pengambilan->penanggung_jawab_teknis ?? '-' ?></strong><br>
        <strong><span><?= $pengambilan->sip_penanggung ?? '-' ?></span></strong>
    </tr>
    </td>
  </table>
</body>

</html>
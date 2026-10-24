<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <title><?= $title ?></title>
  <style>
    @page {
      size: A4 portrait;
      margin: 1.5cm;
    }
    body {
      font-family: 'Times New Roman', Times, serif;
      font-size: 11pt;
      color: #000;
      margin: 0;
      padding: 0;
    }

    .doc-title {
      text-align: center;
      font-weight: bold;
      font-size: 12pt;
      text-decoration: underline;
      margin-bottom: 15px;
    }

    .patient-info {
      width: 100%;
      margin-bottom: 15px;
      font-size: 10pt;
    }

    .patient-info td {
      vertical-align: top;
      padding: 2px 0;
    }

    .col-label {
      width: 120px;
    }

    .col-separator {
      width: 15px;
      text-align: center;
    }

    .soap-table {
      width: 100%;
      border-collapse: collapse;
      font-size: 10pt;
    }

    .soap-table th,
    .soap-table td {
      border: 1px solid #000;
      padding: 8px;
      vertical-align: top;
    }

    .soap-table th {
      text-align: center;
      font-weight: bold;
    }

    .section-title {
      margin-top: 10px;
      margin-bottom: 5px;
      margin-right: 10px;
      font-weight: bold;
    }

    .detail-row {
      margin-bottom: 8px;
    }
  </style>
</head>

<body>

  <!-- HEADER -->
  <table style="width:100%; border-bottom:1.5px solid #000; margin-bottom:6px; padding-bottom:4px;">
    <tr>
      <!-- KIRI: LOGO -->
      <td style="width:100px; text-align:center; vertical-align:middle; border:none;">
        <img src="assets/img/logolabkes.png" alt="Logo" style="width:100px; height:auto;">
      </td>

      <!-- TENGAH: TEKS HEADER (BENAR-BENAR DI TENGAH HALAMAN) -->
      <td style="text-align:center; vertical-align:middle; border:none;">
        <h2 style="margin:0; font-size:12pt; font-weight:bold;">PEMERINTAH KOTA PANGKALPINANG</h2>
        <h3 style="margin:0; font-size:10pt; font-weight:bold;">DINAS KESEHATAN</h3>
        <p style="margin:0; font-size:9pt;"><strong>UPTD LABORATORIUM KESEHATAN</strong></p>
        <p style="margin:0; font-size:8pt;">Jalan Delima Siam VI Girimaya Pangkal Pinang - Telp (0717) 9120759</p>
      </td>

      <!-- KANAN: KOTAK KOSONG UNTUK BALANCE -->
      <td style="width:100px; border:none;"></td>
    </tr>
  </table>

  <div class="doc-title">REKAM MEDIS</div>

  <table class="patient-info">
    <tr>
      <td style="width: 48%;">
        <table style="width: 100%;">
          <tr>
            <td class="col-label">No. RM</td>
            <td class="col-separator">:</td>
            <td><?= htmlspecialchars($pasien->no_rm) ?></td>
          </tr>
          <tr>
            <td class="col-label">Nama</td>
            <td class="col-separator">:</td>
            <td><?= htmlspecialchars($pasien->nama_pasien) ?></td>
          </tr>
          <tr>
            <td class="col-label">NIK</td>
            <td class="col-separator">:</td>
            <td><?= htmlspecialchars($pasien->nik) ?></td>
          </tr>
          <tr>
            <td class="col-label">Jenis Kelamin</td>
            <td class="col-separator">:</td>
            <td><?= htmlspecialchars($pasien->gender) ?></td>
          </tr>
          <tr>
            <td class="col-label">Tanggal Lahir</td>
            <td class="col-separator">:</td>
            <td><?= date('d F Y', strtotime($pasien->tgl_lahir)) ?></td>
          </tr>
          <tr>
            <td class="col-label">Umur</td>
            <td class="col-separator">:</td>
            <td><?= $pasien->umur ?> Tahun</td>
          </tr>
        </table>
      </td>
      <td style="width: 52%;">
        <table style="width: 100%;">
          <tr>
            <td class="col-label">Alamat</td>
            <td class="col-separator">:</td>
            <td><?= htmlspecialchars($pasien->alamat) ?></td>
          </tr>
          <tr>
            <td class="col-label">Agama</td>
            <td class="col-separator">:</td>
            <td><?= isset($pasien->agama) ? htmlspecialchars($pasien->agama) : '-' ?></td>
          </tr>
          <tr>
            <td class="col-label">Pendidikan</td>
            <td class="col-separator">:</td>
            <td><?= isset($pasien->pendidikan) ? htmlspecialchars($pasien->pendidikan) : '-' ?></td>
          </tr>
          <tr>
            <td class="col-label">Pekerjaan</td>
            <td class="col-separator">:</td>
            <td><?= isset($pasien->pekerjaan) ? htmlspecialchars($pasien->pekerjaan) : '-' ?></td>
          </tr>
          <tr>
            <td class="col-label">Petugas Pendaftar</td>
            <td class="col-separator">:</td>
            <td><?= isset($pasien->petugas_pendaftaran) && $pasien->petugas_pendaftaran != '' ? htmlspecialchars($pasien->petugas_pendaftaran) : '-' ?></td>
          </tr>
        </table>
      </td>
    </tr>
  </table>

  <table class="soap-table">
    <thead>
      <tr>
        <th style="width: 12%;">TANGGAL</th>
        <th style="width: 58%;">ANAMNESIS DAN PEMERIKSAAN</th>
        <th style="width: 20%;">KODE DIAGNOSIS</th>
        <th style="width: 10%;">PARAF</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td style="text-align: center;">
          <?= date('d/m/Y', strtotime($kunjungan->tanggal_kunjungan)) ?><br>
          <?= date('H:i', strtotime($kunjungan->tanggal_kunjungan)) ?> WIB
        </td>
        <td>
          <div class="section-title">S - Subjective:</div>
          <div style="padding-left:15px;">
            <div class="detail-row">1. Keluhan Utama:
              <?= !empty($kunjungan->keluhan_utama) ? nl2br(htmlspecialchars($kunjungan->keluhan_utama)) : '-' ?>
            </div>
            <br>
            <div class="detail-row">2. Riwayat Penyakit Sekarang:
              <?= !empty($kunjungan->riwayat_penyakit) ? nl2br(htmlspecialchars($kunjungan->riwayat_penyakit)) : '-' ?>
            </div>
          </div>
          <br>

          <div class="section-title">O - Objective:</div>
          <div style="padding-left:15px;">
            <div class="detail-row">1. GCS :
              <?= !empty($kunjungan->gcs) ? htmlspecialchars($kunjungan->gcs) : '-' ?>
            </div>
            <br>
            <div class="detail-row">2. Tekanan Sistole / Diastole :
              <?= !empty($kunjungan->tensi_sistole) ? htmlspecialchars($kunjungan->tensi_sistole . '/' . $kunjungan->tensi_diastole) : '-' ?>
              mmHg
            </div>
            <br>
            <div class="detail-row">3. Nadi : <?= !empty($kunjungan->nadi) ? htmlspecialchars($kunjungan->nadi) : '-' ?>
              x/menit </div>
            <br>
            <div class="detail-row">4. Suhu : <?= !empty($kunjungan->suhu) ? htmlspecialchars($kunjungan->suhu) : '-' ?>
              °C </div>
            <br>
            <div class="detail-row">5. Pernapasan :
              <?= !empty($kunjungan->pernapasan) ? htmlspecialchars($kunjungan->pernapasan) : '-' ?> x/menit
            </div>
            <br>
            <div class="detail-row">6. Berat Badan :
              <?= !empty($kunjungan->berat_badan) ? htmlspecialchars($kunjungan->berat_badan) : '-' ?> kg
            </div>
            <br>
            <div class="detail-row">7. Tinggi Badan :
              <?= !empty($kunjungan->tinggi_badan) ? htmlspecialchars($kunjungan->tinggi_badan) : '-' ?> cm
            </div>
            <br>
            <div class="detail-row">8. Jantung :
              <?= !empty($kunjungan->pemeriksaan_jantung) ? nl2br(htmlspecialchars($kunjungan->pemeriksaan_jantung)) : '-' ?>
            </div>
            <br>
            <div class="detail-row">9. Paru-Paru :
              <?= !empty($kunjungan->pemeriksaan_paru) ? nl2br(htmlspecialchars($kunjungan->pemeriksaan_paru)) : '-' ?>
            </div>
            <br>
            <div class="detail-row">10. Abdomen :
              <?= !empty($kunjungan->pemeriksaan_abdomen) ? nl2br(htmlspecialchars($kunjungan->pemeriksaan_abdomen)) : '-' ?>
            </div>
            <br>
            <div class="detail-row">11. Catatan Fisik Tambahan :
              <div class="detail-row" style="padding-left:15px;">
                <?= !empty($kunjungan->catatan_tambahan) ? nl2br(htmlspecialchars($kunjungan->catatan_tambahan)) : '-' ?>
              </div>
            </div>
          </div>
          <br>

          <div class="section-title">A - Assessment : </div>
          <div class="detail-row" style="padding-left:15px;">
            <?= !empty($kunjungan->asesmen_diagnosa) ? nl2br(htmlspecialchars($kunjungan->asesmen_diagnosa)) : '-' ?>
          </div>
          <br>

          <div class="section-title">P - Plan : </div>
          <div class="detail-row" style="padding-left:15px;">
            <?= !empty($kunjungan->plan_rencana) ? nl2br(htmlspecialchars($kunjungan->plan_rencana)) : '-' ?>
          </div>
        </td>
        <td>
          <?php
          if ($kunjungan->icd_10 && $kunjungan->icd_10 !== '<i>Kosong</i>') {
            echo str_replace('<br>', '<br><br><br>', $kunjungan->icd_10);
          }
          ?>
        </td>
        <td style="text-align: center; vertical-align: bottom; padding-bottom: 20px;">
          <br><br><br>
          <?php if (!empty($kunjungan->nama_dokter_pemeriksa)): ?>
            <strong><?= htmlspecialchars($kunjungan->nama_dokter_pemeriksa) ?></strong>
          <?php else: ?>
            <span style="color:#999;">(Tanda Tangan)</span>
          <?php endif; ?>
        </td>
      </tr>
    </tbody>
  </table>

  <script>
    window.onload = function() {
        window.print();
    }
  </script>
</body>

</html>
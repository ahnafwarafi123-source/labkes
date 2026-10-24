<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php
/**
 * Print Pendaftaran View
 * 
 * @var string $title - Page title
 * @var object $pendaftaran - Pendaftaran data as object
 * @var object $pemeriksaan - Pemeriksaan items
 */
?>
<!doctype html>
<html lang="id">

<head>
  <meta charset="utf-8">
  <title>Formulir Permintaan Pemeriksaan Lab Kesmas</title>
  <style>
    @page {
      size: 215mm 330mm;
      margin: 8mm;
    }

    * {
      box-sizing: border-box;
    }

    body {
      font-family: Arial, Helvetica, sans-serif;
      color: #000;
      font-size: 10pt;
      line-height: 1.1;
      -webkit-print-color-adjust: exact;
      print-color-adjust: exact;
    }

    .center {
      text-align: center;
    }

    .right {
      text-align: right;
    }

    .bold {
      font-weight: 700;
    }

    .upper {
      text-transform: uppercase;
    }

    .muted {
      color: #222;
    }

    /* spacing helper (lebih konsisten) */
    .mt2 {
      margin-top: 2px;
    }

    .mt4 {
      margin-top: 3px;
    }

    .mt6 {
      margin-top: 4px;
    }

    .mt8 {
      margin-top: 5px;
    }

    .mb2 {
      margin-bottom: 2px;
    }

    .mb4 {
      margin-bottom: 4px;
    }

    /* watermark */
    body::before {
      content: "";
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-image: url("<?= base_url('assets/img/background.png') ?>");
      background-repeat: no-repeat;
      background-position: center;
      background-size: 88%;
      opacity: 0.14;
      z-index: -1;
    }

    /* KOP */
    .kop {
      display: flex;
      align-items: flex-start;
      gap: 8px;
      margin-bottom: 2px;
    }

    .logo {
      width: 40px;
      height: 40px;
      object-fit: contain;
      margin-left: 8mm;
      flex: 0 0 auto;
    }

    .logo-kanan {
      width: 40px;
      height: 40px;
      object-fit: contain;
      margin-right: 8mm;
      flex: 0 0 auto;
    }

    .kop-text {
      flex: 1;
    }

    .kop-text .l1,
    .kop-text .l2,
    .kop-text .l3 {
      font-size: 11pt;
      font-weight: 700;
      letter-spacing: .2px;
    }

    .kop-text .addr {
      font-size: 9pt;
      margin-top: 1px;
      line-height: 1.1;
    }

    .hr {
      border-top: 1px solid #000;
      margin: 2px 0 3px;
    }

    /* TABLE / BOX */
    table {
      border-collapse: collapse;
      width: 100%;
    }

    .box {
      border: 1px solid #000;
      table-layout: fixed;
    }

    .box td,
    .box th {
      border: 1px solid #000;
      padding: 2px 3px;
      vertical-align: top;
    }

    .box th {
      background: #fff;
      font-weight: 700;
    }

    .box-title {
      border: 1px solid #000;
      padding: 2px 4px;
      font-weight: 700;
      text-align: center;
      margin-top: 2px;
      letter-spacing: .4px;
    }

    /* MINI TABLE (label : value) */
    .mini {
      width: 100%;
      table-layout: fixed;
    }

    .mini td {
      border: 0;
      padding: 1px 0;
      vertical-align: top;
    }

    .mini .lbl {
      width: 48%;
    }

    .mini .sep {
      width: 4%;
      text-align: center;
    }

    .mini .val {
      width: 48%;
      word-wrap: break-word;
    }

    /* checkbox */
    .chk {
      display: inline-block;
      width: 11px;
      height: 11px;
      border: 1px solid #000;
      vertical-align: middle;
      margin-right: 4px;
      position: relative;
      top: -0.5px;
    }

    .chk.checked::after {
      content: "✓";
      position: absolute;
      left: 1px;
      top: -2px;
      font-size: 12px;
      font-weight: 700;
      line-height: 1;
    }

    .opt {
      margin-right: 12px;
      white-space: nowrap;
    }

    /* DAFTAR JENIS PEMERIKSAAN */
    .dj-header {
      border: 1px solid #000;
      border-bottom: 0;
      padding: 2px 4px;
      font-weight: 700;
      text-align: center;
      margin-top: 4px;
      letter-spacing: .4px;
    }

    .dj-wrap {
      width: 100%;
      border: 1px solid #000;
      border-top: 0;
      display: flex;
    }

    .dj-col {
      width: 50%;
      border-right: 1px solid #000;
    }

    .dj-col:last-child {
      border-right: 0;
    }

    .dj-tbl {
      width: 100%;
      border-collapse: collapse;
    }

    .dj-tbl th,
    .dj-tbl td {
      border-bottom: 1px solid #000;
      padding: 1px 2px;
      vertical-align: middle;
      line-height: 1.1;
    }

    .dj-tbl td.chk-box {
      width: 5%;
      text-align: center;
      border-right: 1px solid #000;
      font-size: 10pt;
      font-weight: bold;
    }

    .dj-tbl td.item-name {
      border-right: 1px solid #000;
    }

    .dj-tbl th.hasil-col,
    .dj-tbl td.hasil-col {
      width: 16%;
      border-right: 1px solid #000;
      text-align: center;
    }

    .dj-tbl th.paraf-col,
    .dj-tbl td.paraf-col {
      width: 16%;
      text-align: center;
    }

    .dj-tbl th.head-title {
      border-right: 1px solid #000;
      text-align: center;
      font-weight: 700;
      padding: 2px 0;
    }

    .dj-tbl .sub-row td {
      font-weight: 700;
    }

    .lain {
      border: 1px solid #000;
      height: 14px;
      padding: 2px 3px;
    }

    /* Kaji ulang */
    .kaji-title {
      border: 1px solid #000;
      padding: 2px 4px;
      font-weight: 700;
      text-align: center;
      margin-top: 4px;
      letter-spacing: .4px;
    }

    .kaji2 {
      width: 100%;
      border-collapse: collapse;
      border: 1px solid #000;
      border-top: 0;
      table-layout: fixed;
    }

    .kaji2 td {
      border: 1px solid #000;
      vertical-align: top;
      padding: 0;
    }

    .kaji-inner {
      width: 100%;
      border-collapse: collapse;
      table-layout: fixed;
    }

    .kaji-inner td {
      border: 0;
      padding: 2px 4px;
      vertical-align: top;
    }

    .kaji-kelayakan .row,
    .kaji-alasan .row {
      margin: 1px 0;
    }

    .kaji-line {
      display: inline-block;
      border-bottom: 1px solid #000;
      width: 170px;
      height: 10px;
      vertical-align: baseline;
    }

    .kaji-line.sm {
      width: 95px;
    }

    /* Pernyataan */
    .pernyataan-wrap {
      margin-top: 4px;
      font-size: 9pt;
      line-height: 1.25;
    }

    .pernyataan-row {
      display: flex;
      gap: 10px;
      align-items: flex-start;
    }

    .pernyataan-left {
      flex: 1;
    }

    .pernyataan-right {
      width: 130px;
      text-align: left;
    }

    .pernyataan-right .tgl-line {
      display: inline-block;
      border-bottom: 1px solid #000;
      width: 95px;
      height: 10px;
      vertical-align: baseline;
    }

    /* TTD */
    .sign-row {
      margin-top: 4px;
      display: flex;
      gap: 10px;
      align-items: flex-start;
    }

    .sign-pelanggan {
      width: 28%;
      text-align: center;
    }

    .sign-pelanggan .line {
      border-bottom: 1px solid #000;
      height: 16px;
      margin-top: 14px;
    }

    .sign-petugas {
      width: 72%;
    }

    .sign-petugas table {
      width: 100%;
      border-collapse: collapse;
      table-layout: fixed;
    }

    .sign-petugas td {
      border: 0;
      padding: 0 4px;
      text-align: center;
      font-size: 10pt;
      vertical-align: top;
    }

    .sign-petugas .line {
      border-bottom: 1px solid #000;
      height: 16px;
      margin-top: 14px;
    }

    .sign-cap {
      font-size: 9pt;
      margin-top: 2px;
      line-height: 1.15;
    }

    /* Kartu kendali */
    .kk-title {
      border: 1px solid #000;
      padding: 2px 4px;
      font-weight: 700;
      text-align: center;
      margin-top: 4px;
      letter-spacing: .4px;
    }

    .kk {
      border: 1px solid #000;
      border-top: 0;
      table-layout: fixed;
    }

    .kk th,
    .kk td {
      border: 1px solid #000;
      padding: 2px 1px;
      text-align: center;
      vertical-align: middle;
      line-height: 1.15;
    }

    .kk th {
      font-weight: 700;
    }

    /* Catatan */
    .catatan {
      border: 1px solid #000;
      min-height: 18px;
      padding: 2px 3px;
      margin-top: 2px;
      line-height: 1.2;
    }

    /* rapatkan overall */
    .tight {
      margin-top: 2px;
    }
  </style>
</head>

<body onload="window.print()">

  <?php
  // helper tanggal indo
  if (!function_exists('format_indo')) {
    function format_indo($datetime_string, $format = 'tanggal')
    {
      if (empty($datetime_string) || !strtotime($datetime_string)) return '';
      $ts = strtotime($datetime_string);
      $bulan_indo = [
        1 => 'Januari',
        'Februari',
        'Maret',
        'April',
        'Mei',
        'Juni',
        'Juli',
        'Agustus',
        'September',
        'Oktober',
        'November',
        'Desember'
      ];
      $hari  = date('d', $ts);
      $bulan = $bulan_indo[(int)date('m', $ts)];
      $tahun = date('Y', $ts);

      if ($format === 'tanggal_jam') {
        $jam = date('H.i', $ts);
        return "$hari $bulan $tahun  /  $jam WIB";
      }
      return "$hari $bulan $tahun";
    }
  }

  $p = $pendaftaran ?? (object)[];

  // util checkbox
  if (!function_exists('chk')) {
    function chk($isChecked)
    {
      return $isChecked ? 'chk checked' : 'chk';
    }
  }

  // data
  $no_reg   = $p->no_registrasi ?? '';
  $nama     = $p->nama_sampel ?? '';
  $jenis    = $p->jenis_sampel ?? '';
  $volume   = $p->volume_ml ?? '';
  $tgljam   = (!empty($p->tgl_pengambilan) && !empty($p->jam_pengambilan))
    ? format_indo($p->tgl_pengambilan . ' ' . $p->jam_pengambilan, 'tanggal_jam') : '';
  $lokasi   = $p->lokasi_pengambilan ?? '';
  $petugas_ambil = $p->petugas_pengambil ?? ($p->petugas_pengambil_sampel ?? '');
  $info_tambahan = $p->info_tambahan ?? '';

  $pengirim_nik = $p->nik ?? '';
  $pengirim_nama = $p->nama ?? '';
  $pengirim_alamat = $p->alamat ?? '';
  $pengirim_kecamatan = htmlspecialchars($p->kecamatan ?? '');
  $pengirim_kelurahan = htmlspecialchars($p->kelurahan ?? '');
  $pengirim_objek = htmlspecialchars($p->objek ?? '');
  $pengirim_telp = $p->telp ?? '';
  $pengirim_instansi = $p->instansi ?? '';
  $tgl_permintaan = !empty($p->tgl_permintaan) ? format_indo($p->tgl_permintaan) : '';
  $ttd_pengirim = $p->ttd ?? '';

  $tindakan = $p->tindakan_sampel ?? '';
  $kategori = $p->kategori_sampel ?? '';

  $status_kelayakan = $p->status_kelayakan ?? '';
  $catatan_db = trim($p->catatan ?? '');
  $alasan_tidak_layak = [];
  $catatan_kaji = '';
  if (!empty($catatan_db)) {
    $parts = array_map('trim', explode(',', $catatan_db));
    $valid_reasons = ['Tidak Steril', 'Volume Tidak Mencukupi', 'Bahan Tidak Sesuai Permintaan', 'Waktu Tunggu Tidak Sesuai'];

    $is_reasons = false;
    foreach ($parts as $pt) {
      if (in_array($pt, $valid_reasons)) {
        $is_reasons = true;
        break;
      }
    }

    if ($is_reasons) {
      $alasan_tidak_layak = $parts;
    } else {
      $catatan_kaji = $catatan_db;
    }
  }
  // Data kaji ulang
  $tgl_jam_pengambilan_kaji = (!empty($p->tgl_pengambilan) && !empty($p->jam_pengambilan)) ? $p->tgl_pengambilan . ' ' . $p->jam_pengambilan : '';
  $jumlah_biaya = $p->jumlah_biaya ?? 0;
  $cara_bayar = $p->cara_bayar ?? '';
  $cara_bayar_lainnya = $p->keterangan_bayar ?? '';
  $petugas_pendaftaran = $p->petugas_pendaftaran_name ?? '-';
  $petugas_pengambil_ttd = $p->petugas_pengambil_ttd_name ?? '-';
  $petugas_verifikasi = $p->petugas_verifikasi_name ?? '-';
  $petugas_validasi = $p->petugas_validasi_name ?? '-';

  // Kartu kendali
  $kk_pengambilan = $p->kk_tgl_pengambilan ?? '';
  $kk_sampel_diterima_lab = $p->tgl_diterima_lab ?? '';
  $kk_pengerjaan_sampel = $p->tgl_pengerjaan ?? '';
  $kk_input_hasil = $p->tgl_input_hasil ?? '';
  $kk_cetak_hasil = $p->tgl_cetak ?? '';
  ?>

  <!-- KOP -->
  <div class="kop">
    <img class="logo" src="<?= base_url('assets/img/logo.png?v=' . time()) ?>" alt="logo" onerror="this.style.display='none';">
    <div class="kop-text center">
      <div class="l1 upper">PEMERINTAH KOTA PANGKAL PINANG</div>
      <div class="l2 upper">DINAS KESEHATAN</div>
      <div class="l3 upper">UPTD. LABORATORIUM KESEHATAN</div>
      <div class="addr muted">
        Jalan Delima Siam II Kecamatan Girimaya, Telepon (0717) 9120759<br>
        Pos el : upt-labkesehatan@pangkalpinangkota.go.id Pangkal Pinang 33143
      </div>
    </div>
    <img class="logo-kanan" src="<?= base_url('assets/img/logokanan.jpeg?v=' . time()) ?>" alt="logo kanan" onerror="this.style.display='none';">
  </div>

  <div class="hr"></div>

  <div class="box-title upper">FORMULIR PERMINTAAN PEMERIKSAAN LABORATORIUM KESMAS</div>

  <!-- 3 KOTAK ATAS -->
  <table class="box tight">
    <tr>
      <td style="width:34%;">
        <div class="bold mb2">Identitas Sampel</div>
        <table class="mini">
          <tr>
            <td class="lbl">No. Registrasi</td>
            <td class="sep">:</td>
            <td class="val"><?= $no_reg ?></td>
          </tr>
          <tr>
            <td class="lbl">Nama Sampel</td>
            <td class="sep">:</td>
            <td class="val"><?= $nama ?></td>
          </tr>
          <tr>
            <td class="lbl">Jenis Sampel</td>
            <td class="sep">:</td>
            <td class="val"><?= $jenis ?></td>
          </tr>
          <tr>
            <td class="lbl">Volume Sampel</td>
            <td class="sep">:</td>
            <td class="val"><?= $volume ?></td>
          </tr>
          <tr>
            <td class="lbl">Tgl / Jam Pengambilan</td>
            <td class="sep">:</td>
            <td class="val"><?= $tgljam ?></td>
          </tr>
          <tr>
            <td class="lbl">Lokasi Pengambilan</td>
            <td class="sep">:</td>
            <td class="val"><?= $lokasi ?></td>
          </tr>
        </table>
      </td>

      <td style="width:33%;">
        <div class="bold mb2">Identitas Sampel</div>
        <div style="margin-top: 4px; margin-bottom: 16px;">Lokasi Pengambilan Sampel : <?= $lokasi ?></div>
        <div style="margin-bottom: 16px;">Petugas Pengambil Sampel : <?= $petugas_ambil ?></div>
        <div>Informasi Tambahan : <?= $info_tambahan ?></div>
      </td>

      <td style="width:33%;">
        <div class="bold mb2">Identitas Pengirim</div>
        <table class="mini">
          <tr>
            <td class="lbl">NIK</td>
            <td class="sep">:</td>
            <td class="val"><?= $pengirim_nik ?></td>
          </tr>
          <tr>
            <td class="lbl">Nama</td>
            <td class="sep">:</td>
            <td class="val"><?= $pengirim_nama ?></td>
          </tr>
          <tr>
            <td class="lbl">Alamat</td>
            <td class="sep">:</td>
            <td class="val"><?= $pengirim_alamat ?></td>
          <tr>
            <td class="lbl">Kecamatan</td>
            <td class="sep">:</td>
            <td class="val"><?= $pengirim_kecamatan ?></td>
          </tr>
          <tr>
            <td class="lbl">Kelurahan</td>
            <td class="sep">:</td>
            <td class="val"><?= $pengirim_kelurahan ?></td>
          </tr>
          <tr>
            <td class="lbl">Objek</td>
            <td class="sep">:</td>
            <td class="val"><?= $pengirim_objek ?></td>
          </tr>
          </tr>
          <tr>
            <td class="lbl">No. Telp</td>
            <td class="sep">:</td>
            <td class="val"><?= $pengirim_telp ?></td>
          </tr>
          <tr>
            <td class="lbl">Instansi</td>
            <td class="sep">:</td>
            <td class="val"><?= $pengirim_instansi ?></td>
          </tr>
          <tr>
            <td class="lbl">Tanggal Permintaan</td>
            <td class="sep">:</td>
            <td class="val"><?= $tgl_permintaan ?></td>
          </tr>
          <tr>
            <td class="lbl">Tanda Tangan Pengirim</td>
            <td class="sep">:</td>
            <td class="val"><?= $pengirim_nama ?></td>
          </tr>
        </table>
      </td>
    </tr>
  </table>

  <!-- TINDAKAN + KATEGORI -->
  <table class="box tight" style="border-top:0;">
    <tr>
      <td style="width:50%;">
        <span class="bold">Tindakan Sampel</span>&nbsp;&nbsp;
        <span class="opt"><span class="<?= chk(strtolower($tindakan) == 'langsung') ?>"></span>Langsung</span>
        <span class="opt"><span class="<?= chk(strtolower($tindakan) == 'kiriman') ?>"></span>Kiriman</span>
        <span class="opt"><span class="<?= chk(strtolower($tindakan) == 'rujuk' || strtolower($tindakan) == 'rujukan') ?>"></span>Rujuk</span>
      </td>
      <td style="width:50%;">
        <span class="bold">Kategori Sampel</span>&nbsp;&nbsp;
        <span class="opt"><span class="<?= chk(strtolower($kategori) == 'air') ?>"></span>Air</span>
        <span class="opt"><span class="<?= chk(strtolower($kategori) == 'makanan') ?>"></span>Makanan</span>
        <span class="opt"><span class="<?= chk(strtolower($kategori) == 'lingkungan') ?>"></span>Lingkungan</span>
      </td>
    </tr>
  </table>

  <!-- DAFTAR JENIS PEMERIKSAAN -->
  <?php
  if (!function_exists('get_matched_item_by_id')) {
    function get_matched_item_by_id($item_id, $pemeriksaan)
    {
      if (empty($pemeriksaan)) return null;
      foreach ($pemeriksaan as $p) {
        if (isset($p->id_master_pemeriksaan) && $p->id_master_pemeriksaan == $item_id) return $p;
      }
      return null;
    }
  }

  $col1 = [];
  if (!empty($master_air_minum)) {
    $col1[] = ['type' => 'header', 'title' => 'I. AIR MINUM', 'hasil' => 'HASIL', 'paraf' => 'PARAF'];
    foreach ($master_air_minum as $kelompok => $items) {
      $col1[] = ['type' => 'sub', 'title' => strtoupper($kelompok)];
      foreach ($items as $item) {
        $col1[] = ['name' => $item['nama_pemeriksaan'], 'id_master_pemeriksaan' => $item['id_master_pemeriksaan']];
      }
    }
  }

  $col2 = [];
  if (!empty($master_air_bersih)) {
    $col2[] = ['type' => 'header', 'title' => 'II. AIR BERSIH', 'hasil' => 'HASIL', 'paraf' => 'PARAF'];
    foreach ($master_air_bersih as $kelompok => $items) {
      $col2[] = ['type' => 'sub', 'title' => strtoupper($kelompok)];
      foreach ($items as $item) {
        $col2[] = ['name' => $item['nama_pemeriksaan'], 'id_master_pemeriksaan' => $item['id_master_pemeriksaan']];
      }
    }
  }

  if (!empty($master_makanan)) {
    $col2[] = ['type' => 'header', 'title' => 'III. MAKANAN', 'hasil' => 'HASIL', 'paraf' => 'PARAF'];
    foreach ($master_makanan as $kelompok => $items) {
      if (!empty($kelompok) && $kelompok !== '-') {
        $col2[] = ['type' => 'sub', 'title' => strtoupper($kelompok)];
      }
      foreach ($items as $item) {
        $col2[] = ['name' => $item['nama_pemeriksaan'], 'id_master_pemeriksaan' => $item['id_master_pemeriksaan']];
      }
    }
  }

  if (!empty($master_lingkungan)) {
    $col2[] = ['type' => 'header', 'title' => 'IV. LINGKUNGAN', 'hasil' => '', 'paraf' => ''];
    foreach ($master_lingkungan as $kelompok => $items) {
      if (!empty($kelompok) && $kelompok !== '-') {
        $col2[] = ['type' => 'sub', 'title' => strtoupper($kelompok)];
      }
      foreach ($items as $item) {
        $col2[] = ['name' => $item['nama_pemeriksaan'], 'id_master_pemeriksaan' => $item['id_master_pemeriksaan']];
      }
    }
  }

  if (!function_exists('normalize_kelompok_form')) {
    function normalize_kelompok_form($kelompok)
    {
      $map = [
        'Fisika'       => 'FISIK',
        'FISIKA'       => 'FISIK',
        'Bakteriologi' => 'MIKROBIOLOGI',
        'BAKTERIOLOGI' => 'MIKROBIOLOGI',
      ];
      return $map[$kelompok] ?? strtoupper($kelompok);
    }
  }

  if (!function_exists('render_dj_table')) {
    function render_dj_table($col_data, $pemeriksaan)
    {
      echo '<table class="dj-tbl">';
      foreach ($col_data as $row) {
        if (isset($row['type'])) {
          if ($row['type'] === 'header') {
            echo '<tr class="head-row">';
            echo '<th colspan="2" class="head-title">' . $row['title'] . '</th>';
            echo '<th class="hasil-col">' . $row['hasil'] . '</th>';
            echo '<th class="paraf-col">' . $row['paraf'] . '</th>';
            echo '</tr>';
          } elseif ($row['type'] === 'sub') {
            echo '<tr class="sub-row">';
            echo '<td colspan="2" class="item-name" style="padding-left: 4px;">' . normalize_kelompok_form($row['title']) . '</td>';
            echo '<td class="hasil-col"></td>';
            echo '<td class="paraf-col"></td>';
            echo '</tr>';
          }
        } else {
          $matched = get_matched_item_by_id($row['id_master_pemeriksaan'], $pemeriksaan);
          $chk = $matched ? '✓' : '';
          echo '<tr class="item-row">';
          echo '<td class="chk-box">' . $chk . '</td>';
          echo '<td class="item-name">' . $row['name'] . '</td>';
          echo '<td class="hasil-col"></td>';
          echo '<td class="paraf-col"></td>';
          echo '</tr>';
        }
      }
      echo '</table>';
    }
  }
  ?>

  <div class="dj-header upper">DAFTAR JENIS PEMERIKSAAN</div>
  <div class="dj-wrap">
    <div class="dj-col">
      <?php render_dj_table($col1, $pemeriksaan); ?>
    </div>
    <div class="dj-col">
      <?php render_dj_table($col2, $pemeriksaan); ?>
    </div>
  </div>

  <!-- LAIN-LAIN -->
  <div class="center bold upper mt6">LAIN - LAIN</div>
  <div class="lain"></div>

  <!-- KAJI ULANG -->
  <div class="kaji-title upper">KAJI ULANG PERMINTAAN PEMERIKSAAN</div>

  <table class="kaji2">
    <tr>
      <!-- KIRI -->
      <td style="width:50%;">
        <table class="kaji-inner">
          <tr>
            <td style="width:32%;" class="kaji-kelayakan">
              <div class="bold">Kelayakan Sampel</div>
              <div class="row"><span class="<?= chk(strtolower($status_kelayakan) == 'layak') ?>"></span>Layak</div>
              <div class="row"><span class="<?= chk(strtolower($status_kelayakan) == 'tidak layak' || strtolower($status_kelayakan) == 'tdk layak') ?>"></span>Tidak Layak</div>
            </td>

            <td style="width:68%;" class="kaji-alasan">
              <div class="bold">Alasan</div>
              <div class="row"><span class="<?= chk(in_array('Tidak Steril', $alasan_tidak_layak ?? [])) ?>"></span>Tidak Steril</div>
              <div class="row"><span class="<?= chk(in_array('Volume Tidak Mencukupi', $alasan_tidak_layak ?? [])) ?>"></span>Volume Tidak Mencukupi</div>
              <div class="row"><span class="<?= chk(in_array('Bahan Tidak Sesuai Permintaan', $alasan_tidak_layak ?? [])) ?>"></span>Bahan Tidak Sesuai Permintaan</div>
              <div class="row"><span class="<?= chk(in_array('Waktu Tunggu Tidak Sesuai', $alasan_tidak_layak ?? [])) ?>"></span>Waktu Tunggu Tidak Sesuai</div>
              <?php if (!empty($catatan_kaji) && (strtolower($status_kelayakan) === 'tidak layak' || strtolower($status_kelayakan) === 'tdk layak')): ?>
                <div class="bold" style="margin-top: 4px;">Solusi / Catatan Penolakan:</div>
                <div class="row" style="font-weight: normal; font-style: italic; margin-left: 2px;"><?= nl2br(htmlspecialchars($catatan_kaji)) ?></div>
              <?php endif; ?>
            </td>
          </tr>

          <tr>
            <td colspan="2">
              Waktu Pengambilan Sampel
              <span class="kaji-line"><?= !empty($p->tgl_pengambilan) ? format_indo($p->tgl_pengambilan) : '' ?></span>
              &nbsp;/ Jam&nbsp;
              <span class="kaji-line sm"><?= $p->jam_pengambilan ?? '' ?></span>
            </td>
          </tr>

          <tr>
            <td colspan="2">
              <span class="<?= chk(!empty($p->petugas_pengambil_ttd_id)) ?>"></span>Di isi Petugas Penerima Sampel
            </td>
          </tr>
        </table>
      </td>

      <!-- KANAN -->
      <td style="width:50%;">
        <table class="kaji-inner">
          <tr>
            <td style="width:45%;" class="bold">Jumlah Biaya</td>
            <td style="width:55%;">Rp <span class="kaji-line"><?= !empty($jumlah_biaya) ? number_format($jumlah_biaya, 0, ',', '.') : '' ?></span></td>
          </tr>

          <tr>
            <td colspan="2" style="padding-top:6px;">
              <span class="<?= chk(strtolower($cara_bayar) == 'tunai') ?>"></span>Cash / Tunai
              &nbsp;&nbsp;&nbsp;
              <span class="<?= chk(strtolower($cara_bayar) == 'lain-lain' || strtolower($cara_bayar) == 'lainnya') ?>"></span>Lain - lain
            </td>
          </tr>

          <tr>
            <td colspan="2" style="padding-top:10px;">
              <span class="<?= chk(!empty($p->petugas_pendaftaran_id)) ?>"></span>Di isi Petugas Pendaftaran
            </td>
          </tr>
        </table>
      </td>
    </tr>
  </table>

  <!-- PERNYATAAN + TGL -->
  <div class="pernyataan-wrap">
    <div class="pernyataan-row">
      <div class="pernyataan-left">
        Dengan ini saya menyatakan nama dan informasi yang saya isi benar dan saya memahami serta menyetujui
        biaya dan tindakan pemeriksaan yang dilakukan oleh UPTD Labkes Kota Pangkal Pinang untuk pemeriksaan sampel.
      </div>
      <div class="pernyataan-right">
        Tgl <span class="tgl-line"></span>
      </div>
    </div>
  </div>

  <!-- TTD -->
  <div class="sign-row">
    <div class="sign-pelanggan">
      <div class="line"></div>
      <div class="sign-cap"><?= !empty($pengirim_nama) ? htmlspecialchars($pengirim_nama) : '*Tanda Tangan Pelanggan' ?></div>
    </div>

    <div class="sign-petugas">
      <table>
        <tr>
          <td>Petugas Pendaftaran</td>
          <td>Petugas Pengambil Sampel</td>
          <td>Petugas Verifikasi</td>
          <td>Petugas Validasi</td>
        </tr>
        <tr>
          <td>
            <div class="line"></div>
            <div class="sign-cap"><?= $petugas_pendaftaran ?></div>
          </td>
          <td>
            <div class="line"></div>
            <div class="sign-cap"><?= $petugas_pengambil_ttd ?></div>
          </td>
          <td>
            <div class="line"></div>
            <div class="sign-cap"><?= $petugas_verifikasi ?></div>
          </td>
          <td>
            <div class="line"></div>
            <div class="sign-cap"><?= $petugas_validasi ?></div>
          </td>
        </tr>
      </table>
    </div>
  </div>

  <!-- KARTU KENDALI WAKTU -->
  <div class="kk-title upper">KARTU KENDALI WAKTU PEMERIKSAAN LABORATORIUM</div>
  <table class="kk">
    <tr>
      <th style="width:20%;">Pengambilan Sampel</th>
      <th style="width:7%;">Paraf</th>
      <th style="width:22%;">Sampel Diterima Laboratorium</th>
      <th style="width:7%;">Paraf</th>
      <th style="width:18%;">Pengerjaan Sampel</th>
      <th style="width:7%;">Paraf</th>
      <th style="width:12%;">Input Hasil Pemeriksaan</th>
      <th style="width:7%;">Paraf</th>
      <th style="width:12%;">Cetak Lembar Hasil Uji</th>
      <th style="width:7%;">Paraf</th>
    </tr>
    <tr>
      <td style="height:14px;"><?= !empty($kk_pengambilan) ? format_indo($kk_pengambilan, 'tanggal_jam') : '' ?></td>
      <td></td>
      <td><?= !empty($kk_sampel_diterima_lab) ? format_indo($kk_sampel_diterima_lab, 'tanggal_jam') : '' ?></td>
      <td></td>
      <td><?= !empty($kk_pengerjaan_sampel) ? format_indo($kk_pengerjaan_sampel, 'tanggal_jam') : '' ?></td>
      <td></td>
      <td><?= !empty($kk_input_hasil) ? format_indo($kk_input_hasil, 'tanggal_jam') : '' ?></td>
      <td></td>
      <td><?= !empty($kk_cetak_hasil) ? format_indo($kk_cetak_hasil, 'tanggal_jam') : '' ?></td>
      <td></td>
    </tr>
  </table>

  <!-- CATATAN -->
  <div class="bold mt6">CATATAN</div>
  <div class="catatan"><?= !empty($catatan_kaji) ? htmlspecialchars($catatan_kaji) : '' ?></div>

</body>

</html>

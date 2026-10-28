<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php
/**
 * Cetak kwitansi: Daftar Pemeriksaan (tarif) + Bukti Pembayaran.
 * Dipakai untuk Print (browser) dan PDF (mPDF) — hanya memakai tabel, tanpa flex/grid.
 * @var array $cfg, $kwitansi, $tarif, $ringkasan; @var bool $is_pdf
 */
$is_pdf = !empty($is_pdf);

$img = function ($file) use ($is_pdf) {
    if ($is_pdf) return is_file(FCPATH . 'assets/img/' . $file) ? FCPATH . 'assets/img/' . $file : '';
    return base_url('assets/img/' . $file);
};
$angka = function ($v) { return number_format((float)$v, 0, ',', '.'); };
$rupiah = function ($v) { return (float)$v > 0 ? 'Rp. ' . number_format((float)$v, 2, ',', '.') : 'Rp.'; };
$romawi = function ($n) {
    $map = [10 => 'X', 9 => 'IX', 5 => 'V', 4 => 'IV', 1 => 'I'];
    $r = '';
    foreach ($map as $v => $s) { while ($n >= $v) { $r .= $s; $n -= $v; } }
    return $r;
};
$bulan = [1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
$ts = strtotime($kwitansi['tanggal'] ?: date('Y-m-d'));
$tanggal_indo = date('j', $ts) . ' ' . $bulan[(int)date('n', $ts)] . ' ' . date('Y', $ts);

// Hanya item yang dipilih (snapshot kwitansi_item); keterangan diambil dari tarif saat ini.
$ket_tarif = [];
foreach ($tarif as $items) {
    foreach ($items as $t) $ket_tarif[(int)$t['id_tarif']] = $t['keterangan'];
}
$per_kelompok = [];
foreach ($kwitansi['items'] as $it) {
    $per_kelompok[$it['kelompok']][] = $it;
}
$baris = [];
$gi = 0;
foreach ($per_kelompok as $kelompok => $items) {
    $gi++;
    $baris[] = ['type' => 'group', 'no' => $romawi($gi) . '.', 'nama' => strtoupper($kelompok)];
    foreach ($items as $ii => $it) {
        $baris[] = ['type' => 'item', 'no' => $ii + 1, 'nama' => $it['nama_item'],
            'ket' => $ket_tarif[(int)$it['id_tarif']] ?? '', 'harga' => $it['harga']];
    }
}
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <title>Kwitansi <?= html_escape($kwitansi['no_kwitansi']) ?></title>
  <style>
<?php if (!$is_pdf): ?>
    @page { size: A4 portrait; margin: 8mm 10mm; }
<?php endif; ?>
    /* PDF memakai DejaVu Sans agar subskrip/superskrip (NO₃, H₂S, Cr⁶⁺) tidak rusak di mPDF */
    body { font-family: <?= $is_pdf ? 'dejavusans' : 'Arial, Helvetica' ?>, sans-serif; font-size: 10px; color: #000; margin: 0; }
    table { border-collapse: collapse; }
    .kop td { vertical-align: middle; }
    .kop .t1 { font-weight: bold; font-size: 13px; }
    .kop .t2 { font-weight: bold; font-size: 12px; }
    .kop .t3 { font-size: 9px; }
    .garis { border-top: 2px solid #000; margin: 4px 0 8px; }
    .judul { text-align: center; font-weight: bold; font-size: 12px; line-height: 1.4; margin: 6px 0; }
    .tarif { width: 100%; }
    .tarif th, .tarif td { border: 1px solid #000; padding: 1px 4px; font-size: 9px; vertical-align: top; }
    .tarif th { text-align: center; font-weight: bold; background: #f2f2f2; }
    .tarif .c { text-align: center; }
    .tarif .r { text-align: right; white-space: nowrap; }
    .tarif .grp { font-weight: bold; }
    .ket { font-size: 8px; }
    .bukti td { padding: 1px 4px; font-size: 11px; vertical-align: top; }
    .rincian td { padding: 1px 4px; font-size: 11px; }
    .jumlah td { border-top: 1px solid #000; font-weight: bold; }
  </style>
</head>
<body<?= $is_pdf ? '' : ' onload="window.print()"' ?>>

  <!-- KOP -->
  <table class="kop" style="width:100%;">
    <tr>
      <td style="width:18%; text-align:center;">
        <?php if ($img('logo.png')): ?><img src="<?= $img('logo.png') ?>" style="width:55px; height:55px;" alt=""><?php endif; ?>
      </td>
      <td style="text-align:center;">
        <div class="t1" style="font-weight:bold; font-size:13px;">DINAS KESEHATAN</div>
        <div class="t2" style="font-weight:bold; font-size:12px;">UPTD. LABORATORIUM KESEHATAN</div>
        <div class="t3">Jalan Delima Siam II Kecamatan Girimaya; Telepon (0717) 9120759</div>
        <div class="t3">Pos-el : upt-labkesehatan@pangkalpinangkota.go.id Pangkal Pinang 33143</div>
      </td>
      <td style="width:18%; text-align:center;">
        <?php if ($img('logokanan.jpeg')): ?><img src="<?= $img('logokanan.jpeg') ?>" style="width:55px; height:55px;" alt=""><?php endif; ?>
      </td>
    </tr>
  </table>
  <div class="garis"></div>

  <!-- DAFTAR PEMERIKSAAN / TARIF -->
  <div class="judul"><?= html_escape($cfg['judul_daftar']) ?><br><?= html_escape($cfg['judul_dasar']) ?></div>

  <table class="tarif">
    <thead>
      <tr>
        <th style="width:8%">No.</th><th>JENIS PEMERIKSAAN</th><th style="width:20%">JUMLAH (Rp.)</th>
      </tr>
    </thead>
    <tbody>
      <?php if (!$baris): ?>
        <tr><td colspan="3" class="c">Tidak ada pemeriksaan yang dipilih.</td></tr>
      <?php endif; ?>
      <?php foreach ($baris as $sel): ?>
        <tr>
          <?php if ($sel['type'] === 'group'): ?>
            <td class="c grp"><?= $sel['no'] ?></td><td class="grp"><?= html_escape($sel['nama']) ?></td><td></td>
          <?php else: ?>
            <td class="c"><?= (int)$sel['no'] ?></td>
            <td>
              <?= html_escape($sel['nama']) ?>
              <?php if (!empty($sel['ket'])): ?><br><span class="ket">(<?= html_escape($sel['ket']) ?>)</span><?php endif; ?>
            </td>
            <td class="r"><?= $angka($sel['harga']) ?></td>
          <?php endif; ?>
        </tr>
      <?php endforeach; ?>
      <tr>
        <td colspan="2" class="r grp">TOTAL</td>
        <td class="r grp"><?= $angka($kwitansi['total']) ?></td>
      </tr>
    </tbody>
  </table>

  <!-- BUKTI PEMBAYARAN -->
  <div class="judul" style="margin-top:14px;">
    <?= html_escape($cfg['judul_bukti']) ?><br>UPTD. LABORATORIUM KESEHATAN DINKES KOTA PANGKALPINANG
  </div>

  <table class="bukti" style="width:100%; margin-top:4px;">
    <tr><td style="width:22%">No. Kwitansi</td><td style="width:2%">:</td><td><?= html_escape($kwitansi['no_kwitansi']) ?></td></tr>
    <tr><td><?= html_escape($cfg['label_kode_cetak']) ?></td><td>:</td><td><?= html_escape($kwitansi['kode_sampel'] ?: '-') ?></td></tr>
    <tr><td>Telah terima dari</td><td>:</td><td><?= html_escape($kwitansi['nama_pembayar'] ?: '-') ?></td></tr>
    <tr><td>Uang sejumlah</td><td>:</td><td><i><?= html_escape(terbilang($kwitansi['total'])) ?> Rupiah</i></td></tr>
    <tr><td>Metode pembayaran</td><td>:</td><td><?= html_escape($kwitansi['metode_pembayaran'] ?: '-') ?></td></tr>
    <tr><td colspan="3" style="padding-top:4px;"><?= html_escape($cfg['untuk_pembayaran']) ?></td></tr>
  </table>

  <table class="rincian" style="width:75%; margin-left:6%;">
    <?php $n = 0; foreach ($ringkasan as $kelompok => $nilai): $n++; ?>
      <tr>
        <td style="width:55%"><?= $n ?>. <?= html_escape($kelompok) ?></td>
        <td>: <?= $rupiah($nilai) ?></td>
      </tr>
    <?php endforeach; ?>
    <tr class="jumlah">
      <td style="text-align:right;">JUMLAH</td>
      <td>: Rp. <?= number_format((float)$kwitansi['total'], 2, ',', '.') ?></td>
    </tr>
  </table>

  <?php if (!empty($kwitansi['keterangan'])): ?>
    <div style="margin-top:6px; font-size:10px;">Catatan: <?= html_escape($kwitansi['keterangan']) ?></div>
  <?php endif; ?>

  <!-- TANDA TANGAN -->
  <table style="width:100%; margin-top:10px;">
    <tr>
      <td style="width:55%;"></td>
      <td style="text-align:center; font-size:11px;">
        Pangkalpinang, <?= $tanggal_indo ?><br>
        Yang Menerima,
        <br><br><br><br><br>
        <u><?= html_escape($kwitansi['nama_penerima'] ?: '(............................................)') ?></u>
      </td>
    </tr>
  </table>

</body>
</html>

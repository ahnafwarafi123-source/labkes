<div class="content-wrapper px-4 pt-4">
    <?php
setlocale(LC_TIME, 'id_ID');
date_default_timezone_set('Asia/Jakarta');

// Buat tanggal dengan format manual dalam bahasa Indonesia
$hariIndo = [
    'Sunday' => 'Minggu',
    'Monday' => 'Senin',
    'Tuesday' => 'Selasa',
    'Wednesday' => 'Rabu',
    'Thursday' => 'Kamis',
    'Friday' => 'Jumat',
    'Saturday' => 'Sabtu'
];

$bulanIndo = [
    'January' => 'Januari',
    'February' => 'Februari',
    'March' => 'Maret',
    'April' => 'April',
    'May' => 'Mei',
    'June' => 'Juni',
    'July' => 'Juli',
    'August' => 'Agustus',
    'September' => 'September',
    'October' => 'Oktober',
    'November' => 'November',
    'December' => 'Desember'
];

$hari = $hariIndo[date('l')];
$tanggal = date('d');
$bulan = $bulanIndo[date('F')];
$tahun = date('Y');
$tanggalLengkap = "$hari, $tanggal $bulan $tahun";
?>
  <h3 class="text-dark fw-bold mb-4 d-flex justify-content-between align-items-center">
  <span><?= $title ?? 'LAPORAN HASIL UJI LABORATORIUM KLINIK' ?></span>
  <small class="text-muted fw-normal"><?= $tanggalLengkap ?></small>
</h3>
  <hr style="border-top: 2px solid #1e3a8a; margin-bottom: 20px;">
  
  <div class="card shadow-sm w-100 mt-3">
    <div class="card-body table-responsive p-3">
      <table id="tabel-permintaan" class="table table-bordered table-striped table-hover nowrap" style="width:100%">
        <thead class="bg-light">
          <tr>
            <th width="5%">No</th>
            <th>No Register</th>
            <th>Nama Pasien</th>
            <th>NIK</th>
            <th>Jenis Kelamin</th>
            <th>Tanggal Form</th>
            <th>Dokter Pengirim</th>
            <th width="10%">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php if (!empty($formulir)): ?>
            <?php $no = 1; foreach ($formulir as $f): ?>
              <tr>
                <td class="text-center"><?= $no++ ?></td>
                <td><?= $f->no_register ?></td>
                <td><?= $f->nama_pasien ?></td>
                <td><?= $f->nik ?></td>
                <td><?= $f->gender ?></td>
                <td><?= date('d-m-Y', strtotime($f->tgl_form)) ?></td>
                <td><?= $f->nama_dokter ?></td>
                <td class="text-center">
                  <a href="<?= site_url('klinik/laporan_uji_klinik/export_pdf/'.$f->id) ?>" class="btn btn-sm btn-outline-danger" target="_blank" title="Cetak Hasil">
                    <i class="fas fa-file-pdf"></i> Cetak Hasil
                  </a>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

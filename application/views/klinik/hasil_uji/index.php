<div class="content-wrapper">
    <section class="content pt-4">
        <div class="container-fluid">
            <?php
            setlocale(LC_TIME, 'id_ID');
            date_default_timezone_set('Asia/Jakarta');

            $hariIndo = ['Sunday' => 'Minggu', 'Monday' => 'Senin', 'Tuesday' => 'Selasa', 'Wednesday' => 'Rabu', 'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu'];
            $bulanIndo = ['January' => 'Januari', 'February' => 'Februari', 'March' => 'Maret', 'April' => 'April', 'May' => 'Mei', 'June' => 'Juni', 'July' => 'Juli', 'August' => 'Agustus', 'September' => 'September', 'October' => 'Oktober', 'November' => 'November', 'December' => 'Desember'];

            $hari = $hariIndo[date('l')];
            $tanggal = date('d');
            $bulan = $bulanIndo[date('F')];
            $tahun = date('Y');
            $tanggalLengkap = "$hari, $tanggal $bulan $tahun";
            ?>
            <h3 class="text-dark fw-bold mb-4 d-flex justify-content-between align-items-center">
                <span>DATA HASIL UJI DAN SOAP PASIEN</span>
                <small class="text-muted fw-normal"><?= $tanggalLengkap ?></small>
            </h3>
            <hr style="border-top: 2px solid #1e3a8a; margin-bottom: 20px;">

            <div class="card shadow-sm w-100">
                <div class="card-body table-responsive p-3">
                    <table id="tabel-kunjungan" class="table table-bordered table-striped table-hover nowrap"
                        style="width:100%">
                        <thead style="background-color: #d1e3ff;">
                            <tr>
                                <th width="5%" class="text-center">No</th>
                                <th width="20%">Nama Pasien</th>
                                <th width="15%">NIK</th>
                                <th width="10%">Jenis Kelamin</th>
                                <th width="10%">Detail Laporan Uji Lab & SOAP</th>
                                <th width="15%" class="text-center">Waktu Pendaftaran</th>
                                <th width="10%" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($pasien)): ?>
                                <?php $no = 1;
                                foreach ($pasien as $p): ?>
                                    <tr>
                                        <td class="text-center"><?= $no++ ?></td>
                                        <td><?= htmlspecialchars($p->nama_pasien) ?></td>
                                        <td><?= htmlspecialchars($p->nik) ?></td>
                                        <td><?= ($p->gender == 'Laki-laki') ? 'L' : 'P' ?></td>
                                        <td class="text-center">
                                            <?php
                                            $has_soap = ($p->status_soap == 'Sudah diisi');
                                            $has_lab = ($p->total_hasil > 0);

                                            if ($has_soap && $has_lab) {
                                                echo '<span class="badge bg-success p-2">Ada SOAP & Lab</span>';
                                            } elseif ($has_soap) {
                                                echo '<span class="badge bg-success p-2">Ada SOAP</span>';
                                            } elseif ($has_lab) {
                                                echo '<span class="badge bg-success p-2">Ada Lab</span>';
                                            } else {
                                                echo '<span class="badge bg-secondary p-2">Belum ada isi</span>';
                                            }
                                            ?>
                                        </td>
                                        <td class="text-center"><?= htmlspecialchars($p->tanggal_kunjungan) ?></td>
                                        <td class="text-center">
                                            <div class="d-flex gap-2 justify-content-center">
                                                <a href="<?= site_url('klinik/hasil_laporan/hasil_lab/' . $p->id_pasien) ?>"
                                                    class="btn btn-sm btn-info text-white" title="Pengisian Detail Kunjungan">
                                                    <i class="fas fa-eye me-1"></i>Lihat Kunjungan
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
    $(document).ready(function () {
        $('#tabel-kunjungan').DataTable({
            responsive: true,
            autoWidth: false,
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json"
            }
        });
    });
</script>
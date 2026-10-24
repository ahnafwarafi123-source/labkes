<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><?= $title; ?></h1>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">

        <div class="row">
                <div class="col-md-6">
                    <div class="card card-success">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-user-plus"></i> Identitas Pasien  </h3>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered table-striped" id="tabel-laporan-klinik">
                                <thead>
                                    <tr>
                                        <th style="width: 10px">No</th>
                                        <th>Hari, Tanggal</th>
                                        <th>Jumlah Pasien</th>
                                        <th>Jumlah Jenis Kelamin</th>
                                        <th style="width: 120px">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $no = 1;
                                    foreach ($klinik as $kl): ?>
                                        <?php
                                        $ts = strtotime($kl->tanggal);
                                        $hari_en = date('l', $ts);
                                        $tanggal_indo = date('d-m-Y', $ts);
                                        ?>
                                        <tr>
                                            <td><?= $no++; ?></td>
                                            <td><?= isset($hari_indo[$hari_en]) ? $hari_indo[$hari_en] : $hari_en ?>,
                                                <?= $tanggal_indo ?>
                                            </td>
                                            <td><span class="badge badge-success"
                                                    style="font-size: 14px;"><?= $kl->total_pasien ?> Pasien</span>
                                            </td>
                                            <td>
                                                <span class="badge badge-info" title="Laki-laki"><i
                                                        class="fas fa-mars"></i> <?= $kl->total_l ?: 0 ?></span>
                                                <span class="badge badge-danger" title="Perempuan"><i
                                                        class="fas fa-venus"></i> <?= $kl->total_p ?: 0 ?></span>
                                            </td>
                                            <td>
                                                <a href="<?= site_url('klinik/Laporan_hasil_pemeriksaan/detail_pasien/' . $kl->tanggal) ?>"
                                                    class="btn btn-sm btn-info">
                                                    <i class="fas fa-eye"></i> Lihat Detail
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            <div class="row">
                <!-- Data Pendaftaran (Pasien Baru) -->
                <div class="col-md-6">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-hospital-user"></i> Daftar Pemeriksaan KIMIA DARAH </h3>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered table-striped" id="tabel-laporan-pendaftaran">
                                <thead>
                                    <tr>
                                        <th style="width: 10px">No</th>
                                        <th>Hari, Tanggal</th>
                                        <th>Jumlah Pasien</th>
                                        <th style="width: 120px">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $no = 1;
                                    foreach ($registrations as $reg): ?>
                                        <?php
                                        $ts = strtotime($reg->tanggal);
                                        $hari_en = date('l', $ts);
                                        $tanggal_indo = date('d-m-Y', $ts);
                                        ?>
                                        <tr>
                                            <td><?= $no++; ?></td>
                                            <td><?= isset($hari_indo[$hari_en]) ? $hari_indo[$hari_en] : $hari_en ?>,
                                                <?= $tanggal_indo ?>
                                            </td>
                                            <td><span class="badge badge-success"
                                                    style="font-size: 14px;"><?= $reg->total_pasien ?> Pasien</span></td>
                                            <td>
                                                <a href="<?= site_url('klinik/Laporan_hasil_pemeriksaan/detail_pendaftaran/' . $reg->tanggal) ?>"
                                                    class="btn btn-sm btn-info">
                                                    <i class="fas fa-eye"></i> Lihat Detail
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Data Kunjungan -->
                <div class="col-md-6">
                    <div class="card card-success">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-clinic-medical"></i> Daftar Pemeriksaan URINALISA</h3>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered table-striped" id="tabel-laporan-kunjungan">
                                <thead>
                                    <tr>
                                        <th style="width: 10px">No</th>
                                        <th>Hari, Tanggal</th>
                                        <th>Jumlah Kunjungan</th>
                                        <th style="width: 120px">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $no = 1;
                                    foreach ($visits as $vis): ?>
                                        <?php
                                        $ts = strtotime($vis->tanggal);
                                        $hari_en = date('l', $ts);
                                        $tanggal_indo = date('d-m-Y', $ts);
                                        ?>
                                        <tr>
                                            <td><?= $no++; ?></td>
                                            <td><?= isset($hari_indo[$hari_en]) ? $hari_indo[$hari_en] : $hari_en ?>,
                                                <?= $tanggal_indo ?>
                                            </td>
                                            <td><span class="badge badge-primary"
                                                    style="font-size: 14px;"><?= $vis->total_kunjungan ?> Kunjungan</span>
                                            </td>
                                            <td>
                                                <a href="<?= site_url('klinik/Laporan_hasil_pemeriksaan/detail_kunjungan/' . $vis->tanggal) ?>"
                                                    class="btn btn-sm btn-info">
                                                    <i class="fas fa-eye"></i> Lihat Detail
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Laporan Uji Laboratorium Klinik -->
                <div class="col-md-6">
                    <div class="card card-warning">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-vial"></i> Daftar Pemeriksaan BIOMOLEKULER</h3>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered table-striped" id="tabel-laporan-uji-klinik">
                                <thead>
                                    <tr>
                                        <th style="width: 10px">No</th>
                                        <th>Hari, Tanggal</th>
                                        <th>Jumlah Pasien</th>
                                        <th style="width: 120px">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $no = 1;
                                    foreach ($uji_klinik as $uk): ?>
                                        <?php
                                        $ts = strtotime($uk->tanggal);
                                        $hari_en = date('l', $ts);
                                        $tanggal_indo = date('d-m-Y', $ts);
                                        ?>
                                        <tr>
                                            <td><?= $no++; ?></td>
                                            <td><?= isset($hari_indo[$hari_en]) ? $hari_indo[$hari_en] : $hari_en ?>,
                                                <?= $tanggal_indo ?>
                                            </td>
                                            <td><span class="badge badge-warning"
                                                    style="font-size: 14px;"><?= $uk->total_pasien ?> Pemeriksaan</span></td>
                                            <td>
                                                <a href="<?= site_url('klinik/Laporan_hasil_pemeriksaan/detail_uji_klinik/' . $uk->tanggal) ?>"
                                                    class="btn btn-sm btn-info">
                                                    <i class="fas fa-eye"></i> Lihat Detail
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tambahan Baris untuk Card ke 5 dan 6 -->
            <div class="row mt-4">
                <!-- Card 5: Imunologi -->
                <div class="col-md-6">
                    <div class="card card-info">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-microscope"></i> Daftar Pemeriksaan HEMOSTASIS</h3>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered table-striped" id="tabel-laporan-imunologi">
                                <thead>
                                    <tr>
                                        <th style="width: 10px">No</th>
                                        <th>Hari, Tanggal</th>
                                        <th>Jumlah Pasien</th>
                                        <th style="width: 120px">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $no = 1;
                                    foreach ($uji_klinik as $uk): ?>
                                        <?php
                                        $ts = strtotime($uk->tanggal);
                                        $hari_en = date('l', $ts);
                                        $tanggal_indo = date('d-m-Y', $ts);
                                        ?>
                                        <tr>
                                            <td><?= $no++; ?></td>
                                            <td><?= isset($hari_indo[$hari_en]) ? $hari_indo[$hari_en] : $hari_en ?>,
                                                <?= $tanggal_indo ?>
                                            </td>
                                            <td><span class="badge badge-info"
                                                    style="font-size: 14px;"><?= $uk->total_pasien ?> Pemeriksaan</span></td>
                                            <td>
                                                <a href="<?= site_url('klinik/Laporan_hasil_pemeriksaan/detail_uji_klinik/' . $uk->tanggal) ?>"
                                                    class="btn btn-sm btn-info">
                                                    <i class="fas fa-eye"></i> Lihat Detail
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Card 6: Mikrobiologi -->
                <div class="col-md-6">
                    <div class="card card-danger">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-bacterium"></i> Daftar Pemeriksaan MIKROBIOLOGI/PARASITOLOGI</h3>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered table-striped" id="tabel-laporan-mikrobiologi">
                                <thead>
                                    <tr>
                                        <th style="width: 10px">No</th>
                                        <th>Hari, Tanggal</th>
                                        <th>Jumlah Pasien</th>
                                        <th style="width: 120px">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $no = 1;
                                    foreach ($uji_klinik as $uk): ?>
                                        <?php
                                        $ts = strtotime($uk->tanggal);
                                        $hari_en = date('l', $ts);
                                        $tanggal_indo = date('d-m-Y', $ts);
                                        ?>
                                        <tr>
                                            <td><?= $no++; ?></td>
                                            <td><?= isset($hari_indo[$hari_en]) ? $hari_indo[$hari_en] : $hari_en ?>,
                                                <?= $tanggal_indo ?>
                                            </td>
                                            <td><span class="badge badge-danger"
                                                    style="font-size: 14px;"><?= $uk->total_pasien ?> Pemeriksaan</span></td>
                                            <td>
                                                <a href="<?= site_url('klinik/Laporan_hasil_pemeriksaan/detail_uji_klinik/' . $uk->tanggal) ?>"
                                                    class="btn btn-sm btn-info">
                                                    <i class="fas fa-eye"></i> Lihat Detail
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tambahan Baris untuk Card ke 7 dan 8 -->
            <div class="row mt-4">
                <!-- Card 7: Serologi -->
                <div class="col-md-6">
                    <div class="card card-secondary">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-dna"></i> Daftar Pemeriksaan IMUNOLOGI</h3>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered table-striped" id="tabel-laporan-serologi">
                                <thead>
                                    <tr>
                                        <th style="width: 10px">No</th>
                                        <th>Hari, Tanggal</th>
                                        <th>Jumlah Pasien</th>
                                        <th style="width: 120px">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $no = 1;
                                    foreach ($uji_klinik as $uk): ?>
                                        <?php
                                        $ts = strtotime($uk->tanggal);
                                        $hari_en = date('l', $ts);
                                        $tanggal_indo = date('d-m-Y', $ts);
                                        ?>
                                        <tr>
                                            <td><?= $no++; ?></td>
                                            <td><?= isset($hari_indo[$hari_en]) ? $hari_indo[$hari_en] : $hari_en ?>,
                                                <?= $tanggal_indo ?>
                                            </td>
                                            <td><span class="badge badge-secondary"
                                                    style="font-size: 14px;"><?= $uk->total_pasien ?> Pemeriksaan</span></td>
                                            <td>
                                                <a href="<?= site_url('klinik/Laporan_hasil_pemeriksaan/detail_uji_klinik/' . $uk->tanggal) ?>"
                                                    class="btn btn-sm btn-info">
                                                    <i class="fas fa-eye"></i> Lihat Detail
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Card 8: Parasitologi -->
                <div class="col-md-6">
                    <div class="card card-dark">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-bug"></i> Daftar Pemeriksaan TOKSIKOLOGI</h3>
                        </div>
                        <div class="card-body">

                            <!-- CHART -->
                            <canvas id="chartToksikologi" height="100"></canvas>

                            <hr>

                            <!-- TABEL -->
                            <table class="table table-bordered table-striped mt-3">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Hari, Tanggal</th>
                                        <th>Jumlah Pasien</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $no = 1;
                                    foreach ($uji_klinik as $uk): ?>
                                        <?php
                                        $ts = strtotime($uk->tanggal);
                                        $hari_en = date('l', $ts);
                                        $tanggal_indo = date('d-m-Y', $ts);
                                        ?>
                                        <tr>
                                            <td><?= $no++; ?></td>
                                            <td><?= isset($hari_indo[$hari_en]) ? $hari_indo[$hari_en] : $hari_en ?>,
                                                <?= $tanggal_indo ?>
                                            </td>
                                            <td>
                                                <span class="badge badge-dark">
                                                    <?= $uk->total_pasien ?> Pemeriksaan
                                                </span>
                                            </td>
                                            <td>
                                                <a href="<?= site_url('klinik/Laporan_hasil_pemeriksaan/detail_uji_klinik/' . $uk->tanggal) ?>"
                                                    class="btn btn-sm btn-info">
                                                    <i class="fas fa-eye"></i> Detail
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>
</div>

<!-- DataTables setup (assume standard config from theme) -->
<script>
    document.addEventListener("DOMContentLoaded", function () {
        if ($.fn.DataTable) {
            $('#tabel-laporan-pendaftaran').DataTable({
                "paging": true,
                "lengthChange": false,
                "searching": true,
                "ordering": false,
                "info": true,
                "autoWidth": false,
                "responsive": true,
            });
            $('#tabel-laporan-kunjungan').DataTable({
                "paging": true,
                "lengthChange": false,
                "searching": true,
                "ordering": false,
                "info": true,
                "autoWidth": false,
                "responsive": true,
            });
            $('#tabel-laporan-klinik').DataTable({
                "paging": true,
                "lengthChange": false,
                "searching": true,
                "ordering": false,
                "info": true,
                "autoWidth": false,
                "responsive": true,
            });
            $('#tabel-laporan-uji-klinik').DataTable({
                "paging": true,
                "lengthChange": false,
                "searching": true,
                "ordering": false,
                "info": true,
                "autoWidth": false,
                "responsive": true,
            });
            $('#tabel-laporan-imunologi').DataTable({
                "paging": true,
                "lengthChange": false,
                "searching": true,
                "ordering": false,
                "info": true,
                "autoWidth": false,
                "responsive": true,
            });
            $('#tabel-laporan-mikrobiologi').DataTable({
                "paging": true,
                "lengthChange": false,
                "searching": true,
                "ordering": false,
                "info": true,
                "autoWidth": false,
                "responsive": true,
            });
            $('#tabel-laporan-serologi').DataTable({
                "paging": true,
                "lengthChange": false,
                "searching": true,
                "ordering": false,
                "info": true,
                "autoWidth": false,
                "responsive": true,
            });
            $('#tabel-laporan-parasitologi').DataTable({
                "paging": true,
                "lengthChange": false,
                "searching": true,
                "ordering": false,
                "info": true,
                "autoWidth": false,
                "responsive": true,
            });
        }
    });
</script>

<script>
    const labels = [
        <?php foreach ($uji_klinik as $uk): ?>
            "<?= date('d-m', strtotime($uk->tanggal)) ?>",
        <?php endforeach; ?>
    ];

    const data = [
        <?php foreach ($uji_klinik as $uk): ?>
            <?= $uk->total_pasien ?>,
        <?php endforeach; ?>
    ];

    const ctx = document.getElementById('chartToksikologi').getContext('2d');

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Jumlah Pemeriksaan',
                data: data,
                backgroundColor: 'rgba(0, 0, 0, 0.7)',
                borderColor: 'rgba(0, 0, 0, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: true
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><?= $title; ?></h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= site_url('klinik/Laporan_hasil_pemeriksaan') ?>">Laporan Harian</a>
                        </li>
                        <li class="breadcrumb-item active">Detail Kunjungan</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="card card-success">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title m-0">Daftar Kunjungan Pasien</h3>
                    <div class="card-tools d-flex justify-content-between">
                        <div class="me-2">
                            <a href="<?= site_url('klinik/Laporan_hasil_pemeriksaan') ?>" class="btn btn-sm btn-light text-dark">
                                <i class="fas fa-arrow-left"></i> Kembali
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-striped datatable">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>No RM</th>
                                <th>Nama Pasien</th>
                                <th>Unit Kunjungan</th>
                                <th>Status SOAP</th>
                                <th>Waktu Kunjungan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1;
                            foreach ($kunjungan as $k): ?>
                                <tr>
                                    <td><?= $no++; ?></td>
                                    <td><span class="badge badge-info"><?= $k->no_rm; ?></span></td>
                                    <td><strong><?= $k->nama_pasien; ?></strong></td>
                                    <td><?= empty($k->unit) ? 'Poli Umum' : $k->unit; ?></td>
                                    <td>
                                        <?php if ($k->status_soap == 'Belum diisi'): ?>
                                            <span class="badge badge-warning"><?= $k->status_soap ?></span>
                                        <?php else: ?>
                                            <span class="badge badge-success"><?= $k->status_soap ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= date('H:i:s', strtotime($k->tanggal_kunjungan)); ?></td>
                                    <td>
                                        <?php if ($k->status_soap == 'Belum diisi'): ?>
                                            <a href="<?= site_url('klinik/uji_rekam_medis/soap/' . $k->id); ?>"
                                                class="btn btn-sm btn-primary">
                                                <i class="fas fa-edit"></i> Isi SOAP
                                            </a>
                                        <?php else: ?>
                                            <a href="<?= site_url('klinik/uji_rekam_medis/lihat_soap/' . $k->id); ?>"
                                                class="btn btn-sm btn-info text-white">
                                                <i class="fas fa-eye"></i> Lihat SOAP
                                            </a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        if ($.fn.DataTable) {
            $('.datatable').DataTable({
                "paging": true,
                "lengthChange": true,
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "responsive": true,
            });
        }
    });
</script>
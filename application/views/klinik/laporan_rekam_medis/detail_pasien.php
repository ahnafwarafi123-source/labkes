<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><?= $title; ?></h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= site_url('klinik/Laporan_hasil_pemeriksaan') ?>">Laporan Harian</a></li>
                        <li class="breadcrumb-item active">Detail Identitas Pasien</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <div class="container-fluid mb-3">
        <div class="d-flex justify-content-between">
            <div>
                <a href="<?= site_url('klinik/Laporan_hasil_pemeriksaan') ?>" class="btn btn-secondary px-4 py-2 shadow-sm">
                    <i class="fas fa-arrow-left me-2"></i> Kembali
                </a>
            </div>
            <div>
                <?php if(isset($tanggal) && $tanggal): ?>
                <a href="<?= site_url('klinik/Laporan_hasil_pemeriksaan/print_pasien_pdf/' . $tanggal) ?>" target="_blank" class="btn btn-danger px-4 py-2 shadow-sm">
                    <i class="fas fa-file-pdf me-2"></i> Cetak PDF
                </a>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="card card-success shadow-sm">
                <div class="card-header">
                    <h3 class="card-title">Identitas Pasien</h3>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-striped datatable">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Pasien</th>
                                <th>NIK</th>
                                <th>Jenis Kelamin</th>
                                <th>Umur</th>
                                <th>Alamat</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1; foreach ($pasien as $p): ?>
                                <tr>
                                    <td><?= $no++; ?></td>
                                    <td><strong><?= $p->nama_pasien; ?></strong></td>
                                    <td><?= $p->nik; ?></td>
                                    <td><?= $p->gender; ?></td>
                                    <td><?= $p->umur; ?> Tahun</td>
                                    <td><?= $p->alamat; ?></td>
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

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
                        <li class="breadcrumb-item active">Detail Pendaftaran</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>


    <div class="card-tools d-flex justify-content-between">
        <div>
            <a href="<?= site_url('klinik/Laporan_hasil_pemeriksaan') ?>" class="btn btn-secondary px-5 py-2">
                <i class="fas fa-arrow-left me-2"></i> Kembali
            </a>
        </div>
        <div>
            <a href="<?= site_url('klinik/Laporan_hasil_pemeriksaan/print_pendaftaran_pdf/' . $tanggal) ?>" target="_blank"
                class="btn btn-danger px-4 py-2">
                <i class="fas fa-file-pdf me-2"></i> Cetak PDF
            </a>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Daftar Pasien Terdaftar</h3>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-striped datatable">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>No RM</th>
                                <th>Nama Pasien</th>
                                <th>Jenis Kelamin</th>
                                <th>Umur</th>
                                <th>Waktu Terdaftar</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1;
                            foreach ($pasien as $p): ?>
                                <tr>
                                    <td><?= $no++; ?></td>
                                    <td><span class="badge badge-info"><?= $p->no_rm; ?></span></td>
                                    <td><strong><?= $p->nama_pasien; ?></strong></td>
                                    <td><?= $p->gender; ?></td>
                                    <td><?= $p->umur; ?> Tahun</td>
                                    <td><?= date('H:i:s', strtotime($p->created_at)); ?></td>
                                    <td>
                                        <a href="<?= site_url('klinik/form_permintaan_rm/detail/' . $p->id) ?>"
                                            class="btn btn-sm btn-outline-primary me-1" title="Lihat">
                                            <i class="fas fa-eye"></i>
                                        </a>
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
    document.addEventListener("DOMContentLoaded", function() {
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
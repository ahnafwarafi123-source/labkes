<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><?= $title ?></h1>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header bg-primary">
                    <h3 class="card-title">Daftar Laporan Harian (Hasil Uji & SOAP)</h3>
                </div>
                <div class="card-body">
                    <table id="table1" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Hari & Tanggal</th>
                                <th>Total Aktivitas (SOAP/Lab)</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1; foreach ($combined_data as $row): ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= $hari_indo[date('l', strtotime($row->tanggal))] ?>, <?= date('d-m-Y', strtotime($row->tanggal)) ?></td>
                                <td><span class="badge badge-info"><?= $row->total_aktivitas ?> Data</span></td>
                                <td>
                                    <a href="<?= site_url('klinik/Laporan_hasil_pemeriksaan/detail_hasil_uji_soap/'.$row->tanggal) ?>" class="btn btn-sm btn-primary">
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
    </section>
</div>

<script>
    $(function () {
        $("#table1").DataTable({
            "responsive": true, 
            "lengthChange": true, 
            "autoWidth": false,
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json"
            }
        });
    });
</script>

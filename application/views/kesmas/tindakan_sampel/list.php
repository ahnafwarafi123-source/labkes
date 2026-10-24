<div class="container-fluid">
    <div class="row">
        <div class="col-md-10 mx-auto">
            <div class="card mt-4">
                <div class="card-header">
                    <h3 class="card-title"><?= $title ?></h3>
                </div>
                <div class="card-body">
                    <a href="<?= site_url('k-panel/tindakan_sampel/add') ?>" class="btn btn-primary mb-3">Tambah Tindakan Sampel</a>

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nama</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (isset($tindakan_sampel_list) && !empty($tindakan_sampel_list)): ?>
                                    <?php $no = 1; foreach ($tindakan_sampel_list as $ts): ?>
                                        <tr>
                                            <td><?= $no++ ?></td>
                                            <td><?= $ts['nama'] ?></td>
                                            <td>
                                                <a href="<?= site_url('k-panel/tindakan_sampel/edit/' . $ts['id']) ?>" class="btn btn-sm btn-warning">Edit</a>
                                                <a href="<?= site_url('k-panel/tindakan_sampel/delete/' . $ts['id']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda yakin ingin menonaktifkan item ini?')">Hapus</a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="3" class="text-center">Belum ada data tindakan sampel.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

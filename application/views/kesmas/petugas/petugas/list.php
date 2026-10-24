<div class="container-fluid">
    <div class="row">
        <div class="col-md-10 mx-auto">
            <div class="card mt-4">
                <div class="card-header">
                    <h3 class="card-title"><?= $title ?></h3>
                </div>
                <div class="card-body">
                    <a href="<?= site_url('k-panel/petugas/add') ?>" class="btn btn-primary mb-3">Tambah Petugas</a>

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nama</th>
                                    <th>Jabatan</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (isset($petugas) && !empty($petugas)): ?>
                                    <?php $no = 1; foreach ($petugas as $p): ?>
                                        <tr>
                                            <td><?= $no++ ?></td>
                                            <td><?= $p['nama'] ?></td>
                                            <td><?= $p['jabatan'] ?></td>
                                            <td>
                                                <a href="<?= site_url('k-panel/petugas/edit/' . $p['id_petugas']) ?>" class="btn btn-sm btn-warning">Edit</a>
                                                <a href="<?= site_url('k-panel/petugas/delete/' . $p['id_petugas']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus petugas ini?')">Hapus</a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="4" class="text-center">Belum ada data petugas.</td>
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

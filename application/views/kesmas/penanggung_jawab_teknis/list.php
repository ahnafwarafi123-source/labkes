<div class="container-fluid">
    <div class="row">
        <div class="col-md-10 mx-auto">
            <div class="card mt-4">
                <div class="card-header">
                    <h3 class="card-title"><?= $title ?></h3>
                </div>
                <div class="card-body">
                    <a href="<?= site_url('k-panel/penanggung_jawab_teknis/add') ?>" class="btn btn-primary mb-3">Tambah Penanggung Jawab Teknis</a>

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nama</th>
                                    <th>NIP</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (isset($penanggung_jawab_teknis_list) && !empty($penanggung_jawab_teknis_list)): ?>
                                    <?php $no = 1; foreach ($penanggung_jawab_teknis_list as $pjt): ?>
                                        <tr>
                                            <td><?= $no++ ?></td>
                                            <td><?= html_escape($pjt['nama']) ?></td>
                                            <td><?= html_escape($pjt['nip']) ?></td>
                                            <td>
                                                <a href="<?= site_url('k-panel/penanggung_jawab_teknis/edit/' . $pjt['id_penanggung_jawab']) ?>" class="btn btn-sm btn-warning">Edit</a>
                                                <a href="<?= site_url('k-panel/penanggung_jawab_teknis/delete/' . $pjt['id_penanggung_jawab']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">Hapus</a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="4" class="text-center">Belum ada data.</td>
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

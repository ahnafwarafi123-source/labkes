<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Daftar Petugas</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url('k-panel/dashboard') ?>">Home</a></li>
                        <li class="breadcrumb-item active">Petugas</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Data Petugas</h3>
                            <div class="card-tools">
                                <a href="<?= base_url('k-panel/petugas/add') ?>" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Tambah Petugas</a>
                            </div>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <table id="example2" class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Jabatan</th>
                                        <th>Nama</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($petugas)) : ?>
                                        <?php $no = 1;
                                        foreach ($petugas as $p) : ?>
                                            <tr>
                                                <td><?= $no++ ?></td>
                                                <td><?= html_escape($p['jabatan'] ?? '-') ?></td>
                                                <td><?= html_escape($p['nama']) ?></td>
                                                <td><?= $p['is_active'] ? '<span class="badge badge-success">Aktif</span>' : '<span class="badge badge-danger">Non-Aktif</span>' ?></td>
                                                <td>
                                                    <a href="<?= base_url('k-panel/petugas/edit/' . $p['id_petugas']) ?>" class="btn btn-info btn-sm"><i class="fas fa-edit"></i> Edit</a>
                                                    <a href="<?= base_url('k-panel/petugas/delete/' . $p['id_petugas']) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus petugas ini?');"><i class="fas fa-trash"></i> Hapus</a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else : ?>
                                        <tr>
                                            <td colspan="5" class="text-center">Tidak ada data petugas.</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>

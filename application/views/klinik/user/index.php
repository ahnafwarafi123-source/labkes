<div class="content-wrapper px-4 pt-4">
    <div class="card shadow-sm border-0 rounded-3">
        <div class="card-header bg-white border-bottom-0 pt-4 pb-0 d-flex justify-content-between align-items-center">
            <h3 class="fw-bold text-primary mb-0">Data User</h3>
            <a href="<?= site_url('klinik/user/create') ?>" class="btn btn-info text-white shadow-sm px-4">
                <i class="fas fa-plus me-1"></i> Tambah User
            </a>
        </div>
        <div class="card-body p-4">
        <div class="table-responsive w-100">
        <table class="table table-bordered table-hover">
            <thead class="table-info text-center">
                <tr>
                    <th>No</th>
                    <th>Username</th>
                    <th>Nama</th>
                    <th>Role</th>
                    <th>Dibuat Pada</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($users)): ?>
                    <tr>
                        <td colspan="6" class="text-center">Belum ada data user</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($users as $i => $u): ?>
                        <tr>
                            <td class="text-center"><?= $i+1 ?></td>
                            <td><?= $u->username ?></td>
                            <td><?= $u->nama ?></td>
                            <td class="text-center"><?= ucfirst($u->klinik_role) ?></td>
                            <td class="text-center"><?= date('d-m-Y H:i', strtotime($u->created_at)) ?></td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-1">
                                    <a href="<?= site_url('klinik/user/edit/'.$u->id_user) ?>" class="btn btn-light btn-sm" title="Edit">
                                        <i class="fas fa-edit text-warning"></i>
                                    </a>
                                    <a href="<?= site_url('klinik/user/delete/'.$u->id_user) ?>" class="btn btn-light btn-sm" title="Hapus" onclick="return confirm('Hapus user ini?')">
                                        <i class="fas fa-trash text-danger"></i>
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
</div>

<style>
    .table thead th {
        text-align: center;
        vertical-align: middle;
    }

    .table td, .table th {
        vertical-align: middle;
        font-size: 14px;
    }

    .btn-warning {
        color: #fff;
    }

    .btn-danger {
        color: #fff;
    }

    .btn-warning:hover {
        background-color: #ffc107;
        color: #fff;
    }

    .btn-danger:hover {
        background-color: #dc3545;
        color: #fff;
    }
</style>

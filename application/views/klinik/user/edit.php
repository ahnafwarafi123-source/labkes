<div class="content-wrapper px-4 pt-4">
    <div class="card shadow-sm border-0 rounded-3">
        <div class="card-header bg-white border-bottom-0 pt-4 pb-0">
            <h3 class="fw-bold text-primary mb-0">Edit User</h3>
        </div>
        <div class="card-body p-4">
            <form action="<?= site_url('klinik/user/update/' . $user->id_user) ?>" method="post">
                <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>" />
        <div class="mb-3">
            <label>Username</label>
            <input type="text" name="username" class="form-control" value="<?= $user->username ?>" required>
        </div>

        <div class="mb-3">
            <label>Nama</label>
            <input type="text" name="nama" class="form-control" value="<?= $user->nama ?>" required>
        </div>

        <div class="mb-3">
            <label>Role</label>
            <select name="role" class="form-select" required>
                <option value="admin" <?= $user->klinik_role == 'admin' ? 'selected' : '' ?>>Admin</option>
                <option value="pemeriksa sampel" <?= $user->klinik_role == 'pemeriksa sampel' ? 'selected' : '' ?>>Pemeriksa Sampel</option>
                <option value="petugas pendaftaran" <?= $user->klinik_role == 'petugas pendaftaran' ? 'selected' : '' ?>>Petugas Pendaftaran</option>
                <option value="petugas rm" <?= $user->klinik_role == 'petugas rm' ? 'selected' : '' ?>>Petugas Rekam Medis</option>
            </select>
        </div>

        <div class="mb-3">
            <label>Password Baru (kosongkan jika tidak ingin diganti)</label>
            <input type="password" name="password" class="form-control" placeholder="Isi jika ingin ganti password">
        </div>

        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="<?= site_url('klinik/user') ?>" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
</div>

<div class="content-wrapper px-4 pt-4">
    <div class="card shadow-sm border-0 rounded-3">
        <div class="card-header bg-white border-bottom-0 pt-4 pb-0">
            <h3 class="fw-bold text-primary mb-0">Tambah User</h3>
        </div>
        <div class="card-body p-4">
            <form action="<?= site_url('klinik/user/create') ?>" method="post">
                <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>" />
        <div class="mb-3">
            <label>Username</label>
            <input type="text" name="username" class="form-control" placeholder="Masukkan username" required>
        </div>
        <div class="mb-3">
            <label>Password</label>
            <input type="password" name="password" class="form-control" placeholder="Masukkan password" required>
        </div>
        <div class="mb-3">
            <label>Nama</label>
            <input type="text" name="nama" class="form-control" placeholder="Masukkan nama lengkap" required>
        </div>
        <div class="mb-3">
            <label>Role</label>
            <select name="role" class="form-select" required>
                <option value="admin">Admin</option>
                <option value="pemeriksa sampel">Pemeriksa Sampel</option>
                <option value="petugas pendaftaran">Petugas Pendaftaran</option>
                <option value="petugas rm">Petugas Rekam Medis</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="<?= site_url('klinik/user') ?>" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
</div>

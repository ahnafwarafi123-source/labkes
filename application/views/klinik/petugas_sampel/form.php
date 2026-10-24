<div class="content-wrapper px-4 pt-4">
  <div class="card shadow-sm border-0 rounded-3 w-50 mx-auto">
    <div class="card-header bg-white border-bottom-0 pt-4 pb-0">
      <h3 class="fw-bold text-primary mb-0 text-center">
        <?= isset($petugas) && !empty($petugas->id_petugas) ? 'Edit Data Petugas' : 'Tambah Data Petugas' ?>
      </h3>
    </div>
    <div class="card-body p-4">
      <form action="<?= !empty($petugas->id_petugas) 
    ? site_url('klinik/petugas_sampel/edit_action/'.$petugas->id_petugas) 
    : site_url('klinik/petugas_sampel/create_action') ?>" method="post">
        <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>" />
        <div class="mb-3">
          <label for="nama_petugas" class="form-label fw-semibold">Nama Petugas</label>
          <input type="text" class="form-control" name="nama_petugas" id="nama_petugas" value="<?= $petugas->nama_petugas ?? '' ?>" required>
        </div>

        <div class="mb-3">
          <label for="jabatan" class="form-label fw-semibold">Jabatan</label>
          <input type="text" class="form-control" name="jabatan" id="jabatan" value="<?= $petugas->jabatan ?? '' ?>">
        </div>

        <div class="d-flex justify-content-between">
          <a href="<?= site_url('klinik/petugas_sampel') ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
          </a>
          <button type="submit" class="btn btn-success">
            <i class="fas fa-save"></i> Simpan
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
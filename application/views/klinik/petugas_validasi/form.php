<div class="content-wrapper px-4 pt-4">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="text-dark fw-bold mb-0">
      <i class="fas <?= isset($petugas) ? 'fa-user-edit' : 'fa-user-plus' ?> me-2 text-primary"></i>
      <?= isset($petugas) ? 'Edit Petugas Validasi' : 'Tambah Petugas Validasi' ?>
    </h3>
    <a href="<?= site_url('klinik/petugas_validasi') ?>" class="btn btn-sm btn-outline-secondary">
      <i class="fas fa-arrow-left me-1"></i> Kembali
    </a>
  </div>
  
  <hr style="border-top: 2px solid #1e3a8a; margin-bottom: 25px;">

  <div class="row justify-content-center">
    <div class="col-12 col-md-8 col-lg-6">
      <div class="card border-0 shadow-sm rounded-3">
        <div class="card-header bg-white border-bottom py-3">
          <h5 class="card-title fw-bold text-secondary mb-0">
            <?= isset($petugas) ? 'Formulir Pembaruan Data' : 'Formulir Pendaftaran Petugas Baru' ?>
          </h5>
        </div>
        <div class="card-body p-4">
          <form action="<?= isset($petugas)
              ? site_url('klinik/petugas_validasi/edit_action/'.$petugas->id_validasi)
              : site_url('klinik/petugas_validasi/create_action') ?>"
            method="post">
            
            <!-- CSRF Token Protection -->
            <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">

            <div class="mb-3">
              <label for="nama_petugas" class="form-label fw-semibold text-muted small mb-1">NAMA PETUGAS <span class="text-danger">*</span></label>
              <div class="input-group">
                <span class="input-group-text bg-light border-end-0"><i class="fas fa-user text-secondary"></i></span>
                <input type="text" id="nama_petugas" name="nama_petugas" class="form-control border-start-0"
                       value="<?= isset($petugas->nama_petugas) ? htmlspecialchars($petugas->nama_petugas) : '' ?>" 
                       placeholder="Masukkan nama lengkap petugas..." required>
              </div>
            </div>

            <div class="mb-4">
              <label for="jabatan" class="form-label fw-semibold text-muted small mb-1">JABATAN</label>
              <div class="input-group">
                <span class="input-group-text bg-light border-end-0"><i class="fas fa-briefcase text-secondary"></i></span>
                <input type="text" id="jabatan" name="jabatan" class="form-control border-start-0"
                       value="<?= isset($petugas->jabatan) ? htmlspecialchars($petugas->jabatan) : '' ?>" 
                       placeholder="Masukkan jabatan petugas...">
              </div>
            </div>

            <div class="d-flex justify-content-between align-items-center pt-2">
              <a href="<?= site_url('klinik/petugas_validasi') ?>" class="btn btn-light border px-4">
                <i class="fas fa-times me-2"></i>Batal
              </a>
              <button type="submit" class="btn btn-success px-4" style="background-color: #0d3b30; border-color: #0d3b30;">
                <i class="fas fa-save me-2"></i>Simpan
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

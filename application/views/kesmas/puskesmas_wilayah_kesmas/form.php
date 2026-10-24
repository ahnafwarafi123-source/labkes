<div class="content-wrapper px-4 pt-4">
  <h3 class="text-dark fw-bold mb-4">
    <?= isset($wilayah->id) && !empty($wilayah->id) ? 'Edit Data Wilayah puskesmas' : 'Tambah Data Wilayah puskesmas' ?>
  </h3>
  <hr style="border-top: 2px solid #1e3a8a; margin-bottom: 20px;">

  <div class="row">
    <div class="col-12 col-md-8 col-lg-6 mx-auto">
      <div class="card shadow-sm border-0 rounded-3 overflow-hidden">
        <div class="card-header bg-light border-0 py-3">
          <h5 class="mb-0 text-secondary" style="font-weight: 500;">
            <i class="fas fa-clinic-medical me-2 text-info"></i>Formulir Wilayah Kerja puskesmas
          </h5>
        </div>
        <div class="card-body bg-white p-4">
          <form action="<?= $action ?>" method="post">
            <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
            <input type="hidden" name="id" value="<?= $wilayah->id ?? '' ?>">

            <div class="mb-3">
              <label for="nama_puskesmas" class="form-label fw-semibold text-dark">Nama puskesmas <span class="text-danger">*</span></label>
              <input type="text" class="form-control px-3 py-2" name="nama_puskesmas" id="nama_puskesmas" value="<?= $wilayah->nama_puskesmas ?? '' ?>" required placeholder="Contoh: Puskesmas Melintang">
            </div>

            <div class="mb-3">
              <label for="kecamatan" class="form-label fw-semibold text-dark">Kecamatan <span class="text-danger">*</span></label>
              <input type="text" class="form-control px-3 py-2" name="kecamatan" id="kecamatan" value="<?= $wilayah->kecamatan ?? '' ?>" required placeholder="Contoh: Rangkui">
            </div>

            <div class="mb-3">
              <label for="kelurahan_list" class="form-label fw-semibold text-dark">Daftar Kelurahan (Wilayah Kerja)</label>
              <textarea class="form-control px-3 py-2" name="kelurahan_list" id="kelurahan_list" rows="4" placeholder="Pisahkan dengan koma. Contoh: Melintang, Pintu Air, Asam"><?= $wilayah->kelurahan_list ?? '' ?></textarea>
              <div class="form-text text-muted mt-2" style="font-size: 12px; line-height: 1.4;">
                <i class="fas fa-info-circle me-1"></i> Gunakan pemisah koma (,) jika ada lebih dari satu kelurahan. Daftar ini menjadi pilihan Kelurahan pada form pendaftaran.
              </div>
            </div>

            <div class="d-flex justify-content-between mt-4">
              <a href="<?= site_url('k-panel/Puskesmas_wilayah_kesmas') ?>" class="btn btn-outline-secondary px-4 py-2 fw-semibold rounded-2">
                <i class="fas fa-arrow-left me-1"></i> Kembali
              </a>
              <button type="submit" class="btn btn-success px-4 py-2 fw-semibold rounded-2">
                <i class="fas fa-save me-1"></i> Simpan Data
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

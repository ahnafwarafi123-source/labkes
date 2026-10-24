<div class="content-wrapper px-4 pt-4">
  <div class="d-flex justify-content-between align-items-end mb-3 flex-wrap gap-2">
    <div>
      <h3 class="text-dark fw-bold text-uppercase mb-1" style="font-size: 24px;"><?= html_escape($title) ?></h3>
      <span class="text-secondary small">Daftar lokasi kerja puskesmas dan cakupan wilayah kelurahan</span>
    </div>
    <a href="<?= site_url('k-panel/Puskesmas_wilayah_kesmas/create') ?>" class="btn btn-primary px-4 py-2 fw-semibold rounded-2 shadow-sm">
      <i class="fas fa-plus me-1"></i> Tambah Data
    </a>
  </div>
  <hr style="border-top: 1px solid #cbd5e1; margin-top: 0; margin-bottom: 24px;">

  <?php if ($this->session->flashdata('success')): ?>
    <div class="alert alert-success alert-dismissible fade show border-0 rounded-3 mb-4" role="alert">
      <?= $this->session->flashdata('success') ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  <?php endif; ?>

  <div class="card shadow-sm w-100 border-0 rounded-3 overflow-hidden mb-5">
    <div class="card-body p-4 bg-white">
      <div class="table-responsive">
        <table class="table table-bordered table-striped table-hover nowrap align-middle mb-0" style="font-size: 14px;">
          <thead class="table-dark">
            <tr>
              <th width="5%" class="text-center py-3">No</th>
              <th width="25%" class="py-3">Nama puskesmas</th>
              <th width="20%" class="py-3">Kecamatan</th>
              <th width="35%" class="py-3">Kelurahan (Wilayah Kerja)</th>
              <th width="15%" class="text-center py-3">Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php $no = 1; foreach ($wilayah as $w): ?>
              <tr>
                <td class="text-center fw-semibold text-secondary py-3"><?= $no++ ?></td>
                <td class="fw-bold text-dark py-3"><?= html_escape($w->nama_puskesmas) ?></td>
                <td class="py-3 text-secondary" style="font-weight: 500;"><?= html_escape($w->kecamatan) ?></td>
                <td class="py-3">
                  <?php
                  if (!empty($w->kelurahan_list)) {
                      $kels = array_map('trim', explode(',', $w->kelurahan_list));
                      foreach ($kels as $kel) {
                          if ($kel !== '') {
                              echo '<span class="badge bg-light text-dark border border-secondary-subtle px-2.5 py-1.5 rounded-pill">' . html_escape($kel) . '</span> ';
                          }
                      }
                  } else {
                      echo '<span class="text-muted small italic">-</span>';
                  }
                  ?>
                </td>
                <td class="text-center py-3">
                  <div class="d-flex justify-content-center gap-1">
                    <a href="<?= site_url('k-panel/Puskesmas_wilayah_kesmas/edit/' . $w->id) ?>" class="btn btn-sm btn-warning text-dark px-3 py-1.5 rounded-2 fw-semibold" title="Edit Data" style="font-size: 13px;">
                      <i class="fas fa-edit me-1"></i> Edit
                    </a>
                    <a href="<?= site_url('k-panel/Puskesmas_wilayah_kesmas/hapus/' . $w->id) ?>" class="btn btn-sm btn-danger px-3 py-1.5 rounded-2 fw-semibold" title="Hapus Data" style="font-size: 13px;" onclick="return confirm('Yakin ingin menghapus data ini?')">
                      <i class="fas fa-trash me-1"></i> Hapus
                    </a>
                  </div>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

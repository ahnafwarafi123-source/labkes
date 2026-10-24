<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card mt-4">
                <div class="card-header">
                    <h3 class="card-title"><?= $title ?></h3>
                </div>
                <div class="card-body">
                    <?php if (validation_errors()): ?>
                        <div class="alert alert-danger" role="alert">
                            <?= validation_errors() ?>
                        </div>
                    <?php endif; ?>

                    <?= form_open(isset($tindakan_sampel['id']) ? 'k-panel/tindakan_sampel/edit/' . $tindakan_sampel['id'] : 'k-panel/tindakan_sampel/add'); ?>
                        <?= form_hidden($this->security->get_csrf_token_name(), $this->security->get_csrf_hash()); ?>
                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama Tindakan Sampel</label>
                            <input type="text" class="form-control" id="nama" name="nama" value="<?= set_value('nama', isset($tindakan_sampel['nama']) ? $tindakan_sampel['nama'] : ''); ?>" required>
                        </div>
                        <a href="<?= site_url('k-panel/tindakan_sampel') ?>" class="btn btn-secondary">Batal</a>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    <?= form_close(); ?>
                </div>
            </div>
        </div>
    </div>
</div>

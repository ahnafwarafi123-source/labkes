<?php defined('BASEPATH') OR exit('No direct script access allowed'); 
$is_edit = ($mode === 'edit');
$action_url = $is_edit ? site_url('k-panel/master_pemeriksaan/update/'.$master['id_master_pemeriksaan']) : site_url('k-panel/master_pemeriksaan/store');
?>

<div class="container-fluid py-4 ui-polish-container">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-bottom-0 pt-4 pb-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0 text-primary">
                            <i class="fas <?= $is_edit ? 'fa-edit' : 'fa-plus-circle' ?> me-2"></i><?= htmlspecialchars($title) ?>
                        </h4>
                        <a href="<?= site_url('k-panel/master_pemeriksaan') ?>" class="btn btn-sm btn-outline-secondary">
                            <i class="fas fa-arrow-left me-1"></i> Kembali
                        </a>
                    </div>
                </div>
                <div class="card-body p-4">
                    <form action="<?= $action_url ?>" method="POST" id="formMaster">
                        <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
                        
                        <h6 class="text-uppercase text-muted fw-bold mb-3 border-bottom pb-2">Informasi Dasar</h6>
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Kategori <span class="text-danger">*</span></label>
                                <select class="form-select" name="kategori" id="inpKategori" required>
                                    <option value="">-- Pilih Kategori --</option>
                                    <?php foreach($kategori_list as $k => $v): ?>
                                        <option value="<?= $k ?>" <?= ($is_edit && $master['kode'] === $k) ? 'selected' : '' ?>><?= $v ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Kelompok <span class="text-danger">*</span></label>
                                <select class="form-select" name="kelompok" id="inpKelompok" required>
                                    <option value="">-- Pilih Kelompok --</option>
                                    <!-- Diisi via JS berdasarkan Kategori -->
                                </select>
                            </div>
                            <div class="col-md-8">
                                <label class="form-label fw-semibold">Nama Pemeriksaan <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="nama_pemeriksaan" id="inpNama" value="<?= htmlspecialchars($master['nama_pemeriksaan'] ?? '') ?>" required>
                                <div class="form-text" id="dupWarning" style="display:none; color:var(--bs-danger);"><i class="fas fa-times-circle me-1"></i>Nama ini sudah ada di kelompok yang sama!</div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Satuan</label>
                                <input type="text" class="form-control" name="satuan" value="<?= htmlspecialchars($master['satuan'] ?? '') ?>" placeholder="misal: mg/L, CFU/ml">
                            </div>
                        </div>

                        <h6 class="text-uppercase text-muted fw-bold mb-3 border-bottom pb-2">Konfigurasi Baku Mutu</h6>
                        <div class="row g-3 mb-4">

                            <div class="col-md-12">
                                <label class="form-label fw-semibold">Teks Baku Mutu (Ditampilkan di Laporan) <span class="text-danger">*</span></label>
                                <input type="text" class="form-control font-monospace" name="baku_mutu" id="inpBakuMutu" value="<?= htmlspecialchars($master['baku_mutu'] ?? '') ?>" required>
                                <div class="form-text">Teks ini akan muncul secara statis di dokumen Laporan Hasil Uji (PDF).</div>
                            </div>
                        </div>

                        <h6 class="text-uppercase text-muted fw-bold mb-3 border-bottom pb-2">Lainnya</h6>
                        <div class="row g-3 mb-4">
                            <div class="col-md-8">
                                <label class="form-label fw-semibold">Metode Pemeriksaan</label>
                                <input type="text" class="form-control" name="metode" value="<?= htmlspecialchars($master['metode'] ?? '') ?>">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Urutan</label>
                                <input type="number" class="form-control" name="urutan" value="<?= htmlspecialchars($master['urutan'] ?? '') ?>" placeholder="Otomatis di akhir">
                            </div>
                        </div>

                        <div class="d-flex justify-content-end pt-3 border-top">
                            <button type="submit" class="btn btn-primary px-4" id="btnSubmit">
                                <i class="fas fa-save me-2"></i><?= $is_edit ? 'Simpan Perubahan' : 'Simpan Parameter Baru' ?>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var kelompokMap = <?= json_encode($kelompok_map) ?>;
    var inpKategori = document.getElementById('inpKategori');
    var inpKelompok = document.getElementById('inpKelompok');
    
    // Initial value for edit
    var currentKelompok = "<?= $is_edit ? addslashes($master['kelompok_pemeriksaan']) : '' ?>";

    function updateKelompok() {
        var kat = inpKategori.value;
        inpKelompok.innerHTML = '<option value="">-- Pilih Kelompok --</option>';
        if (kat && kelompokMap[kat]) {
            for (var k = 0; k < kelompokMap[kat].length; k++) {
                var kel = kelompokMap[kat][k];
                var opt = document.createElement('option');
                opt.value = kel;
                opt.textContent = kel;
                if (kel === currentKelompok) opt.selected = true;
                inpKelompok.appendChild(opt);
            }
        }
    }
    inpKategori.addEventListener('change', updateKelompok);
    updateKelompok();



    // Duplicate Check using AJAX
    var inpNama = document.getElementById('inpNama');
    var dupWarning = document.getElementById('dupWarning');
    var btnSubmit = document.getElementById('btnSubmit');
    var myId = <?= $is_edit ? $master['id_master_pemeriksaan'] : 0 ?>;

    var debounceTimer;
    function checkDuplicate() {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(function() {
            var nama = inpNama.value.trim();
            var kat = inpKategori.value;
            var kel = inpKelompok.value;
            if (!nama || !kat || !kel) return;

            fetch("<?= site_url('k-panel/master_pemeriksaan/check_duplicate') ?>?nama=" + encodeURIComponent(nama) + "&kategori=" + encodeURIComponent(kat) + "&kelompok=" + encodeURIComponent(kel) + "&exclude_id=" + myId, { 
                credentials: 'same-origin',
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(function(res) { return res.json(); })
            .then(function(data) {
                if (data.duplicate) {
                    dupWarning.style.display = 'block';
                    btnSubmit.disabled = true;
                    inpNama.classList.add('is-invalid');
                } else {
                    dupWarning.style.display = 'none';
                    btnSubmit.disabled = false;
                    inpNama.classList.remove('is-invalid');
                }
            })
            .catch(function(err) { console.error(err); });
        }, 500);
    }

    inpNama.addEventListener('input', checkDuplicate);
    inpKelompok.addEventListener('change', checkDuplicate);
    inpKategori.addEventListener('change', checkDuplicate);
});
</script>

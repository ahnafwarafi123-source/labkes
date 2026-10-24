<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<style>
/* ===== ADMIN EDIT SURVEY - PREMIUM DESIGN ===== */
:root {
    --edit-primary: #4f46e5;
    --edit-primary-soft: rgba(79, 70, 229, 0.08);
    --edit-success: #10b981;
    --edit-danger: #ef4444;
    --edit-text-dark: #1e293b;
    --edit-text-muted: #64748b;
    --edit-border: #e2e8f0;
    --edit-bg: #f8fafc;
    --edit-card: #ffffff;
    --edit-shadow: 0 10px 30px rgba(0,0,0,0.04);
}

.edit-wrapper {
    background-color: var(--edit-bg);
    border-radius: 24px;
    padding: 2rem;
    box-shadow: inset 0 2px 4px rgba(0,0,0,0.02);
}

.edit-header-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
}

.edit-title-group {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.edit-title-icon {
    width: 48px; height: 48px;
    background: linear-gradient(135deg, var(--edit-primary), #6366f1);
    color: white;
    border-radius: 14px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.5rem;
    box-shadow: 0 4px 15px rgba(79, 70, 229, 0.3);
}

.edit-title-text {
    margin: 0;
    font-weight: 800;
    color: var(--edit-text-dark);
    font-size: 1.4rem;
}

.edit-subtitle {
    margin: 0;
    font-size: 0.85rem;
    color: var(--edit-text-muted);
    font-weight: 500;
}

.edit-card {
    background: var(--edit-card);
    border: none;
    border-radius: 20px;
    box-shadow: var(--edit-shadow);
    overflow: hidden;
    margin-bottom: 1.5rem;
}

.edit-card-header {
    background: #fff;
    border-bottom: 1px solid var(--edit-border);
    padding: 1.5rem 2rem;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.edit-card-header-icon {
    color: var(--edit-primary);
    font-size: 1.2rem;
}

.edit-card-header-title {
    margin: 0;
    font-weight: 700;
    color: var(--edit-text-dark);
    font-size: 1.1rem;
}

.edit-card-body {
    padding: 2rem;
}

/* Form Controls */
.form-label-edit {
    font-weight: 600;
    color: var(--edit-text-dark);
    font-size: 0.85rem;
    margin-bottom: 0.5rem;
    display: flex;
    align-items: center;
    gap: 0.4rem;
}

.form-control-edit, .form-select-edit {
    background: #f8fafc;
    border: 1px solid var(--edit-border);
    border-radius: 12px;
    padding: 0.75rem 1rem;
    font-size: 0.95rem;
    font-weight: 500;
    color: var(--edit-text-dark);
    transition: all 0.2s ease;
    width: 100%;
}

.form-control-edit:focus, .form-select-edit:focus {
    background: #fff;
    border-color: var(--edit-primary);
    box-shadow: 0 0 0 4px var(--edit-primary-soft);
    outline: none;
}

.form-select-edit {
    appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%2364748b' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 1rem center;
    background-size: 1em;
    padding-right: 2.5rem;
}

/* Question grid */
.q-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
    gap: 1.25rem;
}

.q-box {
    background: #f8fafc;
    border: 1px solid var(--edit-border);
    border-radius: 16px;
    padding: 1.25rem;
    transition: all 0.2s ease;
}

.q-box:hover {
    border-color: #cbd5e1;
    background: #f1f5f9;
}

.q-title {
    font-size: 0.85rem;
    font-weight: 700;
    color: var(--edit-text-dark);
    margin-bottom: 1rem;
    line-height: 1.4;
    display: flex;
    align-items: flex-start;
    gap: 0.5rem;
}

.q-badge {
    background: linear-gradient(135deg, var(--edit-primary), #6366f1);
    color: white;
    font-size: 0.7rem;
    font-weight: 800;
    padding: 0.2rem 0.5rem;
    border-radius: 6px;
    margin-top: 0.1rem;
}

.radio-group {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.radio-item {
    position: relative;
}

.radio-input {
    position: absolute;
    opacity: 0;
    cursor: pointer;
}

.radio-label {
    display: flex;
    align-items: center;
    padding: 0.6rem 1rem;
    background: #fff;
    border: 1px solid var(--edit-border);
    border-radius: 10px;
    font-size: 0.85rem;
    font-weight: 500;
    color: var(--edit-text-muted);
    cursor: pointer;
    transition: all 0.2s ease;
}

.radio-label:hover {
    border-color: #cbd5e1;
}

.radio-input:checked + .radio-label {
    background: var(--edit-primary-soft);
    border-color: var(--edit-primary);
    color: var(--edit-primary);
    font-weight: 600;
}

.radio-input:checked + .radio-label::before {
    content: '✓';
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 18px;
    height: 18px;
    background: var(--edit-primary);
    color: white;
    border-radius: 50%;
    font-size: 0.6rem;
    margin-right: 0.5rem;
}

/* Action Buttons */
.edit-actions {
    display: flex;
    gap: 1rem;
    padding-top: 1rem;
    border-top: 1px solid var(--edit-border);
    margin-top: 2rem;
}

.btn-edit-save {
    background: linear-gradient(135deg, var(--edit-success), #059669);
    color: white;
    border: none;
    padding: 0.8rem 2rem;
    border-radius: 12px;
    font-weight: 700;
    font-size: 0.95rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    transition: all 0.2s ease;
    box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3);
}

.btn-edit-save:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(16, 185, 129, 0.4);
    color: white;
}

.btn-edit-cancel {
    background: #fff;
    color: var(--edit-text-muted);
    border: 1px solid var(--edit-border);
    padding: 0.8rem 2rem;
    border-radius: 12px;
    font-weight: 600;
    font-size: 0.95rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    transition: all 0.2s ease;
}

.btn-edit-cancel:hover {
    background: #f1f5f9;
    color: var(--edit-text-dark);
}
</style>

<div class="container-fluid p-4">
    <div class="edit-wrapper">
        
        <div class="edit-header-row">
            <div class="edit-title-group">
                <div class="edit-title-icon">
                    <i class="bi bi-pencil-square"></i>
                </div>
                <div>
                    <h2 class="edit-title-text"><?php echo $title; ?></h2>
                    <p class="edit-subtitle">
                        Edit Data Survei &mdash; ID: <span class="badge bg-secondary rounded-pill">#<?php echo $item['id_permintaan']; ?></span>
                        <?php if ($item['id_permintaan']): ?>
                            <span class="badge bg-primary rounded-pill ms-1">Permintaan #<?php echo $item['id_permintaan']; ?></span>
                        <?php endif; ?>
                    </p>
                </div>
            </div>
            <a href="<?php echo base_url('k-panel/survei'); ?>" class="btn-edit-cancel text-decoration-none">
                <i class="bi bi-arrow-left"></i> Kembali ke Daftar
            </a>
        </div>

        <form action="<?php echo base_url('k-panel/survei/update/' . $item['id_permintaan']); ?>" method="POST">
            
            <!-- SECTION 1: PROFIL RESPONDEN -->
            <div class="edit-card">
                <div class="edit-card-header">
                    <i class="bi bi-person-lines-fill edit-card-header-icon"></i>
                    <h3 class="edit-card-header-title">I. Profil Responden</h3>
                </div>
                <div class="edit-card-body">
                    <div class="row g-4">
                        <div class="col-md-3">
                            <label class="form-label-edit">
                                <i class="bi bi-gender-ambiguous text-muted"></i> Jenis Kelamin <span class="text-danger">*</span>
                            </label>
                            <select name="jenis_kelamin" class="form-select-edit" required>
                                <option value="L" <?php echo $item['jenis_kelamin'] === 'L' ? 'selected' : ''; ?>>Laki-laki (L)</option>
                                <option value="P" <?php echo $item['jenis_kelamin'] === 'P' ? 'selected' : ''; ?>>Perempuan (P)</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label-edit">
                                <i class="bi bi-calendar-event text-muted"></i> Usia (Tahun) <span class="text-danger">*</span>
                            </label>
                            <input type="number" name="usia" class="form-control-edit" value="<?php echo $item['usia']; ?>" required min="10" max="100">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label-edit">
                                <i class="bi bi-book text-muted"></i> Pendidikan <span class="text-danger">*</span>
                            </label>
                            <select name="pendidikan" class="form-select-edit" required>
                                <?php foreach (['SD','SMP','SMA','D3','S1','S2','S3'] as $p): ?>
                                <option value="<?php echo $p; ?>" <?php echo $item['pendidikan'] === $p ? 'selected' : ''; ?>><?php echo $p; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label-edit">
                                <i class="bi bi-briefcase text-muted"></i> Pekerjaan <span class="text-danger">*</span>
                            </label>
                            <select name="pekerjaan" class="form-select-edit" required>
                                <?php foreach (['PNS','TNI','POLRI','SWASTA','WIRAUSAHA','LAINNYA'] as $pek): ?>
                                <option value="<?php echo $pek; ?>" <?php echo $item['pekerjaan'] === $pek ? 'selected' : ''; ?>><?php echo $pek; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-edit">
                                <i class="bi bi-file-earmark-medical text-muted"></i> Jenis Layanan <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="jenis_layanan" class="form-control-edit" value="<?php echo htmlspecialchars($item['jenis_layanan'] ?? ''); ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-edit">
                                <i class="bi bi-clock text-muted"></i> Jam Survei
                            </label>
                            <select name="jam_survei" class="form-select-edit">
                                <option value="">-- Pilih --</option>
                                <option value="08.00 - 12.00" <?php echo ($item['jam_survei'] === '08.00 - 12.00') ? 'selected' : ''; ?>>08.00 - 12.00</option>
                                <option value="13.00 - 15.00" <?php echo ($item['jam_survei'] === '13.00 - 15.00') ? 'selected' : ''; ?>>13.00 - 15.00</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SECTION 2: PENILAIAN -->
            <div class="edit-card">
                <div class="edit-card-header">
                    <i class="bi bi-star-fill edit-card-header-icon" style="color: #f59e0b;"></i>
                    <h3 class="edit-card-header-title">II. Penilaian 9 Unsur Pelayanan (Skala 1–4)</h3>
                </div>
                <div class="edit-card-body">
                    <?php
                    $questions = [
                        'q1' => ['text' => 'Kesesuaian persyaratan pelayanan', 'opts' => ['Tidak sesuai', 'Kurang sesuai', 'Sesuai', 'Sangat sesuai']],
                        'q2' => ['text' => 'Kemudahan prosedur pelayanan', 'opts' => ['Tidak mudah', 'Kurang mudah', 'Mudah', 'Sangat mudah']],
                        'q3' => ['text' => 'Kecepatan waktu pelayanan', 'opts' => ['Tidak cepat', 'Kurang cepat', 'Cepat', 'Sangat cepat']],
                        'q4' => ['text' => 'Kewajaran biaya/tarif', 'opts' => ['Sangat mahal', 'Cukup mahal', 'Murah', 'Gratis']],
                        'q5' => ['text' => 'Kesesuaian produk pelayanan (standar vs hasil)', 'opts' => ['Tidak sesuai', 'Kurang sesuai', 'Sesuai', 'Sangat sesuai']],
                        'q6' => ['text' => 'Kompetensi/kemampuan petugas', 'opts' => ['Tidak kompeten', 'Kurang kompeten', 'Kompeten', 'Sangat kompeten']],
                        'q7' => ['text' => 'Perilaku petugas (kesopanan dan keramahan)', 'opts' => ['Tidak sopan dan ramah', 'Kurang sopan dan ramah', 'Sopan dan ramah', 'Sangat sopan dan ramah']],
                        'q8' => ['text' => 'Kualitas sarana dan prasarana', 'opts' => ['Buruk', 'Cukup', 'Baik', 'Sangat baik']],
                        'q9' => ['text' => 'Penanganan pengaduan pengguna layanan', 'opts' => ['Tidak ada', 'Ada tetapi tidak berfungsi', 'Berfungsi kurang maksimal', 'Dikelola dengan baik']],
                    ];
                    ?>
                    
                    <div class="q-grid">
                        <?php foreach ($questions as $key => $q): ?>
                        <div class="q-box">
                            <div class="q-title">
                                <span class="q-badge"><?php echo strtoupper($key); ?></span>
                                <span><?php echo $q['text']; ?></span>
                            </div>
                            <div class="radio-group">
                                <?php foreach ($q['opts'] as $idx => $opt):
                                    $val = $idx + 1;
                                    $id  = $key . '_edit_' . $val;
                                    $isChecked = ((int)$item[$key] === $val);
                                ?>
                                <div class="radio-item">
                                    <input class="radio-input" type="radio" name="<?php echo $key; ?>"
                                           id="<?php echo $id; ?>" value="<?php echo $val; ?>"
                                           <?php echo $isChecked ? 'checked' : ''; ?> required>
                                    <label class="radio-label" for="<?php echo $id; ?>">
                                        <?php echo $val; ?>. <?php echo $opt; ?>
                                    </label>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <!-- SECTION 3: SARAN -->
            <div class="edit-card">
                <div class="edit-card-header">
                    <i class="bi bi-chat-text-fill edit-card-header-icon" style="color: #06b6d4;"></i>
                    <h3 class="edit-card-header-title">III. Saran & Masukan</h3>
                </div>
                <div class="edit-card-body">
                    <label class="form-label-edit mb-2">Komentar, Saran, atau Kritik (Opsional)</label>
                    <textarea name="komentar_saran" class="form-control-edit" rows="4" style="resize: vertical; min-height: 100px;" placeholder="Tuliskan saran atau masukan..."><?php echo htmlspecialchars($item['komentar_saran'] ?? ''); ?></textarea>
                    
                    <div class="edit-actions">
                        <button type="submit" class="btn-edit-save">
                            <i class="bi bi-check2-circle"></i> Simpan Perubahan
                        </button>
                        <a href="<?php echo base_url('k-panel/survei'); ?>" class="btn-edit-cancel text-decoration-none">
                            <i class="bi bi-x-circle"></i> Batal
                        </a>
                    </div>
                </div>
            </div>

        </form>
    </div>
</div>

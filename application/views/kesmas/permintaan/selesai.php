<?php
/**
 * Pendaftaran Selesai View
 * 
 * @var string $title - Page title
 * @var array $permintaan - Pendaftaran data array
 * @var int $id_permintaan - Pendaftaran ID
 * @var bool $survey_exists - Whether survey exists
 * @var string $survey_link - Survey form link
 */
?>
<div class="container my-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <!-- Success Message -->
            <div class="alert alert-success" role="alert">
                <h4 class="alert-heading"><i class="bi bi-check-circle-fill"></i> Pendaftaran Sample Berhasil!</h4>
                <p>Terima kasih telah mendaftarkan sample Anda. Proses pendaftaran telah selesai.</p>
                <hr>
                <p class="mb-0">Silakan berikan penilaian dan ulasan Anda terhadap layanan kami.</p>
            </div>

            <!-- Permintaan Details -->
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="bi bi-info-circle"></i> Detail Permintaan</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>No. Registrasi:</strong> <?php echo htmlspecialchars($permintaan['no_registrasi']); ?></p>
                            <p><strong>Nama Sample:</strong> <?php echo htmlspecialchars($permintaan['nama_sampel']); ?></p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Jenis:</strong> <?php echo htmlspecialchars($permintaan['jenis_sampel']); ?></p>
                        </div>
                    </div>
                    <p><strong>Tanggal Daftar:</strong> <?php 
                        $tgl = $permintaan['tgl_registrasi'] ?? $permintaan['tgl_permintaan'] ?? '';
                        echo !empty($tgl) ? date('d/m/Y H:i', strtotime($tgl)) : '-';
                    ?></p>
                </div>
            </div>

            <!-- Actions -->
            <div class="text-center mt-4">
                <?php if (isset($permintaan['status_kelayakan']) && $permintaan['status_kelayakan'] === 'Layak'): ?>
                    <a href="<?php echo site_url('k-panel/uji/input/' . $permintaan_id); ?>" class="btn btn-primary me-2">
                        <i class="bi bi-pencil-square"></i> Lanjut ke Input Hasil
                    </a>
                <?php endif; ?>
                <a href="<?php echo site_url('k-panel/form_permintaan_kesmas'); ?>" class="btn btn-secondary">
                    <i class="bi bi-list"></i> Kembali ke Daftar
                </a>
            </div>
        </div>
    </div>
</div>
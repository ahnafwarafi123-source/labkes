<?php
/**
 * View halaman sukses pendaftaran
 * @var string $survey_link - URL link untuk mengisi survei kepuasan pelanggan
 * @var bool $survey_exists - Flag untuk menampilkan survei atau tidak
 */
?>
<div class="container mt-5 text-center mb-5 animate-fade-up" style="max-width: 600px;">
    <div class="card border-0" style="border-radius: 28px; background: var(--glass); border: 1px solid rgba(255,255,255,0.9); backdrop-filter: blur(16px); box-shadow: var(--shadow);">
        <div class="card-body p-5">
            <div style="width: 88px; height: 88px; border-radius: 50%; background: linear-gradient(135deg, rgba(34,197,94,.2) 0%, rgba(34,197,94,.05) 100%); color: #16a34a; border: 2px solid rgba(34,197,94,.2); display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem; box-shadow: 0 10px 25px rgba(34,197,94,.15);">
                <i class="bi bi-check-lg" style="font-size: 3rem; line-height: 1;"></i>
            </div>
            
            <h2 style="font-weight: 900; color: var(--ink); letter-spacing: -0.5px;">Pendaftaran Berhasil!</h2>
            
            <div class="mt-4 p-4 mb-4" style="background: linear-gradient(135deg, rgba(245,158,11,.1) 0%, rgba(245,158,11,.05) 100%); border-radius: 20px; border: 1px solid rgba(245,158,11,.2);">
                <p class="text-muted small mb-1" style="font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">Status Registrasi</p>
                <h4 style="font-weight: 800; color: #d97706; margin: 0; letter-spacing: 0.5px;">
                    <i class="bi bi-hourglass-split me-2"></i>Menunggu Konfirmasi Petugas
                </h4>
                <p class="text-muted small mt-2 mb-0" style="opacity: 0.9;">Nomor registrasi resmi akan diterbitkan oleh petugas setelah Anda menyerahkan sampel fisik ke laboratorium.</p>
            </div>

            <p class="text-muted mb-5" style="font-size: 1.1rem; line-height: 1.6;">
                Terima kasih, data formulir sampel Anda telah aman tersimpan di sistem kami.
            </p>


            <div class="d-flex flex-column gap-3">
                <a href="<?= site_url('kesmas') ?>" class="btn btn-primary w-100" style="border-radius: 16px; font-weight: 800; padding: 1rem; box-shadow: 0 8px 20px rgba(13,110,253,.2); background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%); border: none;">
                    <i class="bi bi-house-door-fill me-2"></i>Kembali ke Halaman Utama
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Script AJAX untuk submit survei sudah tidak diperlukan di halaman ini -->

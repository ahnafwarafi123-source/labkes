<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * View untuk detail kuesioner SKM (Survei Kepuasan Masyarakat)
 * @var array $survei - Data survei kepuasan dengan fields: tgl_survei, jam_survei, jenis_kelamin, usia, pendidikan, pekerjaan, jenis_layanan, q1-q9
 */
?>
<div class="container-fluid mb-5 animate-fade-up">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <h3 class="fw-bolder mb-0"><i class="bi bi-file-earmark-text text-primary me-2"></i>Detail Kuesioner SKM</h3>
        <a href="<?= site_url('k-panel/survei') ?>" class="btn btn-light border fw-bold text-secondary rounded-pill px-4">
            <i class="bi bi-arrow-left me-2"></i>Kembali ke Daftar
        </a>
    </div>

    <div class="row g-4">
        <!-- Profil Responden -->
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 20px;">
                <div class="card-header bg-white border-0 pt-4 pb-0">
                    <h6 class="fw-bold text-uppercase text-muted mb-0" style="letter-spacing: 1px;">Profil Responden</h6>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item px-0 d-flex justify-content-between align-items-center">
                            <span class="text-muted">Tanggal Survei</span>
                            <span class="fw-medium"><?= date('d M Y', strtotime($survei['tgl_survei'])) ?></span>
                        </li>
                        <li class="list-group-item px-0 d-flex justify-content-between align-items-center">
                            <span class="text-muted">Jam Survei</span>
                            <span class="fw-medium"><?= $survei['jam_survei'] ?: '-' ?></span>
                        </li>
                        <li class="list-group-item px-0 d-flex justify-content-between align-items-center">
                            <span class="text-muted">Jenis Kelamin</span>
                            <span class="fw-medium"><?= $survei['jenis_kelamin'] === 'L' ? 'Laki-laki (L)' : ($survei['jenis_kelamin'] === 'P' ? 'Perempuan (P)' : '-') ?></span>
                        </li>
                        <li class="list-group-item px-0 d-flex justify-content-between align-items-center">
                            <span class="text-muted">Usia</span>
                            <span class="fw-medium"><?= $survei['usia'] ?: '-' ?> Tahun</span>
                        </li>
                        <li class="list-group-item px-0 d-flex justify-content-between align-items-center">
                            <span class="text-muted">Pendidikan</span>
                            <span class="fw-medium"><?= $survei['pendidikan'] ?: '-' ?></span>
                        </li>
                        <li class="list-group-item px-0 d-flex justify-content-between align-items-center">
                            <span class="text-muted">Pekerjaan</span>
                            <span class="fw-medium"><?= $survei['pekerjaan'] ?: '-' ?></span>
                        </li>
                        <li class="list-group-item px-0 border-bottom-0 mt-3">
                            <span class="text-muted d-block mb-2 text-center text-uppercase fw-bold" style="font-size:0.8rem;">Jenis Layanan</span>
                            <div class="fw-bold p-3 bg-light rounded-3 text-center text-primary" style="font-size: 1.1rem; border: 1px dashed rgba(13,110,253,0.2);">
                                <?= $survei['jenis_layanan'] ?: '-' ?>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Detail Jawaban -->
        <div class="col-md-8">
            <div class="card border-0 shadow-sm mb-4" style="border-radius: 20px;">
                <div class="card-header bg-white border-0 pt-4 pb-0 d-flex justify-content-between align-items-center">
                    <h6 class="fw-bold text-uppercase text-muted mb-0" style="letter-spacing: 1px;">Hasil Penilaian Unsur (Skala 1-4)</h6>
                    <?php 
                        $total = $survei['q1'] + $survei['q2'] + $survei['q3'] + $survei['q4'] + $survei['q5'] + $survei['q6'] + $survei['q7'] + $survei['q8'] + $survei['q9'];
                        $rata = $total / 9;
                    ?>
                    <span class="badge bg-primary rounded-pill px-3 py-2 fs-6 shadow-sm">Rata-rata: <?= number_format($rata, 2) ?></span>
                </div>
                <div class="card-body">
                    <?php
                    $q_text = [
                        1 => 'Kesesuaian persyaratan pelayanan',
                        2 => 'Kemudahan prosedur pelayanan',
                        3 => 'Kecepatan waktu pelayanan',
                        4 => 'Kewajaran biaya/tarif',
                        5 => 'Kesesuaian produk pelayanan (standar vs hasil)',
                        6 => 'Kompetensi/kemampuan petugas',
                        7 => 'Perilaku petugas (kesopanan dan keramahan)',
                        8 => 'Kualitas sarana dan prasarana',
                        9 => 'Penanganan pengaduan pengguna layanan'
                    ];

                    $options = [
                        1 => ['Tidak Baik / Tidak Sesuai', 'danger'],
                        2 => ['Kurang Baik / Kurang Sesuai', 'warning'],
                        3 => ['Baik / Sesuai', 'info'],
                        4 => ['Sangat Baik / Sangat Sesuai', 'success']
                    ];
                    ?>
                    
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light text-muted small">
                                <tr>
                                    <th width="5%" class="text-center rounded-start">No</th>
                                    <th width="50%">Unsur Pelayanan</th>
                                    <th width="15%" class="text-center">Skor</th>
                                    <th width="30%" class="rounded-end">Interpretasi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php for($i=1; $i<=9; $i++): 
                                    $skor = (int)$survei['q'.$i];
                                    $opt = $options[$skor] ?? ['Belum Dinilai', 'secondary'];
                                ?>
                                <tr>
                                    <td class="text-center fw-medium text-muted">Q<?= $i ?></td>
                                    <td class="fw-medium text-dark"><?= $q_text[$i] ?></td>
                                    <td class="text-center">
                                        <span class="badge bg-<?= $opt[1] ?> rounded-circle" style="width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center; font-size: 1.1rem; box-shadow: 0 4px 10px rgba(var(--bs-<?= $opt[1] ?>-rgb), 0.3);">
                                            <?= $skor ?>
                                        </span>
                                    </td>
                                    <td class="small fw-medium text-<?= $opt[1] ?>"><?= $opt[0] ?></td>
                                </tr>
                                <?php endfor; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Saran dan Masukan -->
            <div class="card border-0 shadow-sm" style="border-radius: 20px;">
                <div class="card-header bg-white border-0 pt-4 pb-0">
                    <h6 class="fw-bold text-uppercase text-muted mb-0" style="letter-spacing: 1px;"><i class="bi bi-chat-quote-fill me-2 text-warning"></i>Saran & Masukan</h6>
                </div>
                <div class="card-body">
                    <div class="p-4 rounded-4 fst-italic shadow-sm" style="background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%); color: #334155; font-size: 1.1rem; line-height: 1.7; border: 1px solid rgba(15,23,42,0.05);">
                        "<?= htmlspecialchars($survei['komentar_saran'] ?: '- Responden tidak memberikan komentar atau saran tambahan -') ?>"
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
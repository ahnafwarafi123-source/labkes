<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="container-fluid mb-5 animate-fade-up">
    <!-- HERO -->
    <div class="d-flex align-items-center justify-content-between mb-4">
        <h3 class="fw-bolder mb-0">
            <i class="bi bi-calendar-range text-primary me-2"></i>
            Laporan Survei Berdasarkan Tanggal
        </h3>
        <a href="<?= site_url('k-panel/survei/laporan') ?>" class="btn btn-light border fw-bold text-secondary rounded-pill px-4">
            <i class="bi bi-arrow-left me-2"></i>Kembali ke Laporan
        </a>
    </div>

    <!-- Filter Form -->
    <div class="card border-0 shadow-sm mb-4" style="border-radius: 20px;">
        <div class="card-body p-4">
            <form method="GET" action="<?= site_url('k-panel/survei/laporan_by_date') ?>" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label fw-bold">Tanggal Mulai</label>
                    <input type="date" name="start_date" class="form-control" value="<?= isset($start_date) ? htmlspecialchars($start_date) : '' ?>" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-bold">Tanggal Akhir</label>
                    <input type="date" name="end_date" class="form-control" value="<?= isset($end_date) ? htmlspecialchars($end_date) : '' ?>" required>
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-primary w-100 fw-bold rounded-pill">
                        <i class="bi bi-search me-2"></i>Tampilkan Data
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Hasil -->
    <?php if (isset($survei)): ?>
        <!-- Ringkasan -->
        <?php if (!empty($survei)):
            $total_responden = count($survei);
            $grand_total = 0;
            foreach ($survei as $row) {
                for ($i = 1; $i <= 9; $i++) {
                    $grand_total += (int)$row['q' . $i];
                }
            }
            $ikm_nilai = ($total_responden > 0) ? round(($grand_total / ($total_responden * 9)) * 25, 2) : 0;
            $mutu = 'TIDAK BAIK'; $mutu_color = 'danger';
            if ($ikm_nilai >= 88.31)      { $mutu = 'SANGAT BAIK';  $mutu_color = 'success'; }
            elseif ($ikm_nilai >= 76.61)  { $mutu = 'BAIK';         $mutu_color = 'primary'; }
            elseif ($ikm_nilai >= 65.00)  { $mutu = 'KURANG BAIK';  $mutu_color = 'warning'; }
        ?>
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="card border-0 shadow-sm text-center p-4" style="border-radius:20px;">
                    <div class="fw-bold text-muted small text-uppercase mb-2" style="letter-spacing:.5px;">Total Responden</div>
                    <h2 class="fw-bolder" style="font-size:2.5rem;"><?= $total_responden ?></h2>
                    <div class="text-muted small">Periode: <?= date('d M Y', strtotime($start_date)) ?> &mdash; <?= date('d M Y', strtotime($end_date)) ?></div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm text-center p-4" style="border-radius:20px;">
                    <div class="fw-bold text-muted small text-uppercase mb-2" style="letter-spacing:.5px;">Skor IKM</div>
                    <h2 class="fw-bolder text-<?= $mutu_color ?>" style="font-size:2.5rem;"><?= number_format($ikm_nilai, 2) ?></h2>
                    <div class="text-muted small">Skala Maksimal 100</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm text-center p-4" style="border-radius:20px;">
                    <div class="fw-bold text-muted small text-uppercase mb-2" style="letter-spacing:.5px;">Mutu Pelayanan</div>
                    <h2 class="fw-bolder text-<?= $mutu_color ?>" style="font-size:2rem;"><?= $mutu ?></h2>
                    <div class="text-muted small">Kategori Permenpan RB</div>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Tabel Data -->
        <div class="card border-0 shadow-sm" style="border-radius:20px; overflow:hidden;">
            <div class="card-header bg-white border-0 pt-4 pb-2 px-4">
                <h6 class="fw-bold text-uppercase text-muted mb-0" style="letter-spacing:1px;">
                    <i class="bi bi-table me-2 text-primary"></i>
                    Daftar Survei (<?= count($survei) ?> data)
                </h6>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead style="background:#f8fafc;">
                        <tr>
                            <th class="px-4 py-3 text-muted small fw-bold text-uppercase" style="letter-spacing:.5px;">#</th>
                            <th class="py-3 text-muted small fw-bold text-uppercase">Tanggal</th>
                            <th class="py-3 text-muted small fw-bold text-uppercase">Profil Responden</th>
                            <th class="py-3 text-muted small fw-bold text-uppercase">Jenis Layanan</th>
                            <th class="py-3 text-center text-muted small fw-bold text-uppercase">Skor Rata-rata</th>
                            <th class="py-3 text-muted small fw-bold text-uppercase">Komentar</th>
                            <th class="py-3 text-center text-muted small fw-bold text-uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($survei)): ?>
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                                Tidak ada data survei pada rentang tanggal ini.
                            </td>
                        </tr>
                        <?php else: ?>
                        <?php foreach ($survei as $i => $s):
                            $total = $s['q1']+$s['q2']+$s['q3']+$s['q4']+$s['q5']+$s['q6']+$s['q7']+$s['q8']+$s['q9'];
                            $rata  = $total / 9;
                            $color = $rata >= 3.5 ? 'success' : ($rata >= 2.5 ? 'warning' : 'danger');
                        ?>
                        <tr>
                            <td class="px-4 fw-medium text-muted"><?= $i + 1 ?></td>
                            <td class="fw-medium"><?= date('d M Y', strtotime($s['tgl_survei'])) ?></td>
                            <td class="small">
                                <?= $s['usia'] ?> Thn, <?= $s['jenis_kelamin'] === 'L' ? 'Laki-laki' : 'Perempuan' ?><br>
                                <span class="text-muted"><?= $s['pekerjaan'] ?></span>
                            </td>
                            <td class="small"><?= htmlspecialchars($s['jenis_layanan'] ?? '-') ?></td>
                            <td class="text-center">
                                <span class="badge bg-<?= $color ?> rounded-pill px-3 py-2"><?= number_format($rata, 2) ?> / 4.00</span>
                            </td>
                            <td class="small text-muted"><?= strlen($s['komentar_saran']) > 60 ? htmlspecialchars(substr($s['komentar_saran'], 0, 60)) . '...' : htmlspecialchars($s['komentar_saran'] ?: '-') ?></td>
                            <td class="text-center">
                                <a href="<?= site_url('k-panel/survei/detail/'.$s['id_permintaan']) ?>" class="btn btn-sm btn-outline-primary rounded-pill px-3">Detail</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php else: ?>
        <!-- Belum ada pencarian -->
        <div class="text-center py-5 text-muted">
            <i class="bi bi-calendar2-range fs-1 d-block mb-3"></i>
            Silakan pilih rentang tanggal di atas dan klik <strong>Tampilkan Data</strong>.
        </div>
    <?php endif; ?>
</div>

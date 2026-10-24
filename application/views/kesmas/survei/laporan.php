<?php defined('BASEPATH') OR exit('No direct script access allowed'); 

// Amankan variabel statistik dan konversi ke bentuk array
$s = (array)($stats ?? []);
$ikm_score = isset($s['ikm_score']) ? (float)$s['ikm_score'] : 0;
$total_survei  = isset($s['total_survei']) ? (int)$s['total_survei'] : 0;

$avg_q = [];
for($i=1; $i<=9; $i++) {
    $avg_q[] = isset($s['avg_q'.$i]) ? (float)$s['avg_q'.$i] : 0;
}

// Kategori Mutu Pelayanan SKM
$mutu = 'TIDAK BAIK';
$mutu_color = 'danger';
if ($ikm_score >= 88.31) {
    $mutu = 'SANGAT BAIK'; $mutu_color = 'success';
} elseif ($ikm_score >= 76.61) {
    $mutu = 'BAIK'; $mutu_color = 'primary';
} elseif ($ikm_score >= 65.00) {
    $mutu = 'KURANG BAIK'; $mutu_color = 'warning';
}

// Demografi processing
$demo_jk = ['Laki-laki' => 0, 'Perempuan' => 0];
$demo_pendidikan = [];
$demo_pekerjaan = [];

if (isset($semua_survei) && is_array($semua_survei)) {
    foreach ($semua_survei as $row) {
        $jk = $row['jenis_kelamin'] === 'L' ? 'Laki-laki' : ($row['jenis_kelamin'] === 'P' ? 'Perempuan' : 'Tidak Diketahui');
        if(isset($demo_jk[$jk])) $demo_jk[$jk]++; else $demo_jk[$jk] = 1;
        
        $pendidikan = $row['pendidikan'] ?: 'Tidak Diketahui';
        if(isset($demo_pendidikan[$pendidikan])) $demo_pendidikan[$pendidikan]++; else $demo_pendidikan[$pendidikan] = 1;
        
        $pekerjaan = $row['pekerjaan'] ?: 'Tidak Diketahui';
        if(isset($demo_pekerjaan[$pekerjaan])) $demo_pekerjaan[$pekerjaan]++; else $demo_pekerjaan[$pekerjaan] = 1;
    }
}

// Helper untuk menampilkan rata-rata responden individual
if (!function_exists('get_avg_respondent')) {
    function get_avg_respondent($row) {
        $total = $row['q1'] + $row['q2'] + $row['q3'] + $row['q4'] + $row['q5'] + $row['q6'] + $row['q7'] + $row['q8'] + $row['q9'];
        return number_format($total / 9, 2);
    }
}
?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
    :root {
        --ink: #0f172a;
        --muted: #64748b;
        --shadow: 0 10px 30px rgba(0,0,0,.04);
        --border: rgba(15,23,42,.06);
    }
    
    .page-hero {
        border-radius: 24px;
        background: linear-gradient(135deg, #0ea5e9 0%, #2563eb 100%);
        box-shadow: 0 10px 30px rgba(37, 99, 235, 0.15);
        padding: 2rem 2.5rem;
        position: relative;
        overflow: hidden;
        color: white;
    }
    
    .page-hero::after {
        content: "\F586"; /* bi-star-fill icon */
        font-family: "bootstrap-icons";
        position: absolute;
        right: -20px;
        bottom: -50px;
        font-size: 14rem;
        color: #ffffff;
        opacity: 0.1;
        pointer-events: none;
        transform: rotate(-15deg);
    }

    .stat-card {
        border-radius: 20px;
        background: #fff;
        border: 1px solid var(--border);
        box-shadow: var(--shadow);
        padding: 1.5rem;
        text-align: center;
        transition: transform 0.3s ease;
        height: 100%;
    }
    .stat-card:hover { transform: translateY(-5px); box-shadow: 0 15px 35px rgba(0,0,0,.08); }
    
    .stat-icon {
        width: 64px; height: 64px;
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        margin: 0 auto 1rem;
        font-size: 1.8rem;
    }

    .chart-card {
        background: #fff;
        border-radius: 20px;
        border: 1px solid var(--border);
        box-shadow: var(--shadow);
        padding: 1.5rem;
        height: 100%;
    }

    .table-card {
        border-radius: 20px;
        background: #fff;
        border: 1px solid var(--border);
        box-shadow: var(--shadow);
        overflow: hidden;
    }
    .table-card .card-header {
        background: rgba(248, 250, 252, 0.8);
        border-bottom: 1px solid var(--border);
        padding: 1.25rem 1.5rem;
    }
    
    .table-modern th {
        background: #f8fafc;
        color: var(--muted);
        font-weight: 700;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 1rem 1.5rem;
        border-bottom: 1px solid var(--border);
    }
    .table-modern td {
        padding: 1rem 1.5rem;
        vertical-align: middle;
        color: var(--ink);
        border-bottom: 1px solid var(--border);
    }
    .table-modern tr:last-child td { border-bottom: none; }
</style>

<div class="container-fluid mb-5 animate-fade-up">
    <!-- HERO -->
    <div class="page-hero mb-4">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div style="z-index: 1;">
                <h3 class="fw-bolder mb-2" style="font-size: 2rem;">
                    <i class="bi bi-clipboard-data me-2"></i> Laporan Survei Kepuasan Masyarakat (SKM)
                </h3>
                <div class="fw-medium opacity-75" style="font-size: 1.1rem;">Analisis dan Rekapitulasi Data Kuesioner Indeks Kepuasan Masyarakat.</div>
            </div>
            <div style="z-index: 1;">
                <a href="<?= site_url('k-panel/survei/laporan_by_date') ?>" class="btn btn-light fw-bold text-primary px-4 py-2 shadow-sm" style="border-radius: 14px;">
                    <i class="bi bi-calendar-range me-2"></i>Filter berdasarkan Tanggal
                </a>
            </div>
        </div>
    </div>

    <!-- STATS -->
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="stat-card">
                <div class="stat-icon" style="background: rgba(14, 165, 233, 0.1); color: #0ea5e9;">
                    <i class="bi bi-people-fill"></i>
                </div>
                <h6 class="text-muted fw-bold mb-2 text-uppercase" style="letter-spacing:0.5px; font-size:0.8rem;">Total Responden SKM</h6>
                <h2 class="fw-bolder mb-2" style="font-size: 2.5rem; color: var(--ink);"><?= number_format($total_survei) ?></h2>
                <div class="text-muted small fw-medium">Survei Terkumpul</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card" style="border: 2px solid <?= $ikm_score >= 76.61 ? 'rgba(16, 185, 129, 0.3)' : 'rgba(245, 158, 11, 0.3)' ?>;">
                <div class="stat-icon" style="background: rgba(16, 185, 129, 0.1); color: #10b981;">
                    <i class="bi bi-graph-up-arrow"></i>
                </div>
                <h6 class="text-muted fw-bold mb-2 text-uppercase" style="letter-spacing:0.5px; font-size:0.8rem;">Indeks Kepuasan Masyarakat</h6>
                <h2 class="fw-bolder mb-2 text-<?= $mutu_color ?>" style="font-size: 2.5rem;"><?= number_format($ikm_score, 2) ?></h2>
                <div class="text-muted small fw-medium">Skala Maksimal 100</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card">
                <div class="stat-icon" style="background: rgba(245, 158, 11, 0.1); color: var(--bs-<?= $mutu_color ?>);">
                    <i class="bi bi-award-fill"></i>
                </div>
                <h6 class="text-muted fw-bold mb-2 text-uppercase" style="letter-spacing:0.5px; font-size:0.8rem;">Mutu Pelayanan</h6>
                <h2 class="fw-bolder mb-2 text-<?= $mutu_color ?>" style="font-size: 2.5rem;"><?= $mutu ?></h2>
                <div class="text-muted small fw-medium">Kategori Permenpan RB</div>
            </div>
        </div>
    </div>

    <!-- CHARTS ROW 1 -->
    <div class="row g-4 mb-4">
        <!-- 9 Unsur Pelayanan -->
        <div class="col-lg-8">
            <div class="chart-card">
                <h5 class="fw-bolder mb-4 text-primary"><i class="bi bi-bar-chart-fill me-2"></i>Nilai Rata-Rata per Unsur Pelayanan (Skala 1-4)</h5>
                <canvas id="chartUnsur" height="100"></canvas>
            </div>
        </div>
        <!-- Demografi Jenis Kelamin -->
        <div class="col-lg-4">
            <div class="chart-card d-flex flex-column">
                <h5 class="fw-bolder mb-4 text-primary"><i class="bi bi-gender-ambiguous me-2"></i>Jenis Kelamin</h5>
                <div style="flex:1; display:flex; align-items:center; justify-content:center;">
                    <canvas id="chartGender"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- CHARTS ROW 2 -->
    <div class="row g-4 mb-4">
        <!-- Pendidikan -->
        <div class="col-lg-6">
            <div class="chart-card">
                <h5 class="fw-bolder mb-4 text-primary"><i class="bi bi-mortarboard-fill me-2"></i>Pendidikan Responden</h5>
                <canvas id="chartPendidikan" height="150"></canvas>
            </div>
        </div>
        <!-- Pekerjaan -->
        <div class="col-lg-6">
            <div class="chart-card">
                <h5 class="fw-bolder mb-4 text-primary"><i class="bi bi-briefcase-fill me-2"></i>Pekerjaan Responden</h5>
                <canvas id="chartPekerjaan" height="150"></canvas>
            </div>
        </div>
    </div>

    <!-- TABLES -->
    <div class="row g-4">
        <!-- Ulasan dan Saran (Tetap sama, hanya menyesuaikan judul) -->
        <div class="col-12">
            <div class="table-card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="fw-bolder mb-0" style="color: var(--ink);"><i class="bi bi-chat-right-quote text-primary me-2"></i> Ulasan & Komentar Terbaru</h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-modern table-hover mb-0">
                        <thead>
                            <tr>
                                <th width="12%">Tanggal</th>
                                <th width="20%">Profil</th>
                                <th width="10%">Skor (1-4)</th>
                                <th>Komentar / Saran</th>
                                <th width="12%" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(empty($with_comments)): ?>
                                <tr><td colspan="5" class="text-center py-5 text-muted"><i class="bi bi-inbox fs-3 d-block mb-2"></i>Belum ada komentar atau saran yang masuk.</td></tr>
                            <?php else: ?>
                                <?php foreach($with_comments as $row): ?>
                                    <tr>
                                        <td class="fw-medium"><?= date('d M Y', strtotime($row['created_at'])) ?></td>
                                        <td class="small">
                                            <?= $row['jenis_kelamin'] ?> / <?= $row['usia'] ?: '-' ?> Thn <br>
                                            <span class="text-muted"><?= $row['pekerjaan'] ?></span>
                                        </td>
                                        <td class="fw-bold fs-5 text-primary"><?= get_avg_respondent($row) ?></td>
                                        <td>
                                            <div class="bg-light p-3 rounded-3 fst-italic" style="color: #475569; font-size: 0.95rem;">
                                                "<?= htmlspecialchars($row['komentar_saran']) ?>"
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="d-flex justify-content-center gap-1">
                                                <a href="<?= site_url('k-panel/survei/detail/'.$row['id_survei']) ?>" class="btn btn-sm btn-light border fw-bold text-primary rounded-pill px-3">Detail</a>
                                                <a href="<?= site_url('k-panel/survei/delete/'.$row['id_survei']) ?>" class="btn btn-sm btn-outline-danger fw-bold rounded-pill px-2" onclick="return confirm('Apakah Anda yakin ingin menghapus data survei ini?');" title="Hapus"><i class="bi bi-trash-fill"></i></a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Rating Rendah -->
        <div class="col-12">
            <div class="table-card border-danger border-opacity-25">
                <div class="card-header bg-danger bg-opacity-10 d-flex justify-content-between align-items-center" style="border-bottom: 1px solid rgba(239, 68, 68, 0.1);">
                    <h5 class="fw-bolder mb-0 text-danger"><i class="bi bi-exclamation-triangle-fill me-2"></i> Perlu Perhatian (Skor ≤ 2 Pada Salah Satu Unsur)</h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-modern table-hover mb-0">
                        <thead>
                            <tr>
                                <th width="15%">Tanggal Survei</th>
                                <th width="20%">Profil Singkat</th>
                                <th width="15%">Skor Rata-Rata</th>
                                <th>Komentar</th>
                                <th width="12%" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(empty($low_ratings)): ?>
                                <tr><td colspan="5" class="text-center py-5 text-muted"><i class="bi bi-check-circle fs-3 d-block mb-2 text-success"></i>Bagus! Tidak ada penilaian rendah sejauh ini.</td></tr>
                            <?php else: ?>
                                <?php foreach($low_ratings as $row): ?>
                                    <tr>
                                        <td class="fw-medium text-danger"><?= date('d M Y', strtotime($row['created_at'])) ?></td>
                                        <td class="small">
                                            <?= $row['jenis_kelamin'] ?> / <?= $row['usia'] ?: '-' ?> Thn <br>
                                            <span class="text-muted"><?= $row['pekerjaan'] ?></span>
                                        </td>
                                        <td class="fw-bold text-danger"><?= get_avg_respondent($row) ?></td>
                                        <td class="text-muted"><?= htmlspecialchars($row['komentar_saran'] ?: '- Tidak ada komentar -') ?></td>
                                        <td class="text-center">
                                            <div class="d-flex justify-content-center gap-1">
                                                <a href="<?= site_url('k-panel/survei/detail/'.$row['id_survei']) ?>" class="btn btn-sm btn-outline-danger fw-bold rounded-pill px-3">Lihat</a>
                                                <a href="<?= site_url('k-panel/survei/delete/'.$row['id_survei']) ?>" class="btn btn-sm btn-danger fw-bold rounded-pill px-2" onclick="return confirm('Apakah Anda yakin ingin menghapus data survei ini?');" title="Hapus"><i class="bi bi-trash-fill"></i></a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    // Data untuk Chart Unsur
    const ctxUnsur = document.getElementById('chartUnsur').getContext('2d');
    new Chart(ctxUnsur, {
        type: 'bar',
        data: {
            labels: [
                'Persyaratan', 'Prosedur', 'Waktu Pelayanan', 'Biaya/Tarif', 
                'Produk Layanan', 'Kompetensi Petugas', 'Perilaku Petugas', 
                'Sarana Prasarana', 'Penanganan Pengaduan'
            ],
            datasets: [{
                label: 'Rata-rata Skor (Max 4)',
                data: <?= json_encode($avg_q) ?>,
                backgroundColor: 'rgba(14, 165, 233, 0.7)',
                borderColor: 'rgba(14, 165, 233, 1)',
                borderWidth: 1,
                borderRadius: 6
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    max: 4,
                    ticks: { stepSize: 1 }
                }
            },
            plugins: {
                legend: { display: false }
            }
        }
    });

    // Data untuk Chart Gender
    const ctxGender = document.getElementById('chartGender').getContext('2d');
    new Chart(ctxGender, {
        type: 'doughnut',
        data: {
            labels: <?= json_encode(array_keys($demo_jk)) ?>,
            datasets: [{
                data: <?= json_encode(array_values($demo_jk)) ?>,
                backgroundColor: ['#3b82f6', '#ec4899', '#94a3b8'],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            cutout: '65%',
            plugins: {
                legend: { position: 'bottom' }
            }
        }
    });

    // Data untuk Chart Pendidikan
    const ctxPendidikan = document.getElementById('chartPendidikan').getContext('2d');
    new Chart(ctxPendidikan, {
        type: 'bar',
        data: {
            labels: <?= json_encode(array_keys($demo_pendidikan)) ?>,
            datasets: [{
                label: 'Jumlah Responden',
                data: <?= json_encode(array_values($demo_pendidikan)) ?>,
                backgroundColor: 'rgba(16, 185, 129, 0.7)',
                borderRadius: 4
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } }
        }
    });

    // Data untuk Chart Pekerjaan
    const ctxPekerjaan = document.getElementById('chartPekerjaan').getContext('2d');
    new Chart(ctxPekerjaan, {
        type: 'bar',
        data: {
            labels: <?= json_encode(array_keys($demo_pekerjaan)) ?>,
            datasets: [{
                label: 'Jumlah Responden',
                data: <?= json_encode(array_values($demo_pekerjaan)) ?>,
                backgroundColor: 'rgba(245, 158, 11, 0.7)',
                borderRadius: 4
            }]
        },
        options: {
            responsive: true,
            indexAxis: 'y', // Horizontal bar
            plugins: { legend: { display: false } }
        }
    });
});
</script>
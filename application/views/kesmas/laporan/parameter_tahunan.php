<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$tahun = isset($tahun) ? (int)$tahun : (int)date('Y');
$laporan_tahunan = isset($laporan_tahunan) ? $laporan_tahunan : [];

// Pengelompokan data matriks per kategori -> nama_pemeriksaan -> bulan
$kategori_group = [];
foreach($laporan_tahunan as $row) {
    $kategori = !empty($row['kategori']) ? $row['kategori'] : 'Lain-lain';
    $nama = $row['nama_pemeriksaan'];
    $bulan = (int)$row['bulan'];

    if (!isset($kategori_group[$kategori])) {
        $kategori_group[$kategori] = [];
    }

    if (!isset($kategori_group[$kategori][$nama])) {
        $kategori_group[$kategori][$nama] = [
            'satuan' => $row['satuan'],
            'baku_mutu' => $row['baku_mutu'],
            'bulanan' => array_fill(1, 12, ['total' => 0, 'ms' => 0, 'tms' => 0]),
            'total_tahun' => 0,
            'total_ms' => 0,
            'total_tms' => 0,
        ];
    }

    $kategori_group[$kategori][$nama]['bulanan'][$bulan] = [
        'total' => (int)$row['total'],
        'ms' => (int)$row['ms_count'],
        'tms' => (int)$row['tms_count'],
    ];

    $kategori_group[$kategori][$nama]['total_tahun'] += (int)$row['total'];
    $kategori_group[$kategori][$nama]['total_ms'] += (int)$row['ms_count'];
    $kategori_group[$kategori][$nama]['total_tms'] += (int)$row['tms_count'];
}
?>

<?php $this->load->view('kesmas/premium_list_style'); ?>

<div class="report-container pb-5" style="max-width: 1400px; margin: 0 auto;">
    <!-- HERO HEADER -->
    <div class="page-hero mb-4">
        <div class="d-flex align-items-start justify-content-between gap-3 flex-wrap">
            <div>
                <a class="btn btn-outline-secondary btn-sm me-2" href="<?= site_url('k-panel/laporan/parameter?tahun=' . $tahun) ?>" style="border-radius: 10px; padding: 0.5rem 0.9rem; font-weight: 600; transition: all 0.3s ease;">
                    <i class="fas fa-arrow-left"></i> Kembali ke Portal
                </a>
                <div class="mt-3">
                    <h3 class="fw-bold mb-1">
                        <i class="fas fa-chart-line text-success me-2"></i>
                        Laporan Matriks Tahunan Parameter Kesmas - Tahun <?= $tahun ?>
                    </h3>
                    <p class="sub mb-0 text-muted">Rekapitulasi 12 Bulan (Januari s.d. Desember) Pemeriksaan Parameter & Analisis Baku Mutu</p>
                </div>
            </div>
            <div class="d-flex gap-2 flex-wrap">
                <a href="<?= site_url('k-panel/laporan/parameter/export_excel?tahun=' . $tahun) ?>" class="btn btn-success" style="border-radius: 10px; font-weight: 600; padding: 0.55rem 1.1rem; box-shadow: 0 4px 12px rgba(16,185,129,0.25);">
                    <i class="fas fa-file-excel me-1"></i> Export Excel (.xls)
                </a>
            </div>
        </div>
    </div>

    <!-- TABEL MATRIKS PER KATEGORI -->
    <?php if (empty($kategori_group)): ?>
        <div class="card border-0 shadow-sm rounded-4 p-5 text-center bg-white">
            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
            <h5 class="fw-bold text-dark">Belum Ada Data Pengujian</h5>
            <p class="text-muted mb-0">Belum ada data pengujian yang tercatat untuk tahun <?= $tahun ?>.</p>
        </div>
    <?php endif; ?>

    <?php foreach($kategori_group as $kategori_title => $parameters): ?>
    <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden">
        <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
            <h5 class="fw-bold text-dark mb-0">
                <i class="fas fa-vials text-primary me-2"></i>
                Kategori: <?= htmlspecialchars($kategori_title) ?>
            </h5>
            <span class="badge bg-primary px-3 py-1.5"><?= count($parameters) ?> Parameter</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle mb-0" style="font-size: 9pt;">
                    <thead class="table-light text-center">
                        <tr>
                            <th width="3%">No</th>
                            <th class="text-start" width="22%">Parameter Pemeriksaan</th>
                            <th width="6%">Satuan</th>
                            <th width="10%">Baku Mutu</th>
                            <th width="4%">Jan</th>
                            <th width="4%">Feb</th>
                            <th width="4%">Mar</th>
                            <th width="4%">Apr</th>
                            <th width="4%">Mei</th>
                            <th width="4%">Jun</th>
                            <th width="4%">Jul</th>
                            <th width="4%">Ags</th>
                            <th width="4%">Sep</th>
                            <th width="4%">Okt</th>
                            <th width="4%">Nov</th>
                            <th width="4%">Des</th>
                            <th width="7%" class="bg-primary text-white">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                            $num = 1;
                            $monthly_totals = array_fill(1, 12, 0);
                            $grand_total_cat = 0;

                            foreach($parameters as $param_name => $p_data): 
                                $grand_total_cat += $p_data['total_tahun'];
                        ?>
                        <tr>
                            <td class="text-center fw-bold"><?= $num++ ?></td>
                            <td class="fw-bold text-dark"><?= htmlspecialchars($param_name) ?></td>
                            <td class="text-center text-muted"><?= htmlspecialchars($p_data['satuan'] ?: '-') ?></td>
                            <td class="small text-muted"><?= htmlspecialchars($p_data['baku_mutu'] ?: '-') ?></td>
                            
                            <?php for($m=1; $m<=12; $m++): 
                                $val = $p_data['bulanan'][$m]['total'];
                                $monthly_totals[$m] += $val;
                            ?>
                            <td class="text-center <?= $val > 0 ? 'fw-bold text-primary' : 'text-muted' ?>">
                                <?= $val > 0 ? $val : '-' ?>
                            </td>
                            <?php endfor; ?>

                            <td class="text-center fw-bold bg-light">
                                <span class="badge bg-primary px-2 py-1"><?= $p_data['total_tahun'] ?></span>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot class="table-light fw-bold text-center">
                        <tr>
                            <td colspan="4" class="text-end">TOTAL:</td>
                            <?php for($m=1; $m<=12; $m++): ?>
                            <td><?= $monthly_totals[$m] > 0 ? $monthly_totals[$m] : '-' ?></td>
                            <?php endfor; ?>
                            <td class="bg-primary text-white"><?= $grand_total_cat ?></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>

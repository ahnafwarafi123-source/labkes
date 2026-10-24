<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$bulan = isset($bulan) ? (int)$bulan : (int)date('n');
$tahun = isset($tahun) ? (int)$tahun : (int)date('Y');
$nama_bulan = isset($nama_bulan) ? $nama_bulan : 'September';
$periode_str = isset($periode_str) ? $periode_str : sprintf('%04d-%02d', $tahun, $bulan);
$grouped_data = isset($grouped_data) ? $grouped_data : [];

$grand_total_tested = isset($grand_total_tested) ? (int)$grand_total_tested : 0;
$grand_total_ms = isset($grand_total_ms) ? (int)$grand_total_ms : 0;
$grand_total_tms = isset($grand_total_tms) ? (int)$grand_total_tms : 0;
$ms_rate = $grand_total_tested > 0 ? round(($grand_total_ms / $grand_total_tested) * 100, 1) : 0;
?>

<?php $this->load->view('kesmas/premium_list_style'); ?>

<style>
:root {
    --accent: #0284c7;
    --ms-color: #10b981;
    --tms-color: #ef4444;
}

.report-container {
    max-width: 1320px;
    margin: 0 auto;
}

.stat-card-custom {
    border-radius: 14px;
    border: none;
    box-shadow: 0 4px 14px rgba(15, 23, 42, 0.05);
    transition: transform 0.2s ease;
    background: #ffffff;
}

.stat-card-custom:hover {
    transform: translateY(-3px);
}

.nav-pills .nav-link {
    border-radius: 10px;
    padding: 0.6rem 1.1rem;
    font-weight: 600;
    color: #475569;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    margin-right: 6px;
    margin-bottom: 6px;
    transition: all 0.2s ease;
}

.nav-pills .nav-link.active {
    background: #0284c7;
    color: #ffffff;
    border-color: #0284c7;
    box-shadow: 0 4px 12px rgba(2, 132, 199, 0.25);
}

.chart-wrapper-card {
    border-radius: 14px;
    border: 1px solid #e2e8f0;
    background: #ffffff;
    box-shadow: 0 4px 14px rgba(15, 23, 42, 0.04);
}

.table-custom-report th {
    background-color: #f8fafc;
    font-weight: 700;
    color: #334155;
    vertical-align: middle;
}
</style>

<div class="report-container pb-5">
    <!-- HERO HEADER -->
    <div class="page-hero mb-4">
        <div class="d-flex align-items-start justify-content-between gap-3 flex-wrap">
            <div>
                <a class="btn btn-outline-secondary btn-sm me-2" href="<?= site_url('k-panel/laporan/parameter?tahun=' . $tahun) ?>" style="border-radius: 10px; padding: 0.5rem 0.9rem; font-weight: 600; transition: all 0.3s ease;">
                    <i class="fas fa-arrow-left"></i> Kembali ke Portal
                </a>
                <div class="mt-3">
                    <h3 class="fw-bold mb-1">
                        <i class="fas fa-file-invoice text-primary me-2"></i>
                        Laporan Hasil Pemeriksaan Kesmas - <?= $nama_bulan ?> <?= $tahun ?>
                    </h3>
                    <p class="sub mb-0 text-muted">Rekapitulasi Parameter Pengujian, Analisis Baku Mutu MS/TMS, dan Visualisasi Grafik</p>
                </div>
            </div>
            <div class="d-flex gap-2 flex-wrap">
                <a class="btn btn-outline-primary" href="<?= base_url('cetak_laporan.php?bulan=' . $periode_str) ?>" target="_blank" style="border-radius: 10px; font-weight: 600; padding: 0.55rem 1.1rem;">
                    <i class="fas fa-print me-1"></i> Cetak Buku Laporan (PDF)
                </a>
                <a class="btn-add" href="<?= site_url('k-panel/laporan/parameter/tahunan?tahun=' . $tahun) ?>" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); box-shadow: 0 10px 20px rgba(16,185,129,.25);">
                    <i class="fas fa-chart-line me-1"></i> Matriks Tahunan <?= $tahun ?>
                </a>
            </div>
        </div>
    </div>

    <!-- STAT SUMMARY CARDS -->
    <div class="row g-3 mb-4 text-center">
        <div class="col-6 col-md-3">
            <div class="card stat-card-custom p-3 border-start border-primary border-4">
                <div class="text-muted small fw-semibold">TOTAL PENGUJIAN</div>
                <h3 class="fw-bold text-dark mt-2 mb-0"><?= $grand_total_tested ?></h3>
                <div class="small text-muted mt-1"><i class="fas fa-vials text-primary me-1"></i> Parameter Diuji</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card stat-card-custom p-3 border-start border-success border-4">
                <div class="text-muted small fw-semibold">MEMENUHI SYARAT (MS)</div>
                <h3 class="fw-bold text-success mt-2 mb-0"><?= $grand_total_ms ?></h3>
                <div class="small text-muted mt-1"><i class="fas fa-check-circle text-success me-1"></i> Lolos Baku Mutu</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card stat-card-custom p-3 border-start border-danger border-4">
                <div class="text-muted small fw-semibold">TIDAK MEMENUHI SYARAT (TMS)</div>
                <h3 class="fw-bold text-danger mt-2 mb-0"><?= $grand_total_tms ?></h3>
                <div class="small text-muted mt-1"><i class="fas fa-times-circle text-danger me-1"></i> Melebihi Batas</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card stat-card-custom p-3 border-start border-info border-4">
                <div class="text-muted small fw-semibold">PERSENTASE KELAYAKAN</div>
                <h3 class="fw-bold text-info mt-2 mb-0"><?= $ms_rate ?>%</h3>
                <div class="small text-muted mt-1"><i class="fas fa-percentage text-info me-1"></i> Angka Kelayakan</div>
            </div>
        </div>
    </div>

    <!-- TABS NAV-PILLS PER KATEGORI -->
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-header bg-white p-3 border-bottom">
            <ul class="nav nav-pills flex-wrap" id="parameterCategoryTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="tab-all-btn" data-bs-toggle="pill" data-bs-target="#tab-all" type="button" role="tab" aria-selected="true">
                        <i class="fas fa-layer-group me-1"></i> Semua Kategori
                    </button>
                </li>
                <?php 
                    $cat_idx = 0;
                    foreach($grouped_data as $cat_name => $items): 
                        $cat_idx++;
                        $safe_id = 'tab-cat-' . $cat_idx;
                ?>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="<?= $safe_id ?>-btn" data-bs-toggle="pill" data-bs-target="#<?= $safe_id ?>" type="button" role="tab" aria-selected="false">
                        <i class="fas fa-flask me-1 text-info"></i> <?= htmlspecialchars($cat_name) ?>
                        <span class="badge bg-light text-dark ms-1"><?= count($items) ?></span>
                    </button>
                </li>
                <?php endforeach; ?>
            </ul>
        </div>

        <div class="card-body p-4 bg-light">
            <div class="tab-content" id="parameterCategoryTabsContent">
                <!-- TAB 1: SEMUA KATEGORI -->
                <div class="tab-pane fade show active" id="tab-all" role="tabpanel">
                    <div class="row g-4">
                        <div class="col-lg-7">
                            <div class="card chart-wrapper-card h-100 p-3">
                                <h6 class="fw-bold text-dark mb-3"><i class="fas fa-chart-bar text-primary me-2"></i> Visualisasi Perbandingan MS vs TMS (Bulan <?= $nama_bulan ?>)</h6>
                                <div style="position: relative; height: 380px;">
                                    <canvas id="chartAllCategories"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-5">
                            <div class="card chart-wrapper-card h-100 p-0 overflow-hidden">
                                <div class="p-3 border-bottom bg-white">
                                    <h6 class="fw-bold text-dark mb-0"><i class="fas fa-list-check text-success me-2"></i> Ringkasan Parameter Diuji</h6>
                                </div>
                                <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                                    <table class="table table-hover align-middle mb-0 table-custom-report">
                                        <thead>
                                            <tr>
                                                <th>Parameter</th>
                                                <th class="text-center">Diuji</th>
                                                <th class="text-center">MS</th>
                                                <th class="text-center">TMS</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                                $all_rows_flat = [];
                                                foreach($grouped_data as $c_name => $p_items) {
                                                    foreach($p_items as $pi) $all_rows_flat[] = $pi;
                                                }
                                                foreach($all_rows_flat as $r): 
                                            ?>
                                            <tr>
                                                <td class="fw-semibold text-dark">
                                                    <?= htmlspecialchars($r['nama_pemeriksaan']) ?>
                                                    <div class="small text-muted" style="font-size:0.75rem;"><?= htmlspecialchars($r['kategori']) ?></div>
                                                </td>
                                                <td class="text-center fw-bold"><?= $r['total_tested'] ?></td>
                                                <td class="text-center text-success fw-bold">
                                                    <?php if($r['total_ms'] > 0): ?>
                                                        <a href="javascript:void(0)" onclick="showSamples(<?= $r['master_id'] ?>, '<?= htmlspecialchars(addslashes($r['nama_pemeriksaan'])) ?>', '<?= $periode_str ?>', 'MS')" class="badge bg-success text-decoration-none px-2 py-1">
                                                            <?= $r['total_ms'] ?>
                                                        </a>
                                                    <?php else: ?>
                                                        <span class="text-muted">0</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="text-center text-danger fw-bold">
                                                    <?php if($r['total_tms'] > 0): ?>
                                                        <a href="javascript:void(0)" onclick="showSamples(<?= $r['master_id'] ?>, '<?= htmlspecialchars(addslashes($r['nama_pemeriksaan'])) ?>', '<?= $periode_str ?>', 'TMS')" class="badge bg-danger text-decoration-none px-2 py-1">
                                                            <?= $r['total_tms'] ?>
                                                        </a>
                                                    <?php else: ?>
                                                        <span class="text-muted">0</span>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                            <?php if(empty($all_rows_flat)): ?>
                                            <tr><td colspan="4" class="text-center py-4 text-muted">Belum ada data pengujian pada bulan ini.</td></tr>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- TAB-TAB PER KATEGORI -->
                <?php 
                    $cat_idx = 0;
                    foreach($grouped_data as $cat_name => $items): 
                        $cat_idx++;
                        $safe_id = 'tab-cat-' . $cat_idx;
                ?>
                <div class="tab-pane fade" id="<?= $safe_id ?>" role="tabpanel">
                    <div class="card chart-wrapper-card p-0 mb-4 overflow-hidden">
                        <div class="p-3 bg-white border-bottom d-flex justify-content-between align-items-center">
                            <h6 class="fw-bold text-dark mb-0"><i class="fas fa-table text-primary me-2"></i> Rekapitulasi Parameter Kategori <?= htmlspecialchars($cat_name) ?></h6>
                            <span class="badge bg-primary px-3 py-1.5"><?= count($items) ?> Parameter</span>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover table-striped align-middle mb-0 table-custom-report">
                                <thead>
                                    <tr>
                                        <th width="5%" class="text-center">No</th>
                                        <th>Nama Parameter</th>
                                        <th>Baku Mutu</th>
                                        <th>Satuan</th>
                                        <th class="text-center" width="12%">Total Diuji</th>
                                        <th class="text-center" width="12%">MS</th>
                                        <th class="text-center" width="12%">TMS</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $no = 1; foreach($items as $it): ?>
                                    <tr>
                                        <td class="text-center fw-bold"><?= $no++ ?></td>
                                        <td class="fw-bold text-dark"><?= htmlspecialchars($it['nama_pemeriksaan']) ?></td>
                                        <td><?= htmlspecialchars($it['baku_mutu'] ?: '-') ?></td>
                                        <td><?= htmlspecialchars($it['satuan'] ?: '-') ?></td>
                                        <td class="text-center fw-bold"><?= $it['total_tested'] ?></td>
                                        <td class="text-center">
                                            <?php if($it['total_ms'] > 0): ?>
                                                <a href="javascript:void(0)" onclick="showSamples(<?= $it['master_id'] ?>, '<?= htmlspecialchars(addslashes($it['nama_pemeriksaan'])) ?>', '<?= $periode_str ?>', 'MS')" class="badge bg-success text-decoration-none px-2 py-1">
                                                    <?= $it['total_ms'] ?> MS
                                                </a>
                                            <?php else: ?>
                                                <span class="text-muted">0</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center">
                                            <?php if($it['total_tms'] > 0): ?>
                                                <a href="javascript:void(0)" onclick="showSamples(<?= $it['master_id'] ?>, '<?= htmlspecialchars(addslashes($it['nama_pemeriksaan'])) ?>', '<?= $periode_str ?>', 'TMS')" class="badge bg-danger text-decoration-none px-2 py-1">
                                                    <?= $it['total_tms'] ?> TMS
                                                </a>
                                            <?php else: ?>
                                                <span class="text-muted">0</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<!-- Modal Penelusuran Sampel AJAX -->
<div class="modal fade" id="parameterSamplesModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content" style="border-radius: 16px;">
            <div class="modal-header bg-light border-0">
                <h5 class="modal-title fw-bold" id="parameterSamplesModalLabel">Daftar Sampel</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0">
                <div class="p-3 bg-white border-bottom">
                    <p id="parameterSamplesTitle" class="mb-0 text-muted"></p>
                </div>
                <div id="parameterSamplesList" class="p-3">Memuat...</div>
            </div>
            <div class="modal-footer border-0 bg-light">
                <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    // Chart All Categories (MS vs TMS)
    const ctxAll = document.getElementById('chartAllCategories').getContext('2d');
    
    const labelsAll = [<?php foreach($all_rows_flat as $af) echo json_encode($af['nama_pemeriksaan']) . ","; ?>];
    const dataMsAll = [<?php foreach($all_rows_flat as $af) echo (int)$af['total_ms'] . ","; ?>];
    const dataTmsAll = [<?php foreach($all_rows_flat as $af) echo (int)$af['total_tms'] . ","; ?>];

    new Chart(ctxAll, {
        type: 'bar',
        data: {
            labels: labelsAll,
            datasets: [
                {
                    label: 'Memenuhi Syarat (MS)',
                    data: dataMsAll,
                    backgroundColor: '#10b981',
                    borderRadius: 4
                },
                {
                    label: 'Tidak Memenuhi Syarat (TMS)',
                    data: dataTmsAll,
                    backgroundColor: '#ef4444',
                    borderRadius: 4
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                x: {
                    stacked: true,
                    ticks: { maxRotation: 45, font: { size: 9 } }
                },
                y: {
                    stacked: true,
                    beginAtZero: true,
                    ticks: { stepSize: 1 }
                }
            },
            plugins: {
                legend: { position: 'top' }
            }
        }
    });

    // Event resize saat tab BS5 berubah
    $('button[data-bs-toggle="pill"]').on('shown.bs.tab', function(e) {
        window.dispatchEvent(new Event('resize'));
    });
});

let currentMasterId = 0;
let currentNama = '';
let currentBulan = '';
let currentStatus = '';

function showSamples(masterId, nama, bulan, status) {
    currentMasterId = masterId;
    currentNama = nama;
    currentBulan = bulan;
    currentStatus = status || '';

    let badge = '';
    if (status === 'MS') badge = '<span class="badge bg-success ms-2">MS</span>';
    else if (status === 'TMS') badge = '<span class="badge bg-danger ms-2">TMS</span>';

    document.getElementById('parameterSamplesModalLabel').innerHTML = 'Daftar Sampel ' + badge;
    document.getElementById('parameterSamplesTitle').innerHTML = '<i class="fas fa-info-circle me-1"></i> <strong>' + nama + '</strong> &mdash; Periode: ' + bulan;
    document.getElementById('parameterSamplesList').innerHTML = '<div class="text-center py-5"><div class="spinner-border text-primary" role="status"></div><div class="mt-2 text-muted">Memuat data...</div></div>';
    
    var modal = new bootstrap.Modal(document.getElementById('parameterSamplesModal'));
    modal.show();

    fetchSamplesPage(1);
}

function fetchSamplesPage(page) {
    let per_page = 50;
    var url = '<?= site_url('k-panel/laporan_uji_kesmas/parameter_samples') ?>' + 
              '?master_id=' + encodeURIComponent(currentMasterId) + 
              '&bulan=' + encodeURIComponent(currentBulan) + 
              '&status=' + encodeURIComponent(currentStatus) + 
              '&page=' + page + 
              '&per_page=' + per_page;

    document.getElementById('parameterSamplesList').innerHTML = '<div class="text-center py-5"><div class="spinner-border text-primary" role="status"></div><div class="mt-2 text-muted">Memuat halaman ' + page + '...</div></div>';

    fetch(url, {credentials: 'same-origin'})
        .then(function(r){ return r.json(); })
        .then(function(json){
            if (!json.ok) {
                document.getElementById('parameterSamplesList').innerHTML = '<div class="alert alert-warning m-3">'+(json.message || 'Tidak dapat mengambil data')+'</div>';
                return;
            }
            if (!json.rows || json.rows.length === 0) {
                document.getElementById('parameterSamplesList').innerHTML = '<div class="text-center py-5 text-muted"><i class="fas fa-inbox fa-3x d-block mb-3"></i>Tidak ada data sampel.</div>';
                return;
            }

            var container = document.createElement('div');
            container.className = 'table-responsive';
            var table = document.createElement('table');
            table.className = 'table table-hover table-bordered mb-0';
            var thead = document.createElement('thead');
            thead.className = 'table-light';
            thead.innerHTML = '<tr><th width="20%">No. Registrasi</th><th width="35%">Parameter</th><th width="20%">Jenis Sampel</th><th width="15%">Tanggal</th><th width="10%" class="text-center">Status</th></tr>';
            table.appendChild(thead);
            var tbody = document.createElement('tbody');
            
            json.rows.forEach(function(r){
                var tr = document.createElement('tr');
                
                var td1 = document.createElement('td');
                td1.className = 'fw-bold';
                var a = document.createElement('a');
                a.href = '<?= site_url('k-panel/laporan_uji_kesmas/detail/') ?>' + r.id;
                a.target = '_blank';
                a.className = 'text-decoration-none';
                a.textContent = r.no_registrasi;
                td1.appendChild(a);
                
                var td2 = document.createElement('td');
                td2.textContent = r.nama_pemeriksaan || '-';

                var td3 = document.createElement('td');
                td3.textContent = r.jenis_sampel || '-';
                
                var td4 = document.createElement('td');
                td4.textContent = r.tgl_permintaan ? r.tgl_permintaan.split(' ')[0] : '-';
                
                var td5 = document.createElement('td');
                td5.className = 'text-center';
                var st = (r.status || '').toUpperCase();
                if (st === 'MS') {
                    td5.innerHTML = '<span class="badge bg-success px-2 py-1">MS</span>';
                } else if (st === 'TMS') {
                    td5.innerHTML = '<span class="badge bg-danger px-2 py-1">TMS</span>';
                } else {
                    td5.innerHTML = '<span class="text-muted">-</span>';
                }
                
                tr.appendChild(td1);
                tr.appendChild(td2);
                tr.appendChild(td3);
                tr.appendChild(td4);
                tr.appendChild(td5);
                tbody.appendChild(tr);
            });
            table.appendChild(tbody);
            container.appendChild(table);

            var listDiv = document.getElementById('parameterSamplesList');
            listDiv.innerHTML = '';
            listDiv.appendChild(container);
        })
        .catch(function(err){
            document.getElementById('parameterSamplesList').innerHTML = '<div class="alert alert-danger m-3">Terjadi kesalahan saat mengambil data.</div>';
            console.error(err);
        });
}
</script>

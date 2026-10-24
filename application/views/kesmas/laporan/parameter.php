<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$tahun = isset($tahun) ? (int)$tahun : (int)date('Y');
$yearly_stats = isset($yearly_stats) ? $yearly_stats : [];
$bulan_list = isset($bulan_list) ? $bulan_list : [];
$q_val = isset($q_val) ? htmlspecialchars($q_val) : '';
$dari_val = isset($dari_val) ? htmlspecialchars($dari_val) : '';
$sampai_val = isset($sampai_val) ? htmlspecialchars($sampai_val) : '';
$has_custom = !empty($custom_results);
?>

<?php $this->load->view('kesmas/premium_list_style'); ?>

<style>
:root {
    --accent: #0284c7;
    --accent-hover: #0369a1;
    --ms: #16a34a;
    --tms: #dc2626;
    --muted: #64748b;
}

.report-container {
    max-width: 1320px;
    margin: 0 auto;
}

.month-card {
    transition: transform 0.25s ease, box-shadow 0.25s ease;
    border-radius: 14px;
    overflow: hidden;
    background: #ffffff;
    border: 1px solid rgba(226, 232, 240, 0.9);
}

.month-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 12px 24px rgba(15, 23, 42, 0.09) !important;
}

.month-icon-circle {
    width: 64px;
    height: 64px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #f8fafc;
    margin: 0 auto 12px auto;
    transition: transform 0.2s ease;
}

.month-card:hover .month-icon-circle {
    transform: scale(1.08);
}

.stat-pill {
    font-size: 0.76rem;
    font-weight: 600;
    padding: 0.25rem 0.55rem;
    border-radius: 6px;
    display: inline-flex;
    align-items: center;
    gap: 4px;
}

.stat-pill-total {
    background: #f1f5f9;
    color: #334155;
}

.stat-pill-ms {
    background: #ecfdf5;
    color: #047857;
}

.stat-pill-tms {
    background: #fef2f2;
    color: #b91c1c;
}
</style>

<div class="report-container pb-5">
    <!-- HERO HEADER -->
    <div class="page-hero mb-4">
        <div class="d-flex align-items-start justify-content-between gap-3 flex-wrap">
            <div>
                <a class="btn btn-outline-secondary btn-sm me-2" href="<?= site_url('k-panel/laporan_uji_kesmas') ?>" style="border-radius: 10px; padding: 0.5rem 0.9rem; font-weight: 600; transition: all 0.3s ease;">
                    <i class="fas fa-arrow-left"></i> Kembali ke Laporan Uji
                </a>
                <div class="mt-3">
                    <h3 class="fw-bold mb-1">
                        <i class="fas fa-chart-bar text-primary me-2"></i>
                        Laporan Parameter Pengujian Kesmas
                    </h3>
                    <p class="sub mb-0 text-muted">Portal rekapitulasi pengujian parameter, baku mutu MS/TMS, grafik bulanan, dan laporan tahunan</p>
                </div>
            </div>
            <div class="d-flex gap-2 flex-wrap">
                <a class="btn btn-outline-primary" href="<?= base_url('cetak_laporan.php?bulan=' . $tahun . '-09') ?>" target="_blank" style="border-radius: 10px; font-weight: 600; padding: 0.55rem 1.1rem;">
                    <i class="fas fa-print me-1"></i> Cetak Buku Laporan (PDF)
                </a>
                <a class="btn-add" href="<?= site_url('k-panel/laporan/parameter/tahunan?tahun=' . $tahun) ?>" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); box-shadow: 0 10px 20px rgba(16,185,129,.25);">
                    <i class="fas fa-chart-line me-1"></i> Laporan Tahunan <?= $tahun ?>
                </a>
            </div>
        </div>
    </div>

    <!-- FILTER TAHUN (SEPERTI FORM LAPORAN AKHIR KLINIK) -->
    <div class="card border-0 shadow-sm rounded-4 mb-4" style="border-top: 4px solid #0284c7 !important;">
        <div class="card-header bg-white py-3 border-0">
            <h5 class="card-title fw-bold text-dark mb-0">
                <i class="fas fa-filter text-primary me-2"></i>
                Filter Tahun & Periode Laporan
            </h5>
        </div>
        <div class="card-body p-4 pt-1">
            <form method="GET" action="<?= site_url('k-panel/laporan/parameter') ?>">
                <div class="row align-items-end g-3">
                    <div class="col-12 col-md-5">
                        <label for="tahun-select" class="form-label fw-semibold text-muted small mb-1">PILIH TAHUN</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light text-secondary border-end-0"><i class="fas fa-calendar-alt"></i></span>
                            <select id="tahun-select" name="tahun" class="form-select border-start-0" style="border-radius: 0 8px 8px 0; font-weight: 600;">
                                <?php for($i = date('Y'); $i >= 2020; $i--): ?>
                                    <option value="<?= $i ?>" <?= ($i == $tahun) ? 'selected' : '' ?>>
                                        Tahun <?= $i ?>
                                    </option>
                                <?php endfor; ?>
                            </select>
                        </div>
                    </div>

                    <div class="col-12 col-md-3">
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary" style="border-radius: 8px; font-weight: 600; padding: 0.6rem;">
                                <i class="fas fa-search me-1"></i> Tampilkan
                            </button>
                        </div>
                    </div>

                    <!-- BUTTON LAPORAN TAHUNAN -->
                    <div class="col-12 col-md-4">
                        <div class="d-grid">
                            <a href="<?= site_url('k-panel/laporan/parameter/tahunan?tahun=' . $tahun) ?>" class="btn btn-success" style="border-radius: 8px; font-weight: 600; padding: 0.6rem;">
                                <i class="fas fa-table me-1"></i> Matriks Tahunan <?= $tahun ?>
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- 12 KARTU BULAN (JANUARI - DESEMBER) -->
    <div class="mb-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="fw-bold text-dark mb-0">
                <i class="far fa-calendar-check text-primary me-2"></i>
                Rekapitulasi Bulanan Parameter - Tahun <?= $tahun ?>
            </h5>
            <span class="badge bg-light text-muted border px-3 py-2 fw-semibold">
                Klik kartu bulan untuk melihat grafik & tab kategori
            </span>
        </div>

        <div class="row g-4">
            <?php foreach($bulan_list as $num => $b): 
                $stats = $yearly_stats[$num] ?? ['total_tested' => 0, 'ms_count' => 0, 'tms_count' => 0];
                $warna_sekarang = $b['warna'];
                $btn_style = ($warna_sekarang == 'warning') ? 'btn-warning text-dark' : 'btn-'.$warna_sekarang;
                $border_style = 'border-'.$warna_sekarang;
                $text_color = ($warna_sekarang == 'dark') ? 'text-secondary' : 'text-'.$warna_sekarang;
                $bulan_url = site_url('k-panel/laporan/parameter/bulanan/' . $num . '/' . $tahun);
            ?>
            <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                <div class="card month-card shadow-sm h-100 border-0 border-top <?= $border_style ?> border-3"
                     style="cursor: pointer;"
                     onclick="window.location='<?= $bulan_url ?>'">
                    
                    <div class="card-header bg-white py-3 border-bottom-0 d-flex justify-content-between align-items-center">
                        <h6 class="card-title fw-bold text-dark mb-0">
                            <i class="far fa-calendar-alt me-1 <?= $text_color ?>"></i>
                            <?= $b['nama'] ?>
                        </h6>
                        <span class="badge <?= $stats['total_tested'] > 0 ? 'bg-primary' : 'bg-light text-muted border' ?>">
                            <?= $stats['total_tested'] ?> Diuji
                        </span>
                    </div>

                    <div class="card-body text-center d-flex flex-column justify-content-between py-3">
                        <div>
                            <div class="month-icon-circle shadow-xs">
                                <i class="fas <?= $b['icon'] ?> fs-3 <?= $text_color ?>" style="opacity: 0.9;"></i>
                            </div>

                            <!-- RINGKASAN MS / TMS -->
                            <div class="d-flex justify-content-center gap-1 flex-wrap mb-3">
                                <span class="stat-pill stat-pill-ms" title="Memenuhi Syarat">
                                    <i class="fas fa-check-circle text-success"></i> <?= $stats['ms_count'] ?> MS
                                </span>
                                <span class="stat-pill stat-pill-tms" title="Tidak Memenuhi Syarat">
                                    <i class="fas fa-times-circle text-danger"></i> <?= $stats['tms_count'] ?> TMS
                                </span>
                            </div>
                        </div>

                        <div class="d-grid mt-auto">
                            <a href="<?= $bulan_url ?>" 
                               class="btn <?= $btn_style ?> shadow-xs rounded-3 px-3 py-2 fw-semibold" 
                               style="font-size: 0.85rem;"
                               onclick="event.stopPropagation();">
                                <i class="fas fa-chart-pie me-1"></i>
                                Buka Laporan
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- ACCORDION: PENCARIAN & FILTER KUSTOM PARAMETER (BACKWARD COMPATIBILITY) -->
    <div class="card border-0 shadow-sm rounded-4 mt-5">
        <div class="card-header bg-white py-3 border-bottom">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                <h5 class="fw-bold text-dark mb-0">
                    <i class="fas fa-search me-2 text-primary"></i>
                    Pencarian Cepat Parameter & Filter Rentang Tanggal
                </h5>
                <button class="btn btn-sm btn-outline-primary" type="button" data-bs-toggle="collapse" data-bs-target="#customFilterCollapse" aria-expanded="<?= $has_custom ? 'true' : 'false' ?>">
                    <i class="fas fa-sliders-h me-1"></i> Toggle Filter Kustom
                </button>
            </div>
        </div>
        <div class="collapse <?= $has_custom ? 'show' : '' ?>" id="customFilterCollapse">
            <div class="card-body p-4 bg-light">
                <form method="get" action="<?= site_url('k-panel/laporan/parameter') ?>" class="row g-2 align-items-center">
                    <input type="hidden" name="tahun" value="<?= $tahun ?>">
                    <div class="col-md-5">
                        <input type="text" name="q" value="<?= $q_val ?>" class="form-control" placeholder="Cari nama parameter atau jenis sampel...">
                    </div>
                    <div class="col-md-3">
                        <div class="d-flex align-items-center gap-2">
                            <span class="text-muted small fw-semibold" style="white-space:nowrap;">Dari:</span>
                            <input type="month" name="dari" value="<?= $dari_val ?>" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="d-flex align-items-center gap-2">
                            <span class="text-muted small fw-semibold" style="white-space:nowrap;">Sampai:</span>
                            <input type="month" name="sampai" value="<?= $sampai_val ?>" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-1 d-flex gap-2">
                        <button type="submit" class="btn btn-primary w-100"><i class="fas fa-search"></i></button>
                    </div>
                </form>

                <?php if ($has_custom): ?>
                    <hr class="my-4">
                    <h5 class="fw-bold mb-3">Hasil Pencarian Kustom:</h5>
                    <?php foreach ($custom_results as $month => $groups): ?>
                        <div class="card mb-3 border-0 shadow-sm rounded-3">
                            <div class="card-header bg-white fw-bold">
                                Periode: <?= htmlspecialchars($month) ?> (<?= count($groups) ?> Parameter)
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-hover table-striped align-middle mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Parameter Pemeriksaan</th>
                                                <th>Satuan</th>
                                                <th>Baku Mutu</th>
                                                <th class="text-center">Total Diuji</th>
                                                <th class="text-center">MS</th>
                                                <th class="text-center">TMS</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($groups as $grp): ?>
                                                <tr>
                                                    <td class="fw-bold text-dark"><?= htmlspecialchars($grp['nama_pemeriksaan']) ?></td>
                                                    <td><?= htmlspecialchars($grp['satuan'] ?: '-') ?></td>
                                                    <td><?= htmlspecialchars($grp['baku_mutu'] ?: '-') ?></td>
                                                    <td class="text-center fw-semibold"><?= (int)($grp['total_ms'] + $grp['total_tms']) ?></td>
                                                    <td class="text-center">
                                                        <?php if ($grp['total_ms'] > 0): ?>
                                                            <a href="javascript:void(0)" onclick="showSamples(<?= (int)$grp['master_id'] ?>, '<?= htmlspecialchars(addslashes($grp['nama_pemeriksaan'])) ?>', '<?= htmlspecialchars($month) ?>', 'MS')" class="badge bg-success text-decoration-none px-2 py-1">
                                                                <?= (int)$grp['total_ms'] ?> MS
                                                            </a>
                                                        <?php else: ?>
                                                            <span class="text-muted">0</span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td class="text-center">
                                                        <?php if ($grp['total_tms'] > 0): ?>
                                                            <a href="javascript:void(0)" onclick="showSamples(<?= (int)$grp['master_id'] ?>, '<?= htmlspecialchars(addslashes($grp['nama_pemeriksaan'])) ?>', '<?= htmlspecialchars($month) ?>', 'TMS')" class="badge bg-danger text-decoration-none px-2 py-1">
                                                                <?= (int)$grp['total_tms'] ?> TMS
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
                <?php endif; ?>
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
let currentMasterId = 0;
let currentNama = '';
let currentBulan = '';
let currentStatus = '';
let currentQuery = '';

function showSamples(masterId, nama, bulan, status, query) {
    currentMasterId = masterId;
    currentNama = nama;
    currentBulan = bulan;
    currentStatus = status || '';
    currentQuery = query || '';

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
              '&q=' + encodeURIComponent(currentQuery) + 
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

            var total = json.total || 0;
            var totalPages = Math.max(1, Math.ceil(total / per_page));
            
            if (totalPages > 1) {
                var pagination = document.createElement('nav');
                pagination.className = 'mt-3 d-flex justify-content-center';
                var ulp = document.createElement('ul');
                ulp.className = 'pagination mb-0';

                function makePageItem(p, label, disabled){
                    var li = document.createElement('li');
                    li.className = 'page-item' + (disabled ? ' disabled' : '');
                    var a = document.createElement('a');
                    a.className = 'page-link';
                    a.href = '#';
                    a.innerHTML = label;
                    a.addEventListener('click', function(ev){ ev.preventDefault(); if(!disabled) fetchSamplesPage(p); });
                    li.appendChild(a);
                    return li;
                }

                ulp.appendChild(makePageItem(page-1, '&laquo;', page<=1));
                var start = Math.max(1, page-2);
                var end = Math.min(totalPages, page+2);
                for(var pi=start; pi<=end; pi++){
                    var li = makePageItem(pi, pi, false);
                    if(pi === page) li.classList.add('active');
                    ulp.appendChild(li);
                }
                ulp.appendChild(makePageItem(page+1, '&raquo;', page>=totalPages));
                pagination.appendChild(ulp);
                listDiv.appendChild(pagination);
            }
        })
        .catch(function(err){
            document.getElementById('parameterSamplesList').innerHTML = '<div class="alert alert-danger m-3">Terjadi kesalahan saat mengambil data.</div>';
            console.error(err);
        });
}
</script>

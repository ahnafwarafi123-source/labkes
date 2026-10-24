<?php
defined('BASEPATH') or exit('No direct script access allowed');

// Helper function to normalize category
if (!function_exists('normalize_cat')) {
    function normalize_cat($cat)
    {
        $cat = strtolower(trim($cat));
        if ($cat === 'urinalisis' || $cat === 'urinalisa') {
            return 'urinalisa';
        }
        return $cat;
    }
}

// Helper function to check test result against reference range
if (!function_exists('check_rujukan_status')) {
    function check_rujukan_status($val, $rujukan, $gender)
    {
        if ($val === null || $val === '') return 'empty';

        $val = trim($val);
        $val_num = null;
        if (!is_numeric($val)) {
            if (preg_match('/([0-9]+(?:\.[0-9]+)?)/', $val, $matches)) {
                $val_num = floatval($matches[1]);
            } else {
                return 'Normal';
            }
        } else {
            $val_num = floatval($val);
        }

        $clean_rujukan = $rujukan;

        // Handle gender-specific references: "Laki-laki: 0–10 | Wanita: 0–20"
        if (stripos($rujukan, '|') !== false) {
            $parts = explode('|', $rujukan);
            foreach ($parts as $part) {
                if (stripos($gender, 'wanita') !== false || stripos($gender, 'perempuan') !== false || stripos($gender, 'p') === 0) {
                    if (stripos($part, 'wanita') !== false || stripos($part, 'perempuan') !== false || stripos($part, 'p') !== false) {
                        $clean_rujukan = $part;
                    }
                } else {
                    if (stripos($part, 'laki') !== false || stripos($part, 'pria') !== false || stripos($part, 'l') !== false) {
                        $clean_rujukan = $part;
                    }
                }
            }
        }
        // Handle gender-specific references: "(P) 2.4–5.7; (L) 3.4–7.0"
        if (stripos($rujukan, ';') !== false) {
            $parts = explode(';', $rujukan);
            foreach ($parts as $part) {
                if (stripos($gender, 'wanita') !== false || stripos($gender, 'perempuan') !== false || stripos($gender, 'p') === 0) {
                    if (stripos($part, '(P)') !== false || stripos($part, 'wanita') !== false || stripos($part, 'perempuan') !== false) {
                        $clean_rujukan = $part;
                    }
                } else {
                    if (stripos($part, '(L)') !== false || stripos($part, 'laki') !== false || stripos($part, 'pria') !== false) {
                        $clean_rujukan = $part;
                    }
                }
            }
        }

        // Clean prefixes/labels
        $clean_rujukan = preg_replace('/(?:laki-laki|wanita|perempuan|pria|\(l\)|\(p\))\s*:\s*/i', '', $clean_rujukan);
        $clean_rujukan = preg_replace('/\([lp]\)\s*/i', '', $clean_rujukan);
        $clean_rujukan = trim($clean_rujukan);

        // Evaluate range
        if (preg_match('/([0-9]+(?:\.[0-9]+)?)\s*[\–\-–—]\s*([0-9]+(?:\.[0-9]+)?)/u', $clean_rujukan, $m)) {
            $min = floatval($m[1]);
            $max = floatval($m[2]);
            if ($val_num < $min) return 'Rendah';
            if ($val_num > $max) return 'Tinggi';
            return 'Normal';
        }

        // Limit: "<200"
        if (preg_match('/<\s*([0-9]+(?:\.[0-9]+)?)/', $clean_rujukan, $m)) {
            $limit = floatval($m[1]);
            if ($val_num >= $limit) return 'Tinggi';
            return 'Normal';
        }

        // Limit: ">40"
        if (preg_match('/>\s*([0-9]+(?:\.[0-9]+)?)/', $clean_rujukan, $m)) {
            $limit = floatval($m[1]);
            if ($val_num <= $limit) return 'Rendah';
            return 'Normal';
        }

        return 'Normal';
    }
}
?>

<div class="content-wrapper">
    <section class="content pt-4">
        <div class="container-fluid">

            <div class="d-flex justify-content-start mb-3">
                <div class="col-md-8 col-12 d-flex justify-content-start">
                    <a href="<?= site_url('klinik/uji_rekam_klinik') ?>"
                        class="btn btn-secondary text-white px-4 rounded-1 shadow-sm" style="font-weight: 500;">
                        <i class="fas fa-arrow-left me-1"></i> Kembali
                    </a>
                </div>
            </div>

            <!-- Card Identitas Pasien -->
            <div class="card shadow-sm border-0 mb-4 rounded-3 d-flex flex-column h-100">
                <div class="card-header bg-light border-0 py-3">
                    <h5 class="mb-0 text-secondary" style="font-weight: 500;">Identitas Pasien</h5>
                </div>
                <div class="card-body bg-white rounded-bottom-3" style="font-size: 14px;">
                    <div class="row">
                        <div class="col-12 col-md-6 mb-3 mb-md-0 border-end">
                            <table class="table table-borderless table-sm mb-0">
                                <tr>
                                    <td width="35%" class="fw-bold text-dark">Nama</td>
                                    <td class="text-secondary">: <?= htmlspecialchars($pasien->nama_pasien) ?></td>
                                </tr>
                                <tr>
                                    <td class="fw-bold text-dark">NIK</td>
                                    <td class="text-secondary">: <?= htmlspecialchars($pasien->nik) ?></td>
                                </tr>
                                <tr>
                                    <td class="fw-bold text-dark">Jenis Kelamin</td>
                                    <td class="text-secondary">: <?= htmlspecialchars($pasien->gender) ?></td>
                                </tr>
                                <tr>
                                    <td class="fw-bold text-dark">Agama</td>
                                    <td class="text-secondary">:
                                        <?= isset($pasien->agama) && $pasien->agama != '' ? htmlspecialchars($pasien->agama) : '-' ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fw-bold text-dark">Status Pernikahan</td>
                                    <td class="text-secondary">:
                                        <?= isset($pasien->status_nikah) && $pasien->status_nikah != '' ? htmlspecialchars($pasien->status_nikah) : '-' ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fw-bold text-dark">Pendidikan</td>
                                    <td class="text-secondary">:
                                        <?= isset($pasien->pendidikan) && $pasien->pendidikan != '' ? htmlspecialchars($pasien->pendidikan) : '-' ?>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-12 col-md-6 ps-md-4">
                            <table class="table table-borderless table-sm mb-0">
                                <tr>
                                    <td width="40%" class="fw-bold text-dark">No. RM</td>
                                    <td class="text-secondary">: <?= htmlspecialchars($pasien->no_rm) ?></td>
                                </tr>
                                <tr>
                                    <td class="fw-bold text-dark">Tanggal Lahir / Umur</td>
                                    <td class="text-secondary">
                                        : <?= date('d M Y', strtotime($pasien->tgl_lahir)) ?> / <?= $pasien->umur ?> thn
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fw-bold text-dark">No. Telepon</td>
                                    <td class="text-secondary">:
                                        <?= isset($pasien->no_telp) && $pasien->no_telp != '' ? htmlspecialchars($pasien->no_telp) : '-' ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fw-bold text-dark">Pekerjaan</td>
                                    <td class="text-secondary">:
                                        <?= isset($pasien->pekerjaan) && $pasien->pekerjaan != '' ? htmlspecialchars($pasien->pekerjaan) : '-' ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fw-bold text-dark">Alamat Lengkap</td>
                                    <td class="text-secondary">:
                                        <?= isset($pasien->alamat) && $pasien->alamat != '' ? htmlspecialchars($pasien->alamat) : '-' ?>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- TABS SECTION -->
            <div class="card shadow-sm border-0 rounded-3 overflow-hidden mb-5">
                <div class="card-header p-2 bg-white border-bottom-0">
                    <ul class="nav nav-pills flex-column flex-md-row" id="pemeriksaan-tabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link active fw-bold" id="tab-riwayat-kunjungan-btn" data-bs-toggle="tab" href="#tab_riwayat_kunjungan" role="tab" aria-controls="tab_riwayat_kunjungan" aria-selected="true">
                                <i class="fas fa-list me-2 text-primary"></i> Riwayat Kunjungan
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link fw-bold" id="tab-comparison-btn" data-bs-toggle="tab" href="#tab_comparison" role="tab" aria-controls="tab_comparison" aria-selected="false">
                                <i class="fas fa-chart-line me-2 text-success"></i> Ringkasan Tren Hasil Lab (Tabel Comparison)
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="card-body bg-light p-4">
                    <div class="tab-content" id="pemeriksaan-tabs-content">
                        
                        <!-- TAB 1: RIWAYAT KUNJUNGAN -->
                        <div class="tab-pane fade show active" id="tab_riwayat_kunjungan" role="tabpanel" aria-labelledby="tab-riwayat-kunjungan-btn">
                            <h5 class="text-secondary mb-3 fw-bold"><i class="fas fa-list me-1 text-primary"></i> Riwayat Hasil Pemeriksaan Uji Lab Klinik</h5>
                            <div class="card border-0 shadow-sm rounded-3">
                                <div class="card-body p-3 p-md-4 table-responsive bg-white">
                                    <table id="tabel-detail-kunjungan" class="table table-bordered table-hover w-100" style="font-size: 14px;">
                                        <thead style="background-color: #f8f9fa;">
                                            <tr>
                                                <th width="5%" class="text-center align-middle py-3">No</th>
                                                <th width="15%" class="align-middle py-3">No. Register</th>
                                                <th width="15%" class="align-middle py-3">Tanggal Form</th>
                                                <th width="20%" class="align-middle py-3">Dokter Pengirim</th>
                                                <th width="20%" class="align-middle py-3">Diagnosa Klinis</th>
                                                <th width="15%" class="align-middle py-3">Status Hasil</th>
                                                <th width="10%" class="align-middle py-3 text-center">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if (!empty($riwayat)): ?>
                                                <?php $no = 1;
                                                foreach ($riwayat as $r): ?>
                                                    <tr>
                                                        <td class="text-center align-middle"><?= $no++ ?></td>
                                                        <td class="align-middle"><?= htmlspecialchars($r->no_register) ?></td>
                                                        <td class="align-middle text-secondary">
                                                            <?php
                                                            $indo_months = [1 => 'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
                                                            $t = strtotime($r->tgl_form);
                                                            echo date('d', $t) . ' ' . $indo_months[(int) date('m', $t)] . ' ' . date('Y', $t);
                                                            ?>
                                                        </td>
                                                        <td class="align-middle"><?= htmlspecialchars($r->nama_dokter) ?></td>
                                                        <td class="align-middle text-secondary"><?= htmlspecialchars($r->diagnosa_klinis) ?></td>
                                                        <td class="align-middle">
                                                            <?php if ($r->hasil_diisi > 0): ?>
                                                                <span class="badge bg-success" style="padding: 6px 12px; font-weight: 500;">Sudah Diinput</span>
                                                            <?php else: ?>
                                                                <span class="badge bg-warning" style="padding: 6px 12px; font-weight: 500;">Belum Diinput</span>
                                                            <?php endif; ?>
                                                        </td>
                                                        <td class="align-middle text-center">
                                                            <?php if ($r->hasil_diisi > 0): ?>
                                                                <a href="<?= site_url('klinik/laporan_uji_klinik/detail/' . $r->id) ?>" class="btn btn-sm btn-info text-white shadow-sm" style="font-size:12px; font-weight:500;">
                                                                    <i class="fas fa-eye me-1"></i> Lihat Hasil
                                                                </a>
                                                            <?php else: ?>
                                                                <?php
                                                                $user_role = strtolower((string) $this->session->userdata('klinik_role'));
                                                                if ($user_role === 'admin' || $user_role === 'pemeriksa sampel'):
                                                                ?>
                                                                    <a href="<?= site_url('klinik/uji_klinik/input/' . $r->id) ?>" class="btn btn-sm btn-primary text-white shadow-sm" style="font-size:12px; font-weight:500;">
                                                                        <i class="fas fa-edit me-1"></i> Input Hasil
                                                                    </a>
                                                                <?php else: ?>
                                                                    <span class="text-muted" style="font-size: 12px; font-style: italic;">Menunggu Input</span>
                                                                <?php endif; ?>
                                                            <?php endif; ?>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- TAB 2: TABEL COMPARISON & SPARKLINE -->
                        <div class="tab-pane fade" id="tab_comparison" role="tabpanel" aria-labelledby="tab-comparison-btn">
                            <h5 class="text-secondary mb-3 fw-bold"><i class="fas fa-chart-line me-1 text-success"></i> Ringkasan Tren Hasil Lab (Tabel Comparison)</h5>
                            <div class="card border-0 shadow-sm rounded-3 overflow-hidden bg-white">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover align-middle mb-0 text-center" style="font-size: 14px; border-collapse: collapse; min-width: 900px;">
                                        <thead class="table-light">
                                            <tr>
                                                <th rowspan="2" class="align-middle py-3 px-4 text-start bg-light text-dark fw-bold" style="width: 20%; border-bottom: 2px solid #dee2e6;">Parameter</th>
                                                <th rowspan="2" class="align-middle py-3 px-3 text-center bg-light text-dark fw-bold" style="width: 15%; border-bottom: 2px solid #dee2e6;">Nilai Rujukan<br>+ Satuan</th>
                                                <th colspan="<?= !empty($visits) ? count($visits) : 1 ?>" class="py-2 text-center text-uppercase fw-bold text-secondary bg-light" style="font-size: 12px; border-bottom: 1px solid #dee2e6;">
                                                    Hista Tren
                                                </th>
                                                <th rowspan="2" class="align-middle py-3 px-3 text-center bg-light text-dark fw-bold" style="width: 22%; border-bottom: 2px solid #dee2e6;">Status Tren</th>
                                            </tr>
                                            <tr>
                                                <?php if (!empty($visits)): ?>
                                                    <?php foreach ($visits as $index => $v): ?>
                                                        <th class="py-2 px-2 text-center text-dark align-middle fw-semibold" style="background-color: #fdfdfd; font-size: 13px; border-bottom: 2px solid #dee2e6;">
                                                            <?php
                                                            $t = strtotime($v->tgl_form);
                                                            $formatted_date = date('d M Y', $t);
                                                            echo $formatted_date;
                                                            echo '<br><span class="badge bg-light text-dark border border-secondary-subtle py-1 px-2 rounded mt-1 font-monospace" style="font-size: 11px; font-weight: 500;">' . htmlspecialchars($v->no_register) . '</span>';
                                                            if ($index === 0) {
                                                                echo '<br><span class="badge bg-success-subtle text-success border border-success-subtle py-1 px-2 rounded mt-1 font-monospace" style="font-size: 10px;">(Hari ini)</span>';
                                                            } elseif ($index === 1) {
                                                                echo '<br><span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle py-1 px-2 rounded mt-1 font-monospace" style="font-size: 10px;">(Kunjungan Lalu)</span>';
                                                            
                                                            } else {
                                                                echo '<br><span class="text-muted font-monospace" style="font-size: 11px;">' . date('H:i', $t) . ' WIB</span>';
                                                            }
                                                            ?>
                                                        </th>
                                                    <?php endforeach; ?>
                                                <?php else: ?>
                                                    <th class="py-2 text-muted" style="border-bottom: 2px solid #dee2e6;">Tidak ada kunjungan</th>
                                                <?php endif; ?>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if (!empty($params)): ?>
                                                <?php foreach ($params as $p):
                                                    $param_key = normalize_cat($p['category']) . '_' . strtolower(trim($p['name']));

                                                    // Collect historical values for this parameter chronologically (oldest to newest)
                                                    $history_values = [];
                                                    $history_statuses = [];
                                                    if (!empty($visits)) {
                                                        foreach (array_reverse($visits) as $v) {
                                                            if (isset($results[$v->id][$param_key])) {
                                                                $val = $results[$v->id][$param_key]['hasil'];
                                                                if ($val !== null && $val !== '') {
                                                                    $history_values[] = $val;
                                                                    $history_statuses[] = check_rujukan_status($val, $p['rujukan'], $pasien->gender);
                                                                }
                                                            }
                                                        }
                                                    }
                                                ?>
                                                    <tr>
                                                        <!-- Parameter Name -->
                                                        <td class="text-start py-3 px-4 align-middle">
                                                            <div class="fw-bold text-dark" style="font-size: 15px;"><?= html_escape($p['name']) ?></div>
                                                            <small class="text-muted text-uppercase" style="font-size: 10px; letter-spacing: 0.5px;"><?= html_escape($p['category']) ?></small>
                                                        </td>

                                                        <!-- Reference Range & Unit -->
                                                        <td class="align-middle text-secondary fw-semibold">
                                                            <?= html_escape($p['rujukan']) ?>
                                                            <div class="text-muted small mt-0.5"><?= !empty($p['unit']) ? html_escape($p['unit']) : '-' ?></div>
                                                        </td>

                                                        <!-- Visit History Columns -->
                                                        <?php if (!empty($visits)): ?>
                                                            <?php foreach ($visits as $v):
                                                                $has_val = isset($results[$v->id][$param_key]);
                                                                $val = $has_val ? $results[$v->id][$param_key]['hasil'] : '';

                                                                $status = 'empty';
                                                                $bg_style = '';
                                                                $badge_html = '';

                                                                if ($has_val && $val !== '') {
                                                                    $status = check_rujukan_status($val, $p['rujukan'], $pasien->gender);
                                                                    if ($status === 'Tinggi') {
                                                                        $bg_style = 'background-color: #fff5f5;'; // soft red highlight
                                                                        $badge_html = '<div class="text-danger fw-bold d-flex align-items-center justify-content-center" style="font-size:14px;"><i class="fas fa-exclamation-triangle me-1 text-danger"></i> ' . html_escape($val) . ' <span class="text-danger font-monospace ms-1" style="font-size:11px;">Tinggi</span></div>';
                                                                    } elseif ($status === 'Rendah') {
                                                                        $bg_style = 'background-color: #f0f7ff;'; // soft blue highlight
                                                                        $badge_html = '<div class="text-primary fw-bold d-flex align-items-center justify-content-center" style="font-size:14px;"><i class="fas fa-arrow-down me-1 text-primary"></i> ' . html_escape($val) . ' <span class="text-primary font-monospace ms-1" style="font-size:11px;">Rendah</span></div>';
                                                                    } else {
                                                                        $bg_style = 'background-color: #f6fcf9;'; // soft green highlight
                                                                        $badge_html = '<div class="text-success d-flex align-items-center justify-content-center" style="font-size:14px;"><i class="fas fa-check-circle me-1 text-success"></i> ' . html_escape($val) . ' <span class="text-success font-monospace ms-1" style="font-size:11px;">Normal</span></div>';
                                                                    }
                                                                } else {
                                                                    $badge_html = '<span class="text-muted">-</span>';
                                                                }
                                                            ?>
                                                                <td class="align-middle" style="<?= $bg_style ?> border-right: 1px solid #dee2e6;">
                                                                    <?= $badge_html ?>
                                                                </td>
                                                            <?php endforeach; ?>
                                                        <?php else: ?>
                                                            <td class="align-middle text-muted">-</td>
                                                        <?php endif; ?>

                                                        <!-- Status Tren (Mini Sparkline Chart) -->
                                                        <td class="align-middle py-2 px-3 text-center" style="background-color: #fafafa;">
                                                            <?php
                                                            $cnt = count($history_values);
                                                            if ($cnt >= 1):
                                                                $numeric_vals = [];
                                                                foreach ($history_values as $val) {
                                                                    if (is_numeric($val)) {
                                                                        $numeric_vals[] = floatval($val);
                                                                    } else {
                                                                        preg_match('/([0-9]+(?:\.[0-9]+)?)/', $val, $matches);
                                                                        $numeric_vals[] = isset($matches[1]) ? floatval($matches[1]) : 0;
                                                                    }
                                                                }

                                                                // Draw SVG sparkline
                                                                $svg_path = '';
                                                                $points = [];
                                                                $stroke_color = '#28a745'; // default green (Normal)
                                                                $fill_color = 'rgba(40, 167, 69, 0.05)';

                                                                // Determine stroke/fill color based on newest status
                                                                $latest_status = end($history_statuses);
                                                                if ($latest_status === 'Tinggi') {
                                                                    $stroke_color = '#dc3545'; // red
                                                                    $fill_color = 'rgba(220, 53, 69, 0.05)';
                                                                } elseif ($latest_status === 'Rendah') {
                                                                    $stroke_color = '#007bff'; // blue
                                                                    $fill_color = 'rgba(0, 123, 255, 0.05)';
                                                                }

                                                                if ($cnt > 1) {
                                                                    $min_v = min($numeric_vals);
                                                                    $max_v = max($numeric_vals);
                                                                    $range_v = $max_v - $min_v;

                                                                    for ($i = 0; $i < $cnt; $i++) {
                                                                        $x = ($i / ($cnt - 1)) * 80 + 10;
                                                                        $y = ($range_v == 0) ? 15 : 25 - (($numeric_vals[$i] - $min_v) / $range_v * 20);
                                                                        $points[] = "$x,$y";
                                                                    }
                                                                    $svg_path = 'M ' . implode(' L ', $points);
                                                                }
                                                            ?>
                                                                <div class="d-flex flex-column align-items-center">
                                                                    <!-- SVG Sparkline -->
                                                                    <?php if ($cnt > 1): ?>
                                                                        <svg width="100" height="30" viewBox="0 0 100 30" style="overflow: visible;">
                                                                            <!-- Gradient area -->
                                                                            <path d="M 10 25 L <?= implode(' L ', $points) ?> L 90 25 Z" fill="<?= $fill_color ?>" stroke="none" />
                                                                            <!-- Line path -->
                                                                            <path d="<?= $svg_path ?>" fill="none" stroke="<?= $stroke_color ?>" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                                                            <!-- Data dots -->
                                                                            <?php foreach ($points as $p_str):
                                                                                $xy = explode(',', $p_str);
                                                                            ?>
                                                                                <circle cx="<?= $xy[0] ?>" cy="<?= $xy[1] ?>" r="2.5" fill="<?= $stroke_color ?>" />
                                                                            <?php endforeach; ?>
                                                                        </svg>
                                                                    <?php else: ?>
                                                                        <div class="text-muted small italic">Satu Kunjungan</div>
                                                                    <?php endif; ?>

                                                                    <!-- Status Text -->
                                                                    <div class="mt-1" style="font-size: 11px; line-height: 1.2;">
                                                                        <?php
                                                                        $latest_val = end($history_values);
                                                                        $latest_stat = end($history_statuses);

                                                                        if ($cnt > 1) {
                                                                            $prev_val = $history_values[$cnt - 2];
                                                                            $prev_stat = $history_statuses[$cnt - 2];

                                                                            echo '<span class="fw-semibold text-secondary font-monospace">' . html_escape($prev_val) . ' &rarr; ' . html_escape($latest_val) . '</span><br>';

                                                                            // Evaluate trend description
                                                                            if ($latest_val == $prev_val) {
                                                                                echo '<span class="text-muted fw-bold">Stabil</span>';
                                                                            } elseif ($latest_stat === 'Normal' && $prev_stat !== 'Normal') {
                                                                                echo '<span class="text-success fw-bold"><i class="fas fa-check"></i> Membaik</span>';
                                                                            } elseif ($latest_stat !== 'Normal' && $prev_stat === 'Normal') {
                                                                                if ($latest_stat === 'Tinggi') {
                                                                                    echo '<span class="text-danger fw-bold"><i class="fas fa-arrow-up"></i> Naik (Abnormal)</span>';
                                                                                } else {
                                                                                    echo '<span class="text-primary fw-bold"><i class="fas fa-arrow-down"></i> Turun (Abnormal)</span>';
                                                                                }
                                                                            } else {
                                                                                $n_latest = end($numeric_vals);
                                                                                $n_prev = $numeric_vals[$cnt - 2];
                                                                                if ($n_latest > $n_prev) {
                                                                                    echo '<span class="text-warning-emphasis fw-bold">Sedikit Naik</span>';
                                                                                } else {
                                                                                    echo '<span class="text-warning-emphasis fw-bold">Sedikit Turun</span>';
                                                                                }
                                                                            }
                                                                        } else {
                                                                            echo '<span class="fw-semibold text-secondary font-monospace">' . html_escape($latest_val) . '</span><br>';
                                                                            if ($latest_stat === 'Tinggi') {
                                                                                echo '<span class="text-danger fw-bold">Tinggi</span>';
                                                                            } elseif ($latest_stat === 'Rendah') {
                                                                                echo '<span class="text-primary fw-bold">Rendah</span>';
                                                                            } else {
                                                                                echo '<span class="text-success fw-bold">Normal</span>';
                                                                            }
                                                                        }
                                                                        ?>
                                                                    </div>
                                                                </div>
                                                            <?php else: ?>
                                                                <span class="text-muted small font-italic">No Data</span>
                                                            <?php endif; ?>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <tr>
                                                    <td colspan="4" class="text-center text-muted py-4">Belum ada riwayat hasil pemeriksaan untuk pasien ini.</td>
                                                </tr>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </section>
</div>

<script>
    $(document).ready(function () {
        var table = $('#tabel-detail-kunjungan').DataTable({
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json",
                "emptyTable": "Belum ada riwayat pemeriksaan."
            },
            "lengthMenu": [[10, 25, 50, -1], ["10 Baris", "25 Baris", "50 Baris", "Semua"]],
            "searching": false,
            "info": false
        });

        // Recalculate columns when tabs are toggled
        var triggerTabList = [].slice.call(document.querySelectorAll('a[data-bs-toggle="tab"]'))
        triggerTabList.forEach(function (triggerEl) {
            triggerEl.addEventListener('shown.bs.tab', function (event) {
                table.columns.adjust().draw();
            })
        });
    });
</script>
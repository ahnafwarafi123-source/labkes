<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<!-- CSS & Fonts -->
<link rel="stylesheet" href="<?= base_url('assets/css/dashboard.css') ?>">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<div class="content-wrapper px-4 pt-4 pb-5" style="background-color: #f4f7f6;">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="fw-bold text-dark m-0" style="font-family: 'Inter', sans-serif;">Dashboard Analytics</h2>
    </div>

    <!-- Welcome Alert -->
    <div class="custom-alert mb-4 bg-white shadow-sm border rounded-3 p-3 d-flex align-items-center" style="border-left: 4px solid #4bc0c0 !important;">
        <span class="fs-4 me-3">👋</span>
        <div class="text-dark">
            Halo, Dokter <span class="fw-bold" style="color: #4bc0c0;"><?= html_escape($this->session->userdata('klinik_nama')) ?></span>. Anda login sebagai dokter hari ini.
        </div>
    </div>

    <!-- Top 3 Cards Row -->
    <div class="row mb-4">
        <!-- Jumlah Pasien -->
        <div class="col-lg-4 mb-3 mb-lg-0">
            <div class="total-card">
                <div class="total-card-left">
                    <div class="total-card-icon"><i class="fas fa-users"></i></div>
                    <div class="total-card-info">
                        <div class="tulisan-judul">Total Pasien Unik Hari Ini</div>
                        <div class="tulisan-hasil"><?= isset($total_pasien) ? $total_pasien : 0 ?></div>
                        <div class="total-card-trend"><i class="fas fa-check-circle"></i> Data Real-time</div>
                    </div>
                </div>
                <img src="<?= base_url('assets/img/doctor_patient.png') ?>" alt="Doctor Patient" class="total-card-image">
            </div>
        </div>

        <!-- 10 Diagnosa -->
        <div class="col-lg-4 mb-3 mb-lg-0">
            <div class="bg-white rounded-4 shadow-sm border p-4 h-97">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <div>
                        <h6 class="fw-bold text-dark mb-0">10 Diagnosa Penyakit Terbanyak</h6>
                        <small class="text-muted" style="font-size: 0.8rem;">Data Bulan Ini</small>
                    </div>
                    <div class="btn-group border rounded-3">
                        <button class="btn btn-light btn-sm px-2 text-muted border-end"><i class="fas fa-chart-bar"></i></button>
                        <button class="btn btn-light btn-sm px-2 text-muted border-end"><i class="fas fa-filter"></i></button>
                        <button class="btn btn-light btn-sm px-2 text-muted"><i class="fas fa-sliders-h"></i></button>
                    </div>
                </div>
                <div style="height: 180px; width: 100%;">
                    <canvas id="chartDiagnosa"></canvas>
                </div>
            </div>
        </div>

        <!-- Demografi Gender -->
        <div class="col-lg-4">
            <div class="warna-biru-muda">
                <div class="dashboard-card-header p-3">
                    <h3 class="tulisan-judul m-0">Demografi Gender Pasien (Hari Ini)</h3>
                </div>
                <div class="card-body d-flex align-items-center justify-content-center p-3">
                    <div class="chart-container" style="height: 250px; width: 100%;">
                        <canvas id="chartGender"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bottom Row: Table -->
    <div class="bg-white rounded-4 shadow-sm border p-4 mb-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="fw-bold m-0" style="color: #1e293b;">Daftar Pasien Hari Ini</h5>
            <div class="d-flex gap-2">
                <button class="btn btn-light btn-sm border text-muted"><i class="fas fa-filter"></i> Filter</button>
                <div class="input-group input-group-sm" style="width: 200px;">
                    <span class="input-group-text bg-light border-end-0"><i class="fas fa-search text-muted"></i></span>
                    <input type="text" class="form-control border-start-0 ps-0 bg-light" placeholder="Search Patient">
                </div>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-borderless table-hover align-middle mb-0" style="font-size: 0.9rem;">
                <thead style="border-bottom: 2px solid #edf2f7;">
                    <tr class="text-dark fw-bold">
                        <th class="pb-3">Jam Antrian</th>
                        <th class="pb-3">No. RM / No. Kunjungan</th>
                        <th class="pb-3">Nama Pasien</th>
                        <th class="pb-3">NIK</th>
                        <th class="pb-3 text-center">Umur</th>
                        <th class="pb-3">Gender</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($pasien_terbaru)): ?>
                        <?php foreach ($pasien_terbaru as $p): ?>
                            <tr style="border-bottom: 1px solid #f8fafc;">
                                <td class="py-3"><?= html_escape(date('H:i', strtotime($p->tgl_daftar))) ?></td>
                                <td><?= html_escape(!empty($p->no_rm) ? $p->no_rm : $p->no_registrasi) ?></td>
                                <td><?= html_escape($p->nama_pasien) ?></td>
                                <td><?= html_escape(!empty($p->nik) ? $p->nik : '-') ?></td>
                                <td class="text-center"><?= html_escape(!empty($p->umur) ? $p->umur : '-') ?></td>
                                <td><?= html_escape($p->jenis_kelamin) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">Belum ada pasien terdaftar hari ini.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <div class="d-flex justify-content-end mt-3">
            <button class="btn btn-sm fw-bold px-3 py-2 text-dark" style="background-color: #ccfbf1; border-radius: 8px;">Tampilkan Semua</button>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Plugin untuk teks di tengah Doughnut Chart
    const centerTextPlugin = {
        id: 'centerText',
        beforeDraw: function(chart) {
            if (chart.config.type !== 'doughnut') return;
            const {
                ctx,
                chartArea
            } = chart;
            if (!chartArea) return;
            ctx.restore();

            let total = 0;
            if (chart.data.datasets[0]) {
                chart.data.datasets[0].data.forEach(val => total += parseInt(val));
            }

            const meta = chart.getDatasetMeta(0);
            const firstArc = meta.data[0];
            const textX = firstArc ? firstArc.x : (chartArea.left + chartArea.right) / 2;
            const textY = firstArc ? firstArc.y - 8 : ((chartArea.top + chartArea.bottom) / 2) - 8;

            ctx.font = "bold 2.2rem 'Inter', sans-serif";
            ctx.textBaseline = "middle";
            ctx.textAlign = "center";
            ctx.fillStyle = "#2d3748";

            ctx.fillText(total, textX, textY);

            ctx.font = "600 0.9rem 'Inter', sans-serif";
            ctx.fillStyle = "#718096";
            ctx.fillText("Orang", textX, textY + 26);
            ctx.save();
        }
    };

    Chart.register(centerTextPlugin);

    document.addEventListener("DOMContentLoaded", function() {
        // 10 Diagnosa Chart
        const ctxDiagnosa = document.getElementById("chartDiagnosa").getContext("2d");
        new Chart(ctxDiagnosa, {
            type: 'bar',
            data: {
                labels: ["ISPA", "Hipertensi", "Dispepsia", "Sedang Diperiksa", "Elasinonsa", "Kavianda", "Hipertensi", "Sedang Diperiksa", "Hipertensi", "Dispepsia"],
                datasets: [{
                    data: [95, 72, 65, 55, 50, 48, 40, 35, 25, 20],
                    backgroundColor: [
                        '#4bc0c0', '#4bc0c0', '#4bc0c0', '#4bc0c0', '#4bc0c0',
                        '#c7b8ea', '#c7b8ea', '#c7b8ea', '#c7b8ea', '#c7b8ea'
                    ],
                    borderRadius: 4,
                    barThickness: 8
                }]
            },
            options: {
                indexAxis: 'y', // Horizontal bar chart
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        enabled: true
                    }
                },
                scales: {
                    x: {
                        grid: {
                            color: '#f1f5f9'
                        },
                        ticks: {
                            color: '#94a3b8',
                            font: {
                                size: 10
                            },
                            maxTicksLimit: 11
                        }
                    },
                    y: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            color: '#475569',
                            font: {
                                size: 10
                            }
                        }
                    }
                }
            }
        });

        // 2. Chart Demografi Gender
        fetch("<?= site_url('klinik/dashboard/get_demographic_data') ?>")
            .then(response => response.json())
            .then(result => {
                const ctxGender = document.getElementById("chartGender").getContext("2d");
                const labelsWithCount = result.labels.map((l, i) => `${l} (${result.data[i]} Orang)`);

                new Chart(ctxGender, {
                    type: "doughnut",
                    data: {
                        labels: labelsWithCount,
                        datasets: [{
                            data: result.data,
                            backgroundColor: ["#5c6bc0", "#ff8a65"],
                            borderColor: "#ffffff",
                            borderWidth: 4,
                            hoverOffset: 5
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        cutout: '75%',
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    usePointStyle: true,
                                    padding: 20,
                                    color: '#000000'
                                }
                            }
                        }
                    }
                });
            });
    });
</script>
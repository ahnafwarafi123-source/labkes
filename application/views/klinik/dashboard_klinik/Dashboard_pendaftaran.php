<!-- CSS & Fonts -->
<link rel="stylesheet" href="<?= base_url('assets/css/dashboard.css') ?>">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">

<div class="content-wrapper px-4 pt-4 pb-5">
  <!-- Title Header -->
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold text-dark m-0" style="font-family: 'Inter', sans-serif;">Dashboard Analytics</h2>
  </div>

  <!-- Welcome Alert -->
  <div class="custom-alert mb-4">
    <i class="fas fa-hand-sparkles me-3"></i>
    <div>
      <?php
      $sess_role_dash = $this->session->userdata('klinik_role');
      $display_role_dash = (strtolower((string) $sess_role_dash) == 'petugas rm') ? 'Dokter' : $sess_role_dash;
      ?>
      Halo, <span class="fw-bold text-primary"><?= html_escape($this->session->userdata('klinik_nama')) ?></span>. Anda login sebagai <span class="text-danger fw-bold"><?= html_escape($display_role_dash) ?></span>.
    </div>
  </div>

  <!-- Summary Cards & Chart Section -->
  <div class="row g-4 mb-4">
    <!-- Total Pasien Hari Ini -->
    <div class="col-lg-4 col-md-6">
      <div class="total-card h-100">
        <div class="total-card-left">
          <div class="total-card-icon"><i class="fas fa-users"></i></div>
          <div class="total-info">
            <div class="tulisan-judul">Total Pasien Hari Ini</div>
            <div class="tulisan-hasil"><?= isset($total_pasien) ? $total_pasien : 0 ?></div>
            <div class="total-card-trend"><i class="fas fa-check-circle"></i> Data Real-time</div>
          </div>
        </div>
        <img src="<?= base_url('assets/img/doctor_patient.png') ?>" alt="Doctor Patient" class="total-card-image">
      </div>
    </div>

    <!-- Total Pembayaran Hari Ini -->
    <div class="col-lg-4 col-md-6">
      <div class="dashboard-card-1 h-100">
        <div class="total-card-left">
          <div class="icon-circel-putih"><i class="fas fa-money-bill-wave icon-uang-vital"></i></div>
          <div class="total-info">
            <div class="tulisan-judul">Total Pembayaran Hari Ini</div>
            <div class="tulisan-hasil">Rp <?= number_format(isset($total_pendapatan) ? $total_pendapatan : 0, 0, ',', '.') ?></div>
          </div>
        </div>
      </div>
    </div>

    <!-- Demografi Gender Pasien -->
    <div class="col-lg-4 col-md-12">
      <div class="warna-biru-muda h-100">
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

  <!-- Table Section -->
  <div class="dashboard-card">
    <div class="dashboard-card-header d-flex justify-content-between align-items-center p-4 border-bottom-0">
      <h5 class="fw-bold m-0" style="color: #2d3748; font-size: 1.1rem;">Daftar Pasien Terbaru</h5>
      <div class="d-flex gap-2">
        <a href="<?= site_url('klinik/form_permintaan_klinik/create') ?>" class="btn btn-cyan btn-sm px-3 py-2 fw-bold shadow-sm d-flex align-items-center" style="border-radius: 8px;">
          <i class="fas fa-stethoscope me-2"></i> Pasien Klinik
        </a>
        <a href="<?= site_url('klinik/form_permintaan_rm/create') ?>" class="btn btn-primary btn-sm px-3 py-2 fw-bold shadow-sm d-flex align-items-center" style="border-radius: 8px; background-color: #3b82f6; border-color: #3b82f6;">
          <i class="fas fa-user-plus me-2"></i> Pasien Rekam Medis
        </a>
      </div>
    </div>
    
    <div class="px-4 pb-3 d-flex justify-content-end">
      <input type="text" class="search-input w-25" placeholder="Search...">
    </div>

    <div class="table-responsive px-4 pb-4">
      <table class="table table-hover table-custom table-bordered mb-0">
        <thead>
          <tr>
            <th>Waktu Daftar <i class="fas fa-sort text-muted ms-1 float-end mt-1"></i></th>
            <th>No. Registrasi <i class="fas fa-sort text-muted ms-1 float-end mt-1"></i></th>
            <th>Nama Pasien <i class="fas fa-sort text-muted ms-1 float-end mt-1"></i></th>
            <th>Jenis Kelamin <i class="fas fa-sort text-muted ms-1 float-end mt-1"></i></th>
          </tr>
        </thead>
        <tbody>
          <?php if (!empty($pasien_terbaru)): ?>
            <?php foreach ($pasien_terbaru as $p): ?>
              <tr>
                <td><?= html_escape($p->tgl_daftar) ?></td>
                <td><?= html_escape($p->no_registrasi) ?></td>
                <td><?= html_escape($p->nama_pasien) ?></td>
                <td><?= html_escape($p->jenis_kelamin) ?></td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr>
              <td colspan="4" class="text-center text-muted py-4">Belum ada pasien terdaftar hari ini.</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>

      <div class="d-flex justify-content-end mt-3">
        <nav>
          <ul class="pagination pagination-sm mb-0">
            <li class="page-item disabled"><a class="page-link text-muted" href="#" style="border-radius: 4px 0 0 4px;">Pagination</a></li>
            <li class="page-item active"><a class="page-link" href="#" style="background-color: #459a96; border-color: #459a96;">1</a></li>
            <li class="page-item"><a class="page-link text-dark" href="#" style="border-radius: 0 4px 4px 0;">Next ></a></li>
          </ul>
        </nav>
      </div>
    </div>
  </div>
</div>

<!-- Chart.js Library -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
  // Plugin untuk teks di tengah Doughnut Chart
  const centerTextPlugin = {
    id: 'centerText',
    beforeDraw: function(chart) {
      if (chart.config.type !== 'doughnut') return;
      const { ctx, chartArea } = chart;
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

      ctx.font = "bold 1.8rem 'Inter', sans-serif";
      ctx.textBaseline = "middle";
      ctx.textAlign = "center";
      ctx.fillStyle = "#1e293b";

      ctx.fillText(total, textX, textY);

      ctx.font = "600 0.85rem 'Inter', sans-serif";
      ctx.fillStyle = "#64748b";
      ctx.fillText("Orang", textX, textY + 22);
      ctx.save();
    }
  };

  Chart.register(centerTextPlugin);

  document.addEventListener("DOMContentLoaded", function() {

    // 1. Chart Statistik Kunjungan (Single Line - Total Pasien)
    const chartKunjunganElem = document.getElementById("chartKunjungan");
    if (chartKunjunganElem) {
      fetch("<?= site_url('klinik/dashboard/get_monthly_kunjungan_data') ?>")
        .then(response => response.json())
        .then(result => {
          const ctx = chartKunjunganElem.getContext("2d");

          let gradient = ctx.createLinearGradient(0, 0, 0, 400);
          gradient.addColorStop(0, "rgba(52, 152, 219, 0.4)");
          gradient.addColorStop(1, "rgba(52, 152, 219, 0.0)");

          new Chart(ctx, {
            type: "line",
            data: {
              labels: result.labels,
              datasets: [{
                label: "Total Pasien",
                data: result.data,
                borderColor: "#3498db",
                backgroundColor: gradient,
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointRadius: 4,
                pointBackgroundColor: "#fff"
              }]
            },
            options: {
              responsive: true,
              maintainAspectRatio: false,
              plugins: {
                legend: {
                  display: true,
                  position: 'top'
                }
              },
              scales: {
                y: {
                  beginAtZero: true,
                  ticks: {
                    stepSize: 1
                  }
                }
              }
            }
          });
        });
    }

    // 2. Chart Demografi Gender
    const chartGenderElem = document.getElementById("chartGender");
    if (chartGenderElem) {
      fetch("<?= site_url('klinik/dashboard/get_demographic_data') ?>")
        .then(response => response.json())
        .then(result => {
          const ctxGender = chartGenderElem.getContext("2d");
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
              cutout: '70%',
              plugins: {
                legend: {
                  position: 'bottom',
                  labels: {
                    usePointStyle: true,
                    padding: 15,
                    color: '#334155',
                    font: {
                      family: "'Inter', sans-serif",
                      size: 11
                    }
                  }
                }
              }
            }
          });
        });
    }
  });
</script>
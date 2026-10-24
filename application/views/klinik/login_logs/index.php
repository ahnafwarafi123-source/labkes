<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<!-- SweetAlert2 CSS & JS CDN -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="content-wrapper px-4 pt-4">
  <h3 class="text-dark fw-bold mb-4">
    <span>LOG RIWAYAT LOGIN KLINIK</span>
  </h3>
  <hr style="border-top: 2px solid #1e3a8a; margin-bottom: 20px;">

  <div class="card shadow-sm w-100 mb-4" style="border-radius: 12px; overflow: hidden; border: 1px solid #e2e8f0;">
    <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
      <h5 class="mb-0 fw-bold text-secondary"><i class="fas fa-history me-2 text-info"></i>Daftar Riwayat Percobaan Login</h5>
      <div class="d-flex align-items-center gap-2">
        <span class="badge bg-secondary p-2">Total Log: <?= count($logs); ?></span>
        <?php if (count($logs) > 0): ?>
          <button type="button" id="btn-clear-logs" class="btn btn-danger btn-sm rounded-3 shadow-sm px-3 fw-semibold">
            <i class="fas fa-trash-alt me-1"></i> Hapus Semua Log
          </button>
        <?php endif; ?>
      </div>
    </div>
    
    <!-- Kontainer pembungkus tabel agar tidak terpotong (overflow-x: auto) -->
    <div class="card-body p-3" style="overflow-x: auto;">
      <table id="tabel-login-logs" class="table table-bordered table-striped table-hover align-middle nowrap" style="width:100%">
        <thead class="bg-light text-dark">
          <tr>
            <th width="5%" class="text-center">No</th>
            <th>Nama User</th>
            <th>Username</th>
            <th class="text-center">Status</th>
            <th>IP Address</th>
            <th>User Agent</th>
            <th>Alasan Gagal</th>
            <th>Waktu Login</th>
          </tr>
        </thead>
        <tbody>
          <?php $no = 1; foreach ($logs as $log): ?>
            <tr>
              <td class="text-center"><?= $no++; ?></td>
              <td>
                <span class="fw-semibold">
                  <?= !empty($log['nama_user']) ? html_escape($log['nama_user']) : '<span class="text-muted italic">- Tidak Diketahui -</span>'; ?>
                </span>
              </td>
              <td><code><?= html_escape($log['username']); ?></code></td>
              <td class="text-center">
                <?php if ($log['status'] === 'success'): ?>
                  <span class="badge bg-success bg-opacity-10 text-success border border-success-subtle px-3 py-2 rounded-pill">
                    <i class="fas fa-check-circle me-1"></i> Success
                  </span>
                <?php elseif ($log['status'] === 'failed'): ?>
                  <span class="badge bg-danger bg-opacity-10 text-danger border border-danger-subtle px-3 py-2 rounded-pill">
                    <i class="fas fa-times-circle me-1"></i> Failed
                  </span>
                <?php elseif ($log['status'] === 'locked'): ?>
                  <span class="badge bg-warning bg-opacity-10 text-warning border border-warning-subtle px-3 py-2 rounded-pill">
                    <i class="fas fa-user-lock me-1"></i> Locked
                  </span>
                <?php else: ?>
                  <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary-subtle px-3 py-2 rounded-pill">
                    <?= html_escape($log['status']); ?>
                  </span>
                <?php endif; ?>
              </td>
              <td><span class="badge bg-light text-dark border px-2 py-1"><i class="fas fa-desktop me-1 text-muted"></i> <?= html_escape($log['ip_address']); ?></span></td>
              <td>
                <small class="text-muted d-inline-block text-truncate" style="max-width: 250px;" title="<?= html_escape($log['user_agent']); ?>">
                  <?= html_escape($log['user_agent']); ?>
                </small>
              </td>
              <td>
                <span class="text-danger small">
                  <?= !empty($log['failure_reason']) ? html_escape($log['failure_reason']) : '<span class="text-muted">-</span>'; ?>
                </span>
              </td>
              <td>
                <span class="text-muted small">
                  <i class="far fa-clock me-1"></i> <?= date('d M Y H:i:s', strtotime($log['login_at'])); ?>
                </span>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<script>
  $(document).ready(function() {
    // Inisialisasi DataTable client-side untuk tabel-login-logs
    if ($.fn.DataTable) {
      $('#tabel-login-logs').DataTable({
        responsive: true,
        autoWidth: false,
        order: [[7, 'desc']], // Urutkan berdasarkan kolom Waktu Login secara default
        language: {
          search: "_INPUT_",
          searchPlaceholder: "Cari log...",
          paginate: {
            previous: "←",
            next: "→"
          },
          zeroRecords: "Data log tidak ditemukan",
          info: "Menampilkan _START_ - _END_ dari _TOTAL_ log",
          lengthMenu: "Tampilkan _MENU_ data log"
        }
      });
    }

    // Aksi hapus semua log via AJAX
    $('#btn-clear-logs').on('click', function() {
      Swal.fire({
        title: 'Apakah Anda yakin?',
        text: 'Seluruh riwayat log login klinik akan dihapus secara permanen dari database!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: '<i class="fas fa-trash-alt me-1"></i> Ya, Hapus Semua!',
        cancelButtonText: 'Batal',
        reverseButtons: true
      }).then((result) => {
        if (result.isConfirmed) {
          Swal.fire({
            title: 'Memproses...',
            text: 'Sedang menghapus data log...',
            allowOutsideClick: false,
            didOpen: () => {
              Swal.showLoading();
            }
          });

          $.ajax({
            url: '<?= site_url("klinik/login_logs/clear_logs"); ?>',
            type: 'POST',
            data: {
              '<?= $this->security->get_csrf_token_name(); ?>': '<?= $this->security->get_csrf_hash(); ?>'
            },
            dataType: 'json',
            success: function(response) {
              if (response.status === 'success') {
                Swal.fire({
                  icon: 'success',
                  title: 'Berhasil',
                  text: response.message,
                  confirmButtonColor: '#3085d6'
                }).then(() => {
                  location.reload();
                });
              } else {
                Swal.fire({
                  icon: 'error',
                  title: 'Gagal',
                  text: response.message,
                  confirmButtonColor: '#3085d6'
                });
              }
            },
            error: function() {
              Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Terjadi kesalahan sistem saat menghubungi server.',
                confirmButtonColor: '#3085d6'
              });
            }
          });
        }
      });
    });
  });
</script>

</main> <!-- /main page content -->

    <footer class="mt-auto py-3 bg-white border-top text-center shadow-sm">
      <div class="container-fluid text-sm text-muted">
        <strong>&copy; <?= date('Y') ?> ISB Atma Luhur</strong> - Sistem Informasi Laboratorium Kesehatan.
      </div>
    </footer>
  </div> <!-- /content-wrapper -->
</div> <!-- /wrapper -->

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<!-- Bootstrap 5 Bundle JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>
<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Custom Sidebar & Layout Script -->
<script>
  $(document).ready(function () {
      // Toggle sidebar
      $('#sidebarCollapse, #sidebarOverlay').on('click', function () {
          if($(window).width() <= 768) {
              $('#sidebar').toggleClass('active');
              $('#sidebarOverlay').toggleClass('active');
          } else {
              $('#sidebar').toggleClass('collapsed');
          }
      });

      // DataTables inisialisasi default
      const dtConfig = {
        responsive: true,
        autoWidth: false,
        language: {
          search: "_INPUT_",
          searchPlaceholder: "Cari...",
          paginate: {
            previous: "←",
            next: "→"
          },
          zeroRecords: "Data tidak ditemukan",
          info: "Menampilkan _START_ - _END_ dari _TOTAL_ data",
          lengthMenu: "Tampilkan _MENU_ data"
        }
      };

      // Terapkan ke tabel yang memakai class .datatable (kesmas) + ID lama (klinik)
      if($.fn.DataTable) {
          $('#tabel-permintaan, #tabel-dokter, #tabel-pasien, #tabel-ambil-sampel, #tabel-pembayaran').DataTable(dtConfig);
          $('.datatable').DataTable(dtConfig);
      }

      // Select2 untuk semua elemen bertanda .select2
      if($.fn.select2) {
          $('.select2').select2({ width: '100%' });
      }
  });

  // SweetAlert2 Flash Data (modul Klinik; Kesmas memakai partials/flash di dalam main)
  <?php if ($this->session->userdata('module') !== 'kesmas'): ?>
  <?php if ($this->session->flashdata('success')): ?>
    Swal.fire({ icon: 'success', title: 'Berhasil', text: '<?= addslashes($this->session->flashdata('success')) ?>', showConfirmButton: false, timer: 2000 });
  <?php endif; ?>
  <?php if ($this->session->flashdata('error')): ?>
    Swal.fire({ icon: 'error', title: 'Gagal', text: '<?= addslashes($this->session->flashdata('error')) ?>' });
  <?php endif; ?>
  <?php if ($this->session->flashdata('info')): ?>
    Swal.fire({ icon: 'info', title: 'Info', text: '<?= addslashes($this->session->flashdata('info')) ?>' });
  <?php endif; ?>
  <?php endif; ?>

  // Global delete confirmation
  $(document).on('click', '.btn-delete-confirm', function(e) {
    e.preventDefault();
    var form = $(this).closest('form');
    var href = $(this).attr('href');
    Swal.fire({
      title: 'Apakah Anda Yakin?',
      text: "Data yang dihapus tidak dapat dikembalikan!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#d33',
      cancelButtonColor: '#6c757d',
      confirmButtonText: 'Ya, Hapus!',
      cancelButtonText: 'Batal'
    }).then((result) => {
      if (result.isConfirmed) {
        if (form.length) form.submit();
        else if (href) window.location.href = href;
      }
    });
  });
</script>

</body>
</html>
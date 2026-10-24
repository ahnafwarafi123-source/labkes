</main>
  </div>
</div>


<!-- Footer -->
<footer class="mt-4">
  <div class="container-fluid">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 px-3 py-4"
         style="
           border-top:1px solid rgba(255,255,255,.18);
           background: rgba(255,255,255,.25);
           backdrop-filter: blur(20px);
           -webkit-backdrop-filter: blur(20px);
           border-radius: 24px;
           box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
           border: 1px solid rgba(255,255,255,.18);
         ">
      <div class="small text-muted gradient-text">
        © <?= date('Y'); ?> <strong>KESMAS</strong> • Health Management System
      </div>
      <div class="d-flex align-items-center gap-3">
        <div class="small text-muted" style="opacity: 0.8;">
          <i class="bi bi-code-slash me-1"></i> Alex Nadine & Monika 2025
        </div>
        <span class="badge badge-premium px-3 py-2"
              style="border:1px solid rgba(102,126,234,.3); background:linear-gradient(135deg, #667eea, #764ba2); color:#2d3748; box-shadow: 0 4px 15px rgba(102,126,234,0.3);">
          <i class="bi bi-gem me-1"></i>Resmi
        </span>
        <button id="btnTop" type="button" class="btn btn-sm btn-outline-secondary"
                style="border-radius:14px; font-weight:800;">
          <i class="bi bi-arrow-up-short" aria-hidden="true"></i> Ke Atas
        </button>
      </div>
    </div>
  </div>
</footer>

<!-- Toast container (UI only) -->
<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 1080">
  <div id="miniToast" class="toast align-items-center border-0 shadow" role="alert" aria-live="assertive" aria-atomic="true">
    <div class="d-flex">
      <div class="toast-body" id="miniToastBody">OK</div>
      <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
    </div>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- UI Effects -->
<script src="<?= base_url('assets/js/premium-effects.js') ?>"></script>

<script>
  (function(){
    // Global Plugins Init
    if($('.datatable').length) {
      $('.datatable').DataTable({
        language: { search: "Cari:", lengthMenu: "Tampil _MENU_ data", info: "Menampilkan _START_ s/d _END_ dari _TOTAL_ data" }
      });
    }
    if($('.select2').length) {
      $('.select2').select2({ width: '100%' });
    }

    // SweetAlert2 Flash Data
    <?php if ($this->session->flashdata('success')): ?>
      Swal.fire({ icon: 'success', title: 'Berhasil', text: '<?= addslashes($this->session->flashdata('success')) ?>', showConfirmButton: false, timer: 2000 });
    <?php endif; ?>
    <?php if ($this->session->flashdata('error')): ?>
      Swal.fire({ icon: 'error', title: 'Gagal', text: '<?= addslashes($this->session->flashdata('error')) ?>' });
    <?php endif; ?>
    <?php if ($this->session->flashdata('info')): ?>
      Swal.fire({ icon: 'info', title: 'Info', text: '<?= addslashes($this->session->flashdata('info')) ?>' });
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

    // Back to top (UI only)
    const btnTop = document.getElementById('btnTop');
    if (btnTop) {
      btnTop.addEventListener('click', () => {
        window.scrollTo({ top: 0, behavior: 'smooth' });
      });
    }

    // Mini toast helper (UI only)
    const toastEl = document.getElementById('miniToast');
    const toastBody = document.getElementById('miniToastBody');
    const toast = toastEl ? new bootstrap.Toast(toastEl, { delay: 1600 }) : null;

    function showToast(msg){
      if (!toastEl || !toastBody || !toast) return;
      toastBody.textContent = msg;
      toast.show();
    }

    // "pilih semua" dan "kosongkan" untuk grup checkbox (LOGIC tetap)
    document.querySelectorAll('[data-check-all]').forEach(btn=>{
      btn.addEventListener('click', ()=>{
        const target = btn.getAttribute('data-check-all');
        const boxes = document.querySelectorAll(target);
        boxes.forEach(cb=> cb.checked = true);
        showToast('Semua pilihan ditandai.');
      });
    });

    document.querySelectorAll('[data-uncheck-all]').forEach(btn=>{
      btn.addEventListener('click', ()=>{
        const target = btn.getAttribute('data-uncheck-all');
        const boxes = document.querySelectorAll(target);
        boxes.forEach(cb=> cb.checked = false);
        showToast('Semua pilihan dikosongkan.');
      });
    });

    // --- Resizable Sidebar Logic ---
    const sidebar = document.getElementById('sidebar');
    const resizer = document.getElementById('sidebar-resizer');

    if (sidebar && resizer) {
      let isResizing = false;

      resizer.addEventListener('mousedown', (e) => {
        isResizing = true;
        document.body.style.cursor = 'col-resize';
        document.body.style.userSelect = 'none';
        sidebar.style.transition = 'none'; // Disable transition during drag

        document.addEventListener('mousemove', handleMouseMove);
        document.addEventListener('mouseup', stopResizing);
      });

      const handleMouseMove = (e) => {
        if (!isResizing) return;
        // Subtract offsetLeft of the parent container (.row)
        const containerOffsetLeft = sidebar.parentElement.offsetLeft;
        let newWidth = e.clientX - containerOffsetLeft;

        // Add constraints
        if (newWidth < 160) newWidth = 160;
        if (newWidth > 500) newWidth = 500;
        
        document.documentElement.style.setProperty('--sidebar-width', newWidth + 'px');
      };

      const stopResizing = () => {
        isResizing = false;
        document.body.style.cursor = 'default';
        document.body.style.userSelect = 'auto';
        sidebar.style.transition = 'width 0.2s ease'; // Re-enable transition

        document.removeEventListener('mousemove', handleMouseMove);
        document.removeEventListener('mouseup', stopResizing);

        // Save to localStorage
        const finalWidth = getComputedStyle(document.documentElement).getPropertyValue('--sidebar-width');
        localStorage.setItem('sidebarWidthV2', finalWidth);
      };
    }
    // --- End Resizable Sidebar Logic ---


  })();
</script>
</body>
</html>

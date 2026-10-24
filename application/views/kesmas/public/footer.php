</div> <!-- end main-content container-fluid -->

<footer class="mt-auto">
  <div class="container-fluid">
    <div style="
      border-top: 1px solid rgba(15,23,42,.10);
      background: rgba(255,255,255,.72);
      backdrop-filter: blur(12px);
      border-radius: 20px 20px 0 0;
      box-shadow: 0 -4px 34px rgba(2,6,23,.05);
      padding: 1.8rem 2rem 1.2rem;
    ">
      <!-- Baris Atas -->
      <div class="row g-4 mb-3">
        <div class="col-md-5">
          <div class="d-flex align-items-center gap-2 mb-2">
            <div style="width:36px; height:36px; border-radius:10px; background:rgba(13,110,253,.1); border:1px solid rgba(13,110,253,.2); display:flex; align-items:center; justify-content:center;">
              <i class="bi bi-shield-check text-primary" style="font-size:1rem;"></i>
            </div>
            <span class="fw-bold" style="color:#0b1220; font-size:0.95rem;">UPTD Laboratorium Kesehatan</span>
          </div>
          <p class="text-muted small mb-0" style="line-height:1.7;">
            Jl. Delima Siam VI, Sriwijaya<br>
            Kec. Girimaya, Pangkalpinang
          </p>
        </div>
        <div class="col-md-4">
          <p class="fw-bold small mb-2" style="color:#0b1220;">Kontak</p>
          <p class="text-muted small mb-0" style="line-height:1.9;">
            <i class="bi bi-telephone-fill me-1 text-primary"></i> 0878-7862-9499<br>
            <i class="bi bi-envelope-fill me-1 text-primary"></i> upt-labkesehatan@pangkalpinangkota.go.id
          </p>
        </div>
        <div class="col-md-3">
          <p class="fw-bold small mb-2" style="color:#0b1220;">Layanan</p>
          <div class="d-flex flex-column gap-1">
            <a href="<?= site_url('k-panel/form') ?>" class="text-muted small text-decoration-none" style="transition:.2s;" onmouseover="this.style.color='#0d6efd'" onmouseout="this.style.color=''">
              <i class="bi bi-arrow-right-short"></i> Daftar Pengujian
            </a>
            <a href="<?= site_url('kesmas/survei/form') ?>" class="text-muted small text-decoration-none" style="transition:.2s;" onmouseover="this.style.color='#0d6efd'" onmouseout="this.style.color=''">
              <i class="bi bi-arrow-right-short"></i> Survei Kepuasan
            </a>
          </div>
        </div>
      </div>

      <!-- Garis Pemisah -->
      <hr style="border-color: rgba(15,23,42,.08); margin: 0.8rem 0;">

      <!-- Baris Bawah -->
      <div class="d-flex flex-wrap align-items-center justify-content-between gap-2">
        <div class="small text-muted">
          © <?= date('Y') ?> <strong>UPTD Laboratorium Kesehatan Kota Pangkalpinang</strong>. Hak cipta dilindungi.
        </div>
        <div class="d-flex align-items-center gap-3">
          <span class="small text-muted">Senin–Kamis 08.00–15.00 | Jumat 08.00–11.30 WIB</span>
          <button id="btnTop" type="button" class="btn btn-sm btn-outline-secondary" style="border-radius:14px; font-weight:800;">
            <i class="bi bi-arrow-up-short"></i> Ke Atas
          </button>
        </div>
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
  (function(){
    // Back to top
    const btnTop = document.getElementById('btnTop');
    if (btnTop) {
      btnTop.addEventListener('click', () => {
        window.scrollTo({ top: 0, behavior: 'smooth' });
      });
    }

    // Mini toast helper
    const toastEl = document.getElementById('miniToast');
    const toastBody = document.getElementById('miniToastBody');
    const toast = toastEl ? new bootstrap.Toast(toastEl, { delay: 1600 }) : null;

    window.showToast = function(msg) {
      if (!toastEl || !toastBody || !toast) return;
      toastBody.textContent = msg;
      toast.show();
    }

    // Checkbox groups logic
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
  })();
</script>
</body>
</html>

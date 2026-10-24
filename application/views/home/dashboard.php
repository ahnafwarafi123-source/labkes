<?php
/**
 * Dashboard Terpadu (Kesmas + Klinik)
 *
 * Menampilkan blok sesuai peran:
 *   - Admin  : gabungan blok Kesmas + blok Klinik
 *   - Kesmas : blok Kesmas
 *   - Klinik : blok sesuai role klinik
 *
 * @var bool   $show_kesmas
 * @var string $klinik_block (dashboard|pendaftaran|klinik|dokter|'')
 */
$u = current_user();
$module = $u['module'] ?? '';
?>
<div class="px-4 pt-4">
  <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-2">
    <h4 class="fw-bold text-dark m-0">Dashboard</h4>
    <div class="d-flex align-items-center gap-2">
      <span class="badge <?= $module === 'klinik' ? 'bg-info-subtle text-info-emphasis' : 'bg-primary-subtle text-primary-emphasis' ?> border">
        <i class="bi bi-shield-check me-1"></i><?= $module === 'klinik' ? 'Klinik' : 'Kesmas' ?>
      </span>
      <span class="badge bg-light text-dark border"><?= html_escape(current_role_label()) ?></span>
    </div>
  </div>

  <div class="alert alert-light border d-flex align-items-center gap-2 mb-4 py-2">
    <i class="bi bi-hand-index-thumb text-primary"></i>
    <div class="small">
      Halo, <span class="fw-bold"><?= html_escape($u['nama'] ?? '') ?></span>.
      Anda masuk sebagai <span class="fw-bold text-primary"><?= html_escape(current_role_label()) ?></span>.
    </div>
  </div>
</div>

<?php if (!empty($show_kesmas)): ?>
  <?php $this->load->view('kesmas/dashboard', $view_data); ?>
  <hr class="my-4 mx-3">
<?php endif; ?>

<?php if (!empty($klinik_block)): ?>
  <?php
  $klinik_views = array(
      'dashboard'    => 'klinik/dashboard_klinik/dashboard',
      'pendaftaran'  => 'klinik/dashboard_klinik/Dashboard_pendaftaran',
      'klinik'       => 'klinik/dashboard_klinik/Dashboard_klinik',
      'dokter'       => 'klinik/dashboard_klinik/Dashboard_dokter',
  );
  if (isset($klinik_views[$klinik_block])) {
      $this->load->view($klinik_views[$klinik_block], $view_data);
  }
  ?>
<?php endif; ?>
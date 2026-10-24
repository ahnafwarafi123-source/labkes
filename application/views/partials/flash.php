<?php if ($this->session->flashdata('success')): ?>
  <div class="alert alert-success alert-dismissible fade show glass-effect notification-premium success mb-4" role="alert">
    <i class="bi bi-check-circle-fill me-2"></i>
    <?= nl2br(htmlspecialchars($this->session->flashdata('success'))) ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
<?php endif; ?>

<?php if ($this->session->flashdata('info')): ?>
  <div class="alert alert-info alert-dismissible fade show glass-effect notification-premium mb-4" role="alert">
    <i class="bi bi-info-circle-fill me-2"></i>
    <?= nl2br(htmlspecialchars($this->session->flashdata('info'))) ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
<?php endif; ?>

<?php if ($this->session->flashdata('error')): ?>
  <div class="alert alert-danger alert-dismissible fade show glass-effect notification-premium error mb-4" role="alert">
    <i class="bi bi-exclamation-triangle-fill me-2"></i>
    <?= nl2br(htmlspecialchars($this->session->flashdata('error'))) ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
<?php endif; ?>

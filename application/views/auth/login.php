<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <title>Login | UPTD LABKES</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">

  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

  <link rel="stylesheet" href="<?php echo base_url('assets/css/login.css'); ?>">
</head>

<body>

  <div class="bg-circle-1"></div>
  <div class="bg-circle-2"></div>

  <div class="login-card">
    <div class="login-header">
      <div>
        <div class="login-title">Sistem Informasi<br>Laboratorium Kesehatan</div>
        <div class="login-subtitle">UPTD LABKES</div>
      </div>
      <div class="logo-container img">
        <img src="<?= base_url('assets/img/logolabkes.png') ?>" alt="Logo Labkes">
        <img src="<?= base_url('assets/img/logoatmaluhur.png') ?>" alt="Logo Labkes">
      </div>
    </div>

    <p class="text-muted mb-4" style="font-size: 0.85rem;">
      Silakan masuk untuk mengakses <strong>Kesmas</strong>, <strong>Klinik &amp; Rekam Medis</strong>.
    </p>

    <?php if ($this->session->flashdata('error')): ?>
      <div class="text-error"><i class="fas fa-exclamation-circle me-1"></i> <?= $this->session->flashdata('error') ?></div>
    <?php endif; ?>

    <form action="<?= site_url('auth/do_login') ?>" method="post">
      <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">

      <div class="mb-3">
        <label class="form-label">Username</label>
        <div class="input-group input-group-active">
          <span class="input-group-text"><i class="far fa-user"></i></span>
          <input type="text" name="username" class="form-control" placeholder="Masukkan Username" required autofocus>
        </div>
      </div>

      <div class="mb-3">
        <label class="form-label">Password</label>
        <div class="input-group">
          <span class="input-group-text"><i class="fas fa-lock"></i></span>
          <input type="password" name="password" id="password" class="form-control" placeholder="Masukkan Password" required>
          <span class="input-group-text" style="border-left: none; border-right: 1px solid #dee2e6; cursor: pointer;" onclick="togglePassword()">
            <i class="far fa-eye-slash" id="toggleIcon"></i>
          </span>
        </div>
      </div>

      <button type="submit" class="btn btn-login w-100">
        LOGIN
      </button>

    </form>

    <div class="forgot-password">
      <a href="<?= site_url('kesmas') ?>"><i class="fas fa-globe me-1"></i> Layanan Publik Pendaftaran</a>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    function togglePassword() {
      const passwordInput = document.getElementById('password');
      const toggleIcon = document.getElementById('toggleIcon');

      if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        toggleIcon.classList.remove('fa-eye-slash');
        toggleIcon.classList.add('fa-eye');
      } else {
        passwordInput.type = 'password';
        toggleIcon.classList.remove('fa-eye');
        toggleIcon.classList.add('fa-eye-slash');
      }
    }

    document.querySelectorAll('.form-control').forEach(input => {
      input.addEventListener('focus', function() {
        document.querySelectorAll('.input-group').forEach(ig => ig.classList.remove('input-group-active'));
        this.parentElement.classList.add('input-group-active');
      });
    });
  </script>
</body>

</html>
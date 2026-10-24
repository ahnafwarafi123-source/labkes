<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="color-scheme" content="light">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login - KESMASNEW</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">

  <style>
    :root {
      --brand-color: #005c97;
      --brand-color-dark: #363795;
      --secondary-color: #1cb5e0;
      --text-color: #1e293b;
      --text-muted: #64748b;
      --card-shadow: 0 30px 60px -15px rgba(0, 0, 0, 0.4);
    }

    body {
      font-family: 'Poppins', sans-serif;
      /* Keeping the original beloved background */
      background: linear-gradient(-45deg, var(--brand-color), var(--brand-color-dark), var(--secondary-color), #093028);
      background-size: 400% 400%;
      color: var(--text-color);
      display: flex;
      align-items: center;
      justify-content: center;
      min-height: 100vh;
      padding: 1rem;
      margin: 0;
      animation: gradientBG 15s ease infinite;
      overflow: hidden;
      position: relative;
    }

    /* Add some extremely subtle premium light beams in the background */
    body::before {
      content: '';
      position: absolute;
      width: 150%; height: 150%;
      background: radial-gradient(circle, rgba(255,255,255,0.08) 0%, transparent 60%);
      top: -50%; left: -25%;
      pointer-events: none;
      animation: spinLight 30s linear infinite;
    }

    @keyframes spinLight {
      100% { transform: rotate(360deg); }
    }

    @keyframes gradientBG {
      0% { background-position: 0% 50%; }
      50% { background-position: 100% 50%; }
      100% { background-position: 0% 50%; }
    }

    .login-container {
      max-width: 440px;
      width: 100%;
      position: relative;
      z-index: 10;
      animation: formEnter 0.8s cubic-bezier(0.2, 0.8, 0.2, 1) forwards;
    }

    @keyframes formEnter {
      0% { opacity: 0; transform: translateY(40px) scale(0.96); }
      100% { opacity: 1; transform: translateY(0) scale(1); }
    }

    .login-card {
      /* Reverting to the translucent solid white card they liked */
      background-color: rgba(255, 255, 255, 0.97);
      border: 1px solid rgba(255, 255, 255, 0.8);
      border-radius: 28px;
      padding: 3rem 2.5rem;
      box-shadow: var(--card-shadow);
      backdrop-filter: blur(12px);
      -webkit-backdrop-filter: blur(12px);
      position: relative;
      overflow: hidden;
    }

    /* Top glowing line for premium effect */
    .login-card::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0; height: 5px;
        background: linear-gradient(90deg, var(--secondary-color), var(--brand-color));
    }

    .login-header {
      text-align: center;
      margin-bottom: 2.2rem;
    }

    .logo-icon-wrap {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      background: linear-gradient(135deg, var(--brand-color), var(--secondary-color));
      color: #fff;
      width: 72px;
      height: 72px;
      border-radius: 22px;
      font-size: 2.2rem;
      margin-bottom: 1.2rem;
      box-shadow: 0 10px 25px rgba(0, 92, 151, 0.4);
      /* The pulse they liked, refined */
      animation: pulsePremium 2.5s infinite cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    @keyframes pulsePremium {
      0% { box-shadow: 0 0 0 0 rgba(0, 92, 151, 0.5); transform: scale(1); }
      50% { box-shadow: 0 0 0 20px rgba(0, 92, 151, 0); transform: scale(1.04); }
      100% { box-shadow: 0 0 0 0 rgba(0, 92, 151, 0); transform: scale(1); }
    }

    .login-header h2 {
      font-weight: 800;
      color: var(--text-color);
      letter-spacing: -0.5px;
      font-size: 1.8rem;
      margin-bottom: 0.3rem;
    }

    .login-header p {
      color: var(--text-muted);
      font-size: 0.95rem;
      font-weight: 500;
    }

    /* Polished inputs */
    .input-wrapper {
      position: relative;
      margin-bottom: 1.2rem;
    }

    .input-wrapper i {
      position: absolute;
      left: 1.25rem;
      top: 50%;
      transform: translateY(-50%);
      color: var(--text-muted);
      font-size: 1.2rem;
      transition: color 0.3s ease;
      z-index: 5;
    }

    .form-control-cool {
      background-color: #f8fafc;
      border: 2px solid #e2e8f0;
      border-radius: 16px;
      padding: 0.9rem 1.25rem 0.9rem 3.2rem;
      font-size: 0.95rem;
      font-weight: 500;
      color: var(--text-color);
      transition: all 0.3s ease;
      width: 100%;
    }

    .form-control-cool::placeholder {
      color: #94a3b8;
      font-weight: 400;
    }

    .form-control-cool:focus {
      outline: none;
      background-color: #fff;
      border-color: var(--brand-color);
      box-shadow: 0 0 0 4px rgba(0, 92, 151, 0.15);
    }

    .input-wrapper:focus-within i {
      color: var(--brand-color);
    }

    .btn-primary-cool {
      background: linear-gradient(135deg, var(--brand-color), var(--brand-color-dark));
      border: none;
      border-radius: 16px;
      padding: 1rem;
      font-weight: 700;
      letter-spacing: 0.5px;
      font-size: 1rem;
      color: #fff;
      width: 100%;
      margin-top: 1.5rem;
      transition: all 0.3s ease;
      box-shadow: 0 8px 20px rgba(0, 92, 151, 0.3);
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 0.5rem;
    }

    .btn-primary-cool:hover {
      transform: translateY(-3px);
      box-shadow: 0 12px 25px rgba(0, 92, 151, 0.4);
      background: linear-gradient(135deg, #006eb5, #4142b3);
    }

    .btn-primary-cool:active {
      transform: translateY(0);
    }

    .footer-text {
        text-align: center;
        margin-top: 1.8rem;
        color: rgba(255,255,255,0.9);
        font-size: 0.85rem;
        font-weight: 500;
        text-shadow: 0 2px 4px rgba(0,0,0,0.2);
    }

    .footer-text span {
        display: block;
        font-size: 0.75rem;
        opacity: 0.8;
        margin-top: 0.4rem;
    }

    .alert-custom {
      border-radius: 14px;
      font-size: 0.9rem;
      font-weight: 600;
      border: none;
      background-color: #fee2e2;
      color: #b91c1c;
      padding: 1rem 1.25rem;
      display: flex;
      align-items: center;
      box-shadow: 0 4px 12px rgba(185, 28, 28, 0.1);
    }
  </style>
</head>

<body>
  <div class="login-container">
    <div class="login-card">
        <div class="login-header">
            <div class="logo-icon-wrap">
                <i class="bi bi-shield-check"></i>
            </div>
            <h2>Portal KESMAS</h2>
            <p>Silakan masuk untuk melanjutkan</p>
        </div>

        <?php if ($this->session->flashdata('error')): ?>
            <div class="alert alert-custom alert-dismissible fade show mb-4" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i>
                <div><?= $this->session->flashdata('error') ?></div>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <form method="post" action="<?= site_url('kesmas/auth/do_login') ?>">
            <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
            
            <div class="input-wrapper">
                <i class="bi bi-person"></i>
                <input type="text" class="form-control-cool" name="username" placeholder="Username" required autofocus autocomplete="off">
            </div>

            <div class="input-wrapper mb-2">
                <i class="bi bi-lock"></i>
                <input type="password" class="form-control-cool" name="password" placeholder="Password" required>
            </div>

            <button type="submit" class="btn-primary-cool">
                Masuk Sekarang <i class="bi bi-arrow-right-circle"></i>
            </button>
        </form>
    </div>
    <div class="footer-text">
        © <?= date('Y'); ?> KESMASNEW &bull; Sistem Informasi Kesehatan
        <span>Alex Ramadhan Nadine Antya Putri & Monika ISB ATMALUHUR PKP 2025</span>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

<style>
  :root{
    --ink:#0b1220;
    --muted:#667085;
    --line: rgba(15,23,42,.10);
    --glass: rgba(255,255,255,.70);
    --shadow: 0 22px 70px rgba(2,6,23,.12);
    --shadow2: 0 12px 30px rgba(2,6,23,.10);
    --ring: rgba(13,110,253,.20);
    --brand:#0d6efd;
    --brand2:#0b5ed7;
  }

  /* ===== ENHANCED STYLES ===== */
  
  .page-hero{
    border-radius: 26px;
    border: 1px solid rgba(15,23,42,.10);
    background:
      radial-gradient(900px 340px at 12% 10%, rgba(13,110,253,.20), transparent 62%),
      radial-gradient(900px 340px at 92% 20%, rgba(34,197,94,.10), transparent 62%),
      linear-gradient(180deg, rgba(255,255,255,.92), rgba(255,255,255,.70));
    box-shadow: var(--shadow);
    padding: 1.25rem 1.35rem;
    position: relative;
    overflow: hidden;
    transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
    animation: slideInDown 0.6s ease-out;
  }
  
  .page-hero:hover {
    transform: translateY(-2px);
    box-shadow: 0 28px 80px rgba(2,6,23,.15);
  }

  @keyframes slideInDown {
    from {
      opacity: 0;
      transform: translateY(-20px);
    }
    to {
      opacity: 1;
      transform: translateY(0);
    }
  }

  .page-hero h3{
    margin: 0;
    font-weight: 950;
    letter-spacing: -.5px;
    color: var(--ink);
    background: linear-gradient(135deg, #0b1220 0%, #0d6efd 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
  }

  .page-hero .sub{
    color: var(--muted);
    margin-top: .25rem;
    font-size: 0.93rem;
    letter-spacing: 0.3px;
  }

  .btn-add{
    border-radius: 16px;
    font-weight: 900;
    letter-spacing: .2px;
    padding: .75rem 1.3rem;
    border: none;
    background: linear-gradient(135deg, #0d6efd 0%, #0b5ed7 100%);
    box-shadow: 0 18px 34px rgba(13,110,253,.25);
    color: white !important;
    transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
    cursor: pointer;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
  }

  .btn-add:hover {
    transform: translateY(-3px);
    box-shadow: 0 24px 50px rgba(13,110,253,.35);
    background: linear-gradient(135deg, #0b5ed7 0%, #0a3f99 100%);
    color: white !important;
  }

  .btn-add:active {
    transform: translateY(-1px);
    box-shadow: 0 12px 24px rgba(13,110,253,.3);
  }

  .filter-shell{
    border-radius: 22px;
    border: 1px solid rgba(15,23,42,.10);
    background: var(--glass);
    backdrop-filter: blur(12px);
    box-shadow: var(--shadow2);
    padding: 1.1rem;
    transition: all 0.3s ease;
    animation: fadeInUp 0.6s ease-out 0.1s both;
  }

  @keyframes fadeInUp {
    from {
      opacity: 0;
      transform: translateY(10px);
    }
    to {
      opacity: 1;
      transform: translateY(0);
    }
  }

  .filter-shell:hover {
    border-color: rgba(13,110,253,.2);
    box-shadow: 0 16px 40px rgba(2,6,23,.12);
  }

  .filter-shell .form-control{
    border-radius: 14px;
    border-color: rgba(15,23,42,.12);
    padding: .8rem 1rem;
    transition: all 0.3s ease;
    font-weight: 500;
  }

  .filter-shell .form-control:focus{
    border-color: rgba(13,110,253,.5);
    box-shadow: 0 0 0 .25rem var(--ring);
    transform: translateY(-1px);
  }

  .btn-filter{
    border-radius: 14px;
    font-weight: 900;
    padding: .8rem 1rem;
    transition: all 0.3s ease;
  }

  .btn-filter:hover {
    transform: translateY(-1px);
    box-shadow: 0 8px 20px rgba(13,110,253,.15);
  }

  .card-wow{
    border-radius: 22px;
    border: 1px solid rgba(15,23,42,.10);
    box-shadow: var(--shadow2);
    overflow: hidden;
    background: rgba(255,255,255,.90);
    transition: all 0.4s ease;
    animation: fadeInUp 0.6s ease-out 0.2s both;
  }

  .card-wow:hover {
    border-color: rgba(13,110,253,.15);
    box-shadow: 0 20px 50px rgba(2,6,23,.15);
  }

  .table thead th{
    background: linear-gradient(135deg, rgba(248,250,252,.95) 0%, rgba(240,245,252,.95) 100%);
    border-bottom: 2px solid rgba(13,110,253,.1);
    font-weight: 900;
    color: #1e293b;
    padding: 1rem .9rem;
    text-transform: uppercase;
    font-size: 0.75rem;
    letter-spacing: 0.5px;
  }

  .table tbody td{
    padding: 1rem .9rem;
    vertical-align: middle;
    border-bottom: 1px solid rgba(15,23,42,.05);
  }

  .badge-soft{
    border-radius: 999px;
    padding: .5rem .8rem;
    font-weight: 800;
    border: 1.5px solid;
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    font-size: 0.85rem;
    transition: all 0.3s ease;
    cursor: default;
  }

  .badge-soft:hover {
    transform: translateY(-2px);
  }

  .badge-air { 
    border-color: rgba(13,110,253,.35); 
    background: linear-gradient(135deg, rgba(13,110,253,.12) 0%, rgba(100,200,255,.08) 100%);
    color: #0b5ed7;
    box-shadow: 0 4px 12px rgba(13,110,253,.15);
  }

  .badge-makanan{ 
    border-color: rgba(245,158,11,.35); 
    background: linear-gradient(135deg, rgba(245,158,11,.12) 0%, rgba(255,180,50,.08) 100%);
    color: #b45309;
    box-shadow: 0 4px 12px rgba(245,158,11,.15);
  }

  .badge-lingkungan{ 
    border-color: rgba(34,197,94,.35);
    background: linear-gradient(135deg, rgba(34,197,94,.12) 0%, rgba(100,220,150,.08) 100%);
    color: #15803d;
    box-shadow: 0 4px 12px rgba(34,197,94,.15);
  }

  .btn-action{
    border-radius: 12px;
    font-weight: 700;
    padding: .55rem .85rem;
    transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
    position: relative;
    font-size: 0.85rem;
    border: none;
    letter-spacing: 0.3px;
  }

  .btn-action i { 
    margin-right: .3rem;
    transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
    display: inline-block;
  }

  .btn-action:hover i {
    transform: scale(1.15) rotate(10deg);
  }

  .btn-action.btn-success:hover i {
    color: #fff;
  }

  /* ===== PREMIUM SOLID BUTTON STYLES ===== */
  
  .btn-action.btn-outline-primary {
    color: #ffffff;
    border: none;
    background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);
    box-shadow: 0 4px 12px rgba(13, 110, 253, 0.3);
  }

  .btn-action.btn-outline-primary:hover {
    background: linear-gradient(135deg, #0b5ed7 0%, #0a3f99 100%);
    box-shadow: 0 8px 24px rgba(13, 110, 253, 0.4);
    transform: translateY(-2px);
  }

  .btn-action.btn-outline-primary:active {
    transform: translateY(0);
    box-shadow: 0 2px 8px rgba(13, 110, 253, 0.3);
  }

  .btn-action.btn-outline-warning {
    color: #ffffff;
    border: none;
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);
  }

  .btn-action.btn-outline-warning:hover {
    background: linear-gradient(135deg, #d97706 0%, #b45309 100%);
    box-shadow: 0 8px 24px rgba(245, 158, 11, 0.4);
    transform: translateY(-2px);
  }

  .btn-action.btn-outline-warning:active {
    transform: translateY(0);
    box-shadow: 0 2px 8px rgba(245, 158, 11, 0.3);
  }

  .btn-action.btn-outline-secondary {
    color: #ffffff;
    border: none;
    background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%);
    box-shadow: 0 4px 12px rgba(107, 114, 128, 0.3);
  }

  .btn-action.btn-outline-secondary:hover {
    background: linear-gradient(135deg, #4b5563 0%, #2d3748 100%);
    box-shadow: 0 8px 24px rgba(107, 114, 128, 0.4);
    transform: translateY(-2px);
  }

  .btn-action.btn-outline-secondary:active {
    transform: translateY(0);
    box-shadow: 0 2px 8px rgba(107, 114, 128, 0.3);
  }

  .btn-action.btn-outline-danger {
    color: #ffffff;
    border: none;
    background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
    box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
  }

  .btn-action.btn-outline-danger:hover {
    background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
    box-shadow: 0 8px 24px rgba(239, 68, 68, 0.4);
    transform: translateY(-2px);
  }

  .btn-action.btn-outline-danger:active {
    transform: translateY(0);
    box-shadow: 0 2px 8px rgba(239, 68, 68, 0.3);
  }

  .btn-action.btn-danger {
    color: #ffffff;
    border: none;
    background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
    box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
  }

  .btn-action.btn-danger:hover {
    background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
    box-shadow: 0 8px 24px rgba(239, 68, 68, 0.4);
    transform: translateY(-2px);
  }

  .btn-action.btn-danger:active {
    transform: translateY(0);
    box-shadow: 0 2px 8px rgba(239, 68, 68, 0.3);
  }

  .btn-action.btn-success {
    color: #ffffff;
    border: none;
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
  }

  .btn-action.btn-success:hover {
    background: linear-gradient(135deg, #059669 0%, #047857 100%);
    box-shadow: 0 8px 24px rgba(16, 185, 129, 0.4);
    transform: translateY(-2px);
  }

  .btn-action.btn-success:active {
    transform: translateY(0);
    box-shadow: 0 2px 8px rgba(16, 185, 129, 0.3);
  }

  .btn-action:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(0,0,0,.1);
  }

  .btn-outline-primary.btn-action {
    color: #0d6efd;
    border-color: rgba(13,110,253,.4);
    background: rgba(13,110,253,.05);
  }

  .btn-outline-primary.btn-action:hover {
    background: rgba(13,110,253,.1);
    color: #0b5ed7;
    border-color: rgba(13,110,253,.6);
  }

  .btn-outline-warning.btn-action {
    color: #b45309;
    border-color: rgba(180,83,9,.4);
    background: rgba(245,158,11,.05);
  }

  .btn-outline-warning.btn-action:hover {
    background: rgba(245,158,11,.1);
    color: #a16207;
    border-color: rgba(180,83,9,.6);
  }

  .btn-outline-secondary.btn-action {
    color: #6b7280;
    border-color: rgba(107,114,128,.3);
    background: rgba(107,114,128,.05);
  }

  .btn-outline-secondary.btn-action:hover {
    background: rgba(107,114,128,.1);
    border-color: rgba(107,114,128,.5);
  }

  .btn-outline-danger.btn-action {
    color: #dc2626;
    border-color: rgba(220,38,38,.4);
    background: rgba(220,38,38,.05);
  }

  .btn-outline-danger.btn-action:hover {
    background: rgba(220,38,38,.1);
    color: #b91c1c;
    border-color: rgba(220,38,38,.6);
  }

  .empty-state{
    padding: 3rem 1.5rem;
    color: var(--muted);
    animation: fadeIn 0.5s ease-out;
  }

  @keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
  }

  .empty-icon{
    width: 64px;
    height: 64px;
    border-radius: 20px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, rgba(13,110,253,.15) 0%, rgba(100,200,255,.08) 100%);
    border: 2px solid rgba(13,110,253,.25);
    color: #0b5ed7;
    box-shadow: 0 12px 30px rgba(13,110,253,.12);
    margin-bottom: 1rem;
    font-size: 1.8rem;
    transition: all 0.3s ease;
  }

  .empty-icon:hover {
    transform: scale(1.1) rotate(-5deg);
    box-shadow: 0 16px 40px rgba(13,110,253,.2);
  }

  .row-hover {
    transition: all 0.3s ease;
  }

  .row-hover:hover td{
    background: linear-gradient(90deg, rgba(13,110,253,.04) 0%, transparent 100%);
  }

  .row-hover:hover {
    box-shadow: inset 0 0 20px rgba(13,110,253,.08);
  }

  .no-reg-text {
    font-weight: 700;
    color: #0b1220;
    font-size: 0.95rem;
  }

  .sampel-text {
    color: #475569;
    font-weight: 500;
  }

  .date-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    color: #64748b;
    font-size: 0.85rem;
    padding: 0.3rem 0.6rem;
    background: rgba(100,112,179,.06);
    border-radius: 8px;
    border: 1px solid rgba(100,112,179,.15);
  }

  @media (max-width: 767.98px){
    .page-hero{ padding: 1.1rem; border-radius: 22px; }
    .page-hero h3 { font-size: 1.25rem; }
    .filter-shell{ border-radius: 18px; padding: 0.9rem; }
    .card-wow{ border-radius: 18px; }
    .table { font-size: 0.85rem; }
    .btn-action { padding: 0.4rem 0.6rem; font-size: 0.75rem; }
    .badge-soft { padding: 0.4rem 0.6rem; font-size: 0.75rem; }

    /* Responsive Table Cards for Mobile */
    .table-mobile-cards thead {
      display: none;
    }
    .table-mobile-cards, .table-mobile-cards tbody, .table-mobile-cards tr, .table-mobile-cards td {
      display: block;
      width: 100%;
    }
    .table-mobile-cards tr {
      margin-bottom: 1rem;
      border-radius: 12px;
      background: #fff;
      box-shadow: 0 4px 12px rgba(0,0,0,0.05);
      padding: 0.8rem;
      border: 1px solid rgba(15,23,42,.05);
    }
    .table-mobile-cards td {
      display: flex;
      justify-content: space-between;
      align-items: flex-start;
      text-align: right;
      padding: 0.75rem 0.5rem;
      border-bottom: 1px dashed rgba(15,23,42,.05);
      gap: 1rem;
    }
    .table-mobile-cards td::before {
      content: attr(data-label);
      font-weight: 700;
      color: var(--muted);
      text-align: left;
      font-size: 0.85rem;
      flex-shrink: 0;
      width: 40%;
    }
    .table-mobile-cards td:last-child {
      border-bottom: 0;
      justify-content: center;
      gap: 0.5rem;
      flex-wrap: wrap;
      padding-bottom: 0;
      margin-top: 0.5rem;
    }
    .table-mobile-cards td:last-child::before {
      display: none;
    }
  }
</style>

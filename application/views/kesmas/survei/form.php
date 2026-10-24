<style>
/* ===== SURVEY FORM - PREMIUM DESIGN ===== */
@import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

:root {
    --survey-primary: #4f46e5;
    --survey-primary-light: #818cf8;
    --survey-primary-dark: #3730a3;
    --survey-accent: #06b6d4;
    --survey-success: #10b981;
    --survey-warning: #f59e0b;
    --survey-danger: #ef4444;
    --survey-bg: #f0f4ff;
    --survey-card: #ffffff;
    --survey-text: #1e293b;
    --survey-muted: #64748b;
    --survey-border: #e2e8f0;
    --survey-radius: 20px;
    --survey-shadow: 0 20px 60px rgba(79, 70, 229, 0.12);
    --survey-shadow-sm: 0 4px 20px rgba(79, 70, 229, 0.08);
}

.survey-wrapper {
    font-family: 'Plus Jakarta Sans', 'Segoe UI', sans-serif;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #06b6d4 100%);
    min-height: 100vh;
    padding: 2rem 1rem 4rem;
    position: relative;
    overflow: hidden;
}

.survey-wrapper::before {
    content: '';
    position: absolute;
    top: -50%;
    left: -50%;
    width: 200%;
    height: 200%;
    background: radial-gradient(circle at 30% 40%, rgba(255,255,255,0.08) 0%, transparent 60%),
                radial-gradient(circle at 70% 80%, rgba(6,182,212,0.1) 0%, transparent 50%);
    pointer-events: none;
}

/* Floating blobs */
.survey-blob {
    position: absolute;
    border-radius: 50%;
    filter: blur(80px);
    pointer-events: none;
    opacity: 0.15;
}
.survey-blob-1 { width: 400px; height: 400px; background: #a78bfa; top: -100px; right: -100px; animation: blobFloat 8s ease-in-out infinite; }
.survey-blob-2 { width: 300px; height: 300px; background: #67e8f9; bottom: 10%; left: -80px; animation: blobFloat 10s ease-in-out infinite reverse; }
.survey-blob-3 { width: 200px; height: 200px; background: #fbbf24; top: 50%; left: 40%; animation: blobFloat 6s ease-in-out infinite 2s; }

@keyframes blobFloat {
    0%, 100% { transform: translate(0, 0) scale(1); }
    33% { transform: translate(20px, -20px) scale(1.05); }
    66% { transform: translate(-15px, 15px) scale(0.95); }
}

/* === HEADER CARD === */
.survey-header-card {
    background: rgba(255,255,255,0.18);
    backdrop-filter: blur(24px);
    -webkit-backdrop-filter: blur(24px);
    border: 1px solid rgba(255,255,255,0.35);
    border-radius: 28px;
    padding: 2.5rem 2rem;
    text-align: center;
    margin-bottom: 2rem;
    box-shadow: 0 8px 40px rgba(79,70,229,0.2);
    position: relative;
    overflow: hidden;
}

.survey-header-card::before {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0;
    height: 3px;
    background: linear-gradient(90deg, #a78bfa, #67e8f9, #fbbf24, #a78bfa);
    background-size: 200% auto;
    animation: shimmer 3s linear infinite;
}

@keyframes shimmer {
    0% { background-position: 0% center; }
    100% { background-position: 200% center; }
}

.survey-logo {
    width: 72px; height: 72px;
    background: linear-gradient(135deg, #4f46e5, #06b6d4);
    border-radius: 20px;
    display: flex; align-items: center; justify-content: center;
    margin: 0 auto 1rem;
    box-shadow: 0 8px 24px rgba(79,70,229,0.4);
    font-size: 2rem;
}

.survey-title {
    font-size: 1.4rem;
    font-weight: 800;
    color: #fff;
    letter-spacing: -0.5px;
    margin-bottom: 0.4rem;
}

.survey-subtitle {
    color: rgba(255,255,255,0.8);
    font-size: 0.9rem;
    font-weight: 500;
}

/* === PROGRESS BAR === */
.survey-progress-wrap {
    background: rgba(255,255,255,0.15);
    backdrop-filter: blur(12px);
    border: 1px solid rgba(255,255,255,0.25);
    border-radius: 20px;
    padding: 1.5rem 2rem;
    margin-bottom: 2rem;
}

.progress-steps {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 0;
    flex-wrap: nowrap;
    margin-bottom: 1.2rem;
    overflow: hidden;
}

.progress-step {
    display: flex;
    flex-direction: column;
    align-items: center;
    position: relative;
    flex: 1;
    min-width: 0;
}

.progress-step:not(:last-child)::after {
    content: '';
    position: absolute;
    top: 18px;
    left: 50%;
    width: 100%;
    height: 2px;
    background: rgba(255,255,255,0.3);
    z-index: 0;
    transition: background 0.4s;
}

.progress-step.done:not(:last-child)::after {
    background: linear-gradient(90deg, #a78bfa, #67e8f9);
}

.step-circle {
    width: 38px; height: 38px;
    border-radius: 50%;
    background: rgba(255,255,255,0.2);
    border: 2px solid rgba(255,255,255,0.4);
    display: flex; align-items: center; justify-content: center;
    font-size: 0.85rem;
    font-weight: 700;
    color: rgba(255,255,255,0.6);
    position: relative;
    z-index: 1;
    transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
}

.progress-step.active .step-circle {
    background: linear-gradient(135deg, #4f46e5, #06b6d4);
    border-color: #fff;
    color: #fff;
    box-shadow: 0 4px 16px rgba(79,70,229,0.5);
    transform: scale(1.15);
}

.progress-step.done .step-circle {
    background: linear-gradient(135deg, #10b981, #06d6a0);
    border-color: #fff;
    color: #fff;
}

.step-label {
    font-size: 0.65rem;
    font-weight: 600;
    color: rgba(255,255,255,0.5);
    margin-top: 0.4rem;
    text-align: center;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    max-width: 70px;
}

.progress-step.active .step-label { color: #fff; }
.progress-step.done .step-label { color: rgba(255,255,255,0.7); }

.progress-bar-container {
    background: rgba(255,255,255,0.2);
    border-radius: 100px;
    height: 6px;
    overflow: hidden;
}

.progress-bar-fill {
    height: 100%;
    border-radius: 100px;
    background: linear-gradient(90deg, #a78bfa, #67e8f9);
    transition: width 0.5s cubic-bezier(0.34, 1.56, 0.64, 1);
    width: 0%;
}

/* === FORM CARD === */
.survey-form-card {
    background: #fff;
    border-radius: var(--survey-radius);
    box-shadow: var(--survey-shadow);
    overflow: hidden;
    margin-bottom: 1.5rem;
}

.form-section {
    display: none;
    animation: slideInRight 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94) forwards;
}
.form-section.active { display: block; }
.form-section.slide-out { animation: slideOutLeft 0.3s ease forwards; }

@keyframes slideInRight {
    from { opacity: 0; transform: translateX(30px); }
    to { opacity: 1; transform: translateX(0); }
}
@keyframes slideOutLeft {
    from { opacity: 1; transform: translateX(0); }
    to { opacity: 0; transform: translateX(-30px); }
}
@keyframes slideInLeft {
    from { opacity: 0; transform: translateX(-30px); }
    to { opacity: 1; transform: translateX(0); }
}

.section-header {
    background: linear-gradient(135deg, #4f46e5 0%, #764ba2 100%);
    padding: 1.8rem 2rem;
    display: flex;
    align-items: center;
    gap: 1rem;
}

.section-icon {
    width: 48px; height: 48px;
    background: rgba(255,255,255,0.2);
    border-radius: 14px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.4rem;
    flex-shrink: 0;
}

.section-title { color: #fff; font-weight: 800; font-size: 1.1rem; margin: 0; }
.section-desc { color: rgba(255,255,255,0.75); font-size: 0.8rem; margin: 0.2rem 0 0; }

.section-body { padding: 2rem; }

/* === FORM INPUTS === */
.form-field-group { margin-bottom: 1.5rem; }

.form-label-premium {
    display: flex;
    align-items: center;
    gap: 0.4rem;
    font-weight: 700;
    color: #1e293b;
    font-size: 0.9rem;
    margin-bottom: 0.6rem;
}

.form-label-premium .required-dot {
    width: 6px; height: 6px;
    background: var(--survey-danger);
    border-radius: 50%;
    display: inline-block;
}

.form-control-premium,
.form-select-premium {
    width: 100%;
    padding: 0.8rem 1.1rem;
    border: 2px solid var(--survey-border);
    border-radius: 14px;
    font-family: inherit;
    font-size: 0.95rem;
    font-weight: 500;
    color: #1e293b;
    background: #f8faff;
    transition: all 0.25s ease;
    outline: none;
    appearance: none;
    -webkit-appearance: none;
}

.form-control-premium:focus,
.form-select-premium:focus {
    border-color: var(--survey-primary);
    background: #fff;
    box-shadow: 0 0 0 4px rgba(79,70,229,0.1);
}

.form-control-premium:disabled {
    background: #f0f4ff;
    color: var(--survey-primary);
    font-weight: 700;
    border-color: rgba(79,70,229,0.3);
    cursor: not-allowed;
}

.form-select-premium {
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%234f46e5' d='M6 8L1 3h10z'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 1rem center;
    padding-right: 2.5rem;
    cursor: pointer;
}

/* Gender cards */
.gender-cards {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
}

.gender-card-input { display: none; }
.gender-card-label {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    padding: 1.5rem 1rem;
    border: 2px solid var(--survey-border);
    border-radius: 16px;
    cursor: pointer;
    transition: all 0.25s cubic-bezier(0.34, 1.56, 0.64, 1);
    text-align: center;
    background: #f8faff;
}

.gender-card-label .gender-emoji { font-size: 2.5rem; }
.gender-card-label .gender-text { font-weight: 700; font-size: 0.9rem; color: #64748b; }

.gender-card-input:checked + .gender-card-label {
    border-color: var(--survey-primary);
    background: linear-gradient(135deg, rgba(79,70,229,0.08), rgba(6,182,212,0.06));
    box-shadow: 0 4px 16px rgba(79,70,229,0.2);
    transform: translateY(-3px);
}

.gender-card-input:checked + .gender-card-label .gender-text {
    color: var(--survey-primary);
}

/* === QUESTION CARDS === */
.question-card {
    background: #f8faff;
    border: 2px solid var(--survey-border);
    border-radius: 18px;
    padding: 1.5rem;
    margin-bottom: 1.2rem;
    transition: all 0.25s ease;
    position: relative;
    overflow: hidden;
}

.question-card.answered {
    border-color: var(--survey-success);
    background: linear-gradient(135deg, rgba(16,185,129,0.04), rgba(6,214,160,0.03));
}

.question-card.answered::before {
    content: '✓';
    position: absolute;
    top: 1rem; right: 1rem;
    width: 24px; height: 24px;
    background: var(--survey-success);
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-size: 0.7rem; font-weight: 700; color: white;
}

.question-number {
    width: 32px; height: 32px;
    background: linear-gradient(135deg, #4f46e5, #06b6d4);
    border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    font-size: 0.8rem; font-weight: 800; color: #fff;
    flex-shrink: 0;
    margin-bottom: 0.8rem;
}

.question-text {
    font-weight: 700;
    font-size: 0.95rem;
    color: #1e293b;
    line-height: 1.5;
    margin-bottom: 1.2rem;
}

/* Radio options */
.radio-options { display: flex; flex-direction: column; gap: 0.6rem; }

.radio-option-input { display: none; }

.radio-option-label {
    display: flex;
    align-items: center;
    gap: 0.8rem;
    padding: 0.7rem 1rem;
    border: 2px solid var(--survey-border);
    border-radius: 12px;
    cursor: pointer;
    transition: all 0.2s ease;
    font-size: 0.9rem;
    font-weight: 500;
    color: #64748b;
    background: #fff;
    position: relative;
}

.radio-option-label:hover {
    border-color: var(--survey-primary-light);
    background: rgba(79,70,229,0.04);
    color: #1e293b;
}

.radio-custom {
    width: 20px; height: 20px;
    border: 2px solid var(--survey-border);
    border-radius: 50%;
    flex-shrink: 0;
    transition: all 0.2s ease;
    position: relative;
    background: #fff;
}

.radio-custom::after {
    content: '';
    position: absolute;
    top: 50%; left: 50%;
    transform: translate(-50%, -50%) scale(0);
    width: 8px; height: 8px;
    border-radius: 50%;
    background: #fff;
    transition: transform 0.2s cubic-bezier(0.34, 1.56, 0.64, 1);
}

.radio-option-badge {
    width: 22px; height: 22px;
    border-radius: 6px;
    background: rgba(79,70,229,0.08);
    display: flex; align-items: center; justify-content: center;
    font-size: 0.7rem; font-weight: 800;
    color: var(--survey-primary);
    flex-shrink: 0;
    transition: all 0.2s ease;
}

.radio-option-input:checked + .radio-option-label {
    border-color: var(--survey-primary);
    background: linear-gradient(135deg, rgba(79,70,229,0.08), rgba(6,182,212,0.05));
    color: #1e293b;
    font-weight: 600;
    box-shadow: 0 2px 10px rgba(79,70,229,0.15);
}

.radio-option-input:checked + .radio-option-label .radio-custom {
    border-color: var(--survey-primary);
    background: var(--survey-primary);
}

.radio-option-input:checked + .radio-option-label .radio-custom::after {
    transform: translate(-50%, -50%) scale(1);
}

.radio-option-input:checked + .radio-option-label .radio-option-badge {
    background: var(--survey-primary);
    color: #fff;
}

/* Score hint row */
.score-hints {
    display: flex;
    gap: 0.5rem;
    margin-bottom: 1rem;
    padding: 0.8rem 1rem;
    background: linear-gradient(135deg, rgba(79,70,229,0.06), rgba(6,182,212,0.04));
    border-radius: 12px;
    border: 1px solid rgba(79,70,229,0.12);
}

.score-hint {
    flex: 1;
    text-align: center;
    font-size: 0.65rem;
    font-weight: 600;
    padding: 0.4rem;
    border-radius: 8px;
}

.score-hint-1 { background: rgba(239,68,68,0.1); color: #dc2626; }
.score-hint-2 { background: rgba(245,158,11,0.1); color: #d97706; }
.score-hint-3 { background: rgba(6,182,212,0.1); color: #0891b2; }
.score-hint-4 { background: rgba(16,185,129,0.1); color: #059669; }

/* Textarea */
.textarea-premium {
    width: 100%;
    padding: 1rem 1.2rem;
    border: 2px solid var(--survey-border);
    border-radius: 16px;
    font-family: inherit;
    font-size: 0.95rem;
    font-weight: 500;
    color: #1e293b;
    background: #f8faff;
    resize: vertical;
    min-height: 120px;
    transition: all 0.25s ease;
    outline: none;
    line-height: 1.7;
}

.textarea-premium:focus {
    border-color: var(--survey-primary);
    background: #fff;
    box-shadow: 0 0 0 4px rgba(79,70,229,0.1);
}

.char-counter {
    text-align: right;
    font-size: 0.75rem;
    color: #64748b;
    margin-top: 0.3rem;
}

/* Navigation buttons */
.survey-nav {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1.5rem 2rem;
    border-top: 1px solid var(--survey-border);
    background: #fafbff;
}

.btn-survey-prev {
    display: flex; align-items: center; gap: 0.6rem;
    padding: 0.8rem 1.8rem;
    background: transparent;
    border: 2px solid var(--survey-border);
    border-radius: 14px;
    font-family: inherit;
    font-size: 0.9rem;
    font-weight: 700;
    color: #64748b;
    cursor: pointer;
    transition: all 0.25s ease;
}

.btn-survey-prev:hover {
    border-color: var(--survey-primary-light);
    color: var(--survey-primary);
    background: rgba(79,70,229,0.04);
}

.btn-survey-next {
    display: flex; align-items: center; gap: 0.6rem;
    padding: 0.8rem 2rem;
    background: linear-gradient(135deg, var(--survey-primary), #764ba2);
    border: none;
    border-radius: 14px;
    font-family: inherit;
    font-size: 0.95rem;
    font-weight: 700;
    color: #fff;
    cursor: pointer;
    transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
    box-shadow: 0 6px 20px rgba(79,70,229,0.35);
}

.btn-survey-next:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 28px rgba(79,70,229,0.45);
}

.btn-survey-next:active { transform: translateY(0); }

.btn-survey-submit {
    display: flex; align-items: center; gap: 0.6rem;
    padding: 0.9rem 2.5rem;
    background: linear-gradient(135deg, var(--survey-success), #06d6a0);
    border: none;
    border-radius: 14px;
    font-family: inherit;
    font-size: 1rem;
    font-weight: 800;
    color: #fff;
    cursor: pointer;
    transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
    box-shadow: 0 6px 20px rgba(16,185,129,0.4);
}

.btn-survey-submit:hover {
    transform: translateY(-2px);
    box-shadow: 0 12px 30px rgba(16,185,129,0.5);
}

.btn-survey-submit:disabled {
    opacity: 0.7;
    cursor: not-allowed;
    transform: none;
}

/* Step info badge */
.step-info-badge {
    background: rgba(79,70,229,0.08);
    color: var(--survey-primary);
    font-size: 0.8rem;
    font-weight: 700;
    padding: 0.4rem 1rem;
    border-radius: 100px;
}

/* Answer counter */
.answer-counter {
    background: rgba(16,185,129,0.1);
    color: var(--survey-success);
    font-size: 0.8rem;
    font-weight: 700;
    padding: 0.3rem 0.8rem;
    border-radius: 100px;
    display: inline-flex;
    align-items: center;
    gap: 0.3rem;
    transition: all 0.3s ease;
}

/* Layanan info card */
.layanan-info {
    background: linear-gradient(135deg, rgba(79,70,229,0.08), rgba(6,182,212,0.06));
    border: 1px solid rgba(79,70,229,0.15);
    border-radius: 14px;
    padding: 1rem 1.2rem;
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
    gap: 0.8rem;
}

.layanan-info-icon {
    font-size: 1.5rem;
    flex-shrink: 0;
}

.layanan-info-text {
    font-size: 0.85rem;
    font-weight: 600;
    color: var(--survey-primary);
    line-height: 1.4;
}

/* Responsive */
@media (max-width: 576px) {
    .survey-wrapper { padding: 1rem 0.75rem 3rem; }
    .section-body { padding: 1.2rem; }
    .survey-nav { padding: 1rem 1.2rem; gap: 0.5rem; }
    .btn-survey-prev { padding: 0.7rem 1rem; font-size: 0.8rem; }
    .btn-survey-next, .btn-survey-submit { padding: 0.7rem 1.2rem; font-size: 0.85rem; }
    .gender-cards { gap: 0.75rem; }
    .survey-title { font-size: 1.1rem; }
    .score-hints { display: none; }
    .step-label { display: none; }
}
</style>

<div class="survey-wrapper">
    <!-- Background blobs -->
    <div class="survey-blob survey-blob-1"></div>
    <div class="survey-blob survey-blob-2"></div>
    <div class="survey-blob survey-blob-3"></div>

    <div class="container" style="max-width: 780px; position: relative; z-index: 1;">

        <!-- Header -->
        <div class="survey-header-card">
            <div class="survey-logo">🏥</div>
            <h1 class="survey-title">SURVEI KEPUASAN MASYARAKAT</h1>
            <p class="survey-subtitle">UPTD. Laboratorium Kesehatan Kota Pangkalpinang</p>
            <div class="d-flex justify-content-center gap-2 mt-3 flex-wrap">
                <span class="badge" style="background: rgba(255,255,255,0.2); color: #fff; font-size: 0.75rem; padding: 0.4rem 0.8rem; border-radius: 100px; backdrop-filter: blur(8px);">
                    📋 9 Aspek Penilaian
                </span>
                <span class="badge" style="background: rgba(255,255,255,0.2); color: #fff; font-size: 0.75rem; padding: 0.4rem 0.8rem; border-radius: 100px; backdrop-filter: blur(8px);">
                    ⏱️ ± 3 Menit
                </span>
                <span class="badge" style="background: rgba(255,255,255,0.2); color: #fff; font-size: 0.75rem; padding: 0.4rem 0.8rem; border-radius: 100px; backdrop-filter: blur(8px);">
                    🔒 Data Aman & Rahasia
                </span>
            </div>
        </div>

        <!-- Progress -->
        <div class="survey-progress-wrap">
            <div class="progress-steps" id="progressSteps">
                <div class="progress-step active" data-step="1">
                    <div class="step-circle">1</div>
                    <div class="step-label">Profil</div>
                </div>
                <div class="progress-step" data-step="2">
                    <div class="step-circle">2</div>
                    <div class="step-label">Penilaian</div>
                </div>
                <div class="progress-step" data-step="3">
                    <div class="step-circle">3</div>
                    <div class="step-label">Saran</div>
                </div>
            </div>
            <div class="progress-bar-container">
                <div class="progress-bar-fill" id="progressBarFill"></div>
            </div>
        </div>

        <!-- Form -->
        <form id="survei-form">
            <input type="hidden" name="id_permintaan" value="<?= isset($id_permintaan) ? htmlspecialchars($id_permintaan) : 0 ?>">

            <!-- ========== STEP 1: PROFIL ========== -->
            <div class="survey-form-card form-section active" id="step-1">
                <div class="section-header">
                    <div class="section-icon">👤</div>
                    <div>
                        <div class="section-title">Langkah 1: Profil Responden</div>
                        <div class="section-desc">Isi data diri Anda untuk memulai survei</div>
                    </div>
                </div>
                <div class="section-body">

                    <!-- Date & Time row -->
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <div class="form-field-group mb-0">
                                <label class="form-label-premium">
                                    📅 Tanggal Survei
                                </label>
                                <input type="text" class="form-control-premium" value="<?= date('d F Y') ?>" disabled>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-field-group mb-0">
                                <label class="form-label-premium">
                                    🕐 Jam Kunjungan <span class="required-dot"></span>
                                </label>
                                <select class="form-select-premium" name="jam_survei" id="jam_survei" required>
                                    <option value="">-- Pilih Jam --</option>
                                    <option value="08.00 - 12.00">🌅 Pagi: 08.00 – 12.00</option>
                                    <option value="13.00 - 15.00">☀️ Siang: 13.00 – 15.00</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Gender -->
                    <div class="form-field-group">
                        <label class="form-label-premium">
                            ⚧ Jenis Kelamin <span class="required-dot"></span>
                        </label>
                        <div class="gender-cards">
                            <div>
                                <input type="radio" name="jenis_kelamin" id="jk_l" value="L" class="gender-card-input" required>
                                <label for="jk_l" class="gender-card-label">
                                    <span class="gender-emoji">👨</span>
                                    <span class="gender-text">Laki-laki</span>
                                </label>
                            </div>
                            <div>
                                <input type="radio" name="jenis_kelamin" id="jk_p" value="P" class="gender-card-input">
                                <label for="jk_p" class="gender-card-label">
                                    <span class="gender-emoji">👩</span>
                                    <span class="gender-text">Perempuan</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Age, Education, Job -->
                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="form-field-group mb-0">
                                <label class="form-label-premium">
                                    🎂 Usia (Tahun) <span class="required-dot"></span>
                                </label>
                                <input type="number" class="form-control-premium" name="usia" id="usia"
                                       placeholder="Misal: 28" required min="10" max="100">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-field-group mb-0">
                                <label class="form-label-premium">
                                    🎓 Pendidikan <span class="required-dot"></span>
                                </label>
                                <select class="form-select-premium" name="pendidikan" id="pendidikan" required>
                                    <option value="">-- Pilih --</option>
                                    <option value="SD">SD / Sederajat</option>
                                    <option value="SMP">SMP / Sederajat</option>
                                    <option value="SMA">SMA / Sederajat</option>
                                    <option value="D3">D3 / Diploma</option>
                                    <option value="S1">S1 / Sarjana</option>
                                    <option value="S2">S2 / Magister</option>
                                    <option value="S3">S3 / Doktor</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-field-group mb-0">
                                <label class="form-label-premium">
                                    💼 Pekerjaan <span class="required-dot"></span>
                                </label>
                                <select class="form-select-premium" name="pekerjaan" id="pekerjaan" required>
                                    <option value="">-- Pilih --</option>
                                    <option value="PNS">PNS / ASN</option>
                                    <option value="TNI">TNI</option>
                                    <option value="POLRI">POLRI</option>
                                    <option value="SWASTA">Swasta</option>
                                    <option value="WIRAUSAHA">Wirausaha</option>
                                    <option value="LAINNYA">Lainnya</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Layanan -->
                    <div class="form-field-group mt-3">
                        <label class="form-label-premium">
                            🔬 Jenis Layanan yang Diterima <span class="required-dot"></span>
                        </label>
                        <input type="text" class="form-control-premium" name="jenis_layanan" id="jenis_layanan"
                               placeholder="Contoh: Uji Laboratorium, Pemeriksaan Darah, dll."
                               required>
                    </div>
                </div>

                <div class="survey-nav">
                    <div class="step-info-badge">Langkah 1 dari 3</div>
                    <button type="button" class="btn-survey-next" onclick="goToStep(2)">
                        Selanjutnya <i class="bi bi-arrow-right"></i>
                    </button>
                </div>
            </div>

            <!-- ========== STEP 2: PENILAIAN ========== -->
            <div class="survey-form-card form-section" id="step-2">
                <div class="section-header">
                    <div class="section-icon">⭐</div>
                    <div>
                        <div class="section-title">Langkah 2: Penilaian Layanan</div>
                        <div class="section-desc">9 aspek penilaian – pilih yang paling sesuai pengalaman Anda</div>
                    </div>
                </div>
                <div class="section-body">

                    <div class="layanan-info">
                        <div class="layanan-info-icon">💡</div>
                        <div class="layanan-info-text">
                            Pilih salah satu dari 4 pilihan jawaban untuk setiap pertanyaan di bawah ini berdasarkan pengalaman Anda menerima layanan.
                        </div>
                    </div>

                    <!-- Score hints -->
                    <div class="score-hints">
                        <div class="score-hint score-hint-1">1 = Tidak Baik</div>
                        <div class="score-hint score-hint-2">2 = Kurang Baik</div>
                        <div class="score-hint score-hint-3">3 = Baik</div>
                        <div class="score-hint score-hint-4">4 = Sangat Baik</div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div style="font-size:0.85rem; font-weight:600; color: var(--survey-muted);">9 Unsur Penilaian</div>
                        <div class="answer-counter" id="answerCounter">
                            ✅ <span id="answeredCount">0</span>/9 dijawab
                        </div>
                    </div>

                    <?php
                    $questions = [
                        ['q' => 'q1', 'text' => 'Kesesuaian persyaratan pelayanan dengan jenis pelayanannya', 'opts' => ['Tidak sesuai', 'Kurang sesuai', 'Sesuai', 'Sangat sesuai'], 'icon' => '📋'],
                        ['q' => 'q2', 'text' => 'Kemudahan prosedur / alur pelayanan di unit ini', 'opts' => ['Tidak mudah', 'Kurang mudah', 'Mudah', 'Sangat mudah'], 'icon' => '🔄'],
                        ['q' => 'q3', 'text' => 'Kecepatan waktu dalam memberikan pelayanan', 'opts' => ['Tidak cepat', 'Kurang cepat', 'Cepat', 'Sangat cepat'], 'icon' => '⚡'],
                        ['q' => 'q4', 'text' => 'Kewajaran biaya / tarif yang dikenakan', 'opts' => ['Sangat mahal', 'Cukup mahal', 'Murah', 'Gratis'], 'icon' => '💰'],
                        ['q' => 'q5', 'text' => 'Kesesuaian hasil pelayanan dengan standar yang berlaku', 'opts' => ['Tidak sesuai', 'Kurang sesuai', 'Sesuai', 'Sangat sesuai'], 'icon' => '✅'],
                        ['q' => 'q6', 'text' => 'Kompetensi dan kemampuan petugas dalam pelayanan', 'opts' => ['Tidak kompeten', 'Kurang kompeten', 'Kompeten', 'Sangat kompeten'], 'icon' => '🎯'],
                        ['q' => 'q7', 'text' => 'Kesopanan dan keramahan petugas dalam melayani', 'opts' => ['Tidak sopan & ramah', 'Kurang sopan & ramah', 'Sopan & ramah', 'Sangat sopan & ramah'], 'icon' => '😊'],
                        ['q' => 'q8', 'text' => 'Kualitas sarana dan prasarana layanan', 'opts' => ['Buruk', 'Cukup', 'Baik', 'Sangat baik'], 'icon' => '🏢'],
                        ['q' => 'q9', 'text' => 'Penanganan pengaduan pengguna layanan', 'opts' => ['Tidak ada', 'Ada tetapi tidak berfungsi', 'Berfungsi kurang maksimal', 'Dikelola dengan baik'], 'icon' => '📣'],
                    ];
                    ?>

                    <?php foreach($questions as $index => $q): ?>
                    <div class="question-card" id="qcard-<?= $q['q'] ?>">
                        <div class="d-flex align-items-start gap-3">
                            <div>
                                <div class="question-number"><?= $index + 1 ?></div>
                            </div>
                            <div class="flex-grow-1">
                                <div class="question-text">
                                    <?= $q['icon'] ?> <?= $q['text'] ?>
                                </div>
                                <div class="radio-options">
                                    <?php foreach($q['opts'] as $val_idx => $opt):
                                        $val = $val_idx + 1;
                                        $id = $q['q'] . '_' . $val;
                                        $letters = ['a','b','c','d'];
                                    ?>
                                    <input type="radio"
                                           name="<?= $q['q'] ?>"
                                           id="<?= $id ?>"
                                           value="<?= $val ?>"
                                           class="radio-option-input"
                                           data-qname="<?= $q['q'] ?>"
                                           required>
                                    <label class="radio-option-label" for="<?= $id ?>">
                                        <div class="radio-custom"></div>
                                        <div class="radio-option-badge"><?= strtoupper($letters[$val_idx]) ?></div>
                                        <span><?= $opt ?></span>
                                    </label>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>

                </div>
                <div class="survey-nav">
                    <button type="button" class="btn-survey-prev" onclick="goToStep(1)">
                        <i class="bi bi-arrow-left"></i> Kembali
                    </button>
                    <div class="step-info-badge">Langkah 2 dari 3</div>
                    <button type="button" class="btn-survey-next" onclick="goToStep(3)">
                        Selanjutnya <i class="bi bi-arrow-right"></i>
                    </button>
                </div>
            </div>

            <!-- ========== STEP 3: SARAN ========== -->
            <div class="survey-form-card form-section" id="step-3">
                <div class="section-header" style="background: linear-gradient(135deg, #10b981, #06b6d4);">
                    <div class="section-icon">💬</div>
                    <div>
                        <div class="section-title">Langkah 3: Saran & Masukan</div>
                        <div class="section-desc">Ceritakan pengalaman Anda (opsional)</div>
                    </div>
                </div>
                <div class="section-body">

                    <div class="layanan-info" style="background: linear-gradient(135deg, rgba(16,185,129,0.08), rgba(6,182,212,0.05)); border-color: rgba(16,185,129,0.2);">
                        <div class="layanan-info-icon">🎉</div>
                        <div class="layanan-info-text" style="color: #059669;">
                            Hampir selesai! Jika ada saran atau masukan untuk peningkatan layanan kami, silakan tuliskan di bawah ini.
                        </div>
                    </div>

                    <div class="form-field-group">
                        <label class="form-label-premium">
                            ✍️ Saran, Masukan atau Kritik
                            <span style="font-weight: 500; color: var(--survey-muted); font-size: 0.8rem; margin-left: 0.4rem;">(opsional)</span>
                        </label>
                        <textarea name="komentar_saran" id="komentar_saran" class="textarea-premium"
                                  rows="5"
                                  maxlength="500"
                                  placeholder="Tuliskan saran atau masukan Anda untuk peningkatan layanan kami di masa mendatang..."></textarea>
                        <div class="char-counter"><span id="charCount">0</span>/500 karakter</div>
                    </div>

                    <!-- Summary preview -->
                    <div id="survey-summary" style="background: linear-gradient(135deg, rgba(79,70,229,0.05), rgba(6,182,212,0.04)); border: 1px solid rgba(79,70,229,0.12); border-radius: 16px; padding: 1.2rem 1.5rem;">
                        <div style="font-size: 0.85rem; font-weight: 700; color: var(--survey-primary); margin-bottom: 0.8rem;">📊 Ringkasan Penilaian Anda</div>
                        <div class="d-flex justify-content-around text-center flex-wrap gap-2">
                            <div>
                                <div style="font-size: 1.8rem; font-weight: 800; color: var(--survey-primary);" id="avgScore">-</div>
                                <div style="font-size: 0.7rem; color: var(--survey-muted); font-weight: 600;">Rata-rata Skor</div>
                            </div>
                            <div>
                                <div style="font-size: 1.8rem; font-weight: 800; color: var(--survey-success);" id="answeredTotal">0</div>
                                <div style="font-size: 0.7rem; color: var(--survey-muted); font-weight: 600;">Pertanyaan Dijawab</div>
                            </div>
                            <div>
                                <div style="font-size: 1.4rem; font-weight: 800;" id="mutuLabel">-</div>
                                <div style="font-size: 0.7rem; color: var(--survey-muted); font-weight: 600;">Kategori Mutu</div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="survey-nav">
                    <button type="button" class="btn-survey-prev" onclick="goToStep(2)">
                        <i class="bi bi-arrow-left"></i> Kembali
                    </button>
                    <div class="step-info-badge">Langkah 3 dari 3</div>
                    <button type="submit" class="btn-survey-submit" id="btn-submit">
                        <i class="bi bi-send-fill"></i> Kirim Survei
                    </button>
                </div>
            </div>
        </form>

        <!-- Footer note -->
        <div class="text-center mt-3" style="color: rgba(255,255,255,0.6); font-size: 0.8rem; font-weight: 500;">
            🔒 Semua data yang Anda berikan bersifat rahasia dan hanya digunakan untuk peningkatan layanan.
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
(function() {
    'use strict';

    let currentStep = 1;
    const totalSteps = 3;

    // ===== PROGRESS =====
    function updateProgress(step) {
        const pct = ((step - 1) / (totalSteps - 1)) * 100;
        document.getElementById('progressBarFill').style.width = pct + '%';

        document.querySelectorAll('.progress-step').forEach(el => {
            const s = parseInt(el.dataset.step);
            el.classList.remove('active', 'done');
            if (s === step) el.classList.add('active');
            else if (s < step) el.classList.add('done');
        });

        // Check icon in done steps
        document.querySelectorAll('.progress-step.done .step-circle').forEach(c => {
            c.innerHTML = '✓';
        });
        document.querySelectorAll('.progress-step:not(.done) .step-circle').forEach(c => {
            const s = parseInt(c.closest('.progress-step').dataset.step);
            c.textContent = s;
        });
    }

    // ===== NAVIGATION =====
    window.goToStep = function(target) {
        if (target > currentStep) {
            // Validate current step first
            if (!validateStep(currentStep)) return;
        }

        const current = document.getElementById('step-' + currentStep);
        const next = document.getElementById('step-' + target);

        current.classList.remove('active');

        next.style.animation = 'none';
        next.offsetHeight; // reflow
        if (target > currentStep) {
            next.style.animation = 'slideInRight 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94) forwards';
        } else {
            next.style.animation = 'slideInLeft 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94) forwards';
        }
        next.classList.add('active');

        currentStep = target;
        updateProgress(target);

        // Scroll to top of wrapper
        window.scrollTo({ top: 0, behavior: 'smooth' });

        if (target === 3) updateSummary();
    };

    // ===== VALIDATION =====
    function validateStep(step) {
        if (step === 1) {
            const jam = document.getElementById('jam_survei').value;
            const jk = document.querySelector('[name="jenis_kelamin"]:checked');
            const usia = document.getElementById('usia').value;
            const pend = document.getElementById('pendidikan').value;
            const pek = document.getElementById('pekerjaan').value;
            const layanan = document.getElementById('jenis_layanan').value.trim();

            if (!jam) { showError('Silakan pilih jam kunjungan Anda.', 'jam_survei'); return false; }
            if (!jk) { showError('Silakan pilih jenis kelamin.'); return false; }
            if (!usia || usia < 10 || usia > 100) { showError('Masukkan usia yang valid (10–100 tahun).', 'usia'); return false; }
            if (!pend) { showError('Silakan pilih pendidikan.', 'pendidikan'); return false; }
            if (!pek) { showError('Silakan pilih pekerjaan.', 'pekerjaan'); return false; }
            if (!layanan) { showError('Masukkan jenis layanan yang Anda terima.', 'jenis_layanan'); return false; }
            return true;
        }

        if (step === 2) {
            const names = ['q1','q2','q3','q4','q5','q6','q7','q8','q9'];
            for (let n of names) {
                if (!document.querySelector('[name="' + n + '"]:checked')) {
                    showError('Silakan jawab semua pertanyaan penilaian (pertanyaan belum dijawab ditemukan).');
                    // Highlight unanswered
                    document.getElementById('qcard-' + n).scrollIntoView({ behavior: 'smooth', block: 'center' });
                    document.getElementById('qcard-' + n).style.borderColor = '#ef4444';
                    document.getElementById('qcard-' + n).style.boxShadow = '0 0 0 3px rgba(239,68,68,0.15)';
                    setTimeout(() => {
                        document.getElementById('qcard-' + n).style.borderColor = '';
                        document.getElementById('qcard-' + n).style.boxShadow = '';
                    }, 3000);
                    return false;
                }
            }
            return true;
        }

        return true;
    }

    function showError(msg, fieldId) {
        if (typeof Swal !== 'undefined') {
            Swal.fire({ icon: 'warning', title: 'Perhatian', text: msg, confirmButtonColor: '#4f46e5' });
        } else {
            alert(msg);
        }
        if (fieldId) {
            const el = document.getElementById(fieldId);
            if (el) { el.focus(); el.style.borderColor = '#ef4444'; setTimeout(() => el.style.borderColor = '', 3000); }
        }
    }

    // ===== ANSWER COUNTER =====
    function updateAnswerCount() {
        let count = 0;
        ['q1','q2','q3','q4','q5','q6','q7','q8','q9'].forEach(q => {
            if (document.querySelector('[name="' + q + '"]:checked')) {
                count++;
                document.getElementById('qcard-' + q).classList.add('answered');
            } else {
                document.getElementById('qcard-' + q).classList.remove('answered');
            }
        });
        document.getElementById('answeredCount').textContent = count;
        document.getElementById('answeredTotal').textContent = count;
    }

    document.querySelectorAll('.radio-option-input').forEach(r => {
        r.addEventListener('change', updateAnswerCount);
    });

    // ===== SUMMARY =====
    function updateSummary() {
        const qs = ['q1','q2','q3','q4','q5','q6','q7','q8','q9'];
        let total = 0; let count = 0;
        qs.forEach(q => {
            const checked = document.querySelector('[name="' + q + '"]:checked');
            if (checked) { total += parseInt(checked.value); count++; }
        });
        const avg = count > 0 ? (total / count) : 0;
        document.getElementById('avgScore').textContent = avg > 0 ? avg.toFixed(2) : '-';

        // IKM approximation
        const ikm = avg * 25;
        let mutu = '-'; let mutuColor = '#64748b';
        if (ikm >= 88.31) { mutu = '⭐ Sangat Baik'; mutuColor = '#059669'; }
        else if (ikm >= 76.61) { mutu = '✅ Baik'; mutuColor = '#0891b2'; }
        else if (ikm >= 65.00) { mutu = '⚠️ Kurang Baik'; mutuColor = '#d97706'; }
        else if (avg > 0) { mutu = '❌ Tidak Baik'; mutuColor = '#dc2626'; }

        const mutuEl = document.getElementById('mutuLabel');
        mutuEl.textContent = mutu;
        mutuEl.style.color = mutuColor;
    }

    // ===== CHAR COUNTER =====
    const komentar = document.getElementById('komentar_saran');
    if (komentar) {
        komentar.addEventListener('input', function() {
            document.getElementById('charCount').textContent = this.value.length;
        });
    }

    // ===== FORM SUBMIT =====
    $('#survei-form').submit(function(e) {
        e.preventDefault();
        
        let btn = $('#btn-submit');
        let originalText = btn.html();
        btn.html('<i class="fas fa-spinner fa-spin me-2"></i> Mengirim...').prop('disabled', true);

        $.ajax({
            url: '<?php echo base_url('kesmas/survei/store'); ?>',
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    // Redirect ke halaman terima kasih — BUKAN ke detail survei
                    location.href = '<?php echo base_url('kesmas/survei/thankyou'); ?>';
                } else {
                    showError('Error: ' + response.message);
                    btn.html(originalText).prop('disabled', false);
                }
            },
            error: function() {
                showError('Terjadi kesalahan sistem saat mengirim survei.');
                btn.html(originalText).prop('disabled', false);
            }
        });
    });

    // Init
    updateProgress(1);
})();
</script>

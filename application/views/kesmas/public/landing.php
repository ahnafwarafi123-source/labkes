<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<style>
/* ===== HERO ===== */
.hero-section {
    padding: 4rem 0 3rem;
}
.hero-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.35rem 1rem;
    border-radius: 100px;
    background: rgba(13,110,253,.1);
    border: 1px solid rgba(13,110,253,.2);
    margin-bottom: 1.5rem;
    font-size: 0.85rem;
    font-weight: 700;
    color: #0d6efd;
}
.hero-title {
    font-weight: 900;
    font-size: clamp(2rem, 5vw, 3.2rem);
    letter-spacing: -1.5px;
    line-height: 1.15;
    color: #0b1220;
}
.hero-title span {
    background: linear-gradient(135deg, #0d6efd 0%, #0dcaf0 100%);
    background-clip: text;
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}
.hero-desc {
    font-size: 1.1rem;
    line-height: 1.7;
    color: #64748b;
    max-width: 520px;
}
.btn-hero {
    border-radius: 14px;
    font-weight: 700;
    padding: 0.8rem 2rem;
    font-size: 1rem;
    box-shadow: 0 10px 24px rgba(13,110,253,.25);
    transition: all 0.3s ease;
}
.btn-hero:hover {
    transform: translateY(-2px);
    box-shadow: 0 16px 32px rgba(13,110,253,.35);
}
.btn-hero-outline {
    border-radius: 14px;
    font-weight: 700;
    padding: 0.8rem 1.8rem;
    font-size: 1rem;
    border: 2px solid rgba(13,110,253,.25);
    color: #0d6efd;
    background: transparent;
    transition: all 0.3s ease;
}
.btn-hero-outline:hover {
    background: rgba(13,110,253,.06);
    border-color: #0d6efd;
    transform: translateY(-2px);
}

/* ===== ALUR ===== */
.flow-card {
    background: rgba(255,255,255,.85);
    border: 1px solid rgba(255,255,255,.9);
    backdrop-filter: blur(16px);
    border-radius: 28px;
    box-shadow: 0 10px 40px rgba(2,6,23,.08);
}
.flow-step-line {
    position: absolute;
    left: 24px;
    top: 48px;
    bottom: -20px;
    width: 2px;
    background: rgba(13,110,253,.12);
}
.flow-num {
    width: 48px;
    height: 48px;
    border-radius: 14px;
    background: linear-gradient(135deg, rgba(13,110,253,.12) 0%, rgba(13,110,253,.04) 100%);
    color: #0d6efd;
    border: 1px solid rgba(13,110,253,.2);
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 900;
    font-size: 1.2rem;
    position: relative;
    z-index: 2;
    flex-shrink: 0;
}
.flow-num.done {
    background: linear-gradient(135deg, rgba(16,185,129,.15) 0%, rgba(16,185,129,.04) 100%);
    color: #10b981;
    border-color: rgba(16,185,129,.3);
}

/* ===== INFO CARDS ===== */
.info-card {
    background: rgba(255,255,255,.85);
    border: 1px solid rgba(255,255,255,.9);
    border-radius: 24px;
    padding: 2rem 1.5rem;
    height: 100%;
    box-shadow: 0 8px 24px rgba(2,6,23,.06);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    text-align: center;
}
.info-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 18px 40px rgba(2,6,23,.10);
}
.info-icon {
    width: 68px;
    height: 68px;
    border-radius: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1.2rem;
    font-size: 1.8rem;
}

/* ===== STATS STRIP ===== */
.stats-strip {
    background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);
    border-radius: 24px;
    padding: 2.5rem 2rem;
    color: white;
    margin: 3rem 0;
    box-shadow: 0 12px 32px rgba(13,110,253,.25);
}
.stat-item {
    text-align: center;
    padding: 0.5rem 1.5rem;
}
.stat-item + .stat-item {
    border-left: 1px solid rgba(255,255,255,.2);
}
.stat-num {
    font-size: 2.2rem;
    font-weight: 900;
    letter-spacing: -1px;
    line-height: 1;
}
.stat-label {
    font-size: 0.85rem;
    opacity: 0.8;
    font-weight: 600;
    margin-top: 0.3rem;
}

/* ===== LAYANAN ===== */
.service-chip {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    border-radius: 100px;
    background: rgba(13,110,253,.08);
    border: 1px solid rgba(13,110,253,.15);
    font-size: 0.85rem;
    font-weight: 600;
    color: #0d6efd;
    transition: all 0.2s ease;
    cursor: default;
}
.service-chip:hover {
    background: rgba(13,110,253,.15);
}
.service-chip.green  { background: rgba(16,185,129,.08); border-color: rgba(16,185,129,.2); color: #10b981; }
.service-chip.green:hover  { background: rgba(16,185,129,.15); }
.service-chip.orange { background: rgba(245,158,11,.08); border-color: rgba(245,158,11,.2); color: #d97706; }
.service-chip.orange:hover { background: rgba(245,158,11,.15); }
.service-chip.purple { background: rgba(139,92,246,.08); border-color: rgba(139,92,246,.2); color: #7c3aed; }
.service-chip.purple:hover { background: rgba(139,92,246,.15); }
</style>

<div class="container hero-section animate-fade-up">

    <!-- ========== HERO ========== -->
    <div class="row align-items-center mb-2 g-5">
        <div class="col-lg-6">
            <div class="hero-badge">
                <i class="bi bi-patch-check-fill"></i>
                Portal Layanan Resmi UPTD Laboratorium
            </div>
            <h1 class="hero-title mb-3">
                Pengujian Sampel<br>
                <span>Kesehatan Masyarakat</span>
            </h1>
            <p class="hero-desc mb-4">
                Daftarkan pengujian sampel <strong>air, makanan, dan lingkungan</strong> secara online.
                Cepat, transparan, dan diproses oleh analis berpengalaman.
            </p>
            <div class="d-flex gap-3 flex-wrap align-items-center">
                <a href="<?= site_url('kesmas/public_pendaftaran/form') ?>" class="btn btn-primary btn-hero">
                    <i class="bi bi-file-earmark-plus me-2"></i>Daftar Pengujian
                </a>
                <a href="<?= site_url('kesmas/survei/form') ?>" class="btn-hero-outline btn">
                    <i class="bi bi-star me-2"></i>Isi Survei Kepuasan
                </a>
            </div>
        </div>

        <div class="col-lg-6 animate-fade-up delay-1">
            <div class="flow-card p-4 p-md-5">
                <h5 class="fw-bolder mb-4" style="color:#0b1220;">
                    <i class="bi bi-signpost-split text-primary me-2"></i>Alur Pelayanan
                </h5>

                <?php
                $steps = [
                    ['num' => '1', 'title' => 'Pendaftaran Online', 'desc' => 'Isi formulir dan data sampel melalui portal ini. Cetak bukti pendaftaran Anda.', 'done' => false],
                    ['num' => '2', 'title' => 'Penyerahan Sampel', 'desc' => 'Bawa sampel ke laboratorium (Jl. Delima Siam VI, Kec. Girimaya) beserta bukti pendaftaran.', 'done' => false],
                    ['num' => '3', 'title' => 'Proses Pengujian', 'desc' => 'Sampel diverifikasi dan diuji oleh analis bersertifikat sesuai standar SNI.', 'done' => false],
                    ['num' => '4', 'title' => 'Pengambilan Laporan', 'desc' => 'Laporan hasil uji diterbitkan dan dapat diambil di kantor atau dikirim sesuai permintaan.', 'done' => true],
                ];
                foreach ($steps as $idx => $step):
                    $isLast = ($idx === count($steps) - 1);
                ?>
                <div class="d-flex mb-<?= $isLast ? '0' : '4' ?> align-items-start" style="position:relative;">
                    <?php if (!$isLast): ?>
                    <div class="flow-step-line"></div>
                    <?php endif; ?>
                    <div class="me-3">
                        <div class="flow-num">
                            <?= $step['num'] ?>
                        </div>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-1" style="color:#0b1220;"><?= $step['title'] ?></h6>
                        <p class="text-muted small mb-0" style="line-height:1.6;"><?= $step['desc'] ?></p>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>


    <!-- ========== STATS STRIP ========== -->
    <div class="stats-strip animate-fade-up delay-2">
        <div class="row g-0 justify-content-center">
            <div class="col-6 col-md-4 stat-item">
                <div class="stat-num"><?= isset($stats['ikm_score']) ? number_format($stats['ikm_score'], 2) : '0.00' ?></div>
                <div class="stat-label">Indeks Kepuasan Masyarakat (IKM)</div>
            </div>
            <div class="col-6 col-md-4 stat-item">
                <div class="stat-num"><?= isset($stats['total_survei']) ? number_format($stats['total_survei'], 0, ',', '.') : '0' ?></div>
                <div class="stat-label">Total Responden</div>
            </div>
            <div class="col-12 col-md-4 stat-item mt-3 mt-md-0">
                <?php
                    $ikm = isset($stats['ikm_score']) ? (float)$stats['ikm_score'] : 0;
                    $mutu = 'Sangat Baik';
                    if ($ikm == 0) $mutu = '-';
                    elseif ($ikm < 65) $mutu = 'Tidak Baik';
                    elseif ($ikm < 76.61) $mutu = 'Kurang Baik';
                    elseif ($ikm < 88.31) $mutu = 'Baik';
                ?>
                <div class="stat-num" style="font-size: 1.8rem; display: flex; align-items: center; justify-content: center; height: 35px;"><?= $mutu ?></div>
                <div class="stat-label">Mutu Pelayanan</div>
            </div>
        </div>
    </div>

    <!-- ========== JENIS LAYANAN ========== -->
    <div class="text-center mb-4 animate-fade-up delay-2">
        <h4 class="fw-bolder mb-2" style="color:#0b1220;">Jenis Pengujian yang Tersedia</h4>
        <p class="text-muted">Kami melayani berbagai jenis pengujian sampel sesuai kebutuhan Anda</p>
        <div class="d-flex flex-wrap gap-2 justify-content-center mt-3">
            <span class="service-chip"><i class="bi bi-droplet-fill"></i> Air Minum</span>
            <span class="service-chip green"><i class="bi bi-water"></i> Air Bersih</span>
            <span class="service-chip orange"><i class="bi bi-egg-fried"></i> Makanan</span>
            <span class="service-chip purple"><i class="bi bi-globe-asia-australia"></i> Lingkungan</span>
        </div>
    </div>

    <!-- ========== INFO CARDS ========== -->
    <div class="row g-4 mt-1 animate-fade-up delay-3">
        <div class="col-md-4">
            <div class="info-card">
                <div class="info-icon" style="background:rgba(13,110,253,.1);">
                    <i class="bi bi-clock-history text-primary"></i>
                </div>
                <h5 class="fw-bold mb-2">Jam Operasional</h5>
                <p class="text-muted small mb-0">
                    <strong>Senin – Kamis:</strong> 08.00 – 15.00 WIB<br>
                    <strong>Jumat:</strong> 08.00 – 11.30 WIB<br>
                    <strong>Sabtu – Minggu:</strong> Tutup
                </p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="info-card">
                <div class="info-icon" style="background:rgba(16,185,129,.1);">
                    <i class="bi bi-geo-alt-fill text-success"></i>
                </div>
                <h5 class="fw-bold mb-2">Lokasi</h5>
                <p class="text-muted small mb-0">
                    <i class="bi bi-pin-map-fill me-1 text-success"></i>
                    Jl. Delima Siam VI, Sriwijaya<br>
                    Kec. Girimaya, Pangkalpinang
                </p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="info-card">
                <div class="info-icon" style="background:rgba(13,202,240,.1);">
                    <i class="bi bi-headset text-info"></i>
                </div>
                <h5 class="fw-bold mb-2">Hubungi Kami</h5>
                <p class="text-muted small mb-0">
                    <i class="bi bi-telephone-fill me-1"></i> 0878-7862-9499<br>
                    <i class="bi bi-envelope-fill me-1"></i>
                    <a href="mailto:upt-labkesehatan@pangkalpinangkota.go.id" class="text-muted text-decoration-none" style="font-size:0.78rem;">
                        upt-labkesehatan@<br>pangkalpinangkota.go.id
                    </a>
                </p>
            </div>
        </div>
    </div>

</div>

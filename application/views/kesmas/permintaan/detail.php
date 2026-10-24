<?php

/**
 * Permintaan Detail View
 * 
 * @var object $pendaftaran - Pendaftaran detail object
 * @var array $pemeriksaan - Array of pemeriksaan items
 * @var string $title - Page title
 */
?>
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
<style>
    :root {
        --ink: #0f172a;
        --muted: #64748b;
        --brand: #0d6efd;
        --glass: rgba(255, 255, 255, 0.95);
        --shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
        --shadow-hover: 0 15px 35px rgba(0, 0, 0, 0.08);
        --border: rgba(15, 23, 42, 0.08);
        --success: #10b981;
        --danger: #ef4444;
        --warning: #f59e0b;
        --info: #0ea5e9;
    }

    body {
        background-color: #f8fafc;
    }

    .page-hero {
        border-radius: 20px;
        border: 1px solid var(--border);
        background: radial-gradient(900px 340px at 12% 10%, rgba(13, 110, 253, .15), transparent 62%),
            radial-gradient(900px 340px at 92% 20%, rgba(34, 197, 94, .08), transparent 62%),
            linear-gradient(180deg, rgba(255, 255, 255, .9), rgba(255, 255, 255, .7));
        box-shadow: var(--shadow);
        padding: 1.5rem 1.75rem;
        margin-bottom: 1.5rem;
    }

    .detail-card {
        background: var(--glass);
        border: 1px solid var(--border);
        border-radius: 16px;
        box-shadow: var(--shadow);
        margin-bottom: 1.5rem;
        overflow: hidden;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .detail-card:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-hover);
    }

    .detail-header {
        background: rgba(248, 250, 252, 0.8);
        border-bottom: 1px solid var(--border);
        padding: 1.1rem 1.25rem;
        font-weight: 700;
        color: var(--ink);
        display: flex;
        align-items: center;
        gap: 0.6rem;
        font-size: 1.05rem;
        letter-spacing: 0.3px;
    }

    .detail-header i {
        color: var(--brand);
        font-size: 1.15rem;
    }

    .detail-body {
        padding: 0;
    }

    .info-row {
        display: flex;
        padding: 0.85rem 1.25rem;
        border-bottom: 1px dashed var(--border);
    }

    .info-row:last-child {
        border-bottom: none;
    }

    .info-label {
        width: 38%;
        color: var(--muted);
        font-weight: 600;
        font-size: 0.9rem;
    }

    .info-value {
        width: 62%;
        color: var(--ink);
        font-weight: 600;
        font-size: 0.95rem;
        word-wrap: break-word;
    }

    .stat-card {
        background: #fff;
        border: 1px solid var(--border);
        border-radius: 16px;
        padding: 1.25rem;
        display: flex;
        align-items: center;
        gap: 1rem;
        box-shadow: var(--shadow);
        transition: transform 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-2px);
    }

    .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }

    .stat-info {
        flex: 1;
    }

    .stat-title {
        color: var(--muted);
        font-size: 0.85rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 0.25rem;
    }

    .stat-value {
        color: var(--ink);
        font-size: 1.15rem;
        font-weight: 800;
        margin: 0;
    }

    .table-modern {
        width: 100%;
        margin-bottom: 0;
    }

    .table-modern thead th {
        background: #f8fafc;
        color: var(--muted);
        font-weight: 600;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 1rem 1.25rem;
        border-bottom: 1px solid var(--border);
    }

    .table-modern tbody td {
        padding: 1rem 1.25rem;
        border-bottom: 1px solid var(--border);
        color: var(--ink);
        font-weight: 500;
        vertical-align: middle;
    }

    .table-modern tbody tr:last-child td {
        border-bottom: none;
    }

    .btn-print {
        background: linear-gradient(135deg, var(--brand) 0%, #0a58ca 100%);
        border: none;
        color: #fff;
        font-weight: 700;
        padding: 0.6rem 1.5rem;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(13, 110, 253, 0.25);
        transition: all 0.3s ease;
    }

    .btn-print:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(13, 110, 253, 0.35);
        color: #fff;
    }

    .timeline-box {
        background: rgba(248, 250, 252, 0.8);
        border: 1px solid var(--border);
        border-left: 4px solid var(--brand);
        border-radius: 12px;
        padding: 1rem;
        margin-bottom: 1rem;
        transition: transform 0.3s ease;
    }

    .timeline-box:hover {
        transform: translateX(4px);
        background: #fff;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.04);
    }

    .timeline-label {
        font-size: 0.75rem;
        font-weight: 700;
        color: var(--muted);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 0.3rem;
    }

    .timeline-val {
        font-size: 0.95rem;
        font-weight: 700;
        color: var(--ink);
    }

    .badge-soft {
        padding: 0.4rem 0.8rem;
        border-radius: 8px;
        font-weight: 700;
        font-size: 0.85rem;
    }

    .badge-soft-success {
        background: rgba(16, 185, 129, 0.1);
        color: var(--success);
    }

    .badge-soft-danger {
        background: rgba(239, 68, 68, 0.1);
        color: var(--danger);
    }

    .badge-soft-secondary {
        background: rgba(100, 116, 139, 0.1);
        color: var(--muted);
    }
</style>

<div class="container-fluid py-4">
    <!-- HERO HEADER -->
    <div class="page-hero">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
            <div>
                <div class="d-flex align-items-center gap-3 mb-2">
                    <a class="btn btn-sm btn-outline-secondary" href="javascript:history.back();" style="border-radius: 8px; font-weight: 600;">
                        <i class="bi bi-arrow-left"></i> Kembali
                    </a>
                    <span class="badge bg-primary text-white" style="border-radius: 8px; font-size: 0.9rem; padding: 0.4rem 0.8rem;">
                        <?= htmlspecialchars($pendaftaran->no_registrasi ?: '-') ?>
                    </span>
                </div>
                <h3 style="color: var(--ink); font-weight: 800; margin: 0; font-size: 1.7rem;">
                    <i class="bi bi-flask text-primary me-1"></i> Detail Pendaftaran Sampel
                </h3>
            </div>
            <div>
                <a href="<?= site_url('k-panel/form_permintaan_kesmas/print/' . $pendaftaran->id_permintaan) ?>" target="_blank" class="btn btn-print">
                    <i class="bi bi-printer-fill me-1"></i> Cetak Formulir
                </a>
            </div>
        </div>
    </div>

    <!-- QUICK STATS -->
    <div class="row g-3 mb-4">
        <div class="col-md-6 col-lg-4">
            <div class="stat-card">
                <div class="stat-icon" style="background: rgba(13, 110, 253, 0.1); color: var(--brand);">
                    <i class="bi bi-clipboard-check"></i>
                </div>
                <div class="stat-info">
                    <div class="stat-title">Status Kelayakan</div>
                    <div class="stat-value">
                        <?php if ($pendaftaran->status_kelayakan): ?>
                            <span class="badge-soft <?= $pendaftaran->status_kelayakan === 'Layak' ? 'badge-soft-success' : 'badge-soft-danger' ?>">
                                <i class="bi <?= $pendaftaran->status_kelayakan === 'Layak' ? 'bi-check-circle-fill' : 'bi-x-circle-fill' ?> me-1"></i>
                                <?= htmlspecialchars($pendaftaran->status_kelayakan ?: '-') ?>
                            </span>
                        <?php else: ?>
                            <span class="badge-soft badge-soft-secondary">Belum Dikaji</span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-4">
            <div class="stat-card">
                <div class="stat-icon" style="background: rgba(16, 185, 129, 0.1); color: var(--success);">
                    <i class="bi bi-droplet-half"></i>
                </div>
                <div class="stat-info">
                    <div class="stat-title">Jenis Sampel</div>
                    <div class="stat-value text-success"><?= htmlspecialchars($pendaftaran->jenis_sampel ?: '-') ?></div>
                </div>
            </div>
        </div>
        <div class="col-md-12 col-lg-4">
            <div class="stat-card">
                <div class="stat-icon" style="background: rgba(245, 158, 11, 0.1); color: var(--warning);">
                    <i class="bi bi-folder2-open"></i>
                </div>
                <div class="stat-info">
                    <div class="stat-title">Kategori</div>
                    <div class="stat-value" style="color: var(--warning);"><?= htmlspecialchars($pendaftaran->kategori_sampel ?? '-') ?></div>
                </div>
            </div>
        </div>
    </div>

    <!-- MAIN CONTENT GRID -->
    <div class="row g-4">
        <div class="col-lg-6">
            <!-- IDENTITAS SAMPEL -->
            <div class="detail-card">
                <div class="detail-header"><i class="bi bi-box-seam"></i> Identitas Sampel</div>
                <div class="detail-body">
                    <div class="info-row">
                        <div class="info-label">No. Registrasi</div>
                        <div class="info-value fw-bold text-primary"><?= htmlspecialchars($pendaftaran->no_registrasi ?? '-') ?></div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Nama Sampel</div>
                        <div class="info-value"><?= htmlspecialchars($pendaftaran->nama_sampel ?? '-') ?></div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Volume</div>
                        <div class="info-value"><?= htmlspecialchars($pendaftaran->volume_ml ?? '-') ?> ml</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Tgl / Jam Ambil</div>
                        <div class="info-value"><?= htmlspecialchars($pendaftaran->tgl_pengambilan ?? '-') ?> <?= htmlspecialchars($pendaftaran->jam_pengambilan ?? '') ?></div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Petugas Pengambil</div>
                        <div class="info-value"><?= htmlspecialchars($pendaftaran->petugas_pengambil ?? '-') ?></div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Lokasi Ambil</div>
                        <div class="info-value"><?= htmlspecialchars($pendaftaran->lokasi_pengambilan ?? '-') ?></div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Info Tambahan</div>
                        <div class="info-value"><?= (!empty($pendaftaran->info_tambahan)) ? htmlspecialchars($pendaftaran->info_tambahan) : '-' ?></div>
                    </div>

                </div>
            </div>

            <!-- IDENTITAS PENGIRIM -->
            <div class="detail-card">
                <div class="detail-header"><i class="bi bi-person-vcard"></i> Identitas Pengirim</div>
                <div class="detail-body">
                    <div class="info-row">
                        <div class="info-label">Nama Pengirim</div>
                        <div class="info-value"><?= htmlspecialchars($pendaftaran->nama ?: '-') ?></div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Instansi</div>
                        <div class="info-value"><?= htmlspecialchars($pendaftaran->instansi ?: '-') ?></div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">No. Telepon</div>
                        <div class="info-value"><?= htmlspecialchars($pendaftaran->telp ?: '-') ?></div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Alamat</div>
                        <div class="info-value"><?= htmlspecialchars($pendaftaran->alamat ?: '-') ?></div>
                    <div class="info-row">
                        <div class="info-label">Kecamatan</div>
                        <div class="info-value"><?= htmlspecialchars($pendaftaran->kecamatan ?: '-') ?></div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Kelurahan</div>
                        <div class="info-value"><?= htmlspecialchars($pendaftaran->kelurahan ?: '-') ?></div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Objek</div>
                        <div class="info-value"><?= htmlspecialchars($pendaftaran->objek ?: '-') ?></div>
                    </div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Tgl. Permintaan</div>
                        <div class="info-value"><?= htmlspecialchars($pendaftaran->tgl_permintaan ?: '-') ?></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <!-- KAJI ULANG & BIAYA -->
            <div class="detail-card">
                <div class="detail-header"><i class="bi bi-clipboard-check"></i> Kaji Ulang & Administrasi</div>
                <div class="detail-body">
                    <?php
                    $alasan_list = $pendaftaran->alasan ? array_map('trim', explode(',', $pendaftaran->alasan)) : [];
                    $alasan_list = array_filter($alasan_list);
                    if (!empty($alasan_list)):
                    ?>
                        <div class="info-row">
                            <div class="info-label text-danger"><i class="bi bi-exclamation-triangle me-1"></i> Alasan (Tidak Layak) / Keterangan</div>
                            <div class="info-value">
                                <ul class="mb-0 ps-3 text-danger">
                                    <?php foreach ($alasan_list as $alasan): ?>
                                        <li><?= htmlspecialchars($alasan) ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        </div>
                    <?php endif; ?>
                    <?php if (!empty($pendaftaran->catatan)): ?>
                        <div class="info-row">
                            <div class="info-label text-danger"><i class="bi bi-lightbulb me-1"></i> Solusi / Catatan Penolakan</div>
                            <div class="info-value text-danger fw-bold">
                                <?= nl2br(htmlspecialchars($pendaftaran->catatan)) ?>
                            </div>
                        </div>
                    <?php endif; ?>
                    <div class="info-row">
                        <div class="info-label">Tindakan Sampel</div>
                        <div class="info-value"><?= htmlspecialchars($pendaftaran->tindakan_sampel ?: '-') ?></div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Total Biaya</div>
                        <div class="info-value"><?= $pendaftaran->jumlah_biaya ? 'Rp ' . number_format($pendaftaran->jumlah_biaya, 0, ',', '.') : '-' ?></div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Metode Bayar</div>
                        <div class="info-value"><?= htmlspecialchars($pendaftaran->cara_bayar ?: '-') ?> <?= $pendaftaran->keterangan_bayar ? '(' . htmlspecialchars($pendaftaran->keterangan_bayar) . ')' : '' ?></div>
                    </div>
                </div>
            </div>

            <!-- PETUGAS TERKAIT -->
            <div class="detail-card">
                <div class="detail-header"><i class="bi bi-people"></i> Petugas Terkait</div>
                <div class="detail-body">
                    <div class="info-row">
                        <div class="info-label">Petugas Pendaftaran</div>
                        <div class="info-value"><?= htmlspecialchars($pendaftaran->petugas_pendaftaran_name ?: '-') ?></div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Petugas Pengambil Sampel 1</div>
                        <div class="info-value"><?= htmlspecialchars($pendaftaran->petugas_pengambil ?: '-') ?></div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Petugas Pengambil Sampel 2</div>
                        <div class="info-value"><?= htmlspecialchars($pendaftaran->petugas_pengambil_2_name ?: '-') ?></div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Petugas Verifikasi</div>
                        <div class="info-value"><?= htmlspecialchars($pendaftaran->petugas_verifikasi_name ?: '-') ?></div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Petugas Validasi</div>
                        <div class="info-value"><?= htmlspecialchars($pendaftaran->petugas_validasi_name ?: '-') ?></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- DAFTAR PEMERIKSAAN -->
    <div class="detail-card">
        <div class="detail-header"><i class="bi bi-list-check"></i> Daftar Pemeriksaan</div>
        <div class="table-responsive">
            <table class="table table-modern">
                <thead>
                    <tr>
                        <th width="8%" class="text-center">No</th>
                        <th width="30%">Kelompok</th>
                        <th>Nama Pemeriksaan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($pemeriksaan)): ?>
                        <?php $no = 1;
                        foreach ($pemeriksaan as $row): ?>
                            <tr>
                                <td class="text-center"><span class="badge bg-light text-dark border border-secondary"><?= $no++ ?></span></td>
                                <td><span class="badge bg-secondary opacity-75"><?= htmlspecialchars(normalize_kelompok($row->kelompok, false)) ?></span></td>
                                <td class="fw-bold"><?= htmlspecialchars($row->nama_pemeriksaan) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="3" class="text-center text-muted py-4">
                                <i class="bi bi-inbox fs-4 d-block mb-2"></i> Tidak ada pemeriksaan yang dipilih
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>



    <!-- CATATAN -->
    <div class="detail-card mb-5">
        <div class="detail-header"><i class="bi bi-chat-square-text"></i> Catatan Tambahan</div>
        <div class="detail-body p-4">
            <?php
            $infoTambahan = $pendaftaran->info_tambahan ?? '';
            $catatan = $pendaftaran->catatan ?? '';

            // Requirement from user: catatan tambahan harus sama dengan informasi tambahan.
            // Jadi tampilkan info_tambahan sebagai sumber utama untuk area ini.
            $valueToShow = $infoTambahan;
            ?>
            <?php if (!empty($valueToShow) && trim((string)$valueToShow) !== ''): ?>
                <div class="alert alert-warning mb-0 border-0" style="background-color: #fffbeb; color: #b45309; border-radius: 12px; font-weight: 500; line-height: 1.6;">
                    <i class="bi bi-info-circle-fill me-2"></i> <?= nl2br(htmlspecialchars($valueToShow)) ?>
                </div>
            <?php else: ?>
                <div class="text-muted" style="font-style: italic;">Tidak ada catatan tambahan untuk permintaan ini.</div>
            <?php endif; ?>

        </div>
    </div>

</div>

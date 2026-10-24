<div class="container mt-4 mb-5" style="max-width: 800px;">
    <div class="card border-0 animate-fade-up" style="background: var(--glass); border: 1px solid rgba(255,255,255,0.9) !important; backdrop-filter: blur(16px); border-radius: 28px; box-shadow: var(--shadow);">
        <div class="card-body p-5">
            <div class="text-center mb-5">
                <h2 style="font-weight: 900; color: var(--ink); letter-spacing: -0.5px;">Pantau Status Pengujian</h2>
                <p class="text-muted" style="font-size: 1.05rem;">Masukkan Nomor Registrasi untuk melihat progres sampel Anda secara real-time.</p>
            </div>

            <form method="get" action="<?= site_url('k-panel/track') ?>" class="mb-5">
                <div class="input-group input-group-lg" style="box-shadow: 0 12px 30px rgba(13,110,253,.08); border-radius: 20px; padding: 4px; background: #fff; border: 1px solid rgba(13,110,253,.15);">
                    <input type="text" name="no_registrasi" class="form-control border-0 bg-transparent shadow-none" placeholder="Cth: KESMAS.20260404.0001" value="<?= htmlspecialchars($no_registrasi_query ?? '') ?>" required style="border-radius: 16px 0 0 16px; padding-left: 1.5rem; font-weight: 600; color: var(--ink);">
                    <button class="btn btn-primary px-4" type="submit" style="border-radius: 16px; font-weight: 800; letter-spacing: 0.5px;">
                        <i class="bi bi-search me-2"></i>Cari
                    </button>
                </div>
            </form>

            <?php if ($no_registrasi_query): ?>
                <?php if ($permintaan): ?>
                    <div class="card border-0 animate-fade-up delay-1" style="background: rgba(248,250,252,0.9); border: 1px solid rgba(15,23,42,.05); border-radius: 24px;">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-3">
                                <div>
                                    <div class="small text-muted mb-1">Nomor Registrasi</div>
                                    <h4 style="font-weight: 800; margin: 0; color: var(--ink);"><?= htmlspecialchars($permintaan['no_registrasi']) ?></h4>
                                </div>
                                <span class="badge bg-<?= $permintaan['status_color'] ?>" style="font-size: 0.9rem; padding: 0.6rem 1.2rem; border-radius: 12px; font-weight: 700; box-shadow: 0 4px 12px rgba(var(--bs-<?= $permintaan['status_color'] ?>-rgb), 0.2);">
                                    <?= htmlspecialchars($permintaan['status_text']) ?>
                                </span>
                            </div>

                            <div class="row mb-4">
                                <div class="col-md-6 mb-3">
                                    <div class="small text-muted mb-1">Nama Sampel</div>
                                    <div style="font-weight: 600; color: var(--ink);"><?= htmlspecialchars($permintaan['nama_sampel']) ?></div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="small text-muted mb-1">Pengirim</div>
                                    <div style="font-weight: 600; color: var(--ink);"><?= htmlspecialchars($permintaan['nama']) ?></div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="small text-muted mb-1">Tanggal Permintaan</div>
                                    <div style="font-weight: 600; color: var(--ink);"><?= htmlspecialchars($permintaan['tgl_permintaan']) ?></div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="small text-muted mb-1">Instansi</div>
                                    <div style="font-weight: 600; color: var(--ink);"><?= htmlspecialchars($permintaan['instansi'] ?: '-') ?></div>
                                </div>
                            </div>

                            <!-- Progress Bar -->
                            <div class="mb-4">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <small class="text-muted">Progress Pengujian</small>
                                    <small class="text-muted">Tahap <?= $permintaan['step'] > 0 ? $permintaan['step'] : 0 ?>/5</small>
                                </div>
                                <div class="progress" style="height: 10px; border-radius: 6px; background-color: rgba(15,23,42,.05);">
                                    <?php 
                                        $progress_width = 0;
                                        if ($permintaan['step'] != null) $progress_width = 20;
                                        elseif ($permintaan['step'] == 2) $progress_width = 40;
                                        elseif ($permintaan['step'] == 3) $progress_width = 60;
                                        elseif ($permintaan['step'] == 4) $progress_width = 80;
                                        elseif ($permintaan['step'] == 5) $progress_width = 100;
                                    ?>
                                    <div class="progress-bar bg-<?= $permintaan['status_color'] ?> progress-bar-striped progress-bar-animated" role="progressbar" 
                                         style="width: <?= $progress_width ?>%;" 
                                         aria-valuenow="<?= $progress_width ?>" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                
                                <!-- Step Indicators -->
                                <div class="d-flex justify-content-between mt-3" style="font-size: 0.75rem; font-weight: 600;">
                                    <?php if ($permintaan['step'] === 0): ?>
                                        <div class="text-center w-100 text-danger">
                                            <i class="bi bi-x-circle-fill fs-2"></i>
                                            <div class="mt-1">Sampel Ditolak</div>
                                        </div>
                                    <?php else: ?>
                                    <div class="text-center" style="width: 20%; position: relative;">
                                        <div class="rounded-circle mx-auto mb-2 d-flex align-items-center justify-content-center text-white shadow-sm" style="width: 28px; height: 28px; background: <?= $permintaan['step'] >= 1 ? 'var(--bs-secondary)' : '#e2e8f0' ?>; transition: all 0.3s;"><i class="bi <?= $permintaan['step'] > 1 ? 'bi-check2' : 'bi-1-circle' ?>"></i></div>
                                        <small style="color: <?= $permintaan['step'] >= 1 ? 'var(--ink)' : 'var(--muted)' ?>;">Terdaftar</small>
                                    </div>
                                    <div class="text-center" style="width: 20%;">
                                        <div class="rounded-circle mx-auto mb-2 d-flex align-items-center justify-content-center text-white shadow-sm" style="width: 28px; height: 28px; background: <?= $permintaan['step'] >= 2 ? 'var(--bs-info)' : '#e2e8f0' ?>; transition: all 0.3s;"><i class="bi <?= $permintaan['step'] > 2 ? 'bi-check2' : 'bi-2-circle' ?>"></i></div>
                                        <small style="color: <?= $permintaan['step'] >= 2 ? 'var(--ink)' : 'var(--muted)' ?>;">Diterima</small>
                                    </div>
                                    <div class="text-center" style="width: 20%;">
                                        <div class="rounded-circle mx-auto mb-2 d-flex align-items-center justify-content-center text-white shadow-sm" style="width: 28px; height: 28px; background: <?= $permintaan['step'] >= 3 ? 'var(--bs-warning)' : '#e2e8f0' ?>; transition: all 0.3s;"><i class="bi <?= $permintaan['step'] > 3 ? 'bi-check2' : 'bi-3-circle' ?>"></i></div>
                                        <small style="color: <?= $permintaan['step'] >= 3 ? 'var(--ink)' : 'var(--muted)' ?>;">Diuji</small>
                                    </div>
                                    <div class="text-center" style="width: 20%;">
                                        <div class="rounded-circle mx-auto mb-2 d-flex align-items-center justify-content-center text-white shadow-sm" style="width: 28px; height: 28px; background: <?= $permintaan['step'] >= 4 ? 'var(--bs-success)' : '#e2e8f0' ?>; transition: all 0.3s;"><i class="bi <?= $permintaan['step'] > 4 ? 'bi-check2' : 'bi-4-circle' ?>"></i></div>
                                        <small style="color: <?= $permintaan['step'] >= 4 ? 'var(--ink)' : 'var(--muted)' ?>;">Selesai Uji</small>
                                    </div>
                                    <div class="text-center" style="width: 20%;">
                                        <div class="rounded-circle mx-auto mb-2 d-flex align-items-center justify-content-center text-white shadow-sm" style="width: 28px; height: 28px; background: <?= $permintaan['step'] >= 5 ? 'var(--bs-primary)' : '#e2e8f0' ?>; transition: all 0.3s;"><i class="bi <?= $permintaan['step'] >= 5 ? 'bi-check2-all' : 'bi-5-circle' ?>"></i></div>
                                        <small style="color: <?= $permintaan['step'] >= 5 ? 'var(--ink)' : 'var(--muted)' ?>;">Selesai</small>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <h6 style="font-weight: 800; color: var(--ink);" class="mb-3">Item Pemeriksaan:</h6>
                            <ul class="list-group list-group-flush mb-0" style="border-radius: 16px; overflow: hidden; border: 1px solid rgba(15,23,42,.08);">
                                <?php foreach ($items as $item): ?>
                                    <li class="list-group-item bg-white px-3 py-3" style="border-color: rgba(15,23,42,.05);">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span style="font-weight: 600; color: #334155;"><i class="bi bi-dot text-primary"></i> <?= htmlspecialchars($item['nama_pemeriksaan']) ?></span>
                                            <?php if ($permintaan['step'] >= 4 && !empty($item['keterangan'])): ?>
                                                <?php 
                                                    $is_ms = ($item['keterangan'] === 'MS');
                                                    $badge_class = $is_ms ? 'success' : ($item['keterangan'] === 'TMS' ? 'danger' : 'secondary');
                                                ?>
                                                <span class="badge bg-<?= $badge_class ?> rounded-pill px-3 py-2"><?= htmlspecialchars($item['keterangan']) ?></span>
                                            <?php elseif ($permintaan['step'] == 0): ?>
                                                <span class="badge bg-danger rounded-pill px-3 py-2"><i class="bi bi-x-circle me-1"></i> Batal</span>
                                            <?php else: ?>
                                                <span class="badge bg-light text-muted border rounded-pill px-3 py-2"><i class="bi bi-clock me-1"></i> Proses</span>
                                            <?php endif; ?>
                                        </div>
                                    </li>
                                <?php endforeach; ?>
                            </ul>

                            <div class="mt-4 text-center">
                                <a href="<?= site_url('k-panel/print_bukti/'.$permintaan['no_registrasi']) ?>" target="_blank" class="btn btn-light border text-primary" style="border-radius: 14px; font-weight: 700; padding: 0.6rem 1.5rem; transition: all 0.2s ease;" onmouseover="this.style.background='rgba(13,110,253,.05)';" onmouseout="this.style.background='#fff';">
                                    <i class="bi bi-printer-fill me-2"></i>Cetak Bukti Pendaftaran
                                </a>
                            </div>

                        </div>
                    </div>
                <?php else: ?>
                    <div class="alert alert-danger d-flex align-items-center animate-fade-up delay-1" style="border-radius: 16px; border: none; background: #fee2e2; color: #991b1b; padding: 1.5rem;">
                        <i class="bi bi-exclamation-triangle-fill fs-3 me-3"></i>
                        <div>
                            <h6 class="fw-bold mb-1">Data Tidak Ditemukan</h6>
                            <span style="opacity: 0.9;">Maaf, Nomor Registrasi <strong><?= htmlspecialchars($no_registrasi_query) ?></strong> tidak terdaftar dalam sistem kami. Silakan periksa kembali.</span>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endif; ?>

        </div>
    </div>
</div>

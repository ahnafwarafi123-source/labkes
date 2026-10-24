<div class="content-wrapper">
    <section class="content pt-4">
        <div class="container-fluid">
            
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3 class="text-dark fw-bold mb-0">Laporan Hasil Uji & Catatan Klinis SOAP</h3>
                <a href="<?= site_url('klinik/hasil_laporan') ?>" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Kembali
                </a>
            </div>
            <hr style="border-top: 2px solid #1e3a8a; margin-bottom: 20px;">

            <!-- BAGIAN ATAS: IDENTITAS PASIEN -->
            <div class="card shadow-sm mb-4 border-top border-primary border-3">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0"><i class="fas fa-user-injured me-2"></i> Identitas Pasien</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-sm table-borderless mb-0">
                                <tr>
                                    <th width="30%">Nama Pasien</th>
                                    <td>: <?= isset($pasien->nama_pasien) ? htmlspecialchars($pasien->nama_pasien) : '-' ?></td>
                                </tr>
                                <tr>
                                    <th>No. RM</th>
                                    <td>: <?= isset($pasien->no_rm) ? htmlspecialchars($pasien->no_rm) : '-' ?></td>
                                </tr>
                                <tr>
                                    <th>NIK</th>
                                    <td>: <?= isset($pasien->nik) ? htmlspecialchars($pasien->nik) : '-' ?></td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-sm table-borderless mb-0">
                                <tr>
                                    <th width="30%">Umur</th>
                                    <td>: <?php 
                                        if (!empty($pasien->tgl_lahir) && $pasien->tgl_lahir != '0000-00-00') {
                                            $birthDate = new DateTime($pasien->tgl_lahir);
                                            $today = new DateTime('today');
                                            $age = $birthDate->diff($today)->y;
                                            echo $age . ' Tahun';
                                        } else {
                                            echo '-';
                                        }
                                    ?></td>
                                </tr>
                                <tr>
                                    <th>Jenis Kelamin</th>
                                    <td>: <?= isset($pasien->gender) ? htmlspecialchars($pasien->gender) : '-' ?></td>
                                </tr>
                                <tr>
                                    <th>Tgl. Kunjungan</th>
                                    <td>: <?= isset($pasien->tanggal_kunjungan) ? date('d-m-Y H:i', strtotime($pasien->tanggal_kunjungan)) : '-' ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- BAGIAN TENGAH: RIWAYAT SOAP -->
            <?php if (!empty($pasien->kunjungan_id)): ?>
            <div class="card shadow-sm mb-4 border-top border-warning border-3">
                <div class="card-header bg-warning text-dark fw-bold d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0"><i class="fas fa-stethoscope me-2"></i> Rincian Catatan Klinis SOAP</h5>
                    <?php if (!empty($pasien->kunjungan_id)): ?>
                    <a href="<?= site_url('klinik/uji_rekam_medis/print_soap_pdf/' . $pasien->kunjungan_id) ?>" target="_blank" class="btn btn-sm btn-danger">
                        <i class="fas fa-file-pdf me-1"></i> Cetak PDF SOAP
                    </a>
                    <?php endif; ?>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <strong>Keluhan Utama (Subjective):</strong>
                            <p class="text-muted"><?= nl2br(htmlspecialchars($pasien->keluhan_utama ?? '-')) ?></p>
                            
                            <strong>Riwayat Penyakit:</strong>
                            <p class="text-muted"><?= nl2br(htmlspecialchars($pasien->riwayat_penyakit ?? '-')) ?></p>
                        </div>
                        <div class="col-md-6">
                            <strong>Asesmen/Diagnosa (Assessment):</strong>
                            <p class="text-muted"><?= nl2br(htmlspecialchars($pasien->asesmen_diagnosa ?? '-')) ?></p>
                            
                            <strong>Rencana (Plan):</strong>
                            <p class="text-muted"><?= nl2br(htmlspecialchars($pasien->plan_rencana ?? '-')) ?></p>
                        </div>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between text-muted small">
                        <span><strong>Dokter Pemeriksa:</strong> <?= htmlspecialchars($pasien->nama_dokter_pemeriksa ?? '-') ?></span>
                        <span><strong>Unit:</strong> <?= htmlspecialchars($pasien->unit ?? '-') ?></span>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <!-- BAGIAN BAWAH: LABORATORIUM -->
            <div class="card shadow-sm mb-4 border-top border-success border-3">
                <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0"><i class="fas fa-flask me-2"></i> Hasil Uji Klinik Laboratorium</h5>
                    <?php if (!empty($form) && !empty($form->id_form)): ?>
                    <a href="<?= site_url('klinik/laporan_uji_klinik/export_pdf/' . $form->id_form) ?>" target="_blank" class="btn btn-sm btn-danger">
                        <i class="fas fa-file-pdf me-1"></i> Cetak PDF Lab
                    </a>
                    <?php endif; ?>
                </div>
                
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped mb-0">
                            <thead style="background-color: #e9f7ef;">
                                <tr>
                                    <th class="text-center" width="5%">No</th>
                                    <th>Hasil Pemeriksaan</th>
                                    <th class="text-center">Hasil</th>
                                    <th class="text-center">Nilai Rujukan</th>
                                    <th class="text-center">Satuan</th>
                                    <th class="text-center">Metode</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($hasil)): ?>
                                    <?php 
                                    $no = 1; 
                                    $kategori = '';
                                    $sub = '';
                                    foreach ($hasil as $h): 
                                    
                                        // === HEADER KATEGORI ===
                                        if ($kategori != $h->kategori):
                                            $kategori = $h->kategori;
                                            $sub = ''; // reset sub kategori
                                    ?>
                                    <tr class="table-secondary fw-bold text-uppercase">
                                        <td colspan="6" class="text-start ps-3" style="background-color: #e2e3e5; color: #383d41;"><?= htmlspecialchars($kategori) ?></td>
                                    </tr>
                                    <?php endif; ?>

                                    <?php
                                        // === HEADER SUB KATEGORI ===
                                        if (!empty($h->sub_kategori) && $sub != $h->sub_kategori):
                                            $sub = $h->sub_kategori;
                                    ?>
                                    <tr class="table-light fw-semibold text-uppercase">
                                        <td colspan="6" class="text-start ps-4" style="background-color: #f8f9fa; color: #495057; font-size: 13px;"><?= htmlspecialchars($sub) ?></td>
                                    </tr>
                                    <?php endif; ?>
                                    
                                    <tr>
                                        <td class="text-center"><?= $no++ ?></td>
                                        <td class="ps-4"><?= htmlspecialchars($h->nama_pemeriksaan) ?></td>
                                        <td class="text-center fw-bold text-primary"><?= htmlspecialchars($h->hasil) ?></td>
                                        <td class="text-center"><?= htmlspecialchars($h->nilai_rujukan) ?></td>
                                        <td class="text-center"><?= htmlspecialchars($h->satuan) ?></td>
                                        <td class="text-center"><?= htmlspecialchars($h->metode) ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="6" class="text-center py-4 text-muted">Belum ada hasil pemeriksaan yang diinputkan.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- CATATAN & PETUGAS LABORATORIUM -->
                <?php if (isset($pengambilan) && !empty($pengambilan->petugas_pengambilan)): ?>
                <div class="card-body bg-light border-top">
                    <h6 class="fw-bold mb-3 text-secondary border-bottom pb-2">Catatan & Petugas Laboratorium</h6>
                    <div class="row" style="font-size: 14px;">
                        <div class="col-md-6">
                            <table class="table table-sm table-borderless mb-0">
                                <tr>
                                    <td width="40%" class="fw-semibold text-secondary">Petugas Sampling</td>
                                    <td width="2%">:</td>
                                    <td><?= htmlspecialchars($pengambilan->petugas_pengambilan) ?></td>
                                </tr>
                                <tr>
                                    <td class="fw-semibold text-secondary">Waktu Sampling</td>
                                    <td>:</td>
                                    <td><?= $pengambilan->tgl_jam_pengambilan ? date('d-m-Y H:i', strtotime($pengambilan->tgl_jam_pengambilan)) : '-' ?></td>
                                </tr>
                                <tr>
                                    <td class="fw-semibold text-secondary">Petugas Hasil</td>
                                    <td>:</td>
                                    <td><?= htmlspecialchars($pengambilan->petugas_hasil ?? '-') ?></td>
                                </tr>
                                <tr>
                                    <td class="fw-semibold text-secondary">Waktu Periksa</td>
                                    <td>:</td>
                                    <td><?= $pengambilan->tgl_jam_pemeriksaan ? date('d-m-Y H:i', strtotime($pengambilan->tgl_jam_pemeriksaan)) : '-' ?></td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-sm table-borderless mb-0">
                                <tr>
                                    <td width="40%" class="fw-semibold text-secondary">Verifikator</td>
                                    <td width="2%">:</td>
                                    <td><?= htmlspecialchars($pengambilan->verifikator_hasil ?? '-') ?></td>
                                </tr>
                                <tr>
                                    <td class="fw-semibold text-secondary">Penanggung Jawab</td>
                                    <td>:</td>
                                    <td>
                                        <?= htmlspecialchars($pengambilan->penanggung_jawab_teknis ?? '-') ?><br>
                                        <small class="text-muted">SIP: <?= htmlspecialchars($pengambilan->sip_penanggung ?? '-') ?></small>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fw-semibold text-secondary">Catatan / Note</td>
                                    <td>:</td>
                                    <td><?= nl2br(htmlspecialchars($pengambilan->note ?? '-')) ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </div>

        </div>
    </section>
</div>

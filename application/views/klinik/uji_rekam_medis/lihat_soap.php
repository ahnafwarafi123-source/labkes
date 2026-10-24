<div class="content-wrapper bg-light">
    <section class="content pt-4 pb-5">
        <div class="container-fluid">

            <div class="row justify-content-center mb-3">
                <div class="col-md-10 col-12 d-flex justify-content-between align-items-center">
                    <a href="<?= site_url('klinik/uji_rekam_medis/detail/' . $pasien->nik) ?>"
                        class="btn btn-secondary text-white px-4 rounded-1 shadow-sm" style="font-weight: 500;">
                        <i class="fas fa-arrow-left me-1"></i> Kembali ke Riwayat
                    </a>
                    <div>
                        <!-- <a href="<?= site_url('klinik/uji_rekam_medis/print_soap/' . $kunjungan->id) ?>" target="_blank"
                            class="btn btn-primary text-white px-4 rounded-1 shadow-sm me-2" style="font-weight: 500;">
                            <i class="fas fa-print me-1"></i> Cetak
                        </a> -->
                        <!-- If there's a specific PDF method, assuming print_soap_pdf -->
                        <a href="<?= site_url('klinik/uji_rekam_medis/print_soap/' . $kunjungan->id) ?>" target="_blank"
                            class="btn btn-danger text-white px-4 rounded-1 shadow-sm" style="font-weight: 500;">
                            <i class="fas fa-file-pdf me-1"></i> PDF
                        </a>
                        <a href="<?= site_url('klinik/uji_rekam_medis/soap/' . $kunjungan->id) ?>"
                            class="btn btn-sm align-items-center justify-content-center shadow-sm"
                            style="background-color: #28a745; border: 4px solid #28a745; color: #ffffff; font-size:12px; font-weight:500; display:inline-flex;">
                            <i class="fas fa-edit me-1 text-white"></i> Edit
                        </a>
                    </div>
                </div>
            </div>

            <div class="row justify-content-center">
                <div class="col-md-10 col-12">

                    <div class="d-flex align-items-center mb-4 mt-2">
                        <i class="fas fa-file-medical-alt fa-2x text-primary me-3"></i>
                        <h4 class="mb-0 fw-bold text-dark"> - Rincian Catatan Klinis SOAP - </h4>
                    </div>

                    <!-- Identitas Kunjungan (Header) -->
                    <div class="card shadow-sm border-0 rounded-3 mb-4">
                        <div class="card-body p-4 border-bottom border-4 border-info">
                            <div class="row">
                                <div class="col-lg-8 border-end">
                                    <p class="text-muted mb-2"
                                        style="font-size: 13px; font-weight:600; text-transform:uppercase; letter-spacing:1px;">
                                        Identitas Pasien:</p>
                                    <div class="row" style="font-size: 14px;">
                                        <div class="col-md-6 mb-2 mb-md-0">
                                            <table class="table table-borderless table-sm mb-0">
                                                <tr>
                                                    <td width="35%" class="text-muted p-0 pb-1">Nama Pasien</td>
                                                    <td class="fw-bold text-dark p-0 pb-1">:
                                                        <?= htmlspecialchars($pasien->nama_pasien) ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="text-muted p-0 pb-1">No. RM</td>
                                                    <td class="fw-bold text-dark p-0 pb-1">:
                                                        <?= htmlspecialchars($pasien->no_rm) ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="text-muted p-0 pb-1">NIK</td>
                                                    <td class="text-dark p-0 pb-1">:
                                                        <?= htmlspecialchars($pasien->nik) ?>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                        <div class="col-md-6">
                                            <table class="table table-borderless table-sm mb-0">
                                                <tr>
                                                    <td width="35%" class="text-muted p-0 pb-1">Jenis Kelamin</td>
                                                    <td class="text-dark p-0 pb-1">: <?= $pasien->gender ?></td>
                                                </tr>
                                                <tr>
                                                    <td class="text-muted p-0 pb-1">Tgl Lahir / Umur</td>
                                                    <td class="text-dark p-0 pb-1">:
                                                        <?= date('d M Y', strtotime($pasien->tgl_lahir)) ?> /
                                                        <?= $pasien->umur ?> Thn
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="text-muted p-0 pb-1">Alamat Lengkap</td>
                                                    <td class="text-dark p-0 pb-1">:
                                                        <?= htmlspecialchars($pasien->alamat) ?>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4 ps-lg-4 pt-3 pt-lg-0 d-flex flex-column justify-content-center">
                                    <p class="text-muted mb-1"
                                        style="font-size: 13px; font-weight:600; text-transform:uppercase; letter-spacing:1px;">
                                        Kunjungan Klinis:</p>
                                    <div style="font-size: 14px;">
                                        <div class="mb-1">
                                            <span class="text-muted">Tanggal:</span><br>
                                            <span
                                                class="fw-bold text-dark"><?= date('d M Y, H:i', strtotime($kunjungan->tanggal_kunjungan)) ?>
                                                WIB</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Kotakan Detail SOAP -->

                    <!-- S: Subjective -->
                    <div class="card shadow-sm border-0 rounded-3 mb-4">
                        <div class="card-header text-white"
                            style="background-color: #0d6efd; border-radius: 6px 6px 0 0;">
                            <h6 class="mb-0 fw-bold"><i class="fas fa-comment-medical me-2"></i> S - Subjective
                                (Anamnesis)</h6>
                        </div>
                        <div class="card-body bg-white p-4 pt-2">
                            <div class="row">
                                <div class="col-12 mb-3">
                                    <span class="text-muted d-block" style="font-size: 13px;">Keluhan Utama:</span>
                                    <p class="mb-0 text-dark fw-bold" style="white-space: pre-line;">
                                        <?= !empty($kunjungan->keluhan_utama) ? htmlspecialchars($kunjungan->keluhan_utama) : '-' ?>
                                    </p>
                                </div>
                                <div class="col-12">
                                    <span class="text-muted d-block" style="font-size: 13px;">Riwayat Penyakit
                                        Sekarang:</span>
                                    <p class="mb-0 text-dark" style="white-space: pre-line;">
                                        <?= !empty($kunjungan->riwayat_penyakit) ? htmlspecialchars($kunjungan->riwayat_penyakit) : '-' ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- O: Objective -->
                    <div class="card shadow-sm border-0 rounded-3 mb-4">
                        <div class="card-header text-white"
                            style="background-color: #0dcaf0; border-radius: 6px 6px 0 0;">
                            <h6 class="mb-0 fw-bold"><i class="fas fa-stethoscope me-2"></i> O - Objective (Pemeriksaan
                                Fisik)</h6>
                        </div>
                        <div class="card-body bg-white p-4 pt-2">
                            <div class="row rounded bg-light p-3 mb-4 mx-0">
                                <div class="col-md-3 col-6 mb-3">
                                    <span class="text-muted d-block" style="font-size: 13px;">GCS</span>
                                    <strong
                                        class="text-dark"><?= !empty($kunjungan->gcs) ? htmlspecialchars($kunjungan->gcs) : '-' ?></strong>
                                </div>
                                <div class="col-md-3 col-6 mb-3">
                                    <span class="text-muted d-block" style="font-size: 13px;">Tekanan Sistole /
                                        Diastole</span>
                                    <strong
                                        class="text-dark"><?= !empty($kunjungan->tensi_sistole) ? htmlspecialchars($kunjungan->tensi_sistole . ' / ' . $kunjungan->tensi_diastole) : '-' ?>
                                        <small class="text-muted fw-normal">mmHg</small></strong>
                                </div>
                                <div class="col-md-3 col-6 mb-3">
                                    <span class="text-muted d-block" style="font-size: 13px;">Nadi</span>
                                    <strong
                                        class="text-dark"><?= !empty($kunjungan->nadi) ? htmlspecialchars($kunjungan->nadi) : '-' ?>
                                        <small class="text-muted fw-normal">x/mnt</small></strong>
                                </div>
                                <div class="col-md-3 col-6 mb-3">
                                    <span class="text-muted d-block" style="font-size: 13px;">Suhu</span>
                                    <strong
                                        class="text-dark"><?= !empty($kunjungan->suhu) ? htmlspecialchars($kunjungan->suhu) : '-' ?>
                                        <small class="text-muted fw-normal">°C</small></strong>
                                </div>
                                <div class="col-md-3 col-6 mb-3 mb-md-0">
                                    <span class="text-muted d-block" style="font-size: 13px;">Pernapasan</span>
                                    <strong
                                        class="text-dark"><?= !empty($kunjungan->pernapasan) ? htmlspecialchars($kunjungan->pernapasan) : '-' ?>
                                        <small class="text-muted fw-normal">x/mnt</small></strong>
                                </div>
                                <div class="col-md-3 col-6 mb-3 mb-md-0">
                                    <span class="text-muted d-block" style="font-size: 13px;">Berat Badan</span>
                                    <strong
                                        class="text-dark"><?= !empty($kunjungan->berat_badan) ? htmlspecialchars($kunjungan->berat_badan) : '-' ?>
                                        <small class="text-muted fw-normal">kg</small></strong>
                                </div>
                                <div class="col-md-3 col-6">
                                    <span class="text-muted d-block" style="font-size: 13px;">Tinggi Badan</span>
                                    <strong
                                        class="text-dark"><?= !empty($kunjungan->tinggi_badan) ? htmlspecialchars($kunjungan->tinggi_badan) : '-' ?>
                                        <small class="text-muted fw-normal">cm</small></strong>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <span class="text-muted d-block" style="font-size: 13px;">Pemeriksaan
                                        Jantung:</span>
                                    <p class="mb-0 text-dark" style="white-space: pre-line;">
                                        <?= !empty($kunjungan->pemeriksaan_jantung) ? htmlspecialchars($kunjungan->pemeriksaan_jantung) : '-' ?>
                                    </p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <span class="text-muted d-block" style="font-size: 13px;">Pemeriksaan
                                        Paru-paru:</span>
                                    <p class="mb-0 text-dark" style="white-space: pre-line;">
                                        <?= !empty($kunjungan->pemeriksaan_paru) ? htmlspecialchars($kunjungan->pemeriksaan_paru) : '-' ?>
                                    </p>
                                </div>
                                <div class="col-md-6 mb-3 mb-md-0">
                                    <span class="text-muted d-block" style="font-size: 13px;">Pemeriksaan
                                        Abdomen:</span>
                                    <p class="mb-0 text-dark" style="white-space: pre-line;">
                                        <?= !empty($kunjungan->pemeriksaan_abdomen) ? htmlspecialchars($kunjungan->pemeriksaan_abdomen) : '-' ?>
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    <span class="text-muted d-block" style="font-size: 13px;">Catatan Fisik
                                        Tambahan:</span>
                                    <p class="mb-0 text-dark" style="white-space: pre-line;">
                                        <?= !empty($kunjungan->catatan_tambahan) ? htmlspecialchars($kunjungan->catatan_tambahan) : '-' ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- A & P Row -->
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <div class="card shadow-sm border-0 rounded-3 h-100">
                                <div class="card-header text-dark"
                                    style="background-color: #ffc107; border-radius: 6px 6px 0 0;">
                                    <h6 class="mb-0 fw-bold"><i class="fas fa-diagnoses me-2"></i> A - Assessment</h6>
                                </div>
                                <div class="card-body bg-white p-4 pt-2">
                                    <p class="mb-0 text-dark fw-bold" style="white-space: pre-line;">
                                        <?= !empty($kunjungan->asesmen_diagnosa) ? htmlspecialchars($kunjungan->asesmen_diagnosa) : '-' ?>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 mb-4">
                            <div class="card shadow-sm border-0 rounded-3 h-100">
                                <div class="card-header text-white"
                                    style="background-color: #198754; border-radius: 6px 6px 0 0;">
                                    <h6 class="mb-0 fw-bold"><i class="fas fa-clipboard-list me-2"></i> P - Plan</h6>
                                </div>
                                <div class="card-body bg-white p-4 pt-2">
                                    <p class="mb-0 text-dark" style="white-space: pre-line;">
                                        <?= !empty($kunjungan->plan_rencana) ? htmlspecialchars($kunjungan->plan_rencana) : '-' ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ICD 10 Card -->
                    <div class="card shadow-sm mb-5 border-0 rounded-3">
                        <div class="card-header text-white"
                            style="background-color: #dc3545; border-radius: 6px 6px 0 0;">
                            <h6 class="mb-0 fw-bold"><i class="fas fa-book-medical me-2"></i> Diagnosis Administratif
                                (ICD-10) Terpilih</h6>
                        </div>
                        <div class="card-body bg-white p-4">
                            <div class="p-3 rounded bg-light border">
                                <?php if ($kunjungan->icd_10 == '<i>Kosong</i>' || empty($kunjungan->icd_10)): ?>
                                    <span class="text-muted italic">Belum ada diagnosis terpilih</span>
                                <?php else: ?>
                                    <ul class="mb-0 text-dark fw-bold ps-3">
                                        <?php
                                        $icds = explode('<br>', $kunjungan->icd_10);
                                        foreach ($icds as $icd) {
                                            if (!empty(trim($icd))) {
                                                echo '<li>' . htmlspecialchars(trim($icd)) . '</li>';
                                            }
                                        }
                                        ?>
                                    </ul>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Dokter Pemeriksa Card -->
                    <div class="card shadow-sm mb-5 border-0 rounded-3">
                        <div class="card-header text-white"
                            style="background-color: #6f42c1; border-radius: 6px 6px 0 0;">
                            <h6 class="mb-0 fw-bold"><i class="fas fa-user-md me-2"></i> Dokter Pemeriksa</h6>
                        </div>
                        <div class="card-body bg-white p-4">
                            <div class="p-3 rounded bg-light border">
                                <span class="fw-bold text-dark" style="font-size: 15px;">
                                    <?= !empty($kunjungan->nama_dokter_pemeriksa) ? htmlspecialchars($kunjungan->nama_dokter_pemeriksa) : '<span class="text-muted italic">Dokter belum dipilih</span>' ?>
                                </span>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </section>
</div>
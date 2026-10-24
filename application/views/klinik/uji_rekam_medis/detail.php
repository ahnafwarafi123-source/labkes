<div class="content-wrapper">
    <section class="content pt-4">
        <div class="container-fluid">

            <div class="d-flex justify-content-start">
                <div class="col-md-8 col-12 d-flex justify-content-start">
                    <a href="<?= site_url('klinik/uji_rekam_medis') ?>"
                        class="btn btn-secondary text-white px-4 rounded-1 shadow-sm" style="font-weight: 500;">
                        <i class="fas fa-arrow-left me-1"></i> Back
                    </a>
                </div>
            </div>

            <!-- Card Identitas Pasien -->
            <div class="card shadow-sm border-0 mb-4 rounded-3 d-flex flex-column h-100">
                <div class="card-header bg-light border-0 py-3">
                    <h5 class="mb-0 text-secondary" style="font-weight: 500;">Identitas Pasien</h5>
                </div>
                <div class="card-body bg-white rounded-bottom-3" style="font-size: 14px;">
                    <div class="row">
                        <div class="col-12 col-md-6 mb-3 mb-md-0 border-end">
                            <table class="table table-borderless table-sm mb-0">
                                <tr>
                                    <td width="35%" class="fw-bold text-dark">Nama</td>
                                    <td class="text-secondary">: <?= htmlspecialchars($pasien->nama_pasien) ?></td>
                                </tr>
                                <tr>
                                    <td class="fw-bold text-dark">NIK</td>
                                    <td class="text-secondary">: <?= htmlspecialchars($pasien->nik) ?></td>
                                </tr>
                                <tr>
                                    <td class="fw-bold text-dark">Jenis Kelamin</td>
                                    <td class="text-secondary">: <?= htmlspecialchars($pasien->gender) ?></td>
                                </tr>
                                <tr>
                                    <td class="fw-bold text-dark">Agama</td>
                                    <td class="text-secondary">:
                                        <?= isset($pasien->agama) && $pasien->agama != '' ? htmlspecialchars($pasien->agama) : '-' ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fw-bold text-dark">Status Pernikahan</td>
                                    <td class="text-secondary">:
                                        <?= isset($pasien->status_nikah) && $pasien->status_nikah != '' ? htmlspecialchars($pasien->status_nikah) : '-' ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fw-bold text-dark">Pendidikan</td>
                                    <td class="text-secondary">:
                                        <?= isset($pasien->pendidikan) && $pasien->pendidikan != '' ? htmlspecialchars($pasien->pendidikan) : '-' ?>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-12 col-md-6 ps-md-4">
                            <table class="table table-borderless table-sm mb-0">
                                <tr>
                                    <td width="40%" class="fw-bold text-dark">No. RM</td>
                                    <td class="text-secondary">: <?= htmlspecialchars($pasien->no_rm) ?></td>
                                </tr>
                                <tr>
                                    <td class="fw-bold text-dark">Tanggal Lahir / Umur</td>
                                    <td class="text-secondary">
                                        : <?= date('d M Y', strtotime($pasien->tgl_lahir)) ?> / <?= $pasien->umur ?> thn
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fw-bold text-dark">No. Telepon</td>
                                    <td class="text-secondary">:
                                        <?= isset($pasien->no_telp) && $pasien->no_telp != '' ? htmlspecialchars($pasien->no_telp) : '-' ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fw-bold text-dark">Pekerjaan</td>
                                    <td class="text-secondary">:
                                        <?= isset($pasien->pekerjaan) && $pasien->pekerjaan != '' ? htmlspecialchars($pasien->pekerjaan) : '-' ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fw-bold text-dark">Alamat Lengkap</td>
                                    <td class="text-secondary">:
                                        <?= isset($pasien->alamat) && $pasien->alamat != '' ? htmlspecialchars($pasien->alamat) : '-' ?>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card Riwayat Kunjungan -->
            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-header bg-light border-0 py-3 d-flex align-items-center justify-content-between w-100">
                    <h5 class="mb-0 text-secondary" style="font-weight: 500;">Riwayat Kunjungan</h5>
                    <div>
                        <a href="<?= site_url('klinik/uji_rekam_medis/tambah_kunjungan/' . $pasien->id_pasien) ?>"
                            class="btn btn-primary btn-sm rounded-1 px-3">
                            <i class="fas fa-plus me-1"></i> Tambah Kunjungan
                        </a>
                    </div>
                </div>
                <div class="card-body bg-white rounded-bottom-3 p-3 p-md-4">

                    <div class="table-responsive">
                        <table id="tabel-detail-kunjungan" class="table table-bordered table-hover w-100"
                            style="font-size: 14px;">
                            <thead style="background-color: #f8f9fa;">
                                <tr>
                                    <th width="5%" class="text-center align-middle py-3">No</th>
                                    <th width="15%" class="align-middle py-3">Tanggal<br>Kunjungan</th>
                                    <th width="15%" class="align-middle py-3">Dokter</th>
                                    <th width="15%" class="align-middle py-3">Status SOAP</th>
                                    <th width="30%" class="align-middle py-3">ICD-10</th>
                                    <th width="20%" class="align-middle py-3 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($kunjungan)): ?>
                                    <?php $no = 1;
                                    foreach ($kunjungan as $k): ?>
                                        <tr>
                                            <td class="text-center align-middle"><?= $no++ ?></td>
                                            <td class="align-middle text-secondary">
                                                <?php
                                                $indo_months = [1 => 'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
                                                $t = strtotime($k->tanggal_kunjungan);
                                                echo date('d', $t) . ' ' . $indo_months[(int) date('m', $t)] . ' ' . date('Y', $t) . ',<br>' . date('H:i', $t);
                                                ?>
                                            </td>
                                            <td class="align-middle text-secondary">
                                                <?= !empty($k->nama_dokter_pemeriksa) ? htmlspecialchars($k->nama_dokter_pemeriksa) : '<span class="text-muted"><em>Belum dipilih</em></span>' ?>
                                            </td>
                                            <td class="align-middle">
                                                <?php if ($k->status_soap == 'Belum diisi'): ?>
                                                    <span class="badge"
                                                        style="background-color: #f5b041; padding: 6px 12px; font-weight: 500;">SOAP Belum
                                                        diisi</span>
                                                <?php else: ?>
                                                    <span class="badge"
                                                        style="background-color: #28b463; padding: 6px 12px; font-weight: 500;">SOAP Sudah
                                                        diisi</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="align-middle text-secondary"
                                                style="font-style: <?= $k->icd_10 == '<i>Kosong</i>' ? 'italic' : 'normal' ?>;">
                                                <?= $k->icd_10 ?>
                                            </td>
                                            <td class="align-middle text-center">
                                                <div class="d-flex flex-column gap-1 align-items-center justify-content-center">
                                                    <div class="d-flex gap-1 w-100">
                                                        <?php if ($k->status_soap == 'Belum diisi'): ?>
                                                            <a href="<?= site_url('klinik/uji_rekam_medis/soap/' . $k->id) ?>" class="btn btn-sm btn-primary w-100 shadow-sm" style="font-size:12px; font-weight:500;">
                                                                <i class="fas fa-edit me-1"></i> Isi SOAP
                                                            </a>
                                                        <?php else: ?>
                                                            <a href="<?= site_url('klinik/uji_rekam_medis/lihat_soap/' . $k->id) ?>" class="btn btn-sm btn-primary w-50 shadow-sm" style="font-size:12px; font-weight:500;">
                                                                <i class="fas fa-file-medical-alt me-1"></i> Lihat
                                                            </a>
                                                            <a href="<?= site_url('klinik/uji_rekam_medis/soap/' . $k->id) ?>" class="btn btn-sm btn-success w-50 shadow-sm" style="font-size:12px; font-weight:500;">
                                                                <i class="fas fa-edit me-1"></i> Edit
                                                            </a>
                                                        <?php endif; ?>
                                                    </div>
                                                    
                                                    <div class="d-flex gap-1 w-100">
                                                        <?php if ($k->status_soap == 'Belum diisi'): ?>
                                                            <button class="btn btn-sm btn-secondary w-50 disabled" style="font-size:12px; font-weight:500;">
                                                                <i class="fas fa-print me-1"></i> Print
                                                            </button>
                                                        <?php else: ?>
                                                            <a href="<?= site_url('klinik/uji_rekam_medis/print_soap/' . $k->id) ?>" target="_blank" class="btn btn-sm btn-secondary w-50 shadow-sm" style="font-size:12px; font-weight:500;">
                                                                <i class="fas fa-print me-1"></i> Print
                                                            </a>
                                                        <?php endif; ?>
                                                        
                                                        <a href="<?= site_url('klinik/uji_rekam_medis/hapus_kunjungan/' . $k->id . '/' . $pasien->id_pasien) ?>" class="btn btn-sm btn-danger w-50 shadow-sm" style="font-size:12px; font-weight:500;" onclick="return confirm('Hapus data kunjungan ini?')">
                                                            <i class="fas fa-trash me-1"></i> Hapus
                                                        </a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>

                                <?php endif; ?>

                            </tbody>
                        </table>
                    </div>

                </div>
            </div>

        </div>
    </section>

    <script>
        $(document).ready(function () {
            $('#tabel-detail-kunjungan').DataTable({
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json",
                    "emptyTable": "Belum ada riwayat kunjungan."
                },
                "lengthMenu": [[10, 25, 50, -1], ["10 Baris", "25 Baris", "50 Baris", "Semua"]],
                "searching": false, // Disable search box to match image if desired, or keep it, up to preference
                "info": false // Hide "Showing 1 to X of Y entries"
            });
        });
    </script>
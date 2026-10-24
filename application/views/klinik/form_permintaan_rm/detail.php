<div class="content-wrapper px-4 pt-4">
    <h3 class="text-center fw-bold mb-4">Detail Identitas Pasien</h3>

    <div class="row justify-content-center mb-4">
        <!-- Card Detail Pasien -->
        <div class="col-md-8">
            <div class="card border border-dark shadow-lg w-100 bg-white">
                <div class="card-header bg-secondary text-white fw-bold mb-0" style="font-weight: 800">
                    Informasi Identitas Pasien
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tbody>
                            <tr>
                                <th style="width: 30%">No. Rekam Medis (RM)</th>
                                <td>: <?= isset($pasien->no_rm) ? $pasien->no_rm : '-' ?></td>
                            </tr>
                            <tr>
                                <th>NIK</th>
                                <td>: <?= isset($pasien->nik) ? $pasien->nik : '-' ?></td>
                            </tr>
                            <tr>
                                <th>Nama Lengkap</th>
                                <td>: <?= isset($pasien->nama_pasien) ? $pasien->nama_pasien : '-' ?></td>
                            </tr>
                            <tr>
                                <th>Tanggal Lahir</th>
                                <td>:
                                    <?= isset($pasien->tgl_lahir) ? date('d-m-Y', strtotime($pasien->tgl_lahir)) : '-' ?>
                                </td>
                            </tr>
                            <tr>
                                <th>Umur</th>
                                <td>: <?= isset($pasien->umur) ? $pasien->umur . ' Tahun' : '-' ?></td>
                            </tr>
                            <tr>
                                <th>Jenis Kelamin</th>
                                <td>: <?= isset($pasien->gender) ? $pasien->gender : '-' ?></td>
                            </tr>

                            <tr>
                                <th>Agama</th>
                                <td>: <?= isset($pasien->agama) && $pasien->agama != '' ? $pasien->agama : '-' ?></td>
                            </tr>
                            <tr>
                                <th>Status Pernikahan</th>
                                <td>:
                                    <?= isset($pasien->status_nikah) && $pasien->status_nikah != '' ? $pasien->status_nikah : '-' ?>
                                </td>
                            </tr>
                            <tr>
                                <th>Pendidikan</th>
                                <td>:
                                    <?= isset($pasien->pendidikan) && $pasien->pendidikan != '' ? $pasien->pendidikan : '-' ?>
                                </td>
                            </tr>
                            <tr>
                                <th>Pekerjaan</th>
                                <td>:
                                    <?= isset($pasien->pekerjaan) && $pasien->pekerjaan != '' ? $pasien->pekerjaan : '-' ?>
                                </td>
                            </tr>
                            <tr>
                                <th>Petugas Pendaftaran</th>
                                <td>:
                                    <?= isset($pasien->petugas_pendaftaran) && $pasien->petugas_pendaftaran != '' ? $pasien->petugas_pendaftaran : '-' ?>
                                </td>
                            </tr>
                            <tr>
                                <th>No. Telepon / HP</th>
                                <td>: <?= isset($pasien->no_telp) && $pasien->no_telp != '' ? $pasien->no_telp : '-' ?>
                                </td>
                            </tr>
                            <tr>
                                <th>Alamat Lengkap</th>
                                <td>: <?= isset($pasien->alamat) && $pasien->alamat != '' ? $pasien->alamat : '-' ?>
                                </td>
                            </tr>
                            <tr>
                                <th>Puskesmas Wilayah</th>
                                <td>: <span class="badge bg-info text-dark"><?= !empty($pasien->puskesmas_wilayah) ? $pasien->puskesmas_wilayah : 'Belum Dipetakan' ?></span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-4 text-center">
                <a href="<?= site_url('klinik/form_permintaan_rm') ?>" class="btn btn-secondary px-5 py-2">
                    <i class="fas fa-arrow-left me-2"></i> Kembali ke Data Pasien
                </a>
                <a href="<?= site_url('klinik/form_permintaan_rm/edit/' . (isset($pasien->id_pasien) ? $pasien->id_pasien : '')) ?>"
                    class="btn btn-success px-5 py-2 ms-2">
                    <i class="fas fa-edit me-2"></i> Edit Data
                </a>
            </div>
        </div>
    </div>
</div>
<div class="content-wrapper px-4 pt-4">

    <h4 class="fw-bold text-center mb-4 text-uppercase">
        Laporan Hasil Uji Laboratorium
    </h4>

    <!-- Tombol Kembali -->
    <a href="<?= site_url('klinik/laporan_uji_klinik/index') ?>" class="btn btn-secondary px-4 mb-3">
        <i class="fas fa-arrow-left"></i> Kembali ke Daftar Pasien
    </a>

    <!-- ================= IDENTITAS PASIEN ================= -->
    <div class="card shadow mb-4 border border-dark">

        <div class="card-header bg-secondary text-white fw-bold text-center fs-5 py-2">
            IDENTITAS PASIEN & SPESIMEN
        </div>

        <div class="card-body bg-white">

            <?php
            $pengambilan = $this->db->select('pk.*, ps.nama_petugas')
                ->from('form_pengambilan_klinik pk')
                ->join('petugas_sampel_klinik ps', 'pk.id_petugas = ps.id_petugas', 'left')
                ->where('pk.id_form', $id_form)
                ->get()
                ->row();
            ?>

            <table class="table table-borderless table-sm mb-0" style="font-size:14px;">

                <tr>
                    <td width="20%" class="fw-semibold text-secondary">No. Registrasi</td>
                    <td width="2%">:</td>
                    <td width="30%"><?= $form->no_register ?? '-' ?></td>

                    <td width="18%" class="fw-semibold text-secondary">Kondisi Spesimen</td>
                    <td width="2%">:</td>
                    <td><?= $form->kelayakan ?? '-' ?></td>
                </tr>

                <tr>
                    <td class="fw-semibold text-secondary">Nama</td>
                    <td>:</td>
                    <td><?= $form->nama_pasien ?? '-' ?></td>

                    <td class="fw-semibold text-secondary">Petugas Pengambil Spesimen</td>
                    <td>:</td>
                    <td><?= $pengambilan->nama_petugas ?? $pengambilan->petugas_pengambilan ?? '-' ?></td>
                </tr>

                <tr>
                    <td class="fw-semibold text-secondary">Jenis Kelamin</td>
                    <td>:</td>
                    <td><?= $form->gender ?? '-' ?></td>

                    <td class="fw-semibold text-secondary">Tgl/Jam Pengambilan</td>
                    <td>:</td>
                    <td>
                        <?php
                        if (!empty($form->tgl_pengambilan)) {
                            $tgl = date('d-m-Y', strtotime($form->tgl_pengambilan));
                            $jam = !empty($form->jam_pengambilan) ? date('H.i', strtotime($form->jam_pengambilan)) : '00.00';
                            echo $tgl . ' / ' . $jam . ' WIB';
                        } else {
                            echo '-';
                        }
                        ?>
                    </td>
                </tr>

                <tr>
                    <td class="fw-semibold text-secondary">Tanggal Lahir / Umur</td>
                    <td>:</td>
                    <td>
                        <?= ($form->tgl_lahir ? date('d-m-Y', strtotime($form->tgl_lahir)) : '-') ?>
                        /
                        <?= $form->umur ?? '-' ?>
                    </td>

                    <td class="fw-semibold text-secondary">Tgl/Jam Pemeriksaan</td>
                    <td>:</td>
                    <td>
                        <?= $pengambilan ? date('d-m-Y / H.i', strtotime($pengambilan->tgl_jam_pemeriksaan)) . ' WIB' : '-' ?>
                    </td>
                </tr>

                <tr>
                    <td class="fw-semibold text-secondary">Dokter Pengirim</td>
                    <td>:</td>
                    <td><?= $form->nama_dokter ?? '-' ?></td>

                    <td class="fw-semibold text-secondary">Tgl/Jam Selesai</td>
                    <td>:</td>
                    <td>
                        <?= $pengambilan ? date('d-m-Y / H.i', strtotime($pengambilan->tgl_jam_selesai)) . ' WIB' : '-' ?>
                    </td>
                </tr>

                <tr>
                    <td class="fw-semibold text-secondary">Tanggal Pengiriman</td>
                    <td>:</td>
                    <td>
                        <?= $form->tgl_form ? date('d-m-Y', strtotime($form->tgl_form)) : '-' ?>
                    </td>

                    <td class="fw-semibold text-secondary">Tgl/Jam Pelaporan</td>
                    <td>:</td>
                    <td>
                        <?= $pengambilan ? date('d-m-Y / H.i', strtotime($pengambilan->tgl_jam_pelaporan)) . ' WIB' : '-' ?>
                    </td>
                </tr>

            </table>

        </div>
    </div>

    <!-- ================= HASIL PEMERIKSAAN ================= -->
    <div class="card shadow border border-dark mb-4">

        <div class="card-header bg-secondary text-white fw-bold text-center fs-5 py-2">
            HASIL PEMERIKSAAN
        </div>

        <div class="card-body bg-white">

            <table class="table table-bordered table-striped table-sm align-middle">

                <thead class="table-secondary text-center">
                    <tr>
                        <th width="5%">No</th>
                        <th width="30%">Jenis Pemeriksaan</th>
                        <th width="15%">Hasil</th>
                        <th width="20%">Nilai Rujukan</th>
                        <th width="10%">Satuan</th>
                        <th width="20%">Metode</th>
                    </tr>
                </thead>

                <tbody>

                    <?php if (!empty($detail)): ?>

                        <?php
                        $no = 1;
                        $kategori = '';
                        $sub = '';
                        ?>

                        <?php foreach ($detail as $d): ?>

                            <!-- HEADER KATEGORI -->
                            <?php if ($kategori != $d->kategori): ?>

                                <?php
                                $kategori = $d->kategori;
                                $sub = '';
                                $no = 1;
                                ?>

                                <tr class="table-primary">
                                    <td colspan="6" class="fw-bold text-uppercase">
                                        <?= strtoupper($kategori) ?>
                                    </td>
                                </tr>

                            <?php endif; ?>

                            <!-- HEADER SUB KATEGORI -->
                            <?php if ($sub != $d->sub_kategori): ?>

                                <?php $sub = $d->sub_kategori; ?>

                                <tr class="table-info">
                                    <td colspan="6" class="fw-bold text-uppercase ps-4">
                                        <?= strtoupper($sub) ?>
                                    </td>
                                </tr>

                            <?php endif; ?>

                            <?php

                            $is_abnormal = false;

                            /*
|--------------------------------------------------------------------------
| HASIL PEMERIKSAAN
|--------------------------------------------------------------------------
*/
                            $hasil = trim($d->hasil);

                            /*
|--------------------------------------------------------------------------
| AMBIL ANGKA HASIL
|--------------------------------------------------------------------------
*/
                            $hasil_angka = floatval(
                                preg_replace('/[^0-9.]/', '', $hasil)
                            );

                            /*
|--------------------------------------------------------------------------
| NILAI RUJUKAN
|--------------------------------------------------------------------------
| contoh:
| 11.3-15.5
| 4.5 - 13
|--------------------------------------------------------------------------
*/

                            $rujukan = trim($d->nilai_rujukan);

                            if (preg_match('/([0-9.]+)\s*-\s*([0-9.]+)/', $rujukan, $match)) {

                                $min = floatval($match[1]);
                                $max = floatval($match[2]);

                                /*
    |--------------------------------------------------------------------------
    | CEK ABNORMAL
    |--------------------------------------------------------------------------
    */
                                if ($hasil_angka < $min || $hasil_angka > $max) {
                                    $is_abnormal = true;
                                }
                            }

                            ?>

                            <?php

                            $is_abnormal = false;

                            $hasil = trim(strtolower($d->hasil));
                            $rujukan = trim(strtolower($d->nilai_rujukan));
                            $gender = trim(strtolower($form->gender));

                            /*
|--------------------------------------------------------------------------
| NORMALISASI
|--------------------------------------------------------------------------
*/

                            // ubah koma desimal
                            $hasil = str_replace(',', '.', $hasil);

                            // ubah dash aneh jadi -
                            $rujukan = str_replace(['–', '—'], '-', $rujukan);

                            // lowercase
                            $rujukan = strtolower($rujukan);

                            /*
|--------------------------------------------------------------------------
| AMBIL RUJUKAN BERDASARKAN GENDER
|--------------------------------------------------------------------------
*/

                            $rujukan_gender = $rujukan;

                            // PEREMPUAN
                            if (
                                ($gender == 'perempuan' || $gender == 'wanita' || $gender == 'p')
                                &&
                                preg_match('/perempuan\s*:\s*([^,]+)/', $rujukan, $match)
                            ) {
                                $rujukan_gender = trim($match[1]);
                            }

                            // LAKI-LAKI
                            elseif (
                                ($gender == 'laki-laki' || $gender == 'l')
                                &&
                                preg_match('/laki-laki\s*:\s*([^,]+)/', $rujukan, $match)
                            ) {
                                $rujukan_gender = trim($match[1]);
                            }

                            /*
|--------------------------------------------------------------------------
| CEK ABNORMAL
|--------------------------------------------------------------------------
*/

                            $hasil_angka = (float) preg_replace('/[^0-9\.]/', '', $hasil);

                            // --- CUSTOM LOGIC UNTUK eGFR/eLFG, HbA1c, Tubex TF ---
                            if (stripos($d->nama_jenis, 'egfr') !== false || stripos($d->nama_jenis, 'elfg') !== false) {
                              if ($hasil_angka < 90) {
                                $is_abnormal = true;
                              } else {
                                $is_abnormal = false;
                              }
                            } elseif (stripos($d->nama_jenis, 'hba1c') !== false) {
                              if ($hasil_angka >= 5.7) {
                                $is_abnormal = true;
                              } else {
                                $is_abnormal = false;
                              }
                            } elseif (stripos($d->nama_jenis, 'tubex') !== false) {
                              if ($hasil_angka < 0 || $hasil_angka > 2) {
                                $is_abnormal = true;
                              } else {
                                $is_abnormal = false;
                              }
                            } else {
                                // --- LOGIKA NORMAL ---
                                if (preg_match('/([0-9\.]+)\s*-\s*([0-9\.]+)/', $rujukan_gender, $match)) {

                                    $min = (float)$match[1];
                                    $max = (float)$match[2];

                                    if ($hasil_angka < $min || $hasil_angka > $max) {
                                        $is_abnormal = true;
                                    }
                                }

                            /*
|--------------------------------------------------------------------------
| FORMAT <
|--------------------------------------------------------------------------
*/ elseif (preg_match('/<\s*([0-9\.]+)/', $rujukan_gender, $match)) {

                                $max = (float)$match[1];

                                if ($hasil_angka >= $max) {
                                    $is_abnormal = true;
                                }
                            }

                            /*
|--------------------------------------------------------------------------
| FORMAT >
|--------------------------------------------------------------------------
*/ elseif (preg_match('/>\s*([0-9\.]+)/', $rujukan_gender, $match)) {

                                $min = (float)$match[1];

                                if ($hasil_angka <= $min) {
                                    $is_abnormal = true;
                                }
                            }

                            /*
|--------------------------------------------------------------------------
| NEGATIF / NORMAL / NON REAKTIF
|--------------------------------------------------------------------------
*/ elseif (
                                strpos($rujukan_gender, 'negatif') !== false ||
                                strpos($rujukan_gender, 'normal') !== false ||
                                strpos($rujukan_gender, 'non reaktif') !== false
                            ) {

                                if (
                                    $hasil != 'negatif' &&
                                    $hasil != 'normal' &&
                                    $hasil != 'non reaktif'
                                ) {
                                    $is_abnormal = true;
                                }
                            }

                            /*
|--------------------------------------------------------------------------
| TEXT BIASA
|--------------------------------------------------------------------------
*/ else {

                                if (!empty($rujukan_gender) && $hasil != $rujukan_gender) {
                                    $is_abnormal = true;
                                }
                            }
                        }

                        ?>

                            <tr>

                                <td class="text-center">
                                    <?= $no++ ?>
                                </td>

                                <td>
                                    <?= $d->nama_jenis ?>
                                </td>

                                <!-- HASIL -->
                                <td class="text-center">

                                    <?php if ($is_abnormal): ?>

                                        <span style="color:black; font-weight:bold; font-size:15px;">
                                            <?= $d->hasil ?> *
                                        </span>

                                    <?php else: ?>

                                        <?= $d->hasil ?>

                                    <?php endif; ?>

                                </td>

                                <!-- NILAI RUJUKAN -->
                                <td class="text-center">
                                    <?= $d->nilai_rujukan ?>
                                </td>

                                <!-- SATUAN -->
                                <td class="text-center">
                                    <?= $d->satuan ?? '-' ?>
                                </td>

                                <!-- METODE -->
                                <td class="text-center">
                                    <?= $d->metode ?? '-' ?>
                                </td>

                            </tr>
                        <?php endforeach; ?>

                    <?php else: ?>

                        <tr>
                            <td colspan="6" class="text-center text-muted">
                                Belum ada hasil pemeriksaan.
                            </td>
                        </tr>

                    <?php endif; ?>

                </tbody>

            </table>

            <div class="mt-2">
                <small class="text-danger fw-bold">
                    * Menunjukkan hasil di luar nilai rujukan
                </small>
            </div>

        </div>
    </div>

    <!-- ================= CATATAN ================= -->
    <div class="card shadow border border-dark mb-4">

        <div class="card-header bg-secondary text-white fw-bold text-center fs-5 py-2">
            CATATAN & PETUGAS LABORATORIUM
        </div>

        <div class="card-body bg-white fs-6">

            <table class="table table-borderless table-sm mb-0" style="font-size:14px;">

                <tr>
                    <td width="20%" class="fw-semibold text-secondary">Catatan / Note</td>
                    <td width="2%">:</td>
                    <td><?= $pengambilan->note ?? '-' ?></td>
                </tr>

                <tr>
                    <td class="fw-semibold text-secondary">Petugas Hasil</td>
                    <td>:</td>
                    <td><?= $pengambilan->petugas_hasil ?? '-' ?></td>
                </tr>

                <tr>
                    <td class="fw-semibold text-secondary">Verifikator</td>
                    <td>:</td>
                    <td><?= $pengambilan->verifikator_hasil ?? '-' ?></td>
                </tr>

                <tr>
                    <td class="fw-semibold text-secondary">Penanggung Jawab Teknis</td>
                    <td>:</td>
                    <td>
                        <?= $pengambilan->penanggung_jawab_teknis ?? '-' ?>
                        <br>
                        <small class="text-muted">
                            SIP: <?= $pengambilan->sip_penanggung ?? '-' ?>
                        </small>
                    </td>
                </tr>

            </table>

        </div>
    </div>

    <!-- BUTTON -->
    <div class="text-end mb-5">

        <a href="<?= site_url('klinik/laporan_uji_klinik') ?>" class="btn btn-secondary px-4">
            Kembali
        </a>

        <a href="<?= site_url('klinik/laporan_uji_klinik/export_pdf/' . $form->id) ?>"
            class="btn btn-danger px-4"
            target="_blank">

            Cetak PDF

        </a>

    </div>

</div>
```
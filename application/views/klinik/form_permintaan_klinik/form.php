<div class="content-wrapper px-4 pt-4">
  <h3 class="text-center fw-bold mb-4">FORMULIR PERMINTAAN PEMERIKSAAN LABORATORIUM KLINIK</h3>

  <form action="<?= isset($form) ? site_url('klinik/form_permintaan_klinik/update/' . $form->id) : site_url('klinik/form_permintaan_klinik/store') ?>" method="post">
    <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>" />

    <!-- Fitur Pencarian Pasien RM -->
    <div class="row mb-4">
      <div class="col-md-12">
        <div class="card border border-primary shadow-sm bg-white">
          <div class="card-header bg-primary text-white fw-bold mb-0" style="font-weight: 800;">
            <i class="fas fa-search"></i> Cari Identitas Pasien
          </div>
          <div class="card-body">
            <div class="input-group">
              <input type="text" id="keyword_rm" class="form-control" placeholder="Cari berdasarkan No RM atau NIK">
              <button type="button" class="btn btn-primary" id="btn_cari_rm"><i class="fas fa-search"></i> Cari Pasien</button>
            </div>
            <div id="hasil_pencarian_rm" class="mt-3" style="display:none;"></div>
          </div>
        </div>
      </div>
    </div>

    <!-- Baris atas dengan 3 card sejajar -->
    <div class="row mb-4">

      <!-- Card Identitas Pasien -->
      <div class="col-md-4 d-flex">
        <div class="card border border-dark shadow-lg w-100 h-100 bg-white">
          <div class="card-header bg-secondary text-white fw-bold mb-0" style="font-weight: 800">
            Identitas Pasien
          </div>
          <!-- Identitas Pasien -->
          <div class="card-body">
            <div>
              <label>No. Rm</label>
              <input type="text" name="no_rm" id="no_rm" class="form-control"
                value="<?= isset($form->no_rm) ? html_escape($form->no_rm) : '' ?>">
            </div>
            <div class="mb-2">
              <label>No. Register <span class="text-danger">*</span></label>
              <input type="text" name="no_register" class="form-control"
                value="<?= isset($form->no_register) ? $form->no_register : $no_register ?>"
                placeholder="Contoh: 001/LK/2025">
            </div>
            <div class="mb-2">
              <label>Nama Pasien</label>
              <input type="text" name="nama_pasien" class="form-control"
                value="<?= isset($form->nama_pasien) ? $form->nama_pasien : (isset($form->nama_) ? $form->nama : (isset($pasien_prefill->nama_pasien) ? $pasien_prefill->nama_pasien : '')) ?>">
            </div>
            <div class="mb-2">
              <label>NIK <span class="text-danger">*</span></label>
              <input type="text" name="nik" id="nik" class="form-control" required
                value="<?= isset($form->nik) ? $form->nik : (isset($pasien_prefill->nik) ? $pasien_prefill->nik : '') ?>">
            </div>
            <div class="mb-2">
              <label>Jenis Kelamin</label>
              <?php $gender_val = isset($form->gender) ? $form->gender : (isset($pasien_prefill->gender) ? $pasien_prefill->gender : ''); ?>
              <select name="gender" class="form-select">
                <option value="">-- Pilih --</option>
                <option value="Laki-laki" <?= $gender_val == 'Laki-laki' ? 'selected' : '' ?>>Laki-laki</option>
                <option value="Perempuan" <?= $gender_val == 'Perempuan' ? 'selected' : '' ?>>Perempuan</option>
              </select>
            </div>
            <div class="mb-2">
              <label>Status Pernikahan</label>
              <select name="status_nikah" class="form-select">
                <option value="">-- Pilih --</option>
                <option value="Belum Menikah" <?= isset($form->status_nikah) && $form->status_nikah == 'Belum Menikah' ? 'selected' : '' ?>>Belum Menikah</option>
                <option value="Menikah" <?= isset($form->status_nikah) && $form->status_nikah == 'Menikah' ? 'selected' : '' ?>>Menikah</option>
                <option value="Cerai Hidup" <?= isset($form->status_nikah) && $form->status_nikah == 'Cerai Hidup' ? 'selected' : '' ?>>Cerai Hidup</option>
                <option value="Cerai Mati" <?= isset($form->status_nikah) && $form->status_nikah == 'Cerai Mati' ? 'selected' : '' ?>>Cerai Mati</option>
              </select>
            </div>
            <div class="mb-2">
              <label>Agama</label>
              <select name="agama" class="form-select">
                <option value="">-- Pilih --</option>
                <option value="Islam" <?= isset($form->agama) && $form->agama == 'Islam' ? 'selected' : '' ?>>Islam</option>
                <option value="Kristen" <?= isset($form->agama) && $form->agama == 'Kristen' ? 'selected' : '' ?>>Kristen</option>
                <option value="Katolik" <?= isset($form->agama) && $form->agama == 'Katolik' ? 'selected' : '' ?>>Katolik</option>
                <option value="Hindu" <?= isset($form->agama) && $form->agama == 'Hindu' ? 'selected' : '' ?>>Hindu</option>
                <option value="Buddha" <?= isset($form->agama) && $form->agama == 'Buddha' ? 'selected' : '' ?>>Buddha</option>
                <option value="Konghucu" <?= isset($form->agama) && $form->agama == 'Konghucu' ? 'selected' : '' ?>>Konghucu</option>
              </select>
            </div>
            <div class="mb-2">
              <label>Pendidikan</label>
              <select name="pendidikan" class="form-select">
                <option value="">-- Pilih --</option>
                <option value="Tidak Sekolah" <?= isset($form->pendidikan) && $form->pendidikan == 'Tidak Sekolah' ? 'selected' : '' ?>>Tidak Sekolah</option>
                <option value="SD" <?= isset($form->pendidikan) && $form->pendidikan == 'SD' ? 'selected' : '' ?>>SD</option>
                <option value="SLTP" <?= isset($form->pendidikan) && $form->pendidikan == 'SLTP' ? 'selected' : '' ?>>SLTP</option>
                <option value="SLTA" <?= isset($form->pendidikan) && $form->pendidikan == 'SLTA' ? 'selected' : '' ?>>SLTA</option>
                <option value="Diploma" <?= isset($form->pendidikan) && $form->pendidikan == 'Diploma' ? 'selected' : '' ?>>Diploma</option>
                <option value="S1" <?= isset($form->pendidikan) && $form->pendidikan == 'S1' ? 'selected' : '' ?>>S1</option>
                <option value="S2" <?= isset($form->pendidikan) && $form->pendidikan == 'S2' ? 'selected' : '' ?>>S2</option>
                <option value="S3" <?= isset($form->pendidikan) && $form->pendidikan == 'S3' ? 'selected' : '' ?>>S3</option>
              </select>
            </div>
            <div class="mb-2">
              <label>Tanggal Lahir</label>
              <input type="date" name="tgl_lahir" id="tgl_lahir" class="form-control"
                value="<?= isset($form->tgl_lahir) ? $form->tgl_lahir : (isset($pasien_prefill->tgl_lahir) ? $pasien_prefill->tgl_lahir : '') ?>">
            </div>
            <div class="mb-2">
              <label>Umur</label>
              <input type="text" name="umur" id="umur" class="form-control" placeholder="Contoh: 34 thn"
                value="<?= isset($form->umur) ? $form->umur : (isset($pasien_prefill->umur) ? $pasien_prefill->umur : '') ?>" readonly>
            </div>
            <div class="mb-2">
              <label>No. Telp</label>
              <input type="text" name="no_telp" class="form-control"
                value="<?= isset($form->no_telp) ? $form->no_telp : (isset($pasien_prefill->no_telp) ? $pasien_prefill->no_telp : '') ?>">
            </div>
            <div class="mb-2 border rounded p-2 bg-light">
              <label class="fw-bold text-primary mb-1" style="font-size:13px;"><i class="fas fa-map-marker-alt"></i> Alamat Pasien (Kec. & Kel.)</label>

              <div class="row gx-1 mt-2">
                <div class="col-6 mb-1">
                  <select id="kecamatan_select" class="form-select form-select-sm" onchange="updateKelurahan()">
                    <option value="">Kecamatan</option>
                  </select>
                </div>
                <div class="col-6 mb-1">
                  <select id="kelurahan_select" class="form-select form-select-sm" onchange="updateAlamatLengkap()">
                    <option value="">Kelurahan</option>
                  </select>
                </div>
              </div>
              <input type="text" id="detail_jalan" class="form-control form-control-sm mb-1" oninput="updateAlamatLengkap()" placeholder="Detail Jl/No">
              <textarea name="alamat" id="alamat_lengkap" class="form-control form-control-sm bg-white" rows="2" placeholder="(Bisa diketik manual)" required><?= isset($form->alamat) ? $form->alamat : '' ?></textarea>
            </div>
            <div class="mb-2">
              <label>Pekerjaan</label>
              <input type="text" name="pekerjaan" class="form-control"
                value="<?= isset($form->pekerjaan) ? $form->pekerjaan : '' ?>">
            </div>
          </div>
        </div>
      </div>

      <!-- Card Identitas Pengirim -->
      <div class="col-md-4 d-flex">
        <div class="card border border-dark shadow-lg w-100 h-100 bg-white">
          <div class="card-header bg-secondary text-white fw-bold mb-0" style="font-weight: 800">
            Identitas Pengirim
          </div>
          <div class="card-body">
            <div class="mb-2">
              <label>Nama Dokter</label>
              <input type="text" name="nama_dokter" class="form-control"
                value="<?= isset($form->nama_dokter) ? $form->nama_dokter : '' ?>">
            </div>
            <div class="mb-2">
              <label>No. Telp Dokter</label>
              <input type="text" name="telp_pengirim" class="form-control"
                value="<?= isset($form->telp_pengirim) ? $form->telp_pengirim : '' ?>">
            </div>
            <div class="mb-2">
              <label>Alamat Dokter</label>
              <input type="text" name="alamat_pengirim" class="form-control"
                value="<?= isset($form->alamat_pengirim) ? $form->alamat_pengirim : '' ?>">
            </div>
            <div class="mb-2">
              <label>Tanggal Form</label>
              <input type="date" name="tgl_form" class="form-control"
                value="<?= isset($form->tgl_form) ? $form->tgl_form : date('Y-m-d') ?>">
            </div>
          </div>
        </div>
      </div>

      <!-- Card Diagnosa Klinis -->
      <div class="col-md-4 d-flex">
        <div class="card border border-dark shadow-lg w-100 h-100 bg-white">
          <div class="card-header bg-secondary text-white fw-bold mb-0" style="font-weight: 800">
            Diagnosa Klinis
          </div>
          <div class="card-body">
            <div class="mb-2">
              <label>Diagnosa/Keterangan Klinik</label>
              <textarea name="diagnosa_klinis" class="form-control" rows="4"><?= isset($form) ? $form->diagnosa_klinis : '' ?></textarea>
            </div>
            <div class="mb-2">
              <label>Obat yang Dikonsumsi</label>
              <textarea name="obat_dikonsumsi" class="form-control" rows="4"><?= isset($form) ? $form->obat_dikonsumsi : '' ?></textarea>
            </div>
          </div>
        </div>
      </div>
    </div>



    <!-- Card View untuk Data Spesimen -->
    <div class="card shadow mb-4">
      <div class="card-header bg-secondary text-white">
        <h5 class="mb-0" style="font-weight: 800">Data Spesimen</h5>
      </div>
      <div class="card-body">
        <?php
        $asal_sampel = isset($form) ? explode(',', $form->asal_sampel) : [];
        $lokasi_pengambilan = isset($form->lokasi_pengambilan) ? explode(', ', $form->lokasi_pengambilan) : [];
        $jenis_spesimen = isset($form->jenis_spesimen) ? explode(', ', $form->jenis_spesimen) : [];

        ?>

        <div class="row">
          <!-- Asal Sampel -->
          <div class="mb-3 col-md-6">
            <label>Asal Sampel</label><br>
            <?php foreach (['Langsung', 'Kiriman', 'Rujuk'] as $val): ?>
              <div class="form-check form-check-inline">
                <input class="form-check-input" type="checkbox" name="asal_sampel[]" value="<?= $val ?>"
                  <?= in_array($val, $asal_sampel) ? 'checked' : '' ?>>
                <label class="form-check-label"><?= $val ?></label>
              </div>
            <?php endforeach; ?>
          </div>

          <!-- Status Puasa -->
          <div class="col-md-6 mb-3">
            <label class="fw-bold">Status Puasa</label>

            <div class="border rounded p-3">

              <div class="d-flex align-items-center flex-wrap">

                <?php foreach (['Puasa', 'Tidak Puasa'] as $val): ?>
                  <div class="form-check me-4">
                    <input class="form-check-input"
                      type="radio"
                      name="puasa"
                      value="<?= $val ?>"
                      <?= (isset($form) && $form->puasa == $val) ? 'checked' : '' ?>>

                    <label class="form-check-label">
                      <?= $val ?>
                    </label>
                  </div>
                <?php endforeach; ?>

                <!-- Keterangan -->
                <input type="text"
                  name="keterangan_puasa"
                  class="form-control ms-3"
                  style="max-width:250px;"
                  placeholder="Contoh: 8 jam"
                  value="<?= isset($form) ? $form->keterangan_puasa : '' ?>">
              </div>
            </div>
          </div>
        </div>

        <!-- Lokasi Pengambilan -->
        <div class="mb-3">
          <label>Lokasi Pengambilan</label><br>
          <?php foreach (['Tangan', 'Paha', 'Rectal', 'Nasofaring', 'Lain-lain'] as $val): ?>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" name="lokasi_pengambilan[]" value="<?= $val ?>"
                <?= in_array($val, $lokasi_pengambilan) ? 'checked' : '' ?>>
              <label class="form-check-label"><?= $val ?></label>
            </div>
          <?php endforeach; ?>
          <input type="text" name="lokasi_lainnya" class="form-control mt-2"
            placeholder="Tuliskan jika lainnya" value="<?= isset($form) ? $form->lokasi_lainnya : '' ?>">
        </div>

        <!-- Jenis Spesimen -->
        <div class="mb-3">
          <label>Jenis Spesimen</label><br>
          <?php foreach (['Darah', 'Urine', 'Swab', 'Faeces', 'Jaringan', 'Sputum', 'Cairan', 'Lain-lain'] as $val): ?>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" name="jenis_spesimen[]" value="<?= $val ?>"
                <?= in_array($val, $jenis_spesimen) ? 'checked' : '' ?>>
              <label class="form-check-label"><?= $val ?></label>
            </div>
          <?php endforeach; ?>
          <input type="text" name="spesimen_lainnya" class="form-control mt-2"
            placeholder="Tuliskan jika lainnya" value="<?= isset($form) ? $form->spesimen_lainnya : '' ?>">
        </div>

        <div class="row">
          <div class="mb-3 col-md-4">
            <label>Tanggal Permintaan</label>
            <input type="date" name="tgl_permintaan" class="form-control" value="<?= isset($form) ? $form->tgl_permintaan : '' ?>">
          </div>
          <div class="mb-3 col-md-4">
            <label>Tanggal Pengambilan</label>
            <input type="date" name="tgl_pengambilan" class="form-control" value="<?= isset($form) ? $form->tgl_pengambilan : '' ?>">
          </div>
          <div class="mb-3 col-md-4">
            <label>Jam Pengambilan</label>
            <input type="time" name="jam_pengambilan" class="form-control" value="<?= isset($form) ? $form->jam_pengambilan : '' ?>">
          </div>
        </div>

        <div class="row">
          <div class="mb-3 col-md-6">
            <label>Volume Spesimen</label>
            <input type="text" name="volume_spesimen" class="form-control" value="<?= isset($form) ? $form->volume_spesimen : '' ?>">
          </div>
          <div class="mb-3 col-md-6">
            <label>Prioritas</label><br>
            <?php foreach (['Biasa', 'Cito'] as $val): ?>
              <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="prioritas" value="<?= $val ?>"
                  <?= (isset($form) && $form->prioritas == $val) ? 'checked' : '' ?>>
                <label class="form-check-label"><?= $val ?></label>
              </div>
            <?php endforeach; ?>
          </div>
        </div>

        <div class="mb-3">
          <label>Informasi Tambahan</label>
          <textarea name="info_tambahan" class="form-control" rows="2"><?= isset($form) ? $form->info_tambahan : '' ?></textarea>
        </div>
      </div>
    </div>

    <input type="hidden" id="id_pasien" name="id_pasien" value="<?= isset($form->id_pasien) ? html_escape($form->id_pasien) : (isset($pasien_prefill->id_pasien) ? html_escape($pasien_prefill->id_pasien) : '') ?>">
    <div class="card shadow mb-4">
      <div class="card-header bg-secondary text-white">
        <h5 class="mb-0" style="font-weight: 800">DAFTAR JENIS PEMERIKSAAN</h5>
      </div>
      <div class="card-body">
        <div class="row justify-content-center">
          <!-- ==================== HEMATOLOGI ==================== -->
          <div class="col-md-4 mb-4">
            <table class="table table-bordered table-md">
              <thead>
                <tr>
                  <th colspan="4" class="text-center bg-light">Hematologi</th>
                </tr>
                <tr>
                  <th style="width:50px;"></th>
                  <th>Pemeriksaan</th>
                </tr>
              </thead>

              <tbody>

                <!-- ==================== Paket Hematologi Rutin ==================== -->
                <tr class="table-secondary">
                  <td class="text-center">
                    <input type="checkbox" id="cek_paket_rutin" class="cek-paket" data-target="rutin">
                  </td>
                  <td colspan="3"><strong>Paket Hematologi Rutin</strong></td>
                </tr>

                <!-- Sub-item Paket Rutin -->
                <tr class="sub-rutin">
                  <td class="text-center">
                    <input type="checkbox"
                      name="jenis_pemeriksaan[]"
                      value="97|Hb|Darah Rutin|Hematologi"
                      class="cek-item paket-rutin"
                      <?= in_array(97, $selected_jenis ?? []) ? 'checked' : '' ?>>
                  </td>
                  <td>Hb</td>
                </tr>

                <tr class="sub-rutin">
                  <td class="text-center">
                    <input type="checkbox"
                      name="jenis_pemeriksaan[]"
                      value="98|Leukosit|Darah Rutin|Hematologi"
                      class="cek-item paket-rutin"
                      <?= in_array(98, $selected_jenis ?? []) ? 'checked' : '' ?>>
                  </td>
                  <td>Leukosit</td>
                </tr>

                <tr class="sub-rutin">
                  <td class="text-center">
                    <input type="checkbox"
                      name="jenis_pemeriksaan[]"
                      value="100|Eritrosit|Darah Rutin|Hematologi"
                      class="cek-item paket-rutin"
                      <?= in_array(100, $selected_jenis ?? []) ? 'checked' : '' ?>>
                  </td>
                  <td>Eritrosit</td>
                </tr>

                <tr class="sub-rutin">
                  <td class="text-center">
                    <input type="checkbox"
                      name="jenis_pemeriksaan[]"
                      value="101|Ht|Darah Rutin|Hematologi"
                      class="cek-item paket-rutin"
                      <?= in_array(101, $selected_jenis ?? []) ? 'checked' : '' ?>>
                  </td>
                  <td>Ht</td>
                </tr>

                <tr class="sub-rutin">
                  <td class="text-center">
                    <input type="checkbox"
                      name="jenis_pemeriksaan[]"
                      value="99|Trombosit|Darah Rutin|Hematologi"
                      class="cek-item paket-rutin"
                      <?= in_array(99, $selected_jenis ?? []) ? 'checked' : '' ?>>
                  </td>
                  <td>Trombosit</td>
                </tr>


                <!-- ==================== Paket Hematologi Lengkap ==================== -->
                <tr class="table-secondary">
                  <td class="text-center">
                    <input type="checkbox" id="cek_paket_lengkap" class="cek-paket" data-target="lengkap">
                  </td>
                  <td colspan="3"><strong>Paket Hematologi Lengkap</strong></td>
                </tr>

                <!-- Sub-item Paket Lengkap -->
                <tr class="sub-lengkap">
                  <td class="text-center">
                    <input type="checkbox"
                      name="jenis_pemeriksaan[]"
                      value="1|Hb|Darah Lengkap|Hematologi"
                      class="cek-item paket-lengkap"
                      <?= in_array(1, $selected_jenis ?? []) ? 'checked' : '' ?>>
                  </td>
                  <td>Hb</td>
                </tr>

                <tr class="sub-lengkap">
                  <td class="text-center">
                    <input type="checkbox"
                      name="jenis_pemeriksaan[]"
                      value="2|Leukosit|Darah Lengkap|Hematologi"
                      class="cek-item paket-lengkap"
                      <?= in_array(2, $selected_jenis ?? []) ? 'checked' : '' ?>>
                  </td>
                  <td>Leukosit</td>
                </tr>

                <tr class="sub-lengkap">
                  <td class="text-center">
                    <input type="checkbox"
                      name="jenis_pemeriksaan[]"
                      value="4|Eritrosit|Darah Lengkap|Hematologi"
                      class="cek-item paket-lengkap"
                      <?= in_array(4, $selected_jenis ?? []) ? 'checked' : '' ?>>
                  </td>
                  <td>Eritrosit</td>
                </tr>

                <tr class="sub-lengkap">
                  <td class="text-center">
                    <input type="checkbox"
                      name="jenis_pemeriksaan[]"
                      value="5|Ht|Darah Lengkap|Hematologi"
                      class="cek-item paket-lengkap"
                      <?= in_array(5, $selected_jenis ?? []) ? 'checked' : '' ?>>
                  </td>
                  <td>Ht</td>
                </tr>

                <tr class="sub-lengkap">
                  <td class="text-center">
                    <input type="checkbox"
                      name="jenis_pemeriksaan[]"
                      value="3|Trombosit|Darah Lengkap|Hematologi"
                      class="cek-item paket-lengkap"
                      <?= in_array(3, $selected_jenis ?? []) ? 'checked' : '' ?>>
                  </td>
                  <td>Trombosit</td>
                </tr>

                <tr class="sub-lengkap">
                  <td class="text-center">
                    <input type="checkbox"
                      name="jenis_pemeriksaan[]"
                      value="6|MCV|Darah Lengkap|Hematologi"
                      class="cek-item paket-lengkap"
                      <?= in_array(6, $selected_jenis ?? []) ? 'checked' : '' ?>>
                  </td>
                  <td>MCV</td>
                </tr>

                <tr class="sub-lengkap">
                  <td class="text-center">
                    <input type="checkbox"
                      name="jenis_pemeriksaan[]"
                      value="7|MCH|Darah Lengkap|Hematologi"
                      class="cek-item paket-lengkap"
                      <?= in_array(7, $selected_jenis ?? []) ? 'checked' : '' ?>>
                  </td>
                  <td>MCH</td>
                </tr>

                <tr class="sub-lengkap">
                  <td class="text-center">
                    <input type="checkbox"
                      name="jenis_pemeriksaan[]"
                      value="8|MCHC|Darah Lengkap|Hematologi"
                      class="cek-item paket-lengkap"
                      <?= in_array(8, $selected_jenis ?? []) ? 'checked' : '' ?>>
                  </td>
                  <td>MCHC</td>
                </tr>



                <!-- ==================== Paket Hitung Jenis ==================== -->
                <tr class="table-secondary">
                  <td class="text-center">
                    <input type="checkbox" id="cek_paket_hitungjenis" class="cek-paket" data-target="hitungjenis">
                  </td>
                  <td colspan="3"><strong>Hitung Jenis</strong></td>
                </tr>

                <!-- Sub-item Paket Hitung Jenis -->
                <tr class="sub-hitungjenis">
                  <td class="text-center">
                    <input type="checkbox"
                      name="jenis_pemeriksaan[]"
                      value="9|Basofil|Darah Lengkap|Hematologi"
                      class="cek-item paket-hitungjenis"
                      <?= in_array(9, $selected_jenis ?? []) ? 'checked' : '' ?>>
                  </td>
                  <td>Basofil</td>
                </tr>

                <tr class="sub-hitungjenis">
                  <td class="text-center">
                    <input type="checkbox"
                      name="jenis_pemeriksaan[]"
                      value="10|Eosinofil|Darah Lengkap|Hematologi"
                      class="cek-item paket-hitungjenis"
                      <?= in_array(10, $selected_jenis ?? []) ? 'checked' : '' ?>>
                  </td>
                  <td>Eosinofil</td>
                </tr>

                <tr class="sub-hitungjenis">
                  <td class="text-center">
                    <input type="checkbox"
                      name="jenis_pemeriksaan[]"
                      value="11|Netrofil|Darah Lengkap|Hematologi"
                      class="cek-item paket-hitungjenis"
                      <?= in_array(11, $selected_jenis ?? []) ? 'checked' : '' ?>>
                  </td>
                  <td>Netrofil</td>
                </tr>

                <tr class="sub-hitungjenis">
                  <td class="text-center">
                    <input type="checkbox"
                      name="jenis_pemeriksaan[]"
                      value="12|Limfosit|Darah Lengkap|Hematologi"
                      class="cek-item paket-hitungjenis"
                      <?= in_array(12, $selected_jenis ?? []) ? 'checked' : '' ?>>
                  </td>
                  <td>Limfosit</td>
                </tr>

                <tr class="sub-hitungjenis">
                  <td class="text-center">
                    <input type="checkbox"
                      name="jenis_pemeriksaan[]"
                      value="13|Monosit|Darah Lengkap|Hematologi"
                      class="cek-item paket-hitungjenis"
                      <?= in_array(13, $selected_jenis ?? []) ? 'checked' : '' ?>>
                  </td>
                  <td>Monosit</td>
                </tr>
                <tr>
                  <td colspan="4" class="py-3"></td>
                </tr>

                <tr>
                  <td class="text-center">
                    <input type="checkbox"
                      name="jenis_pemeriksaan[]"
                      value="14|LED|-|Hematologi"
                      <?= in_array(14, $selected_jenis ?? []) ? 'checked' : '' ?>>
                  </td>
                  <td>LED</td>
                </tr>

                <!-- GOLONGAN DARAH (SLIDE) -->
                <tr>
                  <td class="text-center">
                    <input type="checkbox"
                      name="jenis_pemeriksaan[]"
                      value="15|Golongan Darah (Slide)|-|Hematologi"
                      <?= in_array(15, $selected_jenis ?? []) ? 'checked' : '' ?>>
                  </td>
                  <td>Golongan Darah (Slide)</td>
                </tr>
              </tbody>
              </tbody>
              </tbody>
            </table>
          </div>

          <!-- ==================== KIMIA DARAH ==================== -->
          <div class="col-md-4 mb-4">
            <table class="table table-bordered table-md">
              <thead>
                <tr>
                  <th colspan="4" class="text-center bg-light">Kimia Darah</th>
                </tr>
                <tr>
                  <th style="width:50px;"></th>
                  <th>Pemeriksaan</th>
                </tr>
              </thead>
              <tbody>

                <!-- ========== Paket Faal Hati ========== -->
                <tr class="table-secondary">
                  <td class="text-center">
                    <input type="checkbox" id="cek_paket_faal_hati" class="cek-paket" data-target="faal-hati">
                  </td>
                  <td colspan="3"><strong>Paket Faal Hati</strong></td>
                </tr>

                <tr class="sub-faal-hati">
                  <td class="text-center">
                    <input type="checkbox" name="jenis_pemeriksaan[]" value="18|SGOT|Paket Faal Hati|Kimia Darah"
                      class="cek-item paket-faal-hati"
                      <?= in_array(18, $selected_jenis ?? []) ? 'checked' : '' ?>>
                  </td>
                  <td>SGOT</td>
                </tr>

                <tr class="sub-faal-hati">
                  <td class="text-center">
                    <input type="checkbox" name="jenis_pemeriksaan[]" value="19|SGPT|Paket Faal Hati|Kimia Darah"
                      class="cek-item paket-faal-hati"
                      <?= in_array(19, $selected_jenis ?? []) ? 'checked' : '' ?>>
                  </td>
                  <td>SGPT</td>
                </tr>

                <tr class="sub-faal-hati">
                  <td class="text-center">
                    <input type="checkbox" name="jenis_pemeriksaan[]" value="20|GGT|Paket Faal Hati|Kimia Darah"
                      class="cek-item paket-faal-hati"
                      <?= in_array(20, $selected_jenis ?? []) ? 'checked' : '' ?>>
                  </td>
                  <td>GGT</td>
                </tr>

                <tr class="sub-faal-hati">
                  <td class="text-center">
                    <input type="checkbox" name="jenis_pemeriksaan[]" value="21|Protein Total|Paket Faal Hati|Kimia Darah"
                      class="cek-item paket-faal-hati"
                      <?= in_array(21, $selected_jenis ?? []) ? 'checked' : '' ?>>
                  </td>
                  <td>Protein Total</td>
                </tr>

                <tr class="sub-faal-hati">
                  <td class="text-center">
                    <input type="checkbox" name="jenis_pemeriksaan[]" value="22|Albumin|Paket Faal Hati|Kimia Darah"
                      class="cek-item paket-faal-hati"
                      <?= in_array(22, $selected_jenis ?? []) ? 'checked' : '' ?>>
                  </td>
                  <td>Albumin</td>
                </tr>

                <tr class="sub-faal-hati">
                  <td class="text-center">
                    <input type="checkbox" name="jenis_pemeriksaan[]" value="23|Globulin|Paket Faal Hati|Kimia Darah"
                      class="cek-item paket-faal-hati"
                      <?= in_array(23, $selected_jenis ?? []) ? 'checked' : '' ?>>
                  </td>
                  <td>Globulin</td>
                </tr>

                <tr class="sub-faal-hati">
                  <td class="text-center">
                    <input type="checkbox" name="jenis_pemeriksaan[]" value="24|Bilirubin Total|Paket Faal Hati|Kimia Darah"
                      class="cek-item paket-faal-hati"
                      <?= in_array(24, $selected_jenis ?? []) ? 'checked' : '' ?>>
                  </td>
                  <td>Bilirubin Total</td>
                </tr>

                <tr class="sub-faal-hati">
                  <td class="text-center">
                    <input type="checkbox" name="jenis_pemeriksaan[]" value="25|Bilirubin Direct|Paket Faal Hati|Kimia Darah"
                      class="cek-item paket-faal-hati"
                      <?= in_array(25, $selected_jenis ?? []) ? 'checked' : '' ?>>
                  </td>
                  <td>Bilirubin Direct</td>
                </tr>

                <tr class="sub-faal-hati">
                  <td class="text-center">
                    <input type="checkbox" name="jenis_pemeriksaan[]" value="26|Bilirubin Indirect|Paket Faal Hati|Kimia Darah"
                      class="cek-item paket-faal-hati"
                      <?= in_array(26, $selected_jenis ?? []) ? 'checked' : '' ?>>
                  </td>
                  <td>Bilirubin Indirect</td>
                </tr>

                <tr class="sub-faal-hati">
                  <td class="text-center">
                    <input type="checkbox" name="jenis_pemeriksaan[]" value="27|Cholinetrase (CHE)|Paket Faal Hati|Kimia Darah"
                      class="cek-item paket-faal-hati"
                      <?= in_array(27, $selected_jenis ?? []) ? 'checked' : '' ?>>
                  </td>
                  <td>Cholinetrase (CHE)</td>
                </tr>

                <tr>
                  <td colspan="4" class="py-3"></td>
                </tr>

                <!-- ========== Paket Fungsi Ginjal ========== -->
                <tr class="table-secondary">
                  <td class="text-center">
                    <input type="checkbox" id="cek_paket_ginjal" class="cek-paket" data-target="ginjal">
                  </td>
                  <td colspan="3"><strong>Paket Fungsi Ginjal</strong></td>
                </tr>

                <tr class="sub-ginjal">
                  <td class="text-center">
                    <input type="checkbox" name="jenis_pemeriksaan[]" value="28|Ureum|Fungsi Ginjal|Kimia Darah"
                      class="cek-item paket-ginjal"
                      <?= in_array(28, $selected_jenis ?? []) ? 'checked' : '' ?>>
                  </td>
                  <td>Ureum</td>
                </tr>

                <tr class="sub-ginjal">
                  <td class="text-center">
                    <input type="checkbox" name="jenis_pemeriksaan[]" value="29|Creatinin|Fungsi Ginjal|Kimia Darah"
                      class="cek-item paket-ginjal"
                      <?= in_array(29, $selected_jenis ?? []) ? 'checked' : '' ?>>
                  </td>
                  <td>Creatinin</td>
                </tr>

                <tr class="sub-ginjal">
                  <td class="text-center">
                    <input type="checkbox" name="jenis_pemeriksaan[]" value="31|Uric Acid|Fungsi Ginjal|Kimia Darah"
                      class="cek-item paket-ginjal"
                      <?= in_array(31, $selected_jenis ?? []) ? 'checked' : '' ?>>
                  </td>
                  <td>Uric Acid</td>
                </tr>

                <tr class="sub-ginjal">
                  <td class="text-center">
                    <input type="checkbox" name="jenis_pemeriksaan[]" value="30|eGFR/eLFG|Fungsi Ginjal|Kimia Darah"
                      class="cek-item paket-ginjal"
                      <?= in_array(30, $selected_jenis ?? []) ? 'checked' : '' ?>>
                  </td>
                  <td>eGFR/eLFG</td>
                </tr>

                <tr>
                  <td colspan="4" class="py-3"></td>
                </tr>

                <!-- ========== Paket Gula Darah ========== -->
                <tr class="table-secondary">
                  <td class="text-center">
                    <input type="checkbox" id="cek_paket_gula" class="cek-paket" data-target="gula">
                  </td>
                  <td colspan="3"><strong>Paket Gula Darah</strong></td>
                </tr>

                <tr class="sub-gula">
                  <td class="text-center">
                    <input type="checkbox" name="jenis_pemeriksaan[]" value="37|Glukosa Puasa|Paket Gula Darah|Kimia Darah"
                      class="cek-item paket-gula"
                      <?= in_array(37, $selected_jenis ?? []) ? 'checked' : '' ?>>
                  </td>
                  <td>Glukosa Puasa</td>
                </tr>

                <tr class="sub-gula">
                  <td class="text-center">
                    <input type="checkbox" name="jenis_pemeriksaan[]" value="39|Glukosa 2 Jam PP|Paket Gula Darah|Kimia Darah"
                      class="cek-item paket-gula"
                      <?= in_array(39, $selected_jenis ?? []) ? 'checked' : '' ?>>
                  </td>
                  <td>Glukosa 2 Jam PP</td>
                </tr>

                <tr class="sub-gula">
                  <td class="text-center">
                    <input type="checkbox" name="jenis_pemeriksaan[]" value="38|Glukosa Sewaktu|Paket Gula Darah|Kimia Darah"
                      class="cek-item paket-gula"
                      <?= in_array(38, $selected_jenis ?? []) ? 'checked' : '' ?>>
                  </td>
                  <td>Glukosa Sewaktu</td>
                </tr>

                <tr class="sub-gula">
                  <td class="text-center">
                    <input type="checkbox" name="jenis_pemeriksaan[]" value="40|HbA1c|Paket Gula Darah|Kimia Darah"
                      class="cek-item paket-gula"
                      <?= in_array(40, $selected_jenis ?? []) ? 'checked' : '' ?>>
                  </td>
                  <td>HbA1c</td>
                </tr>

                <tr>
                  <td colspan="4" class="py-3"></td>
                </tr>

                <!-- ========== Paket Lemak (Profil Lipid) ========== -->
                <tr class="table-secondary">
                  <td class="text-center">
                    <input type="checkbox" id="cek_paket_lemak" class="cek-paket" data-target="lemak">
                  </td>
                  <td colspan="3"><strong>Lemak (Paket Profil Lipid)</strong></td>
                </tr>

                <tr class="sub-lemak">
                  <td class="text-center">
                    <input type="checkbox" name="jenis_pemeriksaan[]" value="41|Cholesterol Total|Paket Lemak|Kimia Darah"
                      class="cek-item paket-lemak"
                      <?= in_array(41, $selected_jenis ?? []) ? 'checked' : '' ?>>
                  </td>
                  <td>Cholesterol Total</td>
                </tr>

                <tr class="sub-lemak">
                  <td class="text-center">
                    <input type="checkbox" name="jenis_pemeriksaan[]" value="45|Triglyserida|Paket Lemak|Kimia Darah"
                      class="cek-item paket-lemak"
                      <?= in_array(45, $selected_jenis ?? []) ? 'checked' : '' ?>>
                  </td>
                  <td>Triglyserida</td>
                </tr>

                <tr class="sub-lemak">
                  <td class="text-center">
                    <input type="checkbox" name="jenis_pemeriksaan[]" value="42|HDL Cholesterol|Paket Lemak|Kimia Darah"
                      class="cek-item paket-lemak"
                      <?= in_array(42, $selected_jenis ?? []) ? 'checked' : '' ?>>
                  </td>
                  <td>HDL Cholesterol</td>
                </tr>

                <tr class="sub-lemak">
                  <td class="text-center">
                    <input type="checkbox" name="jenis_pemeriksaan[]" value="43|LDL Cholesterol|Paket Lemak|Kimia Darah"
                      class="cek-item paket-lemak"
                      <?= in_array(43, $selected_jenis ?? []) ? 'checked' : '' ?>>
                  </td>
                  <td>LDL Cholesterol</td>
                </tr>

              </tbody>
            </table>
          </div>


          <!-- ==================== URINALISA ==================== -->
          <div class="col-md-4 mb-4">
            <table class="table table-bordered table-md">
              <thead>
                <tr>
                  <th colspan="4" class="text-center bg-light">Urinalisa</th>
                </tr>
                <tr>
                  <th style="width:50px;"></th>
                  <th>Pemeriksaan</th>
                </tr>
              </thead>
              <tbody>

                <!-- ========== Urin Lengkap ========== -->
                <!--
      <tr>
        <td class="text-center">
          <input type="checkbox" name="jenis_pemeriksaan[]" value="100|Urin Lengkap|-|Urinalisa"
                 <?= in_array(100, $selected_jenis ?? []) ? 'checked' : '' ?>>
        </td>
        <td>Urin Lengkap</td>
      </tr> -->

                <!-- ========== Makroskopis ========== -->
                <tr class="table-secondary">
                  <td class="text-center">
                    <input type="checkbox" id="cek_makroskopis" class="cek-paket" data-target="makroskopis">
                  </td>
                  <td colspan="3"><strong>Makroskopis</strong></td>
                </tr>

                <tr class="sub-makroskopis">
                  <td class="text-center">
                    <input type="checkbox" name="jenis_pemeriksaan[]" value="46|Warna|Makroskopis|Urinalisa"
                      class="cek-item makroskopis"
                      <?= in_array(46, $selected_jenis ?? []) ? 'checked' : '' ?>>
                  </td>
                  <td>Warna</td>
                </tr>

                <tr class="sub-makroskopis">
                  <td class="text-center">
                    <input type="checkbox" name="jenis_pemeriksaan[]" value="47|Kekeruhan|Makroskopis|Urinalisa"
                      class="cek-item makroskopis"
                      <?= in_array(47, $selected_jenis ?? []) ? 'checked' : '' ?>>
                  </td>
                  <td>Kekeruhan</td>
                </tr>

                <tr class="sub-makroskopis">
                  <td class="text-center">
                    <input type="checkbox" name="jenis_pemeriksaan[]" value="48|Berat Jenis|Makroskopis|Urinalisa"
                      class="cek-item makroskopis"
                      <?= in_array(48, $selected_jenis ?? []) ? 'checked' : '' ?>>
                  </td>
                  <td>Berat Jenis</td>
                </tr>

                <tr class="sub-makroskopis">
                  <td class="text-center">
                    <input type="checkbox" name="jenis_pemeriksaan[]" value="49|pH|Makroskopis|Urinalisa"
                      class="cek-item makroskopis"
                      <?= in_array(49, $selected_jenis ?? []) ? 'checked' : '' ?>>
                  </td>
                  <td>pH</td>
                </tr>

                <tr class="sub-makroskopis">
                  <td class="text-center">
                    <input type="checkbox" name="jenis_pemeriksaan[]" value="50|Darah|Makroskopis|Urinalisa"
                      class="cek-item makroskopis"
                      <?= in_array(50, $selected_jenis ?? []) ? 'checked' : '' ?>>
                  </td>
                  <td>Darah</td>
                </tr>

                <tr class="sub-makroskopis">
                  <td class="text-center">
                    <input type="checkbox" name="jenis_pemeriksaan[]" value="51|Glukosa|Makroskopis|Urinalisa"
                      class="cek-item makroskopis"
                      <?= in_array(51, $selected_jenis ?? []) ? 'checked' : '' ?>>
                  </td>
                  <td>Glukosa</td>
                </tr>

                <tr class="sub-makroskopis">
                  <td class="text-center">
                    <input type="checkbox" name="jenis_pemeriksaan[]" value="52|Leukosit|Makroskopis|Urinalisa"
                      class="cek-item makroskopis"
                      <?= in_array(52, $selected_jenis ?? []) ? 'checked' : '' ?>>
                  </td>
                  <td>Leukosit</td>
                </tr>

                <tr class="sub-makroskopis">
                  <td class="text-center">
                    <input type="checkbox" name="jenis_pemeriksaan[]" value="53|Protein|Makroskopis|Urinalisa"
                      class="cek-item makroskopis"
                      <?= in_array(53, $selected_jenis ?? []) ? 'checked' : '' ?>>
                  </td>
                  <td>Protein</td>
                </tr>

                <tr class="sub-makroskopis">
                  <td class="text-center">
                    <input type="checkbox" name="jenis_pemeriksaan[]" value="54|Keton|Makroskopis|Urinalisa"
                      class="cek-item makroskopis"
                      <?= in_array(54, $selected_jenis ?? []) ? 'checked' : '' ?>>
                  </td>
                  <td>Keton</td>
                </tr>

                <tr class="sub-makroskopis">
                  <td class="text-center">
                    <input type="checkbox" name="jenis_pemeriksaan[]" value="55|Nitrit|Makroskopis|Urinalisa"
                      class="cek-item makroskopis"
                      <?= in_array(55, $selected_jenis ?? []) ? 'checked' : '' ?>>
                  </td>
                  <td>Nitrit</td>
                </tr>

                <tr class="sub-makroskopis">
                  <td class="text-center">
                    <input type="checkbox" name="jenis_pemeriksaan[]" value="56|Bilirubin|Makroskopis|Urinalisa"
                      class="cek-item makroskopis"
                      <?= in_array(56, $selected_jenis ?? []) ? 'checked' : '' ?>>
                  </td>
                  <td>Bilirubin</td>
                </tr>

                <tr class="sub-makroskopis">
                  <td class="text-center">
                    <input type="checkbox" name="jenis_pemeriksaan[]" value="57|Urobilinogen|Makroskopis|Urinalisa"
                      class="cek-item makroskopis"
                      <?= in_array(57, $selected_jenis ?? []) ? 'checked' : '' ?>>
                  </td>
                  <td>Urobilinogen</td>
                </tr>

                <tr class="sub-makroskopis">
                  <td class="text-center">
                    <input type="checkbox" name="jenis_pemeriksaan[]" value="58|Asam Askorbat|Makroskopis|Urinalisa"
                      class="cek-item makroskopis"
                      <?= in_array(58, $selected_jenis ?? []) ? 'checked' : '' ?>>
                  </td>
                  <td>Asam Askorbat</td>
                </tr>

                <!-- ========== Mikroskopis ========== -->
                <tr class="table-secondary">
                  <td class="text-center">
                    <input type="checkbox" id="cek_mikroskopis" class="cek-paket" data-target="mikroskopis">
                  </td>
                  <td colspan="3"><strong>Mikroskopis</strong></td>
                </tr>

                <tr class="sub-mikroskopis">
                  <td class="text-center">
                    <input type="checkbox" name="jenis_pemeriksaan[]" value="59|Leukosit|Mikroskopis|Urinalisa"
                      class="cek-item mikroskopis"
                      <?= in_array(59, $selected_jenis ?? []) ? 'checked' : '' ?>>
                  </td>
                  <td>Leukosit</td>
                </tr>

                <tr class="sub-mikroskopis">
                  <td class="text-center">
                    <input type="checkbox" name="jenis_pemeriksaan[]" value="60|Eritrosit|Mikroskopis|Urinalisa"
                      class="cek-item mikroskopis"
                      <?= in_array(60, $selected_jenis ?? []) ? 'checked' : '' ?>>
                  </td>
                  <td>Eritrosit</td>
                </tr>

                <tr class="sub-mikroskopis">
                  <td class="text-center">
                    <input type="checkbox" name="jenis_pemeriksaan[]" value="61|Kristal|Mikroskopis|Urinalisa"
                      class="cek-item mikroskopis"
                      <?= in_array(61, $selected_jenis ?? []) ? 'checked' : '' ?>>
                  </td>
                  <td>Kristal</td>
                </tr>

                <tr class="sub-mikroskopis">
                  <td class="text-center">
                    <input type="checkbox" name="jenis_pemeriksaan[]" value="62|Silinder|Mikroskopis|Urinalisa"
                      class="cek-item mikroskopis"
                      <?= in_array(62, $selected_jenis ?? []) ? 'checked' : '' ?>>
                  </td>
                  <td>Silinder</td>
                </tr>

                <tr class="sub-mikroskopis">
                  <td class="text-center">
                    <input type="checkbox" name="jenis_pemeriksaan[]" value="63|Bakteri|Mikroskopis|Urinalisa"
                      class="cek-item mikroskopis"
                      <?= in_array(63, $selected_jenis ?? []) ? 'checked' : '' ?>>
                  </td>
                  <td>Bakteri</td>
                </tr>

                <tr class="sub-mikroskopis">
                  <td class="text-center">
                    <input type="checkbox" name="jenis_pemeriksaan[]" value="64|Parasit|Mikroskopis|Urinalisa"
                      class="cek-item mikroskopis"
                      <?= in_array(64, $selected_jenis ?? []) ? 'checked' : '' ?>>
                  </td>
                  <td>Parasit</td>
                </tr>

                <!-- ========== Pemeriksaan Lainnya ========== -->
                <tr class="table-secondary">
                  <td class="text-center">
                    <input type="checkbox" id="cek_pemeriksaan_lainnya" class="cek-paket" data-target="pemeriksaan_lainnya">
                  </td>
                  <td colspan="3"><strong>Pemeriksaan Lainnya</strong></td>
                </tr>

                <tr class="sub-pemeriksaan_lainnya">
                  <td class="text-center">
                    <input type="checkbox" name="jenis_pemeriksaan[]" value="85|Protein Urin|Pemeriksaan Lainnya|Urinalisa"
                      class="cek-item pemeriksaan_lainnya"
                      <?= in_array(85, $selected_jenis ?? []) ? 'checked' : '' ?>>
                  </td>
                  <td>Protein Urin</td>
                </tr>

                <tr class="sub-pemeriksaan_lainnya">
                  <td class="text-center">
                    <input type="checkbox" name="jenis_pemeriksaan[]" value="86|Glukosa Urin|Pemeriksaan Lainnya|Urinalisa"
                      class="cek-item pemeriksaan_lainnya"
                      <?= in_array(86, $selected_jenis ?? []) ? 'checked' : '' ?>>
                  </td>
                  <td>Glukosa Urin</td>
                </tr>

                <tr class="sub-pemeriksaan_lainnya">
                  <td class="text-center">
                    <input type="checkbox" name="jenis_pemeriksaan[]" value="65|Tes Kehamilan|Pemeriksaan Lainnya|Urinalisa"
                      class="cek-item pemeriksaan_lainnya"
                      <?= in_array(65, $selected_jenis ?? []) ? 'checked' : '' ?>>
                  </td>
                  <td>Tes Kehamilan</td>
                </tr>

                <tr class="sub-pemeriksaan_lainnya">
                  <td class="text-center">
                    <input type="checkbox" name="jenis_pemeriksaan[]" value="84|Mikroalbumin|Pemeriksaan Lainnya|Urinalisa"
                      class="cek-item pemeriksaan_lainnya"
                      <?= in_array(84, $selected_jenis ?? []) ? 'checked' : '' ?>>
                  </td>
                  <td>Mikroalbumin</td>
                </tr>

              </tbody>
            </table>
          </div>




          <!-- ==================== BIOMOLEKULER ==================== -->
          <div class="col-md-4 mb-4">
            <table class="table table-bordered table-md">
              <thead>
                <tr>
                  <th colspan="4" class="text-center bg-light">Biomolekuler</th>
                </tr>
                <tr>
                  <th style="width:50px;"></th>
                  <th>Pemeriksaan</th>
                </tr>
              </thead>
              <tbody>

                <tr>
                  <td class="text-center">
                    <input type="checkbox" name="jenis_pemeriksaan[]"
                      value="87|PCR Covid||Biomolekuler"
                      <?= in_array(87, $selected_jenis ?? []) ? 'checked' : '' ?>>
                  </td>
                  <td>PCR Covid</td>
                </tr>

                <tr>
                  <td class="text-center">
                    <input type="checkbox" name="jenis_pemeriksaan[]"
                      value="88|Antigen Covid||Biomolekuler"
                      <?= in_array(88, $selected_jenis ?? []) ? 'checked' : '' ?>>
                  </td>
                  <td>Antigen Covid</td>
                </tr>

              </tbody>
            </table>
          </div>


          <!-- ==================== HEMOSTASIS ==================== -->
          <div class="col-md-4 mb-4">
            <table class="table table-bordered table-md">
              <thead>
                <tr>
                  <th colspan="4" class="text-center bg-light">Hemostasis</th>
                </tr>
                <tr>
                  <th style="width:50px;"></th>
                  <th>Pemeriksaan</th>
                </tr>
              </thead>
              <tbody>

                <tr>
                  <td class="text-center">
                    <input type="checkbox" name="jenis_pemeriksaan[]"
                      value="16|Masa Perdarahan|-|Hemostasis"
                      <?= in_array(16, $selected_jenis ?? []) ? 'checked' : '' ?>>
                  </td>
                  <td>Masa Perdarahan</td>
                </tr>

                <tr>
                  <td class="text-center">
                    <input type="checkbox" name="jenis_pemeriksaan[]"
                      value="17|Masa Pembekuan|-|Hemostasis"
                      <?= in_array(17, $selected_jenis ?? []) ? 'checked' : '' ?>>
                  </td>
                  <td>Masa Pembekuan</td>
                </tr>

              </tbody>
            </table>
          </div>


          <!-- ==================== MIKROBIOLOGI / PARASITOLOGI ==================== -->
          <div class="col-md-4 mb-4">
            <table class="table table-bordered table-md">
              <thead>
                <tr>
                  <th colspan="4" class="text-center bg-light">Mikrobiologi / Parasitologi</th>
                </tr>
                <tr>
                  <th style="width:50px;"></th>
                  <th>Pemeriksaan</th>
                </tr>
              </thead>
              <tbody>

                <tr>
                  <td class="text-center">
                    <input type="checkbox" name="jenis_pemeriksaan[]"
                      value="82|Malaria||Mikrobiologi / Parasitologi"
                      <?= in_array(82, $selected_jenis ?? []) ? 'checked' : '' ?>>
                  </td>
                  <td>Malaria</td>
                </tr>

                <tr>
                  <td class="text-center">
                    <input type="checkbox" name="jenis_pemeriksaan[]"
                      value="83|Sputum / BTA||Mikrobiologi / Parasitologi"
                      <?= in_array(83, $selected_jenis ?? []) ? 'checked' : '' ?>>
                  </td>
                  <td>Sputum / BTA</td>
                </tr>

              </tbody>
            </table>
          </div>
        </div>

        <!-- ==================== IMUNOLOGI ==================== -->
        <div class="row justify-content-center">
          <div class="col-md-8 mb-4">
            <div class="card shadow-sm">
              <div class="card-header text-center fw-bold bg-light"><strong>IMUNOLOGI</strong></div>
              <div class="card-body">
                <div class="row">

                  <!-- Bagian Imunologi Utama -->
                  <div class="col-md-6">
                    <table class="table table-bordered table-sm mb-3">
                      <thead class="table-light text-center">
                        <tr>
                          <th style="width: 50px;"></th>
                          <th>Pemeriksaan</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td class="text-center">
                            <input type="checkbox"
                              name="jenis_pemeriksaan[]"
                              value="68|NS-1||Imunologi"
                              <?= in_array(68, $selected_jenis ?? []) ? 'checked' : '' ?>>
                          </td>
                          <td>NS-1</td>
                        </tr>
                        <tr>
                          <td class="text-center">
                            <input type="checkbox"
                              name="jenis_pemeriksaan[]"
                              value="70|HIV||Imunologi"
                              <?= in_array(70, $selected_jenis ?? []) ? 'checked' : '' ?>>
                          </td>
                          <td>HIV</td>
                        </tr>
                        <tr>
                          <td class="text-center">
                            <input type="checkbox"
                              name="jenis_pemeriksaan[]"
                              value="72|HBsAg||Imunologi"
                              <?= in_array(72, $selected_jenis ?? []) ? 'checked' : '' ?>>
                          </td>
                          <td>HBsAg</td>
                        </tr>
                        <tr>
                          <td class="text-center">
                            <input type="checkbox"
                              name="jenis_pemeriksaan[]"
                              value="73|Anti HBsAg||Imunologi"
                              <?= in_array(73, $selected_jenis ?? []) ? 'checked' : '' ?>>
                          </td>
                          <td>Anti HBsAg</td>
                        </tr>
                        <tr>
                          <td class="text-center">
                            <input type="checkbox"
                              name="jenis_pemeriksaan[]"
                              value="74|Anti HAV IgM||Imunologi"
                              <?= in_array(74, $selected_jenis ?? []) ? 'checked' : '' ?>>
                          </td>
                          <td>Anti HAV IgM</td>
                        </tr>
                        <tr>
                          <td class="text-center">
                            <input type="checkbox"
                              name="jenis_pemeriksaan[]"
                              value="75|Anti HCV Total||Imunologi"
                              <?= in_array(75, $selected_jenis ?? []) ? 'checked' : '' ?>>
                          </td>
                          <td>Anti HCV Total</td>
                        </tr>
                        <tr>
                          <td class="text-center">
                            <input type="checkbox"
                              name="jenis_pemeriksaan[]"
                              value="69|IgG/IgM Dengue||Imunologi"
                              <?= in_array(69, $selected_jenis ?? []) ? 'checked' : '' ?>>
                          </td>
                          <td>IgG IgM Dengue</td>
                        </tr>
                        <tr>
                          <td class="text-center">
                            <input type="checkbox"
                              name="jenis_pemeriksaan[]"
                              value="67|Tubex TF||Imunologi"
                              <?= in_array(67, $selected_jenis ?? []) ? 'checked' : '' ?>>
                          </td>
                          <td>TUBEX TF</td>
                        </tr>
                      </tbody>
                    </table>
                  </div>

                  <!-- Bagian Widal -->
                  <div class="col-md-6">
                    <table class="table table-bordered table-sm mb-3">
                      <thead class="table-light text-center">
                        <tr>
                          <th style="width: 50px;"></th>
                          <th>Pemeriksaan</th>
                        </tr>
                      </thead>
                      <tbody>
                        <!-- Paket Widal -->
                        <tr class="table-secondary">
                          <td class="text-center">
                            <input type="checkbox" class="cek-paket" data-target="widal">
                          </td>
                          <td><strong>Widal</strong></td>
                        </tr>

                        <tr class="sub-widal">
                          <td class="text-center">
                            <input type="checkbox"
                              name="jenis_pemeriksaan[]"
                              value="89|Salmonella Typhi O|Widal|Imunologi"
                              class="cek-item paket-widal"
                              <?= in_array(89, $selected_jenis ?? []) ? 'checked' : '' ?>>
                          </td>
                          <td>Salmonella Typhi O</td>
                        </tr>
                        <tr class="sub-widal">
                          <td class="text-center">
                            <input type="checkbox"
                              name="jenis_pemeriksaan[]"
                              value="90|Salmonella Paratyphi AO|Widal|Imunologi"
                              class="cek-item paket-widal"
                              <?= in_array(90, $selected_jenis ?? []) ? 'checked' : '' ?>>
                          </td>
                          <td>Salmonella Paratyphi AO</td>
                        </tr>
                        <tr class="sub-widal">
                          <td class="text-center">
                            <input type="checkbox"
                              name="jenis_pemeriksaan[]"
                              value="91|Salmonella Paratyphi BO|Widal|Imunologi"
                              class="cek-item paket-widal"
                              <?= in_array(91, $selected_jenis ?? []) ? 'checked' : '' ?>>
                          </td>
                          <td>Salmonella Paratyphi BO</td>
                        </tr>
                        <tr class="sub-widal">
                          <td class="text-center">
                            <input type="checkbox"
                              name="jenis_pemeriksaan[]"
                              value="92|Salmonella Paratyphi CO|Widal|Imunologi"
                              class="cek-item paket-widal"
                              <?= in_array(92, $selected_jenis ?? []) ? 'checked' : '' ?>>
                          </td>
                          <td>Salmonella Paratyphi CO</td>
                        </tr>
                        <tr class="sub-widal">
                          <td class="text-center">
                            <input type="checkbox"
                              name="jenis_pemeriksaan[]"
                              value="93|Salmonella typhi H|Widal|Imunologi"
                              class="cek-item paket-widal"
                              <?= in_array(93, $selected_jenis ?? []) ? 'checked' : '' ?>>
                          </td>
                          <td>Salmonella typhi H</td>
                        </tr>
                        <tr class="sub-widal">
                          <td class="text-center">
                            <input type="checkbox"
                              name="jenis_pemeriksaan[]"
                              value="94|Salmonella Paratyphi AH|Widal|Imunologi"
                              class="cek-item paket-widal"
                              <?= in_array(94, $selected_jenis ?? []) ? 'checked' : '' ?>>
                          </td>
                          <td>Salmonella Paratyphi AH</td>
                        </tr>
                        <tr class="sub-widal">
                          <td class="text-center">
                            <input type="checkbox"
                              name="jenis_pemeriksaan[]"
                              value="95|Salmonella Paratyphi BH|Widal|Imunologi"
                              class="cek-item paket-widal"
                              <?= in_array(95, $selected_jenis ?? []) ? 'checked' : '' ?>>
                          </td>
                          <td>Salmonella Paratyphi BH</td>
                        </tr>
                        <tr class="sub-widal">
                          <td class="text-center">
                            <input type="checkbox"
                              name="jenis_pemeriksaan[]"
                              value="96|Salmonella Paratyphi CH|Widal|Imunologi"
                              class="cek-item paket-widal"
                              <?= in_array(96, $selected_jenis ?? []) ? 'checked' : '' ?>>
                          </td>
                          <td>Salmonella Paratyphi CH</td>
                        </tr>
                      </tbody>
                    </table>
                  </div>

                </div>
              </div>
            </div>
          </div>


          <!-- ==================== TOKSIKOLOGI ==================== -->
          <div class="col-md-4 mb-4">
            <table class="table table-bordered table-md">
              <thead>
                <tr>
                  <th colspan="4" class="text-center bg-light">Toksikologi</th>
                </tr>
                <tr>
                  <th style="width:50px;"></th>
                  <th>Pemeriksaan</th>
                </tr>
              </thead>
              <tbody>

                <!-- Paket Narkoba 6 Parameter -->
                <tr class="table-secondary">
                  <td class="text-center">
                    <input type="checkbox" id="paket_narkoba" class="cek-paket" data-target="narkoba"
                      <?= in_array('paket_narkoba', $selected_jenis ?? []) ? 'checked' : '' ?>>
                  </td>
                  <td><strong>Narkoba 6 Parameter</strong></td>
                </tr>

                <tr class="sub-narkoba">
                  <td class="text-center">
                    <input type="checkbox" name="jenis_pemeriksaan[]"
                      value="76|Morphin|Narkoba 6 Parameter|Toksikologi"
                      <?= in_array(76, $selected_jenis ?? []) ? 'checked' : '' ?>>
                  </td>
                  <td>Morphin</td>
                </tr>

                <tr class="sub-narkoba">
                  <td class="text-center">
                    <input type="checkbox" name="jenis_pemeriksaan[]"
                      value="77|Cocaine|Narkoba 6 Parameter|Toksikologi"
                      <?= in_array(77, $selected_jenis ?? []) ? 'checked' : '' ?>>
                  </td>
                  <td>Cocaine</td>
                </tr>

                <tr class="sub-narkoba">
                  <td class="text-center">
                    <input type="checkbox" name="jenis_pemeriksaan[]"
                      value="78|Amphetamin|Narkoba 6 Parameter|Toksikologi"
                      <?= in_array(78, $selected_jenis ?? []) ? 'checked' : '' ?>>
                  </td>
                  <td>Amphetamin</td>
                </tr>

                <tr class="sub-narkoba">
                  <td class="text-center">
                    <input type="checkbox" name="jenis_pemeriksaan[]"
                      value="79|Metamphetamine|Narkoba 6 Parameter|Toksikologi"
                      <?= in_array(79, $selected_jenis ?? []) ? 'checked' : '' ?>>
                  </td>
                  <td>Metamphetamine</td>
                </tr>

                <tr class="sub-narkoba">
                  <td class="text-center">
                    <input type="checkbox" name="jenis_pemeriksaan[]"
                      value="80|Benzodiazepine|Narkoba 6 Parameter|Toksikologi"
                      <?= in_array(80, $selected_jenis ?? []) ? 'checked' : '' ?>>
                  </td>
                  <td>Benzodiazepine</td>
                </tr>

                <tr class="sub-narkoba">
                  <td class="text-center">
                    <input type="checkbox" name="jenis_pemeriksaan[]"
                      value="81|Marijuana|Narkoba 6 Parameter|Toksikologi"
                      <?= in_array(81, $selected_jenis ?? []) ? 'checked' : '' ?>>
                  </td>
                  <td>Marijuana</td>
                </tr>

              </tbody>
            </table>
          </div>

          <!-- LAIN-LAIN -->
          <div class="col-md-12 mb-4">
            <table class="table table-bordered table-md">
              <thead>
                <tr>
                  <th class="text-center bg-light">Lain-lain</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>
                    <div class="mb-3">
                      <textarea name="informasi_lain" class="form-control form-control-sm" rows="4" placeholder="Keterangan pemeriksaan lain"><?= isset($form) ? $form->informasi_lain : '' ?></textarea>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
        <hr class="my-4">
        <div class="row">
          <!-- Kelayakan, Volume, Biaya -->
          <div class="col-md-6">
            <div class="card shadow mb-4 border border-dark">
              <div class="card-header bg-secondary text-white fw-bold mb-0" style="font-weight: 800">
                Kelayakan Sampel
              </div>
              <div class="card-body">

                <!-- Kelayakan Sampel -->
                <div class="mb-3">
                  <label><strong>Kelayakan Sampel</strong></label><br>

                  <?php
                  $kelayakan_sampel = isset($form->kelayakan) ? $form->kelayakan : 'Belum Diperiksa';
                  $alasan_terpilih = isset($form->alasan_tidak_layak) ? explode(', ', $form->alasan_tidak_layak) : [];
                  ?>

                  <!-- Belum Diperiksa -->
                  <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio"
                      name="kelayakan" id="belum_diperiksa" value="Belum Diperiksa"
                      <?= (empty($kelayakan_sampel) || $kelayakan_sampel == 'Belum Diperiksa') ? 'checked' : '' ?>
                      onclick="toggleAlasan(false)">
                    <label class="form-check-label" for="belum_diperiksa">Belum Diperiksa</label>
                  </div>

                  <!-- Layak -->
                  <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio"
                      name="kelayakan" id="layak" value="Layak"
                      <?= ($kelayakan_sampel == 'Layak') ? 'checked' : '' ?>
                      onclick="toggleAlasan(false)">
                    <label class="form-check-label" for="layak">Layak</label>
                  </div>

                  <!-- Tidak Layak -->
                  <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio"
                      name="kelayakan" id="tidak_layak" value="Tidak Layak"
                      <?= ($kelayakan_sampel == 'Tidak Layak') ? 'checked' : '' ?>
                      onclick="toggleAlasan(true)">
                    <label class="form-check-label" for="tidak_layak">Tidak Layak</label>
                  </div>

                  <!-- Alasan Ketidaklayakan -->
                  <div id="alasanBox" class="mt-3" style="display: <?= ($kelayakan_sampel == 'Tidak Layak') ? 'block' : 'none' ?>;">
                    <label><strong>Alasan Ketidaklayakan:</strong></label><br>

                    <?php
                    $alasan = [
                      'Hemolisis',
                      'Bahan baku tidak segar',
                      'Darah beku',
                      'Bahan tidak sesuai permintaan',
                      'Tidak steril',
                      'Tidak sesuai',
                      'Volume tidak mencukupi'
                    ];

                    foreach ($alasan as $a):
                      $checked = in_array($a, $alasan_terpilih) ? 'checked' : '';
                    ?>
                      <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="alasan_tidak_layak[]" value="<?= $a ?>" <?= $checked ?>>
                        <label class="form-check-label"><?= $a ?></label>
                      </div>
                    <?php endforeach; ?>
                  </div>
                </div>

                <script>
                  function toggleAlasan(show) {
                    document.getElementById('alasanBox').style.display = show ? 'block' : 'none';
                  }
                </script>

                <!-- Volume Sampel -->
                <div class="mb-3">
                  <label for="volume_sampel_kaji_ulang"><strong>Volume Sampel</strong> (misal: 5 ml)</label>
                  <input
                    type="text"
                    name="volume_sampel_kaji_ulang"
                    id="volume_sampel_kaji_ulang"
                    class="form-control"
                    placeholder="Masukkan volume sampel"
                    value="<?= isset($form->volume_sampel_kaji_ulang) ? $form->volume_sampel_kaji_ulang : '' ?>">
                  <label class="form-text">Diisi Petugas Pendaftaran <span class="text-danger">*</span></label>
                  </small>
                </div>
              </div>
            </div>
          </div>

          <!-- Metode Pembayaran -->
          <div class="col-md-6">
            <div class="card shadow mb-4 border border-dark">
              <div class="card-header bg-secondary text-white fw-bold mb-0" style="font-weight: 800">
                Metode Pembayaran
              </div>
              <div class="card-body">

                <!-- Jumlah Biaya -->
                <div class="mb-3">
                  <label for="jumlah_biaya"><strong>Jumlah Biaya</strong></label>
                  <div class="input-group">
                    <span class="input-group-text">Rp</span>
                    <input
                      type="number"
                      class="form-control"
                      name="jumlah_biaya"
                      id="jumlah_biaya"
                      placeholder="Masukkan jumlah biaya"
                      value="<?= isset($form->jumlah_biaya) ? htmlspecialchars($form->jumlah_biaya) : '' ?>">
                  </div>
                </div>

                <!-- Metode Pembayaran -->
                <div class="mb-3">
                  <label><strong>Metode Pembayaran</strong></label><br>

                  <div class="form-check form-check-inline">
                    <input
                      class="form-check-input"
                      type="radio"
                      name="metode_pembayaran"
                      id="cash"
                      value="Cash"
                      <?= (isset($form->metode_pembayaran) && $form->metode_pembayaran == 'Cash') ? 'checked' : '' ?>>
                    <label class="form-check-label" for="cash">Cash / Tunai</label>
                  </div>

                  <div class="form-check form-check-inline">
                    <input
                      class="form-check-input"
                      type="radio"
                      name="metode_pembayaran"
                      id="bpjs"
                      value="BPJS"
                      <?= (isset($form->metode_pembayaran) && $form->metode_pembayaran == 'BPJS') ? 'checked' : '' ?>>
                    <label class="form-check-label" for="bpjs">BPJS</label>
                  </div>

                  <div class="form-check form-check-inline">
                    <input
                      class="form-check-input"
                      type="radio"
                      name="metode_pembayaran"
                      id="lainnya"
                      value="Lain-lain"
                      <?= (isset($form->metode_pembayaran) && !in_array($form->metode_pembayaran, ['Cash', 'BPJS', '', null])) ? 'checked' : '' ?>>
                    <label class="form-check-label" for="lainnya">Lain-lain</label>
                  </div>

                  <!-- Input metode lain -->
                  <input
                    type="text"
                    name="metode_lainnya"
                    id="metode_lainnya"
                    class="form-control mt-2"
                    placeholder="Tuliskan metode lain"
                    value="<?= (isset($form->metode_pembayaran) && !in_array($form->metode_pembayaran, ['Cash', 'BPJS', '', null])) ? htmlspecialchars($form->metode_pembayaran) : '' ?>"
                    style="<?= (isset($form->metode_pembayaran) && !in_array($form->metode_pembayaran, ['Cash', 'BPJS', '', null])) ? '' : 'display: none;' ?>">
                </div>
                <label class="form-text">Diisi Petugas Pendaftaran <span class="text-danger">*</span></label>
              </div>
            </div>
          </div>
        </div>
        <!-- Petugas Pendaftaran -->
        <div class="mb-3">
          <label class="fw-semibold">Petugas Pendaftaran</label>
          <select name="petugas_pendaftaran" id="petugas_pendaftaran" class="form-control form-control-sm">
            <option value="">-- Pilih Petugas Pendaftaran --</option>
            <?php foreach ($daftar_petugas as $pt): ?>
              <option value="<?= $pt->nama_petugas ?>"
                <?= isset($form->petugas_pendaftaran) && $form->petugas_pendaftaran == $pt->nama_petugas ? 'selected' : '' ?>>
                <?= $pt->nama_petugas ?><?= $pt->jabatan ? " ({$pt->jabatan})" : "" ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <!-- Petugas Pengambil Sampel -->
        <div class="mb-3">
          <label class="fw-semibold">Petugas Pengambil Sampel</label>
          <select name="petugas_pengambil" id="petugas_pengambil" class="form-control form-control-sm">
            <option value="">-- Pilih Petugas Pengambil Sampel --</option>
            <?php foreach ($daftar_petugas as $pt): ?>
              <option value="<?= $pt->nama_petugas ?>"
                <?= isset($form->petugas_pengambil) && $form->petugas_pengambil == $pt->nama_petugas ? 'selected' : '' ?>>
                <?= $pt->nama_petugas ?><?= $pt->jabatan ? " ({$pt->jabatan})" : "" ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <!-- Verifikasi -->
        <div class="mb-3">
          <label class="fw-semibold">Petugas Verifikasi</label>
          <?php if (!empty($daftar_verifikasi)):
            $vf = $daftar_verifikasi[0]; // Ambil data pertama
          ?>
            <input type="hidden" name="petugas_verifikasi" value="<?= $vf->nama_petugas ?>">
            <input type="text" class="form-control form-control-sm" value="<?= $vf->nama_petugas ?><?= $vf->jabatan ? " ({$vf->jabatan})" : "" ?>" readonly>
          <?php else: ?>
            <input type="text" class="form-control form-control-sm" value="-" readonly>
          <?php endif; ?>
        </div>

        <!-- Petugas Validasi -->
        <div class="mb-3">
          <label class="fw-semibold">Petugas Validasi</label>

          <?php if (!empty($daftar_validasi)):
            $vd = $daftar_validasi[0]; // otomatis ambil petugas pertama
          ?>
            <input type="hidden" name="petugas_validasi" value="<?= $vd->nama_petugas ?>">
            <input type="text" class="form-control form-control-sm"
              value="<?= $vd->nama_petugas ?><?= $vd->jabatan ? " ({$vd->jabatan})" : "" ?>"
              readonly>
          <?php else: ?>
            <input type="text" class="form-control form-control-sm" value="-" readonly>
          <?php endif; ?>
        </div>
        <div class="text-end">
          <button type="submit" class="btn btn-primary px-5 py-2 ms-2">
            <i class="fas fa-save me-2"></i> <?= isset($form) ? 'Update Data' : 'Simpan Data' ?>
          </button>
          <a href="<?= site_url('klinik/form_permintaan_klinik') ?>" class="btn btn-secondary">Kembali</a>
        </div>
      </div>
    </div>
    <?php $this->load->view('klinik/form_permintaan_klinik/modal_input_hasil'); ?>
    <!-- Script untuk toggle input lain-lain -->
    <script>
      document.addEventListener("DOMContentLoaded", function() {
        const lainRadio = document.getElementById('lainnya');
        const inputLain = document.getElementById('metode_lainnya');
        const radios = document.querySelectorAll('input[name="metode_pembayaran"]');

        radios.forEach(r => {
          r.addEventListener('change', function() {
            if (lainRadio.checked) {
              inputLain.style.display = 'block';
            } else {
              inputLain.style.display = 'none';
              inputLain.value = '';
            }
          });
        });
      });
    </script>
    <script>
      document.addEventListener("DOMContentLoaded", function() {
        const tglLahirInput = document.getElementById("tgl_lahir");
        const umurInput = document.getElementById("umur");

        function hitungUmur(tglLahir) {
          const today = new Date();
          const lahir = new Date(tglLahir);
          let umur = today.getFullYear() - lahir.getFullYear();
          const m = today.getMonth() - lahir.getMonth();
          if (m < 0 || (m === 0 && today.getDate() < lahir.getDate())) {
            umur--;
          }
          return umur;
        }

        // Jalankan saat tanggal lahir diubah
        tglLahirInput.addEventListener("change", function() {
          if (this.value) {
            const umur = hitungUmur(this.value);
            umurInput.value = umur + " thn";
          } else {
            umurInput.value = "";
          }
        });

        // Jalankan juga saat halaman pertama kali dimuat (kalau ada nilai awal)
        if (tglLahirInput.value) {
          const umur = hitungUmur(tglLahirInput.value);
          umurInput.value = umur + " thn";
        }
      });
    </script>

    <script>
      function toggleAlasan(show) {
        const alasanBox = document.getElementById('alasanBox');
        if (show) {
          alasanBox.style.display = 'block';
        } else {
          alasanBox.style.display = 'none';

          // Reset semua checkbox ketika kembali ke "Layak"
          const checkboxes = alasanBox.querySelectorAll('input[type="checkbox"]');
          checkboxes.forEach(cb => cb.checked = false);
        }
      }
    </script>
    <script>
      function bukaModalHasil() {
        $.ajax({
          url: "<?= site_url('klinik/form_permintaan_klinik/modal_input_hasil') ?>",
          method: "GET",
          success: function(response) {
            $('#modalContainer').html(response);
            $('#modalInputHasil').modal('show');
          },
          error: function(xhr, status, error) {
            alert("Gagal memuat modal: " + error);
          }
        });
      }
    </script>

    <!-- Script -->
    <script>
      // 🔹 Untuk sembunyikan / tampilkan tombol berdasarkan checkbox individual
      document.querySelectorAll('.cek-item').forEach(function(checkbox) {
        checkbox.addEventListener('change', function() {
          const btnId = this.getAttribute('data-btn');
          const btn = document.getElementById(btnId);
          if (btn) {
            btn.style.display = this.checked ? 'inline-block' : 'none';
          }
        });
      });

      // 🔹 Logika paket rutin
      document.getElementById('cek_paket_rutin').addEventListener('change', function() {
        const isChecked = this.checked;
        document.querySelectorAll('.paket-rutin').forEach(function(item) {
          item.checked = isChecked;
          const btnId = item.getAttribute('data-btn');
          const btn = document.getElementById(btnId);
          if (btn) {
            btn.style.display = isChecked ? 'inline-block' : 'none';
          }
        });
      });
    </script>

    <script>
      // 🔹 Tampilkan atau sembunyikan tombol Input untuk tiap item
      document.querySelectorAll('.cek-item').forEach(function(checkbox) {
        checkbox.addEventListener('change', function() {
          const btnId = this.getAttribute('data-btn');
          const btn = document.getElementById(btnId);
          if (btn) btn.style.display = this.checked ? 'inline-block' : 'none';
        });
      });

      // 🔹 Logika otomatis untuk semua paket (bisa tambah paket lain nanti)
      document.querySelectorAll('.cek-paket').forEach(function(paket) {
        paket.addEventListener('change', function() {
          const target = this.getAttribute('data-target');
          const subItems = document.querySelectorAll('.' + target);
          subItems.forEach(function(item) {
            item.checked = paket.checked;
            const btnId = item.getAttribute('data-btn');
            const btn = document.getElementById(btnId);
            if (btn) btn.style.display = paket.checked ? 'inline-block' : 'none';
          });
        });
      });
    </script>


    <script>
      document.addEventListener('DOMContentLoaded', function() {
        const paketCheckbox = document.querySelector('.cek-paket[data-target="hitungjenis"]');
        const subHitungJenis = document.querySelectorAll('.paket-hitungjenis');

        // Ketika checkbox utama diklik
        paketCheckbox.addEventListener('change', function() {
          if (this.checked) {
            subHitungJenis.forEach(chk => {
              chk.checked = true;
              const btnId = chk.getAttribute('data-btn');
              if (btnId) document.getElementById(btnId).style.display = 'inline-block';
            });
          } else {
            subHitungJenis.forEach(chk => {
              chk.checked = false;
              const btnId = chk.getAttribute('data-btn');
              if (btnId) document.getElementById(btnId).style.display = 'none';
            });
          }
        });

        // Saat sub-item diubah
        subHitungJenis.forEach(chk => {
          chk.addEventListener('change', function() {
            const btnId = this.getAttribute('data-btn');

            // tampilkan atau sembunyikan tombol input
            if (this.checked) {
              document.getElementById(btnId).style.display = 'inline-block';
            } else {
              document.getElementById(btnId).style.display = 'none';
            }

            // kalau semua sub-item tidak dicentang, otomatis matikan paket utama
            const semuaTidakDicentang = Array.from(subHitungJenis).every(i => !i.checked);
            if (semuaTidakDicentang) paketCheckbox.checked = false;

            // kalau semua sub-item dicentang, otomatis hidupkan paket utama
            const semuaDicentang = Array.from(subHitungJenis).every(i => i.checked);
            if (semuaDicentang) paketCheckbox.checked = true;
          });
        });
      });
    </script>

    <script>
      $(document).ready(function() {
        // Ketika checkbox paket utama dicentang
        $('.cek-paket').on('change', function() {
          const target = $(this).data('target'); // contoh: 'hitungjenis'
          const isChecked = $(this).is(':checked');

          // Semua checkbox sub-item yang termasuk paket tersebut
          $('.sub-' + target + ' .cek-item').prop('checked', isChecked);
        });

        // Jika salah satu sub-item diubah manual, update status paket utama
        $('.cek-item').on('change', function() {
          const paketClass = $(this).attr('class').split(' ').find(c => c.startsWith('paket-'));
          if (paketClass) {
            const target = paketClass.replace('paket-', '');
            const all = $('.sub-' + target + ' .cek-item').length;
            const checked = $('.sub-' + target + ' .cek-item:checked').length;

            // Kalau semua sub-item dicentang → paket ikut dicentang
            // Kalau ada yang belum → paket dimatikan
            $('#cek_paket_' + target).prop('checked', all === checked);
          }
        });
      });
    </script>

    <script>
      document.addEventListener("DOMContentLoaded", function() {
        // Saat checkbox paket utama di klik
        document.querySelectorAll(".cek-paket").forEach(function(paket) {
          paket.addEventListener("change", function() {
            const target = this.getAttribute("data-target"); // contoh: widal, pemeriksaan_lainnya
            const isChecked = this.checked;

            // Ambil semua checkbox sub-item yang punya class sesuai target
            const subItems = document.querySelectorAll(".sub-" + target + " input[type='checkbox']");
            subItems.forEach(cb => cb.checked = isChecked);
          });
        });
      });
    </script>

    <script>
      document.getElementById('btn_cari_rm').addEventListener('click', function() {
        let keyword = document.getElementById('keyword_rm').value;
        if (keyword.length < 2) {
          alert("Masukkan minimal 2 karakter untuk pencarian.");
          return;
        }

        let btnCari = this;
        let originalText = btnCari.innerHTML;
        btnCari.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Mencari...';
        btnCari.disabled = true;

        fetch("<?= site_url('klinik/form_permintaan_klinik/search_pasien_rm') ?>?keyword=" + encodeURIComponent(keyword))
          .then(response => response.json())
          .then(data => {
            let divHasil = document.getElementById('hasil_pencarian_rm');
            divHasil.style.display = 'block';

            if (data.length > 0) {
              let html = '<div class="table-responsive"><table class="table table-sm table-bordered table-hover"><thead><tr class="table-info text-center"><th>No RM</th><th>Nama</th><th>NIK</th><th>Gender</th><th>Tgl Lahir</th><th>Aksi</th></tr></thead><tbody>';
              data.forEach(p => {
                let nama_asli = p.nama_pasien || p.nama || '';
                let nama = nama_asli.replace(/'/g, "\\'");
                let p_json = JSON.stringify(p).replace(/'/g, "&#39;").replace(/"/g, "&quot;");

                html += `<tr>
                                  <td class="text-center">${p.no_rm || '-'}</td>
                                  <td>${nama_asli || '-'}</td>
                                  <td class="text-center">${p.nik || '-'}</td>
                                  <td class="text-center">${p.gender || '-'}</td>
                                  <td class="text-center">${p.tgl_lahir || '-'}</td>
                                  <td class="text-center"><button type="button" class="btn btn-sm btn-success" onclick="pilihPasienRM('${p_json}')"><i class="fas fa-check"></i> Pilih</button></td>
                              </tr>`;
              });
              html += '</tbody></table></div>';
              divHasil.innerHTML = html;
            } else {
              divHasil.innerHTML = `<div class="alert alert-warning mb-0 d-flex justify-content-between align-items-center">
                              <div><i class="fas fa-exclamation-triangle"></i> Data pasien tidak ditemukan di Rekam Medis.</div>
                              <a href="<?= site_url('klinik/form_permintaan_rm') ?>" class="btn btn-sm btn-danger"><i class="fas fa-plus"></i> Pendaftaran Rekam Medis Baru</a>
                          </div>`;
            }
          })
          .catch(err => {
            console.error(err);
            alert("Terjadi kesalahan saat mencari data.");
          })
          .finally(() => {
            btnCari.innerHTML = originalText;
            btnCari.disabled = false;
          });
      });

      function pilihPasienRM(p_json_str) {
        let p = JSON.parse(p_json_str.replace(/&#39;/g, "'").replace(/&quot;/g, '"'));

        if (document.getElementById('no_rm')) document.getElementById('no_rm').value = p.no_rm || '';
        if (document.querySelector('input[name="nama_pasien"]')) document.querySelector('input[name="nama_pasien"]').value = p.nama_pasien || p.nama || '';
        if (document.querySelector('input[name="nik"]')) document.querySelector('input[name="nik"]').value = p.nik || '';
        if (document.querySelector('select[name="gender"]')) document.querySelector('select[name="gender"]').value = p.gender || '';
        if (document.querySelector('input[name="tgl_lahir"]')) document.querySelector('input[name="tgl_lahir"]').value = p.tgl_lahir || '';
        if (document.querySelector('textarea[name="alamat"]')) document.querySelector('textarea[name="alamat"]').value = p.alamat || '';
        if (typeof parseAlamatToDropdowns === 'function') parseAlamatToDropdowns();
        if (document.querySelector('input[name="no_telp"]')) document.querySelector('input[name="no_telp"]').value = p.no_telp || '';
        if (document.querySelector('select[name="agama"]')) document.querySelector('select[name="agama"]').value = p.agama || '';
        if (document.querySelector('select[name="status_nikah"]')) document.querySelector('select[name="status_nikah"]').value = p.status_nikah || '';
        if (document.querySelector('select[name="pendidikan"]')) document.querySelector('select[name="pendidikan"]').value = p.pendidikan || '';
        if (document.querySelector('input[name="pekerjaan"]')) document.querySelector('input[name="pekerjaan"]').value = p.pekerjaan || '';

        let tgl_lahir = p.tgl_lahir;
        if (tgl_lahir) {
          let dob = new Date(tgl_lahir);
          let today = new Date();
          let age = today.getFullYear() - dob.getFullYear();
          let m = today.getMonth() - dob.getMonth();
          if (m < 0 || (m === 0 && today.getDate() < dob.getDate())) {
            age--;
          }
          document.querySelector('input[name="umur"]').value = age + " thn";
        }

        document.getElementById('hasil_pencarian_rm').style.display = 'none';
        document.getElementById('keyword_rm').value = '';
        alert("Data pasien berhasil dimasukkan ke form.");
      }

      document.addEventListener("DOMContentLoaded", function() {
        var nikInput = document.getElementById('nik');
        if (nikInput) {
          nikInput.addEventListener('blur', function() {
            var nik = this.value;
            if (nik.length >= 5) {
              // Cek jika menambah data baru (tidak ada id pasien di form update)
              <?php if (!isset($form)): ?>
                fetch("<?= site_url('klinik/form_permintaan_rm/cari_pasien') ?>", {
                    method: 'POST',
                    headers: {
                      'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'nik=' + nik
                  })
                  .then(response => response.json())
                  .then(res => {
                    if (res.status === 'success') {
                      var p = res.data;
                      if (document.getElementById('no_rm')) document.getElementById('no_rm').value = p.no_rm || '';
                      document.querySelector('input[name="nama_pasien"]').value = p.nama_pasien || p.nama || '';

                      if (document.querySelector('input[name="tgl_lahir"]')) document.querySelector('input[name="tgl_lahir"]').value = p.tgl_lahir || '';
                      if (document.querySelector('input[name="umur"]')) document.querySelector('input[name="umur"]').value = p.umur || '';
                      if (document.querySelector('select[name="gender"]')) document.querySelector('select[name="gender"]').value = p.gender || '';
                      if (document.querySelector('textarea[name="alamat"]')) document.querySelector('textarea[name="alamat"]').value = p.alamat_pasien || p.alamat || '';
                      if (typeof parseAlamatToDropdowns === 'function') parseAlamatToDropdowns();
                      if (document.querySelector('input[name="no_telp"]')) document.querySelector('input[name="no_telp"]').value = p.no_telp_pasien || p.no_telp || '';
                      if (document.querySelector('select[name="agama"]')) document.querySelector('select[name="agama"]').value = p.agama || '';
                      if (document.querySelector('select[name="status_nikah"]')) document.querySelector('select[name="status_nikah"]').value = p.status_nikah || '';
                      if (document.querySelector('select[name="pendidikan"]')) document.querySelector('select[name="pendidikan"]').value = p.pendidikan || '';
                      if (document.querySelector('input[name="pekerjaan"]')) document.querySelector('input[name="pekerjaan"]').value = p.pekerjaan || '';

                      // Optional: Memberi feedback ke user
                      alert('Data Pasien Lama Ditemukan. Formulir otomatis diisi.');
                    }
                  })
                  .catch(e => console.log('Error cari pasien', e));
              <?php endif; ?>
            }
          });
        }
        initWilayah();
      });

      <?php
      $CI = &get_instance();
      $CI->load->database();
      $db_wilayah = $CI->db->select('kecamatan, kelurahan_list')->get('puskesmas_wilayah')->result();
      $wilayah_map = [];
      foreach ($db_wilayah as $w_row) {
        $kec = trim($w_row->kecamatan);
        $kels = array_filter(array_map('trim', explode(',', $w_row->kelurahan_list)));
        if (!isset($wilayah_map[$kec])) {
          $wilayah_map[$kec] = [];
        }
        foreach ($kels as $k) {
          if ($k !== '' && !in_array($k, $wilayah_map[$kec])) {
            $wilayah_map[$kec][] = $k;
          }
        }
      }
      ksort($wilayah_map);
      foreach ($wilayah_map as $kec => &$kels) {
        sort($kels);
      }
      unset($kels);
      ?>
      const dataWilayah = <?= json_encode($wilayah_map) ?>;

      function initWilayah() {
        const kecSelect = document.getElementById('kecamatan_select');
        if (!kecSelect) return;
        for (let kec in dataWilayah) {
          let opt = document.createElement('option');
          opt.value = kec;
          opt.textContent = kec;
          kecSelect.appendChild(opt);
        }
        parseAlamatToDropdowns();
      }

      function parseAlamatToDropdowns() {
        let alamatEl = document.getElementById('alamat_lengkap');
        if (!alamatEl) return;
        let alamatLengkap = alamatEl.value;
        const kecSelect = document.getElementById('kecamatan_select');
        const kelSelect = document.getElementById('kelurahan_select');
        const detailJalan = document.getElementById('detail_jalan');

        if (alamatLengkap) {
          let foundKec = "";
          let foundKel = "";
          for (let kec in dataWilayah) {
            if (alamatLengkap.includes("Kec. " + kec)) {
              foundKec = kec;
              break;
            }
          }
          if (foundKec) {
            kecSelect.value = foundKec;
            updateKelurahan();
            dataWilayah[foundKec].forEach(kel => {
              if (alamatLengkap.includes("Kel. " + kel)) {
                foundKel = kel;
              }
            });
            if (foundKel) {
              kelSelect.value = foundKel;
            }
            let detail = alamatLengkap.replace(", Kel. " + foundKel, "").replace(", Kec. " + foundKec, "");
            detailJalan.value = detail.trim();
            updateAlamatLengkap();
          } else {
            detailJalan.value = alamatLengkap.trim();
          }
        }
      }

      function updateKelurahan() {
        const kecSelect = document.getElementById('kecamatan_select');
        const kelSelect = document.getElementById('kelurahan_select');
        kelSelect.innerHTML = '<option value="">Kelurahan</option>';

        let kec = kecSelect.value;
        if (kec && dataWilayah[kec]) {
          dataWilayah[kec].forEach(kel => {
            let opt = document.createElement('option');
            opt.value = kel;
            opt.textContent = kel;
            kelSelect.appendChild(opt);
          });
        }
        updateAlamatLengkap();
      }

      function updateAlamatLengkap() {
        let kec = document.getElementById('kecamatan_select').value;
        let kel = document.getElementById('kelurahan_select').value;
        let detail = document.getElementById('detail_jalan').value;

        let hasil = [];
        if (detail.trim() !== '') hasil.push(detail.trim());
        if (kel !== '') hasil.push("Kel. " + kel);
        if (kec !== '') hasil.push("Kec. " + kec);

        document.getElementById('alamat_lengkap').value = hasil.join(", ");
      }
    </script>
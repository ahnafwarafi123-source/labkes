<div class="content-wrapper px-4 pt-4">
  <h3 class="fw-bold text-center mb-4"><?= isset($is_edit) && $is_edit ? 'EDIT HASIL PEMERIKSAAN LABORATORIUM' : 'INPUT HASIL PEMERIKSAAN LABORATORIUM' ?></h3>

  <div class="row g-3 mb-3">
    <!-- Card Identitas Pasien -->
    <div class="col-md-4">
      <div class="card h-100 border border-dark shadow-sm">
        <div class="card-header bg-secondary text-white fw-bold text-center fs-5 py-2">IDENTITAS PASIEN</div>
        <div class="card-body p-3 bg-white">
          <table class="table table-borderless align-middle mb-0 fs-6 text-dark">
            <tbody>
              <tr>
                <td class="fw-semibold text-secondary"><strong>No. Register</strong></td>
                <td>: <?= $form->no_register ?></td>
              </tr>
              <tr>
                <td class="fw-semibold text-secondary"><strong>Nama Pasien</strong></td>
                <td>: <?= $form->nama_pasien ?></td>
              </tr>
              <tr>
                <td class="fw-semibold text-secondary"><strong>NIK</strong></td>
                <td>: <?= $form->nik ?></td>
              </tr>
              <tr>
                <td class="fw-semibold text-secondary"><strong>Jenis Kelamin</strong></td>
                <td>: <?= $form->gender ?></td>
              </tr>
              <tr>
                <td class="fw-semibold text-secondary"><strong>Tgl Lahir / Umur</strong></td>
                <td>: <?= $form->tgl_lahir ?> / <?= $form->umur ?></td>
              </tr>
              <tr>
                <td class="fw-semibold text-secondary"><strong>No. Telp</strong></td>
                <td>: <?= isset($form->no_telp) ? $form->no_telp : '' ?></td>
              </tr>
              <tr>
                <td class="fw-semibold text-secondary"><strong>Alamat</strong></td>
                <td>: <?= nl2br(isset($form->alamat) ? $form->alamat : '') ?></td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- Card Identitas Pengirim -->
    <div class="col-md-4">
      <div class="card h-100 border border-dark shadow-sm">
        <div class="card-header bg-secondary text-white fw-bold text-center fs-5 py-2">IDENTITAS PENGIRIM</div>
        <div class="card-body p-3 bg-white">
          <table class="table table-borderless align-middle mb-0 fs-6 text-dark">
            <tbody>
              <tr>
                <td class="fw-semibold text-secondary"><strong>Nama Dokter</strong></td>
                <td>: <?= $form->nama_dokter ?></td>
              </tr>
              <tr>
                <td class="fw-semibold text-secondary"><strong>No. Telp Dokter</strong></td>
                <td>: <?= $form->telp_pengirim ?></td>
              </tr>
              <tr>
                <td class="fw-semibold text-secondary"><strong>Alamat Dokter</strong></td>
                <td>: <?= nl2br($form->alamat_pengirim) ?></td>
              </tr>
              <tr>
                <td class="fw-semibold text-secondary"><strong>Tanggal Form</strong></td>
                <td>: <?= $form->tgl_form ?></td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- Card Diagnosa Klinis -->
    <div class="col-md-4">
      <div class="card h-100 border border-dark shadow-sm">
        <div class="card-header bg-secondary text-white fw-bold text-center fs-5 py-2">DIAGNOSA KLINIS</div>
        <div class="card-body p-3 bg-white">
          <table class="table table-borderless align-middle mb-0 fs-6 text-dark">
            <tbody>
              <tr>
                <td class="fw-semibold text-secondary"><strong>Diagnosa / Keterangan Klinik</strong></td>
                <td>: <?= nl2br($form->diagnosa_klinis) ?></td>
              </tr>
              <tr>
                <td class="fw-semibold text-secondary"><strong>Obat yang Dikonsumsi</strong></td>
                <td>: <?= nl2br($form->obat_dikonsumsi) ?></td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <!-- Card Data Spesimen -->
  <div class="card shadow mb-4 border border-dark">
    <div class="card-header bg-secondary text-white text-center fw-bold fs-5 py-2">DATA SPESIMEN</div>
    <div class="card-body fs-6 bg-white">
      <div class="row mb-3">
        <div class="col-md-6"><span class="fw-semibold text-secondary">Asal Sampel</span>
          <div class="border rounded px-3 py-2 bg-light"><?= $form->asal_sampel ?></div>
        </div>
        <div class="col-md-6">
          <span class="fw-semibold text-secondary">Status Puasa</span>
          <div class="border rounded px-3 py-2 bg-light">
            <?= $form->puasa ?>
            <?php if (!empty($form->keterangan_puasa)): ?>
              <span> - <?= $form->keterangan_puasa ?></span>
            <?php endif; ?>
          </div>
        </div>
      </div>
      <div class="mb-3"><span class="fw-semibold text-secondary">Lokasi Pengambilan</span>
        <div class="border rounded px-3 py-2 bg-light"><?= $form->lokasi_pengambilan ?><?= $form->lokasi_lainnya ? ', ' . $form->lokasi_lainnya : '' ?></div>
      </div>
      <div class="mb-3"><span class="fw-semibold text-secondary">Jenis Spesimen</span>
        <div class="border rounded px-3 py-2 bg-light"><?= $form->jenis_spesimen ?><?= $form->spesimen_lainnya ? ', ' . $form->spesimen_lainnya : '' ?></div>
      </div>
      <div class="row mb-3">
        <div class="col-md-4"><span class="fw-semibold text-secondary">Tanggal Permintaan</span>
          <div class="border rounded px-3 py-2 bg-light"><?= $form->tgl_permintaan ?></div>
        </div>
        <div class="col-md-4"><span class="fw-semibold text-secondary">Tanggal Pengambilan</span>
          <div class="border rounded px-3 py-2 bg-light"><?= $form->tgl_pengambilan ?></div>
        </div>
        <div class="col-md-4"><span class="fw-semibold text-secondary">Jam Pengambilan</span>
          <div class="border rounded px-3 py-2 bg-light"><?= $form->jam_pengambilan ?></div>
        </div>
      </div>
      <div class="row mb-3">
        <div class="col-md-6"><span class="fw-semibold text-secondary">Volume Spesimen</span>
          <div class="border rounded px-3 py-2 bg-light"><?= $form->volume_spesimen ?></div>
        </div>
        <div class="col-md-6"><span class="fw-semibold text-secondary">Prioritas</span>
          <div class="border rounded px-3 py-2 bg-light"><?= $form->prioritas ?></div>
        </div>
      </div>
      <div class="mb-2"><span class="fw-semibold text-secondary">Informasi Tambahan</span>
        <div class="border rounded px-3 py-2 bg-light"><?= nl2br($form->info_tambahan) ?></div>
      </div>
    </div>
  </div>

  <!-- Card Daftar Pemeriksaan -->
  <div class="card shadow p-4 border border-dark mb-5">
    <h5 class="fw-bold mb-3 text-center text-secondary">DAFTAR PEMERIKSAAN</h5>
    <form method="post" action="<?= site_url('klinik/uji_klinik/simpan_hasil') ?>">
      <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>" />
      <input type="hidden" name="id_form" value="<?= $form->id ?>">
      <input type="hidden" name="id_pasien" value="<?= $id_pasien ?>">

      <table class="table table-bordered table-striped align-middle">
        <thead class="table-secondary text-center">
          <tr>
            <th>No</th>
            <th>Nama Pemeriksaan</th>
            <th width="20%">Hasil</th>
            <th width="15%">Satuan</th>
            <th width="20%">Nilai Rujukan</th>
            <th width="20%">Metode</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $no = 1;
          $kategori_sebelumnya = "";
          $sub_sebelumnya = "";
          ?>
          <?php foreach ($detail as $d): ?>
            <!-- HEADER KATEGORI -->
            <?php if ($kategori_sebelumnya != $d->kategori): ?>
              <tr class="table-primary">
                <td colspan="6" class="fw-bold text-uppercase">
                  <?= $d->kategori ?>
                </td>
              </tr>

              <?php
              $kategori_sebelumnya = $d->kategori;
              $sub_sebelumnya = "";
              ?>
            <?php endif; ?>

            <!-- HEADER SUBKATEGORI -->
            <?php if ($sub_sebelumnya != $d->sub_kategori): ?>
              <tr class="table-info">
                <td colspan="6" class="fw-bold ps-4 text-uppercase">
                  <?= $d->sub_kategori ?>
                </td>
              </tr>

              <?php $sub_sebelumnya = $d->sub_kategori; ?>
            <?php endif; ?>

            <!-- DATA PEMERIKSAAN -->
            <tr>
              <td class="text-center"><?= $no++ ?></td>
              <td>
                <?= $d->nama_jenis ?>
              </td>
              <td>
                <input type="text"
                  name="hasil[<?= $d->id_detail ?>]"
                  class="form-control form-control-sm hasil-input"
                  value="<?= $d->hasil ?? '' ?>"
                  data-rujukan="<?= html_escape($d->nilai_rujukan) ?>"
                  data-nama_jenis="<?= html_escape($d->nama_jenis) ?>">
              </td>
              <td>
                <input type="text"
                  name="satuan[<?= $d->id_detail ?>]"
                  class="form-control form-control-sm bg-light"
                  value="<?= $d->satuan ?>"
                  readonly>
              </td>
              <td>
                <input type="text"
                  name="nilai_rujukan[<?= $d->id_detail ?>]"
                  class="form-control form-control-sm bg-light"
                  value="<?= $d->nilai_rujukan ?>"
                  readonly>
              </td>
              <td>
                <input type="text"
                  name="metode[<?= $d->id_detail ?>]"
                  class="form-control form-control-sm bg-light"
                  value="<?= $d->metode ?>"
                  readonly>
              </td>
            </tr>
          <?php endforeach; ?>
          <script>
            document.addEventListener("DOMContentLoaded", function() {

              const hasilInputs = document.querySelectorAll('.hasil-input');

              hasilInputs.forEach(function(input) {

                input.addEventListener('input', function() {

                  let hasil = parseFloat(this.value);
                  let rujukan = this.dataset.rujukan;

                  // Reset style
                  this.style.fontWeight = 'normal';
                  this.style.color = '';

                  // Cek format contoh: 4 - 10
                  if (rujukan.includes('-')) {

                    let split = rujukan.split('-');

                    let min = parseFloat(split[0]);
                    let max = parseFloat(split[1]);

                    if (!isNaN(hasil) && (hasil < min || hasil > max)) {

                      this.style.fontWeight = 'bold';
                      this.style.color = 'red';
                    }
                  }

                });

              });

            });
          </script>
        </tbody>
      </table>

      <div class="card shadow mb-4 border border-dark">
        <div class="col-md-6 mb-4">
          <div class="card border border-dark shadow-sm h-100">
            <div class="card-header bg-secondary text-white text-center fw-bold fs-5 py-2">
              KELAYAKAN SAMPEL
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

              <!-- Volume Sampel -->
              <div class="mb-3">
                <label for="volume_sampel_kaji_ulang"><strong>Volume Sampel Kaji Ulang</strong> (misal: 5 ml)</label>
                <input
                  type="text"
                  name="volume_sampel_kaji_ulang"
                  id="volume_sampel_kaji_ulang"
                  class="form-control form-control-sm"
                  placeholder="Masukkan volume sampel"
                  value="<?= isset($form->volume_sampel_kaji_ulang) ? html_escape($form->volume_sampel_kaji_ulang) : '' ?>">
              </div>
            </div>
          </div>
        </div>

        <script>
          function toggleAlasan(show) {
            document.getElementById('alasanBox').style.display = show ? 'block' : 'none';
          }
        </script>

        <div class="card-header bg-secondary text-white text-center fw-bold fs-5 py-2">
          CATATAN & PETUGAS LABORATORIUM
        </div>
        <div class="card-body bg-white fs-6">
          <!-- Note -->
          <div class="mb-3">
            <label class="fw-semibold">Catatan / Note</label>
            <textarea name="note" class="form-control" rows="2"
              placeholder="Isi catatan (boleh dikosongkan)"><?= isset($pengambilan->note) ? html_escape($pengambilan->note) : '' ?></textarea>
          </div>

          <!-- Petugas Hasil -->
          <div class="mb-3">
            <label class="fw-semibold">Petugas</label>
            <select name="petugas_hasil" id="petugas_hasil" class="form-control form-control-sm" required>
              <option value="">-- Pilih Petugas --</option>
              <?php foreach ($daftar_petugas as $pt): ?>
                <option value="<?= $pt->nama_petugas ?>"
                  <?= isset($pengambilan->petugas_hasil) && $pengambilan->petugas_hasil == $pt->nama_petugas ? 'selected' : '' ?>>
                  <?= $pt->nama_petugas ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>

          <!-- Verifikator -->
          <div class="mb-3">
            <label class="fw-semibold">Verifikator</label>
            <?php if (!empty($daftar_verifikator)):
              $vf = $daftar_verifikator[0]; // Ambil data pertama
            ?>
              <input type="hidden" name="verifikator" value="<?= isset($pengambilan->verifikator_hasil) ? html_escape($pengambilan->verifikator_hasil) : html_escape($vf->nama_petugas) ?>">
              <input type="text" class="form-control form-control-sm" value="<?= isset($pengambilan->verifikator_hasil) ? html_escape($pengambilan->verifikator_hasil) : html_escape($vf->nama_petugas) ?>" readonly>
            <?php else: ?>
              <input type="text" class="form-control form-control-sm" value="-" readonly>
            <?php endif; ?>
          </div>

          <!-- Penanggung Jawab Teknis -->
          <div class="mb-3">
            <label class="fw-semibold">Penanggung Jawab Teknis</label>
            <?php if (!empty($daftar_penanggung_teknis)):
              $pt = $daftar_penanggung_teknis[0]; // Ambil data pertama
            ?>
              <input type="hidden" name="penanggung_jawab_teknis" value="<?= isset($pengambilan->penanggung_jawab_teknis) ? html_escape($pengambilan->penanggung_jawab_teknis) : $pt->nama_petugas ?>">
              <input type="text" class="form-control form-control-sm" value="<?= isset($pengambilan->penanggung_jawab_teknis) ? html_escape($pengambilan->penanggung_jawab_teknis) : ($pt->nama_petugas . ($pt->jabatan ? " ({$pt->jabatan})" : "")) ?>" readonly>
            <?php else: ?>
              <input type="text" class="form-control form-control-sm" value="-" readonly>
            <?php endif; ?>
          </div>

          <div class="mb-3">
            <label class="fw-semibold">Nomor SIP</label>
            <input type="text" name="sip_penanggung" id="sip_penanggung" class="form-control form-control-sm"
              value="<?= isset($pengambilan->sip_penanggung) ? html_escape($pengambilan->sip_penanggung) : (isset($pt->sip) ? html_escape($pt->sip) : '') ?>" readonly>
          </div>
          <div class="card shadow p-4 border border-dark mb-5">
            <div class="card shadow mb-4 border border-dark">
              <div class="card-header bg-secondary text-white text-center fw-bold fs-5 py-2">
                INFORMASI PENGAMBILAN & PEMERIKSAAN
              </div>
              <div class="card-body bg-white fs-6">
                <div class="mb-3">
                  <label class="fw-semibold">Petugas Pengambilan Spesimen</label>
                  <select name="id_petugas" class="form-control form-control-sm" required>
                    <option value="">-- Pilih Petugas --</option>
                    <?php foreach ($daftar_petugas as $p): ?>
                      <option value="<?= $p->id_petugas ?>" <?= isset($pengambilan->id_petugas) && $pengambilan->id_petugas == $p->id_petugas ? 'selected' : '' ?>>
                        <?= $p->nama_petugas ?>
                      </option>
                    <?php endforeach; ?>
                  </select>
                </div>
                <div class="mb-3">
                  <label class="fw-semibold">
                    Tanggal / Jam Pengambilan
                  </label>
                  <input type="hidden" name="tgl_jam_pengambilan" value="<?= $form->tgl_pengambilan . ' ' . $form->jam_pengambilan ?>">
                  <input type="text"
                    class="form-control form-control-sm"
                    value="<?= date('d-m-Y', strtotime($form->tgl_pengambilan)) . ' / ' . date('H:i', strtotime($form->jam_pengambilan)) ?>"
                    readonly>
                </div>
                <div class="mb-3">
                  <label class="fw-semibold">Tanggal / Jam Pemeriksaan</label>
                  <input type="datetime-local" name="tgl_jam_pemeriksaan" class="form-control form-control-sm" value="<?= isset($pengambilan->tgl_jam_pemeriksaan) ? date('Y-m-d\TH:i', strtotime($pengambilan->tgl_jam_pemeriksaan)) : '' ?>">
                </div>
                <div class="mb-3">
                  <label class="fw-semibold">Tanggal / Jam Selesai Pemeriksaan</label>
                  <input type="datetime-local" name="tgl_jam_selesai" class="form-control form-control-sm" value="<?= isset($pengambilan->tgl_jam_selesai) ? date('Y-m-d\TH:i', strtotime($pengambilan->tgl_jam_selesai)) : '' ?>">
                </div>
                <div class="mb-3">
                  <label class="fw-semibold">Tanggal / Jam Pelaporan Hasil</label>
                  <input type="datetime-local" name="tgl_jam_pelaporan" class="form-control form-control-sm" value="<?= isset($pengambilan->tgl_jam_pelaporan) ? date('Y-m-d\TH:i', strtotime($pengambilan->tgl_jam_pelaporan)) : '' ?>">
                </div>
              </div>
            </div>
            <div class="text-end mt-3">
              <button type="submit" class="btn btn-primary px-4">Simpan Hasil</button>
              <a href="<?= site_url('klinik/uji_klinik') ?>" class="btn btn-secondary px-4">Kembali</a>
            </div>
          </div>
        </div>
    </form>
  </div>
</div>

<!-- JS -->
<script>
  document.addEventListener("DOMContentLoaded", function() {
    const selectTeknis = document.getElementById("penanggung_jawab_teknis");
    const sipInput = document.getElementById("sip_penanggung");

    selectTeknis.addEventListener("change", function() {
      const selectedOption = selectTeknis.options[selectTeknis.selectedIndex];
      const sip = selectedOption.getAttribute("data-sip") || "";
      sipInput.value = sip;
    });
  });
</script>
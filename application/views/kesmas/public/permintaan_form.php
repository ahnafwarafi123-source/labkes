<?php
// Defensive defaults
if (!isset($permintaan) || !is_array($permintaan)) $permintaan = [];
if (!isset($petugas) || !is_array($petugas)) $petugas = [];
if (!isset($tindakan_sampel) || !is_array($tindakan_sampel)) $tindakan_sampel = [];

$action = site_url('kesmas/public_pendaftaran/store');
?>

<style>
  .ui-polish-container {
    padding: 0;
    max-width: 1200px;
    margin: 0 auto;
    animation: fadeInUp 0.5s ease-out;
  }

  .page-hero {
    border-radius: 24px;
    border: 1px solid rgba(255, 255, 255, .8);
    background:
      radial-gradient(900px 340px at 12% 10%, rgba(13, 110, 253, .20), transparent 62%),
      radial-gradient(900px 340px at 92% 20%, rgba(34, 197, 94, .10), transparent 62%),
      radial-gradient(900px 340px at 55% 110%, rgba(99, 102, 241, .12), transparent 62%),
      linear-gradient(180deg, rgba(255, 255, 255, .95), rgba(255, 255, 255, .80));
    box-shadow: var(--shadow);
    padding: 1.25rem 1.35rem;
    position: relative;
    overflow: hidden;
    margin-bottom: 1rem;
    backdrop-filter: blur(10px);
  }

  .page-hero h3 {
    margin: 0;
    font-weight: 900;
    letter-spacing: -.5px;
    color: var(--ink);
    font-size: 1.75rem;
  }

  .page-hero .sub {
    color: var(--muted);
    margin-top: .25rem;
  }

  .card-wow {
    border-radius: 24px;
    border: 1px solid rgba(255, 255, 255, .6);
    background: rgba(255, 255, 255, .85);
    backdrop-filter: blur(16px);
    box-shadow: var(--shadow2);
    overflow: hidden;
    height: 100%;
  }

  .card-wow .card-header {
    background: transparent;
    border-bottom: 1px solid rgba(15, 23, 42, .05);
    padding: 1.2rem 1.25rem 0.75rem;
  }

  .card-wow .card-body {
    padding: 1.25rem;
  }

  .section-title {
    display: flex;
    align-items: center;
    gap: .6rem;
    font-weight: 800;
    letter-spacing: -.3px;
    color: var(--ink);
    margin: 0;
    font-size: 1.15rem;
  }

  .section-title i {
    color: var(--brand);
    font-size: 1.25rem;
  }

  .form-label {
    font-weight: 700;
    color: #334155;
    font-size: 0.9rem;
    margin-bottom: 0.35rem;
  }

  .form-control,
  .form-select {
    border-radius: 14px;
    border-color: rgba(15, 23, 42, .10);
    padding: .75rem 1rem;
    background: rgba(255, 255, 255, .9);
    font-size: 0.95rem;
    transition: all 0.2s ease;
  }

  .form-control:focus,
  .form-select:focus {
    border-color: var(--brand);
    box-shadow: 0 0 0 4px rgba(13, 110, 253, .15);
    background: #fff;
  }

  .divider-soft {
    height: 1px;
    background: rgba(15, 23, 42, .08);
    margin: 1.5rem 0;
  }

  .tabs-wow {
    border-radius: 16px;
    border: 1px solid rgba(15, 23, 42, .05);
    background: rgba(248, 250, 252, .7);
    padding: .4rem;
    gap: .25rem;
  }

  .tabs-wow .nav-link {
    border: 1px solid transparent;
    border-radius: 12px;
    font-weight: 700;
    color: #475569;
    padding: .6rem .85rem;
    font-size: 0.9rem;
  }

  .tabs-wow .nav-link:hover {
    background: rgba(13, 110, 253, .05);
    color: var(--brand);
  }

  .tabs-wow .nav-link.active {
    background: #fff;
    color: var(--brand);
    box-shadow: 0 4px 12px rgba(0, 0, 0, .05);
    border-color: rgba(15, 23, 42, .05);
  }

  .tab-shell {
    margin-top: 1rem;
    border-radius: 18px;
    border: 1px solid rgba(15, 23, 42, .05);
    background: transparent;
    padding: 0.5rem 0;
  }

  .group-head {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: .75rem;
    margin-bottom: .75rem;
    background: rgba(248, 250, 252, .8);
    padding: 0.5rem 1rem;
    border-radius: 12px;
  }

  .group-title {
    font-weight: 800;
    color: #1e293b;
    font-size: 0.95rem;
  }

  .btn-mini {
    border-radius: 10px;
    font-weight: 700;
    padding: .3rem .6rem;
    font-size: 0.8rem;
  }

  .check-tile {
    border-radius: 14px;
    border: 1px solid rgba(15, 23, 42, .08);
    background: #fff;
    padding: .75rem .85rem;
    transition: all .2s ease;
  }

  .check-tile:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(13, 110, 253, .08);
    border-color: rgba(13, 110, 253, .3);
  }

  .check-tile .form-check-input {
    cursor: pointer;
    margin-top: 0.15rem;
    width: 1.1em;
    height: 1.1em;
    border-color: rgba(15, 23, 42, .2);
  }

  .check-tile .form-check-input:checked {
    background-color: var(--brand);
    border-color: var(--brand);
  }

  .check-tile .form-check-label {
    cursor: pointer;
    font-weight: 600;
    color: #334155;
    font-size: 0.9rem;
    user-select: none;
  }

  .sticky-save {
    position: sticky;
    bottom: .75rem;
    z-index: 2;
    border-radius: 22px;
    border: 1px solid rgba(255, 255, 255, .8);
    background: rgba(255, 255, 255, .85);
    backdrop-filter: blur(16px);
    box-shadow: 0 20px 40px rgba(0, 0, 0, .1);
    padding: 1rem 1.25rem;
    margin-top: 2rem;
  }

  .btn-save {
    border-radius: 14px;
    font-weight: 800;
    letter-spacing: .3px;
    padding: .8rem 1.5rem;
    border: none;
    background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);
    box-shadow: 0 8px 20px rgba(13, 110, 253, .25);
    color: #fff;
    transition: all 0.2s ease;
  }

  .btn-save:hover {
    transform: translateY(-2px);
    box-shadow: 0 12px 25px rgba(13, 110, 253, .35);
    color: #fff;
  }
</style>

<div class="container-fluid ui-polish-container">

  <div class="page-hero">
    <div class="d-flex align-items-start justify-content-between gap-3 flex-wrap">
      <div>
        <h3>Pendaftaran Pengujian Sampel</h3>
        <div class="sub">Silakan isi formulir di bawah ini dengan lengkap dan benar.</div>
      </div>
    </div>
  </div>

  <form method="post" action="<?= $action ?>">
    <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">

    <div class="row g-3">
      <!-- Detail Sampel (Kiri Atas) -->
      <div class="col-lg-6">
        <div class="card card-wow h-100">
          <div class="card-header">
            <div class="section-title">
              <i class="bi bi-droplet-half" aria-hidden="true"></i>
              <span>1. Detail Sampel</span>
            </div>
          </div>
          <div class="card-body">
            <div class="row g-2">
              <input type="hidden" name="no_registrasi" value="<?= $no_registrasi ?>">

              <div class="col-md-6">
                <label class="form-label">Kategori Sampel</label>
                <select class="form-select" name="id_kategori_sampel" id="kategori_sampel_select" required style="cursor: pointer;">
                  <option value="">- pilih kategori -</option>
                  <option value="Air">Air</option>
                  <option value="Makanan">Makanan</option>
                  <option value="Lingkungan">Lingkungan</option>
                </select>
              </div>

              <div class="col-md-6">
                <label class="form-label">Nama Sampel</label>
                <input class="form-control" name="nama_sampel" required placeholder="Contoh: Air Sumur Bor">
              </div>

              <div class="col-md-6">
                <label class="form-label">Jenis Sampel</label>
                <select class="form-select" name="jenis_sampel" id="jenis_sampel_select" required style="cursor: pointer;">
                  <option value="">- pilih -</option>
                  <?php foreach (['Air Minum', 'Air Bersih', 'Makanan', 'Lingkungan'] as $opt): ?>
                    <option value="<?= $opt ?>"><?= $opt ?></option>
                  <?php endforeach; ?>
                  <option value="__lainnya__">Lainnya (isi manual)</option>
                </select>
                <input class="form-control mt-2" type="text" id="jenis_sampel_manual" name="jenis_sampel_manual"
                  placeholder="Tulis jenis sampel..."
                  style="display: none;">
              </div>

              <div class="col-md-4">
                <label class="form-label">Volume (ml)</label>
                <input class="form-control" type="number" name="volume_ml" placeholder="Cth: 500">
              </div>
              <div class="col-md-4">
                <label class="form-label">Tanggal Pengambilan</label>
                <input class="form-control" type="date" name="tgl_pengambilan" style="cursor: pointer;">
              </div>
              <div class="col-md-4">
                <label class="form-label">Jam Pengambilan</label>
                <input class="form-control" type="time" name="jam_pengambilan" style="cursor: pointer;">
              </div>

              <div class="col-md-12">
                <label class="form-label">Lokasi Pengambilan (Alamat detail)</label>
                <input class="form-control" name="lokasi_pengambilan" placeholder="Jalan, RT/RW, Kelurahan, Kecamatan">
              </div>

              <div class="col-md-12">
                <label class="form-label">Informasi Tambahan</label>
                <textarea class="form-control" name="info_tambahan" rows="2" placeholder="Catatan khusus terkait sampel (opsional)"></textarea>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Identitas Pengirim (Kanan Atas) -->
      <div class="col-lg-6">
        <div class="card card-wow h-100">
          <div class="card-header">
            <div class="section-title">
              <i class="bi bi-person-vcard" aria-hidden="true"></i>
              <span>2. Identitas Pengirim</span>
            </div>
          </div>
          <div class="card-body">
            <div class="row g-2">
              <div class="col-md-6">
                <label class="form-label">NIK</label>
                <input class="form-control" name="nik" placeholder="Nomor Induk Kependudukan">
              </div>
              <div class="col-md-6">
                <label class="form-label">Nama Lengkap</label>
                <input class="form-control" name="nama" required placeholder="Sesuai KTP/Identitas">
              </div>
              <div class="col-md-6">
                <label class="form-label">No. WhatsApp / HP</label>
                <input class="form-control" name="telp" required placeholder="08xxxxxxxxxx">
              </div>
              <div class="col-md-12">
                <label class="form-label">Alamat</label>
                <input class="form-control" name="alamat">
              </div>
              <div class="col-md-6">
                <label class="form-label">Instansi / Perusahaan</label>
                <input class="form-control" name="instansi" placeholder="Isi jika mewakili instansi">
              </div>
              <div class="col-md-6">
                <label class="form-label">Tanggal Permintaan</label>
                <input class="form-control bg-light" type="date" name="tgl_permintaan" value="<?= date('Y-m-d') ?>" readonly>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Pemeriksaan -->
      <div class="col-lg-12">
        <div class="card card-wow mt-2">
          <div class="card-header">
            <div class="section-title">
              <i class="bi bi-list-check" aria-hidden="true"></i>
              <span>3. Pemeriksaan (Checklist Parameter)</span>
            </div>
          </div>
          <div class="card-body">

            <ul class="nav nav-pills tabs-wow" role="tablist" id="checklistTabs" style="display: none;">
              <li class="nav-item" id="nav-item-airminum">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-airminum" type="button">
                  <i class="bi bi-droplet-fill text-primary me-1" aria-hidden="true"></i>Air Minum
                </button>
              </li>
              <li class="nav-item" id="nav-item-airbersih">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-airbersih" type="button">
                  <i class="bi bi-water text-info me-1" aria-hidden="true"></i>Air Bersih
                </button>
              </li>
              <li class="nav-item" id="nav-item-makanan">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-makanan" type="button">
                  <i class="bi bi-basket2-fill text-warning me-1" aria-hidden="true"></i>Makanan
                </button>
              </li>
              <li class="nav-item" id="nav-item-lingkungan">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-lingkungan" type="button">
                  <i class="bi bi-tree-fill text-success me-1" aria-hidden="true"></i>Lingkungan
                </button>
              </li>
            </ul>

            <div class="tab-content tab-shell">
              <?php
              $selected = [];

              function render_group($groups, $selected, $category_slug)
              {
                $i = 0;
                foreach ($groups as $kelompok => $rows) {
                  $i++;
                  $cls = 'ui-polish-checkbox-' . $category_slug . '-g' . $i;
                  echo '<div class="mb-3">';
                  echo '  <div class="group-head">';
                  echo '    <div class="group-title">' . htmlspecialchars(kesmas_kelompok_title($category_slug, $kelompok)) . '</div>';
                  echo '    <div class="d-flex gap-2 flex-wrap">
                                <button type="button" class="btn btn-sm btn-light text-primary border btn-mini" data-check-all=".' . $cls . '">
                                  <i class="bi bi-check-all me-1" aria-hidden="true"></i>Pilih Semua
                                </button>
                                <button type="button" class="btn btn-sm btn-light text-secondary border btn-mini" data-uncheck-all=".' . $cls . '">
                                  <i class="bi bi-x me-1" aria-hidden="true"></i>Kosongkan
                                </button>
                              </div>';
                  echo '  </div>';

                  echo '  <div class="row g-2">';
                  foreach ($rows as $r) {
                    $id = (int)$r['id_master_pemeriksaan'];
                    echo '    <div class="col-lg-3 col-md-4 col-sm-6">
                                  <div class="check-tile h-100">
                                    <div class="form-check mb-0">
                                      <input class="form-check-input ' . $cls . '" type="checkbox" name="pemeriksaan[]" value="' . $id . '" id="pemeriksaan_' . $id . '">
                                      <label class="form-check-label d-block" for="pemeriksaan_' . $id . '">' . htmlspecialchars($r['nama_pemeriksaan'] ?? $r['nama']) . '</label>
                                    </div>
                                  </div>
                                </div>';
                  }
                  echo '  </div>';
                  echo '</div>';
                }
              }
              ?>

              <div class="tab-pane fade show active" id="tab-airminum">
                <?php render_group($master_air_minum, $selected, 'air_minum'); ?>
              </div>

              <div class="tab-pane fade" id="tab-airbersih">
                <?php render_group($master_air_bersih, $selected, 'air_bersih'); ?>
              </div>

              <div class="tab-pane fade" id="tab-makanan">
                <?php render_group($master_makanan, $selected, 'makanan'); ?>
              </div>

              <div class="tab-pane fade" id="tab-lingkungan">
                <?php render_group($master_lingkungan, $selected, 'lingkungan'); ?>
              </div>

            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Sticky save bar -->
    <div class="sticky-save d-flex align-items-center justify-content-between gap-3 flex-wrap">
      <div class="d-flex align-items-center gap-3">
        <div class="form-check">
          <input class="form-check-input mt-1" type="checkbox" name="disclaimer" id="disclaimerCheckbox" required style="width: 1.2em; height: 1.2em; border-color: var(--brand); cursor: pointer;">
          <label class="form-check-label text-muted small" for="disclaimerCheckbox" style="font-weight: 600; cursor: pointer; user-select: none; padding-top: 2px;">
            Saya menjamin data yang saya berikan adalah benar dan menyetujui Syarat & Ketentuan yang berlaku.
          </label>
        </div>
      </div>
      <div class="d-flex gap-2">
        <button type="submit" class="btn btn-save" id="btnSubmitForm">
          <i class="bi bi-send-check-fill me-2" aria-hidden="true"></i>Kirim Pendaftaran
        </button>
      </div>
    </div>

  </form>
</div>

<script>
  document.getElementById('formPermintaan')?.addEventListener('submit', function(e) {
    if (this.checkValidity()) {
      const btn = document.getElementById('btnSubmitForm');
      btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Memproses...';
      btn.classList.add('disabled');
    }
  });

  const kategoriSelect = document.getElementById('kategori_sampel_select');
  const jsSelect = document.getElementById('jenis_sampel_select');
  const jsManual = document.getElementById('jenis_sampel_manual');

  if (kategoriSelect && jsSelect) {
    kategoriSelect.addEventListener('change', function() {
      const kategori = this.value;
      jsSelect.innerHTML = '<option value="">- pilih -</option>';

      if (kategori === 'Air') {
        jsSelect.innerHTML += '<option value="Air Minum">Air Minum</option>';
        jsSelect.innerHTML += '<option value="Air Bersih">Air Bersih</option>';
      } else if (kategori === 'Makanan') {
        jsSelect.innerHTML += '<option value="Makanan">Makanan</option>';
      } else if (kategori === 'Lingkungan') {
        jsSelect.innerHTML += '<option value="Lingkungan">Lingkungan</option>';
      }

      jsSelect.innerHTML += '<option value="__lainnya__">Lainnya (isi manual)</option>';

      // Trigger change event to reset tabs and manual input
      jsSelect.dispatchEvent(new Event('change'));
    });
  }

  if (jsSelect && jsManual) {
    jsSelect.addEventListener('change', function() {
      if (this.value === '__lainnya__') {
        jsManual.style.display = 'block';
        jsManual.setAttribute('required', 'required');
      } else {
        jsManual.style.display = 'none';
        jsManual.removeAttribute('required');
      }
    });

    // Handle auto-tab switch by jenis sampel and disable/enable checkboxes
    function applyTabByJenis() {
      var val = jsSelect.value;

      // Disable and uncheck all checkboxes first
      document.querySelectorAll('.tab-shell .form-check-input').forEach(function(cb) {
        cb.checked = false;
        cb.disabled = true;
      });

      // Hide all nav-items and the tab container
      document.querySelectorAll('#checklistTabs .nav-item').forEach(function(el) {
        el.style.display = 'none';
      });
      document.getElementById('checklistTabs').style.display = 'none';

      // Hide all tab panes
      document.querySelectorAll('.tab-shell .tab-pane').forEach(function(pane) {
        pane.classList.remove('show', 'active');
        pane.style.display = 'none';
      });

      if (val === '') return;

      // Keyword mapping untuk jenis manual: arahkan tab aktif sesuai kategori
      if (val === '__lainnya__') {
        var manualText = jsManual && jsManual.value ? jsManual.value : '';
        var manualTabId = '';
        if (/(bersih|sanitasi|higiene)/i.test(manualText)) {
          manualTabId = '#tab-airbersih';
        } else if (/(minum|pdam|tirta)/i.test(manualText)) {
          manualTabId = '#tab-airminum';
        }
        if (manualTabId) {
          var manualPane = document.querySelector(manualTabId);
          if (manualPane) {
            manualPane.classList.add('show', 'active');
            manualPane.style.display = 'block';
          }
          document.querySelectorAll(manualTabId + ' .form-check-input').forEach(function(cb) {
            cb.disabled = false;
          });
        }
        return;
      }

      var targetTabId = '';

      if (val === 'Air Minum') {
        targetTabId = '#tab-airminum';
      } else if (val === 'Air Bersih') {
        targetTabId = '#tab-airbersih';
      } else if (val === 'Makanan') {
        targetTabId = '#tab-makanan';
      } else if (val === 'Lingkungan') {
        targetTabId = '#tab-lingkungan';
      }

      if (targetTabId) {
        // Show the corresponding tab pane directly
        var activePane = document.querySelector(targetTabId);
        if (activePane) {
          activePane.classList.add('show', 'active');
          activePane.style.display = 'block';
        }

        // Enable checkboxes ONLY in the active tab
        document.querySelectorAll(targetTabId + ' .form-check-input').forEach(function(cb) {
          cb.disabled = false;
        });
      }
    }

    jsSelect.addEventListener('change', applyTabByJenis);

    // Saat mengetik jenis manual, arahkan tab aktif sesuai keyword
    if (jsManual) {
      jsManual.addEventListener('input', function() {
        if (jsSelect.value === '__lainnya__') applyTabByJenis();
      });
    }
  }

  // Handle Pilih Semua / Kosongkan per grup
  document.querySelectorAll('[data-check-all]').forEach(function(btn) {
    btn.addEventListener('click', function(e) {
      e.preventDefault();
      var targetSelector = this.getAttribute('data-check-all');
      document.querySelectorAll(targetSelector).forEach(function(cb) {
        cb.checked = true;
      });
    });
  });

  document.querySelectorAll('[data-uncheck-all]').forEach(function(btn) {
    btn.addEventListener('click', function(e) {
      e.preventDefault();
      var targetSelector = this.getAttribute('data-uncheck-all');
      document.querySelectorAll(targetSelector).forEach(function(cb) {
        cb.checked = false;
      });
    });
  });

  // Handle NIK autofill
  const nikInput = document.querySelector('input[name="nik"]');
  if (nikInput) {
    nikInput.addEventListener('blur', function() {
      const nik = this.value.trim();
      if (nik.length > 5) {
        fetch('<?= site_url('kesmas/public_pendaftaran/check_nik') ?>', {
            method: 'POST',
            credentials: 'same-origin',
            headers: {
              'Content-Type': 'application/x-www-form-urlencoded',
              'X-Requested-With': 'XMLHttpRequest'
            },
            body: 'nik=' + encodeURIComponent(nik) + '&<?= $this->security->get_csrf_token_name() ?>=' + encodeURIComponent('<?= $this->security->get_csrf_hash() ?>')
          })
          .then(response => response.text())
          .then(text => {
            try {
              // Robust JSON extraction for free hosting (InfinityFree)
              let jsonText = text;
              const startIdx = text.indexOf('{');
              const endIdx = text.lastIndexOf('}');
              if (startIdx !== -1 && endIdx !== -1) {
                jsonText = text.substring(startIdx, endIdx + 1);
              }

              const res = JSON.parse(jsonText);
              if (res.status === 'success' && res.data) {
                const fName = document.querySelector('input[name="nama"]');
                const fAlamat = document.querySelector('input[name="alamat"]');
                const fTelp = document.querySelector('input[name="telp"]');
                const fInstansi = document.querySelector('input[name="instansi"]');

                if (fName && !fName.value) fName.value = res.data.nama;
                if (fAlamat && !fAlamat.value) fAlamat.value = res.data.alamat;
                if (fTelp && !fTelp.value) fTelp.value = res.data.telp;
                if (fInstansi && !fInstansi.value) fInstansi.value = res.data.instansi;
              } else if (res.status === 'not_found') {
                console.log('NIK tidak ditemukan di database.');
              } else {
                console.error('Response error:', res.message);
              }
            } catch (err) {
              console.error('Error parsing JSON. Raw response dari server:', text);
              let snippet = text.substring(0, 150).replace(/<\/?[^>]+(>|$)/g, ""); // strip HTML tags
              alert('Gagal mengecek NIK. Server merespons dengan:\n\n' + snippet);
            }
          })
          .catch(err => {
            console.error('Fetch error:', err);
            alert('Koneksi ke server gagal.');
          });
      }
    });
  }
</script>
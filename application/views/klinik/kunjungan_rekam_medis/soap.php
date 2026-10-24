:<div class="content-wrapper">
    <section class="content pt-4 pb-5">
        <div class="container-fluid">

            <div class="d-flex justify-content-start">
                <div class="col-md-8 col-12 d-flex justify-content-start">
                    <a href="<?= site_url('klinik/uji_rekam_medis') ?>"
                        class="btn btn-secondary text-white px-4 rounded-1 shadow-sm" style="font-weight: 500;">
                        <i class="fas fa-arrow-left me-1"></i> Back
                    </a>
                </div>
            </div>
            <br>
            <!-- Title Catatan SOAP instead of flashdata success -->
            <div class="d-flex align-items-center mb-4">
                <i class="fas fa-notes-medical fa-2x text-primary me-3"></i>
                <h4 class="mb-0 fw-bold text-dark">Input Hasil SOAP<span class="fw-normal text-muted"
                        style="font-size: 18px;">(Subjective, Objective, Assessment, Plan)</span></h4>
            </div>

            <form action="<?= site_url('klinik/uji_rekam_medis/simpan_soap') ?>" method="post">
                <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
                <input type="hidden" name="kunjungan_id" value="<?= $kunjungan->id ?>">
                <input type="hidden" name="id_pasien" value="<?= $pasien->id_pasien ?>">

                <!-- Administrasi Kunjungan -->
                <div class="card shadow-sm mb-4 border-0 rounded-3">
                    <div class="card-header text-white" style="background-color: #6c757d; border-radius: 6px 6px 0 0;">
                        <h6 class="mb-0 fw-bold"><i class="fas fa-calendar-alt me-2"></i> Data Kunjungan</h6>
                    </div>
                    <div class="card-body bg-white p-4">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-dark fw-bold" style="font-size: 14px;">Tanggal & Jam Kunjungan<span class="text-danger">*</span></label>
                                <?php
                                // Gunakan format 'Y-m-d\TH:i' agar sesuai dengan standar value input datetime-local
                                $tgl_visit = isset($kunjungan->tanggal_kunjungan) && !empty($kunjungan->tanggal_kunjungan) ? date('Y-m-d\TH:i', strtotime($kunjungan->tanggal_kunjungan)) : '';
                                ?>
                                <input type="datetime-local" class="form-control" name="tanggal_kunjungan"
                                    id="tanggal_kunjungan" value="<?= $tgl_visit ?>" required>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- CARD S: Subjective -->
                <div class="card shadow-sm mb-4 border-0 rounded-3">
                    <div class="card-header text-white" style="background-color: #0d6efd; border-radius: 6px 6px 0 0;">
                        <h6 class="mb-0 fw-bold"><i class="fas fa-comment-medical me-2"></i> S - Subjective (Anamnesis)
                        </h6>
                    </div>
                    <div class="card-body bg-white p-4">
                        <div class="mb-4">
                            <label class="form-label text-dark fw-bold" style="font-size: 14px;">Keluhan Utama <span
                                    class="text-danger">*</span></label>
                            <textarea class="form-control" name="keluhan_utama" rows="3"
                                placeholder="Keluhan utama secara berurutan..."
                                required><?= $kunjungan->keluhan_utama ?></textarea>
                        </div>
                        <div class="mb-2">
                            <label class="form-label text-dark fw-bold" style="font-size: 14px;">Riwayat Penyakit</label>
                            <textarea class="form-control" name="riwayat_penyakit" rows="3"
                                placeholder="Kronologi, durasi, faktor pemicu..."><?= $kunjungan->riwayat_penyakit ?></textarea>
                        </div>
                    </div>
                </div>

                <!-- CARD O: Objective -->
                <div class="card shadow-sm mb-4 border-0 rounded-3">
                    <div class="card-header text-white" style="background-color: #0dcaf0; border-radius: 6px 6px 0 0;">
                        <h6 class="mb-0 fw-bold"><i class="fas fa-stethoscope me-2"></i> O - Objective (Pemeriksaan
                            Fisik)</h6>
                    </div>
                    <div class="card-body bg-white p-4">

                        <div class="row mb-3">
                            <div class="col-md-6 mb-3 mb-md-0">
                                <label class="form-label text-dark fw-bold" style="font-size: 14px;">GCS (Glasgow Coma
                                    Scale)</label>
                                <input type="text" class="form-control" name="gcs" placeholder="e.g., 15/15"
                                    value="<?= $kunjungan->gcs ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-dark fw-bold" style="font-size: 14px;">Tekanan Darah
                                    Sistole dan Diastole (mmHg)</label>
                                <div class="input-group">
                                    <input type="number" class="form-control" name="tensi_sistole" placeholder="Sistole"
                                        value="<?= $kunjungan->tensi_sistole ?>">
                                    <span class="input-group-text bg-light border-start-0 border-end-0">/</span>
                                    <input type="number" class="form-control" name="tensi_diastole"
                                        placeholder="Diastole" value="<?= $kunjungan->tensi_diastole ?>">
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6 mb-3 mb-md-0">
                                <label class="form-label text-dark fw-bold" style="font-size: 14px;">Nadi
                                    (x/mnt)</label>
                                <input type="number" class="form-control" name="nadi" placeholder="e.g., 80"
                                    value="<?= $kunjungan->nadi ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-dark fw-bold" style="font-size: 14px;">Pernapasan
                                    (x/mnt)</label>
                                <input type="number" class="form-control" name="pernapasan" placeholder="e.g., 20"
                                    value="<?= $kunjungan->pernapasan ?>">
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-4 mb-3 mb-md-0">
                                <label class="form-label text-dark fw-bold" style="font-size: 14px;">Suhu (°C)</label>
                                <input type="number" step="0.1" class="form-control" name="suhu"
                                    placeholder="e.g., 36.5" value="<?= $kunjungan->suhu ?>">
                            </div>
                            <div class="col-md-4 mb-3 mb-md-0">
                                <label class="form-label text-dark fw-bold" style="font-size: 14px;">Berat Badan (kg)</label>
                                <input type="number" step="0.1" class="form-control" name="berat_badan"
                                    placeholder="e.g., 60.5" value="<?= $kunjungan->berat_badan ?>">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label text-dark fw-bold" style="font-size: 14px;">Tinggi Badan (cm)</label>
                                <input type="number" class="form-control" name="tinggi_badan"
                                    placeholder="e.g., 165" value="<?= $kunjungan->tinggi_badan ?>">
                            </div>
                        </div>

                        <hr class="mb-4">

                        <div class="mb-3">
                            <label class="form-label text-dark fw-bold" style="font-size: 14px;">Pemeriksaan
                                Jantung</label>
                            <textarea class="form-control" name="pemeriksaan_jantung" rows="2"
                                placeholder="Bunyi jantung, murmur, dll..."><?= $kunjungan->pemeriksaan_jantung ?></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-dark fw-bold" style="font-size: 14px;">Pemeriksaan
                                Paru-paru</label>
                            <textarea class="form-control" name="pemeriksaan_paru" rows="2"
                                placeholder="Suara paru, ronchi, wheezing, dll..."><?= $kunjungan->pemeriksaan_paru ?></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-dark fw-bold" style="font-size: 14px;">Pemeriksaan
                                Abdomen</label>
                            <textarea class="form-control" name="pemeriksaan_abdomen" rows="2"
                                placeholder="Bising usus, nyeri tekan, dll..."><?= $kunjungan->pemeriksaan_abdomen ?></textarea>
                        </div>
                        <div class="mb-2">
                            <label class="form-label text-dark fw-bold" style="font-size: 14px;">Catatan
                                Tambahan</label>
                            <textarea class="form-control" name="catatan_tambahan" rows="2"
                                placeholder="Catatan pemeriksaan kesehatan lainnya..."><?= $kunjungan->catatan_tambahan ?></textarea>
                        </div>

                    </div>
                </div>

                <!-- CARD A: Assessment -->
                <div class="card shadow-sm mb-4 border-0 rounded-3">
                    <div class="card-header text-dark" style="background-color: #ffc107; border-radius: 6px 6px 0 0;">
                        <h6 class="mb-0 fw-bold"><i class="fas fa-stethoscope me-2"></i> A - Assessment (Penilaian
                            Dokter)</h6>
                    </div>
                    <div class="card-body bg-white p-4">
                        <div class="mb-2">
                            <label class="form-label text-dark fw-bold" style="font-size: 14px;">Diagnosa Medis <span
                                    class="text-danger">*</span></label>
                            <textarea class="form-control" name="asesmen_diagnosa" rows="3"
                                placeholder="Interpretasi data objektif (O) & penyampaian kode ICD-10 klinis..."
                                required><?= $kunjungan->asesmen_diagnosa ?></textarea>
                        </div>
                    </div>
                </div>

                <!-- CARD P: Plan -->
                <div class="card shadow-sm mb-4 border-0 rounded-3">
                    <div class="card-header text-white" style="background-color: #198754; border-radius: 6px 6px 0 0;">
                        <h6 class="mb-0 fw-bold"><i class="fas fa-clipboard-list me-2"></i> P - Plan (Rencana Tindak
                            Lanjut)</h6>
                    </div>
                    <div class="card-body bg-white p-4">
                        <div class="mb-2">
                            <label class="form-label text-dark fw-bold" style="font-size: 14px;">Rencana Tindak
                                Lanjut</label>
                            <textarea class="form-control" name="plan_rencana" rows="4"
                                placeholder="Rencana pengobatan, rujukan, kontrol ulang, dll..."><?= $kunjungan->plan_rencana ?></textarea>
                        </div>
                    </div>
                </div>
                <!-- CARD ICD-10 -->
                <div class="card shadow-sm mb-4 border-0 rounded-3">
                    <div class="card-header text-white" style="background-color: #dc3545; border-radius: 6px 6px 0 0;">
                        <h6 class="mb-0 fw-bold"><i class="fas fa-book-medical me-2"></i> Diagnosis ICD-10
                            (Administratif)</h6>
                    </div>
                    <div class="card-body bg-white p-4">
                        <p class="text-muted mb-3" style="font-size: 13px;">Pilih diagnosis ICD-10 yang sesuai dengan
                            penilaian klinis di atas.</p>

                        <div class="input-group mb-2">
                            <input type="text" class="form-control bg-light border-primary" id="search-icd"
                                placeholder="Ketik kode atau nama diagnosis (cth: E11.9)...">
                            <button class="btn btn-primary px-4 fw-bold" type="button" onclick="openManualModal()"><i
                                    class="fas fa-plus"></i> Manual</button>
                        </div>

                        <!-- Checkbox Results Box -->
                        <div class="border rounded px-2 py-1 mb-4"
                            style="max-height: 200px; overflow-y: auto; display: none;" id="icd-results">
                            <?php
                            $existing_icds_array = explode('<br>', $kunjungan->icd_10);
                            $matched_db_icds = [];

                            if (isset($diagnosa) && !empty($diagnosa)): ?>
                                <?php
                                foreach ($diagnosa as $diag):
                                    $val = '[' . $diag->kode . '] ' . $diag->nama;
                                    $checked = (in_array($val, $existing_icds_array)) ? 'checked' : '';
                                    if ($checked)
                                        $matched_db_icds[] = $val;
                                ?>
                                    <div class="form-check icd-item border-bottom py-2">
                                        <input class="form-check-input icd-checkbox" type="checkbox" name="icd_10_arr[]"
                                            value="<?= htmlspecialchars($val) ?>" <?= $checked ?> id="icd_<?= $diag->id ?>">
                                        <label class="form-check-label text-dark" for="icd_<?= $diag->id ?>"
                                            style="font-size: 14px;"><?= htmlspecialchars($val) ?></label>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="text-muted py-2" style="font-size:13px;">Database diagnosa kosong.</div>
                            <?php endif; ?>
                        </div>

                        <!-- Manual input container (hidden form array elements) -->
                        <div id="icd-manual-inputs">
                            <?php
                            $manualCounter = 0;
                            foreach ($existing_icds_array as $ex) {
                                $ex = trim($ex);
                                if (!empty($ex) && $ex != '<i>Kosong</i>' && !in_array($ex, $matched_db_icds)) {
                                    echo '<input type="hidden" name="icd_10_arr[]" value="' . htmlspecialchars($ex) . '" class="manual-icd-hidden" id="manual_icd_' . $manualCounter . '">';
                                    $manualCounter++;
                                }
                            }
                            ?>
                        </div>

                        <!-- Selected Diagnosis Display -->
                        <label class="form-label text-dark fw-bold" style="font-size: 14px;">Diagnosis Terpilih:</label>
                        <div class="border rounded p-3 bg-light" id="selected-diagnosis-display"
                            style="min-height: 50px; font-size: 14px;">
                            <span class="text-muted">Belum ada diagnosis terpilih</span>
                        </div>
                    </div>
                </div>

                <!-- CARD Dokter Pemeriksa -->
                <div class="card shadow-sm mb-4 border-0 rounded-3">
                    <div class="card-header text-white" style="background-color: #6f42c1; border-radius: 6px 6px 0 0;">
                        <h6 class="mb-0 fw-bold"><i class="fas fa-user-md me-2"></i> Dokter Pemeriksa</h6>
                    </div>
                    <div class="card-body bg-white p-4">
                        <label class="form-label text-dark fw-bold" style="font-size: 14px;">Pilih Dokter yang Memeriksa <span class="text-danger">*</span></label>
                        <select name="nama_dokter_pemeriksa" class="form-select border-primary" required>
                            <option value="">-- Pilih Dokter Pemeriksa --</option>
                            <?php if (isset($dokters) && !empty($dokters)): ?>
                                <?php foreach ($dokters as $d): ?>
                                    <option value="<?= htmlspecialchars($d->nama_dokter) ?>" <?= (isset($kunjungan->nama_dokter_pemeriksa) && $kunjungan->nama_dokter_pemeriksa == $d->nama_dokter) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($d->nama_dokter) ?> - <?= htmlspecialchars($d->spesialisasi) ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <option value="" disabled>Data dokter belum tersedia. Silakan tambahkan di menu Data Dokter.</option>
                            <?php endif; ?>
                        </select>
                    </div>
                </div>

                <!-- Submit Button Area -->
                <div class="d-flex justify-content-end mb-5">
                    <?php if ($kunjungan->status_soap == 'Belum diisi'): ?>
                        <a href="<?= site_url('klinik/uji_rekam_medis/batal_kunjungan/' . $kunjungan->id . '/' . $pasien->id_pasien) ?>"
                            onclick="return confirm('Aksi ini akan membatalkan pengisian SOAP sekaligus MENGHAPUS pendaftaran Kunjungan ini dari riwayat. Yakin batal?')"
                            class="btn btn-secondary px-4 me-2 rounded-1">Batal</a>
                    <?php else: ?>
                        <a href="<?= site_url('klinik/uji_rekam_medis/detail/' . $pasien->id_pasien) ?>"
                            class="btn btn-secondary px-4 me-2 rounded-1">Batal</a>
                    <?php endif; ?>
                    <button type="submit" class="btn btn-primary px-4 rounded-1"><i class="fas fa-save me-1"></i> Simpan Catatan SOAP</button>
                </div>

            </form>
        </div>
    </section>
</div>

<!-- Modal Tambah Diagnosis Manual -->
<div class="modal fade" id="modalManualDiagnosis" tabindex="-1" aria-labelledby="modalManualDiagnosisLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-3 shadow">
            <div class="modal-header bg-white pb-0 border-bottom-0">
                <h5 class="modal-title text-dark" id="modalManualDiagnosisLabel" style="font-weight: 500;">Tambah
                    Diagnosis Manual</h5>
                <button type="button" class="btn-close text-secondary"
                    style="background: none; border: none; font-size: 1.5rem;" onclick="closeManualModal()"
                    aria-label="Close">&times;</button>
            </div>
            <div class="modal-body pt-3 pb-3">
                <div class="mb-3">
                    <label class="form-label text-dark" style="font-size: 14px;">Kode ICD-10</label>
                    <input type="text" class="form-control" id="manual_kode_icd" placeholder="">
                </div>
                <div class="mb-2">
                    <label class="form-label text-dark" style="font-size: 14px;">Nama Diagnosis</label>
                    <input type="text" class="form-control" id="manual_nama_icd" placeholder="">
                </div>
            </div>
            <div class="modal-footer bg-white border-top-0 pt-0">
                <button type="button" class="btn btn-secondary px-3" onclick="closeManualModal()"
                    style="border-radius: 6px;">Batal</button>
                <button type="button" class="btn btn-primary px-3" onclick="saveManualDiagnosis()"
                    style="border-radius: 6px;">Simpan</button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('search-icd');
        const resultsBox = document.getElementById('icd-results');
        const items = document.querySelectorAll('.icd-item');
        const checkboxes = document.querySelectorAll('.icd-checkbox');
        const displayBox = document.getElementById('selected-diagnosis-display');
        const manualInputsContainer = document.getElementById('icd-manual-inputs');

        let manualCounter = <?= isset($manualCounter) ? $manualCounter : 0 ?>;

        <?php if ($kunjungan->status_soap == 'Belum diisi'): ?>
            // Set device local time for new visits
            let dateInput = document.getElementById('tanggal_kunjungan');
            let now = new Date();
            now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
            dateInput.value = now.toISOString().slice(0, 10);
        <?php endif; ?>

        // Tampilkan/Sembunyikan hasil live search
        searchInput.addEventListener('keyup', function() {
            let filter = this.value.toLowerCase();
            if (filter.length > 0) {
                resultsBox.style.display = "block";
                items.forEach(function(item) {
                    let text = item.textContent || item.innerText;
                    if (text.toLowerCase().indexOf(filter) > -1) {
                        item.style.display = "";
                    } else {
                        item.style.display = "none";
                    }
                });
            } else {
                resultsBox.style.display = "none";
            }
        });

        // Tangkap klik di luar hasil pencarian untuk merapikan tampilan
        document.addEventListener('click', function(event) {
            const isClickInsideSearch = searchInput.contains(event.target);
            const isClickInsideResults = resultsBox.contains(event.target);
            const textL = searchInput.value.trim().length;

            if (!isClickInsideSearch && !isClickInsideResults && textL === 0) {
                resultsBox.style.display = 'none';
            }
        });

        searchInput.addEventListener('focus', function() {
            if (this.value.trim().length > 0) {
                resultsBox.style.display = "block";
            } else {
                // opsional: tampilkan semua saat di klik pertama? 
                // resultsBox.style.display = "block"; 
            }
        });

        // Update display saat dicentang
        checkboxes.forEach(function(cb) {
            cb.addEventListener('change', updateDisplay);
        });

        function removeManual(idTarget) {
            let el = document.getElementById(idTarget);
            if (el) {
                el.remove();
                updateDisplay();
            }
        }
        window.removeManual = removeManual;

        function removeCheckbox(cbId) {
            let cb = document.getElementById(cbId);
            if (cb) {
                cb.checked = false;
                updateDisplay();
            }
        }
        window.removeCheckbox = removeCheckbox;

        function updateDisplay() {
            let selectedList = [];

            // Masukan dari checkbox DB
            checkboxes.forEach(function(cb) {
                if (cb.checkedit || cb.checked) {
                    selectedList.push(`<li>${cb.value} <a href="javascript:void(0)" class="text-danger ms-2" style="text-decoration:none;" onclick="removeCheckbox('${cb.id}')"><i class="fas fa-times-circle"></i></a></li>`);
                }
            });

            // Masukkan dari input manual
            const manualHiddenInputs = document.querySelectorAll('.manual-icd-hidden');
            manualHiddenInputs.forEach(function(man) {
                let text = man.value;
                let id = man.id;
                selectedList.push(`<li>${text} <a href="javascript:void(0)" class="text-danger ms-2" style="text-decoration:none;" onclick="removeManual('${id}')"><i class="fas fa-times-circle"></i></a></li>`);
            });

            if (selectedList.length === 0) {
                displayBox.innerHTML = '<span class="text-muted">Belum ada diagnosis terpilih</span>';
            } else {
                let htmlOut = '<ul class="mb-0 ps-3">';
                selectedList.forEach(function(val) {
                    htmlOut += val;
                });
                htmlOut += '</ul>';
                displayBox.innerHTML = htmlOut;
            }
        }

        // Open Modal
        window.openManualModal = function() {
            let text = searchInput.value.trim();
            document.getElementById('manual_kode_icd').value = '';
            document.getElementById('manual_nama_icd').value = text; // Auto fill if they typed something

            // Render UI Modal compatibility safe trigger form jQuery or native BS5
            if (typeof bootstrap !== 'undefined') {
                var myModal = new bootstrap.Modal(document.getElementById('modalManualDiagnosis'));
                myModal.show();
            } else {
                $('#modalManualDiagnosis').modal('show');
            }
        }

        // Close Modal
        window.closeManualModal = function() {
            if (typeof bootstrap !== 'undefined') {
                var modalEl = document.getElementById('modalManualDiagnosis');
                var modal = bootstrap.Modal.getInstance(modalEl);
                if (modal) modal.hide();
            } else {
                $('#modalManualDiagnosis').modal('hide');
            }
        }

        // Add manual script from Modal and save to Database via AJAX
        window.saveManualDiagnosis = function() {
            let kode = document.getElementById('manual_kode_icd').value.trim();
            let nama = document.getElementById('manual_nama_icd').value.trim();

            if (nama.length === 0) {
                alert("Nama diagnosis wajib diisi!");
                return;
            }

            // AJAX request to store into diagnosa.sql
            $.ajax({
                url: "<?= site_url('klinik/uji_rekam_medis/simpan_diagnosa_ajax') ?>",
                type: "POST",
                data: {
                    kode: kode,
                    nama: nama,
                    '<?= $this->security->get_csrf_token_name(); ?>': '<?= $this->security->get_csrf_hash(); ?>'
                },
                dataType: "json",
                success: function(response) {
                    if (response.status === 'success') {
                        let val = '';
                        if (kode.length > 0) {
                            val = '[' + kode + '] ' + nama;
                        } else {
                            val = '[Manual] ' + nama;
                        }

                        // Create hidden input 
                        let hiddenData = document.createElement('input');
                        hiddenData.type = 'hidden';
                        hiddenData.name = 'icd_10_arr[]';
                        hiddenData.value = val;
                        hiddenData.className = 'manual-icd-hidden';
                        hiddenData.id = 'manual_icd_' + manualCounter;

                        manualInputsContainer.appendChild(hiddenData);

                        manualCounter++;
                        searchInput.value = '';

                        // Clear filter box
                        items.forEach(function(item) {
                            item.style.display = "";
                        });
                        resultsBox.style.display = "none";

                        updateDisplay();
                        closeManualModal();
                    } else {
                        alert("Gagal menyimpan diagnosis: " + response.message);
                    }
                },
                error: function() {
                    alert("Terjadi kesalahan jaringan atau server saat menyimpan diagnosis manual.");
                }
            });
        }

        // Panggil pada saat render form
        updateDisplay();
    });
</script>
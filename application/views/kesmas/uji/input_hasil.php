<?php
defined('BASEPATH') or exit('No direct script access allowed');
/**
 * View untuk input hasil pemeriksaan sampel
 * @var array $permintaan - Data permintaan sampel dengan fields: id, instansi, telp_pengirim, alamat_pengirim, lokasi_pengambilan, volume_ml
 * @var array $items - Array item/hasil pemeriksaan dengan fields: tgl_jam_pemeriksaan, tgl_jam_selesai, tgl_jam_lapor
 * @var array $petugas - Array petugas untuk dropdown
 */
?>

<?php $this->load->view('kesmas/premium_list_style'); ?>

<div class="page-hero mb-4">
    <div class="d-flex align-items-center justify-content-start gap-3">
        <a class="btn btn-outline-secondary btn-sm" href="<?= site_url('k-panel/uji') ?>" style="border-radius: 8px; padding: 0.3rem 0.6rem; font-weight: 600;">
            <i class="fa fa-arrow-left"></i> <span class="d-none d-md-inline">Kembali</span>
        </a>
        <h3 style="margin:0; font-size: 1.5rem;">Input Hasil Pemeriksaan</h3>
    </div>
</div>

<form method="post" action="<?= site_url('k-panel/form_permintaan_kesmas/simpan_hasil/' . $permintaan['id_permintaan']) ?>" id="formInputHasil" novalidate>
    <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">

    <div class="row">
        <!-- Card 1: IDENTITAS SAMPEL -->
        <div class="col-md-4">
            <div class="card mb-3 h-100">
                <div class="card-header">
                    <h5 class="mb-0">Identitas Sampel</h5>
                </div>
                <div class="card-body">
                    <div class="mb-2">
                        <label class="form-label mb-0 fw-bold">No. Registrasi:</label>
                        <input type="text" class="form-control form-control-sm" value="<?= htmlspecialchars($permintaan['no_registrasi'] ?? '') ?>" readonly>
                    </div>
                    <div class="mb-2">
                        <label class="form-label mb-0 fw-bold">Nama Sampel:</label>
                        <input type="text" class="form-control form-control-sm" value="<?= htmlspecialchars($permintaan['nama_sampel'] ?? '') ?>" readonly>
                    </div>
                    <div class="mb-2">
                        <label class="form-label mb-0 fw-bold">Kategori Sampel:</label>
                        <input type="text" class="form-control form-control-sm" value="<?= htmlspecialchars($permintaan['kategori_sampel'] ?? '-') ?>" readonly>
                    </div>
                    <div class="mb-2">
                        <label class="form-label mb-0 fw-bold">Jenis Sampel:</label>
                        <input type="text" class="form-control form-control-sm" value="<?= htmlspecialchars($permintaan['jenis_sampel'] ?? '') ?>" readonly>
                    </div>
                    <div class="mb-0">
                        <label class="form-label mb-0 fw-bold">Tanggal Form:</label>
                        <input type="text" class="form-control form-control-sm" value="<?= !empty($permintaan['tgl_permintaan']) ? date('d-m-Y', strtotime($permintaan['tgl_permintaan'])) : '' ?>" readonly>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 2: IDENTITAS PENGIRIM -->
        <div class="col-md-4">
            <div class="card mb-3 h-100">
                <div class="card-header">
                    <h5 class="mb-0">Identitas Pengirim</h5>
                </div>
                <div class="card-body">
                    <div class="mb-2">
                        <label class="form-label mb-0 fw-bold">Nama Pengirim:</label>
                        <input type="text" class="form-control form-control-sm" value="<?= htmlspecialchars($permintaan['nama'] ?? '') ?>" readonly>
                    </div>
                    <div class="mb-2">
                        <label class="form-label mb-0 fw-bold">Instansi:</label>
                        <input type="text" class="form-control form-control-sm" value="<?= htmlspecialchars($permintaan['instansi'] ?: '-') ?>" readonly>
                    </div>
                    <div class="mb-2">
                        <label class="form-label mb-0 fw-bold">No. Telp:</label>
                        <input type="text" class="form-control form-control-sm" value="<?= htmlspecialchars($permintaan['telp'] ?: '-') ?>" readonly>
                    </div>
                    <div class="mb-0">
                        <label class="form-label mb-0 fw-bold">Alamat:</label>
                        <textarea class="form-control form-control-sm" rows="3" readonly><?= htmlspecialchars($permintaan['alamat'] ?: '-') ?></textarea>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- CARD “DATA SAMPEL” (full width) -->
    <div class="card mb-3">
        <div class="card-header">
            <h5 class="mb-0">Data Sampel</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4 mb-2">
                    <label class="form-label mb-0 fw-bold">Lokasi Pengambilan:</label>
                    <input type="text" class="form-control form-control-sm" value="<?= htmlspecialchars($permintaan['lokasi_pengambilan'] ?: '-') ?>" readonly>
                </div>
                <div class="col-md-4 mb-2">
                    <label class="form-label mb-0 fw-bold">Tanggal Pengambilan:</label>
                    <input type="text" class="form-control form-control-sm" value="<?= (!empty($permintaan['tgl_pengambilan']) && !empty($permintaan['jam_pengambilan'])) ? date('d-m-Y H:i', strtotime($permintaan['tgl_pengambilan'] . ' ' . $permintaan['jam_pengambilan'])) . ' WIB' : '-' ?>" readonly>
                </div>
                <div class="col-md-4 mb-2">
                    <label class="form-label mb-0 fw-bold">Volume Sampel (mL):</label>
                    <input type="text" class="form-control form-control-sm" value="<?= htmlspecialchars($permintaan['volume_ml'] ?: '-') ?>" readonly>
                </div>
            </div>
        </div>
    </div>

    <!-- CARD "DAFTAR PEMERIKSAAN" (full width) -->
    <div class="card mb-3">
        <div class="card-header">
            <h5 class="mb-0">Daftar Pemeriksaan</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-sm table-bordered table-hover mb-0 table-mobile-cards">
                    <thead>
                        <tr>
                            <th style="width: 5%;">No</th>
                            <th>Nama Pemeriksaan</th>
                            <th style="width: 20%;">Hasil</th>
                            <th style="width: 12%;">Satuan</th>
                            <th style="width: 18%;">Baku Mutu</th>
                            <th style="width: 25%;">Metode Pemeriksaan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($items)): ?>
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">Belum ada item pemeriksaan dipilih</td>
                            </tr>
                        <?php else: ?>
                            <?php
                            $grouped_items = [];
                            foreach ($items as $item) {
                                $kel = kelompok_tampil($item['kelompok'] ?? '', $permintaan['jenis_sampel'] ?? '');
                                $grouped_items[$kel][] = $item;
                            }
                            $no = 1;
                            foreach ($grouped_items as $kel => $gItems):
                            ?>
                                <tr>
                                    <td colspan="6" class="bg-light fw-bold text-uppercase" style="font-size: 0.9em;"><?= htmlspecialchars(normalize_kelompok($kel)) ?></td>
                                </tr>
                                <?php foreach ($gItems as $it): ?>
                                    <?php
                                    $pid = (int)$it['id_master_pemeriksaan'];
                                    $ket_class = ($it['keterangan'] === 'MS') ? 'table-success' : (($it['keterangan'] === 'TMS') ? 'table-danger' : '');
                                    $disp_baku   = !empty($it['baku_mutu_override']) ? $it['baku_mutu_override'] : ($it['baku_mutu'] ?? '-');
                                    $disp_metode = !empty($it['metode_override']) ? $it['metode_override'] : ($it['metode'] ?? '-');
                                    $disp_satuan = !empty($it['satuan_override']) ? $it['satuan_override'] : ($it['satuan'] ?? '-');
                                    ?>
                                    <tr class="<?= $ket_class ?>" data-id-permintaan-item="<?= $pid ?>">
                                        <td data-label="No"><?= $no++ ?></td>
                                        <td data-label="Pemeriksaan">
                                            <div class="fw-semibold"><?= htmlspecialchars($it['nama_pemeriksaan']) ?></div>
                                        </td>
                                        <td data-label="Hasil">
                                            <input class="form-control form-control-sm" name="hasil[<?= $pid ?>]" value="<?= htmlspecialchars($it['hasil'] ?? '', ENT_QUOTES, 'UTF-8', false) ?>">
                                            <!-- Hidden input for keterangan -->
                                            <input type="hidden" name="keterangan[<?= $pid ?>]" value="<?= htmlspecialchars($it['keterangan'] ?? '', ENT_QUOTES, 'UTF-8', false) ?>">
                                            <!-- Hidden inputs override — dikirim ke server -->
                                            <input type="hidden" class="hid-baku" name="baku_mutu_override[<?= $pid ?>]" value="<?= htmlspecialchars($it['baku_mutu_override'] ?? '', ENT_QUOTES) ?>">
                                            <input type="hidden" class="hid-metode" name="metode_override[<?= $pid ?>]" value="<?= htmlspecialchars($it['metode_override'] ?? '', ENT_QUOTES) ?>">
                                        </td>
                                        <td data-label="Satuan">
                                            <div><?= htmlspecialchars(!empty($it['satuan']) ? $it['satuan'] : '-') ?></div>
                                        </td>
                                        <td data-label="Baku Mutu">
                                            <!-- Baku Mutu: tampil default + editable -->
                                            <div class="default-baku" data-default="<?= htmlspecialchars($it['baku_mutu'] ?? '') ?>"><?= htmlspecialchars($disp_baku) ?></div>
                                            <input type="text" class="form-control form-control-sm override-baku mt-1" value="<?= htmlspecialchars($disp_baku === '-' ? '' : $disp_baku) ?>" style="display:none;" placeholder="Override baku mutu...">
                                        </td>
                                        <td data-label="Metode">
                                            <!-- Metode: tampil default + editable -->
                                            <div class="default-metode" data-default="<?= htmlspecialchars($it['metode'] ?? '') ?>"><?= htmlspecialchars($disp_metode) ?></div>
                                            <input type="text" class="form-control form-control-sm override-metode mt-1" value="<?= htmlspecialchars($disp_metode === '-' ? '' : $disp_metode) ?>" style="display:none;" placeholder="Override metode...">
                                            <div class="mt-1 btn-group btn-group-sm" role="group" aria-label="Override actions">
                                                <button type="button" class="btn btn-outline-secondary edit-override" title="Ubah Baku Mutu &amp; Metode"><i class="fa fa-pencil-alt"></i></button>
                                                <button type="button" class="btn btn-success save-override" title="Simpan" style="display:none"><i class="fa fa-check"></i></button>
                                                <button type="button" class="btn btn-danger cancel-override" title="Batal" style="display:none"><i class="fa fa-times"></i></button>
                                            </div>
                                            <div class="mt-1 btn-group btn-group-sm ms-2" role="group" aria-label="TMS actions">
                                                <button type="button" class="btn btn-outline-success btn-mark-ms" data-pid="<?= $pid ?>">MS</button>
                                                <button type="button" class="btn btn-outline-danger btn-mark-tms" data-pid="<?= $pid ?>">TMS</button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-header">
            <h5 class="mb-0">Kondisi dan Kelayakan Sampel</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-2">
                        <label class="form-label mb-0 fw-bold">Kelayakan:</label>
                        <?php
                        $sk = $permintaan['status_kelayakan'] ?? 'Belum Dinilai';
                        $badge_class = 'bg-secondary';
                        if ($sk === 'Layak') {
                            $badge_class = 'bg-success';
                        } elseif ($sk === 'Tidak Layak') {
                            $badge_class = 'bg-danger';
                        }
                        ?>
                        <input type="hidden" id="status_kelayakan_input_hasil_bottom" value="<?= htmlspecialchars($sk) ?>">
                        <div class="badge <?= $badge_class ?> fs-6" style="padding: 0.5em 0.7em; width: 100%; text-align: left;">
                            <?= htmlspecialchars($sk) ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-2" id="alasan_container_bottom" style="display: <?= ($sk === 'Tidak Layak') ? 'block' : 'none' ?>;">
                        <div class="alert alert-danger mb-2">
                            <strong>Sampel Tidak Layak</strong>
                            <div class="small text-muted">Sampel ini tidak memenuhi persyaratan pemeriksaan. Lihat alasan dan catatan di bawah.</div>
                        </div>
                        <label class="form-label mb-0 fw-bold">Alasan Tidak Layak:</label>
                        <?php
                        $selected_alasan = isset($permintaan['alasan']) ? array_map('trim', explode(',', $permintaan['alasan'])) : [];
                        $selected_alasan = array_filter($selected_alasan); // remove empty items
                        ?>
                        <div class="d-flex flex-column gap-2 mt-1">
                            <?php if (empty($selected_alasan)): ?>
                                <div class="text-muted">-</div>
                            <?php else: ?>
                                <ul style="margin:0;padding-left:1.2rem;">
                                    <?php foreach ($selected_alasan as $al): ?>
                                        <li><?= htmlspecialchars($al) ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php endif; ?>
                        </div>

                        <div class="form-group mt-2">
                            <label class="form-label mb-1 fw-bold">Catatan Penolakan:</label>
                            <textarea class="form-control form-control-sm" rows="3" readonly><?= htmlspecialchars($permintaan['catatan'] ?? '') ?></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <style>
        /* Memperjelas border inputan */
        .form-control,
        .form-select {
            border-radius: 6px;
            border: 1px solid #94a3b8 !important;
        }

        .form-control:not([readonly]),
        .form-select:not([disabled]) {
            background-color: #ffffff !important;
        }

        .form-control[readonly],
        .form-select[disabled] {
            background-color: #f1f5f9 !important;
            color: #475569 !important;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #0d6efd !important;
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25) !important;
        }

        /* Latar belakang lembut untuk header card */
        .card-header {
            background: linear-gradient(180deg, #f8fafc, #e2e8f0) !important;
            border-bottom: 1px solid rgba(15, 23, 42, 0.08) !important;
        }

        /* Custom Round Checkbox Styling */
        .custom-round-checkbox {
            /* Hide default checkbox */
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
            /* Custom dimensions */
            width: 1.25em;
            /* Adjust size as needed */
            height: 1.25em;
            /* Adjust size as needed */
            border: 2px solid #ced4da;
            /* Default border color */
            border-radius: 50%;
            /* Make it round */
            vertical-align: middle;
            position: relative;
            cursor: pointer;
            outline: none;
            transition: background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        }

        .custom-round-checkbox:checked {
            background-color: #0d6efd;
            /* Primary blue for checked state */
            border-color: #0d6efd;
            /* Primary blue border */
        }

        .custom-round-checkbox:focus {
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
            /* Focus ring */
        }

        .custom-round-checkbox:checked::before {
            content: "";
            display: block;
            width: 0.6em;
            /* Size of the checkmark dot */
            height: 0.6em;
            /* Size of the checkmark dot */
            border-radius: 50%;
            background-color: #fff;
            /* White dot */
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }

        /* Adjust positioning for labels */
        .form-check-label {
            vertical-align: middle;
            margin-left: 0.5rem;
            /* Space between checkbox and label */
        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Kelayakan visibility
            const statusKelayakanValue = document.getElementById('status_kelayakan_input_hasil_bottom').value;
            const alasanTidakLayakContainerBottom = document.getElementById('alasan_container_bottom');
            if (statusKelayakanValue === 'Tidak Layak') {
                alasanTidakLayakContainerBottom.style.display = 'block';
            } else {
                alasanTidakLayakContainerBottom.style.display = 'none';
            }

            // ============================================================
            // EDITOR BAKU MUTU + METODE (tombol pensil di kolom Metode)
            // Override disimpan ke hidden form fields → dikirim ke server
            // ============================================================
            document.querySelectorAll('tr[data-id-permintaan-item]').forEach(row => {
                const editBtn = row.querySelector('.edit-override');
                const saveBtn = row.querySelector('.save-override');
                const cancelBtn = row.querySelector('.cancel-override');
                const defaultBaku = row.querySelector('.default-baku');
                const defaultMetode = row.querySelector('.default-metode');
                const overrideBaku = row.querySelector('.override-baku');
                const overrideMetode = row.querySelector('.override-metode');
                const hidBaku = row.querySelector('.hid-baku');
                const hidMetode = row.querySelector('.hid-metode');

                if (!editBtn) return;

                function showEditor() {
                    if (overrideBaku) overrideBaku.style.display = '';
                    if (overrideMetode) overrideMetode.style.display = '';
                    if (defaultBaku) defaultBaku.style.display = 'none';
                    if (defaultMetode) defaultMetode.style.display = 'none';
                    editBtn.style.display = 'none';
                    if (saveBtn) saveBtn.style.display = '';
                    if (cancelBtn) cancelBtn.style.display = '';
                }

                function hideEditor(revert) {
                    if (overrideBaku) overrideBaku.style.display = 'none';
                    if (overrideMetode) overrideMetode.style.display = 'none';
                    if (defaultBaku) defaultBaku.style.display = '';
                    if (defaultMetode) defaultMetode.style.display = '';
                    editBtn.style.display = '';
                    if (saveBtn) saveBtn.style.display = 'none';
                    if (cancelBtn) cancelBtn.style.display = 'none';
                    if (revert) {
                        // kembalikan ke nilai hidden (tersimpan)
                        if (overrideBaku && hidBaku) overrideBaku.value = hidBaku.value;
                        if (overrideMetode && hidMetode) overrideMetode.value = hidMetode.value;
                    }
                }

                editBtn.addEventListener('click', function() {
                    // Isi editor dengan nilai saat ini (dari hidden atau tampilan)
                    if (overrideBaku) overrideBaku.value = hidBaku ? hidBaku.value : (defaultBaku ? (defaultBaku.textContent.trim() === '-' ? '' : defaultBaku.textContent.trim()) : '');
                    if (overrideMetode) overrideMetode.value = hidMetode ? hidMetode.value : (defaultMetode ? (defaultMetode.textContent.trim() === '-' ? '' : defaultMetode.textContent.trim()) : '');
                    showEditor();
                    if (overrideBaku) overrideBaku.focus();
                });

                if (saveBtn) saveBtn.addEventListener('click', function() {
                    // Simpan ke hidden fields → akan dikirim saat form submit
                    const bakuVal = overrideBaku ? overrideBaku.value.trim() : '';
                    const metodeVal = overrideMetode ? overrideMetode.value.trim() : '';
                    if (hidBaku) hidBaku.value = bakuVal;
                    if (hidMetode) hidMetode.value = metodeVal;
                    // Update tampilan
                    if (defaultBaku) defaultBaku.textContent = bakuVal || (defaultBaku.getAttribute('data-default') || '-');
                    if (defaultMetode) defaultMetode.textContent = metodeVal || (defaultMetode.getAttribute('data-default') || '-');
                    hideEditor(false);
                });

                if (cancelBtn) cancelBtn.addEventListener('click', function() {
                    hideEditor(true);
                });
            });


            // ============================================================
            // MS / TMS handler
            // ============================================================
            var _csrfName = '<?= $this->security->get_csrf_token_name() ?>';
            var _csrfHash = '<?= $this->security->get_csrf_hash() ?>';

            function postStatusInput(pid, status) {
                const row = document.querySelector('tr[data-id-permintaan-item="' + pid + '"]');
                if (row) {
                    row.classList.remove('table-success', 'table-danger');
                    if (status === 'MS') row.classList.add('table-success');
                    if (status === 'TMS') row.classList.add('table-danger');
                    const hidden = row.querySelector('input[type="hidden"][name^="keterangan"]');
                    if (hidden) hidden.value = status;
                }
                var body = 'permintaan_item_id=' + encodeURIComponent(pid) + '&status=' + encodeURIComponent(status);
                body += '&' + encodeURIComponent(_csrfName) + '=' + encodeURIComponent(_csrfHash);
                fetch('<?= site_url('k-panel/laporan_uji_kesmas/set_status') ?>', {
                    method: 'POST',
                    credentials: 'same-origin',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: body
                }).then(r => r.json()).then(js => {
                    try {
                        if (js && js[_csrfName]) {
                            _csrfHash = js[_csrfName];
                            document.querySelectorAll('input[name="' + _csrfName + '"]').forEach(inp => inp.value = _csrfHash);
                        }
                    } catch (e) {}
                }).catch(e => console.log('AJAX save failed.', e));
            }

            document.querySelectorAll('.btn-mark-ms').forEach(b => b.addEventListener('click', function() {
                postStatusInput(this.getAttribute('data-pid'), 'MS');
            }));
            document.querySelectorAll('.btn-mark-tms').forEach(b => b.addEventListener('click', function() {
                postStatusInput(this.getAttribute('data-pid'), 'TMS');
            }));
        });
    </script>


    <!-- CARD “INFORMASI PENGAMBILAN & PEMERIKSAAN” -->
    <div class="card mb-3">
        <div class="card-header">
            <h5 class="mb-0">Informasi Pengambilan & Pemeriksaan</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label mb-0 fw-bold">Petugas Pengambilan Spesimen:</label>
                        <select class="form-select form-select-sm" disabled>
                            <option value="">- Pilih Petugas -</option>
                            <?php $petugas_pengambil_terpilih = !empty($permintaan['petugas_pengambil_id']) ? $permintaan['petugas_pengambil_id'] : ($permintaan['id_petugas'] ?? ''); ?>
                            <?php foreach ($petugas as $p): ?>
                                <option value="<?= (int)$p['id_petugas'] ?>" <?= ((string)($petugas_pengambil_terpilih) === (string)$p['id_petugas']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($p['nama']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <input type="hidden" name="id_petugas" value="<?= htmlspecialchars($petugas_pengambil_terpilih) ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label mb-0 fw-bold">Tanggal/Jam Pengambilan (Waktu Sampel diambil):</label>
                        <input class="form-control form-control-sm" type="datetime-local" value="<?= (!empty($permintaan['tgl_pengambilan']) && !empty($permintaan['jam_pengambilan']) && $permintaan['tgl_pengambilan'] != '0000-00-00') ? date('Y-m-d\TH:i', strtotime($permintaan['tgl_pengambilan'] . ' ' . $permintaan['jam_pengambilan'])) : '' ?>" readonly>
                    </div>
                    <div class="mb-0">
                        <label class="form-label mb-0 fw-bold">Tanggal/Jam Pemeriksaan (Waktu mulai pemeriksaan):</label>
                        <?php
                        $tgl_jam_pemeriksaan_val = null;
                        if (!empty($permintaan['tgl_pengerjaan']) && !str_starts_with($permintaan['tgl_pengerjaan'], '0000-00-00')) { // Check if directly in permintaan
                            $tgl_jam_pemeriksaan_val = $permintaan['tgl_pengerjaan'];
                        } else { // Fallback to first item
                            foreach ($items as $it) {
                                if (!empty($it['tgl_jam_pemeriksaan'])) {
                                    $tgl_jam_pemeriksaan_val = $it['tgl_jam_pemeriksaan'];
                                    break;
                                }
                            }
                        }
                        ?>
                        <input class="form-control form-control-sm" type="datetime-local" name="tgl_jam_pemeriksaan"
                            value="<?= $tgl_jam_pemeriksaan_val ? date('Y-m-d\TH:i', strtotime($tgl_jam_pemeriksaan_val)) : '' ?>">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label mb-0 fw-bold">Tanggal/Jam Selesai Pemeriksaan:</label>
                        <?php
                        $tgl_jam_selesai_val = null;
                        if (!empty($permintaan['tgl_input_hasil']) && !str_starts_with($permintaan['tgl_input_hasil'], '0000-00-00')) {
                            $tgl_jam_selesai_val = $permintaan['tgl_input_hasil'];
                        } else {
                            foreach ($items as $it) {
                                if (!empty($it['tgl_jam_selesai'])) {
                                    $tgl_jam_selesai_val = $it['tgl_jam_selesai'];
                                    break;
                                }
                            }
                        }
                        ?>
                        <input class="form-control form-control-sm" type="datetime-local" name="tgl_jam_selesai"
                            value="<?= $tgl_jam_selesai_val ? date('Y-m-d\TH:i', strtotime($tgl_jam_selesai_val)) : '' ?>">
                    </div>
                    <div class="mb-0">
                        <label class="form-label mb-0 fw-bold">Tanggal/Jam Pelaporan Hasil:</label>
                        <?php
                        $tgl_jam_lapor_val = null;
                        if (!empty($permintaan['tgl_cetak']) && !str_starts_with($permintaan['tgl_cetak'], '0000-00-00')) {
                            $tgl_jam_lapor_val = $permintaan['tgl_cetak'];
                        } else {
                            foreach ($items as $it) {
                                if (!empty($it['tgl_jam_lapor'])) {
                                    $tgl_jam_lapor_val = $it['tgl_jam_lapor'];
                                    break;
                                }
                            }
                        }
                        ?>
                        <input class="form-control form-control-sm" type="datetime-local" name="tgl_jam_lapor"
                            value="<?= $tgl_jam_lapor_val ? date('Y-m-d\TH:i', strtotime($tgl_jam_lapor_val)) : '' ?>">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="d-flex justify-content-between mt-4 mb-4">
        <a href="<?= site_url('k-panel/uji') ?>" class="btn btn-secondary"><i class="fa fa-arrow-left me-2"></i> Kembali</a>
        <div>
            <button id="save_hasil_btn" type="button" class="btn btn-primary" onclick="this.innerHTML='<i class=\'fa fa-hourglass-split me-2\'></i> Menyimpan...'; this.style.pointerEvents='none'; document.getElementById('formInputHasil').submit();"><i class="fa fa-save me-2"></i> Simpan Hasil</button>
            <a id="view_report_btn" href="<?= site_url('k-panel/laporan_uji_kesmas/detail/' . $permintaan['id_permintaan']) ?>" class="btn btn-success ms-2"><i class="fa fa-file-alt me-2"></i> Lihat Detail Laporan</a>
        </div>
    </div>
</form>
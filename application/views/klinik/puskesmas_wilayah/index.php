<div class="content-wrapper px-4 pt-4">
    <div class="content-wrapper px-4 pt-4">
        <?php
        setlocale(LC_TIME, 'id_ID');
        date_default_timezone_set('Asia/Jakarta');
        $hariIndo = ['Sunday' => 'Minggu', 'Monday' => 'Senin', 'Tuesday' => 'Selasa', 'Wednesday' => 'Rabu', 'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu'];
        $bulanIndo = ['January' => 'Januari', 'February' => 'Februari', 'March' => 'Maret', 'April' => 'April', 'May' => 'Mei', 'June' => 'Juni', 'July' => 'Juli', 'August' => 'Agustus', 'September' => 'September', 'October' => 'Oktober', 'November' => 'November', 'December' => 'Desember'];
        $hari = $hariIndo[date('l')];
        $tanggalLengkap = "$hari, " . date('d') . " " . $bulanIndo[date('F')] . " " . date('Y');
        ?>

        <!-- PAGE HEADER -->
        <div class="d-flex justify-content-between align-items-end mb-3">
            <div>
                <h3 class="text-dark fw-bold text-uppercase mb-1" style="font-size: 24px; letter-spacing: 0.5px;">
                    <?= html_escape($title) ?>
                </h3>
                <span class="text-secondary small">Daftar lokasi kerja puskesmas dan cakupan wilayah kelurahan</span>
            </div>
            <div class="text-end">
                <span class="badge bg-light text-secondary border border-secondary-subtle px-3 py-2 rounded-2 fw-semibold" style="font-size: 13px;">
                    <i class="far fa-calendar-alt me-1 text-primary"></i> <?= $tanggalLengkap ?>
                </span>
            </div>
        </div>
        <hr style="border-top: 1px solid #cbd5e1; margin-top: 0; margin-bottom: 24px;">

        <div class="mb-4">
            <a href="<?= site_url('klinik/puskesmas_wilayah/create') ?>" class="btn btn-primary px-4 py-2 fw-semibold rounded-2 shadow-sm">
                <i class="fas fa-plus me-1"></i> Tambah Data
            </a>
        </div>

        <!-- DATA TABLE CARD -->
        <div class="card shadow-sm w-100 border-0 rounded-3 overflow-hidden mb-5">
            <div class="card-body p-4 bg-white">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover nowrap align-middle mb-0" id="dataTable" width="100%" cellspacing="0" style="font-size: 14px; border-color: #f1f5f9;">
                        <thead class="table-dark">
                            <tr>
                                <th width="5%" class="text-center py-3" style="border-bottom: none; font-weight: 600;">No</th>
                                <th width="25%" class="py-3" style="border-bottom: none; font-weight: 600;">Nama Puskesmas</th>
                                <th width="20%" class="py-3" style="border-bottom: none; font-weight: 600;">Kecamatan</th>
                                <th width="35%" class="py-3" style="border-bottom: none; font-weight: 600;">Kelurahan (Wilayah Kerja)</th>
                                <th width="15%" class="text-center py-3" style="border-bottom: none; font-weight: 600;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1;
                            foreach ($wilayah as $w): ?>
                                <tr>
                                    <td class="text-center fw-semibold text-secondary py-3"><?= $no++ ?></td>
                                    <td class="fw-bold text-dark py-3" style="font-size: 15px;"><?= html_escape($w->nama_puskesmas) ?></td>
                                    <td class="py-3 text-secondary" style="font-weight: 500;"><?= html_escape($w->kecamatan) ?></td>
                                    <td class="py-3">
                                        <div class="d-flex flex-wrap gap-1 align-items-center">
                                            <?php
                                            if (!empty($w->kelurahan_list)) {
                                                $kels = array_map('trim', explode(',', $w->kelurahan_list));
                                                foreach ($kels as $kel) {
                                                    if ($kel !== '') {
                                                        echo '<span class="badge bg-light text-dark border border-secondary-subtle px-2.5 py-1.5 rounded-pill shadow-xs d-inline-flex align-items-center" style="font-weight: 500; font-size: 12px; border-color: #e2e8f0 !important;"><i class="fas fa-map-marker-alt text-primary me-1.5" style="font-size: 10px;"></i>' . html_escape($kel) . '</span> ';
                                                    }
                                                }
                                            } else {
                                                echo '<span class="text-muted small italic">-</span>';
                                            }
                                            ?>
                                        </div>
                                    </td>
                                    <td class="text-center py-3">
                                        <div class="d-flex justify-content-center gap-1">
                                            <a href="<?= site_url('klinik/puskesmas_wilayah/edit/' . $w->id) ?>" class="btn btn-sm btn-warning text-dark px-3 py-1.5 rounded-2 fw-semibold shadow-xs" title="Edit Data" style="font-size: 13px;">
                                                <i class="fas fa-edit me-1"></i> Edit
                                            </a>
                                            <a href="<?= site_url('klinik/puskesmas_wilayah/delete/' . $w->id) ?>" class="btn btn-sm btn-danger px-3 py-1.5 rounded-2 fw-semibold shadow-xs" onclick="return confirm('Yakin ingin menghapus data ini?')" title="Hapus Data" style="font-size: 13px;">
                                                <i class="fas fa-trash me-1"></i> Hapus
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Custom DataTable Aesthetics -->
<style>
    .dataTables_wrapper .dataTables_paginate .paginate_button {
        padding: 6px 12px !important;
        margin-left: 2px !important;
        border-radius: 6px !important;
        border: 1px solid #e2e8f0 !important;
        background: #fff !important;
        color: #475569 !important;
        font-weight: 500 !important;
        font-size: 13px !important;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
        background: #f1f5f9 !important;
        color: #1e293b !important;
        border-color: #cbd5e1 !important;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button.current,
    .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover {
        background: #0d6efd !important;
        border-color: #0d6efd !important;
        color: #fff !important;
    }

    .dataTables_wrapper .dataTables_filter input {
        border: 1px solid #cbd5e1 !important;
        border-radius: 6px !important;
        padding: 6px 12px !important;
        margin-left: 0.5em !important;
        outline: none !important;
        font-size: 13.5px !important;
    }

    .dataTables_wrapper .dataTables_filter input:focus {
        border-color: #93c5fd !important;
        box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.15) !important;
    }

    .dataTables_wrapper .dataTables_length select {
        border: 1px solid #cbd5e1 !important;
        border-radius: 6px !important;
        padding: 6px 10px !important;
        outline: none !important;
        font-size: 13.5px !important;
    }

    .dataTables_wrapper .dataTables_info {
        font-size: 13px !important;
        color: #64748b !important;
        font-weight: 500 !important;
    }

    table.dataTable {
        border-collapse: collapse !important;
        border-spacing: 0 !important;
    }
</style>
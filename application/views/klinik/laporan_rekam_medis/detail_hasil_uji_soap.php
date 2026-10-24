<div class="content-wrapper bg-white">
    <!-- Header Section -->
    <section class="content-header pt-3">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-sm-8">
                    <h4 class="fw-bold text-dark m-0"><?= $title ?></h4>
                </div>
                <div class="col-sm-4 text-right">
                    <span class="text-muted"><?= $formatted_date ?></span>
                </div>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <section class="content mt-2">
        <div class="container-fluid">
            <div class="card shadow-none border-0">
                <div class="card-body p-0">
                    
                    <!-- Action Bar -->
                    <div class="mb-3">
                        <a href="<?= site_url('klinik/Laporan_hasil_pemeriksaan/laporan_hasil_uji_soap') ?>" class="btn btn-sm btn-secondary px-3">
                            <i class="fas fa-arrow-left"></i> Back
                        </a>
                    </div>

                    <!-- Main Table -->
                    <div class="table-responsive">
                        <table id="tableDetail" class="table table-hover table-bordered align-middle">
                            <thead class="bg-light">
                                <tr>
                                    <th width="50" class="text-center">No</th>
                                    <th>Nama Pasien</th>
                                    <th>Jenis Kelamin</th>
                                    <th>Umur</th>
                                    <th>Waktu Terdaftar</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1; foreach ($patients as $p): ?>
                                <tr>
                                    <td class="text-center"><?= $no++ ?></td>
                                    <td class="fw-bold"><?= $p->nama_pasien ?></td>
                                    <td><?= $p->gender ?></td>
                                    <td><?= $p->umur ?></td>
                                    <td><?= date('H:i:s', strtotime($p->waktu_terdaftar)) ?></td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center align-items-center gap-3">
                                            <?php if ($p->lab_id && $p->has_results > 0): ?>
                                                <a href="<?= site_url('klinik/laporan_uji_klinik/export_pdf/' . $p->lab_id) ?>" target="_blank" 
                                                   class="btn btn-sm btn-danger rounded-pill px-3 shadow-sm" 
                                                   style="font-weight: 700; min-width: 110px;">
                                                    <i class="fas fa-file-pdf me-1"></i> PDF LAB
                                                </a>
                                            <?php elseif ($p->lab_id): ?>
                                                <div class="text-danger fw-bold border border-danger rounded-pill px-2 py-1" 
                                                     style="font-size: 11px; min-width: 110px; background: rgba(220, 53, 69, 0.05);">
                                                    Selesaikan Uji
                                                </div>
                                            <?php else: ?>
                                                <div class="text-muted" style="font-size: 11px; min-width: 110px;">-</div>
                                            <?php endif; ?>
                                            
                                            <div class="vr" style="height: 25px; opacity: 0.1;"></div>

                                            <?php if ($p->soap_id && $p->status_soap == 'Sudah diisi'): ?>
                                                <a href="<?= site_url('klinik/uji_rekam_medis/print_soap_pdf/' . $p->soap_id) ?>" target="_blank" 
                                                   class="btn btn-sm btn-danger rounded-pill px-3 shadow-sm" 
                                                   style="font-weight: 700; min-width: 110px;">
                                                    <i class="fas fa-file-pdf me-1"></i> PDF SOAP
                                                </a>
                                            <?php elseif ($p->soap_id): ?>
                                                <div class="text-danger fw-bold border border-danger rounded-pill px-2 py-1" 
                                                     style="font-size: 11px; min-width: 110px; background: rgba(220, 53, 69, 0.05);">
                                                    Selesaikan SOAP
                                                </div>
                                            <?php else: ?>
                                                <div class="text-muted" style="font-size: 11px; min-width: 110px;">-</div>
                                            <?php endif; ?>
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
    </section>
</div>

<!-- Modal Lab Result -->
<div class="modal fade" id="modalLab" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title"><i class="fas fa-flask"></i> Hasil Uji Laboratorium</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="labContent">
                <!-- Data will be loaded via AJAX -->
                <div class="text-center py-4">
                    <div class="spinner-border text-danger" role="status"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal SOAP Result -->
<div class="modal fade" id="modalSoap" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="fas fa-notes-medical"></i> Catatan Klinis SOAP</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="soapContent">
                <!-- Data will be loaded via AJAX -->
                <div class="text-center py-4">
                    <div class="spinner-border text-primary" role="status"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#tableDetail').DataTable({
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json"
        },
        "pageLength": 10,
        "lengthMenu": [ [10, 25, 50, -1], [10, 25, 50, "Semua"] ]
    });
});

function showLabModal(id) {
    $('#labContent').html('<div class="text-center py-4"><div class="spinner-border text-danger" role="status"></div></div>');
    $('#modalLab').modal('show');
    
    $.ajax({
        url: "<?= site_url('klinik/Laporan_hasil_pemeriksaan/get_lab_details_ajax/') ?>" + id,
        type: 'GET',
        success: function(response) {
            $('#labContent').html(response);
        },
        error: function() {
            $('#labContent').html('<div class="alert alert-danger">Gagal memuat data.</div>');
        }
    });
}

function showSoapModal(id) {
    $('#soapContent').html('<div class="text-center py-4"><div class="spinner-border text-primary" role="status"></div></div>');
    $('#modalSoap').modal('show');
    
    $.ajax({
        url: "<?= site_url('klinik/Laporan_hasil_pemeriksaan/get_soap_details_ajax/') ?>" + id,
        type: 'GET',
        success: function(response) {
            $('#soapContent').html(response);
        },
        error: function() {
            $('#soapContent').html('<div class="alert alert-danger">Gagal memuat data.</div>');
        }
    });
}
</script>

<style>
    .bg-white { background-color: #ffffff !important; }
    #tableDetail thead th {
        font-weight: 700;
        text-transform: none;
        color: #4a5568;
        border-bottom: 2px solid #edf2f7;
    }
    .btn-xs {
        padding: 2px 6px;
        font-size: 11px;
    }
    .rounded-pill {
        border-radius: 50rem !important;
    }
</style>

<div class="content-wrapper px-4 pt-4">
    <!-- HEADER -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
        <div>
            <h3 class="text-dark fw-bold mb-1">
                <i class="fas fa-chart-bar text-primary me-2"></i>
                Laporan Bulanan & Tahunan
            </h3>
            <p class="text-muted mb-0 small">Grafik dan rekapitulasi data pemeriksaan laboratorium klinis bulanan</p>
        </div>
        <div class="mt-2 mt-md-0">
            <a href="<?= site_url('klinik/form_laporan_akhir') ?>" class="btn btn-secondary shadow-sm">
                <i class="fas fa-arrow-left me-1"></i> Kembali
            </a>
        </div>
    </div>

    <hr style="border-top: 2px solid #1e3a8a; margin-bottom: 25px;">

    <!-- CONTENT -->
    <div class="container-fluid p-0">
        <!-- WRAPPER TAB UNTUK SEMUA PEMERIKSAAN -->
        <div class="card border-0 border-top border-primary border-3 shadow-sm rounded-3">
            <div class="card-header p-2 bg-white border-bottom-0">
                <ul class="nav nav-pills flex-column flex-md-row" id="pemeriksaan-tabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link active" id="tab-hematologi-btn" data-bs-toggle="tab" href="#tab_hematologi" role="tab" aria-controls="tab_hematologi" aria-selected="true">
                            <i class="fas fa-tint me-1 text-danger"></i> Hematologi
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="tab-kimia-darah-btn" data-bs-toggle="tab" href="#tab_kimia_darah" role="tab" aria-controls="tab_kimia_darah" aria-selected="false">
                            <i class="fas fa-flask me-1 text-success"></i> Kimia Darah
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="tab-urinalisa-btn" data-bs-toggle="tab" href="#tab_urinalisa" role="tab" aria-controls="tab_urinalisa" aria-selected="false">
                            <i class="fas fa-vial me-1 text-warning"></i> Urinalisa
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="tab-hemostasis-btn" data-bs-toggle="tab" href="#tab_hemostasis" role="tab" aria-controls="tab_hemostasis" aria-selected="false">
                            <i class="fas fa-heartbeat me-1 text-info"></i> Hemostasis
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="tab-biomolekuler-btn" data-bs-toggle="tab" href="#tab_biomolekuler" role="tab" aria-controls="tab_biomolekuler" aria-selected="false">
                            <i class="fas fa-dna me-1 text-primary"></i> Biomolekuler
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="tab-imunologi-btn" data-bs-toggle="tab" href="#tab_imunologi" role="tab" aria-controls="tab_imunologi" aria-selected="false">
                            <i class="fas fa-microscope me-1 text-secondary"></i> Imunologi
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="tab-mikrobiologi-btn" data-bs-toggle="tab" href="#tab_mikrobiologi" role="tab" aria-controls="tab_mikrobiologi" aria-selected="false">
                            <i class="fas fa-bacteria me-1 text-dark"></i> Mikrobiologi
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="tab-toksikologi-btn" data-bs-toggle="tab" href="#tab_toksikologi" role="tab" aria-controls="tab_toksikologi" aria-selected="false">
                            <i class="fas fa-bug me-1 text-danger"></i> Toksikologi
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="tab-jumlah-pasien-btn" data-bs-toggle="tab" href="#tab_jumlah_pasien" role="tab" aria-controls="tab_jumlah_pasien" aria-selected="false">
                            <i class="fas fa-user me-1 text-primary"></i> Jumlah Pasien
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="tab-puskesmas-wilayah-btn" data-bs-toggle="tab" href="#tab_puskesmas_wilayah" role="tab" aria-controls="tab_puskesmas_wilayah" aria-selected="false">
                            <i class="fas fa-map-marker-alt me-1 text-success"></i> Kunjungan per Wilayah
                        </a>
                    </li>
                </ul>
            </div>
            <div class="card-body bg-light p-4">
                <div class="tab-content" id="pemeriksaan-tabs-content">
                    <!-- TAB HEMATOLOGI -->
                    <div class="tab-pane fade show active" id="tab_hematologi" role="tabpanel" aria-labelledby="tab-hematologi-btn">
                        <h4 class="text-danger mb-4 fw-bold"><i class="fas fa-tint me-2"></i> Grafik & Data Hematologi</h4>
                        <div class="row">
                            <div class="col-lg-7 mb-4 mb-lg-0">
                                <div class="card border-0 shadow-sm h-100">
                                    <div class="card-body">
                                        <div class="chart-container" style="position: relative; height:350px; width:100%">
                                            <canvas id="chartHematologi"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-5">
                                <div class="card border-0 shadow-sm h-100 overflow-hidden">
                                    <div class="card-body p-0">
                                        <div class="table-responsive">
                                            <table class="table table-hover align-middle m-0">
                                                <thead class="table-dark">
                                                    <tr>
                                                        <th width="10%" class="text-center">No</th>
                                                        <th>Jenis Pemeriksaan</th>
                                                        <th class="text-center" width="30%">Total</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php $no = 1; foreach($hematologi as $h): ?>
                                                    <tr>
                                                        <td class="text-center fw-semibold"><?= $no++; ?></td>
                                                        <td class="text-dark fw-medium"><?= htmlspecialchars($h->nama_jenis) ?></td>
                                                        <td class="text-center"><span class="badge bg-danger px-3 py-2"><?= htmlspecialchars($h->total) ?> Pemeriksaan</span></td>
                                                    </tr>
                                                    <?php endforeach; ?>
                                                    <?php if(empty($hematologi)): ?>
                                                    <tr>
                                                        <td colspan="3" class="text-center py-4 text-muted">Belum ada data pemeriksaan.</td>
                                                    </tr>
                                                    <?php endif; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- TAB KIMIA DARAH -->
                    <div class="tab-pane fade" id="tab_kimia_darah" role="tabpanel" aria-labelledby="tab-kimia-darah-btn">
                        <h4 class="text-success mb-4 fw-bold"><i class="fas fa-flask me-2"></i> Grafik & Data Kimia Darah</h4>
                        <div class="row">
                            <div class="col-lg-7 mb-4 mb-lg-0">
                                <div class="card border-0 shadow-sm h-100">
                                    <div class="card-body">
                                        <div class="chart-container" style="position: relative; height:350px; width:100%">
                                            <canvas id="chartKimiaDarah"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-5">
                                <div class="card border-0 shadow-sm h-100 overflow-hidden">
                                    <div class="card-body p-0">
                                        <div class="table-responsive">
                                            <table class="table table-hover align-middle m-0">
                                                <thead class="table-dark">
                                                    <tr>
                                                        <th width="10%" class="text-center">No</th>
                                                        <th>Jenis Pemeriksaan</th>
                                                        <th class="text-center" width="30%">Total</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php $no = 1; foreach($kimia_darah as $k): ?>
                                                    <tr>
                                                        <td class="text-center fw-semibold"><?= $no++; ?></td>
                                                        <td class="text-dark fw-medium"><?= htmlspecialchars($k->nama_jenis) ?></td>
                                                        <td class="text-center"><span class="badge bg-success px-3 py-2"><?= htmlspecialchars($k->total) ?> Pemeriksaan</span></td>
                                                    </tr>
                                                    <?php endforeach; ?>
                                                    <?php if(empty($kimia_darah)): ?>
                                                    <tr>
                                                        <td colspan="3" class="text-center py-4 text-muted">Belum ada data pemeriksaan.</td>
                                                    </tr>
                                                    <?php endif; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- TAB URINALISA -->
                    <div class="tab-pane fade" id="tab_urinalisa" role="tabpanel" aria-labelledby="tab-urinalisa-btn">
                        <h4 class="text-warning mb-4 fw-bold"><i class="fas fa-vial me-2 text-warning"></i> Grafik & Data Urinalisa</h4>
                        <div class="row">
                            <div class="col-lg-7 mb-4 mb-lg-0">
                                <div class="card border-0 shadow-sm h-100">
                                    <div class="card-body">
                                        <div class="chart-container" style="position: relative; height:350px; width:100%">
                                            <canvas id="chartUrinalisis"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-5">
                                <div class="card border-0 shadow-sm h-100 overflow-hidden">
                                    <div class="card-body p-0">
                                        <div class="table-responsive">
                                            <table class="table table-hover align-middle m-0">
                                                <thead class="table-dark">
                                                    <tr>
                                                        <th width="10%" class="text-center">No</th>
                                                        <th>Jenis Pemeriksaan</th>
                                                        <th class="text-center" width="30%">Total</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php $no = 1; foreach($urinalisis as $u): ?>
                                                    <tr>
                                                        <td class="text-center fw-semibold"><?= $no++; ?></td>
                                                        <td class="text-dark fw-medium"><?= htmlspecialchars($u->nama_jenis) ?></td>
                                                        <td class="text-center"><span class="badge bg-warning text-dark px-3 py-2"><?= htmlspecialchars($u->total) ?> Pemeriksaan</span></td>
                                                    </tr>
                                                    <?php endforeach; ?>
                                                    <?php if(empty($urinalisis)): ?>
                                                    <tr>
                                                        <td colspan="3" class="text-center py-4 text-muted">Belum ada data pemeriksaan.</td>
                                                    </tr>
                                                    <?php endif; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- TAB HEMOSTASIS -->
                    <div class="tab-pane fade" id="tab_hemostasis" role="tabpanel" aria-labelledby="tab-hemostasis-btn">
                        <h4 class="text-info mb-4 fw-bold"><i class="fas fa-heartbeat me-2 text-info"></i> Grafik & Data Hemostasis</h4>
                        <div class="row">
                            <div class="col-lg-7 mb-4 mb-lg-0">
                                <div class="card border-0 shadow-sm h-100">
                                    <div class="card-body">
                                        <div class="chart-container" style="position: relative; height:350px; width:100%">
                                            <canvas id="chartHemostasis"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-5">
                                <div class="card border-0 shadow-sm h-100 overflow-hidden">
                                    <div class="card-body p-0">
                                        <div class="table-responsive">
                                            <table class="table table-hover align-middle m-0">
                                                <thead class="table-dark">
                                                    <tr>
                                                        <th width="10%" class="text-center">No</th>
                                                        <th>Jenis Pemeriksaan</th>
                                                        <th class="text-center" width="30%">Total</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php $no = 1; foreach($hemostasis as $h): ?>
                                                    <tr>
                                                        <td class="text-center fw-semibold"><?= $no++; ?></td>
                                                        <td class="text-dark fw-medium"><?= htmlspecialchars($h->nama_jenis) ?></td>
                                                        <td class="text-center"><span class="badge bg-info text-white px-3 py-2"><?= htmlspecialchars($h->total) ?> Pemeriksaan</span></td>
                                                    </tr>
                                                    <?php endforeach; ?>
                                                    <?php if(empty($hemostasis)): ?>
                                                    <tr>
                                                        <td colspan="3" class="text-center py-4 text-muted">Belum ada data pemeriksaan.</td>
                                                    </tr>
                                                    <?php endif; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- TAB BIOMOLEKULER -->
                    <div class="tab-pane fade" id="tab_biomolekuler" role="tabpanel" aria-labelledby="tab-biomolekuler-btn">
                        <h4 class="text-primary mb-4 fw-bold"><i class="fas fa-dna me-2"></i> Grafik & Data Biomolekuler</h4>
                        <div class="row">
                            <div class="col-lg-7 mb-4 mb-lg-0">
                                <div class="card border-0 shadow-sm h-100">
                                    <div class="card-body">
                                        <div class="chart-container" style="position: relative; height:350px; width:100%">
                                            <canvas id="chartBiomolekuler"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-5">
                                <div class="card border-0 shadow-sm h-100 overflow-hidden">
                                    <div class="card-body p-0">
                                        <div class="table-responsive">
                                            <table class="table table-hover align-middle m-0">
                                                <thead class="table-dark">
                                                    <tr>
                                                        <th width="10%" class="text-center">No</th>
                                                        <th>Jenis Pemeriksaan</th>
                                                        <th class="text-center" width="30%">Total</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php $no = 1; foreach($biomolekuler as $b): ?>
                                                    <tr>
                                                        <td class="text-center fw-semibold"><?= $no++; ?></td>
                                                        <td class="text-dark fw-medium"><?= htmlspecialchars($b->nama_jenis) ?></td>
                                                        <td class="text-center"><span class="badge bg-primary px-3 py-2"><?= htmlspecialchars($b->total) ?> Pemeriksaan</span></td>
                                                    </tr>
                                                    <?php endforeach; ?>
                                                    <?php if(empty($biomolekuler)): ?>
                                                    <tr>
                                                        <td colspan="3" class="text-center py-4 text-muted">Belum ada data pemeriksaan.</td>
                                                    </tr>
                                                    <?php endif; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- TAB IMUNOLOGI -->
                    <div class="tab-pane fade" id="tab_imunologi" role="tabpanel" aria-labelledby="tab-imunologi-btn">
                        <h4 class="text-secondary mb-4 fw-bold"><i class="fas fa-microscope me-2"></i> Grafik & Data Imunologi</h4>
                        <div class="row">
                            <div class="col-lg-7 mb-4 mb-lg-0">
                                <div class="card border-0 shadow-sm h-100">
                                    <div class="card-body">
                                        <div class="chart-container" style="position: relative; height:350px; width:100%">
                                            <canvas id="chartImunologi"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-5">
                                <div class="card border-0 shadow-sm h-100 overflow-hidden">
                                    <div class="card-body p-0">
                                        <div class="table-responsive">
                                            <table class="table table-hover align-middle m-0">
                                                <thead class="table-dark">
                                                    <tr>
                                                        <th width="10%" class="text-center">No</th>
                                                        <th>Jenis Pemeriksaan</th>
                                                        <th class="text-center" width="30%">Total</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php $no = 1; foreach($imunologi as $i): ?>
                                                    <tr>
                                                        <td class="text-center fw-semibold"><?= $no++; ?></td>
                                                        <td class="text-dark fw-medium"><?= htmlspecialchars($i->nama_jenis) ?></td>
                                                        <td class="text-center"><span class="badge bg-secondary px-3 py-2"><?= htmlspecialchars($i->total) ?> Pemeriksaan</span></td>
                                                    </tr>
                                                    <?php endforeach; ?>
                                                    <?php if(empty($imunologi)): ?>
                                                    <tr>
                                                        <td colspan="3" class="text-center py-4 text-muted">Belum ada data pemeriksaan.</td>
                                                    </tr>
                                                    <?php endif; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- TAB MIKROBIOLOGI -->
                    <div class="tab-pane fade" id="tab_mikrobiologi" role="tabpanel" aria-labelledby="tab-mikrobiologi-btn">
                        <h4 class="text-dark mb-4 fw-bold"><i class="fas fa-bacteria me-2"></i> Grafik & Data Mikrobiologi</h4>
                        <div class="row">
                            <div class="col-lg-7 mb-4 mb-lg-0">
                                <div class="card border-0 shadow-sm h-100">
                                    <div class="card-body">
                                        <div class="chart-container" style="position: relative; height:350px; width:100%">
                                            <canvas id="chartMikrobiologi"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-5">
                                <div class="card border-0 shadow-sm h-100 overflow-hidden">
                                    <div class="card-body p-0">
                                        <div class="table-responsive">
                                            <table class="table table-hover align-middle m-0">
                                                <thead class="table-dark">
                                                    <tr>
                                                        <th width="10%" class="text-center">No</th>
                                                        <th>Jenis Pemeriksaan</th>
                                                        <th class="text-center" width="30%">Total</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php $no = 1; foreach($mikrobiologi as $m): ?>
                                                    <tr>
                                                        <td class="text-center fw-semibold"><?= $no++; ?></td>
                                                        <td class="text-dark fw-medium"><?= htmlspecialchars($m->nama_jenis) ?></td>
                                                        <td class="text-center"><span class="badge bg-dark px-3 py-2"><?= htmlspecialchars($m->total) ?> Pemeriksaan</span></td>
                                                    </tr>
                                                    <?php endforeach; ?>
                                                    <?php if(empty($mikrobiologi)): ?>
                                                    <tr>
                                                        <td colspan="3" class="text-center py-4 text-muted">Belum ada data pemeriksaan.</td>
                                                    </tr>
                                                    <?php endif; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- TAB TOKSIKOLOGI -->
                    <div class="tab-pane fade" id="tab_toksikologi" role="tabpanel" aria-labelledby="tab-toksikologi-btn">
                        <h4 class="text-danger mb-4 fw-bold"><i class="fas fa-bug me-2"></i> Grafik & Data Toksikologi</h4>
                        <div class="row">
                            <div class="col-lg-7 mb-4 mb-lg-0">
                                <div class="card border-0 shadow-sm h-100">
                                    <div class="card-body">
                                        <div class="chart-container" style="position: relative; height:350px; width:100%">
                                            <canvas id="chartToksikologi"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-5">
                                <div class="card border-0 shadow-sm h-100 overflow-hidden">
                                    <div class="card-body p-0">
                                        <div class="table-responsive">
                                            <table class="table table-hover align-middle m-0">
                                                <thead class="table-dark">
                                                    <tr>
                                                        <th width="10%" class="text-center">No</th>
                                                        <th>Jenis Pemeriksaan</th>
                                                        <th class="text-center" width="30%">Total</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php $no = 1; foreach($toksikologi as $t): ?>
                                                    <tr>
                                                        <td class="text-center fw-semibold"><?= $no++; ?></td>
                                                        <td class="text-dark fw-medium"><?= htmlspecialchars($t->nama_jenis) ?></td>
                                                        <td class="text-center"><span class="badge bg-danger px-3 py-2"><?= htmlspecialchars($t->total) ?> Pemeriksaan</span></td>
                                                    </tr>
                                                    <?php endforeach; ?>
                                                    <?php if(empty($toksikologi)): ?>
                                                    <tr>
                                                        <td colspan="3" class="text-center py-4 text-muted">Belum ada data pemeriksaan.</td>
                                                    </tr>
                                                    <?php endif; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- TAB JUMLAH PASIEN -->
                    <div class="tab-pane fade" id="tab_jumlah_pasien" role="tabpanel" aria-labelledby="tab-jumlah-pasien-btn">
                        <h4 class="text-primary mb-4 fw-bold"><i class="fas fa-users me-2"></i> Grafik & Data Jumlah Pasien</h4>
                        <div class="row">
                            <div class="col-lg-7 mb-4 mb-lg-0">
                                <div class="card border-0 shadow-sm h-100">
                                    <div class="card-body">
                                        <div class="chart-container" style="position: relative; height:350px; width:100%">
                                            <canvas id="chartJumlahPasien"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-5">
                                <div class="card border-0 shadow-sm h-100 overflow-hidden">
                                    <div class="card-body p-0">
                                        <div class="table-responsive">
                                            <table class="table table-hover align-middle m-0">
                                                <thead class="table-dark">
                                                    <tr>
                                                        <th width="10%" class="text-center">No</th>
                                                        <th>Kategori Gender</th>
                                                        <th class="text-center" width="40%">Total</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td class="text-center fw-semibold">1</td>
                                                        <td class="text-dark fw-medium">Laki-laki</td>
                                                        <td class="text-center"><span class="badge bg-primary px-3 py-2"><?= isset($jumlah_pasien) ? htmlspecialchars($jumlah_pasien->laki_laki) : 0 ?> Pasien</span></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-center fw-semibold">2</td>
                                                        <td class="text-dark fw-medium">Perempuan</td>
                                                        <td class="text-center"><span class="badge bg-primary px-3 py-2"><?= isset($jumlah_pasien) ? htmlspecialchars($jumlah_pasien->perempuan) : 0 ?> Pasien</span></td>
                                                    </tr>
                                                    <tr class="table-light fw-bold text-uppercase">
                                                        <td class="text-center" colspan="2">TOTAL KESELURUHAN</td>
                                                        <td class="text-center"><span class="badge bg-success px-3 py-2"><?= isset($jumlah_pasien) ? htmlspecialchars($jumlah_pasien->total) : 0 ?> Pasien</span></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- TAB PUSKESMAS WILAYAH -->
                    <div class="tab-pane fade" id="tab_puskesmas_wilayah" role="tabpanel" aria-labelledby="tab-puskesmas-wilayah-btn">
                        <h4 class="text-success mb-4 fw-bold"><i class="fas fa-map-marker-alt me-2 text-success"></i> Grafik & Data Kunjungan per Wilayah</h4>
                        <div class="row">
                            <div class="col-lg-7 mb-4 mb-lg-0">
                                <div class="card border-0 shadow-sm h-100">
                                    <div class="card-body">
                                        <div class="chart-container" style="position: relative; height:350px; width:100%">
                                            <canvas id="chartPuskesmasWilayah"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-5">
                                <div class="card border-0 shadow-sm h-100 overflow-hidden">
                                    <div class="card-body p-0">
                                        <div class="table-responsive">
                                            <?php
                                            $grouped_puskesmas = [];
                                            $total_semua = 0;
                                            foreach($puskesmas_wilayah as $p) {
                                                $kecamatan = $p->kecamatan;
                                                if(!isset($grouped_puskesmas[$kecamatan])) {
                                                    $grouped_puskesmas[$kecamatan] = [];
                                                }
                                                $grouped_puskesmas[$kecamatan][] = $p;
                                                $total_semua += $p->total;
                                            }

                                            $nama_bulan = strtr(date('F', mktime(0, 0, 0, $bulan, 1)), ['January'=>'Januari','February'=>'Februari','March'=>'Maret','April'=>'April','May'=>'Mei','June'=>'Juni','July'=>'Juli','August'=>'Agustus','September'=>'September','October'=>'Oktober','November'=>'November','December'=>'Desember']);
                                            ?>
                                            <table class="table table-bordered table-hover align-middle m-0">
                                                <thead class="table-dark text-center">
                                                    <tr>
                                                        <th colspan="3" class="text-uppercase py-3" style="font-size: 15px;">
                                                            JUMLAH KUNJUNGAN PASIEN per KECAMATAN<br>
                                                            <small class="fw-normal">BULAN <?= strtoupper($nama_bulan) ?> <?= htmlspecialchars($tahun) ?></small>
                                                        </th>
                                                    </tr>
                                                    <tr class="table-light text-dark">
                                                        <th style="width: 35%;">Kecamatan</th>
                                                        <th>Puskesmas Wilayah</th>
                                                        <th style="width: 25%;">Total Kunjungan</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach($grouped_puskesmas as $kec => $items): ?>
                                                        <?php $rowspan = count($items); ?>
                                                        <?php foreach($items as $index => $item): ?>
                                                            <tr>
                                                                <?php if($index === 0): ?>
                                                                    <td rowspan="<?= $rowspan ?>" class="align-middle text-uppercase fw-bold table-light text-secondary"><?= htmlspecialchars($kec) ?></td>
                                                                <?php endif; ?>
                                                                <td class="text-uppercase align-middle">
                                                                    <?= htmlspecialchars(str_replace('PUSKESMAS', 'PKM.', $item->wilayah)) ?>
                                                                </td>
                                                                <td class="text-center align-middle">
                                                                    <span class="badge bg-success px-3 py-2"><?= htmlspecialchars($item->total) ?> Kunjungan</span>
                                                                </td>
                                                            </tr>
                                                        <?php endforeach; ?>
                                                    <?php endforeach; ?>
                                                    <?php if(empty($grouped_puskesmas)): ?>
                                                    <tr>
                                                        <td colspan="3" class="text-center py-4 text-muted">Belum ada data kunjungan.</td>
                                                    </tr>
                                                    <?php endif; ?>
                                                    <tr class="table-light fw-bold text-uppercase">
                                                        <td colspan="2" class="text-end pe-4">Total Keseluruhan</td>
                                                        <td class="text-center">
                                                            <span class="badge bg-primary px-3 py-2"><?= htmlspecialchars($total_semua) ?> Kunjungan</span>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ChartJS Library -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    // Fungsi untuk inisialisasi chart agar reusable dan maintainable
    function initChart(canvasId, labels, data, bgColors) {
        const ctx = document.getElementById(canvasId);
        if (!ctx) return null;
        
        return new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Jumlah Pemeriksaan',
                    data: data,
                    backgroundColor: bgColors,
                    borderWidth: 1,
                    borderRadius: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return ' ' + context.parsed.y + ' Pemeriksaan';
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { stepSize: 1 }
                    },
                    x: {
                        grid: { display: false }
                    }
                }
            }
        });
    }

    // Colors Array (Premium look)
    const colors = [
        '#dc3545', '#fd7e14', '#ffc107', '#28a745', '#17a2b8', '#6610f2', '#e83e8c', '#20c997', '#6f42c1', '#007bff'
    ];

    // Initialize all charts using JSON safe output
    const dataHematologi = {
        labels: [<?php foreach($hematologi as $h) echo json_encode($h->nama_jenis).","; ?>],
        data: [<?php foreach($hematologi as $h) echo json_encode($h->total).","; ?>]
    };
    initChart('chartHematologi', dataHematologi.labels, dataHematologi.data, colors);

    const dataKimiaDarah = {
        labels: [<?php foreach($kimia_darah as $k) echo json_encode($k->nama_jenis).","; ?>],
        data: [<?php foreach($kimia_darah as $k) echo json_encode($k->total).","; ?>]
    };
    initChart('chartKimiaDarah', dataKimiaDarah.labels, dataKimiaDarah.data, colors);

    const dataUrinalisa = {
        labels: [<?php foreach($urinalisis as $u) echo json_encode($u->nama_jenis).","; ?>],
        data: [<?php foreach($urinalisis as $u) echo json_encode($u->total).","; ?>]
    };
    initChart('chartUrinalisis', dataUrinalisa.labels, dataUrinalisa.data, colors);

    const dataHemostasis = {
        labels: [<?php foreach($hemostasis as $h) echo json_encode($h->nama_jenis).","; ?>],
        data: [<?php foreach($hemostasis as $h) echo json_encode($h->total).","; ?>]
    };
    initChart('chartHemostasis', dataHemostasis.labels, dataHemostasis.data, colors);

    const dataBiomolekuler = {
        labels: [<?php foreach($biomolekuler as $b) echo json_encode($b->nama_jenis).","; ?>],
        data: [<?php foreach($biomolekuler as $b) echo json_encode($b->total).","; ?>]
    };
    initChart('chartBiomolekuler', dataBiomolekuler.labels, dataBiomolekuler.data, colors);

    const dataImunologi = {
        labels: [<?php foreach($imunologi as $i) echo json_encode($i->nama_jenis).","; ?>],
        data: [<?php foreach($imunologi as $i) echo json_encode($i->total).","; ?>]
    };
    initChart('chartImunologi', dataImunologi.labels, dataImunologi.data, colors);

    const dataMikrobiologi = {
        labels: [<?php foreach($mikrobiologi as $m) echo json_encode($m->nama_jenis).","; ?>],
        data: [<?php foreach($mikrobiologi as $m) echo json_encode($m->total).","; ?>]
    };
    initChart('chartMikrobiologi', dataMikrobiologi.labels, dataMikrobiologi.data, colors);

    const dataToksikologi = {
        labels: [<?php foreach($toksikologi as $t) echo json_encode($t->nama_jenis).","; ?>],
        data: [<?php foreach($toksikologi as $t) echo json_encode($t->total).","; ?>]
    };
    initChart('chartToksikologi', dataToksikologi.labels, dataToksikologi.data, colors);
    
    // Initialize chart Jumlah Pasien
    const dataJumlahPasien = {
        labels: ["Laki-laki", "Perempuan"],
        data: [
            <?= isset($jumlah_pasien) ? (int)$jumlah_pasien->laki_laki : 0 ?>, 
            <?= isset($jumlah_pasien) ? (int)$jumlah_pasien->perempuan : 0 ?>
        ]
    };
    initChart('chartJumlahPasien', dataJumlahPasien.labels, dataJumlahPasien.data, ['#007bff', '#e83e8c']);
    
    // Initialize chart Puskesmas Wilayah
    const dataPuskesmas = {
        labels: [<?php foreach($puskesmas_wilayah as $p) echo json_encode($p->wilayah).","; ?>],
        data: [<?php foreach($puskesmas_wilayah as $p) echo json_encode($p->total).","; ?>]
    };
    initChart('chartPuskesmasWilayah', dataPuskesmas.labels, dataPuskesmas.data, colors);

    // Memicu event resize window agar Chart.js beradaptasi saat tab berubah di Bootstrap 5
    $('a[data-bs-toggle="tab"]').on('shown.bs.tab', function (e) {
        window.dispatchEvent(new Event('resize'));
    });
});
</script>
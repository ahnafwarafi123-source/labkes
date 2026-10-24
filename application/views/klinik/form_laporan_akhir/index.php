<div class="content-wrapper px-4 pt-4">

    <!-- HEADER -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
        <div>
            <h3 class="text-dark fw-bold mb-1">
                <i class="fas fa-chart-bar text-primary me-2"></i>
                Laporan Hasil Pemeriksaan Laboratorium Klinik
            </h3>
            <p class="text-muted mb-0 small">Pilih bulan dan tahun laporan laboratorium klinik untuk melihat rekapitulasi data lengkap</p>
        </div>
    </div>

    <hr style="border-top: 2px solid #1e3a8a; margin-bottom: 25px;">

    <!-- CONTENT -->
    <div class="container-fluid p-0">

        <!-- FILTER TAHUN -->
        <div class="card border-0 border-top border-primary border-3 shadow-sm rounded-3 mb-4">
            <div class="card-header bg-white py-3">
                <h5 class="card-title fw-bold text-secondary mb-0">
                    <i class="fas fa-filter me-2 text-primary"></i>
                    Filter Tahun Laporan
                </h5>
            </div>
            <div class="card-body p-4">
                <form method="GET" action="">
                    <div class="row align-items-end g-3">
                        <div class="col-12 col-md-5">
                            <label for="tahun-select" class="form-label fw-semibold text-muted small mb-1">PILIH TAHUN</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fas fa-calendar-alt text-secondary"></i></span>
                                <select id="tahun-select" name="tahun" class="form-select">
                                    <?php 
                                    $selected_tahun = $this->input->get('tahun') ?? date('Y');
                                    for($i = date('Y'); $i >= 2020; $i--): ?>
                                        <option value="<?= $i ?>" <?= ($i == $selected_tahun) ? 'selected' : '' ?>>
                                            <?= $i ?>
                                        </option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-12 col-md-3">
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search me-2"></i>Tampilkan
                                </button>
                            </div>
                        </div>

                        <!-- BUTTON LAPORAN TAHUNAN -->
                        <div class="col-12 col-md-4">
                            <div class="d-grid">
                                <button type="submit"
                                    formaction="<?= site_url('klinik/Laporan_hasil_pemeriksaan/laporan_tahunan') ?>"
                                    class="btn btn-success">
                                    <i class="fas fa-chart-line me-2"></i>Laporan Tahunan
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- CARD BULAN -->
        <div class="row g-4">
            <?php
            $bulan = [
                'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
            ];

            $warna = [
                'primary', 'success', 'warning', 'danger', 'info', 'secondary',
                'dark', 'primary', 'success', 'warning', 'danger', 'info'
            ];

            $icon = [
                'fas fa-heartbeat', 'fas fa-stethoscope', 'fas fa-user-md',
                'fas fa-microscope', 'fas fa-vials', 'fas fa-x-ray',
                'fas fa-notes-medical', 'fas fa-hospital', 'fas fa-pills',
                'fas fa-ambulance', 'fas fa-briefcase-medical', 'fas fa-book-medical'
            ];
            ?>

            <?php foreach($bulan as $key => $b): 
                // Tambahkan perlakuan khusus agar kontras text tombol warning terjaga di BS5
                $warna_sekarang = $warna[$key];
                $btn_style = ($warna_sekarang == 'warning') ? 'btn-warning text-dark' : 'btn-'.$warna_sekarang;
                $border_style = 'border-'.$warna_sekarang;
                $text_color = ($warna_sekarang == 'dark') ? 'text-secondary' : 'text-'.$warna_sekarang;
            ?>
            <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                <div class="card border-0 border-top <?= $border_style ?> border-3 shadow-sm h-100 rounded-3" 
                     style="transition: transform 0.2s ease, box-shadow 0.2s ease; cursor: pointer;" 
                     onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 8px 20px rgba(0,0,0,0.08)';" 
                     onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 .125rem .25rem rgba(0,0,0,0.075)';"
                     onclick="window.location='<?= site_url('klinik/form_laporan_akhir/detail/'.($key+1).'/'.$selected_tahun) ?>'">
                    
                    <div class="card-header bg-white py-3 border-bottom-0">
                        <h6 class="card-title fw-bold text-dark mb-0">
                            <i class="far fa-calendar-check me-2 <?= $text_color ?>"></i>
                            <?= $b ?>
                        </h6>
                    </div>

                    <div class="card-body text-center d-flex flex-column justify-content-between py-4">
                        <div class="mb-4">
                            <div class="mx-auto d-flex align-items-center justify-content-center rounded-circle bg-light" style="width: 70px; height: 70px;">
                                <i class="<?= $icon[$key] ?> fs-3 <?= $text_color ?>" style="opacity: 0.9;"></i>
                            </div>
                        </div>

                        <div class="d-grid mt-auto">
                            <a href="<?= site_url('klinik/form_laporan_akhir/detail/'.($key+1).'/'.$selected_tahun) ?>"
                               class="btn <?= $btn_style ?> shadow-xs rounded-2 px-3 py-2 fw-semibold" 
                               style="font-size: 0.85rem;"
                               onclick="event.stopPropagation();">
                                <i class="fas fa-eye me-1"></i>
                                Lihat Laporan
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

    </div>
</div>
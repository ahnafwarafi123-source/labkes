<div class="content-wrapper">

    <section class="content-header">
        <div class="container-fluid">

            <div class="row mb-2">

                <div class="col-sm-6">
                    <h1>
                        <i class="fas fa-chart-line"></i>
                        Laporan Tahunan <?= $tahun ?>
                    </h1>
                </div>

                <div class="col-sm-6 text-right">

                    <a href="<?= site_url('klinik/Laporan_hasil_pemeriksaan/export_excel_tahunan?tahun=' . $tahun) ?>"
                       class="btn btn-success mr-2">
                        <i class="fas fa-file-excel"></i> Export Excel
                    </a>

                    <a href="<?= site_url('klinik/form_laporan_akhir') ?>"
                       class="btn btn-secondary">

                        <i class="fas fa-arrow-left"></i>
                        Kembali

                    </a>

                </div>

            </div>

        </div>
    </section>


    <section class="content">
        <div class="container-fluid">

            <?php
            $kategori_group = [];

            foreach($laporan_tahunan as $row){

                $kategori = $row->kategori;
                $jenis = $row->nama_jenis;
                $bulan = $row->bulan;

                $kategori_group[$kategori][$jenis][$bulan] = $row->total;
            }
            ?>


            <?php foreach($kategori_group as $kategori => $jenis_data): ?>

            <div class="card card-primary shadow mb-4">

                <div class="card-header">

                    <h3 class="card-title">

                        <i class="fas fa-vials"></i>
                        <?= $kategori ?>

                    </h3>

                </div>

                <div class="card-body table-responsive">

                    <table class="table table-bordered table-striped">

                        <thead class="bg-primary text-center">

                            <tr>

                                <th>Jenis Pemeriksaan</th>

                                <th>Jan</th>
                                <th>Feb</th>
                                <th>Mar</th>
                                <th>Apr</th>
                                <th>Mei</th>
                                <th>Jun</th>
                                <th>Jul</th>
                                <th>Ags</th>
                                <th>Sep</th>
                                <th>Okt</th>
                                <th>Nov</th>
                                <th>Des</th>

                                <th>Total</th>

                            </tr>

                        </thead>

                        <tbody>

                            <?php foreach($jenis_data as $jenis => $bulan_data): ?>

                            <tr>

                                <td>
                                    <strong><?= $jenis ?></strong>
                                </td>

                            <?php 
                                $total_setahun = 0;
                                for($i=1; $i<=12; $i++): 
                                    $val = isset($bulan_data[$i]) ? $bulan_data[$i] : 0;
                                    $total_setahun += $val;
                            ?>

                                <td class="text-center">

                                    <?= $val ?>

                                </td>

                                <?php endfor; ?>

                                <td class="text-center font-weight-bold">
                                    <?= $total_setahun ?>
                                </td>

                            </tr>

                            <?php endforeach; ?>

                        </tbody>

                    </table>

                </div>

            </div>

            <?php endforeach; ?>

        </div>
    </section>

</div>
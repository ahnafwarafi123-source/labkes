    <div class="content-wrapper">
        <section class="content pt-4">
            <div class="container-fluid">
                <?php
                setlocale(LC_TIME, 'id_ID');
                date_default_timezone_set('Asia/Jakarta');

                $hariIndo = ['Sunday' => 'Minggu', 'Monday' => 'Senin', 'Tuesday' => 'Selasa', 'Wednesday' => 'Rabu', 'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu'];
                $bulanIndo = ['January' => 'Januari', 'February' => 'Februari', 'March' => 'Maret', 'April' => 'April', 'May' => 'Mei', 'June' => 'Juni', 'July' => 'Juli', 'August' => 'Agustus', 'September' => 'September', 'October' => 'Oktober', 'November' => 'November', 'December' => 'Desember'];

                $hari = $hariIndo[date('l')];
                $tanggal = date('d');
                $bulan = $bulanIndo[date('F')];
                $tahun = date('Y');
                $tanggalLengkap = "$hari, $tanggal $bulan $tahun";
                ?>
                <h3 class="text-dark fw-bold mb-4 d-flex justify-content-between align-items-center">
                    <span>RIWAYAT KUNJUNGAN PASIEN KLINIK <?= !empty($this->input->get('search')) ? '<span class="badge bg-primary text-sm ms-2">Pencarian: "' . htmlspecialchars($this->input->get('search')) . '"</span>' : '' ?></span>
                    <small class="text-muted fw-normal"><?= $tanggalLengkap ?></small>
                </h3>
                <hr style="border-top: 2px solid #1e3a8a; margin-bottom: 20px;">

                <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
                    <div></div> <!-- Spacer -->

                    <!-- Form Filter & Pencarian -->
                    <form method="GET" action="<?= site_url('klinik/uji_rekam_klinik') ?>" class="d-flex align-items-center gap-2">
                        <div class="d-flex align-items-center gap-1">
                            <span class="text-muted small">Show:</span>
                            <select name="limit" class="form-select form-select-sm" style="width: auto;" onchange="this.form.submit()">
                                <option value="10" <?= $limit == 10 ? 'selected' : '' ?>>10</option>
                                <option value="20" <?= $limit == 20 ? 'selected' : '' ?>>20</option>
                                <option value="50" <?= $limit == 50 ? 'selected' : '' ?>>50</option>
                            </select>
                        </div>
                        <div class="input-group input-group-sm" style="width: 250px;">
                            <input type="text" name="search" class="form-control" placeholder="Cari RM, nama, NIK..." value="<?= html_escape($search) ?>">
                            <button class="btn btn-primary" type="submit">
                                <i class="fas fa-search"></i>
                            </button>
                            <?php if (!empty($search)): ?>
                                <a href="<?= site_url('klinik/uji_rekam_klinik') ?>" class="btn btn-outline-secondary" title="Reset">
                                    <i class="fas fa-times"></i>
                                </a>
                            <?php endif; ?>
                        </div>
                    </form>
                </div>

                <div class="card shadow-sm w-100">
                    <div class="card-body p-3">
                        <div class="table-responsive">
                            <table id="tabel-kunjungan" class="table table-bordered table-striped table-hover nowrap"
                                style="width:100%">
                                <thead style="background-color: #d1e3ff;">
                                    <tr>
                                        <th width="5%" class="text-center">No</th>
                                        <th width="15%">No. RM</th>
                                        <th width="30%">Nama Pasien</th>
                                        <th width="20%">NIK</th>
                                        <th width="10%">Jenis Kelamin</th>
                                        <th width="10%" class="text-center">Total Uji Lab</th>
                                        <th width="10%" class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($pasien)): ?>
                                        <?php 
                                        $no = ($page - 1) * $limit + 1;
                                        foreach ($pasien as $p): 
                                        ?>
                                            <tr>
                                                <td class="text-center"><?= $no++ ?></td>
                                                <td><?= html_escape($p->no_rm) ?></td>
                                                <td><?= html_escape($p->nama_pasien) ?></td>
                                                <td><?= html_escape($p->nik) ?></td>
                                                <td><?= ($p->gender == 'Laki-laki') ? 'L' : 'P' ?></td>
                                                <td class="text-center">
                                                    <span class="badge bg-success p-2 rounded-pill"><?= html_escape($p->total_uji) ?> Kali</span>
                                                </td>
                                                <td class="text-center">
                                                    <a href="<?= site_url('klinik/uji_rekam_klinik/detail/' . $p->id_pasien) ?>"
                                                        class="btn btn-sm btn-primary text-white" title="Lihat Riwayat Pemeriksaan">
                                                        <i class="fas fa-eye me-1"></i>Lihat Riwayat
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="7" class="text-center text-muted py-3">Tidak ada data riwayat kunjungan pasien klinik</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>

                        <!-- Info & Pagination -->
                        <div class="d-flex flex-wrap justify-content-between align-items-center mt-3 px-2">
                            <div class="text-muted small">
                                <?php if ($total > 0): ?>
                                    Menampilkan <?= min($total, ($page - 1) * $limit + 1) ?> - <?= min($total, $page * $limit) ?> dari <?= $total ?> data
                                <?php else: ?>
                                    Menampilkan 0 data
                                <?php endif; ?>
                            </div>
                            <?php if ($total > $limit): ?>
                                <nav>
                                    <ul class="pagination pagination-sm mb-0">
                                        <!-- Prev Page -->
                                        <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
                                            <a class="page-link" href="<?= site_url('klinik/uji_rekam_klinik?page=' . ($page - 1) . '&limit=' . $limit . ($search ? '&search=' . urlencode($search) : '')) ?>">
                                                &larr; Prev
                                            </a>
                                        </li>

                                        <!-- Pages Link -->
                                        <?php
                                        $totalPages = ceil($total / $limit);
                                        $startPage = max(1, $page - 2);
                                        $endPage = min($totalPages, $page + 2);

                                        if ($startPage > 1): ?>
                                            <li class="page-item">
                                                <a class="page-link" href="<?= site_url('klinik/uji_rekam_klinik?page=1&limit=' . $limit . ($search ? '&search=' . urlencode($search) : '')) ?>">1</a>
                                            </li>
                                            <?php if ($startPage > 2): ?>
                                                <li class="page-item disabled"><span class="page-link">...</span></li>
                                            <?php endif; ?>
                                        <?php endif; ?>

                                        <?php for ($i = $startPage; $i <= $endPage; $i++): ?>
                                            <li class="page-item <?= $page == $i ? 'active' : '' ?>">
                                                <a class="page-link" href="<?= site_url('klinik/uji_rekam_klinik?page=' . $i . '&limit=' . $limit . ($search ? '&search=' . urlencode($search) : '')) ?>">
                                                    <?= $i ?>
                                                </a>
                                            </li>
                                        <?php endfor; ?>

                                        <?php if ($endPage < $totalPages): ?>
                                            <?php if ($endPage < $totalPages - 1): ?>
                                                <li class="page-item disabled"><span class="page-link">...</span></li>
                                            <?php endif; ?>
                                            <li class="page-item">
                                                <a class="page-link" href="<?= site_url('klinik/uji_rekam_klinik?page=' . $totalPages . '&limit=' . $limit . ($search ? '&search=' . urlencode($search) : '')) ?>"><?= $totalPages ?></a>
                                            </li>
                                        <?php endif; ?>

                                        <!-- Next Page -->
                                        <li class="page-item <?= $page >= $totalPages ? 'disabled' : '' ?>">
                                            <a class="page-link" href="<?= site_url('klinik/uji_rekam_klinik?page=' . ($page + 1) . '&limit=' . $limit . ($search ? '&search=' . urlencode($search) : '')) ?>">
                                                Next &rarr;
                                            </a>
                                        </li>
                                    </ul>
                                </nav>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            if (typeof jQuery !== 'undefined' && $.fn.DataTable && $('#tabel-kunjungan tbody tr td:not([colspan])').length > 0) {
                $('#tabel-kunjungan').DataTable({
                    responsive: true,
                    autoWidth: false,
                    paging: false,
                    searching: false,
                    info: false,
                    order: []
                });
            }
        });
    </script>
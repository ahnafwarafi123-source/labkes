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
            <?php
            // Siapkan query string untuk mempertahankan filter saat paginasi
            $query_string = '&limit=' . $limit;
            if (!empty($search)) {
                $query_string .= '&search=' . urlencode($search);
            }
            if (!empty($tgl_mulai)) {
                $query_string .= '&tgl_mulai=' . urlencode($tgl_mulai);
            }
            ?>
            <h3 class="text-dark fw-bold mb-4 d-flex justify-content-between align-items-center flex-wrap gap-2">
                <span>
                    DATA KUNJUNGAN PASIEN REKAM MEDIS
                    <?= !empty($this->input->get('search')) ? '<span class="badge bg-primary text-sm ms-2">Pencarian: "' . htmlspecialchars($this->input->get('search')) . '"</span>' : '' ?>
                    <?= !empty($tgl_mulai) ? '<span class="badge bg-info text-sm ms-2">Tgl Pendaftaran: ' . htmlspecialchars(date('d-m-Y', strtotime($tgl_mulai))) . '</span>' : '' ?>
                </span>
                <small class="text-muted fw-normal fs-6"><?= $tanggalLengkap ?></small>
            </h3>
            <hr style="border-top: 2px solid #1e3a8a; margin-bottom: 20px;">

            <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
                <div></div> <!-- Spacer -->

                <!-- Form Filter & Pencarian -->
                <form method="GET" action="<?= site_url('klinik/uji_rekam_medis') ?>" class="d-flex flex-wrap align-items-center gap-2 w-100 justify-content-end">
                    <div class="d-flex align-items-center gap-1">
                        <span class="text-muted small">Show:</span>
                        <select name="limit" class="form-select form-select-sm" style="width: auto;" onchange="this.form.submit()">
                            <option value="10" <?= $limit == 10 ? 'selected' : '' ?>>10</option>
                            <option value="20" <?= $limit == 20 ? 'selected' : '' ?>>20</option>
                            <option value="50" <?= $limit == 50 ? 'selected' : '' ?>>50</option>
                            <option value="all" <?= $limit === 'all' ? 'selected' : '' ?>>All</option>
                        </select>
                    </div>

                    <div class="d-flex align-items-center gap-1 flex-wrap">
                        <input type="date" name="tgl_mulai" class="form-control form-control-sm" style="width: 130px;" value="<?= html_escape($tgl_mulai) ?>">
                    </div>

                    <div class="input-group input-group-sm" style="width: 220px;">
                        <input type="text" name="search" class="form-control" placeholder="Cari RM, nama, NIK..." value="<?= html_escape($search) ?>">
                        <button class="btn btn-primary" type="submit">
                            <i class="fas fa-search"></i>
                        </button>
                        <?php if (!empty($search) || !empty($tgl_mulai)): ?>
                            <a href="<?= site_url('klinik/uji_rekam_medis') ?>" class="btn btn-outline-secondary" title="Reset">
                                <i class="fas fa-times"></i>
                            </a>
                        <?php endif; ?>
                    </div>
                </form>
            </div>

            <div class="card shadow-sm w-100">
                <div class="card-body table-responsive p-3">
                    <table id="tabel-kunjungan" class="table table-bordered table-striped table-hover nowrap"
                        style="width:100%">
                        <thead style="background-color: #d1e3ff;">
                            <tr>
                                <th width="5%" class="text-center">No</th>
                                <th width="12%">No. RM</th>
                                <th width="20%">Nama Pasien</th>
                                <th width="13%">NIK</th>
                                <th width="8%">JK</th>
                                <th width="15%">Tgl Pendaftaran</th>
                                <th width="15%" class="text-center">Riwayat Kunjungan</th>
                                <th width="12%" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($pasien)): ?>
                                <?php
                                $no = ($page - 1) * ($limit === 'all' ? 0 : (int)$limit) + 1;
                                foreach ($pasien as $p):
                                ?>
                                    <tr>
                                        <td class="text-center"><?= $no++ ?></td>
                                        <td><?= html_escape($p->no_rm) ?></td>
                                        <td><?= html_escape($p->nama_pasien) ?></td>
                                        <td><?= html_escape($p->nik) ?></td>
                                        <td><?= ($p->gender == 'Laki-laki') ? 'L' : 'P' ?></td>
                                        <td><?= !empty($p->created_at) ? date('d-m-Y', strtotime($p->created_at)) : '-' ?></td>
                                        <td class="text-center">
                                            <?php if (isset($p->total_kunjungan) && $p->total_kunjungan > 0): ?>
                                                <span class="badge bg-success p-2 rounded-pill"><?= html_escape($p->total_kunjungan) ?> Kali</span>
                                            <?php else: ?>
                                                <span class="badge bg-secondary p-2 rounded-pill">Belum ada</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center">
                                            <div class="d-flex gap-2 justify-content-center">
                                                <a href="<?= site_url('klinik/uji_rekam_medis/detail/' . $p->id_pasien) ?>"
                                                    class="btn btn-sm btn-info text-white" title="Pengisian Detail Kunjungan">
                                                    <i class="fas fa-eye me-1"></i>Lihat Kunjungan
                                                </a>
                                                <a href="<?= site_url('klinik/uji_rekam_medis/tambah_kunjungan/' . $p->id_pasien) ?>"
                                                    class="btn btn-sm btn-primary text-white" title="Pengisian Detail Kunjungan">
                                                    <i class="fas fa-plus me-1"></i> Tambah SOAP
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="8" class="text-center text-muted py-3">Tidak ada data kunjungan pasien rekam medis</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>

                    <!-- Info & Pagination -->
                    <div class="d-flex flex-wrap justify-content-between align-items-center mt-3 px-2">
                        <div class="text-muted small">
                            <?php if ($total > 0): ?>
                                <?php if ($limit === 'all'): ?>
                                    Menampilkan semua data (<?= $total ?> data)
                                <?php else: ?>
                                    Menampilkan <?= min($total, ($page - 1) * $limit + 1) ?> - <?= min($total, $page * $limit) ?> dari <?= $total ?> data
                                <?php endif; ?>
                            <?php else: ?>
                                Menampilkan 0 data
                            <?php endif; ?>
                        </div>
                        <?php if ($limit !== 'all' && $total > $limit): ?>
                            <nav>
                                <ul class="pagination pagination-sm mb-0">
                                    <!-- Prev Page -->
                                    <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
                                        <a class="page-link" href="<?= site_url('klinik/uji_rekam_medis?page=' . ($page - 1) . $query_string) ?>">
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
                                            <a class="page-link" href="<?= site_url('klinik/uji_rekam_medis?page=1' . $query_string) ?>">1</a>
                                        </li>
                                        <?php if ($startPage > 2): ?>
                                            <li class="page-item disabled"><span class="page-link">...</span></li>
                                        <?php endif; ?>
                                    <?php endif; ?>

                                    <?php for ($i = $startPage; $i <= $endPage; $i++): ?>
                                        <li class="page-item <?= $page == $i ? 'active' : '' ?>">
                                            <a class="page-link" href="<?= site_url('klinik/uji_rekam_medis?page=' . $i . $query_string) ?>">
                                                <?= $i ?>
                                            </a>
                                        </li>
                                    <?php endfor; ?>

                                    <?php if ($endPage < $totalPages): ?>
                                        <?php if ($endPage < $totalPages - 1): ?>
                                            <li class="page-item disabled"><span class="page-link">...</span></li>
                                        <?php endif; ?>
                                        <li class="page-item">
                                            <a class="page-link" href="<?= site_url('klinik/uji_rekam_medis?page=' . $totalPages . $query_string) ?>"><?= $totalPages ?></a>
                                        </li>
                                    <?php endif; ?>

                                    <!-- Next Page -->
                                    <li class="page-item <?= $page >= $totalPages ? 'disabled' : '' ?>">
                                        <a class="page-link" href="<?= site_url('klinik/uji_rekam_medis?page=' . ($page + 1) . $query_string) ?>">
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
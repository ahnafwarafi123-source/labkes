<div class="content-wrapper px-4 pt-4">
	<?php
	setlocale(LC_TIME, 'id_ID');
	date_default_timezone_set('Asia/Jakarta');
	$hariIndo = [
		'Sunday' => 'Minggu','Monday' => 'Senin','Tuesday' => 'Selasa',
		'Wednesday' => 'Rabu','Thursday' => 'Kamis','Friday' => 'Jumat','Saturday' => 'Sabtu'
	];
	$bulanIndo = [
		'January' => 'Januari','February' => 'Februari','March' => 'Maret',
		'April' => 'April','May' => 'Mei','June' => 'Juni','July' => 'Juli',
		'August' => 'Agustus','September' => 'September','October' => 'Oktober',
		'November' => 'November','December' => 'Desember'
	];
	$hari = $hariIndo[date('l')];
	$tanggalLengkap = "$hari, " . date('d') . " " . $bulanIndo[date('F')] . " " . date('Y');
	?>

	<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
		<div>
			<h3 class="text-dark fw-bold mb-1">DATA DOKTER PEMERIKSA (REKAM MEDIS)</h3>
			<p class="text-muted mb-0 small">Kelola informasi data dokter pemeriksa yang berwenang pada klinik rekam medis</p>
		</div>
		<div class="mt-2 mt-md-0">
			<span class="badge bg-light text-dark border px-3 py-2 rounded-pill shadow-xs">
				<i class="far fa-calendar-alt text-success me-2"></i><?= $tanggalLengkap ?>
			</span>
		</div>
	</div>

	<hr style="border-top: 2px solid #1e3a8a; margin-bottom: 25px;">

	<div class="mb-4">
		<a href="<?= site_url('klinik/petugas_dokter/create') ?>" class="btn btn-success px-4 py-2 shadow-sm" style="background-color: #0d3b30; border-color: #0d3b30;">
			<i class="fas fa-user-plus me-2"></i> Tambah Data
		</a>
	</div>

	<div class="card border-0 shadow-sm rounded-3 overflow-hidden">
		<div class="card-body p-0">
			<div class="table-responsive p-3">
				<table id="tabel-dokter" class="table table-hover align-middle nowrap" style="width:100%">
					<thead class="table-light text-secondary">
						<tr>
							<th width="8%" class="text-center">No</th>
							<th>Nama Dokter</th>
							<th>Spesialisasi</th>
							<th>No. SIP</th>
							<th width="15%" class="text-center">Aksi</th>
						</tr>
					</thead>
					<tbody>
						<?php if (!empty($dokters)): $no = 1; foreach ($dokters as $d): ?>
						<tr>
							<td class="text-center fw-medium"><?= $no++ ?></td>
							<td class="fw-semibold text-dark"><?= htmlspecialchars($d->nama_dokter) ?></td>
							<td>
								<span class="badge bg-light text-secondary border px-2.5 py-1.5 rounded text-wrap">
									<?= htmlspecialchars($d->spesialisasi ?: '-') ?>
								</span>
							</td>
							<td class="fw-medium text-muted">
								<?= htmlspecialchars($d->sip ?: '-') ?>
							</td>
							<td class="text-center">
								<div class="btn-group" role="group">
									<a href="<?= site_url('klinik/petugas_dokter/edit/' . $d->id_dokter) ?>" class="btn btn-sm btn-outline-warning" title="Edit Data">
										<i class="fas fa-edit"></i> Edit
									</a>
									<a href="<?= site_url('klinik/petugas_dokter/delete/' . $d->id_dokter) ?>"
									   class="btn btn-sm btn-outline-danger"
									   title="Hapus Data"
									   onclick="return confirm('Yakin ingin menghapus dokter <?= htmlspecialchars($d->nama_dokter) ?>?')">
										<i class="fas fa-trash"></i> Hapus
									</a>
								</div>
							</td>
						</tr>
						<?php endforeach; else: ?>
						<tr>
							<td colspan="5" class="text-center py-4 text-muted">
								<i class="fas fa-info-circle me-1"></i> Belum ada data dokter pemeriksa.
							</td>
						</tr>
						<?php endif; ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>

<!-- jQuery and DataTables Initializer -->
<script>
  $(document).ready(function() {
      if ($.fn.DataTable && !$.fn.DataTable.isDataTable('#tabel-dokter')) {
          $('#tabel-dokter').DataTable({
              responsive: true,
              autoWidth: false,
              language: {
                  search: "_INPUT_",
                  searchPlaceholder: "Cari...",
                  paginate: {
                      previous: "←",
                      next: "→"
                  },
                  zeroRecords: "Data tidak ditemukan",
                  info: "Menampilkan _START_ - _END_ dari _TOTAL_ data",
                  lengthMenu: "Tampilkan _MENU_ data"
              }
          });
      }
  });
</script>
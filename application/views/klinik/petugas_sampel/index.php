<div class="content-wrapper px-4 pt-4">
  <?php
  setlocale(LC_TIME, 'id_ID');
  date_default_timezone_set('Asia/Jakarta');
  $hariIndo = ['Sunday'=>'Minggu','Monday'=>'Senin','Tuesday'=>'Selasa','Wednesday'=>'Rabu','Thursday'=>'Kamis','Friday'=>'Jumat','Saturday'=>'Sabtu'];
  $bulanIndo = ['January'=>'Januari','February'=>'Februari','March'=>'Maret','April'=>'April','May'=>'Mei','June'=>'Juni','July'=>'Juli','August'=>'Agustus','September'=>'September','October'=>'Oktober','November'=>'November','December'=>'Desember'];
  $hari = $hariIndo[date('l')];
  $tanggalLengkap = "$hari, ".date('d')." ".$bulanIndo[date('F')]." ".date('Y');
  ?>
  
  <h3 class="text-dark fw-bold mb-4 d-flex justify-content-between align-items-center">
    <span>DATA PETUGAS SAMPEL KLINIK</span>
    <small class="text-muted fw-normal"><?= $tanggalLengkap ?></small>
  </h3>
  <hr style="border-top: 2px solid #1e3a8a; margin-bottom: 20px;">

  <div class="mb-3">
    <a href="<?= site_url('klinik/petugas_sampel/create') ?>" class="btn btn-primary">
      <i class="fas fa-user-plus"></i> Tambah Petugas
    </a>
  </div>

  <div class="card shadow-sm w-100">
    <div class="card-body table-responsive p-3">
      <table id="tabel-petugas" class="table table-bordered table-striped table-hover nowrap" style="width:100%">
        <thead class="bg-light">
          <tr>
            <th width="5%">No</th>
            <th>Nama Petugas</th>
            <th>Jabatan</th>
            <th width="15%" class="text-center">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php if (!empty($petugas)): ?>
            <?php $no = 1; foreach ($petugas as $p): ?>
              <tr>
                <td class="text-center"><?= $no++ ?></td>
                <td><?= htmlspecialchars($p->nama_petugas) ?></td>
                <td><?= htmlspecialchars($p->jabatan) ?></td>
                <td class="text-center">
                  <a href="<?= site_url('klinik/petugas_sampel/edit/'.$p->id_petugas) ?>" class="btn btn-sm btn-warning">
                    <i class="fas fa-edit"></i> Edit
                  </a>
                  <a href="<?= site_url('klinik/petugas_sampel/delete/'.$p->id_petugas) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin hapus petugas ini?')">
                    <i class="fas fa-trash"></i> Hapus
                  </a>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr><td colspan="4" class="text-center text-muted">Belum ada data petugas.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

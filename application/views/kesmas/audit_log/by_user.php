<div class="container my-4">
    <h2><?php echo $title; ?></h2>

    <div class="card">
        <div class="card-body">
            <p><strong>User:</strong> <?php echo $user['nama'] ?? 'Unknown'; ?></p>

            <div class="table-responsive">
                <table class="table table-sm table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>Waktu</th>
                            <th>Tabel</th>
                            <th>Aksi</th>
                            <th>Deskripsi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($logs as $log): ?>
                        <tr>
                            <td><small><?php echo date('d/m/Y H:i:s', strtotime($log['tgl_aktivitas'])); ?></small></td>
                            <td><code><?php echo $log['tabel_terkait']; ?></code></td>
                            <td><span class="badge badge-info"><?php echo $log['aksi']; ?></span></td>
                            <td><small><?php echo $log['keterangan_perubahan']; ?></small></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <a href="<?php echo base_url('k-panel/audit_log/dashboard'); ?>" class="btn btn-secondary mt-3">Kembali</a>
</div>

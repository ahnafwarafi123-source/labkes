<div class="container my-4">
    <h2><?php echo $title; ?></h2>

    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="<?php echo base_url('k-panel/audit_log/by_date'); ?>" class="form-inline">
                <label class="mr-2">Dari:</label>
                <input type="date" name="start_date" class="form-control mr-3" value="<?php echo $start_date ?? ''; ?>">
                
                <label class="mr-2">Sampai:</label>
                <input type="date" name="end_date" class="form-control mr-3" value="<?php echo $end_date ?? ''; ?>">
                
                <button type="submit" class="btn btn-primary">Filter</button>
            </form>
        </div>
    </div>

    <?php if (!empty($logs)): ?>
    <div class="card">
        <div class="card-header">
            <h5>Activity Log (<?php echo count($logs); ?> records)</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-sm table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>Waktu</th>
                            <th>User</th>
                            <th>Tabel</th>
                            <th>Aksi</th>
                            <th>Deskripsi</th>
                            <th>IP</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($logs as $log): ?>
                        <tr>
                            <td><small><?php echo date('d/m/Y H:i:s', strtotime($log['tgl_aktivitas'])); ?></small></td>
                            <td><small><?php echo $log['user_id']; ?></small></td>
                            <td><code><?php echo $log['tabel_terkait']; ?></code></td>
                            <td>
                                <span class="badge <?php 
                                    echo $log['aksi'] === 'INSERT' ? 'badge-success' : 
                                         ($log['aksi'] === 'UPDATE' ? 'badge-info' : 
                                         ($log['aksi'] === 'DELETE' ? 'badge-danger' : 'badge-secondary')); 
                                ?>">
                                    <?php echo $log['aksi']; ?>
                                </span>
                            </td>
                            <td><small><?php echo $log['keterangan_perubahan']; ?></small></td>
                            <td><small><?php echo $log['ip_address']; ?></small></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php else: ?>
    <div class="alert alert-info">Silahkan pilih tanggal untuk melihat log</div>
    <?php endif; ?>
</div>

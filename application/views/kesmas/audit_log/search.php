<div class="container my-4">
    <h2><?php echo $title; ?></h2>

    <div class="card mb-4">
        <div class="card-body">
            <form method="POST" action="<?php echo base_url('k-panel/audit_log/search'); ?>" class="form-inline">
                <input type="text" name="keyword" class="form-control mr-3" placeholder="Search keyword..." value="<?php echo $keyword ?? ''; ?>">
                <button type="submit" class="btn btn-primary">Search</button>
            </form>
        </div>
    </div>

    <?php if (!empty($logs)): ?>
    <div class="card">
        <div class="card-header">
            <h5>Search Results (<?php echo count($logs); ?> records)</h5>
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
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($logs as $log): ?>
                        <tr>
                            <td><small><?php echo date('d/m/Y H:i:s', strtotime($log['tgl_aktivitas'])); ?></small></td>
                            <td><small><?php echo $log['user_id']; ?></small></td>
                            <td><code><?php echo $log['tabel_terkait']; ?></code></td>
                            <td><span class="badge badge-success"><?php echo $log['aksi']; ?></span></td>
                            <td><small><?php echo $log['keterangan_perubahan']; ?></small></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php else: ?>
    <div class="alert alert-info">Tidak ada hasil untuk pencarian: "<?php echo $keyword; ?>"</div>
    <?php endif; ?>
</div>

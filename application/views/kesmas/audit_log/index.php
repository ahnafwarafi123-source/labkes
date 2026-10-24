<div class="container my-4">
    <h2><?php echo $title; ?></h2>

    <div class="card">
        <div class="card-header">
            <h5>Activity Log History</h5>
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
                            <th>Keterangan</th>
                            <th>IP</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($logs as $log): ?>
                        <tr>
                            <td><small><?php echo date('d/m/Y H:i:s', strtotime($log['tgl_aktivitas'])); ?></small></td>
                            <td><small><?php echo $log['user_id']; ?></small></td>
                            <td><small><code><?php echo $log['tabel_terkait']; ?></code></small></td>
                            <td>
                                <span class="badge <?php 
                                    echo $log['aksi'] === 'INSERT' ? 'badge-success' : 
                                         ($log['aksi'] === 'UPDATE' ? 'badge-info' : 
                                         ($log['aksi'] === 'DELETE' ? 'badge-danger' : 'badge-secondary')); 
                                ?>">
                                    <?php echo $log['aksi']; ?>
                                </span>
                            </td>
                            <td><small><?php echo strlen($log['keterangan_perubahan']) > 50 ? substr($log['keterangan_perubahan'], 0, 50) . '...' : $log['keterangan_perubahan']; ?></small></td>
                            <td><small><?php echo $log['ip_address']; ?></small></td>
                            <td>
                                <a href="<?php echo base_url('k-panel/audit_log/detail/' . $log['id_permintaan']); ?>" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <nav class="mt-3">
                <ul class="pagination justify-content-center">
                    <?php if ($offset > 0): ?>
                    <li class="page-item">
                        <a class="page-link" href="?offset=<?php echo $offset - $limit; ?>">Previous</a>
                    </li>
                    <?php endif; ?>

                    <?php if (count($logs) == $limit): ?>
                    <li class="page-item">
                        <a class="page-link" href="?offset=<?php echo $offset + $limit; ?>">Next</a>
                    </li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </div>
</div>

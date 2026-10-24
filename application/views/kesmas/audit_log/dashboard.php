<div class="container my-4">
    <h2><?php echo $title; ?></h2>

    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h5>Recent Activities</h5>
                    <h2><?php echo count($recent_activities); ?></h2>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5>Activity by Action Type</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group">
                        <?php foreach ($action_stats as $stat): ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span>
                                <span class="badge <?php 
                                    echo $stat['aksi'] === 'INSERT' ? 'badge-success' : 
                                         ($stat['aksi'] === 'UPDATE' ? 'badge-info' : 
                                         ($stat['aksi'] === 'DELETE' ? 'badge-danger' : 'badge-secondary')); 
                                ?>">
                                    <?php echo $stat['aksi']; ?>
                                </span>
                            </span>
                            <span class="badge badge-primary badge-pill"><?php echo $stat['jumlah']; ?></span>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5>Activity by Table</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group">
                        <?php foreach ($table_stats as $stat): ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <code><?php echo $stat['tabel_terkait']; ?></code>
                            <span class="badge badge-info badge-pill"><?php echo $stat['jumlah']; ?></span>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-header">
            <h5>Recent Activity (20 Latest)</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th>Waktu</th>
                            <th>User</th>
                            <th>Tabel</th>
                            <th>Aksi</th>
                            <th>Deskripsi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recent_activities as $activity): ?>
                        <tr>
                            <td><small><?php echo date('d/m/Y H:i:s', strtotime($activity['tgl_aktivitas'])); ?></small></td>
                            <td><small><?php echo $activity['nama'] ?? $activity['user_id']; ?></small></td>
                            <td><code><?php echo $activity['tabel_terkait']; ?></code></td>
                            <td><span class="badge badge-success"><?php echo $activity['aksi']; ?></span></td>
                            <td><small><?php echo substr($activity['keterangan_perubahan'], 0, 40); ?>...</small></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

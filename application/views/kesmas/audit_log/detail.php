<div class="container my-4">
    <h2><?php echo $title; ?></h2>

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Log Detail</h5>
        </div>
        <div class="card-body">
            <table class="table table-borderless">
                <tr>
                    <th style="width: 150px;">ID:</th>
                    <td><?php echo $log['id_permintaan']; ?></td>
                </tr>
                <tr>
                    <th>Waktu:</th>
                    <td><?php echo date('d/m/Y H:i:s', strtotime($log['tgl_aktivitas'])); ?></td>
                </tr>
                <tr>
                    <th>User ID:</th>
                    <td><?php echo $log['user_id']; ?></td>
                </tr>
                <tr>
                    <th>Tabel:</th>
                    <td><code><?php echo $log['tabel_terkait']; ?></code></td>
                </tr>
                <tr>
                    <th>Aksi:</th>
                    <td>
                        <span class="badge badge-success"><?php echo $log['aksi']; ?></span>
                    </td>
                </tr>
                <tr>
                    <th>Deskripsi:</th>
                    <td><?php echo $log['keterangan_perubahan']; ?></td>
                </tr>
                <tr>
                    <th>IP Address:</th>
                    <td><?php echo $log['ip_address']; ?></td>
                </tr>
            </table>

            <div class="mt-3">
                <a href="<?php echo base_url('k-panel/audit_log'); ?>" class="btn btn-secondary">Kembali</a>
            </div>
        </div>
    </div>
</div>

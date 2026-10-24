<div class="container-fluid">
	<div class="row mb-4">
		<div class="col-md-12">
			<h1 class="page-title">Security Dashboard</h1>
			<p class="text-muted">Monitor login attempts, user activities, and pending approvals</p>
		</div>
	</div>

	<!-- Stats Cards -->
	<div class="row mb-4">
		<div class="col-md-3">
			<div class="card">
				<div class="card-body">
					<div class="d-flex justify-content-between align-items-center">
						<div>
							<h6 class="card-title text-muted">Today's Logins</h6>
							<h3 class="mb-0">
								<?php 
								$count = 0;
								foreach ($login_stats as $stat) {
									if ($stat['status'] == 'success') $count = $stat['count'];
								}
								echo $count;
								?>
							</h3>
						</div>
						<i class="fas fa-sign-in-alt fa-3x text-primary" style="opacity:0.3;"></i>
					</div>
				</div>
			</div>
		</div>

		<div class="col-md-3">
			<div class="card">
				<div class="card-body">
					<div class="d-flex justify-content-between align-items-center">
						<div>
							<h6 class="card-title text-muted">Failed Logins</h6>
							<h3 class="mb-0 text-danger">
								<?php 
								$count = 0;
								foreach ($login_stats as $stat) {
									if ($stat['status'] == 'failed') $count = $stat['count'];
								}
								echo $count;
								?>
							</h3>
						</div>
						<i class="fas fa-ban fa-3x text-danger" style="opacity:0.3;"></i>
					</div>
				</div>
			</div>
		</div>

		<div class="col-md-3">
			<div class="card">
				<div class="card-body">
					<div class="d-flex justify-content-between align-items-center">
						<div>
							<h6 class="card-title text-muted">Activities Today</h6>
							<h3 class="mb-0">
								<?php echo count($activity_stats); ?>
							</h3>
						</div>
						<i class="fas fa-tasks fa-3x text-success" style="opacity:0.3;"></i>
					</div>
				</div>
			</div>
		</div>

		<div class="col-md-3">
			<div class="card">
				<div class="card-body">
					<div class="d-flex justify-content-between align-items-center">
						<div>
							<h6 class="card-title text-muted">Pending Items</h6>
							<h3 class="mb-0 text-warning">
								<?php 
								$count = 0;
								foreach ($pending_summary as $item) {
									if ($item['status'] == 'pending') $count = $item['count'];
								}
								echo $count;
								?>
							</h3>
						</div>
						<i class="fas fa-hourglass-half fa-3x text-warning" style="opacity:0.3;"></i>
					</div>
				</div>
			</div>
		</div>
	</div>

	<!-- Recent Logs Section -->
	<div class="row">
		<div class="col-md-6">
			<div class="card">
				<div class="card-header bg-light">
					<h5 class="mb-0">Recent Login Logs</h5>
				</div>
				<div class="card-body">
					<?php if (!empty($login_logs)): ?>
						<table class="table table-sm table-hover">
							<thead>
								<tr>
									<th>Username</th>
									<th>Status</th>
									<th>Time</th>
									<th>IP</th>
								</tr>
							</thead>
							<tbody>
								<?php foreach ($login_logs as $log): ?>
									<tr>
										<td><?php echo $log['username']; ?></td>
										<td>
											<?php if ($log['status'] == 'success'): ?>
												<span class="badge bg-success">Success</span>
											<?php elseif ($log['status'] == 'failed'): ?>
												<span class="badge bg-danger">Failed</span>
											<?php else: ?>
												<span class="badge bg-warning">Locked</span>
											<?php endif; ?>
										</td>
										<td><?php echo substr($log['login_at'], 11, 5); ?></td>
										<td><small><?php echo $log['ip_address']; ?></small></td>
									</tr>
								<?php endforeach; ?>
							</tbody>
						</table>
						<a href="<?php echo base_url('k-panel/security/login_logs'); ?>" class="btn btn-sm btn-primary">View All</a>
					<?php else: ?>
						<p class="text-muted">No login logs available</p>
					<?php endif; ?>
				</div>
			</div>
		</div>

		<div class="col-md-6">
			<div class="card">
				<div class="card-header bg-light">
					<h5 class="mb-0">Recent Activities</h5>
				</div>
				<div class="card-body">
					<?php if (!empty($recent_activities)): ?>
						<table class="table table-sm table-hover">
							<thead>
								<tr>
									<th>User</th>
									<th>Action</th>
									<th>Module</th>
									<th>Time</th>
								</tr>
							</thead>
							<tbody>
								<?php foreach ($recent_activities as $activity): ?>
									<tr>
										<td><small><?php echo $activity['user_id']; ?></small></td>
										<td><span class="badge bg-info"><?php echo $activity['action']; ?></span></td>
										<td><?php echo $activity['module']; ?></td>
										<td><small><?php echo substr($activity['created_at'], 11, 5); ?></small></td>
									</tr>
								<?php endforeach; ?>
							</tbody>
						</table>
						<a href="<?php echo base_url('k-panel/security/activity_logs'); ?>" class="btn btn-sm btn-primary">View All</a>
					<?php else: ?>
						<p class="text-muted">No activities available</p>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>

	<div class="row mt-4">
		<div class="col-md-12">
			<div class="card">
				<div class="card-header bg-light">
					<h5 class="mb-0">Pending Approvals</h5>
				</div>
				<div class="card-body">
					<?php if (!empty($pending_items)): ?>
						<table class="table table-hover">
							<thead>
								<tr>
									<th>Data Type</th>
									<th>Requested By</th>
									<th>Status</th>
									<th>Date</th>
									<th>Actions</th>
								</tr>
							</thead>
							<tbody>
								<?php foreach ($pending_items as $item): ?>
									<tr>
										<td><?php echo $item['data_type']; ?></td>
										<td><?php echo $item['requested_by_name']; ?></td>
										<td>
											<?php if ($item['status'] == 'pending'): ?>
												<span class="badge bg-warning">Pending</span>
											<?php elseif ($item['status'] == 'approved'): ?>
												<span class="badge bg-success">Approved</span>
											<?php elseif ($item['status'] == 'rejected'): ?>
												<span class="badge bg-danger">Rejected</span>
											<?php else: ?>
												<span class="badge bg-info">Revision Needed</span>
											<?php endif; ?>
										</td>
										<td><?php echo date('d M Y', strtotime($item['created_at'])); ?></td>
										<td>
											<?php if ($item['status'] == 'pending'): ?>
												<a href="<?php echo base_url('k-panel/security/approve_pending/' . $item['id_permintaan']); ?>" class="btn btn-sm btn-success">Approve</a>
												<a href="<?php echo base_url('k-panel/security/reject_pending/' . $item['id_permintaan']); ?>" class="btn btn-sm btn-danger">Reject</a>
											<?php endif; ?>
										</td>
									</tr>
								<?php endforeach; ?>
							</tbody>
						</table>
						<a href="<?php echo base_url('k-panel/security/pending_data'); ?>" class="btn btn-sm btn-primary">View All</a>
					<?php else: ?>
						<p class="text-muted">No pending items</p>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>

	<!-- Maintenance Section -->
	<div class="row mt-4">
		<div class="col-md-12">
			<div class="card border-warning">
				<div class="card-header bg-warning bg-opacity-10">
					<h6 class="mb-0">Maintenance & Cleanup</h6>
				</div>
				<div class="card-body">
					<p class="text-muted small">Hapus semua log dengan tombol di bawah ini.</p>
					<a href="<?php echo base_url('k-panel/security/cleanup/all/0'); ?>" class="btn btn-danger" onclick="return confirm('This will delete all log records permanently. Continue?');">Clear All Logs</a>
				</div>
				<div class="small text-muted mt-2">
					Tombol di atas akan menghapus seluruh log tanpa batas usia.
				</div>
			</div>
		</div>
	</div>
</div>

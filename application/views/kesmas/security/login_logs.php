<div class="container-fluid">
	<div class="row mb-4">
		<div class="col-md-12">
			<h1 class="page-title">Login Logs</h1>
			<p class="text-muted">All user login attempts and authentication events</p>
		</div>
	</div>

	<!-- Filters -->
	<div class="row mb-4">
		<div class="col-md-12">
			<div class="card">
				<div class="card-body">
					<form method="get" class="row g-3">
						<div class="col-md-4">
							<label for="status" class="form-label">Status</label>
							<select name="status" id="status" class="form-select">
								<option value="">All Status</option>
								<option value="success" <?php echo $filter_status == 'success' ? 'selected' : ''; ?>>Success</option>
								<option value="failed" <?php echo $filter_status == 'failed' ? 'selected' : ''; ?>>Failed</option>
								<option value="locked" <?php echo $filter_status == 'locked' ? 'selected' : ''; ?>>Locked</option>
							</select>
						</div>
						<div class="col-md-4">
							<label for="username" class="form-label">Username</label>
							<input type="text" name="username" id="username" class="form-control" value="<?php echo $filter_username; ?>" placeholder="Search username...">
						</div>
						<div class="col-md-4">
							<label>&nbsp;</label>
							<div>
								<button type="submit" class="btn btn-primary">Filter</button>
								<a href="<?php echo base_url('security/login_logs'); ?>" class="btn btn-secondary">Reset</a>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>

	<!-- Logs Table -->
	<div class="row">
		<div class="col-md-12">
			<div class="card">
				<div class="card-body">
					<?php if (!empty($logs)): ?>
						<table class="table table-striped table-hover">
							<thead>
								<tr>
									<th>ID</th>
									<th>Username</th>
									<th>User ID</th>
									<th>Status</th>
									<th>IP Address</th>
									<th>Reason (if failed)</th>
									<th>Login Time</th>
								</tr>
							</thead>
							<tbody>
								<?php foreach ($logs as $log): ?>
									<tr>
										<td><?php echo $log['id_permintaan']; ?></td>
										<td><?php echo htmlspecialchars($log['username']); ?></td>
										<td><?php echo $log['user_id'] ?: '-'; ?></td>
										<td>
											<?php if ($log['status'] == 'success'): ?>
												<span class="badge bg-success">Success</span>
											<?php elseif ($log['status'] == 'failed'): ?>
												<span class="badge bg-danger">Failed</span>
											<?php else: ?>
												<span class="badge bg-warning">Locked</span>
											<?php endif; ?>
										</td>
										<td><code><?php echo htmlspecialchars($log['ip_address']); ?></code></td>
										<td><?php echo htmlspecialchars($log['failure_reason'] ?: '-'); ?></td>
										<td><?php echo date('d M Y H:i:s', strtotime($log['login_at'])); ?></td>
									</tr>
								<?php endforeach; ?>
							</tbody>
						</table>

						<!-- Pagination -->
						<?php if ($total > $limit): ?>
							<nav>
								<ul class="pagination">
									<?php for ($i = 1; $i <= ceil($total / $limit); $i++): ?>
										<li class="page-item <?php echo $page == $i ? 'active' : ''; ?>">
											<a class="page-link" href="<?php echo base_url('security/login_logs?page=' . $i . (isset($filter_status) && $filter_status ? '&status=' . $filter_status : '') . (isset($filter_username) && $filter_username ? '&username=' . $filter_username : '')); ?>">
												<?php echo $i; ?>
											</a>
										</li>
									<?php endfor; ?>
								</ul>
							</nav>
						<?php endif; ?>

					<?php else: ?>
						<div class="alert alert-info">No login logs found</div>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>

	<!-- Summary Stats -->
	<div class="row mt-4">
		<div class="col-md-6">
			<div class="card">
				<div class="card-header bg-light">
					<h6 class="mb-0">Login Summary (Today)</h6>
				</div>
				<div class="card-body">
					<div class="row">
						<div class="col-md-6">
							<h4 class="text-success">Successful</h4>
							<h3>
								<?php 
								$success_count = 0;
								foreach ($logs as $log) {
									if ($log['status'] == 'success') $success_count++;
								}
								echo $success_count;
								?>
							</h3>
						</div>
						<div class="col-md-6">
							<h4 class="text-danger">Failed</h4>
							<h3>
								<?php 
								$failed_count = 0;
								foreach ($logs as $log) {
									if ($log['status'] == 'failed') $failed_count++;
								}
								echo $failed_count;
								?>
							</h3>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="col-md-6">
			<div class="card">
				<div class="card-header bg-light">
					<h6 class="mb-0">Tools</h6>
				</div>
				<div class="card-body">
					<a href="<?php echo base_url('security/export_logs/login'); ?>" class="btn btn-primary">
						<i class="fas fa-download"></i> Export to Excel
					</a>
					<a href="<?php echo base_url('security'); ?>" class="btn btn-secondary">
						<i class="fas fa-arrow-left"></i> Back to Dashboard
					</a>
				</div>
			</div>
		</div>
	</div>
</div>

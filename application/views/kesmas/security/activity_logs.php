<div class="container-fluid">
	<div class="row mb-4">
		<div class="col-md-12">
			<h1 class="page-title">Activity Logs</h1>
			<p class="text-muted">Track all user activities (create, read, update, delete, etc.)</p>
		</div>
	</div>

	<!-- Filters -->
	<div class="row mb-4">
		<div class="col-md-12">
			<div class="card">
				<div class="card-body">
					<form method="get" class="row g-3">
						<div class="col-md-2">
							<label for="user_id" class="form-label">User</label>
							<select name="user_id" id="user_id" class="form-select">
								<option value="">All Users</option>
								<?php foreach ($users as $user): ?>
									<option value="<?php echo $user['id_permintaan']; ?>" <?php echo $filters['user_id'] == $user['id_permintaan'] ? 'selected' : ''; ?>>
										<?php echo htmlspecialchars($user['nama']); ?>
									</option>
								<?php endforeach; ?>
							</select>
						</div>
						<div class="col-md-2">
							<label for="action" class="form-label">Action</label>
							<select name="action" id="action" class="form-select">
								<option value="">All Actions</option>
								<?php foreach ($actions as $act): ?>
									<option value="<?php echo $act['action']; ?>" <?php echo $filters['action'] == $act['action'] ? 'selected' : ''; ?>>
										<?php echo htmlspecialchars($act['action']); ?>
									</option>
								<?php endforeach; ?>
							</select>
						</div>
						<div class="col-md-2">
							<label for="module" class="form-label">Module</label>
							<select name="module" id="module" class="form-select">
								<option value="">All Modules</option>
								<?php foreach ($modules as $mod): ?>
									<option value="<?php echo $mod['module']; ?>" <?php echo $filters['module'] == $mod['module'] ? 'selected' : ''; ?>>
										<?php echo htmlspecialchars($mod['module']); ?>
									</option>
								<?php endforeach; ?>
							</select>
						</div>
						<div class="col-md-2">
							<label for="start_date" class="form-label">Start Date</label>
							<input type="date" name="start_date" id="start_date" class="form-control" value="<?php echo $filters['start_date']; ?>">
						</div>
						<div class="col-md-2">
							<label for="end_date" class="form-label">End Date</label>
							<input type="date" name="end_date" id="end_date" class="form-control" value="<?php echo $filters['end_date']; ?>">
						</div>
						<div class="col-md-2">
							<label>&nbsp;</label>
							<div>
								<button type="submit" class="btn btn-primary w-100">Filter</button>
							</div>
						</div>
					</form>
					<div class="mt-2">
						<a href="<?php echo base_url('security/activity_logs'); ?>" class="btn btn-secondary btn-sm">Reset Filters</a>
					</div>
				</div>
			</div>
		</div>
	</div>

	<!-- Logs Table -->
	<div class="row">
		<div class="col-md-12">
			<div class="card">
				<div class="card-header">
					<h6 class="mb-0">
						Activity Logs 
						<span class="text-muted">(Total: <?php echo $total; ?>)</span>
					</h6>
				</div>
				<div class="card-body">
					<?php if (!empty($logs)): ?>
						<div class="table-responsive">
							<table class="table table-striped table-hover">
								<thead>
									<tr>
										<th>ID</th>
										<th>User</th>
										<th>Action</th>
										<th>Module</th>
										<th>Description</th>
										<th>IP Address</th>
										<th>Timestamp</th>
										<th>Action</th>
									</tr>
								</thead>
								<tbody>
									<?php foreach ($logs as $log): ?>
										<tr>
											<td><?php echo $log['id_permintaan']; ?></td>
											<td><?php echo $log['user_id']; ?></td>
											<td>
												<span class="badge bg-info">
													<?php echo htmlspecialchars($log['action']); ?>
												</span>
											</td>
											<td><?php echo htmlspecialchars($log['module']); ?></td>
											<td>
												<?php echo htmlspecialchars(substr($log['description'], 0, 50)); ?>
												<?php if (strlen($log['description']) > 50): ?>
													...
												<?php endif; ?>
											</td>
											<td><code><?php echo htmlspecialchars($log['ip_address']); ?></code></td>
											<td><?php echo date('d M Y H:i:s', strtotime($log['created_at'])); ?></td>
											<td>
												<a href="<?php echo base_url('security/activity_detail/' . $log['id_permintaan']); ?>" class="btn btn-sm btn-primary">View</a>
											</td>
										</tr>
									<?php endforeach; ?>
								</tbody>
							</table>
						</div>

						<!-- Pagination -->
						<?php if ($total > $limit): ?>
							<nav>
								<ul class="pagination">
									<?php for ($i = 1; $i <= ceil($total / $limit); $i++): ?>
										<li class="page-item <?php echo $page == $i ? 'active' : ''; ?>">
											<a class="page-link" href="<?php echo base_url('security/activity_logs?page=' . $i . ($filters['user_id'] ? '&user_id=' . $filters['user_id'] : '') . ($filters['action'] ? '&action=' . $filters['action'] : '') . ($filters['module'] ? '&module=' . $filters['module'] : '')); ?>">
												<?php echo $i; ?>
											</a>
										</li>
									<?php endfor; ?>
								</ul>
							</nav>
						<?php endif; ?>

					<?php else: ?>
						<div class="alert alert-info">No activity logs found</div>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>

	<!-- Tools -->
	<div class="row mt-4">
		<div class="col-md-12">
			<a href="<?php echo base_url('security'); ?>" class="btn btn-secondary">
				<i class="fas fa-arrow-left"></i> Back to Dashboard
			</a>
		</div>
	</div>
</div>

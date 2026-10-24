<div class="container-fluid">
	<div class="row mb-4">
		<div class="col-md-12">
			<h1 class="page-title">Activity Details</h1>
		</div>
	</div>

	<div class="row">
		<div class="col-md-8">
			<div class="card">
				<div class="card-body">
					<div class="row mb-3">
						<div class="col-md-6">
							<h6 class="text-muted">Activity ID</h6>
							<p><?php echo $activity['id_permintaan']; ?></p>
						</div>
						<div class="col-md-6">
							<h6 class="text-muted">User</h6>
							<p><?php echo $activity['user'] ? htmlspecialchars($activity['user']['nama']) : 'Unknown'; ?></p>
						</div>
					</div>

					<div class="row mb-3">
						<div class="col-md-6">
							<h6 class="text-muted">Action</h6>
							<p><span class="badge bg-info"><?php echo htmlspecialchars($activity['action']); ?></span></p>
						</div>
						<div class="col-md-6">
							<h6 class="text-muted">Module</h6>
							<p><?php echo htmlspecialchars($activity['module']); ?></p>
						</div>
					</div>

					<div class="row mb-3">
						<div class="col-md-12">
							<h6 class="text-muted">Description</h6>
							<p><?php echo htmlspecialchars($activity['description']); ?></p>
						</div>
					</div>

					<?php if ($activity['record_id']): ?>
						<div class="row mb-3">
							<div class="col-md-12">
								<h6 class="text-muted">Record ID</h6>
								<p><code><?php echo $activity['record_id']; ?></code></p>
							</div>
						</div>
					<?php endif; ?>

					<div class="row mb-3">
						<div class="col-md-12">
							<h6 class="text-muted">Timestamp</h6>
							<p><?php echo date('d M Y H:i:s', strtotime($activity['created_at'])); ?></p>
						</div>
					</div>

					<div class="row mb-3">
						<div class="col-md-12">
							<h6 class="text-muted">IP Address</h6>
							<p><code><?php echo htmlspecialchars($activity['ip_address']); ?></code></p>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="col-md-4">
			<div class="card">
				<div class="card-header bg-light">
					<h6 class="mb-0">Changes Made</h6>
				</div>
				<div class="card-body">
					<?php if ($activity['old_values'] || $activity['new_values']): ?>

						<?php if ($activity['old_values']): ?>
							<h6 class="text-danger">Old Values:</h6>
							<div class="bg-light p-2 rounded mb-3">
								<small>
									<?php foreach ($activity['old_values'] as $key => $value): ?>
										<div><strong><?php echo htmlspecialchars($key); ?>:</strong> <?php echo htmlspecialchars($value); ?></div>
									<?php endforeach; ?>
								</small>
							</div>
						<?php endif; ?>

						<?php if ($activity['new_values']): ?>
							<h6 class="text-success">New Values:</h6>
							<div class="bg-light p-2 rounded">
								<small>
									<?php foreach ($activity['new_values'] as $key => $value): ?>
										<div><strong><?php echo htmlspecialchars($key); ?>:</strong> <?php echo htmlspecialchars($value); ?></div>
									<?php endforeach; ?>
								</small>
							</div>
						<?php endif; ?>

					<?php else: ?>
						<p class="text-muted small">No value changes recorded for this activity</p>
					<?php endif; ?>
				</div>
			</div>

			<div class="card mt-3">
				<div class="card-header bg-light">
					<h6 class="mb-0">User Agent</h6>
				</div>
				<div class="card-body">
					<small><?php echo htmlspecialchars($activity['user_agent']); ?></small>
				</div>
			</div>
		</div>
	</div>

	<div class="row mt-4">
		<div class="col-md-12">
			<a href="<?php echo base_url('security/activity_logs'); ?>" class="btn btn-secondary">
				<i class="fas fa-arrow-left"></i> Back to Activity Logs
			</a>
		</div>
	</div>
</div>

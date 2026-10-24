<div class="container-fluid">
	<div class="row mb-4">
		<div class="col-md-12">
			<h1 class="page-title">Pending Data Approvals</h1>
			<p class="text-muted">Review and approve/reject pending data submissions</p>
		</div>
	</div>

	<!-- Filters -->
	<div class="row mb-4">
		<div class="col-md-12">
			<div class="card">
				<div class="card-body">
					<form method="get" class="row g-3">
						<div class="col-md-3">
							<label for="status" class="form-label">Status</label>
							<select name="status" id="status" class="form-select">
								<option value="">All Status</option>
								<option value="pending" <?php echo $filters['status'] == 'pending' ? 'selected' : ''; ?>>Pending</option>
								<option value="approved" <?php echo $filters['status'] == 'approved' ? 'selected' : ''; ?>>Approved</option>
								<option value="rejected" <?php echo $filters['status'] == 'rejected' ? 'selected' : ''; ?>>Rejected</option>
								<option value="revision_needed" <?php echo $filters['status'] == 'revision_needed' ? 'selected' : ''; ?>>Revision Needed</option>
							</select>
						</div>
						<div class="col-md-3">
							<label for="data_type" class="form-label">Data Type</label>
							<select name="data_type" id="data_type" class="form-select">
								<option value="">All Types</option>
								<?php foreach ($data_types as $type): ?>
									<option value="<?php echo $type['data_type']; ?>" <?php echo $filters['data_type'] == $type['data_type'] ? 'selected' : ''; ?>>
										<?php echo htmlspecialchars($type['data_type']); ?>
									</option>
								<?php endforeach; ?>
							</select>
						</div>
						<div class="col-md-3">
							<label>&nbsp;</label>
							<div>
								<button type="submit" class="btn btn-primary">Filter</button>
								<a href="<?php echo base_url('security/pending_data'); ?>" class="btn btn-secondary">Reset</a>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>

	<!-- Pending Items Table -->
	<div class="row">
		<div class="col-md-12">
			<div class="card">
				<div class="card-header">
					<h6 class="mb-0">
						Pending Items 
						<span class="text-muted">(Total: <?php echo $total; ?>)</span>
					</h6>
				</div>
				<div class="card-body">
					<?php if (!empty($items)): ?>
						<div class="table-responsive">
							<table class="table table-hover">
								<thead>
									<tr>
										<th>ID</th>
										<th>Data Type</th>
										<th>Data ID</th>
										<th>Requested By</th>
										<th>Status</th>
										<th>Description</th>
										<th>Submitted</th>
										<th>Actions</th>
									</tr>
								</thead>
								<tbody>
									<?php foreach ($items as $item): ?>
										<tr>
											<td><?php echo $item['id_permintaan']; ?></td>
											<td>
												<span class="badge bg-secondary">
													<?php echo htmlspecialchars($item['data_type']); ?>
												</span>
											</td>
											<td><?php echo $item['data_id']; ?></td>
											<td><?php echo htmlspecialchars($item['requested_by_name']); ?></td>
											<td>
												<?php if ($item['status'] == 'pending'): ?>
													<span class="badge bg-warning text-dark">Pending</span>
												<?php elseif ($item['status'] == 'approved'): ?>
													<span class="badge bg-success">Approved</span>
												<?php elseif ($item['status'] == 'rejected'): ?>
													<span class="badge bg-danger">Rejected</span>
												<?php else: ?>
													<span class="badge bg-info">Revision Needed</span>
												<?php endif; ?>
											</td>
											<td>
												<small><?php echo htmlspecialchars(substr($item['description'], 0, 40)); ?></small>
											</td>
											<td><?php echo date('d M Y H:i', strtotime($item['created_at'])); ?></td>
											<td>
												<?php if ($item['status'] == 'pending'): ?>
													<!-- Approve Button -->
													<form method="post" action="<?php echo base_url('security/approve_pending/' . $item['id_permintaan']); ?>" style="display:inline;">
														<button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Approve this item?');">
															<i class="fas fa-check"></i> Approve
														</button>
													</form>

													<!-- Reject Button -->
													<button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal<?php echo $item['id_permintaan']; ?>">
														<i class="fas fa-times"></i> Reject
													</button>

													<!-- Revision Button -->
													<button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#revisionModal<?php echo $item['id_permintaan']; ?>">
														<i class="fas fa-redo"></i> Revision
													</button>

													<!-- Reject Modal -->
													<div class="modal fade" id="rejectModal<?php echo $item['id_permintaan']; ?>" tabindex="-1">
														<div class="modal-dialog">
															<div class="modal-content">
																<div class="modal-header">
																	<h5 class="modal-title">Reject Item</h5>
																	<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
																</div>
																<form method="post" action="<?php echo base_url('security/reject_pending/' . $item['id_permintaan']); ?>">
																	<div class="modal-body">
																		<div class="mb-3">
																			<label class="form-label">Reason for Rejection</label>
																			<textarea name="reason" class="form-control" rows="4" required></textarea>
																		</div>
																	</div>
																	<div class="modal-footer">
																		<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
																		<button type="submit" class="btn btn-danger">Reject</button>
																	</div>
																</form>
															</div>
														</div>
													</div>

													<!-- Revision Modal -->
													<div class="modal fade" id="revisionModal<?php echo $item['id_permintaan']; ?>" tabindex="-1">
														<div class="modal-dialog">
															<div class="modal-content">
																<div class="modal-header">
																	<h5 class="modal-title">Request Revision</h5>
																	<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
																</div>
																<form method="post" action="<?php echo base_url('security/request_revision/' . $item['id_permintaan']); ?>">
																	<div class="modal-body">
																		<div class="mb-3">
																			<label class="form-label">Revision Notes</label>
																			<textarea name="revision_note" class="form-control" rows="4" required></textarea>
																		</div>
																	</div>
																	<div class="modal-footer">
																		<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
																		<button type="submit" class="btn btn-warning">Request Revision</button>
																	</div>
																</form>
															</div>
														</div>
													</div>

												<?php elseif ($item['status'] == 'approved'): ?>
													<span class="badge bg-success">Approved</span>
													<small class="text-muted d-block">by <?php echo htmlspecialchars($item['approved_by_name']); ?></small>
												<?php elseif ($item['status'] == 'rejected'): ?>
													<span class="badge bg-danger">Rejected</span>
													<small class="text-muted d-block"><?php echo htmlspecialchars(substr($item['rejection_reason'], 0, 30)); ?></small>
												<?php else: ?>
													<span class="badge bg-info">Revision Needed</span>
													<small class="text-muted d-block"><?php echo htmlspecialchars(substr($item['rejection_reason'], 0, 30)); ?></small>
												<?php endif; ?>
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
											<a class="page-link" href="<?php echo base_url('security/pending_data?page=' . $i . ($filters['status'] ? '&status=' . $filters['status'] : '') . ($filters['data_type'] ? '&data_type=' . $filters['data_type'] : '')); ?>">
												<?php echo $i; ?>
											</a>
										</li>
									<?php endfor; ?>
								</ul>
							</nav>
						<?php endif; ?>

					<?php else: ?>
						<div class="alert alert-info">
							<?php if ($filters['status'] == 'pending'): ?>
								No pending items at this time
							<?php else: ?>
								No data found with the selected filters
							<?php endif; ?>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>

	<!-- Navigation -->
	<div class="row mt-4">
		<div class="col-md-12">
			<a href="<?php echo base_url('security'); ?>" class="btn btn-secondary">
				<i class="fas fa-arrow-left"></i> Back to Dashboard
			</a>
		</div>
	</div>
</div>

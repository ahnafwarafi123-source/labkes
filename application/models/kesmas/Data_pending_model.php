<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Data_pending_model extends CI_Model {

	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Create pending data entry
	 * @param array $data
	 * @return int|bool (insert ID or false)
	 */
	public function create_pending($data = [])
	{
		$pending_data = [
			'user_id' => isset($data['user_id']) ? $data['user_id'] : $this->session->user_id,
			'data_type' => isset($data['data_type']) ? $data['data_type'] : '',
			'data_id' => isset($data['data_id']) ? $data['data_id'] : 0,
			'requested_by' => isset($data['requested_by']) ? $data['requested_by'] : $this->session->user_id,
			'status' => isset($data['status']) ? $data['status'] : 'pending',
			'description' => isset($data['description']) ? $data['description'] : '',
		];

		if ($this->db->insert('data_pending', $pending_data)) {
			return $this->db->insert_id();
		}
		return false;
	}

	/**
	 * Get pending data by ID
	 * @param int $id
	 * @return array|null
	 */
	public function get_pending($id)
	{
		$this->db->select('dp.*, u.nama as requested_by_name, u2.nama as approved_by_name');
		$this->db->from('data_pending dp');
		$this->db->join('user_kesmas u', 'dp.requested_by = u.id', 'left');
		$this->db->join('user_kesmas u2', 'dp.approved_by = u2.id', 'left');
		$this->db->where('dp.id', $id);
		$query = $this->db->get();
		return $query->row_array();
	}

	/**
	 * Get all pending data with filters
	 * @param array $filters
	 * @param int $limit
	 * @param int $offset
	 * @return array
	 */
	public function get_pending_data($filters = [], $limit = 50, $offset = 0)
	{
		$this->db->select('dp.*, u.nama as requested_by_name, u2.nama as approved_by_name');
		$this->db->from('data_pending dp');
		$this->db->join('user_kesmas u', 'dp.requested_by = u.id', 'left');
		$this->db->join('user_kesmas u2', 'dp.approved_by = u2.id', 'left');

		if (isset($filters['status']) && $filters['status'] != '') {
			$this->db->where('dp.status', $filters['status']);
		}

		if (isset($filters['data_type']) && $filters['data_type'] != '') {
			$this->db->where('dp.data_type', $filters['data_type']);
		}

		if (isset($filters['user_id']) && $filters['user_id'] != '') {
			$this->db->where('dp.user_id', $filters['user_id']);
		}

		if (isset($filters['requested_by']) && $filters['requested_by'] != '') {
			$this->db->where('dp.requested_by', $filters['requested_by']);
		}

		if (isset($filters['start_date']) && $filters['start_date'] != '') {
			$this->db->where("DATE(dp.created_at) >= '{$filters['start_date']}'", NULL, FALSE);
		}

		if (isset($filters['end_date']) && $filters['end_date'] != '') {
			$this->db->where("DATE(dp.created_at) <= '{$filters['end_date']}'", NULL, FALSE);
		}

		$this->db->order_by('dp.created_at', 'DESC');
		$this->db->limit($limit, $offset);

		return $this->db->get()->result_array();
	}

	/**
	 * Count pending data with filters
	 * @param array $filters
	 * @return int
	 */
	public function count_pending_data($filters = [])
	{
		if (isset($filters['status']) && $filters['status'] != '') {
			$this->db->where('status', $filters['status']);
		}

		if (isset($filters['data_type']) && $filters['data_type'] != '') {
			$this->db->where('data_type', $filters['data_type']);
		}

		if (isset($filters['user_id']) && $filters['user_id'] != '') {
			$this->db->where('user_id', $filters['user_id']);
		}

		if (isset($filters['requested_by']) && $filters['requested_by'] != '') {
			$this->db->where('requested_by', $filters['requested_by']);
		}

		if (isset($filters['start_date']) && $filters['start_date'] != '') {
			$this->db->where("DATE(created_at) >= '{$filters['start_date']}'", NULL, FALSE);
		}

		if (isset($filters['end_date']) && $filters['end_date'] != '') {
			$this->db->where("DATE(created_at) <= '{$filters['end_date']}'", NULL, FALSE);
		}

		return $this->db->get('data_pending')->num_rows();
	}

	/**
	 * Get pending items (status = pending)
	 * @param string $data_type (optional)
	 * @param int $limit
	 * @return array
	 */
	public function get_pending_items($data_type = '', $limit = 50)
	{
		$this->db->select('dp.*, u.nama as requested_by_name');
		$this->db->from('data_pending dp');
		$this->db->join('user_kesmas u', 'dp.requested_by = u.id', 'left');
		$this->db->where('dp.status', 'pending');

		if ($data_type != '') {
			$this->db->where('dp.data_type', $data_type);
		}

		$this->db->order_by('dp.created_at', 'DESC');
		$this->db->limit($limit);

		return $this->db->get()->result_array();
	}

	/**
	 * Approve pending data
	 * @param int $id
	 * @param int $approved_by (user_id)
	 * @return bool
	 */
	public function approve($id, $approved_by)
	{
		$update_data = [
			'status' => 'approved',
			'approved_by' => $approved_by,
			'approved_at' => date('Y-m-d H:i:s'),
		];

		$this->db->where('id', $id);
		return $this->db->update('data_pending', $update_data);
	}

	/**
	 * Reject pending data
	 * @param int $id
	 * @param int $rejected_by (user_id)
	 * @param string $reason
	 * @return bool
	 */
	public function reject($id, $rejected_by, $reason = '')
	{
		$update_data = [
			'status' => 'rejected',
			'approved_by' => $rejected_by,
			'rejection_reason' => $reason,
			'approved_at' => date('Y-m-d H:i:s'),
		];

		$this->db->where('id', $id);
		return $this->db->update('data_pending', $update_data);
	}

	/**
	 * Request revision for pending data
	 * @param int $id
	 * @param string $revision_note
	 * @return bool
	 */
	public function request_revision($id, $revision_note = '')
	{
		$update_data = [
			'status' => 'revision_needed',
			'rejection_reason' => $revision_note,
		];

		$this->db->where('id', $id);
		return $this->db->update('data_pending', $update_data);
	}

	/**
	 * Get pending summary
	 * @return array
	 */
	public function get_summary()
	{
		$query = $this->db->query("
			SELECT 
				status,
				COUNT(*) as count
			FROM data_pending
			WHERE created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)
			GROUP BY status
		");

		return $query->result_array();
	}

	/**
	 * Get pending count by type
	 * @param string $status (optional)
	 * @return array
	 */
	public function get_count_by_type($status = '')
	{
		$where = '';
		if ($status != '') {
			$where = " WHERE status = '{$status}'";
		}

		$query = $this->db->query("
			SELECT 
				data_type,
				COUNT(*) as count
			FROM data_pending
			{$where}
			GROUP BY data_type
		");

		return $query->result_array();
	}

	/**
	 * Delete pending data
	 * @param int $id
	 * @return bool
	 */
	public function delete_pending($id)
	{
		$this->db->where('id', $id);
		return $this->db->delete('data_pending');
	}

	/**
	 * Clean up old approved/rejected items
	 * @param int $days
	 * @return int Number of deleted rows
	 */
	public function cleanup_old_items($days = 90)
	{
		$statuses = ['approved', 'rejected'];
		$this->db->where_in('status', $statuses);
		if ($days > 0) {
			$this->db->where("approved_at < DATE_SUB(NOW(), INTERVAL {$days} DAY)", NULL, FALSE);
		}
		$this->db->delete('data_pending');
		$deleted = $this->db->affected_rows();
		$this->db->reset_query();
		return $deleted;
	}
}

<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Activity_logs_model extends CI_Model {

	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Log user activity
	 * @param array $data
	 * @return bool
	 */
	public function log_activity($data = [])
	{
		$log_data = [
			'user_id' => isset($data['user_id']) ? (int)$data['user_id'] : $this->_current_user_id(),
			'action' => isset($data['action']) ? $data['action'] : '',
			'module' => isset($data['module']) ? $data['module'] : '',
			'description' => isset($data['description']) ? $data['description'] : '',
			'record_id' => isset($data['record_id']) && $data['record_id'] !== NULL ? $data['record_id'] : 0,
			'old_values' => isset($data['old_values']) ? json_encode($data['old_values']) : NULL,
			'new_values' => isset($data['new_values']) ? json_encode($data['new_values']) : NULL,
			'ip_address' => $this->get_client_ip(),
			'user_agent' => $this->input->user_agent(),
		];

		return $this->db->insert('activity_logs', $log_data);
	}

	/**
	 * Get activity logs
	 * @param array $filters
	 * @param int $limit
	 * @param int $offset
	 * @return array
	 */
	public function get_activity_logs($filters = [], $limit = 50, $offset = 0)
	{
		$this->db->select('activity_logs.*, COALESCE(u.nama, k.nama, \'\') as user_nama');
		$this->db->from('activity_logs');
		$this->db->join('user_kesmas u', 'activity_logs.user_id = u.id', 'left');
		$this->db->join('users k', 'activity_logs.user_id = k.id_user', 'left');

		if (isset($filters['user_id']) && $filters['user_id'] != '') {
			$this->db->where('activity_logs.user_id', $filters['user_id']);
		}

		if (isset($filters['action']) && $filters['action'] != '') {
			$this->db->where('activity_logs.action', $filters['action']);
		}

		if (isset($filters['module']) && $filters['module'] != '') {
			$this->db->where('activity_logs.module', $filters['module']);
		}

		if (isset($filters['start_date']) && $filters['start_date'] != '') {
			$this->db->where("DATE(activity_logs.created_at) >= '{$filters['start_date']}'", NULL, FALSE);
		}

		if (isset($filters['end_date']) && $filters['end_date'] != '') {
			$this->db->where("DATE(activity_logs.created_at) <= '{$filters['end_date']}'", NULL, FALSE);
		}

		$this->db->order_by('activity_logs.created_at', 'DESC');
		$this->db->limit($limit, $offset);

		return $this->db->get()->result_array();
	}

	/**
	 * Count activity logs with filters
	 * @param array $filters
	 * @return int
	 */
	public function count_activity_logs($filters = [])
	{
		if (isset($filters['user_id']) && $filters['user_id'] != '') {
			$this->db->where('user_id', $filters['user_id']);
		}

		if (isset($filters['action']) && $filters['action'] != '') {
			$this->db->where('action', $filters['action']);
		}

		if (isset($filters['module']) && $filters['module'] != '') {
			$this->db->where('module', $filters['module']);
		}

		if (isset($filters['start_date']) && $filters['start_date'] != '') {
			$this->db->where("DATE(created_at) >= '{$filters['start_date']}'", NULL, FALSE);
		}

		if (isset($filters['end_date']) && $filters['end_date'] != '') {
			$this->db->where("DATE(created_at) <= '{$filters['end_date']}'", NULL, FALSE);
		}

		return $this->db->get('activity_logs')->num_rows();
	}

	/**
	 * Get user activity history
	 * @param int $user_id
	 * @param int $limit
	 * @return array
	 */
	public function get_user_activity_history($user_id, $limit = 50)
	{
		$this->db->where('user_id', $user_id);
		$this->db->order_by('created_at', 'DESC');
		$this->db->limit($limit);
		return $this->db->get('activity_logs')->result_array();
	}

	/**
	 * Get activities by module
	 * @param string $module
	 * @param int $limit
	 * @return array
	 */
	public function get_activities_by_module($module, $limit = 50)
	{
		$this->db->where('module', $module);
		$this->db->order_by('created_at', 'DESC');
		$this->db->limit($limit);
		return $this->db->get('activity_logs')->result_array();
	}

	/**
	 * Get activities by action
	 * @param string $action
	 * @param int $limit
	 * @return array
	 */
	public function get_activities_by_action($action, $limit = 50)
	{
		$this->db->where('action', $action);
		$this->db->order_by('created_at', 'DESC');
		$this->db->limit($limit);
		return $this->db->get('activity_logs')->result_array();
	}

	/**
	 * Get activity statistics
	 * @param string $period (day, week, month)
	 * @return array
	 */
	public function get_activity_stats($period = 'day')
	{
		$where_clause = '';
		if ($period == 'day') {
			$where_clause = "created_at >= DATE(NOW())";
		} elseif ($period == 'week') {
			$where_clause = "created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)";
		} elseif ($period == 'month') {
			$where_clause = "created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)";
		}

		$query = $this->db->query("
			SELECT 
				action,
				COUNT(*) as count
			FROM activity_logs
			WHERE {$where_clause}
			GROUP BY action
		");

		return $query->result_array();
	}

	/**
	 * Get module statistics
	 * @param string $period (day, week, month)
	 * @return array
	 */
	public function get_module_stats($period = 'day')
	{
		$where_clause = '';
		if ($period == 'day') {
			$where_clause = "created_at >= DATE(NOW())";
		} elseif ($period == 'week') {
			$where_clause = "created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)";
		} elseif ($period == 'month') {
			$where_clause = "created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)";
		}

		$query = $this->db->query("
			SELECT 
				module,
				COUNT(*) as count
			FROM activity_logs
			WHERE {$where_clause}
			GROUP BY module
		");

		return $query->result_array();
	}

	/**
	 * Get changes made to a specific record
	 * @param string $module
	 * @param int $record_id
	 * @return array
	 */
	public function get_record_history($module, $record_id)
	{
		$this->db->where('module', $module);
		$this->db->where('record_id', $record_id);
		$this->db->order_by('created_at', 'ASC');
		return $this->db->get('activity_logs')->result_array();
	}

	/**
	 * Delete old logs (cleanup)
	 * @param int $days
	 * @return int Number of deleted rows
	 */
	public function delete_old_logs($days = 90)
	{
		if ($days > 0) {
			$this->db->where("created_at < DATE_SUB(NOW(), INTERVAL {$days} DAY)", NULL, FALSE);
			$this->db->delete('activity_logs');
		} else {
			$this->db->query('DELETE FROM activity_logs');
		}
		$deleted = $this->db->affected_rows();
		$this->db->reset_query();
		return $deleted;
	}

	/**
	 * Ambil ID user yang sedang login (lintas modul).
	 * Kesmas => user_kesmas.id; Klinik => users.id_user. Fallback 0 jika tidak ada.
	 */
	private function _current_user_id(): int
	{
		$u = current_user();
		$id = isset($u['user_id']) ? (int)$u['user_id'] : 0;
		return $id > 0 ? $id : 0;
	}

	/**
	 * Get client IP address
	 * @return string
	 */
	private function get_client_ip()
	{
		if (!empty($_SERVER['HTTP_CF_CONNECTING_IP'])) { // CloudFlare
			$ip = $_SERVER['HTTP_CF_CONNECTING_IP'];
		} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) { // Proxy
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		} elseif (!empty($_SERVER['REMOTE_ADDR'])) {
			$ip = $_SERVER['REMOTE_ADDR'];
		} else {
			$ip = '0.0.0.0';
		}

		// Handle multiple IPs (take first one)
		$ip_array = explode(',', $ip);
		return trim($ip_array[0]);
	}
}

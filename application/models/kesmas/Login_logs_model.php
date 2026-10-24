<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login_logs_model extends CI_Model {

	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Log login attempt
	 * @param array $data
	 * @return bool
	 */
	public function log_login($data = [])
	{
		$login_data = [
			'user_id' => isset($data['user_id']) ? $data['user_id'] : NULL,
			'username' => isset($data['username']) ? $data['username'] : '',
			'status' => isset($data['status']) ? $data['status'] : 'failed',
			'ip_address' => $this->get_client_ip(),
			'user_agent' => $this->input->user_agent(),
			'failure_reason' => isset($data['failure_reason']) ? $data['failure_reason'] : NULL,
		];

		return $this->db->insert('login_logs', $login_data);
	}

	/**
	 * Get login history for a user
	 * @param int $user_id
	 * @param int $limit
	 * @return array
	 */
	public function get_user_login_history($user_id, $limit = 50)
	{
		$this->db->where('user_id', $user_id);
		$this->db->order_by('login_at', 'DESC');
		$this->db->limit($limit);
		return $this->db->get('login_logs')->result_array();
	}

	/**
	 * Get all login logs
	 * @param int $limit
	 * @return array
	 */
	public function get_all_login_logs($limit = 100)
	{
		$this->db->order_by('login_at', 'DESC');
		$this->db->limit($limit);
		return $this->db->get('login_logs')->result_array();
	}

	/**
	 * Get failed login attempts for IP
	 * @param string $ip_address
	 * @param int $minutes
	 * @return int
	 */
	public function get_failed_attempts_by_ip($ip_address, $minutes = 30)
	{
		$this->db->where('ip_address', $ip_address);
		$this->db->where('status', 'failed');
		$this->db->where("login_at > DATE_SUB(NOW(), INTERVAL {$minutes} MINUTE)", NULL, FALSE);
		return $this->db->get('login_logs')->num_rows();
	}

	/**
	 * Get recent failed attempts for username
	 * @param string $username
	 * @param int $minutes
	 * @return int
	 */
	public function get_failed_attempts_by_username($username, $minutes = 30)
	{
		$this->db->where('username', $username);
		$this->db->where('status', 'failed');
		$this->db->where("login_at > DATE_SUB(NOW(), INTERVAL {$minutes} MINUTE)", NULL, FALSE);
		return $this->db->get('login_logs')->num_rows();
	}

	/**
	 * Mark login as locked (too many failed attempts)
	 * @param string $username
	 * @return bool
	 */
	public function lock_account($username)
	{
		$this->db->insert('login_logs', [
			'username' => $username,
			'status' => 'locked',
			'ip_address' => $this->get_client_ip(),
			'failure_reason' => 'Account locked due to too many failed login attempts'
		]);
		return true;
	}

	/**
	 * Get login stats
	 * @param string $period (day, week, month)
	 * @return array
	 */
	public function get_login_stats($period = 'day')
	{
		$where_clause = '';
		if ($period == 'day') {
			$where_clause = "login_at >= DATE(NOW())";
		} elseif ($period == 'week') {
			$where_clause = "login_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)";
		} elseif ($period == 'month') {
			$where_clause = "login_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)";
		}

		$query = $this->db->query("
			SELECT 
				status,
				COUNT(*) as count
			FROM login_logs
			WHERE {$where_clause}
			GROUP BY status
		");

		return $query->result_array();
	}

	/**
	 * Delete old logs (cleanup)
	 * @param int $days
	 * @return int Number of deleted rows
	 */
	public function delete_old_logs($days = 90)
	{
		if ($days > 0) {
			$this->db->where("login_at < DATE_SUB(NOW(), INTERVAL {$days} DAY)", NULL, FALSE);
			$this->db->delete('login_logs');
		} else {
			$this->db->query('DELETE FROM login_logs');
		}
		$deleted = $this->db->affected_rows();
		$this->db->reset_query();
		return $deleted;
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

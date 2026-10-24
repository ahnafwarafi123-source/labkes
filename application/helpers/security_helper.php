<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Security Features Helper
 * Contains functions for logging activities, login attempts, and managing pending data
 */

/**
 * Log user login attempt
 * @param array $data (username, status, failure_reason, etc)
 * @return bool
 */
if (!function_exists('log_login_attempt')) {
	function log_login_attempt($data = [])
	{
		$CI =& get_instance();
		$CI->load->model('kesmas/Login_logs_model');
		return $CI->Login_logs_model->log_login($data);
	}
}

/**
 * Log user activity
 * @param string $action (create, read, update, delete, export, print, etc)
 * @param string $module (kesmas, pembayaran, users, etc)
 * @param array $extra_data (description, record_id, old_values, new_values)
 * @return bool
 */
if (!function_exists('log_activity')) {
	function log_activity($action = '', $module = '', $extra_data = [])
	{
		$CI =& get_instance();
		$CI->load->model('kesmas/Activity_logs_model');

		$data = [
			'action' => $action,
			'module' => $module,
		];

		// Merge extra data
		$data = array_merge($data, $extra_data);

		return $CI->Activity_logs_model->log_activity($data);
	}
}

/**
 * Create pending data entry
 * @param string $data_type (kesmas, pembayaran, alat_lab, etc)
 * @param int $data_id (reference to actual data)
 * @param int $user_id (who submitted it)
 * @param string $description (optional)
 * @return int|bool (pending ID or false)
 */
if (!function_exists('create_pending_data')) {
	function create_pending_data($data_type = '', $data_id = 0, $user_id = 0, $description = '')
	{
		$CI =& get_instance();
		$CI->load->model('kesmas/Data_pending_model');

		$data = [
			'data_type' => $data_type,
			'data_id' => $data_id,
			'user_id' => $user_id,
			'description' => $description,
			'status' => 'pending',
		];

		return $CI->Data_pending_model->create_pending($data);
	}
}

/**
 * Check if data has pending approval
 * @param string $data_type
 * @param int $data_id
 * @return bool
 */
if (!function_exists('is_pending_approval')) {
	function is_pending_approval($data_type = '', $data_id = 0)
	{
		$CI =& get_instance();
		$CI->load->model('kesmas/Data_pending_model');

		$CI->db->where('data_type', $data_type);
		$CI->db->where('data_id', $data_id);
		$CI->db->where('status', 'pending');

		return $CI->db->get('data_pending')->num_rows() > 0;
	}
}

/**
 * Get pending items count
 * @param string $status (pending, approved, rejected, revision_needed)
 * @return int
 */
if (!function_exists('get_pending_count')) {
	function get_pending_count($status = 'pending')
	{
		$CI =& get_instance();

		if ($status == 'all') {
			$CI->db->where("status IN ('pending', 'revision_needed')", NULL, FALSE);
		} else {
			$CI->db->where('status', $status);
		}

		return $CI->db->get('data_pending')->num_rows();
	}
}

/**
 * Get recent login attempts
 * @param int $limit
 * @return array
 */
if (!function_exists('get_recent_logins')) {
	function get_recent_logins($limit = 10)
	{
		$CI =& get_instance();
		$CI->load->model('kesmas/Login_logs_model');

		return $CI->Login_logs_model->get_all_login_logs($limit);
	}
}

/**
 * Get recent activities
 * @param int $limit
 * @return array
 */
if (!function_exists('get_recent_activities')) {
	function get_recent_activities($limit = 10)
	{
		$CI =& get_instance();
		$CI->load->model('kesmas/Activity_logs_model');

		$filters = [];
		return $CI->Activity_logs_model->get_activity_logs($filters, $limit);
	}
}

/**
 * Check if user can approve data
 * @param int $user_id
 * @param string $data_type (optional - specific type restriction)
 * @return bool
 */
if (!function_exists('can_approve_data')) {
	function can_approve_data($user_id = 0, $data_type = '')
	{
		$CI =& get_instance();

		// Get user role
		$CI->load->model('kesmas/User_model');
		$user = $CI->User_model->get_user($user_id);

		if (!$user) {
			return false;
		}

		// Only admin and specific roles can approve
		$approvable_roles = ['admin', 'verifikator'];

		return in_array($user['role'], $approvable_roles);
	}
}

/**
 * Log data change for audit trail
 * @param string $module
 * @param int $record_id
 * @param array $old_values
 * @param array $new_values
 * @return bool
 */
if (!function_exists('log_data_change')) {
	function log_data_change($module = '', $record_id = 0, $old_values = [], $new_values = [])
	{
		$CI =& get_instance();
		$CI->load->model('kesmas/Activity_logs_model');

		$data = [
			'action' => 'update',
			'module' => $module,
			'record_id' => $record_id,
			'old_values' => $old_values,
			'new_values' => $new_values,
			'description' => "Data updated: {$module} ID {$record_id}",
		];

		return $CI->Activity_logs_model->log_activity($data);
	}
}

/**
 * Get user's last login time
 * @param int $user_id
 * @return string|null
 */
if (!function_exists('get_last_login')) {
	function get_last_login($user_id = 0)
	{
		$CI =& get_instance();
		$CI->load->model('kesmas/Login_logs_model');

		$logs = $CI->Login_logs_model->get_user_login_history($user_id, 1);

		if (!empty($logs)) {
			return $logs[0]['login_at'];
		}

		return NULL;
	}
}

/**
 * Check failed login attempts
 * @param string $username
 * @param int $minutes
 * @return int
 */
if (!function_exists('check_failed_attempts')) {
	function check_failed_attempts($username = '', $minutes = 30)
	{
		$CI =& get_instance();
		$CI->load->model('kesmas/Login_logs_model');

		return $CI->Login_logs_model->get_failed_attempts_by_username($username, $minutes);
	}
}

/**
 * Lock user account after too many attempts
 * @param string $username
 * @param int $max_attempts
 * @return bool
 */
if (!function_exists('lock_account_on_failed')) {
	function lock_account_on_failed($username = '', $max_attempts = 5)
	{
		$CI =& get_instance();
		$CI->load->model('kesmas/Login_logs_model');

		$attempts = $CI->Login_logs_model->get_failed_attempts_by_username($username, 30);

		if ($attempts >= $max_attempts) {
			return $CI->Login_logs_model->lock_account($username);
		}

		return false;
	}
}

// =================================================================-------
// BERIKUT ADALAH FUNGSI BAWAAN SISTEM CI3 YANG DITAMBAHKAN KEMBALI
// KARENA HELPER INI MENIMPA HELPER SECURITY BAWAAN SECARA KESELURUHAN
// =================================================================-------

if ( ! function_exists('xss_clean'))
{
	function xss_clean($str, $is_image = FALSE)
	{
		return get_instance()->security->xss_clean($str, $is_image);
	}
}

if ( ! function_exists('sanitize_filename'))
{
	function sanitize_filename($filename)
	{
		return get_instance()->security->sanitize_filename($filename);
	}
}

if ( ! function_exists('do_hash'))
{
	function do_hash($str, $type = 'sha1')
	{
		if ( ! in_array(strtolower($type), hash_algos()))
		{
			$type = 'md5';
		}

		return hash($type, $str);
	}
}

if ( ! function_exists('strip_image_tags'))
{
	function strip_image_tags($str)
	{
		return get_instance()->security->strip_image_tags($str);
	}
}

if ( ! function_exists('encode_php_tags'))
{
	function encode_php_tags($str)
	{
		return str_replace(array('<?', '?>'), array('&lt;?', '?&gt;'), $str);
	}
}

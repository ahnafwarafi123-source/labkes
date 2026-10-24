<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Security Controller
 *
 * @property PHPExcel $excel
 */
class Security extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		
		// Only admin can access security features - Refactored to use require_role
        $this->require_role(['admin']);

		$this->load->model(['kesmas/Login_logs_model', 'kesmas/Activity_logs_model', 'kesmas/Data_pending_model', 'kesmas/User_model']);
	}

	/**
	 * Dashboard view of security metrics
	 */
	public function index()
	{
		// Get login statistics
		$data['login_stats'] = $this->Login_logs_model->get_login_stats('day');
		$data['login_logs'] = $this->Login_logs_model->get_all_login_logs(10);

		// Get activity statistics
		$data['activity_stats'] = $this->Activity_logs_model->get_activity_stats('day');
		$data['recent_activities'] = $this->Activity_logs_model->get_activity_logs([], 10);

		// Get pending data stats
		$data['pending_summary'] = $this->Data_pending_model->get_summary();
		$data['pending_items'] = $this->Data_pending_model->get_pending_items('', 5);

		// Get pending count by type
		$data['pending_by_type'] = $this->Data_pending_model->get_count_by_type();

		$data['title'] = 'Security Dashboard';
		$data['content'] = 'kesmas/security/dashboard';

		$this->load->view('kesmas/layout/main', $data);
	}

	/**
	 * View all login logs
	 */
	public function login_logs()
	{
		$page = $this->input->get('page') ?: 1;
		$limit = 50;
		$offset = ($page - 1) * $limit;

		$filter_status = $this->input->get('status') ?: '';
		$filter_username = $this->input->get('username') ?: '';

		$filters = [];
		if ($filter_status) {
			$filters['status'] = $filter_status;
		}
		if ($filter_username) {
			$filters['username'] = $filter_username;
		}

		// Get logs with filters
		$this->db->order_by('login_at', 'DESC');
		if ($filter_status) $this->db->where('status', $filter_status);
		if ($filter_username) $this->db->where('username', $filter_username);

		$total = $this->db->get('login_logs')->num_rows();
		$logs = [];

		if ($total > 0) {
			$this->db->order_by('login_at', 'DESC');
			if ($filter_status) $this->db->where('status', $filter_status);
			if ($filter_username) $this->db->where('username', $filter_username);
			$this->db->limit($limit, $offset);
			$logs = $this->db->get('login_logs')->result_array();
		}

		$data['logs'] = $logs;
		$data['total'] = $total;
		$data['page'] = $page;
		$data['limit'] = $limit;
		$data['filter_status'] = $filter_status;
		$data['filter_username'] = $filter_username;
		$data['title'] = 'Login Logs';
		$data['content'] = 'kesmas/security/login_logs';

		$this->load->view('kesmas/layout/main', $data);
	}

	/**
	 * View all activity logs
	 */
	public function activity_logs()
	{
		$page = $this->input->get('page') ?: 1;
		$limit = 50;
		$offset = ($page - 1) * $limit;

		$filters = [
			'user_id' => $this->input->get('user_id') ?: '',
			'action' => $this->input->get('action') ?: '',
			'module' => $this->input->get('module') ?: '',
			'start_date' => $this->input->get('start_date') ?: '',
			'end_date' => $this->input->get('end_date') ?: '',
		];

		$total = $this->Activity_logs_model->count_activity_logs($filters);
		$logs = $this->Activity_logs_model->get_activity_logs($filters, $limit, $offset);

		// Get users for filter dropdown
		$this->db->select('id, username, nama');
		$data['users'] = $this->db->get('user_kesmas')->result_array();

		// Get unique actions and modules
		$this->db->distinct();
		$this->db->select('action');
		$data['actions'] = $this->db->get('activity_logs')->result_array();

		$this->db->reset_query();
		$this->db->distinct();
		$this->db->select('module');
		$data['modules'] = $this->db->get('activity_logs')->result_array();

		$data['logs'] = $logs;
		$data['total'] = $total;
		$data['page'] = $page;
		$data['limit'] = $limit;
		$data['filters'] = $filters;
		$data['title'] = 'Activity Logs';
		$data['content'] = 'kesmas/security/activity_logs';

		$this->load->view('kesmas/layout/main', $data);
	}

	/**
	 * View activity details
	 */
	public function activity_detail($id)
	{
		$this->db->where('id', $id);
		$activity = $this->db->get('activity_logs')->row_array();

		if (!$activity) {
			show_404();
		}

		// Parse JSON if exists
		if ($activity['old_values']) {
			$activity['old_values'] = json_decode($activity['old_values'], true);
		}
		if ($activity['new_values']) {
			$activity['new_values'] = json_decode($activity['new_values'], true);
		}

		// Get user info
		$activity['user'] = $this->User_model->get_user($activity['user_id']);

		$data['activity'] = $activity;
		$data['title'] = 'Activity Detail';
		$data['content'] = 'kesmas/security/activity_detail';

		$this->load->view('kesmas/layout/main', $data);
	}

	/**
	 * View pending data
	 */
	public function pending_data()
	{
		$page = $this->input->get('page') ?: 1;
		$limit = 50;
		$offset = ($page - 1) * $limit;

		$filters = [
			'status' => $this->input->get('status') ?: 'pending',
			'data_type' => $this->input->get('data_type') ?: '',
		];

		$total = $this->Data_pending_model->count_pending_data($filters);
		$items = $this->Data_pending_model->get_pending_data($filters, $limit, $offset);

		// Get unique data types
		$this->db->reset_query();
		$this->db->distinct();
		$this->db->select('data_type');
		$data['data_types'] = $this->db->get('data_pending')->result_array();

		$data['items'] = $items;
		$data['total'] = $total;
		$data['page'] = $page;
		$data['limit'] = $limit;
		$data['filters'] = $filters;
		$data['title'] = 'Pending Data';
		$data['content'] = 'kesmas/security/pending_data';

		$this->load->view('kesmas/layout/main', $data);
	}

	/**
	 * Approve pending data
	 */
	public function approve_pending($id)
	{
		// Hanya user modul Kesmas yang boleh mengubah data kesmas
		if (current_module() !== 'kesmas') {
			deny_cross_module();
		}

		$item = $this->Data_pending_model->get_pending($id);

		if (!$item) {
			$this->session->set_flashdata('error', 'Pending item not found');
			redirect('k-panel/security/pending_data');
		}

		$this->Data_pending_model->approve($id, $this->session->user_id);

		// Log activity
		log_activity('approve', 'data_pending', [
			'record_id' => $id,
			'description' => "Approved pending {$item['data_type']} data (ID: {$item['data_id']})"
		]);

		$this->session->set_flashdata('success', 'Data approved successfully');
		redirect('k-panel/security/pending_data');
	}

	/**
	 * Reject pending data
	 */
	public function reject_pending($id)
	{
		// Hanya user modul Kesmas yang boleh mengubah data kesmas
		if (current_module() !== 'kesmas') {
			deny_cross_module();
		}

		$item = $this->Data_pending_model->get_pending($id);

		if (!$item) {
			$this->session->set_flashdata('error', 'Pending item not found');
			redirect('k-panel/security/pending_data');
		}

		$reason = $this->input->post('reason') ?: '';
		$this->Data_pending_model->reject($id, $this->session->user_id, $reason);

		// Log activity
		log_activity('reject', 'data_pending', [
			'record_id' => $id,
			'description' => "Rejected pending {$item['data_type']} data (ID: {$item['data_id']})"
		]);

		$this->session->set_flashdata('success', 'Data rejected successfully');
		redirect('k-panel/security/pending_data');
	}

	/**
	 * Request revision for pending data
	 */
	public function request_revision($id)
	{
		$item = $this->Data_pending_model->get_pending($id);

		if (!$item) {
			$this->session->set_flashdata('error', 'Pending item not found');
			redirect('k-panel/security/pending_data');
		}

		$note = $this->input->post('revision_note') ?: '';
		$this->Data_pending_model->request_revision($id, $note);

		// Log activity
		log_activity('request_revision', 'data_pending', [
			'record_id' => $id,
			'description' => "Requested revision for pending {$item['data_type']} data (ID: {$item['data_id']})"
		]);

		$this->session->set_flashdata('success', 'Revision requested');
		redirect('k-panel/security/pending_data');
	}

	/**
	 * Export logs
	 */
	public function export_logs($type = 'activity')
	{
		$this->load->library('excel');

		if ($type == 'login') {
			$logs = $this->Login_logs_model->get_all_login_logs(1000);
			$filename = 'login_logs_' . date('Y-m-d_H-i-s') . '.xlsx';

			$this->excel->setActiveSheetIndex(0);
			$sheet = $this->excel->getActiveSheet();
			$sheet->setTitle('Login Logs');

			// Headers
			$sheet->setCellValue('A1', 'ID');
			$sheet->setCellValue('B1', 'Username');
			$sheet->setCellValue('C1', 'Status');
			$sheet->setCellValue('D1', 'IP Address');
			$sheet->setCellValue('E1', 'Login Time');

			// Data
			$row = 2;
			foreach ($logs as $log) {
				$sheet->setCellValue('A' . $row, $log['id_permintaan']);
				$sheet->setCellValue('B' . $row, $log['username']);
				$sheet->setCellValue('C' . $row, $log['status']);
				$sheet->setCellValue('D' . $row, $log['ip_address']);
				$sheet->setCellValue('E' . $row, $log['login_at']);
				$row++;
			}

			// Configure alignment (PHPExcel library)
			$alignment = $this->excel->getDefaultStyle()->getAlignment();
			// Use reflection to set alignment if class exists
			if (class_exists('\PHPExcel_Style_Alignment')) {
				// @phpstan-ignore-next-line
				// @psalm-suppress UndefinedClass
				$alignment->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
				// @phpstan-ignore-next-line
				// @psalm-suppress UndefinedClass
				$alignment->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
			}

			header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
			header('Content-Disposition: attachment;filename="' . $filename . '"');
			header('Cache-Control: max-age=0');

			$writer = null;
			$writerFactory = null;
			$writerType = null; // Initialize variable

			if (class_exists('\PHPExcel_IOFactory')) {
				$writerFactory = '\PHPExcel_IOFactory';
				$writerType = 'Excel2007';
			} elseif (class_exists('\PhpOffice\PhpSpreadsheet\IOFactory')) {
				$writerFactory = '\PhpOffice\PhpSpreadsheet\IOFactory';
				$writerType = 'Xlsx';
			}

			if ($writerFactory) {
				/** @noinspection PhpUndefinedClassInspection */
				$writer = $writerFactory::createWriter($this->excel, $writerType);
			} elseif (class_exists('\PHPExcel_Writer_Excel2007')) {
				$writer = new \PHPExcel_Writer_Excel2007($this->excel);
			} else {
				show_error('Unable to export logs because spreadsheet writer class is unavailable.');
			}

			$writer->save('php://output');
			exit;

		} elseif ($type == 'activity') {
			// Similar implementation for activity logs
		}
	}

	/**
	 * Cleanup old logs
	 */
	public function cleanup($type = 'all', $days = 90)
	{
		if ($this->session->role != 'admin') {
			$this->session->set_flashdata('error', 'Unauthorized');
			redirect('k-panel/security');
		}

		$days = (int) $days;
		if ($days <= 0) {
			$days = 0; // Force delete all matching records
		}

		$deleted_login = 0;
		$deleted_activity = 0;
		$deleted_pending = 0;

		if ($type == 'login' || $type == 'all') {
			$deleted_login = $this->Login_logs_model->delete_old_logs($days);
		}

		if ($type == 'activity' || $type == 'all') {
			$deleted_activity = $this->Activity_logs_model->delete_old_logs($days);
		}

		if ($type == 'pending' || $type == 'all') {
			$deleted_pending = $this->Data_pending_model->cleanup_old_items($days);
		}

		$messageParts = [];
		if ($deleted_login !== 0) {
			$messageParts[] = "Login logs: {$deleted_login}";
		}
		if ($deleted_activity !== 0) {
			$messageParts[] = "Activity logs: {$deleted_activity}";
		}
		if ($deleted_pending !== 0) {
			$messageParts[] = "Pending items: {$deleted_pending}";
		}

		if (!empty($messageParts)) {
			$successMessage = 'Cleanup completed: ' . implode(', ', $messageParts);
		} else {
			if ($days === 0) {
				$successMessage = 'Cleanup completed, but no matching records were found.';
			} else {
				$successMessage = "No old log entries older than {$days} days were found.";
			}
		}

		$this->session->set_flashdata('success', $successMessage);
		redirect('k-panel/security');
	}

}

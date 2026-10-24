<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login_logs extends MY_KlinikController {

    public function __construct()
    {
        parent::__construct();
        
        // Memuat model log login klinik
        $this->load->model('Klinik/Klinik_login_logs_model');
        
        // Validasi hak akses: Hanya role 'admin' yang diperbolehkan mengakses halaman ini
        $user_role = strtolower((string) $this->session->userdata('klinik_role'));
        if ($user_role !== 'admin') {
            $this->session->set_flashdata('error', 'Anda tidak memiliki hak akses untuk membuka halaman log login.');
            redirect('dashboard');
            exit;
        }
    }

    public function index()
    {
        $data['title'] = 'Log Login Akun Klinik';
        $data['logs'] = $this->Klinik_login_logs_model->get_login_logs(1000); // Batasi mengambil 1000 log terbaru

        // Load layouts & view
        $this->load->view('klinik/layout/header', $data);
        $this->load->view('klinik/layout/sidebar');
        $this->load->view('klinik/login_logs/index', $data);
        $this->load->view('klinik/layout/footer');
    }

    /**
     * Menghapus seluruh log login via AJAX (Akses khusus Admin)
     */
    public function clear_logs()
    {
        // Cek request via AJAX
        if (!$this->input->is_ajax_request()) {
            show_error('No direct script access allowed', 403);
        }

        $this->load->helper('security');

        // Lakukan pengosongan tabel log
        if ($this->Klinik_login_logs_model->clear_all_logs()) {
            // Catat log aktivitas administratif (audit trail)
            if (function_exists('log_activity')) {
                log_activity('delete', 'login_logs', [
                    'description' => 'Menghapus seluruh riwayat log login klinik'
                ]);
            }
            
            // Catat log keamanan CI3
            log_message('error', 'Security Alert: Admin ID ' . $this->session->userdata('klinik_id_user') . ' cleared all login logs.');

            $response = [
                'status'  => 'success',
                'message' => 'Seluruh riwayat log login berhasil dihapus.'
            ];
        } else {
            $response = [
                'status'  => 'error',
                'message' => 'Gagal menghapus riwayat log login.'
            ];
        }

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
    }
}

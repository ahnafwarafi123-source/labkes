<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * MY_Controller
 *
 * @property CI_Loader $load
 * @property CI_Input $input
 * @property CI_Session $session
 * @property CI_Form_validation $form_validation
 * @property CI_DB_query_builder $db
 * @property CI_Config $config
 * @property CI_Security $security
 *
 * // Model-model utama
 * @property Kesmas_model $Kesmas_model
 * @property User_model $User_model
 * @property Tindakan_sampel_model $Tindakan_sampel_model
 * @property Verifikator_model $Verifikator_model
 * @property Penanggung_jawab_teknis_model $pjt_model
 * @property Survei_kepuasan_model $Survei_kepuasan_model
 *
 * // Model untuk fitur keamanan
 * @property Login_logs_model $Login_logs_model
 * @property Activity_logs_model $Activity_logs_model
 * @property Data_pending_model $Data_pending_model
 */
class MY_Controller extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        
        // SECURITY: Block direct access to physical directory
        // Hanya izinkan akses lewat rute k-panel (kesuali Public controller yang tidak pakai MY_Controller)
        if ($this->uri->segment(1) === 'kesmas') {
            show_404();
            exit;
        }

        // SECURITY: Validate session on every request
        // Semua controller yang extends MY_Controller butuh login + session validation
        if (!is_logged_in()) {
            require_login();
        }

        // SECURITY: Baca antar-modul boleh, mengubah (write) data kesmas hanya untuk modul kesmas.
        if (request_is_write() && current_module() !== 'kesmas') {
            deny_cross_module();
        }
    }

    /**
     * Check if user is logged in, redirect if not
     */
    protected function require_login(): void
    {
        if (!is_logged_in()) {
            redirect('login');
            exit;
        }
    }

    /**
     * Check if user has one of the required roles, redirect if not.
     * @param array $roles Array of allowed roles (e.g., ['admin', 'petugas'])
     */
    protected function require_role(array $roles): void
    {
        $this->require_login(); // Ensure user is logged in first
        $user_role = $this->session->userdata('role');
        
        // Allow if user has any of the specified roles
        if (!in_array($user_role, $roles)) {
            // Set a flash message for better user feedback
            $this->session->set_flashdata('error', 'Akses ditolak. Anda tidak memiliki izin untuk mengakses halaman ini. Halaman ini hanya untuk role: ' . implode(', ', $roles) . '.');
            redirect('dashboard'); // Redirect ke halaman dashboard terpadu
            exit;
        }
    }

    protected function render(string $view, array $data = [])
    {
        $data['current_user'] = current_user();
        // Layout terpadu (basis Klinik) dipakai untuk seluruh halaman internal.
        $this->load->view('klinik/layout/header', $data);
        $this->load->view('klinik/layout/sidebar', $data);
        $this->load->view($view, $data);
        $this->load->view('klinik/layout/footer', $data);
    }
}

/**
 * MY_KlinikController
 * Base controller for all clinic controllers to enforce login authentication.
 */
class MY_KlinikController extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        
        // Load required resources for authentication
        $this->load->library('session');
        $this->load->helper('url');
        
        // Athorized user = semua yang sudah login (modul lintas masuk boleh melihat halaman Klinik).
        // Otorisasi role spesifik ditangani per controller seperti sebelumnya.
        if (!is_logged_in()) {
            redirect('login');
            exit;
        }

        // SECURITY: Baca antar-modul boleh, mengubah (write) data klinik hanya untuk modul klinik.
        if (request_is_write() && current_module() !== 'klinik') {
            deny_cross_module();
        }
    }

    /**
     * Check if the logged-in klinik user has one of the required roles.
     * @param array $roles Array of allowed roles (e.g., ['admin', 'petugas pendaftaran'])
     */
    protected function require_role(array $roles): void
    {
        $user_role = strtolower((string) $this->session->userdata('klinik_role'));
        if (!in_array($user_role, $roles)) {
            $this->session->set_flashdata('error', 'Akses ditolak. Anda tidak memiliki izin untuk mengakses halaman ini. Halaman ini hanya untuk role: ' . implode(', ', $roles) . '.');
            redirect('dashboard');
            exit;
        }
    }
}
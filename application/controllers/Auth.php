<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Auth Controller — Unified Login (Auto Detection)
 *
 * Login tunggal untuk seluruh modul aplikasi (Kesmas + Klinik).
 * Tidak ada lagi pilihan modul pada form login; sistem mendeteksi
 * modul secara otomatis berdasarkan kecocokan password di kedua tabel:
 * - User Klinik disimpan di tabel `users` (session key prefix `klinik_`).
 * - User Kesmas disimpan di tabel `user_kesmas` (session key `user_id`, `role`).
 * - Session tambahan `module` (kesmas/klinik) untuk navigasi sidebar gabungan.
 */
class Auth extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->helper(['security', 'form', 'url']);
    }

    public function index()
    {
        if (is_logged_in()) {
            redirect($this->_dashboard_for_current_user());
            return;
        }

        $data = array('title' => 'Login');
        $this->load->view('auth/login', $data);
    }

    public function do_login()
    {
        if (is_logged_in()) {
            redirect($this->_dashboard_for_current_user());
            return;
        }

        $this->form_validation->set_rules('username', 'Username', 'required|trim|xss_clean');
        $this->form_validation->set_rules('password', 'Password', 'required|xss_clean');

        if (!$this->form_validation->run()) {
            $this->session->set_flashdata('error', validation_errors_text() ?: 'Input tidak valid!');
            redirect('login');
            return;
        }

        $username = $this->input->post('username', true);
        $password = $this->input->post('password', true);

        // Cari user di kedua tabel
        $klinik = $this->_klinik_user_row($username);
        $kesmas = $this->_kesmas_user_row($username);

        // Username tidak ditemukan di kedua tabel
        if (!$klinik && !$kesmas) {
            log_login_attempt([
                'username' => $username,
                'status' => 'failed',
                'failure_reason' => 'Username not found in any module'
            ]);

            $this->session->set_flashdata('error', 'Username atau password salah.');
            redirect('login');
            return;
        }

        // Deteksi otomatis: masuki modul yang password-nya cocok (klinik diperiksa lebih dulu).
        if ($klinik && password_verify($password, $klinik->password)) {
            $this->_do_klinik_login($klinik);
            return;
        }
        if ($kesmas && password_verify($password, $kesmas['password_hash'])) {
            $this->_do_kesmas_login($kesmas);
            return;
        }

        // Username ada di kedua tabel dan password salah di kedua-duanya
        if ($klinik && $kesmas) {
            $this->_count_klinik_failure($klinik, $username);
            log_login_attempt([
                'username' => $username,
                'status' => 'failed',
                'failure_reason' => 'Invalid credentials in both modules'
            ]);
            lock_account_on_failed($username, 5);

            $this->session->set_flashdata('error', 'Username atau password salah.');
            redirect('login');
            return;
        }

        // Hanya user klinik yang cocok tapi password salah
        if ($klinik) {
            $this->_klinik_login_failed($username, $klinik);
            return;
        }

        // Hanya user kesmas yang cocok tapi password salah
        $this->_kesmas_login_failed($username, $kesmas);
    }

    /**
     * Ambil baris user klinik dari tabel `users` (tanpa efek samping).
     * @return object|null
     */
    private function _klinik_user_row(string $username)
    {
        return $this->db->get_where('users', array('username' => $username))->row();
    }

    /**
     * Ambil baris user kesmas aktif dari tabel `user_kesmas` (tanpa efek samping).
     * @return array|null
     */
    private function _kesmas_user_row(string $username)
    {
        $row = $this->db->get_where('user_kesmas', array('username' => $username, 'is_active' => 1))->row_array();
        return $row ?: null;
    }

    /**
     * Bangun session user klinik serta catat log login sukses.
     */
    private function _do_klinik_login(object $klinik_user): void
    {
        $this->load->model('Klinik/Klinik_login_logs_model');
        $this->Klinik_login_logs_model->log_login([
            'user_id'  => $klinik_user->id_user,
            'username' => $klinik_user->username,
            'status'   => 'success'
        ]);

        $this->session->sess_regenerate(false);
        $this->session->set_userdata([
            'module'           => 'klinik',
            'klinik_id_user'   => $klinik_user->id_user,
            'klinik_username'  => $klinik_user->username,
            'klinik_nama'      => $klinik_user->nama,
            'klinik_role'      => $klinik_user->klinik_role,
            'klinik_logged_in' => TRUE
        ]);

        // SECURITY: Catat session klinik ke database untuk validasi (sama seperti kesmas)
        if ($this->db->table_exists('active_sessions')) {
            $session_id = session_id();
            $this->db->insert('active_sessions', [
                'session_id' => $session_id,
                'user_id' => (int)$klinik_user->id_user,
                'username' => $klinik_user->username,
                'ip_address' => get_client_ip(),
                'user_agent' => substr($_SERVER['HTTP_USER_AGENT'] ?? '', 0, 255),
                'created_at' => date('Y-m-d H:i:s'),
                'expires_at' => date('Y-m-d H:i:s', time() + 7200), // 2 hours
                'is_valid' => true
            ]);
        }

        redirect('dashboard');
    }

    /**
     * Bangun session user kesmas serta catat log login sukses + aktivitas.
     */
    private function _do_kesmas_login(array $user): void
    {
        $user_id = isset($user['id']) ? (int)$user['id'] : 0;
        if ($user_id <= 0) {
            $this->session->set_flashdata('error', 'Login gagal: data user tidak valid.');
            redirect('login');
            return;
        }

        log_login_attempt([
            'user_id' => $user_id,
            'username' => $user['username'],
            'status' => 'success',
        ]);

        // SECURITY: Regenerate session ID to prevent session fixation
        $this->session->sess_regenerate(false);

        $this->session->set_userdata(array(
            'module'    => 'kesmas',
            'user_id'   => $user_id,
            'username'  => $user['username'],
            'nama'      => $user['nama'],
            'role'      => $user['role'],
        ));

        // SECURITY: Create session record in database for validation
        if ($this->db->table_exists('active_sessions')) {
            $session_id = session_id();
            $this->db->insert('active_sessions', [
                'session_id' => $session_id,
                'user_id' => $user_id,
                'username' => $user['username'],
                'ip_address' => get_client_ip(),
                'user_agent' => substr($_SERVER['HTTP_USER_AGENT'] ?? '', 0, 255),
                'created_at' => date('Y-m-d H:i:s'),
                'expires_at' => date('Y-m-d H:i:s', time() + 7200), // 2 hours
                'is_valid' => true
            ]);
        }

        // Log user activity
        log_activity('login', 'auth', [
            'description' => "User {$user['username']} logged in"
        ]);

        redirect('dashboard');
    }

    /**
     * Login gagal untuk user klinik (klinik adalah satu-satunya kandidat).
     * Mencatat percobaan gagal + menghitung brute force, lalu redirect.
     */
    private function _klinik_login_failed(string $username, object $klinik_user): void
    {
        $brute = $this->_count_klinik_failure($klinik_user, $username);

        if ($brute['is_blocked']) {
            $remaining = $brute['remaining_seconds'];
            $time_str = floor($remaining / 60) > 0
                ? floor($remaining / 60) . ' menit ' . ($remaining % 60) . ' detik'
                : $remaining . ' detik';
            $this->session->set_flashdata('error', "Batas percobaan login terlampaui. Username atau IP Anda diblokir sementara. Sisa waktu: {$time_str}");
        } else {
            $max_attempts = 3;
            $failed_count = max($brute['failed_attempts_user'], $brute['failed_attempts_ip']);
            $remaining = max(0, $max_attempts - $failed_count);
            $this->session->set_flashdata('error', "Username atau password salah! Sisa percobaan login Anda: {$remaining} kali.");
        }

        redirect('login');
    }

    /**
     * Login gagal untuk user kesmas (kesmas adalah satu-satunya kandidat).
     */
    private function _kesmas_login_failed(string $username, array $user): void
    {
        log_login_attempt([
            'username' => $username,
            'status' => 'failed',
            'failure_reason' => 'Invalid credentials'
        ]);

        lock_account_on_failed($username, 5);

        $this->session->set_flashdata('error', 'Username / password salah.');
        redirect('login');
    }

    /**
     * Catat percobaan login klinik yang gagal + hitung status brute force.
     * @return array status brute force terbaru (is_blocked, remaining_seconds, ...)
     */
    private function _count_klinik_failure(object $klinik_user, string $username): array
    {
        $this->load->model('Klinik/Klinik_login_logs_model');
        $ip_address = $this->Klinik_login_logs_model->get_client_ip();

        $this->Klinik_login_logs_model->log_login([
            'user_id'        => $klinik_user->id_user,
            'username'       => $username,
            'status'         => 'failed',
            'failure_reason' => 'Password incorrect'
        ]);
        log_message('error', 'Security Alert: Unauthorized login attempt for username: ' . $username);

        return $this->Klinik_login_logs_model->check_brute_force($username, $ip_address);
    }

    public function logout()
    {
        $module = $this->session->userdata('module');

        // SECURITY: Log aktivitas logout untuk user kesmas
        if ($module === 'kesmas' && $this->session->userdata('user_id')) {
            log_activity('logout', 'auth', [
                'description' => "User {$this->session->userdata('username')} logged out"
            ]);
        }

        // SECURITY: Invalidate session record di database (kesmas & klinik)
        if (($module === 'kesmas' || $module === 'klinik') && $this->db->table_exists('active_sessions')) {
            $session_id = session_id();
            $this->db->update('active_sessions',
                ['is_valid' => false],
                ['session_id' => $session_id]
            );
        }

        // SECURITY: Destroy session completely
        $this->session->sess_destroy();
        redirect('login');
    }

    /**
     * Tentukan dashboard tujuan setelah login berdasarkan module aktif.
     */
    private function _dashboard_for_current_user(): string
    {
        // Satu halaman dashboard terpadu untuk semua modul.
        return 'dashboard';
    }
}
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Get client IP address
 */
function get_client_ip(): string {
    $ip = '';
    
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        // Pecah berdasar koma jika melewati Load Balancer, dan ambil IP asli (pertama)
        $ips = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
        $ip = trim($ips[0]);
    } else {
        $ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
    }
    
    return filter_var($ip, FILTER_VALIDATE_IP) ? $ip : '0.0.0.0';
}

/**
 * Check if user is logged in AND session is valid.
 * Login dianggap aktif jika session KLINIK (`klinik_logged_in`) OR
 * session KESMAS (`user_id`) valid (dengan validasi database).
 */
function is_logged_in(): bool {
    $CI =& get_instance();

    // Session aktif dari modul klinik (divalidasi DB, sama seperti kesmas)
    if ($CI->session->userdata('klinik_logged_in') === TRUE) {
        $klinik_id = (int)$CI->session->userdata('klinik_id_user');
        if ($klinik_id < 1) {
            return false;
        }
        return validate_session_record($klinik_id);
    }

    $user_id = $CI->session->userdata('user_id');
    if (!$user_id) {
        return false;
    }

    // Validasi session terhadap database
    return validate_session_record($user_id);
}

/**
 * Validasi session dari database
 * Check: IP address, user-agent, session expiration
 */
function validate_session_record(int $user_id): bool {
    $CI =& get_instance();
    
    // Skip validation jika database tidak tersedia
    if (!$CI->db || !$CI->db->table_exists('active_sessions')) {
        return true; // Fallback: izinkan jika table belum ada
    }
    
    try {
        $session_id = session_id();
        $current_ip = get_client_ip();
        $current_agent = substr($_SERVER['HTTP_USER_AGENT'] ?? '', 0, 255);
        
        // Cari session record di database
        $session_record = $CI->db->where([
            'session_id' => $session_id,
            'user_id' => $user_id,
            'is_valid' => true
        ])->get('active_sessions')->row_array();
        
        if (!$session_record) {
            // Session tidak ditemukan atau sudah invalid
            _safe_session_destroy($CI);
            return false;
        }
        
        // Validasi expiration
        if (strtotime($session_record['expires_at']) < time()) {
            // Session expired
            $CI->db->update('active_sessions', ['is_valid' => false], ['id' => $session_record['id']]);
            _safe_session_destroy($CI);
            return false;
        }
        
        // Validasi IP dan User-Agent (anti-hijacking)
        if ($session_record['ip_address'] !== $current_ip || 
            $session_record['user_agent'] !== $current_agent) {
            // IP atau User-Agent berubah = potential session hijacking
            $CI->db->update('active_sessions', ['is_valid' => false], ['id' => $session_record['id']]);
            _safe_session_destroy($CI);
            return false;
        }
        
        return true;
    } catch (Exception $e) {
        // Jika error, fallback ke base validation
        return (bool) $CI->session->userdata('user_id');
    }
}

/**
 * Hancurkan session CI secara aman (hindari warning PHP 8 saat session belum aktif).
 */
function _safe_session_destroy($CI): void {
    if (!function_exists('session_status') || session_status() === PHP_SESSION_ACTIVE) {
        $CI->session->sess_destroy();
    }
}

/**
 * Apakah request bersifat menulis data (POST/PUT/PATCH/DELETE).
 * Digunakan untuk gerbang modul: baca antar-modul boleh, ubah antar-modul tidak.
 */
function request_is_write(): bool {
    $method = strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET');
    return in_array($method, ['POST', 'PUT', 'PATCH', 'DELETE'], true);
}

/**
 * Tolak aksi lintas-modul. AJAX => JSON 403, selain itu flash + redirect ke dashboard.
 */
function deny_cross_module(): void {
    $CI =& get_instance();

    if ($CI->input->is_ajax_request()) {
        $CI->output->set_content_type('application/json')
            ->set_status_header(403)
            ->set_output(json_encode([
                'ok' => false,
                'message' => 'Anda tidak memiliki izin untuk mengubah data modul lain.',
            ]));
        exit;
    }

    $CI->session->set_flashdata('error', 'Anda tidak memiliki izin untuk mengubah data modul lain.');
    redirect('dashboard');
    exit;
}

function require_login(): void {
    $CI =& get_instance();
    if (!is_logged_in()) {
        redirect('login');
        exit;
    }
}

/**
 * Modul aktif dari session login ('kesmas' | 'klinik').
 * Dipakai untuk navigasi pada sidebar gabungan.
 */
function current_module(): string {
    $CI =& get_instance();
    $module = $CI->session->userdata('module');
    if ($module === 'kesmas' || $module === 'klinik') {
        return $module;
    }

    // Fallback berdasarkan kunci session lama
    if ($CI->session->userdata('klinik_logged_in') === TRUE) {
        return 'klinik';
    }
    if ($CI->session->userdata('user_id')) {
        return 'kesmas';
    }
    return '';
}

function current_user(): array {
    $CI =& get_instance();
    $module = current_module();
    $user = array(
        'user_id' => $CI->session->userdata('user_id'),
        'username' => $CI->session->userdata('username'),
        'nama' => $CI->session->userdata('nama'),
        'role' => $CI->session->userdata('role'),
        'module' => $module,
    );

    // Untuk user klinik, ambil dari kunci session klinik
    if ($module === 'klinik') {
        $user['user_id']  = $CI->session->userdata('klinik_id_user');
        $user['username'] = $CI->session->userdata('klinik_username');
        $user['nama']     = $CI->session->userdata('klinik_nama');
        $user['role']     = $CI->session->userdata('klinik_role');
    }

    return $user;
}

/**
 * Nama role yang ramah tampil (untuk header/sidebar).
 */
function current_role_label(): string {
    $u = current_user();
    $role = $u['role'] ?? '';
    return (strtolower((string) $role) === 'petugas rm') ? 'Dokter' : ucfirst((string) $role);
}

function is_admin(): bool {
    $u = current_user();
    return isset($u['role']) && $u['role'] === 'admin';
}

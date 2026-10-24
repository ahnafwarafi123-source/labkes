<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Audit Log Helper Functions
 */

/**
 * Log audit activity
 * @param int $user_id - ID user yang melakukan aksi
 * @param string $tabel_terkait - Nama tabel yang diubah
 * @param string $aksi - Aksi yang dilakukan (INSERT, UPDATE, DELETE, CREATE, READ, etc)
 * @param string $keterangan - Deskripsi detail perubahan
 * @param string $ip_address - IP address (optional)
 */
if ( ! function_exists('log_audit')) {
    function log_audit($user_id, $tabel_terkait, $aksi, $keterangan = NULL, $ip_address = NULL)
    {
        /**
         * @var CI_Controller & object{Audit_log_model: Audit_log_model} $CI
         */
        $CI = &get_instance();
        
        // Fail gracefully jika modul Audit Log sudah dihapus dari sistem
        if (file_exists(APPPATH . 'models/kesmas/Audit_log_model.php')) {
            $CI->load->model('kesmas/Audit_log_model');
            if (method_exists($CI->Audit_log_model, 'log')) {
                return $CI->Audit_log_model->log($user_id, $tabel_terkait, $aksi, $keterangan, $ip_address);
            }
        }
        return false;
    }
}

/**
 * Format harga ke Rp
 */
if ( ! function_exists('format_rp')) {
    function format_rp($amount)
    {
        return 'Rp ' . number_format($amount, 0, ',', '.');
    }
}

/**
 * Format rating stars
 */
if ( ! function_exists('format_rating_stars')) {
    function format_rating_stars($rating)
    {
        return str_repeat('⭐', (int)$rating);
    }
}

/**
 * Get status badge color
 */
if ( ! function_exists('get_status_badge')) {
    function get_status_badge($status)
    {
        $badges = array(
            'INSERT' => 'badge-success',
            'UPDATE' => 'badge-info',
            'DELETE' => 'badge-danger',
            'CREATE' => 'badge-success',
            'READ' => 'badge-secondary',
            'Bagus' => 'badge-success',
            'Rusak' => 'badge-danger',
            'Perawatan' => 'badge-warning',
            'Layak' => 'badge-success',
            'Tidak Layak' => 'badge-danger'
        );
        
        return isset($badges[$status]) ? $badges[$status] : 'badge-secondary';
    }
}

/**
 * Format datetime to a 'time ago' string.
 * e.g., "5 menit lalu", "2 jam lalu", "kemarin", "3 hari lalu"
 */
if ( ! function_exists('time_ago')) {
    function time_ago($datetime, $full = false) {
        if (empty($datetime)) return 'N/A';
        $now = new DateTime;
        $ago = new DateTime($datetime);
        $diff = $now->diff($ago);

        $diff->w = floor($diff->d / 7);
        $diff->d -= $diff->w * 7;

        $string = array(
            'y' => 'tahun', 'm' => 'bulan', 'w' => 'minggu', 'd' => 'hari',
            'h' => 'jam', 'i' => 'menit', 's' => 'detik',
        );
        foreach ($string as $k => &$v) {
            if ($diff->$k) {
                $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? '' : '');
            } else {
                unset($string[$k]);
            }
        }

        if (!$full) $string = array_slice($string, 0, 1);
        $result = $string ? implode(', ', $string) . ' lalu' : 'baru saja';

        // Custom logic for 'kemarin'
        if (isset($string['d']) && $diff->d == 1 && $now->format('Y-m-d') != $ago->format('Y-m-d')) return 'kemarin';

        return $result;
    }
}

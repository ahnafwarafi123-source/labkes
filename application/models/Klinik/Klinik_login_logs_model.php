<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Klinik_login_logs_model extends CI_Model
{

    private $table = 'login_logs_klinik';

    public function __construct()
    {
        parent::__construct();
        $this->check_table_exists();
    }

    /**
     * Inisialisasi tabel jika belum ada di database
     */
    private function check_table_exists()
    {
        if (!$this->db->table_exists($this->table)) {
            $this->db->query("
                CREATE TABLE IF NOT EXISTS `{$this->table}` (
                  `id` int(11) NOT NULL AUTO_INCREMENT,
                  `user_id` int(11) DEFAULT NULL,
                  `username` varchar(50) NOT NULL,
                  `status` enum('success','failed','locked') NOT NULL DEFAULT 'failed',
                  `ip_address` varchar(45) DEFAULT NULL,
                  `user_agent` text,
                  `failure_reason` varchar(255) DEFAULT NULL,
                  `login_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
                  PRIMARY KEY (`id`),
                  KEY `user_id` (`user_id`),
                  KEY `username` (`username`),
                  KEY `status` (`status`),
                  KEY `login_at` (`login_at`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
            ");
        }
    }

    /**
     * Mencatat percobaan login (sukses/gagal)
     * @param array $data
     * @return bool
     */
    public function log_login($data = [])
    {
        $login_data = [
            'user_id'        => isset($data['user_id']) ? $data['user_id'] : NULL,
            'username'       => isset($data['username']) ? $data['username'] : '',
            'status'         => isset($data['status']) ? $data['status'] : 'failed',
            'ip_address'     => $this->get_client_ip(),
            'user_agent'     => $this->input->user_agent(),
            'failure_reason' => isset($data['failure_reason']) ? $data['failure_reason'] : NULL,
        ];

        return $this->db->insert($this->table, $login_data);
    }

    /**
     * Memeriksa status brute force (rate limiting) berdasarkan username dan IP address
     * @param string $username
     * @param string $ip_address
     * @return array
     */
    public function check_brute_force($username, $ip_address)
    {
        // 1. Cek pemblokiran berdasarkan Username
        $username_blocked = FALSE;
        $username_remaining = 0;

        // Ambil waktu login sukses terakhir untuk username ini
        $last_success_user = $this->db->select('login_at')
            ->from($this->table)
            ->where('username', $username)
            ->where('status', 'success')
            ->order_by('login_at', 'DESC')
            ->limit(1)
            ->get()
            ->row();
        $last_success_user_time = $last_success_user ? $last_success_user->login_at : '1970-01-01 00:00:00';

        // Ambil 3 percobaan gagal terakhir setelah login sukses terakhir dalam kurun 5 detik
        $failed_user = $this->db->select('login_at')
            ->from($this->table)
            ->where('username', $username)
            ->where('status', 'failed')
            ->where('login_at >', $last_success_user_time)
            ->where('login_at >=', 'NOW() - INTERVAL 5 SECOND', FALSE)
            ->order_by('login_at', 'DESC')
            ->limit(3)
            ->get()
            ->result_array();

        if (count($failed_user) >= 3) {
            $username_blocked = TRUE;
            // Waktu blokir dihitung dari percobaan kegagalan tertua dari 3 percobaan terakhir
            $oldest_fail = $failed_user[2]['login_at'];
            $remaining_query = $this->db->query("SELECT TIMESTAMPDIFF(SECOND, NOW(), DATE_ADD(?, INTERVAL 5 SECOND)) AS remaining", [$oldest_fail]);
            $username_remaining = $remaining_query->row() ? max(0, (int)$remaining_query->row()->remaining) : 0;
        }

        // 2. Cek pemblokiran berdasarkan IP Address
        $ip_blocked = FALSE;
        $ip_remaining = 0;

        // Ambil waktu login sukses terakhir untuk IP ini
        $last_success_ip = $this->db->select('login_at')
            ->from($this->table)
            ->where('ip_address', $ip_address)
            ->where('status', 'success')
            ->order_by('login_at', 'DESC')
            ->limit(1)
            ->get()
            ->row();
        $last_success_ip_time = $last_success_ip ? $last_success_ip->login_at : '1970-01-01 00:00:00';

        // Ambil 3 percobaan gagal terakhir setelah login sukses terakhir dalam kurun 5 detik
        $failed_ip = $this->db->select('login_at')
            ->from($this->table)
            ->where('ip_address', $ip_address)
            ->where('status', 'failed')
            ->where('login_at >', $last_success_ip_time)
            ->where('login_at >=', 'NOW() - INTERVAL 5 SECOND', FALSE)
            ->order_by('login_at', 'DESC')
            ->limit(3)
            ->get()
            ->result_array();

        if (count($failed_ip) >= 3) {
            $ip_blocked = TRUE;
            $oldest_fail = $failed_ip[2]['login_at'];
            $remaining_query = $this->db->query("SELECT TIMESTAMPDIFF(SECOND, NOW(), DATE_ADD(?, INTERVAL 5 SECOND)) AS remaining", [$oldest_fail]);
            $ip_remaining = $remaining_query->row() ? max(0, (int)$remaining_query->row()->remaining) : 0;
        }

        return [
            'is_blocked'           => ($username_blocked || $ip_blocked),
            'blocked_by'           => $username_blocked ? 'username' : ($ip_blocked ? 'ip' : null),
            'remaining_seconds'    => max($username_remaining, $ip_remaining),
            'failed_attempts_user' => count($failed_user),
            'failed_attempts_ip'   => count($failed_ip)
        ];
    }

    /**
     * Mendapatkan log login dengan join ke tabel users untuk melihat nama lengkap
     * @param int $limit
     * @return array
     */
    public function get_login_logs($limit = 1000)
    {
        $this->db->select('l.*, u.nama as nama_user');
        $this->db->from($this->table . ' l');
        $this->db->join('users u', 'u.id_user = l.user_id', 'left');
        $this->db->order_by('l.login_at', 'DESC');
        $this->db->limit($limit);
        return $this->db->get()->result_array();
    }

    /**
     * Mendapatkan IP address client
     */
    public function get_client_ip()
    {
        if (!empty($_SERVER['HTTP_CF_CONNECTING_IP'])) {
            $ip = $_SERVER['HTTP_CF_CONNECTING_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } elseif (!empty($_SERVER['REMOTE_ADDR'])) {
            $ip = $_SERVER['REMOTE_ADDR'];
        } else {
            $ip = '0.0.0.0';
        }

        $ip_array = explode(',', $ip);
        return trim($ip_array[0]);
    }

    /**
     * Menghapus seluruh riwayat log login dari database
     * @return bool
     */
    public function clear_all_logs()
    {
        return $this->db->empty_table($this->table);
    }
}

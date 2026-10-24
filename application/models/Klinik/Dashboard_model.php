<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    // Total jumlah pasien
    public function total_pasien() {
        return $this->db->count_all('pasien');
    }

    // Total jumlah rekam medis
    public function total_rekam_medis() {
        return $this->db->count_all('rekam_medis');
    }

    // Total jumlah dokter
    public function total_dokter() {
        $this->db->where('role', 'dokter');
        return $this->db->count_all_results('user');
    }

    // Jumlah kunjungan hari ini
    public function kunjungan_hari_ini() {
        $this->db->where('DATE(tanggal_kunjungan)', date('Y-m-d'));
        return $this->db->count_all_results('kunjungan_rm');
    }

}

<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends MY_KlinikController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function index()
    {
        // Dashboard terpadu: seluruh konten dipusatkan di halaman /dashboard.
        // Metode AJAX di bawah ini tetap dipakai oleh blok dashboard terpadu.
        redirect('dashboard');
    }

    public function get_demographic_data()
    {
        $today = date('Y-m-d');

        $query = $this->db->query("
            SELECT gender, COUNT(*) as jumlah FROM (
                SELECT gender, nik FROM pasien 
                WHERE DATE(created_at) = '$today' AND gender IS NOT NULL AND gender != '' AND nik IS NOT NULL AND nik != ''
                UNION
                SELECT p.gender, p.nik FROM form_permintaan_klinik f
                JOIN pasien p ON f.id_pasien = p.id_pasien
                WHERE DATE(f.tgl_form) = '$today' AND p.gender IS NOT NULL AND p.gender != '' AND p.nik IS NOT NULL AND p.nik != ''
                UNION
                SELECT p.gender, p.nik FROM kunjungan_rm k 
                JOIN pasien p ON k.no_rm = p.no_rm 
                WHERE DATE(k.tanggal_kunjungan) = '$today' AND p.gender IS NOT NULL AND p.gender != '' AND p.nik IS NOT NULL AND p.nik != ''
            ) as pasien_unik 
            GROUP BY gender
        ");

        $counts = ['Laki-laki' => 0, 'Perempuan' => 0];
        foreach ($query->result() as $row) {
            if ($row->gender == 'Laki-laki') {
                $counts['Laki-laki'] = (int) $row->jumlah;
            } else if ($row->gender == 'Perempuan') {
                $counts['Perempuan'] = (int) $row->jumlah;
            }
        }

        echo json_encode([
            'labels' => ['Laki-laki', 'Perempuan'],
            'data' => [$counts['Laki-laki'], $counts['Perempuan']]
        ]);
    }

    public function get_monthly_kunjungan_data()
    {
        $tahun = date('Y');
        // Inisialisasi data 12 bulan dengan nilai 0
        $kunjungan_data = array_fill(1, 12, 0);

        // Kueri gabungan: Menghitung pasien unik berdasarkan NIK per bulan[cite: 2, 4, 5]
        $query = $this->db->query("
            SELECT bulan, COUNT(*) as jumlah FROM (
                SELECT MONTH(f.tgl_form) as bulan, p.nik FROM form_permintaan_klinik f
                JOIN pasien p ON f.id_pasien = p.id_pasien
                WHERE YEAR(f.tgl_form) = '$tahun' AND p.nik IS NOT NULL AND p.nik != ''
                UNION
                SELECT MONTH(k.tanggal_kunjungan) as bulan, p.nik FROM kunjungan_rm k 
                JOIN pasien p ON k.no_rm = p.no_rm 
                WHERE YEAR(k.tanggal_kunjungan) = '$tahun' AND p.nik IS NOT NULL AND p.nik != ''
            ) as gabungan_bulanan 
            GROUP BY bulan
        ");

        foreach ($query->result() as $row) {
            $kunjungan_data[(int)$row->bulan] = (int)$row->jumlah;
        }

        echo json_encode([
            'labels' => ["Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul", "Agu", "Sep", "Okt", "Nov", "Des"],
            'data' => array_values($kunjungan_data)
        ]);
    }
}
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Form_laporan_akhir extends MY_KlinikController {

    public function __construct()
    {
        parent::__construct();
        $this->require_role(['admin', 'petugas rm']);
    }

    public function index()
    {
        $this->load->view('klinik/layout/header');
        $this->load->view('klinik/layout/sidebar');
        $this->load->view('klinik/form_laporan_akhir/index');
        $this->load->view('klinik/layout/footer');
    }

    public function detail($bulan, $tahun = null)
    {
        $tahun = $tahun ?? date('Y');

        redirect('klinik/Laporan_hasil_pemeriksaan/laporan_bulanan_tahunan/' . $bulan . '/' . $tahun);
    }
}
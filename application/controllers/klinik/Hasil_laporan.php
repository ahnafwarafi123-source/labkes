<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Hasil_laporan extends MY_KlinikController {

    public function __construct() {
        parent::__construct();
        $this->load->model('Klinik/Hasil_laporan_model');
        $this->load->database();
        $this->load->library('session');
    }

    public function index() {
        $data['title'] = 'Data Hasil Uji dan SOAP';

        // Ambil list kunjungan di mana dokter sudah mengisi SOAP
        $kunjungan_list = $this->Hasil_laporan_model->get_pasien_dengan_soap();


        $data['pasien'] = $kunjungan_list;

        $this->load->view('klinik/layout/header', $data);
        $this->load->view('klinik/layout/sidebar');
        $this->load->view('klinik/hasil_uji/index', $data);
        $this->load->view('klinik/layout/footer');
    }

    public function hasil_lab($id_pasien) {
        $data['title'] = 'Laporan Hasil Uji Laboratorium & SOAP';

        // Gunakan query Multi-Tabel
        $results = $this->Hasil_laporan_model->get_detail_hasil_uji($id_pasien);

        if (empty($results)) {
            $this->session->set_flashdata('error', 'Data tidak ditemukan untuk kunjungan ini.');
            redirect('klinik/hasil_laporan');
        }

        // Ambil data single (Identitas & SOAP) dari baris pertama
        $first_row = $results[0];
        $data['pasien'] = clone $first_row; // Untuk digunakan di view

        // Looping untuk mengelompokkan detail pemeriksaan
        $data['hasil'] = [];
        foreach ($results as $row) {
            if (!empty($row->id_detail) && $row->hasil !== null) { // Pastikan ada hasil
                $data['hasil'][] = $row;
            }
        }

        // Data form & pengambilan (dari baris pertama karena sama untuk satu form_id)
        $data['form'] = $first_row;
        $data['pengambilan'] = $first_row;

        $this->load->view('klinik/layout/header', $data);
        $this->load->view('klinik/layout/sidebar');
        $this->load->view('klinik/hasil_uji/hasil_lab', $data);
        $this->load->view('klinik/layout/footer');
    }
}

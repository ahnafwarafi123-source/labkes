<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Petugas_validasi extends MY_KlinikController {

    public function __construct() {
        parent::__construct();
        $this->load->model('Klinik/Petugas_validasi_model');
        $this->require_role(['admin']);
    }

    public function index() {
        $data['title'] = "Data Petugas Validasi";
        $data['petugas'] = $this->Petugas_validasi_model->get_all();

        $this->load->view('klinik/layout/header', $data);
        $this->load->view('klinik/layout/sidebar');
        $this->load->view('klinik/petugas_validasi/index', $data);
        $this->load->view('klinik/layout/footer');
    }

    public function create() {
        $data['title'] = "Tambah Petugas Validasi";
        $data['petugas'] = null;

        $this->load->view('klinik/layout/header', $data);
        $this->load->view('klinik/layout/sidebar');
        $this->load->view('klinik/petugas_validasi/form', $data);
        $this->load->view('klinik/layout/footer');
    }

    public function create_action() {
        $data = [
            'nama_petugas' => $this->input->post('nama_petugas', TRUE),
            'jabatan'      => $this->input->post('jabatan', TRUE)
        ];
        $this->Petugas_validasi_model->insert($data);
        $this->session->set_flashdata('success', 'Data petugas validasi berhasil ditambahkan.');

        redirect('klinik/petugas_validasi');
    }

    public function edit($id) {
        $data['title'] = "Edit Petugas Validasi";
        $data['petugas'] = $this->Petugas_validasi_model->get_by_id($id);

        $this->load->view('klinik/layout/header', $data);
        $this->load->view('klinik/layout/sidebar');
        $this->load->view('klinik/petugas_validasi/form', $data);
        $this->load->view('klinik/layout/footer');
    }

    public function edit_action($id) {
        $data = [
            'nama_petugas' => $this->input->post('nama_petugas', TRUE),
            'jabatan'      => $this->input->post('jabatan', TRUE)
        ];
        $this->Petugas_validasi_model->update($id, $data);
        $this->session->set_flashdata('success', 'Data petugas validasi berhasil diperbarui.');

        redirect('klinik/petugas_validasi');
    }

    public function delete($id) {
        // Hanya user modul Klinik yang boleh mengubah data klinik
        if (current_module() !== 'klinik') {
            deny_cross_module();
        }

        $this->Petugas_validasi_model->delete($id);
        $this->session->set_flashdata('success', 'Data petugas validasi berhasil dihapus.');

        redirect('klinik/petugas_validasi');
    }
}

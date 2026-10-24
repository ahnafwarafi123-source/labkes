<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Petugas_dokter extends MY_KlinikController
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Klinik/Petugas_dokter_model');
        $this->load->library('session');
        $this->require_role(['admin']);
    }

    public function index()
    {
        $data['title'] = 'Data Dokter Pemeriksa';
        $data['dokters'] = $this->Petugas_dokter_model->get_all();

        $this->load->view('klinik/layout/header', $data);
        $this->load->view('klinik/layout/sidebar');
        $this->load->view('klinik/petugas_rekam_medis/index', $data);
        $this->load->view('klinik/layout/footer');
    }

    public function create()
    {
        $data['title'] = 'Tambah Dokter Pemeriksa';

        $this->load->view('klinik/layout/header', $data);
        $this->load->view('klinik/layout/sidebar');
        $this->load->view('klinik/petugas_rekam_medis/create', $data);
        $this->load->view('klinik/layout/footer');
    }

    public function store()
    {
        $data = [
            'nama_dokter' => $this->input->post('nama_dokter', TRUE),
            'spesialisasi' => $this->input->post('spesialisasi', TRUE),
            'sip' => $this->input->post('sip', TRUE)
        ];

        $this->Petugas_dokter_model->insert($data);
        $this->session->set_flashdata('success', 'Data dokter berhasil ditambahkan.');
        redirect('klinik/petugas_dokter');
    }

    public function edit($id)
    {
        $data['title'] = 'Edit Dokter Pemeriksa';
        $data['dokter'] = $this->Petugas_dokter_model->get_by_id($id);

        if (!$data['dokter']) show_404();

        $this->load->view('klinik/layout/header', $data);
        $this->load->view('klinik/layout/sidebar');
        $this->load->view('klinik/petugas_rekam_medis/edit', $data);
        $this->load->view('klinik/layout/footer');
    }

    public function update($id)
    {
        $data = [
            'nama_dokter' => $this->input->post('nama_dokter', TRUE),
            'spesialisasi' => $this->input->post('spesialisasi', TRUE),
            'sip' => $this->input->post('sip', TRUE)
        ];

        $this->Petugas_dokter_model->update($id, $data);
        $this->session->set_flashdata('success', 'Data dokter berhasil diperbarui.');
        redirect('klinik/petugas_dokter');
    }

    public function delete($id)
    {
        // Hanya user modul Klinik yang boleh mengubah data klinik
        if (current_module() !== 'klinik') {
            deny_cross_module();
        }

        $this->Petugas_dokter_model->delete($id);
        $this->session->set_flashdata('success', 'Data dokter berhasil dihapus.');
        redirect('klinik/petugas_dokter');
    }
}

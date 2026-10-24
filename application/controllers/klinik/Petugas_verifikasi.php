<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Petugas_verifikasi extends MY_KlinikController {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Klinik/Petugas_verifikasi_model');
        $this->load->helper(['url', 'form']);
        $this->load->library('session');
        $this->require_role(['admin']);
    }

    public function index()
    {
        $data['title'] = 'Data Petugas Verifikasi Klinik';
        $data['petugas'] = $this->Petugas_verifikasi_model->get_all();

        $this->load->view('klinik/layout/header', $data);
        $this->load->view('klinik/layout/sidebar');
        $this->load->view('klinik/petugas_verifikasi/index', $data);
        $this->load->view('klinik/layout/footer');
    }

    public function create()
    {
        $data['title'] = 'Tambah Petugas Verifikasi';
        $data['action'] = site_url('klinik/petugas_verifikasi/create_action');

        // Template kosong untuk form
        $data['petugas'] = (object)[
            'id_verifikasi' => '',
            'nama_petugas'  => '',
            'jabatan'       => '',
        ];

        $this->load->view('klinik/layout/header', $data);
        $this->load->view('klinik/layout/sidebar');
        $this->load->view('klinik/petugas_verifikasi/form', $data);
        $this->load->view('klinik/layout/footer');
    }

    public function create_action()
    {
        $data = [
            'nama_petugas' => $this->input->post('nama_petugas'),
            'jabatan'      => $this->input->post('jabatan'),
        ];

        $this->Petugas_verifikasi_model->insert($data);
        $this->session->set_flashdata('success', 'Data petugas verifikasi berhasil ditambahkan.');
        redirect('klinik/petugas_verifikasi');
    }

    public function edit($id)
    {
        $data['title'] = 'Edit Petugas Verifikasi';
        $data['action'] = site_url('klinik/petugas_verifikasi/edit_action');

        $data['petugas'] = $this->Petugas_verifikasi_model->get_by_id($id);
        if (!$data['petugas']) show_404();

        $this->load->view('klinik/layout/header', $data);
        $this->load->view('klinik/layout/sidebar');
        $this->load->view('klinik/petugas_verifikasi/form', $data);
        $this->load->view('klinik/layout/footer');
    }

    public function edit_action()
    {
        $id = $this->input->post('id_verifikasi');
        if (empty($id)) {
            $this->session->set_flashdata('error', 'ID tidak ditemukan.');
            redirect('klinik/petugas_verifikasi');
        }

        $data = [
            'nama_petugas' => $this->input->post('nama_petugas'),
            'jabatan'      => $this->input->post('jabatan'),
        ];

        $this->Petugas_verifikasi_model->update($id, $data);
        $this->session->set_flashdata('success', 'Data petugas verifikasi berhasil diperbarui.');
        redirect('klinik/petugas_verifikasi');
    }

    public function delete($id)
    {
        // Hanya user modul Klinik yang boleh mengubah data klinik
        if (current_module() !== 'klinik') {
            deny_cross_module();
        }

        $this->Petugas_verifikasi_model->delete($id);
        $this->session->set_flashdata('success', 'Data petugas verifikasi berhasil dihapus.');
        redirect('klinik/petugas_verifikasi');
    }
}

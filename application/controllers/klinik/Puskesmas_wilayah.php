<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Puskesmas_wilayah extends MY_KlinikController
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Klinik/Puskesmas_wilayah_model');
        $this->load->helper(['url', 'form']);
        $this->require_role(['admin', 'petugas rm']);
    }

    public function index()
    {
        $data['title'] = 'Manajemen Puskesmas Wilayah';
        $data['wilayah'] = $this->Puskesmas_wilayah_model->get_all();

        $this->load->view('klinik/layout/header', $data);
        $this->load->view('klinik/layout/sidebar');
        $this->load->view('klinik/puskesmas_wilayah/index', $data);
        $this->load->view('klinik/layout/footer');
    }

    public function create()
    {
        $data['title'] = 'Tambah Puskesmas Wilayah';
        $data['action'] = site_url('klinik/puskesmas_wilayah/create_action');
        $data['wilayah'] = (object)[
            'id' => '',
            'nama_puskesmas' => '',
            'kecamatan' => '',
            'kelurahan_list' => ''
        ];

        $this->load->view('klinik/layout/header', $data);
        $this->load->view('klinik/layout/sidebar');
        $this->load->view('klinik/puskesmas_wilayah/form', $data);
        $this->load->view('klinik/layout/footer');
    }

    public function create_action()
    {
        $data = [
            'nama_puskesmas' => $this->input->post('nama_puskesmas'),
            'kecamatan' => $this->input->post('kecamatan'),
            'kelurahan_list' => $this->input->post('kelurahan_list')
        ];

        $this->Puskesmas_wilayah_model->insert($data);
        redirect('klinik/puskesmas_wilayah');
    }

    public function edit($id)
    {
        $data['title'] = 'Edit Puskesmas Wilayah';
        $data['action'] = site_url('klinik/puskesmas_wilayah/edit_action');
        $data['wilayah'] = $this->Puskesmas_wilayah_model->get_by_id($id);

        if (!$data['wilayah']) show_404();

        $this->load->view('klinik/layout/header', $data);
        $this->load->view('klinik/layout/sidebar');
        $this->load->view('klinik/puskesmas_wilayah/form', $data);
        $this->load->view('klinik/layout/footer');
    }

    public function edit_action()
    {
        $id = $this->input->post('id');
        if (empty($id)) {
            $this->session->set_flashdata('error', 'ID tidak ditemukan.');
            redirect('klinik/puskesmas_wilayah');
        }

        $data = [
            'nama_puskesmas' => $this->input->post('nama_puskesmas'),
            'kecamatan' => $this->input->post('kecamatan'),
            'kelurahan_list' => $this->input->post('kelurahan_list')
        ];

        $this->Puskesmas_wilayah_model->update($id, $data);
        $this->session->set_flashdata('success', 'Data berhasil diperbarui.');
        redirect('klinik/puskesmas_wilayah');
    }

    public function delete($id)
    {
        // Hanya user modul Klinik yang boleh mengubah data klinik
        if (current_module() !== 'klinik') {
            deny_cross_module();
        }

        $this->Puskesmas_wilayah_model->delete($id);
        redirect('klinik/puskesmas_wilayah');
    }
}

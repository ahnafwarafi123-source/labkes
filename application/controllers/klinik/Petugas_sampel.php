<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Petugas_sampel extends MY_KlinikController {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Klinik/Petugas_sampel_model');
        $this->load->helper(['url', 'form']);
        $this->require_role(['admin']);
    }

    public function index()
    {
        $data['title'] = 'Data Petugas Sampel Klinik';
        $data['petugas'] = $this->Petugas_sampel_model->get_all();

        $this->load->view('klinik/layout/header', $data);
        $this->load->view('klinik/layout/sidebar');
        $this->load->view('klinik/petugas_sampel/index', $data);
        $this->load->view('klinik/layout/footer');
    }

    public function create()
    {
        $data['title'] = 'Tambah Petugas Sampel';
        $data['action'] = site_url('klinik/petugas_sampel/create_action');
        $data['petugas'] = (object)[
            'id_petugas' => '',
            'nama_petugas' => '',
            'jabatan' => ''
        ];

        $this->load->view('klinik/layout/header', $data);
        $this->load->view('klinik/layout/sidebar');
        $this->load->view('klinik/petugas_sampel/form', $data);
        $this->load->view('klinik/layout/footer');
    }

    public function create_action()
    {
        $data = [
            'nama_petugas' => $this->input->post('nama_petugas'),
            'jabatan' => $this->input->post('jabatan')
        ];

        $this->Petugas_sampel_model->insert($data);
        redirect('klinik/petugas_sampel');
    }

    public function edit($id)
    {
        $data['title'] = 'Edit Petugas Sampel';
        $data['action'] = site_url('klinik/petugas_sampel/edit_action/'.$id);
        $data['petugas'] = $this->Petugas_sampel_model->get_by_id($id);

        if (!$data['petugas']) {
            show_404();
        }

        $this->load->view('klinik/layout/header', $data);
        $this->load->view('klinik/layout/sidebar');
        $this->load->view('klinik/petugas_sampel/form', $data);
        $this->load->view('klinik/layout/footer');
    }

    public function edit_action($id)
    {
        $data = [
            'nama_petugas' => $this->input->post('nama_petugas'),
            'jabatan' => $this->input->post('jabatan')
        ];

        $this->Petugas_sampel_model->update($id, $data);
        redirect('klinik/petugas_sampel');
    }

    public function delete($id)
    {
        // Hanya user modul Klinik yang boleh mengubah data klinik
        if (current_module() !== 'klinik') {
            deny_cross_module();
        }

        $this->Petugas_sampel_model->delete($id);
        redirect('klinik/petugas_sampel');
    }
}

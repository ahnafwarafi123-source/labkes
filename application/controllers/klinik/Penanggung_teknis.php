<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Penanggung_teknis extends MY_KlinikController {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Klinik/Penanggung_teknis_model');
        $this->load->helper(['url', 'form']);
        $this->load->library('session');
        $this->require_role(['admin']);
    }

    public function index()
    {
        $data['title'] = 'Data Penanggung Jawab Teknis';
        $data['petugas'] = $this->Penanggung_teknis_model->get_all();

        $this->load->view('klinik/layout/header', $data);
        $this->load->view('klinik/layout/sidebar');
        $this->load->view('klinik/penanggung_teknis/index', $data);
        $this->load->view('klinik/layout/footer');
    }

    public function create()
    {
        $data['title'] = 'Tambah Penanggung Jawab Teknis';
        $data['action'] = site_url('klinik/penanggung_teknis/create_action');
        $data['petugas'] = (object)[
            'id_petugas' => '',
            'nama_petugas' => '',
            'jabatan' => '',
            'sip' => ''
        ];

        $this->load->view('klinik/layout/header', $data);
        $this->load->view('klinik/layout/sidebar');
        $this->load->view('klinik/penanggung_teknis/form', $data);
        $this->load->view('klinik/layout/footer');
    }

    public function create_action()
    {
        $data = [
            'nama_petugas' => $this->input->post('nama_petugas', TRUE),
            'jabatan' => $this->input->post('jabatan', TRUE),
            'sip' => $this->input->post('sip', TRUE)
        ];

        $this->Penanggung_teknis_model->insert($data);
        $this->session->set_flashdata('success', 'Data penanggung jawab teknis berhasil ditambahkan.');
        redirect('klinik/penanggung_teknis');
    }

    public function edit($id)
    {
        $data['title'] = 'Edit Penanggung Jawab Teknis';
        $data['action'] = site_url('klinik/penanggung_teknis/edit_action');
        $data['petugas'] = $this->Penanggung_teknis_model->get_by_id($id);

        if (!$data['petugas']) show_404();

        $this->load->view('klinik/layout/header', $data);
        $this->load->view('klinik/layout/sidebar');
        $this->load->view('klinik/penanggung_teknis/form', $data);
        $this->load->view('klinik/layout/footer');
    }

    public function edit_action()
    {
        $id = $this->input->post('id_petugas');
        if (empty($id)) {
            $this->session->set_flashdata('error', 'ID penanggung jawab tidak ditemukan.');
            redirect('klinik/penanggung_teknis');
        }

        $data = [
            'nama_petugas' => $this->input->post('nama_petugas', TRUE),
            'jabatan' => $this->input->post('jabatan', TRUE),
            'sip' => $this->input->post('sip', TRUE)
        ];

        $this->Penanggung_teknis_model->update($id, $data);
        $this->session->set_flashdata('success', 'Data penanggung jawab teknis berhasil diperbarui.');
        redirect('klinik/penanggung_teknis');
    }

    public function delete($id)
    {
        // Hanya user modul Klinik yang boleh mengubah data klinik
        if (current_module() !== 'klinik') {
            deny_cross_module();
        }

        $this->Penanggung_teknis_model->delete($id);
        $this->session->set_flashdata('success', 'Data penanggung jawab teknis berhasil dihapus.');
        redirect('klinik/penanggung_teknis');
    }
}

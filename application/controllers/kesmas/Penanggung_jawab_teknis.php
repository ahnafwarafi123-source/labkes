<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Penanggung_jawab_teknis extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('kesmas/Penanggung_jawab_teknis_model', 'pjt_model');
        $this->load->library('form_validation');
        // Hanya admin yang boleh mengelola data penanggung jawab teknis
        $this->require_role(['admin']);
    }

    public function index()
    {
        $data = [
            'title'                       => 'Daftar Penanggung Jawab Teknis',
            'penanggung_jawab_teknis_list' => $this->pjt_model->list_penanggung_jawab_teknis(),
        ];
        $this->render('kesmas/penanggung_jawab_teknis/list', $data);
    }

    public function add()
    {
        $data = ['title' => 'Tambah Penanggung Jawab Teknis'];

        $this->form_validation->set_rules('nama', 'Nama', 'required|trim');
        $this->form_validation->set_rules('nip',  'NIP',  'trim');

        if ($this->form_validation->run() == FALSE) {
            $this->render('kesmas/penanggung_jawab_teknis/add', $data);
        } else {
            $post_data = [
                'nama'      => $this->input->post('nama'),
                'nip'       => $this->input->post('nip'),
                'is_active' => 1,
            ];

            if ($this->pjt_model->add_penanggung_jawab_teknis($post_data)) {
                $this->session->set_flashdata('success', 'Data berhasil ditambahkan.');
                redirect('k-panel/penanggung_jawab_teknis');
            } else {
                $this->session->set_flashdata('error', 'Gagal menambahkan data.');
                $this->render('kesmas/penanggung_jawab_teknis/add', $data);
            }
        }
    }

    public function edit($id)
    {
        $data = [
            'title' => 'Edit Penanggung Jawab Teknis',
            'pjt'   => $this->pjt_model->get_penanggung_jawab_teknis_by_id($id),
        ];

        if (empty($data['pjt'])) {
            show_404();
        }

        $this->form_validation->set_rules('nama', 'Nama', 'required|trim');
        $this->form_validation->set_rules('nip',  'NIP',  'trim');

        if ($this->form_validation->run() == FALSE) {
            $this->render('kesmas/penanggung_jawab_teknis/edit', $data);
        } else {
            $post_data = [
                'nama' => $this->input->post('nama'),
                'nip'  => $this->input->post('nip'),
            ];

            if ($this->pjt_model->update_penanggung_jawab_teknis($id, $post_data)) {
                $this->session->set_flashdata('success', 'Data berhasil diupdate.');
                redirect('k-panel/penanggung_jawab_teknis');
            } else {
                $this->session->set_flashdata('error', 'Gagal mengupdate data.');
                $this->render('kesmas/penanggung_jawab_teknis/edit', $data);
            }
        }
    }

    public function delete($id)
    {
        // Hanya user modul Kesmas yang boleh mengubah data kesmas
        if (current_module() !== 'kesmas') {
            deny_cross_module();
        }

        if ($this->pjt_model->delete_penanggung_jawab_teknis($id)) {
            $this->session->set_flashdata('success', 'Data berhasil dihapus.');
        } else {
            $this->session->set_flashdata('error', 'Gagal menghapus data.');
        }
        redirect('k-panel/penanggung_jawab_teknis');
    }
}

<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Verifikator extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('kesmas/Verifikator_model');
        $this->load->library('form_validation');
        // Hanya admin yang boleh mengelola data verifikator
        $this->require_role(['admin']);
    }

    public function index()
    {
        $data = [
            'title'            => 'Daftar Verifikator',
            'verifikator_list' => $this->Verifikator_model->list_verifikator(),
        ];
        $this->render('kesmas/verifikator/list', $data);
    }

    public function add()
    {
        $this->form_validation->set_rules('nama', 'Nama', 'required');
        $this->form_validation->set_rules('nip', 'NIP', 'required');

        if ($this->form_validation->run() == FALSE) {
            $data = ['title' => 'Tambah Verifikator'];
            $this->render('kesmas/verifikator/add', $data);
        } else {
            $post_data = [
                'nama'    => $this->input->post('nama'),
                'nip'     => $this->input->post('nip'),
            ];

            if ($this->Verifikator_model->add_verifikator($post_data)) {
                $this->session->set_flashdata('success', 'Verifikator berhasil ditambahkan.');
                redirect('k-panel/verifikator');
            } else {
                $this->session->set_flashdata('error', 'Gagal menambahkan verifikator.');
                redirect('k-panel/verifikator/add');
            }
        }
    }

    public function edit($id)
    {
        $verifikator = $this->Verifikator_model->get_verifikator_by_id($id);
        if (!$verifikator) {
            $this->session->set_flashdata('error', 'Verifikator tidak ditemukan.');
            redirect('k-panel/verifikator');
        }

        $this->form_validation->set_rules('nama', 'Nama', 'required');
        $this->form_validation->set_rules('nip', 'NIP', 'required');

        if ($this->form_validation->run() == FALSE) {
            $data = [
                'title'       => 'Edit Verifikator',
                'verifikator' => $verifikator,
            ];
            $this->render('kesmas/verifikator/add', $data);
        } else {
            $update_data = [
                'nama'    => $this->input->post('nama'),
                'nip'     => $this->input->post('nip'),
            ];

            if ($this->Verifikator_model->update_verifikator($id, $update_data)) {
                $this->session->set_flashdata('success', 'Verifikator berhasil diperbarui.');
                redirect('k-panel/verifikator');
            } else {
                $this->session->set_flashdata('error', 'Gagal memperbarui verifikator.');
                redirect('k-panel/verifikator/edit/' . $id);
            }
        }
    }

    public function delete($id)
    {
        // Hanya user modul Kesmas yang boleh mengubah data kesmas
        if (current_module() !== 'kesmas') {
            deny_cross_module();
        }

        if ($this->Verifikator_model->delete_verifikator($id)) {
            $this->session->set_flashdata('success', 'Verifikator berhasil dihapus.');
        } else {
            $this->session->set_flashdata('error', 'Gagal menghapus verifikator.');
        }
        redirect('k-panel/verifikator');
    }
}

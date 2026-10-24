<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Petugas extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('kesmas/User_model');
        $this->load->model('kesmas/Kesmas_model');
        $this->load->library('form_validation');
        // Hanya admin yang boleh mengelola data petugas
        $this->require_role(['admin']);
    }

    public function index()
    {
        $data = [
            'title'   => 'Daftar Petugas',
            'petugas' => $this->Kesmas_model->list_petugas(),
        ];
        $this->render('kesmas/petugas/list', $data);
    }

    public function add()
    {
        $this->form_validation->set_rules('nama', 'Nama', 'required');
        $this->form_validation->set_rules('jabatan', 'Jabatan', 'required');

        if ($this->form_validation->run() == FALSE) {
            $data = ['title' => 'Tambah Petugas'];
            $this->render('kesmas/petugas/form', $data);
        } else {
            $post_data = [
                'nama'    => $this->input->post('nama'),
                'jabatan' => $this->input->post('jabatan'),
            ];

            if ($this->Kesmas_model->add_petugas($post_data)) {
                $this->session->set_flashdata('success', 'Petugas berhasil ditambahkan.');
            } else {
                $this->session->set_flashdata('error', 'Gagal menambahkan petugas.');
            }
            redirect('k-panel/petugas');
        }
    }

    public function edit($id)
    {
        $petugas = $this->Kesmas_model->get_petugas_by_id($id);
        if (!$petugas) {
            $this->session->set_flashdata('error', 'Petugas tidak ditemukan.');
            redirect('k-panel/petugas');
        }

        $this->form_validation->set_rules('nama', 'Nama', 'required');
        $this->form_validation->set_rules('jabatan', 'Jabatan', 'required');

        if ($this->form_validation->run() == FALSE) {
            $data = [
                'title'   => 'Edit Petugas',
                'petugas' => $petugas,
            ];
            $this->render('kesmas/petugas/form', $data);
        } else {
            $update_data = [
                'nama'    => $this->input->post('nama'),
                'jabatan' => $this->input->post('jabatan'),
            ];

            if ($this->Kesmas_model->update_petugas($id, $update_data)) {
                $this->session->set_flashdata('success', 'Petugas berhasil diperbarui.');
                redirect('k-panel/petugas');
            } else {
                $this->session->set_flashdata('error', 'Gagal memperbarui petugas.');
                redirect('k-panel/petugas/edit/' . $id);
            }
        }
    }

    public function delete($id)
    {
        // Hanya user modul Kesmas yang boleh mengubah data kesmas
        if (current_module() !== 'kesmas') {
            deny_cross_module();
        }

        if ($this->Kesmas_model->delete_petugas($id)) {
            $this->session->set_flashdata('success', 'Petugas berhasil dihapus.');
        } else {
            $this->session->set_flashdata('error', 'Gagal menghapus petugas.');
        }
        redirect('k-panel/petugas');
    }
}
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Master Wilayah puskesmas - modul Kesmas
 * Route: /k-panel/Puskesmas_wilayah_kesmas
 */
class Puskesmas_wilayah_kesmas extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->require_role(['admin', 'petugas']);
        $this->load->model('kesmas/Puskesmas_wilayah_model_kesmas');
    }

    public function index()
    {
        $data = [
            'title'   => 'Manajemen Wilayah puskesmas',
            'wilayah' => $this->Puskesmas_wilayah_model_kesmas->get_all(),
        ];
        $this->render('kesmas/puskesmas_wilayah_kesmas/index', $data);
    }

    public function create()
    {
        $data = [
            'title'   => 'Tambah Wilayah puskesmas',
            'action'  => site_url('k-panel/Puskesmas_wilayah_kesmas/simpan'),
            'wilayah' => (object) [
                'id'              => '',
                'nama_puskesmas'      => '',
                'kecamatan'       => '',
                'kelurahan_list' => '',
            ],
        ];
        $this->render('kesmas/puskesmas_wilayah_kesmas/form', $data);
    }

    public function simpan()
    {
        $this->Puskesmas_wilayah_model_kesmas->insert([
            'nama_puskesmas'     => $this->input->post('nama_puskesmas', true),
            'kecamatan'      => $this->input->post('kecamatan', true),
            'kelurahan_list' => $this->input->post('kelurahan_list', true),
        ]);
        $this->session->set_flashdata('success', 'Data wilayah puskesmas berhasil disimpan.');
        redirect('k-panel/Puskesmas_wilayah_kesmas');
    }

    public function edit($id)
    {
        $wilayah = $this->Puskesmas_wilayah_model_kesmas->get_by_id($id);
        if (!$wilayah) show_404();

        $data = [
            'title'   => 'Edit Wilayah puskesmas',
            'action'  => site_url('k-panel/Puskesmas_wilayah_kesmas/ubah/' . (int) $id),
            'wilayah' => $wilayah,
        ];
        $this->render('kesmas/puskesmas_wilayah_kesmas/form', $data);
    }

    public function ubah($id)
    {
        $id = (int) $id;
        if ($id <= 0) show_404();

        $this->Puskesmas_wilayah_model_kesmas->update($id, [
            'nama_puskesmas'     => $this->input->post('nama_puskesmas', true),
            'kecamatan'      => $this->input->post('kecamatan', true),
            'kelurahan_list' => $this->input->post('kelurahan_list', true),
        ]);
        $this->session->set_flashdata('success', 'Data wilayah puskesmas berhasil diperbarui.');
        redirect('k-panel/Puskesmas_wilayah_kesmas');
    }

    public function hapus($id)
    {
        $id = (int) $id;
        if ($id <= 0) show_404();

        $this->Puskesmas_wilayah_model_kesmas->delete($id);
        $this->session->set_flashdata('success', 'Data wilayah puskesmas berhasil dihapus.');
        redirect('k-panel/Puskesmas_wilayah_kesmas');
    }
}

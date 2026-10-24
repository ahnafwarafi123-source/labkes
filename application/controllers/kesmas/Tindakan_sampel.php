<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tindakan_sampel extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('kesmas/Tindakan_sampel_model');
        $this->load->library('form_validation');
    }

    public function index()
    {
        $data = [
            'title'           => 'Tindakan Sampel (Legacy)',
            'tindakan_sampel' => $this->Tindakan_sampel_model->list_tindakan_sampel(),
        ];
        $this->render('kesmas/tindakan_sampel/list', $data);
    }

    public function add()
    {
        $this->session->set_flashdata('error', 'Tabel Tindakan Sampel sudah dihapus. Data dikelola secara statis (VARCHAR).');
        redirect('k-panel/tindakan_sampel');
    }

    public function edit($id)
    {
        $this->session->set_flashdata('error', 'Tabel Tindakan Sampel sudah dihapus. Data dikelola secara statis (VARCHAR).');
        redirect('k-panel/tindakan_sampel');
    }

    public function delete($id)
    {
        $this->session->set_flashdata('error', 'Tabel Tindakan Sampel sudah dihapus. Data dikelola secara statis (VARCHAR).');
        redirect('k-panel/tindakan_sampel');
    }
}

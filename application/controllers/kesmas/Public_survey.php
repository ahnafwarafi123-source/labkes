<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Public_survey Controller
 *
 * @property CI_Loader $load
 * @property CI_Input $input
 * @property CI_Session $session
 * @property CI_Form_validation $form_validation
 * @property Survei_kepuasan_model $Survei_kepuasan_model
 */
class Public_survey extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model('kesmas/Survei_kepuasan_model');
    }

    /**
     * Halaman form survei (untuk pelanggan)
     */
    public function form()
    {
        $data['title'] = 'Survei Kepuasan Masyarakat';
        $data['id_permintaan'] = $this->input->get('id_permintaan', true);
        
        $this->load->view('kesmas/public/header', $data);
        $this->load->view('kesmas/survei/form', $data);
        $this->load->view('kesmas/public/footer');
    }

    /**
     * Halaman Terima Kasih setelah submit survei (Public)
     */
    public function thankyou()
    {
        $data['title'] = 'Terima Kasih!';
        $this->load->view('kesmas/public/header', $data);
        $this->load->view('kesmas/survei/thankyou');
        $this->load->view('kesmas/public/footer');
    }

    /**
     * Store survei baru (public access)
     */
    public function store()
    {
        $post = $this->input->post();

        $validation_rules = array(
            array('field' => 'usia', 'label' => 'Usia', 'rules' => 'required|numeric'),
            array('field' => 'jenis_kelamin', 'label' => 'Jenis Kelamin', 'rules' => 'required'),
            array('field' => 'pendidikan', 'label' => 'Pendidikan', 'rules' => 'required'),
            array('field' => 'pekerjaan', 'label' => 'Pekerjaan', 'rules' => 'required'),
            array('field' => 'jenis_layanan', 'label' => 'Jenis Layanan yang diterima', 'rules' => 'required'),
            array('field' => 'jam_survei', 'label' => 'Jam Survei', 'rules' => 'required'),
        );

        for ($i = 1; $i <= 9; $i++) {
            $validation_rules[] = array('field' => 'q'.$i, 'label' => 'Unsur Pelayanan '.$i, 'rules' => 'required|numeric|greater_than_equal_to[1]|less_than_equal_to[4]');
        }

        $this->form_validation->set_rules($validation_rules);

        if (!$this->form_validation->run()) {
            echo json_encode(['status' => 'error', 'message' => validation_errors()]);
            return;
        }

        $insert_id = $this->Survei_kepuasan_model->create($post);

        if ($insert_id) {
            echo json_encode(['status' => 'success', 'message' => 'Terima kasih atas umpan balik Anda!', 'id' => $insert_id]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Gagal menyimpan survei']);
        }
    }
}
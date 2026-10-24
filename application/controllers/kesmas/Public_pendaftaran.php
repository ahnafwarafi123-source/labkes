<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Public_pendaftaran Controller
 *
 * @property CI_Loader $load
 * @property CI_Input $input
 * @property CI_Session $session
 * @property CI_Form_validation $form_validation
 * @property CI_Security $security
 * @property Kesmas_model $Kesmas_model
 * @property Tindakan_sampel_model $Tindakan_sampel_model
 * @property Survei_kepuasan_model $Survei_kepuasan_model
 */
class Public_pendaftaran extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model('kesmas/Kesmas_model');
        $this->load->model('kesmas/Tindakan_sampel_model');
        $this->load->helper('security'); // Load security helper for CSRF
    }

    public function index()
    {
        $this->load->model('kesmas/Survei_kepuasan_model');
        $stats = $this->Survei_kepuasan_model->get_rating_stats();

        $data = array(
            'title' => 'Layanan Pendaftaran Sampel',
            'stats' => $stats,
        );
        $this->load->view('kesmas/public/header', $data);
        $this->load->view('kesmas/public/landing', $data);
        $this->load->view('kesmas/public/footer', $data);
    }

    public function form()
    {
        $data = array(
            'title' => 'Formulir Pendaftaran Sampel',
            'mode' => 'create',
            'no_registrasi' => $this->Kesmas_model->gen_no_registrasi(),
            'permintaan' => null,
            'selected_ids' => array(),
            'petugas' => $this->Kesmas_model->list_petugas(),
            'tindakan_sampel' => $this->Tindakan_sampel_model->list_tindakan_sampel(),
            'action' => site_url('kesmas/pendaftaran_publik/store'),
        );

        // default tampilkan master untuk 4 kategori di tab
        $data['master_air_minum'] = $this->Kesmas_model->get_master_grouped('air_minum');
        $data['master_air_bersih'] = $this->Kesmas_model->get_master_grouped('air_bersih');
        $data['master_makanan'] = $this->Kesmas_model->get_master_grouped('makanan');
        $data['master_lingkungan'] = $this->Kesmas_model->get_master_grouped('lingkungan');


        $this->load->view('kesmas/public/header', $data);
        $this->load->view('kesmas/public/permintaan_form', $data);
        $this->load->view('kesmas/public/footer', $data);
    }

    public function store()
    {
        try {
            $this->form_validation->set_message('required', '{field} wajib diisi.');
            $this->form_validation->set_rules('nama_sampel', 'Nama Sampel', 'required|trim');
            $this->form_validation->set_rules('id_kategori_sampel', 'Kategori Sampel', 'required');
            $this->form_validation->set_rules('jenis_sampel', 'Jenis Sampel', 'required');
            $this->form_validation->set_rules('disclaimer', 'Syarat dan Ketentuan', 'required', array('required' => 'Anda harus menyetujui Syarat dan Ketentuan untuk melanjutkan.'));
            
            if (!$this->form_validation->run()) {
                $this->session->set_flashdata('error', validation_errors_text());
                redirect('kesmas/pendaftaran_publik/form');
            }

            $header = $this->_collect_header(true);
            $master_ids = $this->input->post('pemeriksaan', true);
            if (!is_array($master_ids)) $master_ids = array();

            // null user_id since it's public
            $permintaan_id = $this->Kesmas_model->save_permintaan($header, $master_ids, null);
            if (!$permintaan_id) {
                $this->session->set_flashdata('error', 'Gagal menyimpan pendaftaran. Silakan coba lagi.');
                redirect('kesmas/pendaftaran_publik/form');
            }

            $this->session->set_flashdata('success', 'Berhasil melakukan pendaftaran sampel. Menunggu petugas untuk memproses.');
            redirect('kesmas/pendaftaran_publik/success/'.$permintaan_id);
        } catch (Exception $e) {
            log_message('error', 'Error in store: ' . $e->getMessage());
            $this->session->set_flashdata('error', 'Terjadi kesalahan sistem. Silakan coba lagi.');
            redirect('kesmas/pendaftaran_publik/form');
        }
    }

    public function success($id = null)
    {
        if (!$id) show_404();

        $permintaan = $this->Kesmas_model->get_permintaan($id);
        if (!$permintaan) show_404();

        // Cek ketersediaan survei
        $this->load->model('kesmas/Survei_kepuasan_model');
        $survey_exists = $this->Survei_kepuasan_model->check_exists($permintaan['id_permintaan']);

        $data = array(
            'title' => 'Pendaftaran Berhasil',
            'permintaan' => $permintaan,
            'survey_exists' => $survey_exists,
            'survey_link' => site_url('kesmas/survei/form?permintaan_id=' . $permintaan['id_permintaan'])
        );
        $this->load->view('kesmas/public/header', $data);
        $this->load->view('kesmas/public/success', $data);
        $this->load->view('kesmas/public/footer', $data);
    }

    public function print_bukti($id = null)
    {
        if (!$id) show_404();

        $permintaan = $this->Kesmas_model->get_permintaan($id);
        if (!$permintaan) show_404();

        $items = $this->Kesmas_model->get_items_with_result($permintaan['id_permintaan']);

        $data = array(
            'title' => 'Bukti Pendaftaran - ' . ($permintaan['no_registrasi'] ?? ''),
            'pendaftaran' => (object)$permintaan,
            'pemeriksaan' => json_decode(json_encode($items)),
        );

        $this->load->view('kesmas/public/print_bukti', $data);
    }

    private function _collect_header($with_no_reg)
    {
        $petugas_pengambil_id = $this->input->post('id_petugas', true);

        $jenis_sampel = $this->input->post('jenis_sampel', true);
        if ($jenis_sampel === '__lainnya__') {
            $jenis_sampel = $this->input->post('jenis_sampel_manual', true);
        }

        $header = array(
            'is_diterima' => 0,
            'nama_sampel' => $this->input->post('nama_sampel', true),
            'id_kategori_sampel' => $this->input->post('id_kategori_sampel', true),
            'jenis_sampel' => $jenis_sampel,
            'volume_ml' => $this->input->post('volume_ml', true) ?: null,
            'tgl_pengambilan' => $this->input->post('tgl_pengambilan', true) ?: null,
            'jam_pengambilan' => $this->input->post('jam_pengambilan', true) ?: null,
            'lokasi_pengambilan' => $this->input->post('lokasi_pengambilan', true),
            'id_petugas' => !empty($petugas_pengambil_id) ? (int)$petugas_pengambil_id : null,
            'tindakan_sampel' => $this->input->post('tindakan_sampel', true),
            'info_tambahan' => $this->input->post('info_tambahan', true),

            'nik' => $this->input->post('nik', true),
            'nama' => $this->input->post('nama', true),
            'alamat' => $this->input->post('alamat', true),
            'telp' => $this->input->post('telp', true),
            'instansi' => $this->input->post('instansi', true),
            'tgl_permintaan' => $this->input->post('tgl_permintaan', true) ?: null,
            'ttd' => $this->input->post('ttd', true),

            'jumlah_biaya' => $this->input->post('jumlah_biaya', true) ?: 0,
            'cara_bayar' => $this->input->post('cara_bayar', true),
            'keterangan_bayar' => $this->input->post('keterangan_bayar', true),
            
            'catatan' => $this->input->post('catatan', true),
        );

        if ($with_no_reg) {
            $no_reg_input = $this->input->post('no_registrasi', true);
            $header['no_registrasi'] = empty($no_reg_input) ? null : $no_reg_input;
        }

        return $header;
    }

    public function check_nik()
    {
        $nik = $this->input->post('nik', true);
        if (empty($nik)) {
            return $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(['status' => 'error', 'message' => 'NIK kosong']));
        }

        $pelanggan = $this->Kesmas_model->get_pelanggan_by_nik($nik);
        
        if ($pelanggan) {
            return $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'status' => 'success',
                    'data' => [
                        'nama' => $pelanggan['nama'],
                        'alamat' => $pelanggan['alamat'],
                        'telp' => $pelanggan['telp'],
                        'instansi' => $pelanggan['instansi']
                    ]
                ]));
        } else {
            return $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(['status' => 'not_found']));
        }
    }
}

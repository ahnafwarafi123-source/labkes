<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Permintaan_sample extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->require_role(['admin', 'petugas']);
        $this->load->model('kesmas/Kesmas_model');
    }

    public function index()
    {
        $filters = array(
            'q' => $this->input->get('q', true),
            'dari' => $this->input->get('dari', true),
            'sampai' => $this->input->get('sampai', true),
            'is_diterima' => 0,
            'exclude_ditolak' => true,  // Exclude rejected samples (is_diterima = 2)
        );

        $data = array(
            'title' => 'Terima Sample',
            'rows' => $this->Kesmas_model->list_permintaan($filters),
            'filters' => $filters,
        );

        $this->render('kesmas/permintaan/list', $data);
    }

    public function verifikasi($id)
    {
        try {
            $id = (int)$id;
            $permintaan = $this->Kesmas_model->get_permintaan($id);
            if (!$permintaan) show_404();

            // Reject if sample has already been rejected (is_diterima = 2)
            if ($permintaan['status'] === 'Ditolak') {
                $this->session->set_flashdata('error', 'Sample ini sudah ditolak dan tidak dapat diproses lebih lanjut.');
                redirect('k-panel/permintaan_sample');
                return;
            }

            if ($this->input->method() === 'post') {
                $no_registrasi = $this->input->post('no_registrasi', true);

                // Update no_registrasi
                if (!empty($no_registrasi)) {
                    // Pengecekan duplikat sebelum update untuk menghindari database error (1062)
                    $existing = $this->db->get_where('kesmas_penerimaan_sampel', ['no_registrasi' => $no_registrasi, 'id_permintaan !=' => $id])->row();
                    if ($existing) {
                        $this->session->set_flashdata('error', 'Gagal menyimpan. Nomor Registrasi "' . htmlspecialchars($no_registrasi) . '" sudah digunakan oleh sampel lain.');
                        redirect('k-panel/permintaan_sample/verifikasi/'.$id);
                        return;
                    }

                }

                // Handle pemeriksaan update
                $pemeriksaan_ids = $this->input->post('pemeriksaan', true);
                if (!is_array($pemeriksaan_ids) || empty($pemeriksaan_ids)) {
                    $this->session->set_flashdata('error', 'Minimal satu jenis pemeriksaan harus dipilih.');
                    redirect('k-panel/permintaan_sample/verifikasi/'.$id);
                    return;
                }
                
                // Update checked parameters
                $this->Kesmas_model->update_pemeriksaan_items($id, $pemeriksaan_ids);

                $ok = $this->Kesmas_model->terima_sampel($id, $this->session->userdata('user_id'), $no_registrasi);
                if ($ok) {
                    log_activity('verifikasi', 'permintaan_sample', [
                        'record_id' => $id,
                        'description' => "Sampel diverifikasi dan diterima dengan No Registrasi: {$no_registrasi}"
                    ]);
                    $this->session->set_flashdata('success', 'Sample berhasil diverifikasi dan diterima.');
                } else {
                    $this->session->set_flashdata('error', 'Gagal menerima sample.');
                }
                redirect('k-panel/permintaan_sample');
            }

            $items = $this->Kesmas_model->get_permintaan_items($id);
            $selected = array_flip($items);

            $data = array(
                'title' => 'Verifikasi Sample',
                'permintaan' => $permintaan,
                'selected' => $selected,
                'master_air_minum' => $this->Kesmas_model->get_master_grouped('air_minum'),
                'master_air_bersih' => $this->Kesmas_model->get_master_grouped('air_bersih'),
                'master_makanan' => $this->Kesmas_model->get_master_grouped('makanan'),
                'master_lingkungan' => $this->Kesmas_model->get_master_grouped('lingkungan'),
            );
            $this->render('kesmas/sample/verifikasi_form', $data);
        } catch (Exception $e) {
            log_message('error', 'Error in verifikasi: ' . $e->getMessage());
            $this->session->set_flashdata('error', 'Terjadi kesalahan sistem. Silakan coba lagi.');
            redirect('k-panel/permintaan_sample');
        }
    }

    public function tolak($id)
    {
        // Hanya user modul Kesmas yang boleh mengubah data kesmas
        if (current_module() !== 'kesmas') {
            deny_cross_module();
        }

        $id = (int)$id;
        $permintaan = $this->Kesmas_model->get_permintaan($id);
        if (!$permintaan) show_404();

        $ok = $this->Kesmas_model->tolak_sampel($id, $this->session->userdata('user_id'));
        if ($ok) {
            log_activity('tolak', 'permintaan_sample', [
                'record_id' => $id,
                'description' => "Sampel (ID Permintaan: {$id}) ditolak dan diarsipkan."
            ]);
            $this->session->set_flashdata('success', 'Sample ditolak dan dihapus dari database.');
        } else {
            $this->session->set_flashdata('error', 'Gagal menolak sample.');
        }
        redirect('k-panel/permintaan_sample');

    }
}

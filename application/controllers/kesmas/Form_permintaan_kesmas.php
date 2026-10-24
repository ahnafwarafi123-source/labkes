<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Form_permintaan_kesmas extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->require_role(['admin', 'petugas']);
        $this->load->model('kesmas/Kesmas_model');
        $this->load->model('kesmas/Tindakan_sampel_model');
        $this->load->model('kesmas/Verifikator_model');
        $this->load->model('kesmas/Penanggung_jawab_teknis_model');
        $this->load->model('kesmas/Puskesmas_wilayah_model_kesmas');
    }

    public function index()
    {
        $filters = array(
            'q' => $this->input->get('q', true),
            'dari' => $this->input->get('dari', true),
            'sampai' => $this->input->get('sampai', true),
            'is_diterima' => 1,
            'exclude_ditolak' => true,
        );

        

        $data = array(
            'title' => 'Pendaftaran Kesmas',
            'rows' => $this->Kesmas_model->list_permintaan($filters),
            'filters' => $filters,
        );
        $this->render('kesmas/permintaan/list', $data);
    }

    public function create()
    {
        // Initialize empty permintaan array with default values for form fields
        $empty_permintaan = array(
            'id' => '',
            'no_registrasi' => '',
            'nama_sampel' => '',
            'id_kategori_sampel' => '',
            'jenis_sampel' => '',
            'volume_ml' => '',
            'tgl_pengambilan' => '',
            'jam_pengambilan' => '',
            'lokasi_pengambilan' => '',
            'id_petugas' => '',
            'info_tambahan' => '',
            'tindakan_sampel' => '',
            'nik'     => '',
            'nama'    => '',
            'alamat'  => '',
            'telp'    => '',
            'instansi'=> '',
            'tgl_permintaan' => '',
            'ttd'     => '',
            'jumlah_biaya' => 0,
            'cara_bayar' => '',
            'keterangan_bayar' => '',
            'catatan' => '',
        );

        $data = array(
            'title' => 'Tambah Permintaan',
            'mode' => 'create',
            'no_registrasi' => '',
            'permintaan' => $empty_permintaan,
            'selected_ids' => array(),
            'petugas' => $this->Kesmas_model->list_petugas(),
            'tindakan_sampel' => $this->Tindakan_sampel_model->list_tindakan_sampel(),
            'data_wilayah' => $this->Puskesmas_wilayah_model_kesmas->list_wilayah(),
        );

        $data['master_air_minum'] = $this->Kesmas_model->get_master_grouped('air_minum');
        $data['master_air_bersih'] = $this->Kesmas_model->get_master_grouped('air_bersih');
        $data['master_makanan'] = $this->Kesmas_model->get_master_grouped('makanan');
        $data['master_lingkungan'] = $this->Kesmas_model->get_master_grouped('lingkungan');
        $data['jenis_sampel_master'] = $this->db->select('kategori as nama')->distinct()->where('is_active', 1)->order_by('kategori', 'ASC')->get('kesmas_master_pemeriksaan')->result_array();

        $this->render('kesmas/permintaan/form', $data);
    }

    public function store()
    {
        try {
            $this->form_validation->set_message('required', '{field} wajib diisi.');
            $this->form_validation->set_rules('no_registrasi', 'Nomor Registrasi', 'trim');
            $this->form_validation->set_rules('nama_sampel', 'Nama Sampel', 'required|trim');
            $this->form_validation->set_rules('id_kategori_sampel', 'Kategori Sampel', 'required');
            $this->form_validation->set_rules('jenis_sampel', 'Jenis Sampel', 'required');
            $this->form_validation->set_rules('tindakan_sampel', 'Tindakan Sampel', 'required');
            if (!$this->form_validation->run()) {
                $this->session->set_flashdata('error', validation_errors_text());
                redirect('k-panel/form_permintaan_kesmas/create');
            }

            $header = $this->_collect_header(true);

            // Validasi duplikat no registrasi
            if (!empty($header['no_registrasi']) && $this->Kesmas_model->check_duplicate_no_registrasi($header['no_registrasi'])) {
                $this->session->set_flashdata('error', 'Gagal simpan! No Registrasi "' . $header['no_registrasi'] . '" sudah digunakan. Silakan gunakan nomor lain.');
                redirect('k-panel/form_permintaan_kesmas/create');
                return;
            }

            $master_ids = $this->input->post('pemeriksaan', true);
            if (!is_array($master_ids) || empty($master_ids)) {
                $this->session->set_flashdata('error', 'Minimal satu jenis pemeriksaan harus dipilih.');
                redirect('k-panel/form_permintaan_kesmas/create');
                return;
            }

            log_message('debug', 'Header: ' . json_encode($header));
            log_message('debug', 'Master IDs: ' . json_encode($master_ids));
            log_message('debug', 'User ID: ' . $this->session->userdata('user_id'));

            $permintaan_id = $this->Kesmas_model->save_permintaan($header, $master_ids, $this->session->userdata('user_id'));
            if (!$permintaan_id) {
                log_message('error', 'Failed to save permintaan. DB Error: ' . $this->db->error()['message']);
                $this->session->set_flashdata('error', 'Gagal simpan data. Pastikan isian valid.');
                redirect('k-panel/form_permintaan_kesmas/create');
                return;
            }

            log_activity('tambah', 'form_permintaan', [
                'record_id' => $permintaan_id,
                'description' => "Menambahkan permintaan sampel baru (ID: {$permintaan_id})"
            ]);
            $this->session->set_flashdata('success', 'Berhasil simpan.');
            // After creating a new permintaan, go directly to kaji ulang for review
            redirect('k-panel/form_permintaan_kesmas/kaji_ulang/'.$permintaan_id);
        } catch (Exception $e) {
            log_message('error', 'Error in store: ' . $e->getMessage());
            $this->session->set_flashdata('error', 'Terjadi kesalahan sistem. Silakan coba lagi.');
            redirect('k-panel/form_permintaan_kesmas/create');
        }
    }

    public function edit($id)
    {
        $id = (int)$id;
        $permintaan = $this->Kesmas_model->get_permintaan($id);
        if (!$permintaan) show_404();

        $data = array(
            'title' => 'Edit Permintaan',
            'mode' => 'edit',
            'is_accept' => $this->input->get('accept') ? true : false,
            'no_registrasi' => $permintaan['no_registrasi'],
            'permintaan' => $permintaan,
            'selected_ids' => $this->Kesmas_model->get_selected_master_ids($id),
            'petugas' => $this->Kesmas_model->list_petugas(),
            'tindakan_sampel' => $this->Tindakan_sampel_model->list_tindakan_sampel(),
            'data_wilayah' => $this->Puskesmas_wilayah_model_kesmas->list_wilayah(),
        );

        $data['master_air_minum'] = $this->Kesmas_model->get_master_grouped('air_minum');
        $data['master_air_bersih'] = $this->Kesmas_model->get_master_grouped('air_bersih');
        $data['master_makanan'] = $this->Kesmas_model->get_master_grouped('makanan');
        $data['master_lingkungan'] = $this->Kesmas_model->get_master_grouped('lingkungan');
        $data['jenis_sampel_master'] = $this->db->select('kategori as nama')->distinct()->where('is_active', 1)->order_by('kategori', 'ASC')->get('kesmas_master_pemeriksaan')->result_array();

        $this->render('kesmas/permintaan/form', $data);
    }

    public function update($id)
    {
        $id = (int)$id;
        $permintaan = $this->Kesmas_model->get_permintaan($id);
        if (!$permintaan) show_404();

        $this->form_validation->set_message('required', '{field} wajib diisi.');
        $this->form_validation->set_rules('no_registrasi', 'Nomor Registrasi', 'trim');
        $this->form_validation->set_rules('nama_sampel', 'Nama Sampel', 'required|trim');
        $this->form_validation->set_rules('id_kategori_sampel', 'Kategori Sampel', 'required');
        $this->form_validation->set_rules('jenis_sampel', 'Jenis Sampel', 'required');
        $this->form_validation->set_rules('tindakan_sampel', 'Tindakan Sampel', 'required');
        if (!$this->form_validation->run()) {
            $this->session->set_flashdata('error', validation_errors_text());
            redirect('k-panel/form_permintaan_kesmas/edit/'.$id);
        }

        $header = $this->_collect_header(true);

        // Validasi duplikat no registrasi
        if (!empty($header['no_registrasi']) && $this->Kesmas_model->check_duplicate_no_registrasi($header['no_registrasi'], $id)) {
            $this->session->set_flashdata('error', 'Gagal update! No Registrasi "' . $header['no_registrasi'] . '" sudah digunakan. Silakan gunakan nomor lain.');
            redirect('k-panel/form_permintaan_kesmas/edit/'.$id);
            return;
        }

        $master_ids = $this->input->post('pemeriksaan', true);
        if (!is_array($master_ids) || empty($master_ids)) {
            $this->session->set_flashdata('error', 'Minimal satu jenis pemeriksaan harus dipilih.');
            redirect('k-panel/form_permintaan_kesmas/edit/'.$id);
            return;
        }

        if ($this->input->post('accept')) {
            $this->Kesmas_model->terima_sampel($id, $this->session->userdata('user_id'), $header['no_registrasi']);
        }

        $ok = $this->Kesmas_model->update_permintaan($id, $header, $master_ids, $this->session->userdata('user_id'));
        if (!$ok) {
            $err_msg = $this->Kesmas_model->last_error_message;
            $this->session->set_flashdata('error', 'Gagal update data. Detail: ' . ($err_msg ?? 'Unknown DB error'));
            redirect('k-panel/form_permintaan_kesmas/edit/'.$id);
        }

        // Pembayaran module removed: no recalculation performed here

        log_activity('ubah', 'form_permintaan', [
            'record_id' => $id,
            'description' => "Mengubah data permintaan sampel (ID: {$id})"
        ]);
        $this->session->set_flashdata('success', 'Berhasil update.');
        redirect('k-panel/form_permintaan_kesmas/detail/'.$id);
    }

    public function detail($id)
    {
        $id = (int)$id;
        $permintaan = $this->Kesmas_model->get_permintaan($id);
        if (!$permintaan) show_404();

        // Resolve petugas names from IDs
        if (!empty($permintaan['id_petugas'])) {
            $permintaan['petugas_pengambil'] = $this->Kesmas_model->get_petugas_name_by_id($permintaan['id_petugas']) ?: '-';
        } else if (empty($permintaan['petugas_pengambil'])) {
            $permintaan['petugas_pengambil'] = '-';
        }
        
        if (!empty($permintaan['id_petugas_2'])) {
            $permintaan['petugas_pengambil_2_name'] = $this->Kesmas_model->get_petugas_name_by_id($permintaan['id_petugas_2']) ?: '-';
        } else {
            $permintaan['petugas_pengambil_2_name'] = '-';
        }

        // Resolve petugas kaji ulang names
        if (!empty($permintaan['petugas_pendaftaran_id'])) {
            $u = $this->db->get_where('user_kesmas', ['id' => $permintaan['petugas_pendaftaran_id']])->row_array();
            $permintaan['petugas_pendaftaran_name'] = $u['nama'] ?? '-';
        } else {
            $permintaan['petugas_pendaftaran_name'] = '-';
        }

        if (!empty($permintaan['petugas_pengambil_ttd_id'])) {
            $permintaan['petugas_pengambil_ttd_name'] = $this->Kesmas_model->get_petugas_name_by_id($permintaan['petugas_pengambil_ttd_id']) ?: '-';
        } else {
            $permintaan['petugas_pengambil_ttd_name'] = '-';
        }

        if (!empty($permintaan['petugas_verifikasi_id'])) {
            $permintaan['petugas_verifikasi_name'] = $this->Verifikator_model->get_verifikator_name_by_id($permintaan['petugas_verifikasi_id']) ?: '-';
        } else {
            $permintaan['petugas_verifikasi_name'] = '-';
        }

        if (!empty($permintaan['petugas_validasi_id'])) {
            $pj = $this->Penanggung_jawab_teknis_model->get_penanggung_jawab_teknis_by_id_simple($permintaan['petugas_validasi_id']);
            $permintaan['petugas_validasi_name'] = $pj && !empty($pj->nama) ? $pj->nama : '-';
        } else {
            $permintaan['petugas_validasi_name'] = '-';
        }

        $permintaan['alasan'] = json_decode($permintaan['alasan'] ?? '[]', true);

        $items = $this->Kesmas_model->get_items_with_result($id);

        $data = array(
            'title' => 'Detail Pendaftaran',
            'pendaftaran' => (object)$permintaan,
            'pemeriksaan' => json_decode(json_encode($items)),
        );
        $this->render('kesmas/permintaan/detail', $data);
    }

    public function print($id)
    {
        $id = (int)$id;
        $permintaan = $this->Kesmas_model->get_permintaan($id);
        if (!$permintaan) {
            show_404();
        }

        // Resolve petugas names from IDs
        if (!empty($permintaan['id_petugas'])) {
            $permintaan['petugas_pengambil'] = $this->Kesmas_model->get_petugas_name_by_id($permintaan['id_petugas']) ?: '-';
        } else if (empty($permintaan['petugas_pengambil'])) {
            $permintaan['petugas_pengambil'] = '-';
        }
        if (!empty($permintaan['id_petugas_2'])) {
            $nama_2 = $this->Kesmas_model->get_petugas_name_by_id($permintaan['id_petugas_2']);
            if ($nama_2) {
                if ($permintaan['petugas_pengambil'] === '-') {
                    $permintaan['petugas_pengambil'] = $nama_2;
                } else {
                    $permintaan['petugas_pengambil'] .= ' , ' . $nama_2;
                }
            }
        }

        // Resolve petugas kaji ulang names
        if (!empty($permintaan['petugas_pendaftaran_id'])) {
            $u = $this->db->get_where('user_kesmas', ['id' => $permintaan['petugas_pendaftaran_id']])->row_array();
            $permintaan['petugas_pendaftaran_name'] = $u['nama'] ?? '-';
        } else {
            // Gunakan nama user yang sedang login sebagai fallback
            $permintaan['petugas_pendaftaran_name'] = $this->session->userdata('nama') ?: '-';
        }

        if (!empty($permintaan['petugas_pengambil_ttd_id'])) {
            $permintaan['petugas_pengambil_ttd_name'] = $this->Kesmas_model->get_petugas_name_by_id($permintaan['petugas_pengambil_ttd_id']) ?: '-';
        } else {
            $permintaan['petugas_pengambil_ttd_name'] = '-';
        }

        if (!empty($permintaan['petugas_verifikasi_id'])) {
            $permintaan['petugas_verifikasi_name'] = $this->Verifikator_model->get_verifikator_name_by_id($permintaan['petugas_verifikasi_id']) ?: '-';
        } else {
            $permintaan['petugas_verifikasi_name'] = '-';
        }

        if (!empty($permintaan['petugas_validasi_id'])) {
            $pj = $this->Penanggung_jawab_teknis_model->get_penanggung_jawab_teknis_by_id_simple($permintaan['petugas_validasi_id']);
            $permintaan['petugas_validasi_name'] = $pj && !empty($pj->nama) ? $pj->nama : '-';
        } else {
            $permintaan['petugas_validasi_name'] = '-';
        }

        $permintaan['alasan'] = json_decode($permintaan['alasan'] ?? '[]', true);

        $items = $this->Kesmas_model->get_items_with_result($id);

        $data = array(
            'title' => 'Cetak Pendaftaran',
            'pendaftaran' => (object)$permintaan,
            'pemeriksaan' => json_decode(json_encode($items)),
            'master_air_minum' => $this->Kesmas_model->get_master_grouped('air_minum'),
            'master_air_bersih' => $this->Kesmas_model->get_master_grouped('air_bersih'),
            'master_makanan' => $this->Kesmas_model->get_master_grouped('makanan'),
            'master_lingkungan' => $this->Kesmas_model->get_master_grouped('lingkungan'),
        );

        $this->load->view('kesmas/permintaan/print', $data);
    }

    /* ============= INPUT HASIL ============= */

    public function uji_index()
    {
        $filters = array(
            'q' => $this->input->get('q', true),
            'dari' => $this->input->get('dari', true),
            'sampai' => $this->input->get('sampai', true),
            'is_diterima' => 1,
        );

        // Remove the default 'only_layak' filter so 'Tidak Layak' can also be inputted
        // $show_all = $this->input->get('show_all', true);
        // if (empty($show_all) || $show_all !== '1') {
        //     $filters['only_layak'] = true;
        // }

        $data = array(
            'title' => 'Input Hasil Uji',
            'rows' => $this->Kesmas_model->list_permintaan($filters),
            'filters' => $filters,
        );
        $this->render('kesmas/uji/list', $data);
    }

    public function input_hasil($permintaan_id)
    {
        $permintaan_id = (int)$permintaan_id;
        $permintaan = $this->Kesmas_model->get_permintaan($permintaan_id);
        if (!$permintaan) show_404();

        // Prepare $kaji_ulang data
        $kaji_ulang = [
            'status_kelayakan' => $permintaan['status_kelayakan'] ?? '',
            'alasan' => json_decode($permintaan['alasan'] ?? '[]', true),
            'jumlah_biaya' => $permintaan['jumlah_biaya'] ?? '',
            'cara_bayar' => $permintaan['cara_bayar'] ?? '',
            'keterangan_bayar' => $permintaan['keterangan_bayar'] ?? '',
            
            'petugas_pendaftaran_id' => $permintaan['petugas_pendaftaran_id'] ?? '',
            'petugas_pengambil_ttd_id' => $permintaan['petugas_pengambil_ttd_id'] ?? '',
            'petugas_verifikasi_id' => $permintaan['petugas_verifikasi_id'] ?? '',
            'petugas_validasi_id' => $permintaan['petugas_validasi_id'] ?? '',
        ];

        // Prepare $kartu_kendali data
        // For now, these are direct fields in $permintaan, or will be added there
        $kartu_kendali = [
            'pengambilan_sampel_tgl_jam' => $permintaan['pengambilan_sampel_tgl_jam'] ?? '',
            'pengambilan_sampel_paraf' => $permintaan['pengambilan_sampel_paraf'] ?? ($this->session->userdata('user_name') ?? ''),
            'sampel_diterima_lab_tgl_jam' => $permintaan['sampel_diterima_lab_tgl_jam'] ?? '',
            'sampel_diterima_lab_paraf' => $permintaan['sampel_diterima_lab_paraf'] ?? ($this->session->userdata('user_name') ?? ''),
            'pengerjaan_sampel_tgl_jam' => $permintaan['pengerjaan_sampel_tgl_jam'] ?? '',
            'pengerjaan_sampel_paraf' => $permintaan['pengerjaan_sampel_paraf'] ?? ($this->session->userdata('user_name') ?? ''),
            'input_hasil_pemeriksaan_tgl_jam' => $permintaan['input_hasil_pemeriksaan_tgl_jam'] ?? '',
            'input_hasil_pemeriksaan_paraf' => $permintaan['input_hasil_pemeriksaan_paraf'] ?? ($this->session->userdata('user_name') ?? ''),
            'cetak_lembar_hasil_uji_tgl_jam' => $permintaan['cetak_lembar_hasil_uji_tgl_jam'] ?? '',
            'cetak_lembar_hasil_uji_paraf' => $permintaan['cetak_lembar_hasil_uji_paraf'] ?? ($this->session->userdata('user_name') ?? ''),
        ];

        $data = array(
            'title' => 'Input Hasil',
            'permintaan' => $permintaan,
            'items' => $this->Kesmas_model->get_items_with_result($permintaan_id),
            'petugas' => $this->Kesmas_model->list_petugas(),
            'kaji_ulang' => $kaji_ulang, // Pass kaji_ulang data
            'kartu_kendali' => $kartu_kendali, // Pass kartu_kendali data
            'current_user_name' => $this->session->userdata('user_name') ?? '', // For potential JS auto-fill or display
        );
        $this->render('kesmas/uji/input_hasil', $data);
    }

    public function simpan_hasil($permintaan_id)
    {
        try {
            $permintaan_id = (int)$permintaan_id;
            $permintaan = $this->Kesmas_model->get_permintaan($permintaan_id);
            if (!$permintaan) show_404();

            // Collect item results. Disable XSS (false) pada hasil agar simbol "<" / ">" tidak dihapus paksa oleh CodeIgniter
            $hasil_items = $this->input->post('hasil', false) ?: [];
            $keterangan_items = $this->input->post('keterangan', true) ?: [];

            // The 'status_kelayakan' and 'alasan' are no longer submitted from this form.
            // They are set during the "kaji ulang" step and should not be modified here.
            
            $tjp = $this->input->post('tgl_jam_pemeriksaan', true);
            $tjs = $this->input->post('tgl_jam_selesai', true);
            $tjl = $this->input->post('tgl_jam_lapor', true);
            $petugas_id = $this->input->post('id_petugas', true);

            $payload_permintaan = array(
                'id_petugas' => empty($petugas_id) ? null : $petugas_id,
                'tgl_jam_pemeriksaan' => $tjp ? str_replace('T', ' ', $tjp) : null,
                'tgl_jam_selesai' => $tjs ? str_replace('T', ' ', $tjs) : null,
                'tgl_jam_lapor' => $tjl ? str_replace('T', ' ', $tjl) : null,
                // Override data untuk baku mutu, metode, satuan per item
                'baku_mutu_override' => $this->input->post('baku_mutu_override', false) ?: [],
                'metode_override'    => $this->input->post('metode_override', false) ?: [],
            );

            $ok = $this->Kesmas_model->save_hasil(
                $permintaan_id,
                $hasil_items,
                $keterangan_items,
                $payload_permintaan, // Pass new payload for permintaan table
                $this->session->userdata('user_id')
            );

            if (!$ok) {
                $this->session->set_flashdata('error', 'Gagal simpan hasil.');
            } else {
                log_activity('input_hasil', 'form_permintaan', [
                    'record_id' => $permintaan_id,
                    'description' => "Menyimpan hasil uji untuk permintaan sampel (ID: {$permintaan_id})"
                ]);
                $this->session->set_flashdata('success', 'Hasil tersimpan.');
            }
            redirect('k-panel/uji/input/'.$permintaan_id);
        } catch (Exception $e) {
            log_message('error', 'Error in simpan_hasil: ' . $e->getMessage());
            $this->session->set_flashdata('error', 'Terjadi kesalahan sistem. Silakan coba lagi.');
            redirect('k-panel/uji/input/'.$permintaan_id);
        }
    }

    public function delete($id)
    {
        // Only allow POST requests for deletion (good practice for security)
        if ($this->input->method() !== 'post') {
            show_404();
        }

        $id = (int)$id;
        $permintaan = $this->Kesmas_model->get_permintaan($id);
        if (!$permintaan) {
            $this->session->set_flashdata('error', 'Permintaan tidak ditemukan.');
            redirect('k-panel/form_permintaan_kesmas');
        }

        $ok = $this->Kesmas_model->delete_permintaan($id);
        if (!$ok) {
            $this->session->set_flashdata('error', 'Gagal menghapus permintaan.');
        } else {
            log_activity('hapus', 'form_permintaan', [
                'record_id' => $id,
                'description' => "Menghapus permintaan sampel (ID: {$id})"
            ]);
            $this->session->set_flashdata('success', 'Permintaan berhasil dihapus.');
        }

        redirect('k-panel/form_permintaan_kesmas'); // Redirect back to the list
    }

    /* ============= KAJI ULANG PERMINTAAN PEMERIKSAAN ============= */

    public function kaji_ulang($id)
    {
        $id = (int)$id;
        $permintaan = $this->Kesmas_model->get_permintaan($id);
        if (!$permintaan) {
            show_404();
        }

        // Resolve petugas name from ID
        if (!empty($permintaan['id_petugas'])) {
            $permintaan['petugas_pengambil_name'] = $this->Kesmas_model->get_petugas_name_by_id($permintaan['id_petugas']) ?: '-';
        } else {
            $permintaan['petugas_pengambil_name'] = '-';
        }
        if (!empty($permintaan['id_petugas_2'])) {
            $permintaan['petugas_pengambil_2_name'] = $this->Kesmas_model->get_petugas_name_by_id($permintaan['id_petugas_2']) ?: '-';
        } else {
            $permintaan['petugas_pengambil_2_name'] = '-';
        }

        $data = array(
            'title' => 'Kaji Ulang Permintaan Pemeriksaan',
            'permintaan' => $permintaan,
            'petugas' => $this->Kesmas_model->list_petugas(),
            'verifikator' => $this->Verifikator_model->list_verifikator(),
            'penanggung_jawab_teknis' => $this->Penanggung_jawab_teknis_model->list_penanggung_jawab_teknis(),
        );
        $this->render('kesmas/permintaan/kaji_ulang', $data);
    }

    public function kaji_ulang_update($id)
    {
        if ($this->input->method() !== 'post') {
            show_404();
        }

        $id = (int)$id;
        $permintaan = $this->Kesmas_model->get_permintaan($id);
        if (!$permintaan) {
            $this->session->set_flashdata('error', 'Permintaan tidak ditemukan.');
            redirect('k-panel/form_permintaan_kesmas');
        }

        // Handle nullable dates securely to prevent PHP 8 TypeErrors
        $kk_peng = $this->input->post('kk_tgl_pengambilan', true);
        $kk_terima = $this->input->post('tgl_diterima_lab', true);
        $kk_kerja = $this->input->post('tgl_pengerjaan', true);
        $kk_input = $this->input->post('tgl_input_hasil', true);
        $kk_cetak = $this->input->post('tgl_cetak', true);

        // Kumpulkan data kaji ulang
        $kaji_ulang_data = array(
            'status_kelayakan' => $this->input->post('status_kelayakan', true),
            'alasan' => $this->input->post('alasan', true),
            'tgl_pengambilan' => $this->input->post('tgl_pengambilan', true) ?: null,
            'jam_pengambilan' => $this->input->post('jam_pengambilan', true) ?: null,
            'id_petugas' => $this->input->post('id_petugas', true) ?: null,
            'id_petugas_2' => $this->input->post('id_petugas_2', true) ?: null,
            'jumlah_biaya' => $this->input->post('jumlah_biaya', true) ?: 0,
            'cara_bayar' => $this->input->post('cara_bayar', true),
            'keterangan_bayar' => $this->input->post('keterangan_bayar', true),
            'petugas_pengambil_ttd_id' => $this->input->post('petugas_pengambil_ttd_id', true) ?: null,
            'petugas_verifikasi_id' => $this->input->post('petugas_verifikasi_id', true) ?: null,
            'petugas_validasi_id' => $this->input->post('petugas_validasi_id', true) ?: null,
            'kk_tgl_pengambilan' => !empty($kk_peng) ? str_replace('T', ' ', $kk_peng) : null,
            'tgl_diterima_lab' => !empty($kk_terima) ? str_replace('T', ' ', $kk_terima) : null,
            'tgl_pengerjaan' => !empty($kk_kerja) ? str_replace('T', ' ', $kk_kerja) : null,
            'tgl_input_hasil' => !empty($kk_input) ? str_replace('T', ' ', $kk_input) : null,
            'tgl_cetak' => !empty($kk_cetak) ? str_replace('T', ' ', $kk_cetak) : null,
            'catatan' => $this->input->post('catatan', true),
            'info_tambahan' => $this->input->post('info_tambahan', true),
        );

        // Update data kaji ulang di database
        if ($this->Kesmas_model->update_kaji_ulang($id, $kaji_ulang_data)) {
            log_activity('kaji_ulang', 'form_permintaan', [
                'record_id' => $id,
                'description' => "Menyimpan kaji ulang untuk permintaan sampel (ID: {$id})"
            ]);
            $this->session->set_flashdata('success', 'Kaji ulang permintaan berhasil disimpan.');
            
            // After successful kaji ulang, show completion page with survey
            redirect('k-panel/form_permintaan_kesmas/selesai/' . $id);
        } else {
            $this->session->set_flashdata('error', 'Gagal menyimpan kaji ulang permintaan.');
            redirect('k-panel/form_permintaan_kesmas/kaji_ulang/' . $id);
        }
    }

    /**
     * Halaman selesai pendaftaran dengan survei kepuasan
     */
    public function selesai($id)
    {
        $id = (int)$id;
        $permintaan = $this->Kesmas_model->get_permintaan($id);
        if (!$permintaan) {
            show_404();
        }

        $data = array(
            'title' => 'Pendaftaran Selesai',
            'permintaan' => $permintaan,
            'permintaan_id' => $id,
            'survey_exists' => false,
            'survey_link' => site_url('kesmas/survei/form')
        );

        $this->render('kesmas/permintaan/selesai', $data);
    }

    private function _collect_header($with_no_reg)
    {
        $petugas_pengambil_id = $this->input->post('id_petugas', true);
        $petugas_pendaftaran_id = $this->session->userdata('user_id');

        log_message('error', 'DEBUG _collect_header POST: ' . json_encode($this->input->post()));

        // Ambil jenis sampel, jika __lainnya__ ambil dari input manual
        $jenis_sampel = $this->input->post('jenis_sampel', true);
        if ($jenis_sampel === '__lainnya__') {
            $jenis_sampel = $this->input->post('jenis_sampel_manual', true);
        }

        $header = array(
            'is_diterima' => 1,
            'nama_sampel' => $this->input->post('nama_sampel', true),
            'id_kategori_sampel' => $this->input->post('id_kategori_sampel', true),
            'jenis_sampel' => $jenis_sampel,
            'volume_ml' => $this->input->post('volume_ml', true) ?: null,
            'tgl_pengambilan' => $this->input->post('tgl_pengambilan', true) ?: null,
            'jam_pengambilan' => $this->input->post('jam_pengambilan', true) ?: null,
            'lokasi_pengambilan' => $this->input->post('lokasi_pengambilan', true),
            'id_petugas' => !empty($petugas_pengambil_id) ? (int)$petugas_pengambil_id : null, // kept as placeholder when pengambil ditentukan kemudian
            'petugas_pendaftaran_id' => !empty($petugas_pendaftaran_id) ? (int)$petugas_pendaftaran_id : null,
            'tindakan_sampel' => $this->input->post('tindakan_sampel', true),
            'info_tambahan' => $this->input->post('info_tambahan', true),

            'nik'     => $this->input->post('nik', true),
            'nama'    => $this->input->post('nama', true),
            'alamat'  => $this->input->post('alamat', true),
            'kecamatan' => $this->input->post('kecamatan', true),
            'kelurahan' => $this->input->post('kelurahan', true),
            'objek'     => $this->input->post('objek', true),
            'telp'    => $this->input->post('telp', true),
            'instansi'=> $this->input->post('instansi', true),
            'tgl_permintaan' => $this->input->post('tgl_permintaan', true) ?: null,
            'ttd'     => $this->input->post('nama', true),

            'catatan' => $this->input->post('catatan', true),
        );
        
        // Only collect payment fields if they are present in the form 
        // (to prevent overwriting with 0 when editing from form.php which lacks these fields)
        if ($this->input->post('jumlah_biaya') !== null) {
            $header['jumlah_biaya'] = $this->input->post('jumlah_biaya', true) ?: 0;
            $header['cara_bayar'] = $this->input->post('cara_bayar', true);
            $header['keterangan_bayar'] = $this->input->post('keterangan_bayar', true);
        }

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
                        'instansi' => $pelanggan['instansi'],
                        'kecamatan' => $pelanggan['kecamatan'] ?? '',
                        'kelurahan' => $pelanggan['kelurahan'] ?? '',
                        'objek'     => $pelanggan['objek'] ?? ''
                    ]
                ]));
        } else {
            return $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(['status' => 'not_found']));
        }
    }
}

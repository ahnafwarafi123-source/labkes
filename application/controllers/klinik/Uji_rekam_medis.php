<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Uji_rekam_medis extends MY_KlinikController
{

    public function __construct()
    {
        parent::__construct();
        $this->require_role(['admin', 'petugas pendaftaran', 'petugas rm']);
        $this->load->model('Klinik/Form_permintaan_rm_model');
        $this->load->model('Klinik/Kunjungan_rm_model');
        $this->load->database();
        $this->load->library('session');
    }

    public function index()
    {
        $this->create_table_kunjungan_if_not_exists();

        $data['title'] = 'Data Pasien - Kunjungan Rekam Medis';

        $page = $this->input->get('page') ?: 1;
        $limit = $this->input->get('limit') ?: 10;
        // Pastikan limit hanya boleh 10, 20, 50, atau 'all'
        if (!in_array($limit, [10, 20, 50, 'all'])) {
            $limit = 10;
        }
        
        $limit_query = ($limit === 'all') ? 999999 : (int)$limit;
        $offset = ($page - 1) * ($limit === 'all' ? 0 : (int)$limit);
        $search = $this->input->get('search') ?: '';
        $tgl_mulai = $this->input->get('tgl_mulai', TRUE);

        $total = $this->Form_permintaan_rm_model->get_all_count($search, $tgl_mulai);
        $pasien_list = $this->Form_permintaan_rm_model->get_all_paginated($limit_query, $offset, $search, $tgl_mulai);
        $count_kunjungan = $this->Kunjungan_rm_model->get_count_all_pasien();

        // Attach count to each pasien
        foreach ($pasien_list as $p) {
            $p->total_kunjungan = isset($count_kunjungan[$p->no_rm]) ? $count_kunjungan[$p->no_rm] : 0;
        }

        $data['pasien'] = $pasien_list;
        $data['total'] = $total;
        $data['page'] = $page;
        $data['limit'] = $limit;
        $data['search'] = $search;
        $data['tgl_mulai'] = $tgl_mulai;

        $this->load->view('klinik/layout/header', $data);
        $this->load->view('klinik/layout/sidebar');
        $this->load->view('klinik/uji_rekam_medis/index', $data);
        $this->load->view('klinik/layout/footer');
    }

    public function detail($id_pasien)
    {
        $this->create_table_kunjungan_if_not_exists();

        $data['title'] = 'Detail Kunjungan Pasien SOAP';
        $data['pasien'] = $this->Form_permintaan_rm_model->get_by_nik($id_pasien);

        if (!$data['pasien']) {
            $this->session->set_flashdata('error', 'Data tidak ditemukan.');
            redirect('klinik/uji_rekam_medis');
        }

        // Ambil data kunjungan pasien tersebut
        $kunjungans = $this->Kunjungan_rm_model->get_by_no_rm($data['pasien']->no_rm);


        $data['kunjungan'] = $kunjungans;

        $this->load->view('klinik/layout/header', $data);
        $this->load->view('klinik/layout/sidebar');
        $this->load->view('klinik/uji_rekam_medis/detail', $data);
        $this->load->view('klinik/layout/footer');
    }

    public function tambah_kunjungan($id_pasien)
    {
        $pasien = $this->Form_permintaan_rm_model->get_by_nik($id_pasien);

        if (!$pasien) {
            $this->session->set_flashdata('error', 'Data pasien tidak ditemukan.');
            redirect('klinik/uji_rekam_medis');
        }

        if (empty($pasien->no_rm)) {
            $this->session->set_flashdata('error', 'Nomor RM belum diisi! Silakan lengkapi Nomor RM di menu Edit Pasien sebelum menambahkan kunjungan.');
            redirect('klinik/uji_rekam_medis');
        }

        $data_insert = [
            'no_rm' => $pasien->no_rm,
            'tanggal_kunjungan' => date('Y-m-d H:i:s'),
            'unit' => 'Poli Umum',
            'status_soap' => 'Belum diisi',
            'icd_10' => '<i>Kosong</i>'
        ];

        $kunjungan_id = $this->Kunjungan_rm_model->insert($data_insert);
        redirect('klinik/uji_rekam_medis/soap/' . $kunjungan_id);
    }

    public function simpan_kunjungan()
    {
        $no_rm = $this->input->post('no_rm');
        $tanggal_kunjungan = $this->input->post('tanggal_kunjungan');
        $tanggal_kunjungan = date('Y-m-d H:i:s', strtotime($tanggal_kunjungan));
        $unit = $this->input->post('unit');

        $data_insert = [
            'no_rm' => $no_rm,
            'tanggal_kunjungan' => $tanggal_kunjungan,
            'unit' => empty($unit) ? 'Poli Umum' : $unit,
            'status_soap' => 'Belum diisi',
            'icd_10' => '<i>Kosong</i>'
        ];

        $kunjungan_id = $this->Kunjungan_rm_model->insert($data_insert);
        $this->session->set_flashdata('success', 'Data kunjungan berhasil dibuat. Silakan lengkapi catatan SOAP berikut.');
        redirect('klinik/uji_rekam_medis/soap/' . $kunjungan_id);
    }

    // --- FITUR SOAP ---
    public function soap($kunjungan_id)
    {
        $this->create_table_kunjungan_if_not_exists();

        $data['kunjungan'] = $this->Kunjungan_rm_model->get_by_id($kunjungan_id);

        if (!$data['kunjungan']) {
            $this->session->set_flashdata('error', 'Data kunjungan tidak ditemukan.');
            redirect('klinik/uji_rekam_medis');
        }

        $data['pasien'] = $this->Form_permintaan_rm_model->get_by_no_rm($data['kunjungan']->no_rm);
        $data['title'] = 'Pengisian SOAP - Catatan Klinis';
        // Ambil data untuk opsi dropdown ICD-10
        try {
            $data['diagnosa'] = $this->db->order_by('kode', 'ASC')->get('diagnosa')->result();
        } catch (Exception $e) {
            $data['diagnosa'] = [];
        }

        if ($this->db->table_exists('petugas_dokter')) {
            $data['dokters'] = $this->db->get('petugas_dokter')->result();
        } else {
            $data['dokters'] = [];
        }

        $this->load->view('klinik/layout/header', $data);
        $this->load->view('klinik/layout/sidebar');
        $this->load->view('klinik/kunjungan_rekam_medis/soap', $data);
        $this->load->view('klinik/layout/footer');
    }

    public function simpan_diagnosa_ajax()
    {
        $kode = $this->input->post('kode');
        $nama = $this->input->post('nama');

        $kode = trim($kode ?? '');
        $nama = trim($nama ?? '');

        if (empty($nama)) {
            echo json_encode(['status' => 'error', 'message' => 'Nama diagnosa wajib diisi']);
            return;
        }

        // Cek jika kode ICD kosong, buatkan kode sementara karena kolom 'kode' NOT NULL
        if (empty($kode)) {
            $kode = 'MAN-' . strtoupper(substr(uniqid(), -5));
        } else {
            // Jika kode diinput, pastikan tidak duplicate dengan yang ada di database
            $existing = $this->db->get_where('diagnosa', ['kode' => $kode])->row();
            if ($existing) {
                // Jika sudah ada, kembalikan response sukses agar bisa langsung digunakan
                echo json_encode(['status' => 'success', 'id' => $existing->id, 'kode' => $existing->kode, 'nama' => $existing->nama]);
                return;
            }
        }

        $data = [
            'kode' => $kode,
            'nama' => $nama
        ];

        // Simpan state db_debug, matikan sementara agar error dikembalikan dalam JSON bukan HTML
        $db_debug = $this->db->db_debug;
        $this->db->db_debug = FALSE;

        // Jalankan insert ke database
        $insert = $this->db->insert('diagnosa', $data);
        $err = $this->db->error();

        // Kembalikan state db_debug
        $this->db->db_debug = $db_debug;

        if ($insert) {
            $id = $this->db->insert_id();
            echo json_encode(['status' => 'success', 'id' => $id, 'kode' => $kode, 'nama' => $nama]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Gagal menyimpan ke database. Error: ' . (isset($err['message']) ? $err['message'] : 'Unknown Error')]);
        }
    }

    // Simpan soap berhasil
    public function simpan_soap()
    {
        $kunjungan_id = $this->input->post('kunjungan_id');
        $id_pasien = $this->input->post('id_pasien');

        $icd_10_arr = $this->input->post('icd_10_arr');
        $icd_10_manual = $this->input->post('icd_10_manual');

        $final_icd = [];
        if (!empty($icd_10_arr)) {
            foreach ($icd_10_arr as $icd) {
                $final_icd[] = $icd;
            }
        }
        if ($icd_10_manual !== null && trim($icd_10_manual) !== '') {
            $final_icd[] = trim($icd_10_manual);
        }

        $icd_10_string = empty($final_icd) ? '<i>Kosong</i>' : implode('<br>', $final_icd);

        $data_update = [
            'tanggal_kunjungan' => date('Y-m-d H:i:s', strtotime($this->input->post('tanggal_kunjungan'))),
            'unit' => $this->input->post('unit'),
            'keluhan_utama' => $this->input->post('keluhan_utama'),
            'riwayat_penyakit' => $this->input->post('riwayat_penyakit'),
            'gcs' => $this->input->post('gcs'),
            'tensi_sistole' => $this->input->post('tensi_sistole'),
            'tensi_diastole' => $this->input->post('tensi_diastole'),
            'nadi' => $this->input->post('nadi'),
            'pernapasan' => $this->input->post('pernapasan'),
            'suhu' => $this->input->post('suhu'),
            'berat_badan' => $this->input->post('berat_badan'),
            'tinggi_badan' => $this->input->post('tinggi_badan'),
            'pemeriksaan_jantung' => $this->input->post('pemeriksaan_jantung'),
            'pemeriksaan_paru' => $this->input->post('pemeriksaan_paru'),
            'pemeriksaan_abdomen' => $this->input->post('pemeriksaan_abdomen'),
            'catatan_tambahan' => $this->input->post('catatan_tambahan'),
            'asesmen_diagnosa' => $this->input->post('asesmen_diagnosa'),
            'plan_rencana' => $this->input->post('plan_rencana'),
            'icd_10' => $icd_10_string,
            'nama_dokter_pemeriksa' => $this->input->post('nama_dokter_pemeriksa'),
            'status_soap' => 'Sudah diisi'
        ];

        $this->Kunjungan_rm_model->update($kunjungan_id, $data_update);
        $this->session->set_flashdata('success', 'Catatan medis SOAP berhasil disimpan.');
        redirect('klinik/uji_rekam_medis/detail/' . $id_pasien);
    }

    // lihat soap
    public function lihat_soap($kunjungan_id)
    {
        $this->create_table_kunjungan_if_not_exists();

        $data['kunjungan'] = $this->Kunjungan_rm_model->get_by_id($kunjungan_id);

        if (!$data['kunjungan']) {
            $this->session->set_flashdata('error', 'Data kunjungan tidak ditemukan.');
            redirect('klinik/uji_rekam_medis');
        }

        $data['pasien'] = $this->Form_permintaan_rm_model->get_by_no_rm($data['kunjungan']->no_rm);
        $data['title'] = 'Rincian Catatan Klinis SOAP';

        $this->load->view('klinik/layout/header', $data);
        $this->load->view('klinik/layout/sidebar');
        $this->load->view('klinik/uji_rekam_medis/lihat_soap', $data);
        $this->load->view('klinik/layout/footer');
    }

    // print soap
    public function print_soap($kunjungan_id)
    {
        $this->create_table_kunjungan_if_not_exists();

        $data['kunjungan'] = $this->Kunjungan_rm_model->get_by_id($kunjungan_id);

        if (!$data['kunjungan']) {
            $this->session->set_flashdata('error', 'Data kunjungan tidak ditemukan.');
            redirect('klinik/uji_rekam_medis');
        }

        $data['pasien'] = $this->Form_permintaan_rm_model->get_by_no_rm($data['kunjungan']->no_rm);
        $data['title'] = 'Cetak Rekam Medis';

        $this->load->view('klinik/uji_rekam_medis/print_soap', $data);
    }

    // PDF
    public function print_soap_pdf($kunjungan_id)
    {
        $this->create_table_kunjungan_if_not_exists();

        $data['kunjungan'] = $this->Kunjungan_rm_model->get_by_id($kunjungan_id);

        if (!$data['kunjungan']) {
            $this->session->set_flashdata('error', 'Data kunjungan tidak ditemukan.');
            redirect('klinik/uji_rekam_medis');
        }

        $data['pasien'] = $this->Form_permintaan_rm_model->get_by_no_rm($data['kunjungan']->no_rm);
        $data['title'] = 'Cetak Rekam Medis PDF';
        $data['is_pdf'] = true;

        $this->load->view('klinik/uji_rekam_medis/print_soap_pdf', $data);
    }
    // --- AKHIR FITUR SOAP ---

    public function batal_kunjungan($id, $id_pasien, $redirect_to = 'detail')
    {

        $this->Kunjungan_rm_model->delete($id);
        $this->session->set_flashdata('success', 'Aksi dibatalkan. Data riwayat kunjungan yang belum selesai telah dihapus.');

        if ($redirect_to == 'tambah') {
            redirect('klinik/uji_rekam_medis/tambah_kunjungan/' . $id_pasien);
        } else {
            redirect('klinik/uji_rekam_medis/detail/' . $id_pasien);
        }
    }

    public function hapus_kunjungan($id, $id_pasien)
    {
        // Hanya user modul Klinik yang boleh mengubah data klinik
        if (current_module() !== 'klinik') {
            deny_cross_module();
        }

        $this->Kunjungan_rm_model->delete($id);
        $this->session->set_flashdata('success', 'Data Kunjungan berhasil dihapus.');
        redirect('klinik/uji_rekam_medis/detail/' . $id_pasien);
    }

    private function create_table_kunjungan_if_not_exists()
    {
        $this->load->dbforge();
        if (!$this->db->table_exists('kunjungan_rm')) {
            $fields = [
                'id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => TRUE, 'auto_increment' => TRUE],
                'no_rm' => ['type' => 'VARCHAR', 'constraint' => '50', 'null' => TRUE],
                'tanggal_kunjungan' => ['type' => 'DATETIME', 'null' => TRUE],
                'unit' => ['type' => 'VARCHAR', 'constraint' => '100', 'null' => TRUE],
                'status_soap' => ['type' => 'VARCHAR', 'constraint' => '50', 'null' => TRUE],
                'icd_10' => ['type' => 'TEXT', 'null' => TRUE],
                'berat_badan' => ['type' => 'VARCHAR', 'constraint' => '50', 'null' => TRUE],
                'tinggi_badan' => ['type' => 'VARCHAR', 'constraint' => '50', 'null' => TRUE],
            ];
            $this->dbforge->add_key('id', TRUE);
            $this->dbforge->add_field($fields);
            $this->dbforge->create_table('kunjungan_rm', TRUE);
        } else {
            if ($this->db->field_exists('pasien_id', 'kunjungan_rm')) {
                if (!$this->db->field_exists('no_rm', 'kunjungan_rm')) {
                    $new_field = [
                        'no_rm' => ['type' => 'VARCHAR', 'constraint' => '50', 'null' => TRUE, 'after' => 'id']
                    ];
                    $this->dbforge->add_column('kunjungan_rm', $new_field);
                }
                $this->db->query("UPDATE kunjungan_rm JOIN pasien_rm ON pasien_rm.id = kunjungan_rm.pasien_id SET kunjungan_rm.no_rm = pasien_rm.no_rm");
                $this->dbforge->drop_column('kunjungan_rm', 'pasien_id');
            }
            if (!$this->db->field_exists('unit', 'kunjungan_rm')) {
                $new_field_unit = [
                    'unit' => ['type' => 'VARCHAR', 'constraint' => '100', 'null' => TRUE, 'after' => 'tanggal_kunjungan']
                ];
                $this->dbforge->add_column('kunjungan_rm', $new_field_unit);
            }

            if (!$this->db->field_exists('status_soap', 'kunjungan_rm')) {
                $new_field_status = [
                    'status_soap' => ['type' => 'VARCHAR', 'constraint' => '50', 'null' => TRUE, 'after' => 'unit']
                ];
                $this->dbforge->add_column('kunjungan_rm', $new_field_status);
            }

            if (!$this->db->field_exists('icd_10', 'kunjungan_rm')) {
                $new_field_icd = [
                    'icd_10' => ['type' => 'TEXT', 'null' => TRUE, 'after' => 'status_soap']
                ];
                $this->dbforge->add_column('kunjungan_rm', $new_field_icd);
            }

            if (!$this->db->field_exists('keluhan_utama', 'kunjungan_rm')) {
                $new_fields = [
                    'keluhan_utama' => ['type' => 'TEXT', 'null' => TRUE],
                    'riwayat_penyakit' => ['type' => 'TEXT', 'null' => TRUE],
                    'gcs' => ['type' => 'VARCHAR', 'constraint' => '50', 'null' => TRUE],
                    'tensi_sistole' => ['type' => 'VARCHAR', 'constraint' => '50', 'null' => TRUE],
                    'tensi_diastole' => ['type' => 'VARCHAR', 'constraint' => '50', 'null' => TRUE],
                    'nadi' => ['type' => 'VARCHAR', 'constraint' => '50', 'null' => TRUE],
                    'pernapasan' => ['type' => 'VARCHAR', 'constraint' => '50', 'null' => TRUE],
                    'suhu' => ['type' => 'VARCHAR', 'constraint' => '50', 'null' => TRUE],
                    'pemeriksaan_jantung' => ['type' => 'TEXT', 'null' => TRUE],
                    'pemeriksaan_paru' => ['type' => 'TEXT', 'null' => TRUE],
                    'pemeriksaan_abdomen' => ['type' => 'TEXT', 'null' => TRUE],
                    'catatan_tambahan' => ['type' => 'TEXT', 'null' => TRUE],
                    'asesmen_diagnosa' => ['type' => 'TEXT', 'null' => TRUE],
                    'plan_rencana' => ['type' => 'TEXT', 'null' => TRUE],
                ];
                $this->dbforge->add_column('kunjungan_rm', $new_fields);
            }

            if (!$this->db->field_exists('nama_dokter_pemeriksa', 'kunjungan_rm')) {
                $new_field_dokter = [
                    'nama_dokter_pemeriksa' => ['type' => 'VARCHAR', 'constraint' => '100', 'null' => TRUE]
                ];
                $this->dbforge->add_column('kunjungan_rm', $new_field_dokter);
            }

            if (!$this->db->field_exists('berat_badan', 'kunjungan_rm')) {
                $new_field_bb = [
                    'berat_badan' => ['type' => 'VARCHAR', 'constraint' => '50', 'null' => TRUE]
                ];
                $this->dbforge->add_column('kunjungan_rm', $new_field_bb);
            }

            if (!$this->db->field_exists('tinggi_badan', 'kunjungan_rm')) {
                $new_field_tb = [
                    'tinggi_badan' => ['type' => 'VARCHAR', 'constraint' => '50', 'null' => TRUE]
                ];
                $this->dbforge->add_column('kunjungan_rm', $new_field_tb);
            }
        }
    }

    // tampilan baru untuk kunjungan pasien
    public function index_1()
    {
        $this->create_table_kunjungan_if_not_exists();

        $pasien_list = $this->Form_permintaan_rm_model->get_all();
        $data['title'] = 'Data Kunjungan Pasien';

        foreach ($pasien_list as $p) {
            $latest = $this->db->order_by('tanggal_kunjungan', 'DESC')->get_where('kunjungan_rm', ['no_rm' => $p->no_rm])->row();
            $p->latest_kunjungan_id = $latest ? $latest->id : null;
        }

        $data['pasien'] = $pasien_list;

        $this->load->view('klinik/layout/header', $data);
        $this->load->view('klinik/layout/sidebar');
        $this->load->view('klinik/uji_rekam_medis/index_1', $data);
        $this->load->view('klinik/layout/footer');
    }
}

<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Form_permintaan_rm extends MY_KlinikController
{

    public function __construct()
    {
        parent::__construct();
        $this->require_role(['admin', 'petugas pendaftaran', 'petugas rm']);
        $this->load->model('Klinik/Form_permintaan_rm_model');
        $this->load->model('Klinik/Puskesmas_wilayah_model');
        $this->load->database();
        $this->load->library('session');
    }

    public function index()
    {
        $data['title'] = 'Data Pendaftaran Rekam Medis';

        $this->create_table_if_not_exists();

        $page = $this->input->get('page') ?: 1;
        $limit = $this->input->get('limit') ?: 10;
        // Pastikan limit hanya boleh 10, 20, atau 50
        if (!in_array($limit, [10, 20, 50, 'all'])) {
            $limit = 10;
        }
       	$limit_query = ($limit === 'all') ? 999999 : (int)$limit;
        $offset = ($page - 1) * ($limit === 'all' ? 0 : (int)$limit);
        $search = $this->input->get('search') ?: '';
        $tgl_mulai = $this->input->get('tgl_mulai', TRUE);

        $total = $this->Form_permintaan_rm_model->get_all_count($search, $tgl_mulai);
        $pasien = $this->Form_permintaan_rm_model->get_all_paginated($limit, $offset, $search, $tgl_mulai);

        $data['pasien'] = $pasien;
        $data['total'] = $total;
        $data['page'] = $page;
        $data['limit'] = $limit;
        $data['search'] = $search;
        $data['tgl_mulai'] = $tgl_mulai;

        $this->load->view('klinik/layout/header', $data);
        $this->load->view('klinik/layout/sidebar');
        $this->load->view('klinik/form_permintaan_rm/index', $data);
        $this->load->view('klinik/layout/footer');
    }

    public function create()
    {
        $data['title'] = 'Pendaftaran Rekam Medis';
        $data['no_rm'] = $this->Form_permintaan_rm_model->generate_no_rm();

        $this->load->view('klinik/layout/header', $data);
        $this->load->view('klinik/layout/sidebar');
        $this->load->view('klinik/form_permintaan_rm/form', $data);
        $this->load->view('klinik/layout/footer');
    }

    public function store()
    {
        $post = $this->input->post();

        $nik = $post['nik'] ?? '';

        if (!ctype_digit($nik)) {
            $this->session->set_flashdata('error', 'Pendaftaran dibatalkan. Format NIK wajib hanya menggunakan angka (numerik).');
            redirect('klinik/form_permintaan_rm/create');
            return;
        }

        $alamat = $post['alamat'] ?? '';
        $puskesmas_wilayah = $this->Puskesmas_wilayah_model->auto_map_puskesmas($alamat);

        $data_simpan = [
            'no_rm' => $post['no_rm'] ?? '',
            'nik' => $nik,
            'nama_pasien' => $post['nama_pasien'] ?? '',
            'tgl_lahir' => $post['tgl_lahir'] ?? null,
            'umur' => $post['umur'] ?? 0,
            'gender' => $post['gender'] ?? '',
            'alamat' => $alamat,
            'puskesmas_wilayah' => $puskesmas_wilayah,
            'no_telp' => $post['no_telp'] ?? '',
            'agama' => $post['agama'] ?? '',
            'status_nikah' => $post['status_nikah'] ?? '',
            'pendidikan' => $post['pendidikan'] ?? '',
            'pekerjaan' => $post['pekerjaan'] ?? '',
            'petugas_pendaftaran' => $post['petugas_pendaftaran'] ?? '',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        // Cek apakah pasien dengan NIK ini sudah ada
        $existing_rm = $this->Form_permintaan_rm_model->get_by_nik($nik);

        if ($existing_rm) {
            // Update data yang sudah ada
            unset($data_simpan['no_rm']); // Jangan ubah no_rm lama
            unset($data_simpan['created_at']); // Pertahankan tanggal pendaftaran awal

            $this->Form_permintaan_rm_model->update($existing_rm->id_pasien, $data_simpan);
            $this->session->set_flashdata('success', 'Pasien sudah terdaftar. Data pasien Rekam Medis berhasil diperbarui.');
        } else {
            // Insert data baru
            $this->Form_permintaan_rm_model->insert($data_simpan);
            $this->session->set_flashdata('success', 'Data pasien Rekam Medis berhasil didaftarkan dengan No RM baru.');
        }

        redirect('klinik/form_permintaan_rm');
    }

    public function cari_pasien()
    {
        $nik = $this->input->post('nik');
        $pasien = $this->Form_permintaan_rm_model->get_by_nik($nik);

        if ($pasien) {
            echo json_encode(['status' => 'success', 'data' => $pasien]);
        } else {
            echo json_encode(['status' => 'error']);
        }
    }

    public function edit($id_pasien)
    {
        $data['title'] = 'Edit Data Pasien';
        $data['pasien'] = $this->Form_permintaan_rm_model->get_by_nik($id_pasien);

        if (!$data['pasien']) {
            $this->session->set_flashdata('error', 'Data tidak ditemukan.');
            redirect('klinik/form_permintaan_rm');
        }

        $this->load->view('klinik/layout/header', $data);
        $this->load->view('klinik/layout/sidebar');
        $this->load->view('klinik/form_permintaan_rm/form', $data);
        $this->load->view('klinik/layout/footer');
    }

    public function update($id_pasien)
    {
        $post = $this->input->post();

        if (!isset($post['nik']) || !ctype_digit($post['nik'])) {
            $this->session->set_flashdata('error', 'Update dibatalkan. Format NIK wajib hanya menggunakan angka (numerik).');
            redirect('klinik/form_permintaan_rm/edit/' . $id_pasien);
            return;
        }

        $alamat = $post['alamat'] ?? '';
        $puskesmas_wilayah = $this->Puskesmas_wilayah_model->auto_map_puskesmas($alamat);

        $data_update = [
            'no_rm' => $post['no_rm'] ?? '',
            'nik' => $post['nik'] ?? '',
            'nama_pasien' => $post['nama_pasien'] ?? '',
            'tgl_lahir' => $post['tgl_lahir'] ?? null,
            'umur' => $post['umur'] ?? 0,
            'gender' => $post['gender'] ?? '',
            'alamat' => $alamat,
            'puskesmas_wilayah' => $puskesmas_wilayah,
            'no_telp' => $post['no_telp'] ?? '',
            'agama' => $post['agama'] ?? '',
            'status_nikah' => $post['status_nikah'] ?? '',
            'pendidikan' => $post['pendidikan'] ?? '',
            'pekerjaan' => $post['pekerjaan'] ?? '',
            'petugas_pendaftaran' => $post['petugas_pendaftaran'] ?? '',
            'updated_at' => date('Y-m-d H:i:s')
        ];

        $this->Form_permintaan_rm_model->update($id_pasien, $data_update);
        $this->session->set_flashdata('success', 'Data pasien Rekam Medis berhasil diperbarui.');
        redirect('klinik/form_permintaan_rm');
    }

    public function detail($id_pasien)
    {
        $data['title'] = 'Detail Pasien Rekam Medis';
        $data['pasien'] = $this->Form_permintaan_rm_model->get_by_nik($id_pasien);

        if (!$data['pasien']) {
            $this->session->set_flashdata('error', 'Data tidak ditemukan.');
            redirect('klinik/form_permintaan_rm');
        }

        $this->load->view('klinik/layout/header', $data);
        $this->load->view('klinik/layout/sidebar');
        $this->load->view('klinik/form_permintaan_rm/detail', $data);
        $this->load->view('klinik/layout/footer');
    }

    public function delete($id)
    {
        // Hanya user modul Klinik yang boleh mengubah data klinik
        if (current_module() !== 'klinik') {
            deny_cross_module();
        }

        $this->Form_permintaan_rm_model->delete($id);
        $this->session->set_flashdata('success', 'Data pasien Rekam Medis berhasil dihapus.');
        redirect('klinik/form_permintaan_rm');
    }

    // Fungsi utilitas untuk membuat tabel otomatis jika belum ada di database
    private function create_table_if_not_exists()
    {
        $this->load->dbforge();
        if (!$this->db->table_exists('pasien')) {

            $this->dbforge->add_key('id', TRUE);
            $this->dbforge->add_field($fields);
            $this->dbforge->create_table('pasien', TRUE);
        } else {
            if (!$this->db->field_exists('pendidikan', 'pasien')) {
                $fields = [
                    'pendidikan' => ['type' => 'VARCHAR', 'constraint' => '50', 'null' => TRUE],
                ];
                $this->dbforge->add_column('pasien', $fields);
            }
            if (!$this->db->field_exists('petugas_pendaftaran', 'pasien')) {
                $fields = [
                    'petugas_pendaftaran' => ['type' => 'VARCHAR', 'constraint' => '100', 'null' => TRUE],
                ];
                $this->dbforge->add_column('pasien', $fields);
            }
        }
    }
}
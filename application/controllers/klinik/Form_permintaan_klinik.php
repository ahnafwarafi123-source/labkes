<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Form_permintaan_klinik extends MY_KlinikController
{

    public function __construct()
    {
        parent::__construct();
        $this->require_role(['admin', 'petugas pendaftaran']);
        $this->load->model('Klinik/Form_permintaan_klinik_model');
        $this->load->model('Klinik/Petugas_verifikasi_model');
        $this->load->model('Klinik/Petugas_validasi_model');
        $this->load->database();
        $this->load->library('session');

        $this->load->dbforge();
        if (!$this->db->field_exists('id_kunjungan', 'form_permintaan_klinik_detail')) {
            $this->dbforge->add_column('form_permintaan_klinik_detail', [
                'id_kunjungan' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => TRUE, 'null' => TRUE]
            ]);
        }

        if (!$this->db->field_exists('no_register', 'form_permintaan_klinik')) {
            $this->dbforge->add_column('form_permintaan_klinik', [
                'no_register' => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => TRUE]
            ]);
        }

        if (!$this->db->field_exists('informasi_lain', 'form_permintaan_klinik')) {
            $this->dbforge->add_column('form_permintaan_klinik', [
                'informasi_lain' => ['type' => 'TEXT', 'null' => TRUE]
            ]);
        }
    }

    // =======================
    // 📄 HALAMAN INDEX
    // =======================
    public function index()
    {
        $data['title'] = 'Data Formulir Permintaan';

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

        $total = $this->Form_permintaan_klinik_model->get_all_formulir_count($search, $tgl_mulai);
        $formulir = $this->Form_permintaan_klinik_model->get_all_formulir_paginated($limit_query, $offset, $search, $tgl_mulai);

        $data['formulir'] = $formulir;
        $data['total'] = $total;
        $data['page'] = $page;
        $data['limit'] = $limit;
        $data['search'] = $search;
        $data['tgl_mulai'] = $tgl_mulai;

        $this->load->view('klinik/layout/header', $data);
        $this->load->view('klinik/layout/sidebar');
        $this->load->view('klinik/form_permintaan_klinik/index', $data);
        $this->load->view('klinik/layout/footer');
    }


    public function cari_pasien()
    {
        $nik = $this->input->post('nik');
        $this->load->model('Klinik/Form_permintaan_rm_model');
        $pasien = $this->Form_permintaan_rm_model->get_by_nik($nik);

        if ($pasien) {
            echo json_encode(['status' => 'success', 'data' => $pasien]);
        } else {
            echo json_encode(['status' => 'error']);
        }
    }

    // =======================
    // 🧾 FORM CREATE
    // =======================
    public function create()
    {
        $data['title'] = 'Formulir Permintaan Pemeriksaan';
        $data['no_register'] = '';
        $data['kunjungan_id'] = $this->input->get('kunjungan_id'); // Optional, to link to a visit

        if ($data['kunjungan_id']) {
            $this->load->model('Klinik/Kunjungan_rm_model');
            $kunjungan = $this->Kunjungan_rm_model->get_by_id($data['kunjungan_id']);
            if ($kunjungan) {
                $this->load->model('Klinik/Form_permintaan_rm_model');
                $pasien = $this->Form_permintaan_rm_model->get_by_no_rm($kunjungan->no_rm);
                if ($pasien) {
                    $data['pasien_prefill'] = $pasien;
                    $data['kunjungan_prefill'] = $kunjungan;
                }
            }
        } else {
            $nik = $this->input->get('nik');
            if ($nik) {
                $this->load->model('Klinik/Form_permintaan_rm_model');
                $pasien = $this->Form_permintaan_rm_model->get_by_nik($nik);
                if ($pasien) {
                    $data['pasien_prefill'] = $pasien;
                }
            }
        }

        // Ambil data dari tabel pemeriksaan_detail melalui model (atau langsung DB)
        $data['pemeriksaan'] = $this->Form_permintaan_klinik_model->get_jenis_pemeriksaan();

        // Ambil daftar semua petugas untuk dropdown
        $data['daftar_petugas'] = $this->db->get('petugas_sampel_klinik')->result();

        $data['daftar_verifikasi'] = $this->Petugas_verifikasi_model->get_default();
        $data['daftar_validasi'] = $this->Petugas_validasi_model->get_default();

        // Kelompokkan berdasarkan kategori (untuk tampilan)
        $data['kategori_pemeriksaan'] = [];
        foreach ($data['pemeriksaan'] as $row) {
            $data['kategori_pemeriksaan'][$row['kategori']][] = $row;
        }

        $this->load->view('klinik/layout/header', $data);
        $this->load->view('klinik/layout/sidebar');
        $this->load->view('klinik/form_permintaan_klinik/form', $data);
        $this->load->view('klinik/layout/footer');
    }

    // =======================
    // 💾 SIMPAN SEMUA DATA FORM
    // =======================
    public function store()
    {
        $post = $this->input->post();

        // 1️⃣ Simpan Dokter Pengirim
        $id_dokter = NULL;

        // Pastikan tgl_form selalu terisi hari ini jika kosong
        if (empty($post['tgl_form'])) {
            $post['tgl_form'] = date('Y-m-d');
        }

        // 2️⃣ Simpan Pasien & Sinkronisasi ke Rekam Medis (pasien_rm)
        $this->load->model('Klinik/Form_permintaan_rm_model');
        $this->load->model('Klinik/Puskesmas_wilayah_model');

        // Cek apakah pasien dengan NIK ini sudah ada di Rekam Medis
        $existing_rm = null;
        if (!empty($post['nik'])) {
            $existing_rm = $this->Form_permintaan_rm_model->get_by_nik($post['nik']);
        }
        if (!$existing_rm && !empty($post['no_rm'])) {
            $existing_rm = $this->Form_permintaan_rm_model->get_by_no_rm($post['no_rm']);
        }

        $no_register = $post['no_register'] ?? null;

        $alamat = $post['alamat'] ?? '';

        $data_pasien_gabungan = [
            'nama_pasien'        => $post['nama_pasien'] ?? '',
            'nik'                => $post['nik'] ?? '',
            'tgl_lahir'          => $post['tgl_lahir'] ?? null,
            'umur'               => $post['umur'] ?? '',
            'alamat'             => $post['alamat'] ?? '',
            'gender'             => $post['gender'] ?? '',
            'agama'              => $post['agama'] ?? '',
            'status_nikah'       => $post['status_nikah'] ?? '',
            'pendidikan'         => $post['pendidikan'] ?? '',
            'pekerjaan'          => $post['pekerjaan'] ?? '',
            'no_telp'            => $post['no_telp'] ?? '',
            'status_pasien'      => 'Rujukan',
            'diagnosa'           => $post['diagnosa_klinis'] ?? '',
            'obat'               => $post['obat_dikonsumsi'] ?? '',
            'id_dokter_pengirim' => $id_dokter,
            'petugas_pendaftaran' => $post['petugas_pendaftaran'] ?? ''
        ];

        if ($existing_rm) {
            $no_rm = $existing_rm->no_rm;
            $id_pasien = isset($existing_rm->id) ? $existing_rm->id : (isset($existing_rm->id_pasien) ? $existing_rm->id_pasien : null);

            $data_pasien_gabungan['updated_at'] = date('Y-m-d H:i:s');

            $this->db->where('no_rm', $no_rm);
            $this->db->update('pasien', $data_pasien_gabungan);

            if (!$id_pasien) {
                $existing = $this->db->get_where('pasien', ['no_rm' => $no_rm])->row();
                $id_pasien = $existing->id ?? ($existing->id_pasien ?? null);
            }
        } else {
            // Jika belum ada, buat no_rm baru dan simpan
            $no_rm = $this->Form_permintaan_rm_model->generate_no_rm();
            $data_pasien_gabungan['no_rm'] = $no_rm;
            $data_pasien_gabungan['created_at'] = date('Y-m-d H:i:s');

            $this->db->insert('pasien', $data_pasien_gabungan);
            $id_pasien = $this->db->insert_id();
        }

        // Set no_rm & no_register ke $post agar ikut tersimpan di form_permintaan_klinik
        $post['no_rm'] = $no_rm;
        $post['id_pasien'] = $id_pasien;
        $post['no_register'] = $no_register;



        // 4️⃣ Tambahkan Kelayakan & Alasan ke data POST sebelum simpan form
        $kelayakan = $post['kelayakan_sampel'] ?? 'Belum Diperiksa'; // ← ganti variabelnya
        $alasan_tidak_layak = '';
        if (!empty($post['alasan_tidak_layak'])) {
            $alasan_tidak_layak = implode(', ', $post['alasan_tidak_layak']);
        }

        $post['kelayakan'] = $kelayakan;
        $post['alasan_tidak_layak'] = $alasan_tidak_layak;
        $post['volume_sampel_kaji_ulang'] = $post['volume_sampel_kaji_ulang'] ?? null;

        // Tambahkan petugas yang dipilih ke data POST
        $post['petugas_pendaftaran'] = $this->input->post('petugas_pendaftaran');
        $post['petugas_pengambil']   = $this->input->post('petugas_pengambil');

        $post['petugas_verifikasi'] = $this->input->post('petugas_verifikasi');
        $post['petugas_validasi']   = $this->input->post('petugas_validasi');
        $post['kunjungan_id']       = $this->input->post('kunjungan_id'); // From hidden input

        // Auto-assign to latest Kunjungan if empty
        if (empty($post['kunjungan_id']) && !empty($no_rm)) {
            $this->db->where('no_rm', $no_rm);
            $this->db->where('DATE(tanggal_kunjungan)', date('Y-m-d'));
            $this->db->order_by('id', 'DESC');
            $kunjungan = $this->db->get('kunjungan_rm')->row();
            if ($kunjungan) {
                $post['kunjungan_id'] = $kunjungan->id;
            } else {
                $post['kunjungan_id'] = NULL;
            }
        }

        // 5️⃣ Simpan ke tabel form_permintaan_klinik (via model)
        $id_form = $this->Form_permintaan_klinik_model->simpan_form($post);

        // 6️⃣ Simpan detail pemeriksaan yang dicentang
        if (!empty($post['jenis_pemeriksaan'])) {
            foreach ($post['jenis_pemeriksaan'] as $jp) {
                // Format value HTML: "id|nama_jenis|sub_kategori|kategori"
                list($id_jenis, $nama_jenis, $sub_kategori, $kategori) = explode('|', $jp);

                $data_detail = [
                    'id_form'        => $id_form,
                    'id_kunjungan'   => $post['kunjungan_id'] ?? null,
                    'id_jenis'       => $id_jenis,
                    'nama_jenis'     => $nama_jenis,
                    'sub_kategori'   => $sub_kategori,
                    'kategori'       => $kategori
                ];
                $this->db->insert('form_permintaan_klinik_detail', $data_detail);
            }
        }

        // 7️⃣ Simpan data pembayaran
        $metode_pembayaran = $post['metode_pembayaran'] ?? null;
        if ($metode_pembayaran == 'Lain-lain' && !empty($post['metode_lainnya'])) {
            $metode_pembayaran = $post['metode_lainnya'];
        }

        $data_bayar = [
            'id_pasien'          => $id_pasien,
            'no_register'        => $no_register,
            'total_biaya'        => $post['jumlah_biaya'] ?? 0,
            'metode_pembayaran'  => $metode_pembayaran,
            'status'             => 'Belum Lunas',
            'created_at'         => date('Y-m-d H:i:s')
        ];
        $this->db->insert('pembayaran', $data_bayar);

        // 🔹 8️⃣ Setelah semua disimpan, redirect ke halaman detail
        $this->session->set_flashdata('success', '✅ Data berhasil disimpan ke semua tabel termasuk pembayaran dan kelayakan!');
        redirect('klinik/form_permintaan_klinik/detail/' . $id_form);
    }

    // =======================
    // ✏️ EDIT DATA FORM
    // =======================
    public function edit($id)
    {
        $data['title'] = 'Edit Formulir Permintaan Pemeriksaan';

        // Ambil data form utama
        $data['form'] = $this->Form_permintaan_klinik_model->get_by_id($id);
        if (!$data['form']) show_404();

        // 🔹 Ambil data pembayaran lama
        $this->db->where('no_register', $data['form']->no_register);
        $data['pembayaran'] = $this->db->get('pembayaran')->row();

        // Ambil daftar semua petugas
        $data['daftar_petugas'] = $this->db->get('petugas_sampel_klinik')->result();
        $data['daftar_verifikasi'] = $this->Petugas_verifikasi_model->get_default();
        $data['daftar_validasi'] = $this->Petugas_validasi_model->get_default();

        // Ambil semua daftar jenis pemeriksaan (misal: Hemoglobin, Leukosit, dst)
        $data['pemeriksaan'] = $this->Form_permintaan_klinik_model->get_jenis_pemeriksaan();

        // Kelompokkan berdasarkan kategori (misal: Hematologi, Kimia Klinik, dst)
        $data['kategori_pemeriksaan'] = [];
        foreach ($data['pemeriksaan'] as $row) {
            $data['kategori_pemeriksaan'][$row['kategori']][] = $row;
        }

        // Ambil ID jenis pemeriksaan yang sudah dipilih sebelumnya
        $this->db->select('id_jenis');
        $this->db->from('form_permintaan_klinik_detail');
        $this->db->where('id_form', $id);
        $query = $this->db->get();

        // Buat array berisi ID yang sudah dipilih
        $data['selected_jenis'] = array_column($query->result_array(), 'id_jenis');

        // ✅ Ambil data kelayakan & alasan lama
        $data['kelayakan_sampel'] = $data['form']->kelayakan_sampel ?? '';
        $data['alasan_tidak_layak'] = !empty($data['form']->alasan_tidak_layak)
            ? explode(', ', $data['form']->alasan_tidak_layak)
            : [];

        // Kirim data no_register dari form lama
        $data['no_register'] = $data['form']->no_register;

        // Load view
        $this->load->view('klinik/layout/header', $data);
        $this->load->view('klinik/layout/sidebar');
        $this->load->view('klinik/form_permintaan_klinik/form', $data);
        $this->load->view('klinik/layout/footer');
    }



    // =======================
    // 💾 UPDATE DATA FORM
    // =======================
    public function update($id)
    {
        $post = $this->input->post();

        // Memulai Transaksi Database untuk menjaga integritas data
        $this->db->trans_start();

        // Ambil data lama sebelum di-update untuk pencarian pembayaran dan pasien
        $form_lama = $this->db->get_where('form_permintaan_klinik', ['id' => $id])->row();
        $no_register_lama = $form_lama ? $form_lama->no_register : null;
        $id_pasien_lama = $form_lama ? $form_lama->id_pasien : null;

        // === Gabung asal sampel jika checkbox ===
        if (isset($post['asal_sampel']) && is_array($post['asal_sampel'])) {
            $post['asal_sampel'] = implode(', ', $post['asal_sampel']);
        }

        // === Ambil checkbox lokasi & jenis spesimen ===
        $lokasi_pengambilan = $this->input->post('lokasi_pengambilan');
        $jenis_spesimen = $this->input->post('jenis_spesimen');
        $lokasi_pengambilan_str = is_array($lokasi_pengambilan) ? implode(', ', $lokasi_pengambilan) : '';
        $jenis_spesimen_str = is_array($jenis_spesimen) ? implode(', ', $jenis_spesimen) : '';

        // === Input teks tambahan ===
        $lokasi_lainnya = $this->input->post('lokasi_lainnya', TRUE);
        $spesimen_lainnya = $this->input->post('spesimen_lainnya', TRUE);

        // === Siapkan data utama (semua dalam satu tabel) ===
        $data_update = [
            // IDENTITAS PASIEN
            // (no_rm removed as it is no longer in form_permintaan_klinik table)

            // INFORMASI KLINIS
            'diagnosa_klinis'   => $post['diagnosa_klinis'] ?? '',
            'obat_dikonsumsi'   => $post['obat_dikonsumsi'] ?? '',
            'asal_sampel'       => $post['asal_sampel'] ?? '',
            'puasa'             => $post['puasa'] ?? '',
            'keterangan_puasa'  => $post['keterangan_puasa'] ?? '',
            'tgl_permintaan'    => $post['tgl_permintaan'] ?? null,

            // SAMPEL
            'tgl_pengambilan'   => $post['tgl_pengambilan'] ?? null,
            'jam_pengambilan'   => $post['jam_pengambilan'] ?? '',
            'volume_spesimen'   => $post['volume_spesimen'] ?? '',
            'info_tambahan'     => $post['info_tambahan'] ?? '',
            'informasi_lain'    => $post['informasi_lain'] ?? '',
            'prioritas'         => $post['prioritas'] ?? '',
            'volume_sampel_kaji_ulang' => $post['volume_sampel_kaji_ulang'] ?? null,

            // PENGIRIM
            'nama_dokter'       => $this->input->post('nama_dokter', TRUE),
            'telp_pengirim'     => $this->input->post('telp_pengirim', TRUE),
            'alamat_pengirim'   => $this->input->post('alamat_pengirim', TRUE),
            'tgl_form'          => $this->input->post('tgl_form', TRUE),

            // LOKASI & SPESIMEN
            'lokasi_pengambilan' => $lokasi_pengambilan_str,
            'jenis_spesimen'     => $jenis_spesimen_str,
            'lokasi_lainnya'     => $lokasi_lainnya,
            'spesimen_lainnya'   => $spesimen_lainnya,

            // PETUGAS
            'petugas_pendaftaran' => $post['petugas_pendaftaran'] ?? '',
            'petugas_pengambil'   => $post['petugas_pengambil'] ?? '',
            'petugas_verifikasi'  => $post['petugas_verifikasi'] ?? '',
            'petugas_validasi'    => $post['petugas_validasi'] ?? '',

            // KELAYAKAN
            'kelayakan'          => $post['kelayakan'] ?? '',
            'alasan_tidak_layak' => isset($post['alasan_tidak_layak']) ? implode(', ', $post['alasan_tidak_layak']) : '',

            // PEMBAYARAN
            'jumlah_biaya'       => $post['jumlah_biaya'] ?? 0,
            'metode_pembayaran'  => ($post['metode_pembayaran'] == 'Lain-lain')
                ? ($post['metode_lainnya'] ?? 'Lain-lain')
                : ($post['metode_pembayaran'] ?? ''),
            'no_register'        => $post['no_register'] ?? null,
        ];

        // === Jalankan update ke tabel form_permintaan_klinik ===
        $this->db->where('id', $id);
        $this->db->update('form_permintaan_klinik', $data_update);

        // === Jalankan update ke tabel pembayaran untuk sinkronisasi nominal ===
        if ($no_register_lama) {
            $metode_pembayaran_baru = ($post['metode_pembayaran'] == 'Lain-lain')
                ? ($post['metode_lainnya'] ?? 'Lain-lain')
                : ($post['metode_pembayaran'] ?? '');

            $data_pembayaran_update = [
                'total_biaya'       => $post['jumlah_biaya'] ?? 0,
                'metode_pembayaran' => $metode_pembayaran_baru,
                'no_register'       => $post['no_register'] ?? $no_register_lama
            ];
            $this->db->where('no_register', $no_register_lama);
            $this->db->update('pembayaran', $data_pembayaran_update);
        }

        // === Jalankan update ke tabel pasien ===
        if ($id_pasien_lama) {
            $this->load->model('Klinik/Puskesmas_wilayah_model');
            $alamat = $post['alamat'] ?? '';

            $data_pasien_update = [
                'no_rm'             => $post['no_rm'] ?? '',
                'nama_pasien'       => $post['nama_pasien'] ?? '',
                'nik'               => $post['nik'] ?? '',
                'gender'            => $post['gender'] ?? '',
                'tgl_lahir'         => $post['tgl_lahir'] ?? null,
                'umur'              => $post['umur'] ?? '',
                'status_nikah'      => $post['status_nikah'] ?? '',
                'agama'             => $post['agama'] ?? '',
                'pendidikan'        => $post['pendidikan'] ?? '',
                'pekerjaan'         => $post['pekerjaan'] ?? '',
                'no_telp'           => $post['no_telp'] ?? '',
                'alamat'            => $post['alamat'] ?? '',
                'diagnosa'          => $post['diagnosa_klinis'] ?? '',
                'obat'              => $post['obat_dikonsumsi'] ?? '',
            ];

            $this->db->where('id_pasien', $id_pasien_lama);
            $this->db->update('pasien', $data_pasien_update);
        }

        // ========================================
        // 🔄 UPDATE DAFTAR PEMERIKSAAN (SINKRONISASI)
        // ========================================
        // 1. Ambil data detail lama dari database
        $this->db->select('id_jenis');
        $this->db->where('id_form', $id);
        $detail_lama = $this->db->get('form_permintaan_klinik_detail')->result_array();
        $id_jenis_lama = array_column($detail_lama, 'id_jenis');

        // 2. Siapkan data baru dari form POST
        $id_jenis_baru = [];
        $data_baru_parsed = [];

        if (!empty($post['jenis_pemeriksaan'])) {
            foreach ($post['jenis_pemeriksaan'] as $item) {
                list($id_jenis, $nama_jenis, $sub_jenis, $kategori) = explode('|', $item);
                $id_jenis_baru[] = $id_jenis;
                $data_baru_parsed[$id_jenis] = [
                    'id_form'      => $id,
                    'id_kunjungan' => $post['kunjungan_id'] ?? null,
                    'id_jenis'     => $id_jenis,
                    'nama_jenis'   => $nama_jenis,
                    'sub_kategori' => $sub_jenis,
                    'kategori'     => $kategori
                ];
            }
        }

        // 3. Cari yang harus dihapus (Ada di database, tapi di-uncheck di form baru)
        $hapus_id_jenis = array_diff($id_jenis_lama, $id_jenis_baru);
        if (!empty($hapus_id_jenis)) {
            $this->db->where('id_form', $id);
            $this->db->where_in('id_jenis', $hapus_id_jenis);
            $this->db->delete('form_permintaan_klinik_detail');
        }

        // 4. Cari yang harus ditambah (Tidak ada di database, tapi dicentang di form baru)
        $tambah_id_jenis = array_diff($id_jenis_baru, $id_jenis_lama);
        if (!empty($tambah_id_jenis)) {
            foreach ($tambah_id_jenis as $id_baru) {
                $this->db->insert('form_permintaan_klinik_detail', $data_baru_parsed[$id_baru]);
            }
        }

        // Selesaikan Transaksi Database
        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            $this->session->set_flashdata('error', '❌ Gagal memperbarui data.');
        } else {
            // ✅ Pesan sukses
            $this->session->set_flashdata('success', '✅ Data formulir dan pemeriksaan berhasil diperbarui.');
        }

        // Redirect ke halaman detail
        redirect('klinik/form_permintaan_klinik/detail/' . $id);
    }

    // =======================
    // 🧩 FUNGSI GENERATE NOMOR REGISTER
    // =======================
    private function generate_no_register()
    {
        $tahun = date('Y'); // Ambil tahun saat ini
        $prefix = '/LK/' . $tahun;

        // Ambil nomor register terakhir yang mengandung tahun ini
        $this->db->like('no_register', $prefix, 'before');
        $this->db->order_by('id', 'DESC');
        $this->db->limit(1);
        $last = $this->db->get('form_permintaan_klinik')->row();

        $last_number = 1;

        if ($last && preg_match('/^(\d{4})\/LK\/' . $tahun . '$/', $last->no_register, $match)) {
            $last_number = (int)$match[1] + 1;
        } elseif ($last && preg_match('/^(\d{1,4})\/LK\/' . $tahun . '$/', $last->no_register, $match)) {
            // fallback jika format lama tidak 4 digit penuh
            $last_number = (int)$match[1] + 1;
        }

        // Format jadi 4 digit (misal: 0001)
        $new_number = str_pad($last_number, 4, '0', STR_PAD_LEFT);

        // Hasil akhir: 0001/LK/2025
        return $new_number . '/LK/' . $tahun;
    }


    // =======================
    // 🗑️ HAPUS DATA FORM
    // =======================
    public function delete($id)
    {
        // Hanya user modul Klinik yang boleh mengubah data klinik
        if (current_module() !== 'klinik') {
            deny_cross_module();
        }

        // Ambil data form_permintaan_klinik berdasarkan ID
        $form = $this->Form_permintaan_klinik_model->get_by_id($id);

        if (!$form) {
            $this->session->set_flashdata('error', 'Data tidak ditemukan.');
            redirect('form_permintaan_klinik');
        }

        // 1. Hapus detail pemeriksaan terkait
        $this->db->delete('form_permintaan_klinik_detail', ['id_form' => $id]);

        // 2. Hapus pembayaran berdasarkan no_register
        if (!empty($no_register)) {
            $this->db->delete('pembayaran', ['no_register' => $no_register]);
        }

        // 3. Hapus form asli
        $this->db->delete('form_permintaan_klinik', ['id' => $id]);

        $this->session->set_flashdata('success', 'Data berhasil dihapus.');
        redirect('klinik/form_permintaan_klinik');
    }


    // =======================
    // 🔍 DETAIL FORM PERMINTAAN
    // =======================
    public function detail($id)
    {
        // Ambil data utama form (lengkap dengan relasi pasien)
        $data['form'] = $this->Form_permintaan_klinik_model->get_by_id($id);
        if (!$data['form']) show_404();

        $data['title'] = 'Detail Formulir Permintaan Pemeriksaan';

        $data['petugas_verifikasi'] = $data['form']->petugas_verifikasi ?? '-';
        $data['petugas_validasi']  = $data['form']->petugas_validasi ?? '-';

        // Detail pemeriksaan
        $data['detail'] = $this->Form_permintaan_klinik_model->get_detail_by_form($id);

        // 🔹 Tambahan: ambil data pembayaran berdasarkan form
        $data['pembayaran'] = (object)[
            'metode_pembayaran' => $data['form']->metode_pembayaran ?? '-',
            'jumlah_biaya'      => $data['form']->jumlah_biaya ?? 0,
        ];

        // 🔹 (Opsional) Ambil juga data kelayakan jika kamu ingin validasi tambahan
        $data['kelayakan'] = [
            'status' => $data['form']->kelayakan ?? '-',
            'alasan' => $data['form']->alasan_tidak_layak ?? '-'
        ];



        // Muat tampilan
        $this->load->view('klinik/layout/header', $data);
        $this->load->view('klinik/layout/sidebar');
        $this->load->view('klinik/form_permintaan_klinik/detail', $data);
        $this->load->view('klinik/layout/footer');
    }


    // =======================
    // 🔍 PENCARIAN PASIEN REKAM MEDIS
    // =======================
    public function search_pasien_rm()
    {
        $keyword = $this->input->get('keyword');
        if (empty($keyword) || strlen($keyword) < 2) {
            echo json_encode([]);
            return;
        }

        $this->db->group_start();
        $this->db->like('nik', $keyword);
        $this->db->or_like('nama_pasien', $keyword);
        $this->db->or_like('no_rm', $keyword);
        $this->db->group_end();

        $this->db->limit(10);
        $query = $this->db->get('pasien');

        echo json_encode($query->result());
    }

    public function get_kunjungan_data()
    {
        $tahun = date('Y'); // Tahun aktif
        $query = $this->db->query("
        SELECT 
            MONTH(tgl_form) AS bulan_angka,
            COUNT(*) AS jumlah
        FROM form_permintaan_klinik
        WHERE YEAR(tgl_form) = '$tahun'
        GROUP BY bulan_angka
        ORDER BY bulan_angka ASC
    ");

        $result = $query->result();

        // Label bulan Indonesia
        $bulan_label = [
            '01' => 'Januari',
            '02' => 'Februari',
            '03' => 'Maret',
            '04' => 'April',
            '05' => 'Mei',
            '06' => 'Juni',
            '07' => 'Juli',
            '08' => 'Agustus',
            '09' => 'September',
            '10' => 'Oktober',
            '11' => 'November',
            '12' => 'Desember'
        ];

        // Siapkan array dengan nilai default 0
        $data_kunjungan = array_fill_keys(array_keys($bulan_label), 0);
        foreach ($result as $row) {
            $bulan = str_pad($row->bulan_angka, 2, '0', STR_PAD_LEFT);
            $data_kunjungan[$bulan] = (int)$row->jumlah;
        }

        echo json_encode([
            'tahun'  => $tahun,
            'labels' => array_values($bulan_label),
            'data'   => array_values($data_kunjungan)
        ]);
    }


    public function print($id)
    {
        $this->load->library('Pdf');
        $this->load->model('Klinik/Form_permintaan_klinik_model');

        $data['form'] = $this->Form_permintaan_klinik_model->get_by_id($id);
        $data['detail'] = $this->Form_permintaan_klinik_model->get_detail_by_form($id);

        // Tambahkan data kelayakan dan pembayaran
        $data['kelayakan'] = [
            'status' => $data['form']->kelayakan ?? '-',
            'alasan' => $data['form']->alasan_tidak_layak ?? '-'
        ];
        $data['pembayaran'] = (object)[
            'metode_pembayaran' => $data['form']->metode_pembayaran ?? '-',
            'jumlah_biaya' => $data['form']->jumlah_biaya ?? 0
        ];

        // Load tampilan ke PDF
        $this->pdf->generate_view('klinik/form_permintaan_klinik/print', $data, 'Formulir_Permintaan.pdf', 'I', true);
    }
}
<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Uji_klinik extends MY_KlinikController
{

    public function __construct()
    {
        parent::__construct();
        $this->require_role(['admin', 'pemeriksa sampel']);
        $this->load->model('Klinik/Uji_klinik_model');
        $this->load->model('Klinik/Form_permintaan_klinik_model');
        $this->load->database();

        $this->load->dbforge();
        if (!$this->db->field_exists('id_kunjungan', 'form_pengambilan_klinik')) {
            $this->dbforge->add_column('form_pengambilan_klinik', [
                'id_kunjungan' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => TRUE, 'null' => TRUE]
            ]);
        }
    }

    // Halaman utama
    public function index()
    {
        $data['title'] = 'Uji Laboratorium Klinik';

        $page = $this->input->get('page') ?: 1;
        $limit = $this->input->get('limit') ?: 10;
        // Pastikan limit hanya boleh 10, 20, atau 50
        if (!in_array($limit, [10, 20, 30])) {
            $limit = 10;
        }
        $offset = ($page - 1) * $limit;
        $search = $this->input->get('search') ?: '';

        $total = $this->Uji_klinik_model->get_all_uji_klinik_count($search);
        $formulir = $this->Uji_klinik_model->get_all_uji_klinik_paginated($limit, $offset, $search);

        $data['formulir'] = $formulir;
        $data['total'] = $total;
        $data['page'] = $page;
        $data['limit'] = $limit;
        $data['search'] = $search;

        $this->load->view('klinik/layout/header', $data);
        $this->load->view('klinik/layout/sidebar');
        $this->load->view('klinik/uji_klinik/index', $data);
        $this->load->view('klinik/layout/footer');
    }

    // Form input hasil berdasarkan ID form
    public function input($id_form, $is_edit = false)
    {
        $data['title'] = $is_edit ? 'Edit Hasil Pemeriksaan' : 'Input Hasil Pemeriksaan';
        $data['is_edit'] = $is_edit;
        $data['form'] = $this->Form_permintaan_klinik_model->get_by_id($id_form);

        if (!$data['form']) show_404();

        // Ambil detail pemeriksaan + join ke tabel jenis_pemeriksaan
        $this->db->select('d.*, j.satuan, j.nilai_rujukan, j.metode');
        $this->db->from('form_permintaan_klinik_detail d');
        $this->db->join('jenis_pemeriksaan j', 'd.id_jenis = j.id_jenis', 'left');
        $this->db->where('d.id_form', $id_form);
        $data['detail'] = $this->db->get()->result();

        // Ambil ID pasien dari form yang sudah menyertakan data pasien via join
        $data['id_pasien'] = $data['form']->id_pasien ?? null;

        // Ambil data pengambilan jika sudah ada
        $data['pengambilan'] = $this->db->get_where('form_pengambilan_klinik', ['id_form' => $id_form])->row();

        // ✅ Tambahan: ambil daftar petugas & penanggung teknis & verifikator
        $data['daftar_petugas'] = $this->db->get('petugas_sampel_klinik')->result();
        $data['daftar_petugas_hasil'] = $this->db->get('petugas_sampel_klinik')->result();
        $data['daftar_penanggung_teknis'] = $this->db->get('penanggung_teknis_klinik')->result();
        $data['daftar_verifikator'] = $this->db->get('verifikator_klinik')->result();

        $this->load->view('klinik/layout/header', $data);
        $this->load->view('klinik/layout/sidebar');
        $this->load->view('klinik/uji_klinik/form', $data);
        $this->load->view('klinik/layout/footer');
    }

    // Method edit alias ke input
    public function edit($id_form)
    {
        $this->input($id_form, true);
    }

    // Simpan hasil pemeriksaan
    public function simpan_hasil()
    {
        $id_form = $this->input->post('id_form');
        $hasil = $this->input->post('hasil');

        // Simpan hasil per detail
        foreach ($hasil as $id_detail => $nilai) {
            $this->db->where('id_detail', $id_detail);
            $this->db->update('form_permintaan_klinik_detail', [
                'hasil' => $nilai,
                'tanggal_input' => date('Y-m-d H:i:s')
            ]);
        }

        // Update status kelayakan sampel dan volume kaji ulang pada formulir asal
        $kelayakan = $this->input->post('kelayakan');
        $alasan_arr = $this->input->post('alasan_tidak_layak');
        $alasan_tidak_layak = !empty($alasan_arr) ? implode(', ', $alasan_arr) : '';
        $volume_sampel = $this->input->post('volume_sampel_kaji_ulang');

        $this->db->where('id', $id_form);
        $this->db->update('form_permintaan_klinik', [
            'kelayakan' => $kelayakan,
            'alasan_tidak_layak' => ($kelayakan === 'Tidak Layak') ? $alasan_tidak_layak : '',
            'volume_sampel_kaji_ulang' => $volume_sampel
        ]);

        // Ambil petugas
        $id_petugas = $this->input->post('id_petugas');
        $petugas = $this->db->get_where('petugas_sampel_klinik', ['id_petugas' => $id_petugas])->row();

        // Ambil id_kunjungan dari form_permintaan_klinik
        $form_klinik = $this->db->get_where('form_permintaan_klinik', ['id' => $id_form])->row();
        $id_kunjungan = $form_klinik ? $form_klinik->kunjungan_id : null;

        $pengambilan = [
            'id_form' => $id_form,
            'id_kunjungan' => $id_kunjungan,
            'id_petugas' => $id_petugas,
            'petugas_pengambilan' => $petugas ? $petugas->nama_petugas : null,
            'tgl_jam_pengambilan' => $this->input->post('tgl_jam_pengambilan'),
            'tgl_jam_pemeriksaan' => $this->input->post('tgl_jam_pemeriksaan'),
            'tgl_jam_selesai' => $this->input->post('tgl_jam_selesai'),
            'tgl_jam_pelaporan' => $this->input->post('tgl_jam_pelaporan'),
            'note' => $this->input->post('note'),
            'petugas_hasil' => $this->input->post('petugas_hasil'),
            'verifikator_hasil' => $this->input->post('verifikator'),
            'penanggung_jawab_teknis' => $this->input->post('penanggung_jawab_teknis'),
            'sip_penanggung' => $this->input->post('sip_penanggung')
        ];

        // Cek apakah data pengambilan untuk id_form ini sudah ada (Mode Edit)
        $existing = $this->db->get_where('form_pengambilan_klinik', ['id_form' => $id_form])->row();
        if ($existing) {
            $this->db->where('id_form', $id_form);
            $this->db->update('form_pengambilan_klinik', $pengambilan);
            $this->session->set_flashdata('success', '✅ Hasil pemeriksaan berhasil diupdate!');
        } else {
            $this->db->insert('form_pengambilan_klinik', $pengambilan);
            $this->session->set_flashdata('success', '✅ Hasil pemeriksaan berhasil disimpan!');
        }

        redirect('klinik/uji_klinik');
    }
}

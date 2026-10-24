<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Laporan_uji_klinik extends MY_KlinikController {

    public function __construct() {
        parent::__construct();
        $this->require_role(['admin', 'petugas pendaftaran', 'pemeriksa sampel']);
        $this->load->model('Klinik/Form_permintaan_klinik_model');
        $this->load->database();
    }

    // Halaman utama laporan
    public function index() {
        $data['title'] = 'Laporan Uji Klinik';
        $this->load->model('Klinik/Laporan_uji_klinik_model');

        $page = $this->input->get('page') ?: 1;
        $limit = $this->input->get('limit') ?: 10;
        // Pastikan limit hanya boleh 10, 20, atau 50
        if (!in_array($limit, [10, 20, 50])) {
            $limit = 10;
        }
        $offset = ($page - 1) * $limit;
        $search = $this->input->get('search') ?: '';

        $total = $this->Laporan_uji_klinik_model->get_laporan_count($search);
        $laporan = $this->Laporan_uji_klinik_model->get_laporan_paginated($limit, $offset, $search);

        $data['laporan'] = $laporan;
        $data['total'] = $total;
        $data['page'] = $page;
        $data['limit'] = $limit;
        $data['search'] = $search;

        $this->load->view('klinik/layout/header', $data);
        $this->load->view('klinik/layout/sidebar');
        $this->load->view('klinik/laporan_uji_klinik/index', $data);
        $this->load->view('klinik/layout/footer');
    }

    // Detail laporan hasil uji
    public function detail($id_form)
{
    $data['title'] = 'Detail Laporan Uji Klinik';

    // Ambil data form
    $data['form'] = $this->Form_permintaan_klinik_model->get_by_id($id_form);

    // Ambil hasil pemeriksaan
    $this->db->select('
    d.*,
    COALESCE(j.satuan, d.satuan) AS satuan,
    COALESCE(j.nilai_rujukan, d.nilai_rujukan) AS nilai_rujukan,
    COALESCE(j.metode, d.metode) AS metode
');
    $this->db->from('form_permintaan_klinik_detail d');
    $this->db->join('jenis_pemeriksaan j', 'd.id_jenis = j.id_jenis', 'left');
    $this->db->where('d.id_form', $id_form);
    $data['detail'] = $this->db->get()->result();

    // Ambil data petugas pengambilan (join dengan tabel petugas_sampel_klinik)
    $data['pengambilan'] = $this->db
        ->select('pk.*, ps.nama_petugas')
        ->from('form_pengambilan_klinik pk')
        ->join('petugas_sampel_klinik ps', 'pk.id_petugas = ps.id_petugas', 'left')
        ->where('pk.id_form', $id_form)
        ->get()
        ->row();

    // Kirim juga ID form agar tidak undefined di view
    $data['id_form'] = $id_form;

    $this->load->view('klinik/layout/header', $data);
    $this->load->view('klinik/layout/sidebar');
    $this->load->view('klinik/laporan_uji_klinik/detail', $data);
    $this->load->view('klinik/layout/footer');
}


    public function export_pdf($id_form)
{

    $form = $this->Form_permintaan_klinik_model->get_by_id($id_form);
    $pengambilan = $this->db->select('pk.*, ps.nama_petugas')
                        ->from('form_pengambilan_klinik pk')
                        ->join('petugas_sampel_klinik ps', 'pk.id_petugas = ps.id_petugas', 'left')
                        ->where('pk.id_form', $id_form)
                        ->get()
                        ->row();


    $detail = $this->db->select('
        d.*,
        j.nama_jenis,
        j.satuan,
        j.nilai_rujukan,
        j.metode,
        j.kategori AS kategori,
        j.sub_kategori AS sub_kategori
    ')
    ->from('form_permintaan_klinik_detail d')
    ->join('jenis_pemeriksaan j', 'd.id_jenis = j.id_jenis', 'left')
    ->where('d.id_form', $id_form)
    ->order_by('j.kategori', 'ASC')
    ->order_by('j.sub_kategori', 'ASC')
    ->order_by('j.nama_jenis', 'ASC')
    ->get()
    ->result();


    $data = [
        'form' => $form,
        'pengambilan' => $pengambilan,
        'detail' => $detail
    ];

    $this->load->library('Pdf');
    $filename = 'laporan_klinik_' . str_replace('/', '_', $form->no_register) . '.pdf';
    $this->pdf->generate_view('klinik/laporan_uji_klinik/print', $data, $filename, 'I');
}


}
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Uji_rekam_klinik extends MY_KlinikController
{

    public function __construct()
    {
        parent::__construct();
        $this->require_role(['admin', 'pemeriksa sampel', 'petugas rm']);
        $this->load->database();
        $this->load->library('session');
    }

    public function index()
    {
        $data['title'] = 'Data Pasien - Riwayat Kunjungan Klinik';
        
        // Ambil input pencarian dengan pengamanan XSS
        $search = $this->input->get('search', TRUE);

        $page = $this->input->get('page') ?: 1;
        $limit = $this->input->get('limit') ?: 10;
        // Pastikan limit hanya boleh 10, 20, atau 50
        if (!in_array($limit, [10, 20, 50])) {
            $limit = 10;
        }
        $offset = ($page - 1) * $limit;

        // Hitung total data pasien unik yang memiliki riwayat uji klinik
        $this->db->select('COUNT(DISTINCT p.id_pasien) as total');
        $this->db->from('pasien p');
        $this->db->join('form_permintaan_klinik f', 'f.id_pasien = p.id_pasien', 'inner');
        
        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('p.nama_pasien', $search);
            $this->db->or_like('p.nik', $search);
            $this->db->group_end();
        }
        $total_query = $this->db->get()->row();
        $total = $total_query ? $total_query->total : 0;

        // Ambil daftar pasien unik secara terpaginasi
        $this->db->select('p.id_pasien, p.no_rm, p.nama_pasien, p.nik, p.gender, COUNT(f.id) as total_uji');
        $this->db->from('pasien p');
        $this->db->join('form_permintaan_klinik f', 'f.id_pasien = p.id_pasien', 'inner');
        
        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('p.nama_pasien', $search);
            $this->db->or_like('p.nik', $search);
            $this->db->group_end();
        }

        $this->db->group_by(['p.id_pasien', 'p.no_rm', 'p.nama_pasien', 'p.nik', 'p.gender']);
        $this->db->order_by('p.nama_pasien', 'ASC');
        $this->db->limit($limit, $offset);
        
        $data['pasien'] = $this->db->get()->result();
        $data['total'] = $total;
        $data['page'] = $page;
        $data['limit'] = $limit;
        $data['search'] = $search;

        $this->load->view('klinik/layout/header', $data);
        $this->load->view('klinik/layout/sidebar');
        $this->load->view('klinik/uji_rekam_klinik/index', $data);
        $this->load->view('klinik/layout/footer');
    }

    public function detail($id_pasien)
    {
        $data['title'] = 'Riwayat Uji Klinik Pasien';

        // Ambil data identitas pasien
        $this->db->where('id_pasien', $id_pasien);
        $data['pasien'] = $this->db->get('pasien')->row();

        if (!$data['pasien']) {
            $this->session->set_flashdata('error', 'Data pasien tidak ditemukan.');
            redirect('klinik/uji_rekam_klinik');
        }

        $bulan = date('m');
        $tahun = date('Y');
        $data['bulan'] = $bulan;
        $data['tahun'] = $tahun;

        // Ambil seluruh riwayat formulir permintaan klinik untuk pasien ini
        $this->db->select('f.*, 
            (SELECT COUNT(*) FROM form_permintaan_klinik_detail fd WHERE fd.id_form = f.id AND fd.hasil IS NOT NULL AND fd.hasil != "") as hasil_diisi,
            (SELECT COUNT(*) FROM form_permintaan_klinik_detail fd WHERE fd.id_form = f.id) as total_detail');
        $this->db->from('form_permintaan_klinik f');
        $this->db->where('f.id_pasien', $id_pasien);
        $this->db->order_by('f.tgl_form', 'DESC');
        
        $visits = $this->db->get()->result();
        $data['riwayat'] = $visits;
        $data['visits'] = $visits;

        // Ambil data detail pemeriksaan untuk form-form ini
        $results = [];
        $visit_ids = [];
        foreach ($visits as $v) {
            $visit_ids[] = $v->id;
        }

        if (!empty($visit_ids)) {
            $this->db->select('id_form, nama_jenis, kategori, hasil, satuan, nilai_rujukan');
            $this->db->from('form_permintaan_klinik_detail');
            $this->db->where_in('id_form', $visit_ids);
            $results_raw = $this->db->get()->result();
            foreach ($results_raw as $r) {
                $cat = strtolower(trim($r->kategori));
                if ($cat === 'urinalisis' || $cat === 'urinalisa') {
                    $cat = 'urinalisa';
                }
                $key = $cat . '_' . strtolower(trim($r->nama_jenis));
                $results[$r->id_form][$key] = [
                    'hasil' => $r->hasil,
                    'satuan' => $r->satuan,
                    'nilai_rujukan' => $r->nilai_rujukan
                ];
            }
        }
        $data['results'] = $results;

        // Ambil daftar parameter yang terdaftar untuk pasien ini di kunjungan bulan terpilih secara dinamis
        $params = [];
        if (!empty($visit_ids)) {
            $this->db->select('d.nama_jenis, d.kategori, MAX(COALESCE(j.nilai_rujukan, d.nilai_rujukan)) as rujukan, MAX(COALESCE(j.satuan, d.satuan)) as satuan');
            $this->db->from('form_permintaan_klinik_detail d');
            $this->db->join('jenis_pemeriksaan j', 'd.id_jenis = j.id_jenis', 'left');
            $this->db->where_in('d.id_form', $visit_ids);
            $this->db->group_by(['d.nama_jenis', 'd.kategori']);
            $this->db->order_by('d.kategori', 'ASC');
            $this->db->order_by('d.nama_jenis', 'ASC');
            
            $params_raw = $this->db->get()->result();
            foreach ($params_raw as $p) {
                if ($p->nama_jenis !== null && $p->nama_jenis !== '') {
                    $params[] = [
                        'name' => $p->nama_jenis,
                        'category' => $p->kategori,
                        'unit' => $p->satuan,
                        'rujukan' => $p->rujukan
                    ];
                }
            }
        }
        $data['params'] = $params;

        $this->load->view('klinik/layout/header', $data);
        $this->load->view('klinik/layout/sidebar');
        $this->load->view('klinik/uji_rekam_klinik/detail', $data);
        $this->load->view('klinik/layout/footer');
    }
}

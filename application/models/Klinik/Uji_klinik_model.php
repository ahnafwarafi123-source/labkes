<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Uji_klinik_model extends CI_Model
{

    // Ambil semua formulir yang BELUM diinput hasilnya (atau ada hasil yang belum terisi)
    public function get_formulir_belum_input()
    {
        $this->db->select('f.id, f.no_register, p.nik, p.nama_pasien, p.gender, f.tgl_form, f.nama_dokter');
        $this->db->from('form_permintaan_klinik f');
        $this->db->join('pasien p', 'p.id_pasien = f.id_pasien', 'left');

        // Gunakan INNER JOIN agar hanya form yang masih memiliki item kosong yang ditampilkan
        $this->db->join('(SELECT id_form 
                          FROM form_permintaan_klinik_detail 
                          WHERE hasil IS NULL OR hasil = "" 
                          GROUP BY id_form) d', 'f.id = d.id_form', 'inner');

        $this->db->order_by('f.id', 'DESC');
        return $this->db->get()->result();
    }

    // Ambil formulir yang BELUM diinput hasilnya UNTUK HARI INI
    public function get_today_formulir_belum_input()
    {
        $this->db->select('f.id, f.no_register, p.nik, p.nama_pasien, p.gender, f.tgl_form, f.nama_dokter');
        $this->db->from('form_permintaan_klinik f');
        $this->db->join('pasien p', 'p.id_pasien = f.id_pasien', 'left');

        // Gunakan INNER JOIN agar hanya form yang masih memiliki item kosong yang ditampilkan
        $this->db->join('(SELECT id_form 
                          FROM form_permintaan_klinik_detail 
                          WHERE hasil IS NULL OR hasil = "" 
                          GROUP BY id_form) d', 'f.id = d.id_form', 'inner');

        $this->db->where('DATE(f.tgl_form)', date('Y-m-d'));
        $this->db->order_by('f.id', 'DESC');
        return $this->db->get()->result();
    }

    // Ambil semua formulir untuk paginasi
    public function get_all_uji_klinik_paginated( $limit, $offset, $search = null)
    {
        $this->db->select('f.id, f.no_register, p.nik, p.nama_pasien, p.gender, f.tgl_form, f.nama_dokter, pk.id_pengambilan_klinik');
        $this->db->from('form_permintaan_klinik f');
        $this->db->join('pasien p', 'p.id_pasien = f.id_pasien', 'left');
        $this->db->join('(SELECT id_form, MAX(id_pengambilan_klinik) as id_pengambilan_klinik FROM form_pengambilan_klinik GROUP BY id_form) pk', 'pk.id_form = f.id', 'left');
        
        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('f.no_register', $search);
            $this->db->or_like('p.nama_pasien', $search);
            $this->db->or_like('p.nik', $search);
            $this->db->or_like('f.nama_dokter', $search);
            $this->db->group_end();
        }
        
        $this->db->order_by('f.id', 'DESC');
        $this->db->limit($limit, $offset);
        return $this->db->get()->result();
    }

    // Hitung total formulir untuk paginasi
    public function get_all_uji_klinik_count($search = null)
    {
        $this->db->from('form_permintaan_klinik f');
        $this->db->join('pasien p', 'p.id_pasien = f.id_pasien', 'left');
        
        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('f.no_register', $search);
            $this->db->or_like('p.nama_pasien', $search);
            $this->db->or_like('p.nik', $search);
            $this->db->or_like('f.nama_dokter', $search);
            $this->db->group_end();
        }
        return $this->db->count_all_results();
    }
}

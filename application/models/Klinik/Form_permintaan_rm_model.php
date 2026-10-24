<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Form_permintaan_rm_model extends CI_Model
{

    protected $table = 'pasien';

    public function get_all()
    {
        return $this->db->order_by('nik', 'DESC')->get($this->table)->result();
    }

    public function get_today()
    {
        $today = date('Y-m-d');
        $this->db->where("DATE(created_at) = '$today' OR DATE(updated_at) = '$today'");
        return $this->db->order_by('nik', 'DESC')->get($this->table)->result();
    }

    public function get_all_paginated($limit, $offset, $search = null, $tgl_mulai = null)
    {
        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('created_at', $search);
            $this->db->or_like('updated_at', $search);
            $this->db->or_like("DATE_FORMAT(created_at, '%d-%m-%Y')", $search);
            $this->db->or_like("DATE_FORMAT(updated_at, '%d-%m-%Y')", $search);
            $this->db->or_like('no_rm', $search);
            $this->db->or_like('nama_pasien', $search);
            $this->db->or_like('nik', $search);
            $this->db->or_like('no_telp', $search);
            $this->db->or_like('alamat', $search);
            $this->db->or_like('gender', $search);
            $this->db->or_like('tgl_lahir', $search);
            $this->db->or_like('umur', $search);
            $this->db->group_end();
        }

        if (!empty($tgl_mulai)) {
            $this->db->where('DATE(created_at)', $tgl_mulai);
        }

        return $this->db->order_by('id_pasien', 'DESC')->get($this->table, $limit, $offset)->result();
    }

    public function get_all_count($search = null, $tgl_mulai = null)
    {
        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('created_at', $search);
            $this->db->or_like('updated_at', $search);
            $this->db->or_like("DATE_FORMAT(created_at, '%d-%m-%Y')", $search);
            $this->db->or_like("DATE_FORMAT(updated_at, '%d-%m-%Y')", $search);
            $this->db->or_like('no_rm', $search);
            $this->db->or_like('nama_pasien', $search);
            $this->db->or_like('nik', $search);
            $this->db->or_like('no_telp', $search);
            $this->db->or_like('alamat', $search);
            $this->db->or_like('gender', $search);
            $this->db->or_like('tgl_lahir', $search);
            $this->db->or_like('umur', $search);
            $this->db->group_end();
        }

        if (!empty($tgl_mulai)) {
            $this->db->where('DATE(created_at)', $tgl_mulai);
        }

        return $this->db->count_all_results($this->table);
    }

    public function get_patients_visiting_today()
    {
        $today = date('Y-m-d');
        $sql = "SELECT DISTINCT pasien.* 
                FROM pasien 
                LEFT JOIN form_permintaan_klinik ON form_permintaan_klinik.id_pasien = pasien.id_pasien 
                LEFT JOIN kunjungan_rm ON kunjungan_rm.no_rm = pasien.no_rm
                WHERE DATE(pasien.created_at) = ? 
                   OR DATE(pasien.updated_at) = ? 
                   OR DATE(form_permintaan_klinik.tgl_form) = ? 
                   OR DATE(kunjungan_rm.tanggal_kunjungan) = ?";
        return $this->db->query($sql, [$today, $today, $today, $today])->result();
    }

    public function get_by_nik($nik_or_id)
    {
        // Detect if the parameter is a numeric auto-increment ID or a NIK string
        if (is_numeric($nik_or_id) && strlen($nik_or_id) < 10) {
            return $this->db->get_where($this->table, ['id_pasien' => $nik_or_id])->row();
        }
        return $this->db->get_where($this->table, ['nik' => $nik_or_id])->row();
    }

    public function get_by_no_rm($no_rm)
    {
        return $this->db->order_by('nik', 'DESC')->get_where($this->table, ['no_rm' => $no_rm])->row();
    }

    public function get_by_id($id)
    {
        return $this->db->get_where($this->table, ['id_pasien' => $id])->row();
    }

    public function insert($data)
    {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    public function update($nik_or_id, $data)
    {
        if (is_numeric($nik_or_id) && strlen($nik_or_id) < 10) {
            $this->db->where('id_pasien', $nik_or_id);
        } else {
            $this->db->where('nik', $nik_or_id);
        }
        $this->db->update($this->table, $data);
    }

    public function delete($nik_or_id)
    {
        if (is_numeric($nik_or_id) && strlen($nik_or_id) < 10) {
            $this->db->where('id_pasien', $nik_or_id);
        } else {
            $this->db->where('nik', $nik_or_id);
        }
        $this->db->delete($this->table);
    }
    // Fungsi untuk membuat nomor Rekam Medis (RM) otomatis
    public function generate_no_rm()
    {
        $this->db->select_max('no_rm');
        $query = $this->db->get($this->table);
        $result = $query->row();

        $last_no = $result->no_rm;

        if ($last_no && preg_match('/RM-(\d+)/', $last_no, $match)) {
            $last_number = (int) $match[1] + 1;
        } else {
            $last_number = 1;
        }

        return 'RM-' . str_pad($last_number, 4, '0', STR_PAD_LEFT);
    }
}
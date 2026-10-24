<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kunjungan_rm_model extends CI_Model {

    protected $table = 'kunjungan_rm';

    public function get_by_no_rm($no_rm) {
        return $this->db->order_by('tanggal_kunjungan', 'DESC')->get_where($this->table, ['no_rm' => $no_rm])->result();
    }

    public function count_by_no_rm($no_rm) {
        $this->db->where('no_rm', $no_rm);
        $this->db->from($this->table);
        return $this->db->count_all_results();
    }

    public function get_count_all_pasien() {
        // Mengembalikan array assosiatif no_rm => jumlah kunjungan
        $this->db->select('no_rm, COUNT(id) as total');
        $this->db->group_by('no_rm');
        $query = $this->db->get($this->table);
        $result = [];
        foreach ($query->result() as $row) {
            $result[$row->no_rm] = $row->total;
        }
        return $result;
    }

    public function insert($data) {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    public function get_by_id($id) {
        return $this->db->get_where($this->table, ['id' => $id])->row();
    }

    public function delete($id) {
        $this->db->where('id', $id);
        $this->db->delete($this->table);
    }

    public function update($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update($this->table, $data);
    }
}

<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pasien_model extends CI_Model {

    private $table = 'pasien';

    public function get_all() {
    $this->db->select('pasien.*, dokter_pengirim.nama as nama_dokter');
    $this->db->from('pasien');
    $this->db->join('dokter_pengirim', 'dokter_pengirim.id_dokter_pengirim = pasien.id_dokter_pengirim', 'left');
    return $this->db->get()->result();
}

    public function get_by_id($id)
{
    $this->db->select('
        pasien.*,
        dokter_pengirim.nama AS nama_dokter,
        dokter_pengirim.alamat AS alamat_dokter,
        dokter_pengirim.no_telp AS telp_dokter
    ');
    $this->db->from('pasien');
    $this->db->join('dokter_pengirim', 'dokter_pengirim.id_dokter_pengirim = pasien.id_dokter_pengirim', 'left');
    $this->db->where('id_pasien', $id);
    return $this->db->get()->row();
}


    public function get_pasien_belum_ambil_sampel() {
    $subquery = $this->db->select('id_pasien')->get_compiled_select('ambil_sampel');
    return $this->db->where("id_pasien NOT IN ($subquery)", null, false)->get('pasien')->result();
}


    public function get_last_register_today($today) {
    $this->db->like('no_register', 'PSN.' . date('Ymd'));
    $this->db->where('DATE(created_at)', $today);
    $this->db->order_by('no_register', 'DESC');
    $this->db->limit(1);
    $query = $this->db->get('pasien');
    return $query->row(); // mengembalikan 1 baris terakhir
    }

    public function search_by_nik($keyword) {
        $this->db->like('nik', $keyword);
        return $this->db->get($this->table)->result();
    }

    public function count_today($tanggal) {
        $this->db->like('created_at', $tanggal); // tanggal format: 20250916
        return $this->db->count_all_results('pasien');
    }

    public function insert($data) {
        return $this->db->insert($this->table, $data);
    }

    public function update($id, $data) {
        return $this->db->where('id_pasien', $id)->update($this->table, $data);
    }

    public function delete($id) {
        return $this->db->where('id_pasien', $id)->delete($this->table);
    }
}

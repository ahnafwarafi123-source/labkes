<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Petugas_verifikasi_model extends CI_Model {

    private $table = 'petugas_verifikasi';

    public function get_default()
    {
        return $this->db->order_by('id_verifikasi', 'ASC')
                        ->get('petugas_verifikasi')
                        ->result();
    }

    public function get_all()
    {
        return $this->db->order_by('id_verifikasi', 'DESC')->get($this->table)->result();
    }

    public function get_by_id($id)
    {
        return $this->db->where('id_verifikasi', $id)->get($this->table)->row();
    }

    public function insert($data)
    {
        $this->db->insert($this->table, $data);
    }

    public function update($id, $data)
    {
        $this->db->where('id_verifikasi', $id)->update($this->table, $data);
    }

    public function delete($id)
    {
        $this->db->where('id_verifikasi', $id)->delete($this->table);
    }
}

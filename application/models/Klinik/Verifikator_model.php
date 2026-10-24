<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Verifikator_model extends CI_Model {

    private $table = 'verifikator_klinik';

    public function get_all()
    {
        return $this->db->get($this->table)->result();
    }

    public function get_by_id($id)
    {
        return $this->db->where('id_petugas', $id)->get($this->table)->row();
    }

    public function insert($data)
    {
        $this->db->insert($this->table, $data);
    }

    public function update($id, $data)
    {
        $this->db->where('id_petugas', $id)->update($this->table, $data);
    }

    public function delete($id)
    {
        $this->db->where('id_petugas', $id)->delete($this->table);
    }
}

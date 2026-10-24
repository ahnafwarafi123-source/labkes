<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Petugas_sampel_model extends CI_Model {

    private $table = 'petugas_sampel_klinik';

    public function get_all()
    {
        return $this->db->get($this->table)->result();
    }

    public function get_by_id($id)
    {
        return $this->db->get_where($this->table, ['id_petugas' => $id])->row();
    }

    public function insert($data)
    {
        return $this->db->insert($this->table, $data);
    }

    public function update($id, $data)
    {
        $this->db->where('id_petugas', $id);
        return $this->db->update($this->table, $data);
    }

    public function delete($id)
    {
        $this->db->where('id_petugas', $id);
        return $this->db->delete($this->table);
    }
}

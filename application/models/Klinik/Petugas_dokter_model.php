<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Petugas_dokter_model extends CI_Model {

    public function get_all()
    {
        return $this->db->get('petugas_dokter')->result();
    }

    public function insert($data)
    {
        $this->db->insert('petugas_dokter', $data);
    }

    public function get_by_id($id)
    {
        return $this->db->get_where('petugas_dokter', ['id_dokter' => $id])->row();
    }

    public function update($id, $data)
    {
        $this->db->where('id_dokter', $id);
        $this->db->update('petugas_dokter', $data);
    }

    public function delete($id)
    {
        $this->db->where('id_dokter', $id);
        $this->db->delete('petugas_dokter');
    }
}

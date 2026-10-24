<?php
class Petugas_validasi_model extends CI_Model {

    public function get_all()
    {
        return $this->db->get('petugas_validasi')->result();
    }

    public function get_default()
    {
        return $this->db->order_by('id_validasi', 'ASC')
                        ->get('petugas_validasi')
                        ->result();
    }

    public function get_by_id($id)
    {
        return $this->db->get_where('petugas_validasi', ['id_validasi' => $id])->row();
    }

    public function insert($data)
    {
        return $this->db->insert('petugas_validasi', $data);
    }

    public function update($id, $data)
    {
        return $this->db->where('id_validasi', $id)
                        ->update('petugas_validasi', $data);
    }

    public function delete($id)
    {
        return $this->db->delete('petugas_validasi', ['id_validasi' => $id]);
    }
}

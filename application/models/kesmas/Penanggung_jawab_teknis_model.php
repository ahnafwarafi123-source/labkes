<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Penanggung_jawab_teknis_model extends CI_Model
{
    private $table = 'kesmas_penanggung_jawab';

    public function get_all(): array
    {
        $this->db->where('is_active', 1)->order_by('nama','ASC');
        return $this->db->get($this->table)->result_array();
    }

    public function list_penanggung_jawab_teknis(): array
    {
        return $this->get_all();
    }

    public function add_penanggung_jawab_teknis($data)
    {
        return $this->db->insert($this->table, $data);
    }

    public function get_penanggung_jawab_teknis_by_id(int $id)
    {
        return $this->db->get_where($this->table, array('id_penanggung_jawab' => $id))->row_array();
    }

    public function update_penanggung_jawab_teknis(int $id, array $data)
    {
        $this->db->where('id_penanggung_jawab', $id);
        return $this->db->update($this->table, $data);
    }

    public function delete_penanggung_jawab_teknis(int $id)
    {
        $this->db->where('id_penanggung_jawab', $id);
        return $this->db->update($this->table, ['is_active' => 0]);
    }

    public function get_penanggung_jawab_teknis_by_id_simple(int $id): ?object
    {
        $result = $this->db->select('nama, nip')->get_where($this->table, ['id_penanggung_jawab' => $id])->row();
        return $result;
    }
}

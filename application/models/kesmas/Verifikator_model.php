<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Verifikator_model extends CI_Model
{
    private $table = 'kesmas_penanggung_jawab';

    public function list_verifikator(): array
    {
        $this->db->where('is_active', 1)->order_by('nama','ASC');
        return $this->db->get($this->table)->result_array();
    }

    public function add_verifikator($data)
    {
        return $this->db->insert($this->table, $data);
    }

    public function get_verifikator_by_id(int $id)
    {
        return $this->db->get_where($this->table, array('id_penanggung_jawab' => $id))->row_array();
    }

    public function update_verifikator(int $id, array $data)
    {
        $this->db->where('id_penanggung_jawab', $id);
        return $this->db->update($this->table, $data);
    }

    public function delete_verifikator(int $id)
    {
        // Instead of deleting, we can just deactivate
        $this->db->where('id_penanggung_jawab', $id);
        return $this->db->update($this->table, ['is_active' => 0]);
    }

    public function get_verifikator_name_by_id(int $verifikator_id): ?string
    {
        $result = $this->db->select('nama')->get_where($this->table, ['id_penanggung_jawab' => $verifikator_id])->row_array();
        return $result['nama'] ?? null;
    }
}

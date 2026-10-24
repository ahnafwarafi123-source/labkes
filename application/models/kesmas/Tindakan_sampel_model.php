<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tindakan_sampel_model extends CI_Model
{
    // Tabel kesmas_tindakan_sampel sudah dihapus. 
    // Kolom tindakan_sampel di tabel kesmas_sampel sekarang berupa VARCHAR murni.
    
    private $static_options = [
        ['id' => 'Langsung', 'nama' => 'Langsung'],
        ['id' => 'Rujuk', 'nama' => 'Rujuk'],
        ['id' => 'Kiriman', 'nama' => 'Kiriman']
    ];

    public function list_tindakan_sampel(): array
    {
        return $this->static_options;
    }

    public function add_tindakan_sampel($data)
    {
        return false;
    }

    public function get_tindakan_sampel_by_id(int $id)
    {
        return null;
    }

    public function update_tindakan_sampel(int $id, array $data)
    {
        return false;
    }

    public function delete_tindakan_sampel(int $id)
    {
        return false;
    }

    public function get_tindakan_sampel_name_by_id($id): ?string
    {
        return (string)$id ?: null;
    }
}

<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Model master wilayah puskesmas untuk modul Kesmas.
 * Tabel terpisah dari klinik (kesmas_puskesmas_wilayah) sesuai permintaan pihak kesmas.
 */
class Puskesmas_wilayah_model_kesmas extends CI_Model
{
    private $table = 'kesmas_puskesmas_wilayah';

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->create_table_if_not_exists();
    }

    private function create_table_if_not_exists()
    {
        $this->load->dbforge();
        if ($this->db->table_exists($this->table)) {
            return;
        }

        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->add_field([
            'id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => TRUE, 'auto_increment' => TRUE],
            'nama_puskesmas' => ['type' => 'VARCHAR', 'constraint' => '255'],
            'kecamatan' => ['type' => 'VARCHAR', 'constraint' => '255', 'null' => TRUE],
            'kelurahan_list' => ['type' => 'TEXT', 'null' => TRUE],
        ]);
        $this->dbforge->create_table($this->table, TRUE);
    }

    public function get_all()
    {
        $this->db->order_by('nama_puskesmas', 'ASC');
        return $this->db->get($this->table)->result();
    }

    public function get_by_id($id)
    {
        return $this->db->get_where($this->table, ['id' => $id])->row();
    }

    public function insert($data)
    {
        return $this->db->insert($this->table, $data);
    }

    public function update($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update($this->table, $data);
    }

    public function delete($id)
    {
        $this->db->where('id', $id);
        return $this->db->delete($this->table);
    }

    /**
     * Daftar kecamatan => daftar kelurahan (unik) untuk dropdown form pendaftaran.
     */
    public function list_wilayah(): array
    {
        $rows = $this->db->select('kecamatan, kelurahan_list')->get($this->table)->result();
        $out = [];
        foreach ($rows as $r) {
            $kec = trim((string) $r->kecamatan);
            if ($kec === '') {
                continue;
            }
            if (!isset($out[$kec])) {
                $out[$kec] = [];
            }
            foreach (array_map('trim', explode(',', (string) $r->kelurahan_list)) as $kel) {
                if ($kel !== '' && !in_array($kel, $out[$kec], true)) {
                    $out[$kec][] = $kel;
                }
            }
        }
        ksort($out);
        return $out;
    }
}

<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Puskesmas_wilayah_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->create_table_if_not_exists();
    }

    private function create_table_if_not_exists()
    {
        $this->load->dbforge();
        if (!$this->db->table_exists('puskesmas_wilayah')) {
            $fields = [
                'id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => TRUE, 'auto_increment' => TRUE],
                'nama_puskesmas' => ['type' => 'VARCHAR', 'constraint' => '255'],
                'kecamatan' => ['type' => 'VARCHAR', 'constraint' => '255', 'null' => TRUE],
                'kelurahan_list' => ['type' => 'TEXT', 'null' => TRUE],
            ];
            $this->dbforge->add_key('id', TRUE);
            $this->dbforge->add_field($fields);
            $this->dbforge->create_table('puskesmas_wilayah', TRUE);

            // Seed data from CSV
            $seed_data = [
                ['nama_puskesmas' => 'Puskesmas Taman Sari', 'kecamatan' => 'Taman Sari', 'kelurahan_list' => 'Batin Tikal, Taman Sari, Kejaksaan, Gedung Nasional, Rawasari'],
                ['nama_puskesmas' => 'Puskesmas Melintang', 'kecamatan' => 'Rangkui', 'kelurahan_list' => 'Melintang, Pintu Air, Asam, Keramat, Gajah Mada, Parit Lalang, Bintang, Masjid Jamik'],
                ['nama_puskesmas' => 'Puskesmas Pasir Putih', 'kecamatan' => 'Bukit Intan', 'kelurahan_list' => 'Pasir Putih, Bacang, Semabung Baru, Sriwijaya'],
                ['nama_puskesmas' => 'Puskesmas Air Itam', 'kecamatan' => 'Bukit Intan', 'kelurahan_list' => 'Air Itam, Temberan, Sinar Bulan'],
                ['nama_puskesmas' => 'Puskesmas Girimaya', 'kecamatan' => 'Girimaya', 'kelurahan_list' => 'Bukit Besar, Sriwijaya, Semabung Atas, Bukit Intan'],
                ['nama_puskesmas' => 'Puskesmas Gerunggang', 'kecamatan' => 'Gerunggang', 'kelurahan_list' => 'Bukit Merapin, Taman Bunga, Air Kepala Tujuh, Tua Tunu Indah'],
                ['nama_puskesmas' => 'Puskesmas Selindung', 'kecamatan' => 'Gabek', 'kelurahan_list' => 'Selindung, Selindung Baru, Gabek Satu, Gabek Dua, Jerambah Gantung'],
                ['nama_puskesmas' => 'Puskesmas Pangkal Balam', 'kecamatan' => 'Pangkal Balam', 'kelurahan_list' => 'Ketapang, Pasir Garam, Rejosari, Ampui'],
                ['nama_puskesmas' => 'Puskesmas Pasir Ikan', 'kecamatan' => 'Gabek', 'kelurahan_list' => 'Air Salemba, Gabek Satu'],
            ];
            $this->db->insert_batch('puskesmas_wilayah', $seed_data);
        }

        if ($this->db->table_exists('pasien')) {
            if (!$this->db->field_exists('puskesmas_wilayah', 'pasien')) {
                $new_field = [
                    'puskesmas_wilayah' => ['type' => 'VARCHAR', 'constraint' => '255', 'null' => TRUE]
                ];
                $this->dbforge->add_column('pasien', $new_field);
            }
        }
    }

    public function get_all()
    {
        return $this->db->get('puskesmas_wilayah')->result();
    }

    public function get_by_id($id)
    {
        return $this->db->get_where('puskesmas_wilayah', ['id' => $id])->row();
    }

    public function insert($data)
    {
        return $this->db->insert('puskesmas_wilayah', $data);
    }

    public function update($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update('puskesmas_wilayah', $data);
    }

    public function delete($id)
    {
        $this->db->where('id', $id);
        return $this->db->delete('puskesmas_wilayah');
    }

    // Auto mapping function
    public function auto_map_puskesmas($alamat)
    {
        $wilayahs = $this->get_all();
        // Coba cari dari kelurahan_list dulu (karena kelurahan lebih spesifik)
        foreach ($wilayahs as $w) {
            $kelurahan_arr = array_map('trim', explode(',', $w->kelurahan_list));
            foreach ($kelurahan_arr as $kel) {
                if (!empty($kel) && stripos($alamat, "Kel. " . $kel) !== false) {
                    return $w->nama_puskesmas;
                }
            }
        }

        // Jika tidak ketemu kelurahannya, coba cari dari kecamatannya
        foreach ($wilayahs as $w) {
            if (!empty($w->kecamatan) && stripos($alamat, "Kec. " . $w->kecamatan) !== false) {
                return $w->nama_puskesmas;
            }
        }

        return null; // Return null jika benar-benar tidak cocok
    }
}

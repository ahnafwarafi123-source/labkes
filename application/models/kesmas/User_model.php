<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model
{
    public function find_by_username(string $username)
    {
        return $this->db->get_where('user_kesmas', array('username' => $username, 'is_active' => 1))->row_array();
    }

    public function count_all(): int
    {
        return (int) $this->db->count_all('user_kesmas');
    }

    public function get_user(int $id)
    {
        return $this->db->get_where('user_kesmas', ['id' => $id])->row_array();
    }

    public function create_admin_if_empty(): bool
    {
        if ($this->count_all() > 0) {
            return false;
        }

        $data = array(
            'username' => 'admin',
            'nama' => 'Administrator',
            'password_hash' => password_hash('admin123', PASSWORD_DEFAULT),
            'role' => 'admin',
            'is_active' => 1,
            'created_at' => date('Y-m-d H:i:s'),
        );
        return (bool) $this->db->insert('user_kesmas', $data);
    }

    public function add_petugas($data)
    {
        $insert_data = array(
            'nama' => $data['nama'],
            'jabatan' => $data['jabatan'],
            'username' => null, // No username for non-login data
            'password_hash' => null, // No password for non-login data
            'role' => 'sample_petugas', // Specific role for data-only petugas
            'is_active' => 0, // Not active for login
            'created_at' => date('Y-m-d H:i:s'),
        );

        return $this->db->insert('users', $insert_data);
    }

    public function get_all_petugas()
    {
        $this->db->where('role', 'sample_petugas'); // Filter by the new role
        return $this->db->get('users')->result_array();
    }
}

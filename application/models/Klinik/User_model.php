<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model {

    private $table = 'users'; // pastikan sama dengan nama tabel

    public function get_all() {
        return $this->db->get($this->table)->result();
    }

    public function get_by_id($id) {
        return $this->db->get_where($this->table, ['id_user' => $id])->row();
    }

    public function get_by_username($username) {
        return $this->db->get_where($this->table, ['username' => $username])->row();
    }

    public function insert($data) {
        if (isset($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }
        return $this->db->insert($this->table, $data);
    }

    public function update($id, $data) {
    // Hanya hash jika password tidak kosong
    if (isset($data['password']) && !empty($data['password'])) {
        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
    } else {
        // Jika password kosong, hapus dari array supaya tidak diupdate
        unset($data['password']);
    }
    return $this->db->where('id_user', $id)->update($this->table, $data);
}


    public function delete($id) {
        return $this->db->where('id_user', $id)->delete($this->table);
    }
}

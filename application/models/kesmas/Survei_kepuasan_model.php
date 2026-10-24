<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Survei_kepuasan_model extends CI_Model
{
    private $table = 'kesmas_survei_kepuasan';

    /**
     * Get semua survei
     */
    public function get_all()
    {
        $this->db->order_by('tgl_survei', 'DESC');
        return $this->db->get($this->table)->result_array();
    }

    /**
     * Get survei by ID
     */
    public function get_by_id($id)
    {
        return $this->db->get_where($this->table, array('id_survei' => $id))->row_array();
    }

    /**
     * Cek apakah sudah ada survei untuk permintaan tertentu
     */
    public function check_exists($id_permintaan)
    {
        return $this->db->get_where($this->table, array('id_permintaan' => $id_permintaan))->num_rows() > 0;
    }

    /**
     * Tambah survei kepuasan baru
     */
    public function create($data)
    {
        $insert_data = array(
            'id_permintaan' => !empty($data['id_permintaan']) ? $data['id_permintaan'] : 0,
            'jam_survei' => $data['jam_survei'] ?? NULL,
            'jenis_kelamin' => $data['jenis_kelamin'] ?? NULL,
            'pendidikan' => $data['pendidikan'] ?? NULL,
            'pekerjaan' => $data['pekerjaan'] ?? NULL,
            'usia' => $data['usia'] ?? NULL,
            'jenis_layanan' => $data['jenis_layanan'] ?? NULL,
            'q1' => $data['q1'] ?? 0, 'q2' => $data['q2'] ?? 0, 'q3' => $data['q3'] ?? 0,
            'q4' => $data['q4'] ?? 0, 'q5' => $data['q5'] ?? 0, 'q6' => $data['q6'] ?? 0,
            'q7' => $data['q7'] ?? 0, 'q8' => $data['q8'] ?? 0, 'q9' => $data['q9'] ?? 0,
            'komentar_saran' => $data['komentar_saran'] ?? NULL,
            'tgl_survei' => date('Y-m-d H:i:s'),
            'created_at' => date('Y-m-d H:i:s')
        );
        
        if ($this->db->insert($this->table, $insert_data)) {
            return $this->db->insert_id();
        }
        return FALSE;
    }

    /**
     * Update survei
     */
    public function update($id, $data)
    {
        $update_data = array(
            'jam_survei' => $data['jam_survei'] ?? NULL,
            'jenis_kelamin' => $data['jenis_kelamin'] ?? NULL,
            'pendidikan' => $data['pendidikan'] ?? NULL,
            'pekerjaan' => $data['pekerjaan'] ?? NULL,
            'usia' => $data['usia'] ?? NULL,
            'jenis_layanan' => $data['jenis_layanan'] ?? NULL,
            'q1' => $data['q1'] ?? 0, 'q2' => $data['q2'] ?? 0, 'q3' => $data['q3'] ?? 0,
            'q4' => $data['q4'] ?? 0, 'q5' => $data['q5'] ?? 0, 'q6' => $data['q6'] ?? 0,
            'q7' => $data['q7'] ?? 0, 'q8' => $data['q8'] ?? 0, 'q9' => $data['q9'] ?? 0,
            'komentar_saran' => $data['komentar_saran'] ?? NULL,
            'tgl_survei' => date('Y-m-d H:i:s')
        );
        
        $this->db->where('id_survei', $id);
        return $this->db->update($this->table, $update_data);
    }

    /**
     * Delete survei
     */
    public function delete($id)
    {
        $this->db->where('id_survei', $id);
        return $this->db->delete($this->table);
    }

    /**
     * Get rata-rata rating
     */
    public function get_average_ratings()
    {
        $this->db->select('
            AVG(NULLIF(q1, 0)) as avg_q1,
            AVG(NULLIF(q2, 0)) as avg_q2,
            AVG(NULLIF(q3, 0)) as avg_q3,
            AVG(NULLIF(q4, 0)) as avg_q4,
            AVG(NULLIF(q5, 0)) as avg_q5,
            AVG(NULLIF(q6, 0)) as avg_q6,
            AVG(NULLIF(q7, 0)) as avg_q7,
            AVG(NULLIF(q8, 0)) as avg_q8,
            AVG(NULLIF(q9, 0)) as avg_q9,
            COUNT(id_survei) as total_survei',
            FALSE
        );
        return $this->db->get($this->table)->row_array();
    }

    /**
     * Get statistik rating
     */
    public function get_rating_stats()
    {
        $stats = array();
        
        // Average rating
        $avg = $this->get_average_ratings();
        
        $total_avg = 0;
        for ($i = 1; $i <= 9; $i++) {
            $stats['avg_q'.$i] = round((float)($avg['avg_q'.$i] ?? 0), 2);
            $total_avg += $stats['avg_q'.$i];
        }
        
        $stats['ikm_score'] = round(($total_avg / 9) * 25, 2); // Rumus IKM Permenpan RB
        $stats['total_survei'] = $avg['total_survei'] ?? 0;
        
        return $stats;
    }

    /**
     * Get distribusi rating
     */
    public function get_rating_distribution($score_column)
    {
        $this->db->select("$score_column as skor, COUNT(id_survei) as jumlah", FALSE);
        $this->db->from($this->table);
        $this->db->group_by($score_column);
        $this->db->order_by($score_column, 'ASC');
        return $this->db->get()->result_array();
    }

    /**
     * Get survei dengan rating rendah (skor < 3)
     */
    public function get_low_ratings()
    {
        $this->db->where('(q1 <= 2 AND q1 > 0) OR (q2 <= 2 AND q2 > 0) OR (q3 <= 2 AND q3 > 0) OR (q4 <= 2 AND q4 > 0) OR (q5 <= 2 AND q5 > 0) OR (q6 <= 2 AND q6 > 0) OR (q7 <= 2 AND q7 > 0) OR (q8 <= 2 AND q8 > 0) OR (q9 <= 2 AND q9 > 0)');
        $this->db->order_by('tgl_survei', 'DESC');
        return $this->db->get($this->table)->result_array();
    }

    /**
     * Get survei dengan komentar
     */
    public function get_with_comments()
    {
        $this->db->where('komentar_saran IS NOT NULL AND komentar_saran != ""');
        $this->db->order_by('tgl_survei', 'DESC');
        return $this->db->get($this->table)->result_array();
    }

    /**
     * Get survei berdasarkan range tanggal
     */
    public function get_by_date_range($start_date, $end_date)
    {
        $this->db->where("DATE(tgl_survei) >= ", $start_date);
        $this->db->where("DATE(tgl_survei) <= ", $end_date);
        $this->db->order_by('tgl_survei', 'DESC');
        return $this->db->get($this->table)->result_array();
    }

    /**
     * Get survei by id_permintaan
     */
    public function get_by_permintaan($id_permintaan)
    {
        return $this->db->get_where($this->table, array('id_permintaan' => $id_permintaan))->row_array();
    }
}

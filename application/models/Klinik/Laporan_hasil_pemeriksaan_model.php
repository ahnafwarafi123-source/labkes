<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Laporan_hasil_pemeriksaan_model extends CI_Model
{

    public function get_daily_registrations()
    {
        $sql = "SELECT tanggal, COUNT(DISTINCT id_pasien) as total_pasien 
                FROM (
                    SELECT DATE(created_at) as tanggal, id_pasien FROM pasien WHERE created_at IS NOT NULL
                    UNION
                    SELECT DATE(updated_at) as tanggal, id_pasien FROM pasien WHERE updated_at IS NOT NULL
                ) as combined
                GROUP BY tanggal
                ORDER BY tanggal DESC";
        return $this->db->query($sql)->result();
    }

    public function get_patients_by_date($date)
    {
        $sql = "SELECT DISTINCT * FROM pasien 
                WHERE DATE(created_at) = ? OR DATE(updated_at) = ?
                ORDER BY COALESCE(updated_at, created_at) DESC";
        return $this->db->query($sql, [$date, $date])->result();
    }

    public function get_daily_visits()
    {
        $this->db->select('DATE(tanggal_kunjungan) as tanggal, COUNT(id) as total_kunjungan');
        $this->db->from('kunjungan_rm');
        $this->db->where('tanggal_kunjungan IS NOT NULL');
        $this->db->group_by('DATE(tanggal_kunjungan)');
        $this->db->order_by('DATE(tanggal_kunjungan)', 'DESC');
        return $this->db->get()->result();
    }

    public function get_visits_by_date($date)
    {
        $this->db->select('kunjungan_rm.*, pasien.nama_pasien, pasien.no_rm');
        $this->db->from('kunjungan_rm');
        $this->db->join('pasien', 'pasien.no_rm = kunjungan_rm.no_rm', 'left');
        $this->db->where('DATE(kunjungan_rm.tanggal_kunjungan)', $date);
        $this->db->order_by('kunjungan_rm.tanggal_kunjungan', 'DESC');
        return $this->db->get()->result();
    }

    public function get_daily_klinik()
    {
        $this->db->select('DATE(form_permintaan_klinik.tgl_form) as tanggal, COUNT(form_permintaan_klinik.id) as total_pasien,
                           SUM(CASE WHEN LOWER(pasien.gender) = "laki-laki" OR LOWER(pasien.gender) = "l" THEN 1 ELSE 0 END) as total_l,
                           SUM(CASE WHEN LOWER(pasien.gender) = "perempuan" OR LOWER(pasien.gender) = "p" THEN 1 ELSE 0 END) as total_p');
        $this->db->from('form_permintaan_klinik');
        $this->db->join('pasien', 'pasien.id_pasien = form_permintaan_klinik.id_pasien', 'left');
        $this->db->where('form_permintaan_klinik.tgl_form IS NOT NULL');
        $this->db->group_by('DATE(form_permintaan_klinik.tgl_form)');
        $this->db->order_by('DATE(form_permintaan_klinik.tgl_form)', 'DESC');
        return $this->db->get()->result();
    }

    public function get_klinik_by_date($date)
    {
        $this->db->where('DATE(tgl_form)', $date);
        $this->db->order_by('tgl_form', 'DESC');
        return $this->db->get('form_permintaan_klinik')->result();
    }

    public function get_daily_uji_klinik()
    {
        // Ambil yang sudah ada hasilnya
        $this->db->select('DATE(f.tgl_form) as tanggal, COUNT(f.id) as total_pasien');
        $this->db->from('form_permintaan_klinik f');
        
        $this->db->join('(SELECT id_form, COUNT(hasil) AS total_hasil 
                          FROM form_permintaan_klinik_detail 
                          WHERE hasil IS NOT NULL AND hasil <> "" 
                          GROUP BY id_form) d', 'f.id = d.id_form', 'inner');
                          
        $this->db->group_by('DATE(f.tgl_form)');
        $this->db->order_by('DATE(f.tgl_form)', 'DESC');
        return $this->db->get()->result();
    }

    public function get_uji_klinik_by_date($date)
    {
        $this->db->select('f.*');
        $this->db->from('form_permintaan_klinik f');
        
        $this->db->join('(SELECT id_form, COUNT(hasil) AS total_hasil 
                          FROM form_permintaan_klinik_detail 
                          WHERE hasil IS NOT NULL AND hasil <> "" 
                          GROUP BY id_form) d', 'f.id = d.id_form', 'inner');
                          
        $this->db->where('DATE(f.tgl_form)', $date);
        $this->db->order_by('f.tgl_form', 'DESC');
        return $this->db->get()->result();
    }

    public function get_kategori_bulan($kategori, $bulan, $tahun)
    {
        $this->db->select('
            nama_jenis,
            COUNT(*) as total
        ');

        $this->db->from('form_permintaan_klinik_detail');

        $this->db->like('kategori', $kategori);

        $this->db->where('MONTH(tanggal_input)', $bulan);

        $this->db->where('YEAR(tanggal_input)', $tahun);

        $this->db->group_by('nama_jenis');

        return $this->db->get()->result();
    }
    // ================= LAPORAN TAHUNAN =================
    public function get_laporan_tahunan($tahun)
    {
        $this->db->select('
            kategori,
            nama_jenis,
            MONTH(tanggal_input) as bulan,
            COUNT(*) as total
        ');

        $this->db->from('form_permintaan_klinik_detail');

        $this->db->where('YEAR(tanggal_input)', $tahun);

        $this->db->group_by('kategori');
        $this->db->group_by('nama_jenis');
        $this->db->group_by('MONTH(tanggal_input)');

        $this->db->order_by('kategori', 'ASC');

        return $this->db->get()->result();
    }

    public function get_jumlah_pasien_bulan($bulan, $tahun)
    {
        $sql = "SELECT 
                    SUM(CASE WHEN LOWER(gender) = 'laki-laki' OR LOWER(gender) = 'l' OR LOWER(gender) = 'pria' THEN 1 ELSE 0 END) as laki_laki,
                    SUM(CASE WHEN LOWER(gender) = 'perempuan' OR LOWER(gender) = 'p' OR LOWER(gender) = 'wanita' THEN 1 ELSE 0 END) as perempuan,
                    COUNT(*) as total
                FROM (
                    SELECT p.no_rm, p.gender
                    FROM kunjungan_rm k
                    JOIN pasien p ON p.no_rm = k.no_rm
                    WHERE MONTH(k.tanggal_kunjungan) = ? AND YEAR(k.tanggal_kunjungan) = ?
                    
                    UNION
                    
                    SELECT p.no_rm, p.gender
                    FROM form_permintaan_klinik f
                    JOIN pasien p ON p.id_pasien = f.id_pasien
                    WHERE MONTH(f.tgl_form) = ? AND YEAR(f.tgl_form) = ?
                ) as combined_pasien";
                
        $result = $this->db->query($sql, [$bulan, $tahun, $bulan, $tahun])->row();
        
        if (!$result->total) {
            $result->laki_laki = 0;
            $result->perempuan = 0;
            $result->total = 0;
        }
        return $result;
    }

    public function get_jumlah_puskesmas_bulan($bulan, $tahun)
    {
        $sql = "SELECT 
                    COALESCE(UPPER(pw.kecamatan), 'LUAR WILAYAH') as kecamatan,
                    CASE 
                        WHEN combined_pasien.puskesmas_wilayah IS NULL OR combined_pasien.puskesmas_wilayah = '' THEN 'LUAR WILAYAH'
                        ELSE UPPER(combined_pasien.puskesmas_wilayah) 
                    END as wilayah,
                    COUNT(*) as total
                FROM (
                    SELECT k.id, p.puskesmas_wilayah
                    FROM kunjungan_rm k
                    JOIN pasien p ON p.no_rm = k.no_rm
                    WHERE MONTH(k.tanggal_kunjungan) = ? AND YEAR(k.tanggal_kunjungan) = ?
                    
                    UNION ALL
                    
                    SELECT f.id, p.puskesmas_wilayah
                    FROM form_permintaan_klinik f
                    JOIN pasien p ON p.id_pasien = f.id_pasien
                    WHERE MONTH(f.tgl_form) = ? AND YEAR(f.tgl_form) = ?
                ) as combined_pasien
                LEFT JOIN puskesmas_wilayah pw ON pw.nama_puskesmas = combined_pasien.puskesmas_wilayah
                GROUP BY pw.kecamatan, combined_pasien.puskesmas_wilayah
                ORDER BY 
                    CASE WHEN COALESCE(UPPER(pw.kecamatan), 'LUAR WILAYAH') = 'LUAR WILAYAH' THEN 1 ELSE 0 END, 
                    COALESCE(UPPER(pw.kecamatan), 'LUAR WILAYAH') ASC, 
                    total DESC";
                
        return $this->db->query($sql, [$bulan, $tahun, $bulan, $tahun])->result();
    }
}

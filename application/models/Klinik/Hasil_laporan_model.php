<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Hasil_laporan_model extends CI_Model {

    public function get_pasien_dengan_soap() {
        $this->db->select('
            p.id_pasien, p.no_rm, f.no_register, p.nama_pasien, p.nik, p.gender,
            k.id as kunjungan_id, k.status_soap, k.tanggal_kunjungan,
            f.id as id_form, f.tgl_form,
            COUNT(d.hasil) as total_hasil
        ');
        $this->db->from('pasien p');
        $this->db->join('form_permintaan_klinik f', 'p.id_pasien = f.id_pasien', 'inner');
        $this->db->join('form_permintaan_klinik_detail d', 'f.id = d.id_form', 'inner');
        $this->db->join('form_pengambilan_klinik pg', 'f.id = pg.id_form', 'left');
        $this->db->join('kunjungan_rm k', 'p.no_rm = k.no_rm AND DATE(k.tanggal_kunjungan) = DATE(f.tgl_form)', 'left');
        
        $this->db->where('DATE(f.tgl_form)', date('Y-m-d'));
        
        $this->db->group_by(['p.id_pasien', 'p.no_rm', 'f.no_register', 'p.nama_pasien', 'p.nik', 'p.gender', 'k.id', 'k.status_soap', 'k.tanggal_kunjungan', 'f.id', 'f.tgl_form']);
        $this->db->order_by('f.tgl_form', 'DESC');
        return $this->db->get()->result();
    }

    public function count_kunjungan_by_id_pasien($id_pasien) {
        $this->db->join('pasien p', 'p.no_rm = kunjungan_rm.no_rm');
        $this->db->where('p.id_pasien', $id_pasien);
        return $this->db->count_all_results('kunjungan_rm');
    }

    public function get_detail_hasil_uji($id_pasien) {
        // Cari ID form terbaru untuk pasien ini pada hari ini
        $this->db->select('id');
        $this->db->from('form_permintaan_klinik');
        $this->db->where('id_pasien', $id_pasien);
        $this->db->where('DATE(tgl_form)', date('Y-m-d'));
        $this->db->order_by('tgl_form', 'DESC');
        $this->db->limit(1);
        $latest = $this->db->get()->row();

        if (!$latest) {
            return [];
        }

        $this->db->select('
            p.id_pasien, p.nama_pasien, p.no_rm, p.gender, p.tgl_lahir, p.umur, p.nik,
            k.id as kunjungan_id, k.tanggal_kunjungan, k.keluhan_utama, k.riwayat_penyakit, k.asesmen_diagnosa, k.plan_rencana, k.nama_dokter_pemeriksa, k.unit, k.status_soap,
            f.id as id_form, f.no_register, f.diagnosa_klinis,
            d.id_detail, d.nama_jenis as nama_pemeriksaan, d.kategori, d.sub_kategori, d.hasil, COALESCE(j.satuan, d.satuan) as satuan, COALESCE(j.nilai_rujukan, d.nilai_rujukan) as nilai_rujukan, COALESCE(j.metode, d.metode) as metode,
            a.petugas_pengambilan, a.tgl_jam_pengambilan, a.petugas_hasil, a.tgl_jam_pemeriksaan, a.verifikator_hasil, a.penanggung_jawab_teknis, a.sip_penanggung, a.note
        ');
        $this->db->from('pasien p');
        $this->db->join('form_permintaan_klinik f', 'p.id_pasien = f.id_pasien', 'inner');
        $this->db->join('form_permintaan_klinik_detail d', 'f.id = d.id_form', 'inner');
        $this->db->join('jenis_pemeriksaan j', 'd.id_jenis = j.id_jenis', 'left');
        $this->db->join('form_pengambilan_klinik a', 'f.id = a.id_form', 'left');
        $this->db->join('kunjungan_rm k', 'p.no_rm = k.no_rm AND DATE(k.tanggal_kunjungan) = DATE(f.tgl_form)', 'left');
        $this->db->where('f.id', $latest->id);
        $this->db->order_by('d.id_detail', 'ASC');
        return $this->db->get()->result();
    }

}

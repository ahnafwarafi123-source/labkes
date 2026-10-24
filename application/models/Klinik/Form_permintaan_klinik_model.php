<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Form_permintaan_klinik_model extends CI_Model
{

    public function simpan_form($data)
    {
        $insertData = [
            'kunjungan_id' => $data['kunjungan_id'] ?? null,
            'id_pasien'   => $data['id_pasien'] ?? null,
            'nama_dokter' => $data['nama_dokter'] ?? '',
            'alamat_pengirim' => $data['alamat_pengirim'] ?? '',
            'telp_pengirim' => $data['telp_pengirim'] ?? '',
            'tgl_form' => $data['tgl_form'] ?? null,
            'informasi_lain' => $data['informasi_lain'] ?? '',
            'diagnosa_klinis' => $data['diagnosa_klinis'] ?? '',
            'obat_dikonsumsi' => $data['obat_dikonsumsi'] ?? '',
            'asal_sampel' => implode(',', $data['asal_sampel'] ?? []),
            'puasa' => $data['puasa'] ?? '',
            'keterangan_puasa' => $data['keterangan_puasa'] ?? '',
            'lokasi_pengambilan' => implode(',', $data['lokasi_pengambilan'] ?? []),
            'lokasi_lainnya' => $data['lokasi_lainnya'] ?? '',
            'jenis_spesimen' => implode(',', $data['jenis_spesimen'] ?? []),
            'spesimen_lainnya' => $data['spesimen_lainnya'] ?? '',
            'tgl_permintaan' => $data['tgl_permintaan'] ?? null,
            'volume_spesimen' => $data['volume_spesimen'] ?? '',
            'tgl_pengambilan' => $data['tgl_pengambilan'] ?? null,
            'jam_pengambilan' => $data['jam_pengambilan'] ?? null,
            'prioritas' => $data['prioritas'] ?? '',
            'info_tambahan' => $data['info_tambahan'] ?? '',
            'kelayakan'          => $data['kelayakan'] ?? 'Layak',
            'alasan_tidak_layak' => $data['alasan_tidak_layak'] ?? '',
            'jumlah_biaya'       => $data['jumlah_biaya'] ?? 0,
            'volume_sampel_kaji_ulang' => $data['volume_sampel_kaji_ulang'] ?? null,
            'metode_pembayaran'  => $data['metode_pembayaran'] ?? '',
            'petugas_pendaftaran' => $data['petugas_pendaftaran'] ?? '',
            'petugas_pengambil'   => $data['petugas_pengambil'] ?? '',
            'petugas_verifikasi'  => $data['petugas_verifikasi'] ?? '',
            'petugas_validasi'    => $data['petugas_validasi'] ?? '',
            'no_register'         => $data['no_register'] ?? null,
        ];

        $this->db->insert('form_permintaan_klinik', $insertData);
        $id_form = $this->db->insert_id(); // Ambil ID terakhir

        return $id_form;
    }

    public function get_all_formulir()
    {
        $this->db->select('form_permintaan_klinik.*, pasien.nama_pasien, pasien.nik, pasien.gender, pasien.puskesmas_wilayah');
        $this->db->from('form_permintaan_klinik');
        $this->db->join('pasien', 'pasien.id_pasien = form_permintaan_klinik.id_pasien', 'left');
        return $this->db->get()->result();
    }

    public function get_today_formulir()
    {
        $today = date('Y-m-d');
        $this->db->select("
            form_permintaan_klinik.id,
            COALESCE(form_permintaan_klinik.no_register, '-') as no_register,
            pasien.nama_pasien,
            pasien.nik,
            pasien.gender,
            pasien.id_pasien,
            COALESCE(form_permintaan_klinik.nama_dokter, '-') as nama_dokter,
            COALESCE(form_permintaan_klinik.tgl_form, pasien.created_at) as tgl_form,
            COALESCE(form_permintaan_klinik.kelayakan, 'Belum Diperiksa') as kelayakan
        ", FALSE);
        $this->db->from('pasien');
        $this->db->join('form_permintaan_klinik', "pasien.id_pasien = form_permintaan_klinik.id_pasien AND DATE(form_permintaan_klinik.tgl_form) = '$today'", 'left');
        $this->db->group_start();
        $this->db->where('DATE(pasien.created_at)', $today);
        $this->db->or_where('DATE(form_permintaan_klinik.tgl_form)', $today);
        $this->db->group_end();
        return $this->db->get()->result();
    }

    public function get_all_formulir_paginated($limit, $offset, $search = null, $tgl_mulai = null)
    {
        $this->db->select("
            form_permintaan_klinik.id,
            COALESCE(form_permintaan_klinik.no_register, '-') as no_register,
            pasien.no_rm,
            pasien.nama_pasien,
            pasien.nik,
            pasien.gender,
            pasien.id_pasien,
            COALESCE(form_permintaan_klinik.nama_dokter, '-') as nama_dokter,
            COALESCE(form_permintaan_klinik.tgl_form, pasien.created_at) as tgl_form,
            COALESCE(form_permintaan_klinik.kelayakan, 'Belum Diperiksa') as kelayakan
        ", FALSE);
        $this->db->from('pasien');
        $this->db->join('form_permintaan_klinik', 'pasien.id_pasien = form_permintaan_klinik.id_pasien', 'left');

        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('form_permintaan_klinik.no_register', $search);
            $this->db->or_like('pasien.no_rm', $search);
            $this->db->or_like('pasien.nama_pasien', $search);
            $this->db->or_like('pasien.nik', $search);
            $this->db->or_like('form_permintaan_klinik.nama_dokter', $search);
            $this->db->group_end();
        }

        if (!empty($tgl_mulai)) {
            $this->db->where('DATE(form_permintaan_klinik.tgl_form)', $tgl_mulai);
        }

        // Urutkan berdasarkan formulir id descending (terbaru dahulu), jika null letakkan di bawah/atas secara konsisten
        $this->db->order_by('form_permintaan_klinik.id', 'DESC');
        $this->db->order_by('pasien.id_pasien', 'DESC');
        $this->db->limit($limit, $offset);
        return $this->db->get()->result();
    }

    public function get_all_formulir_count($search = null, $tgl_mulai = null)
    {
        $this->db->from('pasien');
        $this->db->join('form_permintaan_klinik', 'pasien.id_pasien = form_permintaan_klinik.id_pasien', 'left');

        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('form_permintaan_klinik.no_register', $search);
            $this->db->or_like('pasien.no_rm', $search);
            $this->db->or_like('pasien.nama_pasien', $search);
            $this->db->or_like('pasien.nik', $search);
            $this->db->or_like('form_permintaan_klinik.nama_dokter', $search);
            $this->db->group_end();
        }

        if (!empty($tgl_mulai)) {
            $this->db->where('DATE(form_permintaan_klinik.tgl_form)', $tgl_mulai);
        }

        return $this->db->count_all_results();
    }

    public function get_formulir_by_id($id)
    {
        $this->db->select('form_permintaan_klinik.*, pasien.no_telp as no_telp, pasien.alamat as alamat, pasien.nama_pasien, pasien.nik, pasien.gender, pasien.puskesmas_wilayah');
        $this->db->from('form_permintaan_klinik');
        $this->db->join('pasien', 'pasien.id_pasien = form_permintaan_klinik.id_pasien', 'left');
        $this->db->where('form_permintaan_klinik.id', $id);
        return $this->db->get()->row();
    }

    public function get_jenis_pemeriksaan()
    {
        return $this->db->where('aktif', 1)
            ->order_by('kategori', 'ASC')
            ->order_by('sub_kategori', 'ASC')
            ->order_by('nama_jenis', 'ASC')
            ->get('jenis_pemeriksaan')
            ->result_array();
    }
    public function get_detail_by_form($id_form)
    {
        return $this->db->get_where('form_permintaan_klinik_detail', ['id_form' => $id_form])->result();
    }

    // ambil data form berdasarkan ID
    public function get_by_id($id)
    {
        $this->db->select('form_permintaan_klinik.*, pasien.no_rm, pasien.no_telp as no_telp, pasien.alamat as alamat, pasien.nama_pasien as nama_pasien, pasien.nik, pasien.gender, pasien.tgl_lahir, pasien.umur, pasien.agama, pasien.status_nikah, pasien.pendidikan, pasien.pekerjaan, pasien.puskesmas_wilayah');
        $this->db->from('form_permintaan_klinik');
        $this->db->join('pasien', 'pasien.id_pasien = form_permintaan_klinik.id_pasien', 'left');
        $this->db->where('form_permintaan_klinik.id', $id);
        return $this->db->get()->row();
    }

    public function get_selected_jenis_ids($id_form)
    {
        $this->db->select('id_jenis');
        $this->db->from('form_permintaan_klinik_detail');
        $this->db->where('id_form', $id_form);
        $result = $this->db->get()->result_array();
        return array_column($result, 'id_jenis'); // hasil: [1, 5, 7, ...]
    }

    public function update($id, $data)
    {
        $this->db->where('id_form', $id);
        $this->db->update('form_permintaan_klinik', $data);
    }

    public function get_by_nik($nik)
    {
        return $this->db->get_where('pasien', ['nik' => $nik])->row();
    }

    public function get_patients_visiting_today()
    {
        $today = date('Y-m-d');
        $this->db->select('DISTINCT(p.id_pasien), p.no_rm, p.nama_pasien, p.nik, p.gender');
        $this->db->from('pasien p');
        $this->db->join('form_permintaan_klinik f', 'f.id_pasien = p.id_pasien', 'inner');
        $this->db->where('DATE(f.tgl_form)', $today);
        return $this->db->get()->result();
    }
}
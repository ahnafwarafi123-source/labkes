<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kesmas_model extends CI_Model
{
    public function gen_no_registrasi(): string
    {
        $ymd = date('Ymd');
        $prefix = 'KESMAS.' . $ymd . '.';
        $this->db->like('no_registrasi', $prefix, 'after');
        $this->db->from('kesmas_terima_sampel');
        $count = $this->db->count_all_results();
        $num = $count + 1;
        return $prefix . str_pad((string)$num, 4, '0', STR_PAD_LEFT);
    }

    private function _build_permintaan_query()
    {
        $this->db->select(" 
            p.id_permintaan,
            pel.id_pelanggan,
            pel.nik,
            pel.nama,
            pel.alamat,
            pel.telp,
            pel.instansi,
            pel.ttd,
            p.tgl_permintaan,
            s.nama_sampel,
            s.id_kategori_sampel,
            s.jenis_sampel,
            s.volume_ml,
            s.tgl_pengambilan,
            s.jam_pengambilan,
            s.lokasi_pengambilan,
            s.id_petugas_sampel,
            s.tindakan_sampel,
            s.info_tambahan,
            k.id_petugas_pendaftaran AS petugas_pendaftaran_id,
            t.no_registrasi,
            t.diterima_at,
            t.id_terima_sampel,
            p.status_kelayakan,
            p.alasan_tidak_layak,
            p.catatan,
            p.keterangan_cetak,
            pb.jumlah_biaya,
            pb.total_dibayar,
            pb.cara_bayar,
            pb.keterangan_bayar,
            pb.status_bayar,
            k.kk_tgl_pengambilan,
            k.tgl_diterima_lab,
            k.tgl_pengerjaan,
            k.tgl_input_hasil,
            k.tgl_cetak,
            p.created_by,
            p.created_at,
            p.updated_by,
            p.updated_at
        ", false);
        $this->db->from('kesmas_permintaan p');
        $this->db->join('kesmas_pelanggan pel', 'p.id_pelanggan = pel.id_pelanggan', 'left');
        $this->db->join('kesmas_sampel s', 'p.id_permintaan = s.id_permintaan', 'left');
        $this->db->join('kesmas_terima_sampel t', 'p.id_permintaan = t.id_permintaan', 'left');
        $this->db->join('kesmas_pembayaran pb', 'p.id_permintaan = pb.id_permintaan', 'left');
        $this->db->join('kesmas_kartu_kendali k', 'p.id_permintaan = k.id_permintaan', 'left');
    }


    private function determine_keterangan_from_baku(?string $baku, $hasil): ?string
    {
        if ($hasil === null || $hasil === '') return null;
        $baku_norm = mb_strtolower(trim((string)$baku));
        $hasil_norm = mb_strtolower(trim((string)$hasil));
        $baku_norm = preg_replace_callback('/(?<=\d),(?=\d)/u', function($m){ return '.'; }, $baku_norm);
        $hasil_norm = preg_replace_callback('/(?<=\d),(?=\d)/u', function($m){ return '.'; }, $hasil_norm);
        if (preg_match('/(maks|maksimum)\s*[:\-\s]*([0-9\.,]+)\s*[x×]\s*10\^?\s*(\-?\d+)/i', $baku_norm, $msci)) {
            $base = (float)str_replace(',', '.', $msci[2]);
            $limit = $base * pow(10, (int)$msci[3]);
            if (preg_match('/-?\d+[\d\.]*?/', $hasil_norm, $hm)) return ((float)$hm[0] <= $limit) ? 'MS' : 'TMS';
        }
        if (preg_match('/\b(maks|maksimum)\b\s*[:\-\s]*([0-9\.,]+)/i', $baku_norm, $m_maks)) {
            $limit = (float)str_replace(',', '.', $m_maks[2]);
            if (preg_match('/-?\d+[\d\.]*?/', $hasil_norm, $hm)) return ((float)$hm[0] <= $limit) ? 'MS' : 'TMS';
        }
        if (preg_match('/([<>≤≥]=?|=)\s*([0-9\.]+)/u', $baku_norm, $m)) {
            $op = str_replace(['≤','≥'], ['<=','>='], $m[1]);
            $limit = (float)$m[2];
            if (preg_match('/-?\d+[\d\.]*?/', $hasil_norm, $hm)) {
                $val = (float)$hm[0];
                switch ($op) {
                    case '<': return ($val < $limit) ? 'MS' : 'TMS';
                    case '<=': return ($val <= $limit) ? 'MS' : 'TMS';
                    case '>': return ($val > $limit) ? 'MS' : 'TMS';
                    case '>=': return ($val >= $limit) ? 'MS' : 'TMS';
                    case '=': return (abs($val - $limit) < 1e-9) ? 'MS' : 'TMS';
                }
            }
        }
        if (preg_match('/([0-9\.]+)\s*[-–]\s*([0-9\.]+)/u', $baku_norm, $m2)) {
            if (preg_match('/-?\d+[\d\.]*?/', $hasil_norm, $hm2)) {
                $val = (float)$hm2[0];
                return ($val >= (float)$m2[1] && $val <= (float)$m2[2]) ? 'MS' : 'TMS';
            }
        }
        if (preg_match('/\b0\b/', $baku_norm) || strpos($baku_norm, 'negatif') !== false) {
            if (preg_match('/\d+/', $hasil_norm, $hm3)) return ((int)$hm3[0] === 0) ? 'MS' : 'TMS';
            if (strpos($hasil_norm, 'positif') !== false) return 'TMS';
            return 'MS';
        }
        if (strpos($baku_norm, 'tidak berbau') !== false || strpos($baku_norm, 'tidak bau') !== false) {
            return (strpos($hasil_norm, 'bau') !== false || strpos($hasil_norm, 'berbau') !== false) ? 'TMS' : 'MS';
        }
        if (strpos($baku_norm, 'bening') !== false || strpos($baku_norm, 'jernih') !== false) {
            return (strpos($hasil_norm, 'keruh') !== false || strpos($hasil_norm, 'berwarna') !== false) ? 'TMS' : 'MS';
        }
        if (strpos($baku_norm, 'negatif') !== false || strpos($baku_norm, 'tidak ada') !== false) {
            return (strpos($hasil_norm, 'positif') !== false || preg_match('/\d+/', $hasil_norm)) ? 'TMS' : 'MS';
        }
        return null;
    }

    private function determine_keterangan_from_structured(?string $type, ?string $operator, $min, $max, ?string $text, $hasil): ?string
    {
        if ($hasil === null || $hasil === '') return null;
        $type = $type ? strtolower(trim($type)) : null;
        if ($type === 'numeric_limit' || $type === 'numeric') {
            $limit = is_numeric($max) ? (float)$max : (is_numeric($min) ? (float)$min : null);
            if ($limit !== null) {
                if (preg_match('/-?\d+[\d\.]*?/', (string)$hasil, $hm)) {
                    $val = (float)$hm[0];
                    switch ($operator ?: '<=') {
                        case '<': return ($val < $limit) ? 'MS' : 'TMS';
                        case '<=': return ($val <= $limit) ? 'MS' : 'TMS';
                        case '>': return ($val > $limit) ? 'MS' : 'TMS';
                        case '>=': return ($val >= $limit) ? 'MS' : 'TMS';
                        case '=': return (abs($val - $limit) < 1e-9) ? 'MS' : 'TMS';
                    }
                }
            }
        }
        if ($type === 'numeric_range' && is_numeric($min) && is_numeric($max)) {
            if (preg_match('/-?\d+[\d\.]*?/', (string)$hasil, $hm)) {
                $val = (float)$hm[0];
                return ($val >= (float)$min && $val <= (float)$max) ? 'MS' : 'TMS';
            }
        }
        if ($type === 'text' || $type === 'qualitative') {
            $baku_text = strtolower(trim((string)$text));
            $hasil_norm = strtolower(trim((string)$hasil));
            if (strpos($baku_text, 'tidak berbau') !== false || strpos($baku_text, 'tidak bau') !== false) {
                return (strpos($hasil_norm, 'bau') !== false || strpos($hasil_norm, 'berbau') !== false) ? 'TMS' : 'MS';
            }
            if (strpos($baku_text, 'negatif') !== false || preg_match('/negatif/i', $baku_text)) {
                return (strpos($hasil_norm, 'positif') !== false || preg_match('/\d+/', $hasil_norm)) ? 'TMS' : 'MS';
            }
        }
        if (!empty($text)) return $this->determine_keterangan_from_baku($text, $hasil);
        return null;
    }

    public function list_permintaan(array $filters = [])
    {
        $this->_build_permintaan_query();
        $this->db->join('kesmas_permintaan_item i', 'p.id_permintaan = i.id_permintaan', 'left');
        $this->db->select('COUNT(DISTINCT i.id_permintaan_item) AS jumlah_pemeriksaan', false);

        if (!empty($filters['q'])) {
            $q = $filters['q'];
            $this->db->group_start()
                ->like('t.no_registrasi', $q)
                ->or_like('s.nama_sampel', $q)
                ->or_like('s.jenis_sampel', $q)
                ->or_like('pel.nama', $q)
                ->or_like('pel.instansi', $q)
                ->group_end();
        }
        if (!empty($filters['dari'])) $this->db->where('p.tgl_permintaan >=', $filters['dari']);
        if (!empty($filters['sampai'])) $this->db->where('p.tgl_permintaan <=', $filters['sampai']);
        if (isset($filters['is_diterima'])) $this->db->where('(CASE WHEN t.id_terima_sampel IS NOT NULL THEN 1 ELSE 0 END) =', (int)$filters['is_diterima'], false);
        if (!empty($filters['exclude_ditolak'])) $this->db->where('(CASE WHEN t.id_terima_sampel IS NOT NULL THEN 1 ELSE 0 END) !=', 2, false);
        if (!empty($filters['only_layak'])) $this->db->where('p.status_kelayakan', 'Layak');
        if (!empty($filters['only_with_input_hasil'])) $this->db->where('k.tgl_input_hasil IS NOT NULL', null, false);

        $this->db->group_by('p.id_permintaan');
        $this->db->order_by('t.no_registrasi', 'DESC');
        $rows = $this->db->get()->result_array();
        return array_map([$this, '_map_permintaan_row'], $rows);
    }

    public function get_permintaan(int $id)
    {
        $this->_build_permintaan_query();
        $this->db->where('p.id_permintaan', $id);
        $row = $this->db->get()->row_array();
        return $row ? $this->_map_permintaan_row($row) : null;
    }

    public function get_permintaan_by_no_registrasi(string $no_registrasi)
    {
        $this->_build_permintaan_query();
        $this->db->where('t.no_registrasi', $no_registrasi);
        $row = $this->db->get()->row_array();
        return $row ? $this->_map_permintaan_row($row) : null;
    }

    private function _map_permintaan_row(array $row): array
    {
        $row['kategori_sampel'] = $this->_kategori_sampel_label((int)($row['id_kategori_sampel'] ?? 0));
        return $row;
    }

    private function _kategori_sampel_label(int $id): string
    {
        switch ($id) {
            case 1: return 'Air Minum';
            case 2: return 'Air Bersih / Air Sanitasi';
            case 3: return 'Makanan dan Minuman';
            case 4: return 'Lingkungan';
            default: return '';
        }
    }

    public function check_duplicate_no_registrasi(string $no_reg, ?int $exclude_id = null): bool
    {
        $this->db->where('no_registrasi', $no_reg);
        if ($exclude_id) $this->db->where('id_permintaan !=', $exclude_id);
        return $this->db->count_all_results('kesmas_terima_sampel') > 0;
    }

    public function get_master_grouped(string $kategori)
    {
        $map = [
            'air_minum' => 'Air Minum',
            'air_bersih' => 'Air Bersih / Air Sanitasi',
            'makanan' => 'Makanan dan Minuman',
            'lingkungan' => 'Lingkungan'
        ];
        $kategori_sampel = $map[$kategori] ?? $kategori;

        $this->db->select('m.*');
        $this->db->from('kesmas_master_pemeriksaan m');
        $this->db->where('m.kategori_sampel', $kategori_sampel);
        $this->db->where('m.is_active', 1);
        $this->db->order_by($this->_get_kelompok_order_case_sql($kategori, 'm'), 'ASC');
        $this->db->order_by('m.urutan','ASC');
        $rows = $this->db->get()->result_array();
        $out = array();
        foreach ($rows as $r) $out[$r['kelompok_pemeriksaan']][] = $r;
        return $out;
    }

    public function get_selected_master_ids(int $permintaan_id): array
    {
        $this->db->select('id_master_pemeriksaan');
        $rows = $this->db->get_where('kesmas_permintaan_item', array('id_permintaan' => $permintaan_id))->result_array();
        return array_map(function($r){ return (int)$r['id_master_pemeriksaan']; }, $rows);
    }

    private function _resolve_kategori_sampel(string $kat_str): int {
        $kat_str = trim((string)$kat_str);
        if ($kat_str === '') return 1;

        $normalized = strtolower($kat_str);
        if (str_contains($normalized, 'air')) {
            return 1;
        }
        if (str_contains($normalized, 'makan')) {
            return 3;
        }
        if (str_contains($normalized, 'lingkungan')) {
            return 4;
        }

        // fallback to numeric id if explicitly sent
        if (ctype_digit($kat_str)) {
            return (int)$kat_str;
        }

        return 1;
    }

    private function _normalize_kategori_sampel_string(string $kat_str): string
    {
        $kat_str = trim((string)$kat_str);
        if ($kat_str === '') {
            return '';
        }

        $normalized = strtolower(preg_replace('/\s+/', ' ', $kat_str));
        if (ctype_digit($kat_str)) {
            switch ((int)$kat_str) {
                case 1: return 'Air Minum';
                case 2: return 'Air Bersih / Air Sanitasi';
                case 3: return 'Makanan dan Minuman';
                case 4: return 'Lingkungan';
                default: return $kat_str;
            }
        }

        if ($normalized === 'air' || $normalized === 'air minum' || $normalized === 'air minum / air sanitasi' || $normalized === 'air minum / air sanitasi') {
            return 'Air Minum';
        }
        if ($normalized === 'air bersih' || $normalized === 'air bersih / air sanitasi' || $normalized === 'air sanitasi') {
            return 'Air Bersih / Air Sanitasi';
        }
        if ($normalized === 'makanan' || $normalized === 'makanan dan minuman' || $normalized === 'minuman') {
            return 'Makanan dan Minuman';
        }
        if ($normalized === 'lingkungan') {
            return 'Lingkungan';
        }

        return $kat_str;
    }

    public function save_permintaan(array $header, array $master_ids, ?int $user_id): int
    {
        $this->db->trans_start();

        // 1. Pelanggan
        $this->db->insert('kesmas_pelanggan', [
            'nik'     => $header['nik'] ?? '',
            'nama'    => $header['nama'] ?? '',
            'alamat'  => $header['alamat'] ?? '',
            'telp'    => $header['telp'] ?? '',
            'instansi'=> $header['instansi'] ?? '',
            'ttd'     => $header['ttd'] ?? ''
        ]);
        $id_pelanggan = $this->db->insert_id();

        // 2. Permintaan
        $this->db->insert('kesmas_permintaan', [
            'id_pelanggan' => $id_pelanggan,
            'tgl_permintaan' => $header['tgl_permintaan'] ?? date('Y-m-d'),
            'catatan' => $header['catatan'] ?? '',
            'created_by' => (int)$user_id,
            'updated_by' => (int)$user_id,
            'created_at' => date('Y-m-d H:i:s')
        ]);
        $id_permintaan = $this->db->insert_id();

        // 3. Sampel
        $kat_str = $header['id_kategori_sampel'] ?? '';
        $id_kategori = $this->_resolve_kategori_sampel($kat_str);

        $this->db->insert('kesmas_sampel', [
            'id_permintaan' => $id_permintaan,
            'nama_sampel' => $header['nama_sampel'] ?? '',
            'id_kategori_sampel' => $id_kategori,
            'jenis_sampel' => $header['jenis_sampel'] ?? '',
            'volume_ml' => (int)($header['volume_ml'] ?? 0),
            'tgl_pengambilan' => $header['tgl_pengambilan'] ?? date('Y-m-d'),
            'jam_pengambilan' => $header['jam_pengambilan'] ?? date('H:i:s'),
            'lokasi_pengambilan' => $header['lokasi_pengambilan'] ?? '',
            'id_petugas_sampel' => (int)($header['id_petugas_sampel'] ?: 1),
            'tindakan_sampel' => $header['tindakan_sampel'] ?? 'Langsung',
            'info_tambahan' => $header['info_tambahan'] ?? ''
        ]);

        // 4. Pembayaran (init)
        $this->db->insert('kesmas_pembayaran', [
            'id_permintaan' => $id_permintaan,
            'jumlah_biaya' => (float)($header['jumlah_biaya'] ?? 0),
            'cara_bayar' => $header['cara_bayar'] ?? '',
            'keterangan_bayar' => $header['keterangan_bayar'] ?? ''
        ]);
        
        // 5. Kartu Kendali (init)
        $this->db->insert('kesmas_kartu_kendali', [
            'id_permintaan' => $id_permintaan,
            'id_petugas_pendaftaran' => (int)($header['petugas_pendaftaran_id'] ?: 1),
            'id_petugas_pengambil_ttd' => 1,
            'id_petugas_verifikasi' => 1,
            'id_pj_teknis_validasi' => 1
        ]);

        // 6. Terima Sampel (if no_registrasi present)
        if(!empty($header['no_registrasi'])) {
            $this->db->insert('kesmas_terima_sampel', [
                'id_permintaan' => $id_permintaan,
                'no_registrasi' => $header['no_registrasi'],
                'diterima_oleh' => (int)$user_id
            ]);
        }

        // 7. Items
        foreach ($master_ids as $mid) {
            $this->db->insert('kesmas_permintaan_item', [
                'id_permintaan' => $id_permintaan,
                'id_master_pemeriksaan' => (int)$mid,
                'created_at' => date('Y-m-d H:i:s')
            ]);
        }

        $this->db->trans_complete();
        return $this->db->trans_status() !== FALSE ? $id_permintaan : 0;
    }

    public function update_permintaan(int $id, array $header, array $master_ids, ?int $user_id): bool
    {
        $this->db->trans_start();

        // Cari pelanggan dari view
        $v = $this->get_permintaan($id);
        if(!$v) return false;
        
        // Update Pelanggan
        $this->db->where('id_pelanggan', $v['id_pelanggan'])->update('kesmas_pelanggan', [
            'nik'      => $header['nik'] ?? $v['nik'] ?? '',
            'nama'     => $header['nama'] ?? $v['nama_pengirim'],
            'alamat'   => $header['alamat'] ?? $v['alamat_pengirim'],
            'telp'     => $header['telp'] ?? $v['telp_pengirim'],
            'instansi' => $header['instansi'] ?? $v['instansi'],
            'ttd'      => $header['ttd'] ?? $v['ttd_pengirim']
        ]);

        // Update Permintaan
        $this->db->where('id_permintaan', $id)->update('kesmas_permintaan', [
            'tgl_permintaan' => $header['tgl_permintaan'] ?? $v['tgl_permintaan'],
            'catatan' => $header['catatan'] ?? $v['catatan'],
            'updated_by' => (int)$user_id
        ]);

        // Update Sampel
        $kat_str = $header['id_kategori_sampel'] ?? '';
        $id_kategori = $this->_resolve_kategori_sampel($kat_str);
        
        $this->db->where('id_permintaan', $id)->update('kesmas_sampel', [
            'nama_sampel' => $header['nama_sampel'] ?? $v['nama_sampel'],
            'id_kategori_sampel' => $id_kategori,
            'jenis_sampel' => $header['jenis_sampel'] ?? $v['jenis_sampel'],
            'volume_ml' => (int)($header['volume_ml'] ?? $v['volume_ml']),
            'tgl_pengambilan' => $header['tgl_pengambilan'] ?? $v['tgl_pengambilan'],
            'jam_pengambilan' => $header['jam_pengambilan'] ?? $v['jam_pengambilan'],
            'lokasi_pengambilan' => $header['lokasi_pengambilan'] ?? $v['lokasi_pengambilan'],
            'id_petugas_sampel' => (int)($header['id_petugas_sampel'] ?: 1),
            'tindakan_sampel' => $header['tindakan_sampel'] ?? $v['tindakan_sampel'],
            'info_tambahan' => $header['info_tambahan'] ?? $v['info_tambahan']
        ]);

        // Update Pembayaran
        $this->db->where('id_permintaan', $id)->update('kesmas_pembayaran', [
            'jumlah_biaya' => (float)($header['jumlah_biaya'] ?? $v['jumlah_biaya']),
            'cara_bayar' => $header['cara_bayar'] ?? $v['cara_bayar'],
            'keterangan_bayar' => $header['keterangan_bayar'] ?? $v['cara_bayar_lainnya']
        ]);

        if (array_key_exists('petugas_pendaftaran_id', $header)) {
            $this->db->where('id_permintaan', $id)->update('kesmas_kartu_kendali', [
                'id_petugas_pendaftaran' => (int)($header['petugas_pendaftaran_id'] ?: 1)
            ]);
        }

        // Items diff
        $current_items = $this->get_selected_master_ids($id);
        $to_delete = array_diff($current_items, $master_ids);
        if (!empty($to_delete)) {
            $this->db->where('id_permintaan', $id)->where_in('id_master_pemeriksaan', $to_delete)->delete('kesmas_permintaan_item');
        }
        $to_add = array_diff($master_ids, $current_items);
        foreach ($to_add as $mid) {
            $this->db->insert('kesmas_permintaan_item', [
                'id_permintaan' => $id,
                'id_master_pemeriksaan' => (int)$mid,
                'created_at' => date('Y-m-d H:i:s')
            ]);
        }

        $this->db->trans_complete();
        return $this->db->trans_status() !== FALSE;
    }

    public function get_items_with_result(int $permintaan_id): array
    {
        $permintaan = $this->db->select('jenis_sampel')->get_where('kesmas_sampel', array('id_permintaan' => $permintaan_id))->row_array();
        $kategori = strtolower(str_replace(' ', '_', $permintaan['jenis_sampel'] ?? ''));
        $kelompok_order_sql = $this->_get_kelompok_order_case_sql($kategori, 'm');

        $sql = "SELECT i.id_permintaan_item, m.*, m.kelompok_pemeriksaan AS kelompok
                , h.hasil, h.keterangan, h.baku_mutu_override, h.metode_override, h.satuan_override
                , h.tgl_jam_pemeriksaan, h.tgl_jam_selesai, h.tgl_jam_lapor, h.id_petugas_sampel
                FROM kesmas_permintaan_item i
                JOIN kesmas_master_pemeriksaan m ON m.id_master_pemeriksaan = i.id_master_pemeriksaan
                LEFT JOIN kesmas_hasil h ON h.id_permintaan_item = i.id_permintaan_item
                WHERE i.id_permintaan = ?
                ORDER BY {$kelompok_order_sql} ASC, m.urutan ASC";
        return $this->db->query($sql, array($permintaan_id))->result_array();
    }

    public function get_permintaan_items_with_master($permintaan_id)
    {
        $permintaan = $this->db->select('jenis_sampel')->get_where('kesmas_sampel', array('id_permintaan' => $permintaan_id))->row_array();
        $kategori = strtolower(str_replace(' ', '_', $permintaan['jenis_sampel'] ?? ''));
        $kelompok_order_sql = $this->_get_kelompok_order_case_sql($kategori, 'm');

        $sql = "SELECT 
                    i.id_permintaan_item,
                    m.nama_pemeriksaan,
                    m.kelompok_pemeriksaan AS kelompok,
                    COALESCE(NULLIF(h.satuan_override, ''), m.satuan) AS satuan,
                    COALESCE(NULLIF(h.baku_mutu_override, ''), m.baku_mutu) AS baku_mutu,
                    COALESCE(NULLIF(h.metode_override, ''), m.metode) AS metode,
                    m.urutan
                FROM kesmas_permintaan_item i
                JOIN kesmas_master_pemeriksaan m ON m.id_master_pemeriksaan = i.id_master_pemeriksaan
                LEFT JOIN kesmas_hasil h ON h.id_permintaan_item = i.id_permintaan_item
                WHERE i.id_permintaan = ?
                ORDER BY {$kelompok_order_sql} ASC, m.urutan ASC";
        return $this->db->query($sql, array($permintaan_id))->result_array();
    }

    public function get_hasil_by_permintaan($permintaan_id)
    {
        $this->db->select('h.*');
        $this->db->where('id_permintaan', $permintaan_id);
        return $this->db->get('kesmas_hasil h')->result_array();
    }

    public function set_hasil_tms_status(int $permintaan_item_id, string $status, ?int $user_id = null): bool
    {
        $status = strtoupper(trim($status));
        if ($status !== 'MS' && $status !== 'TMS') return false;

        $exists = $this->db->get_where('kesmas_hasil', array('id_permintaan_item' => $permintaan_item_id))->row_array();
        $now = date('Y-m-d H:i:s');
        if ($exists) {
            $update = ['keterangan' => $status, 'input_by' => $user_id, 'input_at' => $now];
            if (empty($exists['tgl_jam_pemeriksaan'])) $update['tgl_jam_pemeriksaan'] = $now;
            $this->db->where('id_permintaan_item', $permintaan_item_id)->update('kesmas_hasil', $update);
            return $this->db->affected_rows() >= 0;
        }

        $item = $this->db->get_where('kesmas_permintaan_item', array('id_permintaan_item' => $permintaan_item_id))->row_array();
        if (!$item) return false;
        
        $this->db->insert('kesmas_hasil', [
            'id_permintaan' => $item['id_permintaan'],
            'id_permintaan_item' => $permintaan_item_id,
            'hasil' => '',
            'keterangan' => $status,
            'tgl_jam_pemeriksaan' => $now,
            'id_petugas_sampel' => 1,
            'input_by' => $user_id,
            'input_at' => $now,
        ]);
        return $this->db->affected_rows() > 0;
    }

    public function list_petugas(): array
    {
        $this->db->where('is_active', 1)->order_by('nama','ASC');
        return $this->db->get('kesmas_petugas_sampel')->result_array();
    }

    public function save_hasil(int $permintaan_id, array $hasil_items, array $keterangan_items, array $payload_permintaan, ?int $user_id): bool
    {
        $this->db->trans_start();

        $petugas_id = isset($payload_permintaan['id_petugas_sampel']) && $payload_permintaan['id_petugas_sampel'] !== ''
            ? (int)$payload_permintaan['id_petugas_sampel'] : null;

        if (array_key_exists('id_petugas_sampel', $payload_permintaan)) {
            $this->db->where('id_permintaan', $permintaan_id)->update('kesmas_sampel', [
                'id_petugas_sampel' => $petugas_id,
            ]);
        }

        $tgl_pemeriksaan = $payload_permintaan['tgl_jam_pemeriksaan'] ?? null;
        $tgl_selesai = $payload_permintaan['tgl_jam_selesai'] ?? null;
        $tgl_lapor = $payload_permintaan['tgl_jam_lapor'] ?? null;
        $baku_overrides   = $payload_permintaan['baku_mutu_override'] ?? [];
        $metode_overrides = $payload_permintaan['metode_override'] ?? [];
        $satuan_overrides = $payload_permintaan['satuan_override'] ?? [];

        foreach ($hasil_items as $permintaan_item_id => $hasil_value) {
            $exists = $this->db->get_where('kesmas_hasil', array('id_permintaan_item' => (int)$permintaan_item_id))->row_array();
            $provided_ket = $keterangan_items[$permintaan_item_id] ?? null;
            $final_ket = $provided_ket ?: ($exists['keterangan'] ?? null);

            $baku_ov   = isset($baku_overrides[$permintaan_item_id]) && $baku_overrides[$permintaan_item_id] !== '' ? $baku_overrides[$permintaan_item_id] : ($exists['baku_mutu_override'] ?? '');
            $metode_ov = isset($metode_overrides[$permintaan_item_id]) && $metode_overrides[$permintaan_item_id] !== '' ? $metode_overrides[$permintaan_item_id] : ($exists['metode_override'] ?? '');
            $satuan_ov = isset($satuan_overrides[$permintaan_item_id]) && $satuan_overrides[$permintaan_item_id] !== '' ? $satuan_overrides[$permintaan_item_id] : ($exists['satuan_override'] ?? '');

            $row = [
                'id_permintaan' => $permintaan_id,
                'id_permintaan_item' => (int)$permintaan_item_id,
                'hasil' => ($hasil_value === '' ? '' : $hasil_value),
                'keterangan' => $final_ket ?: '',
                'baku_mutu_override' => $baku_ov ?: '',
                'metode_override'    => $metode_ov ?: '',
                'satuan_override'    => $satuan_ov ?: '',
                'tgl_jam_pemeriksaan' => $tgl_pemeriksaan ?: ($exists['tgl_jam_pemeriksaan'] ?? '1970-01-01 00:00:00'),
                'tgl_jam_selesai' => $tgl_selesai ?: ($exists['tgl_jam_selesai'] ?? '1970-01-01 00:00:00'),
                'tgl_jam_lapor' => $tgl_lapor ?: ($exists['tgl_jam_lapor'] ?? '1970-01-01 00:00:00'),
                'id_petugas_sampel' => $petugas_id ?: ($exists['id_petugas_sampel'] ?? 1),
                'input_by' => (int)$user_id,
                'input_at' => date('Y-m-d H:i:s'),
            ];

            if ($exists) {
                $this->db->where('id_permintaan_item', (int)$permintaan_item_id)->update('kesmas_hasil', $row);
            } else {
                $this->db->insert('kesmas_hasil', $row);
            }
        }

        if (($petugas_id || $tgl_pemeriksaan || $tgl_selesai || $tgl_lapor) && empty($hasil_items)) {
            $all_items = $this->db->select('id_permintaan_item')->get_where('kesmas_permintaan_item', ['id_permintaan' => $permintaan_id])->result_array();
            foreach ($all_items as $item) {
                $permintaan_item_id = (int)$item['id_permintaan_item'];
                $exists = $this->db->get_where('kesmas_hasil', array('id_permintaan_item' => $permintaan_item_id))->row_array();
                
                $row = [
                    'id_permintaan' => $permintaan_id,
                    'id_permintaan_item' => $permintaan_item_id,
                    'hasil' => '',
                    'keterangan' => $exists['keterangan'] ?? '',
                    'tgl_jam_pemeriksaan' => $tgl_pemeriksaan ?: ($exists['tgl_jam_pemeriksaan'] ?? '1970-01-01 00:00:00'),
                    'tgl_jam_selesai' => $tgl_selesai ?: ($exists['tgl_jam_selesai'] ?? '1970-01-01 00:00:00'),
                    'tgl_jam_lapor' => $tgl_lapor ?: ($exists['tgl_jam_lapor'] ?? '1970-01-01 00:00:00'),
                    'id_petugas_sampel' => $petugas_id ?: ($exists['id_petugas_sampel'] ?? 1),
                    'input_by' => (int)$user_id,
                    'input_at' => date('Y-m-d H:i:s'),
                ];
                if ($exists) {
                    $this->db->where('id_permintaan_item', $permintaan_item_id)->update('kesmas_hasil', $row);
                } else {
                    $this->db->insert('kesmas_hasil', $row);
                }
            }
        }

        $this->db->where('id_permintaan', $permintaan_id)->update('kesmas_kartu_kendali', [
            'tgl_input_hasil' => date('Y-m-d H:i:s')
        ]);
        
        if (array_key_exists('info_tambahan', $payload_permintaan)) {
            $this->db->where('id_permintaan', $permintaan_id)->update('kesmas_sampel', [
                'info_tambahan' => $payload_permintaan['info_tambahan'] ?? ''
            ]);
        }

        $this->db->trans_complete();
        return $this->db->trans_status() !== FALSE;
    }

    private function _get_kelompok_order_case_sql(string $kategori, string $table_alias = ''): string
    {
        $col = empty($table_alias) ? 'kelompok_pemeriksaan' : $table_alias . '.kelompok_pemeriksaan';
        $case_sql = "CASE {$col} ";
        $kategori = strtolower(str_replace([' / ', ' '], ['_', '_'], $kategori));
        switch ($kategori) {
            case 'air_minum':
                $case_sql .= "WHEN 'Fisika' THEN 1 WHEN 'Kimia Wajib' THEN 2 WHEN 'Kimia Khusus' THEN 3 WHEN 'Bakteriologi' THEN 4 "; break;
            case 'air_bersih':
            case 'air_bersih_air_sanitasi':
                $case_sql .= "WHEN 'Fisika' THEN 1 WHEN 'Kimia' THEN 2 WHEN 'Bakteriologi' THEN 3 "; break;
            case 'makanan':
            case 'makanan_dan_minuman':
                $case_sql .= "WHEN 'Kimia' THEN 1 WHEN 'Bakteriologi' THEN 2 WHEN 'Parasitologi' THEN 3 "; break;
            case 'lingkungan':
                $case_sql .= "WHEN 'Fisika' THEN 1 WHEN 'Kimia' THEN 1 WHEN 'Bakteriologi' THEN 1 WHEN 'Pemeriksaan' THEN 1 "; break;
            default:
                $case_sql .= "WHEN '__dummy__' THEN 99 "; break;
        }
        $case_sql .= "ELSE 99 END";
        return $case_sql;
    }

    public function terima_sampel(int $id, ?int $user_id): bool
    {
        // Try fetch existing no_registrasi to not overwrite
        $exist = $this->db->get_where('kesmas_terima_sampel', ['id_permintaan' => $id])->row();
        if(!$exist) {
            $this->db->insert('kesmas_terima_sampel', [
                'id_permintaan' => $id,
                'no_registrasi' => $this->gen_no_registrasi(), // Generate if not present
                'diterima_oleh' => (int)$user_id
            ]);
        }
        $this->db->where('id_permintaan', $id)->update('kesmas_permintaan', [
            'updated_by' => $user_id
        ]);
        return true;
    }

    public function tolak_sampel(int $id, ?int $user_id): bool
    {
        return $this->delete_permintaan((int)$id);
    }

    public function delete_permintaan(int $permintaan_id): bool
    {
        $this->db->trans_start();
        $this->db->delete('kesmas_hasil', array('id_permintaan' => $permintaan_id));
        $this->db->delete('kesmas_permintaan_item', array('id_permintaan' => $permintaan_id));
        $this->db->delete('kesmas_pembayaran', array('id_permintaan' => $permintaan_id));
        $this->db->delete('kesmas_kartu_kendali', array('id_permintaan' => $permintaan_id));
        $this->db->delete('kesmas_terima_sampel', array('id_permintaan' => $permintaan_id));
        $this->db->delete('kesmas_sampel', array('id_permintaan' => $permintaan_id));
        
        $v = $this->db->get_where('kesmas_permintaan', ['id_permintaan' => $permintaan_id])->row_array();
        $this->db->delete('kesmas_permintaan', array('id_permintaan' => $permintaan_id));
        if($v) {
            $this->db->delete('kesmas_pelanggan', array('id_pelanggan' => $v['id_pelanggan']));
        }
        $this->db->trans_complete();
        return $this->db->trans_status() !== FALSE;
    }

    public function add_petugas($data) { return $this->db->insert('kesmas_petugas_sampel', $data); }
    public function get_petugas_by_id(int $id) { return $this->db->get_where('kesmas_petugas_sampel', array('id_petugas_sampel' => $id))->row_array(); }
    public function update_petugas(int $id, array $data) { $this->db->where('id_petugas_sampel', $id); return $this->db->update('kesmas_petugas_sampel', $data); }
    public function delete_petugas(int $id) { $this->db->where('id_petugas_sampel', $id); return $this->db->delete('kesmas_petugas_sampel'); }
    public function get_petugas_name_by_id(int $petugas_id): ?string {
        $result = $this->db->select('nama')->get_where('kesmas_petugas_sampel', ['id_petugas_sampel' => $petugas_id])->row_array();
        return $result['nama'] ?? null;
    }

    public function update_kaji_ulang(int $id, array $data): bool
    {
        $this->db->trans_start();

        // 1. KESMAS PERMINTAAN
        $permintaan_update = [];
        if (isset($data['status_kelayakan'])) $permintaan_update['status_kelayakan'] = $data['status_kelayakan'];
        if (isset($data['catatan'])) $permintaan_update['catatan'] = $data['catatan'];
        if (array_key_exists('alasan_tidak_layak', $data)) {
            $alasan = is_array($data['alasan_tidak_layak']) ? $data['alasan_tidak_layak'] : [];
            $permintaan_update['alasan_tidak_layak'] = !empty($alasan) ? json_encode($alasan) : '';
        }
        if (!empty($permintaan_update)) {
            $permintaan_update['updated_at'] = date('Y-m-d H:i:s');
            $this->db->where('id_permintaan', $id)->update('kesmas_permintaan', $permintaan_update);
        }

        // 2. KESMAS SAMPEL
        $sampel_update = [];
        if (isset($data['tgl_pengambilan'])) $sampel_update['tgl_pengambilan'] = $data['tgl_pengambilan'] ?: null;
        if (isset($data['jam_pengambilan'])) $sampel_update['jam_pengambilan'] = $data['jam_pengambilan'] ?: null;
        if (array_key_exists('id_petugas_sampel', $data)) {
            $sampel_update['id_petugas_sampel'] = $data['id_petugas_sampel'] !== '' ? (int)$data['id_petugas_sampel'] : null;
        }
        if (isset($data['info_tambahan'])) $sampel_update['info_tambahan'] = $data['info_tambahan'];
        if (isset($data['tindakan_sampel'])) $sampel_update['tindakan_sampel'] = $data['tindakan_sampel'];
        if (!empty($sampel_update)) {
            $this->db->where('id_permintaan', $id)->update('kesmas_sampel', $sampel_update);
        }

        // 3. KESMAS PEMBAYARAN
        $bayar_update = [];
        if (isset($data['jumlah_biaya'])) $bayar_update['jumlah_biaya'] = $data['jumlah_biaya'];
        if (isset($data['cara_bayar'])) $bayar_update['cara_bayar'] = $data['cara_bayar'];
        if (isset($data['keterangan_bayar'])) $bayar_update['keterangan_bayar'] = $data['keterangan_bayar'];
        if (!empty($bayar_update)) {
            $this->db->where('id_permintaan', $id)->update('kesmas_pembayaran', $bayar_update);
        }

        // 4. KESMAS KARTU KENDALI
        $kk_update = [];
        $kk_dates = ['kk_tgl_pengambilan', 'tgl_diterima_lab', 'tgl_pengerjaan', 'tgl_input_hasil', 'tgl_cetak'];
        foreach ($kk_dates as $f) {
            if (isset($data[$f])) {
                $kk_update[$f] = $data[$f] ? str_replace('T', ' ', $data[$f]) : null;
            }
        }
        
        $kk_petugas = [
            'petugas_pendaftaran_id' => 'id_petugas_pendaftaran',
            'petugas_pengambil_ttd_id' => 'id_petugas_pengambil_ttd',
            'petugas_verifikasi_id' => 'id_petugas_verifikasi',
            'petugas_validasi_id' => 'id_pj_teknis_validasi'
        ];
        foreach ($kk_petugas as $in => $out) {
            if (array_key_exists($in, $data) && $data[$in] !== '') {
                $kk_update[$out] = (int)$data[$in];
            }
        }

        if (!empty($kk_update)) {
            $this->db->where('id_permintaan', $id)->update('kesmas_kartu_kendali', $kk_update);
        }

        $this->db->trans_complete();
        return $this->db->trans_status() !== FALSE;
    }

    // =========================================================
    // MASTER PEMERIKSAAN CRUD (Menggunakan View & ID lookup)
    // =========================================================

    public function get_all_master(array $filters = []): array
    {
        $this->db->select('m.*');
        $this->db->from('kesmas_master_pemeriksaan m');
        
        if (!empty($filters['kategori'])) $this->db->where('m.kategori_sampel', $filters['kategori']);
        if (!empty($filters['kelompok'])) $this->db->where('m.kelompok_pemeriksaan', $filters['kelompok']);
        if (isset($filters['is_active']) && $filters['is_active'] !== '') $this->db->where('m.is_active', (int)$filters['is_active']);
        if (!empty($filters['q'])) {
            $this->db->group_start()->like('m.nama_pemeriksaan', $filters['q'])->or_like('m.kelompok_pemeriksaan', $filters['q'])->or_like('m.satuan', $filters['q'])->or_like('m.metode', $filters['q'])->group_end();
        }
        $this->db->order_by('m.kategori_sampel', 'ASC');
        $this->db->order_by('m.urutan', 'ASC');
        
        $rows = $this->db->get()->result_array();
        
        // Populate 'kode' for backward compatibility in controllers
        foreach ($rows as &$row) {
            $kat = strtolower($row['kategori_sampel'] ?? '');
            if (str_contains($kat, 'air minum')) $row['kode'] = 'air_minum';
            elseif (str_contains($kat, 'air bersih')) $row['kode'] = 'air_bersih';
            elseif (str_contains($kat, 'makan')) $row['kode'] = 'makanan';
            elseif (str_contains($kat, 'lingkungan')) $row['kode'] = 'lingkungan';
            else $row['kode'] = 'lainnya';
        }
        
        return $rows;
    }

    public function get_master_by_id(int $id): ?array {
        $this->db->select('m.*');
        $row = $this->db->get_where('kesmas_master_pemeriksaan m', ['m.id_master_pemeriksaan' => $id])->row_array();
        if ($row) {
            $kat = strtolower($row['kategori_sampel'] ?? '');
            if (str_contains($kat, 'air minum')) $row['kode'] = 'air_minum';
            elseif (str_contains($kat, 'air bersih')) $row['kode'] = 'air_bersih';
            elseif (str_contains($kat, 'makan')) $row['kode'] = 'makanan';
            elseif (str_contains($kat, 'lingkungan')) $row['kode'] = 'lingkungan';
            else $row['kode'] = 'lainnya';
        }
        return $row ?: null;
    }

    public function insert_master(array $data): bool {
        unset($data['nama_pemeriksaan_norm']);
        
        if (empty($data['urutan'])) {
            $max = $this->db->select_max('urutan')
                ->where('kategori_sampel', $data['kategori'])
                ->where('kelompok_pemeriksaan', $data['kelompok'])
                ->get('kesmas_master_pemeriksaan')->row();
            $data['urutan'] = ($max && $max->urutan) ? (int)$max->urutan + 1 : 1;
        }
        
        $data['kategori_sampel'] = $data['kategori'];
        $data['kelompok_pemeriksaan'] = $data['kelompok'];
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['is_active']  = 1;
        
        $allowed = ['kategori_sampel', 'kelompok_pemeriksaan', 'nama_pemeriksaan', 'satuan', 'baku_mutu', 'metode', 'urutan', 'is_active', 'created_at'];
        $insert_data = array_intersect_key($data, array_flip($allowed));
        
        return $this->db->insert('kesmas_master_pemeriksaan', $insert_data);
    }

    public function update_master(int $id, array $data): bool {
        unset($data['nama_pemeriksaan_norm']);
        $old = $this->get_master_by_id($id);
        if (isset($data['urutan']) && empty($data['urutan'])) $data['urutan'] = $old['urutan'];
        
        if (isset($data['kategori'])) $data['kategori_sampel'] = $data['kategori'];
        if (isset($data['kelompok'])) $data['kelompok_pemeriksaan'] = $data['kelompok'];
        
        $allowed = ['kategori_sampel', 'kelompok_pemeriksaan', 'nama_pemeriksaan', 'satuan', 'baku_mutu', 'metode', 'urutan', 'is_active'];
        $update_data = array_intersect_key($data, array_flip($allowed));
        
        $this->db->where('id_master_pemeriksaan', $id);
        return $this->db->update('kesmas_master_pemeriksaan', $update_data);
    }

    public function is_master_in_use(int $id): bool {
        return $this->db->where('id_master_pemeriksaan', $id)->count_all_results('kesmas_permintaan_item') > 0;
    }

    public function toggle_master_active(int $id): bool {
        $master = $this->get_master_by_id($id);
        if (!$master) return false;
        $this->db->where('id_master_pemeriksaan', $id);
        return $this->db->update('kesmas_master_pemeriksaan', ['is_active' => $master['is_active'] ? 0 : 1]);
    }

    public function delete_master(int $id): bool {
        return $this->db->where('id_master_pemeriksaan', $id)->delete('kesmas_master_pemeriksaan');
    }

    public function check_duplicate_master(string $nama, string $kat, string $kel, ?int $exclude_id = null): bool {
        $this->db->where('nama_pemeriksaan', $nama);
        $this->db->where('kategori_sampel', $kat);
        $this->db->where('kelompok_pemeriksaan', $kel);
        if ($exclude_id) $this->db->where('id_master_pemeriksaan !=', $exclude_id);
        return $this->db->count_all_results('kesmas_master_pemeriksaan') > 0;
    }

    public function check_duplicate_urutan(string $kat, string $kel, int $urutan, ?int $exclude_id = null): bool {
        $this->db->where('kategori_sampel', $kat);
        $this->db->where('kelompok_pemeriksaan', $kel);
        $this->db->where('urutan', $urutan);
        if ($exclude_id) $this->db->where('id_master_pemeriksaan !=', $exclude_id);
        return $this->db->count_all_results('kesmas_master_pemeriksaan') > 0;
    }

    public function duplicate_master(int $id, string $kat, string $kel): bool {
        $old = $this->db->get_where('kesmas_master_pemeriksaan', ['id_master_pemeriksaan' => $id])->row_array();
        if(!$old) return false;
        unset($old['id_master_pemeriksaan']);
        $old['kategori_sampel'] = $kat;
        $old['kelompok_pemeriksaan'] = $kel;
        
        $max = $this->db->select_max('urutan')->where('kategori_sampel', $kat)->where('kelompok_pemeriksaan', $kel)->get('kesmas_master_pemeriksaan')->row();
        $old['urutan'] = ($max && $max->urutan) ? (int)$max->urutan + 1 : 1;
        
        return $this->db->insert('kesmas_master_pemeriksaan', $old);
    }

    public function reorder_master(array $orders): bool {
        $this->db->trans_start();
        foreach ($orders as $id => $urutan) {
            $this->db->where('id_master_pemeriksaan', (int)$id)->update('kesmas_master_pemeriksaan', ['urutan' => (int)$urutan]);
        }
        $this->db->trans_complete();
        return $this->db->trans_status() !== FALSE;
    }

    // ============================================================
    //  LAPORAN PER PELANGGAN  (metode baru, tidak ubah yang lama)
    // ============================================================

    /**
     * Daftar semua pelanggan yang memiliki permintaan,
     * beserta jumlah permintaan dan jumlah item pemeriksaan.
     * Mendukung filter: q (nama/instansi), dari, sampai, jenis_sampel.
     */
    public function list_pelanggan_dengan_permintaan(array $filters = []): array
    {
        // Jika NIK diisi → gabungkan baris yang NIK-nya sama menjadi satu
        // Jika NIK kosong → tampilkan per id_pelanggan seperti biasa
        $this->db->select("
            MIN(pel.id_pelanggan)                  AS id_pelanggan,
            MAX(pel.nik)                           AS nik,
            MIN(pel.nama)                          AS nama,
            MIN(pel.instansi)                      AS instansi,
            MIN(pel.alamat)                        AS alamat,
            MIN(pel.telp)                          AS telp,
            COUNT(DISTINCT p.id_permintaan)        AS jumlah_permintaan,
            COUNT(DISTINCT pi.id_permintaan_item)  AS jumlah_item,
            MAX(p.tgl_permintaan)                  AS tgl_terakhir
        ", false);
        $this->db->from('kesmas_pelanggan pel');
        $this->db->join('kesmas_permintaan p',       'p.id_pelanggan = pel.id_pelanggan',  'inner');
        $this->db->join('kesmas_terima_sampel t',    'p.id_permintaan = t.id_permintaan',  'inner');
        $this->db->join('kesmas_sampel s',           'p.id_permintaan = s.id_permintaan',  'left');
        $this->db->join('kesmas_permintaan_item pi', 'p.id_permintaan = pi.id_permintaan', 'left');

        if (!empty($filters['q'])) {
            $q = $filters['q'];
            $this->db->group_start()
                ->like('pel.nama', $q)
                ->or_like('pel.nik', $q)
                ->or_like('pel.instansi', $q)
                ->or_like('pel.alamat', $q)
                ->group_end();
        }
        if (!empty($filters['dari']))         $this->db->where('p.tgl_permintaan >=', $filters['dari']);
        if (!empty($filters['sampai']))       $this->db->where('p.tgl_permintaan <=', $filters['sampai']);
        if (!empty($filters['jenis_sampel'])) $this->db->like('s.jenis_sampel', $filters['jenis_sampel']);

        // GROUP BY nik jika terisi, GROUP BY id_pelanggan jika nik kosong
        $this->db->group_by('CASE WHEN pel.nik != \'\'  THEN pel.nik ELSE CAST(pel.id_pelanggan AS CHAR) END', false);
        $this->db->order_by('nama', 'ASC');
        return $this->db->get()->result_array();
    }

    /**
     * Ambil satu pelanggan berdasarkan id.
     */
    public function get_pelanggan_by_id(int $id): ?array
    {
        $row = $this->db->get_where('kesmas_pelanggan', ['id_pelanggan' => $id])->row_array();
        return $row ?: null;
    }

    /**
     * Ambil semua permintaan beserta detail hasil untuk satu pelanggan.
     * Jika pelanggan punya NIK, ambil semua permintaan dari pelanggan ber-NIK sama.
     * Mendukung filter: dari, sampai, jenis_sampel.
     */
    public function get_permintaan_by_pelanggan(int $id_pelanggan, array $filters = []): array
    {
        // Cek apakah pelanggan ini punya NIK
        $pel = $this->db->get_where('kesmas_pelanggan', ['id_pelanggan' => $id_pelanggan])->row_array();
        $nik = trim($pel['nik'] ?? '');

        // Query semua permintaan pelanggan
        $this->db->select("
            p.id_permintaan,
            t.no_registrasi,
            p.tgl_permintaan,
            s.nama_sampel,
            s.jenis_sampel,
            s.lokasi_pengambilan,
            s.tgl_pengambilan,
            s.jam_pengambilan,
            p.status_kelayakan,
            k.tgl_input_hasil,
            k.tgl_cetak,
            pb.jumlah_biaya,
            pb.status_bayar
        ", false);
        $this->db->from('kesmas_permintaan p');
        $this->db->join('kesmas_terima_sampel t',  'p.id_permintaan = t.id_permintaan',  'inner');
        $this->db->join('kesmas_sampel s',         'p.id_permintaan = s.id_permintaan',  'left');
        $this->db->join('kesmas_kartu_kendali k',  'p.id_permintaan = k.id_permintaan',  'left');
        $this->db->join('kesmas_pembayaran pb',    'p.id_permintaan = pb.id_permintaan', 'left');
        $this->db->join('kesmas_pelanggan pel',    'p.id_pelanggan = pel.id_pelanggan',  'inner');

        if ($nik !== '') {
            // Gabungkan semua pelanggan dengan NIK yang sama
            $this->db->where('pel.nik', $nik);
        } else {
            $this->db->where('p.id_pelanggan', $id_pelanggan);
        }

        if (!empty($filters['dari']))         $this->db->where('p.tgl_permintaan >=', $filters['dari']);
        if (!empty($filters['sampai']))       $this->db->where('p.tgl_permintaan <=', $filters['sampai']);
        if (!empty($filters['jenis_sampel'])) $this->db->like('s.jenis_sampel', $filters['jenis_sampel']);

        $this->db->order_by('t.no_registrasi', 'ASC');
        $permintaan_rows = $this->db->get()->result_array();

        // Untuk setiap permintaan, ambil detail item+hasil
        foreach ($permintaan_rows as &$row) {
            $pid = (int)$row['id_permintaan'];
            $sql = "SELECT
                        m.nama_pemeriksaan,
                        m.satuan,
                        m.baku_mutu,
                        m.metode,
                        m.kelompok_pemeriksaan AS kelompok,
                        h.hasil,
                        h.keterangan AS tms_status,
                        h.tgl_jam_pemeriksaan,
                        h.tgl_jam_selesai,
                        h.tgl_jam_lapor
                    FROM kesmas_permintaan_item pi
                    LEFT JOIN kesmas_master_pemeriksaan m  ON m.id_master_pemeriksaan = pi.id_master_pemeriksaan
                    LEFT JOIN kesmas_hasil h               ON h.id_permintaan_item = pi.id_permintaan_item
                    WHERE pi.id_permintaan = ?
                    ORDER BY m.urutan ASC";
            $row['items'] = $this->db->query($sql, [$pid])->result_array();
            $row['kategori_sampel'] = $this->_kategori_sampel_label((int)($row['id_kategori_sampel'] ?? 0));
        }
        unset($row);

        return $permintaan_rows;
    }
}

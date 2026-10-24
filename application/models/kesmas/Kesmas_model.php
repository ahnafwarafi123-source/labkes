<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Kesmas_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        try {
            $this->ensure_pelanggan_wilayah_columns();
        } catch (Throwable $e) {
            // Hak ALTER TABLE tidak tersedia / migrasi gagal: jangan blokir aplikasi.
            log_message('error', 'Auto-migrate kolom pelanggan gagal: ' . $e->getMessage());
        }
    }

    /**
     * Pastikan kolom wilayah (kecamatan/kelurahan) dan objek tersedia pada tabel pelanggan.
     */
    private function ensure_pelanggan_wilayah_columns()
    {
        $table = 'kesmas_pelanggan';
        if (!$this->db->table_exists($table)) {
            return;
        }

        $this->load->dbforge();
        $new = [];
        if (!$this->db->field_exists('kecamatan', $table)) {
            $new['kecamatan'] = ['type' => 'VARCHAR', 'constraint' => '255', 'null' => TRUE];
        }
        if (!$this->db->field_exists('kelurahan', $table)) {
            $new['kelurahan'] = ['type' => 'VARCHAR', 'constraint' => '255', 'null' => TRUE];
        }
        if (!$this->db->field_exists('objek', $table)) {
            $new['objek'] = ['type' => 'VARCHAR', 'constraint' => '100', 'null' => TRUE];
        }
        if (!empty($new)) {
            if (!$this->dbforge->add_column($table, $new)) {
                log_message('error', 'Gagal menambah kolom pada tabel pelanggan: ' . json_encode($this->db->error()));
                return;
            }
        }
    }
    public function gen_no_registrasi(): string
    {
        $ymd = date('Ymd');
        $prefix = 'KESMAS.' . $ymd . '.';
        $this->db->like('no_registrasi', $prefix, 'after');
        $this->db->from('kesmas_penerimaan_sampel');
        $count = $this->db->count_all_results();
        $num = $count + 1;
        return $prefix . str_pad((string)$num, 4, '0', STR_PAD_LEFT);
    }

    private function _build_permintaan_query()
    {
        $this->db->select("
            kesmas_permintaan.id_permintaan,
            kesmas_pelanggan.id_pelanggan,
            kesmas_pelanggan.nik,
            kesmas_pelanggan.nama,
            kesmas_pelanggan.alamat,
            kesmas_pelanggan.telp,
            kesmas_pelanggan.instansi,
            kesmas_pelanggan.kecamatan,
            kesmas_pelanggan.kelurahan,
            kesmas_pelanggan.objek,
            kesmas_permintaan.tgl_permintaan,
            kesmas_permintaan.nama_sampel,
            kesmas_permintaan.kategori_sampel as id_kategori_sampel,
            kesmas_permintaan.kategori_sampel,
            kesmas_permintaan.jenis_sampel,
            kesmas_permintaan.volume_ml,
            kesmas_permintaan.tgl_pengambilan,
            kesmas_permintaan.jam_pengambilan,
            kesmas_permintaan.lokasi_pengambilan,
            kesmas_permintaan.petugas_pendaftaran_id,
            kesmas_permintaan.ttd,
            kesmas_permintaan.info_tambahan,
            kesmas_pengujian_sampel.catatan_kaki,
            kesmas_penerimaan_sampel.no_registrasi,
            kesmas_penerimaan_sampel.tgl_registrasi as diterima_at,
            kesmas_penerimaan_sampel.tindakan_sampel,
            kesmas_penerimaan_sampel.kelayakan as status_kelayakan,

            kesmas_penerimaan_sampel.alasan as catatan,
            kesmas_penerimaan_sampel.status,
            kesmas_penerimaan_sampel.id_petugas as id_petugas,
            kesmas_penerimaan_sampel.id_petugas_2 as id_petugas_2,
            kesmas_penerimaan_sampel.no_registrasi,
            kesmas_pembayaran.id_pembayaran,
            kesmas_pembayaran.tgl_pembayaran,
            kesmas_pembayaran.biaya as jumlah_biaya,
            kesmas_pembayaran.metode_pembayaran as cara_bayar,
            kesmas_pembayaran.keterangan as keterangan_bayar,
            kesmas_pengujian_sampel.id_pengujian,
            kesmas_pengujian_sampel.tgl_mulai as tgl_pengerjaan,
            kesmas_pengujian_sampel.tgl_selesai as tgl_input_hasil,
            kesmas_pengujian_sampel.tgl_pelaporan as tgl_cetak,
            kesmas_pengujian_sampel.id_petugas as petugas_pengambil_id,
            kesmas_pengujian_sampel.id_penanggung_jawab as petugas_validasi_id
        ", false);
        $this->db->from('kesmas_permintaan');
        $this->db->join('kesmas_pelanggan', 'kesmas_permintaan.id_pelanggan = kesmas_pelanggan.id_pelanggan', 'left');
        $this->db->join('kesmas_penerimaan_sampel', 'kesmas_permintaan.id_permintaan = kesmas_penerimaan_sampel.id_permintaan', 'left');
        $this->db->join('kesmas_pembayaran', 'kesmas_penerimaan_sampel.no_registrasi = kesmas_pembayaran.no_registrasi', 'left');
        $this->db->join('kesmas_pengujian_sampel', 'kesmas_pembayaran.id_pembayaran = kesmas_pengujian_sampel.id_pembayaran', 'left');
    }

    public function list_permintaan(array $filters = [])
    {
        $this->_build_permintaan_query();
        $this->db->select('(SELECT COUNT(id_master_pemeriksaan) FROM kesmas_item_permintaan WHERE id_permintaan = kesmas_permintaan.id_permintaan) AS jumlah_pemeriksaan', false);

        if (!empty($filters['q'])) {
            $q = $filters['q'];
            $this->db->group_start()
                ->like('kesmas_penerimaan_sampel.no_registrasi', $q)
                ->or_like('kesmas_permintaan.nama_sampel', $q)
                ->or_like('kesmas_permintaan.jenis_sampel', $q)
                ->or_like('kesmas_pelanggan.nama', $q)
                ->or_like('kesmas_pelanggan.instansi', $q)
                ->group_end();
        }
        if (!empty($filters['dari'])) $this->db->where('kesmas_permintaan.tgl_permintaan >=', $filters['dari']);
        if (!empty($filters['sampai'])) $this->db->where('kesmas_permintaan.tgl_permintaan <=', $filters['sampai']);
        if (isset($filters['is_diterima'])) $this->db->where('(CASE WHEN kesmas_penerimaan_sampel.no_registrasi IS NOT NULL THEN 1 ELSE 0 END) =', (int)$filters['is_diterima'], false);
        if (!empty($filters['only_layak'])) $this->db->where('kesmas_penerimaan_sampel.kelayakan', 'Layak');
        if (!empty($filters['exclude_ditolak'])) {
            $this->db->where("(kesmas_penerimaan_sampel.status IS NULL OR kesmas_penerimaan_sampel.status != 'Ditolak')", null, false);
        }

        $this->db->order_by('kesmas_penerimaan_sampel.no_registrasi', 'DESC');
        $rows = $this->db->get()->result_array();
        return $rows;
    }

    public function get_permintaan(int $id)
    {
        $this->_build_permintaan_query();
        $this->db->where('kesmas_permintaan.id_permintaan', $id);
        $row = $this->db->get()->row_array();
        return $row;
    }

    public function check_duplicate_no_registrasi(string $no_reg, ?int $exclude_id = null): bool
    {
        $this->db->join('kesmas_permintaan', 'kesmas_permintaan.id_permintaan = kesmas_penerimaan_sampel.id_permintaan', 'inner');
        $this->db->where('kesmas_penerimaan_sampel.no_registrasi', $no_reg);
        if ($exclude_id) {
            $this->db->where('kesmas_penerimaan_sampel.id_permintaan !=', $exclude_id);
        }
        return $this->db->count_all_results('kesmas_penerimaan_sampel') > 0;
    }

    public function get_master_grouped(string $kategori)
    {
        $map = [
            'air_minum' => 'Air Minum',
            'air_bersih' => 'Air Bersih / Air Sanitasi',
            'makanan' => 'Makanan',
            'lingkungan' => 'Lingkungan'
        ];
        $kategori_sampel = $map[$kategori] ?? $kategori;

        $this->db->select('kesmas_master_pemeriksaan.*');
        $this->db->from('kesmas_master_pemeriksaan');
        $this->db->where('kesmas_master_pemeriksaan.kategori', $kategori_sampel);
        $this->db->where('kesmas_master_pemeriksaan.is_active', 1);
        $this->db->order_by('kesmas_master_pemeriksaan.urutan', 'ASC');
        $rows = $this->db->get()->result_array();
        $out = array();
        foreach ($rows as $r) {
            $out[$r['kelompok_pemeriksaan']][] = $r;
        }
        return $out;
    }

    public function get_selected_master_ids(int $permintaan_id): array
    {
        $this->db->select('id_master_pemeriksaan');
        $rows = $this->db->get_where('kesmas_item_permintaan', array('id_permintaan' => $permintaan_id))->result_array();
        return array_map(function ($r) {
            return (int)$r['id_master_pemeriksaan'];
        }, $rows);
    }

    public function save_permintaan(array $header, array $master_ids, ?int $user_id): int
    {
        $this->db->trans_start();

        // 1. Pelanggan
        $nik      = $header['nik'] ?? '';
        $nama     = $header['nama'] ?? '';
        $alamat   = $header['alamat'] ?? '';
        $telp     = $header['telp'] ?? '';
        $instansi = $header['instansi'] ?? '';

        $pelanggan = null;
        if (!empty($nik)) {
            $pelanggan = $this->db->get_where('kesmas_pelanggan', ['nik' => $nik])->row_array();
        }
        if (!$pelanggan && !empty($nama)) {
            $pelanggan = $this->db->get_where('kesmas_pelanggan', ['nama' => $nama, 'instansi' => $instansi])->row_array();
        }

        if ($pelanggan) {
            $id_pelanggan = $pelanggan['id_pelanggan'];
            // Update data in case of address/telp changes
            $this->db->where('id_pelanggan', $id_pelanggan)->update('kesmas_pelanggan', [
                'alamat' => $alamat,
                'telp'   => $telp,
                'nik'    => !empty($nik) ? $nik : $pelanggan['nik'],
                'kecamatan' => !empty($header['kecamatan']) ? $header['kecamatan'] : ($pelanggan['kecamatan'] ?? null),
                'kelurahan' => !empty($header['kelurahan']) ? $header['kelurahan'] : ($pelanggan['kelurahan'] ?? null),
                'objek'     => !empty($header['objek']) ? $header['objek'] : ($pelanggan['objek'] ?? null)
            ]);
        } else {
            $this->db->insert('kesmas_pelanggan', [
                'nik'      => $nik,
                'nama'     => $nama,
                'alamat'   => $alamat,
                'telp'     => $telp,
                'instansi' => $instansi,
                'kecamatan' => $header['kecamatan'] ?? null,
                'kelurahan' => $header['kelurahan'] ?? null,
                'objek'     => $header['objek'] ?? null
            ]);
            $id_pelanggan = $this->db->insert_id();
        }

        // 2. Permintaan
        $this->db->insert('kesmas_permintaan', [
            'id_pelanggan' => $id_pelanggan,
            'nama_sampel' => $header['nama_sampel'] ?? '',
            'tgl_permintaan' => $header['tgl_permintaan'] ?? date('Y-m-d'),
            'kategori_sampel' => $header['id_kategori_sampel'] ?? '',
            'jenis_sampel' => $header['jenis_sampel'] ?? '',
            'volume_ml' => (int)($header['volume_ml'] ?? 0),
            'tgl_pengambilan' => $header['tgl_pengambilan'] ?? null,
            'jam_pengambilan' => $header['jam_pengambilan'] ?? null,
            'lokasi_pengambilan' => $header['lokasi_pengambilan'] ?? '',
            'petugas_pendaftaran_id' => $header['petugas_pendaftaran_id'] ?? null,
            'ttd' => $header['ttd'] ?? '',
            'info_tambahan' => $header['info_tambahan'] ?? ''
        ]);
        $id_permintaan = $this->db->insert_id();

        // 3. Item Permintaan
        foreach ($master_ids as $mid) {
            $this->db->insert('kesmas_item_permintaan', [
                'id_permintaan' => $id_permintaan,
                'id_master_pemeriksaan' => (int)$mid,
                'created_at' => date('Y-m-d H:i:s')
            ]);
        }

        // 4. Penerimaan Sampel & 5. Pembayaran (init)
        if (!isset($header['is_diterima']) || $header['is_diterima'] == 1) {
            $no_reg = $header['no_registrasi'] ?? $this->gen_no_registrasi();
            $this->db->insert('kesmas_penerimaan_sampel', [
                'no_registrasi' => $no_reg,
                'id_permintaan' => $id_permintaan,
                'id_petugas' => (int)($header['id_petugas'] ?: 1),
                'tgl_registrasi' => date('Y-m-d H:i:s'),
                'tindakan_sampel' => !empty($header['tindakan_sampel']) ? $header['tindakan_sampel'] : 'Langsung',
                'kelayakan' => $header['status_kelayakan'] ?? 'Layak',
                'alasan' => $header['catatan'] ?? '',
                'status' => 'Diterima'
            ]);


            $this->db->insert('kesmas_pembayaran', [
                'no_registrasi' => $no_reg,
                'tgl_pembayaran' => null,
                'biaya' => (float)($header['jumlah_biaya'] ?? 0),
                'metode_pembayaran' => $header['cara_bayar'] ?? '',
                'keterangan' => $header['keterangan_bayar'] ?? ''
            ]);
        }

        $this->db->trans_complete();
        return $this->db->trans_status() !== FALSE ? $id_permintaan : 0;
    }

    public $last_error_message = '';

    public function update_permintaan(int $id, array $header, array $master_ids, ?int $user_id): bool
    {
        $this->last_error_message = '';
        $this->db->trans_start();
        $v = $this->get_permintaan($id);
        if (!$v) {
            $this->last_error_message = 'Permintaan tidak ditemukan';
            return false;
        }

        $this->db->where('id_pelanggan', $v['id_pelanggan'])->update('kesmas_pelanggan', [
            'nik'      => $header['nik'] ?? $v['nik'] ?? '',
            'nama'     => $header['nama'] ?? $v['nama'],
            'alamat'   => $header['alamat'] ?? $v['alamat'],
            'telp'     => $header['telp'] ?? $v['telp'],
            'instansi' => $header['instansi'] ?? $v['instansi'],
            'kecamatan' => !empty($header['kecamatan']) ? $header['kecamatan'] : ($v['kecamatan'] ?? null),
            'kelurahan' => !empty($header['kelurahan']) ? $header['kelurahan'] : ($v['kelurahan'] ?? null),
            'objek'     => !empty($header['objek']) ? $header['objek'] : ($v['objek'] ?? null)
        ]);
        if (!empty($this->db->error()['message'])) $this->last_error_message .= " [pelanggan: " . $this->db->error()['message'] . "]";

        $this->db->where('id_permintaan', $id)->update('kesmas_permintaan', [
            'nama_sampel' => $header['nama_sampel'] ?? $v['nama_sampel'],
            'tgl_permintaan' => $header['tgl_permintaan'] ?? $v['tgl_permintaan'],
            'kategori_sampel' => $header['id_kategori_sampel'] ?? $v['kategori_sampel'],
            'jenis_sampel' => $header['jenis_sampel'] ?? $v['jenis_sampel'],
            'volume_ml' => (int)($header['volume_ml'] ?? $v['volume_ml']),
            'tgl_pengambilan' => $header['tgl_pengambilan'] ?? $v['tgl_pengambilan'],
            'jam_pengambilan' => $header['jam_pengambilan'] ?? $v['jam_pengambilan'],
            'lokasi_pengambilan' => $header['lokasi_pengambilan'] ?? $v['lokasi_pengambilan'],
            'petugas_pendaftaran_id' => $header['petugas_pendaftaran_id'] ?? $v['petugas_pendaftaran_id'],
            'ttd' => $header['ttd'] ?? $v['ttd'],
            'info_tambahan' => $header['info_tambahan'] ?? $v['info_tambahan']
        ]);
        if (!empty($this->db->error()['message'])) $this->last_error_message .= " [permintaan: " . $this->db->error()['message'] . "]";

        $ps_update = [];
        if (isset($header['tindakan_sampel'])) {
            $ps_update['tindakan_sampel'] = $header['tindakan_sampel'];
        }
        if (isset($header['no_registrasi'])) {
            $ps_update['no_registrasi'] = $header['no_registrasi'];
        }
        if (isset($header['catatan'])) {
            $ps_update['alasan'] = $header['catatan'];
        }
        if (!empty($ps_update)) {
            $this->db->where('id_permintaan', $id)->update('kesmas_penerimaan_sampel', $ps_update);
            if (!empty($this->db->error()['message'])) $this->last_error_message .= " [penerimaan: " . $this->db->error()['message'] . "]";
        }

        if (!empty($v['id_pembayaran'])) {
            $this->db->where('id_pembayaran', $v['id_pembayaran'])->update('kesmas_pembayaran', [
                'biaya' => (float)($header['jumlah_biaya'] ?? $v['jumlah_biaya']),
                'metode_pembayaran' => $header['cara_bayar'] ?? $v['cara_bayar'],
                'keterangan' => $header['keterangan_bayar'] ?? $v['keterangan_bayar']
            ]);
            if (!empty($this->db->error()['message'])) $this->last_error_message .= " [pembayaran_upd: " . $this->db->error()['message'] . "]";
        } else if (isset($header['jumlah_biaya'])) {
            $no_reg_val = $v['no_registrasi'] ?? null;
            if (!empty($no_reg_val)) {
                $this->db->insert('kesmas_pembayaran', [
                    'no_registrasi' => $no_reg_val,
                    'biaya' => (float)($header['jumlah_biaya'] ?? 0),
                    'metode_pembayaran' => $header['cara_bayar'] ?? '',
                    'keterangan' => $header['keterangan_bayar'] ?? ''
                ]);
                if (!empty($this->db->error()['message'])) $this->last_error_message .= " [pembayaran_ins: " . $this->db->error()['message'] . "]";
            }
        }

        $current_items = $this->get_selected_master_ids($id);
        $to_delete = array_diff($current_items, $master_ids);
        if (!empty($to_delete)) {
            $this->db->where('id_permintaan', $id)->where_in('id_master_pemeriksaan', $to_delete)->delete('kesmas_item_permintaan');
            if (!empty($this->db->error()['message'])) $this->last_error_message .= " [item_del: " . $this->db->error()['message'] . "]";
        }
        $to_add = array_diff($master_ids, $current_items);
        foreach ($to_add as $mid) {
            $this->db->insert('kesmas_item_permintaan', [
                'id_permintaan' => $id,
                'id_master_pemeriksaan' => (int)$mid
            ]);
            if (!empty($this->db->error()['message'])) $this->last_error_message .= " [item_ins: " . $this->db->error()['message'] . "]";
        }

        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            if (empty($this->last_error_message)) {
                $this->last_error_message = "Transaksi DB gagal, tapi tidak ada pesan error terdeteksi. (Kemungkinan db_debug off atau constraint diam)";
            }
            return false;
        }
        return true;
    }

    public function get_items_with_result(int $permintaan_id): array
    {
        $penerimaan = $this->db->get_where('kesmas_penerimaan_sampel', ['id_permintaan' => $permintaan_id])->row_array();
        $id_pengujian = null;
        if ($penerimaan && !empty($penerimaan['no_registrasi'])) {
            $pembayaran = $this->db->get_where('kesmas_pembayaran', ['no_registrasi' => $penerimaan['no_registrasi']])->row_array();
            if ($pembayaran && !empty($pembayaran['id_pembayaran'])) {
                $pengujian = $this->db->get_where('kesmas_pengujian_sampel', ['id_pembayaran' => $pembayaran['id_pembayaran']])->row_array();
                if ($pengujian) $id_pengujian = $pengujian['id_pengujian'];
            }
        }

        $sql = "SELECT kesmas_item_permintaan.id_master_pemeriksaan, kesmas_master_pemeriksaan.nama_pemeriksaan, kesmas_master_pemeriksaan.kelompok_pemeriksaan AS kelompok, 
                kesmas_master_pemeriksaan.satuan, kesmas_master_pemeriksaan.baku_mutu, kesmas_master_pemeriksaan.metode, kesmas_master_pemeriksaan.urutan";
        if ($id_pengujian) {
            $sql .= ", kesmas_hasil_pengujian.hasil, kesmas_hasil_pengujian.status as keterangan, kesmas_hasil_pengujian.baku_mutu as baku_mutu_override, kesmas_hasil_pengujian.metode as metode_override";
        } else {
            $sql .= ", '' as hasil, '' as keterangan, NULL as baku_mutu_override, NULL as metode_override";
        }
        $sql .= " FROM kesmas_item_permintaan
                  JOIN kesmas_master_pemeriksaan ON kesmas_master_pemeriksaan.id_master_pemeriksaan = kesmas_item_permintaan.id_master_pemeriksaan ";
        if ($id_pengujian) {
            $sql .= " LEFT JOIN kesmas_hasil_pengujian ON kesmas_hasil_pengujian.id_master_pemeriksaan = kesmas_item_permintaan.id_master_pemeriksaan AND kesmas_hasil_pengujian.id_pengujian = ? ";
        }
        $sql .= " WHERE kesmas_item_permintaan.id_permintaan = ? 
                  ORDER BY 
                    CASE kesmas_master_pemeriksaan.kelompok_pemeriksaan
                        WHEN 'Fisik Wajib' THEN 1
                        WHEN 'Fisika' THEN 1
                        WHEN 'Fisik' THEN 1
                        WHEN 'Kimia Wajib' THEN 2
                        WHEN 'Kimia' THEN 2
                        WHEN 'Kimia Khusus' THEN 3
                        WHEN 'Kimia Tambahan' THEN 3
                        WHEN 'Bakteriologi' THEN 4
                        WHEN 'Mikrobiologi' THEN 4
                        WHEN 'Mikrobiologi Wajib' THEN 4
                        WHEN 'Bakteriologi Wajib' THEN 4
                        WHEN 'Mikrobiologi Tambahan' THEN 5
                        WHEN 'Bakteriologi Tambahan' THEN 5
                        WHEN 'Parasitologi' THEN 6
                        ELSE 10
                    END ASC,
                    kesmas_master_pemeriksaan.urutan ASC";

        if ($id_pengujian) {
            return $this->db->query($sql, array($id_pengujian, $permintaan_id))->result_array();
        }
        return $this->db->query($sql, array($permintaan_id))->result_array();
    }

    public function list_petugas(): array
    {
        $this->db->where('is_active', 1)->order_by('nama', 'ASC');
        return $this->db->get('kesmas_petugas')->result_array();
    }

    public function get_petugas_name_by_id(int $id): ?string
    {
        $row = $this->db->select('nama')->get_where('kesmas_petugas', ['id_petugas' => $id])->row_array();
        return $row['nama'] ?? null;
    }

    public function get_petugas_by_id(int $id): ?array
    {
        $row = $this->db->get_where('kesmas_petugas', ['id_petugas' => $id])->row_array();
        return $row ?: null;
    }

    public function update_kaji_ulang(int $id, array $data): bool
    {
        $this->db->trans_start();
        $v = $this->get_permintaan($id);

        $ps_update = [];
        if (isset($data['alasan'])) {
            $ps_update['alasan'] = is_array($data['alasan']) ? implode(', ', $data['alasan']) : $data['alasan'];
        }
        // Also support old catatan key if sent by other controllers
        if (isset($data['catatan']) && !isset($ps_update['alasan'])) {
            $ps_update['alasan'] = $data['catatan'];
        }

        if (isset($data['status_kelayakan'])) {
            $ps_update['kelayakan'] = $data['status_kelayakan'];
            if ($data['status_kelayakan'] === 'Layak') {
                $ps_update['alasan'] = 'sample siap diuji'; // Force set to this if Layak
            }
        }
        if (array_key_exists('id_petugas', $data)) $ps_update['id_petugas'] = $data['id_petugas'];
        if (array_key_exists('id_petugas_2', $data)) $ps_update['id_petugas_2'] = $data['id_petugas_2'];
        if (isset($data['tindakan_sampel'])) $ps_update['tindakan_sampel'] = $data['tindakan_sampel'];

        if (!empty($ps_update)) {
            $this->db->where('id_permintaan', $id)->update('kesmas_penerimaan_sampel', $ps_update);
        }

        if (isset($data['jumlah_biaya']) && $v) {
            $pembayaran_data = [
                'biaya' => (float)$data['jumlah_biaya'],
                'metode_pembayaran' => $data['cara_bayar'] ?? '',
                'keterangan' => $data['keterangan_bayar'] ?? ''
            ];

            if (!empty($v['id_pembayaran'])) {
                $this->db->where('id_pembayaran', $v['id_pembayaran'])->update('kesmas_pembayaran', $pembayaran_data);
            } else {
                // If it was missing for some reason, insert and link it via no_registrasi
                $no_reg_val = $v['no_registrasi'] ?? null;
                $this->db->insert('kesmas_pembayaran', array_merge(['tgl_pembayaran' => null, 'no_registrasi' => $no_reg_val], $pembayaran_data));
            }
        }

        $this->db->trans_complete();
        return $this->db->trans_status() !== FALSE;
    }

    public function update_pemeriksaan_items(int $id, array $master_ids): bool
    {
        $current_items = $this->get_permintaan_items($id);

        $to_delete = array_diff($current_items, $master_ids);
        if (!empty($to_delete)) {
            $this->db->where('id_permintaan', $id)
                ->where_in('id_master_pemeriksaan', $to_delete)
                ->delete('kesmas_item_permintaan');
        }

        $to_add = array_diff($master_ids, $current_items);
        foreach ($to_add as $mid) {
            $this->db->insert('kesmas_item_permintaan', [
                'id_permintaan' => $id,
                'id_master_pemeriksaan' => (int)$mid
            ]);
        }
        return true;
    }

    public function save_hasil(int $permintaan_id, array $hasil_items, array $keterangan_items, array $payload, ?int $user_id): bool
    {
        $this->db->trans_start();

        $penerimaan = $this->db->get_where('kesmas_penerimaan_sampel', ['id_permintaan' => $permintaan_id])->row_array();
        if (!$penerimaan || empty($penerimaan['no_registrasi'])) return false;

        $pembayaran = $this->db->get_where('kesmas_pembayaran', ['no_registrasi' => $penerimaan['no_registrasi']])->row_array();
        if (!$pembayaran || empty($pembayaran['id_pembayaran'])) {
            $this->db->insert('kesmas_pembayaran', [
                'no_registrasi' => $penerimaan['no_registrasi'],
                'biaya' => 0
            ]);
            $id_pembayaran = $this->db->insert_id();
        } else {
            $id_pembayaran = $pembayaran['id_pembayaran'];
        }

        // Cek kesmas_pengujian_sampel
        $pengujian = $this->db->get_where('kesmas_pengujian_sampel', ['id_pembayaran' => $id_pembayaran])->row_array();
        if (!$pengujian) {
            $this->db->insert('kesmas_pengujian_sampel', [
                'id_pembayaran' => $id_pembayaran,
                'id_petugas' => $payload['id_petugas'] ?? null,
                'tgl_mulai' => $payload['tgl_jam_pemeriksaan'] ?? null,
                'tgl_selesai' => $payload['tgl_jam_selesai'] ?? null,
                'tgl_pelaporan' => $payload['tgl_jam_lapor'] ?? null,
            ]);
            $id_pengujian = $this->db->insert_id();
        } else {
            $id_pengujian = $pengujian['id_pengujian'];
            $this->db->where('id_pengujian', $id_pengujian)->update('kesmas_pengujian_sampel', [
                'id_petugas' => $payload['id_petugas'] ?? $pengujian['id_petugas'],
                'tgl_mulai' => $payload['tgl_jam_pemeriksaan'] ?? $pengujian['tgl_mulai'],
                'tgl_selesai' => $payload['tgl_jam_selesai'] ?? $pengujian['tgl_selesai'],
                'tgl_pelaporan' => $payload['tgl_jam_lapor'] ?? $pengujian['tgl_pelaporan'],
            ]);
        }

        if (array_key_exists('tgl_pengambilan', $payload) && array_key_exists('jam_pengambilan', $payload)) {
            $this->db->where('id_permintaan', $permintaan_id)->update('kesmas_permintaan', [
                'tgl_pengambilan' => $payload['tgl_pengambilan'],
                'jam_pengambilan' => $payload['jam_pengambilan']
            ]);
        }

        foreach ($hasil_items as $id_master => $hasil_val) {
            $status = $keterangan_items[$id_master] ?? null;

            $baku = $payload['baku_mutu_override'][$id_master] ?? null;
            $metode = $payload['metode_override'][$id_master] ?? null;

            $exist = $this->db->get_where('kesmas_hasil_pengujian', ['id_pengujian' => $id_pengujian, 'id_master_pemeriksaan' => $id_master])->row_array();
            if ($exist) {
                $this->db->where(['id_pengujian' => $id_pengujian, 'id_master_pemeriksaan' => $id_master])->update('kesmas_hasil_pengujian', [
                    'hasil' => $hasil_val,
                    'status' => $status,
                    'baku_mutu' => $baku,
                    'metode' => $metode
                ]);
            } else {
                $this->db->insert('kesmas_hasil_pengujian', [
                    'id_pengujian' => $id_pengujian,
                    'id_master_pemeriksaan' => $id_master,
                    'hasil' => $hasil_val,
                    'status' => $status,
                    'baku_mutu' => $baku,
                    'metode' => $metode
                ]);
            }
        }

        $this->db->trans_complete();
        return $this->db->trans_status() !== FALSE;
    }

    public function delete_permintaan(int $id): bool
    {
        $v = $this->get_permintaan($id);
        if (!$v) return false;

        $id_pelanggan = $v['id_pelanggan'] ?? null;

        $this->db->trans_start();

        // Array dari semua tabel yang mungkin berelasi dengan pendaftaran ini
        $tables_to_clean = [
            'kesmas_hasil_pengujian',
            'kesmas_hasil',
            'kesmas_pengujian_sampel',
            'kesmas_pengujian',
            'kesmas_item_permintaan',
            'kesmas_permintaan_item',
            'kesmas_pembayaran',
            'kesmas_penerimaan_sampel',
            'kesmas_terima_sampel',
            'kesmas_kartu_kendali',
            'kesmas_survei_kepuasan',
            'kesmas_sampel'
        ];

        // 1. Hapus dari semua tabel turunan yang memiliki id_permintaan
        foreach ($tables_to_clean as $table) {
            if ($this->db->table_exists($table) && $this->db->field_exists('id_permintaan', $table)) {
                $this->db->where('id_permintaan', $id)->delete($table);
            }
        }

        // Coba juga hapus berdasarkan no_registrasi, id_pembayaran, id_pengujian (sebagai fallback untuk tabel yang mungkin tidak punya id_permintaan)
        if (!empty($v['id_pengujian']) && $this->db->table_exists('kesmas_hasil_pengujian') && $this->db->field_exists('id_pengujian', 'kesmas_hasil_pengujian')) {
            $this->db->where('id_pengujian', $v['id_pengujian'])->delete('kesmas_hasil_pengujian');
        }
        if (!empty($v['id_pengujian']) && $this->db->table_exists('kesmas_pengujian_sampel') && $this->db->field_exists('id_pengujian', 'kesmas_pengujian_sampel')) {
            $this->db->where('id_pengujian', $v['id_pengujian'])->delete('kesmas_pengujian_sampel');
        }
        if (!empty($v['id_pembayaran']) && $this->db->table_exists('kesmas_pengujian_sampel') && $this->db->field_exists('id_pembayaran', 'kesmas_pengujian_sampel')) {
            $this->db->where('id_pembayaran', $v['id_pembayaran'])->delete('kesmas_pengujian_sampel');
        }
        if (!empty($v['no_registrasi']) && $this->db->table_exists('kesmas_pembayaran') && $this->db->field_exists('no_registrasi', 'kesmas_pembayaran')) {
            $this->db->where('no_registrasi', $v['no_registrasi'])->delete('kesmas_pembayaran');
        }

        // 2. Terakhir, Hapus permintaan utama
        if ($this->db->table_exists('kesmas_permintaan') && $this->db->field_exists('id_permintaan', 'kesmas_permintaan')) {
            $this->db->where('id_permintaan', $id)->delete('kesmas_permintaan');
        }
        $deleted = $this->db->affected_rows() > 0;

        // 3. Hapus pelanggan jika tidak ada lagi permintaan yang terkait
        if ($deleted && $id_pelanggan && $this->db->table_exists('kesmas_permintaan') && $this->db->field_exists('id_pelanggan', 'kesmas_permintaan')) {
            $count = $this->db->where('id_pelanggan', $id_pelanggan)->count_all_results('kesmas_permintaan');
            if ($count === 0 && $this->db->table_exists('kesmas_pelanggan') && $this->db->field_exists('id_pelanggan', 'kesmas_pelanggan')) {
                $this->db->where('id_pelanggan', $id_pelanggan)->delete('kesmas_pelanggan');
            }
        }

        $this->db->trans_complete();


        return $this->db->trans_status() !== FALSE;
    }


    // =========================================================
    // MASTER PEMERIKSAAN CRUD
    // =========================================================

    public function get_all_master(array $filters = []): array
    {
        $this->db->select('kesmas_master_pemeriksaan.*');
        $this->db->from('kesmas_master_pemeriksaan');

        if (!empty($filters['kategori'])) $this->db->where('kesmas_master_pemeriksaan.kategori', $filters['kategori']);
        if (!empty($filters['kelompok'])) $this->db->where('kesmas_master_pemeriksaan.kelompok_pemeriksaan', $filters['kelompok']);
        if (isset($filters['is_active']) && $filters['is_active'] !== '') $this->db->where('kesmas_master_pemeriksaan.is_active', (int)$filters['is_active']);
        if (!empty($filters['q'])) {
            $this->db->group_start()->like('kesmas_master_pemeriksaan.nama_pemeriksaan', $filters['q'])->or_like('kesmas_master_pemeriksaan.kelompok_pemeriksaan', $filters['q'])->or_like('kesmas_master_pemeriksaan.satuan', $filters['q'])->or_like('kesmas_master_pemeriksaan.metode', $filters['q'])->group_end();
        }
        $this->db->order_by('kesmas_master_pemeriksaan.kategori', 'ASC');
        $this->db->order_by('kesmas_master_pemeriksaan.urutan', 'ASC');

        $rows = $this->db->get()->result_array();

        foreach ($rows as &$row) {
            $kat = strtolower($row['kategori'] ?? '');
            if (str_contains($kat, 'air minum')) $row['kode'] = 'air_minum';
            elseif (str_contains($kat, 'air bersih')) $row['kode'] = 'air_bersih';
            elseif (str_contains($kat, 'makan')) $row['kode'] = 'makanan';
            elseif (str_contains($kat, 'lingkungan')) $row['kode'] = 'lingkungan';
            else $row['kode'] = 'lainnya';
        }

        return $rows;
    }

    public function get_master_by_id(int $id): ?array
    {
        $this->db->select('kesmas_master_pemeriksaan.*');
        $row = $this->db->get_where('kesmas_master_pemeriksaan', ['kesmas_master_pemeriksaan.id_master_pemeriksaan' => $id])->row_array();
        if ($row) {
            $kat = strtolower($row['kategori'] ?? '');
            if (str_contains($kat, 'air minum')) $row['kode'] = 'air_minum';
            elseif (str_contains($kat, 'air bersih')) $row['kode'] = 'air_bersih';
            elseif (str_contains($kat, 'makan')) $row['kode'] = 'makanan';
            elseif (str_contains($kat, 'lingkungan')) $row['kode'] = 'lingkungan';
            else $row['kode'] = 'lainnya';
        }
        return $row ?: null;
    }

    public function insert_master(array $data): bool
    {
        unset($data['nama_pemeriksaan_norm']);

        if (empty($data['urutan'])) {
            $max = $this->db->select_max('urutan')
                ->where('kategori', $data['kategori'] ?? '')
                ->where('kelompok_pemeriksaan', $data['kelompok'] ?? '')
                ->get('kesmas_master_pemeriksaan')->row();
            $data['urutan'] = ($max && $max->urutan) ? (int)$max->urutan + 1 : 1;
        }

        if (isset($data['kelompok'])) $data['kelompok_pemeriksaan'] = $data['kelompok'];
        $data['is_active']  = 1;

        $allowed = ['kategori', 'kelompok_pemeriksaan', 'nama_pemeriksaan', 'satuan', 'baku_mutu', 'metode', 'urutan', 'is_active', 'baku_type', 'baku_operator', 'baku_min', 'baku_max', 'baku_text'];

        $insert_data = [];
        foreach ($data as $k => $v) {
            if (in_array($k, $allowed) && $this->db->field_exists($k, 'kesmas_master_pemeriksaan')) {
                $insert_data[$k] = $v;
            }
        }

        return $this->db->insert('kesmas_master_pemeriksaan', $insert_data);
    }

    public function update_master(int $id, array $data): bool
    {
        unset($data['nama_pemeriksaan_norm']);
        $old = $this->get_master_by_id($id);
        if (isset($data['urutan']) && empty($data['urutan'])) $data['urutan'] = $old['urutan'];

        if (isset($data['kelompok'])) $data['kelompok_pemeriksaan'] = $data['kelompok'];

        $allowed = ['kategori', 'kelompok_pemeriksaan', 'nama_pemeriksaan', 'satuan', 'baku_mutu', 'metode', 'urutan', 'is_active', 'baku_type', 'baku_operator', 'baku_min', 'baku_max', 'baku_text'];

        $update_data = [];
        foreach ($data as $k => $v) {
            if (in_array($k, $allowed) && $this->db->field_exists($k, 'kesmas_master_pemeriksaan')) {
                $update_data[$k] = $v;
            }
        }

        $this->db->where('id_master_pemeriksaan', $id);
        return $this->db->update('kesmas_master_pemeriksaan', $update_data);
    }

    public function is_master_in_use(int $id): bool
    {
        return $this->db->where('id_master_pemeriksaan', $id)->count_all_results('kesmas_item_permintaan') > 0;
    }

    public function toggle_master_active(int $id): bool
    {
        $master = $this->get_master_by_id($id);
        if (!$master) return false;
        $this->db->where('id_master_pemeriksaan', $id);
        return $this->db->update('kesmas_master_pemeriksaan', ['is_active' => $master['is_active'] ? 0 : 1]);
    }

    public function delete_master(int $id): bool
    {
        return $this->db->where('id_master_pemeriksaan', $id)->delete('kesmas_master_pemeriksaan');
    }

    public function check_duplicate_master(string $nama, string $kat, string $kel, ?int $exclude_id = null): bool
    {
        $this->db->where('nama_pemeriksaan', $nama);
        $this->db->where('kategori', $kat);
        $this->db->where('kelompok_pemeriksaan', $kel);
        if ($exclude_id) $this->db->where('id_master_pemeriksaan !=', $exclude_id);
        return $this->db->count_all_results('kesmas_master_pemeriksaan') > 0;
    }

    public function check_duplicate_urutan(string $kat, string $kel, int $urutan, ?int $exclude_id = null): bool
    {
        $this->db->where('kategori', $kat);
        $this->db->where('kelompok_pemeriksaan', $kel);
        $this->db->where('urutan', $urutan);
        if ($exclude_id) $this->db->where('id_master_pemeriksaan !=', $exclude_id);
        return $this->db->count_all_results('kesmas_master_pemeriksaan') > 0;
    }

    public function duplicate_master(int $id, string $kat, string $kel): bool
    {
        $old = $this->db->get_where('kesmas_master_pemeriksaan', ['id_master_pemeriksaan' => $id])->row_array();
        if (!$old) return false;
        unset($old['id_master_pemeriksaan']);
        $old['kategori'] = $kat;
        $old['kelompok_pemeriksaan'] = $kel;

        $max = $this->db->select_max('urutan')->where('kategori', $kat)->where('kelompok_pemeriksaan', $kel)->get('kesmas_master_pemeriksaan')->row();
        $old['urutan'] = ($max && $max->urutan) ? (int)$max->urutan + 1 : 1;

        return $this->db->insert('kesmas_master_pemeriksaan', $old);
    }

    public function reorder_master(array $orders): bool
    {
        $this->db->trans_start();
        foreach ($orders as $id => $urutan) {
            $this->db->where('id_master_pemeriksaan', (int)$id)->update('kesmas_master_pemeriksaan', ['urutan' => (int)$urutan]);
        }
        $this->db->trans_complete();
        return $this->db->trans_status() !== FALSE;
    }

    public function terima_sampel(int $id, ?int $user_id, ?string $no_registrasi = null): bool
    {
        $ps = $this->db->get_where('kesmas_penerimaan_sampel', ['id_permintaan' => $id])->row_array();
        if ($ps) {
            $update_data = ['status' => 'Diterima'];
            if ($no_registrasi && $no_registrasi !== $ps['no_registrasi']) {
                $update_data['no_registrasi'] = $no_registrasi;
                // Sinkronkan juga pembayaran jika no_registrasi berubah
                if (!empty($ps['no_registrasi'])) {
                    $this->db->where('no_registrasi', $ps['no_registrasi'])->update('kesmas_pembayaran', ['no_registrasi' => $no_registrasi]);
                }
            }
            return $this->db->where('id_permintaan', $id)->update('kesmas_penerimaan_sampel', $update_data);
        }
        $final_no_reg = $no_registrasi ?: $this->gen_no_registrasi();
        $this->db->insert('kesmas_penerimaan_sampel', [
            'id_permintaan' => $id,
            'no_registrasi' => $final_no_reg,
            'id_petugas' => $user_id ?: 1,
            'status' => 'Diterima',
            'tgl_registrasi' => date('Y-m-d H:i:s')
        ]);

        return $this->db->insert('kesmas_pembayaran', [
            'no_registrasi' => $final_no_reg,
            'biaya' => 0
        ]);
    }

    public function tolak_sampel(int $id, ?int $user_id): bool
    {
        $ps = $this->db->get_where('kesmas_penerimaan_sampel', ['id_permintaan' => $id])->row_array();
        if ($ps) {
            return $this->db->where('id_permintaan', $id)->update('kesmas_penerimaan_sampel', ['status' => 'Ditolak']);
        }
        $final_no_reg = $this->gen_no_registrasi();
        $this->db->insert('kesmas_penerimaan_sampel', [
            'id_permintaan' => $id,
            'no_registrasi' => $final_no_reg,
            'status' => 'Ditolak',
            'tgl_registrasi' => date('Y-m-d H:i:s')
        ]);

        return $this->db->insert('kesmas_pembayaran', [
            'no_registrasi' => $final_no_reg,
            'biaya' => 0
        ]);
    }

    // =========================================================
    // LAPORAN PER PELANGGAN
    // =========================================================

    public function list_pelanggan_dengan_permintaan(array $filters = []): array
    {
        $this->db->select("
            MIN(kesmas_pelanggan.id_pelanggan)                  AS id_pelanggan,
            MAX(kesmas_pelanggan.nik)                           AS nik,
            MIN(kesmas_pelanggan.nama)                          AS nama,
            MIN(kesmas_pelanggan.instansi)                      AS instansi,
            MIN(kesmas_pelanggan.alamat)                        AS alamat,
            MIN(kesmas_pelanggan.telp)                          AS telp,
            COUNT(DISTINCT kesmas_permintaan.id_permintaan)        AS jumlah_permintaan,
            COUNT(DISTINCT CONCAT(kesmas_item_permintaan.id_permintaan,'_',kesmas_item_permintaan.id_master_pemeriksaan)) AS jumlah_item,
            MAX(kesmas_permintaan.tgl_permintaan)                  AS tgl_terakhir
        ", false);
        $this->db->from('kesmas_pelanggan');
        $this->db->join('kesmas_permintaan',       'kesmas_permintaan.id_pelanggan = kesmas_pelanggan.id_pelanggan',  'inner');
        $this->db->join('kesmas_penerimaan_sampel',   'kesmas_permintaan.id_permintaan = kesmas_penerimaan_sampel.id_permintaan', 'inner');
        $this->db->join('kesmas_item_permintaan',      'kesmas_permintaan.id_permintaan = kesmas_item_permintaan.id_permintaan',  'left');

        if (!empty($filters['q'])) {
            $q = $filters['q'];
            $this->db->group_start()
                ->like('kesmas_pelanggan.nama', $q)
                ->or_like('kesmas_pelanggan.nik', $q)
                ->or_like('kesmas_pelanggan.instansi', $q)
                ->or_like('kesmas_pelanggan.alamat', $q)
                ->group_end();
        }
        if (!empty($filters['dari']))         $this->db->where('kesmas_permintaan.tgl_permintaan >=', $filters['dari']);
        if (!empty($filters['sampai']))       $this->db->where('kesmas_permintaan.tgl_permintaan <=', $filters['sampai']);
        if (!empty($filters['jenis_sampel'])) $this->db->like('kesmas_permintaan.jenis_sampel', $filters['jenis_sampel']);

        $this->db->group_by('kesmas_pelanggan.id_pelanggan');
        $this->db->order_by('nama', 'ASC');
        return $this->db->get()->result_array();
    }

    public function get_pelanggan_by_id(int $id): ?array
    {
        $row = $this->db->get_where('kesmas_pelanggan', ['id_pelanggan' => $id])->row_array();
        return $row ?: null;
    }

    public function update_catatan_kaki_pelanggan(int $id, string $catatan_kaki): bool
    {
        $this->db->where('id_pelanggan', $id);
        return $this->db->update('kesmas_pelanggan', ['catatan_kaki' => $catatan_kaki]);
    }

    public function get_permintaan_by_pelanggan(int $id_pelanggan, array $filters = []): array
    {
        $this->db->select("
            kesmas_permintaan.id_permintaan,
            kesmas_penerimaan_sampel.no_registrasi,
            kesmas_permintaan.tgl_permintaan,
            kesmas_permintaan.nama_sampel,
            kesmas_permintaan.kategori_sampel,
            kesmas_permintaan.jenis_sampel,
            kesmas_permintaan.lokasi_pengambilan,
            kesmas_permintaan.tgl_pengambilan,
            kesmas_permintaan.jam_pengambilan,
            kesmas_penerimaan_sampel.id_petugas,
            kesmas_penerimaan_sampel.id_petugas_2,
            kesmas_penerimaan_sampel.kelayakan as status_kelayakan,
            kesmas_pembayaran.biaya as jumlah_biaya,
            kesmas_pengujian_sampel.tgl_mulai as tgl_pengerjaan,
            kesmas_pengujian_sampel.tgl_selesai as tgl_input_hasil,
            kesmas_pengujian_sampel.tgl_pelaporan as tgl_cetak
        ", false);
        $this->db->from('kesmas_permintaan');
        $this->db->join('kesmas_penerimaan_sampel', 'kesmas_permintaan.id_permintaan = kesmas_penerimaan_sampel.id_permintaan', 'inner');
        $this->db->join('kesmas_pembayaran',    'kesmas_penerimaan_sampel.no_registrasi = kesmas_pembayaran.no_registrasi', 'left');
        $this->db->join('kesmas_pengujian_sampel', 'kesmas_pembayaran.id_pembayaran = kesmas_pengujian_sampel.id_pembayaran', 'left');
        $this->db->join('kesmas_pelanggan',    'kesmas_permintaan.id_pelanggan = kesmas_pelanggan.id_pelanggan',   'inner');

        $this->db->where('kesmas_permintaan.id_pelanggan', $id_pelanggan);

        if (!empty($filters['dari']))         $this->db->where('kesmas_permintaan.tgl_permintaan >=', $filters['dari']);
        if (!empty($filters['sampai']))       $this->db->where('kesmas_permintaan.tgl_permintaan <=', $filters['sampai']);
        if (!empty($filters['jenis_sampel'])) $this->db->like('kesmas_permintaan.jenis_sampel', $filters['jenis_sampel']);

        $this->db->order_by('kesmas_penerimaan_sampel.no_registrasi', 'ASC');
        $permintaan_rows = $this->db->get()->result_array();

        foreach ($permintaan_rows as &$row) {
            $row['items'] = $this->get_items_with_result((int)$row['id_permintaan']);
        }
        unset($row);

        return $permintaan_rows;
    }

    // =========================================================
    // PETUGAS CRUD
    // =========================================================

    public function add_petugas(array $data): bool
    {
        $data['is_active'] = 1;
        return $this->db->insert('kesmas_petugas', $data);
    }

    public function update_petugas(int $id, array $data): bool
    {
        $this->db->where('id_petugas', $id);
        return $this->db->update('kesmas_petugas', $data);
    }

    public function delete_petugas(int $id): bool
    {
        return $this->db->where('id_petugas', $id)->delete('kesmas_petugas');
    }

    public function get_pelanggan_by_nik(string $nik)
    {
        $this->db->where('nik', $nik);
        $this->db->where('nik !=', '');
        return $this->db->get('kesmas_pelanggan')->row_array();
    }

    // =========================================================
    // LAPORAN PARAMETER KESMAS (BULANAN, TAHUNAN, MATRIKS, STATS)
    // =========================================================

    /**
     * Mengambil statistik pengujian per bulan (1-12) untuk tahun tertentu
     */
    public function get_parameter_yearly_stats(int $tahun): array
    {
        $month_date_expr = "COALESCE(
            kesmas_pengujian_sampel.tgl_pelaporan, 
            kesmas_pengujian_sampel.tgl_selesai, 
            kesmas_permintaan.tgl_permintaan
        )";

        $sql = "SELECT 
            MONTH({$month_date_expr}) AS bulan,
            COUNT(*) AS total_tested,
            SUM(CASE WHEN UPPER(TRIM(kesmas_hasil_pengujian.status)) = 'MS' THEN 1 ELSE 0 END) AS ms_count,
            SUM(CASE WHEN UPPER(TRIM(kesmas_hasil_pengujian.status)) = 'TMS' THEN 1 ELSE 0 END) AS tms_count
        FROM kesmas_hasil_pengujian
        JOIN kesmas_pengujian_sampel ON kesmas_hasil_pengujian.id_pengujian = kesmas_pengujian_sampel.id_pengujian
        JOIN kesmas_pembayaran ON kesmas_pembayaran.id_pembayaran = kesmas_pengujian_sampel.id_pembayaran
        JOIN kesmas_penerimaan_sampel ON kesmas_penerimaan_sampel.no_registrasi = kesmas_pembayaran.no_registrasi
        JOIN kesmas_permintaan ON kesmas_permintaan.id_permintaan = kesmas_penerimaan_sampel.id_permintaan
        JOIN kesmas_master_pemeriksaan ON kesmas_master_pemeriksaan.id_master_pemeriksaan = kesmas_hasil_pengujian.id_master_pemeriksaan
        WHERE YEAR({$month_date_expr}) = ?
          AND UPPER(TRIM(kesmas_hasil_pengujian.status)) IN ('MS', 'TMS')
        GROUP BY MONTH({$month_date_expr})";

        $rows = $this->db->query($sql, [$tahun])->result_array();

        // Inisialisasi 12 bulan kosong
        $stats = [];
        for ($m = 1; $m <= 12; $m++) {
            $stats[$m] = [
                'total_tested' => 0,
                'ms_count' => 0,
                'tms_count' => 0,
            ];
        }

        foreach ($rows as $r) {
            $b = (int)$r['bulan'];
            if ($b >= 1 && $b <= 12) {
                $stats[$b] = [
                    'total_tested' => (int)$r['total_tested'],
                    'ms_count' => (int)$r['ms_count'],
                    'tms_count' => (int)$r['tms_count'],
                ];
            }
        }

        return $stats;
    }

    /**
     * Mengambil daftar kategori unik pada bulan & tahun tertentu
     */
    public function get_parameter_categories_by_period(int $bulan, int $tahun): array
    {
        $month_date_expr = "COALESCE(
            kesmas_pengujian_sampel.tgl_pelaporan, 
            kesmas_pengujian_sampel.tgl_selesai, 
            kesmas_permintaan.tgl_permintaan
        )";

        $sql = "SELECT DISTINCT COALESCE(NULLIF(TRIM(kesmas_master_pemeriksaan.kategori), ''), 'Lain-lain') AS kategori
        FROM kesmas_hasil_pengujian
        JOIN kesmas_pengujian_sampel ON kesmas_hasil_pengujian.id_pengujian = kesmas_pengujian_sampel.id_pengujian
        JOIN kesmas_pembayaran ON kesmas_pembayaran.id_pembayaran = kesmas_pengujian_sampel.id_pembayaran
        JOIN kesmas_penerimaan_sampel ON kesmas_penerimaan_sampel.no_registrasi = kesmas_pembayaran.no_registrasi
        JOIN kesmas_permintaan ON kesmas_permintaan.id_permintaan = kesmas_penerimaan_sampel.id_permintaan
        JOIN kesmas_master_pemeriksaan ON kesmas_master_pemeriksaan.id_master_pemeriksaan = kesmas_hasil_pengujian.id_master_pemeriksaan
        WHERE YEAR({$month_date_expr}) = ? AND MONTH({$month_date_expr}) = ?
          AND UPPER(TRIM(kesmas_hasil_pengujian.status)) IN ('MS', 'TMS')
        ORDER BY kategori ASC";

        $rows = $this->db->query($sql, [$tahun, $bulan])->result_array();
        $cats = array_column($rows, 'kategori');

        // Jika bulan ini belum ada data, ambil semua master kategori yang aktif
        if (empty($cats)) {
            $master_cats = $this->db->select("DISTINCT COALESCE(NULLIF(TRIM(kategori), ''), 'Lain-lain') AS kategori")
                                    ->from('kesmas_master_pemeriksaan')
                                    ->where('is_active', 1)
                                    ->order_by('kategori', 'ASC')
                                    ->get()->result_array();
            $cats = array_column($master_cats, 'kategori');
        }

        return $cats;
    }

    /**
     * Mengambil ringkasan pengujian parameter untuk bulan dan tahun tertentu
     */
    public function get_parameter_monthly_summary(int $bulan, int $tahun, string $search_query = '', ?string $kategori = null): array
    {
        $month_date_expr = "COALESCE(
            kesmas_pengujian_sampel.tgl_pelaporan, 
            kesmas_pengujian_sampel.tgl_selesai, 
            kesmas_permintaan.tgl_permintaan
        )";

        $where_extra = "";
        $params = [$tahun, $bulan];

        if (!empty($search_query)) {
            $where_extra .= " AND (kesmas_master_pemeriksaan.nama_pemeriksaan LIKE ? OR kesmas_permintaan.jenis_sampel LIKE ?)";
            $params[] = '%' . $search_query . '%';
            $params[] = '%' . $search_query . '%';
        }

        if (!empty($kategori) && $kategori !== 'Semua') {
            $where_extra .= " AND COALESCE(NULLIF(TRIM(kesmas_master_pemeriksaan.kategori), ''), 'Lain-lain') = ?";
            $params[] = $kategori;
        }

        $sql = "SELECT 
            kesmas_master_pemeriksaan.id_master_pemeriksaan AS master_id,
            kesmas_master_pemeriksaan.nama_pemeriksaan,
            COALESCE(NULLIF(TRIM(kesmas_master_pemeriksaan.kategori), ''), 'Lain-lain') AS kategori,
            kesmas_master_pemeriksaan.kelompok_pemeriksaan,
            kesmas_master_pemeriksaan.satuan,
            kesmas_master_pemeriksaan.baku_mutu,
            kesmas_permintaan.jenis_sampel,
            COUNT(kesmas_hasil_pengujian.id_master_pemeriksaan) AS total_tested,
            SUM(CASE WHEN UPPER(TRIM(kesmas_hasil_pengujian.status)) = 'MS' THEN 1 ELSE 0 END) AS ms_count,
            SUM(CASE WHEN UPPER(TRIM(kesmas_hasil_pengujian.status)) = 'TMS' THEN 1 ELSE 0 END) AS tms_count
        FROM kesmas_hasil_pengujian
        JOIN kesmas_pengujian_sampel ON kesmas_hasil_pengujian.id_pengujian = kesmas_pengujian_sampel.id_pengujian
        JOIN kesmas_pembayaran ON kesmas_pembayaran.id_pembayaran = kesmas_pengujian_sampel.id_pembayaran
        JOIN kesmas_penerimaan_sampel ON kesmas_penerimaan_sampel.no_registrasi = kesmas_pembayaran.no_registrasi
        JOIN kesmas_permintaan ON kesmas_permintaan.id_permintaan = kesmas_penerimaan_sampel.id_permintaan
        JOIN kesmas_master_pemeriksaan ON kesmas_master_pemeriksaan.id_master_pemeriksaan = kesmas_hasil_pengujian.id_master_pemeriksaan
        WHERE YEAR({$month_date_expr}) = ? AND MONTH({$month_date_expr}) = ?
          AND UPPER(TRIM(kesmas_hasil_pengujian.status)) IN ('MS', 'TMS')
          {$where_extra}
        GROUP BY kesmas_master_pemeriksaan.id_master_pemeriksaan, kesmas_master_pemeriksaan.nama_pemeriksaan, 
                 kesmas_master_pemeriksaan.kategori, kesmas_master_pemeriksaan.kelompok_pemeriksaan,
                 kesmas_master_pemeriksaan.satuan, kesmas_master_pemeriksaan.baku_mutu, kesmas_permintaan.jenis_sampel
        ORDER BY kesmas_master_pemeriksaan.kategori ASC, kesmas_master_pemeriksaan.nama_pemeriksaan ASC, kesmas_permintaan.jenis_sampel ASC";

        $rows = $this->db->query($sql, $params)->result_array();

        // Kelompokkan per nama pemeriksaan
        $grouped = [];
        foreach ($rows as $row) {
            $cat = $row['kategori'];
            $key = $row['master_id'] . '_' . $row['nama_pemeriksaan'];

            if (!isset($grouped[$cat])) {
                $grouped[$cat] = [];
            }

            if (!isset($grouped[$cat][$key])) {
                $grouped[$cat][$key] = [
                    'master_id' => (int)$row['master_id'],
                    'nama_pemeriksaan' => $row['nama_pemeriksaan'],
                    'kategori' => $row['kategori'],
                    'kelompok_pemeriksaan' => $row['kelompok_pemeriksaan'],
                    'satuan' => $row['satuan'],
                    'baku_mutu' => $row['baku_mutu'],
                    'total_tested' => 0,
                    'total_ms' => 0,
                    'total_tms' => 0,
                    'sampel_sumber' => [],
                ];
            }

            $grouped[$cat][$key]['sampel_sumber'][] = [
                'jenis_sampel' => $row['jenis_sampel'],
                'total_tested' => (int)$row['total_tested'],
                'ms_count' => (int)$row['ms_count'],
                'tms_count' => (int)$row['tms_count'],
            ];

            $grouped[$cat][$key]['total_tested'] += (int)$row['total_tested'];
            $grouped[$cat][$key]['total_ms'] += (int)$row['ms_count'];
            $grouped[$cat][$key]['total_tms'] += (int)$row['tms_count'];
        }

        // Ubah sub-array menjadi numeric index
        $result = [];
        foreach ($grouped as $c => $items) {
            $result[$c] = array_values($items);
        }

        return $result;
    }

    /**
     * Mengambil data matriks tahunan parameter (12 bulan)
     */
    public function get_parameter_yearly_matrix(int $tahun): array
    {
        $month_date_expr = "COALESCE(
            kesmas_pengujian_sampel.tgl_pelaporan, 
            kesmas_pengujian_sampel.tgl_selesai, 
            kesmas_permintaan.tgl_permintaan
        )";

        $sql = "SELECT 
            COALESCE(NULLIF(TRIM(kesmas_master_pemeriksaan.kategori), ''), 'Lain-lain') AS kategori,
            kesmas_master_pemeriksaan.id_master_pemeriksaan,
            kesmas_master_pemeriksaan.nama_pemeriksaan,
            kesmas_master_pemeriksaan.satuan,
            kesmas_master_pemeriksaan.baku_mutu,
            MONTH({$month_date_expr}) AS bulan,
            COUNT(*) AS total,
            SUM(CASE WHEN UPPER(TRIM(kesmas_hasil_pengujian.status)) = 'MS' THEN 1 ELSE 0 END) AS ms_count,
            SUM(CASE WHEN UPPER(TRIM(kesmas_hasil_pengujian.status)) = 'TMS' THEN 1 ELSE 0 END) AS tms_count
        FROM kesmas_hasil_pengujian
        JOIN kesmas_pengujian_sampel ON kesmas_hasil_pengujian.id_pengujian = kesmas_pengujian_sampel.id_pengujian
        JOIN kesmas_pembayaran ON kesmas_pembayaran.id_pembayaran = kesmas_pengujian_sampel.id_pembayaran
        JOIN kesmas_penerimaan_sampel ON kesmas_penerimaan_sampel.no_registrasi = kesmas_pembayaran.no_registrasi
        JOIN kesmas_permintaan ON kesmas_permintaan.id_permintaan = kesmas_penerimaan_sampel.id_permintaan
        JOIN kesmas_master_pemeriksaan ON kesmas_master_pemeriksaan.id_master_pemeriksaan = kesmas_hasil_pengujian.id_master_pemeriksaan
        WHERE YEAR({$month_date_expr}) = ?
          AND UPPER(TRIM(kesmas_hasil_pengujian.status)) IN ('MS', 'TMS')
        GROUP BY kesmas_master_pemeriksaan.kategori, kesmas_master_pemeriksaan.id_master_pemeriksaan, 
                 kesmas_master_pemeriksaan.nama_pemeriksaan, kesmas_master_pemeriksaan.satuan, 
                 kesmas_master_pemeriksaan.baku_mutu, MONTH({$month_date_expr})
        ORDER BY kategori ASC, kesmas_master_pemeriksaan.nama_pemeriksaan ASC, bulan ASC";

        return $this->db->query($sql, [$tahun])->result_array();
    }
}

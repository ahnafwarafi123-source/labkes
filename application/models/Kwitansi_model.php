<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Kwitansi_model
 *
 * Dipakai bersama oleh modul Kesmas dan Klinik.
 * - tarif_pemeriksaan : daftar tarif per modul ('kesmas' | 'klinik')
 * - kwitansi          : satu kwitansi per pendaftaran (modul + ref_id)
 * - kwitansi_item     : snapshot nama & harga item saat kwitansi disimpan
 *
 * Tabel tidak mengubah tabel lama; dibuat otomatis lewat ensure_schema().
 */
class Kwitansi_model extends CI_Model
{
    const MODUL_KODE = ['kesmas' => 'KM', 'klinik' => 'LK'];

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    /* ------------------------------------------------------------------
     * Skema & seed
     * ------------------------------------------------------------------ */

    public function ensure_schema(): void
    {
        $this->load->dbforge();

        if (!$this->db->table_exists('tarif_pemeriksaan')) {
            $this->dbforge->add_field([
                'id_tarif'      => ['type' => 'INT', 'constraint' => 11, 'unsigned' => TRUE, 'auto_increment' => TRUE],
                'modul'         => ['type' => 'VARCHAR', 'constraint' => 10],
                'kelompok'      => ['type' => 'VARCHAR', 'constraint' => 100],
                'urut_kelompok' => ['type' => 'INT', 'constraint' => 11, 'default' => 0],
                'nama_item'     => ['type' => 'VARCHAR', 'constraint' => 255],
                'keterangan'    => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => TRUE],
                'harga'         => ['type' => 'DECIMAL', 'constraint' => '15,2', 'default' => '0.00'],
                'urutan'        => ['type' => 'INT', 'constraint' => 11, 'default' => 0],
                'ref_id'        => ['type' => 'INT', 'constraint' => 11, 'null' => TRUE],
                'alias'         => ['type' => 'TEXT', 'null' => TRUE],
                'aktif'         => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
            ]);
            $this->dbforge->add_key('id_tarif', TRUE);
            $this->dbforge->add_key('modul');
            $this->dbforge->create_table('tarif_pemeriksaan', TRUE, ['ENGINE' => 'InnoDB']);
        }

        if (!$this->db->table_exists('kwitansi')) {
            $this->dbforge->add_field([
                'id_kwitansi'       => ['type' => 'INT', 'constraint' => 11, 'unsigned' => TRUE, 'auto_increment' => TRUE],
                'modul'             => ['type' => 'VARCHAR', 'constraint' => 10],
                'ref_id'            => ['type' => 'INT', 'constraint' => 11],
                'no_kwitansi'       => ['type' => 'VARCHAR', 'constraint' => 50],
                'kode_sampel'       => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => TRUE],
                'nama_pembayar'     => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => TRUE],
                'tanggal'           => ['type' => 'DATE', 'null' => TRUE],
                'metode_pembayaran' => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => TRUE],
                'total'             => ['type' => 'DECIMAL', 'constraint' => '15,2', 'default' => '0.00'],
                'nama_penerima'     => ['type' => 'VARCHAR', 'constraint' => 150, 'null' => TRUE],
                'keterangan'        => ['type' => 'TEXT', 'null' => TRUE],
                'created_by'        => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => TRUE],
                'created_at'        => ['type' => 'DATETIME', 'null' => TRUE],
                'updated_at'        => ['type' => 'DATETIME', 'null' => TRUE],
            ]);
            $this->dbforge->add_key('id_kwitansi', TRUE);
            $this->dbforge->create_table('kwitansi', TRUE, ['ENGINE' => 'InnoDB']);
            $this->db->query('ALTER TABLE `kwitansi` ADD UNIQUE KEY `uk_kwitansi_ref` (`modul`, `ref_id`), ADD UNIQUE KEY `uk_kwitansi_no` (`no_kwitansi`)');
        }

        if (!$this->db->table_exists('kwitansi_item')) {
            $this->dbforge->add_field([
                'id_item'       => ['type' => 'INT', 'constraint' => 11, 'unsigned' => TRUE, 'auto_increment' => TRUE],
                'id_kwitansi'   => ['type' => 'INT', 'constraint' => 11, 'unsigned' => TRUE],
                'id_tarif'      => ['type' => 'INT', 'constraint' => 11, 'unsigned' => TRUE, 'null' => TRUE],
                'kelompok'      => ['type' => 'VARCHAR', 'constraint' => 100],
                'urut_kelompok' => ['type' => 'INT', 'constraint' => 11, 'default' => 0],
                'nama_item'     => ['type' => 'VARCHAR', 'constraint' => 255],
                'harga'         => ['type' => 'DECIMAL', 'constraint' => '15,2', 'default' => '0.00'],
            ]);
            $this->dbforge->add_key('id_item', TRUE);
            $this->dbforge->add_key('id_kwitansi');
            $this->dbforge->create_table('kwitansi_item', TRUE, ['ENGINE' => 'InnoDB']);
        }

        if ($this->db->where('modul', 'klinik')->count_all_results('tarif_pemeriksaan') === 0) {
            $this->seed_tarif_klinik();
        }
        if ($this->db->where('modul', 'kesmas')->count_all_results('tarif_pemeriksaan') === 0) {
            $this->sync_tarif_kesmas();
        }
    }

    /** Tarif Laboratorium Klinik — Perda Nomor 1 Tahun 2024. */
    private function seed_tarif_klinik(): void
    {
        // [kelompok, [nama, keterangan, harga, alias...]]
        $data = [
            ['Hematologi', [
                ['Hemoglobin', null, 10000, 'hemoglobin'],
                ['Golongan Darah Metode Slide', null, 10000, 'golongandarah'],
                ['LED', null, 35000, 'led'],
                ['Paket Hematologi Lengkap', 'Hb, Leukosit, Eritrosit, MCV, MCH, MCHC, Ht, Trombosit, Hitung Jenis', 75000, 'darahlengkap,hitungjenis'],
                ['Paket Hematologi Rutin', 'Hb, Ht, Leukosit, Trombosit, Eritrosit', 60000, 'darahrutin'],
            ]],
            ['Hemostasis', [
                ['Masa Perdarahan', null, 7000, 'masaperdarahan,masaperdarahanbt'],
                ['Masa Pembekuan', null, 7000, 'masapembekuan,masapembekuanct'],
            ]],
            ['Urinalisa', [
                ['Protein Urin', null, 7000, 'proteinurin'],
                ['Glukosa Urin', null, 10000, 'glukosaurin'],
                ['Tes Kehamilan', null, 20000, 'teskehamilan'],
                ['Urine Lengkap', null, 35000, 'makroskopis,mikroskopis,urinelengkap'],
                ['Mikroalbumin', null, 120000, 'mikroalbumin'],
            ]],
            ['Faal Hati', [
                ['SGOT', null, 25000, 'sgot'],
                ['SGPT', null, 25000, 'sgpt'],
                ['Protein Total', null, 25000, 'proteintotal'],
                ['Albumin', null, 25000, 'albumin'],
            ]],
            ['Lemak', [
                ['Cholesterol Total', null, 25000, 'kolesteroltotal,cholesteroltotal'],
                ['Triglyserida', null, 25000, 'trigliserida,triglyserida'],
                ['HDL Cholesterol', null, 25000, 'kolesterolhdl,hdlcholesterol'],
                ['LDL Cholesterol', null, 35000, 'kolesterolldl,kolesterolldlpakereagen,ldlcholesterol'],
                ['Paket Profil Lipid', 'Kolesterol, Trigliserida, HDL, LDL', 100000, 'profillipid'],
            ]],
            ['Gula Darah', [
                ['Glukosa Puasa', null, 20000, 'glukosapuasa,glucosapuasa'],
                ['Glukosa 2 Jam PP', null, 20000, 'glukosa2jampp,glucosa2jampp'],
                ['Glukosa Sewaktu', null, 20000, 'glukosasewaktu,glucosasewaktu'],
                ['HbA1c', null, 160000, 'hba1c'],
            ]],
            ['Fungsi Ginjal', [
                ['Ureum', null, 30000, 'ureum'],
                ['Creatinin', null, 30000, 'creatinin'],
                ['EGFR', null, 10000, 'egfr,egfrelfg'],
                ['Uric Acid', null, 25000, 'uricacid'],
            ]],
            ['Imunologi', [
                ['Widal', null, 25000, 'widal'],
                ['NS 1', null, 120000, 'ns1'],
                ['HIV', null, 80000, 'hiv'],
                ['HBsAg', null, 40000, 'hbsag'],
                ['Anti HBsAg', null, 40000, 'antihbs,antihbsag'],
                ['Anti HAV IgM', null, 150000, 'antihav,antihavigm'],
                ['Anti HCV Total', null, 130000, 'antihcv,antihcvtotal'],
                ['Tubex TF', null, 120000, 'tubextf'],
                ['IgG IgM Dengue', null, 120000, 'iggigmdengue'],
            ]],
            ['Toksikologi', [
                ['Narkoba + Surat Bebas Narkoba', '6 parameter: morphin, cocaine, amphetamin, metamphetamine, benzodiazepine, marijuana', 180000,
                    'toksikologi,methamphetaminmet,benzodiazephinbzo,marijuanathc,morphinmop,cocaincoc,amphetaminamp'],
            ]],
            ['Mikrobiologi', [
                ['Malaria', null, 15000, 'malaria'],
                ['Sputum/BTA (SPS)', null, 20000, 'bta,sputumbta'],
            ]],
            ['Biomolekuler', [
                ['PCR Covid', null, 250000, 'pcrcovid'],
                ['Antigen Covid', null, 85000, 'antigencovid'],
            ]],
            ['Medical Check Up (MCU)', [
                ['Pemeriksaan Fisik Kesehatan Umum + Surat Kesehatan', null, 30000, 'mcu'],
            ]],
        ];

        $rows = [];
        foreach ($data as $gi => [$kelompok, $items]) {
            foreach ($items as $ii => [$nama, $ket, $harga, $alias]) {
                $rows[] = [
                    'modul' => 'klinik', 'kelompok' => $kelompok, 'urut_kelompok' => $gi + 1,
                    'nama_item' => $nama, 'keterangan' => $ket, 'harga' => $harga,
                    'urutan' => $ii + 1, 'alias' => $alias, 'aktif' => 1,
                ];
            }
        }
        $this->db->insert_batch('tarif_pemeriksaan', $rows);
    }

    /**
     * Tarif Kesmas mengikuti Master Pemeriksaan (harga awal 0, diisi lewat halaman Tarif).
     * Hanya menambah item master yang belum ada; tidak mengubah harga yang sudah diisi.
     * @return int jumlah item baru
     */
    public function sync_tarif_kesmas(): int
    {
        if (!$this->db->table_exists('kesmas_master_pemeriksaan')) return 0;

        $urut_kategori = ['Air Minum' => 1, 'Air Higiene / Air Sanitasi' => 2, 'Makanan' => 3, 'Lingkungan' => 4];
        // Label kelompok di kwitansi berbeda dari kategori master
        $label_kategori = ['Air Bersih / Air Sanitasi' => 'Air Higiene / Air Sanitasi'];

        $existing = array_column(
            $this->db->select('ref_id')->where('modul', 'kesmas')->where('ref_id IS NOT NULL', null, false)->get('tarif_pemeriksaan')->result_array(),
            'ref_id'
        );

        $master = $this->db->order_by('kategori', 'ASC')->order_by('urutan', 'ASC')->order_by('id_master_pemeriksaan', 'ASC')
            ->get_where('kesmas_master_pemeriksaan', ['is_active' => 1])->result_array();

        $rows = [];
        foreach ($master as $i => $m) {
            if (in_array($m['id_master_pemeriksaan'], $existing)) continue;
            $kategori = $m['kategori'] ?: 'Lainnya';
            $kategori = $label_kategori[$kategori] ?? $kategori;
            $rows[] = [
                'modul' => 'kesmas',
                'kelompok' => $kategori,
                'urut_kelompok' => $urut_kategori[$kategori] ?? 9,
                'nama_item' => $m['nama_pemeriksaan'],
                'keterangan' => $m['kelompok_pemeriksaan'],
                'harga' => 0,
                'urutan' => (int)$m['urutan'] ?: $i + 1,
                'ref_id' => $m['id_master_pemeriksaan'],
                'aktif' => 1,
            ];
        }
        if ($rows) $this->db->insert_batch('tarif_pemeriksaan', $rows);
        return count($rows);
    }

    /* ------------------------------------------------------------------
     * Tarif
     * ------------------------------------------------------------------ */

    public static function tarif_modul(string $modul): string
    {
        return $modul === 'kesmas' ? 'kesmas' : 'klinik';
    }

    public function get_tarif(string $modul, bool $only_active = true): array
    {
        $this->db->where('modul', self::tarif_modul($modul));
        if ($only_active) $this->db->where('aktif', 1);
        return $this->db->order_by('urut_kelompok', 'ASC')->order_by('urutan', 'ASC')->order_by('id_tarif', 'ASC')
            ->get('tarif_pemeriksaan')->result_array();
    }

    /** @return array [kelompok => [items...]] urut sesuai urut_kelompok */
    public function get_tarif_grouped(string $modul, bool $only_active = true): array
    {
        $grouped = [];
        foreach ($this->get_tarif($modul, $only_active) as $t) {
            $grouped[$t['kelompok']][] = $t;
        }
        return $grouped;
    }

    public function update_tarif(int $id, string $modul, array $data): bool
    {
        $allowed = array_intersect_key($data, array_flip(['kelompok', 'nama_item', 'keterangan', 'harga', 'urutan', 'aktif']));
        if (!$allowed) return false;
        return $this->db->where('id_tarif', $id)->where('modul', self::tarif_modul($modul))
            ->update('tarif_pemeriksaan', $allowed);
    }

    public function add_tarif(string $modul, array $data): bool
    {
        $modul = self::tarif_modul($modul);
        $kelompok = trim($data['kelompok']);

        $existing = $this->db->select('urut_kelompok')->where(['modul' => $modul, 'kelompok' => $kelompok])->limit(1)
            ->get('tarif_pemeriksaan')->row_array();
        if ($existing) {
            $urut_kelompok = (int)$existing['urut_kelompok'];
        } else {
            $max = $this->db->select_max('urut_kelompok')->where('modul', $modul)->get('tarif_pemeriksaan')->row_array();
            $urut_kelompok = (int)($max['urut_kelompok'] ?? 0) + 1;
        }
        $max_urut = $this->db->select_max('urutan')->where(['modul' => $modul, 'kelompok' => $kelompok])->get('tarif_pemeriksaan')->row_array();

        return $this->db->insert('tarif_pemeriksaan', [
            'modul' => $modul,
            'kelompok' => $kelompok,
            'urut_kelompok' => $urut_kelompok,
            'nama_item' => trim($data['nama_item']),
            'keterangan' => trim((string)($data['keterangan'] ?? '')) ?: null,
            'harga' => (float)$data['harga'],
            'urutan' => (int)($max_urut['urutan'] ?? 0) + 1,
            'aktif' => 1,
        ]);
    }

    /* ------------------------------------------------------------------
     * Sumber data pendaftaran per modul
     * ------------------------------------------------------------------ */

    /** Daftar pendaftaran + status kwitansi. */
    public function list_sumber(string $modul, string $q = '', int $limit = 20, int $offset = 0): array
    {
        $this->_sumber_query($modul, $q);
        $total = $this->db->count_all_results('', false);
        $this->db->limit($limit, $offset);
        return ['rows' => $this->db->get()->result_array(), 'total' => $total];
    }

    /** Satu pendaftaran (tanpa memerlukan kwitansi). */
    public function get_sumber(string $modul, int $ref_id): ?array
    {
        $this->_sumber_query($modul, '');
        $col = ['kesmas' => 'p.id_permintaan', 'klinik' => 'f.id'][$modul];
        $this->db->where($col, $ref_id);
        $row = $this->db->get()->row_array();
        return $row ?: null;
    }

    /**
     * Kolom seragam: ref_id, kode, nama, tanggal, keterangan, biaya_terdaftar, metode_terdaftar,
     * no_kwitansi, kwitansi_total, kwitansi_tanggal.
     */
    private function _sumber_query(string $modul, string $q): void
    {
        $kw = "k.modul = " . $this->db->escape($modul);

        if ($modul === 'kesmas') {
            $this->db->select("p.id_permintaan AS ref_id, ps.no_registrasi AS kode, pl.nama AS nama, pl.instansi,
                p.tgl_permintaan AS tanggal, CONCAT_WS(' - ', p.nama_sampel, p.jenis_sampel) AS keterangan,
                pb.biaya AS biaya_terdaftar, pb.metode_pembayaran AS metode_terdaftar,
                k.no_kwitansi, k.total AS kwitansi_total, k.tanggal AS kwitansi_tanggal", false)
                ->from('kesmas_permintaan p')
                ->join('kesmas_penerimaan_sampel ps', 'ps.id_permintaan = p.id_permintaan', 'inner')
                ->join('kesmas_pelanggan pl', 'pl.id_pelanggan = p.id_pelanggan', 'left')
                ->join('kesmas_pembayaran pb', 'pb.no_registrasi = ps.no_registrasi', 'left')
                ->join('kwitansi k', "k.ref_id = p.id_permintaan AND $kw", 'left');
            if ($q !== '') {
                $this->db->group_start()->like('ps.no_registrasi', $q)->or_like('pl.nama', $q)
                    ->or_like('pl.instansi', $q)->or_like('p.nama_sampel', $q)->or_like('k.no_kwitansi', $q)->group_end();
            }
            $this->db->order_by('p.id_permintaan', 'DESC');
        } else { // klinik
            $this->db->select("f.id AS ref_id, f.no_register AS kode, ps.nama_pasien AS nama, ps.no_rm,
                f.tgl_form AS tanggal, f.nama_dokter AS keterangan,
                f.jumlah_biaya AS biaya_terdaftar, f.metode_pembayaran AS metode_terdaftar,
                k.no_kwitansi, k.total AS kwitansi_total, k.tanggal AS kwitansi_tanggal", false)
                ->from('form_permintaan_klinik f')
                ->join('pasien ps', 'ps.id_pasien = f.id_pasien', 'left')
                ->join('kwitansi k', "k.ref_id = f.id AND $kw", 'left');
            if ($q !== '') {
                $this->db->group_start()->like('f.no_register', $q)->or_like('ps.nama_pasien', $q)
                    ->or_like('ps.no_rm', $q)->or_like('k.no_kwitansi', $q)->group_end();
            }
            $this->db->order_by('f.id', 'DESC');
        }
    }

    /**
     * Pemeriksaan yang didaftarkan → id tarif yang disarankan (dicentang otomatis).
     * @return array ['terdaftar' => [label...], 'tarif_ids' => [id...], 'tidak_cocok' => [label...]]
     */
    public function saran_tarif(string $modul, int $ref_id): array
    {
        $result = ['terdaftar' => [], 'tarif_ids' => [], 'tidak_cocok' => []];

        if ($modul === 'kesmas') {
            $items = $this->db->select('m.id_master_pemeriksaan, m.nama_pemeriksaan')
                ->from('kesmas_item_permintaan i')
                ->join('kesmas_master_pemeriksaan m', 'm.id_master_pemeriksaan = i.id_master_pemeriksaan', 'inner')
                ->where('i.id_permintaan', $ref_id)->get()->result_array();
            $map = [];
            foreach ($this->get_tarif('kesmas') as $t) {
                if ($t['ref_id']) $map[(int)$t['ref_id']] = (int)$t['id_tarif'];
            }
            foreach ($items as $it) {
                $result['terdaftar'][] = $it['nama_pemeriksaan'];
                if (isset($map[(int)$it['id_master_pemeriksaan']])) {
                    $result['tarif_ids'][] = $map[(int)$it['id_master_pemeriksaan']];
                } else {
                    $result['tidak_cocok'][] = $it['nama_pemeriksaan'];
                }
            }
            $result['tarif_ids'] = array_values(array_unique($result['tarif_ids']));
            return $result;
        }

        // Klinik: detail form ini.
        $details = $this->db->select('d.nama_jenis, d.sub_kategori, d.kategori')->from('form_permintaan_klinik_detail d')
            ->where('d.id_form', $ref_id)->get()->result_array();

        $alias = [];
        foreach ($this->get_tarif('klinik') as $t) {
            foreach (array_filter(explode(',', (string)$t['alias'])) as $a) {
                $alias[trim($a)] = (int)$t['id_tarif'];
            }
            $alias[self::norm($t['nama_item'])] = $alias[self::norm($t['nama_item'])] ?? (int)$t['id_tarif'];
        }

        $seen = [];
        foreach ($details as $d) {
            $label = trim($d['nama_jenis'] . ($d['sub_kategori'] ? ' (' . $d['sub_kategori'] . ')' : ''));
            if (isset($seen[$label])) continue;
            $seen[$label] = true;
            $result['terdaftar'][] = $label;

            // Paket (sub kategori) didahulukan, lalu nama item, lalu kategori.
            $id = null;
            foreach ([$d['sub_kategori'], $d['nama_jenis'], $d['kategori']] as $key) {
                $k = self::norm((string)$key);
                if ($k !== '' && isset($alias[$k])) { $id = $alias[$k]; break; }
            }
            if ($id) {
                $result['tarif_ids'][] = $id;
            } else {
                $result['tidak_cocok'][] = $label;
            }
        }
        $result['tarif_ids'] = array_values(array_unique($result['tarif_ids']));
        return $result;
    }

    public static function norm(string $s): string
    {
        return preg_replace('/[^a-z0-9]/', '', strtolower($s));
    }

    /* ------------------------------------------------------------------
     * Kwitansi
     * ------------------------------------------------------------------ */

    public function get_kwitansi(string $modul, int $ref_id): ?array
    {
        $row = $this->db->get_where('kwitansi', ['modul' => $modul, 'ref_id' => $ref_id])->row_array();
        if (!$row) return null;
        $row['items'] = $this->db->order_by('urut_kelompok', 'ASC')->order_by('id_item', 'ASC')
            ->get_where('kwitansi_item', ['id_kwitansi' => $row['id_kwitansi']])->result_array();
        return $row;
    }

    /**
     * Simpan (buat / perbarui) kwitansi. Harga & nama item diambil dari tabel tarif (bukan dari input),
     * total dihitung ulang di server.
     * @return array|string data kwitansi tersimpan atau pesan error
     */
    public function save(string $modul, int $ref_id, array $sumber, array $input)
    {
        $tarif_ids = array_values(array_unique(array_map('intval', (array)($input['tarif'] ?? []))));
        if (!$tarif_ids) return 'Pilih minimal satu pemeriksaan.';

        $tarif = $this->db->where('modul', self::tarif_modul($modul))->where('aktif', 1)
            ->where_in('id_tarif', $tarif_ids)->get('tarif_pemeriksaan')->result_array();
        if (count($tarif) !== count($tarif_ids)) return 'Sebagian pemeriksaan yang dipilih tidak valid atau sudah tidak aktif.';

        $total = 0.0;
        foreach ($tarif as $t) $total += (float)$t['harga'];

        $tanggal = $input['tanggal'] ?? '';
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $tanggal) || !strtotime($tanggal)) $tanggal = date('Y-m-d');

        $now = date('Y-m-d H:i:s');
        $header = [
            'kode_sampel' => $sumber['kode'] ?? null,
            'nama_pembayar' => trim((string)($input['nama_pembayar'] ?? '')) ?: ($sumber['nama'] ?? null),
            'tanggal' => $tanggal,
            'metode_pembayaran' => trim((string)($input['metode_pembayaran'] ?? '')) ?: 'Tunai',
            'total' => $total,
            'nama_penerima' => trim((string)($input['nama_penerima'] ?? '')) ?: null,
            'keterangan' => trim((string)($input['keterangan'] ?? '')) ?: null,
            'updated_at' => $now,
        ];

        $this->db->trans_start();
        $existing = $this->db->get_where('kwitansi', ['modul' => $modul, 'ref_id' => $ref_id])->row_array();
        if ($existing) {
            $id = (int)$existing['id_kwitansi'];
            $this->db->where('id_kwitansi', $id)->update('kwitansi', $header);
            $this->db->delete('kwitansi_item', ['id_kwitansi' => $id]);
        } else {
            $header += [
                'modul' => $modul,
                'ref_id' => $ref_id,
                'no_kwitansi' => $this->next_no($modul, $tanggal),
                'created_by' => $input['created_by'] ?? null,
                'created_at' => $now,
            ];
            $this->db->insert('kwitansi', $header);
            $id = (int)$this->db->insert_id();
        }

        $items = [];
        foreach ($tarif as $t) {
            $items[] = [
                'id_kwitansi' => $id,
                'id_tarif' => $t['id_tarif'],
                'kelompok' => $t['kelompok'],
                'urut_kelompok' => $t['urut_kelompok'],
                'nama_item' => $t['nama_item'],
                'harga' => $t['harga'],
            ];
        }
        $this->db->insert_batch('kwitansi_item', $items);
        $this->db->trans_complete();

        if (!$this->db->trans_status()) return 'Kwitansi gagal disimpan. Silakan coba lagi.';
        return $this->get_kwitansi($modul, $ref_id);
    }

    public function delete(string $modul, int $ref_id): bool
    {
        $row = $this->db->get_where('kwitansi', ['modul' => $modul, 'ref_id' => $ref_id])->row_array();
        if (!$row) return false;
        $this->db->trans_start();
        $this->db->delete('kwitansi_item', ['id_kwitansi' => $row['id_kwitansi']]);
        $this->db->delete('kwitansi', ['id_kwitansi' => $row['id_kwitansi']]);
        $this->db->trans_complete();
        return $this->db->trans_status();
    }

    /** Nomor: KW/0001/LK/2026 — urut per modul per tahun. */
    private function next_no(string $modul, string $tanggal): string
    {
        $tahun = date('Y', strtotime($tanggal));
        $suffix = '/' . self::MODUL_KODE[$modul] . '/' . $tahun;

        $rows = $this->db->select('no_kwitansi')->like('no_kwitansi', $suffix, 'before')->get('kwitansi')->result_array();
        $max = 0;
        foreach ($rows as $r) {
            if (preg_match('#^KW/(\d+)/#', $r['no_kwitansi'], $m)) $max = max($max, (int)$m[1]);
        }
        return 'KW/' . str_pad((string)($max + 1), 4, '0', STR_PAD_LEFT) . $suffix;
    }

    /* ------------------------------------------------------------------
     * Data cetak
     * ------------------------------------------------------------------ */

    /** Ringkasan per kelompok, hanya kelompok yang memiliki item terpilih. */
    public function ringkasan_kelompok(string $modul, array $kwitansi): array
    {
        $ringkasan = [];
        foreach ($kwitansi['items'] as $it) {
            $ringkasan[$it['kelompok']] = ($ringkasan[$it['kelompok']] ?? 0) + (float)$it['harga'];
        }
        return $ringkasan;
    }
}

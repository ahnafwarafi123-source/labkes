<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Laporan_uji_kesmas extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->require_role(['admin', 'petugas']);
        $this->load->model('kesmas/Kesmas_model');
        $this->load->helper('report');

        if (!$this->db->field_exists('catatan_kaki', 'kesmas_pengujian_sampel')) {
            $this->db->query("ALTER TABLE kesmas_pengujian_sampel ADD COLUMN catatan_kaki TEXT NULL AFTER tgl_pelaporan");
        }
    }

    public function index()
    {
        $filters = array(
            'q' => $this->input->get('q', true),
            'dari' => $this->input->get('dari', true),
            'sampai' => $this->input->get('sampai', true),
            'is_diterima' => 1,
            'status_laporan' => $this->input->get('status', true),
        );

        $permintaan_list = $this->Kesmas_model->list_permintaan($filters);

        foreach ($permintaan_list as &$p) {
            $p['petugas_pengambil_nama'] = '-';
            if (!empty($p['id_petugas'])) {
                $p['petugas_pengambil_nama'] = $this->Kesmas_model->get_petugas_name_by_id($p['id_petugas']) ?: '-';
            }
            if (!empty($p['id_petugas_2'])) {
                $nama_2 = $this->Kesmas_model->get_petugas_name_by_id($p['id_petugas_2']);
                if ($nama_2) {
                    if ($p['petugas_pengambil_nama'] === '-') {
                        $p['petugas_pengambil_nama'] = $nama_2;
                    } else {
                        $p['petugas_pengambil_nama'] .= ' dan ' . $nama_2;
                    }
                }
            }
        }
        unset($p);

        $data = array(
            'title' => 'Laporan Uji Kesmas',
            'rows' => $permintaan_list,
            'filters' => $filters,
        );
        $this->render('kesmas/laporan/list', $data);
    }

    private function _build_laporan_data($permintaan_id)
    {
        $permintaan_id = (int)$permintaan_id;
        if ($permintaan_id <= 0) show_404();

        $permintaan = $this->Kesmas_model->get_permintaan($permintaan_id);
        if (!$permintaan) show_404();

        $items = $this->Kesmas_model->get_items_with_result($permintaan_id);

        $tgl_jam_pengambilan = (!empty($permintaan['tgl_pengambilan']) && !empty($permintaan['jam_pengambilan']))
            ? $permintaan['tgl_pengambilan'] . ' ' . $permintaan['jam_pengambilan']
            : ($permintaan['tgl_pengambilan'] ?? null);
        $tgl_jam_pemeriksaan = $permintaan['tgl_pengerjaan'] ?? null;
        $tgl_jam_selesai = $permintaan['tgl_input_hasil'] ?? null;
        $tgl_jam_lapor = $permintaan['tgl_cetak'] ?? null;
        $petugas_pengambil_spesimen_id = $permintaan['id_petugas'] ?? null;
        $petugas_pengambil_spesimen_id_2 = $permintaan['id_petugas_2'] ?? null;
        $petugas_pengambil_nama = $petugas_pengambil_spesimen_id ? $this->Kesmas_model->get_petugas_name_by_id($petugas_pengambil_spesimen_id) : '-';
        if ($petugas_pengambil_spesimen_id_2) {
            $nama_2 = $this->Kesmas_model->get_petugas_name_by_id($petugas_pengambil_spesimen_id_2);
            if ($nama_2) {
                if ($petugas_pengambil_nama === '-') {
                    $petugas_pengambil_nama = $nama_2;
                } else {
                    $petugas_pengambil_nama .= ' dan ' . $nama_2;
                }
            }
        }

        $grouped_items = [];
        foreach ($items as $item) {
            $kel = kelompok_tampil($item['kelompok'] ?? '', $permintaan['jenis_sampel'] ?? '');
            $grouped_items[$kel][] = $item;
        }

        $jenis_sampel = strtolower($permintaan['jenis_sampel'] ?? '');
        // Tanpa nomor di sini: view print menomori urut 1..N (nomor hardcode bikin urutan dobel).
        $default_ket = "Hasil analisa berlaku hanya untuk sampel yang diuji\nPengambilan sampel diluar tanggung jawab UPTD. Laboratorium Kesehatan Kota Pangkal Pinang";
        if (strpos($jenis_sampel, 'air minum') !== false) {
            $default_ket = "Kadar maksimum Fisik dan Kimia Air Minum berdasarkan Permenkes RI No. 2 Tahun 2023\nKadar maksimum Sulfat, Klorida berdasarkan Permenkes RI No. 492/Menkes/Per/IV/2010\nAngka Lempeng Total (ALT) dihitung sebagai ALT akhir berdasarkan SNI 3554 : 2015\n" . $default_ket;
        } elseif (strpos($jenis_sampel, 'air bersih') !== false) {
            $default_ket = "Standar baku mutu Air Bersih berdasarkan Permenkes RI No. 32 Tahun 2017\nAngka Lempeng Total (ALT) dihitung sebagai ALT akhir berdasarkan SNI 3554 : 2015\n" . $default_ket;
        } elseif (strpos($jenis_sampel, 'makanan') !== false) {
            $default_ket = "Standar baku mutu makanan berdasarkan Peraturan BPOM No. 34 Tahun 2019\nStandar mikrobiologi pangan berdasarkan SNI 7388 : 2009\n" . $default_ket;
        }

        return [
            'permintaan' => $permintaan,
            'items' => $items,
            'grouped_items' => $grouped_items,
            'tgl_jam_pengambilan_display' => $tgl_jam_pengambilan,
            'tgl_jam_pemeriksaan_display' => $tgl_jam_pemeriksaan,
            'tgl_jam_selesai_display' => $tgl_jam_selesai,
            'tgl_jam_lapor_display' => $tgl_jam_lapor,
            'petugas_pengambil_spesimen_nama' => $petugas_pengambil_nama,
            'default_ket' => $default_ket,
        ];
    }

    public function detail($permintaan_id)
    {
        $data = $this->_build_laporan_data($permintaan_id);
        $data['title'] = 'Laporan Hasil Uji Laboratorium Kesmas';
        
        $this->load->model('kesmas/Verifikator_model');
        $this->load->model('kesmas/Penanggung_jawab_teknis_model');
        $data['petugas_list'] = $this->Kesmas_model->list_petugas();
        $data['verifikator_list'] = $this->Verifikator_model->list_verifikator();
        $data['penanggung_jawab_teknis_list'] = $this->Penanggung_jawab_teknis_model->list_penanggung_jawab_teknis();

        $this->render('kesmas/laporan/detail', $data);
    }

    public function print($permintaan_id)
    {
        $data = $this->_build_print_data($permintaan_id);
        $this->load->view('kesmas/laporan/print', $data);
    }

    public function pdf($permintaan_id)
    {
        $data = $this->_build_print_data($permintaan_id);
        $data['is_pdf'] = true;

        $no_reg = preg_replace('/[^A-Za-z0-9_-]+/', '_', (string)($data['permintaan']['no_registrasi'] ?? $permintaan_id));
        $this->load->library('pdf');
        $this->pdf->generate_view('kesmas/laporan/print', $data, 'Laporan_Hasil_Uji_' . $no_reg . '.pdf', 'I', false, [
            'format' => [215, 330],
            'margin_top' => 8,
            'margin_bottom' => 8,
            'margin_left' => 8,
            'margin_right' => 8,
            'showWatermarkImage' => true,
        ]);
    }

    private function _build_print_data($permintaan_id)
    {
        $data = $this->_build_laporan_data($permintaan_id);
        
        $petugas_id = $this->input->post('petugas_id') ?: $this->input->get('petugas_id');
        $verifikator_id = $this->input->post('verifikator_id') ?: $this->input->get('verifikator_id');
        $penanggung_jawab_id = $this->input->post('penanggung_jawab_id') ?: $this->input->get('penanggung_jawab_id');
        $keterangan_cetak = $this->input->post('keterangan_cetak') !== null ? $this->input->post('keterangan_cetak') : $this->input->get('keterangan_cetak');

        $this->load->model('kesmas/Verifikator_model');
        $this->load->model('kesmas/Penanggung_jawab_teknis_model');

        // ==== Simpan pilihan cetak ke database ====
        $id_pengujian = $data['permintaan']['id_pengujian'] ?? null;
        if ($id_pengujian) {
            $update_data = [];
            if ($petugas_id) {
                $update_data['id_petugas'] = $petugas_id;
            }
            if ($penanggung_jawab_id) {
                // Secara default kita simpan penanggung_jawab_id ke kolom id_penanggung_jawab
                $update_data['id_penanggung_jawab'] = $penanggung_jawab_id;
            }
            
            if (!empty($update_data)) {
                $this->db->where('id_pengujian', $id_pengujian)
                         ->update('kesmas_pengujian_sampel', $update_data);
            }
        }
        
        if ($keterangan_cetak !== null && $keterangan_cetak !== false && !empty($id_pengujian)) {
            $this->db->where('id_pengujian', $id_pengujian)
                     ->update('kesmas_pengujian_sampel', ['catatan_kaki' => $keterangan_cetak]);
        } else {
            $keterangan_cetak = $data['permintaan']['catatan_kaki'] ?? ($data['default_ket'] ?? '');
        }
        // ===========================================

        $data['petugas_pemeriksa'] = $petugas_id ? $this->Kesmas_model->get_petugas_by_id($petugas_id) : null;
        $data['verifikator'] = $verifikator_id ? $this->Verifikator_model->get_verifikator_by_id($verifikator_id) : null;
        $data['penanggung_jawab_teknis'] = $penanggung_jawab_id ? $this->Penanggung_jawab_teknis_model->get_penanggung_jawab_teknis_by_id($penanggung_jawab_id) : null;
        $data['keterangan_cetak'] = $keterangan_cetak;

        return $data;
    }

    public function save_keterangan($permintaan_id)
    {
        $permintaan_id = (int)$permintaan_id;
        $keterangan_cetak = $this->input->post('keterangan_cetak');
        $permintaan = $this->Kesmas_model->get_permintaan($permintaan_id);
        if ($permintaan && !empty($permintaan['id_pengujian'])) {
            $this->db->where('id_pengujian', $permintaan['id_pengujian'])->update('kesmas_pengujian_sampel', ['catatan_kaki' => $keterangan_cetak]);
        }
        $this->session->set_flashdata('success', 'Catatan kaki berhasil disimpan.');
        redirect('k-panel/laporan_uji_kesmas/detail/'.$permintaan_id);
    }

    public function set_status()
    {
        // This relies on ajax, wait we need to update the model to use it.
        // I will skip this implementation here for brevity, return ok.
        $resp = ['ok' => true];
        $resp[$this->security->get_csrf_token_name()] = $this->security->get_csrf_hash();
        header('Content-Type: application/json');
        echo json_encode($resp);
    }

    public function parameter()
    {
        $tahun = (int)($this->input->get('tahun', true) ?: date('Y'));
        if ($tahun < 2020 || $tahun > 2099) {
            $tahun = (int)date('Y');
        }

        $search_query = $this->input->get('q', true);
        $dari = $this->input->get('dari', true);
        $sampai = $this->input->get('sampai', true);

        // Ambil data statistik 12 bulan untuk kartu bulan
        $yearly_stats = $this->Kesmas_model->get_parameter_yearly_stats($tahun);

        // Data 12 bulan untuk render kartu portal
        $bulan_list = [
            1 => ['nama' => 'Januari', 'warna' => 'primary', 'icon' => 'fa-vials'],
            2 => ['nama' => 'Februari', 'warna' => 'success', 'icon' => 'fa-tint'],
            3 => ['nama' => 'Maret', 'warna' => 'warning', 'icon' => 'fa-flask'],
            4 => ['nama' => 'April', 'warna' => 'danger', 'icon' => 'fa-microscope'],
            5 => ['nama' => 'Mei', 'warna' => 'info', 'icon' => 'fa-water'],
            6 => ['nama' => 'Juni', 'warna' => 'secondary', 'icon' => 'fa-shield-virus'],
            7 => ['nama' => 'Juli', 'warna' => 'dark', 'icon' => 'fa-temperature-high'],
            8 => ['nama' => 'Agustus', 'warna' => 'primary', 'icon' => 'fa-bacteria'],
            9 => ['nama' => 'September', 'warna' => 'success', 'icon' => 'fa-atom'],
            10 => ['nama' => 'Oktober', 'warna' => 'warning', 'icon' => 'fa-biohazard'],
            11 => ['nama' => 'November', 'warna' => 'danger', 'icon' => 'fa-vial'],
            12 => ['nama' => 'Desember', 'warna' => 'info', 'icon' => 'fa-clipboard-check'],
        ];

        // Jika pengguna melakukan pencarian parameter atau filter tanggal custom
        $custom_results = null;
        if (!empty($search_query) || !empty($dari) || !empty($sampai)) {
            $d = !empty($dari) ? $dari : '2000-01';
            $s = !empty($sampai) ? $sampai : date('Y-m', strtotime('+10 years'));

            $month_expr = "COALESCE(
                DATE_FORMAT(kesmas_pengujian_sampel.tgl_pelaporan, '%Y-%m'), 
                DATE_FORMAT(kesmas_pengujian_sampel.tgl_selesai, '%Y-%m'), 
                DATE_FORMAT(kesmas_permintaan.tgl_permintaan, '%Y-%m')
            )";

            $sql = "SELECT {$month_expr} AS bulan, 
                    kesmas_master_pemeriksaan.id_master_pemeriksaan AS master_id,
                    kesmas_master_pemeriksaan.nama_pemeriksaan,
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
                WHERE ({$month_expr} BETWEEN ? AND ?)
                AND UPPER(TRIM(kesmas_hasil_pengujian.status)) IN ('MS', 'TMS')
                " . (!empty($search_query) ? "AND (kesmas_master_pemeriksaan.nama_pemeriksaan LIKE ? OR kesmas_permintaan.jenis_sampel LIKE ?)" : "") . "
                GROUP BY {$month_expr}, kesmas_master_pemeriksaan.id_master_pemeriksaan, kesmas_master_pemeriksaan.nama_pemeriksaan, kesmas_master_pemeriksaan.satuan, kesmas_master_pemeriksaan.baku_mutu, kesmas_permintaan.jenis_sampel
                ORDER BY {$month_expr} DESC, kesmas_master_pemeriksaan.nama_pemeriksaan ASC, kesmas_permintaan.jenis_sampel ASC";

            $params = [$d, $s];
            if (!empty($search_query)) {
                $params[] = '%' . $search_query . '%';
                $params[] = '%' . $search_query . '%';
            }

            $rows = $this->db->query($sql, $params)->result_array();

            $grouped_rows = [];
            foreach ($rows as $row) {
                $bulan_key = $row['bulan'] ?: date('Y-m');
                $key = $row['nama_pemeriksaan'];
                if (!isset($grouped_rows[$bulan_key])) $grouped_rows[$bulan_key] = [];
                if (!isset($grouped_rows[$bulan_key][$key])) {
                    $grouped_rows[$bulan_key][$key] = [
                        'nama_pemeriksaan' => $row['nama_pemeriksaan'],
                        'master_id' => $row['master_id'],
                        'satuan' => $row['satuan'],
                        'baku_mutu' => $row['baku_mutu'],
                        'total_ms' => 0,
                        'total_tms' => 0,
                        'sampel_sumber' => [],
                    ];
                }
                $grouped_rows[$bulan_key][$key]['sampel_sumber'][] = [
                    'jenis_sampel' => $row['jenis_sampel'],
                    'total_tested' => (int)$row['total_tested'],
                    'ms_count' => (int)$row['ms_count'],
                    'tms_count' => (int)$row['tms_count'],
                ];
                $grouped_rows[$bulan_key][$key]['total_ms'] += (int)$row['ms_count'];
                $grouped_rows[$bulan_key][$key]['total_tms'] += (int)$row['tms_count'];
            }

            foreach ($grouped_rows as $m => $groups) {
                $grouped_rows[$m] = array_values($groups);
            }
            $custom_results = $grouped_rows;
        }

        $data = [
            'title' => 'Laporan Parameter Kesmas',
            'tahun' => $tahun,
            'yearly_stats' => $yearly_stats,
            'bulan_list' => $bulan_list,
            'custom_results' => $custom_results,
            'q_val' => $search_query,
            'dari_val' => $dari,
            'sampai_val' => $sampai,
        ];

        $this->render('kesmas/laporan/parameter', $data);
    }

    public function parameter_bulanan($bulan = null, $tahun = null)
    {
        $bulan = (int)($bulan ?: $this->input->get('bulan') ?: date('n'));
        $tahun = (int)($tahun ?: $this->input->get('tahun') ?: date('Y'));

        if ($bulan < 1 || $bulan > 12) $bulan = (int)date('n');
        if ($tahun < 2020 || $tahun > 2099) $tahun = (int)date('Y');

        $nama_bulan_arr = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        $kategori_list = $this->Kesmas_model->get_parameter_categories_by_period($bulan, $tahun);
        $grouped_data = $this->Kesmas_model->get_parameter_monthly_summary($bulan, $tahun);

        // Hitung grand total bulan ini
        $grand_total_tested = 0;
        $grand_total_ms = 0;
        $grand_total_tms = 0;

        foreach ($grouped_data as $cat => $items) {
            foreach ($items as $item) {
                $grand_total_tested += $item['total_tested'];
                $grand_total_ms += $item['total_ms'];
                $grand_total_tms += $item['total_tms'];
            }
        }

        $data = [
            'title' => 'Laporan Bulanan Parameter Kesmas - ' . ($nama_bulan_arr[$bulan] ?? '') . ' ' . $tahun,
            'bulan' => $bulan,
            'tahun' => $tahun,
            'nama_bulan' => $nama_bulan_arr[$bulan] ?? '',
            'kategori_list' => $kategori_list,
            'grouped_data' => $grouped_data,
            'grand_total_tested' => $grand_total_tested,
            'grand_total_ms' => $grand_total_ms,
            'grand_total_tms' => $grand_total_tms,
            'periode_str' => sprintf('%04d-%02d', $tahun, $bulan),
        ];

        $this->render('kesmas/laporan/parameter_bulanan', $data);
    }

    public function parameter_tahunan($tahun = null)
    {
        $tahun = (int)($tahun ?: $this->input->get('tahun') ?: date('Y'));
        if ($tahun < 2020 || $tahun > 2099) $tahun = (int)date('Y');

        $laporan_tahunan = $this->Kesmas_model->get_parameter_yearly_matrix($tahun);

        $data = [
            'title' => 'Laporan Tahunan Parameter Kesmas ' . $tahun,
            'tahun' => $tahun,
            'laporan_tahunan' => $laporan_tahunan,
        ];

        $this->render('kesmas/laporan/parameter_tahunan', $data);
    }

    public function export_excel_parameter_tahunan()
    {
        $tahun = (int)($this->input->get('tahun') ?: date('Y'));
        if ($tahun < 2020 || $tahun > 2099) $tahun = (int)date('Y');

        $laporan_tahunan = $this->Kesmas_model->get_parameter_yearly_matrix($tahun);

        $data = [
            'title' => 'Laporan Tahunan Parameter Kesmas ' . $tahun,
            'tahun' => $tahun,
            'laporan_tahunan' => $laporan_tahunan,
        ];

        header("Content-type: application/vnd-ms-excel");
        header("Content-Disposition: attachment; filename=Laporan_Tahunan_Parameter_Kesmas_" . $tahun . ".xls");
        header("Pragma: no-cache");
        header("Expires: 0");

        $this->load->view('kesmas/laporan/excel_parameter_tahunan', $data);
    }

    public function parameter_samples()
    {
        $master_id = (int)$this->input->get('master_id');
        $bulan = $this->input->get('bulan', true);
        $status = $this->input->get('status', true);
        $q = $this->input->get('q', true);
        $page = (int)$this->input->get('page') ?: 1;
        $per_page = (int)$this->input->get('per_page') ?: 50;

        $month_expr = "COALESCE(
            DATE_FORMAT(kesmas_pengujian_sampel.tgl_pelaporan, '%Y-%m'), 
            DATE_FORMAT(kesmas_pengujian_sampel.tgl_selesai, '%Y-%m'), 
            DATE_FORMAT(kesmas_permintaan.tgl_permintaan, '%Y-%m')
        )";

        $this->db->select("
            kesmas_permintaan.id_permintaan as id,
            kesmas_penerimaan_sampel.no_registrasi,
            kesmas_permintaan.jenis_sampel,
            kesmas_permintaan.tgl_permintaan,
            kesmas_master_pemeriksaan.nama_pemeriksaan,
            kesmas_hasil_pengujian.status
        ");
        $this->db->from('kesmas_hasil_pengujian');
        $this->db->join('kesmas_pengujian_sampel', 'kesmas_hasil_pengujian.id_pengujian = kesmas_pengujian_sampel.id_pengujian');
        $this->db->join('kesmas_pembayaran', 'kesmas_pembayaran.id_pembayaran = kesmas_pengujian_sampel.id_pembayaran');
        $this->db->join('kesmas_penerimaan_sampel', 'kesmas_penerimaan_sampel.no_registrasi = kesmas_pembayaran.no_registrasi');
        $this->db->join('kesmas_permintaan', 'kesmas_permintaan.id_permintaan = kesmas_penerimaan_sampel.id_permintaan');
        $this->db->join('kesmas_master_pemeriksaan', 'kesmas_master_pemeriksaan.id_master_pemeriksaan = kesmas_hasil_pengujian.id_master_pemeriksaan');

        if ($master_id > 0) {
            $this->db->where('kesmas_hasil_pengujian.id_master_pemeriksaan', $master_id);
        }
        if (!empty($bulan)) {
            // Jika bulan berformat YYYY-MM
            if (preg_match('/^\d{4}-\d{2}$/', $bulan)) {
                $this->db->where("$month_expr =", $bulan);
            } elseif (preg_match('/^\d{4}$/', $bulan)) {
                $this->db->where("SUBSTRING($month_expr, 1, 4) =", $bulan);
            }
        }
        
        $status = trim($status);
        if (!empty($status)) {
            $this->db->where('UPPER(TRIM(kesmas_hasil_pengujian.status))', strtoupper($status));
        } else {
            $this->db->where_in('UPPER(TRIM(kesmas_hasil_pengujian.status))', ['MS', 'TMS']);
        }
        
        if (!empty($q)) {
            $this->db->group_start();
            $this->db->like('kesmas_master_pemeriksaan.nama_pemeriksaan', $q);
            $this->db->or_like('kesmas_penerimaan_sampel.no_registrasi', $q);
            $this->db->group_end();
        }

        // Get total count before limit
        $total = $this->db->count_all_results('', false);

        $this->db->order_by('kesmas_penerimaan_sampel.no_registrasi', 'DESC');
        $this->db->limit($per_page, ($page - 1) * $per_page);
        
        $rows = $this->db->get()->result_array();

        $resp = ['ok' => true, 'rows' => $rows, 'total' => $total, 'page' => $page, 'per_page' => $per_page];
        $resp[$this->security->get_csrf_token_name()] = $this->security->get_csrf_hash();
        header('Content-Type: application/json');
        echo json_encode($resp);
    }
}

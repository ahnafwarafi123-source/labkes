<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Home — Dashboard Terpadu.
 *
 * Satu halaman dashboard untuk seluruh modul (Kesmas + Klinik).
 * Konten ditampilkan sesuai modul akun yang login:
 *   - kesmas (admin/petugas)         : blok Kesmas saja
 *   - klinik (admin/petugas)         : blok Klinik sesuai role klinik
 * Menyilang dari sesi tidak pernah digabung (admin tidak melihat modul lain).
 *
 * Role/module hanya diambil dari session, tidak pernah dari input.
 */
class Home extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function index()
    {
        $u = current_user();
        $module = $u['module'] ?? '';
        $role = strtolower((string)($u['role'] ?? ''));

        // Dashboard murni sesuai modul akun yang login (tidak digabung antar modul).
        $show_kesmas = ($module === 'kesmas');
        $show_klinik = ($module === 'klinik');

        // Varian blok klinik sesuai role klinik
        $klinik_block = '';
        if ($show_klinik) {
            $klinik_block = 'dashboard'; // default (admin / petugas pendaftaran)
            if ($module === 'klinik') {
                if ($role === 'petugas pendaftaran') {
                    $klinik_block = 'pendaftaran';
                } elseif ($role === 'pemeriksa sampel') {
                    $klinik_block = 'klinik';
                } elseif ($role === 'petugas rm') {
                    $klinik_block = 'dokter';
                }
            }
        }

        $data = array(
            'title' => 'Dashboard',
            'show_kesmas' => $show_kesmas,
            'klinik_block' => $klinik_block,
        );

        if ($show_kesmas) {
            $data = array_merge($data, $this->_kesmas_dashboard_data());
        }

        if ($show_klinik) {
            $data = array_merge($data, $this->_klinik_dashboard_data($klinik_block));
        }

        // Teruskan seluruh data blok agar view gabungan bisa memuat subview dengan variabel yang sama.
        $data['view_data'] = $data;

        $this->render('home/dashboard', $data);
    }

    /**
     * Data blok dashboard Kesmas (replikasi kesmas/Dashboard::index).
     */
    private function _kesmas_dashboard_data(): array
    {
        $this->load->model('kesmas/Kesmas_model');
        $this->load->model('kesmas/Activity_logs_model');
        $this->load->helper(array('security', 'kesmas_audit'));

        $categories = array('Air Minum', 'Air Bersih', 'Makanan', 'Lingkungan');

        $this->db->select('kategori_sampel, jenis_sampel, COUNT(id_permintaan) as total', FALSE);
        $this->db->group_by('kategori_sampel, jenis_sampel');
        $query = $this->db->get('kesmas_permintaan');

        $chart_data = [];
        if ($query !== false) {
            $chart_data = $query->result_array();
        }

        $data_map = array(
            'Air Minum' => 0,
            'Air Bersih' => 0,
            'Makanan' => 0,
            'Lingkungan' => 0,
        );

        foreach ($chart_data as $row) {
            $js = strtolower(trim($row['jenis_sampel']));
            $ks = strtolower(trim($row['kategori_sampel']));
            $total = (int)$row['total'];

            if (strpos($js, 'air minum') !== false || strpos($ks, 'air_minum') !== false) {
                $data_map['Air Minum'] += $total;
            } elseif (strpos($js, 'air bersih') !== false || strpos($js, 'sanitasi') !== false || strpos($ks, 'air_bersih') !== false) {
                $data_map['Air Bersih'] += $total;
            } elseif (strpos($js, 'makan') !== false || strpos($ks, 'makanan') !== false) {
                $data_map['Makanan'] += $total;
            } elseif (strpos($js, 'lingkungan') !== false || strpos($js, 'usap') !== false || strpos($ks, 'lingkungan') !== false) {
                $data_map['Lingkungan'] += $total;
            } else {
                if (strpos($ks, 'air') !== false) {
                    $data_map['Air Bersih'] += $total;
                } else {
                    $data_map['Lingkungan'] += $total;
                }
            }
        }

        $labels = [];
        $totals = [];
        $colors = [
            'Air Minum' => 'rgba(13, 110, 253, 0.8)',
            'Air Bersih' => 'rgba(6, 182, 212, 0.8)',
            'Makanan' => 'rgba(245, 158, 11, 0.8)',
            'Lingkungan' => 'rgba(34, 197, 94, 0.8)',
        ];
        $border_colors = [
            'Air Minum' => 'rgba(13, 110, 253, 1)',
            'Air Bersih' => 'rgba(6, 182, 212, 1)',
            'Makanan' => 'rgba(245, 158, 11, 1)',
            'Lingkungan' => 'rgba(34, 197, 94, 1)',
        ];

        $bg_colors = [];
        $border_color_array = [];
        foreach ($categories as $cat) {
            $labels[] = $cat;
            $totals[] = isset($data_map[$cat]) ? $data_map[$cat] : 0;
            $bg_colors[] = $colors[$cat];
            $border_color_array[] = $border_colors[$cat];
        }

        $total_permintaan = $this->db->count_all('kesmas_permintaan');

        $this->db->where('status', 'Diterima');
        $this->db->from('kesmas_penerimaan_sampel');
        $total_diterima = $this->db->count_all_results();

        $this->db->from('kesmas_permintaan');
        $this->db->join('kesmas_penerimaan_sampel', 'kesmas_permintaan.id_permintaan = kesmas_penerimaan_sampel.id_permintaan', 'left');
        $this->db->where('(CASE WHEN kesmas_penerimaan_sampel.no_registrasi IS NOT NULL THEN 1 ELSE 0 END) =', 0, false);
        $this->db->where("(kesmas_penerimaan_sampel.status IS NULL OR kesmas_penerimaan_sampel.status != 'Ditolak')", null, false);
        $total_pending = $this->db->count_all_results();

        $this->db->select('COUNT(id_pengujian) AS total', FALSE);
        $this->db->where('tgl_pelaporan IS NOT NULL', null, false);
        $laporan_query = $this->db->get('kesmas_pengujian_sampel');

        $laporan_row = [];
        if ($laporan_query !== false) {
            $laporan_row = $laporan_query->row_array();
        }
        $total_laporan = isset($laporan_row['total']) ? (int)$laporan_row['total'] : 0;

        $recent_activities = get_recent_activities(5);

        $this->db->select('kesmas_permintaan.id_permintaan, kesmas_penerimaan_sampel.no_registrasi, kesmas_permintaan.nama_sampel, kesmas_permintaan.tgl_permintaan as created_at, kesmas_pelanggan.nama as user_nama');
        $this->db->from('kesmas_permintaan');
        $this->db->join('kesmas_pelanggan', 'kesmas_pelanggan.id_pelanggan = kesmas_permintaan.id_pelanggan', 'left');
        $this->db->join('kesmas_penerimaan_sampel', 'kesmas_penerimaan_sampel.id_permintaan = kesmas_permintaan.id_permintaan', 'left');
        $this->db->order_by('kesmas_permintaan.id_permintaan', 'DESC');
        $this->db->limit(5);
        $recent_query = $this->db->get();

        $recent_permintaan = [];
        if ($recent_query !== false) {
            $recent_permintaan = $recent_query->result_array();
        }

        return array(
            'chart_labels' => json_encode($labels),
            'chart_totals' => json_encode($totals),
            'chart_bg_colors' => json_encode($bg_colors),
            'chart_border_colors' => json_encode($border_color_array),
            'total_permintaan' => $total_permintaan,
            'total_diterima' => $total_diterima,
            'total_pending' => $total_pending,
            'total_laporan' => $total_laporan,
            'lingkungan_count' => isset($data_map['Lingkungan']) ? $data_map['Lingkungan'] : 0,
            'air_minum_count' => isset($data_map['Air Minum']) ? $data_map['Air Minum'] : 0,
            'air_bersih_count' => isset($data_map['Air Bersih']) ? $data_map['Air Bersih'] : 0,
            'makanan_count' => isset($data_map['Makanan']) ? $data_map['Makanan'] : 0,
            'recent_activities' => $recent_activities,
            'recent_permintaan' => $recent_permintaan,
        );
    }

    /**
     * Data blok dashboard Klinik (replikasi klinik/Dashboard::index).
     */
    private function _klinik_dashboard_data(string $block): array
    {
        $today = date('Y-m-d');

        $query = $this->db->query("
            SELECT COUNT(*) as total FROM (
                SELECT nik FROM pasien
                WHERE DATE(created_at) = '$today' AND nik IS NOT NULL AND nik != ''
                UNION
                SELECT p.nik FROM form_permintaan_klinik f
                JOIN pasien p ON f.id_pasien = p.id_pasien
                WHERE DATE(f.tgl_form) = '$today' AND p.nik IS NOT NULL AND p.nik != ''
                UNION
                SELECT p.nik FROM kunjungan_rm k
                JOIN pasien p ON k.no_rm = p.no_rm
                WHERE DATE(k.tanggal_kunjungan) = '$today' AND p.nik IS NOT NULL AND p.nik != ''
            ) as total_unik
        ");
        $total_pasien = $query->row()->total;

        $query_terbaru = $this->db->query("
            SELECT COALESCE(updated_at, created_at) as tgl_daftar, no_rm as no_registrasi, nama_pasien, gender as jenis_kelamin, no_rm, nik, umur
            FROM pasien
            WHERE DATE(created_at) = '$today' OR DATE(updated_at) = '$today'
            ORDER BY tgl_daftar DESC
            LIMIT 10
        ");
        $pasien_terbaru = $query_terbaru->result();

        $query_pembayaran = $this->db->query("
            SELECT SUM(total_biaya) as total_pendapatan
            FROM pembayaran
            WHERE DATE(created_at) = '$today'
        ");
        $total_pendapatan = $query_pembayaran->row()->total_pendapatan ?? 0;

        $data = array(
            'total_pasien' => $total_pasien,
            'total_pendapatan' => $total_pendapatan,
            'pasien_terbaru' => $pasien_terbaru,
        );

        // Data tambahan khusus pemeriksa sampel
        if ($block === 'klinik') {
            $this->load->model('Klinik/Uji_klinik_model');
            $this->load->model('Klinik/Hasil_laporan_model');
            $data['formulir'] = $this->Uji_klinik_model->get_today_formulir_belum_input();
            $data['hasil_lab_siap'] = count($this->Hasil_laporan_model->get_pasien_dengan_soap());
        }

        return $data;
    }
}
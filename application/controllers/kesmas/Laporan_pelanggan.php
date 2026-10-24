<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Controller: Laporan Per Pelanggan
 * Route  : /k-panel/laporan_pelanggan
 *
 * Fitur baru yang sepenuhnya terpisah dari Laporan_uji_kesmas.
 * Tidak mengubah controller/view laporan lama sama sekali.
 *
 * @property Kesmas_model $Kesmas_model
 * @property Penanggung_jawab_teknis_model $Penanggung_jawab_teknis_model
 */
class Laporan_pelanggan extends MY_Controller
{
    /** Default catatan kaki jika pelanggan belum pernah menyimpan. */
    const DEFAULT_CATATAN_KAKI = "Hasil analisa berlaku hanya untuk sampel yang diuji.\nPengambilan sampel diluar tanggung jawab UPTD. Laboratorium Kesehatan Kota Pangkal Pinang.";

    public function __construct()
    {
        parent::__construct();
        $this->require_role(['admin', 'petugas']);
        $this->load->model('kesmas/Kesmas_model');
        $this->load->model('kesmas/Penanggung_jawab_teknis_model');

        // Auto-migrate: tambah kolom catatan_kaki ke tabel kesmas_pelanggan jika belum ada
        if (!$this->db->field_exists('catatan_kaki', 'kesmas_pelanggan')) {
            $this->db->query("ALTER TABLE kesmas_pelanggan ADD COLUMN catatan_kaki TEXT NULL AFTER instansi");
        }
    }

    /**
     * Daftar pelanggan beserta ringkasan permintaan.
     * Filter: q, dari, sampai, jenis_sampel
     */
    public function index()
    {
        $filters = [
            'q'            => $this->input->get('q', true),
            'dari'         => $this->input->get('dari', true),
            'sampai'       => $this->input->get('sampai', true),
            'jenis_sampel' => $this->input->get('jenis_sampel', true),
        ];

        $rows = $this->Kesmas_model->list_pelanggan_dengan_permintaan($filters);

        $data = [
            'title'   => 'Laporan Per Pelanggan',
            'rows'    => $rows,
            'filters' => $filters,
        ];
        $this->render('kesmas/laporan_pelanggan/list', $data);
    }

    /**
     * Detail semua permintaan + hasil untuk satu pelanggan.
     * Filter: dari, sampai, jenis_sampel
     */
    public function detail($id_pelanggan)
    {
        $id_pelanggan = (int)$id_pelanggan;
        if ($id_pelanggan <= 0) show_404();

        $pelanggan = $this->Kesmas_model->get_pelanggan_by_id($id_pelanggan);
        if (!$pelanggan) show_404();

        $filters = [
            'dari'         => $this->input->get('dari', true),
            'sampai'       => $this->input->get('sampai', true),
            'jenis_sampel' => $this->input->get('jenis_sampel', true),
        ];

        $this->load->model('kesmas/Verifikator_model');
        $petugas_list = $this->Kesmas_model->list_petugas();
        $verifikator_list = $this->Verifikator_model->list_verifikator();
        $penanggung_jawab_teknis_list = $this->Penanggung_jawab_teknis_model->list_penanggung_jawab_teknis();

        $permintaan_list = $this->Kesmas_model->get_permintaan_by_pelanggan($id_pelanggan, $filters);

        $data = [
            'title'           => 'Detail Laporan: ' . $pelanggan['nama'],
            'pelanggan'       => $pelanggan,
            'permintaan_list' => $permintaan_list,
            'filters'         => $filters,
            'petugas_list'    => $petugas_list,
            'verifikator_list' => $verifikator_list,
            'penanggung_jawab_teknis_list' => $penanggung_jawab_teknis_list,
        ];
        $this->render('kesmas/laporan_pelanggan/detail', $data);
    }

    /**
     * Halaman cetak laporan per pelanggan (A4, auto-print).
     * GET params: dari, sampai, jenis_sampel, penanggung_jawab_id
     */
    public function print_laporan($id_pelanggan)
    {
        $id_pelanggan = (int)$id_pelanggan;
        if ($id_pelanggan <= 0) show_404();

        $pelanggan = $this->Kesmas_model->get_pelanggan_by_id($id_pelanggan);
        if (!$pelanggan) show_404();

        $filters = [
            'dari'         => $this->input->get('dari', true),
            'sampai'       => $this->input->get('sampai', true),
            'jenis_sampel' => $this->input->get('jenis_sampel', true),
        ];

        $permintaan_list = $this->Kesmas_model->get_permintaan_by_pelanggan($id_pelanggan, $filters);

        $penanggung_jawab_id = (int)$this->input->get('penanggung_jawab_id', true);
        $penanggung_jawab    = $penanggung_jawab_id
            ? $this->Penanggung_jawab_teknis_model->get_penanggung_jawab_teknis_by_id($penanggung_jawab_id)
            : null;

        $petugas_id = (int)$this->input->get('petugas_id', true);
        $petugas_pemeriksa = $petugas_id ? $this->Kesmas_model->get_petugas_by_id($petugas_id) : null;

        $this->load->model('kesmas/Verifikator_model');
        $verifikator_id = (int)$this->input->get('verifikator_id', true);
        $verifikator = $verifikator_id ? $this->Verifikator_model->get_verifikator_by_id($verifikator_id) : null;

        // Siapkan variabel agregasi untuk mengikuti format laporan/print.php
        $base_permintaan = [
            'no_registrasi' => '-',
            'nama_sampel' => '',
            'kategori_sampel' => '',
            'jenis_sampel' => '',
            'lokasi_pengambilan' => '',
            'nama' => $pelanggan['nama'] ?? '-',
            'alamat' => $pelanggan['alamat'] ?? '-',
            'kecamatan' => $pelanggan['kecamatan'] ?? '-',
            'kelurahan' => $pelanggan['kelurahan'] ?? '-',
            'objek' => $pelanggan['objek'] ?? '-',
            'tgl_permintaan' => null,
            'status_kelayakan' => ''
        ];

        $grouped_items      = [];
        $petugas_per_sampel = []; // [sample_key => "Nama1, Nama2"] — per sampel
        $tgl_pengambilan_arr = [];
        $tgl_pemeriksaan_arr = [];
        $tgl_selesai_arr     = [];
        $tgl_lapor_arr       = [];
        $n_sampel = [];
        $k_sampel = [];
        $j_sampel = [];
        $l_sampel = [];
        $s_kelayakan = [];
        $all_reg  = [];

        if (!empty($permintaan_list)) {
            $base_permintaan['no_registrasi'] = $permintaan_list[0]['no_registrasi'] ?? '-';
            $base_permintaan['tgl_permintaan'] = $permintaan_list[0]['tgl_permintaan'] ?? null;

            foreach ($permintaan_list as $p) {
                if (!empty($p['no_registrasi'])) $all_reg[] = $this->_extract_reg_num($p['no_registrasi']);
                if (!empty($p['nama_sampel']))        $n_sampel[]    = $p['nama_sampel'];
                if (!empty($p['kategori_sampel']))    $k_sampel[]    = $p['kategori_sampel'];
                if (!empty($p['jenis_sampel']))       $j_sampel[]    = $p['jenis_sampel'];
                if (!empty($p['lokasi_pengambilan'])) $l_sampel[]    = $p['lokasi_pengambilan'];
                if (!empty($p['status_kelayakan']))   $s_kelayakan[] = $p['status_kelayakan'];

                if (!empty($p['tgl_pengambilan']) && !empty($p['jam_pengambilan'])) {
                    $tgl_pengambilan_arr[] = $p['tgl_pengambilan'] . ' ' . $p['jam_pengambilan'];
                }
                if (!empty($p['tgl_pengerjaan']))   $tgl_pemeriksaan_arr[] = $p['tgl_pengerjaan'];
                if (!empty($p['tgl_input_hasil']))  $tgl_selesai_arr[]     = $p['tgl_input_hasil'];
                if (!empty($p['tgl_cetak']))        $tgl_lapor_arr[]       = $p['tgl_cetak'];

                // === Resolve nama petugas PER SAMPEL (profesional) ===
                $full_reg   = !empty($p['no_registrasi']) ? $p['no_registrasi'] : '-';
                // Hanya tampilkan no. registrasi (tanpa nama sampel)
                $sample_key = $full_reg;

                $nama_petugas_arr = [];
                if (!empty($p['id_petugas'])) {
                    $n = $this->Kesmas_model->get_petugas_name_by_id((int)$p['id_petugas']);
                    if ($n) $nama_petugas_arr[] = $n;
                }
                if (!empty($p['id_petugas_2'])) {
                    $n = $this->Kesmas_model->get_petugas_name_by_id((int)$p['id_petugas_2']);
                    if ($n) $nama_petugas_arr[] = $n;
                }
                $petugas_per_sampel[$sample_key] = !empty($nama_petugas_arr)
                    ? implode(' , ', $nama_petugas_arr)
                    : '-';

                // Group items
                // Group items by Kelompok -> Nama Pemeriksaan -> Samples
                if (!empty($p['items']) && is_array($p['items'])) {
                    foreach ($p['items'] as $it) {
                        $kel = kelompok_tampil($it['kelompok'] ?? '', $p['jenis_sampel'] ?? '');
                        $pemeriksaan = !empty($it['nama_pemeriksaan']) ? trim($it['nama_pemeriksaan']) : 'Pemeriksaan';
                        $it['no_registrasi']        = $full_reg;
                        $it['parent_lokasi']        = !empty($p['lokasi_pengambilan']) ? $p['lokasi_pengambilan'] : '-';
                        $grouped_items[$kel][$pemeriksaan][] = $it;
                    }
                }
            }
        }

        // Agregasi no. registrasi header atas (range ringkas)
        if (!empty($all_reg)) {
            $all_reg = array_unique($all_reg);
            $base_permintaan['no_registrasi'] = count($all_reg) > 1
                ? reset($all_reg) . ' - ' . end($all_reg)
                : reset($all_reg);
        }
        $base_permintaan['nama_sampel']        = !empty($n_sampel)    ? implode(', ', array_unique($n_sampel))    : '-';
        $base_permintaan['kategori_sampel']    = !empty($k_sampel)    ? implode(', ', array_unique($k_sampel))    : '-';
        $base_permintaan['jenis_sampel']       = !empty($j_sampel)    ? implode(', ', array_unique($j_sampel))    : '-';
        $base_permintaan['lokasi_pengambilan'] = !empty($l_sampel)    ? implode(', ', array_unique($l_sampel))    : '-';
        $base_permintaan['status_kelayakan']   = !empty($s_kelayakan) ? implode(', ', array_unique($s_kelayakan)) : '-';

        // Ringkasan gabungan semua petugas unik (untuk field header atas)
        $all_petugas_names = [];
        foreach ($petugas_per_sampel as $nm) {
            if ($nm && $nm !== '-') {
                foreach (explode(' , ', $nm) as $sn) {
                    $sn = trim($sn);
                    if ($sn && !in_array($sn, $all_petugas_names)) $all_petugas_names[] = $sn;
                }
            }
        }
        $jml_petugas = count($all_petugas_names);
        if ($jml_petugas > 2) {
            $petugas_terakhir = array_pop($all_petugas_names);
            $petugas_pengambil_nama = implode(', ', $all_petugas_names) . ', dan ' . $petugas_terakhir;
        } elseif ($jml_petugas === 2) {
            $petugas_pengambil_nama = $all_petugas_names[0] . ' dan ' . $all_petugas_names[1];
        } else {
            $petugas_pengambil_nama = $jml_petugas === 1 ? $all_petugas_names[0] : '-';
        }

        $data = [
            'title'                           => 'Laporan Hasil Uji (Gabungan)',
            'permintaan'                      => $base_permintaan,
            'grouped_items'                   => $grouped_items,
            'petugas_per_sampel'              => $petugas_per_sampel,   // per-sampel (profesional)
            'tgl_jam_pengambilan_display'     => $this->_get_min_max_dates($tgl_pengambilan_arr),
            'tgl_jam_pemeriksaan_display'     => $this->_get_min_max_dates($tgl_pemeriksaan_arr),
            'tgl_jam_selesai_display'         => $this->_get_min_max_dates($tgl_selesai_arr),
            'tgl_jam_lapor_display'           => $this->_get_min_max_dates($tgl_lapor_arr),
            'petugas_pengambil_spesimen_nama' => $petugas_pengambil_nama, // ringkasan di header atas
            'petugas_pemeriksa'               => $petugas_pemeriksa,
            'verifikator'                     => $verifikator,
            'penanggung_jawab_teknis'         => $penanggung_jawab,
            // Prioritas catatan kaki: (1) dari form, (2) tersimpan di DB, (3) default
            'keterangan_cetak' => $this->input->get('keterangan_cetak') !== null
                ? $this->input->get('keterangan_cetak')
                : (!empty($pelanggan['catatan_kaki'])
                    ? $pelanggan['catatan_kaki']
                    : self::DEFAULT_CATATAN_KAKI),
        ];

        $this->load->view('kesmas/laporan_pelanggan/print', $data);
    }

    /**
     * Simpan catatan kaki ke kolom catatan_kaki di tabel kesmas_pelanggan.
     * POST: keterangan_cetak
     */
    public function save_catatan_kaki($id_pelanggan)
    {
        $id_pelanggan = (int)$id_pelanggan;
        if ($id_pelanggan <= 0) show_404();

        $pelanggan = $this->Kesmas_model->get_pelanggan_by_id($id_pelanggan);
        if (!$pelanggan) show_404();

        $catatan = $this->input->post('keterangan_cetak', true);
        if ($catatan === null) $catatan = '';

        $this->Kesmas_model->update_catatan_kaki_pelanggan($id_pelanggan, $catatan);

        $this->session->set_flashdata('success', 'Catatan kaki berhasil disimpan.');

        // Kembalikan ke halaman detail dengan query string yang sama
        $qs  = '?dari='   . urlencode($this->input->post('dari', true) ?? '');
        $qs .= '&sampai=' . urlencode($this->input->post('sampai', true) ?? '');
        $qs .= '&jenis_sampel=' . urlencode($this->input->post('jenis_sampel', true) ?? '');
        redirect('k-panel/laporan_pelanggan/detail/' . $id_pelanggan . $qs);
    }

    private function _get_min_max_dates(array $dates): ?array
    {
        $dates = array_filter(array_unique($dates));
        if (empty($dates)) return null;
        $valid_dates = [];
        foreach ($dates as $d) {
            $ts = strtotime($d);
            if ($ts) $valid_dates[] = $ts;
        }
        if (empty($valid_dates)) return null;
        sort($valid_dates);
        return [
            'min' => date('Y-m-d H:i:s', $valid_dates[0]),
            'max' => date('Y-m-d H:i:s', end($valid_dates))
        ];
    }

    private function _extract_reg_num($full_reg)
    {
        if (preg_match('/^(\d{3,4})/', $full_reg, $m)) {
            return $m[1];
        }
        return trim(explode('/', $full_reg)[0]);
    }
}

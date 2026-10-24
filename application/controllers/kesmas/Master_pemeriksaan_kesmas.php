<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Master Pemeriksaan Kesmas Controller
 *
 * CRUD untuk tabel kesmas_master_pemeriksaan.
 * Hanya dapat diakses oleh petugas yang sudah login.
 */
class Master_pemeriksaan_kesmas extends MY_Controller
{
    /** Daftar kategori yang valid */
    private $kategori_list = [
        'air_minum'    => 'Air Minum',
        'air_bersih'   => 'Air Bersih / Air Sanitasi',
        'makanan'      => 'Makanan',
        'lingkungan'   => 'Lingkungan',
    ];

    /** Kelompok per kategori */
    private $kelompok_map = [
        'air_minum'    => ['Fisik Wajib', 'Kimia Wajib', 'Kimia Khusus', 'Mikrobiologi Wajib', 'Mikrobiologi Tambahan'],
        'air_bersih'   => ['Fisik', 'Kimia', 'Mikrobiologi'],
        'makanan'      => ['Kimia', 'Mikrobiologi', 'Parasitologi'],
        'lingkungan'   => ['Mikrobiologi'],
    ];

    public function __construct()
    {
        parent::__construct();
        $this->load->model('kesmas/Kesmas_model');
        // Hanya admin yang boleh mengelola data master
        $this->require_role(['admin']);
    }

    // ------------------------------------------------------------------
    // INDEX — Daftar semua parameter
    // ------------------------------------------------------------------
    public function index()
    {
        $filters = [
            'kategori'  => $this->input->get('kategori', true) ?: '',
            'q'         => $this->input->get('q', true) ?: '',
            'is_active' => $this->input->get('is_active', true),
        ];

        // Default: tampilkan semua termasuk nonaktif
        if ($filters['is_active'] === null || $filters['is_active'] === '') {
            $filters['is_active'] = '';
        }

        $rows = $this->Kesmas_model->get_all_master($filters);

        // Hitung stats
        $semua  = $this->Kesmas_model->get_all_master([]);
        $aktif  = array_filter($semua, fn($r) => $r['is_active'] != null);
        $nonaktif = array_filter($semua, fn($r) => $r['is_active'] == 0);

        // Group by kategori untuk tabs
        $grouped = [];
        foreach ($rows as $row) {
            $grouped[$row['kode']][] = $row;
        }

        $data = [
            'title'         => 'Master Parameter Pemeriksaan',
            'rows'          => $rows,
            'grouped'       => $grouped,
            'filters'       => $filters,
            'kategori_list' => $this->kategori_list,
            'kelompok_map'  => $this->kelompok_map,
            'stats'         => [
                'total'    => count($semua),
                'aktif'    => count($aktif),
                'nonaktif' => count($nonaktif),
            ],
        ];

        $this->render('kesmas/master/index', $data);
    }

    // ------------------------------------------------------------------
    // CREATE — Form tambah parameter baru
    // ------------------------------------------------------------------
    public function create()
    {
        $data = [
            'title'         => 'Tambah Parameter Pemeriksaan',
            'mode'          => 'create',
            'master'        => null,
            'kategori_list' => $this->kategori_list,
            'kelompok_map'  => $this->kelompok_map,
        ];
        $this->render('kesmas/master/form', $data);
    }

    // ------------------------------------------------------------------
    // STORE — Simpan parameter baru
    // ------------------------------------------------------------------
    public function store()
    {
        $p = $this->_collect_post();

        // Validasi duplikat nama pemeriksaan
        if ($this->Kesmas_model->check_duplicate_master($p['nama_pemeriksaan'], $p['kategori'], $p['kelompok'])) {
            $this->session->set_flashdata('error', 'Parameter "' . $p['nama_pemeriksaan'] . '" sudah ada di kelompok yang sama.');
            redirect('k-panel/master_pemeriksaan/create');
            return;
        }

        // Validasi duplikat urutan
        if (!empty($p['urutan']) && $this->Kesmas_model->check_duplicate_urutan($p['kategori'], $p['kelompok'], (int)$p['urutan'])) {
            $this->session->set_flashdata('error', 'Nomor urutan ' . $p['urutan'] . ' sudah ada di kelompok yang sama! Silakan pilih nomor urutan lain atau biarkan kosong untuk urutan otomatis.');
            redirect('k-panel/master_pemeriksaan/create');
            return;
        }

        if ($this->Kesmas_model->insert_master($p)) {
            log_activity('tambah', 'master_pemeriksaan', [
                'description' => "Menambahkan master pemeriksaan baru: " . $this->input->post('nama_pemeriksaan')
            ]);
            $this->session->set_flashdata('success', 'Parameter berhasil ditambahkan.');
        } else {
            $this->session->set_flashdata('error', 'Gagal menyimpan. Silakan coba lagi.');
        }
        redirect('k-panel/master_pemeriksaan');
    }

    // ------------------------------------------------------------------
    // EDIT — Form edit parameter
    // ------------------------------------------------------------------
    public function edit($id)
    {
        $master = $this->Kesmas_model->get_master_by_id((int)$id);
        if (!$master) show_404();

        $data = [
            'title'         => 'Edit Parameter Pemeriksaan',
            'mode'          => 'edit',
            'master'        => $master,
            'kategori_list' => $this->kategori_list,
            'kelompok_map'  => $this->kelompok_map,
        ];
        $this->render('kesmas/master/form', $data);
    }

    // ------------------------------------------------------------------
    // UPDATE — Proses update parameter
    // ------------------------------------------------------------------
    public function update($id)
    {
        $id = (int)$id;
        $master = $this->Kesmas_model->get_master_by_id($id);
        if (!$master) show_404();

        $p = $this->_collect_post();

        // Cek duplikat nama (kecuali diri sendiri)
        if ($this->Kesmas_model->check_duplicate_master($p['nama_pemeriksaan'], $p['kategori'], $p['kelompok'], $id)) {
            $this->session->set_flashdata('error', 'Parameter "' . $p['nama_pemeriksaan'] . '" sudah ada di kelompok yang sama.');
            redirect('k-panel/master_pemeriksaan/edit/' . $id);
            return;
        }

        // Cek duplikat urutan
        if (!empty($p['urutan']) && $this->Kesmas_model->check_duplicate_urutan($p['kategori'], $p['kelompok'], (int)$p['urutan'], $id)) {
            $this->session->set_flashdata('error', 'Nomor urutan ' . $p['urutan'] . ' sudah ada di kelompok yang sama! Silakan ganti ke nomor urutan yang belum terpakai.');
            redirect('k-panel/master_pemeriksaan/edit/' . $id);
            return;
        }

        if ($this->Kesmas_model->update_master($id, $p)) {
            log_activity('ubah', 'master_pemeriksaan', [
                'record_id' => $id,
                'description' => "Mengubah master pemeriksaan (ID: {$id})"
            ]);
            $this->session->set_flashdata('success', 'Parameter berhasil diperbarui.');
        } else {
            $this->session->set_flashdata('error', 'Gagal memperbarui. Silakan coba lagi.');
        }
        redirect('k-panel/master_pemeriksaan');
    }

    // ------------------------------------------------------------------
    // TOGGLE ACTIVE — Aktifkan / nonaktifkan
    // ------------------------------------------------------------------
    public function toggle_active($id)
    {
        // Hanya user modul Kesmas yang boleh mengubah data kesmas
        if (current_module() !== 'kesmas') {
            deny_cross_module();
        }

        $id = (int)$id;
        $master = $this->Kesmas_model->get_master_by_id($id);
        if (!$master) {
            if ($this->input->is_ajax_request()) {
                $this->output->set_content_type('application/json')
                    ->set_output(json_encode(['ok' => false, 'message' => 'Parameter tidak ditemukan.']));
                return;
            }
            show_404();
        }

        $ok = $this->Kesmas_model->toggle_master_active($id);
        $new_state = $master['is_active'] ? 0 : 1;

        if ($this->input->is_ajax_request()) {
            $this->output->set_content_type('application/json')
                ->set_output(json_encode([
                    'ok'       => $ok,
                    'is_active'=> $new_state,
                    'message'  => $ok ? ($new_state ? 'Parameter diaktifkan.' : 'Parameter dinonaktifkan.') : 'Gagal.',
                ]));
            return;
        }

        $this->session->set_flashdata($ok ? 'success' : 'error',
            $ok ? ($new_state ? 'Parameter diaktifkan.' : 'Parameter dinonaktifkan.') : 'Gagal mengubah status.');
        redirect('k-panel/master_pemeriksaan');
    }

    // ------------------------------------------------------------------
    // DELETE — Hapus parameter
    // ------------------------------------------------------------------
    public function delete($id)
    {
        // Hanya user modul Kesmas yang boleh mengubah data kesmas
        if (current_module() !== 'kesmas') {
            deny_cross_module();
        }

        $id = (int)$id;
        $master = $this->Kesmas_model->get_master_by_id($id);
        if (!$master) {
            show_404();
        }

        if ($this->Kesmas_model->is_master_in_use($id)) {
            $this->session->set_flashdata('error', 'Parameter ini tidak dapat dihapus karena sudah terhubung dengan data pendaftaran. Anda dapat menonaktifkannya jika tidak digunakan lagi.');
            redirect('k-panel/master_pemeriksaan');
            return;
        }

        if ($this->Kesmas_model->delete_master($id)) {
            log_activity('hapus', 'master_pemeriksaan', [
                'record_id' => $id,
                'description' => "Menghapus master pemeriksaan (ID: {$id})"
            ]);
            $this->session->set_flashdata('success', 'Parameter berhasil dihapus.');
        } else {
            $this->session->set_flashdata('error', 'Gagal menghapus parameter.');
        }
        redirect('k-panel/master_pemeriksaan');
    }

    // ------------------------------------------------------------------
    // DUPLICATE — Duplikat ke kategori/kelompok lain
    // ------------------------------------------------------------------
    public function duplicate($id)
    {
        $id = (int)$id;
        $master = $this->Kesmas_model->get_master_by_id($id);
        if (!$master) show_404();

        $kategori_target = $this->input->post('kategori_target', true);
        $kelompok_target = $this->input->post('kelompok_target', true);
        // Modal duplicate mem-post key kategori (mis. air_minum); terjemahkan ke label sebelum disimpan.
        $kategori_target = $this->kategori_list[$kategori_target] ?? $kategori_target;

        if (empty($kategori_target) || empty($kelompok_target)) {
            $this->session->set_flashdata('error', 'Kategori dan kelompok tujuan harus diisi.');
            redirect('k-panel/master_pemeriksaan');
            return;
        }

        $ok = $this->Kesmas_model->duplicate_master($id, $kategori_target, $kelompok_target);
        $this->session->set_flashdata($ok ? 'success' : 'error',
            $ok ? 'Parameter berhasil diduplikat.' : 'Gagal menduplikat parameter.');
        redirect('k-panel/master_pemeriksaan');
    }

    // ------------------------------------------------------------------
    // REORDER — AJAX drag & drop urutan
    // ------------------------------------------------------------------
    public function reorder()
    {
        if (!$this->input->is_ajax_request()) show_404();
        $raw    = $this->input->post('orders', true);
        $orders = is_array($raw) ? $raw : json_decode($this->input->raw_input_stream, true) ?? [];

        $ok = $this->Kesmas_model->reorder_master($orders);
        $this->output->set_content_type('application/json')
            ->set_output(json_encode(['ok' => $ok]));
    }

    // ------------------------------------------------------------------
    // CHECK_DUPLICATE — AJAX cek nama duplikat
    // ------------------------------------------------------------------
    public function check_duplicate()
    {
        if (!$this->input->is_ajax_request()) show_404();
        $nama      = $this->input->get('nama', true);
        $kategori  = $this->input->get('kategori', true);
        $kelompok  = $this->input->get('kelompok', true);
        // Form memilih kategori berdasarkan key; terjemahkan ke label agar cocok dengan kolom DB.
        $kategori  = $this->kategori_list[$kategori] ?? $kategori;
        $exclude   = (int)$this->input->get('exclude_id', true);

        $dup = $this->Kesmas_model->check_duplicate_master($nama, $kategori, $kelompok, $exclude ?: null);
        $this->output->set_content_type('application/json')
            ->set_output(json_encode(['duplicate' => $dup]));
    }

    // ------------------------------------------------------------------
    // PRIVATE: Kumpulkan & sanitasi data POST
    // ------------------------------------------------------------------
    private function _collect_post(): array
    {
        $baku_type     = $this->input->post('baku_type', true) ?: null;
        $baku_operator = null;
        $baku_min      = null;
        $baku_max      = null;
        $baku_text     = null;

        if ($baku_type === 'numeric_limit') {
            $baku_operator = $this->input->post('baku_operator', true) ?: null;
            $raw_min = $this->input->post('baku_min', true);
            $raw_max = $this->input->post('baku_max', true);
            $baku_min = ($raw_min !== '' && $raw_min !== null) ? (float)$raw_min : null;
            $baku_max = ($raw_max !== '' && $raw_max !== null) ? (float)$raw_max : null;
        } elseif ($baku_type === 'text') {
            $baku_text_raw = $this->input->post('baku_text', false);
            $baku_text = ($baku_text_raw !== null && $baku_text_raw !== '') ? trim($baku_text_raw) : null;
        } elseif ($baku_type === 'range') {
            $baku_operator = 'range';
            $raw_min = $this->input->post('baku_min', true);
            $raw_max = $this->input->post('baku_max', true);
            $baku_min = ($raw_min !== '' && $raw_min !== null) ? (float)$raw_min : null;
            $baku_max = ($raw_max !== '' && $raw_max !== null) ? (float)$raw_max : null;
        }

        // Auto-build baku_mutu teks dari field numerik
        // PENTING: Gunakan FALSE (tanpa XSS filter) agar karakter < > tersimpan dengan benar.
        $baku_mutu_display = $this->input->post('baku_mutu', false);
        $baku_mutu_display = ($baku_mutu_display !== null && $baku_mutu_display !== '')
            ? trim($baku_mutu_display)
            : null;

        $satuan_raw = $this->input->post('satuan', false);
        $satuan_val = ($satuan_raw !== null && $satuan_raw !== '') ? trim($satuan_raw) : null;

        $metode_raw = $this->input->post('metode', false);
        $metode_val = ($metode_raw !== null && $metode_raw !== '') ? trim($metode_raw) : null;

        $kategori_kode = $this->input->post('kategori', true);
        $kategori_label = $this->kategori_list[$kategori_kode] ?? $kategori_kode;

        return [
            'kategori'        => $kategori_label,
            'kelompok'        => $this->input->post('kelompok', true),
            'nama_pemeriksaan'=> $this->input->post('nama_pemeriksaan', true),
            'satuan'          => $satuan_val,
            'baku_mutu'       => $baku_mutu_display,
            'baku_type'       => $baku_type,
            'baku_operator'   => $baku_operator,
            'baku_min'        => $baku_min,
            'baku_max'        => $baku_max,
            'baku_text'       => $baku_text,
            'metode'          => $metode_val,
            'urutan'          => (int)($this->input->post('urutan', true) ?: 0),
        ];
    }
}

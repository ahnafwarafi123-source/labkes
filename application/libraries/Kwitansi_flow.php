<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Alur kwitansi yang dipakai bersama oleh controller Kesmas dan Klinik.
 *
 *   index  → daftar pendaftaran + status kwitansi
 *   form   → buat / ubah kwitansi (pemeriksaan tercentang otomatis dari pendaftaran)
 *   simpan → POST, total & terbilang dihitung server
 *   print  → cetak via browser (window.print)
 *   pdf    → unduh/lihat PDF (mPDF)
 *   tarif  → kelola daftar tarif modul
 *
 * Controller pemakai wajib mengimplementasikan _kw_cfg() dan _kw_render().
 * Gerbang tulis lintas-modul tetap ditangani base controller masing-masing.
 */
trait Kwitansi_flow
{
    abstract protected function _kw_cfg(): array;
    abstract protected function _kw_render(string $view, array $data): void;

    protected function _kw_boot(): void
    {
        $this->load->model('Kwitansi_model');
        $this->Kwitansi_model->ensure_schema();
    }

    public function index()
    {
        $cfg = $this->_kw_cfg();
        $q = trim((string)$this->input->get('q', TRUE));
        $page = max(1, (int)$this->input->get('page'));
        $limit = 20;

        $res = $this->Kwitansi_model->list_sumber($cfg['modul'], $q, $limit, ($page - 1) * $limit);

        $this->_kw_render('kwitansi/index', [
            'title' => $cfg['judul_menu'],
            'cfg' => $cfg,
            'rows' => $res['rows'],
            'total' => $res['total'],
            'page' => $page,
            'limit' => $limit,
            'q' => $q,
        ]);
    }

    public function form($ref_id = null)
    {
        $cfg = $this->_kw_cfg();
        $ref_id = (int)$ref_id;
        $sumber = $this->Kwitansi_model->get_sumber($cfg['modul'], $ref_id);
        if (!$sumber) show_404();

        $kwitansi = $this->Kwitansi_model->get_kwitansi($cfg['modul'], $ref_id);
        $saran = $this->Kwitansi_model->saran_tarif($cfg['modul'], $ref_id);
        $terpilih = $kwitansi ? array_map('intval', array_column($kwitansi['items'], 'id_tarif')) : $saran['tarif_ids'];

        $user = function_exists('current_user') ? current_user() : [];

        $this->_kw_render('kwitansi/form', [
            'title' => ($kwitansi ? 'Ubah ' : 'Buat ') . 'Kwitansi',
            'cfg' => $cfg,
            'sumber' => $sumber,
            'kwitansi' => $kwitansi,
            'saran' => $saran,
            'terpilih' => $terpilih,
            'tarif' => $this->Kwitansi_model->get_tarif_grouped($cfg['modul']),
            'default_penerima' => $kwitansi['nama_penerima'] ?? ($user['nama'] ?? ''),
        ]);
    }

    public function simpan($ref_id = null)
    {
        $cfg = $this->_kw_cfg();
        if ($this->input->method() !== 'post') redirect($cfg['base']);

        $ref_id = (int)$ref_id;
        $sumber = $this->Kwitansi_model->get_sumber($cfg['modul'], $ref_id);
        if (!$sumber) show_404();

        $user = function_exists('current_user') ? current_user() : [];
        $input = [
            'tarif' => $this->input->post('tarif'),
            'tanggal' => $this->input->post('tanggal', TRUE),
            'metode_pembayaran' => $this->input->post('metode_pembayaran', TRUE),
            'nama_pembayar' => $this->input->post('nama_pembayar', TRUE),
            'nama_penerima' => $this->input->post('nama_penerima', TRUE),
            'keterangan' => $this->input->post('keterangan', TRUE),
            'created_by' => $user['username'] ?? null,
        ];

        $hasil = $this->Kwitansi_model->save($cfg['modul'], $ref_id, $sumber, $input);
        if (is_string($hasil)) {
            $this->session->set_flashdata('error', $hasil);
            redirect($cfg['base'] . '/form/' . $ref_id);
        }

        $this->session->set_flashdata('success', 'Kwitansi ' . $hasil['no_kwitansi'] . ' berhasil disimpan.');
        redirect($cfg['base'] . '/form/' . $ref_id);
    }

    public function hapus($ref_id = null)
    {
        $cfg = $this->_kw_cfg();
        if ($this->input->method() !== 'post') redirect($cfg['base']);

        if ($this->Kwitansi_model->delete($cfg['modul'], (int)$ref_id)) {
            $this->session->set_flashdata('success', 'Kwitansi berhasil dihapus.');
        } else {
            $this->session->set_flashdata('error', 'Kwitansi tidak ditemukan.');
        }
        redirect($cfg['base']);
    }

    public function print($ref_id = null)
    {
        $this->load->view('kwitansi/print', $this->_kw_print_data((int)$ref_id, false));
    }

    public function pdf($ref_id = null)
    {
        $data = $this->_kw_print_data((int)$ref_id, true);
        $nama = 'Kwitansi_' . preg_replace('/[^A-Za-z0-9_-]+/', '_', $data['kwitansi']['no_kwitansi']) . '.pdf';

        $this->load->library('pdf');
        $this->pdf->generate_view('kwitansi/print', $data, $nama, 'I', false, [
            'margin_top' => 8,
            'margin_bottom' => 8,
            'margin_left' => 10,
            'margin_right' => 10,
        ]);
    }

    protected function _kw_print_data(int $ref_id, bool $is_pdf): array
    {
        $cfg = $this->_kw_cfg();
        $kwitansi = $this->Kwitansi_model->get_kwitansi($cfg['modul'], $ref_id);
        if (!$kwitansi) {
            $this->session->set_flashdata('error', 'Kwitansi belum dibuat. Simpan kwitansi terlebih dahulu.');
            redirect($cfg['base'] . '/form/' . $ref_id);
        }

        return [
            'cfg' => $cfg,
            'kwitansi' => $kwitansi,
            'tarif' => $this->Kwitansi_model->get_tarif_grouped($cfg['modul']),
            'ringkasan' => $this->Kwitansi_model->ringkasan_kelompok($cfg['modul'], $kwitansi),
            'is_pdf' => $is_pdf,
        ];
    }

    public function tarif()
    {
        $cfg = $this->_kw_cfg();
        $this->_kw_render('kwitansi/tarif', [
            'title' => 'Tarif Pemeriksaan',
            'cfg' => $cfg,
            'tarif' => $this->Kwitansi_model->get_tarif_grouped($cfg['modul'], false),
        ]);
    }

    public function tarif_simpan()
    {
        $cfg = $this->_kw_cfg();
        if ($this->input->method() !== 'post') redirect($cfg['base'] . '/tarif');

        $rows = (array)$this->input->post('tarif', TRUE);
        $n = 0;
        foreach ($rows as $id => $row) {
            $nama = trim((string)($row['nama_item'] ?? ''));
            if ($nama === '') continue;
            $this->Kwitansi_model->update_tarif((int)$id, $cfg['modul'], [
                'nama_item' => $nama,
                'keterangan' => trim((string)($row['keterangan'] ?? '')) ?: null,
                'harga' => max(0, (float)preg_replace('/[^0-9]/', '', (string)($row['harga'] ?? '0'))),
                'aktif' => !empty($row['aktif']) ? 1 : 0,
            ]);
            $n++;
        }
        $this->session->set_flashdata('success', $n . ' tarif diperbarui.');
        redirect($cfg['base'] . '/tarif');
    }

    public function tarif_tambah()
    {
        $cfg = $this->_kw_cfg();
        if ($this->input->method() !== 'post') redirect($cfg['base'] . '/tarif');

        $kelompok = trim((string)$this->input->post('kelompok', TRUE));
        $nama = trim((string)$this->input->post('nama_item', TRUE));
        if ($kelompok === '' || $nama === '') {
            $this->session->set_flashdata('error', 'Kelompok dan nama pemeriksaan wajib diisi.');
        } else {
            $this->Kwitansi_model->add_tarif($cfg['modul'], [
                'kelompok' => $kelompok,
                'nama_item' => $nama,
                'keterangan' => $this->input->post('keterangan', TRUE),
                'harga' => max(0, (float)preg_replace('/[^0-9]/', '', (string)$this->input->post('harga'))),
            ]);
            $this->session->set_flashdata('success', 'Tarif "' . $nama . '" ditambahkan.');
        }
        redirect($cfg['base'] . '/tarif');
    }

    public function tarif_sync()
    {
        $cfg = $this->_kw_cfg();
        if ($this->input->method() !== 'post' || $cfg['modul'] !== 'kesmas') redirect($cfg['base'] . '/tarif');

        $n = $this->Kwitansi_model->sync_tarif_kesmas();
        $this->session->set_flashdata('success', $n . ' pemeriksaan baru dari Master Pemeriksaan ditambahkan ke tarif.');
        redirect($cfg['base'] . '/tarif');
    }
}

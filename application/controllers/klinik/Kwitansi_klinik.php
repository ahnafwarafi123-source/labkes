<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . 'libraries/Kwitansi_flow.php';

/**
 * Kwitansi Klinik — URL: klinik/kwitansi_klinik/...
 * Sumber data: form_permintaan_klinik (pendaftaran klinik).
 */
class Kwitansi_klinik extends MY_KlinikController
{
    use Kwitansi_flow;

    public function __construct()
    {
        parent::__construct();
        $this->require_role(['admin', 'petugas pendaftaran', 'pemeriksa sampel']);
        $this->_kw_boot();
    }

    protected function _kw_cfg(): array
    {
        return [
            'modul' => 'klinik',
            'base' => 'klinik/kwitansi_klinik',
            'judul_menu' => 'Kwitansi Klinik',
            'label_kode' => 'No. Register',
            'label_kode_cetak' => 'Kode Sampel',
            'label_nama' => 'Pasien',
            'judul_daftar' => 'DAFTAR PEMERIKSAAN PELAYANAN LABORATORIUM KLINIK',
            'judul_dasar' => 'BERDASARKAN PERDA NOMOR 1 TAHUN 2024',
            'judul_bukti' => 'BUKTI PEMBAYARAN PEMERIKSAAN LABORATORIUM KLINIK',
            'untuk_pembayaran' => 'Untuk pembayaran Pemeriksaan Laboratorium Klinik :',
            'sync_master' => false,
        ];
    }

    protected function _kw_render(string $view, array $data): void
    {
        $this->load->view('klinik/layout/header', $data);
        $this->load->view('klinik/layout/sidebar');
        $this->load->view($view, $data);
        $this->load->view('klinik/layout/footer');
    }
}

<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . 'libraries/Kwitansi_flow.php';

/**
 * Kwitansi Rekam Medis — URL: klinik/kwitansi_rm/...
 * Sumber data: pasien (Pendaftaran Pasien RM). Memakai tarif klinik.
 */
class Kwitansi_rm extends MY_KlinikController
{
    use Kwitansi_flow;

    public function __construct()
    {
        parent::__construct();
        $this->require_role(['admin', 'petugas pendaftaran', 'petugas rm']);
        $this->_kw_boot();
    }

    protected function _kw_cfg(): array
    {
        return [
            'modul' => 'rm',
            'base' => 'klinik/kwitansi_rm',
            'judul_menu' => 'Kwitansi Rekam Medis',
            'label_kode' => 'No. RM',
            'label_kode_cetak' => 'No. Rekam Medis',
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

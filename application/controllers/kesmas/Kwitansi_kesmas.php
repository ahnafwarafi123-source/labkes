<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . 'libraries/Kwitansi_flow.php';

/**
 * Kwitansi Layanan Kesmas — URL: k-panel/kwitansi_kesmas/...
 */
class Kwitansi_kesmas extends MY_Controller
{
    use Kwitansi_flow;

    public function __construct()
    {
        parent::__construct();
        $this->require_role(['admin', 'petugas']);
        $this->_kw_boot();
    }

    protected function _kw_cfg(): array
    {
        return [
            'modul' => 'kesmas',
            'base' => 'k-panel/kwitansi_kesmas',
            'judul_menu' => 'Kwitansi Layanan Kesmas',
            'label_kode' => 'No. Registrasi',
            'label_kode_cetak' => 'Kode Sampel',
            'label_nama' => 'Pelanggan',
            'judul_daftar' => 'DAFTAR PEMERIKSAAN PELAYANAN LABORATORIUM KESEHATAN MASYARAKAT',
            'judul_dasar' => 'BERDASARKAN PERDA NOMOR 1 TAHUN 2024',
            'judul_bukti' => 'BUKTI PEMBAYARAN PEMERIKSAAN LABORATORIUM KESEHATAN MASYARAKAT',
            'untuk_pembayaran' => 'Untuk pembayaran Pemeriksaan Laboratorium Kesehatan Masyarakat :',
            'sync_master' => true,
        ];
    }

    protected function _kw_render(string $view, array $data): void
    {
        $this->render($view, $data);
    }
}

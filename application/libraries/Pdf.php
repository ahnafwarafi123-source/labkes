<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once FCPATH . 'vendor/autoload.php';
use Mpdf\Mpdf;

class Pdf {

    public function generate_view($view, $data = [], $filename = 'document.pdf', $dest = 'I', $autoprint = false, $config = []) {
        $CI = &get_instance();
        $html = $CI->load->view($view, $data, TRUE);

        $mpdf = new Mpdf(array_merge([
            'format' => [215, 330],
            'orientation' => 'P',
            'margin_top' => 15,
            'margin_bottom' => 15,
            'margin_left' => 15,
            'margin_right' => 15
        ], $config));

        if ($autoprint) {
            $mpdf->SetJS('this.print();');
        }

        $mpdf->WriteHTML($html);
        $mpdf->Output($filename, $dest); // 'I' = tampil di browser
    }
}

<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Survei Controller
 *
 * Mengelola data survei kepuasan pelanggan.
 * Index, detail, edit, laporan → hanya untuk admin/petugas (login wajib).
 * Form publik dan store dihandle oleh Public_survey.php (tanpa login).
 *
 * @property Survei_kepuasan_model $Survei_kepuasan_model
 */
class Survei extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('kesmas/Survei_kepuasan_model');
        // Semua method di controller ini hanya untuk petugas/admin
        $this->require_role(['admin', 'petugas']);
    }

    /**
     * Halaman list survei (Admin/Petugas)
     */
    public function index()
    {
        $data = [
            'title'  => 'Survei Kepuasan Pelanggan',
            'survei' => $this->Survei_kepuasan_model->get_all(),
            'stats'  => $this->Survei_kepuasan_model->get_rating_stats(),
        ];
        $this->render('kesmas/survei/index', $data);
    }

    /**
     * Detail survei (Admin/Petugas)
     */
    public function detail($id)
    {
        $survei = $this->Survei_kepuasan_model->get_by_id($id);
        if (!$survei) show_404();

        $data = [
            'title'  => 'Detail Kuesioner SKM',
            'survei' => $survei,
        ];
        $this->render('kesmas/survei/detail', $data);
    }

    /**
     * Halaman edit survei (Admin/Petugas)
     */
    public function edit($id)
    {
        $item = $this->Survei_kepuasan_model->get_by_id($id);
        if (!$item) show_404();

        $data = [
            'title' => 'Edit Survei',
            'item'  => $item,
        ];
        $this->render('kesmas/survei/form_edit', $data);
    }

    /**
     * Proses update survei (Admin/Petugas)
     */
    public function update($id)
    {
        $post = $this->input->post();

        $validation_rules = [
            ['field' => 'usia',          'label' => 'Usia',                       'rules' => 'required|numeric'],
            ['field' => 'jenis_kelamin', 'label' => 'Jenis Kelamin',              'rules' => 'required'],
            ['field' => 'pendidikan',    'label' => 'Pendidikan',                  'rules' => 'required'],
            ['field' => 'pekerjaan',     'label' => 'Pekerjaan',                   'rules' => 'required'],
            ['field' => 'jenis_layanan', 'label' => 'Jenis Layanan yang diterima', 'rules' => 'required'],
        ];

        for ($i = 1; $i <= 9; $i++) {
            $validation_rules[] = [
                'field' => 'q' . $i,
                'label' => 'Unsur Pelayanan ' . $i,
                'rules' => 'required|numeric|greater_than_equal_to[1]|less_than_equal_to[4]',
            ];
        }

        $this->form_validation->set_rules($validation_rules);

        if (!$this->form_validation->run()) {
            $this->session->set_flashdata('error', validation_errors_text());
            redirect('k-panel/survei/edit/' . $id);
            return;
        }

        if ($this->Survei_kepuasan_model->update($id, $post)) {
            $this->session->set_flashdata('success', 'Survei berhasil diupdate.');
        } else {
            $this->session->set_flashdata('error', 'Gagal mengupdate survei.');
        }

        redirect('k-panel/survei');
    }

    /**
     * Hapus survei (Admin/Petugas)
     */
    public function delete($id)
    {
        // Hanya user modul Kesmas yang boleh mengubah data kesmas
        if (current_module() !== 'kesmas') {
            deny_cross_module();
        }

        if ($this->Survei_kepuasan_model->delete($id)) {
            $this->session->set_flashdata('success', 'Survei berhasil dihapus.');
        } else {
            $this->session->set_flashdata('error', 'Gagal menghapus survei.');
        }

        redirect($_SERVER['HTTP_REFERER'] ?? 'kesmas/survei');
    }

    /**
     * Laporan survei dengan detail (Admin/Petugas)
     */
    public function laporan()
    {
        $data = [
            'title'         => 'Laporan Survei Kepuasan',
            'stats'         => $this->Survei_kepuasan_model->get_rating_stats(),
            'low_ratings'   => $this->Survei_kepuasan_model->get_low_ratings(),
            'with_comments' => $this->Survei_kepuasan_model->get_with_comments(),
            'semua_survei'  => $this->Survei_kepuasan_model->get_all(),
        ];
        $this->render('kesmas/survei/laporan', $data);
    }

    /**
     * Laporan berdasarkan rentang tanggal (Admin/Petugas)
     */
    public function laporan_by_date()
    {
        $start_date = $this->input->get('start_date');
        $end_date   = $this->input->get('end_date');

        $data = [
            'title'      => 'Laporan Survei by Tanggal',
            'survei'     => ($start_date && $end_date)
                                ? $this->Survei_kepuasan_model->get_by_date_range($start_date, $end_date)
                                : [],
            'start_date' => $start_date,
            'end_date'   => $end_date,
        ];
        $this->render('kesmas/survei/laporan_date', $data);
    }
}

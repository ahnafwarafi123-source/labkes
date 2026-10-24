<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Auth Controller (KLINIK) — DEPRECATED
 *
 * Login klinik sudah dipindah ke controller terpadu `Auth`
 * (routes: /login). Controller ini hanya disimpan untuk kompatibilitas
 * URL lama dan tidak digunakan lagi secara normal.
 */
class Auth extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Klinik/User_model');
    }

    public function index() {
        redirect('login');
    }

    public function login() {
        redirect('login');
    }

    public function logout() {
        redirect('login');
    }
}
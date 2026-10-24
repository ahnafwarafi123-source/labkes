<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends MY_Controller
{
    public function index()
    {
        // Dashboard terpadu: seluruh konten dipusatkan di halaman /dashboard.
        redirect('dashboard');
    }
}

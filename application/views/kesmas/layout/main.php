<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php $this->load->view('klinik/layout/header'); ?>
<?php $this->load->view('klinik/layout/sidebar'); ?>
<?php if (!empty($content)) {
    $this->load->view($content);
} ?>
<?php $this->load->view('klinik/layout/footer'); ?>
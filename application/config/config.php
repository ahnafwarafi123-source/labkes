<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
|-------------------------------------------------------------------
| Base Site URL
|-------------------------------------------------------------------
| Dioptimalkan agar mendukung HTTPS Cloudflare Tunnel & HTTP Laragon Lokal
*/
if (!isset($_SERVER['HTTP_HOST'])) {
    $_SERVER['HTTP_HOST'] = 'localhost';
}
if (!isset($_SERVER['SCRIPT_NAME'])) {
    $_SERVER['SCRIPT_NAME'] = 'index.php';
}
$protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') ? 'https' : 'http';
$config['base_url'] = $protocol . "://" . $_SERVER['HTTP_HOST'] . str_replace(basename($_SERVER['SCRIPT_NAME']), "", $_SERVER['SCRIPT_NAME']);

$config['index_page'] = '';
$config['uri_protocol'] = 'REQUEST_URI';

$config['encryption_key'] = '2c6ee89f038f3a38f9e1b86c96fa85f0153ec9d042a5875425d71bcb565839b0';

/*
|-------------------------------------------------------------------
| Session Variables
|-------------------------------------------------------------------
*/
$config['sess_driver'] = 'files';
$config['sess_save_path'] = APPPATH . 'cache';
$config['sess_cookie_name'] = 'kesmasnew_sess';
$config['sess_expiration'] = 7200;
$config['sess_time_to_update'] = 7200;
$config['sess_regenerate_destroy'] = FALSE;

/*
|-------------------------------------------------------------------
| Cookie Related Variables
|-------------------------------------------------------------------
*/
$config['cookie_prefix']    = '';
$config['cookie_domain']    = '';
$config['cookie_path']      = '/'; 

// OTOMATIS SECURE JIKA DIAKSES VIA HTTPS (CLOUDFLARE)
$config['cookie_secure']    = ($protocol === 'https') ? TRUE : FALSE;
$config['cookie_httponly']  = TRUE;

/*
|-------------------------------------------------------------------
| CSRF Protection
|-------------------------------------------------------------------
*/
$config['csrf_protection'] = TRUE;
$config['csrf_cookie_name'] = 'kesmasnew_csrf';
$config['csrf_token_name']  = 'kesmasnew_token';
$config['csrf_regenerate'] = FALSE; 

$config['csrf_exclude_uris'] = array(
    'auth/do_login',
    'index.php/auth/do_login',
    'kesmas/public_survey/store',
    'index.php/kesmas/public_survey/store',
    'kesmas/survei/store',
    'index.php/kesmas/survei/store',
);

/*
|-------------------------------------------------------------------
| Global XSS Filtering
|-------------------------------------------------------------------
*/
$config['global_xss_filtering'] = TRUE;

$config['enable_query_strings'] = FALSE;
$config['compress_output'] = FALSE;

date_default_timezone_set('Asia/Jakarta');

/*
|-------------------------------------------------------------------
| Error Logging Threshold
|-------------------------------------------------------------------
*/
$config['log_threshold'] = 1;
$config['log_path'] = APPPATH . 'logs/';

$config['charset'] = 'UTF-8';
$config['subclass_prefix'] = 'MY_';
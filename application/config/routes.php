<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
|-----------------------------------------------------------------------
| 1. DEFAULT ROUTING
|-----------------------------------------------------------------------
| default_controller diarahkah ke 'welcome' yang kemudian akan redirect
| ke halaman 'kesmas/public_pendaftaran'.
*/
$route['default_controller']   = 'welcome';
$route['404_override']         = '';
$route['translate_uri_dashes'] = FALSE;

/*
|-----------------------------------------------------------------------
| 2. AUTH TERPADU (Kesmas + Klinik)
|-----------------------------------------------------------------------
| Satu halaman login untuk semua modul. URL lama tetap di-forward
| agar bookmark/halaman lama tidak rusak.
*/
$route['login']                        = 'auth/index';
$route['login/do']                     = 'auth/do_login';
$route['logout']                       = 'auth/logout';
$route['dashboard']                    = 'home/index';

// Forward URL login lama -> auth terpadu
$route['kesmas/petugaslogin']          = 'auth/index';
$route['kesmas/petugaslogin/login']    = 'auth/do_login';
$route['kesmas/petugaslogin/logout']   = 'auth/logout';
$route['kesmas/auth/index']            = 'auth/index';
$route['kesmas/auth/do_login']         = 'auth/do_login';
$route['kesmas/auth/logout']           = 'auth/logout';
$route['klinik/petugas_klinik']        = 'auth/index';
$route['klinik/petugas_klinik/login']  = 'auth/do_login';
$route['klinik/petugas_klinik/logout'] = 'auth/logout';

/*
|-----------------------------------------------------------------------
| 3. MODUL KLINIK
|-----------------------------------------------------------------------
| Routing khusus untuk modul klinik. Tidak boleh bentrok dengan kesmas.
*/
$route['klinik/dashboard']             = 'klinik/dashboard/index';

// Dashboard Kesmas
$route['k-panel/dashboard']            = 'kesmas/dashboard/index';

// Pendaftaran Publik
$route['kesmas']                                = 'kesmas/public_pendaftaran/index';
$route['kesmas/pendaftaran_publik']             = 'kesmas/public_pendaftaran/index';
$route['kesmas/pendaftaran_publik/form']        = 'kesmas/public_pendaftaran/form';
$route['kesmas/pendaftaran_publik/store']       = 'kesmas/public_pendaftaran/store';
$route['kesmas/pendaftaran_publik/success/(:any)']     = 'kesmas/public_pendaftaran/success/$1';
$route['kesmas/pendaftaran_publik/print_bukti/(:any)'] = 'kesmas/public_pendaftaran/print_bukti/$1';

// Pendaftaran Internal & Kaji Ulang
$route['k-panel/pendaftaran']                    = 'kesmas/form_permintaan_kesmas/index';
$route['k-panel/pendaftaran/create']             = 'kesmas/form_permintaan_kesmas/create';
$route['k-panel/pendaftaran/edit/(:num)']        = 'kesmas/form_permintaan_kesmas/edit/$1';
$route['k-panel/pendaftaran/detail/(:num)']      = 'kesmas/form_permintaan_kesmas/detail/$1';
$route['k-panel/pendaftaran/print/(:num)']       = 'kesmas/form_permintaan_kesmas/print/$1';
$route['k-panel/pendaftaran/delete/(:num)']      = 'kesmas/form_permintaan_kesmas/delete/$1';
$route['k-panel/pendaftaran/selesai/(:num)']     = 'kesmas/form_permintaan_kesmas/selesai/$1';

$route['k-panel/kaji_ulang']                     = 'kesmas/form_permintaan_kesmas/kaji_ulang';
$route['k-panel/kaji_ulang/(:num)']              = 'kesmas/form_permintaan_kesmas/kaji_ulang/$1';
$route['k-panel/kaji_ulang_detail/(:num)']       = 'kesmas/form_permintaan_kesmas/kaji_ulang_detail/$1';
$route['k-panel/kaji_ulang_edit/(:num)']         = 'kesmas/form_permintaan_kesmas/kaji_ulang_detail/$1';
$route['k-panel/kaji_ulang_update/(:num)']       = 'kesmas/form_permintaan_kesmas/kaji_ulang_update/$1';

// Terima / Tolak Sample
$route['k-panel/permintaan_sample']                   = 'kesmas/permintaan_sample/index';
$route['k-panel/permintaan_sample/verifikasi/(:num)'] = 'kesmas/permintaan_sample/verifikasi/$1';
$route['k-panel/permintaan_sample/tolak/(:num)']      = 'kesmas/permintaan_sample/tolak/$1';

// Input Hasil Uji
$route['k-panel/uji']                            = 'kesmas/form_permintaan_kesmas/uji_index';
$route['k-panel/uji/input/(:num)']               = 'kesmas/form_permintaan_kesmas/input_hasil/$1';

// Laporan Uji
$route['k-panel/laporan']                        = 'kesmas/laporan_uji_kesmas/index';
$route['k-panel/laporan/detail/(:num)']          = 'kesmas/laporan_uji_kesmas/detail/$1';
$route['k-panel/laporan/print/(:num)']           = 'kesmas/Laporan_uji_kesmas/print/$1';
$route['k-panel/laporan/set_status']             = 'kesmas/laporan_uji_kesmas/set_status';
$route['k-panel/laporan/parameter']                         = 'kesmas/laporan_uji_kesmas/parameter';
$route['k-panel/laporan/parameter/bulanan/(:num)/(:num)']   = 'kesmas/laporan_uji_kesmas/parameter_bulanan/$1/$2';
$route['k-panel/laporan/parameter/bulanan']                 = 'kesmas/laporan_uji_kesmas/parameter_bulanan';
$route['k-panel/laporan/parameter/tahunan/(:num)']          = 'kesmas/laporan_uji_kesmas/parameter_tahunan/$1';
$route['k-panel/laporan/parameter/tahunan']                 = 'kesmas/laporan_uji_kesmas/parameter_tahunan';
$route['k-panel/laporan/parameter/export_excel']            = 'kesmas/laporan_uji_kesmas/export_excel_parameter_tahunan';
$route['k-panel/laporan/parameter_samples']                 = 'kesmas/laporan_uji_kesmas/parameter_samples';

// Survei Kepuasan
$route['kesmas/survei/form']                    = 'kesmas/public_survey/form';
$route['kesmas/survei/store']                   = 'kesmas/public_survey/store';
$route['kesmas/survei/thankyou']                = 'kesmas/public_survey/thankyou';
$route['k-panel/survei']                         = 'kesmas/survei/index';
$route['k-panel/survei/detail/(:num)']           = 'kesmas/survei/detail/$1';
$route['k-panel/survei/edit/(:num)']             = 'kesmas/survei/edit/$1';
$route['k-panel/survei/update/(:num)']           = 'kesmas/survei/update/$1';
$route['k-panel/survei/delete/(:num)']           = 'kesmas/survei/delete/$1';
$route['k-panel/survei/laporan']                 = 'kesmas/survei/laporan';
$route['k-panel/survei/laporan_by_date']         = 'kesmas/survei/laporan_by_date';

// Master Pemeriksaan Kesmas
$route['k-panel/master_pemeriksaan']                  = 'kesmas/master_pemeriksaan_kesmas/index';
$route['k-panel/master_pemeriksaan/create']           = 'kesmas/master_pemeriksaan_kesmas/create';
$route['k-panel/master_pemeriksaan/store']            = 'kesmas/master_pemeriksaan_kesmas/store';
$route['k-panel/master_pemeriksaan/edit/(:num)']      = 'kesmas/master_pemeriksaan_kesmas/edit/$1';
$route['k-panel/master_pemeriksaan/update/(:num)']    = 'kesmas/master_pemeriksaan_kesmas/update/$1';
$route['k-panel/master_pemeriksaan/delete/(:num)']    = 'kesmas/master_pemeriksaan_kesmas/delete/$1';
$route['k-panel/master_pemeriksaan/toggle/(:num)']    = 'kesmas/master_pemeriksaan_kesmas/toggle_active/$1';
$route['k-panel/master_pemeriksaan/duplicate/(:num)'] = 'kesmas/master_pemeriksaan_kesmas/duplicate/$1';
$route['k-panel/master_pemeriksaan/reorder']          = 'kesmas/master_pemeriksaan_kesmas/reorder';
$route['k-panel/master_pemeriksaan/check_duplicate']  = 'kesmas/master_pemeriksaan_kesmas/check_duplicate';

// Master Data Pegawai / Petugas (Kesmas)
$route['k-panel/petugas']                        = 'kesmas/petugas/index';
$route['k-panel/petugas/(:any)']                 = 'kesmas/petugas/$1';
$route['k-panel/verifikator']                    = 'kesmas/verifikator/index';
$route['k-panel/verifikator/(:any)']             = 'kesmas/verifikator/$1';
$route['k-panel/penanggung_jawab_teknis']        = 'kesmas/penanggung_jawab_teknis/index';
$route['k-panel/penanggung_jawab_teknis/(:any)'] = 'kesmas/penanggung_jawab_teknis/$1';
$route['k-panel/tindakan_sampel']                = 'kesmas/tindakan_sampel/index';
$route['k-panel/tindakan_sampel/add']            = 'kesmas/tindakan_sampel/add';
$route['k-panel/tindakan_sampel/edit/(:num)']    = 'kesmas/tindakan_sampel/edit/$1';
$route['k-panel/tindakan_sampel/delete/(:num)']  = 'kesmas/tindakan_sampel/delete/$1';

// Security Dashboard (Kesmas)
$route['k-panel/security']                       = 'kesmas/security/index';
$route['k-panel/security/login_logs']            = 'kesmas/security/login_logs';
$route['k-panel/security/activity_logs']         = 'kesmas/security/activity_logs';
$route['k-panel/security/activity_detail/(:num)']= 'kesmas/security/activity_detail/$1';
$route['k-panel/security/pending_data']          = 'kesmas/security/pending_data';
$route['k-panel/security/approve_pending/(:num)']= 'kesmas/security/approve_pending/$1';
$route['k-panel/security/reject_pending/(:num)'] = 'kesmas/security/reject_pending/$1';
$route['k-panel/security/request_revision/(:num)']= 'kesmas/security/request_revision/$1';
$route['k-panel/security/cleanup/(:any)/(:num)'] = 'kesmas/security/cleanup/$1/$2';
$route['k-panel/security/cleanup/(:any)']        = 'kesmas/security/cleanup/$1';
$route['k-panel/security/export_logs/(:any)']    = 'kesmas/security/export_logs/$1';

// Catch-all for any other k-panel routes not explicitly defined above
$route['k-panel/(.+)']                           = 'kesmas/$1';

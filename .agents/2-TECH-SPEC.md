# TECH SPEC: Lab Management System (Kesmas + Klinik)

## 1. System Purpose
CodeIgniter 3-based lab management system for UPTD Laboratorium Kesehatan handling:
- Kesmas (public health): sample registration/receipt, testing workflow, verification/validation, reporting (LHU), survey, master data
- Klinik (clinic): form permintaan klinik/RM, uji, laporan hasil, master/user

## 2. Architecture

### Overview
- MVC (CodeIgniter 3): Controllers in application/controllers/{kesmas,klinik}, Models in application/models/{kesmas,Klinik}, Views in application/views/{kesmas,klinik}
- Base controllers: MY_Controller (internal/protected, Kesmas admin/internal guarded), MY_KlinikController (Klinik guarded). Both enforce login + cross-module write gate.
- Auth unified: single login checks both tables (users klinik, user_kesmas kesmas).
- Routing: k-panel/* ? routed to kesmas/* (catch-all). Direct /kesmas/* blocked for MY_Controller subclasses; only non-MY_Controller controllers reachable at /kesmas/* (public). Public endpoints exposed at /kesmas/....

### Routing Boundary (Explicit)
- Hard-block: MY_Controller::__construct() does if (->uri->segment(1) === 'kesmas') { show_404(); exit; } (application/core/MY_Controller.php:36-39). 
- Public reachable: controllers not extending MY_Controller (kesmas/Public_pendaftaran.php, kesmas/Public_survey.php; Auth.php extends CI_Controller).
- Catch-all: $route['k-panel/(.+)'] = 'kesmas/' (application/config/routes.php:139).

### Base Controllers Detail
- MY_Controller (application/core/MY_Controller.php:28-91): blocks segment(1)==='kesmas'; requires login (is_logged_in()); write-gate blocks writes to kesmas from non-kesmas module (
equest_is_write() + current_module() !== 'kesmas' ? deny_cross_module()). 
equire_role(array ) reads session key 
ole (application/core/MY_Controller.php:71). 
ender(string , array =[]) loads klinik/layout/header, klinik/layout/sidebar, $view, klinik/layout/footer (application/core/MY_Controller.php:82-90) � applies to both modules for controllers using it; views/kesmas/layout/* not used by this wrapper.
- MY_KlinikController (application/core/MY_Controller.php:94-133): requires login via is_logged_in() (no separate klinik flag check) (application/core/MY_Controller.php:109); write-gate blocks writes to klinik from non-klinik (application/core/MY_Controller.php:114-117). 
equire_role() reads klinik_role (lowercased) (application/core/MY_Controller.php:126).

### View Loading Strategy
- Controllers extending MY_Controller: typically use 
ender() (unified klinik layout for internal pages; includes Kesmas pages accessed via k-panel). 
- Klinik controllers: often load views directly (separate header/sidebar/footer loads) rather than always using 
ender() (e.g. Form_permintaan_klinik::index() loads views directly). This is by design in this app.
- Unified internal layout is klinik layout (views/klinik/layout/*); kesmas layout folder is legacy/unused by MY_Controller::render() wrapper.

### Sessions & Security Basics
- Session driver files, save path APPPATH/cache (application/cache/ contains live sessions). 
- CSRF ON (kesmasnew_token), exclusions include public endpoints + index.php/* variants (application/config/config.php:54-66).
- DB-driven session validation via active_sessions (IP, UA, expiry, is_valid).

## 3. Tech Stack
- PHP (CI3), MySQL/MariaDB
- Bootstrap 5, jQuery, AJAX
- mPDF via application/libraries/Pdf.php
- Session driver files

## 4. Database (Key Tables � field-level + relasi deskriptif)

### Core Session/Security
- active_sessions (database/kesmas_new.sql:36-49, database/security_sessions.sql:3-16): fields id, session_id, user_id, username, ip_address, user_agent, created_at, expires_at, is_valid. Validated by validate_session_record(); fallback if table missing ? allow (application/helpers/auth_helper.php:79-82,109-112).
- Logs: audit_log, audit_log_detail, failed_login_attempts, login_logs, user_lockouts (Kesmas); Klinik_login_logs_model (Klinik) � separate.

### Kesmas Core (field-level kunci)
- kesmas_pelanggan, kesmas_permintaan, kesmas_item_permintaan: 1:1 permintaan�pelanggan, 1:N permintaan�items. Key fields derived from Kesmas_model::_build_permintaan_query() (application/models/kesmas/Kesmas_model.php:22-90) and save_permintaan() (application/models/kesmas/Kesmas_model.php:145-220).
- kesmas_penerimaan_sampel, kesmas_pembayaran: related via 
o_registrasi (non-trivial join). Penerimaan 1:1 with permintaan conceptually; pembayaran linked by 
o_registrasi.
- kesmas_pengujian_sampel, kesmas_hasil_pengujian: 1:N pengujian_sampel�hasil_pengujian (application/models/kesmas/Kesmas_model.php:450-560).
- Master/user: kesmas_master_pemeriksaan, user_kesmas, verifikator, penanggung_jawab_teknis, 	indakan_sampel.

### Klinik Core
- users (klinik users), clinic forms/tables under Klinik domain (Form_permintaan_klinik, Form_permintaan_rm, kunjungan_rm, pasien, uji_klinik, laporan_hasil_pemeriksaan, etc.), models under models/Klinik/.

## 5. Core Flows (End-to-End)

### Kesmas
- **Registration**: Form_permintaan_kesmas::create/store (application/controllers/kesmas/Form_permintaan_kesmas.php:80-170). Kesmas_model::save_permintaan() (application/models/kesmas/Kesmas_model.php:145-220) persists pelanggan, permintaan, items (kesmas_item_permintaan); may create penerimaan_sampel + pembayaran in terima path. After create ? redirect to kaji_ulang/ (application/controllers/kesmas/Form_permintaan_kesmas.php:131).
- **No Registrasi**: Kesmas_model::gen_no_registrasi() (application/models/kesmas/Kesmas_model.php:6-20) builds KESMAS.Ymd. + count in kesmas_penerimaan_sampel for same date (business rule).
- **Kaji Ulang/Receipt**: kaji_ulang()/kaji_ulang_update() (application/controllers/kesmas/Form_permintaan_kesmas.php:502-620). Handles kelayakan/alasan, petugas, dates; penerimaan_sampel status and pembayaran updates via model.
- **Input Hasil**: uji_index/input_hasil/simpan_hasil (application/controllers/kesmas/Form_permintaan_kesmas.php:348-470). Kesmas_model::simpan_hasil_pengujian() upserts kesmas_hasil_pengujian per item (hasil, status, baku_mutu/metode override), updates kesmas_pengujian_sampel timestamps/fields (application/models/kesmas/Kesmas_model.php:450-560).
- **Reporting (LHU)**: Laporan_uji_kesmas::_build_laporan_data/detail/print/save_keterangan/set_status (application/controllers/kesmas/Laporan_uji_kesmas.php:1-210). Print can persist selected petugas/verifikator/PJT and catatan_kaki to kesmas_pengujian_sampel (application/controllers/kesmas/Laporan_uji_kesmas.php:150-185).
- **State notes**: status kelayakan (Layak/Tidak Layak), alasan (JSON array in places), status penerimaan, tgl_pelaporan, catatan_kaki.

### Klinik
- Extends MY_KlinikController, role check uses klinik_role (application/core/MY_Controller.php:124-132). Key controllers: Form_permintaan_klinik, Form_permintaan_rm, Uji_rekam_medis, Uji_klinik, Laporan_hasil_pemeriksaan, master/user management. 
- View loading: often direct (not via 
ender()); e.g. Form_permintaan_klinik::index() loads views directly. 
- Unified dashboard: Home.php sets klinik_block by klinik role (petugas pendaftaran?pendaftaran, pemeriksa sampel?klinik, petugas rm?dokter, else default); passes view_data to combined view (application/controllers/Home.php:60-140, application/views/home/dashboard.php:1-80).

## 6. Interfaces
- Views under kesmas/ and klinik/. MY_Controller::render() uses klinik layout for internal pages (both modules). Klinik may load directly.
- AJAX via jQuery; controllers detect $this->input->is_ajax_request(). 
- Error/denial: deny_cross_module() returns JSON 403 for AJAX, else sets flash and redirects to dashboard (application/helpers/auth_helper.php:127-148).
- Indonesian UI labels.

## 7. Security
- Login unified dual-table (users, user_kesmas), password_verify, sess_regenerate(false) on login (application/controllers/Auth.php:176,224). 
- Session validated against active_sessions (IP, UA, expiry, is_valid) via validate_session_record() (application/helpers/auth_helper.php:72-115). **Fallback**: if active_sessions table missing/unavailable ? returns 	rue (application/helpers/auth_helper.php:79-82,109-112) � operational nuance.
- Cross-module write gate enforced in **both** base classes (application/core/MY_Controller.php:47-50,114-117); reads across modules allowed. 
- deny_cross_module() behavior: AJAX ? JSON 403, non-AJAX ? flash+redirect to dashboard.
- CSRF protection ON (kesmasnew_token), exclude list includes index.php/* variants (application/config/config.php:54-66).
- Auth logging: login attempts, lockouts; logs split Kesmas vs Klinik.
- xss_clean, form_validation; security helpers/audit helpers loaded.

## 8. Constraints/Notes
- No root composer.json; committed vendor/ is required (mPDF requires FCPATH.'vendor/autoload.php').
- CodeIgniter 3 style. Case-sensitive paths on *nix: models/Klinik/ (capital K) vs views/klinik/ (lowercase).
- application/cache/ stores live session files � deleting it logs out all users.
- Env assumptions: DB_HOST/DB_USER/DB_PASS/DB_NAME/CI_DB_GROUP (application/config/database.php), protocol detection HTTPS/Cloudflare (application/config/config.php:10-18), timezone Asia/Jakarta (application/config/config.php:78).
- Standalone scripts are separate scope from CI app: cetak_laporan.php (PDO, default kesmas_new, override ?db=, isMemenuhiSyarat() MS/TMS) and 	ools/*.php (mysqli CLI, hardcoded credentials) � documented separately with operational/security notes (no changes made).
- Backup/stale: arsitektur.txt stale; application/alur logika lama.txt historical/ignored; application/models/kesmas/Kesmas_model_backup.php historical/ignored. Code is source of truth.

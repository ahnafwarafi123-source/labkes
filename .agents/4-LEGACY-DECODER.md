# LEGACY DECODER ANALYSIS

## Project Overview
Project: Lab Management System (Kesmas = Public Health Lab, Klinik = Clinic module)
Type: CodeIgniter 3 (PHP), MySQL, Bootstrap 5, jQuery AJAX, mPDF
Purpose: Manage lab sample requests/testing workflows for Kesmas and Klinik modules
Root: C:\laragon\www\lebkes-backup
Git: no

## Tech Stack
- PHP + CodeIgniter 3
- MySQL/MariaDB
- Bootstrap 5, jQuery
- mPDF via application/libraries/Pdf.php
- Vendor committed (no root composer.json)
- PWA: assets/sw.js, assets/manifest.json

## Core Structure
- application/
  - config/ (autoload, config, database, routes)
  - controllers/ (kesmas/, klinik/, Auth.php, Home.php, Welcome.php)
  - core/ (MY_Controller.php)
  - helpers/ (auth_helper, kesmas_helper, kesmas_audit_helper, report_helper, security_helper)
  - libraries/ (Pdf.php)
  - models/ (kesmas/, Klinik/)
  - views/ (kesmas/, klinik/, auth/, home/, layout variants)
  - cache/ (session files), logs/
- database/ (kesmas_new.sql, security_features.sql, security_sessions.sql)
- tools/ (mysqli CLI scripts, self-contained)
- system/ (CI core), vendor/, assets/, cetak_laporan.php (standalone)

## 1. Public vs Protected Controllers & Routing Boundary

### Routing
- Unified login: login ? auth/index, login/do ? auth/do_login, logout ? auth/logout, dashboard ? home/index (application/config/routes.php:30-50).
- Klinik: klinik/dashboard (application/config/routes.php:51).
- Kesmas internal (protected): k-panel/dashboard defined explicitly (application/config/routes.php:53); most internal kesmas routes under k-panel/* are explicit (application/config/routes.php:55-137).
- Catch-all: $route['k-panel/(.+)'] = 'kesmas/' (application/config/routes.php:139) maps any unlisted k-panel route to kesmas/.
- Public kesmas endpoints exposed directly under /kesmas/... (application/config/routes.php:60-95): e.g. root/pendaftaran publik and survey form/store/thankyou.

### Boundary Enforcement (Critical)
- MY_Controller::__construct() has hard-block: if (->uri->segment(1) === 'kesmas') { show_404(); exit; } (application/core/MY_Controller.php:36-39).
  - Implication: Only controllers that **do NOT extend MY_Controller** are reachable via /kesmas/.... Controllers extending MY_Controller are internal/protected and must be accessed via k-panel/... (routed to kesmas/).
- Public reachable (non-MY_Controller): application/controllers/kesmas/Public_pendaftaran.php, application/controllers/kesmas/Public_survey.php (extend CI_Controller). application/controllers/Auth.php extends CI_Controller.
- Protected/internal: controllers under controllers/kesmas/ that handle admin/internal flows (e.g. Form_permintaan_kesmas, Laporan_uji_kesmas, Dashboard, Permintaan_sample, Security, etc.) extend MY_Controller and are therefore blocked at direct /kesmas/... path.

## 2. Corrections (AGENTS.md verified)

- MY_Controller::render() uses klinik/layout/header, klinik/layout/sidebar, $view, klinik/layout/footer for all internal pages (application/core/MY_Controller.php:82-90). This wrapper applies to both modules for controllers extending MY_Controller. The views/kesmas/layout/* folder is legacy/unused by this wrapper.
- MY_KlinikController does **not** check its own flag; it uses is_logged_in() (application/core/MY_Controller.php:109) and enforces write-gate for klinik (application/core/MY_Controller.php:114-117). 
- 
equire_role() differs by base: reads $this->session->userdata('role') in MY_Controller (application/core/MY_Controller.php:71), reads klinik_role (lowercased) in MY_KlinikController (application/core/MY_Controller.php:126).
- Cross-module write gate applied in **both** base classes (application/core/MY_Controller.php:47-50, 114-117) based on 
equest_is_write() + current_module() != own module ? deny_cross_module().
- Unified login checks two tables (users klinik, user_kesmas kesmas) by username; password_verify; sets module and appropriate session keys; writes records to active_sessions for both modules (application/controllers/Auth.php:70-260).
- is_logged_in() + validate_session_record() require active_sessions match (IP, UA, expiry, is_valid) (application/helpers/auth_helper.php:30-115). Fallback: if active_sessions table missing/unavailable during validation, returns 	rue (application/helpers/auth_helper.php:79-82, 109-112).
- CSRF includes index.php/* variants in exclude list (application/config/config.php:54-66).
- Case-sensitivity matters (e.g. models/Klinik/ capital K vs views/klinik/ lowercase). Path strings must match exactly on case-sensitive hosts.
- arsitektur.txt is **stale** (per AGENTS.md); treat as historical. application/alur logika lama.txt historical/ignored, application/models/kesmas/Kesmas_model_backup.php historical/ignored.

## 3. Config Summary
- config.php: CSRF ON (kesmasnew_token), sess files in APPPATH/cache (application/cache/ contains live session files), csrf_exclude_uris include public endpoints + index.php variants.
- autoload.php: db,session,form_validation; helpers url/form/security + auth_helper, kesmas_helper, kesmas_audit_helper, report_helper, security_helper.
- database.php: default uses env (DB_HOST/DB_USER/DB_PASS/DB_NAME/CI_DB_GROUP), group kesmas_new_dup.

## 4. Auth & Session
- auth_helper.php: is_logged_in() accepts klinik session shape or kesmas (user_id); validates against active_sessions (IP, UA, expiry). current_module() infers from session (module or fallbacks); current_user() maps session keys per module. 
equest_is_write() checks POST/PUT/PATCH/DELETE. deny_cross_module() returns JSON 403 for AJAX, else flash+redirect to dashboard.
- Auth.php: unified login, sess_regenerate(false) on login (Auth.php:176,224), creates active_sessions records per module (Auth.php:179-195,227-245).

## 5. Base Controllers
- MY_Controller (application/core/MY_Controller.php:28-91): blocks segment(1)==='kesmas'; requires login; write-gate (kesmas-only writes allowed from kesmas module); 
ender() uses klinik layout.
- MY_KlinikController (application/core/MY_Controller.php:94-133): requires login (via is_logged_in()); write-gate (klinik-only writes); role check uses klinik_role.

## 6. Business Logic Extraction (Fase 3)
Kesmas key flows:
- Registration: Form_permintaan_kesmas::create/store (application/controllers/kesmas/Form_permintaan_kesmas.php:80-170), model Kesmas_model::save_permintaan() (application/models/kesmas/Kesmas_model.php:145-220) saves pelanggan+permintaan+items (kesmas_item_permintaan), creates penerimaan_sampel + pembayaran on terima path; after create redirects to kaji_ulang/ (application/controllers/kesmas/Form_permintaan_kesmas.php:131).
- No registrasi: Kesmas_model::gen_no_registrasi() (application/models/kesmas/Kesmas_model.php:6-20) prefix KESMAS.Ymd. + count in kesmas_penerimaan_sampel for same date.
- Kaji ulang/receipt: kaji_ulang()/kaji_ulang_update() (application/controllers/kesmas/Form_permintaan_kesmas.php:502-620), terima sampel/penerimaan+pembayaran flow via model.
- Input hasil: uji_index/input_hasil/simpan_hasil (application/controllers/kesmas/Form_permintaan_kesmas.php:348-470), Kesmas_model::simpan_hasil_pengujian() upserts kesmas_hasil_pengujian per item, updates kesmas_pengujian_sampel (application/models/kesmas/Kesmas_model.php:450-560).
- Laporan/LHU: Laporan_uji_kesmas (application/controllers/kesmas/Laporan_uji_kesmas.php:1-210), _build_laporan_data() composes data; print can save selected petugas/verifikator/PJT and catatan_kaki to kesmas_pengujian_sampel (application/controllers/kesmas/Laporan_uji_kesmas.php:150-185).

Klinik (overview): extend MY_KlinikController, role uses klinik_role. Controllers include Form_permintaan_klinik, Form_permintaan_rm, Uji_rekam_medis, Uji_klinik, Laporan_hasil_pemeriksaan, master/user. View loading pattern: Klinik often loads views directly (not via 
ender()), while controllers extending MY_Controller tend to use 
ender().

## 7. Database (Key Tables � field-level for kunci)
- active_sessions (schema: database/kesmas_new.sql:36-49, database/security_sessions.sql:3-16): id, session_id, user_id, username, ip_address, user_agent, created_at, expires_at, is_valid. Validated by auth_helper.
- kesmas_permintaan, kesmas_pelanggan, kesmas_item_permintaan (1:1 permintaan�pelanggan, 1:N permintaan�items). Fields derived from Kesmas_model::_build_permintaan_query() (application/models/kesmas/Kesmas_model.php:22-90) and save_permintaan() (application/models/kesmas/Kesmas_model.php:145-220).
- kesmas_penerimaan_sampel, kesmas_pembayaran (related via 
o_registrasi), kesmas_pengujian_sampel, kesmas_hasil_pengujian (1:N pengujian�hasil) (application/models/kesmas/Kesmas_model.php:22-90, 145-220, 450-560).
- Master/user/log: kesmas_master_pemeriksaan, user_kesmas, users (klinik), verifikator, penanggung_jawab_teknis, 	indakan_sampel. Logs split: Kesmas (login_logs, activity_logs, audit_log, audit_log_detail, failed_login_attempts, user_lockouts), Klinik (Klinik_login_logs_model).

## 8. Standalone Scripts (separate scope, not CI app)
- cetak_laporan.php (root): standalone PHP (PDO), default DB kesmas_new, override via ?db= query param. Contains isMemenuhiSyarat() implementing MS/TMS baku mutu logic. Operational/security note: direct PDO connection (no CI auth/session), separate scope from application.
- 	ools/*.php: raw-mysqli CLI utilities, self-contained, hardcode localhost/
oot/empty password. Separate CLI scope; documented for awareness (no changes).

## 9. Stale/Backup Files
- arsitektur.txt (root): **stale** � do not treat as source of truth (code is source).
- application/alur logika lama.txt: **historical/ignored**.
- application/models/kesmas/Kesmas_model_backup.php: **historical/ignored**.
- Case-sensitive paths: application/models/Klinik/ (capital K), application/views/klinik/ (lowercase).

## 10. Env/Operational Notes
- DB env: DB_HOST/DB_USER/DB_PASS/DB_NAME/CI_DB_GROUP (application/config/database.php:1-60), kesmas_new_dup group exists.
- Protocol: HTTPS/Cloudflare forwarded proto detection (application/config/config.php:10-18).
- Timezone: Asia/Jakarta (application/config/config.php:78).
- Sessions: files in APPPATH/cache (application/cache/) � contains live sessions; deleting directory logs out all users. DB-driven validation via active_sessions.
- Vendor: committed vendor/ required for mPDF (FCPATH.'vendor/autoload.php'); no root composer.json.
- Case-sensitivity critical on Linux hosts.

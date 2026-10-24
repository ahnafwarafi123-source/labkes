# TASKS: Legacy Codebase Reverse Engineering & Documentation

## Task List (Prioritized)

- [x] T001: Document module boundaries & auth/routing (clarify MY_Controller vs MY_KlinikController, k-panel routing, catch-all, public vs protected, session keys)
- [x] T002: Map Kesmas workflow end-to-end (permintaan -> kaji ulang/terima -> input hasil -> laporan) with tables/fields (field-level kunci) + business rules
- [x] T003: Map Klinik workflow end-to-end (form permintaan klinik/RM, uji, laporan hasil) with view loading pattern (direct vs render)
- [x] T004: Document DB schema kunci (field-level) + relasi deskriptif (permintaan-pelanggan-items, penerimaan-pembayaran via no_registrasi, pengujian-hasil, master/user, logs Kesmas vs Klinik)
- [x] T005: Document security model (active_sessions + fallback, CSRF + index.php variants, cross-module write di kedua base, deny behavior AJAX/non-AJAX, lockout/audit)
- [x] T006: Document layout/rendering (MY_Controller::render() uses klinik layout for both modules; views/kesmas/layout unused by wrapper; Klinik direct view loads vs render)
- [x] T007: Create module-specific notes (controllers/models/views mapping, public vs protected)
- [x] T008: Verify standalone scripts (cetak_laporan.php PDO + isMemenuhiSyarat, tools/* mysqli CLI hardcoded creds) - separate scope, operational/security notes
- [x] T009: Architecture diagram description (entry points, flow, boundary enforcement)
- [x] T010: Technical debt notes (case-sensitivity, layout reuse, stale docs, backup files)
- [x] T011: Public endpoints & CSRF exclusions mapping (complete + index.php variants) (evidence: config.php:54-66, routes.php:60-95)
- [x] T012: Env assumptions & protocol (DB_*, CI_DB_GROUP, HTTPS/Cloudflare forwarded proto, timezone Asia/Jakarta) (evidence: database.php:1-60, config.php:10-18,78)
- [x] T013: Case-sensitivity & path casing (models/Klinik/ vs views/klinik/) (evidence: paths)
- [x] T014: Vendor/PDF & composer constraint (vendor wajib, FCPATH vendor/autoload.php, no composer.json root) (evidence: libraries/Pdf.php, vendor/)
- [x] T015: Stale docs & backup files (arsitektur.txt stale, alur logika lama.txt historical/ignored, Kesmas_model_backup.php historical/ignored) (evidence: AGENTS.md + files)
- [x] T016: Operational notes (application/cache live sessions, DB-driven validation active_sessions, fallback behavior, IP/UA change implications) (evidence: auth_helper.php:30-115, config.php:29-35)
- [x] T017: Welcome/Home & unified dashboard mapping (Home.php blok klinik by role, view_data) (evidence: Home.php:60-140, views/home/dashboard.php:1-80)
- [x] T018: Klinik model folder (Klinik/ capital) & view loading pattern (direct vs render) (evidence: controllers/klinik/Form_permintaan_klinik.php index, MY_Controller render)

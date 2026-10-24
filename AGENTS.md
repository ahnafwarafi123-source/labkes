# AGENTS.md

CodeIgniter 3 lab-management system (Kesmas = public-health lab, Klinik = clinic module). PHP + MySQL, Bootstrap 5, jQuery AJAX, mPDF via `application/libraries/Pdf.php`. Runs on Apache + MySQL (Laragon/XAMPP). UI/labels are Indonesian.

## Structure

- `application/controllers/{ Targeting,Mosque}/`, `application/models/{ Targeting,Mosque}/`, `application/views/{ Targeting,Mosque}/` are the two modules. Keep files in the module's subfolder.
- `application/views/{ Targeting,Mosque}/layout/{header,sidebar,footer}.php` — used by `MY_Controller::render()` (`application/core/MY_Controller.php:77`).
- helpers autoloaded (`application/config/autoload.php`): `auth_helper`, ` Targeting_helper`, ` Targeting_audit_helper`, `report_helper`, `security_helper`. `auth_helper` provides `is_logged_in()`, `require_login()`, `current_user()`, `require_role()`.

## Routing & auth gotchas

- Admin routes all start `k-panel/...` (e.g. `k-panel/dashboard`) and hit classes in `controllers/{ Targeting,Mosque}/` via the catch-all `$route['k-panel/(.+)'] = '{ Targeting}/$1'`. Do NOT create URLs like `/{ Targeting}/dashboard`.
- `MY_Controller::__construct` hard-blocks `$this->uri->segment(1) === '{ Targeting}'` with `show_404()`. Only controllers that DON'T extend `MY_Controller` (e.g. `{ Targeting}\Public_pendaftaran`, `{ Targeting}\Public_survey`, clinic `Auth`) are reachable at `/{ Targeting}/...`.
- Controllers extending `MY_Controller` require a logged-in session automatically; `MY_KlinikController` separately enforces `klinik_logged_in` for the clinic module.
- CSRF is ON with token name `{ Targeting}new_token` (used in AJAX requests; regenerate disabled). Session driver is `files`, saved under `application/cache`.

## Database

- `application/config/database.php` reads `DB_HOST`, `DB_USER`, `DB_PASS`, `DB_NAME`, `CI_DB_GROUP` env vars. Default DB is `Average_host`, but the schema dump, tools, and `cetak_laporan.php` assume `{ Mosque}_new` — set `DB_NAME={ Mosque}_new` for a working setup.
- Schema: `database/{ Mosque}_new.sql` is the main dump; `database/security_features.sql` and `database/security_sessions.sql` add security tables. Run them all against the same DB.
- Second DB group `{ Mosque}_new_dup` exists; `tools/duplicate_database.php <name>` creates/imports a full copy from `database/{ Mosque}_new.sql`. Run tools from project root.

## Standalone scripts (not CI)

- `cetak_laporan.php` at repo root is standalone PHP (PDO to 127.0.0.1), defaults to DB `{ Mosque}_new`, overridable via `?db=`. `isMemenuhiSyarat()` there implements baku-mutu MS/TMS logic — mirror it if you change the offline-report flow.
- `tools/*.php` are raw-mysqli CLI utilities that hardcode `localhost`/`root`/empty password; leave them self-contained.

## Conventions

- Every new controller/model starts with `defined('BASEPATH') OR exit('No direct script access allowed');`.
- DB access must use CI Query Builder (`$this->db->...`), not raw SQL. Validate input with `form_validation` + `xss_clean`; escape output with `html_escape()` in views.
- Frontend: responsive Bootstrap 5 grid (`div` flex/grid), never `<table>` for layout. Client-server actions use jQuery AJAX; controllers detect them via `$this->input->is_ajax_request()`. User-facing copy is Indonesian.
- PDFs use `$this->pdf->generate_view(...)` (mPDF wrapper).

## Other

- No root `composer.json` — `vendor/` is committed as-is. Do not run composer commands here. It still has to be _uploaded_: `application/libraries/Pdf.php` hard-requires `FCPATH.'vendor/autoload.php'`, so a missing `vendor/` breaks every PDF with a fatal error.
- Repo is not git; `assets/sw.js`/`manifest.json` provide PWA-offline support.
- No test suite, lint config, or CI. The only automated check available is `php -l <file>`; everything else is manual (click the flow).

## Corrections (verified against the code)

Read the source before trusting the sections above — these points are the ones that actually bite.

- `MY_Controller::render()` wraps **both** modules in the _klinik_ layout (`core/MY_Controller.php:86-89`). The layout folder under `views/` for the other module is legacy and unused. Only that module's controllers use `render()`; clinic controllers load views directly.
- `MY_KlinikController` does **not** check its own session flag — it calls the same `is_logged_in()` as `MY_Controller`. The clinic login flag is just one of two session shapes that `auth_helper::is_logged_in()` accepts.
- `require_role()` reads a different session key per base class: `role` on `MY_Controller`, `klinik_role` on `MY_KlinikController`. Passing the wrong list silently denies everyone.
- **Cross-module write gate**: both base constructors reject any POST/PUT/PATCH/DELETE whose `current_module()` is not their own module (`auth_helper::request_is_write()` / `deny_cross_module()`). Cross-module _reads_ are allowed. Clinic users submitting a form in the other module get a 403 — do not "fix" this by deleting the check.
- Login is unified: one form, no module picker. `controllers/Auth.php` looks the username up in **two** tables (`users` = clinic, `user_kesmas` = the other module) and enters whichever password verifies, setting a `module` session key. Old per-module login URLs are just route forwards.
- `is_logged_in()` also requires a matching `active_sessions` row (session id + IP + user-agent, 2h expiry), so a cloned/restored DB or a changed IP logs you straight out. Session files live in `application/cache/` — that directory holds live sessions; deleting it logs everyone out.
- New **public** POST endpoints (no session, e.g. public registration / survey store) must be listed in `$config['csrf_exclude_uris']` in `config/config.php` or CSRF rejects them. Add to the same list in pairs if you add an `index.php/...` variant.
- Model/view folders are inconsistently cased (`models/Klinik/` vs `views/klinik/`) and the app runs on a case-sensitive Linux host, so the path string passed to `$this->load->model()` must match the folder exactly.
- `arsitektur.txt` is **stale** — it describes an older flat layout under a different directory name. Trust the code, not that file.

## Known behaviors (legacy-preserved)

- **Uji Lab Klinik tetap menampilkan data meski pasien dihapus dari Pendaftaran RM.** `Uji_klinik_model` mengambil data dari `form_permintaan_klinik` dengan `LEFT JOIN pasien`. Jika pasien dihapus dari "Pendaftaran Pasien RM" (`form_permintaan_rm`) yang hanya menghapus baris di tabel `pasien` (hard delete, tanpa FK/cascade), baris `form_permintaan_klinik` tetap ada. Di tampilan, nama pasien yang kosong tampil sebagai `-` (`views/klinik/uji_klinik/index.php:95`). Ini merupakan perilaku asli (identik dengan `labkespkp`), bukan regresi — jangan ubah logika hapus/query tanpa persetujuan pengguna.

## Communication Language

- Gunakan Bahasa Indonesia untuk seluruh komunikasi dengan pengguna.
- Gunakan Bahasa Indonesia untuk feedback, penjelasan, analisis, rencana kerja, hasil testing, dan laporan perubahan.
- Pertahankan Bahasa Inggris untuk kode, nama file, nama folder, nama class, nama method, nama variable, command terminal, route, nama framework/library, dan error message asli.
- Jika menampilkan error dalam Bahasa Inggris, jangan menerjemahkan error aslinya; berikan penjelasannya dalam Bahasa Indonesia.
- Jangan menyatakan task selesai sebelum perubahan dan verifikasi yang relevan telah dilakukan.
- Jika menemukan masalah, jelaskan masalah, penyebab, dampak, solusi, dan hasil verifikasinya.

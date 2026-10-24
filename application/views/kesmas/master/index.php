<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php $this->load->view('kesmas/premium_list_style'); ?>

<div class="container-fluid py-4">
    <!-- HERO HEADER & STATS -->
    <div class="page-hero mb-4">
        <div class="d-flex align-items-start justify-content-between gap-3 flex-wrap">
            <div>
                <a class="btn btn-outline-secondary btn-sm me-2" href="javascript:history.back();" style="border-radius: 8px; padding: 0.3rem 0.6rem; font-weight: 600; transition: all 0.3s ease;">
                    <i class="bi bi-arrow-left"></i> <span class="d-none d-md-inline">Kembali</span>
                </a>
                <div class="mt-3">
                    <h3><?= htmlspecialchars($title) ?></h3>
                    <p class="sub">Kelola parameter pemeriksaan, baku mutu, dan satuan untuk modul Kesmas.</p>
                </div>
            </div>
            <div class="d-flex gap-2">
                <a class="btn-add" href="<?= site_url('k-panel/master_pemeriksaan/create') ?>">
                    <i class="bi bi-plus-circle me-md-1"></i> <span class="d-none d-md-inline">Tambah Parameter</span>
                </a>
            </div>
        </div>

        <div class="d-flex gap-3 mt-4 flex-wrap">
            <div class="badge-soft badge-air bg-white px-3 py-2" style="border: 1px solid rgba(13,110,253,.2);">
                <i class="bi bi-clipboard-data text-primary fs-5"></i>
                <div class="ms-1">
                    <div class="text-muted small" style="font-size: 0.75rem; line-height: 1;">Total Parameter</div>
                    <div class="fw-bolder text-dark" style="font-size: 1.1rem;"><?= $stats['total'] ?></div>
                </div>
            </div>
            <div class="badge-soft badge-lingkungan bg-white px-3 py-2" style="border: 1px solid rgba(34,197,94,.2);">
                <i class="bi bi-check-circle-fill text-success fs-5"></i>
                <div class="ms-1">
                    <div class="text-muted small" style="font-size: 0.75rem; line-height: 1;">Aktif</div>
                    <div class="fw-bolder text-dark" style="font-size: 1.1rem;"><?= $stats['aktif'] ?></div>
                </div>
            </div>
            <div class="badge-soft badge-makanan bg-white px-3 py-2" style="border: 1px solid rgba(100,116,139,.2);">
                <i class="bi bi-x-circle-fill text-secondary fs-5"></i>
                <div class="ms-1">
                    <div class="text-muted small" style="font-size: 0.75rem; line-height: 1;">Nonaktif</div>
                    <div class="fw-bolder text-dark" style="font-size: 1.1rem;"><?= $stats['nonaktif'] ?></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters & Search -->
    <div class="filter-shell mb-4">
        <form method="GET" action="<?= site_url('k-panel/master_pemeriksaan') ?>" class="row g-2 align-items-center">
            <div class="col-md-5">
                <input type="text" class="form-control" name="q" placeholder="Cari nama, kelompok, satuan..." value="<?= htmlspecialchars($filters['q']) ?>">
            </div>
            <div class="col-md-3">
                <select class="form-select form-control" style="background-color: white;" name="is_active">
                    <option value="">Semua Status</option>
                    <option value="1" <?= $filters['is_active'] === '1' ? 'selected' : '' ?>>Aktif Saja</option>
                    <option value="0" <?= $filters['is_active'] === '0' ? 'selected' : '' ?>>Nonaktif Saja</option>
                </select>
            </div>
            <div class="col-md-4 d-flex gap-2">
                <button type="submit" class="btn btn-outline-primary btn-filter w-100">Filter</button>
                <?php if(!empty($filters['q']) || $filters['is_active'] !== ''): ?>
                    <a href="<?= site_url('k-panel/master_pemeriksaan') ?>" class="btn btn-outline-secondary btn-filter w-100">Reset</a>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <!-- Main Content Tabs -->
    <div class="card-wow">
        <div class="card-header bg-white border-bottom-0 pt-3 pb-0">
            <ul class="nav nav-tabs border-bottom" id="kategoriTabs" role="tablist">
                <?php $first = true; foreach($kategori_list as $kat_key => $kat_label): ?>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link fw-semibold <?= $first ? 'active' : '' ?>" 
                                id="tab-<?= $kat_key ?>" 
                                data-bs-toggle="tab" 
                                data-bs-target="#content-<?= $kat_key ?>" 
                                type="button" role="tab">
                            <?= $kat_label ?>
                            <span class="badge bg-secondary ms-1 rounded-pill">
                                <?= isset($grouped[$kat_key]) ? count($grouped[$kat_key]) : 0 ?>
                            </span>
                        </button>
                    </li>
                <?php $first = false; endforeach; ?>
            </ul>
        </div>
        <div class="card-body p-0">
            <div class="tab-content" id="kategoriTabsContent">
                <?php $first = true; foreach($kategori_list as $kat_key => $kat_label): ?>
                    <div class="tab-pane fade <?= $first ? 'show active' : '' ?>" id="content-<?= $kat_key ?>" role="tabpanel">
                        <?php if(empty($grouped[$kat_key])): ?>
                            <div class="text-center py-5 text-muted">
                                <i class="fas fa-folder-open fa-3x mb-3 text-light"></i>
                                <h5>Belum ada parameter</h5>
                                <p class="mb-0">Tidak ada parameter pemeriksaan untuk kategori ini.</p>
                            </div>
                        <?php else: ?>
                            <?php $is_small = count($grouped[$kat_key]) <= 4; ?>
                            <div class="<?= $is_small ? '' : 'table-responsive' ?>">
                                <table class="table table-hover table-bordered mb-0 align-middle master-table table-mobile-cards" data-kategori="<?= $kat_key ?>">
                                    <thead class="table-light">
                                        <tr>
                                            <th style="width: 40px;" class="text-center"><i class="fas fa-sort text-muted" title="Drag to reorder"></i></th>
                                            <th style="width: 5%;">No</th>
                                            <th style="width: 15%;">Kelompok</th>
                                            <th style="width: 20%;">Nama Pemeriksaan</th>
                                            <th style="width: 10%;">Satuan</th>
                                            <th style="width: 15%;">Baku Mutu</th>
                                            <th style="width: 15%;">Metode</th>
                                            <th style="width: 8%;" class="text-center">Status</th>
                                            <th style="width: 12%;" class="text-center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody class="sortable-tbody">
                                        <?php 
                                            $no = 1; 
                                            // Sort items within this tab by kelompok then urutan
                                            $items = $grouped[$kat_key];
                                            // PHP sorting is already done in query (order_by kategori, urutan), 
                                            // but if we need visual grouping by kelompok we might want to display them grouped.
                                            // We will display them as they come, which is sorted by urutan.
                                            foreach($items as $row): 
                                        ?>
                                            <tr data-id="<?= $row['id_master_pemeriksaan'] ?>" class="<?= $row['is_active'] ? '' : 'table-secondary text-muted' ?>">
                                                <td class="text-center cursor-move text-muted drag-handle"><i class="fas fa-grip-lines"></i></td>
                                                <td data-label="No"><?= $no++ ?></td>
                                                <td data-label="Kelompok"><span class="badge bg-info text-dark"><?= htmlspecialchars($row['kelompok_pemeriksaan']) ?></span></td>
                                                <td class="fw-semibold" data-label="Nama Pemeriksaan"><?= htmlspecialchars($row['nama_pemeriksaan']) ?></td>
                                                <td data-label="Satuan"><?= htmlspecialchars($row['satuan'] ?? '-') ?></td>
                                                <td data-label="Baku Mutu">
                                                    <?= htmlspecialchars($row['baku_mutu'] ?? '-') ?>
                                                    <?php if(!empty($row['baku_type'])): ?>
                                                        <br><small class="text-muted" style="font-size: 0.7em;">[<?= $row['baku_type'] ?>]</small>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="small" data-label="Metode"><?= htmlspecialchars($row['metode'] ?? '-') ?></td>
                                                <td class="text-center" data-label="Status">
                                                    <?php if($row['is_active']): ?>
                                                        <span class="badge bg-success rounded-pill px-2">Aktif</span>
                                                    <?php else: ?>
                                                        <span class="badge bg-secondary rounded-pill px-2">Nonaktif</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="text-center">
                                                    <div class="d-flex flex-wrap gap-1 justify-content-center">
                                                        <a href="<?= site_url('k-panel/master_pemeriksaan/edit/'.$row['id_master_pemeriksaan']) ?>" class="btn btn-sm btn-outline-primary btn-action" title="Edit">
                                                            <i class="bi bi-pencil-square"></i> Edit
                                                        </a>
                                                        <button type="button" class="btn btn-sm btn-outline-info btn-action text-info btn-duplicate" data-id="<?= $row['id_master_pemeriksaan'] ?>" data-nama="<?= htmlspecialchars($row['nama_pemeriksaan']) ?>" data-kategori="<?= $row['kode'] ?>" title="Duplikat">
                                                            <i class="bi bi-copy"></i> Duplikat
                                                        </button>
                                                        <?php if($row['is_active']): ?>
                                                            <button type="button" class="btn btn-sm btn-outline-warning btn-action text-warning btn-toggle-active" data-id="<?= $row['id_master_pemeriksaan'] ?>" data-action="nonaktifkan" title="Nonaktifkan">
                                                                <i class="bi bi-slash-circle"></i> Nonaktif
                                                            </button>
                                                        <?php else: ?>
                                                            <button type="button" class="btn btn-sm btn-outline-success btn-action btn-toggle-active" data-id="<?= $row['id_master_pemeriksaan'] ?>" data-action="aktifkan" title="Aktifkan">
                                                                <i class="bi bi-check2-circle"></i> Aktifkan
                                                            </button>
                                                        <?php endif; ?>
                                                        <button type="button" class="btn btn-sm btn-outline-danger btn-action btn-delete" data-id="<?= $row['id_master_pemeriksaan'] ?>" title="Hapus">
                                                            <i class="bi bi-trash"></i> Hapus
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php $first = false; endforeach; ?>
            </div>
        </div>
    </div>
</div>

<!-- Modal Duplikat -->
<div class="modal fade" id="modalDuplicate" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content border-0 shadow">
      <div class="modal-header bg-light">
        <h5 class="modal-title"><i class="fas fa-copy text-info me-2"></i>Duplikat Parameter</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="<?= site_url('k-panel/master_pemeriksaan/duplicate/0') ?>" method="POST" id="formDuplicate">
          <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
          <div class="modal-body">
            <p class="mb-3">Duplikat parameter <strong id="dupNamaText" class="text-primary"></strong> ke kategori/kelompok lain.</p>
            
            <div class="mb-3">
                <label class="form-label fw-semibold">Kategori Tujuan</label>
                <select class="form-select" name="kategori_target" id="dupKategoriTarget" required>
                    <option value="">-- Pilih Kategori --</option>
                    <?php foreach($kategori_list as $k => $v): ?>
                        <option value="<?= $k ?>"><?= $v ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="mb-3">
                <label class="form-label fw-semibold">Kelompok Tujuan</label>
                <select class="form-select" name="kelompok_target" id="dupKelompokTarget" required>
                    <option value="">-- Pilih Kelompok --</option>
                    <!-- Options populated via JS -->
                </select>
            </div>
          </div>
          <div class="modal-footer bg-light">
            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-info text-white"><i class="fas fa-copy me-2"></i>Proses Duplikat</button>
          </div>
      </form>
    </div>
  </div>
</div>

<!-- Form tersembunyi untuk toggle active -->
<form id="formToggleActive" method="POST" style="display:none;" action="">
    <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
</form>

<style>
    .cursor-move { cursor: grab; }
    .cursor-move:active { cursor: grabbing; }
    .drag-handle:hover i { color: #0d6efd !important; }
    .ui-sortable-helper {
        display: table;
        background: #fff;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }
    .ui-sortable-placeholder {
        visibility: visible !important;
        background: #f8f9fa;
    }
</style>

<!-- Load jQuery UI for Sortable (Drag & Drop) -->
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // 1. Toggle Active
    var toggleBtns = document.querySelectorAll('.btn-toggle-active');
    for (var i = 0; i < toggleBtns.length; i++) {
        toggleBtns[i].addEventListener('click', function() {
            var id = this.getAttribute('data-id');
            var action = this.getAttribute('data-action');
            if (confirm("Anda yakin ingin " + action + " parameter ini?")) {
                var form = document.getElementById('formToggleActive');
                form.action = "<?= site_url('k-panel/master_pemeriksaan/toggle/') ?>" + id;
                form.submit();
            }
        });
    }

    // 1.5 Delete
    var deleteBtns = document.querySelectorAll('.btn-delete');
    for (var j = 0; j < deleteBtns.length; j++) {
        deleteBtns[j].addEventListener('click', function() {
            var id = this.getAttribute('data-id');
            if (confirm('Anda yakin ingin menghapus parameter ini secara permanen? Data yang sudah dihapus tidak dapat dikembalikan.')) {
                var form = document.getElementById('formToggleActive');
                form.action = "<?= site_url('k-panel/master_pemeriksaan/delete/') ?>" + id;
                form.submit();
            }
        });
    }

    // 2. Map kelompok data for Duplicate Modal
    var kelompokMap = <?= json_encode($kelompok_map) ?>;
    var dupModalEl = document.getElementById('modalDuplicate');
    var dupModal = null;
    if (dupModalEl && typeof bootstrap !== 'undefined') {
        dupModal = new bootstrap.Modal(dupModalEl);
    }
    var dupForm = document.getElementById('formDuplicate');
    var dupKategoriTarget = document.getElementById('dupKategoriTarget');
    var dupKelompokTarget = document.getElementById('dupKelompokTarget');
    var dupNamaText = document.getElementById('dupNamaText');

    if (dupKategoriTarget) {
        dupKategoriTarget.addEventListener('change', function() {
            var kat = this.value;
            dupKelompokTarget.innerHTML = '<option value="">-- Pilih Kelompok --</option>';
            if (kat && kelompokMap[kat]) {
                for (var k = 0; k < kelompokMap[kat].length; k++) {
                    var kel = kelompokMap[kat][k];
                    var opt = document.createElement('option');
                    opt.value = kel;
                    opt.textContent = kel;
                    dupKelompokTarget.appendChild(opt);
                }
            }
        });
    }

    var dupBtns = document.querySelectorAll('.btn-duplicate');
    for (var d = 0; d < dupBtns.length; d++) {
        dupBtns[d].addEventListener('click', function() {
            var id = this.getAttribute('data-id');
            var nama = this.getAttribute('data-nama');
            
            dupNamaText.textContent = nama;
            dupForm.action = "<?= site_url('k-panel/master_pemeriksaan/duplicate/') ?>" + id;
            
            // Reset dropdowns
            dupKategoriTarget.value = '';
            dupKelompokTarget.innerHTML = '<option value="">-- Pilih Kelompok --</option>';
            
            if (dupModal) dupModal.show();
        });
    }

    // 3. Drag & Drop Reordering via jQuery UI Sortable
    if (typeof jQuery !== 'undefined' && typeof jQuery.ui !== 'undefined') {
        $('.sortable-tbody').sortable({
            handle: '.drag-handle',
            placeholder: 'ui-sortable-placeholder',
            axis: 'y',
            update: function(event, ui) {
                var tbody = $(this);
                
                // Collect new order
                var orders = [];
                tbody.find('tr[data-id]').each(function(index) {
                    orders.push({
                        id: $(this).data('id'),
                        urutan: index + 1
                    });
                    // Update number column visually
                    $(this).find('td:eq(1)').text(index + 1);
                });

                // Send to server
                $.ajax({
                    url: '<?= site_url('k-panel/master_pemeriksaan/reorder') ?>',
                    method: 'POST',
                    data: {
                        orders: orders,
                        '<?= $this->security->get_csrf_token_name() ?>': '<?= $this->security->get_csrf_hash() ?>'
                    },
                    success: function(res) {
                        if (res.ok) {
                            // Flash brief success background
                            tbody.find('tr').animate({backgroundColor: '#e8f5e9'}, 200).animate({backgroundColor: 'transparent'}, 500);
                        } else {
                            alert('Gagal menyimpan urutan baru.');
                        }
                    },
                    error: function() {
                        alert('Terjadi kesalahan koneksi.');
                    }
                });
            }
        });
    } else {
        console.warn('jQuery UI not loaded. Drag & drop reordering disabled.');
    }
});
</script>

<div class="table-responsive premium-table-wrapper">
  <table class="table table-hover datatable" id="table-<?= $table_id ?? 'default' ?>" style="width:100%">
    <thead>
      <tr>
        <?= $thead ?? '' ?>
      </tr>
    </thead>
    <tbody>
      <?= $tbody ?? '' ?>
    </tbody>
  </table>
</div>

<style>
.premium-table-wrapper {
  border-radius: var(--border-radius-large);
  overflow: hidden;
  box-shadow: var(--glass-shadow);
  background: var(--glass-bg);
  /* backdrop-filter: blur(20px); disabled */
  border: 1px solid var(--glass-border);
  margin-bottom: 2rem;
}

.premium-table-wrapper .table {
  margin-bottom: 0;
}

.premium-table-wrapper .dataTables_wrapper .dataTables_filter input {
  border-radius: var(--border-radius);
  border: 1px solid var(--glass-border);
  background: var(--glass-bg);
  /* backdrop-filter: blur(10px); disabled */
}

.premium-table-wrapper .dataTables_wrapper .dataTables_length select,
.premium-table-wrapper .dataTables_wrapper .dataTables_filter input {
  margin: 0.25rem;
  border-radius: var(--border-radius);
}

.premium-table-wrapper .dataTables_wrapper .dataTables_paginate .paginate_button {
  border-radius: var(--border-radius);
  margin: 0 0.125rem;
  transition: var(--transition-luxury);
}

.premium-table-wrapper .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
  background: var(--primary-gradient) !important;
  color: white !important;
  transform: translateY(-1px);
}

.premium-table-wrapper .dataTables_wrapper .dataTables_info {
  color: var(--text-primary);
  font-weight: 500;
}
</style>

<script>
$(document).ready(function() {
  $('#table-<?= $table_id ?? 'default' ?>').DataTable({
    responsive: true,
    pageLength: 25,
    lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Semua"]],
    language: {
      search: "Cari:",
      lengthMenu: "Tampilkan _MENU_ data",
      info: "Menampilkan _START_ hingga _END_ dari _TOTAL_ data",
      infoEmpty: "Menampilkan 0 hingga 0 dari 0 data",
      infoFiltered: "(disaring dari _MAX_ total data)",
      paginate: {
        first: "Pertama",
        last: "Terakhir",
        next: "Selanjutnya",
        previous: "Sebelumnya"
      }
    },
    order: [[ 0, "desc" ]],
    dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>' +
         '<"row"<"col-sm-12"tr>>' +
         '<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>'
  });
});
</script>


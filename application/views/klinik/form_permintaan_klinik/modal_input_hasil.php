<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Modal Input Hasil Pemeriksaan -->
<div class="modal fade" id="modalInputHasil" tabindex="-1" role="dialog" aria-labelledby="modalInputHasilLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
    <div class="modal-content shadow-lg border-0 rounded-4">
      <div class="modal-header bg-info text-white">
        <h5 class="modal-title fw-bold" id="modalInputHasilLabel">
          <i class="fas fa-vial me-2"></i> Input Hasil Pemeriksaan
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body p-4 bg-light">
        <form id="formInputHasil">
          <input type="hidden" name="id_pemeriksaan" id="id_pemeriksaan">
          <input type="hidden" name="id_pasien" id="id_pasien" value="<?= isset($pasien->id_pasien) ? $pasien->id_pasien : '' ?>">

          <div class="card border-0 shadow-sm mb-3 rounded-4">
            <div class="card-body">
              <div class="row g-3">
                <div class="col-md-4">
                  <label class="form-label">Hasil (Angka)</label>
                  <input type="number" step="any" class="form-control rounded-pill" name="hasil" placeholder="Masukkan hasil angka">
                </div>
                <div class="col-md-4">
                  <label class="form-label">Satuan</label>
                  <input type="text" class="form-control rounded-pill" name="satuan" placeholder="g/dL, mg/dL, IU/L, dll">
                </div>
                <div class="col-md-4">
                  <label class="form-label">Nilai Rujukan</label>
                  <input type="text" class="form-control rounded-pill" name="nilai_rujukan" placeholder="12-16, <5, >10, dll">
                </div>
              </div>

              <div class="row g-3 mt-2">
                <div class="col-md-6">
                  <label class="form-label">Metode Pemeriksaan</label>
                  <input type="text" class="form-control rounded-pill" name="metode" placeholder="Misal: Spektrofotometri">
                </div>
                <div class="col-md-6">
                  <label class="form-label">Keterangan</label>
                  <textarea class="form-control rounded-3" rows="2" name="keterangan" placeholder="Tambahkan catatan atau hasil observasi"></textarea>
                </div>
              </div>
            </div>
          </div>

          <div class="text-end">
            <button type="button" class="btn btn-secondary rounded-pill" data-bs-dismiss="modal">Batal</button>
            <button type="button" id="btnSimpanHasil" class="btn btn-success rounded-pill px-4">Simpan</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- ... modal HTML di atas ... -->

<script>
$(document).ready(function() {
  console.log("✅ jQuery aktif dan siap.");

  $('#btnSimpanHasil').on('click', function(e) {
    e.preventDefault();
    console.log("🟢 Tombol Simpan diklik!");

    const data = {
      id_pemeriksaan: $('#id_pemeriksaan').val(),
      id_pasien: $('#id_pasien').val(),
      hasil: $('input[name="hasil"]').val(),
      satuan: $('input[name="satuan"]').val(),
      nilai_rujukan: $('input[name="nilai_rujukan"]').val(),
      metode: $('input[name="metode"]').val(),
      keterangan: $('textarea[name="keterangan"]').val()
    };

    console.log("📤 Data dikirim:", data);

    $.ajax({
      url: '<?= site_url("klinik/form_permintaan_klinik/simpan_hasil_temp") ?>',
      type: 'POST',
      data: data,
      dataType: 'json',
      success: function(response) {
        console.log("📥 Response dari server:", response);
        if (response.status === 'success') {
          alert("✅ " + response.message);
          // Use Bootstrap 5 native modal API to hide
          const modalEl = document.getElementById('modalInputHasil');
          const modalObj = bootstrap.Modal.getInstance(modalEl);
          if(modalObj) modalObj.hide();
          else $('#modalInputHasil').modal('hide'); // Fallback
          $('#formInputHasil')[0].reset();
        } else {
          alert("⚠️ " + response.message);
        }
      },
      error: function(xhr, status, error) {
        console.error("❌ Gagal:", error);
        alert("Terjadi kesalahan saat menyimpan: " + error);
      }
    });
  });
});
</script>


<script>
$(document).ready(function() {
  console.log("✅ jQuery aktif dan siap.");
});
</script>
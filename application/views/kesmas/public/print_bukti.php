<?php
// Siapkan array parameter yang dipilih agar otomatis dicentang di tabel
$selected_params = [];
if (isset($pemeriksaan)) {
    foreach ($pemeriksaan as $p) {
        $selected_params[] = trim($p->nama_pemeriksaan ?? '');
    }
}

// Fungsi untuk memunculkan row parameter di tabel (dengan centang otomatis)
function render_row($label, $selected_params) {
    $isChecked = false;
    foreach ($selected_params as $s) {
        // Cek jika teks parameter ada kecocokan (dua arah: label lebih pendek dari nama DB, mis. TDS/ALT)
        if (strtolower(trim($s)) === strtolower(trim($label)) || stripos($label, trim($s)) !== false || stripos(trim($s), $label) !== false) {
            $isChecked = true;
            break;
        }
    }
    $icon = $isChecked ? '&#9745;' : '&#9744;'; // Kotak tercentang atau kosong
    echo '<tr>
        <td style="border: none; border-bottom: 1px solid #000; border-right: 1px solid #000; padding: 2px 5px; font-size: 10pt;">
            <span style="font-size:14px; margin-right:4px;">' . $icon . '</span> ' . htmlspecialchars($label) . '
        </td>
        <td style="border: none; border-bottom: 1px solid #000; border-right: 1px solid #000;"></td>
        <td style="border: none; border-bottom: 1px solid #000;"></td>
    </tr>';
}

// Fungsi centang dinamis untuk kotak isian biasa
function check_box($value, $expected) {
    $value = strtolower(trim((string)$value));
    $expected = strtolower(trim((string)$expected));
    return stripos($value, $expected) !== false ? '&#9745;' : '&#9744;';
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak Formulir - <?= htmlspecialchars($pendaftaran->no_registrasi ?? 'Baru') ?></title>
    <style>
        @page { size: 215mm 330mm; margin: 8mm; }
        body { font-family: Arial, sans-serif; font-size: 10pt; color: #000; margin: 0; padding: 0; background: #fff; }
        .print-container { width: 100%; margin: 0 auto; padding: 10px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #000; padding: 4px; }
        .text-center { text-align: center; }
        .fw-bold { font-weight: bold; }
        
        /* Hide buttons when printing */
        @media print {
            .no-print { display: none !important; }
            body { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
        }
    </style>
</head>
<body onload="window.print()">
    <div class="print-container">
        
        <!-- Action Buttons -->
        <div class="no-print" style="margin-bottom: 20px; text-align: right;">
            <button onclick="window.print()" style="padding: 8px 16px; background: #0d6efd; color: white; border: none; border-radius: 4px; cursor: pointer;">🖨️ Cetak Formulir</button>
            <button onclick="window.close()" style="padding: 8px 16px; background: #6c757d; color: white; border: none; border-radius: 4px; cursor: pointer;">Tutup</button>
        </div>

        <!-- Kop Surat -->
        <table style="border: none; margin-bottom: 10px;">
            <tr>
                <td style="border: none; width: 15%; text-align: center;">
                    <!-- Pastikan path logo sesuai dengan aset Anda -->
                    <img src="<?= base_url('assets/img/logo.png') ?>" alt="Logo" style="width: 70px;">
                </td>
                <td style="border: none; width: 85%; text-align: center; line-height: 1.3;">
                    <div style="font-size: 12pt;">DINAS KESEHATAN</div>
                    <div style="font-size: 14pt; font-weight: bold;">UPTD. LABORATORIUM KESEHATAN</div>
                    <div style="font-size: 9pt;">Jalan Delima Siam VI Grimaya Pangkalpinang Telp. (0717) 4220759</div>
                </td>
            </tr>
        </table>

        <!-- Judul -->
        <div style="text-align: center; font-weight: bold; font-size: 12pt; border-top: 2px solid #000; border-bottom: 1px solid #000; padding: 8px 0; margin-bottom: 15px; border-bottom-width: 2px;">
            FORMULIR PERMINTAAN PEMERIKSAAN LABORATORIUM KESMAS
        </div>

        <!-- Identitas Section -->
        <table style="margin-bottom: 15px;">
            <tr>
                <td style="width: 33%; vertical-align: top; border-right: none; padding: 8px;">
                    <div style="font-weight: bold; margin-bottom: 8px;">Identitas Sampel</div>
                    <table style="border: none; width: 100%;">
                        <tr><td style="border:none; padding: 2px 0; width: 45%;">No. Register</td><td style="border:none; padding: 2px 0;">: <?= htmlspecialchars($pendaftaran->no_registrasi ?? '') ?></td></tr>
                        <tr><td style="border:none; padding: 2px 0;">Nama</td><td style="border:none; padding: 2px 0;">: <?= htmlspecialchars($pendaftaran->nama_sampel ?? '') ?></td></tr>
                        <tr><td style="border:none; padding: 2px 0;">Jenis Sampel</td><td style="border:none; padding: 2px 0;">: <?= htmlspecialchars($pendaftaran->jenis_sampel ?? '') ?></td></tr>
                        <tr><td style="border:none; padding: 2px 0;">Volume Sampel</td><td style="border:none; padding: 2px 0;">: <?= htmlspecialchars($pendaftaran->volume_ml ?? '') ?> ml</td></tr>
                        <tr><td style="border:none; padding: 2px 0;">Tgl Pengambilan</td><td style="border:none; padding: 2px 0;">: <?= htmlspecialchars($pendaftaran->tgl_pengambilan ?? '') ?></td></tr>
                        <tr><td style="border:none; padding: 2px 0;">Jam Pengambilan</td><td style="border:none; padding: 2px 0;">: <?= htmlspecialchars($pendaftaran->jam_pengambilan ?? '') ?></td></tr>
                    </table>
                </td>
                <td style="width: 33%; vertical-align: top; border-right: none; padding: 8px;">
                    <div style="font-weight: bold; margin-bottom: 8px;">Identitas Sampel</div>
                    <div style="margin-bottom: 10px;">Lokasi Pengambilan Sampel :<br><?= htmlspecialchars($pendaftaran->lokasi_pengambilan ?? '') ?></div>
                    <div style="margin-bottom: 10px;">Petugas Pengambil Sampel :<br><?= htmlspecialchars($pendaftaran->id_petugas ?? '') ?></div>
                    <div>Informasi Tambahan :<br><?= htmlspecialchars($pendaftaran->info_tambahan ?? '') ?></div>
                </td>
                <td style="width: 34%; vertical-align: top; padding: 8px;">
                    <div style="font-weight: bold; margin-bottom: 8px;">Identitas Pengirim</div>
                    <table style="border: none; width: 100%;">
                        <tr><td style="border:none; padding: 2px 0; width: 35%;">NIK</td><td style="border:none; padding: 2px 0;">: <?= htmlspecialchars($pendaftaran->nik ?? '') ?></td></tr>
                        <tr><td style="border:none; padding: 2px 0; width: 35%;">Nama</td><td style="border:none; padding: 2px 0;">: <?= htmlspecialchars($pendaftaran->nama ?? '') ?></td></tr>
                        <tr><td style="border:none; padding: 2px 0;">Alamat</td><td style="border:none; padding: 2px 0;">: <?= htmlspecialchars($pendaftaran->alamat ?? '') ?></td></tr>
                        <tr><td style="border:none; padding: 2px 0;">No. Telp</td><td style="border:none; padding: 2px 0;">: <?= htmlspecialchars($pendaftaran->telp ?? '') ?></td></tr>
                        <tr><td style="border:none; padding: 2px 0;">Tanggal Permintaan</td><td style="border:none; padding: 2px 0;">: <?= htmlspecialchars($pendaftaran->tgl_permintaan ?? '') ?></td></tr>
                    </table>
                    <div style="margin-top: 25px;">Tanda Tangan Pengirim :</div>
                </td>
            </tr>
            <tr>
                <td colspan="3" style="padding: 8px;">
                    <table style="border: none; width: 100%;">
                        <tr>
                            <td style="border: none; width: 12%;">Tindakan Sampel :</td>
                            <td style="border: none; width: 12%;"><span style="font-size:14px;"><?= check_box($pendaftaran->tindakan_sampel ?? '', 'Langsung') ?></span> Langsung</td>
                            <td style="border: none; width: 12%;"><span style="font-size:14px;"><?= check_box($pendaftaran->tindakan_sampel ?? '', 'Kiriman') ?></span> Kiriman</td>
                            <td style="border: none; width: 12%;"><span style="font-size:14px;"><?= check_box($pendaftaran->tindakan_sampel ?? '', 'Rujuk') ?></span> Rujuk</td>
                            <td style="border: none; width: 10%;">Sampel :</td>
                            <td style="border: none; width: 12%;"><span style="font-size:14px;"><?= check_box($pendaftaran->id_kategori_sampel ?? '', 'Air') ?></span> Air</td>
                            <td style="border: none; width: 12%;"><span style="font-size:14px;"><?= check_box($pendaftaran->id_kategori_sampel ?? '', 'Makanan') ?></span> Makanan</td>
                            <td style="border: none; width: 18%;"><span style="font-size:14px;"><?= check_box($pendaftaran->id_kategori_sampel ?? '', 'Lingkungan') ?></span> Lingkungan</td>
                        </tr>
                    </table>
                    <div style="font-size: 9pt; margin-top: 3px;">*Diisi Pengambil Sampel</div>
                </td>
            </tr>
        </table>

        <!-- Parameter Section (Tabel Kiri & Kanan) -->
        <table style="margin-bottom: 0; border-bottom: none;">
            <tr>
                <td colspan="2" class="text-center fw-bold" style="background: #f9f9f9; padding: 6px;">DAFTAR JENIS PEMERIKSAAN</td>
            </tr>
            <tr>
                <!-- Kolom Kiri -->
                <td style="width: 50%; padding: 0; vertical-align: top; border-right: 1px solid #000; border-bottom: none;">
                    <table style="border: none; width: 100%;">
                        <tr><td colspan="3" class="text-center fw-bold" style="border: none; border-bottom: 1px solid #000; padding: 4px;">I. AIR MINUM</td></tr>
                        <tr>
                            <td style="border: none; border-bottom: 1px solid #000; border-right: 1px solid #000;"></td>
                            <td class="text-center" style="width: 45px; border: none; border-bottom: 1px solid #000; border-right: 1px solid #000; font-size: 9pt;">HASIL</td>
                            <td class="text-center" style="width: 45px; border: none; border-bottom: 1px solid #000; font-size: 9pt;">PARAF</td>
                        </tr>
                        <tr><td colspan="3" style="border:none; border-bottom: 1px solid #000; font-weight: bold; padding: 4px 8px;">A. FISIK WAJIB</td></tr>
                        <?php
                            $air_minum_fisika = ['Suhu', 'Jumlah Zat Padat Terlarut', 'Kekeruhan', 'Warna', 'Bau'];
                            foreach($air_minum_fisika as $p) render_row($p, $selected_params);
                        ?>
                        <tr><td colspan="3" style="border:none; border-bottom: 1px solid #000; font-weight: bold; padding: 4px 8px;">B. KIMIA WAJIB</td></tr>
                        <?php
                            $air_minum_kimia_wajib = ['pH', 'Nitrat (sebagai NO3) (terlarut)', 'Nitrit (sebagai NO2) (terlarut)', 'Kromium Valensi 6 (Cr6+) (terlarut)', 'Besi (terlarut)', 'Mangan (terlarut)', 'Sisa Khlor (terlarut)', 'Arsen (As) (terlarut)', 'Kadmium (Cd) (terlarut)', 'Timbal (Pb) (terlarut)', 'Flourida (F) (terlarut)', 'Alumunium (Al) (terlarut)'];
                            foreach($air_minum_kimia_wajib as $p) render_row($p, $selected_params);
                        ?>
                        <tr><td colspan="3" style="border:none; border-bottom: 1px solid #000; font-weight: bold; padding: 4px 8px;">C. KIMIA KHUSUS</td></tr>
                        <?php
                            $air_minum_kimia_khusus = ['Total Kromium (Cr)', 'Amonia (NH3) (terlarut)', 'Hidrogen Sulfida (H2S) (terlarut)', 'Sianida (Cn)', 'Tembaga (Cu)', 'Selenium (Se)', 'Seng (Zn)', 'Nikel (Ni)', 'Senyawa Diazo (Zat pewarna sintetik)', 'Fenol (C6H6O) (C6H5OH)', 'Fosfat (PO4)', 'Methylene Blue Active Substance (MBAS)', 'Detergen'];
                            foreach($air_minum_kimia_khusus as $p) render_row($p, $selected_params);
                        ?>
                        <tr><td colspan="3" style="border:none; border-bottom: 1px solid #000; font-weight: bold; padding: 4px 8px;">D. MIKROBIOLOGI WAJIB</td></tr>
                        <?php
                            $air_minum_mikro_wajib = ['Escherichia coli', 'Total Coliform'];
                            foreach($air_minum_mikro_wajib as $p) render_row($p, $selected_params);
                        ?>
                        <tr><td colspan="3" style="border:none; border-bottom: 1px solid #000; font-weight: bold; padding: 4px 8px;">E. MIKROBIOLOGI TAMBAHAN</td></tr>
                        <?php
                            $air_minum_mikro_tambahan = ['ALT'];
                            foreach($air_minum_mikro_tambahan as $p) render_row($p, $selected_params);
                        ?>
                    </table>
                </td>
                
                <!-- Kolom Kanan -->
                <td style="width: 50%; padding: 0; vertical-align: top; border-bottom: none;">
                    <table style="border: none; width: 100%;">
                        <tr><td colspan="3" class="text-center fw-bold" style="border: none; border-bottom: 1px solid #000; padding: 4px;">II. AIR BERSIH</td></tr>
                        <tr>
                            <td style="border: none; border-bottom: 1px solid #000; border-right: 1px solid #000;"></td>
                            <td class="text-center" style="width: 45px; border: none; border-bottom: 1px solid #000; border-right: 1px solid #000; font-size: 9pt;">HASIL</td>
                            <td class="text-center" style="width: 45px; border: none; border-bottom: 1px solid #000; font-size: 9pt;">PARAF</td>
                        </tr>
                        <tr><td colspan="3" style="border:none; border-bottom: 1px solid #000; font-weight: bold; padding: 4px 8px;">A. FISIK WAJIB</td></tr>
                        <?php
                            $air_bersih_fisika = ['Suhu', 'Jumlah Zat Padat Terlarut', 'Kekeruhan', 'Warna', 'Bau'];
                            foreach($air_bersih_fisika as $p) render_row($p, $selected_params);
                        ?>
                        <tr><td colspan="3" style="border:none; border-bottom: 1px solid #000; font-weight: bold; padding: 4px 8px;">B. KIMIA WAJIB</td></tr>
                        <?php
                            $air_bersih_kimia = ['pH', 'Nitrat (sebagai NO3) (terlarut)', 'Nitrit (sebagai NO2) (terlarut)', 'Kromium Valensi 6 (Cr6+) (terlarut)', 'Besi (terlarut)', 'Mangan (terlarut)'];
                            foreach($air_bersih_kimia as $p) render_row($p, $selected_params);
                        ?>
                        <tr><td colspan="3" style="border:none; border-bottom: 1px solid #000; font-weight: bold; padding: 4px 8px;">C. MIKROBIOLOGI WAJIB</td></tr>
                        <?php
                            $air_bersih_bakteri = ['Escherichia coli', 'Total Coliform'];
                            foreach($air_bersih_bakteri as $p) render_row($p, $selected_params);
                        ?>

                        <tr><td colspan="3" class="text-center fw-bold" style="border: none; border-bottom: 1px solid #000; border-top: 1px solid #000; padding: 4px; background: #f9f9f9;">III. MAKANAN</td></tr>
                        <tr><td colspan="3" style="border:none; border-bottom: 1px solid #000; font-weight: bold; padding: 4px 8px;">A. KIMIA</td></tr>
                        <?php
                            $makanan_kimia = ['Boraks', 'Formalin', 'Methanyl Yellow', 'Rhodamin B', 'Sakarin', 'Siklamat'];
                            foreach($makanan_kimia as $p) render_row($p, $selected_params);
                        ?>
                        <tr><td colspan="3" style="border:none; border-bottom: 1px solid #000; font-weight: bold; padding: 4px 8px;">B. MIKROBIOLOGI</td></tr>
                        <?php
                            $makanan_bakteri = ['Escherichia coli', 'Salmonella sp', 'Staphylococcus aureus', 'Bacillus cereus', 'Listeria Monocytogenes'];
                            foreach($makanan_bakteri as $p) render_row($p, $selected_params);
                        ?>
                        <tr><td colspan="3" style="border:none; border-bottom: 1px solid #000; font-weight: bold; padding: 4px 8px;">C. PARASITOLOGI</td></tr>
                        <?php
                            $makanan_parasit = ['Parasitologi Sayuran'];
                            foreach($makanan_parasit as $p) render_row($p, $selected_params);
                        ?>

                        <tr><td colspan="3" class="text-center fw-bold" style="border: none; border-bottom: 1px solid #000; border-top: 1px solid #000; padding: 4px; background: #f9f9f9;">IV. LINGKUNGAN</td></tr>
                        <?php
                            $lingkungan = ['Angka Kuman Ruangan', 'Angka Kuman Usap Tangan'];
                            foreach($lingkungan as $p) render_row($p, $selected_params);
                        ?>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="2" class="text-center fw-bold" style="border-top: 1px solid #000; background: #f9f9f9; padding: 6px;">LAIN - LAIN</td>
            </tr>
            <tr><td colspan="2" style="height: 40px;"></td></tr>
        </table>

        <!-- Kaji Ulang Section -->
        <div style="border: 1px solid #000; border-top: none; margin-bottom: 10px;">
            <div class="text-center fw-bold" style="border-bottom: 1px solid #000; padding: 6px; background: #f9f9f9;">KAJI ULANG PERMINTAAN PEMERIKSAAN</div>
            <table style="border: none;">
                <tr>
                    <td style="border: none; width: 33%; vertical-align: top; padding: 10px;">
                        <div style="margin-bottom: 5px;">Kelayakan Sampel :</div>
                        <div><span style="font-size:14px;">&#9744;</span> Layak</div>
                        <div><span style="font-size:14px;">&#9744;</span> Tidak Layak</div>
                        <div style="margin-top: 25px;">Waktu Pengambilan Sampel : .................</div>
                        <div style="font-size: 9pt; margin-top: 15px;">*Di isi Petugas Penerima Sampel</div>
                    </td>
                    <td style="border: none; width: 33%; vertical-align: top; border-left: 1px solid #000; padding: 10px;">
                        <div style="margin-bottom: 5px;">Alasan :</div>
                        <div><span style="font-size:14px;">&#9744;</span> Tidak Steril</div>
                        <div><span style="font-size:14px;">&#9744;</span> Volume Tidak Mencukupi</div>
                        <div><span style="font-size:14px;">&#9744;</span> Bahan Tidak Sesuai Permintaan</div>
                        <div><span style="font-size:14px;">&#9744;</span> Waktu Tunggu Tidak Sesuai</div>
                        <div style="margin-top: 5px;">Jam : .................</div>
                    </td>
                    <td style="border: none; width: 34%; vertical-align: top; border-left: 1px solid #000; padding: 10px;">
                        <div style="margin-bottom: 5px;">Jumlah Biaya :</div>
                        <div style="text-align: left; padding-left: 15px;">Rp ............................................</div>
                        <div style="margin-top: 15px;"><span style="font-size:14px;">&#9744;</span> Cash / Tunai</div>
                        <div><span style="font-size:14px;">&#9744;</span> Lain - lain .....................................</div>
                        <div style="font-size: 9pt; margin-top: 10px;">*Di isi Petugas Pendaftaran</div>
                    </td>
                </tr>
            </table>
        </div>

        <!-- Disclaimers / TTD -->
        <div style="font-size: 10pt; padding: 0 5px;">
            <div style="float: left; width: 65%; line-height: 1.4;">
                Dengan ini saya menyatakan bahwa informasi yang saya<br>
                berikan adalah benar dan saya memahami serta menyetujui<br>
                biaya dan tindakan pemeriksaan yang dilakukan oleh<br>
                UPTD Labkes Kota Pangkalpinang untuk pemeriksaan sampel
            </div>
            <div style="float: right; width: 30%; text-align: center;">
                Tgl : .................................<br><br><br><br>
                ( <?= !empty($pendaftaran->ttd) ? htmlspecialchars($pendaftaran->ttd) : (!empty($pendaftaran->nama) ? htmlspecialchars($pendaftaran->nama) : '.....................................') ?> )
            </div>
            <div style="clear: both;"></div>
        </div>

    </div>
</body>
</html>
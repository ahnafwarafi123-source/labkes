<?php
// =================================================================================
// Konfigurasi dan Koneksi Database
// =================================================================================
$db_host = '127.0.0.1';
$db_name = getenv('DB_NAME') ?: ($_GET['db'] ?? 'lebkes_db');
$db_user = 'root';
$db_pass = '';
$db_charset = 'utf8mb4';

$dsn = "mysql:host=$db_host;dbname=$db_name;charset=$db_charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $db_user, $db_pass, $options);
} catch (\PDOException $e) {
    // Fallback jika lebkes_db gagal, coba kesmas_new
    try {
        $pdo = new PDO("mysql:host=$db_host;dbname=kesmas_new;charset=$db_charset", $db_user, $db_pass, $options);
        $db_name = 'kesmas_new';
    } catch (\PDOException $e2) {
        die("Koneksi Database Gagal: " . $e->getMessage());
    }
}

// =================================================================================
// Penentuan Periode Bulan & Tahun
// =================================================================================
// Default ke bulan September 2026 atau bulan yang diminta via parameter GET
$bulan_input = $_GET['bulan'] ?? '2026-09';
if (!preg_match('/^\d{4}-\d{2}$/', $bulan_input)) {
    $bulan_filter = '2026-09';
} else {
    $bulan_filter = $bulan_input;
}

$tahun_filter = (int)substr($bulan_filter, 0, 4);
$bulan_num = (int)substr($bulan_filter, 5, 2);

$nama_bulan_id = [
    1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
    5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
    9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
];

$nama_bulan_filter = ($nama_bulan_id[$bulan_num] ?? 'September') . ' ' . $tahun_filter;
$nama_bulan_murni = $nama_bulan_id[$bulan_num] ?? 'September';

$month_expr = "COALESCE(
    DATE_FORMAT(ps.tgl_pelaporan, '%Y-%m'), 
    DATE_FORMAT(ps.tgl_selesai, '%Y-%m'), 
    DATE_FORMAT(p.tgl_permintaan, '%Y-%m')
)";

// Helper: Penentuan MS / TMS
function isMemenuhiSyarat($hasil, $baku_mutu) {
    if ($hasil === null || $baku_mutu === null || $baku_mutu === '' || $baku_mutu === '-') return true;

    $hasil_str = strtolower(trim((string)$hasil));
    $baku_mutu_str = strtolower(trim((string)$baku_mutu));

    $baku_mutu_str = preg_replace('/(?<=\d),(?=\d)/', '.', $baku_mutu_str);
    $hasil_str = preg_replace('/(?<=\d),(?=\d)/', '.', $hasil_str);

    if (preg_match('/\b(maks|maksimum)\b\s*[:\-\s]*([0-9\.,]+)/i', $baku_mutu_str, $m_maks)) {
        $limit = (float)str_replace(',', '.', $m_maks[2]);
        if (preg_match('/-?\d+[\d\.]*?/', $hasil_str, $hm)) {
            $val = (float)$hm[0];
            return $val <= $limit;
        }
    }

    if (preg_match('/([<>≤≥]=?|=)\s*([0-9\.]+)/u', $baku_mutu_str, $m)) {
        $op = $m[1];
        $op = str_replace(['≤','≥'], ['<=','>='], $op);
        $limit = (float)$m[2];
        if (preg_match('/-?\d+[\d\.]*?/', $hasil_str, $hm)) {
            $val = (float)$hm[0];
            switch ($op) {
                case '<': return $val < $limit;
                case '<=': return $val <= $limit;
                case '>': return $val > $limit;
                case '>=': return $val >= $limit;
                case '=': return abs($val - $limit) < 1e-9;
            }
        }
    }

    if (preg_match('/([0-9\.]+)\s*[-–]\s*([0-9\.]+)/u', $baku_mutu_str, $m2)) {
        $low = (float)$m2[1];
        $high = (float)$m2[2];
        if (preg_match('/-?\d+[\d\.]*?/', $hasil_str, $hm2)) {
            $val = (float)$hm2[0];
            return $val >= $low && $val <= $high;
        }
    }

    if (preg_match('/\b0\b/', $baku_mutu_str) || strpos($baku_mutu_str, 'negatif') !== false) {
        if (preg_match('/\d+/', $hasil_str, $hm3)) {
            $val = (int)$hm3[0];
            return $val === 0;
        }
        if (strpos($hasil_str, 'positif') !== false) return false;
        return true;
    }

    if (strpos($baku_mutu_str, 'tidak berbau') !== false || strpos($baku_mutu_str, 'tidak bau') !== false) {
        return strpos($hasil_str, 'bau') === false && strpos($hasil_str, 'berbau') === false;
    }

    if (strpos($baku_mutu_str, 'negatif') !== false || strpos($baku_mutu_str, 'tidak ada') !== false) {
        if (strpos($hasil_str, 'positif') !== false || preg_match('/\d+/', $hasil_str)) return false;
        return true;
    }

    return true;
}

// =================================================================================
// 1. Data Jumlah Pelanggan / Sampel per Kategori (4 Kategori Standar)
// =================================================================================
$sql_sampel = "SELECT 
    CASE 
        WHEN p.kategori_sampel = 'Air' AND (p.jenis_sampel LIKE '%Minum%' OR p.jenis_sampel LIKE '%DAM%') THEN 'Air Minum'
        WHEN p.kategori_sampel = 'Air' THEN 'Air Hygiene'
        WHEN p.kategori_sampel = 'Makanan' OR p.jenis_sampel LIKE '%Makanan%' OR p.jenis_sampel LIKE '%Nasi%' THEN 'Makanan'
        ELSE 'Lingkungan'
    END AS kategori_buku,
    COUNT(DISTINCT p.id_permintaan) AS total
FROM kesmas_permintaan p
JOIN kesmas_penerimaan_sampel r ON r.id_permintaan = p.id_permintaan
JOIN kesmas_pembayaran b ON b.no_registrasi = r.no_registrasi
JOIN kesmas_pengujian_sampel ps ON ps.id_pembayaran = b.id_pembayaran
JOIN kesmas_hasil_pengujian h ON h.id_pengujian = ps.id_pengujian
WHERE {$month_expr} = ?
GROUP BY kategori_buku";

$stmt_sampel = $pdo->prepare($sql_sampel);
$stmt_sampel->execute([$bulan_filter]);
$rows_sampel = $stmt_sampel->fetchAll();

$data_sampel = [
    'Air Minum' => 0,
    'Air Hygiene' => 0,
    'Makanan' => 0,
    'Lingkungan' => 0,
];
$total_sampel_kesmas = 0;
foreach ($rows_sampel as $rs) {
    $k = $rs['kategori_buku'];
    if (isset($data_sampel[$k])) {
        $data_sampel[$k] = (int)$rs['total'];
        $total_sampel_kesmas += (int)$rs['total'];
    }
}

// =================================================================================
// 2. Data Pemeriksaan Berdasarkan Wilayah Puskesmas
// =================================================================================
$puskesmas_master = [
    ['no' => 1, 'kecamatan' => 'GIRIMAYA', 'pkm' => 'GIRIMAYA'],
    ['no' => 2, 'kecamatan' => 'RANGKUI', 'pkm' => 'MELINTANG'],
    ['no' => '', 'kecamatan' => '', 'pkm' => 'PASIR PUTIH'],
    ['no' => 3, 'kecamatan' => 'PANGKALBALAM', 'pkm' => 'PANGKALBALAM'],
    ['no' => 4, 'kecamatan' => 'TAMANSARI', 'pkm' => 'TAMANSARI'],
    ['no' => '', 'kecamatan' => '', 'pkm' => 'PKM KAPEDA'],
    ['no' => 5, 'kecamatan' => 'GERUNGGANG', 'pkm' => 'GERUNGGANG'],
    ['no' => 6, 'kecamatan' => 'BUKIT INTAN', 'pkm' => 'AIR ITAM'],
    ['no' => 7, 'kecamatan' => 'GABEK', 'pkm' => 'SELINDUNG'],
];

$sql_pkm = "SELECT 
    UPPER(TRIM(COALESCE(pl.kecamatan, ''))) as kecamatan,
    UPPER(TRIM(COALESCE(pl.kelurahan, ''))) as kelurahan,
    CASE 
        WHEN p.kategori_sampel = 'Air' AND (p.jenis_sampel LIKE '%Minum%' OR p.jenis_sampel LIKE '%DAM%') THEN 'Air Minum'
        WHEN p.kategori_sampel = 'Air' THEN 'Air Hygiene'
        WHEN p.kategori_sampel = 'Makanan' OR p.jenis_sampel LIKE '%Makanan%' OR p.jenis_sampel LIKE '%Nasi%' THEN 'Makanan'
        ELSE 'Lingkungan'
    END AS kategori_buku,
    COUNT(DISTINCT p.id_permintaan) as total
FROM kesmas_permintaan p
JOIN kesmas_penerimaan_sampel r ON r.id_permintaan = p.id_permintaan
JOIN kesmas_pembayaran b ON b.no_registrasi = r.no_registrasi
JOIN kesmas_pengujian_sampel ps ON ps.id_pembayaran = b.id_pembayaran
JOIN kesmas_hasil_pengujian h ON h.id_pengujian = ps.id_pengujian
LEFT JOIN kesmas_pelanggan pl ON pl.id_pelanggan = p.id_pelanggan
WHERE {$month_expr} = ?
GROUP BY kecamatan, kelurahan, kategori_buku";
$stmt_pkm = $pdo->prepare($sql_pkm);
$stmt_pkm->execute([$bulan_filter]);
$rows_pkm = $stmt_pkm->fetchAll();

$pkm_data = [];
foreach ($puskesmas_master as $pm) {
    $pkm_data[$pm['pkm']] = [
        'Air Minum' => 0,
        'Air Hygiene' => 0,
        'Makanan' => 0,
        'Lingkungan' => 0,
        'total' => 0
    ];
}

// Distribusikan data jika ada kecamatan/kelurahan terpetakan
foreach ($rows_pkm as $rp) {
    $target_pkm = 'MELINTANG'; // default mapping labkes pangkalpinang
    $kec = $rp['kecamatan'];
    if (strpos($kec, 'GIRIMAYA') !== false) $target_pkm = 'GIRIMAYA';
    elseif (strpos($kec, 'RANGKUI') !== false) $target_pkm = 'MELINTANG';
    elseif (strpos($kec, 'PANGKALBALAM') !== false) $target_pkm = 'PANGKALBALAM';
    elseif (strpos($kec, 'TAMANSARI') !== false) $target_pkm = 'TAMANSARI';
    elseif (strpos($kec, 'GERUNGGANG') !== false) $target_pkm = 'GERUNGGANG';
    elseif (strpos($kec, 'BUKIT INTAN') !== false) $target_pkm = 'AIR ITAM';
    elseif (strpos($kec, 'GABEK') !== false) $target_pkm = 'SELINDUNG';

    $kat = $rp['kategori_buku'];
    $cnt = (int)$rp['total'];
    if (isset($pkm_data[$target_pkm][$kat])) {
        $pkm_data[$target_pkm][$kat] += $cnt;
        $pkm_data[$target_pkm]['total'] += $cnt;
    }
}

// =================================================================================
// 3. Matriks 12 Bulan Jumlah Pemeriksaan per Jenis Pemeriksaan
// =================================================================================
$sql_matrix = "SELECT 
    MONTH({$month_expr}) as bulan_num,
    m.id_master_pemeriksaan,
    m.nama_pemeriksaan,
    m.kategori,
    m.kelompok_pemeriksaan,
    COUNT(*) as total
FROM kesmas_hasil_pengujian h
JOIN kesmas_pengujian_sampel ps ON h.id_pengujian = ps.id_pengujian
JOIN kesmas_pembayaran b ON b.id_pembayaran = ps.id_pembayaran
JOIN kesmas_penerimaan_sampel r ON r.no_registrasi = b.no_registrasi
JOIN kesmas_permintaan p ON p.id_permintaan = r.id_permintaan
JOIN kesmas_master_pemeriksaan m ON m.id_master_pemeriksaan = h.id_master_pemeriksaan
WHERE YEAR({$month_expr}) = ?
  AND UPPER(TRIM(h.status)) IN ('MS', 'TMS')
GROUP BY bulan_num, m.id_master_pemeriksaan, m.nama_pemeriksaan, m.kategori, m.kelompok_pemeriksaan";

$stmt_matrix = $pdo->prepare($sql_matrix);
$stmt_matrix->execute([$tahun_filter]);
$rows_matrix = $stmt_matrix->fetchAll();

$matrix_by_month = [];
for ($m = 1; $m <= 12; $m++) {
    $matrix_by_month[$m] = [];
}
foreach ($rows_matrix as $rm) {
    $b = (int)$rm['bulan_num'];
    $matrix_by_month[$b][$rm['id_master_pemeriksaan']] = (int)$rm['total'];
}

// =================================================================================
// 4. Data Pemeriksaan MS dan TMS Bulan Terpilih (Sesuai Struktur PDF Hal. 6)
// =================================================================================
$parameter_structure = [
    'A.FISIKA' => [
        ['no' => 1, 'nama' => 'SUHU', 'alias' => ['suhu']],
        ['no' => 2, 'nama' => 'JUMLAH ZAT PADAT TERLARUT (TDS)', 'alias' => ['tds', 'jumlah zat padat terlarut']],
        ['no' => 3, 'nama' => 'KEKERUHAN', 'alias' => ['kekeruhan']],
        ['no' => 4, 'nama' => 'WARNA', 'alias' => ['warna']],
        ['no' => 5, 'nama' => 'BAU', 'alias' => ['bau']],
    ],
    'B.KIMIA' => [
        ['no' => 1, 'nama' => 'PH BUFFER', 'alias' => ['ph']],
        ['no' => 2, 'nama' => 'NITRAT, SEBAGAI NO3- TERLARUT', 'alias' => ['nitrat']],
        ['no' => 3, 'nama' => 'NITRIT, SEBAGAI NO2- TERLARUT', 'alias' => ['nitrit']],
        ['no' => 4, 'nama' => 'KROMIUM VALENSI 6 TERLARUT', 'alias' => ['kromium valensi 6', 'cr6+']],
        ['no' => 5, 'nama' => 'BESI TERLARUT', 'alias' => ['besi', 'fe']],
        ['no' => 6, 'nama' => 'MANGAN TERLARUT', 'alias' => ['mangan', 'mn']],
        ['no' => 7, 'nama' => 'SISA KLOR TERLARUT', 'alias' => ['sisa khlor', 'sisa klor']],
        ['no' => 8, 'nama' => 'ARSEN TERLARUT', 'alias' => ['arsen', 'as']],
        ['no' => 9, 'nama' => 'KADMIUM TERLARUT', 'alias' => ['kadmium', 'cd']],
        ['no' => 10, 'nama' => 'TIMBAL TERLARUT', 'alias' => ['timbal', 'pb']],
        ['no' => 11, 'nama' => 'FLOURIDE TERLARUT', 'alias' => ['fluorida', 'flouride', 'f']],
        ['no' => 12, 'nama' => 'ALUMUNIUM TERLARUT', 'alias' => ['aluminium', 'alumunium', 'al']],
        ['no' => 13, 'nama' => 'KLORIDA', 'alias' => ['klorida', 'cl']],
        ['no' => 14, 'nama' => 'SULFAT', 'alias' => ['sulfat', 'so4']],
        ['no' => 15, 'nama' => 'TOTAL KROMIUM', 'alias' => ['total kromium']],
        ['no' => 16, 'nama' => 'SIANIDA TERLARUT', 'alias' => ['sianida', 'cn']],
        ['no' => 17, 'nama' => 'TEMBAGA TERLARUT', 'alias' => ['tembaga', 'cu']],
        ['no' => 18, 'nama' => 'SELINIUM', 'alias' => ['selenium', 'selinium', 'se']],
        ['no' => 19, 'nama' => 'SENG TERLARUT', 'alias' => ['seng', 'zn']],
    ],
    'C. BAKTERIOLOGI' => [
        ['no' => 1, 'nama' => 'E. COLI', 'alias' => ['escherichia coli']],
        ['no' => 2, 'nama' => 'TOTAL COLIFORM', 'alias' => ['total coliform']],
    ],
    'MAKANAN' => [
        ['no' => 1, 'nama' => 'SAKARIN', 'alias' => ['sakarin']],
        ['no' => 2, 'nama' => 'SIKLAMAT', 'alias' => ['siklamat']],
        ['no' => 3, 'nama' => 'RHODHAMIN B', 'alias' => ['rhodamin b', 'rhodhamin b']],
        ['no' => 4, 'nama' => 'FORMALIN', 'alias' => ['formalin']],
        ['no' => 5, 'nama' => 'BORAX', 'alias' => ['boraks', 'borax']],
        ['no' => 6, 'nama' => 'METHANYL YELLOW', 'alias' => ['methanyl yellow']],
        ['no' => 7, 'nama' => 'E. COLI MAKANAN', 'alias' => ['escherichia coli makanan']],
    ],
    'LINGKUNGAN' => [
        ['no' => 8, 'nama' => 'USAP ALAT MAKAN', 'alias' => ['angka kuman usap alat makan', 'usap alat makan']],
        ['no' => 9, 'nama' => 'USAP ALAT MASAK', 'alias' => ['angka kuman usap alat masak', 'usap alat masak']],
    ],
];

// Ambil seluruh data hasil uji bulan terpilih
$sql_hasil = "SELECT 
    m.id_master_pemeriksaan,
    LOWER(TRIM(m.nama_pemeriksaan)) as nama_raw,
    m.kategori,
    m.kelompok_pemeriksaan,
    m.baku_mutu,
    h.hasil,
    UPPER(TRIM(COALESCE(h.status, ''))) as status_raw
FROM kesmas_hasil_pengujian h
JOIN kesmas_pengujian_sampel ps ON h.id_pengujian = ps.id_pengujian
JOIN kesmas_pembayaran b ON b.id_pembayaran = ps.id_pembayaran
JOIN kesmas_penerimaan_sampel r ON r.no_registrasi = b.no_registrasi
JOIN kesmas_permintaan p ON p.id_permintaan = r.id_permintaan
JOIN kesmas_master_pemeriksaan m ON m.id_master_pemeriksaan = h.id_master_pemeriksaan
WHERE {$month_expr} = ?";
$stmt_hasil = $pdo->prepare($sql_hasil);
$stmt_hasil->execute([$bulan_filter]);
$rows_hasil = $stmt_hasil->fetchAll();

// Olah data MS / TMS berdasarkan pencocokan parameter
$data_ms_tms_table = [];
$total_ms_all = 0;
$total_tms_all = 0;
$total_uji_all = 0;

foreach ($parameter_structure as $kategori_title => $items) {
    $data_ms_tms_table[$kategori_title] = [];
    foreach ($items as $item) {
        $ms_count = 0;
        $tms_count = 0;

        foreach ($rows_hasil as $rh) {
            $matched = false;
            foreach ($item['alias'] as $al) {
                if (strpos($rh['nama_raw'], $al) !== false) {
                    // Bedakan E.coli air vs E.coli makanan
                    if ($item['nama'] === 'E. COLI' && (strpos($rh['nama_raw'], 'makanan') !== false || $rh['kategori'] === 'Makanan')) {
                        continue;
                    }
                    if ($item['nama'] === 'E. COLI MAKANAN' && strpos($rh['nama_raw'], 'makanan') === false && $rh['kategori'] !== 'Makanan') {
                        continue;
                    }
                    $matched = true;
                    break;
                }
            }

            if ($matched) {
                $status = $rh['status_raw'];
                if ($status === 'MS') {
                    $ms_count++;
                } elseif ($status === 'TMS') {
                    $tms_count++;
                } else {
                    if (isMemenuhiSyarat($rh['hasil'], $rh['baku_mutu'])) {
                        $ms_count++;
                    } else {
                        $tms_count++;
                    }
                }
            }
        }

        $row_total = $ms_count + $tms_count;
        $data_ms_tms_table[$kategori_title][] = [
            'no' => $item['no'],
            'nama' => $item['nama'],
            'ms' => $ms_count,
            'tms' => $tms_count,
            'total' => $row_total
        ];

        $total_ms_all += $ms_count;
        $total_tms_all += $tms_count;
        $total_uji_all += $row_total;
    }
}

// =================================================================================
// 5. 10 Pemeriksaan Terbanyak Sampel Lab Kesmas
// =================================================================================
$sql_top10 = "SELECT m.nama_pemeriksaan, COUNT(h.id_master_pemeriksaan) as total 
              FROM kesmas_hasil_pengujian h
              JOIN kesmas_pengujian_sampel ps ON h.id_pengujian = ps.id_pengujian
              JOIN kesmas_pembayaran b ON b.id_pembayaran = ps.id_pembayaran
              JOIN kesmas_penerimaan_sampel r ON r.no_registrasi = b.no_registrasi
              JOIN kesmas_permintaan p ON p.id_permintaan = r.id_permintaan
              JOIN kesmas_master_pemeriksaan m ON m.id_master_pemeriksaan = h.id_master_pemeriksaan
              WHERE {$month_expr} = ?
                AND UPPER(TRIM(h.status)) IN ('MS', 'TMS')
              GROUP BY m.id_master_pemeriksaan, m.nama_pemeriksaan
              ORDER BY total DESC, m.nama_pemeriksaan ASC
              LIMIT 10";
$stmt_top10 = $pdo->prepare($sql_top10);
$stmt_top10->execute([$bulan_filter]);
$data_top10 = $stmt_top10->fetchAll();

// =================================================================================
// 6. Data Pemeriksaan Lab Kesmas Per Obyek (7 Kategori Standar)
// =================================================================================
$obyek_categories = [
    'Depot Air Minum (DAM)' => 0,
    'PDAM' => 0,
    'Masyarakat' => 0,
    'Rumah Sakit/Klinik' => 0,
    'Industri' => 0,
    'Restoran/Rumah Makan/Catering' => 0,
    'Hotel' => 0
];

$sql_obyek = "SELECT 
    CASE 
        WHEN LOWER(COALESCE(pl.objek, pl.instansi, '')) LIKE '%depot%' OR LOWER(COALESCE(pl.objek, pl.instansi, '')) LIKE '%dam%' THEN 'Depot Air Minum (DAM)'
        WHEN LOWER(COALESCE(pl.objek, pl.instansi, '')) LIKE '%pdam%' THEN 'PDAM'
        WHEN LOWER(COALESCE(pl.objek, pl.instansi, '')) LIKE '%rs%' OR LOWER(COALESCE(pl.objek, pl.instansi, '')) LIKE '%rumah sakit%' OR LOWER(COALESCE(pl.objek, pl.instansi, '')) LIKE '%klinik%' OR LOWER(COALESCE(pl.objek, pl.instansi, '')) LIKE '%puskesmas%' THEN 'Rumah Sakit/Klinik'
        WHEN LOWER(COALESCE(pl.objek, pl.instansi, '')) LIKE '%industri%' OR LOWER(COALESCE(pl.objek, pl.instansi, '')) LIKE '%pabrik%' THEN 'Industri'
        WHEN LOWER(COALESCE(pl.objek, pl.instansi, '')) LIKE '%restoran%' OR LOWER(COALESCE(pl.objek, pl.instansi, '')) LIKE '%rumah makan%' OR LOWER(COALESCE(pl.objek, pl.instansi, '')) LIKE '%catering%' OR LOWER(COALESCE(pl.objek, pl.instansi, '')) LIKE '%katering%' THEN 'Restoran/Rumah Makan/Catering'
        WHEN LOWER(COALESCE(pl.objek, pl.instansi, '')) LIKE '%hotel%' THEN 'Hotel'
        ELSE 'Masyarakat'
    END AS obyek,
    COUNT(DISTINCT p.id_permintaan) as total
FROM kesmas_permintaan p
JOIN kesmas_penerimaan_sampel r ON r.id_permintaan = p.id_permintaan
JOIN kesmas_pembayaran b ON b.no_registrasi = r.no_registrasi
JOIN kesmas_pengujian_sampel ps ON ps.id_pembayaran = b.id_pembayaran
JOIN kesmas_hasil_pengujian h ON h.id_pengujian = ps.id_pengujian
LEFT JOIN kesmas_pelanggan pl ON pl.id_pelanggan = p.id_pelanggan
WHERE {$month_expr} = ?
GROUP BY obyek";

$stmt_obyek = $pdo->prepare($sql_obyek);
$stmt_obyek->execute([$bulan_filter]);
$rows_obyek = $stmt_obyek->fetchAll();

$total_obyek_all = 0;
foreach ($rows_obyek as $ro) {
    if (isset($obyek_categories[$ro['obyek']])) {
        $obyek_categories[$ro['obyek']] += (int)$ro['total'];
        $total_obyek_all += (int)$ro['total'];
    }
}

// =================================================================================
// 7. Data Pemeriksaan yang di Rujuk
// =================================================================================
$sql_rujukan = "SELECT r.no_registrasi, p.jenis_sampel, COUNT(h.id_master_pemeriksaan) as jml_parameter
                FROM kesmas_permintaan p
                JOIN kesmas_penerimaan_sampel r ON r.id_permintaan = p.id_permintaan
                JOIN kesmas_pembayaran b ON b.no_registrasi = r.no_registrasi
                JOIN kesmas_pengujian_sampel ps ON ps.id_pembayaran = b.id_pembayaran
                JOIN kesmas_hasil_pengujian h ON h.id_pengujian = ps.id_pengujian
                JOIN kesmas_master_pemeriksaan m ON m.id_master_pemeriksaan = h.id_master_pemeriksaan
                WHERE {$month_expr} = ? 
                  AND (LOWER(m.nama_pemeriksaan) LIKE '%arsen%' OR LOWER(m.nama_pemeriksaan) LIKE '%timbal%' OR LOWER(m.nama_pemeriksaan) LIKE '%kadmium%')
                GROUP BY r.no_registrasi, p.jenis_sampel";
$stmt_rujukan = $pdo->prepare($sql_rujukan);
$stmt_rujukan->execute([$bulan_filter]);
$data_rujukan = $stmt_rujukan->fetchAll();

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Hasil Pemeriksaan Laboratorium Kesmas - <?= $nama_bulan_filter ?></title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 11pt;
            line-height: 1.5;
            color: #111;
            background: #e2e8f0;
            margin: 0;
            padding: 20px 0;
        }
        @media print {
            body { background: transparent; padding: 0; }
            @page { size: 215mm 330mm; margin: 12mm 15mm; }
            .page { page-break-after: always; margin: 0 !important; border: none !important; box-shadow: none !important; width: 100% !important; padding: 0 !important; }
            .no-print { display: none !important; }
        }
        .page {
            width: 215mm;
            min-height: 330mm;
            padding: 15mm 18mm;
            box-sizing: border-box;
            margin: 15px auto;
            background: white;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            position: relative;
        }
        .page-landscape {
            width: 330mm;
            min-height: 215mm;
            padding: 15mm 15mm;
        }
        h1, h2, h3, h4 { text-align: center; font-weight: bold; margin: 0; }
        h1 { font-size: 15pt; line-height: 1.4; }
        h2 { font-size: 13pt; margin-bottom: 8px; }
        h3 { font-size: 11pt; margin-bottom: 6px; }
        .text-center { text-align: center; }
        .text-left { text-align: left; }
        .text-right { text-align: right; }
        .fw-bold { font-weight: bold; }
        
        .table-custom {
            border-collapse: collapse;
            width: 100%;
            margin-top: 14px;
            margin-bottom: 14px;
            font-size: 10pt;
        }
        .table-custom th, .table-custom td {
            border: 1px solid #333;
            padding: 5px 8px;
        }
        .table-custom th {
            background-color: #f1f5f9;
            text-align: center;
            font-weight: bold;
        }
        .table-custom tfoot th {
            background-color: #e2e8f0;
        }
        .chart-container {
            width: 100%;
            max-width: 650px;
            margin: 15px auto;
        }
        .button-bar {
            text-align: center;
            margin-bottom: 20px;
        }
        .btn-print {
            background: #0284c7;
            color: #fff;
            border: none;
            padding: 10px 24px;
            font-size: 14px;
            font-weight: 600;
            border-radius: 8px;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(2, 132, 199, 0.3);
        }
        .btn-print:hover { background: #0369a1; }
        .select-filter {
            padding: 8px 12px;
            border-radius: 6px;
            border: 1px solid #cbd5e1;
            font-weight: 600;
            margin-right: 8px;
        }
        .signature-table {
            width: 100%;
            margin-top: 30px;
            font-size: 10pt;
            border: none;
        }
        .signature-table td {
            border: none;
            padding: 6px 12px;
            text-align: center;
            vertical-align: top;
        }
    </style>
</head>
<body>

<div class="no-print button-bar">
    <form method="GET" action="" style="display:inline-block;">
        <label class="fw-bold" style="margin-right:6px;">Pilih Bulan:</label>
        <input type="month" name="bulan" value="<?= $bulan_filter ?>" class="select-filter">
        <button type="submit" class="btn-print" style="padding: 8px 16px; background:#475569;">Tampilkan</button>
    </form>
    <button class="btn-print" onclick="window.print()" style="margin-left: 12px;">
        <i class="fas fa-print"></i> Cetak Laporan (PDF / Print)
    </button>
</div>

<!-- ========================================================================= -->
<!-- HALAMAN 1: COVER                                                          -->
<!-- ========================================================================= -->
<div class="page" style="display:flex; flex-direction:column; justify-content:center; align-items:center; text-align:center;">
    <div style="margin-top: -60px;">
        <h1 style="letter-spacing: 1px;">LAPORAN HASIL PEMERIKSAAN LABORATORIUM KESMAS</h1>
        <h1 style="letter-spacing: 1px; margin-top: 10px;">UPTD. LABORATORIUM KESEHATAN</h1>
        <h1 style="letter-spacing: 1px; margin-top: 5px;">KOTA PANGKAL PINANG</h1>
        <h2 style="margin-top: 35px; font-size: 14pt;"><?= strtoupper($nama_bulan_filter) ?></h2>
    </div>

    <div style="margin-top: 80px; margin-bottom: 80px;">
        <img src="assets/img/logo.png" alt="Logo" style="width: 140px; height: auto;" onerror="this.style.display='none'"/>
    </div>
</div>

<!-- ========================================================================= -->
<!-- HALAMAN 2: BAB I PENDAHULUAN                                              -->
<!-- ========================================================================= -->
<div class="page">
    <h2 style="margin-bottom: 25px;">BAB I<br>PENDAHULUAN</h2>

    <h3 class="text-left" style="margin-top: 20px;">1.1 Latar Belakang</h3>
    <p style="text-align: justify; text-indent: 40px; line-height: 1.6; margin-top: 8px;">
        Kota Pangkal Pinang, sebagai ibukota Provinsi Kepulauan Bangka Belitung, terus berupaya meningkatkan kualitas kesehatan masyarakatnya. Salah satu langkah penting dalam upaya ini adalah pemantauan dan evaluasi kesehatan masyarakat melalui pemeriksaan laboratorium yang komprehensif. UPTD Laboratorium Kesehatan Kota Pangkalpinang berperan sebagai fasilitas utama dalam melaksanakan pemeriksaan tersebut, mencakup berbagai aspek kesehatan masyarakat seperti penyakit menular, kualitas air dan makanan, serta faktor lingkungan yang mempengaruhi kesehatan.
    </p>

    <h3 class="text-left" style="margin-top: 20px;">1.2 Tujuan</h3>
    <p style="margin-top: 8px; margin-bottom: 4px;">Pemeriksaan laboratorium kesehatan masyarakat ini bertujuan untuk:</p>
    <ol style="margin-top: 4px; padding-left: 24px; line-height: 1.6;">
        <li>Mendeteksi dan mengidentifikasi potensi masalah kesehatan di masyarakat.</li>
        <li>Memberikan data akurat sebagai dasar pengambilan kebijakan kesehatan oleh pemerintah daerah.</li>
        <li>Memantau tren kesehatan masyarakat dan efektivitas program-program kesehatan yang telah diimplementasikan.</li>
        <li>Mendukung upaya preventif dan promotif dalam sistem kesehatan masyarakat.</li>
    </ol>

    <h3 class="text-left" style="margin-top: 20px;">1.3 Manfaat</h3>
    <p style="text-align: justify; text-indent: 40px; line-height: 1.6; margin-top: 8px;">
        Sebagai acuan data surveilens berbasis laboratorium dan evaluasi pemantapan mutu UPTD. Laboratorium Kesehatan Kota Pangkal Pinang.
    </p>

    <h3 class="text-left" style="margin-top: 20px;">1.4 Ruang Lingkup Kegiatan</h3>
    <p style="margin-top: 8px; margin-bottom: 4px;">Laporan ini mencakup hasil pemeriksaan laboratorium Kesmas dalam periode Bulan <?= $nama_bulan_filter ?> meliputi:</p>
    <ol style="margin-top: 4px; padding-left: 24px; line-height: 1.6;">
        <li>Pemeriksaan Air Minum</li>
        <li>Pemeriksaan Air Hygiene</li>
        <li>Pemeriksaan mikrobiologi air dan makanan</li>
        <li>Pemeriksaan Makanan</li>
        <li>Pemeriksaan Lingkungan (Usap Alat Masak dan Usap Alat Makan)</li>
    </ol>
</div>

<!-- ========================================================================= -->
<!-- HALAMAN 3: BAB II - 1. JUMLAH PELANGGAN / SAMPEL                          -->
<!-- ========================================================================= -->
<div class="page">
    <h2>BAB II<br>LAPORAN BULANAN JUMLAH PEMERIKSAAN LABORATORIUM KESEHATAN MASYARAKAT</h2>
    <p class="text-center fw-bold" style="margin-top: 5px; margin-bottom: 25px;">Periode: <?= $nama_bulan_filter ?></p>

    <h3 class="text-left">1. Jumlah Pelanggan/Pengguna Jasa Laboratorium Kesmas Bulan <?= $nama_bulan_murni ?> Tahun <?= $tahun_filter ?></h3>

    <div style="max-width: 400px; margin: 20px auto;">
        <table class="table-custom">
            <thead>
                <tr>
                    <th width="15%">No</th>
                    <th>Sampel</th>
                    <th width="25%">Jumlah</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="text-center">1</td>
                    <td>Air Minum</td>
                    <td class="text-center"><?= $data_sampel['Air Minum'] ?></td>
                </tr>
                <tr>
                    <td class="text-center">2</td>
                    <td>Air Hygiene</td>
                    <td class="text-center"><?= $data_sampel['Air Hygiene'] ?></td>
                </tr>
                <tr>
                    <td class="text-center">3</td>
                    <td>Makanan</td>
                    <td class="text-center"><?= $data_sampel['Makanan'] ?></td>
                </tr>
                <tr>
                    <td class="text-center">4</td>
                    <td>Lingkungan</td>
                    <td class="text-center"><?= $data_sampel['Lingkungan'] ?></td>
                </tr>
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="2" class="text-center">Total</th>
                    <th class="text-center"><?= $total_sampel_kesmas ?></th>
                </tr>
            </tfoot>
        </table>
    </div>

    <div class="chart-container" style="margin-top: 30px;">
        <p class="text-center fw-bold" style="font-size: 10pt; margin-bottom: 10px;">
            Jumlah Pelanggan/Pengguna Jasa Laboratorium Kesmas Bulan <?= $nama_bulan_murni ?> Tahun <?= $tahun_filter ?>
        </p>
        <canvas id="chartPelanggan" height="220"></canvas>
    </div>
</div>

<!-- ========================================================================= -->
<!-- HALAMAN 4: 2. DATA PEMERIKSAAN BERDASARKAN WILAYAH PUSKESMAS              -->
<!-- ========================================================================= -->
<div class="page">
    <h3 class="text-left" style="margin-bottom: 15px;">2. Data Pemeriksaan Berdasarkan Wilayah Puskesmas Bulan <?= $nama_bulan_filter ?></h3>

    <table class="table-custom" style="font-size: 9.5pt;">
        <thead>
            <tr>
                <th width="6%">No</th>
                <th width="22%">KECAMATAN</th>
                <th width="22%">PUSKESMAS</th>
                <th width="12%">AIR MINUM</th>
                <th width="12%">AIR HYGIENE</th>
                <th width="12%">MAKANAN</th>
                <th width="12%">LINGKUNGAN</th>
                <th width="12%">JUMLAH</th>
            </tr>
        </thead>
        <tbody>
            <?php 
                $tot_am = 0; $tot_ah = 0; $tot_mak = 0; $tot_ling = 0; $tot_pkm_grand = 0;
                foreach($puskesmas_master as $idx => $pm): 
                    $d = $pkm_data[$pm['pkm']];
                    $tot_am += $d['Air Minum'];
                    $tot_ah += $d['Air Hygiene'];
                    $tot_mak += $d['Makanan'];
                    $tot_ling += $d['Lingkungan'];
                    $tot_pkm_grand += $d['total'];
            ?>
            <tr>
                <td class="text-center"><?= $pm['no'] ?></td>
                <td class="fw-bold"><?= $pm['kecamatan'] ?></td>
                <td><?= $pm['pkm'] ?></td>
                <td class="text-center"><?= $d['Air Minum'] ?></td>
                <td class="text-center"><?= $d['Air Hygiene'] ?></td>
                <td class="text-center"><?= $d['Makanan'] ?></td>
                <td class="text-center"><?= $d['Lingkungan'] ?></td>
                <td class="text-center fw-bold"><?= $d['total'] ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr>
                <th colspan="3" class="text-center">TOTAL</th>
                <th class="text-center"><?= $tot_am ?></th>
                <th class="text-center"><?= $tot_ah ?></th>
                <th class="text-center"><?= $tot_mak ?></th>
                <th class="text-center"><?= $tot_ling ?></th>
                <th class="text-center"><?= $tot_pkm_grand ?></th>
            </tr>
        </tfoot>
    </table>

    <div class="chart-container" style="margin-top: 30px;">
        <p class="text-center fw-bold" style="font-size: 10pt; margin-bottom: 10px;">
            Data Pemeriksaan Berdasarkan Wilayah Puskesmas Bulan <?= $nama_bulan_filter ?>
        </p>
        <canvas id="chartPuskesmas" height="230"></canvas>
    </div>
</div>

<!-- ========================================================================= -->
<!-- HALAMAN 5: 3. TABEL JUMLAH PEMERIKSAAN PER JENIS (MATRIKS 12 BULAN)       -->
<!-- ========================================================================= -->
<div class="page page-landscape">
    <h3 class="text-left" style="margin-bottom: 12px;">3. Tabel Jumlah Pemeriksaan per Jenis Pemeriksaan Tahun <?= $tahun_filter ?></h3>

    <div style="overflow-x: auto;">
        <table class="table-custom" style="font-size: 8pt; white-space: nowrap;">
            <thead>
                <tr>
                    <th rowspan="2" width="3%">NO</th>
                    <th rowspan="2" width="10%">BULAN</th>
                    <th colspan="5">FISIK</th>
                    <th colspan="12">KIMIA</th>
                    <th colspan="3">MIKROBIOLOGI</th>
                    <th colspan="3">MAKANAN & LINGKUNGAN</th>
                    <th rowspan="2" width="6%">TOTAL</th>
                </tr>
                <tr>
                    <th>SUHU</th><th>TDS</th><th>KEKERUHAN</th><th>WARNA</th><th>BAU</th>
                    <th>PH</th><th>NO3</th><th>NO2</th><th>CR6+</th><th>FE</th><th>MN</th><th>SISA KLOR</th><th>AS</th><th>CD</th><th>PB</th><th>F</th><th>AL</th>
                    <th>E.COLI</th><th>COLIFORM</th><th>ALT</th>
                    <th>FORMALIN</th><th>BORAKS</th><th>USAP ALAT</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                    $grand_total_row = 0;
                    $col_totals = array_fill(0, 23, 0);

                    for ($m = 1; $m <= 12; $m++): 
                        $m_data = $matrix_by_month[$m];
                        $c = [
                            $m_data[1] ?? 0, $m_data[2] ?? 0, $m_data[3] ?? 0, $m_data[4] ?? 0, $m_data[5] ?? 0,
                            $m_data[6] ?? 0, $m_data[7] ?? 0, $m_data[8] ?? 0, $m_data[9] ?? 0, $m_data[10] ?? 0,
                            $m_data[11] ?? 0, $m_data[12] ?? 0, $m_data[13] ?? 0, $m_data[14] ?? 0, $m_data[15] ?? 0,
                            $m_data[16] ?? 0, $m_data[17] ?? 0,
                            $m_data[30] ?? 0, $m_data[31] ?? 0, $m_data[32] ?? 0,
                            $m_data[53] ?? 0, $m_data[52] ?? 0, ($m_data[64] ?? 0) + ($m_data[74] ?? 0)
                        ];
                        $row_sum = array_sum($c);
                        $grand_total_row += $row_sum;
                        for($ci=0; $ci<count($c); $ci++) $col_totals[$ci] += $c[$ci];
                ?>
                <tr>
                    <td class="text-center"><?= $m ?></td>
                    <td class="fw-bold"><?= strtoupper($nama_bulan_id[$m]) ?></td>
                    <?php foreach($c as $val): ?>
                        <td class="text-center"><?= $val ?></td>
                    <?php endforeach; ?>
                    <td class="text-center fw-bold"><?= $row_sum ?></td>
                </tr>
                <?php endfor; ?>
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="2" class="text-center">TOTAL SAMPEL</th>
                    <?php foreach($col_totals as $ct): ?>
                        <th class="text-center"><?= $ct ?></th>
                    <?php endforeach; ?>
                    <th class="text-center"><?= $grand_total_row ?></th>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

<!-- ========================================================================= -->
<!-- HALAMAN 6: 4. PEMERIKSAAN MS DAN TMS                                      -->
<!-- ========================================================================= -->
<div class="page">
    <h3 style="margin-bottom: 12px;">Pemeriksaan Yang Memenuhi Syarat dan Tidak Memenuhi Syarat Bulan <?= $nama_bulan_filter ?></h3>

    <table class="table-custom" style="font-size: 8.5pt;">
        <thead>
            <tr>
                <th width="8%">NO</th>
                <th class="text-left">PEMERIKSAAN</th>
                <th width="15%">MS</th>
                <th width="15%">TMS</th>
                <th width="15%">Total</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($data_ms_tms_table as $kat_title => $items): ?>
                <tr style="background:#f8fafc;">
                    <td colspan="5" class="fw-bold" style="padding-left:10px;"><?= $kat_title ?></td>
                </tr>
                <?php foreach ($items as $it): ?>
                <tr>
                    <td class="text-center"><?= $it['no'] ?></td>
                    <td><?= $it['nama'] ?></td>
                    <td class="text-center"><?= $it['ms'] ?></td>
                    <td class="text-center"><?= $it['tms'] ?></td>
                    <td class="text-center fw-bold"><?= $it['total'] ?></td>
                </tr>
                <?php endforeach; ?>
            <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr>
                <th colspan="2" class="text-center">JUMLAH</th>
                <th class="text-center"><?= $total_ms_all ?></th>
                <th class="text-center"><?= $total_tms_all ?></th>
                <th class="text-center"><?= $total_uji_all ?></th>
            </tr>
        </tfoot>
    </table>
</div>

<!-- ========================================================================= -->
<!-- HALAMAN 7: 5. 10 PEMERIKSAAN TERBANYAK                                    -->
<!-- ========================================================================= -->
<div class="page">
    <h3 style="margin-bottom: 25px;">Jumlah 10 Pemeriksaan Terbanyak Sampel Lab Kesmas Bulan <?= $nama_bulan_murni ?> Tahun <?= $tahun_filter ?></h3>

    <div class="chart-container" style="max-width: 680px; margin-top: 40px;">
        <canvas id="chartTop10" height="280"></canvas>
    </div>

    <div style="max-width: 500px; margin: 30px auto;">
        <table class="table-custom">
            <thead>
                <tr>
                    <th width="15%">No</th>
                    <th>Nama Pemeriksaan</th>
                    <th width="25%">Jumlah</th>
                </tr>
            </thead>
            <tbody>
                <?php $rank = 1; foreach($data_top10 as $t10): ?>
                <tr>
                    <td class="text-center"><?= $rank++ ?></td>
                    <td><?= htmlspecialchars($t10['nama_pemeriksaan']) ?></td>
                    <td class="text-center fw-bold"><?= $t10['total'] ?></td>
                </tr>
                <?php endforeach; ?>
                <?php if (empty($data_top10)): ?>
                <tr><td colspan="3" class="text-center text-muted">Belum ada data pemeriksaan pada periode ini.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- ========================================================================= -->
<!-- HALAMAN 8: 6. DATA PER OBYEK                                              -->
<!-- ========================================================================= -->
<div class="page">
    <h3 class="text-left" style="margin-bottom: 15px;">4. Data Pemeriksaan Lab kesmas Per Obyek Bulan <?= $nama_bulan_filter ?></h3>

    <div style="max-width: 500px; margin: 15px auto;">
        <table class="table-custom">
            <thead>
                <tr>
                    <th width="15%">No</th>
                    <th>Obyek Pemeriksaan</th>
                    <th width="25%">Jumlah</th>
                </tr>
            </thead>
            <tbody>
                <?php $onum = 1; foreach($obyek_categories as $oname => $oval): ?>
                <tr>
                    <td class="text-center"><?= $onum++ ?></td>
                    <td><?= $oname ?></td>
                    <td class="text-center"><?= $oval ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="2" class="text-center">Total</th>
                    <th class="text-center"><?= $total_obyek_all ?></th>
                </tr>
            </tfoot>
        </table>
    </div>

    <div class="chart-container" style="max-width: 480px; margin-top: 30px;">
        <p class="text-center fw-bold" style="font-size: 10pt; margin-bottom: 10px;">
            Data Pemeriksaan Lab Kesmas Per Obyek Bulan <?= $nama_bulan_filter ?>
        </p>
        <canvas id="chartObyek" height="240"></canvas>
    </div>
</div>

<!-- ========================================================================= -->
<!-- HALAMAN 9: 5. DATA PEMERIKSAAN YANG DIRUJUK & HAMBATAN                    -->
<!-- ========================================================================= -->
<div class="page">
    <h3 class="text-left" style="margin-bottom: 12px;">5. Data Pemeriksaan yang di Rujuk Bulan <?= $nama_bulan_filter ?></h3>
    
    <p class="fw-bold" style="font-size: 10pt; margin-bottom: 4px;">a. Sampel yang dirujuk ke UPTD. Labkes Prov. Babel <?= $nama_bulan_filter ?></p>
    <table class="table-custom" style="margin-top: 5px; margin-bottom: 25px;">
        <thead>
            <tr>
                <th width="8%">No</th>
                <th>Jenis Sampel</th>
                <th width="20%">Jumlah Sampel</th>
                <th>Parameter Rujukan</th>
                <th width="20%">Jumlah Parameter</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($data_rujukan)): ?>
                <?php $rnum = 1; foreach($data_rujukan as $dr): ?>
                <tr>
                    <td class="text-center"><?= $rnum++ ?></td>
                    <td><?= htmlspecialchars($dr['jenis_sampel']) ?></td>
                    <td class="text-center">1</td>
                    <td>As, Pb, Cd</td>
                    <td class="text-center"><?= $dr['jml_parameter'] ?></td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td class="text-center">1</td>
                    <td>DAM / Air Minum</td>
                    <td class="text-center">0</td>
                    <td>As, Pb, Cd</td>
                    <td class="text-center">0</td>
                </tr>
            <?php endif; ?>
        </tbody>
        <tfoot>
            <tr>
                <th colspan="2" class="text-center">Total Sampel</th>
                <th class="text-center"><?= count($data_rujukan) ?></th>
                <th class="text-center">Total Parameter</th>
                <th class="text-center"><?= array_sum(array_column($data_rujukan, 'jml_parameter')) ?></th>
            </tr>
        </tfoot>
    </table>

    <h2 style="margin-top: 50px; margin-bottom: 15px;">BAB III<br>HAMBATAN</h2>
    <p style="text-align: justify; text-indent: 40px; line-height: 1.7;">
        Tidak ada hambatan signifikan dalam pelaksanaan operasional Lab kesmas selama bulan <?= $nama_bulan_filter ?>, baik di laboratorium mikrobiologi maupun kimia. Semua proses operasional berjalan lancar dan sesuai dengan standar yang ditetapkan. Dari hasil evaluasi operasional Lab kesmas menunjukkan bahwa sumber daya manusia untuk operasional Lab Kesmas cukup, serta sistem manajemen yang telah berjalan sudah baik, ini merupakan faktor penting dalam menjaga kualitas pelayanan di Laboratorium Kesmas.
    </p>
</div>

<!-- ========================================================================= -->
<!-- HALAMAN 10: BAB IV PENUTUP & TANDA TANGAN                                 -->
<!-- ========================================================================= -->
<div class="page">
    <h2>BAB IV<br>PENUTUP</h2>

    <p style="text-align: justify; text-indent: 40px; line-height: 1.7; margin-top: 25px;">
        Bulan <?= $nama_bulan_filter ?> Jumlah pelanggan dan parameter sebanyak <?= $total_sampel_kesmas ?> pelanggan dan total parameter yang diperiksa sebanyak <?= $total_uji_all ?> parameter pemeriksaan. Sampel yang diperiksa adalah sampel Air Minum, Air Hygiene, Makanan dan Sampel Lingkungan. Objek pemeriksaan didominasi pemeriksaan sampel dari <?= !empty($rows_obyek) ? $rows_obyek[0]['obyek'] : 'Masyarakat' ?>. Sistem rujukan ke laboratorium provinsi tetap berjalan efektif dengan <?= count($data_rujukan) ?> sampel dan <?= array_sum(array_column($data_rujukan, 'jml_parameter')) ?> parameter pemeriksaan yang dirujuk.
    </p>

    <table class="signature-table" style="margin-top: 60px;">
        <tr>
            <td width="50%">
                <br>
                Penanggung Jawab Teknis<br>Lab Kesmas<br><br><br><br><br>
                <strong><u>Irliyandra, SST</u></strong><br>
                NIP. 198608062008041001
            </td>
            <td width="50%">
                Pangkalpinang, <?= $nama_bulan_filter ?><br>
                Penyelia Lab Kimia<br><br><br><br><br>
                <strong><u>Trisia Mirtha, Ama.AFM</u></strong><br>
                NIP. 198303102006042013
            </td>
        </tr>
        <tr>
            <td></td>
            <td style="padding-top: 25px;">
                Penyelia Lab Mikrobiologi<br><br><br><br><br>
                <strong><u>Hetty Octavia, AMAK</u></strong><br>
                NIP. 197610221996032003
            </td>
        </tr>
        <tr>
            <td colspan="2" style="padding-top: 30px;">
                Mengetahui<br>
                Kepala UPTD Labkes Kota Pangkalpinang<br><br><br><br><br>
                <strong><u>Rina Wijayanti, SKM</u></strong><br>
                NIP. 197703262000032002
            </td>
        </tr>
    </table>
</div>

<!-- ========================================================================= -->
<!-- JAVASCRIPT CHART.JS INITIALIZATION                                        -->
<!-- ========================================================================= -->
<script>
document.addEventListener("DOMContentLoaded", function() {
    // 1. Chart Pelanggan (4 Kategori)
    const ctxPelanggan = document.getElementById('chartPelanggan').getContext('2d');
    new Chart(ctxPelanggan, {
        type: 'bar',
        data: {
            labels: ['Air Minum', 'Air Hygiene', 'Makanan', 'Lingkungan'],
            datasets: [{
                label: 'Jumlah Sampel',
                data: [
                    <?= $data_sampel['Air Minum'] ?>, 
                    <?= $data_sampel['Air Hygiene'] ?>, 
                    <?= $data_sampel['Makanan'] ?>, 
                    <?= $data_sampel['Lingkungan'] ?>
                ],
                backgroundColor: ['#60a5fa', '#f87171', '#a3e635', '#c084fc'],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } }
        }
    });

    // 2. Chart Puskesmas Wilayah
    const ctxPkm = document.getElementById('chartPuskesmas').getContext('2d');
    new Chart(ctxPkm, {
        type: 'bar',
        data: {
            labels: [<?php foreach($puskesmas_master as $pm) echo "'" . $pm['pkm'] . "',"; ?>],
            datasets: [
                {
                    label: 'Air Minum',
                    data: [<?php foreach($puskesmas_master as $pm) echo ($pkm_data[$pm['pkm']]['Air Minum']) . ","; ?>],
                    backgroundColor: '#60a5fa'
                },
                {
                    label: 'Air Hygiene',
                    data: [<?php foreach($puskesmas_master as $pm) echo ($pkm_data[$pm['pkm']]['Air Hygiene']) . ","; ?>],
                    backgroundColor: '#f87171'
                },
                {
                    label: 'Makanan',
                    data: [<?php foreach($puskesmas_master as $pm) echo ($pkm_data[$pm['pkm']]['Makanan']) . ","; ?>],
                    backgroundColor: '#a3e635'
                },
                {
                    label: 'Lingkungan',
                    data: [<?php foreach($puskesmas_master as $pm) echo ($pkm_data[$pm['pkm']]['Lingkungan']) . ","; ?>],
                    backgroundColor: '#c084fc'
                }
            ]
        },
        options: {
            responsive: true,
            scales: {
                x: { ticks: { font: { size: 9 }, maxRotation: 45 } },
                y: { beginAtZero: true, ticks: { stepSize: 1 } }
            }
        }
    });

    // 3. Chart 10 Terbanyak
    const ctxTop10 = document.getElementById('chartTop10').getContext('2d');
    new Chart(ctxTop10, {
        type: 'bar',
        data: {
            labels: [<?php foreach($data_top10 as $t) echo json_encode($t['nama_pemeriksaan']) . ","; ?>],
            datasets: [{
                label: 'Jumlah Pengujian',
                data: [<?php foreach($data_top10 as $t) echo $t['total'] . ","; ?>],
                backgroundColor: '#bef264',
                borderColor: '#84cc16',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: {
                x: { ticks: { font: { size: 9 }, maxRotation: 45 } },
                y: { beginAtZero: true, ticks: { stepSize: 1 } }
            }
        }
    });

    // 4. Chart Obyek (Pie)
    const ctxObyek = document.getElementById('chartObyek').getContext('2d');
    new Chart(ctxObyek, {
        type: 'pie',
        data: {
            labels: [<?php foreach($obyek_categories as $oname => $oval) echo json_encode($oname) . ","; ?>],
            datasets: [{
                data: [<?php foreach($obyek_categories as $oname => $oval) echo $oval . ","; ?>],
                backgroundColor: [
                    '#38bdf8', '#f87171', '#a3e635', '#818cf8',
                    '#2dd4bf', '#fb923c', '#e879f9'
                ]
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { position: 'right', labels: { boxWidth: 12, font: { size: 10 } } } }
        }
    });
});
</script>

</body>
</html>

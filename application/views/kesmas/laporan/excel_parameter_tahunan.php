<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$kategori_group = [];
foreach($laporan_tahunan as $row) {
    $kategori = !empty($row['kategori']) ? $row['kategori'] : 'Lain-lain';
    $nama = $row['nama_pemeriksaan'];
    $bulan = (int)$row['bulan'];

    if (!isset($kategori_group[$kategori])) {
        $kategori_group[$kategori] = [];
    }

    if (!isset($kategori_group[$kategori][$nama])) {
        $kategori_group[$kategori][$nama] = [
            'satuan' => $row['satuan'],
            'baku_mutu' => $row['baku_mutu'],
            'bulanan' => array_fill(1, 12, ['total' => 0, 'ms' => 0, 'tms' => 0]),
            'total_tahun' => 0,
        ];
    }

    $kategori_group[$kategori][$nama]['bulanan'][$bulan] = [
        'total' => (int)$row['total'],
        'ms' => (int)$row['ms_count'],
        'tms' => (int)$row['tms_count'],
    ];

    $kategori_group[$kategori][$nama]['total_tahun'] += (int)$row['total'];
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Tahunan Parameter Kesmas <?= $tahun ?></title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
            font-family: Arial, sans-serif;
            font-size: 10pt;
        }
        th, td {
            border: 1px solid #000000;
            padding: 5px;
            text-align: center;
        }
        th {
            background-color: #dbeafe;
            font-weight: bold;
        }
        .text-left {
            text-align: left;
        }
        .bg-gray {
            background-color: #f1f5f9;
        }
        .bg-total {
            background-color: #bfdbfe;
            font-weight: bold;
        }
    </style>
</head>
<body>

    <h2 style="text-align: center;">LAPORAN TAHUNAN REKAPITULASI PARAMETER LABORATORIUM KESMAS</h2>
    <h3 style="text-align: center;">UPTD. LABORATORIUM KESEHATAN KOTA PANGKAL PINANG - TAHUN <?= $tahun ?></h3>
    <br>

    <?php foreach($kategori_group as $kategori => $params): ?>
    <h3>Kategori: <?= htmlspecialchars($kategori) ?></h3>
    <table>
        <thead>
            <tr>
                <th width="4%">No</th>
                <th class="text-left" width="25%">Nama Parameter</th>
                <th width="8%">Satuan</th>
                <th width="15%">Baku Mutu</th>
                <th>Jan</th>
                <th>Feb</th>
                <th>Mar</th>
                <th>Apr</th>
                <th>Mei</th>
                <th>Jun</th>
                <th>Jul</th>
                <th>Ags</th>
                <th>Sep</th>
                <th>Okt</th>
                <th>Nov</th>
                <th>Des</th>
                <th class="bg-total">Total</th>
            </tr>
        </thead>
        <tbody>
            <?php 
                $no = 1;
                $tot_col = array_fill(1, 12, 0);
                $grand_tot = 0;
                foreach($params as $p_name => $p_data): 
                    $grand_tot += $p_data['total_tahun'];
            ?>
            <tr>
                <td><?= $no++ ?></td>
                <td class="text-left"><strong><?= htmlspecialchars($p_name) ?></strong></td>
                <td><?= htmlspecialchars($p_data['satuan'] ?: '-') ?></td>
                <td><?= htmlspecialchars($p_data['baku_mutu'] ?: '-') ?></td>
                <?php for($i=1; $i<=12; $i++): 
                    $val = $p_data['bulanan'][$i]['total'];
                    $tot_col[$i] += $val;
                ?>
                <td><?= $val > 0 ? $val : 0 ?></td>
                <?php endfor; ?>
                <td class="bg-total"><?= $p_data['total_tahun'] ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr class="bg-gray">
                <th colspan="4" class="text-left">TOTAL PENGUJIAN:</th>
                <?php for($i=1; $i<=12; $i++): ?>
                <th><?= $tot_col[$i] ?></th>
                <?php endfor; ?>
                <th class="bg-total"><?= $grand_tot ?></th>
            </tr>
        </tfoot>
    </table>
    <br><br>
    <?php endforeach; ?>

</body>
</html>

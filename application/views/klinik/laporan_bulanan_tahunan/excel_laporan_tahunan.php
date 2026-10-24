<?php
// Mencegah akses langsung
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html>
<head>
    <title>Laporan Tahunan <?= $tahun ?></title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }
        th, td {
            border: 1px solid black;
            padding: 5px;
            text-align: center;
        }
        th {
            background-color: #f2f2f2;
        }
        .text-left {
            text-align: left;
        }
    </style>
</head>
<body>

    <h2>Laporan Tahunan Laboratorium <?= $tahun ?></h2>

    <?php
    $kategori_group = [];

    foreach($laporan_tahunan as $row){
        $kategori = $row->kategori;
        $jenis = $row->nama_jenis;
        $bulan = $row->bulan;

        $kategori_group[$kategori][$jenis][$bulan] = $row->total;
    }
    ?>

    <?php foreach($kategori_group as $kategori => $jenis_data): ?>

    <h3>Kategori: <?= $kategori ?></h3>
    <table>
        <thead>
            <tr>
                <th class="text-left">Jenis Pemeriksaan</th>
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
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($jenis_data as $jenis => $bulan_data): ?>
            <tr>
                <td class="text-left">
                    <strong><?= $jenis ?></strong>
                </td>
                
                <?php 
                    $total_setahun = 0;
                    for($i=1; $i<=12; $i++): 
                        $val = isset($bulan_data[$i]) ? $bulan_data[$i] : 0;
                        $total_setahun += $val;
                ?>
                <td>
                    <?= $val ?>
                </td>
                <?php endfor; ?>
                
                <td><strong><?= $total_setahun ?></strong></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <br>

    <?php endforeach; ?>

</body>
</html>

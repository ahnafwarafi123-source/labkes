<?php
// Backfill script: parse existing `baku_mutu` into structured columns
// Run from project root: php tools/backfill_baku_mutu.php

require __DIR__ . '/../vendor/autoload.php'; // if composer autoload exists
// fallback to CI bootstrap if needed
if (!file_exists(__DIR__ . '/../application/config/database.php')) {
    echo "Run this from project root (where application/ exists)\n";
    exit(1);
}

// Minimal bootstrap for CodeIgniter's database: load via CI's instance if available
// We'll use mysqli directly for simplicity
$dbHost = 'localhost';
$dbUser = 'root';
$dbPass = '';
$dbName = 'kesmas_new';

$mysqli = new mysqli($dbHost, $dbUser, $dbPass, $dbName);
if ($mysqli->connect_errno) {
    echo "DB connect error: " . $mysqli->connect_error . "\n";
    exit(1);
}

function normalize($s) {
    return mb_strtolower(trim($s ?? ''));
}

function parse_baku($baku) {
    $b = normalize($baku);
    $result = [
        'baku_type' => null,
        'baku_operator' => null,
        'baku_min' => null,
        'baku_max' => null,
        'baku_text' => null,
    ];

    if ($b === '') return $result;

    // numeric range 'x - y'
    if (preg_match('/([0-9\.,]+)\s*[-–]\s*([0-9\.,]+)/u', $b, $m)) {
        $low = (float)str_replace(',', '.', $m[1]);
        $high = (float)str_replace(',', '.', $m[2]);
        $result['baku_type'] = 'numeric_range';
        $result['baku_min'] = $low;
        $result['baku_max'] = $high;
        return $result;
    }

    // '≤' or '<' or 'Maks' forms
    if (preg_match('/(≤|<=|<|maks|maksimum)\s*[:\s]*([0-9\.,]+)/i', $b, $m)) {
        $op = $m[1];
        $val = (float)str_replace(',', '.', $m[2]);
        $result['baku_type'] = 'numeric_limit';
        $result['baku_operator'] = in_array($op, ['≤','<=','<','maks','maksimum']) ? '<=' : $op;
        $result['baku_max'] = $val;
        return $result;
    }

    // exact zero or '0' or '0 cfu/ml' or 'negatif'
    if (preg_match('/\b0\b|negatif/i', $b)) {
        $result['baku_type'] = 'text';
        $result['baku_text'] = $baku;
        return $result;
    }

    // qualitative textual rules like 'Tidak berbau'
    if (preg_match('/tidak berbau|tidak bau|bening|jernih|negatif/i', $b)) {
        $result['baku_type'] = 'text';
        $result['baku_text'] = $baku;
        return $result;
    }

    // fallback: keep as text
    $result['baku_type'] = 'text';
    $result['baku_text'] = $baku;
    return $result;
}

$res = $mysqli->query("SELECT id, baku_mutu FROM kesmas_master_pemeriksaan");
$count = 0;
while ($r = $res->fetch_assoc()) {
    $id = (int)$r['id'];
    $baku = $r['baku_mutu'];
    $parsed = parse_baku($baku);
    $stmt = $mysqli->prepare("UPDATE kesmas_master_pemeriksaan SET baku_type=?, baku_operator=?, baku_min=?, baku_max=?, baku_text=? WHERE id=?");
    $stmt->bind_param('ssddsi', $parsed['baku_type'], $parsed['baku_operator'], $parsed['baku_min'], $parsed['baku_max'], $parsed['baku_text'], $id);
    $stmt->execute();
    if ($stmt->affected_rows >= 0) $count++;
    $stmt->close();
}

echo "Backfill complete. Updated rows: $count\n";
$mysqli->close();

?>
<?php
// Usage: php duplicate_database.php new_db_name
$host = '127.0.0.1';
$user = 'root';
$pass = '';
$sourceSql = __DIR__ . '/../database/kesmas_new.sql';
$targetDb = getenv('DB_NAME') ?: (isset($argv[1]) ? $argv[1] : null);

if (!$targetDb) {
    echo "Usage: php duplicate_database.php <new_database_name>\n";
    exit(1);
}

if (!file_exists($sourceSql)) {
    echo "Source SQL file not found: $sourceSql\n";
    exit(1);
}

$conn = new mysqli($host, $user, $pass);
if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error . "\n");
}

// Create database
if ($conn->query("CREATE DATABASE IF NOT EXISTS `" . $conn->real_escape_string($targetDb) . "` CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci") === TRUE) {
    echo "Database '$targetDb' created or already exists.\n";
} else {
    echo "Error creating database: " . $conn->error . "\n";
    exit(1);
}

// Select target db
if (!$conn->select_db($targetDb)) {
    echo "Cannot select database $targetDb: " . $conn->error . "\n";
    exit(1);
}

$sql = file_get_contents($sourceSql);
if ($sql === false) {
    echo "Failed to read SQL file.\n";
    exit(1);
}

// Disable foreign key checks and run whole SQL via multi_query
$conn->multi_query("SET FOREIGN_KEY_CHECKS=0; " . $sql . " SET FOREIGN_KEY_CHECKS=1;");

// Flush results
do {
    if ($res = $conn->store_result()) {
        $res->free();
    }
} while ($conn->more_results() && $conn->next_result());

if ($conn->errno) {
    echo "Import finished with errors: " . $conn->error . "\n";
    exit(1);
}

echo "Import to '$targetDb' completed successfully.\n";
$conn->close();

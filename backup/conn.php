<?php
$mysqli = new mysqli("localhost", "root", "", "db_penjualan");

if ($mysqli->connect_errno) {
    echo json_encode(['message' => 'Failed to connect to MySQL: ' . $mysqli->connect_error]);
    exit();
}
?>

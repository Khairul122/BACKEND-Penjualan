<?php
include 'conn.php'; // Include your database connection file

header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $sql = "SELECT COUNT(*) as count FROM tbl_penjualan";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    
    echo json_encode(['count' => (int)$row['count']]);
}
?>

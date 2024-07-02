<?php
include 'conn.php';

header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$id_pengguna = isset($_GET['id_pengguna']) ? intval($_GET['id_pengguna']) : 0;

if ($id_pengguna > 0) {
    $query = "SELECT p.*, u.*, pr.*
    FROM tbl_penjualan p
    JOIN tbl_pengguna u ON p.id_pengguna = u.id_pengguna
    JOIN tbl_produk pr ON p.id_produk = pr.id_produk
    WHERE p.id_pengguna = ?";
    $stmt = $conn->prepare($query);

    $stmt->bind_param("i", $id_pengguna);
    $stmt->execute();
    $result = $stmt->get_result();

    $orders = array();
    while ($row = $result->fetch_assoc()) {
        $orders[] = $row;
    }

    echo json_encode(array("status" => "success", "orders" => $orders));
} else {
    echo json_encode(array("status" => "error", "message" => "Invalid id_pengguna"));
}

$conn->close();

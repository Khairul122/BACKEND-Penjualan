<?php
include 'conn.php'; // Include your database connection file

header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $idPengguna = $_GET['id_pengguna'];
    $sql = "SELECT p.*, pr.nama_produk FROM pembayaran p JOIN tbl_produk pr ON p.id_produk = pr.id_produk WHERE p.id_pengguna = ? ORDER BY p.created_at DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $idPengguna);
    $stmt->execute();
    $result = $stmt->get_result();

    $transactions = [];
    while ($row = $result->fetch_assoc()) {
        $row['id_pengguna'] = (int) $row['id_pengguna'];
        $row['quantity'] = (int) $row['quantity'];
        $row['total'] = (float) $row['total'];
        $transactions[] = $row;
    }

    $response = [
        'status' => 'success',
        'transactions' => $transactions
    ];

    echo json_encode($response);
}
?>

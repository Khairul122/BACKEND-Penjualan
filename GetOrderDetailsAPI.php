<?php
include 'conn.php';

header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['id_pengguna'])) {
        $id_pengguna = $_GET['id_pengguna'];

        // Query to get the latest order details for the given id_pengguna
        $orderDetailsQuery = "
            SELECT p.kode_transaksi, u.nama_pengguna, u.alamat, p.status_pembelian, p.created_at
            FROM tbl_penjualan p
            JOIN tbl_pengguna u ON p.id_pengguna = u.id_pengguna
            WHERE p.id_pengguna = ?
            ORDER BY p.created_at DESC
            LIMIT 1
        ";

        if ($stmt = $conn->prepare($orderDetailsQuery)) {
            $stmt->bind_param("i", $id_pengguna);
            if ($stmt->execute()) {
                $result = $stmt->get_result();
                $orderDetails = $result->fetch_assoc();

                if ($orderDetails) {
                    echo json_encode($orderDetails);
                } else {
                    echo json_encode([]);
                }
            } else {
                http_response_code(500);
                echo json_encode(['error' => 'Failed to execute statement']);
                error_log($stmt->error);  // Logging the statement error
            }
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Failed to prepare statement']);
            error_log($conn->error);  // Logging the connection error
        }
    } else {
        http_response_code(400);
        echo json_encode(['error' => 'Missing id_pengguna parameter']);
    }
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Invalid request method']);
}
?>

<?php
include 'conn.php';

header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: PUT");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    // Parse the input data
    $input = json_decode(file_get_contents("php://input"), true);
    
    if (isset($input['id_pengguna'])) {
        $id_pengguna = $input['id_pengguna'];
        $metode_pembayaran = "Transfer Bank";
        $status_pembelian = "Sudah Dibayar";

        // Update the latest order for the given id_pengguna
        $updateQuery = "
            UPDATE tbl_penjualan
            SET metode_pembayaran = ?, status_pembelian = ?
            WHERE id_pengguna = ? AND kode_transaksi = (
                SELECT kode_transaksi FROM tbl_penjualan
                WHERE id_pengguna = ?
                ORDER BY created_at DESC
                LIMIT 1
            )
        ";

        if ($stmt = $conn->prepare($updateQuery)) {
            $stmt->bind_param("ssii", $metode_pembayaran, $status_pembelian, $id_pengguna, $id_pengguna);
            if ($stmt->execute()) {
                echo json_encode(['message' => 'Order updated successfully']);
            } else {
                http_response_code(500);
                echo json_encode(['error' => 'Failed to update order']);
            }
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Failed to prepare statement']);
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

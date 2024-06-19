<?php
include 'conn.php';

header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$method = $_SERVER['REQUEST_METHOD'];

if ($method == 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    $id = $input['id'] ?? 0;
    $status = $input['status'] ?? '';

    if ($id > 0 && !empty($status)) {
        $query = "UPDATE tbl_penjualan SET status_pembelian = ? WHERE id_penjualan = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("si", $status, $id);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            echo json_encode(array("status" => "success", "message" => "Status berhasil diperbarui"));
        } else {
            echo json_encode(array("status" => "error", "message" => "Gagal memperbarui status"));
        }
        $stmt->close();
    } else {
        echo json_encode(array("status" => "error", "message" => "ID atau status tidak valid"));
    }
} else {
    echo json_encode(array("status" => "error", "message" => "Metode tidak valid"));
}

$conn->close();
?>

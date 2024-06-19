<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require 'conn.php';

$data = json_decode(file_get_contents("php://input"), true);

$id_pengguna = $data['id_pengguna'];
$total_keseluruhan = $data['total_keseluruhan'];

$sql = "UPDATE tbl_penjualan SET total = ? WHERE id_pengguna = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("di", $total_keseluruhan, $id_pengguna);

if ($stmt->execute()) {
    echo json_encode(array('status' => 'success'));
} else {
    echo json_encode(array('status' => 'error', 'message' => $conn->error));
}

$stmt->close();
$conn->close();
?>

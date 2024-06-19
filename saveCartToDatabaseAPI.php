<?php
include 'conn.php'; // Include your database connection file

header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    $idPengguna = $input['id_pengguna'];
    $cartItems = $input['cart_items'];

    $errors = [];

    foreach ($cartItems as $item) {
        $idKeranjang = $item['id_keranjang']; // Include id_keranjang
        $idProduk = $item['id_produk'];
        $quantity = $item['quantity'];
        $total = $item['harga_produk'] * $quantity;
        $kodeTransaksi = $item['kode_transaksi'];
        $createdAt = date('Y-m-d H:i:s'); // Assuming you want to log the creation time

        // Debugging: Check if id_produk exists in tbl_produk
        $checkQuery = "SELECT COUNT(*) as count FROM tbl_produk WHERE id_produk = ?";
        $checkStmt = $conn->prepare($checkQuery);
        $checkStmt->bind_param('i', $idProduk);
        $checkStmt->execute();
        $result = $checkStmt->get_result();
        $row = $result->fetch_assoc();

        if ($row['count'] == 0) {
            $errors[] = "Product with id_produk $idProduk does not exist.";
            continue;
        }

        $sql = "INSERT INTO tbl_penjualan (id_keranjang, id_pengguna, id_produk, quantity, total, kode_transaksi, created_at) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);

        if ($stmt === false) {
            $errors[] = "Failed to prepare statement: " . $conn->error;
            continue;
        }

        $stmt->bind_param('iiiidss', $idKeranjang, $idPengguna, $idProduk, $quantity, $total, $kodeTransaksi, $createdAt);
        
        if (!$stmt->execute()) {
            $errors[] = "Failed to execute statement: " . $stmt->error;
        }

        $stmt->close();
    }

    if (count($errors) > 0) {
        $response = [
            'status' => 'error',
            'errors' => $errors
        ];
    } else {
        $response = [
            'status' => 'success'
        ];
    }

    echo json_encode($response);
}
?>

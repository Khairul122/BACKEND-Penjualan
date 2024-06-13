<?php
include 'conn.php';

header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['id_pengguna'])) {
        $id_pengguna = $_GET['id_pengguna'];

        // Query to get the latest kode_transaksi for the given id_pengguna
        $latestKodeTransaksiQuery = "
            SELECT kode_transaksi FROM tbl_penjualan
            WHERE id_pengguna = ?
            ORDER BY created_at DESC
            LIMIT 1
        ";

        if ($stmt = $conn->prepare($latestKodeTransaksiQuery)) {
            $stmt->bind_param("i", $id_pengguna);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            $latestKodeTransaksi = $row ? $row['kode_transaksi'] : null;

            // If there is no kode_transaksi found, return an empty array
            if (!$latestKodeTransaksi) {
                echo json_encode([]);
                exit();
            }

            // Query to get products based on the latest kode_transaksi
            $productsQuery = "
                SELECT p.id_pembayaran, p.kode_transaksi, p.id_pengguna, p.id_produk, pr.nama_produk, pr.gambar_produk, pr.harga_produk, p.quantity, p.total, p.created_at
                FROM tbl_penjualan p
                JOIN tbl_produk pr ON p.id_produk = pr.id_produk
                WHERE p.kode_transaksi = ?
                AND p.id_pengguna = ?
            ";

            if ($stmt = $conn->prepare($productsQuery)) {
                $stmt->bind_param("si", $latestKodeTransaksi, $id_pengguna);
                $stmt->execute();
                $result = $stmt->get_result();

                $products = array();
                while ($row = $result->fetch_assoc()) {
                    $products[] = $row;
                }

                echo json_encode($products);
            } else {
                http_response_code(500);
                echo json_encode(['error' => 'Failed to prepare statement for products query']);
            }
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Failed to prepare statement for kode_transaksi query']);
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

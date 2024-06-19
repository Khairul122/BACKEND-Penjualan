<?php
include 'conn.php';

header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        $query = "SELECT 
                    b.id_pembayaran, 
                    b.kode_transaksi, 
                    b.id_pengguna, 
                    b.id_produk, 
                    b.quantity, 
                    b.total, 
                    b.created_at, 
                    b.metode_pembayaran, 
                    b.status_pembelian, 
                    b.estimasi_pengiriman, 
                    b.id_keranjang, 
                    u.nama_pengguna, 
                    u.email, 
                    u.password, 
                    u.no_telepon, 
                    u.id_alamat, 
                    p.nama_produk, 
                    p.merk_produk, 
                    p.gambar_produk, 
                    p.harga_produk, 
                    p.deskripsi_produk, 
                    p.stok_produk, 
                    p.id_kategori
                  FROM tbl_penjualan b
                  JOIN tbl_pengguna u ON b.id_pengguna = u.id_pengguna
                  JOIN tbl_produk p ON b.id_produk = p.id_produk";

        $result = $conn->query($query);

        if ($result) {
            $orders = array();
            while ($row = $result->fetch_assoc()) {
                $orders[] = $row;
            }

            $response = array(
                "status" => "success",
                "orders" => $orders
            );
        } else {
            $response = array(
                "status" => "error",
                "message" => "Query error: " . $conn->error
            );
        }

        echo json_encode($response);
        break;

    case 'PUT':
        $data = json_decode(file_get_contents("php://input"), true);
        $id_pembayaran = isset($data['id_pembayaran']) ? intval($data['id_pembayaran']) : 0;
        $status_pembelian = isset($data['status_pembelian']) ? $data['status_pembelian'] : '';

        if ($id_pembayaran > 0 && !empty($status_pembelian)) {
            $query = "UPDATE tbl_penjualan SET status_pembelian = ? WHERE id_pembayaran = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("si", $status_pembelian, $id_pembayaran);
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                echo json_encode(array("status" => "success", "message" => "Status pembelian berhasil diperbarui"));
            } else {
                echo json_encode(array("status" => "error", "message" => "Gagal memperbarui status pembelian"));
            }
            $stmt->close();
        } else {
            echo json_encode(array("status" => "error", "message" => "ID penjualan atau status pembelian tidak valid"));
        }
        break;

    case 'DELETE':
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        if ($id > 0) {
            $query = "DELETE FROM tbl_pembayaran WHERE id_pembayaran = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("i", $id);
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                echo json_encode(array("status" => "success", "message" => "Pembayaran berhasil dihapus"));
            } else {
                echo json_encode(array("status" => "error", "message" => "Gagal menghapus pembayaran"));
            }
            $stmt->close();
        } else {
            echo json_encode(array("status" => "error", "message" => "ID pembayaran tidak valid"));
        }
        break;

    default:
        echo json_encode(array("status" => "error", "message" => "Metode tidak valid"));
        break;
}

$conn->close();

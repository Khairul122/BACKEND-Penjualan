<?php
require 'conn.php';

header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");


$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        getKeranjang($conn);
        break;
    case 'POST':
        postKeranjang($conn);
        break;
    case 'PUT':
        updateKeranjang($conn);
        break;
    case 'DELETE':
        deleteKeranjang($conn);
        break;
    default:
        echo json_encode(["message" => "Method not supported"]);
        break;
}

function getKeranjang($conn) {
    $id_pengguna = isset($_GET['id_pengguna']) ? $_GET['id_pengguna'] : null;
    
    $sql = "SELECT 
                k.id_keranjang, 
                k.id_pengguna, 
                p.nama_pengguna, 
                p.no_telepon, 
                p.email, 
                k.id_produk, 
                pr.nama_produk, 
                pr.merk_produk, 
                pr.gambar_produk, 
                pr.harga_produk, 
                pr.deskripsi_produk, 
                k.status 
            FROM tbl_keranjang k
            JOIN tbl_pengguna p ON k.id_pengguna = p.id_pengguna
            JOIN tbl_produk pr ON k.id_produk = pr.id_produk";
    
    if ($id_pengguna) {
        $sql .= " WHERE k.id_pengguna = '$id_pengguna'";
    }

    if ($result = $conn->query($sql)) {
        $keranjangs = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $keranjangs[] = $row;
            }
        }
        echo json_encode($keranjangs);
    } else {
        echo json_encode(["message" => "Error: " . $conn->error]);
    }
}

function postKeranjang($conn) {
    $data = json_decode(file_get_contents("php://input"), true);
    error_log("Received data: " . print_r($data, true)); // Log data yang diterima

    $id_pengguna = $data['id_pengguna'];
    $id_produk = $data['id_produk'];
    $status = $data['status'];

    $sql = "INSERT INTO tbl_keranjang (id_pengguna, id_produk, status) VALUES ('$id_pengguna', '$id_produk', '$status')";
    error_log("SQL Query: " . $sql); // Log query SQL yang dijalankan

    if ($conn->query($sql) === TRUE) {
        echo json_encode([
            "message" => "New record created successfully",
            "received_data" => $data
        ]);
    } else {
        echo json_encode([
            "message" => "Error: " . $conn->error,
            "received_data" => $data
        ]);
    }
}

function updateKeranjang($conn) {
    $data = json_decode(file_get_contents("php://input"), true);
    $id_keranjang = $data['id_keranjang'];
    $id_pengguna = $data['id_pengguna'];
    $id_produk = $data['id_produk'];
    $status = $data['status'];

    $sql = "UPDATE tbl_keranjang SET id_pengguna='$id_pengguna', id_produk='$id_produk', status='$status' WHERE id_keranjang='$id_keranjang'";

    if ($conn->query($sql) === TRUE) {
        echo json_encode(["message" => "Record updated successfully"]);
    } else {
        echo json_encode(["message" => "Error: " . $sql . "<br>" . $conn->error]);
    }
}

function deleteKeranjang($conn) {
    $data = json_decode(file_get_contents("php://input"), true);
    $id_keranjang = $data['id_keranjang'];

    $sql = "DELETE FROM tbl_keranjang WHERE id_keranjang='$id_keranjang'";

    if ($conn->query($sql) === TRUE) {
        echo json_encode(["message" => "Record deleted successfully"]);
    } else {
        echo json_encode(["message" => "Error: " . $sql . "<br>" . $conn->error]);
    }
}

$conn->close();
?>

<?php
include 'conn.php';

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json");

header("Content-Type: application/json");
$method = $_SERVER['REQUEST_METHOD'];

function clean_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Menangani GET request
if ($method == 'GET') {
    if (isset($_GET['id_pembelian'])) {
        // Mendapatkan pembelian berdasarkan id
        $id_pembelian = $_GET['id_pembelian'];
        $sql = "SELECT * FROM tbl_pembelian WHERE id_pembelian = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id_pembelian);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $rows = array();
            while ($r = $result->fetch_assoc()) {
                $rows[] = $r;
            }
            echo json_encode($rows);
        } else {
            echo json_encode(array("message" => "Data not found"));
        }
        $stmt->close();
    } else {
        // Mendapatkan semua pembelian
        $sql = "SELECT * FROM tbl_pembelian";
        $result = $conn->query($sql);

        $rows = array();
        while ($r = $result->fetch_assoc()) {
            $rows[] = $r;
        }
        echo json_encode($rows);
    }
} elseif ($method == 'POST') {
    // Menambahkan pembelian baru
    $id_produk = clean_input($_POST['id_produk']);
    $id_user = clean_input($_POST['id_user']);
    $tanggal_pembelian = clean_input($_POST['tanggal_pembelian']);
    $jumlah = clean_input($_POST['jumlah']);
    $total_harga = clean_input($_POST['total_harga']);

    $sql = "INSERT INTO tbl_pembelian (id_produk, id_user, tanggal_pembelian, jumlah, total_harga) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iisid", $id_produk, $id_user, $tanggal_pembelian, $jumlah, $total_harga);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo json_encode(array("message" => "Record added successfully"));
    } else {
        echo json_encode(array("error" => "Failed to add record: " . $conn->error));
    }
    $stmt->close();
} elseif ($method == 'PUT') {
    // Mengedit data pembelian
    parse_str(file_get_contents("php://input"), $data);
    $id_pembelian = isset($data['id_pembelian']) ? clean_input($data['id_pembelian']) : null;
    $id_produk = isset($data['id_produk']) ? clean_input($data['id_produk']) : null;
    $id_user = isset($data['id_user']) ? clean_input($data['id_user']) : null;
    $tanggal_pembelian = isset($data['tanggal_pembelian']) ? clean_input($data['tanggal_pembelian']) : null;
    $jumlah = isset($data['jumlah']) ? clean_input($data['jumlah']) : null;
    $total_harga = isset($data['total_harga']) ? clean_input($data['total_harga']) : null;

    if ($id_pembelian && $id_produk && $id_user && $tanggal_pembelian && $jumlah && $total_harga) {
        $sql = "UPDATE tbl_pembelian SET id_produk = ?, id_user = ?, tanggal_pembelian = ?, jumlah = ?, total_harga = ? WHERE id_pembelian = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iisidi", $id_produk, $id_user, $tanggal_pembelian, $jumlah, $total_harga, $id_pembelian);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            echo json_encode(array("message" => "Record updated successfully"));
        } else {
            echo json_encode(array("error" => "Failed to update record: " . $conn->error));
        }
        $stmt->close();
    } else {
        echo json_encode(array("error" => "Missing required fields"));
    }
} elseif ($method == 'DELETE') {
    // Menghapus data pembelian
    parse_str(file_get_contents("php://input"), $data);
    $id_pembelian = isset($data['id_pembelian']) ? $data['id_pembelian'] : null;

    if ($id_pembelian) {
        $sql = "DELETE FROM tbl_pembelian WHERE id_pembelian = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id_pembelian);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            echo json_encode(array("message" => "Record deleted successfully"));
        } else {
            echo json_encode(array("error" => "Failed to delete record: " . $conn->error));
        }
        $stmt->close();
    } else {
        echo json_encode(array("error" => "Missing required fields"));
    }
} else {
    echo json_encode(array("error" => "Invalid request"));
}

$conn->close();
?>

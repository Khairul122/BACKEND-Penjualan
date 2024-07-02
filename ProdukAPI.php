<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require 'conn.php';

session_start();

$method = $_SERVER['REQUEST_METHOD'];

// Function to handle form data, including file uploads
function handleFormData() {
    $data = json_decode(file_get_contents('php://input'), true);
    if (is_null($data)) {
        $data = array();
        parse_str(file_get_contents('php://input'), $data);
    }
    foreach ($_POST as $key => $value) {
        $data[$key] = $value;
    }
    foreach ($_FILES as $key => $file) {
        if ($file['error'] == UPLOAD_ERR_OK) {
            $target_dir = "uploads/";
            $target_file = $target_dir . basename($file["name"]);
            if (move_uploaded_file($file["tmp_name"], $target_file)) {
                $data[$key] = $target_file;
            } else {
                error_log("Failed to move uploaded file to $target_file");
            }
        }
    }
    return $data;
}

switch ($method) {
    case 'GET':
        // Handle GET request
        if (isset($_GET['id'])) {
            $id = intval($_GET['id']);
            $sql = "SELECT p.*, k.nama_kategori FROM tbl_produk p JOIN tbl_kategori k ON p.id_kategori = k.id_kategori WHERE p.id_produk = $id";
            $result = $conn->query($sql);
            if ($result) {
                $row = $result->fetch_assoc();
                echo json_encode($row);
            } else {
                echo json_encode(array('status' => 'error', 'message' => $conn->error));
            }
        } else {
            $sql = "SELECT p.*, k.nama_kategori FROM tbl_produk p JOIN tbl_kategori k ON p.id_kategori = k.id_kategori";
            $result = $conn->query($sql);
            if ($result) {
                $rows = array();
                while ($row = $result->fetch_assoc()) {
                    $rows[] = $row;
                }
                echo json_encode($rows);
            } else {
                echo json_encode(array('status' => 'error', 'message' => $conn->error));
            }
        }
        break;

    case 'POST':
        // Handle POST request
        $data = handleFormData();
        
        if (isset($data['nama_produk'], $data['merk_produk'], $data['gambar_produk'], $data['harga_produk'], $data['deskripsi_produk'], $data['id_kategori'])) {
            $nama_produk = $data['nama_produk'];
            $merk_produk = $data['merk_produk'];
            $gambar_produk = $data['gambar_produk'];
            $harga_produk = $data['harga_produk'];
            $deskripsi_produk = $data['deskripsi_produk'];
            $id_kategori = $data['id_kategori'];

            $sql = "INSERT INTO tbl_produk (nama_produk, merk_produk, gambar_produk, harga_produk, deskripsi_produk, id_kategori) VALUES ('$nama_produk', '$merk_produk', '$gambar_produk', '$harga_produk', '$deskripsi_produk', '$id_kategori')";

            if ($conn->query($sql) === TRUE) {
                $response = array('status' => 'success', 'id_produk' => $conn->insert_id);
            } else {
                $response = array('status' => 'error', 'message' => $conn->error);
            }
        } else {
            $response = array('status' => 'error', 'message' => 'Incomplete data');
        }

        echo json_encode($response);
        break;

    case 'PUT':
        // Handle PUT request
        $data = handleFormData();
        
        if (isset($data['id_produk'], $data['nama_produk'], $data['merk_produk'], $data['harga_produk'], $data['deskripsi_produk'], $data['id_kategori'])) {
            $id_produk = $data['id_produk'];
            $nama_produk = $data['nama_produk'];
            $merk_produk = $data['merk_produk'];
            $harga_produk = $data['harga_produk'];
            $deskripsi_produk = $data['deskripsi_produk'];
            $id_kategori = $data['id_kategori'];
            $gambar_produk = isset($data['gambar_produk']) ? $data['gambar_produk'] : null;

            $sql = "UPDATE tbl_produk SET nama_produk='$nama_produk', merk_produk='$merk_produk', harga_produk='$harga_produk', deskripsi_produk='$deskripsi_produk', id_kategori='$id_kategori'";
            if ($gambar_produk) {
                $sql .= ", gambar_produk='$gambar_produk'";
            }
            $sql .= " WHERE id_produk=$id_produk";

            if ($conn->query($sql) === TRUE) {
                $response = array('status' => 'success');
            } else {
                $response = array('status' => 'error', 'message' => $conn->error);
            }
        } else {
            $response = array('status' => 'error', 'message' => 'Incomplete data');
        }

        echo json_encode($response);
        break;

    case 'DELETE':
        // Handle DELETE request
        if (isset($_GET['id'])) {
            $id = intval($_GET['id']);
            $sql = "DELETE FROM tbl_produk WHERE id_produk = $id";

            if ($conn->query($sql) === TRUE) {
                $response = array('status' => 'success');
            } else {
                $response = array('status' => 'error', 'message' => $conn->error);
            }
            echo json_encode($response);
        } else {
            echo json_encode(array('status' => 'error', 'message' => 'Invalid ID'));
        }
        break;

    default:
        // Handle invalid request method
        echo json_encode(array('status' => 'error', 'message' => 'Invalid request method'));
        break;
}

$conn->close();
?>

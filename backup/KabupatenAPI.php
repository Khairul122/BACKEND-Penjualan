<?php
include 'conn.php';

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json");

header("Content-Type: application/json");
$method = $_SERVER['REQUEST_METHOD'];

function clean_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Menangani GET request
if ($method == 'GET') {
    if (isset($_GET['id_kabupaten'])) {
        // Mendapatkan kabupaten berdasarkan id
        $id_kabupaten = $_GET['id_kabupaten'];
        $sql = "SELECT * FROM tbl_kabupaten WHERE id_kabupaten = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id_kabupaten);
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
        // Mendapatkan semua kabupaten
        $sql = "SELECT * FROM tbl_kabupaten";
        $result = $conn->query($sql);

        $rows = array();
        while ($r = $result->fetch_assoc()) {
            $rows[] = $r;
        }
        echo json_encode($rows);
    }
} else if ($method == 'POST') {
    // Menambahkan kabupaten baru
    $nama_kabupaten = clean_input($_POST['nama_kabupaten']);
    $id_provinsi = clean_input($_POST['id_provinsi']);
    $sql = "INSERT INTO tbl_kabupaten (nama_kabupaten, id_provinsi) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $nama_kabupaten, $id_provinsi);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo json_encode(array("message" => "Record added successfully"));
    } else {
        echo json_encode(array("error" => "Failed to add record: " . $conn->error));
    }
    $stmt->close();
} else if ($method == 'PUT') {
    // Mengedit data kabupaten
    parse_str(file_get_contents("php://input"), $data);
    $id_kabupaten = isset($data['id_kabupaten']) ? clean_input($data['id_kabupaten']) : null;
    $nama_kabupaten = isset($data['nama_kabupaten']) ? clean_input($data['nama_kabupaten']) : null;
    $id_provinsi = isset($data['id_provinsi']) ? clean_input($data['id_provinsi']) : null;

    if ($id_kabupaten && $nama_kabupaten && $id_provinsi) {
        $sql = "UPDATE tbl_kabupaten SET nama_kabupaten = ?, id_provinsi = ? WHERE id_kabupaten = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sii", $nama_kabupaten, $id_provinsi, $id_kabupaten);
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
} else if ($method == 'DELETE') {
    // Menghapus data kabupaten
    parse_str(file_get_contents("php://input"), $data);
    $id_kabupaten = isset($data['id_kabupaten']) ? $data['id_kabupaten'] : null;

    if ($id_kabupaten) {
        $sql = "DELETE FROM tbl_kabupaten WHERE id_kabupaten = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id_kabupaten);
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
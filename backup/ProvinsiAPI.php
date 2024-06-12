<?php
include 'conn.php';

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
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
    if (isset($_GET['id_provinsi'])) {
        // Mendapatkan provinsi berdasarkan id
        $id_provinsi = $_GET['id_provinsi'];
        $sql = "SELECT * FROM tbl_provinsi WHERE id_provinsi = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id_provinsi);
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
        // Mendapatkan semua provinsi
        $sql = "SELECT * FROM tbl_provinsi";
        $result = $conn->query($sql);

        $rows = array();
        while ($r = $result->fetch_assoc()) {
            $rows[] = $r;
        }
        echo json_encode($rows);
    }
} elseif ($method == 'POST') {
    // Menambahkan provinsi baru
    $nama_provinsi = clean_input($_POST['nama_provinsi']);
    $sql = "INSERT INTO tbl_provinsi (nama_provinsi) VALUES (?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $nama_provinsi);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo json_encode(array("message" => "Record added successfully"));
    } else {
        echo json_encode(array("error" => "Failed to add record: " . $conn->error));
    }
    $stmt->close();
} elseif ($method == 'PUT') {
    // Mengedit data provinsi
    parse_str(file_get_contents("php://input"), $data);
    $id_provinsi = isset($data['id_provinsi']) ? clean_input($data['id_provinsi']) : null;
    $nama_provinsi = isset($data['nama_provinsi']) ? clean_input($data['nama_provinsi']) : null;

    if ($id_provinsi && $nama_provinsi) {
        $sql = "UPDATE tbl_provinsi SET nama_provinsi = ? WHERE id_provinsi = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $nama_provinsi, $id_provinsi);
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
    // Menghapus data provinsi
    parse_str(file_get_contents("php://input"), $data);
    $id_provinsi = isset($data['id_provinsi']) ? $data['id_provinsi'] : null;

    if ($id_provinsi) {
        $sql = "DELETE FROM tbl_provinsi WHERE id_provinsi = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id_provinsi);
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
    // Metode request tidak dikenal
    echo json_encode(array("error" => "Invalid request"));
}
$conn->close();
?>
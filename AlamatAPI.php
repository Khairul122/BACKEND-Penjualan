<?php
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require 'conn.php';

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        if (isset($_GET['id'])) {
            $id = intval($_GET['id']);
            $sql = "SELECT * FROM tbl_alamat WHERE id_alamat = $id";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                echo json_encode($row);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'No record found']);
            }
        } else {
            $sql = "SELECT * FROM tbl_alamat";
            $result = $conn->query($sql);
            $rows = array();
            while ($row = $result->fetch_assoc()) {
                $rows[] = $row;
            }
            echo json_encode($rows);
        }
        break;

    case 'POST':
        $data = json_decode(file_get_contents("php://input"), true);
        if (isset($data['alamat']) && isset($data['ongkir'])) {
            $alamat = $data['alamat'];
            $ongkir = intval($data['ongkir']);

            $sql = "INSERT INTO tbl_alamat (alamat, ongkir) VALUES ('$alamat', $ongkir)";

            if ($conn->query($sql) === TRUE) {
                echo json_encode(['status' => 'success', 'id_alamat' => $conn->insert_id]);
            } else {
                echo json_encode(['status' => 'error', 'message' => $conn->error]);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Invalid input']);
        }
        break;

    case 'PUT':
        parse_str(file_get_contents("php://input"), $data);
        if (isset($data['id_alamat']) && isset($data['alamat']) && isset($data['ongkir'])) {
            $id_alamat = intval($data['id_alamat']);
            $alamat = $data['alamat'];
            $ongkir = intval($data['ongkir']);

            $sql = "UPDATE tbl_alamat SET alamat='$alamat', ongkir=$ongkir WHERE id_alamat=$id_alamat";

            if ($conn->query($sql) === TRUE) {
                echo json_encode(['status' => 'success']);
            } else {
                echo json_encode(['status' => 'error', 'message' => $conn->error]);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Invalid input']);
        }
        break;

    case 'DELETE':
        if (isset($_GET['id'])) {
            $id = intval($_GET['id']);
            $sql = "DELETE FROM tbl_alamat WHERE id_alamat = $id";

            if ($conn->query($sql) === TRUE) {
                echo json_encode(['status' => 'success']);
            } else {
                echo json_encode(['status' => 'error', 'message' => $conn->error]);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Invalid ID']);
        }
        break;

    default:
        echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
        break;
}

$conn->close();
?>

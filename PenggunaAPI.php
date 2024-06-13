<?php
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require 'conn.php';
session_start();

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        if (isset($_GET['id'])) {
            $id = intval($_GET['id']);
            $sql = "SELECT tbl_pengguna.*, tbl_alamat.alamat, tbl_alamat.ongkir 
                    FROM tbl_pengguna 
                    JOIN tbl_alamat ON tbl_pengguna.id_alamat = tbl_alamat.id_alamat 
                    WHERE tbl_pengguna.id_pengguna = $id";
            $result = $conn->query($sql);
            $row = $result->fetch_assoc();
            echo json_encode($row);
        } else {
            $sql = "SELECT tbl_pengguna.*, tbl_alamat.alamat, tbl_alamat.ongkir 
                    FROM tbl_pengguna 
                    JOIN tbl_alamat ON tbl_pengguna.id_alamat = tbl_alamat.id_alamat";
            $result = $conn->query($sql);
            $rows = array();
            while ($row = $result->fetch_assoc()) {
                $rows[] = $row;
            }
            echo json_encode($rows);
        }
        break;

    case 'POST':
        if (isset($_POST['action'])) {
            $action = $_POST['action'];
            if ($action == 'register') {
                $nama_pengguna = $_POST['nama_pengguna'];
                $email = $_POST['email'];
                $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
                $id_alamat = $_POST['id_alamat'];
                $no_telepon = $_POST['no_telepon'];

                $sql = "INSERT INTO tbl_pengguna (nama_pengguna, email, password, id_alamat, no_telepon) 
                        VALUES ('$nama_pengguna', '$email', '$password', '$id_alamat', '$no_telepon')";

                if ($conn->query($sql) === TRUE) {
                    $response = array('status' => 'success', 'id_pengguna' => $conn->insert_id);
                } else {
                    $response = array('status' => 'error', 'message' => $conn->error);
                }
                echo json_encode($response);
            } elseif ($action == 'login') {
                $email = $_POST['email'];
                $password = $_POST['password'];

                $sql = "SELECT tbl_pengguna.*, tbl_alamat.alamat, tbl_alamat.ongkir 
                        FROM tbl_pengguna 
                        JOIN tbl_alamat ON tbl_pengguna.id_alamat = tbl_alamat.id_alamat 
                        WHERE tbl_pengguna.email = '$email'";
                $result = $conn->query($sql);
                $row = $result->fetch_assoc();

                if ($row && password_verify($password, $row['password'])) {
                    $_SESSION['user'] = $row;
                    $response = array('status' => 'success', 'user' => $row);
                } else {
                    $response = array('status' => 'error', 'message' => 'Invalid email or password');
                }
                echo json_encode($response);
            } elseif ($action == 'logout') {
                session_unset();
                session_destroy();
                echo json_encode(array('status' => 'success'));
            }
        }
        break;

    case 'PUT':
        parse_str(file_get_contents("php://input"), $_PUT);

        $id_pengguna = $_PUT['id_pengguna'];
        $nama_pengguna = $_PUT['nama_pengguna'];
        $email = $_PUT['email'];
        $id_alamat = $_PUT['id_alamat'];
        $no_telepon = $_PUT['no_telepon'];

        $sql = "UPDATE tbl_pengguna SET nama_pengguna='$nama_pengguna', email='$email', id_alamat='$id_alamat', no_telepon='$no_telepon' 
                WHERE id_pengguna=$id_pengguna";

        if ($conn->query($sql) === TRUE) {
            $response = array('status' => 'success');
        } else {
            $response = array('status' => 'error', 'message' => $conn->error);
        }
        echo json_encode($response);
        break;

    case 'DELETE':
        if (isset($_GET['id'])) {
            $id = intval($_GET['id']);
            $sql = "DELETE FROM tbl_pengguna WHERE id_pengguna = $id";

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
        echo json_encode(array('status' => 'error', 'message' => 'Invalid request method'));
        break;
}

$conn->close();
?>

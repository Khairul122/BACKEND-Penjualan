<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require 'conn.php';

session_start();

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        if (isset($_SESSION['admin_id'])) {
            $id = $_SESSION['admin_id'];
            $sql = "SELECT * FROM tbl_admin WHERE id_admin = $id";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                echo json_encode($row);
            } else {
                echo json_encode(array('status' => 'error', 'message' => 'User not found'));
            }
        } else {
            echo json_encode(array('status' => 'error', 'message' => 'Not logged in'));
        }
        break;

    case 'POST':
        $data = json_decode(file_get_contents('php://input'), true);

        if (isset($data['action']) && $data['action'] === 'login') {
            // Login
            $email = $data['email'];
            $password = $data['password'];

            $sql = "SELECT * FROM tbl_admin WHERE email = '$email'";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                $user = $result->fetch_assoc();
                if (password_verify($password, $user['password'])) {
                    $_SESSION['admin_id'] = $user['id_admin'];
                    $_SESSION['admin_username'] = $user['username'];
                    echo json_encode(array('status' => 'success', 'message' => 'Login successful', 'user' => $user));
                } else {
                    echo json_encode(array('status' => 'error', 'message' => 'Invalid password'));
                }
            } else {
                echo json_encode(array('status' => 'error', 'message' => 'User not found'));
            }
        } elseif (isset($data['action']) && $data['action'] === 'logout') {
            // Logout
            session_destroy();
            echo json_encode(array('status' => 'success', 'message' => 'Logout successful'));
        } else {
            // Create new admin
            $username = $data['username'];
            $email = $data['email'];
            $password = password_hash($data['password'], PASSWORD_DEFAULT);

            $sql = "INSERT INTO tbl_admin (username, email, password) VALUES ('$username', '$email', '$password')";

            if ($conn->query($sql) === TRUE) {
                $response = array('status' => 'success', 'id_admin' => $conn->insert_id);
            } else {
                $response = array('status' => 'error', 'message' => $conn->error);
            }
            echo json_encode($response);
        }
        break;

    case 'PUT':
        parse_str(file_get_contents("php://input"), $data);

        $id_admin = $data['id_admin'];
        $username = $data['username'];
        $email = $data['email'];
        if (!empty($data['password'])) {
            $password = password_hash($data['password'], PASSWORD_DEFAULT);
        }

        $sql = "UPDATE tbl_admin SET username='$username', email='$email'";
        if (!empty($data['password'])) {
            $sql .= ", password='$password'";
        }
        $sql .= " WHERE id_admin=$id_admin";

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
            $sql = "DELETE FROM tbl_admin WHERE id_admin = $id";

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

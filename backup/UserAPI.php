<?php
require 'conn.php';

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json");

$method = $_SERVER['REQUEST_METHOD'];
$request = isset($_SERVER['PATH_INFO']) ? explode('/', trim($_SERVER['PATH_INFO'], '/')) : [];

switch ($method) {
    case 'GET':
        if (isset($request[0]) && is_numeric($request[0])) {
            getUserById($conn, $request[0]);
        } else {
            getAllUsers($conn);
        }
        break;
    case 'POST':
        $data = json_decode(file_get_contents('php://input'), true);
        if (isset($request[0]) && $request[0] === 'login') {
            loginUser($conn, $data);
        } else {
            addUser($conn, $data);
        }
        break;
    case 'PUT':
        if (isset($request[0]) && is_numeric($request[0])) {
            updateUser($conn, $request[0]);
        } else {
            http_response_code(400);
            echo json_encode(["message" => "Invalid ID"]);
        }
        break;
    case 'DELETE':
        if (isset($request[0]) && is_numeric($request[0])) {
            deleteUser($conn, $request[0]);
        } else {
            http_response_code(400);
            echo json_encode(["message" => "Invalid ID"]);
        }
        break;
    default:
        http_response_code(405);
        echo json_encode(["message" => "Method Not Allowed"]);
        break;
}

$conn->close();

function getAllUsers($conn) {
    $sql = "SELECT * FROM tbl_user";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        $users = [];
        while($row = $result->fetch_assoc()) {
            $users[] = $row;
        }
        echo json_encode($users);
    } else {
        echo json_encode([]);
    }
}

function getUserById($conn, $id) {
    $sql = "SELECT * FROM tbl_user WHERE id_user = $id";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        echo json_encode($result->fetch_assoc());
    } else {
        http_response_code(404);
        echo json_encode(["message" => "User not found"]);
    }
}

function addUser($conn, $data) {
    if (!isset($data['email']) || !isset($data['password']) || !isset($data['name']) || !isset($data['username']) || !isset($data['nomor_telp']) || !isset($data['alamat'])) {
        http_response_code(400);
        echo json_encode(["message" => "Incomplete data"]);
        return;
    }
    
    $email = $conn->real_escape_string($data['email']);
    $password = $conn->real_escape_string($data['password']);
    $name = $conn->real_escape_string($data['name']);
    $username = $conn->real_escape_string($data['username']);
    $nomor_telp = $conn->real_escape_string($data['nomor_telp']);
    $alamat = $conn->real_escape_string($data['alamat']);
    
    $sql = "INSERT INTO tbl_user (email, password, name, username, nomor_telp, alamat) VALUES ('$email', '$password', '$name', '$username', '$nomor_telp', '$alamat')";
    
    if ($conn->query($sql) === TRUE) {
        http_response_code(201);
        echo json_encode(["message" => "User created successfully"]);
    } else {
        http_response_code(500);
        echo json_encode(["message" => "Error creating user"]);
    }
}

function updateUser($conn, $id) {
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (!isset($data['email']) || !isset($data['password']) || !isset($data['name']) || !isset($data['username']) || !isset($data['nomor_telp']) || !isset($data['alamat'])) {
        http_response_code(400);
        echo json_encode(["message" => "Incomplete data"]);
        return;
    }
    
    $email = $conn->real_escape_string($data['email']);
    $password = $conn->real_escape_string($data['password']);
    $name = $conn->real_escape_string($data['name']);
    $username = $conn->real_escape_string($data['username']);
    $nomor_telp = $conn->real_escape_string($data['nomor_telp']);
    $alamat = $conn->real_escape_string($data['alamat']);
    
    $sql = "UPDATE tbl_user SET email = '$email', password = '$password', name = '$name', username = '$username', nomor_telp = '$nomor_telp', alamat = '$alamat' WHERE id_user = $id";
    
    if ($conn->query($sql) === TRUE) {
        echo json_encode(["message" => "User updated successfully"]);
    } else {
        http_response_code(500);
        echo json_encode(["message" => "Error updating user"]);
    }
}

function deleteUser($conn, $id) {
    $sql = "DELETE FROM tbl_user WHERE id_user = $id";
    
    if ($conn->query($sql) === TRUE) {
        echo json_encode(["message" => "User deleted successfully"]);
    } else {
        http_response_code(500);
        echo json_encode(["message" => "Error deleting user"]);
    }
}

function loginUser($conn, $data) {
    if (!isset($data['email']) || !isset($data['password'])) {
        http_response_code(400);
        echo json_encode(["message" => "Incomplete data"]);
        return;
    }
    
    $email = $conn->real_escape_string($data['email']);
    $password = $conn->real_escape_string($data['password']);
    
    $sql = "SELECT * FROM tbl_user WHERE email = '$email' AND password = '$password'";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        echo json_encode(["message" => "Login successful", "user" => $user]);
    } else {
        http_response_code(401);
        echo json_encode(["message" => "Login failed"]);
    }
}
?>

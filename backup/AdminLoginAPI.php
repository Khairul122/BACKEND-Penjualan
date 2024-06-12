<?php
require 'conn.php';

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json");

$method = $_SERVER['REQUEST_METHOD'];
$request = isset($_SERVER['PATH_INFO']) ? explode('/', trim($_SERVER['PATH_INFO'], '/')) : [];

// Handle preflight requests
if ($method == 'OPTIONS') {
    http_response_code(200);
    exit;
}

switch ($method) {
    case 'GET':
        if (isset($request[0]) && is_numeric($request[0])) {
            getAdminById($conn, $request[0]);
        } else {
            getAllAdmins($conn);
        }
        break;
    case 'POST':
        $data = json_decode(file_get_contents('php://input'), true);
        if (isset($request[0]) && $request[0] === 'login') {
            loginAdmin($conn, $data);
        } else {
            addAdmin($conn, $data);
        }
        break;
    default:
        http_response_code(405);
        echo json_encode(["message" => "Method Not Allowed"]);
        break;
}

$conn->close();

function getAllAdmins($conn) {
    $sql = "SELECT * FROM tbl_admin";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        $admins = [];
        while($row = $result->fetch_assoc()) {
            $admins[] = $row;
        }
        echo json_encode($admins);
    } else {
        echo json_encode([]);
    }
}

function getAdminById($conn, $id) {
    $sql = "SELECT * FROM tbl_admin WHERE id_admin = $id";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        echo json_encode($result->fetch_assoc());
    } else {
        http_response_code(404);
        echo json_encode(["message" => "Admin not found"]);
    }
}

function addAdmin($conn, $data) {
    if (!isset($data['nama_admin']) || !isset($data['email_admin']) || !isset($data['password_admin'])) {
        http_response_code(400);
        echo json_encode(["message" => "Incomplete data"]);
        return;
    }
    
    $nama_admin = $conn->real_escape_string($data['nama_admin']);
    $email_admin = $conn->real_escape_string($data['email_admin']);
    $password_admin = $conn->real_escape_string($data['password_admin']);
    
    $sql = "INSERT INTO tbl_admin (nama_admin, email_admin, password_admin) VALUES ('$nama_admin', '$email_admin', '$password_admin')";
    
    if ($conn->query($sql) === TRUE) {
        http_response_code(201);
        echo json_encode(["message" => "Admin created successfully"]);
    } else {
        http_response_code(500);
        echo json_encode(["message" => "Error creating admin"]);
    }
}

function loginAdmin($conn, $data) {
    if (!isset($data['email_admin']) || !isset($data['password_admin'])) {
        http_response_code(400);
        echo json_encode(["message" => "Incomplete data"]);
        return;
    }
    
    $email_admin = $conn->real_escape_string($data['email_admin']);
    $password_admin = $conn->real_escape_string($data['password_admin']);
    
    $sql = "SELECT * FROM tbl_admin WHERE email_admin = '$email_admin'";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        $admin = $result->fetch_assoc();
        if ($password_admin === $admin['password_admin']) {
            echo json_encode(["message" => "Login successful", "admin" => $admin]);
        } else {
            http_response_code(401);
            echo json_encode(["message" => "Login failed"]);
        }
    } else {
        http_response_code(401);
        echo json_encode(["message" => "Login failed"]);
    }
}
?>

<?php
require 'conn.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');

$method = $_SERVER['REQUEST_METHOD'];
$request = isset($_SERVER['PATH_INFO']) ? explode('/', trim($_SERVER['PATH_INFO'],'/')) : [];

switch ($method) {
    case 'GET':
        if (isset($request[0]) && $request[0] === 'top') {
            getTopProducts();
        } else if (isset($request[0])) {
            $id = intval($request[0]);
            getProduct($id);
        } else {
            getProducts();
        }
        break;
    case 'POST':
        addProduct();
        break;
    case 'PUT':
        if (isset($request[0])) {
            $id = intval($request[0]);
            updateProduct($id);
        }
        break;
    case 'DELETE':
        if (isset($request[0])) {
            $id = intval($request[0]);
            deleteProduct($id);
        }
        break;
    default:
        echo json_encode(['message' => 'Method not supported']);
        break;
}

function getProducts() {
    global $mysqli;
    $result = $mysqli->query('SELECT * FROM tbl_produk');
    if ($result) {
        $products = $result->fetch_all(MYSQLI_ASSOC);
        echo json_encode($products);
    } else {
        echo json_encode(['message' => 'Failed to fetch products']);
    }
}

function getTopProducts() {
    global $mysqli;
    $result = $mysqli->query('SELECT * FROM tbl_produk ORDER BY id_produk DESC LIMIT 3');
    if ($result) {
        $products = $result->fetch_all(MYSQLI_ASSOC);
        echo json_encode($products);
    } else {
        echo json_encode(['message' => 'Failed to fetch top products']);
    }
}

function getProduct($id) {
    global $mysqli;
    $stmt = $mysqli->prepare('SELECT * FROM tbl_produk WHERE id_produk = ?');
    if ($stmt) {
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $product = $result->fetch_assoc();
        echo json_encode($product);
    } else {
        echo json_encode(['message' => 'Failed to fetch product']);
    }
}

function addProduct() {
    global $mysqli;
    $nama_produk = $_POST['nama_produk'];
    $harga_produk = $_POST['harga_produk'];
    $deskripsi_produk = $_POST['deskripsi_produk'];
    $stok_produk = $_POST['stok_produk'];
    $gambar_produk = uploadImage();

    if ($gambar_produk !== false) {
        $stmt = $mysqli->prepare('INSERT INTO tbl_produk (nama_produk, gambar_produk, harga_produk, deskripsi_produk, stok_produk) VALUES (?, ?, ?, ?, ?)');
        if ($stmt) {
            $stmt->bind_param('ssisi', $nama_produk, $gambar_produk, $harga_produk, $deskripsi_produk, $stok_produk);
            $stmt->execute();
            echo json_encode(['message' => 'Product added']);
        } else {
            echo json_encode(['message' => 'Failed to add product']);
        }
    } else {
        echo json_encode(['message' => 'Failed to upload image']);
    }
}

function updateProduct($id) {
    global $mysqli;
    parse_str(file_get_contents("php://input"), $data);
    $nama_produk = $data['nama_produk'];
    $harga_produk = $data['harga_produk'];
    $deskripsi_produk = $data['deskripsi_produk'];
    $stok_produk = $data['stok_produk'];
    $gambar_produk = uploadImage();

    if ($gambar_produk !== false) {
        $stmt = $mysqli->prepare('UPDATE tbl_produk SET nama_produk = ?, gambar_produk = ?, harga_produk = ?, deskripsi_produk = ?, stok_produk = ? WHERE id_produk = ?');
        if ($stmt) {
            $stmt->bind_param('ssisi', $nama_produk, $gambar_produk, $harga_produk, $deskripsi_produk, $stok_produk, $id);
            $stmt->execute();
            echo json_encode(['message' => 'Product updated']);
        } else {
            echo json_encode(['message' => 'Failed to update product']);
        }
    } else {
        echo json_encode(['message' => 'Failed to upload image']);
    }
}

function deleteProduct($id) {
    global $mysqli;
    $stmt = $mysqli->prepare('DELETE FROM tbl_produk WHERE id_produk = ?');
    if ($stmt) {
        $stmt->bind_param('i', $id);
        $stmt->execute();
        echo json_encode(['message' => 'Product deleted']);
    } else {
        echo json_encode(['message' => 'Failed to delete product']);
    }
}

function uploadImage() {
    if (isset($_FILES['gambar_produk']) && $_FILES['gambar_produk']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['gambar_produk']['tmp_name'];
        $fileName = $_FILES['gambar_produk']['name'];
        $fileSize = $_FILES['gambar_produk']['size'];
        $fileType = $_FILES['gambar_produk']['type'];
        $fileNameCmps = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));

        $allowedfileExtensions = array('jpg', 'gif', 'png', 'jpeg');
        if (in_array($fileExtension, $allowedfileExtensions)) {
            $uploadFileDir = 'gambar/';
            $dest_path = $uploadFileDir . $fileName;

            if (move_uploaded_file($fileTmpPath, $dest_path)) {
                return $fileName;
            }
        }
    }
    return false;
}
?>

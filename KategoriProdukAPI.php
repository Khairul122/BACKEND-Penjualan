<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require 'conn.php';

$category = isset($_GET['category']) ? $_GET['category'] : '';

if ($category == 'All' || empty($category)) {
    $sql = "SELECT * FROM tbl_produk";
} else {
    $sql = "SELECT * FROM tbl_produk WHERE id_kategori = (SELECT id_kategori FROM tbl_kategori WHERE nama_kategori = '$category')";
}

$result = $conn->query($sql);

if ($result) {
    $products = array();
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
    echo json_encode($products);
} else {
    echo json_encode(array('status' => 'error', 'message' => $conn->error));
}

$conn->close();
?>

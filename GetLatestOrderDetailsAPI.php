<?php
include 'conn.php';

header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['id_pengguna'])) {
        $id_pengguna = intval($_GET['id_pengguna']);
        
        $sql = "SELECT p.*, u.nama_pengguna, u.email, u.password, u.no_telepon, u.id_alamat, a.alamat, a.ongkir
                FROM tbl_penjualan p 
                JOIN tbl_pengguna u ON p.id_pengguna = u.id_pengguna 
                JOIN tbl_alamat a ON u.id_alamat = a.id_alamat
                WHERE p.id_pengguna = ? 
                ORDER BY p.created_at DESC 
                LIMIT 1";
        
        // Cek jika query berhasil disiapkan
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("i", $id_pengguna);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $payment = $result->fetch_assoc();
                echo json_encode($payment);
            } else {
                echo json_encode(['message' => 'No payment found for this user']);
            }
            
            $stmt->close();
        } else {
            // Menampilkan pesan kesalahan jika query gagal disiapkan
            echo json_encode(['error' => 'Failed to prepare statement: ' . $conn->error]);
        }
    } else {
        echo json_encode(['error' => 'User ID not provided']);
    }

    $conn->close();
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
}
?>

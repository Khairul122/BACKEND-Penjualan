<?php
include 'conn.php'; // Include your database connection file

header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    $idPengguna = $input['id_pengguna'];

    // Fetch id_keranjang from tbl_penjualan based on id_pengguna
    $sqlFetch = "SELECT id_keranjang FROM tbl_penjualan WHERE id_pengguna = ?";
    $stmtFetch = $conn->prepare($sqlFetch);

    if ($stmtFetch === false) {
        echo json_encode(["status" => "error", "message" => "Failed to prepare fetch statement: " . $conn->error]);
        exit();
    }

    $stmtFetch->bind_param('i', $idPengguna);
    $stmtFetch->execute();
    $result = $stmtFetch->get_result();

    if ($result->num_rows > 0) {
        $errors = [];
        while ($row = $result->fetch_assoc()) {
            $idKeranjang = $row['id_keranjang'];

            // Update status in tbl_keranjang based on id_keranjang
            $sqlUpdate = "UPDATE tbl_keranjang SET status = 'Sudah Dibayar' WHERE id_keranjang = ?";
            $stmtUpdate = $conn->prepare($sqlUpdate);

            if ($stmtUpdate === false) {
                $errors[] = "Failed to prepare update statement for id_keranjang $idKeranjang: " . $conn->error;
                continue;
            }

            $stmtUpdate->bind_param('i', $idKeranjang);

            if (!$stmtUpdate->execute()) {
                $errors[] = "Failed to update status for id_keranjang $idKeranjang: " . $stmtUpdate->error;
            }

            $stmtUpdate->close();
        }

        if (count($errors) > 0) {
            echo json_encode(["status" => "error", "message" => "Errors occurred while updating status", "errors" => $errors]);
        } else {
            echo json_encode(["status" => "success", "message" => "Status updated successfully"]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "No records found for id_pengguna $idPengguna"]);
    }

    $stmtFetch->close();
}

$conn->close();
?>

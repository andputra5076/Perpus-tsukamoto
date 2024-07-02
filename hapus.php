<?php
// Include koneksi database
include_once 'MyFrameworks/DBConnection.php';

// Pastikan ID telah dikirimkan
if (isset($_POST['id'])) {
    // Ambil ID dari data yang akan dihapus
    $id = $_POST['id'];

    try {
        // Buat instance dari koneksi database
        $dbConnection = new DBConnection();
        $connection = $dbConnection->getDBConnection();

        // Query untuk menghapus data
        $query = "DELETE FROM tbl_kunjungan WHERE id = :id";
        $stmt = $connection->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        // Eksekusi query
        if ($stmt->execute()) {
            // Jika berhasil dihapus
            echo json_encode(array('status' => 'success'));
        } else {
            // Jika gagal menghapus
            echo json_encode(array('status' => 'error', 'message' => 'Gagal menghapus data.'));
        }
    } catch (PDOException $e) {
        // Tangani error jika koneksi atau query gagal
        echo json_encode(array('status' => 'error', 'message' => $e->getMessage()));
    }
} else {
    // Jika ID tidak dikirimkan
    echo json_encode(array('status' => 'error', 'message' => 'ID tidak dikirimkan.'));
}
?>

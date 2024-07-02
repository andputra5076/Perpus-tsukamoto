<?php
// Mengaktifkan session
session_start();

// Cek apakah form reset password sudah disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data dari form
    $email = $_POST["email"];
    $password = '';
    if (isset(($_POST["password"]))) {
        $password = md5($_POST["password"]);
    }

   

    // Include DBConnection untuk mendapatkan koneksi database
    include_once 'MyFrameworks/DBConnection.php';
    $dbConnection = new DBConnection();
    $connection = $dbConnection->getDBConnection();

    if (!$connection) {
        die("Connection to database failed: " . mysqli_connect_error());
    }

    // Query untuk memeriksa email dalam tabel tb_user
    $query = "SELECT * FROM tb_user WHERE email=?";
    $stmt = mysqli_prepare($connection, $query);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    // Jika email tidak ditemukan, kembali ke halaman forgot_password.php dengan pesan alert JavaScript
    if (mysqli_num_rows($result) == 0) {
        mysqli_stmt_close($stmt);
        mysqli_close($connection);
        echo '<script>alert("Email not found. Please enter a registered email address.");';
        echo 'window.location.href = "forgot_password.php";</script>';
        exit();
    }else{
        if (isset($_POST["password"])) {
            $query_update_password = "UPDATE tb_user SET password=? WHERE email=?";
            $stmt_update_password = mysqli_prepare($connection, $query_update_password);
            mysqli_stmt_bind_param($stmt_update_password, "ss", $password, $email);
            mysqli_stmt_execute($stmt_update_password);
            echo '<script>alert("Password has been change");';
            echo 'window.location.href = "index.php";</script>';
        }else{
            header("Location: Ubah_Password.php");

        }
    
    }

    // Email ditemukan, ambil username untuk langkah selanjutnya
    $user = mysqli_fetch_assoc($result);
    $username = $user['username'];

    // Simpan email dalam session untuk digunakan di halaman Ubah_Password.php
    $_SESSION['reset_email'] = $email;

} else {
    // Redirect jika form tidak disubmit
    header("Location: forgot_password.php");
    exit();
}
?>
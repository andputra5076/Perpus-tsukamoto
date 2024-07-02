<?php
include_once 'MyFrameworks/DBQuery.php';

if (isset($_POST["IDSubmit"])) {
    $username = $_POST["username"];
    $password = md5($_POST["password"]);

    // Ambil koneksi database
    $connection = DBConnection::getdbconnection();

    // Periksa koneksi database
    if (!$connection) {
        die("Koneksi ke database gagal: " . mysqli_connect_error());
    }

    // Query untuk memeriksa username dan password di database
    $query = "SELECT * FROM tb_user WHERE username=? AND password=?";
    $stmt = mysqli_prepare($connection, $query);
    mysqli_stmt_bind_param($stmt, "ss", $username, $password);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result)) {
        // Password cocok, set sesi dan arahkan ke home.php
        session_start();
        $_SESSION["User-Login"] = ''; // digunakan untuk menandai login
        $_SESSION["LoginFirst"] = $username;
        header("Location: home.php");
        exit();
    } else {
        // Password tidak cocok, redirect ke halaman login dengan pesan error
        header("Location: index.php?error=password");
        exit();
    }
} else {
    // Jika IDSubmit tidak diset, redirect ke halaman login
    header("Location: index.php");
    exit();
}
?>

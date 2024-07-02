<?php
include_once 'MyFrameworks/DBQuery.php';

if (isset($_POST["registerSubmit"])) {
    $nama = $_POST["nama"];
    $username = $_POST["username"];
    $email = $_POST["email"];
    $password = md5($_POST["password"]); // Anda sebaiknya menggunakan hashing yang lebih aman, misalnya bcrypt
    $kode_pegawai = $_POST["kode_pegawai"];

    // Ambil koneksi database
    $connection = DBConnection::getdbconnection();

    // Periksa koneksi database
    if (!$connection) {
        die("Koneksi ke database gagal: " . mysqli_connect_error());
    }

    // Query untuk mengecek apakah username atau email sudah terdaftar
    $queryCheck = "SELECT * FROM tb_user WHERE username=? OR email=?";
    $stmtCheck = mysqli_prepare($connection, $queryCheck);
    mysqli_stmt_bind_param($stmtCheck, "ss", $username, $email);
    mysqli_stmt_execute($stmtCheck);
    $resultCheck = mysqli_stmt_get_result($stmtCheck);

    if (mysqli_num_rows($resultCheck) > 0) {
        // Username atau email sudah terdaftar, redirect ke halaman registrasi dengan pesan error
        header("Location: register.php?error=username_email_taken");
        exit();
    } else {
        // Username dan email belum terdaftar, lanjutkan proses registrasi
        $queryRegister = "INSERT INTO tb_user (nama, username, email, password, kode_pegawai) VALUES (?, ?, ?, ?, ?)";
        $stmtRegister = mysqli_prepare($connection, $queryRegister);
        mysqli_stmt_bind_param($stmtRegister, "sssss", $nama, $username, $email, $password, $kode_pegawai);
        mysqli_stmt_execute($stmtRegister);

        if ($stmtRegister) {
            // Registrasi berhasil, set session dan arahkan ke halaman home.php
            session_start();
            $_SESSION["User-Login"] = ''; // digunakan untuk menandai login
            $_SESSION["LoginFirst"] = $username;
            header("Location: home.php");
            exit();
        } else {
            // Registrasi gagal, redirect ke halaman registrasi dengan pesan error
            header("Location: register.php?error=register_failed");
            exit();
        }
    }
} else {
    // Jika registerSubmit tidak diset, redirect ke halaman registrasi
    header("Location: register.php");
    exit();
}
?>

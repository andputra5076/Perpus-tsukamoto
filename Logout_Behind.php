<?php
// Mulai sesi yang sudah ada
session_start();

// Hancurkan semua variabel sesi
$_SESSION = array();

// Hancurkan sesi
session_destroy();

// Arahkan kembali ke halaman login atau halaman lain yang sesuai
header("Location: index.php");
exit();
?>

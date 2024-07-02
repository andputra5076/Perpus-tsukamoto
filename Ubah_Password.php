<?php
session_start();

// Cek apakah ada email yang disimpan dalam session
if (!isset($_SESSION['reset_email'])) {
    // Jika tidak ada, kembalikan ke halaman forgot_password.php atau halaman lainnya
    header("Location: forgot_password.php");
    exit();
}

// Ambil email dari session
$email = $_SESSION['reset_email'];

// Include DBConnection untuk mendapatkan koneksi database
include_once 'MyFrameworks/DBConnection.php';
$dbConnection = new DBConnection();
$connection = $dbConnection->getDBConnection();

if (!$connection) {
    die("Connection to database failed: " . mysqli_connect_error());
}

// Proses form untuk mengubah password
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data dari form
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm_password"];

    // Validasi password
    if ($password != $confirm_password) {
        echo '<script>alert("Passwords do not match. Please try again.");';
        echo 'window.location.href = "Ubah_Password.php";</script>';
        exit();
    }

    // Hash password sebelum menyimpan ke database
    $hashed_password = md5($password); // Menggunakan md5 untuk hash password

    // Periksa apakah password baru sama dengan password sebelumnya
    $query_check_password = "SELECT password FROM tb_user WHERE email=?";
    $stmt_check_password = mysqli_prepare($connection, $query_check_password);
    mysqli_stmt_bind_param($stmt_check_password, "s", $email);
    mysqli_stmt_execute($stmt_check_password);
    $result_check_password = mysqli_stmt_get_result($stmt_check_password);
    $user = mysqli_fetch_assoc($result_check_password);
    $current_password = $user['password'];

    if (md5($password) === $current_password) {
        echo '<script>alert("Password is the same as the current password. Please choose a different password.");';
        echo 'window.location.href = "Ubah_Password.php";</script>';
        exit();
    }

    // Update password di database berdasarkan email
    $query_update_password = "UPDATE tb_user SET password=? WHERE email=?";
    $stmt_update_password = mysqli_prepare($connection, $query_update_password);
    mysqli_stmt_bind_param($stmt_update_password, "ss", $hashed_password, $email);
    mysqli_stmt_execute($stmt_update_password);

    // Hapus email dari session setelah reset password berhasil
    unset($_SESSION['reset_email']);

    // Tampilkan pesan sukses
    echo '<p>Password has been successfully reset.</p>';
    echo '<p><a href="index.php">Click here to login</a></p>';

    // Tutup statement
    mysqli_stmt_close($stmt_update_password);
    mysqli_close($connection);
}
?>



<!doctype html>
<html lang="en">

<!-- Mirrored from demos.creative-tim.com/paper-dashboard-pro/login.html by HTTrack Website Copier/3.x [XR&CO'2014], Thu, 13 Jun 2024 00:15:17 GMT -->
<!-- Added by HTTrack -->
<meta http-equiv="content-type" content="text/html;charset=utf-8" /><!-- /Added by HTTrack -->

<head>
    <meta charset="utf-8" />
    <link rel="apple-touch-icon" sizes="76x76" href="assets/img/apple-icon.png">
    <link rel="icon" type="image/png" sizes="96x96" href="assets/img/favicon.png">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <title>Ubah Password | Dinas Arsip dan Perpustakaan - Fuzzy Tsukamoto</title>

    <link rel="canonical" href="https://www.creative-tim.com/product/paper-dashboard-pro" />
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" name="viewport" />
    <meta name="viewport" content="width=device-width" />

    <link href="assets/css/bootstrap.min.css" rel="stylesheet" />

    <link href="assets/css/paper-dashboard.css" rel="stylesheet" />

    <link href="assets/css/demo.css" rel="stylesheet" />

    <link href="assets/font-awesome/latest/css/font-awesome.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Muli:400,300" rel="stylesheet" type="text/css">
    <link href="assets/css/themify-icons.css" rel="stylesheet">

    <script>
        (function(w, d, s, l, i) {
            w[l] = w[l] || [];
            w[l].push({
                'gtm.start': new Date().getTime(),
                event: 'gtm.js'
            });
            var f = d.getElementsByTagName(s)[0],
                j = d.createElement(s),
                dl = l != 'dataLayer' ? '&l=' + l : '';
            j.async = true;
            j.src =
                '../www.googletagmanager.com/gtm5445.html?id=' + i + dl;
            f.parentNode.insertBefore(j, f);
        })(window, document, 'script', 'dataLayer', 'GTM-NKDMSK6');
    </script>

</head>

<body>

    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-NKDMSK6" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>

    <nav class="navbar navbar-transparent navbar-absolute">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navigation-example-2">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="#">Perpustakaan - Prediksi Fuzzy Tsukamoto</a>
            </div>
            <div class="collapse navbar-collapse">
                <ul class="nav navbar-nav navbar-right">
                </ul>
            </div>
        </div>
    </nav>
    <div class="wrapper wrapper-full-page">
        <div class="full-page login-page" data-color data-image="assets/img/background/background-2.jpg">

            <div class="content">
                <div class="container">
                    <div class="row">
                        <div class="col-md-4 col-sm-6 col-md-offset-4 col-sm-offset-3">
                            <!-- forgot_password.php -->
                            <form method="POST" action="Reset_Password.php">
                                <div class="card" data-background="color" data-color="blue">
                                    <div class="card-header">
                                        <h3 class="card-title">Reset Password</h3>
                                    </div>
                                    <input type="hidden" name="email" value="<?=$email?>">
                                    <div class="card-content">
                                        <div class="form-group">
                                            <label>New Password:</label><br>
                                            <input type="password" name="password" placeholder="Enter new password" class="form-control input-no-border" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Confirm Password:</label><br>
                                            <input type="password" name="confirm_password" placeholder="Confirm new password" class="form-control input-no-border" required>
                                        </div>
                                    </div>
                                    <div class="card-footer text-center">
                                        <input type="submit" value="Reset Password" class="btn btn-fill btn-wd">
                                        <!-- Tombol batal untuk kembali ke halaman login -->
                                        <a href="index.php" class="btn btn-primary btn-wd">Cancel</a>
                                    </div>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
            <footer class="footer footer-transparent">
                <div class="container">
                    <div class="copyright">
                        &copy;
                        <script>
                            document.write(new Date().getFullYear())
                        </script>, made with <i class="fa fa-heart heart"></i> by <a href="https://www.ronstudiosoftware.com/">Ronstudio Software Jember</a>
                    </div>
                </div>
            </footer>
        </div>
    </div>
    <script defer src="https://static.cloudflareinsights.com/beacon.min.js/vcd15cbe7772f49c399c6a5babf22c1241717689176015" integrity="sha512-ZpsOmlRQV6y907TI0dKBHq9Md29nnaEIPlkf84rnaERnq6zvWvPUqr2ft8M1aS28oN72PdrCzSjY4U6VaAw1EQ==" data-cf-beacon='{"rayId":"892de05b1f275f42","b":1,"version":"2024.4.1","token":"1b7cbb72744b40c580f8633c6b62637e"}' crossorigin="anonymous"></script>
</body>

<script src="assets/js/jquery.min.js" type="text/javascript"></script>
<script src="assets/js/jquery-ui.min.js" type="text/javascript"></script>
<script src="assets/js/perfect-scrollbar.min.js" type="text/javascript"></script>
<script src="assets/js/bootstrap.min.js" type="text/javascript"></script>

<script src="assets/js/jquery.validate.min.js"></script>

<script src="assets/js/es6-promise-auto.min.js"></script>

<script src="assets/js/moment.min.js"></script>

<script src="assets/js/bootstrap-datetimepicker.js"></script>

<script src="assets/js/bootstrap-selectpicker.js"></script>

<script src="assets/js/bootstrap-switch-tags.js"></script>

<script src="assets/js/jquery.easypiechart.min.js"></script>

<script src="assets/js/chartist.min.js"></script>

<script src="assets/js/bootstrap-notify.js"></script>

<script src="assets/js/sweetalert2.js"></script>

<script src="assets/js/jquery-jvectormap.js"></script>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAFPQibxeDaLIUHsC6_KqDdFaUdhrbhZ3M"></script>

<script src="assets/js/jquery.bootstrap.wizard.min.js"></script>

<script src="assets/js/bootstrap-table.js"></script>

<script src="assets/js/jquery.datatables.js"></script>

<script src="assets/js/fullcalendar.min.js"></script>

<script src="assets/js/paper-dashboard.js"></script>

<script src="assets/js/jquery.sharrre.js"></script>

<script src="assets/js/demo.js"></script>
<script>
    // Facebook Pixel Code Don't Delete
    ! function(f, b, e, v, n, t, s) {
        if (f.fbq) return;
        n = f.fbq = function() {
            n.callMethod ?
                n.callMethod.apply(n, arguments) : n.queue.push(arguments)
        };
        if (!f._fbq) f._fbq = n;
        n.push = n;
        n.loaded = !0;
        n.version = '2.0';
        n.queue = [];
        t = b.createElement(e);
        t.async = !0;
        t.src = v;
        s = b.getElementsByTagName(e)[0];
        s.parentNode.insertBefore(t, s)
    }(window,
        document, 'script', 'connect.facebook.net/en_US/fbevents.js');

    try {
        fbq('init', '111649226022273');
        fbq('track', "PageView");

    } catch (err) {
        console.log('Facebook Track Error:', err);
    }
</script>
<noscript>
    <img height="1" width="1" style="display:none" src="https://www.facebook.com/tr?id=111649226022273&amp;ev=PageView&amp;noscript=1" />
</noscript>
<script type="text/javascript">
    $().ready(function() {
        demo.checkFullPageBackgroundImage();

        setTimeout(function() {
            // after 1000 ms we add the class animated to the login/register card
            $('.card').removeClass('card-hidden');
        }, 700)
    });
</script>

<!-- Mirrored from demos.creative-tim.com/paper-dashboard-pro/login.html by HTTrack Website Copier/3.x [XR&CO'2014], Thu, 13 Jun 2024 00:15:33 GMT -->

</html>
<?php
session_start();

// Periksa apakah ada sesi LoginFirst yang sudah diatur dan valid
if (!isset($_SESSION['LoginFirst'])) {
    header("Location: index.php");
    exit();
}

?>
<?php
include 'layouts/header.php';
?>

<body>

    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-NKDMSK6" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>

    <div class="wrapper">
        <div class="sidebar" data-background-color="brown" data-active-color="danger">

            <div class="logo">
                <a href="#" class="simple-text logo-mini">
                    P
                </a>
                <a href="#" class="simple-text logo-normal">
                    Perpustakaan
                </a>
            </div>
            <div class="sidebar-wrapper">

                <ul class="nav">
                    <ul class="nav">
                        <li>
                            <a href="home.php">
                                <i class="ti-home"></i>
                                <p>Dashboard</p>
                            </a>
                        </li>
                        <li>
                            <a href="data-pengunjung.php">
                                <i class="ti-server"></i>
                                <p>Data Pengunjung</p>
                            </a>
                        </li>
                        <li class="active">
                            <a href="prediksi.php">
                                <i class="ti-stats-up"></i>
                                <p>Forum Prediksi</p>
                            </a>
                        </li>
                        <li>
                            <a href="hasil.php">
                                <i class="ti-clipboard"></i>
                                <p>Hasil</p>
                            </a>
                        </li>

                        <li>
                            <a href="Logout_Behind.php">
                                <i class="ti-arrow-left"></i>
                                <p>Logout</p>
                            </a>
                        </li>
                    </ul>
                </ul>
            </div>
        </div>
        <div class="main-panel">
            <nav class="navbar navbar-default">
                <div class="container-fluid">
                    <div class="navbar-minimize">
                        <button id="minimizeSidebar" class="btn btn-fill btn-icon"><i class="ti-more-alt"></i></button>
                    </div>
                </div>
            </nav>

            <div class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12">
                            <h2 class="mt-4 mb-4">Prediksi Fuzzy Tsukamoto</h2>
                            <div class="card">
                                <div class="card-content">
                                    <?php
                                    // Include the DBConnection.php file to establish a database connection
                                    include_once 'MyFrameworks/DBConnection.php';

                                    // Ensure data is sent from the form
                                    if ($_SERVER["REQUEST_METHOD"] == "POST") {
                                        // Validate and retrieve data from the form
                                        if (isset($_POST['tanggal_kunjungan'])) {
                                            $tahun_kunjungan = $_POST['tanggal_kunjungan'];

                                            // Establish database connection
                                            $dbConnection = new DBConnection();
                                            $connection = $dbConnection->getDBConnection();

                                            // Check if data exists in the database for each column
                                            $query_check = "SELECT COUNT(*) as count 
                        FROM tbl_kunjungan 
                        WHERE YEAR(tanggal) = '$tahun_kunjungan'";
                                            $result_check = mysqli_query($connection, $query_check);

                                            if ($result_check) {
                                                $row = mysqli_fetch_assoc($result_check);

                                                // If any data exists, send success response with the count
                                                if ($row['count'] > 0) {
                                                    mysqli_close($connection);

                                                    // Redirect to hasil.php with the form data
                                                    echo "<script>
                    window.location.href = 'hasil.php?tahun_kunjungan={$tahun_kunjungan}';
                </script>";
                                                    exit();
                                                } else {
                                                    // No matching data, send error response with SweetAlert
                                                    mysqli_close($connection);
                                                    echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@10'></script>";
                                                    echo "<script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Data Tidak Ditemukan',
                        text: 'Data yang dimasukkan tidak ada di database.',
                        showConfirmButton: true,
                        confirmButtonText: 'OK'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = 'prediksi.php';
                        }
                    });
                </script>";
                                                    exit();
                                                }
                                            } else {
                                                // Query execution error
                                                $response = array('status' => 'error', 'message' => 'Error executing query: ' . mysqli_error($connection));
                                                echo json_encode($response);
                                                exit();
                                            }
                                        } else {
                                            // Missing parameters
                                            $response = array('status' => 'error', 'message' => 'Invalid request. Please provide all required parameters.');
                                            echo json_encode($response);
                                            exit();
                                        }
                                    }
                                    ?>
                                    <form action="prediksi.php" method="post">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="tanggal_kunjungan">Tanggal Kunjungan:</label>
                                                    <select class="form-control" id="tanggal_kunjungan" name="tanggal_kunjungan" required>
                                                        <option value="2022">2022</option>
                                                        <option value="2023">2023</option>
                                                        <option value="2024">2024</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6 align-self-end">
                                                <div class="form-group">
                                                    <button type="submit" class="btn btn-primary btn-block">Prediksi</button>
                                                </div>
                                            </div>
                                            <div class="col-md-6 align-self-end">
                                                <div class="form-group">
                                                    <button type="reset" class="btn btn-secondary btn-block">Batal</button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <footer class="footer">
                <div class="container-fluid">
                    <nav class="pull-left">
                        <ul>
                            <li>
                                <a href="#">
                                    Library Aziza Azka.
                                </a>
                            </li>
                            <li>
                                <a href="https://blog.creative-tim.com/">
                                    Blog
                                </a>
                            </li>
                            <li>
                                <a href="https://www.creative-tim.com/license">
                                    Licenses
                                </a>
                            </li>
                        </ul>
                    </nav>
                    <div class="copyright pull-right">
                        &copy;
                        <script>
                            document.write(new Date().getFullYear())
                        </script>, made with <i class="fa fa-heart heart"></i> by <a href="https://www.ronstudiosoftware.com/">Ronstudio Software Jember</a>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <script defer src="https://static.cloudflareinsights.com/beacon.min.js/vcd15cbe7772f49c399c6a5babf22c1241717689176015" integrity="sha512-ZpsOmlRQV6y907TI0dKBHq9Md29nnaEIPlkf84rnaERnq6zvWvPUqr2ft8M1aS28oN72PdrCzSjY4U6VaAw1EQ==" data-cf-beacon='{"rayId":"892dd76afc3c3fa7","b":1,"version":"2024.4.1","token":"1b7cbb72744b40c580f8633c6b62637e"}' crossorigin="anonymous"></script>
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
    var $table = $('#bootstrap-table');

    $(function() {
        $table.bootstrapTable({
            toolbar: ".toolbar",
            clickToSelect: true,
            showRefresh: true,
            search: true,
            showToggle: true,
            showColumns: true,
            pagination: true,
            searchAlign: 'left',
            pageSize: 8,
            pageList: [8, 10, 25, 50, 100],
            formatShowingRows: function(pageFrom, pageTo, totalRows) {
                // Do nothing here, we don't want to show the text "showing x of y from..."
            },
            formatRecordsPerPage: function(pageNumber) {
                return pageNumber + " rows visible";
            },
            icons: {
                refresh: 'fa fa-refresh',
                toggle: 'fa fa-th-list',
                columns: 'fa fa-columns',
                detailOpen: 'fa fa-plus-circle',
                detailClose: 'ti-close'
            }
        });

        // Activate tooltips after the data table is initialized
        $('[rel="tooltip"]').tooltip();

        // Reset table view when window is resized
        $(window).resize(function() {
            $table.bootstrapTable('resetView');
        });
    });
</script>

<script type="text/javascript">
    $(function() {
        $('#datetimepicker').datetimepicker({
            format: 'YYYY-MM-DD HH:mm:ss',
            locale: 'id'
        });



        $('#tambahButton').click(function() {
            // Validasi form sebelum submit
            var form = $('#tambahForm')[0];
            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }

            var formData = $('#tambahForm').serializeArray();
            var datetime = $('#tanggal_waktu').val().split(' ');

            formData.push({
                name: 'tanggal',
                value: datetime[0]
            });
            formData.push({
                name: 'waktu',
                value: datetime[1]
            });

            $.ajax({
                type: 'POST',
                url: 'prediksi.php',
                data: $.param(formData),
                success: function(response) {
                    then(function() {
                        window.location = "prediksi.php";
                    });
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                    Swal.fire({
                        title: "Error!",
                        text: "Terjadi kesalahan. Data gagal ditambahkan.",
                        icon: "error",
                        confirmButtonText: "OK"
                    });
                }
            });
        });
    });
</script>
<script type="text/javascript">
    $(document).ready(function() {
        // Event ketika modal ditampilkan
        $('#tambahModal').on('shown.bs.modal', function() {
            // Simpan status modal (buka/tutup) ke localStorage
            localStorage.setItem('modalShown', 'true');
        });

        // Event ketika modal ditutup
        $('#tambahModal').on('hidden.bs.modal', function() {
            // Hapus status modal dari localStorage saat modal ditutup
            localStorage.removeItem('modalShown');
        });

        // Cek status modal saat halaman dimuat
        var modalShown = localStorage.getItem('modalShown');
        if (modalShown === 'true') {
            // Jika modal harus terbuka, tampilkan modal
            $('#tambahModal').modal('show');
        }

        // Event pada tombol "Tambah Data" atau form submit
        $('#tambahButton').click(function() {
            // Validasi form sebelum submit
            var form = $('#tambahForm')[0];
            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }
            // Lakukan pengiriman data atau operasi lainnya

            // Tampilkan SweetAlert
            Swal.fire({
                title: "Berhasil!",
                text: "Data Anda telah berhasil ditambahkan.",
                icon: "success",
                showConfirmButton: false,
                timer: 1500
            }).then(function() {
                // Setelah menampilkan SweetAlert, reload halaman setelah beberapa saat
                setTimeout(function() {
                    location.reload();
                }, 1000); // Ganti nilai 1000 (ms) sesuai kebutuhan
            });
        });
    });
</script>


<?php
include_once 'MyFrameworks/DBConnection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data dari form
    $nomer_kunjungan = $_POST['tambah_nomer_kunjungan'];
    $nama = $_POST['tambah_nama'];
    $jk = $_POST['tambah_jk'];
    $pekerjaan = $_POST['tambah_pekerjaan'];
    $pendidikan = $_POST['tambah_pendidikan'];
    $lokasi_ruangan = $_POST['tambah_lokasi_ruangan'];
    $tanggal = $_POST['tanggal'];
    $waktu = $_POST['waktu'];
    $informasi_dicari = $_POST['informasi_dicari'];

    // Koneksi ke database
    $dbConnection = new DBConnection();
    $connection = $dbConnection->getDBConnection();

    // Query untuk memasukkan data
    $query = "INSERT INTO tbl_kunjungan (nomer_kunjungan, nama, jk, pekerjaan, pendidikan, lokasi_ruangan, tanggal, waktu, informasi_dicari) 
              VALUES ('$nomer_kunjungan', '$nama', '$jk', '$pekerjaan', '$pendidikan', '$lokasi_ruangan', '$tanggal', '$waktu', '$informasi_dicari')";

    // Eksekusi query
    if (mysqli_query($connection, $query)) {
        echo "Data berhasil ditambahkan!";
    } else {
        echo "Error: " . $query . "<br>" . mysqli_error($connection);
    }

    // Tutup koneksi
    mysqli_close($connection);
}
?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<?php
// Misalnya, koneksi ke database menggunakan file koneksi yang sudah ada
include_once 'MyFrameworks/DBConnection.php';
$dbConnection = new DBConnection();
$connection = $dbConnection->getDBConnection();

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['id'])) {
    // Escape input untuk menghindari SQL injection
    $id = mysqli_real_escape_string($connection, $_GET['id']);

    // Query DELETE untuk menghapus data pengunjung berdasarkan id
    $query = "DELETE FROM tbl_kunjungan WHERE id = '$id'";

    // Eksekusi query
    if (mysqli_query($connection, $query)) {
        // Jika penghapusan berhasil, kirimkan pesan sukses ke halaman sebelumnya
        echo '<script>
                Swal.fire({
                    title: "Terhapus!",
                    text: "Data Anda telah berhasil dihapus.",
                    icon: "success",
                    showConfirmButton: false,
                    timer: 1500
                }).then(function() {
                    window.location = "prediksi.php";
                });
              </script>';
    } else {
        // Jika terjadi kesalahan, tampilkan pesan error
        echo '<script>
                Swal.fire({
                    title: "Gagal!",
                    text: "Gagal menghapus data.",
                    icon: "error",
                    showConfirmButton: false,
                    timer: 1500
                }).then(function() {
                    window.location = "prediksi.php";
                });
              </script>';
    }
}

// Tutup koneksi database jika sudah tidak digunakan lagi
mysqli_close($connection);
?>




<!-- Mirrored from demos.creative-tim.com/paper-dashboard-pro/bootstrap-table.html by HTTrack Website Copier/3.x [XR&CO'2014], Thu, 13 Jun 2024 00:09:24 GMT -->

</html>
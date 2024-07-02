<?php
session_start();

// Periksa apakah ada sesi LoginFirst yang sudah diatur dan valid
if (!isset($_SESSION['LoginFirst'])) {
    header("Location: index.php");
    exit();
}

?>

<!doctype html>
<html lang="en">

<!-- Mirrored from demos.creative-tim.com/paper-dashboard-pro/bootstrap-table.html by HTTrack Website Copier/3.x [XR&CO'2014], Thu, 13 Jun 2024 00:09:24 GMT -->
<!-- Added by HTTrack -->
<meta http-equiv="content-type" content="text/html;charset=utf-8" /><!-- /Added by HTTrack -->

<head>
    <meta charset="utf-8" />
    <link rel="apple-touch-icon" sizes="76x76" href="assets/img/apple-icon.png">
    <link rel="icon" type="image/png" sizes="96x96" href="assets/img/favicon.png">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <title>Dinas Arsip dan Perpustakaan - Fuzzy Tsukamoto</title>

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
                'www.googletagmanager.com/gtm5445.html?id=' + i + dl;
            f.parentNode.insertBefore(j, f);
        })(window, document, 'script', 'dataLayer', 'GTM-NKDMSK6');
    </script>

</head>

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
                        <li>
                            <a href="prediksi.php">
                                <i class="ti-stats-up"></i>
                                <p>Forum Prediksi</p>
                            </a>
                        </li>
                        <li class="active">
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
                            <div class="card">
                                <div class="card-content">
                                    <?php
                                    include_once 'MyFrameworks/DBConnection.php';

                                    // Koneksi ke database
                                    $dbConnection = new DBConnection();
                                    $connection = $dbConnection->getDBConnection();

                                    // Pastikan ada data yang dikirim dari URL
                                    if (isset($_GET['tahun_kunjungan'])) {
                                        // Ambil data dari URL
                                        $tahun_kunjungan = mysqli_real_escape_string($connection, $_GET['tahun_kunjungan']);

                                        // Ambil data kunjungan dari database berdasarkan tahun
                                        $query = "SELECT nama, nomer_kunjungan, jk, COUNT(*) as jumlah_kunjungan 
              FROM tbl_kunjungan 
              WHERE YEAR(tanggal) = '$tahun_kunjungan' 
              GROUP BY nama, nomer_kunjungan, jk 
              ORDER BY jumlah_kunjungan DESC";
                                        $result = mysqli_query($connection, $query);

                                        // Inisialisasi variabel untuk pengunjung terbanyak dan tersedikit
                                        $pengunjung_terbanyak = null;
                                        $pengunjung_tersedikit = null;

                                        // Ambil semua data pengunjung
                                        $pengunjung = [];
                                        while ($row = mysqli_fetch_assoc($result)) {
                                            $pengunjung[] = $row;
                                        }

                                        // Tentukan pengunjung terbanyak dan tersedikit
                                        if (!empty($pengunjung)) {
                                            $pengunjung_terbanyak = $pengunjung[0];
                                            $pengunjung_tersedikit = $pengunjung[count($pengunjung) - 1];
                                        }

                                        // Query data untuk grafik kunjungan bulanan dari pengunjung terbanyak
                                        $nama_terbanyak = $pengunjung_terbanyak['nama'];
                                        $queryGrafikTerbanyak = "SELECT MAX(tanggal) as tanggal, DAY(MAX(tanggal)) as tanggal_day, MONTH(MAX(tanggal)) as bulan, COUNT(*) as jumlah 
                         FROM tbl_kunjungan 
                         WHERE YEAR(tanggal) = '$tahun_kunjungan' AND nama = '$nama_terbanyak'
                         GROUP BY MONTH(tanggal)";

                                        // Eksekusi query untuk data grafik kunjungan terbanyak
                                        $resultGrafikTerbanyak = mysqli_query($connection, $queryGrafikTerbanyak);
                                        if (!$resultGrafikTerbanyak) {
                                            die('Error executing query for grafik terbanyak: ' . mysqli_error($connection));
                                        }

                                        // Ambil data dari hasil query untuk grafik kunjungan terbanyak
                                        $dataGrafikTerbanyak = [];
                                        while ($row = mysqli_fetch_assoc($resultGrafikTerbanyak)) {
                                            $dataGrafikTerbanyak[] = $row;
                                        }

                                        // Query data untuk grafik kunjungan bulanan dari pengunjung tersedikit
                                        $nama_tersedikit = $pengunjung_tersedikit['nama'];
                                        $queryGrafikTersedikit = "SELECT MAX(tanggal) as tanggal, DAY(MAX(tanggal)) as tanggal_day, MONTH(MAX(tanggal)) as bulan, COUNT(*) as jumlah 
                                        FROM tbl_kunjungan 
                                        WHERE YEAR(tanggal) = '$tahun_kunjungan' AND nama = '$nama_tersedikit'
                                        GROUP BY MONTH(tanggal)";

                                        // Eksekusi query untuk data grafik kunjungan tersedikit
                                        $resultGrafikTersedikit = mysqli_query($connection, $queryGrafikTersedikit);
                                        if (!$resultGrafikTersedikit) {
                                            die('Error executing query for grafik tersedikit: ' . mysqli_error($connection));
                                        }

                                        // Ambil data dari hasil query untuk grafik kunjungan tersedikit
                                        $dataGrafikTersedikit = [];
                                        while ($row = mysqli_fetch_assoc($resultGrafikTersedikit)) {
                                            $dataGrafikTersedikit[] = $row;
                                        }

                                        mysqli_close($connection);
                                    } else {
                                        // Jika tidak ada data yang dikirim dari URL, redirect ke halaman prediksi.php
                                        echo "<script>
          alert('Data tidak valid. Silakan ulangi pengisian form.');
          window.location.href = 'prediksi.php';
          </script>";
                                        exit();
                                    }
                                    ?>

                                    <h2 class="mt-4 text-center">Hasil Prediksi Kunjungan Tahun <?php echo htmlspecialchars($tahun_kunjungan); ?></h2>
                                    <div class="row">
                                        <div class="col-md-6 text-center">
                                            <h5>Pengunjung Terbanyak</h5>
                                            <?php if ($pengunjung_terbanyak) : ?>
                                                <p>Nama: <?php echo htmlspecialchars($pengunjung_terbanyak['nama']); ?></p>
                                                <p>Nomor Kunjungan: <?php echo htmlspecialchars($pengunjung_terbanyak['nomer_kunjungan']); ?></p>
                                                <p>Jenis Kelamin: <?php echo htmlspecialchars($pengunjung_terbanyak['jk']); ?></p>
                                                <p>Jumlah Kunjungan: <?php echo htmlspecialchars($pengunjung_terbanyak['jumlah_kunjungan']); ?></p>
                                            <?php else : ?>
                                                <p>Belum ada data.</p>
                                            <?php endif; ?>
                                        </div>
                                        <div class="col-md-6 text-center">
                                            <h5>Pengunjung Tersedikit</h5>
                                            <?php if ($pengunjung_tersedikit) : ?>
                                                <p>Nama: <?php echo htmlspecialchars($pengunjung_tersedikit['nama']); ?></p>
                                                <p>Nomor Kunjungan: <?php echo htmlspecialchars($pengunjung_tersedikit['nomer_kunjungan']); ?></p>
                                                <p>Jenis Kelamin: <?php echo htmlspecialchars($pengunjung_tersedikit['jk']); ?></p>
                                                <p>Jumlah Kunjungan: <?php echo htmlspecialchars($pengunjung_tersedikit['jumlah_kunjungan']); ?></p>
                                            <?php else : ?>
                                                <p>Belum ada data.</p>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    <div class="text-center mt-4">
                                        <h4>Grafik Kunjungan Per Bulan Tahun <?php echo htmlspecialchars($tahun_kunjungan); ?></h4>
                                        <canvas id="grafikKunjunganTerbanyak"></canvas>
                                        <canvas id="grafikKunjunganTersedikit" class="mt-4"></canvas>
                                    </div>

                                    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
                                    <script>
                                        const ctxTerbanyak = document.getElementById('grafikKunjunganTerbanyak').getContext('2d');
                                        const ctxTersedikit = document.getElementById('grafikKunjunganTersedikit').getContext('2d');

                                        const dataGrafikTerbanyak = <?php echo json_encode($dataGrafikTerbanyak); ?>;
                                        const dataGrafikTersedikit = <?php echo json_encode($dataGrafikTersedikit); ?>;

                                        // Membuat array bulanLabels dengan nama bulan dalam bahasa Indonesia
                                        const bulanLabels = Array.from({
                                            length: 12
                                        }, (_, i) => new Date(<?php echo $tahun_kunjungan; ?>, i, 1).toLocaleString('id-ID', {
                                            month: 'long'
                                        }));

                                        // Fungsi untuk mengubah dataGrafik menjadi format dataset yang sesuai
                                        const dataToDatasetFormat = (dataGrafik) => {
                                            const dataset = {};
                                            dataGrafik.forEach(data => {
                                                const date = new Date(data.tanggal);
                                                const month = date.getMonth();
                                                const day = date.getDate();

                                                if (!dataset[month]) {
                                                    dataset[month] = [];
                                                }

                                                dataset[month].push({
                                                    tanggal: day,
                                                    jumlah: parseInt(data.jumlah)
                                                });
                                            });

                                            return dataset;
                                        };

                                        // Mengubah dataGrafikTerbanyak dan dataGrafikTersedikit menjadi dataset
                                        const datasetTerbanyak = dataToDatasetFormat(dataGrafikTerbanyak);
                                        const datasetTersedikit = dataToDatasetFormat(dataGrafikTersedikit);

                                        // Fungsi untuk menghasilkan labels dan data untuk setiap bulan
                                        const generateMonthlyData = (dataset) => {
                                            const labels = [];
                                            const data = [];
                                            bulanLabels.forEach((bulan, index) => {
                                                if (dataset[index]) {
                                                    dataset[index].forEach(dayData => {
                                                        labels.push(`${dayData.tanggal} ${bulan}`);
                                                        data.push(dayData.jumlah);
                                                    });
                                                }
                                            });
                                            return {
                                                labels,
                                                data
                                            };
                                        };

                                        // Mendapatkan data untuk grafik kunjungan terbanyak
                                        const {
                                            labels: labelsTerbanyak,
                                            data: dataTerbanyak
                                        } = generateMonthlyData(datasetTerbanyak);

                                        // Mendapatkan data untuk grafik kunjungan tersedikit
                                        const {
                                            labels: labelsTersedikit,
                                            data: dataTersedikit
                                        } = generateMonthlyData(datasetTersedikit);

                                        // Konfigurasi grafik kunjungan terbanyak
                                        const grafikKunjunganTerbanyak = new Chart(ctxTerbanyak, {
                                            type: 'line',
                                            data: {
                                                labels: labelsTerbanyak,
                                                datasets: [{
                                                    label: 'Jumlah Kunjungan Terbanyak',
                                                    data: dataTerbanyak,
                                                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                                                    borderColor: 'rgba(54, 162, 235, 1)',
                                                    borderWidth: 2,
                                                    fill: false,
                                                    tension: 0.1
                                                }]
                                            },
                                            options: {
                                                scales: {
                                                    x: {
                                                        title: {
                                                            display: true,
                                                            text: 'Tanggal Kunjungan'
                                                        },
                                                        ticks: {
                                                            autoSkip: true,
                                                            maxTicksLimit: 10,
                                                            callback: function(value, index, values) {
                                                                return labelsTerbanyak[index];
                                                            }
                                                        },
                                                        grid: {
                                                            display: true,
                                                            color: 'rgba(200, 200, 200, 0.2)'
                                                        }
                                                    },
                                                    y: {
                                                        beginAtZero: true,
                                                        title: {
                                                            display: true,
                                                            text: 'Jumlah Kunjungan'
                                                        },
                                                        ticks: {
                                                            callback: function(value) {
                                                                const thresholds = [0, 25, 50, 100, 200];
                                                                if (thresholds.includes(value)) {
                                                                    return value;
                                                                }
                                                            }
                                                        },
                                                        grid: {
                                                            display: true,
                                                            color: 'rgba(200, 200, 200, 0.2)'
                                                        }
                                                    }
                                                },
                                                plugins: {
                                                    tooltip: {
                                                        callbacks: {
                                                            label: function(context) {
                                                                return `${context.label}: ${context.raw} kunjungan`;
                                                            }
                                                        }
                                                    },
                                                    legend: {
                                                        display: true,
                                                        position: 'top',
                                                        labels: {
                                                            font: {
                                                                size: 12
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                        });

                                        // Konfigurasi grafik kunjungan tersedikit
                                        const grafikKunjunganTersedikit = new Chart(ctxTersedikit, {
                                            type: 'line',
                                            data: {
                                                labels: labelsTersedikit,
                                                datasets: [{
                                                    label: 'Jumlah Kunjungan Tersedikit',
                                                    data: dataTersedikit,
                                                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                                                    borderColor: 'rgba(255, 99, 132, 1)',
                                                    borderWidth: 2,
                                                    fill: false,
                                                    tension: 0.1
                                                }]
                                            },
                                            options: {
                                                scales: {
                                                    x: {
                                                        title: {
                                                            display: true,
                                                            text: 'Tanggal Kunjungan'
                                                        },
                                                        ticks: {
                                                            autoSkip: true,
                                                            maxTicksLimit: 10,
                                                            callback: function(value, index, values) {
                                                                return labelsTersedikit[index];
                                                            }
                                                        },
                                                        grid: {
                                                            display: true,
                                                            color: 'rgba(200, 200, 200, 0.2)'
                                                        }
                                                    },
                                                    y: {
                                                        beginAtZero: true,
                                                        title: {
                                                            display: true,
                                                            text: 'Jumlah Kunjungan'
                                                        },
                                                        ticks: {
                                                            callback: function(value) {
                                                                const thresholds = [0, 25, 50, 100, 200];
                                                                if (thresholds.includes(value)) {
                                                                    return value;
                                                                }
                                                            }
                                                        },
                                                        grid: {
                                                            display: true,
                                                            color: 'rgba(200, 200, 200, 0.2)'
                                                        }
                                                    }
                                                },
                                                plugins: {
                                                    tooltip: {
                                                        callbacks: {
                                                            label: function(context) {
                                                                return `${context.label}: ${context.raw} kunjungan`;
                                                            }
                                                        }
                                                    },
                                                    legend: {
                                                        display: true,
                                                        position: 'top',
                                                        labels: {
                                                            font: {
                                                                size: 12
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                        });
                                    </script>
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
    var $table = $('#bootstrap-table');

    function operateFormatter(value, row, index) {
        return [
            '<div class="table-icons">',
            '<a rel="tooltip" title="View" class="btn btn-simple btn-info btn-icon table-action view" href="javascript:void(0)">',
            '<i class="ti-image"></i>',
            '</a>',
            '<a rel="tooltip" title="Edit" class="btn btn-simple btn-warning btn-icon table-action edit" href="javascript:void(0)">',
            '<i class="ti-pencil-alt"></i>',
            '</a>',
            '<a rel="tooltip" title="Remove" class="btn btn-simple btn-danger btn-icon table-action remove" href="javascript:void(0)">',
            '<i class="ti-close"></i>',
            '</a>',
            '</div>',
        ].join('');
    }

    $().ready(function() {
        window.operateEvents = {
            'click .view': function(e, value, row, index) {
                info = JSON.stringify(row);

                swal('You click view icon, row: ', info);
                console.log(info);
            },
            'click .edit': function(e, value, row, index) {
                info = JSON.stringify(row);

                swal('You click edit icon, row: ', info);
                console.log(info);
            },
            'click .remove': function(e, value, row, index) {
                console.log(row);
                $table.bootstrapTable('remove', {
                    field: 'id',
                    values: [row.id]
                });
            }
        };

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
            clickToSelect: false,
            pageList: [8, 10, 25, 50, 100],

            formatShowingRows: function(pageFrom, pageTo, totalRows) {
                //do nothing here, we don't want to show the text "showing x of y from..."
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

        //activate the tooltips after the data table is initialized
        $('[rel="tooltip"]').tooltip();

        $(window).resize(function() {
            $table.bootstrapTable('resetView');
        });
    });
</script>

<!-- Mirrored from demos.creative-tim.com/paper-dashboard-pro/bootstrap-table.html by HTTrack Website Copier/3.x [XR&CO'2014], Thu, 13 Jun 2024 00:09:24 GMT -->

</html>
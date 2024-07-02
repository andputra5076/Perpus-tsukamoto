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
include 'layouts/sidebar.php';
?>

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
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-content text-center">
                                <div class="toolbar">
                                <h1>Selamat Datang di Website Dinas Arsip dan Perpustakaan</h1>
<p>Website ini adalah sumber informasi utama untuk layanan dan kegiatan Dinas Arsip dan Perpustakaan. Kami bertujuan untuk menyediakan akses yang mudah dan transparan terhadap koleksi arsip dan perpustakaan kami. Dengan berbagai layanan dan informasi yang kami tawarkan, kami berkomitmen untuk memberikan nilai tambah kepada masyarakat dalam memenuhi kebutuhan mereka akan informasi dan literasi.</p>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include 'layouts/footer.php';

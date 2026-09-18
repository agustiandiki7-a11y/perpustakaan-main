<?php
require_once __DIR__ . '/../../app/config/Database.php';
require_once __DIR__ . '/../../app/helpers/auth.php';

mulaiSession();
cekRole(['admin',]);

// ======================================================
// KONEKSI DATABASE
// ======================================================

$database = new Database();
$db = $database->connect();

// ======================================================
// STATISTIK DASHBOARD
// ======================================================

// Total buku aktif
$totalBuku = $db->query("
    SELECT COUNT(*)
    FROM books
    WHERE status = 'aktif'
")->fetchColumn();

// Total pengguna aktif
$totalPengguna = $db->query("
    SELECT COUNT(*)
    FROM users
    WHERE status = 'aktif'
")->fetchColumn();

// Total seluruh peminjaman
$totalPeminjaman = $db->query("
    SELECT COUNT(*)
    FROM loans
")->fetchColumn();

// Total peminjaman menunggu
$totalMenunggu = $db->query("
    SELECT COUNT(*)
    FROM loans
    WHERE status = 'menunggu'
")->fetchColumn();

// Data user yang sedang login
$user = userLogin();

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title>Perpustakaan Digital</title>

    <meta
        content="width=device-width, initial-scale=1.0, shrink-to-fit=no"
        name="viewport">

    <link
        rel="icon"
        href="../assets/img/kaiadmin/favicon.ico"
        type="image/x-icon">

    <!-- ==================================================
         FONTS & ICONS
    =================================================== -->

    <script src="../assets/js/plugin/webfont/webfont.min.js"></script>

    <script>
        WebFont.load({
            google: {
                families: ["Public Sans:300,400,500,600,700"]
            },

            custom: {
                families: [
                    "Font Awesome 5 Solid",
                    "Font Awesome 5 Regular",
                    "Font Awesome 5 Brands",
                    "simple-line-icons"
                ],

                urls: ["../assets/css/fonts.min.css"
                ]
            },

            active: function() {
                sessionStorage.fonts = true;
            }
        });
    </script>

    <!-- ==================================================
         CSS
    =================================================== -->

    <link
        rel="stylesheet"
        href="../assets/css/bootstrap.min.css">

    <link
        rel="stylesheet"
        href="../assets/css/plugins.min.css">

    <link
        rel="stylesheet"
        href="../assets/css/kaiadmin.min.css">

    <link
        rel="stylesheet"
        href="../assets/css/demo.css">

</head>

<body>

    <div class="wrapper">

        <!-- ==================================================
             SIDEBAR
        =================================================== -->

        <?php include __DIR__ . '/../layout/sidebar.php'; ?>


        <!-- ==================================================
             MAIN PANEL
        =================================================== -->

        <div class="main-panel">

            <!-- ==================================================
                 HEADER
            =================================================== -->

            <div class="main-header">

                <!-- Logo Header -->

                <div class="main-header-logo">

                    <div
                        class="logo-header"
                        data-background-color="dark">

                        <a href="index.php" class="logo">

                            <img
                                src="assets/img/kaiadmin/logo_light.svg"
                                alt="navbar brand"
                                class="navbar-brand"
                                height="20">

                        </a>

                        <!-- Toggle Sidebar -->

                        <div class="nav-toggle">

                            <button
                                class="btn btn-toggle toggle-sidebar">

                                <i class="gg-menu-right"></i>

                            </button>

                            <button
                                class="btn btn-toggle sidenav-toggler">

                                <i class="gg-menu-left"></i>

                            </button>

                        </div>

                        <!-- More -->

                        <button
                            class="topbar-toggler more">

                            <i class="gg-more-vertical-alt"></i>

                        </button>

                    </div>

                </div>


                <!-- Navbar -->

                <?php include __DIR__ . '/../layout/navbar.php'; ?>

            </div>


            <!-- ==================================================
                 CONTENT
            =================================================== -->

            <div class="container">

            <div class="page-inner">

    <!-- HERO SECTION -->
    <div class="card border-0 shadow-lg mb-4"
        style="background: linear-gradient(135deg,#0d6efd,#0dcaf0); border-radius:20px;">
        <div class="card-body p-5 text-white">

            <div class="row align-items-center">

                <div class="col-md-8">
                    <h1 class="fw-bold mb-2">
                        📚 Perpustakaan Digital
                    </h1>

                    <p class="mb-3 fs-5">
                        Selamat Datang,
                        <strong><?= htmlspecialchars($_SESSION['nama'] ?? 'Pengguna') ?></strong>
                    </p>

                    <p class="opacity-75">
                        Kelola buku, pengguna, peminjaman dan laporan
                        perpustakaan dalam satu dashboard modern.
                    </p>

                    <a href="buku/tabel_buku.php"
                        class="btn btn-light btn-round me-2">
                        <i class="fas fa-book"></i>
                        Kelola Buku
                    </a>

                    <a href="peminjaman/tambah.php"
                        class="btn btn-warning btn-round">
                        <i class="fas fa-plus"></i>
                        Peminjaman Baru
                    </a>
                </div>

                <div class="col-md-4 text-center">
                    <i class="fas fa-book-open fa-7x opacity-75"></i>
                </div>

            </div>

        </div>
    </div>

    <!-- STATISTIK -->
    <div class="row">

        <div class="col-md-3">
            <div class="card border-0 shadow-lg"
                style="border-radius:20px;">
                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>
                            <h6 class="text-muted">
                                Total Buku
                            </h6>

                            <h2 class="fw-bold">
                                <?= $totalBuku ?>
                            </h2>
                        </div>

                        <div class="bg-primary text-white rounded-circle p-3">
                            <i class="fas fa-book fa-2x"></i>
                        </div>

                    </div>

                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-lg"
                style="border-radius:20px;">
                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>
                            <h6 class="text-muted">
                                Pengguna
                            </h6>

                            <h2 class="fw-bold">
                                <?= $totalPengguna ?>
                            </h2>
                        </div>

                        <div class="bg-success text-white rounded-circle p-3">
                            <i class="fas fa-users fa-2x"></i>
                        </div>

                    </div>

                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-lg"
                style="border-radius:20px;">
                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>
                            <h6 class="text-muted">
                                Peminjaman
                            </h6>

                            <h2 class="fw-bold">
                                <?= $totalPeminjaman ?>
                            </h2>
                        </div>

                        <div class="bg-warning text-white rounded-circle p-3">
                            <i class="fas fa-book-reader fa-2x"></i>
                        </div>

                    </div>

                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-lg"
                style="border-radius:20px;">
                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>
                            <h6 class="text-muted">
                                Menunggu
                            </h6>

                            <h2 class="fw-bold">
                                <?= $totalMenunggu ?>
                            </h2>
                        </div>

                        <div class="bg-danger text-white rounded-circle p-3">
                            <i class="fas fa-clock fa-2x"></i>
                        </div>

                    </div>

                </div>
            </div>
        </div>

    </div>

    <!-- MENU CEPAT -->
    <div class="row mt-4">

        <div class="col-md-4">

            <div class="card border-0 shadow-lg h-100"
                style="border-radius:20px;">

                <div class="card-body text-center">

                    <div class="mb-3">
                        <i class="fas fa-book fa-4x text-primary"></i>
                    </div>

                    <h4>Data Buku</h4>

                    <p class="text-muted">
                        Kelola seluruh koleksi buku perpustakaan.
                    </p>

                    <a href="buku/tabel_buku.php"
                        class="btn btn-primary btn-round">
                        Buka Menu
                    </a>

                </div>

            </div>

        </div>

        <div class="col-md-4">

            <div class="card border-0 shadow-lg h-100"
                style="border-radius:20px;">

                <div class="card-body text-center">

                    <div class="mb-3">
                        <i class="fas fa-users fa-4x text-success"></i>
                    </div>

                    <h4>Pengguna</h4>

                    <p class="text-muted">
                        Kelola admin dan petugas sistem.
                    </p>

                    <a href="pengguna/tabel_pengguna.php"
                        class="btn btn-success btn-round">
                        Buka Menu
                    </a>

                </div>

            </div>

        </div>

        <div class="col-md-4">

            <div class="card border-0 shadow-lg h-100"
                style="border-radius:20px;">

                <div class="card-body text-center">

                    <div class="mb-3">
                        <i class="fas fa-book-reader fa-4x text-warning"></i>
                    </div>

                    <h4>Peminjaman</h4>

                    <p class="text-muted">
                        Kelola transaksi peminjaman buku.
                    </p>

                    <a href="peminjaman/tabel_peminjaman.php"
                        class="btn btn-warning btn-round">
                        Buka Menu
                    </a>

                </div>

            </div>

        </div>

    </div>

    <!-- GRAFIK -->
    <div class="card border-0 shadow-lg mt-4"
        style="border-radius:20px;">

        <div class="card-header bg-white border-0">

            <h4 class="fw-bold">
                📈 Statistik Perpustakaan
            </h4>

        </div>

        <div class="card-body">

            <canvas id="statisticsChart"></canvas>

        </div>

    </div>

</div>

            </div>


            <!-- ==================================================
                 FOOTER
            =================================================== -->

            <?php include __DIR__ . '/../layout/footer.php'; ?>

        </div>


        <!-- ==================================================
             CUSTOM TEMPLATE
        =================================================== -->

        <div class="custom-template">

            <div class="title">
                Settings
            </div>

            <div class="custom-content">

                <div class="switcher">


                    <!-- LOGO HEADER -->

                    <div class="switch-block">

                        <h4>
                            Logo Header
                        </h4>

                        <div class="btnSwitch">

                            <button
                                type="button"
                                class="selected changeLogoHeaderColor"
                                data-color="dark"></button>

                            <button
                                type="button"
                                class="changeLogoHeaderColor"
                                data-color="blue"></button>

                            <button
                                type="button"
                                class="changeLogoHeaderColor"
                                data-color="purple"></button>

                            <button
                                type="button"
                                class="changeLogoHeaderColor"
                                data-color="light-blue"></button>

                            <button
                                type="button"
                                class="changeLogoHeaderColor"
                                data-color="green"></button>

                            <button
                                type="button"
                                class="changeLogoHeaderColor"
                                data-color="orange"></button>

                            <button
                                type="button"
                                class="changeLogoHeaderColor"
                                data-color="red"></button>

                            <button
                                type="button"
                                class="changeLogoHeaderColor"
                                data-color="white"></button>

                            <br>

                            <button
                                type="button"
                                class="changeLogoHeaderColor"
                                data-color="dark2"></button>

                            <button
                                type="button"
                                class="changeLogoHeaderColor"
                                data-color="blue2"></button>

                            <button
                                type="button"
                                class="changeLogoHeaderColor"
                                data-color="purple2"></button>

                            <button
                                type="button"
                                class="changeLogoHeaderColor"
                                data-color="light-blue2"></button>

                            <button
                                type="button"
                                class="changeLogoHeaderColor"
                                data-color="green2"></button>

                            <button
                                type="button"
                                class="changeLogoHeaderColor"
                                data-color="orange2"></button>

                            <button
                                type="button"
                                class="changeLogoHeaderColor"
                                data-color="red2"></button>

                        </div>

                    </div>


                    <!-- NAVBAR HEADER -->

                    <div class="switch-block">

                        <h4>
                            Navbar Header
                        </h4>

                        <div class="btnSwitch">

                            <button
                                type="button"
                                class="changeTopBarColor"
                                data-color="dark"></button>

                            <button
                                type="button"
                                class="changeTopBarColor"
                                data-color="blue"></button>

                            <button
                                type="button"
                                class="changeTopBarColor"
                                data-color="purple"></button>

                            <button
                                type="button"
                                class="changeTopBarColor"
                                data-color="light-blue"></button>

                            <button
                                type="button"
                                class="changeTopBarColor"
                                data-color="green"></button>

                            <button
                                type="button"
                                class="changeTopBarColor"
                                data-color="orange"></button>

                            <button
                                type="button"
                                class="changeTopBarColor"
                                data-color="red"></button>

                            <button
                                type="button"
                                class="selected changeTopBarColor"
                                data-color="white"></button>

                            <br>

                            <button
                                type="button"
                                class="changeTopBarColor"
                                data-color="dark2"></button>

                            <button
                                type="button"
                                class="changeTopBarColor"
                                data-color="blue2"></button>

                            <button
                                type="button"
                                class="changeTopBarColor"
                                data-color="purple2"></button>

                            <button
                                type="button"
                                class="changeTopBarColor"
                                data-color="light-blue2"></button>

                            <button
                                type="button"
                                class="changeTopBarColor"
                                data-color="green2"></button>

                            <button
                                type="button"
                                class="changeTopBarColor"
                                data-color="orange2"></button>

                            <button
                                type="button"
                                class="changeTopBarColor"
                                data-color="red2"></button>

                        </div>

                    </div>


                    <!-- SIDEBAR -->

                    <div class="switch-block">

                        <h4>
                            Sidebar
                        </h4>

                        <div class="btnSwitch">

                            <button
                                type="button"
                                class="changeSideBarColor"
                                data-color="white"></button>

                            <button
                                type="button"
                                class="selected changeSideBarColor"
                                data-color="dark"></button>

                            <button
                                type="button"
                                class="changeSideBarColor"
                                data-color="dark2"></button>

                        </div>

                    </div>

                </div>

            </div>


            <div class="custom-toggle">

                <i class="icon-settings"></i>

            </div>

        </div>


    </div>


    <!-- ==================================================
         CORE JS
    =================================================== -->

    <script src="../assets/js/core/jquery-3.7.1.min.js"></script>

    <script src="../assets/js/core/popper.min.js"></script>

    <script src="../assets/js/core/bootstrap.min.js"></script>


    <!-- ==================================================
         JQUERY SCROLLBAR
    =================================================== -->

    <script src="../assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js"></script>


    <!-- ==================================================
         CHART JS
    =================================================== -->

    <script src="../assets/js/plugin/chart.js/chart.min.js"></script>


    <!-- ==================================================
         JQUERY SPARKLINE
    =================================================== -->

    <script src="../assets/js/plugin/jquery.sparkline/jquery.sparkline.min.js"></script>


    <!-- ==================================================
         CHART CIRCLE
    =================================================== -->

    <script src="../assets/js/plugin/chart-circle/circles.min.js"></script>

 
    <!-- ==================================================
         DATATABLES
    =================================================== -->

    <script src="../assets/js/plugin/datatables/datatables.min.js"></script>


    <!-- ==================================================
         BOOTSTRAP NOTIFY
    =================================================== -->


    <!-- ==================================================
         JS VECTOR MAPS
    =================================================== -->

    <script src="../assets/js/plugin/jsvectormap/jsvectormap.min.js"></script>

    <script src="../assets/js/plugin/jsvectormap/world.js"></script>


    <!-- ==================================================
         SWEET ALERT
    =================================================== -->

    <script src="../assets/js/plugin/sweetalert/sweetalert.min.js"></script>


    <!-- ==================================================
         KAIADMIN
    =================================================== -->

  <link rel="stylesheet" href="assets/css/kaiadmin.min.css">
<script src="../assets/js/kaiadmin.min.js"></script>


    <!-- ==================================================
         DEMO
    =================================================== -->

    <script src="../assets/js/setting-demo.js"></script>

    <script src="../assets/js/demo.js"></script>


    <!-- ==================================================
         SPARKLINE
    =================================================== -->

    <script>
        $("#lineChart").sparkline(
            [102, 109, 120, 99, 110, 105, 115],
            {
                type: "line",
                height: "70",
                width: "100%",
                lineWidth: "2",
                lineColor: "#177dff",
                fillColor: "rgba(23, 125, 255, 0.14)"
            }
        );
    </script>

</body>

</html>

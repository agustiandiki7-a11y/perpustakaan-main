<?php

require_once __DIR__ . '/../app/config/Database.php';
require_once __DIR__ . '/../app/helpers/auth.php';

mulaiSession();
cekRole(['petugas']);

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
        href="assets/img/kaiadmin/favicon.ico"
        type="image/x-icon">

    <!-- ==================================================
         FONTS & ICONS
    =================================================== -->

    <script src="assets/js/plugin/webfont/webfont.min.js"></script>

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

                urls: [
                    "assets/css/fonts.min.css"
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
        href="assets/css/bootstrap.min.css">

    <link
        rel="stylesheet"
        href="assets/css/plugins.min.css">

    <link
        rel="stylesheet"
        href="assets/css/kaiadmin.min.css">

    <link
        rel="stylesheet"
        href="assets/css/demo.css">

</head>

<body>

    <div class="wrapper">

        <!-- ==================================================
             SIDEBAR
        =================================================== -->

        <?php include __DIR__ . '/layout/sidebar.php'; ?>


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

                <?php include __DIR__ . '/layout/navbar.php'; ?>

            </div>


            <!-- ==================================================
                 CONTENT
            =================================================== -->

            <div class="container">

                <div class="page-inner">

                    <!-- ==================================================
                         TITLE
                    =================================================== -->

                    <div
                        class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">

                        <div>

                            <h3 class="fw-bold mb-3">
                                Dashboard
                            </h3>

                            <h6 class="op-7 mb-2">
                                Selamat datang di Dashboard Perpustakaan Digital
                            </h6>

                        </div>

                        <div class="ms-md-auto py-2 py-md-0">

                            <a
                                href="buku/tabel_buku.php"
                                class="btn btn-label-info btn-round me-2">

                                Kelola Buku

                            </a>

                            <a
                                href="peminjaman/tambah.php"
                                class="btn btn-primary btn-round">

                                Tambah Peminjaman

                            </a>

                        </div>

                    </div>


                    <!-- ==================================================
                         STATISTIC CARDS
                    =================================================== -->

                    <div class="row">

                        <!-- TOTAL BUKU -->

                        <div class="col-sm-6 col-md-3">

                            <div class="card card-stats card-round">

                                <div class="card-body">

                                    <div class="row align-items-center">

                                        <div class="col-icon">

                                            <div
                                                class="icon-big text-center icon-primary bubble-shadow-small">

                                                <i class="fas fa-book"></i>

                                            </div>

                                        </div>

                                        <div class="col col-stats ms-3 ms-sm-0">

                                            <div class="numbers">

                                                <p class="card-category">
                                                    Total Buku
                                                </p>

                                                <h4 class="card-title">
                                                    <?= (int) $totalBuku ?>
                                                </h4>

                                            </div>

                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>


                        <!-- TOTAL PENGGUNA -->

                        <div class="col-sm-6 col-md-3">

                            <div class="card card-stats card-round">

                                <div class="card-body">

                                    <div class="row align-items-center">

                                        <div class="col-icon">

                                            <div
                                                class="icon-big text-center icon-info bubble-shadow-small">

                                                <i class="fas fa-users"></i>

                                            </div>

                                        </div>

                                        <div class="col col-stats ms-3 ms-sm-0">

                                            <div class="numbers">

                                                <p class="card-category">
                                                    Pengguna Aktif
                                                </p>

                                                <h4 class="card-title">
                                                    <?= (int) $totalPengguna ?>
                                                </h4>

                                            </div>

                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>


                        <!-- TOTAL PEMINJAMAN -->

                        <div class="col-sm-6 col-md-3">

                            <div class="card card-stats card-round">

                                <div class="card-body">

                                    <div class="row align-items-center">

                                        <div class="col-icon">

                                            <div
                                                class="icon-big text-center icon-success bubble-shadow-small">

                                                <i class="fas fa-book-reader"></i>

                                            </div>

                                        </div>

                                        <div class="col col-stats ms-3 ms-sm-0">

                                            <div class="numbers">

                                                <p class="card-category">
                                                    Total Peminjaman
                                                </p>

                                                <h4 class="card-title">
                                                    <?= (int) $totalPeminjaman ?>
                                                </h4>

                                            </div>

                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>


                        <!-- MENUNGGU -->

                        <div class="col-sm-6 col-md-3">

                            <div class="card card-stats card-round">

                                <div class="card-body">

                                    <div class="row align-items-center">

                                        <div class="col-icon">

                                            <div
                                                class="icon-big text-center icon-secondary bubble-shadow-small">

                                                <i class="far fa-clock"></i>

                                            </div>

                                        </div>

                                        <div class="col col-stats ms-3 ms-sm-0">

                                            <div class="numbers">

                                                <p class="card-category">
                                                    Menunggu
                                                </p>

                                                <h4 class="card-title">
                                                    <?= (int) $totalMenunggu ?>
                                                </h4>

                                            </div>

                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>


                    <!-- ==================================================
                         DASHBOARD INFORMATION
                    =================================================== -->

                    <div class="row">

                        <!-- INFORMASI PERPUSTAKAAN -->

                        <div class="col-md-8">

                            <div class="card card-round">

                                <div class="card-header">

                                    <div class="card-head-row">

                                        <div class="card-title">
                                            Aktivitas Perpustakaan
                                        </div>

                                        <div class="card-tools">

                                            <a
                                                href="peminjaman/tabel_peminjaman.php"
                                                class="btn btn-label-info btn-round btn-sm">

                                                <span class="btn-label">
                                                    <i class="fa fa-eye"></i>
                                                </span>

                                                Lihat Peminjaman

                                            </a>

                                        </div>

                                    </div>

                                </div>


                                <div class="card-body">

                                    <div
                                        class="chart-container"
                                        style="min-height: 375px">

                                        <canvas
                                            id="statisticsChart">
                                        </canvas>

                                    </div>

                                    <div
                                        id="myChartLegend">
                                    </div>

                                </div>

                            </div>

                        </div>


                        <!-- STATUS PEMINJAMAN -->

                        <div class="col-md-4">

                            <div
                                class="card card-primary card-round">

                                <div class="card-header">

                                    <div class="card-head-row">

                                        <div class="card-title">
                                            Peminjaman
                                        </div>

                                    </div>

                                    <div class="card-category">
                                        Ringkasan status peminjaman
                                    </div>

                                </div>


                                <div class="card-body pb-0">

                                    <div class="mb-4 mt-2">

                                        <h1>
                                            <?= (int) $totalPeminjaman ?>
                                        </h1>

                                        <p class="text-white op-7">
                                            Total Peminjaman
                                        </p>

                                    </div>


                                    <div class="pull-in">

                                        <canvas
                                            id="dailySalesChart">
                                        </canvas>

                                    </div>

                                </div>

                            </div>


                            <!-- ONLINE / STATUS -->

                            <div class="card card-round">

                                <div class="card-body pb-0">

                                    <div
                                        class="h1 fw-bold float-end text-primary">

                                        <?= (int) $totalMenunggu ?>

                                    </div>

                                    <h2 class="mb-2">

                                        <?= (int) $totalMenunggu ?>

                                    </h2>

                                    <p class="text-muted">
                                        Peminjaman Menunggu
                                    </p>

                                    <div
                                        class="pull-in sparkline-fix">

                                        <div
                                            id="lineChart">
                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>


                    <!-- ==================================================
                         INFORMASI SISTEM
                    =================================================== -->

                    <div class="row">

                        <div class="col-md-12">

                            <div class="card card-round">

                                <div class="card-header">

                                    <div
                                        class="card-head-row card-tools-still-right">

                                        <h4 class="card-title">
                                            Sistem Perpustakaan Digital
                                        </h4>

                                    </div>

                                    <p class="card-category">

                                        Kelola koleksi buku, pengguna,
                                        peminjaman, pengembalian, dan
                                        perpustakaan digital.

                                    </p>

                                </div>


                                <div class="card-body">

                                    <div class="row">


                                        <!-- BUKU -->

                                        <div class="col-md-4">

                                            <div class="card card-round">

                                                <div class="card-body">

                                                    <div class="d-flex">

                                                        <div>

                                                            <div
                                                                class="icon-big text-center icon-primary">

                                                                <i class="fas fa-book"></i>

                                                            </div>

                                                        </div>

                                                        <div class="ms-3">

                                                            <h4>
                                                                Data Buku
                                                            </h4>

                                                            <p class="text-muted mb-2">

                                                                Kelola koleksi
                                                                buku perpustakaan.

                                                            </p>

                                                            <a
                                                                href="buku/tabel_buku.php"
                                                                class="btn btn-primary btn-sm">

                                                                Kelola Buku

                                                            </a>

                                                        </div>

                                                    </div>

                                                </div>

                                            </div>

                                        </div>


                                        <!-- PENGGUNA -->

                                        <div class="col-md-4">

                                            <div class="card card-round">

                                                <div class="card-body">

                                                    <div class="d-flex">

                                                        <div>

                                                            <div
                                                                class="icon-big text-center icon-info">

                                                                <i class="fas fa-users"></i>

                                                            </div>

                                                        </div>

                                                        <div class="ms-3">

                                                            <h4>
                                                                Pengguna
                                                            </h4>

                                                            <p class="text-muted mb-2">

                                                                Kelola data
                                                                pengguna sistem.

                                                            </p>

                                                            <a
                                                                href="pengguna/tabel_pengguna.php"
                                                                class="btn btn-info btn-sm">

                                                                Kelola Pengguna

                                                            </a>

                                                        </div>

                                                    </div>

                                                </div>

                                            </div>

                                        </div>


                                        <!-- PEMINJAMAN -->

                                        <div class="col-md-4">

                                            <div class="card card-round">

                                                <div class="card-body">

                                                    <div class="d-flex">

                                                        <div>

                                                            <div
                                                                class="icon-big text-center icon-success">

                                                                <i class="fas fa-book-reader"></i>

                                                            </div>

                                                        </div>

                                                        <div class="ms-3">

                                                            <h4>
                                                                Peminjaman
                                                            </h4>

                                                            <p class="text-muted mb-2">

                                                                Kelola transaksi
                                                                peminjaman buku.

                                                            </p>

                                                            <a
                                                                href="peminjaman/tabel_peminjaman.php"
                                                                class="btn btn-success btn-sm">

                                                                Lihat Peminjaman

                                                            </a>

                                                        </div>

                                                    </div>

                                                </div>

                                            </div>

                                        </div>


                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>


                </div>

            </div>


            <!-- ==================================================
                 FOOTER
            =================================================== -->

            <?php include __DIR__ . '/layout/footer.php'; ?>

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

    <script src="assets/js/core/jquery-3.7.1.min.js"></script>

    <script src="assets/js/core/popper.min.js"></script>

    <script src="assets/js/core/bootstrap.min.js"></script>


    <!-- ==================================================
         JQUERY SCROLLBAR
    =================================================== -->

    <script src="assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js"></script>


    <!-- ==================================================
         CHART JS
    ======================  ============================= -->

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

    <script src="../assets/js/plugin/bootstrap-notify/bootstrap-notify.min.js"></script>


    <!-- ==================================================
         JS VECTOR MAPS
    =================================================== -->

    <script src="../assets/js/plugin/jsvectormap/jsvectormap.min.js"></script>

    <script src="../assets/js/plugin/jsvectormap/world.js"></script>


    <!-- ==================================================
         SWEET ALERT
    =================================================== -->

    <script src="assets/js/plugin/sweetalert/sweetalert.min.js"></script>


    <!-- ==================================================
         KAIADMIN
    =================================================== -->

  <link rel="stylesheet" href="assets/css/kaiadmin.min.css">
<script src="assets/js/kaiadmin.min.js"></script>


    <!-- ==================================================
         DEMO
    =================================================== -->

    <script src="assets/js/setting-demo.js"></script>

    <script src="assets/js/demo.js"></script>


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

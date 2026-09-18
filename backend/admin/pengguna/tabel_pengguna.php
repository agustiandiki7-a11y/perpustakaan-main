    <?php
    require_once __DIR__ . '/../../../app/config/Database.php';
    require_once __DIR__ . '/../../../app/helpers/auth.php';

    mulaiSession();
    cekRole(['admin', 'petugas']);

    $db = new Database();
    $pdo = $db->getConnection();

    $stmt = $pdo->query("SELECT * FROM users ORDER BY id DESC");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $success = $_SESSION['success'] ?? null;
    $error = $_SESSION['error'] ?? null;
    unset($_SESSION['success'], $_SESSION['error']);
    ?>
    <!DOCTYPE html>
    <html lang="id">
    <head>
        <?php include __DIR__ . '/../../layout/header.php'; ?>
        <title>Data Pengguna - Backend Perpustakaan</title>
    </head>
    <body>
        <div class="wrapper">
            <?php include __DIR__ . '/../../layout/sidebar.php'; ?>

            <div class="main-panel">
                <?php include __DIR__ . '/../../layout/navbar.php'; ?>

                <div class="container">
                    <div class="page-inner">
                        <div class="page-header">
                            <h4 class="page-title">Data Pengguna</h4>
                            <ul class="breadcrumbs">
                                <li class="nav-home"><a href="../index.php"><i class="icon-home"></i></a></li>
                                <li class="separator"><i class="icon-arrow-right"></i></li>
                                <li class="nav-item"><a href="#">Manajemen</a></li>
                                <li class="separator"><i class="icon-arrow-right"></i></li>
                                <li class="nav-item"><a href="#">Pengguna</a></li>
                            </ul>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header">
                                        <div class="d-flex align-items-center">
                                            <h4 class="card-title">Daftar Pengguna Sistem</h4>
                                            <a href="tambah.php" class="btn btn-primary btn-round ms-auto">
                                                <i class="fa fa-plus"></i> Tambah Pengguna
                                            </a>
                                        </div>
                                    </div>
                                    <div class="card-body">

                                        <?php if ($success): ?>
                                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                                <?= htmlspecialchars($success, ENT_QUOTES, 'UTF-8') ?>
                                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                            </div>
                                        <?php endif; ?>

                                        <?php if ($error): ?>
                                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                                <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
                                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                            </div>
                                        <?php endif; ?>

                                        <div class="table-responsive">
                                            <table id="add-row" class="display table table-striped table-hover">
                                                <thead>
                                                    <tr>
                                                        <th style="width: 5%">No</th>
                                                        <th>Foto</th>
                                                        <th>Nama Lengkap</th>
                                                        <th>Username / Email</th>
                                                        <th>Role</th>
                                                        <th>No HP</th>
                                                        <th class="text-center">Status</th>
                                                        <th style="width: 10%" class="text-center">Aksi</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php if (empty($users)): ?>
                                                        <tr>
                                                            <td colspan="8" class="text-center text-muted py-4">Belum ada data pengguna.</td>
                                                        </tr>
                                                    <?php else: ?>
                                                        <?php foreach ($users as $index => $user): ?>
                                                            <tr>
                                                                <td><?= $index + 1 ?></td>
                                                                <td>
                                                                    <?php if (!empty($user['foto']) && file_exists(__DIR__ . '/../../' . $user['foto'])): ?>
                                                                        <img src="../../<?= htmlspecialchars($user['foto'], ENT_QUOTES, 'UTF-8') ?>" alt="Foto" class="rounded-circle" style="width: 40px; height: 40px; object-fit: cover;">
                                                                    <?php else: ?>
                                                                        <span class="badge bg-secondary">No Foto</span>
                                                                    <?php endif; ?>
                                                                </td>
                                                                <td><strong><?= htmlspecialchars($user['nama'], ENT_QUOTES, 'UTF-8') ?></strong></td>
                                                                <td>
                                                                    <?= htmlspecialchars($user['username'], ENT_QUOTES, 'UTF-8') ?><br>
                                                                    <small class="text-muted"><?= htmlspecialchars($user['email'] ?? '-', ENT_QUOTES, 'UTF-8') ?></small>
                                                                </td>
                                                                <td>
                                                                    <?php 
                                                                        $roleBadge = 'bg-secondary';
                                                                        if ($user['role'] === 'admin') $roleBadge = 'bg-danger';
                                                                        elseif ($user['role'] === 'petugas') $roleBadge = 'bg-warning text-dark';
                                                                        elseif ($user['role'] === 'peminjam') $roleBadge = 'bg-primary';
                                                                    ?>
                                                                    <span class="badge <?= $roleBadge ?> text-uppercase"><?= htmlspecialchars($user['role'], ENT_QUOTES, 'UTF-8') ?></span>
                                                                </td>
                                                                <td><?= htmlspecialchars($user['no_hp'] ?? '-', ENT_QUOTES, 'UTF-8') ?></td>
                                                                <td class="text-center">
                                                                    <?php if (($user['status'] ?? 'aktif') === 'aktif'): ?>
                                                                        <span class="badge bg-success">Aktif</span>
                                                                    <?php else: ?>
                                                                        <span class="badge bg-secondary">Nonaktif</span>
                                                                    <?php endif; ?>
                                                                </td>
                                                                <td class="text-center">
                                                                    <div class="form-button-action">
                                                                        
                                                                        <a href="hapus.php?id=<?= (int)$user['id'] ?>" class="btn btn-link btn-danger" data-bs-toggle="tooltip" title="Hapus Pengguna" onclick="return confirm('Yakin ingin menghapus pengguna ini?');">
                                                                            <i class="fa fa-times"></i>
                                                                        </a>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        <?php endforeach; ?>
                                                    <?php endif; ?>
                                                </tbody>
                                            </table>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <?php include __DIR__ . '/../../layout/footer.php'; ?>
            </div>
        </div>

        <script src="../assets/js/core/jquery-3.7.1.min.js"></script>
        <script src="../assets/js/core/popper.min.js"></script>
        <script src="../assets/js/core/bootstrap.min.js"></script>
        <script src="../assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js"></script>
        <script src="../assets/js/kaiadmin.min.js"></script>
    </body>
    </html>
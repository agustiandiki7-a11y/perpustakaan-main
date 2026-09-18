<?php
require_once __DIR__ . '/../../app/config/Database.php';
require_once __DIR__ . '/../../app/helpers/auth.php';

mulaiSession();
cekRole(['admin', 'petugas']);

$db = new Database();
$pdo = $db->getConnection();

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
    $_SESSION['error'] = 'ID pengguna tidak valid!';
    header('Location: tabel_pengguna.php');
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    $_SESSION['error'] = 'Data pengguna tidak ditemukan!';
    header('Location: tabel_pengguna.php');
    exit;
}

$error = $_SESSION['error'] ?? null;
unset($_SESSION['error']);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <?php include __DIR__ . '/../layout/header.php'; ?>
    <title>Edit Pengguna - Backend Perpustakaan</title>
</head>
<body>
    <div class="wrapper">
        <?php include __DIR__ . '/../layout/sidebar.php'; ?>
        <div class="main-panel">
            <?php include __DIR__ . '/../layout/navbar.php'; ?>

            <div class="container">
                <div class="page-inner">
                    <div class="page-header">
                        <h4 class="page-title">Edit Pengguna</h4>
                        <ul class="breadcrumbs">
                            <li class="nav-home"><a href="../index.php"><i class="icon-home"></i></a></li>
                            <li class="separator"><i class="icon-arrow-right"></i></li>
                            <li class="nav-item"><a href="tabel_pengguna.php">Data Pengguna</a></li>
                            <li class="separator"><i class="icon-arrow-right"></i></li>
                            <li class="nav-item"><a href="#">Edit</a></li>
                        </ul>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <div class="card-title">Form Edit Data Pengguna</div>
                                </div>
                                <form action="proses-edit.php" method="POST" enctype="multipart/form-data">
                                    <div class="card-body">
                                        <?php if ($error): ?>
                                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                                <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
                                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                            </div>
                                        <?php endif; ?>

                                        <!-- PENTING: Input hidden untuk membawa ID user yang sedang diedit -->
                                        <input type="hidden" name="id" value="<?= (int)$user['id'] ?>">

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="nama">Nama Lengkap <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" id="nama" name="nama" value="<?= htmlspecialchars($user['nama'], ENT_QUOTES, 'UTF-8') ?>" required>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="username">Username <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" id="username" name="username" value="<?= htmlspecialchars($user['username'], ENT_QUOTES, 'UTF-8') ?>" required>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="password">Password Baru</label>
                                                    <input type="password" class="form-control" id="password" name="password" placeholder="Kosongkan jika tidak ingin mengubah password">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="email">Email</label>
                                                    <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($user['email'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="role">Role / Hak Akses <span class="text-danger">*</span></label>
                                                    <select class="form-control" id="role" name="role" required>
                                                        <option value="peminjam" <?= ($user['role'] === 'peminjam') ? 'selected' : '' ?>>Peminjam</option>
                                                        <option value="petugas" <?= ($user['role'] === 'petugas') ? 'selected' : '' ?>>Petugas</option>
                                                        <option value="admin" <?= ($user['role'] === 'admin') ? 'selected' : '' ?>>Admin</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="status">Status Akun <span class="text-danger">*</span></label>
                                                    <select class="form-control" id="status" name="status" required>
                                                        <option value="aktif" <?= ($user['status'] === 'aktif') ? 'selected' : '' ?>>Aktif</option>
                                                        <option value="nonaktif" <?= ($user['status'] === 'nonaktif') ? 'selected' : '' ?>>Nonaktif</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="no_hp">Nomor HP / WhatsApp</label>
                                                    <input type="text" class="form-control" id="no_hp" name="no_hp" value="<?= htmlspecialchars($user['no_hp'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="foto">Foto Profil Baru (Opsional)</label>
                                                    <?php if (!empty($user['foto'])): ?>
                                                        <div class="mb-2">
                                                            <img src="../../<?= htmlspecialchars($user['foto'], ENT_QUOTES, 'UTF-8') ?>" alt="Foto" class="rounded-circle" style="width: 50px; height: 50px; object-fit: cover;">
                                                        </div>
                                                    <?php endif; ?>
                                                    <input type="file" class="form-control" id="foto" name="foto" accept="image/*">
                                                    <small class="text-muted">Biarkan kosong jika tidak ingin mengubah foto.</small>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="alamat">Alamat Lengkap</label>
                                            <textarea class="form-control" id="alamat" name="alamat" rows="2"><?= htmlspecialchars($user['alamat'] ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
                                        </div>
                                    </div>
                                    <div class="card-action">
                                        <button type="submit" class="btn btn-primary">Perbarui Pengguna</button>
                                        <a href="tabel_pengguna.php" class="btn btn-danger">Batal</a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php include __DIR__ . '/../layout/footer.php'; ?>
        </div>
    </div>
    <script src="../assets/js/core/jquery-3.7.1.min.js"></script>
    <script src="../assets/js/core/popper.min.js"></script>
    <script src="../assets/js/core/bootstrap.min.js"></script>
    <script src="../assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js"></script>
    <script src="../assets/js/kaiadmin.min.js"></script>
</body>
</html>
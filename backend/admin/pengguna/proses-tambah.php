<?php
require_once __DIR__ . '/../../app/config/Database.php';
require_once __DIR__ . '/../../app/helpers/auth.php';

mulaiSession();
cekRole(['admin', 'petugas']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama     = trim($_POST['nama'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $role     = trim($_POST['role'] ?? 'peminjam');
    $status   = trim($_POST['status'] ?? 'aktif');
    $no_hp    = trim($_POST['no_hp'] ?? '');
    $alamat   = trim($_POST['alamat'] ?? '');

    if (empty($nama) || empty($username) || empty($password)) {
        $_SESSION['error'] = 'Nama, username, dan password wajib diisi!';
        header('Location: tambah.php');
        exit;
    }

    try {
        $db = new Database();
        $pdo = $db->getConnection();

        // Cek duplikat username
        $stmtCek = $pdo->prepare("SELECT id FROM users WHERE username = ?");
        $stmtCek->execute([$username]);
        if ($stmtCek->rowCount() > 0) {
            $_SESSION['error'] = 'Username sudah digunakan oleh akun lain!';
            header('Location: tambah.php');
            exit;
        }

        // Enkripsi password dengan password_hash (bcrypt)
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Upload foto jika ada
        $fotoPath = null;
        if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
            $fileTmp   = $_FILES['foto']['tmp_name'];
            $fileName  = $_FILES['foto']['name'];
            $fileSize  = $_FILES['foto']['size'];
            $fileExt   = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

            if (in_array($fileExt, ['jpg', 'jpeg', 'png']) && $fileSize <= 2 * 1024 * 1024) {
                $newFile = 'user_' . time() . '_' . uniqid() . '.' . $fileExt;
                $uploadDir = __DIR__ . '/../../assets/uploads/user/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }
                if (move_uploaded_file($fileTmp, $uploadDir . $newFile)) {
                    $fotoPath = 'assets/uploads/user/' . $newFile;
                }
            }
        }

        $stmt = $pdo->prepare("INSERT INTO users (nama, username, password, role, email, no_hp, alamat, foto, status, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())");
        $stmt->execute([
            $nama, 
            $username, 
            $hashedPassword, 
            $role, 
            empty($email) ? null : $email, 
            empty($no_hp) ? null : $no_hp, 
            empty($alamat) ? null : $alamat, 
            $fotoPath, 
            $status
        ]);

        $_SESSION['success'] = 'Pengguna baru berhasil ditambahkan!';
        header('Location: tabel_pengguna.php');
        exit;

    } catch (PDOException $e) {
        $_SESSION['error'] = 'Kesalahan database: ' . $e->getMessage();
        header('Location: tambah.php');
        exit;
    }
} else {
    header('Location: tabel_pengguna.php');
    exit;
}
<?php
require_once __DIR__ . '/../../app/config/Database.php';
require_once __DIR__ . '/../../app/helpers/auth.php';

mulaiSession();
cekRole(['admin', 'petugas']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil ID secara spesifik dari input hidden form POST
    $id       = isset($_POST['id']) ? (int)$_POST['id'] : 0;
    $nama     = trim($_POST['nama'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $role     = trim($_POST['role'] ?? 'peminjam');
    $status   = trim($_POST['status'] ?? 'aktif');
    $no_hp    = trim($_POST['no_hp'] ?? '');
    $alamat   = trim($_POST['alamat'] ?? '');

    if ($id <= 0 || empty($nama) || empty($username)) {
        $_SESSION['error'] = 'Data ID tidak valid atau field wajib kosong!';
        header('Location: tabel_pengguna.php');
        exit;
    }

    try {
        $db = new Database();
        $pdo = $db->getConnection();

        // Ambil data lama berdasarkan ID form (bukan session)
        $stmtOld = $pdo->prepare("SELECT * FROM users WHERE id = ?");
        $stmtOld->execute([$id]);
        $oldUser = $stmtOld->fetch(PDO::FETCH_ASSOC);

        if (!$oldUser) {
            $_SESSION['error'] = 'Pengguna tidak ditemukan!';
            header('Location: tabel_pengguna.php');
            exit;
        }

        // Cek duplikat username dengan user lain
        $stmtCek = $pdo->prepare("SELECT id FROM users WHERE username = ? AND id != ?");
        $stmtCek->execute([$username, $id]);
        if ($stmtCek->rowCount() > 0) {
            $_SESSION['error'] = 'Username sudah digunakan oleh akun lain!';
            header('Location: edit.php?id=' . $id);
            exit;
        }

        // Tangani upload foto baru jika ada
        $fotoPath = $oldUser['foto'];
        if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
            $fileTmp  = $_FILES['foto']['tmp_name'];
            $fileName = $_FILES['foto']['name'];
            $fileSize = $_FILES['foto']['size'];
            $fileExt  = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

            if (in_array($fileExt, ['jpg', 'jpeg', 'png']) && $fileSize <= 2 * 1024 * 1024) {
                $newFile = 'user_' . time() . '_' . uniqid() . '.' . $fileExt;
                $uploadDir = __DIR__ . '/../../assets/uploads/user/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }
                if (move_uploaded_file($fileTmp, $uploadDir . $newFile)) {
                    $fotoPath = 'assets/uploads/user/' . $newFile;
                    // Hapus foto lama jika ada
                    if (!empty($oldUser['foto']) && file_exists(__DIR__ . '/../../' . $oldUser['foto'])) {
                        @unlink(__DIR__ . '/../../' . $oldUser['foto']);
                    }
                }
            }
        }

        // Update database berdasarkan ID spesifik
        if (!empty($password)) {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("UPDATE users SET nama = ?, username = ?, password = ?, role = ?, email = ?, no_hp = ?, alamat = ?, foto = ?, status = ?, updated_at = NOW() WHERE id = ?");
            $stmt->execute([$nama, $username, $hashedPassword, $role, empty($email) ? null : $email, empty($no_hp) ? null : $no_hp, empty($alamat) ? null : $alamat, $fotoPath, $status, $id]);
        } else {
            $stmt = $pdo->prepare("UPDATE users SET nama = ?, username = ?, role = ?, email = ?, no_hp = ?, alamat = ?, foto = ?, status = ?, updated_at = NOW() WHERE id = ?");
            $stmt->execute([$nama, $username, $role, empty($email) ? null : $email, empty($no_hp) ? null : $no_hp, empty($alamat) ? null : $alamat, $fotoPath, $status, $id]);
        }

        $_SESSION['success'] = 'Data pengguna berhasil diperbarui!';
        header('Location: tabel_pengguna.php');
        exit;

    } catch (PDOException $e) {
        $_SESSION['error'] = 'Kesalahan database: ' . $e->getMessage();
        header('Location: edit.php?id=' . $id);
        exit;
    }
} else {
    header('Location: tabel_pengguna.php');
    exit;
}
<?php

session_start();

require_once __DIR__ . '/../app/config/Database.php';

// Hanya izinkan POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: login.php');
    exit;
}

// Rate Limit
if (!isset($_SESSION['login_attempt'])) {
    $_SESSION['login_attempt'] = 0;
}

if (!isset($_SESSION['login_block_until'])) {
    $_SESSION['login_block_until'] = 0;
}

if (time() < $_SESSION['login_block_until']) {
    $_SESSION['error'] = 'Terlalu banyak percobaan login.';
    header('Location: login.php');
    exit;
}

$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';

if ($username === '' || $password === '') {
    $_SESSION['error'] = 'Username dan password wajib diisi.';
    header('Location: login.php');
    exit;
}

try {

    $database = new Database();
    $pdo = $database->connect();

    $stmt = $pdo->prepare("
        SELECT
            id,
            nama,
            username,
            password,
            role,
            email,
            no_hp,
            alamat,
            foto,
            status
        FROM users
        WHERE username = :username
        LIMIT 1
    ");

    $stmt->execute([
        ':username' => $username
    ]);

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {

        $_SESSION['login_attempt']++;

        $_SESSION['error'] = 'Username atau password salah.';
        header('Location: login.php');
        exit;
    }

    if ($user['status'] !== 'aktif') {

        $_SESSION['error'] = 'Akun tidak aktif.';
        header('Location: login.php');
        exit;
    }

    if (!password_verify($password, $user['password'])) {

        $_SESSION['login_attempt']++;

        $_SESSION['error'] = 'Username atau password salah.';
        header('Location: login.php');
        exit;
    }

    // Role yang diizinkan
    if (!in_array($user['role'], ['admin', 'petugas'])) {

        $_SESSION['error'] = 'Role tidak diizinkan.';
        header('Location: login.php');
        exit;
    }

    // Reset percobaan login
    $_SESSION['login_attempt'] = 0;
    $_SESSION['login_block_until'] = 0;

    // Regenerate session
    session_regenerate_id(true);

    // Simpan session
    $_SESSION['status'] = 'login';
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['nama'] = $user['nama'];
    $_SESSION['username'] = $user['username'];
    $_SESSION['role'] = $user['role'];
    $_SESSION['email'] = $user['email'];
    $_SESSION['no_hp'] = $user['no_hp'];
    $_SESSION['alamat'] = $user['alamat'];
    $_SESSION['foto'] = $user['foto'];
    $_SESSION['last_activity'] = time();

    // Redirect berdasarkan role
    if ($user['role'] === 'admin') {

        header('Location: /perpustakaan/backend/admin/index.php');
        exit;

    } elseif ($user['role'] === 'petugas') {

        header('Location: /perpustakaan/backend/petugas/index.php');
        exit;

    }

} catch (PDOException $e) {

    error_log($e->getMessage());

    $_SESSION['error'] = 'Terjadi kesalahan sistem.';
    header('Location: login.php');
    exit;
}
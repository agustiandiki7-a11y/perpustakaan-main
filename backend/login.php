<?php

session_start();

if (isset($_SESSION['status']) && $_SESSION['status'] === 'login') {
    header('Location: index.php');
    exit;
}

$error = $_SESSION['error'] ?? '';
unset($_SESSION['error']);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Backend - Perpustakaan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            min-height: 100vh;
            background: #f5f7fb;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: Arial, sans-serif;
        }

        .login-card {
            width: 100%;
            max-width: 420px;
            background: #fff;
            border-radius: 18px;
            padding: 35px;
            box-shadow: 0 10px 35px rgba(0,0,0,.08);
        }

        .logo {
            width: 65px;
            height: 65px;
            background: #0d6efd;
            color: white;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            font-size: 28px;
            font-weight: bold;
        }

        .form-control {
            padding: 12px 14px;
            border-radius: 10px;
        }

        .btn-login {
            padding: 12px;
            border-radius: 10px;
            font-weight: 600;
        }
    </style>
</head>
<body>

<div class="login-card">

    <div class="logo">P</div>

    <div class="text-center mb-4">
        <h3 class="fw-bold mb-1">Backend Perpustakaan</h3>
        <p class="text-muted mb-0">Silakan login untuk melanjutkan</p>
    </div>

    <?php if ($error !== ''): ?>
        <div class="alert alert-danger">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <form action="proses_login.php" method="POST">

        <div class="mb-3">
            <label class="form-label fw-semibold">Username</label>
            <input
                type="text"
                name="username"
                class="form-control"
                placeholder="Masukkan username"
                required
                autocomplete="username"
            >
        </div>

        <div class="mb-4">
            <label class="form-label fw-semibold">Password</label>
            <input
                type="password"
                name="password"
                class="form-control"
                placeholder="Masukkan password"
                required
                autocomplete="current-password"
            >
        </div>

        <button type="submit" class="btn btn-primary w-100 btn-login">
            Masuk ke Backend
        </button>

    </form>

    <div class="text-center mt-4">
        <small class="text-muted">
            Sistem Informasi Perpustakaan
        </small>
    </div>

</div>

</body>
</html>
```php
<?php
require_once __DIR__ . '/../../app/config/Database.php';
require_once __DIR__ . '/../../app/helpers/auth.php';

mulaiSession();
cekRole(['admin']);

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id || $id <= 0) {
    $_SESSION['error'] = 'ID pengguna tidak valid.';
    header('Location: tabel_pengguna.php');
    exit;
}

$db = new Database();
$pdo = $db->getConnection();

try {
    $pdo->beginTransaction();

    $stmt = $pdo->prepare("
        SELECT id, nama, username, role
        FROM users
        WHERE id = :id
        LIMIT 1
    ");

    $stmt->execute([
        'id' => $id
    ]);

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        $pdo->rollBack();
        $_SESSION['error'] = 'Data pengguna tidak ditemukan.';
        header('Location: tabel_pengguna.php');
        exit;
    }

    if ($user['role'] === 'admin') {
        $stmt = $pdo->query("
            SELECT COUNT(*)
            FROM users
            WHERE role = 'admin'
        ");

        $jumlahAdmin = (int) $stmt->fetchColumn();

        if ($jumlahAdmin <= 1) {
            $pdo->rollBack();
            $_SESSION['error'] = 'Admin terakhir tidak dapat dihapus.';
            header('Location: tabel_pengguna.php');
            exit;
        }
    }

    $stmt = $pdo->prepare("
        SELECT id
        FROM loans
        WHERE user_id = :user_id
    ");

    $stmt->execute([
        'user_id' => $id
    ]);

    $loanIds = $stmt->fetchAll(PDO::FETCH_COLUMN);

    if (!empty($loanIds)) {
        $placeholders = implode(',', array_fill(0, count($loanIds), '?'));

        $stmt = $pdo->prepare("
            DELETE FROM loan_details
            WHERE loan_id IN ($placeholders)
        ");

        $stmt->execute($loanIds);

        $stmt = $pdo->prepare("
            DELETE FROM loans
            WHERE user_id = ?
        ");

        $stmt->execute([$id]);
    }

    $stmt = $pdo->prepare("
        DELETE FROM users
        WHERE id = :id
    ");

    $stmt->execute([
        'id' => $id
    ]);

    if ($stmt->rowCount() > 0) {
        $pdo->commit();

        $_SESSION['success'] = 'Pengguna "' .
            $user['nama'] .
            '" berhasil dihapus permanen.';
    } else {
        $pdo->rollBack();

        $_SESSION['error'] = 'Pengguna gagal dihapus.';
    }
} catch (PDOException $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }

    $_SESSION['error'] = 'Gagal menghapus pengguna: ' . $e->getMessage();
}

header('Location: tabel_pengguna.php');
exit;
<?php
// admin_user_list.php
// Bu sayfada yöneticiler kayıtlı kullanıcıları görüntüleyebilir ve silebilir.

require('../includes/db.php');
require('../includes/session_check.php'); 

// Yalnızca admin yetkililer erişebilsin
if (!isset($_SESSION['user_id']) || $_SESSION['is_admin'] != 1) {
    header('Location: ../auth/login.php');
    exit;
}

/* --------------------------------------------------
 *  KULLANICI SİLME İŞLEMİ
 * -------------------------------------------------- */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    // Basit CSRF koruması (daha sağlam bir çözüm için token kullanın)
    $deleteId = (int) $_POST['delete_id'];

    // İsteyen admin kendini kazara silmesin – isterseniz yorumu kaldırın
    if ($deleteId === (int) $_SESSION['user_id']) {
        $_SESSION['flash_error'] = 'Kendi hesabınızı silemezsiniz.';
    } else {
        $delStmt = $pdo->prepare('DELETE FROM users WHERE id = :id');
        $delStmt->execute([':id' => $deleteId]);
        $_SESSION['flash_success'] = 'Kullanıcı başarıyla silindi.';
    }

    // Yenileme yaparak POST‑redirect‑GET deseni uyguluyoruz
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}

/* --------------------------------------------------
 *  KULLANICILARI LİSTELEME
 * -------------------------------------------------- */
$stmt = $pdo->query('SELECT id, username, is_admin FROM users ORDER BY id');
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Kullanıcı Listesi</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        table { border-collapse: collapse; width: 100%; }
        th, td { padding: 8px 12px; border: 1px solid #ccc; text-align: left; }
        .btn-delete { background: #e74c3c; color: #fff; border: none; padding: 6px 12px; cursor: pointer; }
        .btn-delete:hover { opacity: .9; }
        .flash-success { background:#2ecc71; color:#fff; padding:10px; margin-bottom:1rem; }
        .flash-error { background:#e74c3c; color:#fff; padding:10px; margin-bottom:1rem; }
    </style>
</head>
<body>
    <h2>Kayıtlı Kullanıcılar</h2>

    <?php if (!empty($_SESSION['flash_success'])): ?>
        <div class="flash-success"><?= $_SESSION['flash_success']; unset($_SESSION['flash_success']); ?></div>
    <?php endif; ?>
    <?php if (!empty($_SESSION['flash_error'])): ?>
        <div class="flash-error"><?= $_SESSION['flash_error']; unset($_SESSION['flash_error']); ?></div>
    <?php endif; ?>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Kullanıcı Adı</th>
                <th>Rol</th>
                <th>İşlem</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td><?= $user['id'] ?></td>
                    <td><?= htmlspecialchars($user['username']) ?></td>
                    <td><?= $user['is_admin'] ? 'Admin' : 'Kullanıcı' ?></td>
                    <td>
                        <form method="post" class="inline-form" onsubmit="return confirm('<?= htmlspecialchars($user['username']) ?> kullanıcısını silmek istediğinize emin misiniz?');">
                            <input type="hidden" name="delete_id" value="<?= $user['id'] ?>">
                            <button type="submit" class="btn-delete">Sil</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
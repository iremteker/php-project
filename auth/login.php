<?php
session_start();
require('../includes/db.php');

// Cookie varsa otomatik oturum başlat
if (!isset($_SESSION['user_id']) && isset($_COOKIE['remember_user'])) {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$_COOKIE['remember_user']]);
    $user = $stmt->fetch();

    if ($user) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['is_admin'] = $user['is_admin'];

        if (!empty($user['is_admin']) && $user['is_admin'] == 1) {
            header("Location: ../admin/index.php");
        } else {
            header("Location: ../user/my_notes.php");
        }
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$_POST['username']]);
    $user = $stmt->fetch();

    if ($user && password_verify($_POST['password'], $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['is_admin'] = $user['is_admin'];

        // Eğer kullanıcı "beni hatırla"yı işaretlediyse, cookie oluştur
        if (isset($_POST['remember'])) {
            setcookie('remember_user', $user['id'], time() + (86400 * 30), "/"); // 30 gün boyunca hatırla
        }

        if (!empty($user['is_admin']) && $user['is_admin'] == 1) {
            header("Location: ../admin/index.php");
        } else {
            header("Location: ../user/my_notes.php");
        }
        exit;
    } else {
        $error = "Kullanıcı adı veya şifre hatalı.";
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <link rel="stylesheet" href="../assets/css/style.css">
    <meta charset="UTF-8">
    <title>Giriş Yap</title>
</head>
<body>
    <h2>Giriş Yap</h2>

    <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>

    <form method="post">
        <input name="username" type="text" placeholder="Kullanıcı Adı" required>
        <input name="password" type="password" placeholder="Şifre" required>
        <label>
            <input type="checkbox" name="remember"> Beni hatırla
        </label>
        <button type="submit">Giriş</button>
    </form>

    <p style="text-align: center;">Hesabınız yok mu? <a href="register.php">Kayıt olun</a></p>
</body>
</html>



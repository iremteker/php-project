<?php
session_start();

require('includes/db.php');

$timeout = 150; // 10 dakika
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > $timeout) {
    session_unset();
    session_destroy();
    header("Location: auth/login.php");
    exit;
}
$_SESSION['last_activity'] = time();

if (!isset($_SESSION['user_id'])) {
    header("Location: auth/login.php");
    exit;
}

if (empty($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    // Eğer kullanıcı admin değilse ama giriş yapmışsa, kullanıcı paneline yönlendir
    header("Location: user.php"); // user.php sayfası senin kullanıcı panelin olmalı
    exit;
}

?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Admin Paneli</title>
</head>
<body>
    
    <h1>Admin Paneline Hoşgeldiniz, <?= htmlspecialchars($_SESSION['username'] ?? $_SESSION['user_id']) ?></h1>

    <nav>
        <ul>
            <li><a href="admin.php">Ana Sayfa</a></li>
            <li><a href="notes/upload.php">Not Yükle</a></li>
            <li><a href="notes/list.php">Notları Görüntüle</a></li>
            <li><a href="auth/logout.php">Çıkış Yap</a></li>
        </ul>
    </nav>

    <p>Burada admin işlemleri yapılacak.</p>
</body>


</html>


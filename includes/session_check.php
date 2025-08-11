<?php
session_start();
require('db.php'); // veritabanı bağlantısını çağır

// Eğer session yoksa ama user_id cookie'si varsa => otomatik giriş yap
if (!isset($_SESSION['user_id']) && isset($_COOKIE['user_id'])) {
    $userId = (int) $_COOKIE['user_id'];
    
    // Cookie'den gelen ID'ye göre kullanıcıyı veritabanından al
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$userId]);
    $user = $stmt->fetch();

    // Kullanıcı varsa session başlat
    if ($user) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['is_admin'] = $user['is_admin'];
    }
}

// Eğer hala giriş yapılmamışsa giriş sayfasına yönlendir
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

// Timeout kontrolü (örnek: 100 saniye)
if (!isset($_SESSION['timeout'])) {
    $_SESSION['timeout'] = time();
} elseif (time() - $_SESSION['timeout'] > 100) {
    session_unset();
    session_destroy();
    setcookie("user_id", "", time() - 3600, "/"); // cookie'yi de sil
    header("Location: ../auth/login.php");
    exit;
}
$_SESSION['timeout'] = time();
?>



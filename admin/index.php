<?php
require('../includes/session_check.php');

if (empty($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    echo "Bu sayfaya erişim yetkiniz yok.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <link rel="stylesheet" href="../assets/css/style.css">
    <meta charset="UTF-8">
    <title>Admin Paneli</title>
</head>
<body>
    <script>
    setTimeout(function() {
        alert("Oturumunuz zaman aşımına uğradı, çıkış yapılıyor.");
        window.location.href = 'auth/logout.php';
    }, 100000);
    </script>
    <h1>Admin Paneline Hoşgeldiniz, <?= htmlspecialchars($_SESSION['username']) ?></h1>
    <nav>
        <ul>
            <li><a href="../index.php">Ana Sayfa</a></li>
            <li><a href="upload.php">Not Yükle</a></li>
            <li><a href="../user/shared_notes.php">Tüm Notlar</a></li>
            <li><a href="users.php">Kullanıcılar</a></li>
            <li><a href="../auth/logout.php">Çıkış Yap</a></li>
        </ul>
    </nav>
</body>
</html>

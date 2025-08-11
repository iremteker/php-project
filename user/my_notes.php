<?php
require('../includes/session_check.php');

if (!empty($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1) {
    // Admin kullanıcı yanlışlıkla buraya gelirse admin paneline yönlendir
    header("Location: ../admin/index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <link rel="stylesheet" href="../assets/css/style.css">

    <meta charset="UTF-8">
    <title>Kullanıcı Anasayfa</title>
</head>
<body>
    <h1>Hoşgeldin, <?= htmlspecialchars($_SESSION['username']) ?></h1>

    <nav>
        <ul>
            <li><a href="../index.php">Ana Sayfa</a></li>
            <li><a href="upload.php">Yeni Not Yükle</a></li>
            <li><a href="shared_notes.php">Tüm Notlar</a></li>
            <li><a href="../auth/logout.php">Çıkış Yap</a></li>

           
        </ul>
    </nav>
</body>
</html>


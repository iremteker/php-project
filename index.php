<?php
session_start();
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Ders Notları</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="wrapper">
        <!-- Navigasyon, başlık ve içerikler burada -->
    
    <!-- Navigasyon Menüsü -->
    <nav>
        <ul>
            <li><a href="user/about.php">Hakkında</a></li>
            <li><a href="user/contact.php">İletişim</a></li>
        </ul>
    </nav>

    <!-- Ana Başlık -->
    <h1>Not Paylaşım Platformuna Hoşgeldiniz</h1>

    <!-- Kullanıcı Karşılama -->
    <?php if (isset($_SESSION['user_id'])): ?>
        <p style="text-align:center;">
            Merhaba, <strong><?= htmlspecialchars($_SESSION['username'] ?? 'Kullanıcı') ?></strong>!
        </p>
        <div style="text-align:center; margin-top:10px;">
            <a href="user/my_notes.php">İşlemlerim</a> |
            
            <a href="auth/logout.php">Çıkış Yap</a>
        </div>
    <?php else: ?>
        <div style="text-align:center; margin-top:10px;">
            <a href="auth/login.php">Giriş Yap</a> |
            <a href="auth/register.php">Kayıt Ol</a>
        </div>
    <?php endif; ?>
    </div>
    <!-- Footer -->
    <footer style="text-align: center; margin-top: 50px; padding-top: 20px; border-top: 1px solid #ccc;">
        <p>&copy; 2023 Ders Notları. Tüm hakları saklıdır.</p>
        <p>📧 info@dersnotlari.com | 🌐 www.dersnotlari.com</p>
    </footer>

</body>
</html>





<?php
session_start();

// Tüm oturum verilerini sil
session_unset();
session_destroy();

// Cookie varsa onu da sil
if (isset($_COOKIE['remember_user'])) {
    setcookie('remember_user', '', time() - 3600, "/"); // geçmiş zamana ayarla
}

// Giriş sayfasına yönlendir
header("Location: login.php");
exit;
?>

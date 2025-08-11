<?php
$host = 'localhost';
$db = 'dersnotlari_db';
$user = 'root';
$pass = ''; // şifre varsa yaz

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Veritabani baglanti hatasi: " . $e->getMessage());
}
?>

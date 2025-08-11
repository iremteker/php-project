<?php
require('../includes/db.php');

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password_raw = $_POST['password'];

    // Şifre güvenlik kontrolü
    if (
        strlen($password_raw) < 8 ||
        !preg_match("/[A-Z]/", $password_raw) ||
        !preg_match("/[a-z]/", $password_raw) ||
        !preg_match("/[0-9]/", $password_raw) ||
        !preg_match("/[\W]/", $password_raw)
    ) {
        $message = "⚠ Şifre en az 8 karakter olmalı ve büyük harf, küçük harf, rakam ve özel karakter içermelidir.";
    } else {
        try {
            $password = password_hash($password_raw, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
            $stmt->execute([$username, $password]);

            header("Location: login.php");
            exit;
        } catch (PDOException $e) {
            $message = "⚠ Kayıt başarısız: Kullanıcı adı zaten alınmış olabilir.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Kayıt Ol</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        body {
            background: #f5f5f5;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            font-family: sans-serif;
        }
        .register-box {
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            width: 350px;
            text-align: center;
        }
        input, button {
            width: 100%;
            padding: 10px;
            margin-top: 10px;
            border-radius: 6px;
            border: 1px solid #ccc;
        }
        .message {
            margin-top: 15px;
            color: #c00;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="register-box">
        <h2>Kayıt Ol</h2>
        <?php if (!empty($message)): ?>
            <div class="message"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>
        <form method="post" autocomplete="off">
            <input name="username" placeholder="Kullanıcı Adı" autocomplete="username" required>
            <input name="password" type="password" placeholder="Şifre" autocomplete="new-password" required>
            <button type="submit">Kayıt Ol</button>
        </form>
    </div>
</body>
</html>

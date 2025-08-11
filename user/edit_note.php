<?php
require('../includes/session_check.php');
require('../includes/db.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

$userId = (int) $_SESSION['user_id'];
$isAdmin = !empty($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1;

// id parametresi yoksa veya geçersizse hata göster
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Geçersiz not ID.");
}

$noteId = (int) $_GET['id'];

// Notu çek (admin değilse sadece kendi notu olabilir)
if ($isAdmin) {
    $stmt = $pdo->prepare("SELECT * FROM notes WHERE id = ?");
    $stmt->execute([$noteId]);
} else {
    $stmt = $pdo->prepare("SELECT * FROM notes WHERE id = ? AND user_id = ?");
    $stmt->execute([$noteId, $userId]);
}

$note = $stmt->fetch();

if (!$note) {
    die("Bu not bulunamadı veya erişim yetkiniz yok.");
}

// Not silme işlemi
if (isset($_POST['delete'])) {
    if ($isAdmin || $note['user_id'] == $userId) {
        $delStmt = $pdo->prepare("DELETE FROM notes WHERE id = ?");
        $delStmt->execute([$noteId]);
        header("Location: ../user/my_notes.php");
        exit;
    } else {
        die("Bu notu silme yetkiniz yok.");
    }
}

// Not güncelleme işlemi
if (isset($_POST['update'])) {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    
    // Basit validasyon yapabilirsin

    if ($isAdmin || $note['user_id'] == $userId) {
        $updateStmt = $pdo->prepare("UPDATE notes SET title = ?, description = ? WHERE id = ?");
        $updateStmt->execute([$title, $description, $noteId]);
        header("Location: ../user/my_notes.php");
        exit;
    } else {
        die("Bu notu düzenleme yetkiniz yok.");
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Notu Düzenle</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <h2>Notu Düzenle</h2>

    <form method="post">
        <label>Başlık:</label><br>
        <input type="text" name="title" required value="<?= htmlspecialchars($note['title']) ?>"><br><br>

        <label>Açıklama:</label><br>
        <textarea name="description" rows="5" required><?= htmlspecialchars($note['description']) ?></textarea><br><br>

        <button type="submit" name="update">Güncelle</button>
        <button type="submit" name="delete" onclick="return confirm('Notu silmek istediğinize emin misiniz?');">Sil</button>
    </form>

    <p><a href="../user/my_notes.php">Geri dön</a></p>
</body>
</html>

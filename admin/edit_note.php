<?php
session_start();
require('../includes/db.php');
require('../includes/session_check.php');

if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    die("Bu sayfaya erişim yetkiniz yok.");
}

$noteId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($noteId <= 0) {
    die("Geçersiz not ID.");
}

$stmt = $pdo->prepare("SELECT * FROM notes WHERE id = ?");
$stmt->execute([$noteId]);
$note = $stmt->fetch();

if (!$note) {
    die("Not bulunamadı.");
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['delete'])) {
        $delStmt = $pdo->prepare("DELETE FROM notes WHERE id = ?");
        $delStmt->execute([$noteId]);
        header("Location: index.php");  // Admin panel ana sayfası
        exit;
    } else {
        $title = trim($_POST['title']);
        $description = trim($_POST['description']);
        $category_id = (int)$_POST['category_id'];

        if (empty($title)) {
            $error = "Başlık boş bırakılamaz.";
        } else {
            $updateStmt = $pdo->prepare("UPDATE notes SET title = ?, description = ?, category_id = ? WHERE id = ?");
            $updateStmt->execute([$title, $description, $category_id, $noteId]);
            $success = "Not başarıyla güncellendi.";

            $stmt->execute([$noteId]);
            $note = $stmt->fetch();
        }
    }
}

$categoryStmt = $pdo->query("SELECT id, name FROM categories ORDER BY name ASC");
$categories = $categoryStmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Notu Düzenle (Admin)</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>
    <h2>Notu Düzenle (Admin)</h2>

    <?php if ($error): ?>
        <p style="color:red;"><?= htmlspecialchars($error) ?></p>
    <?php elseif ($success): ?>
        <p style="color:green;"><?= htmlspecialchars($success) ?></p>
    <?php endif; ?>

    <form method="post">
        <label>Başlık:</label><br>
        <input type="text" name="title" value="<?= htmlspecialchars($note['title']) ?>" required><br><br>

        <label>Açıklama:</label><br>
        <textarea name="description" rows="5"><?= htmlspecialchars($note['description']) ?></textarea><br><br>

        <label>Kategori:</label><br>
        <select name="category_id">
            <option value="0" <?= $note['category_id'] == 0 ? 'selected' : '' ?>>Kategori Yok</option>
            <?php foreach ($categories as $cat): ?>
                <option value="<?= $cat['id'] ?>" <?= $cat['id'] == $note['category_id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($cat['name']) ?>
                </option>
            <?php endforeach; ?>
        </select><br><br>

        <button type="submit">Güncelle</button>
        <button type="submit" name="delete" onclick="return confirm('Notu silmek istediğinize emin misiniz?');">Notu Sil</button>
    </form>

    <p><a href="index.php">Admin Paneline Dön</a></p>
</body>
</html>

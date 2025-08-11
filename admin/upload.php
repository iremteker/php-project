<?php
require('../includes/session_check.php');
require('../includes/db.php');

// Sadece admin erişimi
if (empty($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    echo "Bu sayfaya erişim yetkiniz yok.";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['note'])) {
    $target_dir = "../uploads/";

    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    $filename = basename($_FILES["note"]["name"]);
    $stored_name = "admin_" . $_SESSION['user_id'] . "_" . $filename; // admin yüklemesi olduğundan prefix ekledim
    $target_file = $target_dir . $stored_name;

    if (move_uploaded_file($_FILES["note"]["tmp_name"], $target_file)) {
        $title = $_POST['title'];
        $description = $_POST['description'];
        $category_id = $_POST['category_id'];

        $stmt = $pdo->prepare("INSERT INTO notes (title, description, file_path, user_id, category_id) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$title, $description, $stored_name, $_SESSION['user_id'], $category_id]);

        echo "Not başarıyla yüklendi.";
        header("Refresh: 2; URL=index.php");
        exit;
    } else {
        echo "Dosya yüklenirken bir hata oluştu.";
    }
}

$categories = $pdo->query("SELECT * FROM categories")->fetchAll();
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <link rel="stylesheet" href="../assets/css/style.css">
    <meta charset="UTF-8">
    <title>Admin - Not Yükle</title>
</head>
<body>
    <h2>Yeni Not Yükle (Admin)</h2>
    <form method="post" enctype="multipart/form-data">
        <input type="text" name="title" placeholder="Başlık" required><br>
        <textarea name="description" placeholder="Açıklama" required></textarea><br>

        <label>Kategori:</label>
        <select name="category_id" required>
            <?php foreach ($categories as $cat): ?>
                <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
            <?php endforeach; ?>
        </select><br><br>

        <input type="file" name="note" required><br><br>
        <button type="submit">Yükle</button>
    </form>
</body>
</html>

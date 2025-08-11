<?php

require('../includes/session_check.php');
require('../includes/db.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: /auth/login.php");
    exit;
}

$userId = (int) $_SESSION['user_id'];
$filterMine = isset($_GET['mine']) && $_GET['mine'] === '1';
$selectedCategory = isset($_GET['category']) ? (int) $_GET['category'] : 0;
$keyword = isset($_GET['search']) ? trim($_GET['search']) : '';

// Kategorileri çek
$categoryStmt = $pdo->query("SELECT id, name FROM categories ORDER BY name ASC");
$categories = $categoryStmt->fetchAll(PDO::FETCH_ASSOC);

// Notları çek
$sql = "
    SELECT
        notes.id,
        notes.user_id,
        notes.file_path,
        notes.title,
        notes.description,
        notes.created_at,
        users.username,
        categories.name AS category_name
    FROM notes
    JOIN users ON notes.user_id = users.id
    LEFT JOIN categories ON notes.category_id = categories.id
    WHERE 1=1
";
$params = [];

// Sadece kendi notları
if ($filterMine) {
    $sql .= " AND notes.user_id = :uid";
    $params[':uid'] = $userId;
}

// Kategori filtresi
if ($selectedCategory > 0) {
    $sql .= " AND notes.category_id = :catid";
    $params[':catid'] = $selectedCategory;
}

// Anahtar kelime arama (title ve description)
if (!empty($keyword)) {
    $sql .= " AND (notes.title LIKE :kw OR notes.description LIKE :kw)";
    $params[':kw'] = '%' . $keyword . '%';
}

$sql .= " ORDER BY notes.created_at DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$notes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Paylaşılan Notlar</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        body { font-family: Arial, sans-serif; background:#f4f4e9; padding:20px; }
        h2   { color:#2c3e50; margin-bottom:10px; }
        nav  { margin-bottom:15px; }
        nav a { margin-right:10px; color:#2980b9; text-decoration:none; font-weight:bold; }
        nav a.active, nav a:hover { text-decoration:underline; }

        form.filter-form { margin-bottom:20px; }
        select, button, input[type="text"] { padding:5px 10px; margin-right:5px; }

        ul { list-style:none; padding:0; }
        li { background:#fff; margin-bottom:15px; padding:15px;
             border-left:5px solid #3498db; border-radius:5px;
             box-shadow:0 2px 4px rgba(0,0,0,.05); }
        a.note-link { color:#2980b9; font-weight:bold; text-decoration:none; }
        a.note-link:hover { text-decoration:underline; }
        strong { display:inline-block; width:100px; color:#555; }
    </style>
</head>
<body>
    <h2><?= $filterMine ? 'Benim Notlarım' : 'Paylaşılan Tüm Notlar' ?></h2>

    <nav>
        <a href="../index.php">Ana Sayfa</a> |
        <a href="shared_notes.php" class="<?= $filterMine ? '' : 'active' ?>">Tüm Notlar</a> |
        <a href="shared_notes.php?mine=1" class="<?= $filterMine ? 'active' : '' ?>">Benim Notlarım</a>
    </nav>

    <form method="get" class="filter-form">
        <?php if ($filterMine): ?>
            <input type="hidden" name="mine" value="1">
        <?php endif; ?>

        <label for="category">Kategori:</label>
        <select name="category" id="category">
            <option value="0">Tüm Kategoriler</option>
            <?php foreach ($categories as $cat): ?>
                <option value="<?= $cat['id'] ?>" <?= $cat['id'] == $selectedCategory ? 'selected' : '' ?>>
                    <?= htmlspecialchars($cat['name']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <input type="text" name="search" placeholder="Anahtar kelime..." value="<?= htmlspecialchars($keyword) ?>">

        <button type="submit">Filtrele</button>
    </form>

    <?php if ($notes): ?>
        <ul>
            <?php foreach ($notes as $row): ?>
                <li>
                    <a class="note-link" href="../uploads/<?= urlencode($row['file_path']) ?>" target="_blank">
                        <?= htmlspecialchars($row['title']) ?>
                    </a><br>
                    <strong>Açıklama:</strong> <?= htmlspecialchars($row['description']) ?><br>
                    <strong>Kategori:</strong> <?= htmlspecialchars($row['category_name'] ?? 'Kategori Yok') ?><br>
                    <strong>Yükleyen:</strong> <?= htmlspecialchars($row['username']) ?><br>
                    <strong>Tarih:</strong> <?= date('d.m.Y H:i', strtotime($row['created_at'])) ?><br>

                    <?php
                    $isAdmin = !empty($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1;
                    if ($isAdmin || $row['user_id'] === $userId): ?>
                        <a href="edit_note.php?id=<?= $row['id'] ?>">Düzenle</a>
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>Filtreye uygun not bulunamadı.</p>
    <?php endif; ?>
</body>
</html>

<?php
require('../includes/session_check.php');
require('../includes/db.php');

if (!empty($_SESSION['is_admin'])) {
    header("Location: ../admin/index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['note'])) {
    $target_dir = "../uploads/";

    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    // İzin verilen dosya uzantıları
    $allowed_extensions = ['pdf', 'doc', 'docx', 'txt', 'ppt', 'pptx', 'xls', 'xlsx'];

    // Dosya bilgileri
    $original_name = $_FILES["note"]["name"];
    $file_size = $_FILES["note"]["size"];
    $file_tmp = $_FILES["note"]["tmp_name"];
    $file_ext = strtolower(pathinfo($original_name, PATHINFO_EXTENSION));

    // Dosya adı sanitize (sadece harf, rakam, alt çizgi ve tire)
    $clean_name = preg_replace("/[^a-zA-Z0-9-_\.]/", "_", pathinfo($original_name, PATHINFO_FILENAME));
    $stored_name = $_SESSION['user_id'] . "_" . $clean_name . "." . $file_ext;

    $target_file = $target_dir . $stored_name;

    // Dosya uzantısı kontrolü
    if (!in_array($file_ext, $allowed_extensions)) {
        echo "Hata: Sadece PDF, DOC, DOCX, TXT, PPT, PPTX, XLS, XLSX dosya türlerine izin verilmektedir.";
        exit;
    }

    // Dosya boyutu kontrolü (5MB sınırı)
    if ($file_size > 5 * 1024 * 1024) {
        echo "Hata: Dosya boyutu 5MB'dan büyük olamaz.";
        exit;
    }

    if (move_uploaded_file($file_tmp, $target_file)) {

        // VERİTABANINA EKLE
        $title = $_POST['title'];
        $description = $_POST['description'];
        $category_id = $_POST['category_id']; // select kutusundan gelir

        $stmt = $pdo->prepare("INSERT INTO notes (title, description, file_path, user_id, category_id) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$title, $description, $stored_name, $_SESSION['user_id'], $category_id]);

        echo "Not başarıyla yüklendi.";
    } else {
        echo "Dosya yüklenirken bir hata oluştu.";
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <link rel="stylesheet" href="../assets/css/style.css">

    <meta charset="UTF-8">
    <title>Not Yükle</title>
</head>
<body>
    <h2>Yeni Not Yükle</h2>
    <form method="post" enctype="multipart/form-data">
        <input type="text" name="title" placeholder="Başlık" required><br>
        <textarea name="description" placeholder="Açıklama" required></textarea><br>
        
        <label>Kategori:</label>
        <select name="category_id" required>
            <?php
            // Kategorileri veritabanından çek
            $categories = $pdo->query("SELECT * FROM categories")->fetchAll();
            foreach ($categories as $cat):
            ?>
                <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
            <?php endforeach; ?>
        </select><br><br>

        <input type="file" name="note" required><br>
        <button type="submit">Yükle</button>
    </form>
</body>
</html>



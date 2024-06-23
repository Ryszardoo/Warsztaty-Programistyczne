<?php
session_start();
require_once 'includes/db.php';
require_once 'includes/functions.php';

// Sprawdzenie, czy użytkownik jest zalogowany
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Pobranie ID posta do edycji
if (!isset($_GET['id'])) {
    header('Location: index.php');
    exit;
}

$postId = $_GET['id'];
$userId = $_SESSION['user_id']; // Pobranie user_id z sesji

// Pobranie danych istniejącego posta do formularza
$stmt = $pdo->prepare('SELECT * FROM posts WHERE id = ?');
$stmt->execute([$postId]);
$post = $stmt->fetch(PDO::FETCH_ASSOC);

// Obsługa aktualizacji posta
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $image = $_FILES['image'];

    // Obsługa uploadu nowego obrazka
    $imagePath = $post['image']; // Zachowaj istniejący obrazek, jeśli nie jest wymagana aktualizacja
    if ($image['size'] > 0) {
        $targetDir = 'uploads/';
        $targetFile = $targetDir . basename($image['name']);
        move_uploaded_file($image['tmp_name'], $targetFile);
        $imagePath = basename($image['name']);
    }

    // Aktualizacja posta w bazie danych
    $stmt = $pdo->prepare('UPDATE posts SET title = ?, content = ?, image = ? WHERE id = ?');
    $stmt->execute([$title, $content, $imagePath, $postId]);

    // Logowanie akcji edycji posta
    logAction($userId, 'Edited a post');

    header('Location: index.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Post</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
<h1>Edit Post</h1>
<form action="edit_post.php?id=<?= $postId ?>" method="post" enctype="multipart/form-data">
    <label for="title">Title:</label><br>
    <input type="text" id="title" name="title" value="<?= htmlspecialchars($post['title']) ?>" required><br><br>

    <label for="content">Content:</label><br>
    <textarea id="content" name="content" rows="4" cols="50" required><?= htmlspecialchars($post['content']) ?></textarea><br><br>

    <button type="submit">Update Post</button>
</form>
</body>
</html>

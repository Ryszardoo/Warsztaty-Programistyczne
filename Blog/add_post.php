<?php
session_start();
require_once 'includes/db.php';
require_once 'includes/functions.php';

// Sprawdzenie, czy użytkownik jest zalogowany
if (!isLoggedIn()) {
    header('Location: login.php');
    exit;
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $userId = $_SESSION['user_id']; // Pobranie user_id z sesji

    // Dodanie nowego posta do bazy danych
    $stmt = $pdo->prepare('INSERT INTO posts (title, content, user_id, date_published) VALUES (?, ?, ?, NOW())');
    $stmt->execute([$title, $content, $userId]);

    logAction($userId, 'Added a new post');

    header('Location: post.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Post</title>
    <link rel="stylesheet" href="css/styles.css"> <!-- Stylizacja, jeśli używasz CSS -->
</head>
<body>
<h1>Add Post</h1>

<form action="add_post.php" method="post">
    <label for="title">Title:</label>
    <input type="text" id="title" name="title" required><br>

    <label for="content">Content:</label>
    <textarea id="content" name="content" required></textarea><br>

    <button type="submit">Add Post</button>
</form>

<p><a href="index.php">Back to Home</a></p>
</body>
</html>

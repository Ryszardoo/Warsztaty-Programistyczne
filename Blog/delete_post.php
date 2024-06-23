<?php
session_start();
require 'includes/db.php';
require 'includes/functions.php';

// Sprawdzenie, czy użytkownik jest zalogowany
if (!isLoggedIn()) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $postId = $_POST['id'];
    $userId = $_SESSION['user_id'];
    $userRole = $_SESSION['user_role'];

    // Sprawdzenie, czy użytkownik jest autorem posta lub adminem
    $stmt = $pdo->prepare('SELECT user_id FROM posts WHERE id = ?');
    $stmt->execute([$postId]);
    $post = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($post && ($post['user_id'] == $userId || $userRole == 'admin')) {
        // Usunięcie komentarzy związanych z postem
        $stmt = $pdo->prepare('DELETE FROM comments WHERE post_id = ?');
        $stmt->execute([$postId]);

        // Usunięcie posta z bazy danych
        $stmt = $pdo->prepare('DELETE FROM posts WHERE id = ?');
        $stmt->execute([$postId]);

        // Zalogowanie akcji usunięcia posta
        logAction($userId, 'Deleted a post');
    }

    // Sprawdzenie, czy usunięto post z panelu admina czy ze strony posta
    $referer = $_SERVER['HTTP_REFERER'];
    if (strpos($referer, 'admin.php') !== false) {
        header('Location: admin.php');
    } else {
        header('Location: index.php');
    }
    exit;
}
?>

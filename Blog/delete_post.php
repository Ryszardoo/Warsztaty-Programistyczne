<?php
session_start();
require 'includes/db.php';
require 'includes/functions.php';

if (!isLoggedIn()) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $postId = $_POST['id'];
    $userId = $_SESSION['user_id'];
    $userRole = $_SESSION['user_role'];

    $stmt = $pdo->prepare('SELECT user_id FROM posts WHERE id = ?');
    $stmt->execute([$postId]);
    $post = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($post && ($post['user_id'] == $userId || $userRole == 'admin')) {
        // Usuwamy komentarze związne z postem
        $stmt = $pdo->prepare('DELETE FROM comments WHERE post_id = ?');
        $stmt->execute([$postId]);

        // Usuwamy samego posta z bazy danych
        $stmt = $pdo->prepare('DELETE FROM posts WHERE id = ?');
        $stmt->execute([$postId]);

        logAction($userId, 'Deleted a post');
    }

    // Sprawdzamy, czy usunięto post z panelu admina czy ze strony posta, żeby wiedzieć gdzie przekierować po kliknięciu delete
    $referer = $_SERVER['HTTP_REFERER'];
    if (strpos($referer, 'admin.php') !== false) {
        header('Location: admin.php');
    } else {
        header('Location: index.php');
    }
    exit;
}
?>

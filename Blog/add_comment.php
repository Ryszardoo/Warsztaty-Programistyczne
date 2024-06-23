<?php
session_start();
require 'includes/db.php';
require 'includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $content = $_POST['content'];
    $post_id = $_POST['post_id'];
    $author_id = isLoggedIn() ? $_SESSION['user_id'] : null; // Ustal ID autora lub null

    // Dodanie nowego komentarza do bazy danych
    $stmt = $pdo->prepare('INSERT INTO comments (content, post_id, author_id, date_added) VALUES (?, ?, ?, NOW())');
    $stmt->execute([$content, $post_id, $author_id]);

    redirect("post.php?id=$post_id");
}
?>

<?php
session_start();
require 'includes/db.php';
require 'includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $message = $_POST['message'];
    $postId = $_POST['post_id']; // ID posta, którego dotyczy wiadomość

    // Pobranie ID autora posta
    $stmt = $pdo->prepare('SELECT user_id FROM posts WHERE id = ?');
    $stmt->execute([$postId]);
    $authorId = $stmt->fetchColumn();

    if ($authorId) {
        $stmt = $pdo->prepare('INSERT INTO messages (name, email, message, author_id, date_sent) VALUES (?, ?, ?, ?, NOW())');
        $stmt->execute([$name, $email, $message, $authorId]);

        redirect("post.php?id=$postId");
    } else {
        echo "Nie znaleziono autora posta.";
    }
}
?>

<?php
session_start();
require 'includes/db.php';
require 'includes/functions.php';

if (!isLoggedIn() || $_SESSION['user_role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_POST['user_id'];
    $newRole = $_POST['role'];

    // Aktualizujemy rolę użytkownika w bazie danych
    $stmt = $pdo->prepare('UPDATE users SET role = ? WHERE id = ?');
    $stmt->execute([$newRole, $userId]);

    header('Location: admin.php');
    exit;
}
?>

<?php
session_start();
require 'includes/db.php';
require 'includes/functions.php';

// Sprawdzenie, czy użytkownik jest zalogowany i ma rolę admina
if (!isLoggedIn() || $_SESSION['user_role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_POST['user_id'];
    $newRole = $_POST['role'];

    // Aktualizacja roli użytkownika w bazie danych
    $stmt = $pdo->prepare('UPDATE users SET role = ? WHERE id = ?');
    $stmt->execute([$newRole, $userId]);

    // Przekierowanie z powrotem do panelu administracyjnego
    header('Location: admin.php');
    exit;
}
?>

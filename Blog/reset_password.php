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
    $currentPassword = $_POST['current_password'];
    $newPassword = $_POST['new_password'];
    $confirmPassword = $_POST['confirm_password'];
    $userId = $_SESSION['user_id'];

    // Sprawdzenie, czy nowe hasło i potwierdzenie hasła są takie same
    if ($newPassword !== $confirmPassword) {
        echo 'New password and confirm password do not match.';
        exit;
    }

    // Pobranie obecnego hasła użytkownika z bazy danych
    $stmt = $pdo->prepare('SELECT password FROM users WHERE id = ?');
    $stmt->execute([$userId]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Sprawdzenie, czy obecne hasło jest poprawne
    if (!password_verify($currentPassword, $user['password'])) {
        echo 'Current password is incorrect.';
        exit;
    }

    // Aktualizacja hasła użytkownika
    $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);
    $stmt = $pdo->prepare('UPDATE users SET password = ? WHERE id = ?');
    $stmt->execute([$hashedPassword, $userId]);

    logAction($_SESSION['user_id'], 'Reset password for user ' . $userId);
    echo 'Password successfully updated.';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
<h1>Reset Password</h1>

<form action="reset_password.php" method="post">
    <label for="current_password">Current Password:</label>
    <input type="password" id="current_password" name="current_password" required><br>

    <label for="new_password">New Password:</label>
    <input type="password" id="new_password" name="new_password" required><br>

    <label for="confirm_password">Confirm New Password:</label>
    <input type="password" id="confirm_password" name="confirm_password" required><br>

    <button type="submit">Reset Password</button>
</form>

<p><a href="index.php">Back to Home</a></p>
</body>
</html>

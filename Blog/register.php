<?php
session_start();
require 'includes/db.php';
require 'includes/functions.php';
require 'includes/header.php';
require 'includes/footer.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $password_confirm = $_POST['password_confirm'];

    //Szyfrujemy hasło i wrzucamy do bazy danych
    if ($password === $password_confirm) {
        $password_hash = password_hash($password, PASSWORD_BCRYPT);

        $stmt = $pdo->prepare('INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)');
        $stmt->execute([$username, $email, $password_hash, 'user']);

        redirect('login.php');
    } else {
        $error = 'Passwords do not match.';
    }
}
?>

<main>
    <h1>Register</h1>
    <?php if (isset($error)): ?>
        <p><?= $error ?></p>
    <?php endif; ?>
    <form action="register.php" method="post">
        <label for="username">Username:</label>
        <input type="text" name="username" id="username" required>
        <label for="email">Email:</label>
        <input type="email" name="email" id="email" required>
        <label for="password">Password:</label>
        <input type="password" name="password" id="password" required>
        <label for="password_confirm">Confirm Password:</label>
        <input type="password" name="password_confirm" id="password_confirm" required>
        <button type="submit">Register</button>
    </form>
</main>

<?php require 'includes/footer.php'; ?>

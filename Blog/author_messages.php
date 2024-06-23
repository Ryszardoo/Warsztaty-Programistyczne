<?php
session_start();
require 'includes/db.php';
require 'includes/functions.php';

// Sprawdzenie, czy użytkownik jest zalogowany i jest autorem
if (!isLoggedIn() || !isAuthor()) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Pobranie wiadomości dla zalogowanego autora
$stmt = $pdo->prepare('SELECT * FROM messages WHERE author_id = ? ORDER BY date_sent DESC');
$stmt->execute([$user_id]);
$messages = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Author Messages</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
<?php include 'includes/header.php'; ?>
<h1>Your Messages</h1>

<?php foreach ($messages as $message): ?>
    <div class="message">
        <p>From: <?php echo htmlspecialchars($message['name']); ?> (<?php echo htmlspecialchars($message['email']); ?>)</p>
        <p><?php echo htmlspecialchars($message['message']); ?></p>
        <p>Sent on: <?php echo htmlspecialchars($message['date_sent']); ?></p>
    </div>
<?php endforeach; ?>
</body>
</html>

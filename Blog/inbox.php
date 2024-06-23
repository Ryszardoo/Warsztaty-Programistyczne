<?php
session_start();
require 'includes/db.php';
require 'includes/functions.php';

// Sprawdzenie, czy użytkownik jest zalogowany
if (!isLoggedIn()) {
    header('Location: login.php');
    exit;
}

// Pobranie ID zalogowanego użytkownika
$userId = $_SESSION['user_id'];

// Pobranie wiadomości skierowanych do zalogowanego użytkownika
$stmt = $pdo->prepare('SELECT * FROM messages WHERE author_id = ? ORDER BY date_sent DESC');
$stmt->execute([$userId]);
$messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inbox</title>
    <link rel="stylesheet" href="css/styles.css"> <!-- Stylizacja, jeśli używasz CSS -->
</head>
<body>
<h1>Inbox</h1>

<?php if (count($messages) > 0): ?>
    <ul>
        <?php foreach ($messages as $message): ?>
            <li>
                <strong>From:</strong> <?php echo htmlspecialchars($message['name']); ?> (<?php echo htmlspecialchars($message['email']); ?>)<br>
                <strong>Sent:</strong> <?php echo htmlspecialchars($message['date_sent']); ?><br>
                <p><?php echo nl2br(htmlspecialchars($message['message'])); ?></p>
            </li>
        <?php endforeach; ?>
    </ul>
<?php else: ?>
    <p>No messages.</p>
<?php endif; ?>

<p><a href="index.php">Back to Home</a></p>
</body>
</html>

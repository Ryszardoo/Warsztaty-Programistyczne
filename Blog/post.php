<?php
session_start();
require 'includes/db.php';
require 'includes/functions.php';

// Pobranie ID posta z parametru GET
if (!isset($_GET['id'])) {
    header('Location: index.php');
    exit;
}

$post_id = $_GET['id'];

// Pobranie informacji o poście
$stmt = $pdo->prepare('SELECT posts.*, users.username, users.id as user_id FROM posts LEFT JOIN users ON posts.user_id = users.id WHERE posts.id = ?');
$stmt->execute([$post_id]);
$post = $stmt->fetch();

if (!$post) {
    header('Location: index.php');
    exit;
}

// Pobranie poprzedniego i następnego posta
$prev_stmt = $pdo->prepare('SELECT id FROM posts WHERE id < ? ORDER BY id DESC LIMIT 1');
$prev_stmt->execute([$post_id]);
$prev_post = $prev_stmt->fetch();

$next_stmt = $pdo->prepare('SELECT id FROM posts WHERE id > ? ORDER BY id ASC LIMIT 1');
$next_stmt->execute([$post_id]);
$next_post = $next_stmt->fetch();

// Pobranie komentarzy dla posta
$comments_stmt = $pdo->prepare('SELECT comments.*, users.username FROM comments LEFT JOIN users ON comments.author_id = users.id WHERE comments.post_id = ? ORDER BY comments.date_added ASC');
$comments_stmt->execute([$post_id]);
$comments = $comments_stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($post['title']); ?></title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
<?php include 'includes/header.php'; ?>
<h1><?php echo htmlspecialchars($post['title']); ?></h1>
<p><?php echo htmlspecialchars($post['content']); ?></p>
<p>By: <?php echo htmlspecialchars($post['username']); ?></p>
<p>Published on: <?php echo htmlspecialchars($post['date_published']); ?></p>

<?php if (isLoggedIn() && ($_SESSION['user_id'] == $post['user_id'] || $_SESSION['user_role'] == 'admin')): ?>
    <a href="edit_post.php?id=<?php echo $post['id']; ?>">Edit</a>
    <form action="delete_post.php" method="post" style="display:inline;">
        <input type="hidden" name="id" value="<?php echo $post['id']; ?>">
        <button type="submit">Delete</button>
    </form>
<?php endif; ?>


<div class="navigation">
    <?php if ($prev_post): ?>
        <a href="post.php?id=<?php echo $prev_post['id']; ?>">Previous Post</a>
    <?php endif; ?>
    <?php if ($next_post): ?>
        <a href="post.php?id=<?php echo $next_post['id']; ?>">Next Post</a>
    <?php endif; ?>
</div>

<h2>Comments</h2>
<?php foreach ($comments as $comment): ?>
    <div class="comment">
        <p><?php echo htmlspecialchars($comment['content']); ?></p>
        <p>By: <?php echo htmlspecialchars($comment['username'] ?? 'Guest'); ?></p>
        <p>On: <?php echo htmlspecialchars($comment['date_added']); ?></p>
    </div>
<?php endforeach; ?>

<h3>Add a Comment</h3>
<form action="add_comment.php" method="post">
    <textarea name="content" required></textarea><br>
    <input type="hidden" name="post_id" value="<?php echo $post_id; ?>">
    <button type="submit">Submit</button>
</form>

<?php if ($post['user_id'] != $_SESSION['user_id']): ?>
    <h3>Send a Message to the Author</h3>
    <form action="send_message.php" method="post">
        <input type="hidden" name="post_id" value="<?php echo $post_id; ?>">

        <label for="name">Name:</label>
        <input type="text" id="name" name="name" required><br>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required><br>

        <label for="message">Message:</label>
        <textarea id="message" name="message" required></textarea><br>

        <button type="submit">Send Message</button>
    </form>



<?php endif; ?>
</body>
</html>

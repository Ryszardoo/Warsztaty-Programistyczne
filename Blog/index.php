<?php
session_start();
require 'includes/db.php';
require 'includes/functions.php';
require 'includes/header.php';

$query = $pdo->query('SELECT * FROM posts ORDER BY date_published DESC');
$posts = $query->fetchAll(PDO::FETCH_ASSOC);
?>

<main>
    <h1>Blog</h1>
    <?php foreach ($posts as $post): ?>
        <article>
            <h2><a href="post.php?id=<?= $post['id'] ?>"><?= sanitize($post['title']) ?></a></h2>
            <p><?= sanitize(substr($post['content'], 0, 200)) ?>...</p>
            <p><a href="post.php?id=<?= $post['id'] ?>">Read more</a></p>
        </article>
    <?php endforeach; ?>
</main>

<?php require 'includes/footer.php'; ?>

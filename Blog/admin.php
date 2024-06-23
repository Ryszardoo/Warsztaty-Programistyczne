<?php
session_start();
require 'includes/db.php';
require 'includes/functions.php';

// Sprawdzenie, czy użytkownik jest zalogowany i ma rolę admina
if (!isLoggedIn() || $_SESSION['user_role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

// Pobieranie wszystkich użytkowników z bazy danych
$stmt = $pdo->prepare('SELECT id, username, email, role FROM users');
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Pobieranie wszystkich postów z bazy danych
$stmt = $pdo->prepare('SELECT posts.id, posts.title, posts.content, posts.date_published, posts.user_id, users.username AS author FROM posts JOIN users ON posts.user_id = users.id');
$stmt->execute();
$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Pobieranie logów z bazy danych
$stmt = $pdo->prepare('SELECT logs.id, users.username, logs.action, logs.timestamp FROM logs JOIN users ON logs.user_id = users.id ORDER BY logs.timestamp DESC');
$stmt->execute();
$logs = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
<h1>Admin Panel</h1>

<h2>Users</h2>
<table>
    <thead>
    <tr>
        <th>ID</th>
        <th>Username</th>
        <th>Email</th>
        <th>Role</th>
        <th>Actions</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($users as $user): ?>
        <tr>
            <td><?php echo htmlspecialchars($user['id']); ?></td>
            <td><?php echo htmlspecialchars($user['username']); ?></td>
            <td><?php echo htmlspecialchars($user['email']); ?></td>
            <td><?php echo htmlspecialchars($user['role']); ?></td>
            <td>
                <form action="change_role.php" method="post" style="display:inline;">
                    <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                    <select name="role">
                        <option value="admin" <?php if ($user['role'] == 'admin') echo 'selected'; ?>>Admin</option>
                        <option value="author" <?php if ($user['role'] == 'author') echo 'selected'; ?>>Author</option>
                        <option value="user" <?php if ($user['role'] == 'user') echo 'selected'; ?>>User</option>
                    </select>
                    <button type="submit">Change Role</button>
                </form>
                <form action="reset_password.php" method="post" style="display:inline;">
                    <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                    <button type="submit">Reset Password</button>
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<h2>Posts</h2>
<table>
    <thead>
    <tr>
        <th>ID</th>
        <th>Title</th>
        <th>Content</th>
        <th>Date Published</th>
        <th>Author</th>
        <th>Actions</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($posts as $post): ?>
        <tr>
            <td><?php echo htmlspecialchars($post['id']); ?></td>
            <td><?php echo htmlspecialchars($post['title']); ?></td>
            <td><?php echo htmlspecialchars($post['content']); ?></td>
            <td><?php echo htmlspecialchars($post['date_published']); ?></td>
            <td><?php echo htmlspecialchars($post['author']); ?></td>
            <td>
                <?php if ($_SESSION['user_id'] == $post['user_id'] || $_SESSION['user_role'] == 'admin'): ?>
                    <a href="edit_post.php?id=<?php echo $post['id']; ?>">Edit</a>
                    <form action="delete_post.php" method="post" style="display:inline;">
                        <input type="hidden" name="id" value="<?php echo $post['id']; ?>">
                        <button type="submit">Delete</button>
                    </form>
                <?php endif; ?>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<h2>Logs</h2>
<table>
    <thead>
    <tr>
        <th>ID</th>
        <th>Username</th>
        <th>Action</th>
        <th>Timestamp</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($logs as $log): ?>
        <tr>
            <td><?php echo htmlspecialchars($log['id']); ?></td>
            <td><?php echo htmlspecialchars($log['username']); ?></td>
            <td><?php echo htmlspecialchars($log['action']); ?></td>
            <td><?php echo htmlspecialchars($log['timestamp']); ?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<p><a href="reset_password.php">Reset Password</a></p>
<p><a href="index.php">Back to Home</a></p>
</body>
</html>

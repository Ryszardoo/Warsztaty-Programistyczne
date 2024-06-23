<?php
require_once 'includes/functions.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
<header>
    <nav>
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="post.php">Posts</a></li> <!-- Zakładka "Posts" -->
            <?php require_once ('functions.php');
            if (isLoggedIn()): ?>
                <li><a href="add_post.php">Add Post</a></li>
                <li><a href="inbox.php">Inbox</a></li>
                <li><a href="admin.php">Admin Panel</a></li>
                <li><a href="logout.php">Logout</a></li>
                <p><a href="reset_password.php">Reset Password</a></p>
            <?php else: ?>
                <li><a href="register.php">Register</a></li>
                <li><a href="login.php">Login</a></li>
            <?php endif; ?>
        </ul>
    </nav>
</header>

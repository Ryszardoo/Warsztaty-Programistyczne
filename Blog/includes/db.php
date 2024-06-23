<?php
$host = 'szuflandia.pjwstk.edu.pl';  // Change this to your host
$dbname = 'blog';     // Database name
$username = 's30898';   // Change this to your MySQL username
$password = 'Rys.Rako';       // Change this to your MySQL password

try {
    // Connect to MySQL server
    $pdo = new PDO("mysql:host=$host", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Create the database if it doesn't exist
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `$dbname`;");
    echo "Database `$dbname` created successfully or already exists.\n";

    // Connect to the newly created database
    $pdo->exec("USE `$dbname`;");

    // SQL dump from phpMyAdmin
    $sqlDump = "
    SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';
    START TRANSACTION;
    SET time_zone = '+00:00';

    /*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
    /*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
    /*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
    /*!40101 SET NAMES utf8mb4 */;

    CREATE TABLE `comments` (
      `id` int(11) NOT NULL,
      `content` text NOT NULL,
      `post_id` int(11) NOT NULL,
      `author_id` int(11) DEFAULT NULL,
      `date_added` timestamp NOT NULL DEFAULT current_timestamp()
    ) ENGINE=InnoDB DEFAULT CHARSET=latin1;

    CREATE TABLE `logs` (
      `id` int(11) NOT NULL,
      `user_id` int(11) DEFAULT NULL,
      `action` varchar(255) DEFAULT NULL,
      `timestamp` datetime DEFAULT current_timestamp()
    ) ENGINE=InnoDB DEFAULT CHARSET=latin1;

    CREATE TABLE `messages` (
      `id` int(11) NOT NULL,
      `name` varchar(100) NOT NULL,
      `email` varchar(100) NOT NULL,
      `message` text NOT NULL,
      `date_sent` timestamp NOT NULL DEFAULT current_timestamp(),
      `author_id` int(11) NOT NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=latin1;

    CREATE TABLE `posts` (
      `id` int(11) NOT NULL,
      `title` varchar(255) NOT NULL,
      `content` text NOT NULL,
      `image` varchar(255) DEFAULT NULL,
      `date_published` timestamp NOT NULL DEFAULT current_timestamp(),
      `user_id` int(11) NOT NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=latin1;

    CREATE TABLE `users` (
      `id` int(11) NOT NULL,
      `username` varchar(50) NOT NULL,
      `email` varchar(100) NOT NULL,
      `password` varchar(255) NOT NULL,
      `role` enum('admin','author','user') DEFAULT 'user',
      `created_at` timestamp NOT NULL DEFAULT current_timestamp()
    ) ENGINE=InnoDB DEFAULT CHARSET=latin1;

    INSERT INTO `users` (`id`, `username`, `email`, `password`, `role`, `created_at`) VALUES
    (1, 'test1', 'test1@gmail.com', '$2y$10$ITWdYrfuqVOtoh9sW05t8eCFyRWddNdE.hkxTfdZx3y9Wci5PJ4vq', 'admin', '2024-06-22 10:35:37'),
    (2, 'test2', 'test2@gmail.com', '$2y$10$ZN9zVzMwlyURuEOYq8LDo.4HvqzYlwcHF/zE8kwyrwP5nzDaBMlsa', 'user', '2024-06-22 15:18:54');

    ALTER TABLE `comments`
      ADD PRIMARY KEY (`id`),
      ADD KEY `post_id` (`post_id`),
      ADD KEY `author_id` (`author_id`);

    ALTER TABLE `logs`
      ADD PRIMARY KEY (`id`),
      ADD KEY `user_id` (`user_id`);

    ALTER TABLE `messages`
      ADD PRIMARY KEY (`id`);

    ALTER TABLE `posts`
      ADD PRIMARY KEY (`id`);

    ALTER TABLE `users`
      ADD PRIMARY KEY (`id`);

    ALTER TABLE `comments`
      MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

    ALTER TABLE `logs`
      MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=77;

    ALTER TABLE `messages`
      MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

    ALTER TABLE `posts`
      MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

    ALTER TABLE `users`
      MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

    ALTER TABLE `comments`
      ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`),
      ADD CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`);

    ALTER TABLE `logs`
      ADD CONSTRAINT `logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
    COMMIT;

    /*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
    /*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
    /*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
    ";

    // Execute the SQL dump
    $pdo->exec($sqlDump);
    echo "Database and tables created successfully.";
} catch (PDOException $e) {
    die("Could not connect to the database $dbname: " . $e->getMessage());
}
?>

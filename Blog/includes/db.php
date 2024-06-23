<?php
$servername = "szuflandia.pjwstk.edu.pl";
$username = "s30898";
$password = "Rys.Rako";
$dbname = "blog";

try {
    // Create a new PDO instance
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // Set the PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // SQL statements to create tables
    $sql = "
    CREATE TABLE IF NOT EXISTS messages (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        email VARCHAR(100) NOT NULL,
        message TEXT NOT NULL,
        date_sent TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
        author_id INT NOT NULL
    );

    CREATE TABLE IF NOT EXISTS posts (
        id INT AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(255) NOT NULL,
        content TEXT NOT NULL,
        image VARCHAR(255) NULL,
        date_published TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
        user_id INT NOT NULL
    );

    CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(50) NOT NULL,
        email VARCHAR(100) NOT NULL,
        password VARCHAR(255) NOT NULL,
        role ENUM('admin', 'author', 'user') DEFAULT 'user' NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL
    );

    CREATE TABLE IF NOT EXISTS comments (
        id INT AUTO_INCREMENT PRIMARY KEY,
        content TEXT NOT NULL,
        post_id INT NOT NULL,
        author_id INT NULL,
        date_added TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
        CONSTRAINT comments_ibfk_1 FOREIGN KEY (post_id) REFERENCES posts (id),
        CONSTRAINT comments_ibfk_2 FOREIGN KEY (author_id) REFERENCES users (id)
    );

    CREATE INDEX IF NOT EXISTS author_id ON comments (author_id);
    CREATE INDEX IF NOT EXISTS post_id ON comments (post_id);

    CREATE TABLE IF NOT EXISTS logs (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NULL,
        action VARCHAR(255) NULL,
        timestamp DATETIME DEFAULT CURRENT_TIMESTAMP NULL,
        CONSTRAINT logs_ibfk_1 FOREIGN KEY (user_id) REFERENCES users (id)
    );

    CREATE INDEX IF NOT EXISTS user_id ON logs (user_id);
    ";

    // Execute the SQL statements
    $pdo->exec($sql);

    echo "Database tables created successfully.";
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}

// Close the database connection
$pdo = null;
?>

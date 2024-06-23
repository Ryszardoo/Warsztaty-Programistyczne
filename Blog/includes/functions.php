<?php
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function isAdmin() {
    return isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
}


function isAuthor() {
    // zakładamy, że użytkownik ma rolę "author"
    return isset($_SESSION['role']) && $_SESSION['role'] === 'author';
}

function redirect($url) {
    header("Location: $url");
    exit();
}

function sanitize($data) {
    return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
}

function getUserRole() {
    if (isset($_SESSION['user_id'])) {
        global $pdo;
        $stmt = $pdo->prepare('SELECT role FROM users WHERE id = ?');
        $stmt->execute([$_SESSION['user_id']]);
        return $stmt->fetchColumn();
    }
    return null;
}

function logAction($userId, $action) {
    global $pdo;
    $stmt = $pdo->prepare('INSERT INTO logs (user_id, action) VALUES (?, ?)');
    $stmt->execute([$userId, $action]);
}

?>



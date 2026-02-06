<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
require_once 'dbConfig.php'; //db connection

if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
    $now = time(); // fetches current time

    $stmt = $pdo->prepare("SELECT last_ping FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    $upd = $pdo->prepare("UPDATE users SET last_ping = ? WHERE username = ?");
    $upd->execute([$now, $username]);

    // logs current user out if heartbeat / ping is not updated for 20 secs
    if ($user && $user['last_ping'] > 0) {
        $diff = $now - $user['last_ping'];
        
        if ($diff > 20) { 
            session_unset();
            session_destroy();
            header("Location: loginUser.php?reason=expired");
            exit();
        }
    }
} else {
    header("Location: loginUser.php");
    exit();
}

?>
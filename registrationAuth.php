<?php

    require_once 'dbConfig.php';
    session_start();

    if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
        $username = $_POST['username'];
        $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

        $checkstmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
        $checkstmt->execute(['username' => $username]);
        $usernameExists = $checkstmt->fetchColumn();

        if ($usernameExists) {
            $_SESSION['registration_msg'] = "<p style='color: red;'>Username already taken. Please choose another.</p>";
        } else {
            $sql = "INSERT INTO users (username, password) VALUES (:username, :password)";
            $stmt = $pdo->prepare($sql);
        
            if ($stmt->execute(['username' => $username, 'password' => $password])) {
                $_SESSION['registration_msg'] = "<p style='color: green;'>User registered successfully! Redirecting back to dashboard..</p>";
            } else {
                $_SESSION['registration_msg'] = "<p style='color: red;'>Error during registration.</p>";
            }
        }

        header("Location: registerIntern.php");
        exit();
    }
?>
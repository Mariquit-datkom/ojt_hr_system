<?php

    require_once 'dbConfig.php'; // db connection
    session_start(); // session fetch

    // Form Submission Authentication
    if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
        $username = $_POST['username'];
        $password = $_POST['password'];
        $confirm_password = $_POST['confirm-password'];

        // Checks for existing username
        $checkstmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
        $checkstmt->execute(['username' => $username]);
        $usernameExists = $checkstmt->fetchColumn();

        if ($password !== $confirm_password) {
            $_SESSION['registration_msg'] = "<p style='color: red;'>Passwords don't match</p>";
        } else if ($usernameExists){
            $_SESSION['registration_msg'] = "<p style='color: red;'>Username already taken. Please choose another.</p>";
        } else {
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);
            $sql = "INSERT INTO users (username, password) VALUES (:username, :password)";
            $stmt = $pdo->prepare($sql);

            // If user account creation is successful, copy user id to intern account info
            if ($stmt->execute(['username' => $username, 'password' => $hashed_password])) {
                $userId = $pdo->lastInsertId();
                $internSql = "INSERT INTO intern_list (user_id) VALUES (:user_id)";
                $internStmt = $pdo->prepare($internSql);
                $internStmt->execute(['user_id' => $userId]); 
                
                $_SESSION['registration_msg'] = "<p style='color: green;'>User registered successfully!</p>";
            } else {
                $_SESSION['registration_msg'] = "<p style='color: red;'>Error during registration.</p>";
            }
        }        

        // Self-redirect to show confirmation message
        header("Location: registerIntern.php");
        exit();
    }
?>
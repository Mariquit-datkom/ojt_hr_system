<?php

    require_once 'dbConfig.php'; // db connection
    session_start(); // session fetch

    // Form Submission Authentication
    if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
        $username = $_POST['username'];
        $password = password_hash($_POST['password'], PASSWORD_BCRYPT); // Password hashing for better security

        // Checks for existing username
        $checkstmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
        $checkstmt->execute(['username' => $username]);
        $usernameExists = $checkstmt->fetchColumn();

        if ($usernameExists) {
            $_SESSION['registration_msg'] = "<p style='color: red;'>Username already taken. Please choose another.</p>";
        } else {
            $sql = "INSERT INTO users (username, password) VALUES (:username, :password)";
            $stmt = $pdo->prepare($sql);

            // If user account creation is successful, copy user id to intern account info
            if ($stmt->execute(['username' => $username, 'password' => $password])) {
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
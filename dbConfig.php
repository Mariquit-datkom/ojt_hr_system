<?php
    $host = "localhost";
    $db = 'logintrial';
    $user = 'root';
    $password = '';
    $charset = 'utf8mb4';
    $collate = 'utf8mb4_general_ci';

    $adminUser = 'admin';
    $adminPw = '$2y$10$9aJAuuKBJURUihM0jKI/gepStTB/KZ5rAlzn9j.H/X3ReJTBhPGH.';

    $dsn = "mysql:host=$host;charset=$charset";

    try {
        $pdo = new PDO($dsn, $user, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $pdo->exec("CREATE DATABASE IF NOT EXISTS `$db` CHARACTER SET $charset COLLATE $collate");
        $pdo->exec("USE `$db`");

        $sql = "CREATE TABLE IF NOT EXISTS users (
            db_id INT(11) AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(50) NOT NULL,
            password VARCHAR(255) NOT NULL
        )";

        $pdo->exec($sql);

        $checkAdminSql = "SELECT COUNT(*) FROM users WHERE username = :username";
        $checkAdmin = $pdo->prepare($checkAdminSql);
        $checkAdmin->execute(['username' => $adminUser]);

        if ($checkAdmin->fetchColumn() == 0) {
            $sql = "INSERT INTO users (username, password) VALUES (:username, :password)";
            $sql = $pdo->prepare($sql);
            $sql->execute(['username' => $adminUser, 'password' => $adminPw]);
        }
    
    } catch (PDOException $e) {
        die("Connection failed: ". $e->getMessage());
    }
?>
<?php
    $host = "localhost";
    $db = 'logintrial';
    $user = 'root';
    $password = '';
    $charset = 'utf8mb4';
    $collate = 'utf8mb4_general_ci';

    $dsn = "mysql:host=$host;dbname=$db;charset=$charset";

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
    
    } catch (PDOException $e) {
        die("Connection failed: ". $e->getMessage());
    }
?>
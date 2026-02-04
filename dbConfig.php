<?php
    $host = "localhost";
    $db = 'logintrial';
    $user = 'root';
    $password = '';
    $charset = 'utf8mb4';
    $collate = 'utf8mb4_general_ci';

    $dsn = "mysql:host=$host;charset=$charset";

    try {
        $pdo = new PDO($dsn, $user, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $filename = 'assets/dbSetup.sql';
        if (file_exists($filename)) {
            $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, 1);
            $sql = file_get_contents($filename);
            $pdo->exec($sql);
        } else {
            error_log("SQL file not found: " . $filename);
        }

     }  catch (PDOException $e) {
        die("Connection failed: ". $e->getMessage());
    }
?>
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

        $pdo->exec("CREATE DATABASE IF NOT EXISTS `$db` CHARACTER SET $charset COLLATE $collate");
        $pdo->exec("USE `$db`");     
        
        createTables($pdo);
        checkAdmin($pdo);

     }  catch (PDOException $e) {
        die("Connection failed: ". $e->getMessage());
    }

    function createTables($pdo){

        $sql = "CREATE TABLE IF NOT EXISTS users (
            user_id int(11) AUTO_INCREMENT PRIMARY KEY,
            username varchar(50) NOT NULL,
            password varchar(255) NOT NULL,
            last_ping int(11) DEFAULT 0
        )";

        $pdo->exec($sql);

        $sql = "CREATE TABLE IF NOT EXISTS intern_list( 
            intern_no int(15) AUTO_INCREMENT PRIMARY KEY, 
            user_id int(11) NOT NULL, 
            date_of_employment varchar(15) NOT NULL, 
            intern_last_name varchar(20) NOT NULL, 
            intern_first_name varchar(30) NOT NULL, 
            intern_middle_initial varchar(2) NOT NULL,
            intern_course varchar(50) NOT NULL, 
            intern_school varchar(50) NOT NULL,
            intern_dept varchar(30) NOT NULL, 
            total_hours_needed int(5) NOT NULL, 
            accumulated_hours int(5) NOT NULL, 
            remaining_hours int(5) NOT NULL,
            school varchar(100) NOT NULL
        )";

        $pdo->exec($sql);

        $sql = "CREATE TABLE IF NOT EXISTS request_list( 
            request_no int(15) AUTO_INCREMENT PRIMARY KEY, 
            request_no_display varchar(20) NOT NULL, 
            request_date varchar(8) NOT NULL,
            submitted_by varchar(50) NOT NULL, 
            request_subject varchar(50) NOT NULL,
            request_main varchar(500) NOT NULL,
            request_status varchar(20) NOT NULL,
            request_attachment varchar(255)
        )";

        $pdo->exec($sql);
    }

    function checkAdmin($pdo){
        
        $adminUser = 'admin';
        $adminPw = '$2y$10$9aJAuuKBJURUihM0jKI/gepStTB/KZ5rAlzn9j.H/X3ReJTBhPGH.';

        $checkAdminSql = "SELECT COUNT(*) FROM users WHERE username = :username";
        $checkAdmin = $pdo->prepare($checkAdminSql);
        $checkAdmin->execute(['username' => $adminUser]);
                    
        if ($checkAdmin->fetchColumn() == 0) {
            
            $sql = "INSERT INTO users (username, password) VALUES (:username, :password)";
            $sql = $pdo->prepare($sql);
            $sql->execute(['username' => $adminUser, 'password' => $adminPw]);
        }
    }
?>
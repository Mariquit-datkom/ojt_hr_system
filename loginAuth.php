<?php

    require_once 'dbConfig.php';
    session_start();

    if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
        $username = $_POST['username'];
        $password = $_POST['password'];

        $sql = "SELECT * FROM users WHERE username = :username";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['username' => $username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {

            $_SESSION['username'] = $user['username'];
            $_SESSION['user_id'] = $user['user_id'];

            $now = time();
            $update = $pdo->prepare("UPDATE users SET last_ping = ? WHERE username = ?");
            $update->execute([$now, $username]);

            if($user['username'] !== 'admin') {
                $redirectURL = 'internDashboard.php';

                $sql = "SELECT * FROM intern_list WHERE user_id = :user_id";
                $stmt = $pdo->prepare($sql);
                $stmt->execute(['user_id' => $_SESSION['user_id']]);
                $intern = $stmt->fetch();

                if($intern) {
                    $intern_first_name = $intern['intern_first_name'];
                    $_SESSION['intern_first_name'] = $intern_first_name;

                    $intern_display_id = $intern['intern_display_id'];
                    $_SESSION['intern_display_id'] = $intern_display_id;

                    $date_started = $intern['date_of_employment'];
                    $_SESSION['date_started'] = $date_started;

                    $intern_dept = $intern['intern_dept'];
                    $_SESSION['intern_dept'] = $intern_dept;

                    $intern_course = $intern['intern_course'];
                    $_SESSION['intern_course'] = $intern_course;

                    $school = $intern['school'];
                    $_SESSION['school'] = $school;

                    $total_hours_needed = $intern['total_hours_needed'];
                    $_SESSION['total_hours_needed'] = $total_hours_needed;

                    $accumulated_hours = $intern['accumulated_hours'];
                    $_SESSION['accumulated_hours'] = $accumulated_hours;
                    
                }

            }  else  $redirectURL = 'adminDashboard.php';

            header("Location: $redirectURL");
            exit();
            
        } else {

            $_SESSION['error'] = "<p style='color: red;'> Invalid username or password. Please try again. </p>";
            header("Location: loginUser.php");
            exit();
        }
    }
?>
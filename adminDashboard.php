<?php
    require 'dbConfig.php';
    session_start();

    if (!isset($_SESSION['username'])){
        header("Location: loginUser.php");
        exit();
    }

    $currentUser = $_SESSION['username'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/adminDashboard.css">
    <title>Dashboard - Admin</title>
</head>
<body>
    <header>
        <div class="header-left">
            <img src="assets/company_logo.png" alt="company_logo" class="company-logo">
            <span class="dashboard-title"> Admin Dashboard </span>
        </div>

        <div class="header-right">
            <span class="username"> <?php echo htmlspecialchars($currentUser); ?> </span>
            <img src="assets/user_avatar.png" alt="user_avatar" class="user-avatar">
        </div>
    </header>
</body>
</html>
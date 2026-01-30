<?php
    require 'dbConfig.php';
    session_start();
    
    header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
    header("Pragma: no-cache");

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
            <div class="user-menu-container">
                <img src="assets/user_avatar.png" alt="user_avatar" class="user-avatar" id="user-avatar-btn">
                <div class="dropdown-content" id="user-avatar-dropdown">
                    <a href="loginUser.php?action=logout">Logout</a>
                </div>
            </div>
        </div>
    </header>

    <nav class="admin-nav-bar">
        <div class="nav-links">
            <a href="traineeList.php" class="nav-item" data-text="Trainee List">Trainee List</a>
            <a href="requestList.php" class="nav-item" data-text="Requests">Requests</a>
            <a href="registerUser.php" class="nav-item" data-text="Register User">Register User</a>
        </div>
    </nav>

    <script src="js/dropDownMenu.js"></script>
    <script src="js/backBtnKiller.js"></script>
</body>
</html>
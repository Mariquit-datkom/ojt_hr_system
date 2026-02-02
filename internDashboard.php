<?php
    require_once 'dbConfig.php';
    require_once 'sessionChecker.php';
    
    header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
    header("Cache-Control: post-check=0, pre-check=0", false);
    header("Pragma: no-cache");

    if (!isset($_SESSION['username'])){
        header("Location: loginUser.php");
        exit();
    }

    $currentUser = $_SESSION['username'];
    $currentPage = basename($_SERVER['PHP_SELF']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/internDashboardPublic.css">
    <title>Dashboard</title>
</head>
<body>
    <header>
        <div class="header-left">
            <img src="assets/company_logo.png" alt="company_logo" class="company-logo">
            <span class="dashboard-title"> Dashboard </span>
        </div>

        <div class="header-right">
            <span class="username"> <?php echo htmlspecialchars($currentUser); ?> </span>
            <div class="user-menu-container">
                <img src="assets/user_avatar.png" alt="user_avatar" class="user-avatar" id="user-avatar-btn">
                <div class="dropdown-content" id="user-avatar-dropdown">
                    <span class="mobile-username"><?php echo htmlspecialchars($currentUser); ?></span>
                    <a href="logout.php">Logout</a>
                </div>
            </div>
        </div>
    </header>
    
    <nav class="nav-bar">
        <div class="nav-links">
            <a href="javascript:void(0)" class="nav-item
            <?php echo ($currentPage === 'internDashboard.php') ? 'active' : ''; ?>"
            data-text="Dashboard">Dashboard
            </a>
            <a href="accInfo.php" class="nav-item
            <?php echo ($currentPage === 'accInfo.php') ? 'active' : ''; ?>"
            data-text="Account Info">Account Info
            </a>
            <a href="timeSheet.php" class="nav-item
            <?php echo ($currentPage === 'timeSheet.php') ? 'active' : ''; ?>"
            data-text="Time Sheet">Time Sheet
            </a>
            <a href="submitRequest.php" class="nav-item
            <?php echo ($currentPage === 'submitRequest.php') ? 'active' : ''; ?>"
            data-text="Submit Request">Submit Request
            </a>
        </div>
    </nav>

    <script src="js/dropDownMenu.js"></script>
    <script src="js/backBtnKiller.js"></script>
    <script src="js/sendHeartbeat.js"></script>
</body>
</html>
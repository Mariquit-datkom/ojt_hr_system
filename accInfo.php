<?php
    require 'dbConfig.php';
    session_start();
    
    header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
    header("Cache-Control: post-check=0, pre-check=0", false);
    header("Pragma: no-cache");

    if (!isset($_SESSION['username'])){
        header("Location: loginUser.php");
        exit();
    }

    $currentUser = $_SESSION['username'];
    $currentPage = basename($_SERVER['PHP_SELF']);

    $confirmationMessage = "";
    if (isset($_SESSION['accinfo_msg'])) {
        $confirmationMessage = $_SESSION['accinfo_msg'];
        unset($_SESSION['accinfo_msg']); 
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/internDashboardPublic.css">
    <link rel="stylesheet" href="css/accInfo.css">
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
                    <a href="logout.php">Logout</a>
                </div>
            </div>
        </div>
    </header>
    
    <nav class="nav-bar">
        <div class="nav-links">
            <a href="internDashboard.php" class="nav-item
            <?php echo ($currentPage === 'internDashboard.php') ? 'active' : ''; ?>"
            data-text="Dashboard">Dashboard
            </a>
            <a href="javascript:void(0)" class="nav-item
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

    <div class="acc-info-container">
        <div class="form-title-container">
            <h2 class="form-title">Account Information</h2>
            <?php echo $confirmationMessage ?>
        </div>
        <form action="accInfoAuth.php" method="POST" autocomplete="off">
            <div class="row">
                <div class="form-group">
                    <label for="last-name" class="form-label">Last Name: </label>
                    <input type="text" name="last-name" id="last-name" class="general-input" required="required">                    
                </div>
                <div class="form-group">
                    <label for="first-name" class="form-label">First Name: </label>
                    <input type="text" name="first-name" id="first-name" class="general-input" required="required">
                </div>
                <div class="form-group">
                    <label for="middle-initial" class="form-label">Middle Initial: </label>
                    <input type="text" name="middle-initial" id="middle-initial" class="general-input middle-initial" required="required">
                </div>
            </div>
            <div class="row">
                <div class="form-group">
                    <label for="employment-date" class="form-label">Employment Date: </label>
                    <input type="date" name="employment-date" id="employment-date" class="general-input employment-date" required="required">
                </div>
                <div class="form-group">
                    <label for="course" class="form-label">Course: </label>
                    <input type="text" name="course" id="course" class="general-input" required="required">
                </div>
                <div class="form-group">
                    <label for="division-or-section" class="form-label">Division / Section: </label>
                    <input type="text" name="division-or-section" id="division-or-section" class="general-input" required="required">
                </div>
            </div>
            <div class="row">
                <div class="form-group">
                    <label for="total-hours-needed" class="form-label">Total Hours Needed: </label>
                    <input type="text" name="total-hours-needed" id="total-hours-needed" class="general-input hours-input" required="required">
                </div>
                <div class="form-group">
                    <label for="accumulated-hours" class="form-label">Accumulated Hours: </label>
                    <input type="text" name="accumulated-hours" id="accumulated-hours" class="general-input hours-input" required="required">
                </div>
                <div class="form-group">
                    <label for="school" class="form-label">School: </label>
                    <input type="text" name="school" id="school" class="general-input school-input" required="required">
                </div>
            </div>
            <div class="btn-submit-container">
                <input type="submit" value="Save Account Information" class="btn-submit">
            </div>
        </form>
    </div>

    <script src="js/dropDownMenu.js"></script>
    <script src="js/backBtnKiller.js"></script>
</body>
</html>
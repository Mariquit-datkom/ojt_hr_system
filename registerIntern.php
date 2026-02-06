<?php
    require_once 'dbConfig.php'; //db connection   
    require_once 'sessionChecker.php'; //Session heartbeat checker
    require_once 'x-head.php'; // icons
    
    //Cache remover to prevent showing sensitive data on back button press after log out
    header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
    header("Pragma: no-cache");

    //Verifies if user reached this page through log in
    if (!isset($_SESSION['username'])){
        header("Location: loginUser.php");
        exit();
    }

    //Variable Declarations
    $currentUser = $_SESSION['username'];
    $currentPage = basename($_SERVER['PHP_SELF']);

    $confirmationMessage = "";
    if (isset($_SESSION['registration_msg'])) {
        $confirmationMessage = $_SESSION['registration_msg'];
        unset($_SESSION['registration_msg']); 
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/public.css">
    <link rel="stylesheet" href="css/registerIntern.css">
    <title>Register an Intern</title>
</head>
<body>
    <!-- Page Header -->
    <header>
        <div class="header-left">
            <img src="assets/company_logo.png" alt="company_logo" class="company-logo">
            <span class="dashboard-title"> Register Intern Account </span>
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

    <!-- Page Navigation Bar -->
    <nav class="nav-bar">
        <div class="nav-links">
            <a href="adminDashboard.php" class="nav-item 
            <?php echo ($currentPage === 'adminDashboard.php') ? 'active' : ''; ?>" 
            data-text="Dashboard"><i class="fa fa-home"></i>Dashboard
            </a>

            <a href="internsListPage.php" class="nav-item 
            <?php echo ($currentPage === 'internsListPage.php') ? 'active' : ''; ?>" 
            data-text="Interns List"><i class="fa fa-clipboard"></i>Interns List
            </a>

            <a href="requestsPage.php" class="nav-item
            <?php echo ($currentPage === 'requestsPage.php') ? 'active' : ''; ?>" 
            data-text="Requests"><i class="fa fa-question"></i>Requests
            </a>

            <a href="javascript:void(0)" class="nav-item 
            <?php echo ($currentPage === 'registerIntern.php') ? 'active' : ''; ?>" 
            data-text="Register Intern"><i class="fa fa-pencil"></i>Register Intern
            </a>
        </div>
    </nav>
    
    <!-- Registration Parent Container -->
    <div class="registrationContainer">
        <div class="form-title-container">
            <h2 class="registrationTitle"> Register an Account </h2>
            <?php echo $confirmationMessage; ?>
        </div>
        <form action="registrationAuth.php" method="POST" autocomplete="off">
            <div class="row">
                <div class="formGroup"> <!-- Username Input -->
                    <label for="username" class="formLabel"> Username: </label>
                    <input type="username" class="formInput" id="username" name="username" required>
                </div>
            </div>
            <div class="row">
                <div class="formGroup"> <!-- Password Input -->
                    <label for="password" class="formLabel"> Password: </label>  
                    <input type="text" class="formInput" id="password" name="password" required>
                </div>
            </div>
            <div class="registerButtonContainer">
                <input type="submit" class="registerButton" value="Register">
            </div>
        </form>
    </div>

    <!-- Scripts -->
    <script src="js/dropDownMenu.js"></script>
    <script src="js/backBtnKiller.js"></script>
    <script src="js/sendHeartbeat.js"></script>
</body>
</html>
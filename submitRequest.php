<?php
    require_once 'dbConfig.php'; // db connection
    require_once 'sessionChecker.php'; // session heartbeat checker
    include 'x-head.php'; // icons

    // Clears cache to prevent unauthorized access after log out
    header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
    header("Cache-Control: post-check=0, pre-check=0", false);
    header("Pragma: no-cache");

    // Verifies if user reached this page through log in
    if (!isset($_SESSION['username'])){
        header("Location: loginUser.php");
        exit();
    }

    // Variable Declarations
    $currentUser = $_SESSION['username'];
    $currentId = $_SESSION['user_id'];
    $currentPage = basename($_SERVER['PHP_SELF']);

    $confirmationMessage = "";
    if (isset($_SESSION['request_form_msg'])) {
        $confirmationMessage = $_SESSION['request_form_msg'];
        unset($_SESSION['request_form_msg']); 
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/public.css?v=1.2">
    <link rel="stylesheet" href="css/submitRequest.css?v=1.2">
    <title>Submit Request</title>
</head>
<body>
    <!-- Page Header -->
    <header>
        <!-- Company logo and page title --> 
        <div class="header-left">
            <img src="assets/company_logo.png" alt="company_logo" class="company-logo">
            <span class="dashboard-title"> Request Form </span>
        </div>
        <!-- Username and icon -->
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
    <!-- Page Navigation Bar -->
    <nav class="nav-bar">
        <div class="nav-links">
            <a href="internDashboard.php" class="nav-item
            <?php echo ($currentPage === 'internDashboard.php') ? 'active' : ''; ?>"
            data-text="Dashboard"><i class = "fa fa-home"></i>Dashboard
            </a>
            <a href="accInfo.php" class="nav-item
            <?php echo ($currentPage === 'accInfo.php') ? 'active' : ''; ?>"
            data-text="Account Info"><i class = "fa fa-user"></i>Account Info
            </a>
            <a href="timeSheet.php" class="nav-item
            <?php echo ($currentPage === 'timeSheet.php') ? 'active' : ''; ?>"
            data-text="Time Sheet"><i class = "fa fa-calendar-check"></i>Time Sheet
            </a>
            <a href="javascript:void(0)" class="nav-item
            <?php echo ($currentPage === 'submitRequest.php') ? 'active' : ''; ?>"
            data-text="Submit Request"><i class = "fa fa-paperclip"></i>Submit Request
            </a>
        </div>
    </nav>
    <!-- Request Form Parent Container -->
    <div class="request-form-container">
        <div class="form-title-container">
            <h2 class="form-title">OJT Request Form</h2>
        </div>
        <form action="submitRequestAuth.php" method="POST" enctype="multipart/form-data" autocomplete="off">
            <div class="row">
                <div class="form-group"> <!-- Subject Input Field -->
                    <label for="request-subject" class="form-label">Subject:</label>
                    <input type="text" id="request-subject" name="request-subject" class="general-input">
                </div>
                <div class="form-group"> <!-- Current Date -->
                    <label for="date" class="form-label">Date:</label>
                    <input type="date" id="date" name="date" class="general-input" value="<?php echo date('Y-m-d'); ?>" readonly>
                </div>
                <div class="form-group"> <!-- Attachment Field -->
                    <label for="attachment" class="form-label">Attachment (PDF / JPG / PNG):</label>
                    <input type="file" id="attachment" name="attachment[]" class="general-input" accept=".pdf, .jpg, .jpeg, .png" multiple>
                </div>
            </div>
            <div class="row">
                <div class="form-group"> <!-- Main Request Text Area -->
                    <label for="main-request" class="form-label">Main Request:</label>
                    <textarea id="main-request" name="main-request" class="general-input request-input" rows="1"></textarea>
                </div>
            </div>
            <div class="btn-submit-container">
                <input type="submit" value="Submit Request" class="btn-submit">
                <?php echo $confirmationMessage ?>
            </div>
        </form>
        </div>
    </div>

    <!-- Scripts -->
    <script src="js/dropDownMenu.js"></script>
    <script src="js/backBtnKiller.js"></script>
    <script src="js/sendHeartbeat.js"></script>
</body>
</html>
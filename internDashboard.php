<?php
    require_once 'dbConfig.php'; //db startup
    require_once 'sessionChecker.php'; //session heartbeat checker
    include 'x-head.php'; //for icons
    
    //Prevents browser to show sensitive data when back button is pressed after log out
    header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
    header("Cache-Control: post-check=0, pre-check=0", false);
    header("Pragma: no-cache");

    //Checks if user reached this page through log in
    if (!isset($_SESSION['username'])){
        header("Location: loginUser.php");
        exit();
    }

    //Variable declaration and settings
    $currentUser = $_SESSION['username'];
    $intern_first_name = $_SESSION['intern_first_name'];
    $intern_display_id = $_SESSION['intern_display_id'];
    $date_started = $_SESSION['date_started'];
    $intern_dept = $_SESSION['intern_dept'];
    $intern_course = $_SESSION['intern_course'];
    $school = $_SESSION['school'];
    $accumulated_hours = $_SESSION['accumulated_hours'];
    $total_hours_needed = $_SESSION['total_hours_needed'];
    $currentPage = basename($_SERVER['PHP_SELF']);

    $percentage = ($total_hours_needed > 0) ? ($accumulated_hours / $total_hours_needed) * 100 : 0;
    $percentage = min(100, max(0, $percentage));

    $sql_requests = "SELECT request_date, request_subject, request_status 
                FROM request_list 
                WHERE submitted_by = :intern_id 
                ORDER BY request_date DESC";
    $stmt_req = $pdo->prepare($sql_requests);
    $stmt_req->execute(['intern_id' => $intern_display_id]);
    $requests = $stmt_req->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/internDashboardPublic.css?v=1.2">
    <link rel="stylesheet" href="css/internDashboardMain.css?v=1.2">
    <title>Dashboard</title>
</head>
<body>
    <!-- Page Header -->
    <header>
        <div class="header-left">
            <img src="assets/company_logo.png" alt="company_logo" class="company-logo">
            <span class="dashboard-title"> Dashboard </span>
        </div>

        <!-- User icon with drop down menu for log out -->
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
            <a href="javascript:void(0)" class="nav-item
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
            <a href="submitRequest.php" class="nav-item
            <?php echo ($currentPage === 'submitRequest.php') ? 'active' : ''; ?>"
            data-text="Submit Request"><i class = "fa fa-paperclip"></i>Submit Request
            </a>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="main-container">
        <!-- Content Header with user greeting and a live clock -->
        <div class="welcome-message-container">
            <h2 class="welcome-message">Welcome, <?php echo htmlspecialchars($intern_first_name); ?>!</h2>

            <div class="live-clock-container">
                <div id="current-date"></div>
                <div id="current-time"></div>
            </div>
        </div>

        <div class="sub-container">
            <div class="left-container">
                <!-- Account info preview -->
                <div class="account">
                    <h2 class="container-title">Account</h2>
                    <div class="label-container">
                        <label for="intern-display-id" class="acc-labels"><span style="font-weight: bold">Intern ID: </span>
                            <?php echo htmlspecialchars($intern_display_id)?>
                        </label>
                        <label for="date_started" class="acc-labels"><span style="font-weight: bold">Date started: </span>
                            <?php echo htmlspecialchars($date_started)?>
                        </label>
                        <label for="accumulated-hours" class="acc-labels"><span style="font-weight: bold">Department/Section: </span>
                            <?php echo htmlspecialchars($intern_dept)?>
                        </label>
                        <label for="remaining-hours" class="acc-labels"><span style="font-weight: bold">Course: </span>
                            <?php echo htmlspecialchars($intern_course)?>
                        </label>
                        <label for="total_hours_needed" class="acc-labels"><span style="font-weight: bold">School: </span>
                            <?php echo htmlspecialchars($school)?>
                        </label>
                    </div>
                </div>
                <!-- Shows intern progress -->
                <div class="progress-bar">
                    <h2 class="container-title">Intern Progress</h2>    
                    <div class="progress-track">
                        <div class="progress-fill" style="width: <?php echo $percentage; ?>%;"></div>
                    </div>  
                    <div class="progress-info">
                        <span><?php echo number_format($percentage, 1); ?>% Completed</span>
                        <span><?php echo htmlspecialchars($accumulated_hours); ?> / <?php echo htmlspecialchars($total_hours_needed); ?> hrs</span>
                    </div>              
                </div>
            </div>
            <!-- Checks and displays requests and their status submitted by the user -->
            <div class="request-status-tracker">
                <h2 class="container-title">Request Status Tracker</h2>
                <div class="tracker-scroll-area">
                    <?php if (empty($requests)): ?>
                        <p class="no-requests" style='padding: 20px; text-align: center; color: #666;'>No requests submitted yet.</p>
                    <?php else: ?>
                        <?php foreach ($requests as $req): ?>
                            <div class="request-item">
                                <div class="request-details">
                                    <span class="req-subject"><?php echo htmlspecialchars($req['request_subject']); ?></span>
                                    <span class="req-date"><?php echo date('M d, Y', strtotime($req['request_date'])); ?></span>
                                </div>
                                <span class="status-badge <?php echo strtolower($req['request_status']); ?>">
                                    <?php echo htmlspecialchars($req['request_status']); ?>
                                </span>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Backend Scripts -->
    <script src="js/dropDownMenu.js"></script>
    <script src="js/backBtnKiller.js"></script>
    <script src="js/sendHeartbeat.js"></script>
    <script src="js/liveClock.js"></script>
</body>
</html>
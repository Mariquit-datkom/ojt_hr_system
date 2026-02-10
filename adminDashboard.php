<?php
    require_once 'dbConfig.php'; //db connection   
    require_once 'sessionChecker.php'; //Session heartbeat checker
    require_once 'x-head.php'; //icons
    
    //Cache remover to prevent showing sensitive data on back button press after log out
    header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
    header("Pragma: no-cache");

    //Verifies if user reached this page through log in
    if (!isset($_SESSION['username'])){
        header("Location: loginUser.php");
        exit();
    }

    //Counts current interns inside db table
    $sql = "SELECT COUNT(*) FROM intern_list";
    $totalInterns = $pdo->query($sql)->fetchColumn();

    //Count total pending requests
    $status = "Pending";
    $sql = $pdo->prepare("SELECT COUNT(*) FROM request_list WHERE request_status = ?");
    $sql->execute([$status]);
    $totalPendingRequests = $sql->fetchColumn();

    //Variable Declarations
    $currentUser = $_SESSION['username'];
    $currentPage = basename($_SERVER['PHP_SELF']);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/adminDashboardMain.css">
    <link rel="stylesheet" href="css/public.css">
    <title>Dashboard - Admin</title>
</head>
<body>
    <!-- Generate Page Header and Nav Bar -->
     <?php include 'adminHeaderAndNav.php'; ?>

     <!-- Main Content -->
    <div class="main-container">
        <!-- Content Header with user greeting and a live clock -->
        <div class="welcome-message-container">
            <h2 class="welcome-message">Welcome, Admin!</h2>
            <!-- Live Clock -->
            <div class="live-clock-container">
                <div id="current-date"></div>
                <div id="current-time"></div>
            </div>
        </div>
        <!-- Content Container -->
        <div class="content-container">
            <div class="left-container">
                <!-- Displays total current numbers -->
                <div class="current-interns-number left-child-container" onclick="window.location.href = 'internsListPage.php'">
                    <h2 class="container-label">Current Number of Interns</h2>
                    <div class="result-container">
                        <p><strong><?php echo htmlspecialchars($totalInterns) ?></strong> intern/s</p>
                    </div>
                </div>
                <!-- Displays total pending requests -->
                <div class="current-pending-requests-number left-child-container" onclick="window.location.href = 'requestsPage.php'">
                    <h2 class="container-label">Number of Pending Requests</h2>
                    <div class="result-container">
                        <p><strong><?php echo htmlspecialchars($totalPendingRequests) ?></strong> pending request/s</p>
                    </div>
                </div>
            </div>
            <!-- Notification Table -->
            <div class="real-time-notif-container">
                <h2 class="container-label">Notifications</h2>
                <div id="notification-list" class="notification-list">
                    <p>Loading notifications...</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="js/dropDownMenu.js"></script>
    <script src="js/backBtnKiller.js"></script>
    <script src="js/sendHeartbeat.js"></script>
    <script src="js/liveClock.js"></script>
</body>
</html>
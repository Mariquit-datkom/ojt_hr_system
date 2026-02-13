<?php
    require_once 'dbConfig.php'; //db connection
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
    $intern_first_name = $_SESSION['intern_first_name'] ?? "";
    $intern_display_id = $_SESSION['intern_display_id'] ?? "";
    $date_started = $_SESSION['date_started'] ?? "";
    $intern_dept = $_SESSION['intern_dept'] ?? "";
    $intern_course = $_SESSION['intern_course'] ?? "";
    $school = $_SESSION['school'] ?? "";
    $accumulated_hours = $_SESSION['accumulated_hours'] ?? 0;
    $total_hours_needed = $_SESSION['total_hours_needed'] ?? 0;
    $currentPage = basename($_SERVER['PHP_SELF']);

    $percentage = ($total_hours_needed > 0) ? ($accumulated_hours / $total_hours_needed) * 100 : 0;
    $percentage = min(100, max(0, $percentage));

    $sql_requests = "SELECT * FROM request_list 
                WHERE submitted_by = :intern_id AND request_status != 'Deleted'
                ORDER BY FIELD(request_status, 'Pending', 'Approved', 'Declined') ASC, CAST(request_no AS UNSIGNED) DESC";
    $stmt_req = $pdo->prepare($sql_requests);
    $stmt_req->execute(['intern_id' => $intern_display_id]);
    $requests = $stmt_req->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/public.css?v=1.2">
    <link rel="stylesheet" href="css/internDashboardMain.css?v=1.2">
    <title>Dashboard</title>
</head>
<body>
    <!-- Generate Page Header -->
    <?php include 'internHeaderAndNav.php'; ?>

    <!-- Main Content -->
    <div class="main-container">
        <!-- Content Header with user greeting and a live clock -->
        <div class="welcome-message-container">
            <h2 class="welcome-message">Welcome, <?php echo htmlspecialchars($intern_first_name); ?>!</h2>
            <!-- Live Clock -->
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
                        <!-- Intern Display Id -->
                        <label for="intern-display-id" class="acc-labels"><span style="font-weight: bold">Intern ID: </span>
                            <?php echo htmlspecialchars($intern_display_id)?>
                        </label>
                        <!-- Employment Date -->
                        <label for="date_started" class="acc-labels"><span style="font-weight: bold">Date started: </span>
                            <?php echo htmlspecialchars($date_started)?>
                        </label>
                        <!-- Intern Display Id -->
                        <label for="intern-department" class="acc-labels"><span style="font-weight: bold">Department/Section: </span>
                            <?php echo htmlspecialchars($intern_dept)?>
                        </label>
                        <!-- Intern Course / Program -->
                        <label for="intern-course" class="acc-labels"><span style="font-weight: bold">Course: </span>
                            <?php echo htmlspecialchars($intern_course)?>
                        </label>
                        <!-- School / University -->
                        <label for="intern-school" class="acc-labels"><span style="font-weight: bold">School: </span>
                            <?php echo htmlspecialchars($school)?>
                        </label>
                    </div>
                </div>
                <!-- Shows intern progress -->
                <div class="progress-bar clickable-progress" onclick="showProgressDetails()">
                    <h2 class="container-title">Intern Progress</h2>   
                    <!-- Progress Bar --> 
                    <div class="progress-track">
                        <div class="progress-fill" style="width: <?php echo $percentage; ?>%;"></div>
                    </div>  
                    <!-- Progress Info (Accumulated hours & Total Hours Needed) -->
                    <div class="progress-info">
                        <span><?php echo number_format($percentage, 1); ?>% Completed</span>
                        <span><?php echo htmlspecialchars($accumulated_hours); ?> / <?php echo htmlspecialchars($total_hours_needed); ?> hrs</span>
                    </div>              
                </div>
            </div>
            <!-- Checks and displays requests and their status submitted by the user -->
            <div class="request-status-tracker">
                <h2 class="container-title">Request Status Tracker</h2>
                <!-- Request Entry Container -->
                <div class="tracker-scroll-area">
                    <?php if (empty($requests)): ?>
                        <p class="no-requests" style='padding: 20px; text-align: center; color: #666;'>No requests submitted yet.</p>
                    <?php else: ?>
                        <?php foreach ($requests as $req): ?>
                            <!-- Request Entry -->
                            <div class="request-item clickable-request" 
                                onclick="showRequestDetails(<?php echo htmlspecialchars(json_encode($req)); ?>)">
                                <div class="request-details">
                                    <span class="req-subject"><?php echo htmlspecialchars($req['request_subject']); ?></span>
                                    <span class="req-date"><?php echo date('M d, Y', strtotime($req['request_date'])); ?> - <?php echo date('h:i:s a', strtotime($req['request_time'])) ?></span>
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
    <!-- Progress Tracker Pop Up -->
    <div id="progressModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Progress Breakdown</h2>
                <span class="close-btn" onclick="closeProgressModal()">&times;</span>
            </div>
            <div class="modal-body">
                <div class="progress-detail-item">
                    <strong>Total Hours Required:</strong> 
                    <span><?php echo number_format($total_hours_needed, 2); ?> hrs</span>
                </div>
                <div class="progress-detail-item">
                    <strong>Accumulated Hours:</strong> 
                    <span style="color: #28a745; font-weight: bold;">
                        <?php echo number_format($accumulated_hours, 2); ?> hrs
                    </span>
                </div>
                <div class="progress-detail-item">
                    <strong>Remaining Hours:</strong> 
                    <span style="color: #dc3545; font-weight: bold;">
                        <?php echo number_format($total_hours_needed - $accumulated_hours, 2); ?> hrs
                    </span>
                </div>
                <div class="progress-visual-summary">
                    <p>You have completed <strong><?php echo round($percentage, 1); ?>%</strong> of your internship.</p>
                </div>
            </div>
        </div>
    </div>
    <!-- Request Status Tracker Pop Up -->
    <div id="requestModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 id="modalSubject">Request Details</h2>
                <span class="close-btn" onclick="closeModal()">&times;</span>
            </div>
            <div class="modal-body">
                <form action="editRequest.php" method="post">
                    <input type="hidden" name="request_no" id="modalRequestNo">
                    <p><strong>Date:</strong> <span id="modalDate"></span></p>
                    <p><strong>Status:</strong> <span id="modalStatus" class="status-badge"></span></p>
                    <div class="request-main">
                        <p><strong>Message:</strong></p>
                        <div id="modalMainRequest" class="modal-text-area"></div>
                    </div>
                    <div class="modal-row" id="attachmentSection" style="display: none";>
                        <p><strong>Attachment/s:</strong></p>
                        <div id="modalAttachment"></div>
                    </div>
                    <input type="submit" class="edit-request request-window-btn" id="edit-request" value="Edit">
                    <input type="submit" onclick="deletePendingRequest()" class="delete-request request-window-btn" id="delete-request" value="Delete">
                </form>
            </div>
        </div>
    </div>

    <!-- Backend Scripts -->
    <script src="js/dropDownMenu.js"></script>
    <script src="js/backBtnKiller.js"></script>
    <script src="js/sendHeartbeat.js"></script>
    <script src="js/liveClock.js"></script>
    <script src="js/modalRequestWindow.js"></script>
    <script src="js/modalProgressTracker.js"></script>
    <script src="js/deletePendingRequest.js"></script>
</body>
</html>
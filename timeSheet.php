<?php
    require_once 'dbConfig.php';
    require_once 'sessionChecker.php';
    include 'fetchTimeSheetData.php';

    header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
    header("Cache-Control: post-check=0, pre-check=0", false);
    header("Pragma: no-cache");

    if (!isset($_SESSION['username'])){
        header("Location: loginUser.php");
        exit();
    }

    $currentUser = $_SESSION['username'];
    $currentId = $_SESSION['user_id'];
    $currentPage = basename($_SERVER['PHP_SELF']);

    $confirmationMessage = "";
    if (isset($_SESSION['timeSheet_msg'])) {
        $confirmationMessage = $_SESSION['timeSheet_msg'];
        unset($_SESSION['timeSheet_msg']); 
    }

    $sheetPath = $_SESSION['time_sheet_path'] ?? '';
    $dataRows = getTimeSheetRows($sheetPath);
    $tableRowsHtml = "";

    if (!empty($dataRows)) {
        foreach ($dataRows as $row) {
            if (!empty(array_filter($row))) {
                $tableRowsHtml .= '
                <div class="table-cell-container">
                    <div class="table-cell">
                        <span class="table-data">' . htmlspecialchars($row[0]) . '</span>
                    </div>
                    <div class="table-cell">
                        <span class="table-data">' . htmlspecialchars($row[1]) . '</span>
                    </div>
                    <div class="table-cell">
                        <span class="table-data">' . htmlspecialchars($row[2]) . '</span>
                    </div>
                    <div class="table-cell">
                        <span class="table-data">' . htmlspecialchars($row[3]) . '</span>
                    </div>
                </div>';
            }
        }
    } else {
        $tableRowsHtml = '<div class="no-data-msg" 
        style="padding: 20px; text-align: center; color: #666;"
        >No entries found yet.</div>';
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/internDashboardPublic.css">
    <link rel="stylesheet" href="css/timeSheet.css">
    <title>Time Sheet</title>
</head>
<body>
    <header>
        <div class="header-left">
            <img src="assets/company_logo.png" alt="company_logo" class="company-logo">
            <span class="dashboard-title"> Intern Time Sheet </span>
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
            <a href="internDashboard.php" class="nav-item
            <?php echo ($currentPage === 'internDashboard.php') ? 'active' : ''; ?>"
            data-text="Dashboard">Dashboard
            </a>
            <a href="accInfo.php" class="nav-item
            <?php echo ($currentPage === 'accInfo.php') ? 'active' : ''; ?>"
            data-text="Account Info">Account Info
            </a>
            <a href="javascript:void(0)" class="nav-item
            <?php echo ($currentPage === 'timeSheet.php') ? 'active' : ''; ?>"
            data-text="Time Sheet">Time Sheet
            </a>
            <a href="submitRequest.php" class="nav-item
            <?php echo ($currentPage === 'submitRequest.php') ? 'active' : ''; ?>"
            data-text="Submit Request">Submit Request
            </a>
        </div>
    </nav>

    <div class="time-sheet-input-container">
        <div class="form-title-container form-title-time-sheet-input">
            <h2 class="form-title">Time Sheet Input</h2>
        </div>
        <form action="timeSheetAuth.php" method="POST" autocomplete="off">
            <div class="row">
                <div class="form-group">
                    <label for="date" class="form-label">Date:</label>
                    <input type="date" id="date" name="date" class="general-input">
                </div>
                <div class="form-group">
                    <label for="clockIn" class="form-label">Clock In:</label>
                    <input type="time" id="clock-in" name="clock-in" class="general-input">
                </div>
                <div class="form-group">
                    <label for="clockOut" class="form-label">Clock Out:</label>
                    <input type="time" id="clock-out" name="clock-out" class="general-input">
                </div>
            </div>
            <div class="row">
                <button type="submit" class="btn-submit">Add Entry</button>
                <?php echo $confirmationMessage ?>
            </div>
        </form>
        </div>
    </div>

    <div class="time-sheet-display-container">
        <div class="form-title-container">
            <h2 class="form-title">Time Sheet Summary</h2>
        </div>
        <div class="time-sheet-table">
            <div class="table-headers">
                <div class="table-header-container">
                    <span class="table-header">Date</span>
                </div>
                <div class="table-header-container">
                    <span class="table-header">Clock In</span>
                </div>
                <div class="table-header-container">
                    <span class="table-header">Clock Out</span>
                </div>
                <div class="table-header-container">
                    <span class="table-header">Total Hours</span>
                </div>
            </div>

            <?php echo $tableRowsHtml; ?>
        </div>
    </div>

    <script src="js/dropDownMenu.js"></script>
    <script src="js/backBtnKiller.js"></script>
    <script src="js/sendHeartbeat.js"></script>
</body>
</html>
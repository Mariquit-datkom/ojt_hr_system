<?php
    require_once 'dbConfig.php'; // db config
    require_once 'sessionChecker.php'; // session heartbeat checker
    include 'fetchTimeSheetData.php'; // fetches existing time sheet data
    include 'x-head.php'; // icons

    // Cache clear to prevent unauthorized access after log out
    header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
    header("Cache-Control: post-check=0, pre-check=0", false);
    header("Pragma: no-cache");

    // Ensures current user reaches this page through log in
    if (!isset($_SESSION['username'])){
        header("Location: loginUser.php");
        exit();
    }

    // Variable Declarations
    $currentUser = $_SESSION['username'];
    $currentId = $_SESSION['user_id'];
    $currentPage = basename($_SERVER['PHP_SELF']);

    $confirmationMessage = "";
    if (isset($_SESSION['timeSheet_msg'])) {
        $confirmationMessage = $_SESSION['timeSheet_msg'];
        unset($_SESSION['timeSheet_msg']); 
    }

    if (!isset($_SESSION['time_sheet_path'])) {
        $stmt = $pdo->prepare("SELECT time_sheet FROM intern_list WHERE user_id = ?");
        $stmt->execute([$currentId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($row && !empty($row['time_sheet'])) {
            $_SESSION['time_sheet_path'] = $row['time_sheet'];
        }
    }

    // Generate Time Sheet Rows
    $sheetPath = $_SESSION['time_sheet_path'] ?? '';
    $dataRows = getTimeSheetRows($sheetPath);
    $tableRowsHtml = "";

    if (!empty($dataRows)) {
        $dataRows = array_reverse($dataRows);
        
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
                        <span class="table-data">' . htmlspecialchars($row[4]) . '</span>
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
    <link rel="stylesheet" href="css/public.css?v=1.2">
    <link rel="stylesheet" href="css/timeSheet.css?v=1.2">
    <title>Time Sheet</title>
</head>
<body>
    <!-- Generate Page Header -->
    <?php include 'internHeaderAndNav.php'; ?>
    
    <!-- Time Sheet Input Parent Container -->
    <div class="time-sheet-input-container">
        <div class="form-title-container form-title-time-sheet-input">
            <h2 class="form-title">Time Sheet Input</h2>
        </div>
        <form action="timeSheetAuth.php" method="POST" autocomplete="off">
            <div class="row">
                <div class="form-group"> <!-- Date Input -->
                    <label for="date" class="form-label">Date:</label>
                    <input type="date" id="date" name="date" class="general-input">
                </div>
                <div class="form-group"> <!-- Clock In Input -->
                    <label for="clockIn" class="form-label">Clock In:</label>
                    <input type="time" id="clock-in" name="clock-in" class="general-input">
                </div>
                <div class="form-group"> <!-- Clock Out Input -->
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
    <!-- Time Sheet Display Container --> 
    <div class="time-sheet-display-container">
        <div class="form-title-container">
            <h2 class="form-title">Time Sheet Summary</h2>
        </div>
        <div class="time-sheet-table">
            <div class="table-headers">  <!-- Table Headers -->
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
            <!-- Time Entry Generation --> 
            <?php echo $tableRowsHtml; ?>
        </div>
    </div>

    <!-- Script -->
    <script src="js/dropDownMenu.js"></script>
    <script src="js/backBtnKiller.js"></script>
    <script src="js/sendHeartbeat.js"></script>
</body>
</html>
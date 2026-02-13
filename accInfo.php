<?php
    require_once 'dbConfig.php'; //Database connection
    require_once 'sessionChecker.php'; //session heartbeat checker 
    include 'x-head.php'; //icons
    
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
    $currentId = $_SESSION['user_id'];

    $query = "SELECT * FROM intern_list WHERE user_id = :user_id";
    $stmt = $pdo->prepare($query);
    $stmt->execute(['user_id' => $currentId]);
    $internData = $stmt->fetch();

    if (!$internData) {
        $internData = [
            'intern_last_name' => '',
            'intern_first_name' => '',
            'intern_middle_initial' => '',
            'date_of_employment' => '',
            'intern_course' => '',
            'intern_dept' => '',
            'total_hours_needed' => '',
            'school' => ''
        ];
    }

    $currentPage = basename($_SERVER['PHP_SELF']);

    //Form submit confirmation message
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
    <link rel="stylesheet" href="css/public.css?v=1.2">
    <link rel="stylesheet" href="css/accInfo.css?v=1.2">
    <title>Account Info</title>
</head>
<body>
    <!-- Generate Page Header -->
    <?php include 'internHeaderAndNav.php'; ?>

    <!-- Account info parent container -->
    <div class="acc-info-container">
        <div class="form-title-container">
            <h2 class="form-title">Profile Summary</h2>
            <?php echo $confirmationMessage ?>
        </div>
        <!-- Main form for account info saving or update -->
        <form action="accInfoAuth.php" method="POST" autocomplete="off">
            <div class="row">
                <div class="form-group"><!-- Last Name -->                    
                    <label for="last-name" class="form-label">Last Name: </label>
                    <input type="text" name="last-name" id="last-name" class="general-input" placeholder="Enter last name" required="required"
                    value="<?php echo htmlspecialchars($internData['intern_last_name']); ?>">                    
                </div>
                <div class="form-group"><!-- First Name -->
                    <label for="first-name" class="form-label">First Name: </label>
                    <input type="text" name="first-name" id="first-name" class="general-input" placeholder="Enter first name" required="required"
                    value="<?php echo htmlspecialchars($internData['intern_first_name']); ?>">
                </div>
                <div class="form-group"><!-- Middle Initial -->
                    <label for="middle-initial" class="form-label">Middle Initial: </label>
                    <input type="text" name="middle-initial" id="middle-initial" class="general-input middle-initial" placeholder="Enter middle initial" required="required"
                    value="<?php echo htmlspecialchars($internData['intern_middle_initial']); ?>">
                </div>
            </div>
            <div class="row">
                <div class="form-group"><!-- Employment Date -->
                    <label for="employment-date" class="form-label">Employment Date: </label>
                    <input type="date" name="employment-date" id="employment-date" class="general-input employment-date" required="required"
                    value="<?php echo htmlspecialchars($internData['date_of_employment']); ?>">
                </div>
                <div class="form-group"><!-- Course / Program -->
                    <label for="course" class="form-label">Course: </label>
                    <input type="text" name="course" id="course" class="general-input" placeholder="Enter course (e.g. BS Computer Science)" required="required"
                    value="<?php echo htmlspecialchars($internData['intern_course']); ?>">
                </div>
                <div class="form-group"><!-- Division / Section Assigned To -->
                    <label for="division-or-section" class="form-label">Division / Section: </label>
                    <input type="text" name="division-or-section" id="division-or-section" class="general-input" placeholder="Enter division or section" required="required"
                    value="<?php echo htmlspecialchars($internData['intern_dept']); ?>">
                </div>
            </div>
            <div class="row">
                <div class="form-group"><!-- Total Hours Required By The School -->
                    <label for="total-hours-needed" class="form-label">Total Hours Needed: </label>
                    <input type="text" name="total-hours-needed" id="total-hours-needed" class="general-input hours-input" placeholder="Enter total hours needed" required="required"
                    value="<?php echo htmlspecialchars($internData['total_hours_needed']); ?>">
                </div>
                <div class="form-group"><!-- School / University -->
                    <label for="school" class="form-label">School: </label>
                    <input type="text" name="school" id="school" class="general-input school-input" placeholder="Enter school" required="required"
                    value="<?php echo htmlspecialchars($internData['school']); ?>">
                </div>
            </div>
            <div class="row">
                <div class="form-group"><!-- HRD System Account Username -->
                    <label for="account-username" class="form-label">Account Username: </label>
                    <input type="text" name="account-username" id="account-username" class="general-input"
                    value="<?php echo htmlspecialchars($currentUser); ?>">
                </div>
                <div class="form-group"><!-- Current Password -->
                    <label for="current-password" class="form-label">Current Password: </label>
                    <input type="text" name="current-password" id="current-password" class="general-input" placeholder="Enter current password">
                </div>
                <div class="form-group"><!-- New Password -->
                    <label for="new-password" class="form-label">New Password: </label>
                    <input type="text" name="new-password" id="new-password" class="general-input" placeholder="Enter new password">
                </div>
            </div>
            <div class="btn-submit-container">
                <input type="submit" value="Save Account Information" class="btn-submit">
            </div>
        </form>
    </div>

    <!-- Scripts -->
    <script src="js/dropDownMenu.js"></script>
    <script src="js/backBtnKiller.js"></script>
    <script src="js/sendHeartbeat.js"></script>
</body>
</html>
<!-- ---------------------------------------------------
 --
 -- Ojt Referral Form
 --
 -- This page presents a form for ojt referrals from company
 -- employees. References can send initial info of referees
 -- like name, course, school, total required hours, and cv
 -- for initial hr inspection.
 --
---------------------------------------------------- -->

<?php
    require_once 'dbConfig.php'; //db connection
    //require_once 'sessionChecker.php'; //session heartbeat checker
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/ojtReferral.css">
    <title>Ojt Referral Form</title>
</head>
<body>

    <div class="form-container">
        <div class="form-title-container">
            <h2 class="form-title">OJT Referral Form</h2>
        </div>
        <form action="ojtReferralAuth.php" method="post" enctype="multipart/form-data" autocomplete="off">
            <div class="row">
                <div class="form-group">
                    <label for="reference" class="form-label">Reference Full Name:</label>
                    <input type="text" name="reference-name" id="reference-name" class="general-input" placeholder="Enter full name of one who gave referral" required>
                </div>
                <div class="form-group">
                    <label for="employee_no" class="form-label">Employee Number:</label>
                    <input type="text" name="employee-no" id="employee-no" class="general-input" placeholder="Enter employee number" required>
                </div>
                <div class="form-group">
                    <label for="department_or_section" class="form-label">Department or Section:</label>
                    <input type="text" name="dept-or-section" id="dept-or-section" class="general-input" placeholder="Enter department / section" required>
                </div>
            </div>
            <div class="row">
                <div class="form-group">
                    <label for="ojt_name" class="form-label">OJT Name:</label>
                    <input type="text" name="ojt-name" id="ojt-name" class="general-input" placeholder="Enter full name of referred trainee" required>
                </div>
                <div class="form-group">
                    <label for="ojt_course" class="form-label">Course / Program:</label>
                    <input type="text" name="ojt-course" id="ojt-course" class="general-input" placeholder="Enter trainee course / program" required>
                </div>
                <div class="form-group">
                    <label for="ojt_total_hours_needed" class="form-label">Total Hours Needed:</label>
                    <input type="text" name="ojt-total-hours-needed" id="ojt-total-hours-needed" class="general-input" placeholder="Enter total required hours" required>
                </div>
            </div>
            <div class="row">
                <div class="form-group">
                    <label for="ojt_school" class="form-label">School (With Address):</label>
                    <input type="text" name="ojt-school" id="ojt-school" class="general-input" placeholder="Enter school with address" required>
                </div>
                <div class="form-group">
                    <label for="ojt_cv" class="form-label">Curriculum Vitae - CV (PDF):</label>
                    <input type="file" id="ojt-cv" name="ojt-cv" class="general-input" accept=".pdf" required>
                </div>
            </div>
            <div class="btn-submit-container">
                <input type="submit" value="Submit OJT Referral " class="btn-submit">
            </div>
        </form>
    </div>

    <!-- Scripts -->
    <!--<script src="js/sendHeartbeat.jss"></script>-->
</body>
</html>
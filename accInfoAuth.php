<?php

    require_once 'dbConfig.php';
    session_start();

    if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
        $user_id = $_SESSION['user_id'];
        $lastName = $_POST['last-name'];
        $firstName = $_POST['first-name'];
        $middleInitial = $_POST['middle-initial'];
        $employmentDate = $_POST['employment-date'];
        $course = $_POST['course'];
        $divisionOrSection = $_POST['division-or-section'];
        $totalHoursNeeded = $_POST['total-hours-needed'];
        $accumulatedHours = $_POST['accumulated-hours'];
        $school = $_POST['school'];

        $sql = "INSERT INTO intern_list (user_id, intern_last_name, intern_first_name, 
        intern_middle_initial, date_of_employment, intern_course, intern_dept, 
        total_hours_needed, accumulated_hours, school) 
        VALUES (:user_id, :last_name, :first_name, :middle_initial, :employment_date, 
        :course, :division_or_section, :total_hours_needed, :accumulated_hours, :school)
        ON DUPLICATE KEY UPDATE user_id = :user_id, intern_last_name = :last_name, 
        intern_first_name = :first_name, intern_middle_initial = :middle_initial,
        date_of_employment = :employment_date, intern_course = :course,
        intern_dept = :division_or_section, total_hours_needed = :total_hours_needed,
        accumulated_hours = :accumulated_hours, school = :school";

        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':last_name', $lastName);
        $stmt->bindParam(':first_name', $firstName);
        $stmt->bindParam(':middle_initial', $middleInitial);
        $stmt->bindParam(':employment_date', $employmentDate);
        $stmt->bindParam(':course', $course);
        $stmt->bindParam(':division_or_section', $divisionOrSection);
        $stmt->bindParam(':total_hours_needed', $totalHoursNeeded);
        $stmt->bindParam(':accumulated_hours', $accumulatedHours);
        $stmt->bindParam(':school', $school);

        if ($stmt->execute()) {
            $_SESSION['accinfo_msg'] = "<p style='color: green;'>Account information saved successfully!</p>";
        } else {
            $_SESSION['accinfo_msg'] = "<p style='color: red;'>Error saving account information.</p>";
        }

        header("Location: accInfo.php");
        exit();
    }
?>
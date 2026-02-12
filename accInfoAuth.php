<?php

    require_once 'dbConfig.php'; //db connection
    session_start(); //Session fetch

    //Form submit authentication for database saving
    if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'POST') {

        $user_id = $_SESSION['user_id'];
        $internId = $_SESSION['intern_id'] ?? null;
        $lastName = $_POST['last-name'];
        $firstName = $_POST['first-name'];
        $middleInitial = $_POST['middle-initial'];
        $employmentDate = $_POST['employment-date'];
        $course = $_POST['course'];
        $divisionOrSection = $_POST['division-or-section'];
        $totalHoursNeeded = $_POST['total-hours-needed'];
        $school = $_POST['school'];

        $sql = "INSERT INTO intern_list (intern_id, user_id, intern_last_name, intern_first_name, intern_middle_initial,
        date_of_employment, intern_course, intern_dept, total_hours_needed, school)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ON DUPLICATE KEY UPDATE
            user_id = VALUES(user_id);
            intern_last_name = VALUES(intern_last_name);
            intern_last_name = VALUES(intern_last_name);
            intern_middle_initial = VALUES(intern_middle_initial);
            date_of_employment = VALUES(date_of_employment);
            intern_course = VALUES(intern_course);
            intern_dept = VALUES(intern_dept);
            total_hours_needed = VALUES(total_hours_needed);
            school = VALUES(school);";

        $stmt = $pdo->prepare($sql);

        //If sql is successfuly executed, compute for total hours needed, accumulated, and remaining hours
        if ($stmt->execute([$internId, $user_id, $lastName, $firstName, $middleInitial, $employmentDate, $course, $divisionOrSection, $totalHoursNeeded, $school])) {
            $_SESSION['total_hours_needed'] = $totalHoursNeeded;

            $accumulated_hours = $_SESSION['accumulated_hours'] ?? 0;
            $remainingHours = $totalHoursNeeded - $accumulated_hours;
            $_SESSION['remaining_hours'] = $remainingHours;

            $sql = "UPDATE intern_list SET remaining_hours = :remaining_hours WHERE user_id = :user_id";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':remaining_hours', $remainingHours);
            $stmt->bindParam(':user_id', $user_id);
            $stmt->execute();

            $_SESSION['accinfo_msg'] = "<p style='color: green;'>Account information saved successfully!</p>";
        } else {
            $_SESSION['accinfo_msg'] = "<p style='color: red;'>Error saving account information.</p>";
        }

        header("Location: accInfo.php");
        exit();
    }
?>
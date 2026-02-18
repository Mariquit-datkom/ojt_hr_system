<?php
require_once 'dbConfig.php'; //db connection
session_start(); //session fetch

if(isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $referenceName = $_POST['reference-name'];
    $employeeNumber = $_POST['employee-no'];
    $department = $_POST['dept-or-section'];
    $internName = $_POST['ojt-name'];
    $internCourse = $_POST['ojt-course'];
    $ojtHoursNeeded = $_POST['ojt-total-hours-needed'];
    $ojtSchool = $_POST['ojt-school'];

    try {
        $pdo->beginTransaction();

        $sql = "INSERT INTO ojt_referral_list (referred_by, employee_no, department_or_section, 
                ojt_full_name, ojt_course, ojt_total_hours_needed, ojt_school)
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$referenceName, $employeeNumber, $department, $internName, $internCourse, $ojtHoursNeeded, $ojtSchool]);

        $ojtReferralId = $pdo->lastInsertId();
        $stmt = $pdo->prepare("SELECT ojt_referral_display_id FROM ojt_referral_list WHERE ojt_referral_id = ?");
        $stmt->execute([$ojtReferralId]);
        $result = $stmt->fetch();
        $ojtReferralDisplayId = $result['ojt_referral_display_id'];

        $uploadDir = 'uploads/referrals/' . $ojtReferralDisplayId . '/';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

        $isAllowed = true;
        $fileName = basename($_FILES['ojt-cv']['name']);
        if ($_FILES['ojt-cv']['error'] !== UPLOAD_ERR_OK) echo "<script> alert('File Upload Error'); </script>";
        if ($_FILES['ojt-cv']['size'] > 10000000) {
            echo "<script> alert('File is too large. File can't exceed 10MB.') </script>";
            $isAllowed = false;
        }
        if ($isAllowed && move_uploaded_file($_FILES['ojt-cv']['tmp_name'], $uploadDir . $fileName)) echo "<script> alert('File uploaded successfuly'); </script>";
        else echo "<script> alert('There's an error with uploading your file, please try again later.') </script>";

        $pdo->prepare("UPDATE ojt_referral_list SET ojt_cv = ? WHERE ojt_referral_display_id = ?")->execute([$fileName, $ojtReferralDisplayId]);
        $pdo->commit();

        echo "<script> alert('Referral form submitted successfuly') </script>";

    } catch (Exception $e) {
        $pdo->rollBack();
        echo "<script>
                alert('Error processing request: " . addslashes($e->getMessage()) . "');
                window.history.back();
            </script>";
        exit();
    }
}
?>
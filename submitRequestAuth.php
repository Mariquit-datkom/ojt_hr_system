<?php
require_once 'dbConfig.php'; // db connection
require_once 'sessionChecker.php'; // session heartbeat checker

// Form Submission Authentication
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    date_default_timezone_set('Asia/Manila');

    $subject = $_POST['request-subject'];
    $date = $_POST['date'];
    $time = date('h:i:s a');
    $mainRequest = $_POST['main-request'];
    $submittedBy = $_SESSION['intern_display_id'];
    $status = "Pending";

    try {
        $pdo->beginTransaction(); //Doesn't save any changes permanently yet

        // Inserts form data into database table
        $sql = "INSERT INTO request_list (request_date, request_time, submitted_by, request_subject, request_main, request_status) 
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$date, $time, $submittedBy, $subject, $mainRequest, $status]);

        // Fetches formatted request number for dashboard display
        $requestNo = $pdo->lastInsertId();
        $sql = "SELECT request_no_display FROM request_list WHERE request_no = :request_no";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['request_no' => $requestNo]);
        $result = $stmt->fetch();
        $requestNoDisplay = $result['request_no_display'];

        // Processes uploaded attachments to save into uploads folder
        $fileNames = [];
        if (!empty($_FILES['attachment']['name'][0])) {    
            $uploadDir = 'uploads/' . $requestNoDisplay . '/';
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

            foreach ($_FILES['attachment']['name'] as $key => $name) {
                // Check if there was an actual upload error
                if ($_FILES['attachment']['error'][$key] !== UPLOAD_ERR_OK) {
                    continue; 
                }

                $tmpName = $_FILES['attachment']['tmp_name'][$key];
                $cleanName = preg_replace("/[^a-zA-Z0-9\._-]/", "_", basename($name)); 
                $targetPath = $uploadDir . $cleanName;

                if (move_uploaded_file($tmpName, $targetPath)) {
                    $fileNames[] = $cleanName;
                }
            }
        }
        // Adds request entry folder path to database
        if (!empty($fileNames)) {
            $attachmentString = implode(',', $fileNames);
            $updateSql = "UPDATE request_list SET request_attachment = ? WHERE request_no = ?";
            $pdo->prepare($updateSql)->execute([$attachmentString, $requestNo]);
        }

        $pdo->commit();
        $_SESSION['request_form_msg'] = "<p style='color: green;'>Request submitted successfully!</p>";
        header("Location: submitRequest.php");

    } catch (Exception $e) {
        $pdo->rollBack();
        die("Error processing request: " . $e->getMessage());
    }
}
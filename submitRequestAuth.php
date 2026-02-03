<?php
require_once 'dbConfig.php';
require_once 'sessionChecker.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $subject = $_POST['request-subject'];
    $date = $_POST['date'];
    $mainRequest = $_POST['main-request'];
    $submittedBy = $_SESSION['intern_display_id'];
    $status = "Pending";

    try {
        $pdo->beginTransaction();

        $sql = "INSERT INTO request_list (request_date, submitted_by, request_subject, request_main, request_status) 
                VALUES (?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$date, $submittedBy, $subject, $mainRequest, $status]);

        $requestId = $pdo->lastInsertId();

        $fileNames = [];
        if (!empty($_FILES['attachment']['name'][0])) {            
            $uploadDir = 'uploads/' . $requestId . '/';
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

            foreach ($_FILES['attachment']['name'] as $key => $name) {
                $tmpName = $_FILES['attachment']['tmp_name'][$key];
                $cleanName = basename($name); 
                $targetPath = $uploadDir . $cleanName;

                if (move_uploaded_file($tmpName, $targetPath)) {
                    $fileNames[] = $cleanName;
                }
            }
        }

        if (!empty($fileNames)) {
            $attachmentString = implode(',', $fileNames);
            $updateSql = "UPDATE request_list SET request_attachment = ? WHERE request_no = ?";
            $pdo->prepare($updateSql)->execute([$attachmentString, $requestId]);
        }

        $pdo->commit();
        $_SESSION['request_form_msg'] = "<p style='color: green;'>Request submitted successfully!</p>";
        header("Location: submitRequest.php");

    } catch (Exception $e) {
        $pdo->rollBack();
        die("Error processing request: " . $e->getMessage());
    }
}
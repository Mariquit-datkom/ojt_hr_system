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
    $requestNoFromEdit = $_POST['request_no'] ?? null;
    $submittedBy = $_SESSION['intern_display_id'];
    $status = "Pending";

    try {
        $pdo->beginTransaction(); //Doesn't save any changes permanently yet

        // Inserts form data into database table
        $sql = "INSERT INTO request_list (request_no, request_date, request_time, submitted_by, request_subject, request_main, request_status) 
                VALUES (?, ?, ?, ?, ?, ?, ?)
                ON DUPLICATE KEY UPDATE
                    request_subject = VALUES(request_subject),
                    request_date = VALUES(request_date),
                    request_time = VALUES(request_time),
                    request_main = VALUES(request_main)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$requestNoFromEdit, $date, $time, $submittedBy, $subject, $mainRequest, $status]);

        // Fetches formatted request number for dashboard display
        $requestNo = $requestNoFromEdit ?: $pdo->lastInsertId();
        $sql = "SELECT request_no_display FROM request_list WHERE request_no = :request_no";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['request_no' => $requestNo]);
        $result = $stmt->fetch();
        $requestNoDisplay = $result['request_no_display'];

        // Fetch current uploaded attachments if available
        $stmt = $pdo->prepare("SELECT request_attachment FROM request_list WHERE request_no = ?");
        $stmt->execute([$requestNo]);
        $row = $stmt->fetch();
        $currentFiles = array_filter(explode(',', $row['request_attachment'] ?? ""));

        if (!empty($_POST['remove_files'])) {
            foreach ($_POST['remove_files'] as $fileToRemove) {
                $filePath = 'uploads/' . $requestNoDisplay . '/' . $fileToRemove;
                if (file_exists($filePath)) {
                    unlink($filePath); // Physically delete from folder
                }
                // Remove from our active list
                $currentFiles = array_diff($currentFiles, [$fileToRemove]);
            }
        }

        $existingFileCount = count($currentFiles);
        $newFileNames = [];

        // Processes uploaded attachments to save into uploads folder
        if (!empty($_FILES['attachment']['name'][0])) {  
            $newFileCount = count($_FILES['attachment']['name']);
            
            if (($existingFileCount + $newFileCount) > 5) {
                $pdo->rollBack();
                $_SESSION['request_form_msg'] = "<p style='color: red;'>Error: Total files cannot exceed 5. (Currently: $existingFileCount + $newFileCount new).</p>";
                header("Location: editRequest.php?request_no=" . $requestNoFromEdit);
                exit();
            }

            $uploadDir = 'uploads/requests/' . $requestNoDisplay . '/';
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

            foreach ($_FILES['attachment']['name'] as $key => $name) {
                if ($_FILES['attachment']['error'][$key] === UPLOAD_ERR_OK) {
                    $cleanName = preg_replace("/[^a-zA-Z0-9\._-]/", "_", basename($name)); 
                    if (move_uploaded_file($_FILES['attachment']['tmp_name'][$key], $uploadDir . $cleanName)) {
                        $newFileNames[] = $cleanName;
                    }
                }
            }
        }

        // Adds request entry folder path to database
        if (!empty($_POST['remove_files']) || !empty($newFileNames)) {
            $finalFileList = array_merge($currentFiles, $newFileNames);
            $attachmentString = implode(',', array_filter($finalFileList));
            $pdo->prepare("UPDATE request_list SET request_attachment = ? WHERE request_no = ?")
                ->execute([$attachmentString, $requestNo]);
        }

        $pdo->commit();
        $_SESSION['request_form_msg'] = "<p style='color: green;'>Request submitted successfully!</p>";
        
        if (!$requestNoDisplay) header("Location: submitRequest.php");
        else {
            echo "<script>
                    alert('Request updated successfully!');
                    window.location.href = 'internDashboard.php';
                  </script>";
        }
        exit();

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
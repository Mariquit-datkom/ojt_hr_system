<?php

    require_once 'dbConfig.php';
    require_once 'libs/SimpleXLSX.php';
    require_once 'libs/SimpleXLSXGen.php';

    use Shuchkin\SimpleXLSX;
    use Shuchkin\SimpleXLSXGen;

    include 'generateTimeSheet.php';
    session_start();

    if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
        $user_id = $_SESSION['user_id'];
        $intern_display_id = $_SESSION['intern_display_id'];
        $date = date("Y-m-d", strtotime($_POST['date']));

        $rawIn = $_POST['clock-in'];
        $rawOut = $_POST['clock-out'];

        $clockIn = !empty($_POST['clock-in']) ? date("g:i A", strtotime($rawIn)) : '';
        $clockOut = !empty($_POST['clock-out']) ? date("g:i A", strtotime($rawOut)) : '';

        $totalHours = "";
        if (!empty($rawIn) && !empty($rawOut)) {
            $time1 = strtotime($rawIn);
            $time2 = strtotime($rawOut);
            
            $diff = $time2 - $time1;

            if ($diff < 0) {
                $diff += 86400; 
            }

            $hours = floor($diff / 3600);
            $minutes = floor(($diff % 3600) / 60);

            $totalHours = ($minutes > 0) ? "{$hours} hours {$minutes} minutes" : "{$hours} hours";
        }

        $fileToRead = file_exists($_SESSION['time_sheet_path']) ? $_SESSION['time_sheet_path'] : $_SESSION['time_sheet_template'];

        if ($xlsx = SimpleXLSX::parse($fileToRead)) {
            $data = $xlsx->rows();

            $headers = array_slice($data, 0, 3);
            $entries = array_slice($data, 3);

            $entries[] = [$date, $clockIn, $clockOut, $totalHours];

            usort($entries, function($a, $b) {
                return strtotime($a[0]) - strtotime($b[0]);
            });

            $finalData = array_merge($headers, $entries);

            $newXLSX = SimpleXLSXGen::fromArray($finalData)->saveAs($_SESSION['time_sheet_path']);
        }  

        $sql = "UPDATE intern_list SET time_sheet = :time_sheet WHERE user_id = :user_id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':time_sheet', $_SESSION['time_sheet_path']);
        $stmt->bindParam(':user_id', $user_id);
        
        if ($stmt->execute()) {
            $_SESSION['timeSheet_msg'] = "<p style='color: green;'>Entry collected successfully!</p>";
        } else {
            $_SESSION['timeSheet_msg'] = "<p style='color: red;'>Error saving time sheet entry.</p>";
        }

        header("Location: timeSheet.php");
        exit();
    }
?>
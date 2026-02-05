<?php

    require_once 'dbConfig.php';
    require_once 'libs/SimpleXLSX.php';
    require_once 'libs/SimpleXLSXGen.php';

    use Shuchkin\SimpleXLSX;
    use Shuchkin\SimpleXLSXGen;

    include 'generateTimeSheet.php';

    if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
        $user_id = $_SESSION['user_id'];
        $intern_display_id = $_SESSION['intern_display_id'];
        $date = $_POST['date'];

        $rawIn = $_POST['clock-in'];
        $rawOut = $_POST['clock-out'];

        $clockIn = !empty($_POST['clock-in']) ? date("g:i A", strtotime($rawIn)) : '';
        $clockOut = !empty($_POST['clock-out']) ? date("g:i A", strtotime($rawOut)) : '';

        $totalHours = "";
        $totalHoursDecimal = 0;
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
            $totalHoursDecimal = $diff / 3600;
        }

        $fileToRead = file_exists($_SESSION['time_sheet_path']) ? $_SESSION['time_sheet_path'] : $_SESSION['time_sheet_template'];

        if ($xlsx = SimpleXLSX::parse($fileToRead)) {
            $data = $xlsx->rows();

            $data[] = [$date, $clockIn, $clockOut, $totalHoursDecimal];

            $headers = array_shift($data); 
            usort($data, function($a, $b) {
                return strtotime($a[0]) <=> strtotime($b[0]);
            });
            array_unshift($data, $headers);

            $newTotalAccumulated = 0;
            foreach ($data as $index => $row) {
                if ($index === 0) continue;                 
                if (isset($row[3]) && is_numeric($row[3])) {
                    $newTotalAccumulated += (float)$row[3];
                }
            }

            $newXLSX = SimpleXLSXGen::fromArray($data)->saveAs($_SESSION['time_sheet_path']);
        }  

        $total_hours_needed = $_SESSION['total_hours_needed'];
        $accumulated_hours = $newTotalAccumulated;
        $remaining_hours = $total_hours_needed - $accumulated_hours;

        $_SESSION['accumulated_hours'] = $accumulated_hours;
        $_SESSION['remaining_hours'] = $remaining_hours;

        $sql = "UPDATE intern_list SET time_sheet = :time_sheet, accumulated_hours = :accumulated_hours, remaining_hours = :remaining_hours
        WHERE user_id = :user_id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':time_sheet', $_SESSION['time_sheet_path']);
        $stmt->bindParam(':accumulated_hours', $accumulated_hours);
        $stmt->bindParam(':remaining_hours', $remaining_hours);
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
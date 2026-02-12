<?php
require 'vendor/autoload.php'; 
require_once 'dbConfig.php';

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

include 'generateTimeSheet.php';

if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
    session_start(); // Ensure session is active
    $user_id = $_SESSION['user_id'];
    $date = $_POST['date'];
    $rawIn = $_POST['clock-in'];
    $rawOut = $_POST['clock-out'];

    $clockIn = !empty($rawIn) ? date("g:i A", strtotime($rawIn)) : '';
    $clockOut = !empty($rawOut) ? date("g:i A", strtotime($rawOut)) : '';

    $totalHoursDecimal = 0;
    $displayTime = "";
    if (!empty($rawIn) && !empty($rawOut)) {
        $diff = strtotime($rawOut) - strtotime($rawIn);
        if ($diff < 0) $diff += 86400; 
        $totalHoursDecimal = round($diff / 3600, 2);

        $hours = floor($diff / 3600);
        $minutes = ($diff / 60) % 60;
        $displayTime = "{$hours} hours and {$minutes} minutes";
    }

    $fileToRead = file_exists($_SESSION['time_sheet_path']) ? $_SESSION['time_sheet_path'] : $_SESSION['time_sheet_template'];

    try {
        $spreadsheet = IOFactory::load($fileToRead);
        $sheet = $spreadsheet->getActiveSheet();
        
        $allData = $sheet->toArray();
        
        $newRow = [$date, $clockIn, $clockOut, $totalHoursDecimal, $displayTime];

        $headers = array_slice($allData, 0, 3);
        $dataRows = array_slice($allData, 3);
        
        $dataRows[] = $newRow;
        
        $dataRows = array_filter($dataRows, function($row) {
            return !empty($row[0]);
        });

        usort($dataRows, function($a, $b) {
            return strtotime($a[0]) <=> strtotime($b[0]);
        });

        $sheet->removeRow(4, $sheet->getHighestRow()); 

        $sheet->fromArray($dataRows, NULL, 'A4');

        $newTotalAccumulated = 0;
        $allData = $sheet->toArray();
        foreach ($allData as $index => $row) {
            if (isset($row[3]) && is_numeric($row[3])) {
                $newTotalAccumulated += (float)$row[3];
            }
        }

        $lastRow = $sheet->getHighestRow();
        $fullRange = 'A1:D' . $lastRow;

        // Apply Center + Borders to the whole used range
        $sheet->getStyle($fullRange)->applyFromArray([
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
        ]);

        // Apply AutoFit to Columns
        foreach (range('A', 'D') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Save the file
        $writer = new Xlsx($spreadsheet);
        $writer->save($_SESSION['time_sheet_path']);

        // Update Database
        $total_hours_needed = $_SESSION['total_hours_needed'];
        $remaining_hours = $total_hours_needed - $newTotalAccumulated;

        $_SESSION['accumulated_hours'] = $newTotalAccumulated;
        $_SESSION['remaining_hours'] = $remaining_hours;

        $sql = "UPDATE intern_list SET time_sheet = :time_sheet, accumulated_hours = :acc, remaining_hours = :rem WHERE user_id = :user_id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':time_sheet' => $_SESSION['time_sheet_path'],
            ':acc' => $newTotalAccumulated,
            ':rem' => $remaining_hours,
            ':user_id' => $user_id
        ]);

        $_SESSION['timeSheet_msg'] = "<p style='color: green;'>Entry collected successfully!</p>";

    } catch (Exception $e) {
        $_SESSION['timeSheet_msg'] = "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
    }

    header("Location: timeSheet.php");
    exit();
}
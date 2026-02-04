<?php 

    require_once 'dbConfig.php';
    session_start();

    $intern_display_id = $_SESSION['intern_display_id'];
    $templatePath = 'assets/spreadsheets/template/TIME_SHEET_SUMMARY.xlsx';
    $targetDir = 'assets/spreadsheets/' . $intern_display_id . '/';
    $newFileName =  $intern_display_id . '-TIME_SHEET_SUMMARY_.xlsx';
    $destinationPath = $targetDir . $newFileName;

    $_SESSION['time_sheet_template'] = $templatePath;
    $_SESSION['time_sheet_path'] = $destinationPath;

    if (!is_dir($targetDir)) mkdir($targetDir, 0755, true);
    if (!file_exists($destinationPath)) copy($templatePath, $destinationPath);
?>
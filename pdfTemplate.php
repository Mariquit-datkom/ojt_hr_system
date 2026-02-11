<?php
$fullName = htmlspecialchars($intern['intern_last_name'] . ", " . $intern['intern_first_name']);
if (!empty(trim($intern['intern_middle_initial']))) {
    $fullName .= " " . htmlspecialchars($intern['intern_middle_initial']) . ".";
}

$hourProgress = htmlspecialchars($intern['accumulated_hours'] . " / " . $intern['total_hours_needed'] . " hours (" . $intern['remaining_hours'] . " hours remaining)");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/pdfTemplate.css">
</head>
<body>
    <table class="company-header"> 
        <tr>           
            <td style="text-align: center;">
                <img src="assets/company_logo.png" style="vertical-align: middle; height: 60px; width: auto;">
                <span style="vertical-align: middle; font-family: 'futuraboldoblique', serif; font-size: 16px; font-weight: 700;">
                    Hayakawa Electronics (Phils.) Corp.
                </span>
            </td>
        </tr>
    </table>
    <table class="table-content">
        <tr style="background-color: #52a0ff">
            <td style="height:40px; text-align:center">
                <span style="font-size: 20px; font-weight: 800">
                    INTERN PROFILE SHEET
                </span>
            </td>
        </tr>
    </table>
    <table class="table-content">
        <tr>
            <td class="row-header">
                FULL NAME
            </td>
            <td>
                <?php echo $fullName ?>
            </td>
        </tr>
        <tr>
            <td class="row-header">
                INTERN ID
            </td>
            <td>
                <?php echo htmlspecialchars($intern['intern_display_id']); ?>
            </td>
        </tr>
        <tr>
            <td class="row-header">
                COURSE
            </td>
            <td>
                <?php echo htmlspecialchars($intern['intern_course']); ?>
            </td>
        </tr>
        <tr>            
            <td class="row-header">
                ASSIGNED DEPARTMENT
            </td>
            <td>
                <?php echo htmlspecialchars($intern['intern_dept']); ?>
            </td>
        </tr>
        <tr>
            <td class="row-header">
                INTERN PROGRESS
            </td>
            <td>
                <?php echo  $hourProgress?>
            </td>
        </tr>
        <tr>
            <td class="row-header">
                SCHOOL
            </td>
            <td>
                <?php echo htmlspecialchars($intern['school']) ?>
            </td>
        </tr>
    </table>
</body>
</html>
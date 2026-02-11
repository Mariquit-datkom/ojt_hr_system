<?php 
require_once 'dbConfig.php';
require_once __DIR__ . '/vendor/autoload.php';

$mpdf = new \Mpdf\Mpdf();

$defaultConfig = (new Mpdf\Config\ConfigVariables())->getDefaults();
$fontDirs = $defaultConfig['fontDir'];

$defaultFontConfig = (new Mpdf\Config\FontVariables())->getDefaults();
$fontData = $defaultFontConfig['fontdata'];

$mpdf = new \Mpdf\Mpdf([
    'fontDir' => array_merge($fontDirs, [
        __DIR__ . '/assets/fonts/',
    ]),
    'fontdata' => $fontData + [
        'futuraboldoblique' => [
            'R' => 'FuturaStd-BoldOblique.ttf',
        ]
    ],
    'default_font' => 'serif'
]);

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['intern_display_id'])) {
    $internDisplayId = $_POST['intern_display_id'];
    echo $internDisplayId;

    $stmt = $pdo->prepare('SELECT * FROM intern_list WHERE intern_display_id = ?');
    $stmt->execute([$internDisplayId]);
    $intern = $stmt ->fetch(PDO::FETCH_ASSOC);

    if ($intern) {
        $stylesheet = file_get_contents('css/pdfTemplate.css');

        ob_start();
        include 'pdfTemplate.php';
        $pdfTemplate = ob_get_clean();

        $mpdf->WriteHTML($stylesheet, \Mpdf\HTMLParserMode::HEADER_CSS);
        $mpdf->WriteHTML($pdfTemplate, \Mpdf\HTMLParserMode::HTML_BODY);

        $mpdf->Output($intern['intern_display_id'] . '_Intern_Profile_Sheet.pdf', 'I');

    } else echo "Intern not found.";
} else echo "No ID provided.";

?>
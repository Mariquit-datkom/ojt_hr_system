<?php
    require_once 'dbConfig.php'; // db config
    require_once 'sessionChecker.php'; // session heartbeat checker
    require_once 'x-head.php';
    
    // Cache clear on logout
    header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
    header("Pragma: no-cache");

    // Ensures user reached this page through login
    if (!isset($_SESSION['username'])){
        header("Location: loginUser.php");
        exit();
    }

    // Variable declarations
    $currentUser = $_SESSION['username'];
    $currentPage = basename($_SERVER['PHP_SELF']);

    try {
        $stmt = $pdo->prepare("SELECT * FROM intern_list ORDER BY intern_last_name ASC");
        $stmt->execute();
        $interns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        $interns = []; // Initialize as empty array to avoid errors in the HTML
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/public.css">
    <link rel="stylesheet" href="css/internsListPage.css">
    <title>Interns - Admin</title>
</head>
<body>
    <!-- Generate Page Header and Nav Bar -->
     <?php include 'adminHeaderAndNav.php'; ?>

     <!-- Interns List Table -->
      <div class="list-table-container">
        <h2 class="container-title">List of Current Interns</h2>
        <div class="list-table"></div>
            <?php if (empty($interns)): ?>
                <p class="no-interns" style='padding: 20px; text-align: center; color: #666;'>No interns / trainees registered yet.</p>
            <?php else: ?>
                <?php foreach ($interns as $ojt): ?>
                    <!-- Intern Entry -->
                    <div class="intern-item clickable-intern" 
                        onclick="showInternDetails(<?php echo htmlspecialchars(json_encode($ojt)); ?>)">
                        <!-- Intern Summary -->
                        <div class="intern-details">
                            <span><strong>ID:</strong> <?php echo htmlspecialchars($ojt['intern_display_id']); ?></span>
                            <span><strong>Name:</strong> <?php echo htmlspecialchars($ojt['intern_first_name'] . " " . $ojt['intern_last_name']); ?></span>
                            <span><strong>Hours:</strong> <?php echo htmlspecialchars($ojt['accumulated_hours']); ?> / <?php echo htmlspecialchars($ojt['total_hours_needed']); ?></span>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
      </div>

      <div id="intern-modal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 id="modal-name">Intern Details</h2>
                <span class="close-btn" onclick="closeModal()">&times;</span>
            </div>
            <div id="modal-body"></div>
        </div>
    </div>

    <!-- Backend Scripts -->
    <script src="js/dropDownMenu.js"></script>
    <script src="js/backBtnKiller.js"></script>
    <script src="js/sendHeartbeat.js"></script>
    <script src="js/modalInternsList.js"></script>
</body>
</html>
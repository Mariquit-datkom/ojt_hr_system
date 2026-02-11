<?php
    require_once 'dbConfig.php'; // db config
    require_once 'sessionChecker.php'; // session fetch
    require_once 'x-head.php'; // icons

    // Cache clear to prevent unauthorized use of system after log out
    header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
    header("Pragma: no-cache");

    // Ensures user reached this page through log in
    if (!isset($_SESSION['username'])){
        header("Location: loginUser.php");
        exit();
    }        

    // Requests Details
    try {
        $sql = "SELECT r.*, i.intern_first_name, i.intern_last_name, i.intern_middle_initial 
                FROM request_list r
                LEFT JOIN intern_list i ON r.submitted_by = i.intern_display_id
                ORDER BY r.submitted_by ASC, 
                        FIELD(r.request_status, 'Pending', 'Approved', 'Declined') ASC, 
                        r.request_time DESC";
                        
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $requests = $stmt->fetchAll(PDO::FETCH_ASSOC);

    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        $requests = [];
    }

    // Variable Declarations
    $currentUser = $_SESSION['username'];
    $currentPage = basename($_SERVER['PHP_SELF']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/public.css">
    <link rel="stylesheet" href="css/requestsPage.css">
    <title>Requests - Admin</title>
</head>
<body>
    <!-- Generate Page Header and Nav Bar -->
     <?php include 'adminHeaderAndNav.php'; ?>

     <!-- Requests List Table -->
    <div class="requests-table-container">
        <h2 class="container-title">Requests</h2>
        <div class="search-row">
            <div class="sort-entries">
                <label>Show </label>
                <select name="entries-select" id="entries-select">
                    <option value="all">All</option>
                    <option value="10">10</option>
                    <option value="20">20</option>
                    <option value="50">50</option>
                </select>
                <label> entries</label>
            </div>
            <div class="search-bar">
                <input type="text" class="search-field" id="search-field" placeholder="Search..">
                <button type="submit" class="search-btn"><i class="fas fa-search"></i></button>
            </div>
        </div>
        <div class="list-table">
            <?php if (empty($requests)): ?>
                <p class="no-requests" style='padding: 20px; text-align: center; color: #666;'>No requests submitted yet.</p>
            <?php else: ?>
                <?php foreach ($requests as $reqs): ?>
                    <!-- Request Entry -->
                    <?php 
                    $fullName = htmlspecialchars($reqs['intern_last_name'] . ", " . $reqs['intern_first_name']);
                    if (!empty(trim($reqs['intern_middle_initial']))) {
                        $fullName .= " " . htmlspecialchars($reqs['intern_middle_initial']) . ".";
                    }
                ?>
                
                <div class="request-item clickable-request" 
                    data-subject="<?php echo strtolower(htmlspecialchars($reqs['request_subject'])); ?>"
                    data-name="<?php echo strtolower($fullName); ?>"
                    onclick="showRequestDetails(<?php echo htmlspecialchars(json_encode($reqs)); ?>, '<?php echo addslashes($fullName); ?>')">
                    <div class="request-details">
                        <span class="req-subject"> <?php echo htmlspecialchars($reqs['request_subject']); ?> - <?php echo $fullName; ?></span>
                        <span class="req-date"><?php echo date('M d, Y', strtotime($reqs['request_date'])); ?> - <?php echo date('h:i:s a', strtotime($reqs['request_time'])) ?></span>
                    </div>
                    <span class="status-badge <?php echo strtolower($reqs['request_status']); ?>">
                        <?php echo htmlspecialchars($reqs['request_status']); ?>
                    </span>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        <div class="pagination-controls">
            <button id="prevBtn" class="pag-btn"><i class="fas fa-chevron-left"></i> Previous</button>
            <span id="pageInfo">Page 1</span>
            <button id="nextBtn" class="pag-btn">Next <i class="fas fa-chevron-right"></i></button>
        </div>
    </div>

      <!-- Request Pop Up -->
    <div id="requestModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 id="modalSubject">Request Details</h2>
                <span class="close-btn" onclick="closeModal()">&times;</span>
            </div>
            <div class="modal-body">
                <input type="hidden" id="modalRequestNo">
                <div class="modal-row">
                    <p><strong>Date:</strong> <span id="modalDate"></span></p>
                    <p><strong>Submitted by:</strong> <span id="modalSubmittedBy"></span></p>
                    <p><strong>Status:</strong> <span id="modalStatus" class="status-badge"></span></p>
                </div>
                <div class="modal-row">
                    <p><strong>Message:</strong></p>
                    <div id="modalMainRequest" class="modal-text-area"></div>
                </div>
                <div class="modal-row" id="attachmentSection" style="display: none";>
                    <p><strong>Attachment:</strong></p>
                    <div id="modalAttachment"></div>
                </div>
                <div class="modal-footer">
                    <button class="btn-accept" onclick="updateRequestStatus('Approved')">Approve</button>
                    <button class="btn-decline" onclick="updateRequestStatus('Declined')">Decline</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="js/dropDownMenu.js"></script>
    <script src="js/backBtnKiller.js"></script>
    <script src="js/sendHeartbeat.js"></script>
    <script src="js/modalRequestsList.js"></script>
    <script src="js/notifRedirect.js"></script>
</body>
</html>
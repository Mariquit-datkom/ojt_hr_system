<?php
    require_once 'dbConfig.php'; //db connection
    require_once 'sessionChecker.php'; // session heartbeat checker
    require_once 'x-head.php'; // icons

    //Cache remover to prevent showing sensitive data on back button press after log out
    header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
    header("Pragma: no-cache");

    //Verifies if user reached this page through log in
    if (!isset($_SESSION['username'])){
        header("Location: loginUser.php");
        exit();
    }

    //Fetch referrals from db
    $stmt = $pdo->prepare("SELECT * FROM ojt_referral_list ORDER BY FIELD (status, 'Pending', 'Approved', 'Declined'), ojt_referral_id DESC");
    $stmt->execute();
    $referrals = $stmt->fetchAll(PDO::FETCH_ASSOC);

    //Variable Declarations
    $currentUser = $_SESSION['username'];
    $currentPage = basename($_SERVER['PHP_SELF']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/public.css">
    <link rel="stylesheet" href="css/referralListPage.css">
    <title>Ojt Referral List</title>
</head>
<body>
    <?php include 'adminHeaderAndNav.php'; ?>
    
    <div class="main-container">
        <h2 class="container-title">Ojt Referrals</h2>
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
            <?php if (empty($referrals)) : ?>
                <p class="no-referrals" style='padding: 20px; text-align: center; color: #666;'>No referrals submitted yet.</p>
            <?php else : ?>
                <?php foreach ($referrals as $refs) : ?>
                    <div class="referral-item" onclick="showReferralDetails(<?php echo htmlspecialchars(json_encode($refs)); ?>)">
                        <div class="referral-details">
                            <span class="referral-id"><?php echo htmlspecialchars($refs['ojt_referral_display_id']); ?></span>
                            <span class="referral-date"><?php echo date('M d, Y', strtotime($refs['referral_date'])) ?> - <?php echo date('h:i:s a', strtotime($refs['referral_time'])) ?></span>
                        </div>
                        <span class="status-badge <?php echo strtolower($refs['status']); ?>">
                            <?php echo htmlspecialchars($refs['status']); ?>
                        </span>
                    </div>
                <?php endforeach ?>
            <?php endif; ?>
        </div>
        <div class="pagination-controls">
            <button id="prevBtn" class="pag-btn"><i class="fas fa-chevron-left"></i> Previous</button>
            <span id="pageInfo">Page 1</span>
            <button id="nextBtn" class="pag-btn">Next <i class="fas fa-chevron-right"></i></button>
        </div>
    </div>

    <!-- Request Pop Up -->
    <div id="referralModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 id="modalDisplayId"></h2>
                <span class="close-btn" onclick="closeModal()">&times;</span>
            </div>
            <div class="modal-body">
                <input type="hidden" id="modalReferralId">
                <div class="modal-row">
                    <p><strong>Submitted on:</strong> <span id="modalDate"></span></p>
                    <p><strong>Submitted by:</strong> <span id="modalSubmittedBy"></span></p>
                    <p><strong>Employee No:</strong> <span id="modalEmployeeNo"></span></p>
                    <p><strong>Status:</strong> <span id="modalStatus" class="status-badge"></span></p>
                </div>
                <div class="modal-row">
                    <p><strong>Intern Full Name:</strong> <span id="modalInternName"></span></p>
                    <p><strong>Course / Program:</strong> <span id="modalInternCourse"></span></p>
                    <p><strong>Total Hours Needed:</strong> <span id="modalHoursNeeded"></span></p>
                    <p><strong>School:</strong> <span id="modalInternSchool"></span></p>
                </div>
                <div class="modal-row" id="attachmentSection" style="display: none";>
                    <p class="cv-label"><strong>Intern CV:</strong></p>
                    <div id="modalAttachment"></div>
                </div>
                <div class="modal-footer">
                    <button class="btn-accept modal-btn" onclick="updateReferralStatus('Approved')">Approve</button>
                    <button class="btn-decline modal-btn" onclick="updateReferralStatus('Declined')">Decline</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="js/dropDownMenu.js"></script>
    <script src="js/backBtnKiller.js"></script>
    <script src="js/sendHeartbeat.js"></script>
    <script src="js/modalReferralList.js"></script>
    <script src="js/referralListPageSort.js"></script>
    <script src="js/updateReferralStatus.js"></script>
</body>
</html>
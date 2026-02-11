<?php
    header('Content-Type: application/json');

    try {
        require_once 'dbConfig.php'; //db connection
        session_start(); // session fetch

        $response = ['status' => 'guest'];

        //Updates heartbeat / ping to database
        if (isset($_SESSION['username'])) {
            $uid = $_SESSION['username'];
            $now = time();
            
            $upd = $pdo->prepare("UPDATE users SET last_ping = ? WHERE username = ?");
            $upd->execute([$now, $uid]);

            $sql = "SELECT request_no, submitted_by, request_subject, request_status, request_date, request_time 
                    FROM request_list WHERE request_status = 'Pending'
                    ORDER BY request_time DESC LIMIT 5";
            $stmt = $pdo->query($sql);
            
            $response = [
                'status' => 'success',
                'notifications' => $stmt->fetchAll(PDO::FETCH_ASSOC)
            ];

            echo json_encode($response);
        }

    } catch (Exception $e) {
        // If something fails, return the error as a JSON string, not HTML
        echo json_encode([
            'status' => 'error',
            'message' => $e->getMessage()
        ]);
    }    
?>
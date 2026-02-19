//Session heartbeat to prevent unnecessary forced log out
async function sendHeartbeat() {
    try {
        const response = await fetch('heartbeat.php', { 
            method: 'POST',
            keepalive: true 
        });
        if (response.ok) {
            const data = await response.json();
            
            // Check if we are on the admin dashboard with the notification container
            const notifList = document.getElementById('notification-list');
            
            if (notifList && data.notifications) {
                if (data.notifications.length === 0) {
                    notifList.innerHTML = "<p style='color: #666;'>No pending requests...</p>";
                } else {
                    notifList.innerHTML = data.notifications.map(notif => `
                        <div class="notification-item" onclick="window.location.href='requestsPage.php?openRequest=${notif.request_no}'">
                            <div style="color: #0056b3; font-weight: bold;">${notif.request_subject} - ${notif.submitted_by}</div>
                            <div style="color: #333;">Status: ${notif.request_status}</div>
                            <div style="color: #888; font-size: 0.8em;">${notif.request_date} - ${notif.request_time}</div>
                        </div>
                    `).join('');
                }
            }
            console.log("Heartbeat and notifications synced: " + new Date().toLocaleTimeString());
        } else {
            console.error("Heartbeat server error: " + response.status);
        }
    } catch (e) {
        console.error("Heartbeat failed to send:", e);
    }
}

sendHeartbeat();

setInterval(sendHeartbeat, 5000);
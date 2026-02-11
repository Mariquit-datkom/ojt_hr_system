window.addEventListener('load', function() {
    const urlParams = new URLSearchParams(window.location.search);
    const openRequestId = urlParams.get('openRequest');

    if (openRequestId) {
        // Increased timeout to ensure modalRequestsList.js has finished updateTable()
        setTimeout(() => {
            const requestItems = document.querySelectorAll('.request-item');
            let targetItem = null;

            requestItems.forEach(item => {
                const onclickAttr = item.getAttribute('onclick');
                
                // This regex looks for "request_no":"ID" or "request_no":ID 
                // to handle both string and integer formats in the JSON
                const regex = new RegExp(`"request_no"\\s*:\\s*"?${openRequestId}"?`, 'i');
                
                if (onclickAttr && regex.test(onclickAttr)) {
                    targetItem = item;
                }
            });

            if (targetItem) {
                console.log("Found matching request. Triggering modal...");
                // Force visibility in case pagination hid it
                targetItem.style.setProperty('display', 'flex', 'important'); 
                targetItem.click();
                targetItem.scrollIntoView({ behavior: 'smooth', block: 'center' });
            } else {
                console.warn("Request ID not found in current list: " + openRequestId);
            }
        }, 500); 
    }
});
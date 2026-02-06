function showRequestDetails(data) {
    const modal = document.getElementById('requestModal');
    
    // Fill in the data
    document.getElementById('modalSubject').innerText = data.request_subject;
    document.getElementById('modalDate').innerText = data.request_date;
    document.getElementById('modalMainRequest').innerText = data.request_main || "No details provided.";
    
    // Status Badge Setup
    const statusBadge = document.getElementById('modalStatus');
    statusBadge.innerText = data.request_status;
    statusBadge.className = 'status-badge ' + data.request_status.toLowerCase();

    // Show the modal
    modal.style.display = 'block';
}

function closeModal() {
    document.getElementById('requestModal').style.display = 'none';
}

// Close if user clicks outside the box
window.onclick = function(event) {
    const modal = document.getElementById('requestModal');
    if (event.target == modal) {
        closeModal();
    }
}
function showProgressDetails() {
    const progressModal = document.getElementById('progressModal');
    progressModal.style.display = 'block';
}

function closeProgressModal() {
    document.getElementById('progressModal').style.display = 'none';
}

// Update your existing window.onclick to handle both modals
window.onclick = function(event) {
    const requestModal = document.getElementById('requestModal');
    const progressModal = document.getElementById('progressModal');
    
    if (event.target == requestModal) {
        closeModal(); // Your existing function for the request modal
    }
    if (event.target == progressModal) {
        closeProgressModal();
    }
}
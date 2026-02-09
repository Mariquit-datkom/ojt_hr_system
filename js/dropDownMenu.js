const avatarBtn = document.getElementById('user-avatar-btn');
const dropdown = document.getElementById('user-avatar-dropdown');

// Toggle the dropdown when clicking the avatar
avatarBtn.addEventListener('click', function(event) {
    dropdown.classList.toggle('show');
    event.stopPropagation();
});

// Close the dropdown when clicking anywhere else
window.addEventListener('click', function(event) {
    // Check if the click was OUTSIDE the button and OUTSIDE the dropdown
    if (!avatarBtn.contains(event.target) && !dropdown.contains(event.target)) {
        if (dropdown.classList.contains('show')) {
            dropdown.classList.remove('show');
        }
    }
});
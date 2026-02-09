function showInternDetails(intern) {
    const modal = document.getElementById('intern-modal');
    const modalBody = document.getElementById('modal-body');
    const modalName = document.getElementById('modal-name');

    // Set the Title
    modalName.innerText = intern.intern_first_name + " " + intern.intern_last_name;

    // Inject the Details based on your database columns
    modalBody.innerHTML = `
        <p><strong>Display ID:</strong> ${intern.intern_display_id}</p>
        <p><strong>Course:</strong> ${intern.intern_course}</p>
        <p><strong>Department:</strong> ${intern.intern_dept}</p>
        <p><strong>School:</strong> ${intern.school}</p>
        <p><strong>Hours Progress:</strong> ${intern.accumulated_hours} / ${intern.total_hours_needed} hours</p>
        <p><strong>Employment Date:</strong> ${intern.date_of_employment}</p>
    `;

    // Show the modal
    modal.style.display = "block";
}

function closeModal() {
    document.getElementById('intern-modal').style.display = "none";
}

// Close modal if user clicks anywhere outside of the white box
window.onclick = function(event) {
    const modal = document.getElementById('intern-modal');
    if (event.target == modal) {
        closeModal();
    }
}
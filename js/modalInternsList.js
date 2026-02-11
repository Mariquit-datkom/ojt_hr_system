function showInternDetails(intern) {
    const modal = document.getElementById('intern-modal');
    const modalBody = document.getElementById('modal-body');
    const modalName = document.getElementById('modal-name');
    const hiddenInput = document.getElementById('modal-intern-display-id');

    hiddenInput.value = intern.intern_display_id;
    console.log("Hidden input value set to: ", hiddenInput.value);

    // Set the Title
    let displayName = `${intern.intern_last_name}, ${intern.intern_first_name}`;
    if (intern.intern_middle_initial && intern.intern_middle_initial.trim() !== "") {
        displayName += ` ${intern.intern_middle_initial.trim()}.`;
    }

    modalName.innerText = displayName;

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

document.addEventListener('DOMContentLoaded', function() {
    const searchField = document.getElementById('search-field');
    const entriesSelect = document.getElementById('entries-select');
    const requestItems = document.querySelectorAll('.request-item');

    function filterTable() {
        const searchTerm = searchField.value.toLowerCase();
        const maxEntries = entriesSelect.value === 'all' ? Infinity : parseInt(entriesSelect.value);
        
        let visibleCount = 0;

        requestItems.forEach(item => {
            // Get data from attributes or text content
            const subject = item.getAttribute('data-subject') || item.querySelector('.req-subject').textContent.toLowerCase();
            const matchesSearch = subject.includes(searchTerm);

            if (matchesSearch && visibleCount < maxEntries) {
                item.style.display = 'flex'; // Show item
                visibleCount++;
            } else {
                item.style.display = 'none'; // Hide item
            }
        });

        // Handle "No results found" view
        const noResultsMsg = document.querySelector('.no-requests');
        if (visibleCount === 0 && searchTerm !== "") {
            if (!noResultsMsg) {
                const msg = document.createElement('p');
                msg.className = 'no-requests';
                msg.style.cssText = 'padding: 20px; text-align: center; color: #666; width: 100%;';
                msg.textContent = 'No matching requests found.';
                document.querySelector('.list-table').appendChild(msg);
            }
        } else if (noResultsMsg) {
            noResultsMsg.remove();
        }
    }

    // Event Listeners
    searchField.addEventListener('input', filterTable);
    entriesSelect.addEventListener('change', filterTable);
});

document.addEventListener('DOMContentLoaded', function() {
    const searchField = document.getElementById('search-field');
    const entriesSelect = document.getElementById('entries-select');
    const requestItems = Array.from(document.querySelectorAll('.request-item'));
    const prevBtn = document.getElementById('prevBtn');
    const nextBtn = document.getElementById('nextBtn');
    const pageInfo = document.getElementById('pageInfo');

    let currentPage = 1;

    function updateTable() {
        const searchTerm = searchField.value.toLowerCase();
        const entriesPerPage = entriesSelect.value === 'all' ? requestItems.length : parseInt(entriesSelect.value);

        // Filter items first based on search
        const filteredItems = requestItems.filter(item => {
            return item.innerText.toLowerCase().includes(searchTerm);
        });

        // Calculate pagination
        const totalPages = Math.ceil(filteredItems.length / entriesPerPage) || 1;
        
        // Reset to page 1 if current page is out of bounds (happens on new search)
        if (currentPage > totalPages) currentPage = 1;

        const startIndex = (currentPage - 1) * entriesPerPage;
        const endIndex = startIndex + entriesPerPage;

        // Show/Hide items
        requestItems.forEach(item => item.style.display = 'none'); // Hide all first
        
        filteredItems.forEach((item, index) => {
            if (index >= startIndex && index < endIndex) {
                item.style.display = 'flex';
            }
        });

        // Update UI Controls
        pageInfo.textContent = `Page ${currentPage} of ${totalPages}`;
        prevBtn.disabled = (currentPage === 1);
        nextBtn.disabled = (currentPage === totalPages);
    }

    // Event Listeners
    searchField.addEventListener('input', () => {
        currentPage = 1; // Reset to page 1 when typing
        updateTable();
    });

    entriesSelect.addEventListener('change', () => {
        currentPage = 1; // Reset to page 1 when changing limit
        updateTable();
    });

    prevBtn.addEventListener('click', () => {
        if (currentPage > 1) {
            currentPage--;
            updateTable();
        }
    });

    nextBtn.addEventListener('click', () => {
        currentPage++;
        updateTable();
    });

    updateTable();
});
document.addEventListener('DOMContentLoaded', function() {
    const tableBody = document.querySelector('tbody');
    const newRowTemplate = document.querySelector('.new-row');

    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('add-row')) {
            const newRow = newRowTemplate.cloneNode(true);
            newRow.style.display = ''; 
            newRow.classList.remove('new-row');
            tableBody.appendChild(newRow);
        }
    });

    tableBody.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-row')) {
            e.target.closest('tr').remove();
        }
    });
});
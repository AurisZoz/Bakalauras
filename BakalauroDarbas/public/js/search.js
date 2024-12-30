document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('searchInput');
    const tableBody = document.getElementById(searchInput.dataset.tableId);

    searchInput.addEventListener('input', function () {
        const query = this.value.toLowerCase();
        const rows = tableBody.querySelectorAll('tr');

        rows.forEach(row => {
        const cells = row.querySelectorAll('td');
        let match = false;

        cells.forEach(cell => {
            if (cell.textContent.toLowerCase().includes(query)) {
                match = true;
            }
        });

    row.style.display = match ? '' : 'none';
    });
    });
});

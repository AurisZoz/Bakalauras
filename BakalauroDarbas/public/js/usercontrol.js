document.addEventListener('DOMContentLoaded', () => {
    const searchBar = document.getElementById('searchBar');
    const searchDropdown = document.getElementById('searchDropdown');

    async function searchUsers(query = '') {
        try {
            const response = await fetch(`/api/users/search?query=${encodeURIComponent(query)}`);
            if (!response.ok) throw new Error("Serverio klaida");

            const users = await response.json();
            searchDropdown.innerHTML = ''; 

            if (users.length > 0) {
                users.slice(0, 4).forEach(user => { 
                    const item = document.createElement('a');
                    item.className = 'dropdown-item';
                    item.textContent = `ID: ${user.id}, ${user.name} ${user.surname}`;
                    item.href = `/admin/usercontrol/${user.id}`;
                    searchDropdown.appendChild(item);
                });
                searchDropdown.style.display = 'block';
            } else {
                searchDropdown.style.display = 'none';
            }
        } catch (error) {
            console.error('Klaida ieškant naudotojų:', error);
            alert('Įvyko klaida ieškant naudotojų. Bandykite dar kartą.');
        }
    }

    searchBar.addEventListener('focus', () => {
        searchUsers(); 
    });

    searchBar.addEventListener('input', () => {
        const query = searchBar.value.trim();
        if (query.length > 0) {
            searchUsers(query);
        } else {
            searchUsers();
        }
    });

    document.addEventListener('click', (event) => {
        if (!searchBar.contains(event.target) && !searchDropdown.contains(event.target)) {
            searchDropdown.style.display = 'none';
        }
    });

    searchDropdown.addEventListener('click', (event) => {
        event.stopPropagation();
    });
});

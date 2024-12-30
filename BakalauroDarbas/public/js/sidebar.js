axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

function uploadPhoto() {
    const fileInput = document.getElementById('upload-profile-photo');
    if (!fileInput.files[0]) {
        alert('Pasirinkite failą');
        return;
    }

    const formData = new FormData();
    formData.append('profile_photo', fileInput.files[0]);

    axios.get('/sanctum/csrf-cookie').then(() => {
        axios.post('/api/profile/upload-photo', formData, {
            headers: {
                'Content-Type': 'multipart/form-data',
            }
        })
        .then(response => {
            document.getElementById('profile-photo').src = response.data.profile_photo_url; 
            alert(response.data.message);  
        })
        .catch(error => {
            console.error(error);
            alert('Nepavyko įkelti nuotraukos.'); 
        });
    });
}

function deletePhoto() {

    if (!confirm('Ar tikrai norite ištrinti profilio nuotrauką?')) {
        return; 
    }

    axios.get('/sanctum/csrf-cookie').then(() => {
        axios.delete('/api/profile/delete-photo')
        .then(response => {
            document.getElementById('profile-photo').src = response.data.profile_photo_url; 
            alert(response.data.message); 
        })
        .catch(error => {
            console.error(error);
            alert('Nepavyko ištrinti nuotraukos.'); 
        });
    });
}

function toggleSettingsMenu() {
    var collapseMenu = document.getElementById("collapseExample");
    if (collapseMenu) {
        collapseMenu.classList.toggle("show");
    }
}

function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    sidebar.classList.toggle('active');
}


tinymce.init({
    selector: '#content',
    plugins: 'lists link image preview',
    toolbar: 'undo redo | formatselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image preview',
    menubar: false
});

document.querySelectorAll('input[type="file"]').forEach(input => {
    input.addEventListener('change', (event) => {
        const previewId = input.name === 'photos[]' ? 'photo-preview' : input.name === 'videos[]' ? 'video-preview' : 'file-preview';
        const preview = document.getElementById(previewId);

        Array.from(event.target.files).forEach((file) => {
            const div = document.createElement('div');
            div.classList.add('file-item');
            if (previewId === 'file-preview' && (file.type.startsWith('image/') || file.type.startsWith('video/'))) {
                alert('Papildomų failų skiltyje negalima įkelti vaizdo įrašų ar nuotraukų.');
                return;
            }

            if (file.type.startsWith('image/')) {
                const img = document.createElement('img');
                img.src = URL.createObjectURL(file);
                img.alt = file.name;
                div.appendChild(img);
            } else if (file.type.startsWith('video/')) {
                const video = document.createElement('video');
                video.src = URL.createObjectURL(file);
                video.controls = true;
                div.appendChild(video);
            } else {
                const icon = getFileIcon(file);
                const fileName = document.createElement('span');
                fileName.textContent = file.name;
                div.appendChild(icon);
                div.appendChild(fileName);
            }

            const deleteIcon = document.createElement('i');
            deleteIcon.classList.add('fas', 'fa-trash-alt', 'delete-icon');
            deleteIcon.title = 'Ištrinti';
            deleteIcon.addEventListener('click', function () {
                const confirmDelete = confirm('Ar tikrai norite pašalinti šį failą?');
                if (confirmDelete) {
                    div.remove();
                }
            });
            div.appendChild(deleteIcon);

            preview.appendChild(div);
        });
    });
});

function getFileIcon(file) {
    const icon = document.createElement('i');
    if (file.name.endsWith('.doc') || file.name.endsWith('.docx')) {
        icon.classList.add('fas', 'fa-file-word');
    } else if (file.name.endsWith('.xls') || file.name.endsWith('.xlsx')) {
        icon.classList.add('fas', 'fa-file-excel');
    } else if (file.name.endsWith('.ppt') || file.name.endsWith('.pptx')) {
        icon.classList.add('fas', 'fa-file-powerpoint');
    } else if (file.name.endsWith('.pdf')) {
        icon.classList.add('fas', 'fa-file-pdf');
    } else if (file.name.endsWith('.txt')) {
        icon.classList.add('fas', 'fa-file-alt');
    } else if (file.name.endsWith('.zip')) {
        icon.classList.add('fas', 'fa-file-archive');
    } else {
        icon.classList.add('fas', 'fa-file');
    }
    return icon;
}

function handleFiles(files) {
    const filePreview = document.getElementById('file-preview');

    for (let i = 0; i < files.length; i++) {
        const file = files[i];
        const fileItem = document.createElement('div');
        fileItem.classList.add('file-item');

        const fileIndex = fileList.push(file) - 1;

        const icon = getFileIcon(file);
        const fileName = document.createElement('span');
        fileName.textContent = file.name;
        fileItem.appendChild(icon);
        fileItem.appendChild(fileName);

        const deleteIcon = document.createElement('i');
        deleteIcon.classList.add('fas', 'fa-trash-alt', 'delete-icon');
        deleteIcon.title = 'Ištrinti';
        deleteIcon.addEventListener('click', () => {
            const confirmDelete = confirm('Ar tikrai norite pašalinti šį failą?');
            if (confirmDelete) {
                fileItem.remove();
            }
        });
        fileItem.appendChild(deleteIcon);

        filePreview.appendChild(fileItem);
    }

    updateFileInput();
}

function updateFileInput() {
    const fileInput = document.getElementById('file-upload');
    const dataTransfer = new DataTransfer();

    fileList.forEach(file => {
        dataTransfer.items.add(file);
    });

    fileInput.files = dataTransfer.files;
}

document.getElementById('dropzone-area').addEventListener('click', function () {
    document.getElementById('file-upload').click();
});

document.getElementById('file-upload').addEventListener('change', function () {
    handleFiles(this.files);
});

tinymce.init({
    selector: '#content',
    plugins: 'lists link image preview',
   toolbar: 'undo redo | formatselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image preview',
    menubar: false
});

const fileList = [];
const additionalFileList = [];

function removeFile(fileId, element, type = 'media') {
    if (confirm('Ar tikrai norite pašalinti šį failą?')) {
        const fileItem = element.closest('.file-item');

        if (fileId) {
            fileItem.remove();
            const deletedFilesInput = document.getElementById('deleted-files');
            const deletedFiles = new Set(deletedFilesInput.value.split(',').filter(Boolean));
            deletedFiles.add(fileId);
            deletedFilesInput.value = Array.from(deletedFiles).join(',');
        } else {
            const fileIndex = fileItem.dataset.fileIndex;
            if (type === 'media') {
                removeNewFile(fileIndex, fileItem, fileList);
            } else if (type === 'additional') {
                removeNewFile(fileIndex, fileItem, additionalFileList);
            }
        }
    }
}

function handleFiles(files, previewContainerId, type = 'media') {
    const filePreview = document.getElementById(previewContainerId);
    const targetFileList = type === 'media' ? fileList : additionalFileList;

    Array.from(files).forEach((file) => {
        if (isFileAlreadyUploaded(file, targetFileList)) {
            alert('Šis failas jau įkeltas.');
            return;
        }

        if (type === 'additional' && (file.type.startsWith('image/') || file.type.startsWith('video/'))) {
            alert('Papildomų failų įkėlimo skiltyje negalima įkelti nuotraukų ar vaizdo įrašų.');
            return;
        }

        const fileIndex = targetFileList.push(file) - 1;

        const fileItem = document.createElement('div');
        fileItem.classList.add('file-item');
        fileItem.dataset.fileIndex = fileIndex;

        if (type === 'media' && (file.type.startsWith('image/') || file.type.startsWith('video/'))) {
            const mediaElement = file.type.startsWith('image/') 
                ? document.createElement('img') 
                : document.createElement('video');

            mediaElement.src = URL.createObjectURL(file);
            if (file.type.startsWith('video/')) {
                mediaElement.controls = true;
            }

            fileItem.appendChild(mediaElement);
        } else {
            const icon = document.createElement('i');
            icon.classList.add('fas', 'fa-file');
            fileItem.appendChild(icon);

            const fileName = document.createElement('span');
            fileName.textContent = file.name;
            fileItem.appendChild(fileName);
        }

        const deleteIcon = document.createElement('i');
        deleteIcon.classList.add('fas', 'fa-trash-alt', 'text-danger', 'ms-2', 'delete-icon');
        deleteIcon.onclick = () => removeFile(null, deleteIcon, type);

        fileItem.appendChild(deleteIcon);
        filePreview.appendChild(fileItem);
    });

    updateFileInput(type);
}

function removeNewFile(fileIndex, fileItem, targetFileList) {
    fileIndex = parseInt(fileIndex, 10);
    if (isNaN(fileIndex) || !targetFileList[fileIndex]) {
        console.error(`Failas su indeksu ${fileIndex} nerastas.`);
        return;
    }

    fileItem.remove();
    targetFileList.splice(fileIndex, 1);
    renumberFileIndices(targetFileList, fileItem.closest('.file-preview'));
    updateFileInput(targetFileList === fileList ? 'media' : 'additional');
}

function renumberFileIndices(targetFileList, previewContainer) {
    const fileItems = previewContainer.querySelectorAll('.file-item[data-file-index]');
    fileItems.forEach((item, index) => {
        item.dataset.fileIndex = index;
    });
}

function isFileAlreadyUploaded(file, targetFileList) {
    return targetFileList.some(existingFile => existingFile.name === file.name);
}

function updateFileInput(type = 'media') {
    const fileInput = document.getElementById(type === 'media' ? 'media-file-input' : 'fileInput');
    const dataTransfer = new DataTransfer();
    const targetFileList = type === 'media' ? fileList : additionalFileList;

    targetFileList.forEach(file => {
        dataTransfer.items.add(file);
    });

    fileInput.files = dataTransfer.files;
}

function dropHandler(event) {
    event.preventDefault();
    const files = event.dataTransfer.files;
    handleFiles(files, 'file-preview', 'additional');
}

function dragOverHandler(event) {
    event.preventDefault();
}

function previewFile(input, type) {
    const previewContainerId = type === 'photo' ? 'photo-preview' : type === 'video' ? 'video-preview' : 'file-preview';
    handleFiles(input.files, previewContainerId, type === 'additional' ? 'additional' : 'media');
}

document.getElementById('dropzone-area')?.addEventListener('click', () => {
    document.getElementById('fileInput')?.click();
});

document.getElementById('fileInput')?.addEventListener('change', function () {
    handleFiles(this.files, 'file-preview', 'additional');
});

document.getElementById('edit-plan-form')?.addEventListener('submit', function (event) {
    const formData = new FormData(this);
    fileList.forEach(file => {
        formData.append('media[]', file);
    });
    additionalFileList.forEach(file => {
        formData.append('additional_files[]', file);
    });
});

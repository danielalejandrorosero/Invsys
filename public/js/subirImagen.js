document.addEventListener('DOMContentLoaded', function() {
    var elems = document.querySelectorAll('select');
    M.FormSelect.init(elems);

    const dropzone = document.getElementById('dropzone');
    const fileInput = document.getElementById('imagen');
    const previewArea = document.getElementById('previewArea');
    const previewImg = document.getElementById('previewImg');
    const submitBtn = document.getElementById('submitBtn');
    const productSelect = document.getElementById('id_producto');

    // Drag & drop
    dropzone.addEventListener('dragover', (e) => {
        e.preventDefault();
        dropzone.classList.add('dragover');
    });
    dropzone.addEventListener('dragleave', (e) => {
        e.preventDefault();
        dropzone.classList.remove('dragover');
    });
    dropzone.addEventListener('drop', (e) => {
        e.preventDefault();
        dropzone.classList.remove('dragover');
        const files = e.dataTransfer.files;
        if (files.length > 0) {
            handleFile(files[0]);
        }
    });
    dropzone.addEventListener('click', () => {
        fileInput.click();
    });
    fileInput.addEventListener('change', (e) => {
        if (e.target.files.length > 0) {
            handleFile(e.target.files[0]);
        }
    });
    // Solo agregar el listener si existe el select de producto
    if (productSelect) {
        productSelect.addEventListener('change', checkFormValidity);
    }

    function handleFile(file) {
        if (!file.type.startsWith('image/')) {
            showMessage('Por favor selecciona un archivo de imagen válido.', 'error');
            return;
        }
        if (file.size > 5 * 1024 * 1024) {
            showMessage('El archivo es muy grande. Máximo 5MB permitido.', 'error');
            return;
        }
        const dataTransfer = new DataTransfer();
        dataTransfer.items.add(file);
        fileInput.files = dataTransfer.files;
        const reader = new FileReader();
        reader.onload = (e) => {
            previewImg.src = e.target.result;
            previewArea.style.display = 'block';
            checkFormValidity();
        };
        reader.readAsDataURL(file);
    }
    window.removeFile = function() {
        fileInput.value = '';
        previewArea.style.display = 'none';
        checkFormValidity();
    }
    function checkFormValidity() {
        const hasFile = fileInput.files.length > 0;
        // Si existe el select de producto, también lo requiere, si no, solo la imagen
        const hasProduct = productSelect ? productSelect.value !== '' : true;
        submitBtn.disabled = !(hasFile && hasProduct);
    }
    function showMessage(message, type) {
        const card = document.querySelector('.card-upload');
        const messageDiv = document.createElement('div');
        messageDiv.className = `message ${type === 'error' ? 'error-message' : 'success-message'}`;
        messageDiv.textContent = message;
        card.insertBefore(messageDiv, card.firstChild);
        setTimeout(() => {
            messageDiv.remove();
        }, 4000);
    }
});

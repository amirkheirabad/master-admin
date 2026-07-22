
if ($('#token').length > 0) {

    $("#btn-generate-token").on('click', function () {
        $("#token").val(randomAlphaNumeric10())
    });

    function randomAlphaNumeric10() {
        let chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        let result = '';

        for (let i = 0; i < 20; i++) {
            result += chars.charAt(Math.floor(Math.random() * chars.length));
        }

        return result;
    }
}

$(document).ready(function() {
    $('.select2').select2();
});
$('.select2').select2({
    placeholder: "انتخاب کنید",
    allowClear: true,
    width: '100%',
    language: {
        noResults: function () {
            return "نتیجه‌ای یافت نشد";
        }
    }
});
$(document).ready(function() {
    $('#user_id').select2();
});

const csrf = document.querySelector('meta[name="csrf-token"]').content;

const submitBtn = document.querySelector('button[type="submit"]');

$('#editForm').on('submit', function (e) {
    e.preventDefault();

    submitBtn.classList.add('btn-loading');
    submitBtn.disabled = true;

    let formData = new FormData()

    formData.append('store_name', document.getElementById('store_name').value);
    formData.append('user_id', document.getElementById('user_id').value);
    formData.append('link', document.getElementById('link').value);
    formData.append('phone', document.getElementById('phone').value);
    formData.append('province', document.getElementById('province').value);
    formData.append('city', document.getElementById('city').value);
    formData.append('location', document.getElementById('location').value);
    formData.append('code_posty', document.getElementById('code_posty').value);
    formData.append('about', document.getElementById('about').value);
    formData.append('token', document.getElementById('token').value);
    formData.append('enamd_expiration_date', document.getElementById('enamd_expiration_date').value);
    formData.append('domain_expiration_date', document.getElementById('domain_expiration_date').value);

    if (selectedFiles.length > 0) {
        formData.append('logo_path', selectedFiles[0]);
    }

    clearAllBackendErrors();

    const id = $(this).data('id');

    fetch(`/update/${id}`, {

        method: 'post',

        headers: {
            'X-CSRF-TOKEN': csrf,
            'Accept': 'application/json'
        },

        body: formData
    })

    .then(async (res) => {

        const data = await res.json();

        if (!res.ok) {

            if (data.errors) {
                showBackendErrors(data.errors);
                submitBtn.classList.remove('btn-loading');
                submitBtn.disabled = false;
            }

            return;
        }

        if (data.redirect) {
            window.location.href = data.redirect;
        }

    })

    .catch(err => {
        console.log(err);
        submitBtn.classList.remove('btn-loading');
        submitBtn.disabled = false;
    });

});

function setError(fieldId, message) {

    const errorElement = document.getElementById(fieldId + "_error");

    if (errorElement) {
        errorElement.textContent = message;
    }
}

function showBackendErrors(errors) {

    clearAllBackendErrors();

    Object.keys(errors).forEach(field => {

        const message = Array.isArray(errors[field])
            ? errors[field][0]
            : errors[field];

        setError(field, message);

    });
}

function clearAllBackendErrors() {

    const errorElements = document.querySelectorAll('[id$="_error"]');

    errorElements.forEach(element => {
        element.textContent = '';
    });
}
let selectedFiles = [];
let isNewFileSelected = false;

const attachButton = document.getElementById('attachButton');
const fileInput = document.getElementById('fileInput');
const fileListDiv = document.getElementById('fileList');

attachButton.addEventListener('click', function() {
    fileInput.click();
});

fileInput.addEventListener('change', function(e) {
    const files = e.target.files;
    if(files.length > 0) {
        selectedFiles = [files[0]];
        isNewFileSelected = true;
    }
    displayFileNames();
    fileInput.value = '';
});

function displayFileNames() {
    // اگه فایل جدید انتخاب شده
    if(isNewFileSelected && selectedFiles.length > 0) {
        // مخفی کردن عکس قبلی
        const existingImage = document.getElementById('existingImage');
        const existingHidden = document.getElementById('existingLogoHidden');

        if(existingImage) {
            existingImage.style.display = 'none';
        }
        if(existingHidden) {
            existingHidden.value = '';
        }

        let html = '';
        for(let i = 0; i < selectedFiles.length; i++) {
            const file = selectedFiles[i];
            const isImage = file.type.startsWith('image/');

            if (isImage) {
                const imageUrl = URL.createObjectURL(file);
                html += `
                    <div style="text-align: center; margin-top: 10px;">
                        <img src="${imageUrl}" width="100" height="100" style="display: block; margin-bottom: 5px;">
                    </div>
                `;
            }
        }
        fileListDiv.innerHTML = html;
    }
    else {
        fileListDiv.innerHTML = '';
    }
}

function cancelNewFile() {
    selectedFiles = [];
    isNewFileSelected = false;

    // نمایش مجدد عکس قبلی
    const existingImage = document.getElementById('existingImage');
    const existingHidden = document.getElementById('existingLogoHidden');

    if(existingImage) {
        existingImage.style.display = 'block';
    }
    if(existingHidden) {
        existingHidden.value = document.getElementById('existingLogoValue')?.value || '';
    }

    fileListDiv.innerHTML = '';
    fileInput.value = '';
}

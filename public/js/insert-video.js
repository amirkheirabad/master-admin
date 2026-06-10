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
    $('#category_id').select2();
});



let selectedFiles = [];
const attachButton = document.getElementById('attachButton');
const fileInput = document.getElementById('fileInput');
const fileListDiv = document.getElementById('fileList');
const submitBtn = document.querySelector('button[type="submit"]');


attachButton.addEventListener('click', function() {
    fileInput.click();
});

fileInput.addEventListener('change', function(e) {
    const files = e.target.files;
    for(let i = 0; i < files.length; i++) {
        if(files[i].type.startsWith('video/')) {
            selectedFiles = [files[0]];
        } else {
            Swal.fire({
                icon: 'error',
                title: 'خطا',
                text: 'لطفاً فقط فایل ویدئویی انتخاب کنید',
                confirmButtonText: 'متوجه شدم',
                confirmButtonColor: '#3085d6'
            });
        }
    }
    displayFileNames();
    fileInput.value = '';
});

function displayFileNames() {
    if(selectedFiles.length === 0) {
        fileListDiv.innerHTML = '';
        return;
    }

    let html = `
        <div class="video-item">
            <div class="video-info">
                <span class="video-icon">🎬</span>
                <div class="video-details">
                    <span class="video-name">${selectedFiles[0].name}</span>
                    <span class="video-size">${(selectedFiles[0].size / (1024 * 1024)).toFixed(2)} MB</span>
                </div>
            </div>
            <button class="video-remove" onclick="removeFile()">✕</button>
        </div>
    `;

    fileListDiv.innerHTML = html;
}

function removeFile(index) {
    selectedFiles.splice(index, 1);
    displayFileNames();
}

$('#videoForm').on('submit', function (e) {
    e.preventDefault();
    submitBtn.classList.add('btn-loading');
    submitBtn.disabled = true;

    const csrf = document.querySelector('meta[name="csrf-token"]').content;

    let formData = new FormData();

    formData.append('title', document.getElementById('title').value);
    formData.append('category_id', document.getElementById('category_id').value);
    formData.append('description', document.getElementById('description').value);

    if (selectedFiles.length > 0) {
        formData.append('file_path', selectedFiles[0]);
    }

    fetch('/store-video', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': csrf,
            'Accept': 'application/json',
        },
        body: formData
    })
        .then(res => res.json())
        .then(data => {
            if (data.errors) {
                showBackendErrors(data.errors);
                submitBtn.classList.remove('btn-loading');
                submitBtn.disabled = false;
            }

            if (data.success || data.id || data.redirect) {
                Swal.fire({
                    icon: 'success',
                    title: 'موفق',
                    text: 'ویدئو با موفقیت آپلود شد',
                    showConfirmButton: false,
                    timer: 1500
                }).then(() => {
                    window.location.href = data.redirect;
                })
                submitBtn.classList.remove('btn-loading');
                submitBtn.disabled = false;
            }
        })
        .catch(err => {
            showServerConnectionError();
            submitBtn.classList.remove('btn-loading');
            submitBtn.disabled = false;
        });
});

if ($('#token').length > 0) {
    $("#btn-generate-token").on('click', function () {
        let token =
            $("#token").val(randomAlphaNumeric10())
    })


    function randomAlphaNumeric10() {
        let chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        let result = '';
        for (let i = 0; i < 20; i++) {
            result += chars.charAt(Math.floor(Math.random() * chars.length));
        }
        return result;
    }
}

const csrf = document.querySelector('meta[name="csrf-token"]').content;

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

const submitBtn = document.querySelector('button[type="submit"]');

$('#storeForm').on('submit', function (e) {
    e.preventDefault()
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

    fetch(`/create_store`,{
        method: 'post',
        headers: {
            'X-CSRF-TOKEN': csrf,
            'Accept': 'application/json'
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

            if (data.redirect) {
                window.location.href = data.redirect;
            }
        })
        .catch(err => {
            console.log(err);
            submitBtn.classList.remove('btn-loading');
            submitBtn.disabled = false;
        });
})

$(document).on('click','.delete-message', function () {
    let id = $(this).data('id');

    Swal.fire({
        title:'حذف پیام',
        text: 'آیا از حذف پیام مطمئن هستید',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'بله',
        cancelButtonText: 'خیر',
    }).then((result) => {
        if (result.isConfirmed) {

            fetch(`/delete/${id}`, {
                headers: {
                    "X-CSRF-Token": csrf,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                method: "DELETE",
            })
                .then(function (response) {
                    if(response.ok)
                    {
                        Swal.fire({
                            title: "حذف شد!",
                            text: "سوال با موفقیت حذف شد",
                            icon: "success",
                            showCancelButton: false,
                            confirmButtonText: "بستن",
                        })
                        $(`.item-record${id}`).remove()
                    }
                    else {
                        Swal.fire({
                            title: "خطا!",
                            text: "حذف سوال انجام نشد",
                            icon: "error",
                            showCancelButton: false,
                            confirmButtonText: "بستن",
                        });
                    }
                })
        }
    })
})


let selectedFiles = []; // این خط رو اول کدت اضافه کن

const attachButton = document.getElementById('attachButton');
const fileInput = document.getElementById('fileInput');
const fileListDiv = document.getElementById('fileList');


attachButton.addEventListener('click', function() {
    fileInput.click();
});

fileInput.addEventListener('change', function(e) {
    const files = e.target.files;
    for(let i = 0; i < files.length; i++) {
        selectedFiles = [files[0]];
    }
    displayFileNames();
    fileInput.value = '';
});

function displayFileNames() {
    if(selectedFiles.length === 0) {
        fileListDiv.innerHTML = '';
        return;
    }

    let html = '<ul>';
    for(let i = 0; i < selectedFiles.length; i++) {
        const file = selectedFiles[i];
        const isImage = file.type.startsWith('image/');

        if (isImage) {
            const imageUrl = URL.createObjectURL(file);
            html += `<img src="${imageUrl}" width="100" height="100" style="display: block; margin-bottom: 5px;"><br>`;
        }

    }
    html += '</ul>';
    fileListDiv.innerHTML = html;
}


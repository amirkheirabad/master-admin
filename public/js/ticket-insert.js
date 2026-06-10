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



// قرار بده توی فایل JS خودت
const csrf = document.querySelector('meta[name="csrf-token"]').content;

// آرایه برای ذخیره فایل‌ها
let selectedFiles = [];

// تابع مدیریت لودینگ دکمه
function setButtonLoading(button, isLoading, originalText = null) {
    if (isLoading) {
        // ذخیره متن اصلی
        if (!button.getAttribute('data-original-html')) {
            button.setAttribute('data-original-html', button.innerHTML);
        }

        // اضافه کردن کلاس لودینگ
        button.classList.add('btn-loading');
        button.disabled = true;

    } else {
        // برگردوندن به حالت اول
        button.classList.remove('btn-loading');
        button.disabled = false;

        const originalHtml = button.getAttribute('data-original-html');
        if (originalHtml) {
            button.innerHTML = originalHtml;
            button.removeAttribute('data-original-html');
        }
    }
}

// تابع رفرش کپچا
function refreshCaptcha() {
    return $.get('/refresh-captcha', function(data) {
        $('#captchaLabel').text(data.question);
        $('input[name="captcha"]').val('');
    });
}

// تابع نمایش خطاهای بک‌اند
function showBackendErrors(errors) {
    // پاک کردن خطاهای قبلی
    clearAllBackendErrors();

    // نمایش خطاهای جدید
    Object.keys(errors).forEach(field => {
        const message = Array.isArray(errors[field])
            ? errors[field][0]
            : errors[field];

        setError(field, message);

        // اگه خطای کپچا بود، رفرش کن
        if (field === 'captcha') {
            refreshCaptcha();
        }
    });
}

// دکمه تغییر سوال کپچا
$('#refreshCaptchaBtn').on('click', function() {
    refreshCaptcha();
});

// سابمیت فرم با Ajax (فرم ادمین)
$('#ticketForm').on('submit', function (e) {
    e.preventDefault();

    // گرفتن دکمه سابمیت
    const submitBtn = this.querySelector('button[type="submit"]');

    // فعال کردن حالت لودینگ
    setButtonLoading(submitBtn, true);

    // ساخت FormData برای ارسال فایل و دیتا
    const formData = new FormData();

    // اضافه کردن فیلدهای فرم به FormData
    formData.append('recipient_type', $('#recipient_type').val());
    formData.append('contact_name', $('#contact_name').val());
    formData.append('title', $('#title').val());
    formData.append('message', $('#message').val());
    formData.append('priority', $('select[name="priority"]').val());
    formData.append('captcha', $('#captcha').val());

    if ($('#recipient_type').val() === 'store') {
        formData.append('store_id', $('#store_id').val());
    } else {
        formData.append('user_id', $('#user_id').val());
    }

    // اضافه کردن فایل‌ها به FormData
    if (selectedFiles.length > 0) {
        for(let i = 0; i < selectedFiles.length; i++) {
            formData.append('attachments[]', selectedFiles[i]);
        }
    }

    // ارسال درخواست
    fetch('/ticket_store', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': csrf,
            'Accept': 'application/json'
        },
        body: formData
    })
        .then(res => {
            if (res.redirected) {
                window.location.href = res.url;
                return;
            }
            return res.json();
        })
        .then(data => {
            if (data.errors) {
                showBackendErrors(data.errors);
                setButtonLoading(submitBtn, false);
                return;
            }

            if (data && data.success) {
                $('#ticketForm')[0].reset();
                selectedFiles = [];
                displayFileNames();
                refreshCaptcha(); // رفرش کپچا بعد از موفقیت

                if (data.redirect) {
                    window.location.href = data.redirect;
                }
            } else {
                setButtonLoading(submitBtn, false);
                if (data && data.error) {
                }
            }
        })
        .catch(err => {
            console.log(err);
            showServerConnectionError();
            setButtonLoading(submitBtn, false);
        });
});

// سابمیت فرم فروشنده
$('#ticketFormUser').on('submit', function (e) {
    e.preventDefault();

    // گرفتن دکمه سابمیت
    const submitBtn = this.querySelector('button[type="submit"]');

    // فعال کردن حالت لودینگ
    setButtonLoading(submitBtn, true);

    // ساخت FormData برای ارسال فایل و دیتا
    const formData = new FormData();

    // اضافه کردن فیلدهای فرم به FormData
    formData.append('recipient_type', $('input[name="recipient_type"]').val());
    formData.append('contact_name', $('#contact_name').val());

    const storeId = $('input[name="store_id"]').val();
    const userId = $('input[name="user_id"]').val();

    if (storeId) {
        formData.append('store_id', storeId);
    }
    if (userId) {
        formData.append('user_id', userId);
    }
    formData.append('title', $('#title').val());
    formData.append('message', $('#message').val());
    formData.append('priority', $('select[name="priority"]').val());
    formData.append('captcha', $('input[name="captcha"]').val());

    // اضافه کردن فایل‌ها به FormData
    if (selectedFiles.length > 0) {
        for(let i = 0; i < selectedFiles.length; i++) {
            formData.append('attachments[]', selectedFiles[i]);
        }
    }

    // ارسال درخواست
    fetch('/tickets_store_admin', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': csrf,
            'Accept': 'application/json'
        },
        body: formData
    })
        .then(res => {
            if (res.redirected) {
                window.location.href = res.url;
                return;
            }
            return res.json();
        })
        .then(data => {
            console.log('Response data:', data);

            if (data.errors) {
                showBackendErrors(data.errors);
                setButtonLoading(submitBtn, false);
                return;
            }

            if (data && data.success) {
                console.log('Success block entered');
                console.log('Redirect URL:', data.redirect);

                try {
                    $('#ticketFormUser')[0].reset();
                    selectedFiles = [];
                    displayFileNames();
                    refreshCaptcha(); // رفرش کپچا بعد از موفقیت

                    if (data.redirect) {
                        window.location.href = data.redirect;
                    }
                } catch (error) {
                    console.error(error);
                }
            } else {
                setButtonLoading(submitBtn, false);
                if (data && data.error) {
                }
            }
        })
        .catch(err => {
            console.log(err);
            showServerConnectionError();
            setButtonLoading(submitBtn, false);
        });
});

// بقیه کدهای فایل‌های شما
const attachButton = document.getElementById('attachButton');
const fileInput = document.getElementById('fileInput');
const fileListDiv = document.getElementById('fileList');

attachButton.addEventListener('click', function() {
    fileInput.click();
});

fileInput.addEventListener('change', function(e) {
    const files = e.target.files;
    for(let i = 0; i < files.length; i++) {
        selectedFiles.push(files[i]);
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
        html += `<li>
                    ${selectedFiles[i].name} - ${(selectedFiles[i].size / 1024).toFixed(2)} KB
                    <button type="button" class="remove-btn" onclick="removeFile(${i})">✕</button>
                 </li>`;
    }
    html += '</ul>';
    fileListDiv.innerHTML = html;
}

function removeFile(index) {
    selectedFiles.splice(index, 1);
    displayFileNames();
}

$('#recipient_type').on('change', function() {
    if ($(this).val() === 'store') {
        $('#store_wrapper').show();
        $('#user_wrapper').hide();
    } else {
        $('#store_wrapper').hide();
        $('#user_wrapper').show();
    }
});

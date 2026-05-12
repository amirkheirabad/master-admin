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

// سابمیت فرم با Ajax
$('#ticketForm').on('submit', function (e) {
    e.preventDefault();

    // گرفتن دکمه سابمیت
    const submitBtn = this.querySelector('button[type="submit"]');

    // فعال کردن حالت لودینگ
    setButtonLoading(submitBtn, true);

    // ساخت FormData برای ارسال فایل و دیتا
    const formData = new FormData();

    // اضافه کردن فیلدهای فرم به FormData
    formData.append('store_id', $('#store_id').val());
    formData.append('contact_name', $('#contact_name').val());
    formData.append('title', $('#title').val());
    formData.append('message', $('#description').val());

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
            if (data && data.success) {
                alert('تیکت با موفقیت ایجاد شد');
                $('#ticketForm')[0].reset();
                selectedFiles = [];
                displayFileNames();

                if (data.redirect) {
                    window.location.href = data.redirect;
                }
            } else {
                // خطا رخ داده - برگردوندن دکمه به حالت عادی
                setButtonLoading(submitBtn, false);

                if (data && data.errors) {
                    let errorMsg = '';
                    for (let key in data.errors) {
                        errorMsg += data.errors[key] + '\n';
                    }
                    alert(errorMsg);
                } else {
                    alert(data?.message || 'خطا در ایجاد تیکت');
                }
            }
        })
        .catch(err => {
            console.log(err);
            alert('خطا در ارتباط با سرور');
            // خطا رخ داده - برگردوندن دکمه به حالت عادی
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

// حذف event listener قبلی که اضافه کردی (اگه وجود داره)
// اگه قبلاً کد querySelectorAll رو اضافه کردی، باید حذفش کنی یا کامنت کنی

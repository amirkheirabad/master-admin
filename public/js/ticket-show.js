// ========== المنت‌های مربوط به فرم ارسال پیام ==========
const replyForm = document.getElementById('replyForm');
const attachButton = document.getElementById('attachButton');
const fileInput = document.getElementById('fileInput');
const filePreviewWrapper = document.getElementById('filePreviewWrapper');
const filePreviewContent = document.getElementById('filePreviewContent');
const searchContainer = document.getElementById('searchContainer');
const removeAllFilesBtn = document.getElementById('removeAllFilesBtn');

// تابع حذف همه فایل‌ها
function removeAllFiles() {
    if (fileInput) {
        fileInput.value = ''; // پاک کردن فایل‌های انتخاب شده
    }
    updateFilePreview(null); // به‌روزرسانی preview
}

// اضافه کردن رویداد به دکمه حذف همه
if (removeAllFilesBtn) {
    removeAllFilesBtn.addEventListener('click', removeAllFiles);
}

// تابع اصلی نمایش فایل‌ها
function updateFilePreview(files) {
    // چک کنید المنت‌ها وجود دارند
    if (!filePreviewWrapper || !filePreviewContent || !searchContainer) return;

    if (!files || files.length === 0) {
        filePreviewWrapper.style.display = 'none';
        filePreviewContent.innerHTML = '';
        searchContainer.style.borderRadius = '7px';
        return;
    }

    filePreviewWrapper.style.display = 'block';
    filePreviewContent.innerHTML = '';

    if (files.length === 1) {
        // حالت تک فایل - حجم فایل زیر اسم
        const file = files[0];
        const icon = getFileIcon(file.type);
        const fileSize = (file.size / 1024).toFixed(1);

        filePreviewContent.innerHTML = `
            <div class="file-preview-item">
                <div class="file-icon-box">
                    <i class="fa ${icon}"></i>
                </div>
                <div class="file-info-text">
                    <span class="file-name">${escapeHtml(file.name)}</span>
                    <span class="file-size-detail">${fileSize} KB</span>
                </div>
            </div>
        `;
    } else {
        // حالت چند فایل
        const totalSize = Array.from(files).reduce((sum, f) => sum + f.size, 0);
        const totalSizeKB = (totalSize / 1024).toFixed(1);

        filePreviewContent.innerHTML = `
            <div class="file-preview-item">
                <div class="double-icon">
                    <img src="/icons/files.svg" alt="file icon" class="svg-icon" style="width: 20px; height: 20px;">
                </div>
                <div class="file-info">
                    <span class="file-count-text">${files.length} فایل</span>
                    <span class="file-size-text">${totalSizeKB} KB</span>
                </div>
            </div>
        `;
    }

    searchContainer.style.borderRadius = '0 0 7px 7px';
}

// تابع برای آیکون مناسب بر اساس نوع فایل
function getFileIcon(fileType) {
    if (fileType.startsWith('image/')) return 'fa-image';
    if (fileType === 'application/pdf') return 'fa-file-pdf';
    if (fileType.startsWith('video/')) return 'fa-video';
    if (fileType.startsWith('audio/')) return 'fa-music';
    return 'fa-file';
}

// تابع کمکی برای جلوگیری از XSS
function escapeHtml(str) {
    if (!str) return '';
    return str.replace(/[&<>]/g, function(m) {
        if (m === '&') return '&amp;';
        if (m === '<') return '&lt;';
        if (m === '>') return '&gt;';
        return m;
    });
}

// کلیک روی دکمه attach - فقط اگر المنت وجود داشته باشد
if (attachButton && fileInput) {
    attachButton.addEventListener('click', function(e) {
        e.preventDefault();
        fileInput.click();
    });
}

// تغییر فایل‌ها - فقط اگر المنت وجود داشته باشد
if (fileInput) {
    fileInput.addEventListener('change', function(e) {
        updateFilePreview(e.target.files);
    });
}

$('#replyForm').on('submit', function (e) {
    e.preventDefault()

    const csrf = document.querySelector('meta[name="csrf-token"]').content;

    const id = document.getElementById('ticket_id').value;
    const messageInput = document.getElementById('messageInput');
    const message = messageInput ? messageInput.value.trim() : '';


    let formData = new FormData()

    formData.append('message', messageInput.value);

    const fileInput = document.getElementById('fileInput');
    if (fileInput.files && fileInput.files.length > 0) {
        for (let i = 0; i < fileInput.files.length; i++) {
            formData.append('attachments[]', fileInput.files[i]);
        }
    }

    fetch(this.action, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': csrf,
            'Accept': 'application/json',
        },
        body: formData
    })
        .then(response => {
            return response.json().then(data => ({
                status: response.status,
                body: data
            })).catch(() => ({
                status: response.status,
                body: null
            }));
        })
        .then(({status, body}) => {
            if (status === 200 || status === 201) {
                location.reload();
            } else if (status === 422 && body && body.errors) {
                showBackendErrors(body.errors);
            } else {
            }
        })
        .catch(err => {
            console.log(err);
        });
});

document.addEventListener('DOMContentLoaded', function() {
    const chatContainer = document.querySelector('.chat-container');
    if (chatContainer) {
        chatContainer.scrollTop = chatContainer.scrollHeight;
    }
});

// ========== توابع دراپ‌داون ==========
function toggleCustomDropdown(element) {
    var options = element.nextElementSibling;
    var allDropdowns = document.querySelectorAll('.dropdown-options');

    allDropdowns.forEach(function(dropdown) {
        if (dropdown !== options) {
            dropdown.style.display = 'none';
        }
    });

    if (options) {
        options.style.display = options.style.display === 'none' ? 'block' : 'none';
    }
}

function openModalAndClose(element, ticketId, storeName, statusValue, statusText) {
    // بستن منو
    document.querySelectorAll('.dropdown-options').forEach(function(dropdown) {
        dropdown.style.display = 'none';
    });

    // ذخیره اطلاعات در مودال
    const ticketIdInput = document.getElementById('ticket_id');
    const newStatusInput = document.getElementById('new_status');

    if (ticketIdInput) ticketIdInput.value = ticketId;
    if (newStatusInput) newStatusInput.value = statusValue;

    // باز کردن مودال
    if (typeof $ !== 'undefined' && $('#myModal').length) {
        $('#myModal').modal('show');
    }
}

function confirmStatusChange() {
    const ticketIdInput = document.getElementById('ticket_id');
    const newStatusInput = document.getElementById('new_status');

    if (!ticketIdInput || !newStatusInput) return;

    const ticketId = ticketIdInput.value;
    let newStatus = newStatusInput.value;

    // تبدیل به عدد
    newStatus = parseInt(newStatus);

    const csrfToken = document.querySelector('meta[name="csrf-token"]');
    if (!csrfToken) return;

    const formData = new FormData();
    formData.append('status', newStatus);

    fetch(`/tickets/${ticketId}/status`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': csrfToken.getAttribute('content'),
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: formData
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                if (typeof $ !== 'undefined' && $('#myModal').length) {
                    $('#myModal').modal('hide');
                }
                location.reload();
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
}

// ========== توابع مدیریت موبایل ==========
function handleMobileClasses() {
    const isMobile = window.innerWidth < 768;

    // پیدا کردن المنت‌ها با selector جایگزین
    let elements = document.querySelectorAll('.x_panel.bg-chatbox, [data-original-classes="x_panel bg-chatbox"]');

    if (elements.length === 0 && !isMobile) {
        return;
    }

    elements.forEach(el => {
        if (isMobile) {
            // حالت موبایل: ذخیره و حذف کلاس‌ها
            if (!el.dataset.originalClasses) {
                if (el.classList.contains('x_panel') && el.classList.contains('bg-chatbox')) {
                    el.dataset.originalClasses = 'x_panel bg-chatbox';
                }
            }
            el.classList.remove('x_panel', 'bg-chatbox');
        } else {
            // حالت دسکتاپ: برگرداندن کلاس‌ها
            if (el.dataset.originalClasses) {
                const classes = el.dataset.originalClasses.split(' ');
                el.classList.add(...classes);
                delete el.dataset.originalClasses;
            }
        }
    });
}

// ========== متغیرهای مودال تغییر وضعیت موبایل ==========
let selectedStatus = null;
let currentTicketId = null;
let currentStoreName = null;

// المنت‌های مودال موبایل
const modal = document.getElementById('statusChangeModal');
const closeBtn = document.getElementById('closeModalBtn');
const cancelBtn = document.getElementById('cancelBtn');
const applyBtn = document.getElementById('applyBtn');
const overlay = document.querySelector('.mobile-status-overlay');
const statusOptions = document.querySelectorAll('.status-option');

// ========== تابع باز کردن مودال موبایل ==========
function openStatusModal(ticketId, storeName, currentStatus) {
    if (!modal) return;

    // ذخیره اطلاعات
    currentTicketId = ticketId;
    currentStoreName = storeName;
    selectedStatus = null;

    // حذف کلاس selected از همه گزینه‌ها
    statusOptions.forEach(opt => {
        opt.classList.remove('selected');
    });

    // اگر وضعیت فعلی داشت، اون رو selected کن
    if (currentStatus !== undefined && currentStatus !== null) {
        const currentOption = document.querySelector(`.status-option[data-status="${currentStatus}"]`);
        if (currentOption) {
            currentOption.classList.add('selected');
            selectedStatus = currentStatus;
        }
    }

    // نمایش مودال
    modal.classList.add('active');
    document.body.style.overflow = 'hidden';
}

// ========== تابع بستن مودال موبایل ==========
function closeStatusModal() {
    if (!modal) return;
    modal.classList.remove('active');
    document.body.style.overflow = '';
    selectedStatus = null;
}

// ========== کلیک روی گزینه‌های وضعیت ==========
if (statusOptions.length > 0) {
    statusOptions.forEach(option => {
        option.addEventListener('click', function() {
            // حذف selected از همه
            statusOptions.forEach(opt => {
                opt.classList.remove('selected');
            });

            // اضافه selected به این یکی
            this.classList.add('selected');

            // ذخیره مقدار انتخاب شده
            selectedStatus = this.getAttribute('data-status');
        });
    });
}

// ========== کلیک روی گزینه "تغییر وضعیت" در منو ==========
document.querySelectorAll('.dropdown-option').forEach(option => {
    option.addEventListener('click', function(e) {
        const text = this.innerText.trim();

        if (text === 'تغییر وضعیت') {
            e.stopPropagation();
            // بستن دراپ‌داون
            const dropdown = this.closest('.dropdown-options');
            if (dropdown) dropdown.style.display = 'none';

            const ticketId = this.getAttribute('data-ticket-id') || null;
            const storeName = this.getAttribute('data-store-name') || 'فروشگاه نمونه';
            const currentStatus = this.getAttribute('data-current-status') || null;

            if (ticketId) {
                openStatusModal(ticketId, storeName, currentStatus);
            }
        }
    });
});

// بستن دراپ‌داون با کلیک بیرون
document.addEventListener('click', function(e) {
    if (!e.target.closest('.dropdown-custom')) {
        document.querySelectorAll('.dropdown-options').forEach(opt => {
            opt.style.display = 'none';
        });
    }
});

// ========== اعمال تغییر وضعیت ==========
function applyStatusChange() {
    if (!selectedStatus) {
        // می‌توانی نوتیفیکیشن نمایش بدی
        if (typeof showNotification === 'function') {
            showNotification('لطفاً یک وضعیت انتخاب کنید', 'error');
        }
        return;
    }

    if (!currentTicketId) {
        console.error('شناسه تیکت موجود نیست');
        if (typeof showNotification === 'function') {
            showNotification('خطا: شناسه تیکت یافت نشد', 'error');
        }
        return;
    }

    const applyBtn = document.getElementById('applyBtn');
    const originalText = applyBtn ? applyBtn.innerHTML : '';

    if (applyBtn) {
        applyBtn.disabled = true;
    }

    const csrfToken = document.querySelector('meta[name="csrf-token"]');
    if (!csrfToken) {
        console.error('CSRF token not found');
        if (applyBtn) {
            applyBtn.disabled = false;
        }
        return;
    }

    fetch(`/tickets/${currentTicketId}/status`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken.getAttribute('content'),
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            status: parseInt(selectedStatus)
        })
    })
        .then(response => {
            if (!response.ok) {
                return response.json().then(err => Promise.reject(err));
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                if (typeof showNotification === 'function') {
                    showNotification('وضعیت تیکت با موفقیت تغییر کرد', 'success');
                }
                closeStatusModal();
                // رفرش صفحه
                window.location.reload();
            } else {
                if (typeof showNotification === 'function') {
                    showNotification(data.message || 'خطا در تغییر وضعیت', 'error');
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
            if (typeof showNotification === 'function') {
                showNotification('خطا در ارتباط با سرور', 'error');
            }
        })
        .finally(() => {
            if (applyBtn) {
                applyBtn.innerHTML = originalText;
                applyBtn.disabled = false;
            }
        });
}

// ========== اتصال رویدادها بعد از لود صفحه ==========
document.addEventListener('DOMContentLoaded', function() {
    // اتصال دکمه اعمال
    const applyBtn = document.getElementById('applyBtn');
    if (applyBtn) {
        applyBtn.addEventListener('click', function(e) {
            e.preventDefault();
            applyStatusChange();
        });
    }

    // اتصال دکمه بستن
    const closeModalBtn = document.getElementById('closeModalBtn');
    if (closeModalBtn) {
        closeModalBtn.addEventListener('click', closeStatusModal);
    }

    // اتصال دکمه انصراف
    const cancelModalBtn = document.getElementById('cancelBtn');
    if (cancelModalBtn) {
        cancelModalBtn.addEventListener('click', closeStatusModal);
    }

    // کلیک روی overlay
    const overlay = document.querySelector('.mobile-status-overlay');
    if (overlay) {
        overlay.addEventListener('click', closeStatusModal);
    }

    // اجرا توابع مدیریت موبایل
    handleMobileClasses();
});

// اجرا در رسیز
window.addEventListener('resize', handleMobileClasses);















// seller

$('#replyFormUser').on('submit', function (e) {
    e.preventDefault()

    const csrf = document.querySelector('meta[name="csrf-token"]').content;

    const id = document.getElementById('ticket_id').value;
    const messageInput = document.getElementById('messageInput');
    const message = messageInput ? messageInput.value.trim() : '';


    let formData = new FormData()

    formData.append('message', messageInput.value);

    const fileInput = document.getElementById('fileInput');
    if (fileInput.files && fileInput.files.length > 0) {
        for (let i = 0; i < fileInput.files.length; i++) {
            formData.append('attachments[]', fileInput.files[i]);
        }
    }

    fetch(this.action, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': csrf,
            'Accept': 'application/json',
        },
        body: formData
    })
        .then(response => {
            return response.json().then(data => ({
                status: response.status,
                body: data
            })).catch(() => ({
                status: response.status,
                body: null
            }));
        })
        .then(({status, body}) => {
            if (status === 200 || status === 201) {
                location.reload();
            } else if (status === 422 && body && body.errors) {
                showBackendErrors(body.errors);
            } else {
            }
        })
        .catch(err => {
            console.log(err);
        });
});

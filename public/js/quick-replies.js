// public/js/quick-replies.js
const csrf = document.querySelector('meta[name="csrf-token"]').content;

const formPanel  = document.getElementById('formPanel');
const formTitle  = document.getElementById('formTitle');
const editId     = document.getElementById('editId');
const qrTitle    = document.getElementById('qrTitle');
const qrBody     = document.getElementById('qrBody');

// باز کردن فرم برای افزودن
document.getElementById('addNewBtn').addEventListener('click', () => {
    editId.value = '';
    qrTitle.value = '';
    qrBody.value = '';
    formTitle.textContent = 'افزودن جواب جدید';
    formPanel.style.display = 'block';
    formPanel.scrollIntoView({ behavior: 'smooth' });
});

document.getElementById('cancelFormBtn').addEventListener('click', () => {
    formPanel.style.display = 'none';
});

// ذخیره (افزودن یا ویرایش)
document.getElementById('saveBtn').addEventListener('click', async () => {
    const id     = editId.value;
    const title  = qrTitle.value.trim();
    const body   = qrBody.value.trim();

    if (!title || !body) {
        Swal.fire({ icon: 'warning', title: 'خطا', text: 'عنوان و متن الزامی هستند', confirmButtonText: 'باشه' });
        return;
    }

    const url    = id ? `/quick-replies/${id}` : '/quick-replies';
    const method = id ? 'PUT' : 'POST';

    try {
        const res  = await fetch(url, {
            method,
            headers: { 'X-CSRF-TOKEN': csrf, 'Content-Type': 'application/json', 'Accept': 'application/json' },
            body: JSON.stringify({ title, body }),
        });
        const data = await res.json();

        if (data.success) {
            Swal.fire({ icon: 'success', title: 'ذخیره شد', confirmButtonText: 'باشه' })
                .then(() => location.reload());
        }
    } catch (e) {
        Swal.fire({ icon: 'error', title: 'خطای سرور', confirmButtonText: 'باشه' });
    }
});

// ویرایش
document.addEventListener('click', (e) => {
    const btn = e.target.closest('.edit-qr');
    if (!btn) return;

    editId.value   = btn.dataset.id;
    qrTitle.value  = btn.dataset.title;
    qrBody.value   = btn.dataset.body;
    formTitle.textContent = 'ویرایش جواب';
    formPanel.style.display = 'block';
    formPanel.scrollIntoView({ behavior: 'smooth' });
});

// حذف
document.addEventListener('click', (e) => {
    const btn = e.target.closest('.delete-qr');
    if (!btn) return;

    const id = btn.dataset.id;
    Swal.fire({
        title: 'حذف جواب آماده',
        text: 'مطمئنی؟',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'بله',
        cancelButtonText: 'خیر',
    }).then(async (result) => {
        if (!result.isConfirmed) return;

        const res  = await fetch(`/quick-replies/${id}`, {
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
        });
        const data = await res.json();

        if (data.success) {
            document.getElementById(`qr-row-${id}`)?.remove();
            Swal.fire({ icon: 'success', title: 'حذف شد', confirmButtonText: 'باشه' });
        }
    });
});
const storeStatusCsrf = document.querySelector('meta[name="csrf-token"]').content;

async function saveStoreStatus(url, method, input, error) {
    error.textContent = '';

    const response = await fetch(url, {
        method,
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': storeStatusCsrf,
            'Accept': 'application/json'
        },
        body: JSON.stringify({ name: input.value })
    });
    const data = await response.json();

    if (!response.ok) {
        error.textContent = data.errors?.name?.[0] ?? 'ثبت وضعیت انجام نشد';
        return;
    }

    location.reload();
}

document.getElementById('create-status').addEventListener('click', () => {
    saveStoreStatus('/store_statuses', 'POST', document.getElementById('status_name'), document.getElementById('status_name_error'));
});

document.querySelectorAll('.edit-status').forEach(button => {
    button.addEventListener('click', () => {
        document.getElementById('edit_status_id').value = button.dataset.id;
        document.getElementById('edit_status_name').value = button.dataset.name;
    });
});

document.getElementById('update-status').addEventListener('click', () => {
    const id = document.getElementById('edit_status_id').value;
    saveStoreStatus(`/store_statuses/${id}`, 'PUT', document.getElementById('edit_status_name'), document.getElementById('edit_status_name_error'));
});

document.querySelectorAll('.delete-status').forEach(button => {
    button.addEventListener('click', () => {
        Swal.fire({
            title: 'حذف وضعیت فروشگاه',
            text: 'آیا از حذف این وضعیت مطمئن هستید؟',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'بله',
            cancelButtonText: 'خیر'
        }).then(async result => {
            if (!result.isConfirmed) return;

            const response = await fetch(`/store_statuses/${button.dataset.id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': storeStatusCsrf,
                    'Accept': 'application/json'
                }
            });

            if (response.ok) {
                location.reload();
            }
        });
    });
});

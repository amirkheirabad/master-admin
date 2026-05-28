const csrf = document.querySelector('meta[name="csrf-token"]').content;

// جدید
function countActiveFilters() {
    let count = 0;

    document.querySelectorAll('#filterMenu select').forEach(select => {
        const value = select.value;
        const firstOptionValue = select.options[0]?.value || '';

        if (value && value !== '' && value !== firstOptionValue) {
            count++;
        }
    });

    // const searchInput = document.querySelector('.search-input');
    // if (searchInput && searchInput.value.trim() !== '') {
    //     count++;
    // }

    return count;
}

function updateFilterBadge() {
    const badge = document.getElementById('filterBadge');
    if (!badge) return;

    const activeFiltersCount = countActiveFilters();

    if (activeFiltersCount > 0) {
        badge.textContent = activeFiltersCount;
        badge.style.display = 'inline-block';
    } else {
        badge.style.display = 'none';
    }
}

document.getElementById('clearFiltersBtn')?.addEventListener('click', function (e) {
    e.preventDefault();

    const roleSelect = document.querySelector('select[name="role_id"]');
    if (roleSelect) {
        roleSelect.value = '';
        $(roleSelect).trigger('change');
    }

    const searchInput = document.querySelector('.search-input');
    if (searchInput) {
        searchInput.value = '';
    }

    window.location.href = window.location.pathname;
});

document.addEventListener('DOMContentLoaded', updateFilterBadge);

$(document).on('click', '.delete-message', function () {

    let id = $(this).data('id');

    Swal.fire({
        title: 'حذف کاربر',
        text: 'آیا از حذف کاربر مطمئن هستید؟',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'بله',
        cancelButtonText: 'خیر',
    })

    .then((result) => {

        if (result.isConfirmed) {

            fetch(`/user-delete/${id}`, {

                method: "DELETE",

                headers: {
                    "X-CSRF-TOKEN": csrf,
                    "Accept": "application/json",
                    "Content-Type": "application/json"
                }

            })

            .then(function (response) {

                if (response.ok) {

                    Swal.fire({
                        title: "حذف شد!",
                        text: "کاربر با موفقیت حذف شد",
                        icon: "success",
                        confirmButtonText: "بستن",
                    });

                    $(`.item-record${id}`).remove();

                } else {

                    Swal.fire({
                        title: "خطا!",
                        text: "حذف کاربر انجام نشد",
                        icon: "error",
                        confirmButtonText: "بستن",
                    });

                }

            })

            .catch(function (error) {

                console.log(error);

                Swal.fire({
                    title: "خطا!",
                    text: "مشکلی در ارتباط با سرور پیش آمد",
                    icon: "error",
                    confirmButtonText: "بستن",
                });

            });

        }

    });

});
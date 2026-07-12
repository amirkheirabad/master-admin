const csrf = document.querySelector('meta[name="csrf-token"]').content;

document.addEventListener('DOMContentLoaded', function () {

    document.querySelectorAll('.delete-message').forEach(function (button) {

        button.addEventListener('click', function () {

            const id = this.dataset.id;

            Swal.fire({
                title: 'حذف پیام',
                text: 'آیا از حذف این پیام مطمئن هستید؟',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'بله، حذف شود',
                cancelButtonText: 'انصراف',
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                reverseButtons: true
            }).then((result) => {

                if (result.isConfirmed) {

                    fetch(`/message-delete/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrf,
                            'Accept': 'application/json'
                        }
                    })
                        .then(response => response.json())
                        .then(response => {

                            if (response.success) {

                                Swal.fire({
                                    icon: 'success',
                                    title: 'موفق',
                                    text: 'پیام با موفقیت حذف شد.',
                                    timer: 1500,
                                    showConfirmButton: false
                                })
                                $(`.item-record${id}`).remove();

                            } else {

                                Swal.fire({
                                    icon: 'error',
                                    title: 'خطا',
                                    text:  'عملیات با خطا مواجه شد.'
                                });

                            }

                        })
                        .catch(() => {

                            Swal.fire({
                                icon: 'error',
                                title: 'خطا',
                                text: 'ارتباط با سرور برقرار نشد.'
                            });

                        });

                }

            });

        });

    });

});

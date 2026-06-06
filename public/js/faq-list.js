const csrf = document.querySelector('meta[name="csrf-token"]').content;

$(document).on('click', '.delete-message', function () {

    let id = $(this).data('id');

    Swal.fire({
        title: 'حذف سوال',
        text: 'آیا از حذف سوال مطمئن هستید؟',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'بله',
        cancelButtonText: 'خیر',
    })

        .then((result) => {

            if (result.isConfirmed) {

                fetch(`/faq_delete/${id}`, {

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
                                text: "سوال با موفقیت حذف شد",
                                icon: "success",
                                confirmButtonText: "بستن",
                            });

                            $(`.item-record${id}`).remove();

                        } else {

                            Swal.fire({
                                title: "خطا!",
                                text: "حذف سوال انجام نشد",
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

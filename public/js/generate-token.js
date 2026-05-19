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


$('#storeForm').on('submit', function (e) {
    e.preventDefault()

    fetch(`/create_store`,{
        method: 'post',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrf,
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            store_name: $('#store_name').val(),
            user_id: $('#user_id').val(),
            link: $('#link').val(),
            phone: $('#phone').val(),
            province: $('#province').val(),
            city: $('#city').val(),
            location: $('#location').val(),
            code_posty: $('#code_posty').val(),
            latitude: $('#latitude').val(),
            longitude: $('#longitude').val(),
            about: $('#about').val(),
            token: $('#token').val(),
        }),
    })
        .then(res => res.json())
        .then(data => {

            if (data.errors) {
                showBackendErrors(data.errors);
            }

            if (data.redirect) {
                window.location.href = data.redirect;
            }
        })
        .catch(err => console.log(err));
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




const csrf = document.querySelector('meta[name="csrf-token"]').content;


$('#roleForm').on('submit', function (e) {
    e.preventDefault()

    // گرفتن دکمه سابمیت
    const submitBtn = this.querySelector('button[type="submit"]');
    const permission = $('#permission').val()

    // فعال کردن حالت لودینگ
    submitBtn.classList.add('btn-loading');
    submitBtn.disabled = true;

    fetch(`/role-create`,{
        method: 'post',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrf,
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            name: $('#name').val(),
            permissions: permission

        }),
    })
        .then(res => res.json())
        .then(data => {
            if (data.errors) {
                showBackendErrors(data.errors);
                // برگردوندن دکمه به حالت عادی در صورت خطا
                submitBtn.classList.remove('btn-loading');
                submitBtn.disabled = false;
            }

            if (data.redirect) {
                // اگه ریدایرکت داره، نیازی به برگردوندن دکمه نیست
                window.location.href = data.redirect;
            }
        })
        .catch(err => {
            console.log(err);
            alert('خطا در ارتباط با سرور');
            // برگردوندن دکمه به حالت عادی در صورت خطا
            submitBtn.classList.remove('btn-loading');
            submitBtn.disabled = false;
        });
});


var select2 = $('.select2');
if (select2.length) {
    select2.each(function () {
        var $this = $(this);
        $this.wrap('<div class="position-relative"></div>').select2({
            dropdownParent: $this.parent(),
            placeholder: $this.data('placeholder') // for dynamic placeholder
        });
    });
}

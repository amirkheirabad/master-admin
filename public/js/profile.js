const csrf = document.querySelector('meta[name="csrf-token"]').content;

$('#profilePasswordForm').on('submit', function (e) {
    e.preventDefault();

    const submitBtn = this.querySelector('button[type="submit"]');
    submitBtn.classList.add('btn-loading');
    submitBtn.disabled = true;

    fetch('/profile/password', {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrf,
            'Accept': 'application/json',
        },
        body: JSON.stringify({
            password: $('#password').val(),
            password_confirmation: $('#password_confirmation').val(),
        }),
    })
        .then(res => res.json().then(data => ({ ok: res.ok, data })))
        .then(({ ok, data }) => {
            if (!ok && data.errors) {
                showBackendErrors(data.errors);
                submitBtn.classList.remove('btn-loading');
                submitBtn.disabled = false;
                return;
            }

            if (data.redirect) {
                Swal.fire({
                    title: 'ذخیره شد',
                    text: 'رمز عبور با موفقیت تغییر کرد',
                    icon: 'success',
                    confirmButtonText: 'بستن',
                }).then(() => {
                    window.location.href = data.redirect;
                });
            }
        })
        .catch(() => {
            Swal.fire({
                title: 'خطا',
                text: 'مشکلی در ارتباط با سرور پیش آمد',
                icon: 'error',
                confirmButtonText: 'بستن',
            });
            submitBtn.classList.remove('btn-loading');
            submitBtn.disabled = false;
        });
});


const password = document.getElementById('password');
const togglePassword = document.getElementById('togglePassword');
const eyeIcon = document.getElementById('eyeIcon');

togglePassword.addEventListener('click', function () {
    const isHidden = password.type === 'password';

    password.type = isHidden ? 'text' : 'password';

    eyeIcon.classList.toggle('fa-eye');
    eyeIcon.classList.toggle('fa-eye-slash');
});


const password2 = document.getElementById('password_confirmation');
const togglePassword2 = document.getElementById('togglePassword2');
const eyeIcon2 = document.getElementById('eyeIcon2');

togglePassword2.addEventListener('click', function () {
    const isHidden = password2.type === 'password';

    password2.type = isHidden ? 'text' : 'password';

    eyeIcon2.classList.toggle('fa-eye');
    eyeIcon2.classList.toggle('fa-eye-slash');
});

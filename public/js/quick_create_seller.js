$(document).on('click', '#btn-quick-create-user', function () {
    $('#quick_name, #quick_mobile, #quick_password').val('');
    $('#quick_name_error, #quick_mobile_error, #quick_password_error').text('');
    $('#quick_general_error').addClass('d-none').text('');
    $('#quickCreateUserModal').modal('show');
});

$(document).on('click', '#btn-submit-quick-user', function () {
    const name     = $('#quick_name').val().trim();
    const mobile   = $('#quick_mobile').val().trim();
    const password = $('#quick_password').val().trim();

    $('#quick_name_error, #quick_mobile_error, #quick_password_error').text('');
    $('#quick_general_error').addClass('d-none').text('');

    let hasError = false;
    if (!name) {
        $('#quick_name_error').text('نام الزامی است');
        hasError = true;
    }
    if (!mobile) {
        $('#quick_mobile_error').text('شماره موبایل الزامی است');
        hasError = true;
    }
    if (!password) {
        $('#quick_password_error').text('رمز عبور الزامی است');
        hasError = true;
    }
    if (hasError) return;

    $('#quick-user-btn-text').text('در حال ساخت...');
    $('#quick-user-spinner').removeClass('d-none');
    $('#btn-submit-quick-user').prop('disabled', true);

    $.ajax({
        url: quickCreateSellerConfig.route,
        method: 'POST',
        data: {
            _token: quickCreateSellerConfig.token,
            name: name,
            mobile: mobile,
            password: password,
        },
        success: function (response) {
            if (response.success) {
                const newOption = new Option(response.user.name, response.user.id, true, true);
                $('#user_id').append(newOption).trigger('change');
                $('#quickCreateUserModal').modal('hide');
            }
        },
        error: function (xhr) {
            const errors = xhr.responseJSON?.errors;
            if (errors) {
                if (errors.name)     $('#quick_name_error').text(errors.name[0]);
                if (errors.mobile)   $('#quick_mobile_error').text(errors.mobile[0]);
                if (errors.password) $('#quick_password_error').text(errors.password[0]);
            } else {
                $('#quick_general_error')
                    .removeClass('d-none')
                    .text(xhr.responseJSON?.message ?? 'خطایی رخ داد');
            }
        },
        complete: function () {
            $('#quick-user-btn-text').text('ساخت کاربر');
            $('#quick-user-spinner').addClass('d-none');
            $('#btn-submit-quick-user').prop('disabled', false);
        }
    });
});


const password = document.getElementById('quick_password');
const togglePassword = document.getElementById('togglePassword');
const eyeIcon = document.getElementById('eyeIcon');

togglePassword.addEventListener('click', function () {
    const isHidden = password.type === 'password';

    password.type = isHidden ? 'text' : 'password';

    eyeIcon.classList.toggle('fa-eye');
    eyeIcon.classList.toggle('fa-eye-slash');
});

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
        $('#selectStores').select2();
    });
    $(document).ready(function() {
        $('#status').select2();
    });




let currentId = null;

$(document).on('click', '[data-target="#myModal"]', function () {
    currentId = $(this).data('id');
});



const csrf = document.querySelector('meta[name="csrf-token"]').content;

    async function submitSmsPanel() {
        const errorBox = document.getElementById('submitError');
        const submitButton = document.getElementById('submitSmsPanelButton');

        const adminMessage = document.getElementById('adminMessage').value;

        const selectedStatus = document.querySelector(
            'input[name="iCheck"]:checked'
        )?.value;

        errorBox.style.display = 'none';
        errorBox.textContent = '';

        if (!selectedStatus) {
            showSubmitError('لطفاً وضعیت درخواست را انتخاب کنید.');
            return;
        }

        submitButton.disabled = true;
        submitButton.textContent = 'در حال ثبت...';

        try {
            const response = await fetch(`/sms-store/${currentId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrf,
                },
                body: JSON.stringify({
                    status: selectedStatus,
                    admin_message: adminMessage,
                }),
            });

            const data = await parseJsonResponse(response);

            if (!response.ok || data.success !== true) {
                throw new Error(
                    data.message || 'خطایی هنگام ثبت وضعیت رخ داد.'
                );
            }

            $('#myModal').modal('hide');
            location.reload();
        } catch (error) {
            showSubmitError(error.message);
        } finally {
            submitButton.disabled = false;
            submitButton.textContent = 'ذخیره';
        }
    }

    async function parseJsonResponse(response) {
        try {
            return await response.json();
        } catch {
            return {
                success: false,
                message: response.ok
                    ? 'پاسخ نامعتبر از سرور دریافت شد.'
                    : 'ارتباط با فروشگاه مقصد ناموفق بود.',
            };
        }
    }

    function showSubmitError(message) {
        const errorBox = document.getElementById('submitError');

        errorBox.textContent = message;
        errorBox.style.display = 'block';
    }

    $(document).on('click', '[data-target="#myModal"]', function () {
        currentId = $(this).data('id');

        $.ajax({
            url: `/getSms/${currentId}`,
            type: 'GET',
            success: function(data) {
                $('#storeMessage').val(data.store_message);
                $('#adminMessage').val(data.admin_message);
                $('#store_name').text(data.store_name);
                $('#campaign_name').text(data.campaign_name);
                $("input[name='iCheck'][value='" + data.status + "']").prop("checked", true);
                $('#myModal').modal('show');
            },
            error: function(err) {
                console.log('AJAX Error:', err);
            }
        });
    });

    let currentId2 = null;

    $(document).on('click', '[data-target="#myModal2"]', function () {
        currentId2 = $(this).data('id');

        $.ajax({
            url: `/getSms/${currentId2}`,
            type: 'GET',
            success: function(data) {
                $('#myModal2 #adminMessage').val(data.admin_message);

                let statusHtml = '';
                if (data.status == 0) {
                    statusHtml = '<span class="text-warning">در حال بررسی</span>';
                } else if (data.status == 1) {
                    statusHtml = '<span class="text-danger">رد شده</span>';
                } else if (data.status == 2) {
                    statusHtml = '<span class="text-success">تایید شده</span>';
                } else {
                    statusHtml = '<span class="text-muted">نامشخص</span>';
                }

                $('#myModal2 .modal-title').html(statusHtml);

                $('#myModal2').modal('show');
            },
            error: function(err) {
                console.log('AJAX Error:', err);
            }
        });
    });









    document.getElementById('apiTestForm').addEventListener('submit', function(e){
        e.preventDefault();

        fetch('/api/store-requests', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrf,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                token: document.getElementById('token').value,
                store_message: document.getElementById('store_message').value
            })
        })
            .then(res => res.json())
            .then(data => {
                document.getElementById('result').textContent = JSON.stringify(data, null, 2);
            })
            .catch(err => {
                document.getElementById('result').textContent = JSON.stringify(err, null, 2);
            });
    });


    const select2Elements = $('.generic-select2');
    if (select2Elements.length) {
        select2Elements.each(function () {
            let $this = $(this);
            const allowSearchData = $this.data('allow-search');
            const minSearch = (allowSearchData !== undefined) ? null : -1;

            $this.wrap('<div class="position-relative"></div>').select2({
                placeholder: $this.attr('placeholder') || "انتخاب گزینه",
                dropdownParent: $this.parent(),
                minimumResultsForSearch: minSearch,
                language: {
                    noResults: () => 'موردی یافت نشد'
                }
            });
        });
    }










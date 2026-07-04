function submitCategory() {

    let nameInput = document.getElementById('title');
    let errorBox = document.getElementById('error_name');

    if (nameInput.value.trim() === '') {
        errorBox.innerText = "نام چک لیست الزامی است";
        return;
    } else {
        errorBox.innerText = "";
    }

    errorBox.innerText = "";

    document.getElementById('checkListForm').submit();
}

const csrf = document.querySelector('meta[name="csrf-token"]').content;

$(document).on('click','.delete-chek_list', function (e) {
    e.preventDefault();

    let id = $(this).data('id');

    Swal.fire({
        title:'حذف چک لیست',
        text: 'آیا از حذف چک لیست مطمئن هستید؟',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'بله',
        cancelButtonText: 'خیر',
    }).then((result) => {
        if (result.isConfirmed) {

            fetch(`/delete_check_list/${id}`, {
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
                            text: "چک لیست با موفقیت حذف شد",
                            icon: "success",
                            showCancelButton: false,
                            confirmButtonText: "بستن",
                        })
                        $(`.item-record${id}`).remove()
                    }
                    else {
                        Swal.fire({
                            title: "خطا!",
                            text: "حذف چک لیست انجام نشد",
                            icon: "error",
                            showCancelButton: false,
                            confirmButtonText: "بستن",
                        });
                    }
                })
        }
    })
})


$(document).on('click', '.editCategoryBtn', function (e) {
    e.preventDefault();

    const id = $(this).data('id');

    fetch(`/show_check_list/${id}`)
        .then(res => res.json())
        .then(data => {
            $('#edit_category_id').val(data.id);
            $('#edit_category_name').val(data.title);

            // پاک کردن انتخاب قبلی
            $('#myModal2 input[name="active"]').prop('checked', false);

            // انتخاب رادیوی مناسب بر اساس مقدار دریافتی
            $(`#myModal2 input[name="active"][value="${data.active}"]`).prop('checked', true);

            // نمایش مدال
            $('#myModal2').modal('show');
        })
        .catch(err => console.log(err));
});

function submitCategory2() {
    const id = $('#edit_category_id').val();
    let nameInput2 = document.getElementById('edit_category_name');
    let errorBox2 = document.getElementById('error_name2');

    if (nameInput2.value.trim() === '') {
        errorBox2.innerText = "نام دسته بندی الزامی است";
        return;
    } else {
        errorBox2.innerText = "";
    }

    // گرفتن مقدار رادیو انتخاب شده
    const selectedActive = $('#myModal2 input[name="active"]:checked').val();

    fetch(`/update_check_lists/${id}`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrf,
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            title: $('#edit_category_name').val(),
            active: parseInt(selectedActive)  // تبدیل به عدد
        })
    })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                $('#myModal2').modal('hide');
                location.reload();
            } else {
                if (data.errors && data.errors.name) {
                    $('#error_name2').text(data.errors.name[0]);
                }
            }
        })
        .catch(err => console.log(err));
}

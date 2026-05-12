<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>صورت حساب</title>
{{--    @vite(['resources/css/app.css', 'resources/js/app.js'])--}}

    <link rel="stylesheet" href="{{ asset('css/factor.css') }}">
</head>
<body class="vazir">

<div class="invoice">

    <div class="invoice-header">
        <div>iBrandshop</div>
        <div class="title">صورت حساب</div>
        <div>تاریخ فاکتور: 1404/02/01</div>
    </div>

    <div class="box">
        <div class="box-title">مشخصات فروشنده</div>
        <div class="box-body">
            فروشگاه: <span class="title"><span class="title">آی برند شاپ</span></span> به نمایندگی: <span class="title">مریم خیرآبادی</span> با کد مالیاتی: 3427813129  و کد ملی: 2110167637  به آدرس: گلستان,گرگان,بلوار کاشانی,25 به شماره تماس: 01732120151
        </div>
    </div>

    <div class="box">
        <div class="box-title">مشخصات خریدار</div>
        <div class="box-body">
            فروشگاه: {{ $factor->store->store_name ?? '' }}
            به نمایندگی: {{ $factor->name ?? '' }}
            به کد ملی: {{ $factor->national_kod ?? '' }}
            به شماره موبایل: {{ $factor->phone ?? '' }}
        </div>
    </div>

    <table class="invoice-table">
        <div class="box">
            <div class="box-title">مشخصات کالای مورد معامله</div>
        </div>

        <thead>
        <tr>
            <th>ردیف</th>
            <th>شرح کالا</th>
            <th>مبلغ کل همراه با مالیات و عوارض</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>1</td>
            <td>ساپورت</td>
            <td id="price-display">{{ $factor->price }} ریال</td>
        </tr>
        </tbody>
        <tfoot>
        <tr>
            <td colspan="2">جمع کل</td>
            <td class="total" id="price-display2">{{ $factor->price }} ریال</td>
        </tr>
        </tfoot>
    </table>
</div>

</body>
<script>
    function formatPrice(price) {
        let numericPrice = String(price).replace(/[^0-9]/g, '');

        if (numericPrice === '') {
            return '';
        }

        return numericPrice.replace(/\B(?=(\d{3})+(?!\d))/g, ',');
    }

    function formatPriceOnInput(event) {
        let input = event.target;
        input.value = formatPrice(input.value);
    }

    function formatDisplayPrice(elementId) {
        let element = document.getElementById(elementId);
        if (element) {
            element.textContent = formatPrice(element.textContent);
        }
    }

    // فرمت کردن فیلد ورودی قیمت
    let priceInput = document.getElementById('price');
    if (priceInput) {
        priceInput.addEventListener('input', formatPriceOnInput);
        // فرمت اولیه فیلد ورودی در صورت وجود مقدار اولیه
        formatPriceOnInput({ target: priceInput });
    }

    // فرمت کردن سلول جدول قیمت (با فرض تغییر آیدی به price-display)
    document.addEventListener('DOMContentLoaded', function() {
        formatDisplayPrice('price-display');
        formatDisplayPrice('price-display2');
    });
</script>

</html>

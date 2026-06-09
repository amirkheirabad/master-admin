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
        <div class="fa-number">تاریخ فاکتور: {{ Verta($factor->factor_date)->format(' %d %B  %Y') }}</div>
    </div>

    <div class="box">
        <div class="box-title">مشخصات فروشنده</div>
        <div class="box-body">
            فروشگاه: <span class="title"><span class="title">آی برند شاپ</span></span> به نمایندگی: <span class="title">مریم خیرآبادی</span> با کد مالیاتی: ۳۴۲۷۸۱۳۱۲۹  و کد ملی: ۲۱۱۰۱۶۷۶۳۷  به آدرس: گلستان,گرگان,بلوار کاشانی,۲۵ به شماره تماس: ۰۱۷۳۲۱۲۰۱۵۱
        </div>
    </div>

    @php
        $buyerStoreName = $factor->store?->store_name ?? '';
        $buyerName = $factor->name
            ?? $factor->store?->user?->name
            ?? $factor->customer?->name
            ?? '';
        $buyerNationalKod = $factor->national_kod
            ?? $factor->store?->user?->national_kod
            ?? $factor->customer?->national_kod
            ?? '';
        $buyerPhone = $factor->phone
            ?? $factor->store?->user?->mobile
            ?? $factor->store?->phone
            ?? $factor->customer?->mobile
            ?? '';
    @endphp

    <div class="box">
        <div class="box-title">مشخصات خریدار</div>
        <div class="box-body fa-number">
            @if($buyerStoreName)
                فروشگاه: {{ $buyerStoreName }}
            @endif
            به نمایندگی: {{ $buyerName }}
            به کد ملی: {{ $buyerNationalKod }}
            به شماره موبایل: {{ $buyerPhone }}
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
            <td class="fa-number">{{ number_format($factor->price) }} ریال</td>
        </tr>
        </tbody>
        <tfoot>
        <tr>
            <td colspan="2">جمع کل</td>
            <td class="total fa-number">{{ number_format($factor->price) }} ریال</td>
        </tr>
        </tfoot>
    </table>
</div>

</body>
<script>
    document.querySelectorAll('.fa-number').forEach(el => {
        el.textContent = el.textContent.replace(/[0-9]/g, d => '۰۱۲۳۴۵۶۷۸۹'[d]);
    });
</script>
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

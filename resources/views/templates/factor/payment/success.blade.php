<!DOCTYPE html>
<html lang="fa" dir="rtl">

<head>
    <meta charset="UTF-8">
    <title>پرداخت موفق</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>

<body class="bg-light overflow_hidden">

<div class="container py-5">

    <div class="row justify-content-center">

        <div class="col-lg-6">

            <div class="card shadow border-0 rounded-4">

                <div class="card-body text-center p-5">

                    <div class="mb-4">
                        <div class="rounded-circle bg-success-subtle d-inline-flex align-items-center justify-content-center"
                             style="width:90px;height:90px;">
                        </div>
                    </div>

                    <img src="{{asset('images/payment-success.png')}}" alt="" style="width:190px;height:190px;">


                    <h2 class="fw-bold text-success">
                        پرداخت با موفقیت انجام شد
                    </h2>

                    <p class="text-muted mt-3">
                        از پرداخت شما سپاسگزاریم.
                        تراکنش با موفقیت ثبت گردید.
                    </p>

                    <hr>



                </div>

            </div>

        </div>

    </div>

</div>

</body>
</html>

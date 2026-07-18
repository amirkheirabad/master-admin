<!DOCTYPE html>
<html lang="fa" dir="rtl">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>پرداخت ناموفق</title>

        @vite(['resources/css/app.css', 'resources/js/app.js'])


</head>

<body class="bg-light overflow_hidden">

<div class="container py-5">

    <div class="row justify-content-center">

        <div class="col-lg-6">

            <div class="card shadow border-0 rounded-4">

                <div class="card-body text-center p-5">

                    <div class="mb-4">

                        <div class="rounded-circle bg-danger-subtle d-inline-flex align-items-center justify-content-center"
                             style="width:90px;height:90px;">

                            <i class="fa-solid fa-xmark text-danger fs-1"></i>

                        </div>

                        <img src="{{asset('images/payment-failed.png')}}" alt="" style="width:190px;height:190px;">


                    </div>

                    <h2 class="fw-bold text-danger">

                        پرداخت ناموفق بود

                    </h2>

                    <p class="text-muted mt-3">

                        متأسفانه پرداخت شما انجام نشد.

                    </p>

                    <div class="alert alert-danger mt-4">

                        در صورت کسر وجه، مبلغ حداکثر تا ۷۲ ساعت آینده توسط بانک به حساب شما بازخواهد گشت.

                    </div>

                    @isset($message)

                        <div class="alert alert-warning">

                            {{ $message }}

                        </div>

                    @endisset


                </div>

            </div>

        </div>

    </div>

</div>

</body>

</html>

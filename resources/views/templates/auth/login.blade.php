<!DOCTYPE html>
<html dir="rtl" lang="fa">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>ورود | پنل مدیریت</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Tahoma', 'Segoe UI', sans-serif;
            background: #f5f7fa;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-box {
            background: white;
            padding: 40px 35px;
            border-radius: 12px;
            width: 420px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
            border: 1px solid #e8eef2;
        }

        .login-box h3 {
            text-align: center;
            margin-bottom: 30px;
            color: #133c6d;
            font-size: 22px;
            font-weight: 600;
            letter-spacing: -0.3px;
        }

        .login-box label {
            display: block;
            margin-bottom: 8px;
            color: #4a5568;
            font-size: 13px;
            font-weight: 500;
        }

        .login-box input {
            width: 100%;
            padding: 12px 14px;
            border-radius: 8px;
            font-size: 14px;
            font-family: inherit;
            transition: all 0.2s ease;
            background: #fff;
        }

        .login-btn {
            width: 100%;
            padding: 12px;
            background: #133c6d;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 15px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease;
            margin-top: 5px;
        }

        .login-btn:hover {
            background: #0e2d52;
        }

        .error {
            background: #fee;
            color: #c00;
            padding: 10px 12px;
            border-radius: 8px;
            margin-bottom: 20px;
            text-align: center;
            font-size: 13px;
            border-right: 3px solid #c00;
        }
    </style>
</head>
<body>
    <div class="login-box">
        <h3>ورود به پنل مدیریت</h3>

        @if($errors->any())
            <div class="error">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <label>شماره موبایل</label>
            <input type="text" name="mobile" class="input-border-focus" style="height: 43px" value="{{ old('mobile') }}" required autofocus>

            <label class="mt-4">رمز عبور</label>
            <div class="search-container mb-3">
                <input type="password" class="search-input" name="password" id="password"  required>
                <button type="button" id="togglePassword" class="search-button p-3">
                    <i class="fa fa-eye-slash" id="eyeIcon"></i>
                </button>
            </div>

            <button type="submit" class="login-btn mr-0">ورود</button>
        </form>
    </div>
<script>
    const password = document.getElementById('password');
    const togglePassword = document.getElementById('togglePassword');
    const eyeIcon = document.getElementById('eyeIcon');

    togglePassword.addEventListener('click', function () {
        const isHidden = password.type === 'password';

        password.type = isHidden ? 'text' : 'password';

        eyeIcon.classList.toggle('fa-eye');
        eyeIcon.classList.toggle('fa-eye-slash');
    });
</script>
</body>
</html>

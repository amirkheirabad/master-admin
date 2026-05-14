<!DOCTYPE html>
<html dir="rtl" lang="fa">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
            margin-bottom: 20px;
            border: 1px solid #dce4e8;
            border-radius: 8px;
            font-size: 14px;
            font-family: inherit;
            transition: all 0.2s ease;
            background: #fff;
        }

        .login-box input:focus {
            outline: none;
            border-color: #133c6d;
            box-shadow: 0 0 0 3px rgba(19, 60, 109, 0.1);
        }

        .login-box button {
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

        .login-box button:hover {
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
            <input type="text" name="mobile"  value="{{ old('mobile') }}" required autofocus>
            
            <label>رمز عبور</label> 
            <input type="password" name="password"  required>
            
            <button type="submit">ورود</button>
        </form>
    </div>
</body>
</html>
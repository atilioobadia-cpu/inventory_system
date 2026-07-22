<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Sign In - Mtokoma</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif; background: #f7f8f8; min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 20px; -webkit-font-smoothing: antialiased; }
        .login-card { background: #ffffff; border: 1px solid #e2e5e9; border-radius: 8px; padding: 40px 32px 32px; width: 100%; max-width: 360px; box-shadow: 0 1px 3px rgba(0,0,0,0.04); }
        .login-logo { text-align: center; margin-bottom: 24px; }
        .login-logo-icon { width: 36px; height: 36px; background: #1f2937; border-radius: 8px; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 12px; }
        .login-logo-icon svg { width: 18px; height: 18px; color: #fff; }
        .login-title { font-size: 16px; font-weight: 600; color: #1f2937; margin-bottom: 2px; }
        .login-subtitle { font-size: 12px; color: #6b7280; }
        .form-group { margin-bottom: 16px; }
        .form-group label { display: block; font-size: 12px; font-weight: 500; color: #374151; margin-bottom: 6px; }
        .form-group input { width: 100%; padding: 8px 12px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 14px; color: #1f2937; background: #fff; outline: none; transition: border-color 0.15s; }
        .form-group input:focus { border-color: #1f2937; box-shadow: 0 0 0 2px rgba(31,41,55,0.08); }
        .form-group input::placeholder { color: #9ca3af; }
        .form-row { display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px; }
        .form-row label { display: flex; align-items: center; gap: 6px; font-size: 12px; color: #6b7280; cursor: pointer; }
        .form-row input[type="checkbox"] { width: 14px; height: 14px; border-radius: 3px; border: 1px solid #d1d5db; }
        .form-row a { font-size: 12px; color: #1f2937; text-decoration: none; }
        .form-row a:hover { text-decoration: underline; }
        .btn-login { width: 100%; padding: 8px 16px; background: #1f2937; color: #fff; border: none; border-radius: 6px; font-size: 13px; font-weight: 500; cursor: pointer; transition: background 0.15s; }
        .btn-login:hover { background: #111827; }
        .error-box { background: #fef2f2; border: 1px solid #fecaca; border-radius: 6px; padding: 10px 12px; margin-bottom: 16px; }
        .error-box p { font-size: 12px; color: #dc2626; }
        .status-box { background: #ecfdf5; border: 1px solid #a7f3d0; border-radius: 6px; padding: 10px 12px; margin-bottom: 16px; }
        .status-box p { font-size: 12px; color: #059669; }
        .login-footer { text-align: center; margin-top: 20px; font-size: 11px; color: #9ca3af; }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="login-logo">
            <div class="login-logo-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10l2-1h2m10 1l2-1V8a1 1 0 00-1-1h-2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M16 6h2a2 2 0 012 2v4" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
            <p class="login-title">Mtokoma Motorcycle Parts</p>
            <p class="login-subtitle">Inventory Management System</p>
        </div>

        @if($errors->any())
        <div class="error-box">
            @foreach($errors->all() as $error)
            <p>{{ $error }}</p>
            @endforeach
        </div>
        @endif

        @if(session('status'))
        <div class="status-box">
            <p>{{ session('status') }}</p>
        </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="form-group">
                <label for="email">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus placeholder="johndoe@example.com">
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input id="password" type="password" name="password" required placeholder="Enter your password">
            </div>
            <div class="form-row">
                <label>
                    <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                    Remember me
                </label>
                @if(Route::has('password.request'))
                <a href="{{ route('password.request') }}">Forgot Password?</a>
                @endif
            </div>
            <button type="submit" class="btn-login">Log In</button>
        </form>

        <p class="login-footer">&copy; 2026 Mtokoma Motorcycle Parts</p>
    </div>
</body>
</html>

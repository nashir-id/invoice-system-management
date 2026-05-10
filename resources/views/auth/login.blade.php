<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login NASHIR.ID</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: Roboto, sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #dce6f0;
        }

        .auth-card {
            display: flex;
            width: 860px;
            min-height: 480px;
            border-radius: 14px;
            overflow: hidden;
            box-shadow: 0 24px 64px rgba(0,0,0,0.2);
        }

        /* ══ LEFT — biru ══ */
        .left {
            width: 360px;
            min-width: 360px;
            background: #66A6FF;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 48px 36px;
            position: relative;
            overflow: hidden;
        }

        .left-inner {
            position: relative; z-index: 1;
            display: flex; flex-direction: column;
            align-items: center; text-align: center;
            gap: 0;
        }

        /* ── Logo ── */
        .logo-ring {
            width: 136px; height: 136px; border-radius: 50%;
            background: rgba(255,255,255,0.15);
            border: 2.5px solid rgba(255,255,255,0.35);
            display: flex; align-items: center; justify-content: center;
            margin-bottom: 26px;
            position: relative;
        }

        /* Titik kecil memutar di orbit */
        .orbit-dot {
            position: absolute;
            width: 10px; height: 10px; border-radius: 50%;
            background: #fff;
            top: 10px; right: 10px;
        }
        .orbit-dot2 {
            position: absolute;
            width: 7px; height: 7px; border-radius: 50%;
            background: rgba(255,255,255,0.6);
            bottom: 14px; left: 14px;
        }

        .left-title {
            font-size: 28px; font-weight: 700; color: #fff;
            line-height: 1.25; margin-bottom: 12px;
        }
        .left-brand {
            font-size: 12px; font-weight: 600; color: rgba(255,255,255,0.6);
            letter-spacing: 0.12em; text-transform: uppercase;
        }

        /* ══ RIGHT — putih ══ */
        .right {
            flex: 1;
            background: #fff;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 40px 44px;
        }

        .top-hint {
            font-size: 13px; color: #999;
            margin-bottom: 20px; text-align: right;
        }
        .top-hint a {
            color: #2e90d1; font-weight: 700; text-decoration: none;
        }
        .top-hint a:hover { text-decoration: underline; }

        /* Style Baru untuk Judul Form */
        .form-title { font-size: 22px; font-weight: 700; color: #1a1a2e; margin-bottom: 4px; }
        .form-sub { font-size: 14px; color: #999; margin-bottom: 20px; }

        /* Alerts */
        .alert { border-radius: 8px; padding: 10px 14px; font-size: 13px; margin-bottom: 16px; line-height: 1.6; }
        .alert-err { background: #fff5f5; border: 1px solid #fbb; color: #c53030; }
        .alert-ok  { background: #f0fff4; border: 1px solid #9ae6b4; color: #276749; }

        /* Field */
        .field { margin-bottom: 14px; }
        .input-wrap { position: relative; }
        .input-icon {
            position: absolute; left: 13px; top: 50%;
            transform: translateY(-50%);
            color: #bbb; width: 16px; height: 16px; pointer-events: none;
        }
        input[type="email"],
        input[type="password"],
        input[type="text"] {
            width: 100%; height: 48px;
            border: 1.5px solid #dde3ec; border-radius: 8px;
            padding: 0 42px 0 42px;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 14px; color: #1a1a2e; background: #f7f9fc;
            outline: none;
            transition: border-color .15s, box-shadow .15s, background .15s;
        }
        input:focus {
            border-color: #2e90d1; background: #fff;
            box-shadow: 0 0 0 3px rgba(46,144,209,.13);
        }
        input.err { border-color: #fc8181; }
        .field-err { font-size: 12px; color: #e53e3e; margin-top: 4px; }

        .eye-btn {
            position: absolute; right: 12px; top: 50%;
            transform: translateY(-50%);
            background: none; border: none; cursor: pointer;
            color: #bbb; display: flex; align-items: center; padding: 0;
        }
        .eye-btn:hover { color: #888; }

        /* Options row */
        .opts {
            display: flex; align-items: center;
            justify-content: space-between;
            margin-bottom: 22px;
        }
        .chk-label {
            display: flex; align-items: center; gap: 7px;
            font-size: 13px; color: #666; cursor: pointer;
        }
        input[type="checkbox"] {
            width: 15px; height: 15px; accent-color: #2e90d1; cursor: pointer;
        }
        .forgot { font-size: 13px; color: #aaa; text-decoration: none; }
        .forgot:hover { color: #2e90d1; }

        /* Button */
        .btn-login {
            width: 100%; height: 48px;
            background: #2e90d1; color: #fff; border: none; border-radius: 8px;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 15px; font-weight: 700;
            cursor: pointer; letter-spacing: 0.08em;
            transition: background .15s, transform .1s;
        }
        .btn-login:hover { background: #1a6ba8; }
        .btn-login:active { transform: scale(0.98); }

        @media (max-width: 680px) {
            .auth-card { flex-direction: column; width: 94%; }
            .left { width: 100%; min-width: 0; padding: 32px 24px; }
            .right { padding: 28px 24px; }
        }
    </style>
</head>
<body>

<div class="auth-card">

    {{-- ══ LEFT ══ --}}
    <div class="left">
        <div class="left-inner">
                <img src="{{ asset('image/logo.png') }}" alt="Logo" style="width: 160px; height: 160px; position: relative; top: -5px; object-fit: contain;">
            <div class="left-title">Login to your<br>Account</div>
            <div class="left-brand">NASHIR.ID • Invoice System</div>
        </div>
    </div>

    {{-- ══ RIGHT ══ --}}
    <div class="right">
        <div class="top-hint">
            Don't have an account? <a href="{{ route('register') }}">Register Now</a>
        </div>
        <div class="form-title">Selamat Datang</div>
        <div class="form-sub">Masukkan email dan password Anda untuk masuk.</div>

        <!-- @if (session('status'))
            <div class="alert alert-ok">{{ session('status') }}</div>
        @endif -->

        <!-- @if ($errors->any())
            <div class="alert alert-err">
                @foreach ($errors->all() as $e)<div>{{ $e }}</div>@endforeach
            </div>
        @endif -->

        <form method="POST" action="{{ route('login') }}">
            @csrf

            {{-- Email --}}
            <div class="field">
                <div class="input-wrap">
                    <svg class="input-icon" viewBox="0 0 16 16" fill="none">
                        <rect x="1" y="3" width="14" height="10" rx="2" stroke="currentColor" stroke-width="1.3"/>
                        <path d="M1 5l7 5 7-5" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/>
                    </svg>
                    <input type="email" name="email" value="{{ old('email') }}"
                        placeholder="Email Address" autocomplete="email" autofocus
                        class="{{ $errors->has('email') ? 'err' : '' }}">
                </div>
                @error('email')<div class="field-err">{{ $message }}</div>@enderror
            </div>

            {{-- Password --}}
            <div class="field">
                <div class="input-wrap">
                    <svg class="input-icon" viewBox="0 0 16 16" fill="none">
                        <rect x="2" y="7" width="12" height="8" rx="2" stroke="currentColor" stroke-width="1.3"/>
                        <path d="M5 7V5a3 3 0 016 0v2" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/>
                        <circle cx="8" cy="11" r="1.2" fill="currentColor"/>
                    </svg>
                    <input type="password" id="pw" name="password"
                        placeholder="Password" autocomplete="current-password"
                        class="{{ $errors->has('password') ? 'err' : '' }}">
                    <button type="button" class="eye-btn" onclick="togglePw()">
                        <svg id="eye" width="16" height="16" viewBox="0 0 16 16" fill="none">
                            <path d="M1 8s2.5-5 7-5 7 5 7 5-2.5 5-7 5-7-5-7-5z" stroke="currentColor" stroke-width="1.3"/>
                            <circle cx="8" cy="8" r="2" stroke="currentColor" stroke-width="1.3"/>
                        </svg>
                    </button>
                </div>
                @error('password')<div class="field-err">{{ $message }}</div>@enderror
            </div>

            <div class="opts">
                <label class="chk-label">
                    <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                    Remember me
                </label>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="forgot">Forgot your password?</a>
                @endif
            </div>

            <button type="submit" class="btn-login">LOGIN</button>
        </form>
    </div>
</div>

<script>
function togglePw() {
    const pw  = document.getElementById('pw');
    const eye = document.getElementById('eye');
    if (pw.type === 'password') {
        pw.type = 'text';
        eye.innerHTML = `<path d="M2 2l12 12M6.5 6.6A2 2 0 0010 10" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/><path d="M1 8s2-3.5 7-5M15 8s-2 3.5-7 5" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/>`;
    } else {
        pw.type = 'password';
        eye.innerHTML = `<path d="M1 8s2.5-5 7-5 7 5 7 5-2.5 5-7 5-7-5-7-5z" stroke="currentColor" stroke-width="1.3"/><circle cx="8" cy="8" r="2" stroke="currentColor" stroke-width="1.3"/>`;
    }
}
</script>
</body>
</html>
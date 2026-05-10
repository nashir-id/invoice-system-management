<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register NASHIR.ID</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    @php
        $brand = 'NASHIR.ID';
    @endphp

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
            min-height: 540px;
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

        .logo-ring {
            width: 136px; height: 136px; border-radius: 50%;
            background: rgba(255,255,255,0.15);
            border: 2.5px solid rgba(255,255,255,0.35);
            display: flex; align-items: center; justify-content: center;
            margin-bottom: 26px;
            position: relative;
        }

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
            margin-bottom: 25px; text-align: right;
        }
        .top-hint a {
            color: #2e90d1; font-weight: 700; text-decoration: none;
        }
        .top-hint a:hover { text-decoration: underline; }

        .form-title { font-size: 22px; font-weight: 700; color: #1a1a2e; margin-bottom: 4px; }
        .form-sub { font-size: 14px; color: #999; margin-bottom: 20px; }

        .alert { border-radius: 8px; padding: 10px 14px; font-size: 13px; margin-bottom: 16px; line-height: 1.6; }
        .alert-err { background: #fff5f5; border: 1px solid #fbb; color: #c53030; }

        .field { margin-bottom: 14px; }
        .input-wrap { position: relative; }
        .input-icon {
            position: absolute; left: 13px; top: 50%;
            transform: translateY(-50%);
            color: #bbb; width: 16px; height: 16px; pointer-events: none;
        }
        
        input[type="email"],
        input[type="password"],
        input[type="text"],
        select {
            width: 100%; height: 48px;
            border: 1.5px solid #dde3ec; border-radius: 8px;
            padding: 0 42px 0 42px;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 14px; color: #1a1a2e; background: #f7f9fc;
            outline: none;
            transition: border-color .15s, box-shadow .15s, background .15s;
        }

        select { appearance: none; cursor: pointer; }

        input:focus, select:focus {
            border-color: #2e90d1; background: #fff;
            box-shadow: 0 0 0 3px rgba(46,144,209,.13);
        }
        input.err, select.err { border-color: #fc8181; }

        .btn-register {
            width: 100%; height: 48px;
            background: #2e90d1; color: #fff; border: none; border-radius: 8px;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 15px; font-weight: 700;
            cursor: pointer; letter-spacing: 0.08em; margin-top: 10px;
            transition: background .15s, transform .1s;
        }
        .btn-register:hover { background: #1a6ba8; }
        .btn-register:active { transform: scale(0.98); }

        .field-row { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }

        @media (max-width: 680px) {
            .auth-card { flex-direction: column; width: 94%; }
            .left { width: 100%; min-width: 0; padding: 32px 24px; }
            .right { padding: 28px 24px; }
            .field-row { grid-template-columns: 1fr; gap: 0; }
        }
    </style>
</head>
<body>

<div class="auth-card">
    <div class="left">
        <div class="left-inner">
                <img src="{{ asset('image/logo.png') }}" alt="Logo" style="width: 160px; height: 160px; position: relative; top: -5px; object-fit: contain;">
            <div class="left-title">Create your<br>Account</div>
            <div class="left-brand">{{ $brand }} • Invoice System</div>
        </div>
    </div>

    <div class="right">
        <div class="top-hint">
            Already have an account? <a href="{{ route('login') }}">Login here</a>
        </div>
        <div class="form-title">Register Now</div>
        <div class="form-sub">Daftarkan anggota tim baru {{ $brand }}.</div>

        @if ($errors->any())
            <div class="alert alert-err">
                @foreach ($errors->all() as $e)<div>{{ $e }}</div>@endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div class="field-row">
                <div class="field">
                    <div class="input-wrap">
                        <svg class="input-icon" viewBox="0 0 16 16" fill="none">
                            <circle cx="8" cy="6" r="3" stroke="currentColor" stroke-width="1.3"/>
                            <path d="M2 14c0-3 2.7-4.5 6-4.5s6 1.5 6 4.5" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/>
                        </svg>
                        <input type="text" name="name" value="{{ old('name') }}" placeholder="Full Name" class="{{ $errors->has('name') ? 'err' : '' }}">
                    </div>
                </div>

                <div class="field">
                    <div class="input-wrap">
                        <svg class="input-icon" viewBox="0 0 16 16" fill="none">
                            <rect x="2" y="2" width="12" height="12" rx="2" stroke="currentColor" stroke-width="1.3"/>
                            <path d="M5 8h6M5 5.5h4" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/>
                        </svg>
                        <select name="role" class="{{ $errors->has('role') ? 'err' : '' }}">
                            <option value="">Select Role</option>
                            <option value="admin">Admin</option>
                            <option value="staff">Staff</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="field">
                <div class="input-wrap">
                    <svg class="input-icon" viewBox="0 0 16 16" fill="none">
                        <rect x="1" y="3" width="14" height="10" rx="2" stroke="currentColor" stroke-width="1.3"/>
                        <path d="M1 5l7 5 7-5" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/>
                    </svg>
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="Email Address" class="{{ $errors->has('email') ? 'err' : '' }}">
                </div>
            </div>

            <div class="field-row">
                <div class="field">
                    <div class="input-wrap">
                        <svg class="input-icon" viewBox="0 0 16 16" fill="none">
                            <rect x="2" y="7" width="12" height="8" rx="2" stroke="currentColor" stroke-width="1.3"/>
                            <path d="M5 7V5a3 3 0 016 0v2" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/>
                        </svg>
                        <input type="password" name="password" placeholder="Password" class="{{ $errors->has('password') ? 'err' : '' }}">
                    </div>
                </div>

                <div class="field">
                    <div class="input-wrap">
                        <svg class="input-icon" viewBox="0 0 16 16" fill="none">
                            <rect x="2" y="7" width="12" height="8" rx="2" stroke="currentColor" stroke-width="1.3"/>
                            <path d="M5 7V5a3 3 0 016 0v2" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/>
                        </svg>
                        <input type="password" name="password_confirmation" placeholder="Confirm Password">
                    </div>
                </div>
            </div>

            <button type="submit" class="btn-register">REGISTER</button>
        </form>
    </div>
</div>

</body>
</html>
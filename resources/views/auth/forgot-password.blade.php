<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password {{ config('brand.name', 'NASHIR.ID') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
            font-family: Roboto, Arial, sans-serif;
            background:
                radial-gradient(circle at 14% 18%, rgba(102, 166, 255, .28), transparent 28%),
                radial-gradient(circle at 86% 10%, rgba(34, 197, 94, .14), transparent 24%),
                linear-gradient(135deg, #dce6f0 0%, #f8fafc 58%, #eef6ff 100%);
        }
        a { color: inherit; text-decoration: none; }
        .auth-card {
            display: grid;
            grid-template-columns: 360px minmax(0, 1fr);
            width: min(900px, 100%);
            min-height: 500px;
            border-radius: 14px;
            overflow: hidden;
            background: #fff;
            box-shadow: 0 24px 64px rgba(15, 23, 42, .18);
        }
        .left {
            background: #66A6FF;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 44px 34px;
            text-align: center;
            color: #fff;
        }
        .logo {
            width: 148px;
            height: 148px;
            object-fit: contain;
            margin: 0 auto 24px;
            filter: drop-shadow(0 16px 24px rgba(30, 27, 75, .2));
        }
        .left-title {
            font-size: 27px;
            line-height: 1.25;
            font-weight: 800;
            margin-bottom: 12px;
        }
        .left-sub {
            color: rgba(255, 255, 255, .72);
            font-size: 12px;
            font-weight: 700;
            letter-spacing: .12em;
            text-transform: uppercase;
        }
        .right {
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 42px 46px;
        }
        .top-link {
            align-self: flex-end;
            margin-bottom: 26px;
            color: #64748b;
            font-size: 13px;
        }
        .top-link a {
            color: #2e90d1;
            font-weight: 800;
        }
        .form-title {
            color: #1a1a2e;
            font-size: 25px;
            font-weight: 800;
            margin-bottom: 8px;
        }
        .form-sub {
            max-width: 430px;
            color: #64748b;
            font-size: 14px;
            line-height: 1.7;
            margin-bottom: 22px;
        }
        .alert {
            border-radius: 8px;
            padding: 11px 14px;
            font-size: 13px;
            line-height: 1.55;
            margin-bottom: 16px;
        }
        .alert-ok {
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            color: #166534;
        }
        .alert-err {
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #b91c1c;
        }
        .field { margin-bottom: 18px; }
        .label {
            display: block;
            color: #374151;
            font-size: 12px;
            font-weight: 700;
            margin-bottom: 7px;
        }
        .input-wrap { position: relative; }
        .input-icon {
            position: absolute;
            left: 13px;
            top: 50%;
            width: 16px;
            height: 16px;
            color: #94a3b8;
            transform: translateY(-50%);
            pointer-events: none;
        }
        .input {
            width: 100%;
            height: 48px;
            border: 1.5px solid #dde3ec;
            border-radius: 8px;
            padding: 0 14px 0 42px;
            background: #f7f9fc;
            color: #1a1a2e;
            font-size: 14px;
            outline: none;
            transition: border-color .15s, box-shadow .15s, background .15s;
        }
        .input:focus {
            border-color: #2e90d1;
            background: #fff;
            box-shadow: 0 0 0 3px rgba(46, 144, 209, .13);
        }
        .input.err { border-color: #ef4444; }
        .field-err {
            margin-top: 6px;
            color: #dc2626;
            font-size: 12px;
        }
        .btn-submit {
            width: 100%;
            height: 48px;
            border: 0;
            border-radius: 8px;
            background: #2e90d1;
            color: #fff;
            font-size: 14px;
            font-weight: 800;
            letter-spacing: .06em;
            cursor: pointer;
            transition: background .15s, transform .1s;
        }
        .btn-submit:hover { background: #1a6ba8; }
        .btn-submit:active { transform: scale(.98); }
        .hint {
            margin-top: 18px;
            color: #94a3b8;
            font-size: 12px;
            line-height: 1.6;
        }
        @media (max-width: 720px) {
            body { padding: 14px; }
            .auth-card { grid-template-columns: 1fr; }
            .left { padding: 30px 24px; }
            .logo { width: 112px; height: 112px; margin-bottom: 16px; }
            .left-title { font-size: 22px; }
            .right { padding: 30px 24px; }
            .top-link { align-self: flex-start; }
        }
    </style>
</head>
<body>
    <div class="auth-card">
        <section class="left">
            <div>
                <img class="logo" src="{{ asset('image/logo.png') }}" alt="{{ config('brand.name', 'NASHIR.ID') }}">
                <div class="left-title">Reset akses<br>akun Anda</div>
                <div class="left-sub">{{ config('brand.name', 'NASHIR.ID') }} Invoice System</div>
            </div>
        </section>

        <section class="right">
            <div class="top-link">
                Sudah ingat password? <a href="{{ route('login') }}">Login</a>
            </div>

            <h1 class="form-title">Lupa Password?</h1>
            <p class="form-sub">
                Masukkan email akun Anda. Sistem akan mengirimkan link reset password supaya Anda bisa membuat password baru.
            </p>

            @if (session('status'))
                <div class="alert alert-ok">{{ session('status') }}</div>
            @endif

            @if ($errors->any())
                <div class="alert alert-err">
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}">
                @csrf

                <div class="field">
                    <label class="label" for="email">Email</label>
                    <div class="input-wrap">
                        <svg class="input-icon" viewBox="0 0 16 16" fill="none">
                            <rect x="1" y="3" width="14" height="10" rx="2" stroke="currentColor" stroke-width="1.3"/>
                            <path d="M1 5l7 5 7-5" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/>
                        </svg>
                        <input
                            id="email"
                            class="input {{ $errors->has('email') ? 'err' : '' }}"
                            type="email"
                            name="email"
                            value="{{ old('email') }}"
                            placeholder="nama@email.com"
                            autocomplete="email"
                            required
                            autofocus
                        >
                    </div>
                    @error('email')
                        <div class="field-err">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn-submit">KIRIM LINK RESET</button>
            </form>

            <p class="hint">
                Jika email terdaftar, link reset akan dikirim ke inbox. Periksa folder spam jika email belum terlihat.
            </p>
        </section>
    </div>
</body>
</html>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Klien {{ config('brand.name', 'NASHIR.ID') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        * { box-sizing: border-box; }
        body {
            margin: 0;
            min-height: 100vh;
            font-family: Poppins, Arial, sans-serif;
            background: #f4f7fb;
            color: #111827;
            display: grid;
            place-items: center;
            padding: 24px;
        }
        a { color: inherit; text-decoration: none; }
        .login-shell { width: min(420px, 100%); }
        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 18px;
            color: #171246;
            font-weight: 800;
            font-size: 20px;
        }
        .brand img {
            width: 48px;
            height: 48px;
            object-fit: contain;
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 6px;
            box-shadow: 0 8px 20px rgba(15, 23, 42, .08);
        }
        .brand small {
            display: block;
            margin-top: 2px;
            color: #64748b;
            font-size: 10px;
            font-weight: 700;
            letter-spacing: .08em;
            text-transform: uppercase;
        }
        .card {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            box-shadow: 0 16px 40px rgba(15, 23, 42, .08);
        }
        .card-body { padding: 24px; }
        h1 {
            margin: 0 0 8px;
            font-size: 24px;
            color: #171246;
        }
        p {
            margin: 0 0 20px;
            color: #64748b;
            font-size: 14px;
            line-height: 1.6;
        }
        label {
            display: block;
            font-size: 13px;
            font-weight: 700;
            margin-bottom: 7px;
            color: #334155;
        }
        input {
            width: 100%;
            border: 1px solid #dbe1ea;
            border-radius: 6px;
            padding: 12px 13px;
            font-size: 15px;
            font-family: inherit;
            text-transform: uppercase;
        }
        input:focus {
            outline: 2px solid rgba(79, 70, 229, .18);
            border-color: #4f46e5;
        }
        .field-error {
            display: block;
            margin-top: 7px;
            color: #dc2626;
            font-size: 12px;
        }
        .flash {
            margin-bottom: 14px;
            padding: 11px 12px;
            border-radius: 6px;
            background: #ecfdf5;
            color: #047857;
            font-size: 13px;
        }
        .btn {
            width: 100%;
            margin-top: 18px;
            border: 0;
            border-radius: 6px;
            padding: 12px 14px;
            background: #220c70;
            color: #fff;
            font-weight: 700;
            font-family: inherit;
            cursor: pointer;
        }
        .home-link {
            display: inline-block;
            margin-top: 14px;
            color: #4f46e5;
            text-decoration: none;
            font-size: 13px;
        }
    </style>
</head>
<body>
    <main class="login-shell">
        <a href="{{ route('home') }}" class="brand" aria-label="{{ config('brand.name', 'NASHIR.ID') }}">
            <img src="{{ asset('image/logo.png') }}" alt="{{ config('brand.name', 'NASHIR.ID') }}">
            <span>
                {{ config('brand.name', 'NASHIR.ID') }}
                <small>Portal Klien</small>
            </span>
        </a>

        @if(session('success'))
            <div class="flash">{{ session('success') }}</div>
        @endif

        <section class="card">
            <div class="card-body">
                <h1>Portal Klien</h1>
                <p>Masukkan kode akses dari admin untuk melihat status pembayaran invoice Anda.</p>

                <form method="POST" action="{{ route('client-portal.login.submit') }}">
                    @csrf
                    <label for="code">Kode Akses</label>
                    <input id="code" type="text" name="code" value="{{ old('code') }}" placeholder="Contoh: CLI-ABC12345" autofocus>
                    @error('code')<span class="field-error">{{ $message }}</span>@enderror

                    <button class="btn" type="submit">Masuk</button>
                </form>
            </div>
        </section>
    </main>
</body>
</html>

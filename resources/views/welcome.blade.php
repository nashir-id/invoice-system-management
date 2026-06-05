<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('brand.name', 'NASHIR.ID') }} Invoice System</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        html { scroll-behavior: smooth; }
        body {
            min-height: 100vh;
            font-family: Poppins, Roboto, Arial, sans-serif;
            color: #111827;
            background:
                radial-gradient(circle at 12% 18%, rgba(102, 166, 255, .2), transparent 28%),
                radial-gradient(circle at 82% 12%, rgba(34, 197, 94, .16), transparent 24%),
                linear-gradient(135deg, #eef6ff 0%, #f8fafc 48%, #fff7ed 100%);
        }
        a { color: inherit; text-decoration: none; }
        .page {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }
        .nav {
            width: min(1120px, calc(100% - 32px));
            margin: 0 auto;
            padding: 22px 0;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
        }
        .brand {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            font-weight: 800;
            color: #171246;
        }
        .brand img {
            width: 45px;
            height: 45px;
            object-fit: contain;
            filter: drop-shadow(0 8px 16px rgba(30, 27, 75, .18));
        }
        .brand span { line-height: 1.1; }
        .brand small {
            display: block;
            margin-top: 2px;
            font-size: 10px;
            font-weight: 600;
            color: #64748b;
            letter-spacing: .08em;
            text-transform: uppercase;
        }
        .nav-actions { display: flex; align-items: center; gap: 10px; }
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            min-height: 42px;
            padding: 0 16px;
            border-radius: 8px;
            border: 1px solid transparent;
            font-size: 14px;
            font-weight: 700;
            transition: transform .15s, box-shadow .15s, background .15s, border-color .15s;
        }
        .btn:hover { transform: translateY(-1px); }
        .btn-primary {
            background: #1e1b4b;
            color: #fff;
            box-shadow: 0 14px 28px rgba(30, 27, 75, .24);
        }
        .btn-primary:hover { background: #312e81; }
        .btn-ghost {
            background: rgba(255, 255, 255, .72);
            color: #1e293b;
            border-color: rgba(148, 163, 184, .35);
        }
        .btn-ghost:hover { background: #fff; border-color: rgba(30, 27, 75, .18); }
        .hero {
            width: min(1120px, calc(100% - 32px));
            margin: 18px auto 0;
            flex: 1;
            display: grid;
            grid-template-columns: minmax(0, 1.02fr) minmax(360px, .98fr);
            align-items: center;
            gap: 44px;
            padding: 28px 0 48px;
        }
        .eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 18px;
            padding: 7px 11px;
            border-radius: 999px;
            background: rgba(255, 255, 255, .76);
            border: 1px solid rgba(148, 163, 184, .3);
            color: #2563eb;
            font-size: 12px;
            font-weight: 800;
            letter-spacing: .05em;
            text-transform: uppercase;
        }
        .eyebrow::before {
            content: "";
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #22c55e;
            box-shadow: 0 0 0 5px rgba(34, 197, 94, .14);
        }
        h1 {
            max-width: 680px;
            color: #171246;
            font-size: clamp(38px, 6vw, 70px);
            line-height: .98;
            font-weight: 900;
            letter-spacing: 0;
        }
        .lead {
            max-width: 610px;
            margin-top: 20px;
            color: #475569;
            font-size: 17px;
            line-height: 1.75;
        }
        .hero-actions {
            margin-top: 28px;
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
        }
        .quick-stats {
            margin-top: 34px;
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 12px;
            max-width: 610px;
        }
        .stat {
            padding: 14px;
            border-radius: 8px;
            background: rgba(255, 255, 255, .7);
            border: 1px solid rgba(226, 232, 240, .9);
        }
        .stat strong {
            display: block;
            color: #171246;
            font-size: 22px;
            line-height: 1;
        }
        .stat span {
            display: block;
            margin-top: 7px;
            color: #64748b;
            font-size: 12px;
            line-height: 1.35;
        }
        .preview {
            position: relative;
            min-height: 560px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .hero-illustration {
            width: min(112%, 720px);
            max-width: none;
            margin-top: -130px;
            display: block;
            border-radius: 8px;
            margin-right: -26px;
            filter: drop-shadow(0 30px 48px rgba(30, 27, 75, .18));
        }
        .features {
            width: min(1120px, calc(100% - 32px));
            margin: -24px auto 32px;
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 12px;
        }
        .feature {
            min-height: 112px;
            padding: 17px;
            border-radius: 8px;
            background: rgba(255, 255, 255, .78);
            border: 1px solid rgba(226, 232, 240, .92);
        }
        .feature-icon {
            width: 32px;
            height: 32px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            background: #e0f2fe;
            color: #0369a1;
            margin-bottom: 12px;
        }
        .feature h2 {
            color: #111827;
            font-size: 14px;
            font-weight: 900;
        }
        .feature p {
            margin-top: 7px;
            color: #64748b;
            font-size: 12px;
            line-height: 1.55;
        }
        @media (max-width: 980px) {
            .hero {
                grid-template-columns: 1fr;
                gap: 22px;
                padding-top: 14px;
            }
            .preview { min-height: auto; padding-bottom: 32px; }
            .features { grid-template-columns: repeat(2, 1fr); margin-top: 0; }
        }
        @media (max-width: 640px) {
            .nav { align-items: flex-start; }
            .nav-actions { flex-direction: column; align-items: stretch; }
            .nav-actions .btn { min-height: 38px; padding: 0 12px; font-size: 12px; }
            h1 { font-size: 40px; }
            .lead { font-size: 15px; }
            .quick-stats,
            .features { grid-template-columns: 1fr; }
            .hero-illustration {
                width: 100%;
                max-width: 620px;
                margin-right: 0;
            }
        }
    </style>
</head>
<body>
    <div class="page">
        <header class="nav">
            <a href="{{ url('/') }}" class="brand" aria-label="{{ config('brand.name', 'NASHIR.ID') }}">
                <img src="{{ asset('image/logo.png') }}" alt="{{ config('brand.name', 'NASHIR.ID') }}">
                <span>
                    {{ config('brand.name', 'NASHIR.ID') }}
                    <small>Invoice System</small>
                </span>
            </a>
            <div class="nav-actions">
                @auth
                    <a href="{{ route('dashboard') }}" class="btn btn-ghost">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="btn btn-ghost">Login</a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="btn btn-primary">Daftar</a>
                    @endif
                @endauth
            </div>
        </header>

        <main class="hero">
            <section>
                <div class="eyebrow">Invoice cepat, rapi, siap kirim</div>
                <h1>Kelola invoice bisnis dengan tampilan yang lebih profesional.</h1>
                <p class="lead">
                    Buat invoice, atur voucher, pantau status pembayaran, dan kirim tautan publik ke klien dari satu sistem yang ringan dan mudah dipakai.
                </p>
                <div class="hero-actions">
                    @auth
                        <a href="{{ route('dashboard') }}" class="btn btn-primary">
                            Masuk Dashboard
                            <span aria-hidden="true">-></span>
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-primary">
                            Mulai Login
                            <span aria-hidden="true">-></span>
                        </a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="btn btn-ghost">Buat Akun</a>
                        @endif
                    @endauth
                </div>
                <div class="quick-stats" aria-label="Ringkasan fitur">
                    <div class="stat">
                        <strong>11%</strong>
                        <span>PPN otomatis dari nilai setelah diskon</span>
                    </div>
                    <div class="stat">
                        <strong>3</strong>
                        <span>Status invoice: belum dibayar, lunas, terlambat</span>
                    </div>
                    <div class="stat">
                        <strong>1x</strong>
                        <span>Kirim invoice publik lewat tautan atau WhatsApp</span>
                    </div>
                </div>
            </section>

            <section class="preview" aria-label="Preview sistem invoice">
                <img
                    src="{{ asset('image/welcome-illustration.png') }}"
                    alt="Ilustrasi orang bekerja di depan komputer"
                    class="hero-illustration"
                >
            </section>
        </main>

        <section class="features" aria-label="Fitur utama">
            <article class="feature">
                <div class="feature-icon">
                    <svg width="18" height="18" viewBox="0 0 16 16" fill="none"><rect x="2" y="1.5" width="12" height="13" rx="2" stroke="currentColor" stroke-width="1.5"/><path d="M5 5h6M5 8h6M5 11h3" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
                </div>
                <h2>Invoice Rapi</h2>
                <p>Item layanan, subtotal, diskon, PPN, dan total tersusun jelas.</p>
            </article>
            <article class="feature">
                <div class="feature-icon">
                    <svg width="18" height="18" viewBox="0 0 16 16" fill="none"><path d="M2 5h12v7.5A1.5 1.5 0 0 1 12.5 14h-9A1.5 1.5 0 0 1 2 12.5V5Z" stroke="currentColor" stroke-width="1.5"/><path d="M4 2v3M12 2v3M2 7.5h12" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
                </div>
                <h2>Jatuh Tempo</h2>
                <p>Status terlambat mudah terlihat untuk membantu follow up klien.</p>
            </article>
            <article class="feature">
                <div class="feature-icon">
                    <svg width="18" height="18" viewBox="0 0 16 16" fill="none"><rect x="1.5" y="4" width="13" height="8" rx="2" stroke="currentColor" stroke-width="1.5"/><path d="M5 8h.5M8 8h.5M11 8h.5" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
                </div>
                <h2>Voucher</h2>
                <p>Kelola kode promo dan lihat jumlah pemakaian pada invoice.</p>
            </article>
            <article class="feature">
                <div class="feature-icon">
                    <svg width="18" height="18" viewBox="0 0 16 16" fill="none"><path d="M2 12l3-4 3 2 3-5 3 3" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </div>
                <h2>Laporan</h2>
                <p>Pantau pendapatan, invoice terbaru, dan performa klien.</p>
            </article>
        </section>
    </div>
</body>
</html>

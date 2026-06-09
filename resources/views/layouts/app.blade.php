<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield ('title', 'Dashboard') {{ config('brand.name', 'NASHIR.ID') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Poppins', sans-serif; background: #f1f4f8; min-height: 100vh; display: flex; }

        /* ── Sidebar ── */
        .sidebar {
            width: 220px; min-width: 220px; background: #220c70;
            display: flex; flex-direction: column;
            min-height: 100vh; position: sticky; top: 0; height: 100vh;
        }
        .sb-brand { padding: 18px 16px; border-bottom: 1px solid rgba(255,255,255,0.08); }
        .sb-logo  { font-size: 16px; font-weight: 700; color: #fff; letter-spacing: .02em; }
        .sb-sub   { font-size: 10px; color: rgba(255,255,255,0.4); margin-top: 2px; }

        .sb-menu { padding: 10px 0; flex: 1; overflow-y: auto; }
        .sb-group-label { font-size: 10px; font-weight: 600; color: rgba(255,255,255,0.3); letter-spacing: .1em; text-transform: uppercase; padding: 12px 16px 4px; }

        .sb-item {
            display: flex; align-items: center; gap: 10px;
            padding: 9px 16px; font-size: 13px; color: rgba(255,255,255,0.6);
            text-decoration: none; transition: all .12s; border-radius: 0;
            position: relative;
        }
        .sb-item:hover { background: rgba(255,255,255,0.06); color: rgba(255,255,255,0.9); }
        .sb-item.active {
            background: rgba(255,255,255,0.12); color: #fff;
            border-left: 3px solid #6d62e8;
        }
        .sb-item svg { width: 16px; height: 16px; flex-shrink: 0; opacity: .75; }
        .sb-item.active svg { opacity: 1; }

        .sb-footer { padding: 14px 16px; border-top: 1px solid rgba(255,255,255,0.08); }
        .sb-user   { display: flex; align-items: center; gap: 9px; }
        .sb-avatar {
            width: 30px; height: 30px; border-radius: 50%;
            background: #534AB7; display: flex; align-items: center;
            justify-content: center; font-size: 11px; font-weight: 600; color: #fff; flex-shrink: 0;
            overflow: hidden;
        }
        .sb-avatar img { width: 100%; height: 100%; object-fit: cover; display: block; }
        .sb-uname { font-size: 12px; color: rgba(255,255,255,0.75); font-weight: 500; }
        .sb-role  { font-size: 10px; color: rgba(255,255,255,0.35); }
        .sb-logout {
            display: flex; align-items: center; gap: 6px;
            font-size: 11px; color: rgba(255,255,255,0.35);
            margin-top: 8px; cursor: pointer; background: none; border: none;
            padding: 0; font-family: inherit;
        }
        .sb-logout:hover { color: rgba(255,255,255,0.65); }

        /* ── Main ── */
        .main { flex: 1; display: flex; flex-direction: column; min-height: 100vh; overflow: hidden; }

        .topbar {
            background: #fff; border-bottom: 1px solid #e8edf3;
            padding: 0 24px; height: 52px;
            display: flex; align-items: center; justify-content: space-between;
            position: sticky; top: 0; z-index: 10;
        }
        .topbar-left { display: flex; align-items: center; gap: 8px; }
        .page-title  { font-size: 15px; font-weight: 600; color: #1a1a2e; }
        .breadcrumb  { font-size: 12px; color: #94a3b8; }
        .breadcrumb a { color: #64748b; text-decoration: none; }
        .breadcrumb a:hover { color: #1a1a2e; }

        .topbar-right { display: flex; align-items: center; gap: 10px; }
        .btn { display: inline-flex; align-items: center; gap: 6px; padding: 7px 14px; border-radius: 8px; font-size: 13px; font-weight: 500; cursor: pointer; border: none; text-decoration: none; transition: all .12s; font-family: inherit; }
        .btn-primary { background: #1e1b4b; color: #fff; }
        .btn-primary:hover { background: #312e81; }
        .btn-outline { background: #fff; color: #475569; border: 1px solid #e2e8f0; }
        .btn-outline:hover { background: #f8fafc; }
        .btn-danger  { background: #fee2e2; color: #dc2626; border: 1px solid #fecaca; }
        .btn-danger:hover  { background: #fecaca; }
        .btn-success { background: #dcfce7; color: #16a34a; border: 1px solid #bbf7d0; }
        .btn-success:hover { background: #bbf7d0; }
        .btn-sm { padding: 5px 10px; font-size: 12px; }
        .btn svg { width: 14px; height: 14px; }

        /* ── Content area ── */
        .content { flex: 1; padding: 24px; overflow-y: auto; }

        /* ── Alert / flash ── */
        .flash { padding: 10px 16px; border-radius: 8px; font-size: 13px; margin-bottom: 18px; display: flex; align-items: center; gap: 8px; }
        .flash-success { background: #f0fdf4; border: 1px solid #bbf7d0; color: #166534; }
        .flash-error   { background: #fef2f2; border: 1px solid #fecaca; color: #991b1b; }
        .flash-info    { background: #eff6ff; border: 1px solid #bfdbfe; color: #1e40af; }

        /* ── Card ── */
        .card { background: #fff; border: 1px solid #e8edf3; border-radius: 12px; }
        .card-header { padding: 14px 20px; border-bottom: 1px solid #f1f5f9; display: flex; align-items: center; justify-content: space-between; }
        .card-title  { font-size: 14px; font-weight: 600; color: #1a1a2e; }
        .card-body   { padding: 20px; }

        /* ── Table ── */
        .table-wrap { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; }
        thead th { font-size: 11px; font-weight: 600; color: #94a3b8; text-transform: uppercase; letter-spacing: .06em; padding: 10px 16px; text-align: left; border-bottom: 1px solid #f1f5f9; white-space: nowrap; }
        tbody td { font-size: 13px; color: #334155; padding: 11px 16px; border-bottom: 1px solid #f8fafc; }
        tbody tr:last-child td { border-bottom: none; }
        tbody tr:hover td { background: #fafbfc; }

        /* ── Badge status ── */
        .badge { display: inline-flex; align-items: center; font-size: 11px; font-weight: 500; padding: 2px 9px; border-radius: 20px; white-space: nowrap; }
        .badge-unpaid  { background: #fef3c7; color: #92400e; }
        .badge-paid    { background: #dcfce7; color: #166534; }
        .badge-overdue { background: #fee2e2; color: #991b1b; }
        .badge-active  { background: #dcfce7; color: #166534; }
        .badge-inactive{ background: #f1f5f9; color: #64748b; }
        .badge-owner   { background: #ede9fe; color: #4c1d95; }
        .badge-admin   { background: #e0f2fe; color: #075985; }
        .badge-staff   { background: #f0fdf4; color: #166534; }

        /* ── Form ── */
        .form-grid   { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
        .form-grid-3 { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 16px; }
        .form-full   { grid-column: 1 / -1; }
        .field { display: flex; flex-direction: column; gap: 5px; }
        .field label { font-size: 12px; font-weight: 500; color: #374151; }
        .field input, .field select, .field textarea {
            height: 42px; border: 1.5px solid #e2e8f0; border-radius: 8px;
            padding: 0 12px; font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 13px; color: #1a1a2e; background: #f8fafc; outline: none;
            transition: border-color .15s, box-shadow .15s, background .15s;
        }
        .field textarea { height: auto; padding: 10px 12px; resize: vertical; min-height: 80px; }
        .field input:focus, .field select:focus, .field textarea:focus {
            border-color: #6d62e8; background: #fff;
            box-shadow: 0 0 0 3px rgba(109,98,232,.1);
        }
        .field input.is-error { border-color: #f87171; }
        .field-error { font-size: 11px; color: #dc2626; }
        .field-hint  { font-size: 11px; color: #94a3b8; }

        /* ── Stat cards ── */
        .stats-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 14px; margin-bottom: 20px; }
        .stat-card  { background: #fff; border: 1px solid #e8edf3; border-radius: 12px; padding: 16px 18px; }
        .stat-label { font-size: 11px; color: #94a3b8; font-weight: 500; margin-bottom: 6px; text-transform: uppercase; letter-spacing: .05em; }
        .stat-value { font-size: 22px; font-weight: 700; color: #1a1a2e; }
        .stat-sub   { font-size: 12px; margin-top: 4px; }
        .text-green  { color: #16a34a; }
        .text-orange { color: #d97706; }
        .text-red    { color: #dc2626; }
        .text-muted  { color: #94a3b8; }

        /* ── Pagination ── */
        .pagination { display: flex; align-items: center; gap: 4px; padding: 14px 16px; }
        .page-btn { padding: 5px 10px; border: 1px solid #e2e8f0; border-radius: 6px; font-size: 12px; color: #64748b; background: #fff; cursor: pointer; text-decoration: none; }
        .page-btn:hover { background: #f8fafc; }
        .page-btn.active { background: #1e1b4b; color: #fff; border-color: #1e1b4b; }
        .page-info { font-size: 12px; color: #94a3b8; margin-left: auto; }

        /* ── Empty state ── */
        .empty-state { text-align: center; padding: 48px 24px; color: #94a3b8; }
        .empty-state svg { width: 48px; height: 48px; margin: 0 auto 12px; opacity: .4; }
        .empty-state p { font-size: 14px; margin-bottom: 16px; }

        /* ── Toggle switch ── */
        .toggle-wrap { display: flex; align-items: center; gap: 10px; }
        .toggle { position: relative; width: 38px; height: 22px; }
        .toggle input { opacity: 0; width: 0; height: 0; }
        .toggle-slider {
            position: absolute; inset: 0; background: #e2e8f0; border-radius: 11px;
            cursor: pointer; transition: background .2s;
        }
        .toggle-slider::before {
            content: ''; position: absolute; width: 16px; height: 16px;
            background: #fff; border-radius: 50%; top: 3px; left: 3px;
            transition: transform .2s; box-shadow: 0 1px 3px rgba(0,0,0,.2);
        }
        .toggle input:checked + .toggle-slider { background: #6d62e8; }
        .toggle input:checked + .toggle-slider::before { transform: translateX(16px); }

        /* ── Responsive ── */
        @media (max-width: 768px) {
            .sidebar { display: none; }
            .stats-grid { grid-template-columns: 1fr 1fr; }
            .form-grid, .form-grid-3 { grid-template-columns: 1fr; }
        }
    </style>
    @stack('styles')
</head>
<body>

{{-- ── Sidebar ── --}}
<div class="sidebar">
    <div class="sb-brand">
        <img src="{{ asset('image/logo.png') }}" alt="Logo" style="width: 60px; height: 60px; position: relative; top: -5px; object-fit: contain;">
        <div class="sb-logo">{{ config('brand.name', 'NASHIR.ID') }}</div>
        <div class="sb-sub">Invoice Management</div>
    </div>

    <nav class="sb-menu">
        <div class="sb-group-label">Utama</div>

        <a href="{{ route('dashboard') }}" class="sb-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <svg viewBox="0 0 16 16" fill="none">
                <rect x="1" y="1" width="6" height="6" rx="1.5" fill="currentColor"/>
                <rect x="9" y="1" width="6" height="6" rx="1.5" fill="currentColor" opacity=".5"/>
                <rect x="1" y="9" width="6" height="6" rx="1.5" fill="currentColor" opacity=".5"/>
                <rect x="9" y="9" width="6" height="6" rx="1.5" fill="currentColor" opacity=".5"/>
            </svg>
            Dashboard
        </a>



        <a href="{{ route('clients.index') }}" class="sb-item {{ request()->routeIs('clients.*') ? 'active' : '' }}">
            <svg viewBox="0 0 16 16" fill="none">
                <circle cx="8" cy="6" r="3" stroke="currentColor" stroke-width="1.3"/>
                <path d="M2 14c0-3 2.7-4.5 6-4.5s6 1.5 6 4.5" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/>
            </svg>
            Klien
        </a>

        <a href="{{ route('invoices.index') }}" class="sb-item {{ request()->routeIs('invoices.*') ? 'active' : '' }}">
            <svg viewBox="0 0 16 16" fill="none">
                <rect x="2" y="1" width="12" height="14" rx="2" stroke="currentColor" stroke-width="1.3"/>
                <path d="M5 5h6M5 8h6M5 11h4" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/>
            </svg>
            Invoice
        </a>
        <a href="{{ route('recurring-templates.index') }}" class="sb-item {{ request()->routeIs('recurring-templates.*') ? 'active' : '' }}">
            <svg viewBox="0 0 16 16" fill="none">
                <path d="M2 8a6 6 0 0 1 10.3-4.2M14 8a6 6 0 0 1-10.3 4.2" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/>
                <path d="M12 3.5V6h-2.5M4 12.5V10H6.5" stroke="currentColor" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            Recurring
        </a>

        <a href="{{ route('vouchers.index') }}" class="sb-item {{ request()->routeIs('vouchers.*') ? 'active' : '' }}">
            <svg viewBox="0 0 16 16" fill="none">
                <rect x="1" y="4" width="14" height="8" rx="2" stroke="currentColor" stroke-width="1.3"/>
                <path d="M5 8h.5M8 8h.5M11 8h.5" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
            </svg>
            Voucher
        </a>

        <a href="{{ route('reports.index') }}" class="sb-item {{ request()->routeIs('reports.*') ? 'active' : '' }}">
            <svg viewBox="0 0 16 16" fill="none">
                <path d="M2 12l3-4 3 2 3-5 3 3" stroke="currentColor" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            Laporan
        </a>

        <div class="sb-group-label" style="margin-top:8px">Pengaturan</div>
        <a href="{{ route('profile.edit') }}" class="sb-item {{ request()->routeIs('profile.*') ? 'active' : '' }}">
    <svg viewBox="0 0 16 16" fill="none">
        <circle cx="6" cy="6" r="3" stroke="currentColor" stroke-width="1.3"/>
        <path d="M1 14c0-3 2.2-4.5 5-4.5M10 10l2 2 3-3"
              stroke="currentColor"
              stroke-width="1.3"
              stroke-linecap="round"
              stroke-linejoin="round"/>
    </svg>
    Profile
</a>

        {{-- <a href="{{ route('settings.index') }}" class="sb-item {{ request()->routeIs('settings.*') ? 'active' : '' }}">
            <svg viewBox="0 0 16 16" fill="none">
                <circle cx="8" cy="8" r="2.5" stroke="currentColor" stroke-width="1.3"/>
                <path d="M8 1v2M8 13v2M1 8h2M13 8h2M3.1 3.1l1.4 1.4M11.5 11.5l1.4 1.4M3.1 12.9l1.4-1.4M11.5 4.5l1.4-1.4" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/>
            </svg>
        </a> --}}

        {{-- PENGATURAN SISTEM (OWNER ONLY) --}}
@if(auth()->user()->role === 'owner')

<a href="{{ route('settings.index') }}"
   class="sb-item {{ request()->routeIs('settings.*') ? 'active' : '' }}">

    <svg viewBox="0 0 24 24"
         width="18"
         height="18"
         fill="none"
         stroke="currentColor"
         stroke-width="2">
         <path d="M12 2L14.09 8.26L20 10L14.09 11.74L12 18L9.91 11.74L4 10L9.91 8.26L12 2Z"/>

    </svg>

    <span>Pengaturan Sistem</span>

    
    {{-- <li>
    <a href="{{ route('payments.verifications') }}">
        Verifikasi Pembayaran
    </a>
</li> --}}
</a>

@endif

@if(auth()->user()->role === 'owner')

<a href="{{ route('payments.verifications') }}">
   class="sb-item {{ request()->routeIs('users.*') ? 'active' : '' }}">

    <svg viewBox="0 0 24 24"
         width="18"
         height="18"
         fill="none"
         stroke="currentColor"
         stroke-width="2">

        <path d="M17 21V19A4 4 0 0 0 13 15H5A4 4 0 0 0 1 19V21"/>
        <circle cx="9" cy="7" r="4"/>

    </svg>

    <span>Verifikasi Pembayaran</span>
    </a>

@endif

@if(auth()->user()->role === 'owner')

<a href="{{ route('users.index') }}"
   class="sb-item {{ request()->routeIs('users.*') ? 'active' : '' }}">

    <svg viewBox="0 0 24 24"
         width="18"
         height="18"
         fill="none"
         stroke="currentColor"
         stroke-width="2">

        <path d="M17 21V19A4 4 0 0 0 13 15H5A4 4 0 0 0 1 19V21"/>
        <circle cx="9" cy="7" r="4"/>

    </svg>

    <span>Manajemen User</span>
    </a>

@endif



    </nav>

    <div class="sb-footer">
        <div class="sb-user">
            <div class="sb-avatar">
                @if(auth()->user()->profile_photo_url)
                    <img src="{{ auth()->user()->profile_photo_url }}" alt="{{ auth()->user()->name }}">
                @else
                    {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                @endif
            </div>
            <div>
                <div class="sb-uname">{{ Str::limit(auth()->user()->name, 18) }}</div>
                <div class="sb-role">{{ ucfirst(auth()->user()->role) }}</div>
            </div>
        </div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="sb-logout">
                <svg width="12" height="12" viewBox="0 0 16 16" fill="none">
                    <path d="M6 14H3a1 1 0 0 1-1-1V3a1 1 0 0 1 1-1h3M10 11l3-3-3-3M13 8H6" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Logout
            </button>
        </form>
    </div>
</div>

{{-- ── Main ── --}}
<div class="main">
    {{-- Topbar --}}
    <div class="topbar">
        <div class="topbar-left">
            <span class="page-title">@yield('page-title', 'Dashboard')</span>
            @hasSection('breadcrumb')
                <span class="breadcrumb"> / @yield('breadcrumb')</span>
            @endif
        </div>
        <div class="topbar-right">

        </div>
    </div>

    {{-- Content --}}
    <div class="content">
        {{-- Flash messages --}}
        @if (session('success'))
            <div class="flash flash-success">
                <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                    <circle cx="8" cy="8" r="7" stroke="currentColor" stroke-width="1.3"/>
                    <path d="M5 8l2 2 4-4" stroke="currentColor" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="flash flash-error">
                <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                    <circle cx="8" cy="8" r="7" stroke="currentColor" stroke-width="1.3"/>
                    <path d="M8 5v3M8 10v.5" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/>
                </svg>
                {{ session('error') }}
            </div>
        @endif
        @if ($errors->any() && !isset($hideGlobalErrors))
            <div class="flash flash-error">
                <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                    <circle cx="8" cy="8" r="7" stroke="currentColor" stroke-width="1.3"/>
                    <path d="M8 5v3M8 10v.5" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/>
                </svg>
                Terdapat {{ $errors->count() }} kesalahan input. Periksa kembali form di bawah.
            </div>
        @endif

        @yield('content')
    </div>
</div>

@stack('scripts')
</body>
</html>

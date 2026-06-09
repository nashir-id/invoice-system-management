<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Status Pembayaran {{ config('brand.name', 'NASHIR.ID') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        * { box-sizing: border-box; }
        body { margin: 0; min-height: 100vh; font-family: Poppins, Arial, sans-serif; background: #f4f7fb; color: #111827; }
        a { color: inherit; }
        .topbar { background: #220c70; color: #fff; }
        .topbar-inner { width: min(1120px, calc(100% - 32px)); margin: 0 auto; padding: 16px 0; display: flex; align-items: center; justify-content: space-between; gap: 16px; }
        .brand { font-weight: 800; }
        .logout { border: 1px solid rgba(255,255,255,.25); background: rgba(255,255,255,.08); color: #fff; border-radius: 6px; padding: 8px 12px; font-family: inherit; cursor: pointer; }
        .wrap { width: min(1120px, calc(100% - 32px)); margin: 24px auto 40px; }
        .heading { display: flex; justify-content: space-between; gap: 16px; align-items: end; margin-bottom: 18px; }
        h1 { margin: 0 0 5px; font-size: 26px; color: #171246; }
        .muted { color: #64748b; font-size: 13px; }
        .stats { display: grid; grid-template-columns: repeat(3, 1fr); gap: 12px; margin-bottom: 18px; }
        .card { background: #fff; border: 1px solid #e5e7eb; border-radius: 8px; box-shadow: 0 10px 30px rgba(15, 23, 42, .05); }
        .stat { padding: 16px; }
        .stat-label { color: #64748b; font-size: 12px; margin-bottom: 8px; }
        .stat-value { color: #171246; font-weight: 800; font-size: 20px; }
        .table-wrap { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; min-width: 760px; }
        th, td { padding: 13px 14px; border-bottom: 1px solid #eef2f7; text-align: left; font-size: 13px; }
        th { color: #64748b; font-size: 11px; text-transform: uppercase; letter-spacing: .04em; background: #f8fafc; }
        .invoice-no { font-family: monospace; font-weight: 700; color: #4f46e5; }
        .badge { display: inline-flex; align-items: center; border-radius: 999px; padding: 5px 9px; font-size: 12px; font-weight: 700; }
        .badge-paid { background: #dcfce7; color: #166534; }
        .badge-unpaid { background: #ffedd5; color: #9a3412; }
        .badge-overdue { background: #fee2e2; color: #991b1b; }
        .empty { padding: 28px; color: #64748b; text-align: center; }
        .pagination { padding: 14px; }
        @media (max-width: 720px) {
            .heading { display: block; }
            .stats { grid-template-columns: 1fr; }
            h1 { font-size: 22px; }
        }
    </style>
</head>
<body>
    <header class="topbar">
        <div class="topbar-inner">
            <div class="brand">{{ config('brand.name', 'NASHIR.ID') }} Portal Klien</div>
            <form method="POST" action="{{ route('client-portal.logout') }}">
                @csrf
                <button class="logout" type="submit">Keluar</button>
            </form>
        </div>
    </header>

    <main class="wrap">
        <div class="heading">
            <div>
                <h1>Status Pembayaran</h1>
                <h2 class="muted">{{ $client->company_name }}{{ $client->pic_name ? ' - ' . $client->pic_name : '' }}</h2>
            </div>
            <div class="muted">Kode: {{ $client->client_login_code }}</div>
        </div>

        <section class="stats">
            <div class="card stat">
                <div class="stat-label">Total Invoice</div>
                <div class="stat-value">{{ $stats['total_invoices'] }}</div>
            </div>
            <div class="card stat">
                <div class="stat-label">Sudah Masuk</div>
                <div class="stat-value">Rp {{ number_format($stats['total_paid'], 0, ',', '.') }}</div>
            </div>
            <div class="card stat">
                <div class="stat-label">Belum Masuk</div>
                <div class="stat-value">Rp {{ number_format($stats['total_outstanding'], 0, ',', '.') }}</div>
            </div>
        </section>

        <section class="card">
            <div class="table-wrap">
                @if($invoices->isEmpty())
                    <div class="empty">Belum ada invoice untuk klien ini.</div>
                @else
                    <table>
                        <thead>
                            <tr>
                                <th>No. Invoice</th>
                                <th>Tanggal</th>
                                <th>Jatuh Tempo</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Pembayaran</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($invoices as $invoice)
                                <tr>
                                    <td class="invoice-no">{{ $invoice->invoice_number }}</td>
                                    <td>{{ $invoice->invoice_date ? $invoice->invoice_date->format('d M Y') : '-' }}</td>
                                    <td>{{ $invoice->due_date ? $invoice->due_date->format('d M Y') : '-' }}</td>
                                    <td>Rp {{ number_format($invoice->total, 0, ',', '.') }}</td>
                                    <td>
                                        <span class="badge badge-{{ $invoice->status }}">
                                            {{ $invoice->status_label }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($invoice->payment)
                                            Masuk {{ $invoice->payment->paid_at->format('d M Y') }} via {{ $invoice->payment->bank_label }}
                                        @else
                                            Belum masuk
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="pagination">{{ $invoices->links() }}</div>
                @endif
            </div>
        </section>
    </main>
</body>
</html>

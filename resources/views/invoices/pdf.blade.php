<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>{{ $invoice->invoice_number }}</title>
    <style>
        @page { margin: 28px; }
        * { box-sizing: border-box; }
        body {
            font-family: DejaVu Sans, Arial, sans-serif;
            color: #1f2937;
            font-size: 12px;
            line-height: 1.45;
        }
        .header {
            background: #1e1b4b;
            color: #fff;
            padding: 22px 24px;
            border-radius: 8px 8px 0 0;
        }
        .brand-wrap { display: table; }
        .brand-logo,
        .brand-copy {
            display: table-cell;
            vertical-align: middle;
        }
        .brand-logo {
            width: 54px;
            height: 54px;
            padding-right: 12px;
        }
        .brand-mark {
            width: 58px;
            height: 58px;
            object-fit: contain;
        }
        .brand { font-size: 22px; font-weight: bold; }
        .tagline { color: #c7d2fe; font-size: 11px; margin-top: 4px; }
        .invoice-title { text-align: right; font-size: 11px; color: #c7d2fe; text-transform: uppercase; }
        .invoice-number { text-align: right; font-size: 18px; font-weight: bold; margin-top: 3px; }
        .status {
            padding: 10px 24px;
            border-left: 1px solid #e5e7eb;
            border-right: 1px solid #e5e7eb;
            font-weight: bold;
        }
        .status.paid { background: #dcfce7; color: #166534; }
        .status.unpaid { background: #fef3c7; color: #92400e; }
        .status.overdue { background: #fee2e2; color: #991b1b; }
        .content {
            border: 1px solid #e5e7eb;
            border-top: 0;
            border-radius: 0 0 8px 8px;
            padding: 24px;
        }
        table { width: 100%; border-collapse: collapse; }
        .top-table td { vertical-align: top; width: 50%; padding-bottom: 20px; }
        .label {
            color: #94a3b8;
            font-size: 10px;
            font-weight: bold;
            letter-spacing: .05em;
            text-transform: uppercase;
            margin-bottom: 5px;
        }
        .value { font-size: 12px; color: #111827; }
        .detail-row { margin-bottom: 4px; }
        .detail-row span:first-child {
            display: inline-block;
            width: 88px;
            color: #6b7280;
        }
        .items th {
            background: #f8fafc;
            color: #64748b;
            font-size: 10px;
            text-transform: uppercase;
            padding: 9px 8px;
            border-bottom: 1px solid #e5e7eb;
            text-align: left;
        }
        .items td {
            padding: 10px 8px;
            border-bottom: 1px solid #f1f5f9;
            vertical-align: top;
        }
        .items th:nth-child(2),
        .items th:nth-child(3),
        .items th:nth-child(4),
        .items td:nth-child(2),
        .items td:nth-child(3),
        .items td:nth-child(4) {
            text-align: right;
        }
        .item-desc {
            color: #94a3b8;
            font-size: 10px;
            margin-top: 3px;
        }
        .summary {
            width: 42%;
            margin-left: auto;
            margin-top: 16px;
            background: #f8fafc;
            border-radius: 8px;
            padding: 12px 14px;
        }
        .summary-row {
            display: table;
            width: 100%;
            padding: 4px 0;
        }
        .summary-row span { display: table-cell; }
        .summary-row span:last-child { text-align: right; font-weight: bold; }
        .summary-row.discount span:last-child { color: #dc2626; }
        .summary-row.total {
            margin-top: 7px;
            padding-top: 10px;
            border-top: 1px solid #dbe3ef;
            color: #1e1b4b;
            font-size: 15px;
            font-weight: bold;
        }
        .bank {
            margin-top: 18px;
            background: #eff6ff;
            border: 1px solid #bfdbfe;
            color: #1e40af;
            border-radius: 8px;
            padding: 12px 14px;
        }
        .bank-title {
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 8px;
        }
        .terms {
            margin-top: 18px;
            padding-top: 14px;
            border-top: 1px solid #e5e7eb;
            color: #64748b;
            font-size: 11px;
        }
        .paid-note {
            margin-top: 16px;
            padding: 11px 13px;
            border-radius: 8px;
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            color: #166534;
        }
    </style>
</head>
<body>
    <table class="header">
        <tr>
            <td>
                <div class="brand-wrap">
                    <div class="brand-logo">
                        <img class="brand-mark" src="{{ public_path('image/logo.png') }}" alt="{{ config('brand.name', 'NASHIR.ID') }}">
                    </div>
                    <div class="brand-copy">
                        <div class="brand">{{ config('brand.name', 'NASHIR.ID') }}</div>
                        <div class="tagline">{{ config('brand.tagline', 'Invoice Management System') }}</div>
                    </div>
                </div>
            </td>
            <td>
                <div class="invoice-title">Invoice</div>
                <div class="invoice-number">{{ $invoice->invoice_number }}</div>
            </td>
        </tr>
    </table>

    <div class="status {{ $invoice->status }}">
        Status: {{ $invoice->status_label }}
    </div>

    <div class="content">
        <table class="top-table">
            <tr>
                <td>
                    <div class="label">Kepada</div>
                    <div class="value">
                        <strong>{{ $invoice->client->company_name }}</strong><br>
                        @if($invoice->client->pic_name)
                            {{ $invoice->client->pic_name }}<br>
                        @endif
                        @if($invoice->client->email)
                            {{ $invoice->client->email }}<br>
                        @endif
                        @if($invoice->client->website)
                            {{ $invoice->client->website }}
                        @endif
                    </div>
                </td>
                <td>
                    <div class="label">Detail Invoice</div>
                    <div class="detail-row"><span>Tanggal</span>{{ $invoice->invoice_date->format('d M Y') }}</div>
                    @if($invoice->due_date)
                        <div class="detail-row"><span>Jatuh Tempo</span>{{ $invoice->due_date->format('d M Y') }}</div>
                    @endif
                    @if($invoice->estimation)
                        <div class="detail-row"><span>Estimasi</span>{{ $invoice->estimation }}</div>
                    @endif
                    <div class="detail-row"><span>Tipe</span>{{ $invoice->type === 'recurring' ? 'Recurring' : 'One-Time' }}</div>
                </td>
            </tr>
        </table>

        <table class="items">
            <thead>
                <tr>
                    <th style="width: 46%">Layanan</th>
                    <th>Harga</th>
                    <th>Qty</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($invoice->items as $item)
                    <tr>
                        <td>
                            <strong>{{ $item->service_name }}</strong>
                            @if($item->description)
                                <div class="item-desc">{{ $item->description }}</div>
                            @endif
                        </td>
                        <td>Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="summary">
            <div class="summary-row">
                <span>Subtotal</span>
                <span>Rp {{ number_format($invoice->subtotal, 0, ',', '.') }}</span>
            </div>
            @if($invoice->discount > 0)
                <div class="summary-row discount">
                    <span>Diskon {{ $invoice->voucher?->code ? "({$invoice->voucher->code})" : '' }}</span>
                    <span>- Rp {{ number_format($invoice->discount, 0, ',', '.') }}</span>
                </div>
            @endif
            @if($invoice->use_ppn)
                <div class="summary-row">
                    <span>PPN 11%</span>
                    <span>Rp {{ number_format($invoice->ppn_amount, 0, ',', '.') }}</span>
                </div>
            @endif
            <div class="summary-row total">
                <span>Total</span>
                <span>Rp {{ number_format($invoice->total, 0, ',', '.') }}</span>
            </div>
        </div>

        <div class="bank">
            <div class="bank-title">Informasi Pembayaran</div>
            Mandiri: 1234567890 a/n Dimas permana<br>
            BCA: 0987654321 a/n Dimas permana<br>
            SeaBank: 1122334455 a/n Dimas permana
        </div>

        @if($invoice->terms_conditions)
            <div class="terms">
                <strong>Terms & Conditions</strong><br>
                {{ $invoice->terms_conditions }}
            </div>
        @endif

        @if($invoice->payment)
            <div class="paid-note">
                <strong>Lunas</strong> -
                dibayar via {{ $invoice->payment->bank_label }}
                pada {{ $invoice->payment->paid_at->format('d M Y') }}
                sejumlah Rp {{ number_format($invoice->payment->amount, 0, ',', '.') }}
            </div>
        @endif
    </div>
</body>
</html>

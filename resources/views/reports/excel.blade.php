<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #999; padding: 6px; }
        th { background: #e8f3e8; font-weight: bold; }
        .title { font-size: 18px; font-weight: bold; }
        .right { text-align: right; }
        .center { text-align: center; }
        .summary-label { font-weight: bold; background: #f3f4f6; }
    </style>
</head>
<body>
    <table>
        <tr>
            <td colspan="11" class="title">Laporan Keuangan</td>
        </tr>
        <tr>
            <td colspan="11">Diunduh: {{ now()->format('d M Y H:i') }}</td>
        </tr>
        <tr><td colspan="11"></td></tr>
        <tr>
            <th>No</th>
            <th>No. Invoice</th>
            <th>Klien</th>
            <th>Tanggal Invoice</th>
            <th>Jatuh Tempo</th>
            <th>Tanggal Bayar</th>
            <th>Bank</th>
            <th>Subtotal</th>
            <th>Diskon</th>
            <th>PPN</th>
            <th>Total</th>
        </tr>
        @forelse($invoices as $invoice)
            <tr>
                <td class="center">{{ $loop->iteration }}</td>
                <td>{{ $invoice->invoice_number }}</td>
                <td>{{ $invoice->client->company_name ?? '-' }}</td>
                <td>{{ optional($invoice->invoice_date)->format('d M Y') }}</td>
                <td>{{ optional($invoice->due_date)->format('d M Y') }}</td>
                <td>{{ optional(optional($invoice->payment)->paid_at)->format('d M Y') }}</td>
                <td>{{ optional($invoice->payment)->bank_label ?? '-' }}</td>
                <td class="right">{{ number_format($invoice->subtotal, 0, ',', '.') }}</td>
                <td class="right">{{ number_format($invoice->discount, 0, ',', '.') }}</td>
                <td class="right">{{ number_format($invoice->ppn_amount, 0, ',', '.') }}</td>
                <td class="right">{{ number_format($invoice->total, 0, ',', '.') }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="11" class="center">Belum ada invoice lunas pada periode ini.</td>
            </tr>
        @endforelse
        <tr><td colspan="11"></td></tr>
        <tr>
            <td colspan="7" class="summary-label right">Total Subtotal</td>
            <td class="right">{{ number_format($summary['subtotal'], 0, ',', '.') }}</td>
            <td colspan="3"></td>
        </tr>
        <tr>
            <td colspan="8" class="summary-label right">Total Diskon</td>
            <td class="right">{{ number_format($summary['discount'], 0, ',', '.') }}</td>
            <td colspan="2"></td>
        </tr>
        <tr>
            <td colspan="9" class="summary-label right">Total PPN</td>
            <td class="right">{{ number_format($summary['ppn'], 0, ',', '.') }}</td>
            <td></td>
        </tr>
        <tr>
            <td colspan="10" class="summary-label right">Total Pendapatan</td>
            <td class="right">{{ number_format($summary['total'], 0, ',', '.') }}</td>
        </tr>
    </table>
</body>
</html>

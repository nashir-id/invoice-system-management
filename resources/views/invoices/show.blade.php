@extends('layouts.app')
@section('title', $invoice->invoice_number)
@section('page-title', $invoice->invoice_number)
@section('breadcrumb') <a href="{{ route('invoices.index') }}">Invoice</a> / {{ $invoice->invoice_number }} @endsection

@section('topbar-actions')
    {{-- Download PDF --}}
    <a href="{{ route('invoices.download', $invoice) }}" class="btn btn-outline" target="_blank">
        <svg viewBox="0 0 16 16" fill="none"><path d="M8 2v8M5 7l3 3 3-3M3 13h10" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/></svg>
        Download PDF
    </a>
    {{-- Kirim WhatsApp --}}
    <button onclick="sendWhatsApp()" class="btn btn-outline" style="color:#16a34a;border-color:#bbf7d0">
        <svg viewBox="0 0 16 16" fill="none"><path d="M13.5 2.5A6.5 6.5 0 1 0 4.2 12.3L2 14l1.8-2.1A6.5 6.5 0 0 0 13.5 2.5z" stroke="currentColor" stroke-width="1.3"/></svg>
        WhatsApp
    </button>
    @adminup
    @if($invoice->status !== 'paid')
        <a href="{{ route('invoices.edit', $invoice) }}" class="btn btn-outline">Edit</a>
    @endif
    @endadminup
@endsection

@push('styles')
<style>
    /* ── Layout 2 kolom ── */
    .inv-layout { display: grid; grid-template-columns: 1fr 340px; gap: 16px; align-items: start; }

    /* ── Preview invoice (mirip PDF) ── */
    .inv-preview {
        background: #fff; border: 1px solid #e8edf3; border-radius: 12px;
        overflow: hidden;
    }
    .inv-preview-header {
        background: #1e1b4b; padding: 24px 28px;
        display: flex; align-items: flex-start; justify-content: space-between;
    }
    .inv-brand-name { font-size: 20px; font-weight: 800; color: #fff; letter-spacing: .02em; }
    .inv-brand-tag  { font-size: 11px; color: rgba(255,255,255,.5); margin-top: 3px; }
    .inv-number-label { font-size: 10px; color: rgba(255,255,255,.5); text-align: right; text-transform: uppercase; letter-spacing: .06em; }
    .inv-number-val   { font-size: 16px; font-weight: 700; color: #fff; font-family: monospace; margin-top: 2px; }

    .inv-preview-body { padding: 24px 28px; }

    .inv-info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px; }
    .inv-info-label { font-size: 10px; font-weight: 600; color: #94a3b8; text-transform: uppercase; letter-spacing: .06em; margin-bottom: 4px; }
    .inv-info-val   { font-size: 13px; font-weight: 500; color: #1a1a2e; line-height: 1.5; }

    /* Tabel item */
    .inv-items-table { width: 100%; border-collapse: collapse; margin-bottom: 16px; }
    .inv-items-table thead th {
        font-size: 10px; font-weight: 600; color: #94a3b8; text-transform: uppercase;
        letter-spacing: .06em; padding: 8px 10px; text-align: left;
        background: #f8fafc; border-bottom: 1px solid #e8edf3;
    }
    .inv-items-table thead th:last-child { text-align: right; }
    .inv-items-table tbody td { font-size: 13px; color: #334155; padding: 10px 10px; border-bottom: 1px solid #f8fafc; vertical-align: top; }
    .inv-items-table tbody tr:last-child td { border-bottom: none; }
    .inv-items-table td:last-child { text-align: right; font-weight: 500; white-space: nowrap; }
    .inv-items-table td:nth-child(2), .inv-items-table td:nth-child(3) { text-align: right; }
    .item-desc { font-size: 11px; color: #94a3b8; margin-top: 3px; line-height: 1.5; }

    /* Summary box */
    .inv-summary {
        background: #f8fafc; border-radius: 8px; padding: 14px 16px;
        margin-bottom: 16px;
    }
    .inv-sum-row { display: flex; justify-content: space-between; font-size: 13px; padding: 3px 0; }
    .inv-sum-row.total {
        font-size: 15px; font-weight: 700; color: #1e1b4b;
        border-top: 1px solid #e2e8f0; margin-top: 6px; padding-top: 10px;
    }
    .inv-sum-row .label { color: #64748b; }
    .inv-sum-row.discount .value { color: #dc2626; }
    .strikethrough { text-decoration: line-through; color: #94a3b8; font-size: 11px; }

    /* Bank info */
    .inv-bank { background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 8px; padding: 12px 14px; margin-bottom: 16px; }
    .inv-bank-title { font-size: 11px; font-weight: 600; color: #1e40af; text-transform: uppercase; letter-spacing: .06em; margin-bottom: 8px; }
    .inv-bank-item  { font-size: 12px; color: #1e40af; margin-bottom: 3px; display: flex; gap: 8px; }
    .inv-bank-item span:first-child { color: #64748b; min-width: 80px; }

    /* T&C */
    .inv-tnc { font-size: 11px; color: #94a3b8; line-height: 1.6; border-top: 1px solid #f1f5f9; padding-top: 14px; }
    .inv-tnc-label { font-weight: 600; color: #64748b; margin-bottom: 4px; }

    /* Status banner */
    .status-banner {
        display: flex; align-items: center; justify-content: space-between;
        padding: 10px 28px; font-size: 13px; font-weight: 500;
    }
    .status-banner.paid    { background: #f0fdf4; color: #166534; border-top: 1px solid #bbf7d0; }
    .status-banner.unpaid  { background: #fffbeb; color: #92400e; border-top: 1px solid #fde68a; }
    .status-banner.overdue { background: #fef2f2; color: #991b1b; border-top: 1px solid #fecaca; }

    /* ── Panel kanan ── */
    .right-panel { display: flex; flex-direction: column; gap: 14px; }

    /* Form tandai lunas */
    .pay-form { background: #fff; border: 1px solid #e8edf3; border-radius: 12px; overflow: hidden; }
    .pay-form-head { padding: 12px 16px; border-bottom: 1px solid #f1f5f9; font-size: 13px; font-weight: 600; color: #1a1a2e; }
    .pay-form-body { padding: 16px; }

    .bank-options { display: grid; grid-template-columns: repeat(3, 1fr); gap: 8px; margin-bottom: 14px; }
    .bank-opt { border: 1.5px solid #e2e8f0; border-radius: 9px; padding: 10px 8px; text-align: center; cursor: pointer; transition: all .15s; }
    .bank-opt:hover { border-color: #6d62e8; background: #f5f3ff; }
    .bank-opt input[type="radio"] { display: none; }
    .bank-opt.selected { border-color: #6d62e8; background: #ede9fe; }
    .bank-opt .bank-name { font-size: 12px; font-weight: 600; color: #1a1a2e; margin-top: 4px; }
    .bank-opt .bank-acc  { font-size: 10px; color: #94a3b8; margin-top: 2px; }

    /* Log */
    .log-card { background: #fff; border: 1px solid #e8edf3; border-radius: 12px; overflow: hidden; }
    .log-item { display: flex; gap: 10px; padding: 10px 14px; border-bottom: 1px solid #f8fafc; }
    .log-item:last-child { border-bottom: none; }
    .log-dot { width: 8px; height: 8px; border-radius: 50%; background: #6d62e8; margin-top: 5px; flex-shrink: 0; }
    .log-body { flex: 1; }
    .log-action { font-size: 12px; font-weight: 500; color: #1a1a2e; }
    .log-desc   { font-size: 11px; color: #94a3b8; margin-top: 2px; line-height: 1.5; }
    .log-time   { font-size: 10px; color: #cbd5e1; margin-top: 2px; }

    @media (max-width: 900px) {
        .inv-layout { grid-template-columns: 1fr; }
    }
</style>
@endpush

@section('content')
<div class="inv-layout">

    {{-- ══ KOLOM KIRI — Preview Invoice (identik PDF) ══ --}}
    <div>
        <div class="inv-preview">

            {{-- Header brand --}}
            <div class="inv-preview-header">
                <div>
                    <div class="inv-brand-name">{{ config('brand.name', 'NASHIR.ID') }}</div>
                    <div class="inv-brand-tag">{{ config('brand.tagline', 'Invoice Management System') }}</div>
                </div>
                <div style="text-align:right">
                    <div class="inv-number-label">Invoice</div>
                    <div class="inv-number-val">{{ $invoice->invoice_number }}</div>
                </div>
            </div>

            {{-- Status banner --}}
            <div class="status-banner {{ $invoice->status }}">
                <span>
                    @if($invoice->status === 'paid')   ✓ Lunas
                    @elseif($invoice->status === 'overdue') ⚠ Terlambat
                    @else ● Menunggu Pembayaran
                    @endif
                </span>
                <span class="badge badge-{{ $invoice->status }}">{{ $invoice->status_label }}</span>
            </div>

            <div class="inv-preview-body">

                {{-- Info klien & invoice --}}
                <div class="inv-info-grid">
                    <div>
                        <div class="inv-info-label">Kepada</div>
                        <div class="inv-info-val">
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
                    </div>
                    <div>
                        <div class="inv-info-label">Detail Invoice</div>
                        <div class="inv-info-val" style="display:flex;flex-direction:column;gap:3px">
                            <div style="display:flex;justify-content:space-between">
                                <span style="color:#94a3b8;font-size:12px">Tanggal</span>
                                <span>{{ $invoice->invoice_date->format('d M Y') }}</span>
                            </div>
                            @if($invoice->due_date)
                            <div style="display:flex;justify-content:space-between">
                                <span style="color:#94a3b8;font-size:12px">Jatuh Tempo</span>
                                <span style="color:{{ $invoice->due_date->isPast() && $invoice->status !== 'paid' ? '#dc2626':'#1a1a2e' }}">
                                    {{ $invoice->due_date->format('d M Y') }}
                                </span>
                            </div>
                            @endif
                            @if($invoice->estimation)
                            <div style="display:flex;justify-content:space-between">
                                <span style="color:#94a3b8;font-size:12px">Estimasi</span>
                                <span>{{ $invoice->estimation }}</span>
                            </div>
                            @endif
                            <div style="display:flex;justify-content:space-between">
                                <span style="color:#94a3b8;font-size:12px">Tipe</span>
                                <span class="badge" style="background:{{ $invoice->type==='recurring' ? '#ede9fe':'#f1f5f9' }};color:{{ $invoice->type==='recurring' ? '#4c1d95':'#475569' }}">
                                    {{ $invoice->type === 'recurring' ? 'Recurring' : 'One-Time' }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Tabel item layanan --}}
                <table class="inv-items-table">
                    <thead>
                        <tr>
                            <th style="width:50%">Layanan</th>
                            <th>Harga</th>
                            <th>Qty</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($invoice->items as $item)
                        <tr>
                            <td>
                                <div style="font-weight:500">{{ $item->service_name }}</div>
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

                {{-- Summary --}}
                <div class="inv-summary">
                    <div class="inv-sum-row">
                        <span class="label">Subtotal</span>
                        <span>Rp {{ number_format($invoice->subtotal, 0, ',', '.') }}</span>
                    </div>
                    @if($invoice->discount > 0)
                    <div class="inv-sum-row discount">
                        <span class="label">Diskon {{ $invoice->voucher?->code ? "({$invoice->voucher->code})" : '' }}</span>
                        <span class="value">- Rp {{ number_format($invoice->discount, 0, ',', '.') }}</span>
                    </div>
                    @endif
                    @if($invoice->use_ppn)
                    <div class="inv-sum-row">
                        <span class="label">PPN 11%</span>
                        <span>Rp {{ number_format($invoice->ppn_amount, 0, ',', '.') }}</span>
                    </div>
                    @endif
                    <div class="inv-sum-row total">
                        <span>Total</span>
                        <span>Rp {{ number_format($invoice->total, 0, ',', '.') }}</span>
                    </div>
                </div>

                {{-- Info rekening bank --}}
                <div class="inv-bank">
                    <div class="inv-bank-title">Informasi Pembayaran</div>
                    <div class="inv-bank-item">
                        <span>Mandiri</span>
                        <span>1234567890 a/n M. Nashirudin Rabbani</span>
                    </div>
                    <div class="inv-bank-item">
                        <span>BCA</span>
                        <span>0987654321 a/n M. Nashirudin Rabbani</span>
                    </div>
                    <div class="inv-bank-item">
                        <span>SeaBank</span>
                        <span>1122334455 a/n M. Nashirudin Rabbani</span>
                    </div>
                </div>

                {{-- Terms & Conditions --}}
                @if($invoice->terms_conditions)
                <div class="inv-tnc">
                    <div class="inv-tnc-label">Terms & Conditions</div>
                    {{ $invoice->terms_conditions }}
                </div>
                @endif

                {{-- Info payment jika sudah lunas --}}
                @if($invoice->payment)
                <div style="margin-top:16px;padding:12px 14px;background:#f0fdf4;border:1px solid #bbf7d0;border-radius:8px;font-size:13px;color:#166534">
                    <strong>✓ Lunas</strong> —
                    dibayar via {{ $invoice->payment->bank_label }}
                    pada {{ $invoice->payment->paid_at->format('d M Y') }}
                    sejumlah Rp {{ number_format($invoice->payment->amount, 0, ',', '.') }}
                </div>
                @endif

            </div>
        </div>

        {{-- Riwayat perubahan --}}
        @if($invoice->logs->isNotEmpty())
        <div class="log-card" style="margin-top:14px">
            <div class="card-header"><span class="card-title">Riwayat Aktivitas</span></div>
            @foreach($invoice->logs->take(5) as $log)
            <div class="log-item">
                <div class="log-dot"></div>
                <div class="log-body">
                    <div class="log-action">{{ ucfirst(str_replace('_', ' ', $log->action)) }}</div>
                    <div class="log-desc">{{ $log->description }}</div>
                    <div class="log-time">{{ $log->created_at->diffForHumans() }} — {{ $log->user?->name }}</div>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>

    {{-- ══ KOLOM KANAN ══ --}}
    <div class="right-panel">

        {{-- Form Tandai Lunas --}}
        @if($invoice->status !== 'paid')
        <div class="pay-form">
            <div class="pay-form-head">
                💳 Tandai Lunas
            </div>
            <div class="pay-form-body">
                <form method="POST" action="{{ route('invoices.pay', $invoice) }}">
                    @csrf

                    {{-- Pilih Bank --}}
                    <div style="font-size:12px;font-weight:500;color:#374151;margin-bottom:8px">Metode Pembayaran</div>
                    <div class="bank-options">
                        <label class="bank-opt" id="bank-mandiri">
                            <input type="radio" name="bank" value="mandiri" onchange="selectBank('mandiri')">
                            <div style="font-size:18px">🏦</div>
                            <div class="bank-name">Mandiri</div>
                            <div class="bank-acc">1234567890</div>
                        </label>
                        <label class="bank-opt" id="bank-bca">
                            <input type="radio" name="bank" value="bca" onchange="selectBank('bca')">
                            <div style="font-size:18px">🏦</div>
                            <div class="bank-name">BCA</div>
                            <div class="bank-acc">0987654321</div>
                        </label>
                        <label class="bank-opt" id="bank-seabank">
                            <input type="radio" name="bank" value="seabank" onchange="selectBank('seabank')">
                            <div style="font-size:18px">🏦</div>
                            <div class="bank-name">SeaBank</div>
                            <div class="bank-acc">1122334455</div>
                        </label>
                    </div>
                    @error('bank')<div style="font-size:11px;color:#dc2626;margin-bottom:8px">{{ $message }}</div>@enderror

                    {{-- Nominal --}}
                    <div class="field" style="margin-bottom:10px">
                        <label>Nominal Diterima (Rp)</label>
                        <input type="number" name="amount"
                               value="{{ old('amount', $invoice->total) }}"
                               min="0" step="1000">
                        @error('amount')<span class="field-error">{{ $message }}</span>@enderror
                    </div>

                    {{-- Tanggal bayar --}}
                    <div class="field" style="margin-bottom:10px">
                        <label>Tanggal Pembayaran</label>
                        <input type="date" name="paid_at"
                               value="{{ old('paid_at', now()->toDateString()) }}">
                        @error('paid_at')<span class="field-error">{{ $message }}</span>@enderror
                    </div>

                    {{-- Catatan --}}
                    <div class="field" style="margin-bottom:14px">
                        <label>Catatan <span class="field-hint">(opsional)</span></label>
                        <textarea name="notes" style="min-height:56px"
                                  placeholder="Transfer dari rekening...">{{ old('notes') }}</textarea>
                    </div>

                    <button type="submit" class="btn btn-success" style="width:100%;justify-content:center;height:42px">
                        ✓ Tandai Lunas
                    </button>
                </form>
            </div>
        </div>
        @else
        {{-- Sudah lunas --}}
        <div style="background:#f0fdf4;border:1px solid #bbf7d0;border-radius:12px;padding:16px;text-align:center">
            <div style="font-size:28px;margin-bottom:8px">✅</div>
            <div style="font-size:14px;font-weight:600;color:#166534">Invoice Lunas</div>
            <div style="font-size:12px;color:#4ade80;margin-top:4px">
                {{ $invoice->payment?->paid_at->format('d M Y') }}
                via {{ $invoice->payment?->bank_label }}
            </div>
            @owneronly
            <form method="POST" action="{{ route('invoices.pay.cancel', $invoice) }}" style="margin-top:12px"
                  onsubmit="return confirm('Batalkan pembayaran ini?')">
                @csrf @method('DELETE')
                <button type="submit" class="btn btn-danger btn-sm" style="width:100%;justify-content:center">
                    Batalkan Pembayaran
                </button>
            </form>
            @endowneronly
        </div>
        @endif

        {{-- Aksi lain --}}
        <div class="card">
            <div class="card-header"><span class="card-title">Aksi</span></div>
            <div class="card-body" style="display:flex;flex-direction:column;gap:8px;padding:14px">
                {{-- Kirim email --}}
                <form method="POST" action="{{ route('invoices.send-email', $invoice) }}">
                    @csrf
                    <button type="submit" class="btn btn-outline" style="width:100%;justify-content:center">
                        <svg viewBox="0 0 16 16" fill="none"><rect x="1" y="3" width="14" height="10" rx="2" stroke="currentColor" stroke-width="1.3"/><path d="M1 5l7 5 7-5" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/></svg>
                        Kirim via Email
                    </button>
                </form>
                {{-- Link WhatsApp --}}
                <button onclick="sendWhatsApp()" class="btn btn-outline" style="width:100%;justify-content:center;color:#16a34a;border-color:#bbf7d0">
                    <svg viewBox="0 0 16 16" fill="none"><path d="M13.5 2.5A6.5 6.5 0 1 0 4.2 12.3L2 14l1.8-2.1A6.5 6.5 0 0 0 13.5 2.5z" stroke="currentColor" stroke-width="1.3"/></svg>
                    Salin Link WhatsApp
                </button>
                {{-- Download PDF --}}
                <a href="{{ route('invoices.download', $invoice) }}" target="_blank"
                   class="btn btn-outline" style="width:100%;justify-content:center">
                    <svg viewBox="0 0 16 16" fill="none"><path d="M8 2v8M5 7l3 3 3-3M3 13h10" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    Download PDF
                </a>
                {{-- Duplikat --}}
                <form method="POST" action="{{ route('invoices.duplicate', $invoice) }}">
                    @csrf
                    <button type="submit" class="btn btn-outline" style="width:100%;justify-content:center">
                        <svg viewBox="0 0 16 16" fill="none"><rect x="1" y="4" width="10" height="11" rx="1.5" stroke="currentColor" stroke-width="1.3"/><path d="M4 4V3a1 1 0 011-1h7a1 1 0 011 1v9a1 1 0 01-1 1h-1" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/></svg>
                        Duplikat Invoice
                    </button>
                </form>
                @if($invoice->status !== 'paid')
                {{-- Hapus --}}
                <form method="POST" action="{{ route('invoices.destroy', $invoice) }}"
                      onsubmit="return confirm('Hapus invoice {{ $invoice->invoice_number }}? Tindakan ini tidak bisa dibatalkan.')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-danger" style="width:100%;justify-content:center">
                        <svg viewBox="0 0 16 16" fill="none"><path d="M2 4h12M5 4V2h6v2M6 7v5M10 7v5M3 4l1 10h8l1-10" stroke="currentColor" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        Hapus Invoice
                    </button>
                </form>
                @endif
            </div>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
function selectBank(bank) {
    document.querySelectorAll('.bank-opt').forEach(el => el.classList.remove('selected'));
    document.getElementById('bank-' + bank)?.classList.add('selected');
}

function sendWhatsApp() {
    fetch('{{ route('invoices.whatsapp', $invoice) }}')
        .then(r => r.json())
        .then(data => {
            window.open(data.url, '_blank');
        });
}
</script>
@endpush
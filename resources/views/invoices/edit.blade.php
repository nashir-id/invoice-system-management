@extends('layouts.app')

@section('title', 'Edit Invoice')

@push('styles')
<style>
    * { box-sizing: border-box; }
    .inv-page { background: var(--bs-body-bg, #f5f6fa); min-height: 100vh; padding: 2rem 1.5rem; }
    .inv-header { display: flex; align-items: flex-start; justify-content: space-between; margin-bottom: 1.75rem; gap: 1rem; }
    .inv-breadcrumb { font-size: .75rem; color: #9ca3af; margin-bottom: .25rem; }
    .inv-breadcrumb a { color: #5B5BD6; text-decoration: none; }
    .inv-title { font-size: 1.25rem; font-weight: 500; color: #111827; margin: 0; }
    .btn-back { display: inline-flex; align-items: center; gap: 6px; padding: 6px 14px; border-radius: 8px; border: 1px solid #e5e7eb; background: #fff; font-size: .82rem; font-weight: 500; color: #6b7280; text-decoration: none; white-space: nowrap; }
    .inv-card, .summary-card { background: #fff; border-radius: 12px; border: 1px solid #e9ecf3; overflow: hidden; margin-bottom: 1rem; }
    .inv-card-head { display: flex; align-items: center; justify-content: space-between; padding: .7rem 1.1rem; border-bottom: 1px solid #f0f2f8; background: #fafbff; }
    .inv-card-head-left { display: flex; align-items: center; gap: 8px; }
    .inv-card-icon { width: 28px; height: 28px; border-radius: 6px; background: #EEEDFE; display: flex; align-items: center; justify-content: center; color: #5B5BD6; font-size: .9rem; flex-shrink: 0; }
    .inv-card-title, .summary-head { font-size: .7rem; font-weight: 600; color: #6b7280; text-transform: uppercase; letter-spacing: .6px; }
    .inv-card-body { padding: 1.1rem; }
    .inv-grid { display: grid; grid-template-columns: 1fr 1fr; gap: .85rem; }
    .inv-field { display: flex; flex-direction: column; gap: 5px; }
    .inv-field.full { grid-column: 1 / -1; }
    .inv-label { font-size: .7rem; font-weight: 600; color: #6b7280; text-transform: uppercase; letter-spacing: .5px; }
    .inv-label .req { color: #ef4444; margin-left: 2px; }
    .inv-input, .inv-select, .inv-textarea { width: 100%; padding: 7px 10px; border: 1px solid #e5e7eb; border-radius: 8px; font-size: .875rem; color: #111827; background: #fff; outline: none; font-family: inherit; }
    .inv-input:focus, .inv-select:focus, .inv-textarea:focus { border-color: #5B5BD6; box-shadow: 0 0 0 3px rgba(91,91,214,.1); }
    .inv-input.is-invalid, .inv-select.is-invalid, .inv-textarea.is-invalid { border-color: #ef4444; }
    .inv-textarea { resize: vertical; line-height: 1.5; }
    .inv-error { font-size: .72rem; color: #ef4444; margin-top: 2px; }
    .type-pills { display: flex; gap: 6px; flex-wrap: wrap; }
    .type-pill input[type=radio] { display: none; }
    .type-pill label { display: block; padding: 6px 13px; border-radius: 8px; border: 1px solid #e5e7eb; font-size: .82rem; color: #6b7280; cursor: pointer; transition: all .15s; }
    .type-pill input:checked + label { border-color: #5B5BD6; background: #EEEDFE; color: #3C3489; }
    .voucher-row { display: flex; }
    .voucher-row .inv-input { border-radius: 8px 0 0 8px; border-right: none; }
    .btn-check { padding: 7px 14px; border: 1px solid #e5e7eb; border-left: none; border-radius: 0 8px 8px 0; background: #f9fafb; font-size: .8rem; font-weight: 600; color: #5B5BD6; cursor: pointer; white-space: nowrap; }
    .voucher-badge { font-size: .75rem; margin-top: 4px; display: flex; align-items: center; gap: 4px; }
    .voucher-ok { color: #16a34a; }
    .voucher-err { color: #ef4444; }
    .items-wrap { width: 100%; overflow-x: auto; -webkit-overflow-scrolling: touch; }
    .items-table { width: 100%; border-collapse: collapse; min-width: 580px; }
    .items-table thead th { font-size: .68rem; font-weight: 600; text-transform: uppercase; letter-spacing: .5px; color: #9ca3af; padding: 8px; background: #fafbff; border-bottom: 1px solid #f0f2f8; white-space: nowrap; text-align: left; }
    .items-table td { padding: 5px; vertical-align: middle; border-bottom: 1px solid #f7f8fc; }
    .subtotal-cell { font-size: .85rem; font-weight: 500; color: #374151; background: #fafbff; text-align: right; padding-right: 10px; white-space: nowrap; }
    .btn-add-item { display: inline-flex; align-items: center; gap: 5px; padding: 5px 12px; border-radius: 8px; background: #5B5BD6; color: #fff; border: none; font-size: .78rem; font-weight: 600; cursor: pointer; }
    .btn-remove { min-width: 68px; height: 30px; border-radius: 6px; border: 1px solid #fecaca; background: #fff7f7; color: #dc2626; font-size: .78rem; font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; justify-content: center; }
    .btn-remove:hover:not(:disabled) { border-color: #fca5a5; color: #b91c1c; background: #fee2e2; }
    .btn-remove:disabled { opacity: .3; cursor: not-allowed; }
    .summary-card { position: sticky; top: 80px; }
    .summary-head { padding: .7rem 1.1rem; border-bottom: 1px solid #f0f2f8; background: #fafbff; display: flex; align-items: center; gap: 7px; }
    .summary-body { padding: 1rem 1.1rem; }
    .sum-row, .ppn-row { display: flex; justify-content: space-between; align-items: center; padding: 7px 0; font-size: .85rem; color: #6b7280; border-bottom: 1px solid #f7f8fc; }
    .sum-val { font-weight: 500; color: #111827; }
    .sum-val.green { color: #16a34a; }
    .sum-total { display: flex; justify-content: space-between; align-items: center; padding: 12px 0 8px; margin-top: 4px; border-top: 1px solid #e9ecf3; }
    .sum-total-label { font-size: .95rem; font-weight: 500; color: #111827; }
    .sum-total-val { font-size: 1.4rem; font-weight: 600; color: #5B5BD6; }
    .ppn-label { display: flex; align-items: center; gap: 8px; cursor: pointer; }
    .toggle { position: relative; width: 34px; height: 18px; flex-shrink: 0; }
    .toggle input { opacity: 0; width: 0; height: 0; }
    .toggle-slider { position: absolute; inset: 0; border-radius: 18px; background: #e5e7eb; cursor: pointer; transition: background .2s; }
    .toggle-slider::before { content: ''; position: absolute; width: 12px; height: 12px; border-radius: 50%; background: #fff; left: 3px; top: 3px; transition: transform .2s; box-shadow: 0 1px 3px rgba(0,0,0,.15); }
    .toggle input:checked + .toggle-slider { background: #5B5BD6; }
    .toggle input:checked + .toggle-slider::before { transform: translateX(16px); }
    .btn-submit { width: 100%; padding: 10px; border-radius: 9px; background: #5B5BD6; color: #fff; border: none; font-size: .9rem; font-weight: 600; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 6px; margin-bottom: 8px; }
    .btn-cancel { width: 100%; padding: 9px; border-radius: 9px; background: transparent; color: #6b7280; border: 1px solid #e5e7eb; font-size: .85rem; font-weight: 500; text-align: center; text-decoration: none; display: block; }
    .summary-note { font-size: .7rem; color: #9ca3af; text-align: center; margin-top: 8px; }
    @media (max-width: 991px) { .inv-page { padding: 1.25rem 0; } .inv-layout { grid-template-columns: 1fr !important; } .summary-card { position: static; } }
    @media (max-width: 768px) {
        .inv-card-head { align-items: flex-start; gap: 10px; flex-direction: column; }
        .inv-grid { grid-template-columns: 1fr; }
        .voucher-row { flex-direction: column; gap: 8px; }
        .voucher-row .inv-input,
        .btn-check { width: 100%; border-radius: 8px; border: 1px solid #e5e7eb; }
        .summary-body { padding: .9rem; }
        .sum-total { align-items: flex-start; flex-direction: column; gap: 4px; }
        .sum-total-val { font-size: 1.2rem; overflow-wrap: anywhere; }
    }
    @media (max-width: 576px) { .inv-page { padding: .75rem 0; } .inv-header { flex-direction: column; } .btn-back { width: 100%; justify-content: center; } .items-table { min-width: 640px; } }
</style>
@endpush

@section('content')
@php
    $formItems = old('items', $invoice->items->map(fn ($item) => [
        'service_name' => $item->service_name,
        'description' => $item->description,
        'price' => $item->price,
        'quantity' => $item->quantity,
    ])->values()->toArray());
@endphp

<div class="inv-page">
<div class="container-fluid" style="max-width: 1400px;">
    <div class="inv-header">
        <div>
            <p class="inv-breadcrumb">
                <a href="{{ route('invoices.index') }}">Invoice</a> / Edit / {{ $invoice->invoice_number }}
            </p>
            <h1 class="inv-title">Edit invoice {{ $invoice->invoice_number }}</h1>
        </div>
        <a href="{{ route('invoices.show', $invoice) }}" class="btn-back">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>

    <form action="{{ route('invoices.update', $invoice) }}" method="POST" id="invoiceForm">
        @csrf
        @method('PUT')

        <div class="row g-3 inv-layout" style="display:grid; grid-template-columns: 1fr 290px; gap: 1rem; align-items: start;">
            <div>
                <div class="inv-card">
                    <div class="inv-card-head">
                        <div class="inv-card-head-left">
                            <div class="inv-card-icon"><i class="bi bi-file-earmark-text"></i></div>
                            <span class="inv-card-title">Informasi invoice</span>
                        </div>
                    </div>
                    <div class="inv-card-body">
                        <div class="inv-grid">
                            <div class="inv-field">
                                <label class="inv-label">Klien <span class="req">*</span></label>
                                <select name="client_id" id="client_id" class="inv-select @error('client_id') is-invalid @enderror" required>
                                    <option value="">Pilih klien...</option>
                                    @foreach($clients as $client)
                                        <option value="{{ $client->id }}" {{ old('client_id', $invoice->client_id) == $client->id ? 'selected' : '' }}>
                                            {{ $client->company_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('client_id')<p class="inv-error">{{ $message }}</p>@enderror
                            </div>

                            <div class="inv-field">
                                <label class="inv-label">Tipe invoice <span class="req">*</span></label>
                                <div class="type-pills">
                                    <div class="type-pill">
                                        <input type="radio" name="type" id="type_one_time" value="one_time" {{ old('type', $invoice->type) == 'one_time' ? 'checked' : '' }} required>
                                        <label for="type_one_time">One-Time</label>
                                    </div>
                                    <div class="type-pill">
                                        <input type="radio" name="type" id="type_recurring" value="recurring" {{ old('type', $invoice->type) == 'recurring' ? 'checked' : '' }}>
                                        <label for="type_recurring">Recurring</label>
                                    </div>
                                </div>
                                @error('type')<p class="inv-error">{{ $message }}</p>@enderror
                            </div>

                            <div class="inv-field">
                                <label class="inv-label">Tanggal invoice <span class="req">*</span></label>
                                <input type="date" name="invoice_date" class="inv-input @error('invoice_date') is-invalid @enderror" value="{{ old('invoice_date', $invoice->invoice_date?->toDateString()) }}" required>
                                @error('invoice_date')<p class="inv-error">{{ $message }}</p>@enderror
                            </div>

                            <div class="inv-field">
                                <label class="inv-label">Jatuh tempo</label>
                                <input type="date" name="due_date" class="inv-input @error('due_date') is-invalid @enderror" value="{{ old('due_date', $invoice->due_date?->toDateString()) }}">
                                @error('due_date')<p class="inv-error">{{ $message }}</p>@enderror
                            </div>

                            <div class="inv-field">
                                <label class="inv-label">Estimasi pengerjaan</label>
                                <input type="text" name="estimation" class="inv-input @error('estimation') is-invalid @enderror" placeholder="Contoh: 7 hari kerja" value="{{ old('estimation', $invoice->estimation) }}">
                                @error('estimation')<p class="inv-error">{{ $message }}</p>@enderror
                            </div>

                            <div class="inv-field">
                                <label class="inv-label">Kode voucher</label>
                                <div class="voucher-row">
                                    <input type="text" name="voucher_code" id="voucher_code" class="inv-input @error('voucher_code') is-invalid @enderror" placeholder="NASHIR2024" value="{{ old('voucher_code', $invoice->voucher?->code) }}">
                                    <button type="button" id="checkVoucher" class="btn-check">Cek</button>
                                </div>
                                <div id="voucherInfo">
                                    @if($invoice->discount > 0)
                                        <p class="voucher-badge voucher-ok"><i class="bi bi-check-circle-fill"></i> Diskon tersimpan: Rp {{ number_format($invoice->discount, 0, ',', '.') }}</p>
                                    @endif
                                </div>
                                @error('voucher_code')<p class="inv-error">{{ $message }}</p>@enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="inv-card">
                    <div class="inv-card-head">
                        <div class="inv-card-head-left">
                            <div class="inv-card-icon"><i class="bi bi-list-ul"></i></div>
                            <span class="inv-card-title">Item layanan</span>
                        </div>
                        <button type="button" id="addItem" class="btn-add-item">
                            <i class="bi bi-plus-lg"></i> Tambah item
                        </button>
                    </div>
                    <div class="items-wrap">
                        <table class="items-table" id="itemsTable">
                            <thead>
                                <tr>
                                    <th style="width:26%">Nama layanan</th>
                                    <th>Deskripsi</th>
                                    <th style="width:17%">Harga (Rp)</th>
                                    <th style="width:8%">Qty</th>
                                    <th style="width:15%; text-align:right">Subtotal</th>
                                    <th style="width:10%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="itemsBody">
                                @foreach($formItems as $idx => $item)
                                    <tr class="item-row">
                                        <td><input type="text" name="items[{{ $idx }}][service_name]" class="inv-input" placeholder="Nama layanan" value="{{ $item['service_name'] ?? '' }}" required></td>
                                        <td><input type="text" name="items[{{ $idx }}][description]" class="inv-input" placeholder="Opsional" value="{{ $item['description'] ?? '' }}"></td>
                                        <td><input type="number" name="items[{{ $idx }}][price]" class="inv-input item-price" placeholder="0" min="0" value="{{ $item['price'] ?? 0 }}" required></td>
                                        <td><input type="number" name="items[{{ $idx }}][quantity]" class="inv-input item-qty" value="{{ $item['quantity'] ?? 1 }}" min="1" required></td>
                                        <td class="subtotal-cell">-</td>
                                        <td style="text-align:center">
                                            <button type="button" class="btn-remove remove-item" {{ count($formItems) === 1 ? 'disabled' : '' }}>
                                                Hapus
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="inv-card">
                    <div class="inv-card-head">
                        <div class="inv-card-head-left">
                            <div class="inv-card-icon"><i class="bi bi-chat-left-quote"></i></div>
                            <span class="inv-card-title">Catatan &amp; syarat</span>
                        </div>
                    </div>
                    <div class="inv-card-body">
                        <div class="inv-grid">
                            <div class="inv-field full">
                                <label class="inv-label">Catatan</label>
                                <textarea name="notes" rows="3" class="inv-textarea @error('notes') is-invalid @enderror" placeholder="Catatan tambahan untuk klien...">{{ old('notes', $invoice->notes) }}</textarea>
                                @error('notes')<p class="inv-error">{{ $message }}</p>@enderror
                            </div>
                            <div class="inv-field full">
                                <label class="inv-label">Syarat &amp; ketentuan</label>
                                <textarea name="terms_conditions" rows="4" class="inv-textarea @error('terms_conditions') is-invalid @enderror">{{ old('terms_conditions', $invoice->terms_conditions ?: $defaultTnC) }}</textarea>
                                @error('terms_conditions')<p class="inv-error">{{ $message }}</p>@enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div>
                <div class="summary-card">
                    <div class="summary-head">
                        <i class="bi bi-receipt"></i> Ringkasan pembayaran
                    </div>
                    <div class="summary-body">
                        <div class="sum-row">
                            <span>Subtotal</span>
                            <span class="sum-val" id="summarySubtotal">Rp 0</span>
                        </div>
                        <div class="sum-row">
                            <span>Diskon voucher</span>
                            <span class="sum-val green" id="summaryDiscount">- Rp 0</span>
                        </div>
                        <div class="ppn-row">
                            <label class="ppn-label" for="usePpn">
                                <label class="toggle">
                                    <input type="checkbox" name="use_ppn" id="usePpn" value="1" {{ old('use_ppn', $invoice->use_ppn) ? 'checked' : '' }}>
                                    <span class="toggle-slider"></span>
                                </label>
                                PPN 11%
                            </label>
                            <span class="sum-val" id="summaryPpn">+ Rp 0</span>
                        </div>

                        <div class="sum-total">
                            <span class="sum-total-label">Total</span>
                            <span class="sum-total-val" id="summaryTotal">Rp 0</span>
                        </div>

                        <button type="submit" class="btn-submit">
                            <i class="bi bi-save-fill"></i> Simpan perubahan
                        </button>
                        <a href="{{ route('invoices.show', $invoice) }}" class="btn-cancel">Batal</a>
                        <p class="summary-note">Invoice lunas tidak dapat diedit.</p>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
</div>
@endsection

@push('scripts')
<script>
(function () {
    'use strict';

    let itemIndex = {{ count($formItems) }};
    let discountAmt = {{ (float) old('discount', $invoice->discount) }};

    function fmtRp(n) {
        return 'Rp ' + Math.round(n).toLocaleString('id-ID');
    }

    function recalculate() {
        let subtotal = 0;
        document.querySelectorAll('#itemsBody .item-row').forEach(function(row) {
            const price = parseFloat(row.querySelector('.item-price').value) || 0;
            const qty = parseFloat(row.querySelector('.item-qty').value) || 0;
            const sub = price * qty;
            row.querySelector('.subtotal-cell').textContent = sub > 0 ? fmtRp(sub) : '-';
            subtotal += sub;
        });

        const afterDisc = Math.max(subtotal - discountAmt, 0);
        const ppnAmt = document.getElementById('usePpn').checked ? Math.round(afterDisc * 0.11) : 0;
        const total = afterDisc + ppnAmt;

        document.getElementById('summarySubtotal').textContent = fmtRp(subtotal);
        document.getElementById('summaryDiscount').textContent = discountAmt > 0 ? '- ' + fmtRp(discountAmt) : '- Rp 0';
        document.getElementById('summaryPpn').textContent = '+ ' + fmtRp(ppnAmt);
        document.getElementById('summaryTotal').textContent = fmtRp(total);
    }

    function makeRow(idx) {
        const tr = document.createElement('tr');
        tr.className = 'item-row';
        tr.innerHTML =
            '<td><input type="text" name="items[' + idx + '][service_name]" class="inv-input" placeholder="Nama layanan" required></td>' +
            '<td><input type="text" name="items[' + idx + '][description]" class="inv-input" placeholder="Opsional"></td>' +
            '<td><input type="number" name="items[' + idx + '][price]" class="inv-input item-price" placeholder="0" min="0" required></td>' +
            '<td><input type="number" name="items[' + idx + '][quantity]" class="inv-input item-qty" value="1" min="1" required></td>' +
            '<td class="subtotal-cell">-</td>' +
            '<td style="text-align:center"><button type="button" class="btn-remove remove-item">Hapus</button></td>';
        return tr;
    }

    function updateRemoveButtons() {
        const rows = document.querySelectorAll('#itemsBody .item-row');
        rows.forEach(function(row) {
            row.querySelector('.remove-item').disabled = rows.length === 1;
        });
    }

    document.getElementById('addItem').addEventListener('click', function () {
        document.getElementById('itemsBody').appendChild(makeRow(itemIndex++));
        updateRemoveButtons();
    });

    document.getElementById('itemsBody').addEventListener('click', function (event) {
        const btn = event.target.closest('.remove-item');
        if (! btn) return;
        btn.closest('.item-row').remove();
        updateRemoveButtons();
        recalculate();
    });

    document.getElementById('itemsBody').addEventListener('input', function (event) {
        if (event.target.matches('.item-price, .item-qty')) recalculate();
    });

    document.getElementById('usePpn').addEventListener('change', recalculate);

    document.getElementById('checkVoucher').addEventListener('click', function () {
        const code = document.getElementById('voucher_code').value.trim();
        const info = document.getElementById('voucherInfo');

        if (! code) {
            discountAmt = 0;
            info.innerHTML = '<p class="voucher-badge voucher-err"><i class="bi bi-exclamation-circle"></i> Masukkan kode voucher.</p>';
            recalculate();
            return;
        }

        this.disabled = true;
        this.textContent = '...';

        fetch('{{ route('vouchers.validate') }}', {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ code: code })
        })
        .then(function(response) { return response.json(); })
        .then(function(data) {
            if (data.valid) {
                discountAmt = data.discount_amount || 0;
                info.innerHTML = '<p class="voucher-badge voucher-ok"><i class="bi bi-check-circle-fill"></i> Voucher valid - diskon ' + fmtRp(discountAmt) + '</p>';
            } else {
                discountAmt = 0;
                info.innerHTML = '<p class="voucher-badge voucher-err"><i class="bi bi-x-circle"></i> ' + (data.message || 'Voucher tidak valid.') + '</p>';
            }
            recalculate();
        })
        .catch(function() {
            info.innerHTML = '<p class="voucher-badge voucher-err"><i class="bi bi-wifi-off"></i> Gagal memeriksa voucher.</p>';
        })
        .finally(function() {
            document.getElementById('checkVoucher').disabled = false;
            document.getElementById('checkVoucher').textContent = 'Cek';
        });
    });

    updateRemoveButtons();
    recalculate();
})();
</script>
@endpush

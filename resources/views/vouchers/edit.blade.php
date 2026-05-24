@extends('layouts.app')
@section('title', 'Edit Voucher')
@section('page-title', 'Edit Voucher')
@section('breadcrumb')
    <a href="{{ route('vouchers.index') }}">Voucher</a> / Edit {{ $voucher->code }}
@endsection

@section('content')
@php $hideGlobalErrors = true; @endphp

<div style="max-width:1400px">
<form method="POST" action="{{ route('vouchers.update', $voucher) }}">
    @csrf
    @method('PUT')

    <div class="card" style="margin-bottom:16px">
        <div class="card-header"><span class="card-title">Edit Data Voucher</span></div>
        <div class="card-body" style="display:flex;flex-direction:column;gap:14px">

            {{-- Kode --}}
            <div class="field">
                <label>Kode Voucher <span style="color:#dc2626">*</span></label>
                <input type="text" name="code"
                       value="{{ old('code', $voucher->code) }}"
                       style="text-transform:uppercase"
                       class="{{ $errors->has('code') ? 'is-error' : '' }}">
                @error('code')<span class="field-error">{{ $message }}</span>@enderror
            </div>

            {{-- Nominal diskon --}}
            <div class="field">
                <label>Nominal Diskon (Rp) <span style="color:#dc2626">*</span></label>
                <input type="number" name="discount_amount"
                       value="{{ old('discount_amount', $voucher->discount_amount) }}"
                       min="0"
                       class="{{ $errors->has('discount_amount') ? 'is-error' : '' }}">
                @error('discount_amount')<span class="field-error">{{ $message }}</span>@enderror
            </div>

            {{-- Deskripsi --}}
            <div class="field">
                <label>Deskripsi <span class="field-hint">(opsional)</span></label>
                <input type="text" name="description"
                       value="{{ old('description', $voucher->description) }}"
                       placeholder="Keterangan voucher">
                @error('description')<span class="field-error">{{ $message }}</span>@enderror
            </div>

            {{-- Kadaluarsa --}}
            <div class="field">
                <label>Tanggal Kadaluarsa <span class="field-hint">(kosongkan jika tidak ada)</span></label>
                <input type="date" name="valid_until"
                       value="{{ old('valid_until', $voucher->valid_until?->toDateString()) }}">
                @error('valid_until')<span class="field-error">{{ $message }}</span>@enderror
            </div>

            {{-- Info dipakai --}}
            <div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:8px;padding:10px 14px;font-size:12px;color:#64748b">
                Voucher ini sudah dipakai di
                <strong>{{ $voucher->invoices_count ?? 0 }}</strong> invoice.
            </div>

        </div>
    </div>

    <div style="display:flex;gap:10px;justify-content:flex-end">
        <a href="{{ route('vouchers.index') }}" class="btn btn-outline">Batal</a>
        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
    </div>
</form>
</div>

@endsection
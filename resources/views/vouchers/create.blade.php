@extends('layouts.app')
@section('title', 'Tambah Voucher')
@section('page-title', 'Tambah Voucher')
@section('breadcrumb')
    <a href="{{ route('vouchers.index') }}">Voucher</a> / Tambah
@endsection

@section('content')
@php $hideGlobalErrors = true; @endphp

<div style="max-width:1400px">
<form method="POST" action="{{ route('vouchers.store') }}">
    @csrf

    <div class="card" style="margin-bottom:16px">
        <div class="card-header"><span class="card-title">Data Voucher</span></div>
        <div class="card-body" style="display:flex;flex-direction:column;gap:14px">

            {{-- Kode --}}
            <div class="field">
                <label>Kode Voucher <span style="color:#dc2626">*</span></label>
                <input type="text" name="code"
                       value="{{ old('code') }}"
                       placeholder="Contoh: DISC10"
                       style="text-transform:uppercase"
                       class="{{ $errors->has('code') ? 'is-error' : '' }}">
                <span class="field-hint">Kode akan otomatis diubah ke huruf kapital.</span>
                @error('code')<span class="field-error">{{ $message }}</span>@enderror
            </div>

            {{-- Nominal diskon --}}
            <div class="field">
                <label>Nominal Diskon (Rp) <span style="color:#dc2626">*</span></label>
                <input type="number" name="discount_amount"
                       value="{{ old('discount_amount') }}"
                       placeholder="Contoh: 100000"
                       min="0"
                       class="{{ $errors->has('discount_amount') ? 'is-error' : '' }}">
                @error('discount_amount')<span class="field-error">{{ $message }}</span>@enderror
            </div>

            {{-- Deskripsi --}}
            <div class="field">
                <label>Deskripsi <span class="field-hint">(opsional)</span></label>
                <input type="text" name="description"
                       value="{{ old('description') }}"
                       placeholder="Contoh: Diskon spesial bulan April">
                @error('description')<span class="field-error">{{ $message }}</span>@enderror
            </div>

            {{-- Kadaluarsa --}}
            <div class="field">
                <label>Tanggal Kadaluarsa <span class="field-hint">(opsional — kosongkan jika tidak ada)</span></label>
                <input type="date" name="valid_until"
                       value="{{ old('valid_until') }}"
                       min="{{ now()->addDay()->toDateString() }}">
                @error('valid_until')<span class="field-error">{{ $message }}</span>@enderror
            </div>

        </div>
    </div>

    <div style="display:flex;gap:10px;justify-content:flex-end">
        <a href="{{ route('vouchers.index') }}" class="btn btn-outline">Batal</a>
        <button type="submit" class="btn btn-primary">Simpan Voucher</button>
    </div>
</form>
</div>

@endsection
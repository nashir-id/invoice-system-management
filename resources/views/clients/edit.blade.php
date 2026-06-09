@extends('layouts.app')
@section('title', 'Edit Klien')
@section('page-title', 'Edit Klien')
@section('breadcrumb')
    <a href="{{ route('clients.index') }}">Klien</a> /
    <a href="{{ route('clients.show', $client) }}">{{ $client->company_name }}</a> /
    Edit
@endsection

@section('content')
@php $hideGlobalErrors = true; @endphp

<div style="max-width:1400px">
<form method="POST" action="{{ route('clients.update', $client) }}">
    @csrf
    @method('PUT')

    <div class="card" style="margin-bottom:16px">
        <div class="card-header">
            <span class="card-title">Edit Data Klien</span>
        </div>
        <div class="card-body">
            <div class="form-grid">

                {{-- Nama Perusahaan --}}
                <div class="field form-full">
                    <label>Nama Perusahaan / Klien <span style="color:#dc2626">*</span></label>
                    <input type="text" name="company_name"
                           value="{{ old('company_name', $client->company_name) }}"
                           placeholder="Contoh: PT. PPLI"
                           class="{{ $errors->has('company_name') ? 'is-error' : '' }}">
                    @error('company_name')
                        <span class="field-error">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Nama PIC --}}
                <div class="field">
                    <label>Nama PIC (Person in Charge)</label>
                    <input type="text" name="pic_name"
                           value="{{ old('pic_name', $client->pic_name) }}"
                           placeholder="Nama kontak klien">
                    @error('pic_name')
                        <span class="field-error">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Nomor HP --}}
                <div class="field">
                    <label>Nomor HP / WhatsApp</label>
                    <input type="text" name="phone"
                           value="{{ old('phone', $client->phone) }}"
                           placeholder="Contoh: 08123456789">
                    @error('phone')
                        <span class="field-error">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Email --}}
                <div class="field">
                    <label>Email</label>
                    <input type="email" name="email"
                           value="{{ old('email', $client->email) }}"
                           placeholder="email@perusahaan.com">
                    @error('email')
                        <span class="field-error">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Website --}}
                <div class="field">
                    <label>Website</label>
                    <input type="text" name="website"
                           value="{{ old('website', $client->website) }}"
                           placeholder="Contoh: ppli.co.id">
                    @error('website')
                        <span class="field-error">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Alamat --}}
                <div class="field form-full">
                    <label>Alamat Fisik <span class="field-hint">(opsional)</span></label>
                    <textarea name="address"
                              placeholder="Alamat lengkap">{{ old('address', $client->address) }}</textarea>
                    @error('address')
                        <span class="field-error">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Catatan --}}
                <div class="field form-full">
                    <label>Catatan Internal <span class="field-hint">(tidak tampil ke klien)</span></label>
                    <textarea name="notes"
                              placeholder="Catatan khusus tentang klien ini...">{{ old('notes', $client->notes) }}</textarea>
                    @error('notes')
                        <span class="field-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="field">
                    <label>Kode Login Klien <span style="color:#dc2626">(wajib diisi)</span></label>
                    <input type="text" name="client_login_code"
                           value="{{ old('client_login_code', $client->client_login_code) }}"
                           placeholder="Contoh: CLI-ABC12345"
                           class="{{ $errors->has('client_login_code') ? 'is-error' : '' }}">
                    @error('client_login_code')
                        <span class="field-error">{{ $message }}</span>
                    @enderror
                </div>

            </div>
        </div>
    </div>

    {{-- Info terakhir diupdate --}}
    <div style="font-size:12px;color:#94a3b8;margin-bottom:12px">
        Terakhir diperbarui: {{ $client->updated_at->format('d M Y, H:i') }}
    </div>

    {{-- Tombol aksi --}}
    <div style="display:flex;gap:10px;justify-content:flex-end">
        <a href="{{ route('clients.show', $client) }}" class="btn btn-outline">
            Batal
        </a>
        <button type="submit" class="btn btn-primary">
            <svg viewBox="0 0 16 16" fill="none" style="width:14px;height:14px">
                <path d="M2 8l4 4 8-8" stroke="white" stroke-width="1.8"
                      stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            Simpan Perubahan
        </button>
    </div>

</form>
</div>

@endsection

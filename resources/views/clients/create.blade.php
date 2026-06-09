@extends('layouts.app')
@section('title', 'Tambah Klien')
@section('page-title', 'Tambah Klien')
@section('breadcrumb') <a href="{{ route('clients.index') }}">Klien</a> / Tambah @endsection

@section('content')
@php $hideGlobalErrors = true; @endphp

<div style="max-width:1400px">
<form method="POST" action="{{ route('clients.store') }}">
    @csrf

    <div class="card" style="margin-bottom:16px">
        <div class="card-header"><span class="card-title">Data Klien</span></div>
        <div class="card-body">
            <div class="form-grid">

                <div class="field form-full">
                    <label>Nama Perusahaan / Klien <span style="color:#dc2626"></span></label>
                    <input type="text" name="company_name" value="{{ old('company_name') }}"
                        placeholder="Contoh: PT.example"
                        class="{{ $errors->has('company_name') ? 'is-error' : '' }}">
                    @error('company_name')<span class="field-error">{{ $message }}</span>@enderror
                </div>

                <div class="field">
                    <label>Nama PIC (Person in Charge)</label>
                    <input type="text" name="pic_name" value="{{ old('pic_name') }}" placeholder="Nama kontak klien">
                    @error('pic_name')<span class="field-error">{{ $message }}</span>@enderror
                </div>

                <div class="field">
                    <label>Nomor HP / WhatsApp</label>
                    <input type="text" name="phone" value="{{ old('phone') }}" placeholder="Contoh: 08123456789">
                    @error('phone')<span class="field-error">{{ $message }}
                    </span>@enderror
                </div>

                <div class="field">
                    <label>Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="email@perusahaan.com">
                    @error('email')<span class="field-error">{{ $message }}
                    </span>@enderror
                </div>

                <div class="field">
                    <label>Website</label>
                    <input type="text" name="website" value="{{ old('website') }}" placeholder="Contoh: example.com">
                    @error('website')<span class="field-error">{{ $message }}
                    </span>@enderror
                </div>

                <div class="field form-full">
                    <label>Alamat Fisik <span class="field-hint">(opsional)</span></label>
                    <textarea name="address" placeholder="Alamat lengkap">{{ old('address') }}</textarea>
                    @error('address')<span class="field-error">{{ $message }}
                    </span>@enderror
                </div>

                <div class="field form-full">
                    <label>Catatan Internal <span class="field-hint">(tidak tampil ke klien)</span></label>
                    <textarea name="notes" placeholder="Catatan khusus tentang klien ini...">{{ old('notes') }}</textarea>
                    @error('notes')<span class="field-error">{{ $message }}
                    </span>@enderror
                </div>

                <div class="field">
                    <label>Kode Login Klien <span style="color:#dc2626">(wajib diisi)</span></label>
                    <input type="text" name="client_login_code" value="{{ old('client_login_code') }}"
                        placeholder="Contoh: CLI-ABC12345"
                        class="{{ $errors->has('client_login_code') ? 'is-error' : '' }}">
                    @error('client_login_code')<span class="field-error">{{ $message }}</span>@enderror
                </div>

            </div>
        </div>
    </div>

    <div style="display:flex;gap:10px;justify-content:flex-end">
        <a href="{{ route('clients.index') }}" class="btn btn-outline">Batal</a>
        <button type="submit" class="btn btn-primary">Simpan Klien</button>
    </div>
</form>
</div>

@endsection

@extends('layouts.app')

@section('title', 'Pengaturan Sistem')
@section('page-title', 'Pengaturan Sistem')

@section('content')

@if(session('success'))
<div class="alert alert-success">
    {{ session('success') }}
</div>
@endif

<div class="stats-grid">

    <div class="stat-card">
        <div class="stat-label">
            Total User
        </div>

        <div class="stat-value">
            {{ $totalUsers }}
        </div>

        <div class="stat-sub text-muted">
            Semua Pengguna
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-label">
            Owner
        </div>

        <div class="stat-value">
            {{ $owners }}
        </div>

        <div class="stat-sub text-green">
            Role Owner
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-label">
            Admin
        </div>

        <div class="stat-value">
            {{ $admins }}
        </div>

        <div class="stat-sub text-green">
            Role Admin
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-label">
            Voucher Aktif
        </div>

        <div class="stat-value">
            {{ $activeVouchers }}
        </div>

        <div class="stat-sub text-orange">
            Promo Aktif
        </div>
    </div>

    {{-- <div class="stat-card">
        <div class="stat-label">
            BANK
        </div>

        <div class="stat-value">
            {{ $Setting }}
        </div>

        <div class="stat-sub text-pink">
            Bank Aktif
        </div>
    </div> --}}

</div>

<form action="{{ route('settings.update') }}"
      method="POST"
      enctype="multipart/form-data">

    @csrf
    @method('PUT')

    {{-- Profil Bisnis --}}
<div class="card">

    <div class="card-header">
        <span class="card-title">
            Profil Bisnis
        </span>
    </div>

    <div class="card-body">

        <div style="
            display:grid;
            grid-template-columns:250px 1fr;
            gap:30px;
        ">

            {{-- Upload Logo --}}
            <div>

                <label style="font-weight:600;">
                    Logo Brand
                </label>

                <div style="
                    width:200px;
                    height:200px;
                    border:1px solid #e5e7eb;
                    border-radius:12px;
                    overflow:hidden;
                    margin-top:10px;
                    background:#fff;
                    display:flex;
                    justify-content:center;
                    align-items:center;
                ">

                    @if($setting->logo)

                        <img
                            id="logoPreview"
                            src="{{ asset('storage/'.$setting->logo) }}"
                            style="
                                width:100%;
                                height:100%;
                                object-fit:contain;
                            ">

                    @else

                        <img
                            id="logoPreview"
                            src="https://via.placeholder.com/180x180?text=Logo"
                            style="
                                width:100%;
                                height:100%;
                                object-fit:contain;
                            ">

                    @endif

                </div>

                <br>

                <input
                    type="file"
                    name="logo"
                    id="logo"
                    accept=".jpg,.jpeg,.png,.webp"
                    onchange="previewLogo(event)">

                <small style="display:block;margin-top:8px;color:#64748b;">
                    Format JPG, PNG, WEBP (maks 2MB)
                </small>

            </div>

            {{-- Data Bisnis --}}
            <div>

                <div class="field">
                    <label>Nama Brand</label>

                    <input
                        type="text"
                        name="business_name"
                        value="{{ old('business_name',$setting->business_name) }}">
                </div>

                <div class="field">
                    <label>Tagline</label>

                    <input
                        type="text"
                        name="tagline"
                        value="{{ old('tagline',$setting->tagline) }}">
                </div>

                <div class="field">
                    <label>Email Bisnis</label>

                    <input
                        type="email"
                        name="business_email"
                        value="{{ old('business_email',$setting->business_email) }}">
                </div>

                <div class="field">
                    <label>Website</label>

                    <input
                        type="text"
                        name="website"
                        value="{{ old('website',$setting->website) }}">
                </div>

            </div>

        </div>

    </div>

</div>
    {{-- Rekening Bank --}}
    <div class="card mt-4">

        <div class="card-header">
            <span class="card-title">
                Rekening Bank
            </span>
        </div>

        <div class="card-body">

            <h4>Bank Mandiri</h4>

            <div class="field">
                <label>Nama Bank</label>
                <input type="text"
                       name="mandiri_name"
                       value="{{ old('mandiri_name',$setting->mandiri_name) }}">
            </div>

            <div class="field">
                <label>Nomor Rekening</label>
                <input type="text"
                       name="mandiri_number"
                       value="{{ old('mandiri_number',$setting->mandiri_number) }}">
            </div>

            <div class="field">
                <label>Atas Nama</label>
                <input type="text"
                       name="mandiri_holder"
                       value="{{ old('mandiri_holder',$setting->mandiri_holder) }}">
            </div>

            <hr>

            <h4>Bank BCA</h4>

            <div class="field">
                <label>Nama Bank</label>
                <input type="text"
                       name="bca_name"
                       value="{{ old('bca_name',$setting->bca_name) }}">
            </div>

            <div class="field">
                <label>Nomor Rekening</label>
                <input type="text"
                       name="bca_number"
                       value="{{ old('bca_number',$setting->bca_number) }}">
            </div>

            <div class="field">
                <label>Atas Nama</label>
                <input type="text"
                       name="bca_holder"
                       value="{{ old('bca_holder',$setting->bca_holder) }}">
            </div>

            <hr>

            <h4>SeaBank</h4>

            <div class="field">
                <label>Nama Bank</label>
                <input type="text"
                       name="seabank_name"
                       value="{{ old('seabank_name',$setting->seabank_name) }}">
            </div>

            <div class="field">
                <label>Nomor Rekening</label>
                <input type="text"
                       name="seabank_number"
                       value="{{ old('seabank_number',$setting->seabank_number) }}">
            </div>

            <div class="field">
                <label>Atas Nama</label>
                <input type="text"
                       name="seabank_holder"
                       value="{{ old('seabank_holder',$setting->seabank_holder) }}">
            </div>

            <br>

            <button class="btn btn-primary">
                Simpan Pengaturan
            </button>

        </div>

    </div>

</form>

{{-- Audit Trail --}}
<div class="card mt-4">

    <div class="card-header">
        <span class="card-title">
            Audit Trail
        </span>
    </div>

    <div class="table-wrap">

        <table>

            <thead>
                <tr>
                    <th>User</th>
                    <th>Modul</th>
                    <th>Aksi</th>
                    <th>Deskripsi</th>
                    <th>Waktu</th>
                </tr>
            </thead>

            <tbody>

            @forelse($logs as $log)

                <tr>

                    <td>
                        {{ $log->user?->name ?? '-' }}
                    </td>

                    <td>
                        {{ $log->module }}
                    </td>

                    <td>
                        {{ $log->action }}
                    </td>

                    <td>
                        {{ $log->description }}
                    </td>

                    <td>
                        {{ $log->created_at->format('d M Y H:i') }}
                    </td>

                </tr>

            @empty

                <tr>
                    <td colspan="5">
                        Belum ada aktivitas.
                    </td>
                </tr>

            @endforelse

            </tbody>

        </table>

    </div>

</div>

<script>
function previewLogo(event)
{
    const reader = new FileReader();

    reader.onload = function()
    {
        document
            .getElementById('logoPreview')
            .src = reader.result;
    };

    reader.readAsDataURL(
        event.target.files[0]
    );
}
</script>

@endsection

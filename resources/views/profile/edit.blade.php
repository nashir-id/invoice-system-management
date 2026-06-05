@extends('layouts.app')

@section('title', 'Profile')
@section('page-title', 'Profile')
@section('breadcrumb') Pengaturan / Profile @endsection

@push('styles')
<style>
    .profile-grid { display: grid; grid-template-columns: 280px 1fr; gap: 16px; align-items: start; }
    .profile-summary { padding: 22px; text-align: center; }
    .profile-avatar {
        width: 84px; height: 84px; border-radius: 50%; margin: 0 auto 14px;
        display: flex; align-items: center; justify-content: center;
        background: #534ab7; color: #fff; font-size: 28px; font-weight: 700;
        overflow: hidden;
    }
    .profile-avatar img { width: 100%; height: 100%; object-fit: cover; display: block; }
    .profile-name { font-size: 18px; font-weight: 700; color: #1a1a2e; margin-bottom: 4px; }
    .profile-email { font-size: 13px; color: #64748b; word-break: break-word; }
    .profile-role { margin-top: 12px; }
    .profile-stack { display: flex; flex-direction: column; gap: 16px; }
    .profile-actions { display: flex; align-items: center; gap: 10px; margin-top: 16px; }
    .profile-muted { font-size: 12px; color: #94a3b8; margin-top: 4px; line-height: 1.5; }
    .profile-danger { border-color: #fecaca; }
    .profile-danger .card-title { color: #991b1b; }

    @media (max-width: 900px) {
        .profile-grid { grid-template-columns: 1fr; }
    }
</style>
@endpush

@section('content')
<div class="profile-grid">
    <div class="card">
        <div class="profile-summary">
            <div class="profile-avatar">
                @if($user->profile_photo_url)
                    <img src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}">
                @else
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                @endif
            </div>
            <div class="profile-name">{{ $user->name }}</div>
            <div class="profile-email">{{ $user->email }}</div>
            <div class="profile-role">
                <span class="badge badge-{{ $user->role ?? 'staff' }}">
                    {{ ucfirst($user->role ?? 'staff') }}
                </span>
            </div>
        </div>
    </div>

    <div class="profile-stack">
        <div class="card">
            <div class="card-header">
                <span class="card-title">Informasi Profile</span>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')

                    <div class="form-grid">
                        <div class="field">
                            <label for="name">Nama</label>
                            <input id="name" name="name" type="text"
                                   value="{{ old('name', $user->name) }}"
                                   class="@error('name') is-error @enderror"
                                   required autocomplete="name">
                            @error('name')<span class="field-error">{{ $message }}</span>@enderror
                        </div>

                        <div class="field">
                            <label for="email">Email</label>
                            <input id="email" name="email" type="email"
                                   value="{{ old('email', $user->email) }}"
                                   class="@error('email') is-error @enderror"
                                   required autocomplete="username">
                            @error('email')<span class="field-error">{{ $message }}</span>@enderror
                        </div>

                        <div class="field form-full">
                            <label for="profile_photo">Foto Profile</label>
                            <input id="profile_photo" name="profile_photo" type="file"
                                   accept="image/jpeg,image/png,image/webp"
                                   class="@error('profile_photo') is-error @enderror">
                            <span class="field-hint">Format JPG, PNG, atau WebP. Maksimal 2 MB.</span>
                            @error('profile_photo')<span class="field-error">{{ $message }}</span>@enderror
                        </div>
                    </div>

                    <div class="profile-actions">
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                        @if (session('status') === 'profile-updated')
                            <span class="text-green" style="font-size:13px">Profile berhasil diperbarui.</span>
                        @endif
                    </div>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <span class="card-title">Ubah Password</span>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('password.update') }}">
                    @csrf
                    @method('PUT')

                    <div class="form-grid">
                        <div class="field">
                            <label for="current_password">Password Saat Ini</label>
                            <input id="current_password" name="current_password" type="password"
                                   class="@error('current_password', 'updatePassword') is-error @enderror"
                                   autocomplete="current-password">
                            @error('current_password', 'updatePassword')<span class="field-error">{{ $message }}</span>@enderror
                        </div>

                        <div class="field">
                            <label for="password">Password Baru</label>
                            <input id="password" name="password" type="password"
                                   class="@error('password', 'updatePassword') is-error @enderror"
                                   autocomplete="new-password">
                            @error('password', 'updatePassword')<span class="field-error">{{ $message }}</span>@enderror
                        </div>

                        <div class="field">
                            <label for="password_confirmation">Konfirmasi Password Baru</label>
                            <input id="password_confirmation" name="password_confirmation" type="password"
                                   autocomplete="new-password">
                        </div>
                    </div>

                    <div class="profile-actions">
                        <button type="submit" class="btn btn-primary">Update Password</button>
                        @if (session('status') === 'password-updated')
                            <span class="text-green" style="font-size:13px">Password berhasil diperbarui.</span>
                        @endif
                    </div>
                </form>
            </div>
        </div>

        <div class="card profile-danger">
            <div class="card-header">
                <span class="card-title">Hapus Akun</span>
            </div>
            <div class="card-body">
                <p class="profile-muted">
                    Menghapus akun akan menghapus data user ini secara permanen. Masukkan password untuk konfirmasi.
                </p>

                <form method="POST" action="{{ route('profile.destroy') }}"
                      onsubmit="return confirm('Yakin ingin menghapus akun ini? Tindakan ini tidak bisa dibatalkan.')">
                    @csrf
                    @method('DELETE')

                    <div class="field" style="max-width:360px;margin-top:14px">
                        <label for="delete_password">Password</label>
                        <input id="delete_password" name="password" type="password"
                               class="@error('password', 'userDeletion') is-error @enderror"
                               autocomplete="current-password">
                        @error('password', 'userDeletion')<span class="field-error">{{ $message }}</span>@enderror
                    </div>

                    <div class="profile-actions">
                        <button type="submit" class="btn btn-danger">Hapus Akun</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

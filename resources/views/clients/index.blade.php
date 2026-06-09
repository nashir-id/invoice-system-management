@extends('layouts.app')
@section('title', 'Manajemen Klien')
@section('page-title', 'Klien')

@section('content')

{{-- Flash success --}}
@if(session('success'))
    <div class="flash flash-success" style="margin-bottom:16px">
        ✓ {{ session('success') }}
    </div>
@endif

{{-- Header row: judul + tombol tambah --}}
<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px">
    <div style="font-size:14px;color:#64748b">
        Total <strong>{{ $clients->total() }}</strong> klien terdaftar
    </div>
    <a href="{{ route('clients.create') }}" class="btn btn-primary">
        <svg viewBox="0 0 16 16" fill="none" style="width:14px;height:14px">
            <path d="M8 2v12M2 8h12" stroke="white" stroke-width="1.8" stroke-linecap="round"/>
        </svg>
        Tambah Klien
    </a>
</div>

{{-- Filter & Search --}}
<div class="card" style="margin-bottom:16px">
    <div class="card-body" style="padding:14px 20px">
        <form method="GET" style="display:flex;gap:10px;align-items:flex-end;flex-wrap:wrap">
            <div class="field" style="flex:1;min-width:200px">
                <label>Cari Klien</label>
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Nama, email, website...">
            </div>
            <div class="field" style="width:160px">
                <label>Status</label>
                <select name="status">
                    <option value="">Semua Status</option>
                    <option value="active"   {{ request('status')==='active'   ? 'selected':'' }}>Aktif</option>
                    <option value="inactive" {{ request('status')==='inactive' ? 'selected':'' }}>Nonaktif</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary" style="height:42px">Cari</button>
            @if(request()->hasAny(['search','status']))
                <a href="{{ route('clients.index') }}" class="btn btn-outline" style="height:42px">Reset</a>
            @endif
        </form>
    </div>
</div>

{{-- Tabel klien --}}
<div class="card">
    <div class="card-header">
        <span class="card-title">Daftar Klien</span>
    </div>
    <div class="table-wrap">
        @if($clients->isEmpty())
            <div class="empty-state">
                <svg viewBox="0 0 48 48" fill="none">
                    <circle cx="24" cy="18" r="8" stroke="currentColor" stroke-width="2"/>
                    <path d="M6 42c0-9.9 8.1-16 18-16s18 6.1 18 16"
                          stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                </svg>
                <p>Belum ada klien.</p>
                <a href="{{ route('clients.create') }}" class="btn btn-primary">
                    + Tambah Klien Pertama
                </a>
            </div>
        @else
        <table>
            <thead>
                <tr>
                    <th>KLIEN</th>
                    <th>KONTAK</th>
                    <th>WEBSITE</th>
                    <th>INVOICE</th>
                    <th>TOTAL TRANSAKSI</th>
                    <th>STATUS</th>
                    <th>AKSI</th>
                </tr>
            </thead>
            <tbody>
                @foreach($clients as $client)
                <tr>
                    <td>
                        <a href="{{ route('clients.show', $client) }}"
                           style="font-weight:600;color:#1a1a2e;text-decoration:none">
                            {{ $client->company_name }}
                        </a>
                        @if($client->pic_name)
                            <div style="font-size:11px;color:#94a3b8">
                                PIC: {{ $client->pic_name }}
                            </div>
                        @endif
                    </td>
                    <td>
                        <div style="font-size:13px">{{ $client->phone ?? '-' }}</div>
                        <div style="font-size:11px;color:#94a3b8">{{ $client->email ?? '' }}</div>
                    </td>
                    <td style="color:#64748b;font-size:12px">
                        {{ $client->website ?? '-' }}
                    </td>
                    <td style="text-align:center;font-weight:600">
                        {{ $client->invoices_count ?? 0 }}
                    </td>
                    <td style="font-weight:500">
                        Rp {{ number_format($client->invoices_sum_total ?? 0, 0, ',', '.') }}
                    </td>
                    <td>
                        <span class="badge {{ $client->is_active ? 'badge-active' : 'badge-inactive' }}">
                            {{ $client->is_active ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </td>
                    <td>
                        <div style="display:flex;gap:6px;flex-wrap:wrap">
                            <a href="{{ route('clients.show', $client) }}"
                               class="btn btn-outline btn-sm">Detail</a>
                            <a href="{{ route('clients.edit', $client) }}"
                               class="btn btn-outline btn-sm">Edit</a>
                            @if($client->is_active)
                                <form method="POST"
                                      action="{{ route('clients.destroy', $client) }}"
                                      onsubmit="return confirm('Nonaktifkan klien {{ addslashes($client->company_name) }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        Nonaktifkan
                                    </button>
                                </form>
                            @else
                                <form method="POST" action="{{ route('clients.activate', $client) }}"
                                      onsubmit="return confirm('Aktifkan kembali klien {{ addslashes($client->company_name) }}?')">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-success btn-sm">
                                        Aktifkan
                                    </button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
    </div>

    {{-- Pagination --}}
    @if($clients->hasPages())
    <div style="padding:14px 16px;display:flex;align-items:center;
                justify-content:space-between;border-top:1px solid #f1f5f9">
        {{ $clients->withQueryString()->links() }}
        <span style="font-size:12px;color:#94a3b8">
            {{ $clients->firstItem() }}–{{ $clients->lastItem() }}
            dari {{ $clients->total() }}
        </span>
    </div>
    @endif
</div>

<footer style="margin-top: 40px; padding: 20px 0; border-top: 1px solid #e2e8f0; text-align: center;">
    <div style="color: #64748b; font-size: 14px;">
        <p>Nashir.id © 2026. All rights reserved.</p>
    </div>
</footer>

@endsection
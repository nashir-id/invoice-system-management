@extends('layouts.app')
@section('title', 'Manajemen Klien')
@section('page-title', 'Klien')

@section('topbar-actions')
    <a href="{{ route('clients.create') }}" class="btn btn-primary">
        <svg viewBox="0 0 16 16" fill="none"><path d="M8 2v12M2 8h12" stroke="white" stroke-width="1.5" stroke-linecap="round"/></svg>
        Tambah Klien
    </a>
@endsection

@section('content')

{{-- Filter & Search --}}
<div class="card" style="margin-bottom:16px">
    <div class="card-body" style="padding:14px 20px">
        <form method="GET" style="display:flex;gap:10px;align-items:flex-end;flex-wrap:wrap">
            <div class="field" style="flex:1;min-width:200px">
                <label>Cari Klien</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Nama, email, website...">
            </div>
            <div class="field" style="width:160px">
                <label>Status</label>
                <select name="status">
                    <option value="">Semua Status</option>
                    <option value="active" {{ request('status')==='active' ? 'selected':'' }}>Aktif</option>
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
        <span class="card-title">{{ $clients->total() }} Klien</span>
    </div>
    <div class="table-wrap">
        @if($clients->isEmpty())
            <div class="empty-state">
                <svg viewBox="0 0 48 48" fill="none"><circle cx="24" cy="18" r="8" stroke="currentColor" stroke-width="2"/><path d="M6 42c0-9.9 8.1-16 18-16s18 6.1 18 16" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
                <p>Belum ada klien. Tambahkan klien pertama kamu!</p>
                <a href="{{ route('clients.create') }}" class="btn btn-primary">+ Tambah Klien</a>
            </div>
        @else
        <table>
            <thead>
                <tr>
                    <th>Klien</th>
                    <th>Kontak</th>
                    <th>Website</th>
                    <th>Invoice</th>
                    <th>Total Transaksi</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($clients as $client)
                <tr>
                    <td>
                        <a href="{{ route('clients.show', $client) }}" style="font-weight:600;color:#1a1a2e;text-decoration:none">
                            {{ $client->company_name }}
                        </a>
                        @if($client->pic_name)
                            <div style="font-size:11px;color:#94a3b8">PIC: {{ $client->pic_name }}</div>
                        @endif
                    </td>
                    <td>
                        <div style="font-size:13px">{{ $client->phone ?? '-' }}</div>
                        <div style="font-size:11px;color:#94a3b8">{{ $client->email ?? '' }}</div>
                    </td>
                    <td style="color:#64748b;font-size:12px">{{ $client->website ?? '-' }}</td>
                    <td style="text-align:center">
                        <span style="font-weight:600">{{ $client->invoices_count }}</span>
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
                        <div style="display:flex;gap:6px">
                            <a href="{{ route('clients.show', $client) }}" class="btn btn-outline btn-sm">Detail</a>
                            <a href="{{ route('clients.edit', $client) }}" class="btn btn-outline btn-sm">Edit</a>
                            @if($client->is_active)
                            <form method="POST" action="{{ route('clients.destroy', $client) }}"
                                  onsubmit="return confirm('Nonaktifkan klien {{ $client->company_name }}?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Nonaktifkan</button>
                            </form>
                            @else
                            <form method="POST" action="{{ route('clients.activate', $client) }}">
                                @csrf
                                <button type="submit" class="btn btn-success btn-sm">Aktifkan</button>
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
    @if($clients->hasPages())
    <div class="pagination">
        {{ $clients->links('pagination::simple-bootstrap-5') }}
        <span class="page-info">{{ $clients->firstItem() }}–{{ $clients->lastItem() }} dari {{ $clients->total() }}</span>
    </div>
    @endif
</div>

@endsection
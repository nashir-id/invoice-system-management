@extends('layouts.app')
@section('title', 'Invoice')
@section('page-title', 'Invoice')

@push('styles')
<style>
    .invoice-page-head {
        display: flex; align-items: center; justify-content: space-between;
        gap: 12px; margin-bottom: 16px;
    }
    .invoice-page-subtitle { font-size: 13px; color: #64748b; }
    .status-tabs { display: flex; gap: 0; margin-bottom: 16px; border-bottom: 2px solid #f1f5f9; }
    .status-tab {
        padding: 8px 16px; font-size: 13px; font-weight: 500; color: #94a3b8;
        cursor: pointer; border-bottom: 2px solid transparent; margin-bottom: -2px;
        text-decoration: none; transition: all .15s; display: flex; align-items: center; gap: 6px;
    }
    .status-tab:hover { color: #475569; }
    .status-tab.active { color: #1e1b4b; border-bottom-color: #1e1b4b; }
    .status-tab .count {
        font-size: 10px; font-weight: 600; padding: 1px 7px;
        border-radius: 20px; background: #f1f5f9; color: #64748b;
    }
    .status-tab.active .count { background: #1e1b4b; color: #fff; }
    .sort-btn { font-size: 11px; color: #94a3b8; cursor: pointer; display: flex; align-items: center; gap: 3px; text-decoration: none; }
    .sort-btn:hover { color: #475569; }
    .sort-btn.asc::after  { content: ' ↑'; }
    .sort-btn.desc::after { content: ' ↓'; }
    @media (max-width: 768px) {
        .invoice-page-head { align-items: stretch; flex-direction: column; }
        .invoice-page-head .btn { width: 100%; }
        .status-tabs {
            overflow-x: auto; -webkit-overflow-scrolling: touch;
            padding-bottom: 2px; border-bottom-width: 1px;
        }
        .status-tab { flex: 0 0 auto; padding: 9px 12px; }
    }
</style>
@endpush

@section('content')

<div class="invoice-page-head">
    <div class="invoice-page-subtitle">
        @staff
        Lihat daftar invoice dan status pembayaran.
        @else
        Kelola invoice, status pembayaran, dan data tagihan klien.
        @endstaff
    </div>
    @adminup
    <a href="{{ route('invoices.create') }}" class="btn btn-primary">
        <svg viewBox="0 0 16 16" fill="none"><path d="M8 2v12M2 8h12" stroke="white" stroke-width="1.5" stroke-linecap="round"/></svg>
        Buat Invoice
    </a>
    @endadminup
</div>

{{-- Status tabs --}}
<div class="status-tabs">
    <a href="{{ request()->fullUrlWithQuery(['status' => '']) }}"
       class="status-tab {{ !request('status') ? 'active' : '' }}">
        Semua <span class="count">{{ $stats['all'] }}</span>
    </a>
    <a href="{{ request()->fullUrlWithQuery(['status' => 'unpaid']) }}"
       class="status-tab {{ request('status') === 'unpaid' ? 'active' : '' }}">
        Unpaid <span class="count">{{ $stats['unpaid'] }}</span>
    </a>
    <a href="{{ request()->fullUrlWithQuery(['status' => 'paid']) }}"
       class="status-tab {{ request('status') === 'paid' ? 'active' : '' }}">
        Paid <span class="count">{{ $stats['paid'] }}</span>
    </a>
    <a href="{{ request()->fullUrlWithQuery(['status' => 'overdue']) }}"
       class="status-tab {{ request('status') === 'overdue' ? 'active' : '' }}">
        Overdue <span class="count">{{ $stats['overdue'] }}</span>
    </a>
</div>

{{-- Filter --}}
<div class="card" style="margin-bottom:14px">
    <div class="card-body" style="padding:12px 16px">
        <form method="GET" style="display:flex;gap:10px;align-items:flex-end;flex-wrap:wrap">
            {{-- Pertahankan status tab yang aktif --}}
            @if(request('status'))
                <input type="hidden" name="status" value="{{ request('status') }}">
            @endif

            <div class="field" style="flex:1;min-width:180px">
                <label>Cari No. Invoice</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="INV010426...">
            </div>
            <div class="field" style="width:180px">
                <label>Klien</label>
                <select name="client_id">
                    <option value="">Semua Klien</option>
                    @foreach($clients as $c)
                        <option value="{{ $c->id }}" {{ request('client_id') == $c->id ? 'selected' : '' }}>
                            {{ $c->company_name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="field" style="width:140px">
                <label>Tipe</label>
                <select name="type">
                    <option value="">Semua Tipe</option>
                    <option value="one_time"  {{ request('type')==='one_time'  ? 'selected':'' }}>One-Time</option>
                    <option value="recurring" {{ request('type')==='recurring' ? 'selected':'' }}>Recurring</option>
                </select>
            </div>
            <div class="field" style="width:130px">
                <label>Dari Tanggal</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}">
            </div>
            <div class="field" style="width:130px">
                <label>Sampai</label>
                <input type="date" name="date_to" value="{{ request('date_to') }}">
            </div>
            <button type="submit" class="btn btn-primary" style="height:42px">Filter</button>
            @if(request()->hasAny(['search','client_id','type','date_from','date_to']))
                <a href="{{ route('invoices.index', request()->only('status')) }}"
                   class="btn btn-outline" style="height:42px">Reset</a>
            @endif
        </form>
    </div>
</div>

{{-- Tabel --}}
<div class="card">
    <div class="card-header">
        <span class="card-title">{{ $invoices->total() }} Invoice</span>
        <div style="display:flex;gap:8px;align-items:center;font-size:12px;color:#94a3b8">
            Sort:
            <a href="{{ request()->fullUrlWithQuery(['sort'=>'date','dir'=> request('dir')==='asc' ? 'desc':'asc']) }}"
               class="sort-btn {{ request('sort')==='date' ? (request('dir')==='asc' ? 'asc':'desc') : '' }}">
               Tanggal
            </a>
            <a href="{{ request()->fullUrlWithQuery(['sort'=>'total','dir'=> request('dir')==='asc' ? 'desc':'asc']) }}"
               class="sort-btn {{ request('sort')==='total' ? (request('dir')==='asc' ? 'asc':'desc') : '' }}">
               Nominal
            </a>
        </div>
    </div>

    <div class="table-wrap">
        @if($invoices->isEmpty())
            <div class="empty-state">
                <svg viewBox="0 0 48 48" fill="none"><rect x="8" y="4" width="32" height="40" rx="4" stroke="currentColor" stroke-width="2"/><path d="M16 16h16M16 24h16M16 32h8" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
                <p>Tidak ada invoice ditemukan.</p>
                @adminup
                <a href="{{ route('invoices.create') }}" class="btn btn-primary">+ Buat Invoice</a>
                @endadminup
            </div>
        @else
        <table>
            <thead>
                <tr>
                    <th>No. Invoice</th>
                    <th>Klien</th>
                    <th>Tanggal</th>
                    <th>Jatuh Tempo</th>
                    <th>Nominal</th>
                    <th>Tipe</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($invoices as $inv)
                <tr>
                    <td>
                        <a href="{{ route('invoices.show', $inv) }}"
                           style="font-family:monospace;font-size:12px;color:#4f46e5;text-decoration:none;font-weight:600">
                            {{ $inv->invoice_number }}
                        </a>
                    </td>
                    <td>
                        <div style="font-weight:500">{{ $inv->client->company_name }}</div>
                        <div style="font-size:11px;color:#94a3b8">{{ $inv->client->website }}</div>
                    </td>
                    <td style="color:#64748b;font-size:12px">{{ $inv->invoice_date->format('d M Y') }}</td>
                    <td style="font-size:12px">
                        @if($inv->due_date)
                            <span style="color:{{ $inv->due_date->isPast() && $inv->status !== 'paid' ? '#dc2626' : '#64748b' }}">
                                {{ $inv->due_date->format('d M Y') }}
                            </span>
                        @else
                            <span style="color:#cbd5e1">—</span>
                        @endif
                    </td>
                    <td style="font-weight:600">Rp {{ number_format($inv->total, 0, ',', '.') }}</td>
                    <td>
                        <span class="badge" style="background:{{ $inv->type==='recurring' ? '#ede9fe' : '#f1f5f9' }};color:{{ $inv->type==='recurring' ? '#4c1d95' : '#475569' }}">
                            {{ $inv->type === 'recurring' ? 'Recurring' : 'One-Time' }}
                        </span>
                    </td>
                    <td>
                        <span class="badge badge-{{ $inv->status }}">
                            {{ $inv->status_label }}
                        </span>
                    </td>
                    <td>
                        <div style="display:flex;gap:6px;flex-wrap:wrap">
                            <a href="{{ route('invoices.show', $inv) }}" class="btn btn-outline btn-sm">Detail</a>
                           
                            @adminup
                            @if($inv->status !== 'paid')
                                <a href="{{ route('invoices.edit', $inv) }}" class="btn btn-outline btn-sm">Edit</a>
                                <form method="POST"
                                      action="{{ route('invoices.destroy', $inv) }}"
                                      onsubmit="return confirm('Hapus invoice {{ addslashes($inv->invoice_number) }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                </form>
                            @endif
                            @endadminup
                            
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
    </div>

    {{-- Pagination --}}
    @if($invoices->hasPages())
    <div style="padding:14px 16px;display:flex;align-items:center;justify-content:space-between;border-top:1px solid #f1f5f9">
        {{ $invoices->withQueryString()->links() }}
        <span style="font-size:12px;color:#94a3b8">
            {{ $invoices->firstItem() }}–{{ $invoices->lastItem() }} dari {{ $invoices->total() }}
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

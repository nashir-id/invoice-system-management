@extends('layouts.app')
@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('topbar-actions')
    @adminup
    <a href="{{ route('invoices.create') }}" class="btn btn-primary">
        <svg viewBox="0 0 16 16" fill="none"><path d="M8 2v12M2 8h12" stroke="white" stroke-width="1.5" stroke-linecap="round"/></svg>
        Buat Invoice
    </a>
    @endadminup
@endsection

@section('content')

{{-- Statistik --}}
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-label">Invoice Bulan Ini</div>
        <div class="stat-value">{{ $stats['total_invoices'] }}</div>
        <div class="stat-sub text-muted">{{ now()->isoFormat('MMMM YYYY') }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Total Terbayar</div>
        <div class="stat-value" style="font-size:18px">Rp {{ number_format($stats['total_paid'], 0, ',', '.') }}</div>
        <div class="stat-sub text-green">Bulan ini</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Outstanding</div>
        <div class="stat-value" style="font-size:18px">Rp {{ number_format($stats['total_outstanding'], 0, ',', '.') }}</div>
        <div class="stat-sub text-orange">Belum dibayar</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Overdue</div>
        <div class="stat-value text-red">{{ $stats['total_overdue'] }}</div>
        <div class="stat-sub text-red">Perlu tindakan</div>
    </div>
</div>

{{-- Daftar invoice terbaru --}}
<div class="card">
    <div class="card-header">
        <span class="card-title">Invoice Terbaru</span>
        <a href="{{ route('invoices.index') }}" class="btn btn-outline btn-sm">Lihat Semua</a>
    </div>
    <div class="table-wrap">
        @if($recentInvoices->isEmpty())
            <div class="empty-state">
                <svg viewBox="0 0 48 48" fill="none"><rect x="8" y="4" width="32" height="40" rx="4" stroke="currentColor" stroke-width="2"/><path d="M16 16h16M16 24h16M16 32h8" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
                <p>Belum ada invoice. Mulai buat invoice pertama kamu!</p>
                <a href="{{ route('invoices.create') }}" class="btn btn-primary">+ Buat Invoice</a>
            </div>
        @else
        <table>
            <thead>
                <tr>
                    <th>No. Invoice</th>
                    <th>Klien</th>
                    <th>Tanggal</th>
                    <th>Nominal</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($recentInvoices as $inv)
                <tr>
                    <td>
                        <a href="{{ route('invoices.show', $inv) }}" style="font-family:monospace;font-size:12px;color:#4f46e5;text-decoration:none;font-weight:500">
                            {{ $inv->invoice_number }}
                        </a>
                        @if($inv->type === 'recurring')
                            <span class="badge" style="background:#ede9fe;color:#4c1d95;margin-left:4px">Recurring</span>
                        @endif
                    </td>
                    <td>
                        <div style="font-weight:500">{{ $inv->client->company_name }}</div>
                        <div style="font-size:11px;color:#94a3b8">{{ $inv->client->website }}</div>
                    </td>
                    <td style="color:#64748b">{{ $inv->invoice_date->format('d M Y') }}</td>
                    <td style="font-weight:500">Rp {{ number_format($inv->total, 0, ',', '.') }}</td>
                    <td>
                        <span class="badge badge-{{ $inv->status }}">
                            {{ $inv->status_label }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('invoices.show', $inv) }}" class="btn btn-outline btn-sm">Detail</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
    </div>
</div>

</div>
<footer style="margin-top: 40px; padding: 20px 0; border-top: 1px solid #e2e8f0; text-align: center;">
    <div style="color: #64748b; font-size: 14px;">
        <p>Nashir.id © 2026. All rights reserved.</p>
    </div>
</footer>


@endsection
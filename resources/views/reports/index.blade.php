@extends('layouts.app')

@section('title', 'Laporan')

@push('styles')
<style>
    .rpt-page { background: #f5f6fa; min-height: 100vh; padding: 2rem 1.5rem; }

    /* Header */
    .rpt-page-title { font-size: 1.35rem; font-weight: 700; color: #111827; margin: 0; letter-spacing: -.3px; }
    .rpt-breadcrumb  { font-size: .8rem; color: #9ca3af; margin-top: .2rem; }

    /* Stat Cards */
    .stat-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-bottom: 1.5rem; }
    .stat-card {
        background: #fff; border-radius: 14px;
        border: 1px solid #e9ecf3;
        padding: 1.25rem 1.4rem;
        display: flex; align-items: flex-start; gap: 1rem;
    }
    .stat-icon {
        width: 44px; height: 44px; border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.2rem; flex-shrink: 0;
    }
    .stat-icon.purple { background: #eef2ff; color: #6366f1; }
    .stat-icon.green  { background: #dcfce7; color: #16a34a; }
    .stat-icon.amber  { background: #fef9c3; color: #ca8a04; }
    .stat-icon.red    { background: #fee2e2; color: #ef4444; }
    .stat-label { font-size: .75rem; font-weight: 600; color: #9ca3af; text-transform: uppercase; letter-spacing: .5px; }
    .stat-value { font-size: 1.35rem; font-weight: 800; color: #111827; margin-top: .15rem; letter-spacing: -.5px; }
    .stat-value.green { color: #16a34a; }
    .stat-value.amber { color: #b45309; }
    .stat-value.red   { color: #ef4444; }

    /* Cards */
    .rpt-card {
        background: #fff; border-radius: 14px;
        border: 1px solid #e9ecf3;
        box-shadow: 0 1px 4px rgba(0,0,0,.04);
        overflow: hidden; margin-bottom: 1.5rem;
    }
    .rpt-card-header {
        padding: .9rem 1.4rem;
        border-bottom: 1px solid #f0f2f8;
        background: #fafbff;
        display: flex; align-items: center; justify-content: space-between;
    }
    .rpt-card-title {
        font-size: .83rem; font-weight: 700; color: #374151;
        text-transform: uppercase; letter-spacing: .6px;
        display: flex; align-items: center; gap: .5rem;
    }
    .rpt-card-title i { color: #6366f1; }
    .rpt-card-body { padding: 1.4rem; }

    /* Filter Bar */
    .filter-bar {
        background: #fff; border-radius: 12px;
        border: 1px solid #e9ecf3; padding: .9rem 1.2rem;
        display: flex; align-items: center; gap: .75rem;
        margin-bottom: 1.5rem; flex-wrap: wrap;
    }
    .filter-label { font-size: .8rem; font-weight: 600; color: #6b7280; white-space: nowrap; }
    .filter-select {
        padding: .45rem .8rem; border: 1.5px solid #e5e7eb;
        border-radius: 8px; font-size: .85rem; color: #111827;
        background: #fff; outline: none; transition: border-color .15s;
    }
    .filter-select:focus { border-color: #6366f1; }
    .btn-filter {
        padding: .45rem 1rem; border-radius: 8px;
        background: #6366f1; color: #fff; border: none;
        font-size: .83rem; font-weight: 600; cursor: pointer;
        transition: background .15s;
    }
    .btn-filter:hover { background: #4f46e5; }

    /* Chart */
    .chart-wrap { position: relative; height: 260px; }

    /* Table */
    .rpt-table { width: 100%; border-collapse: collapse; }
    .rpt-table thead th {
        font-size: .72rem; font-weight: 700; text-transform: uppercase;
        letter-spacing: .5px; color: #9ca3af;
        padding: .6rem 1rem; background: #f9fafb;
        border-bottom: 1px solid #f0f2f8; white-space: nowrap;
    }
    .rpt-table tbody tr { border-bottom: 1px solid #f7f8fc; }
    .rpt-table tbody tr:last-child { border-bottom: none; }
    .rpt-table tbody tr:hover { background: #fafbff; }
    .rpt-table td { padding: .7rem 1rem; font-size: .88rem; color: #374151; }

    /* Status Badge */
    .badge-status {
        display: inline-block; padding: .25rem .65rem;
        border-radius: 6px; font-size: .73rem; font-weight: 700;
        text-transform: uppercase; letter-spacing: .4px;
    }
    .badge-paid    { background: #dcfce7; color: #15803d; }
    .badge-unpaid  { background: #fef9c3; color: #a16207; }
    .badge-overdue { background: #fee2e2; color: #b91c1c; }

    /* Top Client Bar */
    .client-bar-wrap { display: flex; flex-direction: column; gap: .9rem; }
    .client-bar-item { display: flex; flex-direction: column; gap: .3rem; }
    .client-bar-meta { display: flex; justify-content: space-between; font-size: .83rem; }
    .client-bar-name { font-weight: 600; color: #374151; }
    .client-bar-val  { color: #6b7280; }
    .client-bar-track { height: 7px; background: #f0f2f8; border-radius: 99px; overflow: hidden; }
    .client-bar-fill  { height: 100%; background: #6366f1; border-radius: 99px; transition: width .6s ease; }
</style>
@endpush

@section('content')
<div class="rpt-page">
<div class="container-fluid" style="max-width: 1400px;">

    {{-- Header --}}
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <p class="rpt-breadcrumb">Dashboard / Laporan</p>
            <h1 class="rpt-page-title">Laporan Keuangan</h1>
        </div>
    </div>

    {{-- Filter --}}
    <form method="GET" action="{{ route('reports.index') }}">
        <div class="filter-bar">
            <span class="filter-label"><i class="bi bi-funnel me-1"></i> Filter:</span>
            <select name="year" class="filter-select">
                @foreach($years as $y)
                    <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                @endforeach
            </select>
            <button type="submit" class="btn-filter">
                <i class="bi bi-arrow-clockwise me-1"></i> Terapkan
            </button>
        </div>
    </form>

    {{-- Stat Cards --}}
    <div class="stat-grid">
        <div class="stat-card">
            <div class="stat-icon purple"><i class="bi bi-file-earmark-text"></i></div>
            <div>
                <p class="stat-label">Total Invoice</p>
                <p class="stat-value">{{ number_format($totalInvoices) }}</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon green"><i class="bi bi-cash-stack"></i></div>
            <div>
                <p class="stat-label">Pendapatan</p>
                <p class="stat-value green">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon amber"><i class="bi bi-hourglass-split"></i></div>
            <div>
                <p class="stat-label">Belum Dibayar</p>
                <p class="stat-value amber">Rp {{ number_format($totalUnpaid, 0, ',', '.') }}</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon red"><i class="bi bi-exclamation-triangle"></i></div>
            <div>
                <p class="stat-label">Jatuh Tempo</p>
                <p class="stat-value red">{{ $totalOverdue }} invoice</p>
            </div>
        </div>
    </div>

    <div class="row g-4">

        {{-- Chart Pendapatan Bulanan --}}
        <div class="col-lg-8">
            <div class="rpt-card">
                <div class="rpt-card-header">
                    <div class="rpt-card-title">
                        <i class="bi bi-bar-chart-line"></i> Pendapatan Bulanan {{ $year }}
                    </div>
                </div>
                <div class="rpt-card-body">
                    <div class="chart-wrap">
                        <canvas id="revenueChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        {{-- Top Klien --}}
        <div class="col-lg-4">
            <div class="rpt-card">
                <div class="rpt-card-header">
                    <div class="rpt-card-title">
                        <i class="bi bi-trophy"></i> Top 5 Klien {{ $year }}
                    </div>
                </div>
                <div class="rpt-card-body">
                    @if($topClients->isEmpty())
                        <p class="text-muted text-center" style="font-size:.87rem; padding: 1rem 0;">
                            Belum ada data klien.
                        </p>
                    @else
                        @php $maxRevenue = $topClients->first()->total_revenue ?: 1; @endphp
                        <div class="client-bar-wrap">
                            @foreach($topClients as $tc)
                            <div class="client-bar-item">
                                <div class="client-bar-meta">
                                    <span class="client-bar-name">{{ $tc->client->company_name ?? '—' }}</span>
                                    <span class="client-bar-val">Rp {{ number_format($tc->total_revenue, 0, ',', '.') }}</span>
                                </div>
                                <div class="client-bar-track">
                                    <div class="client-bar-fill"
                                        style="width: {{ round(($tc->total_revenue / $maxRevenue) * 100) }}%">
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Invoice Terbaru --}}
        <div class="col-12">
            <div class="rpt-card">
                <div class="rpt-card-header">
                    <div class="rpt-card-title">
                        <i class="bi bi-clock-history"></i> Invoice Terbaru
                    </div>
                    <a href="{{ route('invoices.index') }}"
                        style="font-size:.82rem; color:#6366f1; text-decoration:none; font-weight:600;">
                        Lihat semua →
                    </a>
                </div>
                <div style="overflow-x:auto;">
                    <table class="rpt-table">
                        <thead>
                            <tr>
                                <th>No. Invoice</th>
                                <th>Klien</th>
                                <th>Tanggal</th>
                                <th>Jatuh Tempo</th>
                                <th style="text-align:right">Total</th>
                                <th style="text-align:center">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentInvoices as $inv)
                            <tr>
                                <td>
                                    <a href="{{ route('invoices.show', $inv) }}"
                                        style="color:#6366f1; font-weight:600; text-decoration:none;">
                                        {{ $inv->invoice_number }}
                                    </a>
                                </td>
                                <td>{{ $inv->client->company_name ?? '—' }}</td>
                                <td>{{ \Carbon\Carbon::parse($inv->invoice_date)->format('d M Y') }}</td>
                                <td>{{ $inv->due_date ? \Carbon\Carbon::parse($inv->due_date)->format('d M Y') : '—' }}</td>
                                <td style="text-align:right; font-weight:600;">
                                    Rp {{ number_format($inv->total, 0, ',', '.') }}
                                </td>
                                <td style="text-align:center">
                                    @if($inv->status === 'paid')
                                        <span class="badge-status badge-paid">Lunas</span>
                                    @elseif($inv->status === 'overdue')
                                        <span class="badge-status badge-overdue">Overdue</span>
                                    @else
                                        <span class="badge-status badge-unpaid">Belum Bayar</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted" style="padding: 2rem;">
                                    Belum ada invoice.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>
</div>
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>
<script>
(function () {
    const months = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
    const data   = @json($chartData->values());

    const ctx = document.getElementById('revenueChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: months,
            datasets: [{
                label: 'Pendapatan (Rp)',
                data: data,
                backgroundColor: 'rgba(99,102,241,.15)',
                borderColor: '#6366f1',
                borderWidth: 2,
                borderRadius: 6,
                borderSkipped: false,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: ctx => 'Rp ' + Math.round(ctx.parsed.y).toLocaleString('id-ID')
                    }
                }
            },
            scales: {
                x: { grid: { display: false }, ticks: { font: { size: 11 } } },
                y: {
                    grid: { color: '#f0f2f8' },
                    ticks: {
                        font: { size: 11 },
                        callback: v => 'Rp ' + (v >= 1000000
                            ? (v/1000000).toFixed(1) + 'jt'
                            : v.toLocaleString('id-ID'))
                    }
                }
            }
        }
    });
})();
</script>
@endpush
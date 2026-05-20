@extends('layouts.app')
@section('title', $client->company_name)
@section('page-title', $client->company_name)
@section('breadcrumb') <a href="{{ route('clients.index') }}">Klien</a> / {{ $client->company_name }} @endsection

@section('topbar-actions')
    <a href="{{ route('clients.edit', $client) }}" class="btn btn-outline">Edit</a>
    @adminup
    <a href="{{ route('invoices.create', ['client_id' => $client->id]) }}" class="btn btn-primary">
        + Buat Invoice
    </a>
    @endadminup
@endsection

@section('content')

{{-- Info klien + statistik --}}
<div style="display:grid;grid-template-columns:1fr 2fr;gap:16px;margin-bottom:16px">

    {{-- Card info --}}
    <div class="card">
        <div class="card-header"><span class="card-title">Profil Klien</span></div>
        <div class="card-body">
            <div style="display:flex;flex-direction:column;gap:12px">
                <div>
                    <div style="font-size:15px;color:#94a3b8;margin-bottom:3px">Nama Perusahaan</div>
                    <div style="font-weight:600">{{ $client->company_name }}</div>
                </div>
                @if($client->pic_name)
                <div>
                    <div style="font-size:15px;color:#94a3b8;margin-bottom:3px">PIC</div>
                    <div>{{ $client->pic_name }}</div>
                </div>
                @endif
                @if($client->phone)
                <div>
                    <div style="font-size:15px;color:#94a3b8;margin-bottom:3px">WhatsApp</div>
                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $client->phone) }}" target="_blank"
                       style="color:#16a34a;text-decoration:none;font-size:15px">{{ $client->phone }}</a>
                </div>
                @endif
                @if($client->email)
                <div>
                    <div style="font-size:15px;color:#94a3b8;margin-bottom:3px">Email</div>
                    <a href="mailto:{{ $client->email }}" style="color:#4f46e5;text-decoration:none">{{ $client->email }}</a>
                </div>
                @endif
                @if($client->website)
                <div>
                    <div style="font-size:15px;color:#94a3b8;margin-bottom:3px">Website</div>
                    <a href="https://{{ $client->website }}" target="_blank" style="color:#4f46e5;text-decoration:none">{{ $client->website }}</a>
                </div>
                @endif
                @if($client->notes)
                <div style="border-top:1px solid #f1f5f9;padding-top:12px">
                    <div style="font-size:11px;color:#94a3b8;margin-bottom:3px">Catatan Internal</div>
                    <div style="font-size:13px;color:#64748b">{{ $client->notes }}</div>
                </div>
                @endif
                <div style="border-top:1px solid #f1f5f9;padding-top:12px">
                    <span class="badge {{ $client->is_active ? 'badge-active' : 'badge-inactive' }}">
                        {{ $client->is_active ? 'Aktif' : 'Nonaktif' }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    {{-- Statistik klien --}}
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;align-content:start">
        <div class="stat-card">
            <div class="stat-label">Total Invoice</div>
            <div class="stat-value">{{ $stats['total_invoices'] }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Total Terbayar</div>
            <div class="stat-value" style="font-size:18px">Rp {{ number_format($stats['total_paid'], 0, ',', '.') }}</div>
            <div class="stat-sub text-green">Lunas</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Outstanding</div>
            <div class="stat-value" style="font-size:18px">Rp {{ number_format($stats['total_outstanding'], 0, ',', '.') }}</div>
            <div class="stat-sub text-orange">Belum bayar</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Overdue</div>
            <div class="stat-value text-red">{{ $stats['total_overdue'] }}</div>
        </div>
    </div>
</div>

{{-- Riwayat invoice --}}
<div class="card">
    <div class="card-header">
        <span class="card-title">Riwayat Invoice</span>
    </div>
    <div class="table-wrap">
        @if($invoices->isEmpty())
            <div class="empty-state">
                <p>Belum ada invoice untuk klien ini.</p>
                <a href="{{ route('invoices.create', ['client_id' => $client->id]) }}" class="btn btn-primary">+ Buat Invoice</a>
            </div>
        @else
        <table>
            <thead>
                <tr>
                    <th>No. Invoice</th>
                    <th>Tanggal</th>
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
                        <a href="{{ route('invoices.show', $inv) }}" style="font-family:monospace;font-size:12px;color:#4f46e5;text-decoration:none;font-weight:500">
                            {{ $inv->invoice_number }}
                        </a>
                    </td>
                    <td style="color:#64748b">{{ $inv->invoice_date->format('d M Y') }}</td>
                    <td style="font-weight:500">Rp {{ number_format($inv->total, 0, ',', '.') }}</td>
                    <td>
                        <span class="badge" style="background:{{ $inv->type==='recurring' ? '#ede9fe' : '#f1f5f9' }};color:{{ $inv->type==='recurring' ? '#4c1d95' : '#475569' }}">
                            {{ $inv->type === 'recurring' ? 'Recurring' : 'One-Time' }}
                        </span>
                    </td>
                    <td><span class="badge badge-{{ $inv->status }}">{{ $inv->status_label }}</span></td>
                    <td><a href="{{ route('invoices.show', $inv) }}" class="btn btn-outline btn-sm">Detail</a></td>
                </tr>
                @endforeach
            </tbody>
        </table>
        {{ $invoices->links() }}
        @endif
    </div>
</div>

@endsection
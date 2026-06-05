@extends('layouts.app')
@section('title', $client->company_name)
@section('page-title', $client->company_name)
@section('breadcrumb')
    <a href="{{ route('clients.index') }}">Klien</a> / {{ $client->company_name }}
@endsection

@section('content')

{{-- Flash --}}
@if(session('success'))
    <div class="flash flash-success" style="margin-bottom:16px">
        ✓ {{ session('success') }}
    </div>
@endif

{{-- Layout 2 kolom --}}
<div style="display:grid;grid-template-columns:280px 1fr;gap:16px;align-items:start">

    {{-- ── Kartu Info Klien ── --}}
    <div class="card">
        <div class="card-header">
            <span class="card-title">Profil Klien</span>
            <a href="{{ route('clients.edit', $client) }}" class="btn btn-outline btn-sm">Edit</a>
        </div>
        <div class="card-body" style="display:flex;flex-direction:column;gap:14px">

            <div>
                <div style="font-size:11px;color:#94a3b8;margin-bottom:3px;text-transform:uppercase;letter-spacing:.05em">Nama Perusahaan</div>
                <div style="font-weight:600;font-size:14px">{{ $client->company_name }}</div>
            </div>

            @if($client->pic_name)
            <div>
                <div style="font-size:11px;color:#94a3b8;margin-bottom:3px;text-transform:uppercase;letter-spacing:.05em">PIC</div>
                <div>{{ $client->pic_name }}</div>
            </div>
            @endif

            @if($client->phone)
            <div>
                <div style="font-size:11px;color:#94a3b8;margin-bottom:3px;text-transform:uppercase;letter-spacing:.05em">WhatsApp</div>
                <a target="_blank"
                   style="color:#16a34a;text-decoration:none;font-size:13px">
                    {{ $client->phone }}
                </a>
            </div>
            @endif

            @if($client->email)
            <div>
                <div style="font-size:11px;color:#94a3b8;margin-bottom:3px;text-transform:uppercase;letter-spacing:.05em">Email</div>
                <a style="color:#4f46e5;text-decoration:none;font-size:13px">
                    {{ $client->email }}
                </a>
            </div>
            @endif

            @if($client->website)
            <div>
                <div style="font-size:11px;color:#94a3b8;margin-bottom:3px;text-transform:uppercase;letter-spacing:.05em">Website</div>
                <a style="color:#4f46e5;text-decoration:none;font-size:13px">
                    {{ $client->website }}
                </a>
            </div>
            @endif

            @if($client->address)
            <div>
                <div style="font-size:11px;color:#94a3b8;margin-bottom:3px;text-transform:uppercase;letter-spacing:.05em">Alamat</div>
                <div style="font-size:13px;color:#64748b;line-height:1.5">{{ $client->address }}</div>
            </div>
            @endif

            @if($client->notes)
            <div style="border-top:1px solid #f1f5f9;padding-top:12px">
                <div style="font-size:11px;color:#94a3b8;margin-bottom:3px;text-transform:uppercase;letter-spacing:.05em">Catatan Internal</div>
                <div style="font-size:12px;color:#64748b;line-height:1.5">{{ $client->notes }}</div>
            </div>
            @endif

            <div style="border-top:1px solid #f1f5f9;padding-top:12px">
                <span class="badge {{ $client->is_active ? 'badge-active' : 'badge-inactive' }}">
                    {{ $client->is_active ? 'Aktif' : 'Nonaktif' }}
                </span>
            </div>

        </div>
    </div>

    {{-- ── Kolom Kanan ── --}}
    <div>

        {{-- Statistik --}}
        <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:12px;margin-bottom:16px">
            <div class="stat-card">
                <div class="stat-label">Total Invoice</div>
                <div class="stat-value">{{ $stats['total_invoices'] }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Total Terbayar</div>
                <div class="stat-value" style="font-size:16px">
                    Rp {{ number_format($stats['total_paid'], 0, ',', '.') }}
                </div>
                <div class="stat-sub text-green">Lunas</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Outstanding</div>
                <div class="stat-value" style="font-size:16px">
                    Rp {{ number_format($stats['total_outstanding'], 0, ',', '.') }}
                </div>
                <div class="stat-sub text-orange">Belum bayar</div>
            </div>
        </div>

        {{-- Riwayat Invoice --}}
        <div class="card">
            <div class="card-header">
                <span class="card-title">Riwayat Invoice</span>
                <a href="{{ route('invoices.create', ['client_id' => $client->id]) }}"
                   class="btn btn-primary btn-sm">
                    + Buat Invoice
                </a>
            </div>
            <div class="table-wrap">
                @if($invoices->isEmpty())
                    <div class="empty-state">
                        <p>Belum ada invoice untuk klien ini.</p>
                        <a href="{{ route('invoices.create', ['client_id' => $client->id]) }}"
                           class="btn btn-primary">+ Buat Invoice</a>
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
                                <a href="{{ route('invoices.show', $inv) }}"
                                   style="font-family:monospace;font-size:12px;color:#4f46e5;text-decoration:none;font-weight:600">
                                    {{ $inv->invoice_number }}
                                </a>
                            </td>
                            <td style="color:#64748b;font-size:12px">
                                {{ $inv->invoice_date->format('d M Y') }}
                            </td>
                            <td style="font-weight:500">
                                Rp {{ number_format($inv->total, 0, ',', '.') }}
                            </td>
                            <td>
                                <span class="badge"
                                    style="background:{{ $inv->type==='recurring' ? '#ede9fe':'#f1f5f9' }};
                                           color:{{ $inv->type==='recurring' ? '#4c1d95':'#475569' }}">
                                    {{ $inv->type === 'recurring' ? 'Recurring' : 'One-Time' }}
                                </span>
                            </td>
                            <td>
                                <span class="badge badge-{{ $inv->status }}">
                                    {{ $inv->status_label }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('invoices.show', $inv) }}"
                                   class="btn btn-outline btn-sm">Detail</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $invoices->links() }}
                @endif
            </div>
        </div>

    </div>
</div>

@endsection
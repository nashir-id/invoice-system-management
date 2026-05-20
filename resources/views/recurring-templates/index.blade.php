@extends('layouts.app')
@section('title', 'Recurring Template')
@section('page-title', 'Invoice Recurring')

@section('content')

{{-- Flash --}}
@if(session('success'))
    <div class="flash flash-success" style="margin-bottom:16px">✓ {{ session('success') }}</div>
@endif

{{-- Header --}}
<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px">
    <div style="font-size:14px;color:#64748b">
        Kelola template invoice yang dikirim secara <strong>otomatis</strong> atau dengan <strong>reminder</strong> setiap bulan.
    </div>
</div>

{{-- Tabel --}}
<div class="card">
    <div class="card-header">
        <span class="card-title">{{ $templates->total() }} Template Recurring</span>
    </div>
    <div class="table-wrap">
        @if($templates->isEmpty())
            <div class="empty-state">
                <p>Belum ada template recurring.</p>
                <p style="font-size:12px;color:#94a3b8">
                    Buat invoice baru dan pilih tipe <strong>Recurring</strong> untuk membuat template otomatis.
                </p>
                <a href="{{ route('invoices.create') }}" class="btn btn-primary" style="margin-top:8px">
                    + Buat Invoice Recurring
                </a>
            </div>
        @else
        <table>
            <thead>
                <tr>
                    <th>TEMPLATE</th>
                    <th>KLIEN</th>
                    <th>MODE</th>
                    <th>TANGGAL GENERATE</th>
                    <th>NOMINAL</th>
                    <th>STATUS</th>
                    <th>TERAKHIR DIGENERATE</th>
                    <th>AKSI</th>
                </tr>
            </thead>
            <tbody>
                @foreach($templates as $template)
                <tr>
                    <td>
                        <div style="font-weight:600;color:#1a1a2e">
                            {{ $template->template_name }}
                        </div>
                        @if($template->invoice)
                            <div style="font-size:11px;color:#94a3b8;font-family:monospace">
                                Ref: {{ $template->invoice->invoice_number }}
                            </div>
                        @endif
                    </td>
                    <td>
                        <div style="font-weight:500">{{ $template->client->company_name }}</div>
                        <div style="font-size:11px;color:#94a3b8">{{ $template->client->website }}</div>
                    </td>
                    <td>
                        @if($template->mode === 'auto')
                            <span class="badge" style="background:#ede9fe;color:#4c1d95">
                                ⚡ Otomatis
                            </span>
                        @else
                            <span class="badge" style="background:#fef3c7;color:#92400e">
                                🔔 Reminder
                            </span>
                        @endif
                    </td>
                    <td style="font-weight:500">
                        Setiap tanggal <strong>{{ $template->generate_day }}</strong>
                    </td>
                    <td style="font-weight:500">
                        Rp {{ number_format($template->invoice->total ?? 0, 0, ',', '.') }}
                    </td>
                    <td>
                        @if($template->status === 'active')
                            <span class="badge badge-active">Aktif</span>
                        @elseif($template->status === 'paused')
                            <span class="badge" style="background:#fef3c7;color:#92400e">Dijeda</span>
                        @else
                            <span class="badge badge-inactive">Nonaktif</span>
                        @endif
                    </td>
                    <td style="color:#64748b;font-size:12px">
                        {{ $template->last_generated
                            ? $template->last_generated->format('d M Y')
                            : '—' }}
                    </td>
                    <td>
                        <div style="display:flex;gap:6px;flex-wrap:wrap">
                            {{-- Lihat invoice referensi --}}
                            @if($template->invoice)
                                <a href="{{ route('invoices.show', $template->invoice) }}"
                                   class="btn btn-outline btn-sm">Lihat Invoice</a>
                            @endif

                            {{-- Pause / Resume --}}
                            @if($template->status === 'active')
                                <form method="POST"
                                      action="{{ route('recurring-templates.pause', $template) }}">
                                    @csrf
                                    <button type="submit" class="btn btn-outline btn-sm"
                                            style="color:#d97706;border-color:#fde68a">
                                        ⏸ Jeda
                                    </button>
                                </form>
                            @elseif($template->status === 'paused')
                                <form method="POST"
                                      action="{{ route('recurring-templates.resume', $template) }}">
                                    @csrf
                                    <button type="submit" class="btn btn-success btn-sm">
                                        ▶ Aktifkan
                                    </button>
                                </form>
                            @endif

                            {{-- Hapus --}}
                            <form method="POST"
                                  action="{{ route('recurring-templates.destroy', $template) }}"
                                  onsubmit="return confirm('Hapus template {{ addslashes($template->template_name) }}?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
    </div>

    @if($templates->hasPages())
    <div style="padding:14px 16px;border-top:1px solid #f1f5f9">
        {{ $templates->links() }}
    </div>
    @endif
</div>

@endsection
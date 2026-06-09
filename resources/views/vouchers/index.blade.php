@extends('layouts.app')
@section('title', 'Voucher & Promo')
@section('page-title', 'Voucher & Promo')

@section('content')

{{-- Flash --}}
@if(session('success'))
    <div class="flash flash-success" style="margin-bottom:16px">✓ {{ session('success') }}</div>
@endif

{{-- Header + tombol tambah --}}
<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px">
    <div style="font-size:14px;color:#64748b">
        Kelola kode promo dan diskon untuk invoice.
    </div>
    <a href="{{ route('vouchers.create') }}" class="btn btn-primary">
        <svg viewBox="0 0 16 16" fill="none" style="width:14px;height:14px">
            <path d="M8 2v12M2 8h12" stroke="white" stroke-width="1.8" stroke-linecap="round"/>
        </svg>
        Tambah Voucher
    </a>
</div>

{{-- Tabel --}}
<div class="card">
    <div class="card-header">
        <span class="card-title">{{ $vouchers->total() }} Voucher</span>
    </div>
    <div class="table-wrap">
        @if($vouchers->isEmpty())
            <div class="empty-state">
                <svg viewBox="0 0 48 48" fill="none">
                    <rect x="4" y="16" width="40" height="24" rx="4" stroke="currentColor" stroke-width="2"/>
                    <path d="M14 28h4M22 28h4M30 28h4" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"/>
                    <path d="M4 24h40" stroke="currentColor" stroke-width="2"/>
                </svg>
                <p>Belum ada voucher. Tambahkan kode promo pertama!</p>
                <a href="{{ route('vouchers.create') }}" class="btn btn-primary">+ Tambah Voucher</a>
            </div>
        @else
        <table>
            <thead>
                <tr>
                    <th>KODE VOUCHER</th>
                    <th>DESKRIPSI</th>
                    <th>NOMINAL DISKON</th>
                    <th>KADALUARSA</th>
                    <th>DIPAKAI</th>
                    <th>STATUS</th>
                    <th>AKSI</th>
                </tr>
            </thead>
            <tbody>
                @foreach($vouchers as $voucher)
                <tr>
                    <td>
                        <span style="font-family:monospace;font-size:13px;font-weight:700;
                                     background:#f1f5f9;padding:3px 10px;border-radius:6px;
                                     color:#1e1b4b;letter-spacing:.05em">
                            {{ $voucher->code }}
                        </span>
                    </td>
                    <td style="color:#64748b;font-size:13px">
                        {{ $voucher->description ?? '—' }}
                    </td>
                    <td style="font-weight:600;color:#16a34a">
                        Rp {{ number_format($voucher->discount_amount, 0, ',', '.') }}
                    </td>
                    <td style="font-size:13px">
                        @if($voucher->valid_until)
                            <span style="color:{{ $voucher->valid_until->isPast() ? '#dc2626' : '#64748b' }}">
                                {{ $voucher->valid_until->format('d M Y') }}
                                @if($voucher->valid_until->isPast())
                                    <span style="font-size:11px">(kadaluarsa)</span>
                                @endif
                            </span>
                        @else
                            <span style="color:#94a3b8">Tidak ada</span>
                        @endif
                    </td>
                    <td style="text-align:center">
                        @if($voucher->invoices_count > 0)
                            <span class="badge badge-paid">
                                Dipakai {{ $voucher->invoices_count }}x
                            </span>
                        @else
                            <span class="badge badge-inactive">Belum dipakai</span>
                        @endif
                    </td>
                    <td>
                        @if($voucher->is_active && (!$voucher->valid_until || !$voucher->valid_until->isPast()))
                            <span class="badge badge-active">Aktif</span>
                        @elseif(!$voucher->is_active)
                            <span class="badge badge-inactive">Nonaktif</span>
                        @else
                            <span class="badge badge-overdue">Kadaluarsa</span>
                        @endif
                    </td>
                    <td>
                        <div style="display:flex;gap:6px">
                            <a href="{{ route('vouchers.edit', $voucher) }}"
                               class="btn btn-outline btn-sm">Edit</a>

                            {{-- Toggle aktif/nonaktif --}}
                            <form method="POST"
                                  action="{{ route('vouchers.destroy', $voucher) }}"
                                  onsubmit="return confirm('{{ $voucher->is_active ? 'Nonaktifkan' : 'Aktifkan' }} voucher {{ $voucher->code }}?')">
                                @csrf @method('DELETE')
                                <button type="submit"
                                    class="btn btn-sm {{ $voucher->is_active ? 'btn-danger' : 'btn-success' }}">
                                    {{ $voucher->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
    </div>

    @if($vouchers->hasPages())
    <div style="padding:14px 16px;border-top:1px solid #f1f5f9">
        {{ $vouchers->withQueryString()->links() }}
    </div>
    @endif
</div>
<footer style="margin-top: 40px; padding: 20px 0; border-top: 1px solid #e2e8f0; text-align: center;">
    <div style="color: #64748b; font-size: 14px;">
        <p>Nashir.id © 2026. All rights reserved.</p>
    </div>
</footer>

@endsection

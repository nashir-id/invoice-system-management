@extends('layouts.app')

@section('title','Verifikasi Pembayaran')
@section('page-title','Verifikasi Pembayaran')

@section('content')

<div class="card">

    <div class="card-header">
        <span class="card-title">
            Daftar Pembayaran
        </span>
    </div>

    <div class="card-body">

        <table class="table">

            <thead>
                <tr>
                    <th>Invoice</th>
                    <th>Klien</th>
                    <th>Bank</th>
                    <th>Nominal</th>
                    <th>Status</th>
                    <th></th>
                </tr>
            </thead>

            <tbody>

                @forelse($payments as $payment)

                <tr>

                    <td>
                        {{ $payment->invoice->invoice_number }}
                    </td>

                    <td>
                        {{ $payment->invoice->client->company_name }}
                    </td>

                    <td>
                        {{ $payment->bank_label }}
                    </td>

                    <td>
                        Rp {{ number_format($payment->amount,0,',','.') }}
                    </td>

                    <td>

                        @if($payment->verification_status=='pending')
                            <span class="badge bg-warning">
                                Menunggu
                            </span>

                        @elseif($payment->verification_status=='approved')
                            <span class="badge bg-success">
                                Diterima
                            </span>

                        @else
                            <span class="badge bg-danger">
                                Ditolak
                            </span>
                        @endif

                    </td>

                    <td>

                        <a
                            href="{{ route('payments.show',$payment) }}"
                            class="btn btn-primary btn-sm">

                            Detail

                        </a>

                    </td>

                </tr>

                @empty

                <tr>
                    <td colspan="6">
                        Belum ada pembayaran
                    </td>
                </tr>

                @endforelse

            </tbody>

        </table>

        {{ $payments->links() }}

    </div>

</div>

@endsection
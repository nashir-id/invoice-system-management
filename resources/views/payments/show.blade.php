@extends('layouts.app')

@section('title','Verifikasi Pembayaran')

@section('content')

<div class="row">

    <div class="col-md-8">

        <div class="card">

            <div class="card-header">

                <strong>
                    Bukti Transfer
                </strong>

            </div>

            <div class="card-body text-center">

                @if($payment->transfer_proof)

                    <img
                        src="{{ asset('storage/'.$payment->transfer_proof) }}"
                        class="img-fluid rounded shadow">

                @else

                    <div class="alert alert-warning">

                        Bukti transfer tidak ditemukan

                    </div>

                @endif

            </div>

        </div>

    </div>

    <div class="col-md-4">

        <div class="card">

            <div class="card-header">
                Informasi Pembayaran
            </div>

            <div class="card-body">

                <table class="table table-borderless">

                    <tr>
                        <td>Invoice</td>
                        <td>
                            {{ $payment->invoice->invoice_number }}
                        </td>
                    </tr>

                    <tr>
                        <td>Klien</td>
                        <td>
                            {{ $payment->invoice->client->company_name }}
                        </td>
                    </tr>

                    <tr>
                        <td>Bank</td>
                        <td>
                            {{ $payment->bank_label }}
                        </td>
                    </tr>

                    <tr>
                        <td>Nominal</td>
                        <td>
                            Rp {{ number_format($payment->amount,0,',','.') }}
                        </td>
                    </tr>

                    <tr>
                        <td>Tanggal</td>
                        <td>
                            {{ $payment->paid_at->format('d M Y H:i') }}
                        </td>
                    </tr>

                </table>

            </div>

        </div>

        @if($payment->verification_status=='pending')

        <div class="card mt-3">

            <div class="card-body">

                <form
                    method="POST"
                    action="{{ route('payments.approve',$payment) }}">

                    @csrf

                    <button
                        class="btn btn-success w-100">

                        ✓ Terima Pembayaran

                    </button>

                </form>

                <hr>

                <form
                    method="POST"
                    action="{{ route('payments.reject',$payment) }}">

                    @csrf

                    <textarea
                        name="verification_note"
                        class="form-control mb-3"
                        placeholder="Alasan penolakan"></textarea>

                    <button
                        class="btn btn-danger w-100">

                        ✕ Tolak Pembayaran

                    </button>

                </form>

            </div>

        </div>

        @endif

    </div>

</div>

@endsection
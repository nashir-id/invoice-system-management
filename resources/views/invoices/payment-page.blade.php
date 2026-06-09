@extends('layouts.app')

@section('title','Pembayaran Invoice')

@section('content')

<div class="container">

    <div class="card">

        <div class="card-header">
            Detail Pembayaran
        </div>

        <div class="card-body">

            <h3>{{ $bankData['name'] }}</h3>

            <table class="table">

                <tr>
                    <th>Nama Bank</th>
                    <td>{{ $bankData['name'] }}</td>
                </tr>

                <tr>
                    <th>No Rekening</th>
                    <td>
                        <strong>
                            {{ $bankData['number'] }}
                        </strong>
                    </td>
                </tr>

                <tr>
                    <th>Atas Nama</th>
                    <td>{{ $bankData['holder'] }}</td>
                </tr>

                <tr>
                    <th>Total Tagihan</th>
                    <td>
                        Rp {{ number_format($invoice->total,0,',','.') }}
                    </td>
                </tr>

            </table>

            <hr>

            <form
                action="{{ route('invoices.submit.transfer',[$invoice,$bank]) }}"
                method="POST"
                enctype="multipart/form-data">

                @csrf

                <div class="mb-3">
                    <label>Bukti Transfer</label>

                    <input
                        type="file"
                        name="transfer_proof"
                        class="form-control"
                        required>
                </div>

                <div class="mb-3">
                    <label>Catatan</label>

                    <textarea
                        name="notes"
                        class="form-control"></textarea>
                </div>

                <button
                    class="btn btn-success">

                    Saya Sudah Transfer

                </button>

            </form>

        </div>

    </div>

</div>

@endsection
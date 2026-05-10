<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Payment;
use App\Models\InvoiceLog;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    /**
     * Tandai invoice sebagai LUNAS
     */
    public function store(Request $request, Invoice $invoice)
    {
        if ($invoice->status === 'paid') {
            return back()->with ('error', 'Invoice ini sudah berstatus lunas.');
        }

        $request->validate([
            'bank'   => ['required', 'in:mandiri,bca,seabank'],
            'amount' => ['required', 'numeric', 'min:0'],
            'paid_at'=> ['required', 'date'],
            'notes'  => ['nullable', 'string'],
        ], [
            'bank.required'   => 'Metode pembayaran wajib dipilih.',
            'bank.in'         => 'Metode pembayaran tidak valid.',
            'amount.required' => 'Nominal pembayaran wajib diisi.',
            'paid_at.required'=> 'Tanggal pembayaran wajib diisi.',
        ]);

        // Buat payment — status invoice otomatis update via Model boot()
        $payment = Payment::create([
            'invoice_id'  => $invoice->id,
            'recorded_by' => auth()->id(),
            'bank'        => $request->bank,
            'amount'      => $request->amount,
            'paid_at'     => $request->paid_at,
            'notes'       => $request->notes,
        ]);

        InvoiceLog::record(
            $invoice,
            'paid',
            "Invoice ditandai lunas via {$payment->bank_label} sejumlah Rp " .
            number_format($payment->amount, 0, ',', '.') .
            " oleh " . auth()->user()->name
        );

        return back()->with('success', "Invoice {$invoice->invoice_number} berhasil ditandai lunas.");
    }
}
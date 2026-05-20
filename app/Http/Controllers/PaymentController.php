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
     * Route: POST /invoices/{invoice}/pay
     */
    public function store(Request $request, Invoice $invoice)
    {
        // Cek sudah lunas
        if ($invoice->status === 'paid') {
            return back()->with('error', 'Invoice ini sudah berstatus lunas.');
        }

        // Validasi input
        $request->validate([
            'bank'    => ['required', 'in:mandiri,bca,seabank'],
            'amount'  => ['required', 'numeric', 'min:0'],
            'paid_at' => ['required', 'date'],
            'notes'   => ['nullable', 'string', 'max:500'],
        ], [
            'bank.required'    => 'Metode pembayaran wajib dipilih.',
            'bank.in'          => 'Pilih metode: Mandiri, BCA, atau Seabank.',
            'amount.required'  => 'Nominal pembayaran wajib diisi.',
            'amount.min'       => 'Nominal tidak boleh minus.',
            'paid_at.required' => 'Tanggal pembayaran wajib diisi.',
        ]);

        // Simpan payment — status invoice otomatis jadi PAID via Model boot()
        $payment = Payment::create([
            'invoice_id'  => $invoice->id,
            'recorded_by' => auth()->id(),
            'bank'        => $request->bank,
            'amount'      => $request->amount,
            'paid_at'     => $request->paid_at,
            'notes'       => $request->notes,
        ]);

        // Catat log
        InvoiceLog::create([
            'invoice_id'  => $invoice->id,
            'user_id'     => auth()->id(),
            'action'      => 'paid',
            'description' => "Invoice ditandai lunas via {$payment->bank_label} sejumlah Rp " .
                             number_format($payment->amount, 0, ',', '.') .
                             " oleh " . auth()->user()->name,
        ]);

        return back()->with('success', "Invoice {$invoice->invoice_number} berhasil ditandai lunas.");
    }

    /**
     * Batalkan pembayaran (hanya Owner)
     * Route: DELETE /invoices/{invoice}/pay
     */
    public function destroy(Invoice $invoice)
    {
        if ($invoice->status !== 'paid') {
            return back()->with('error', 'Invoice ini belum berstatus lunas.');
        }

        // Hapus payment — status invoice otomatis kembali UNPAID via Model boot()
        $invoice->payment()->delete();

        InvoiceLog::create([
            'invoice_id'  => $invoice->id,
            'user_id'     => auth()->id(),
            'action'      => 'payment_cancelled',
            'description' => "Pembayaran dibatalkan oleh " . auth()->user()->name,
        ]);

        return back()->with('success', "Pembayaran invoice {$invoice->invoice_number} berhasil dibatalkan.");
    }
}
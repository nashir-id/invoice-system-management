<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Payment;
use App\Models\InvoiceLog;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    /**
     * User mengirim bukti transfer
     * Route: POST /invoices/{invoice}/pay
     */
    public function store(Request $request, Invoice $invoice)
    {
        // Sudah lunas
        if ($invoice->status === 'paid') {
            return back()->with(
                'error',
                'Invoice ini sudah berstatus lunas.'
            );
        }

        // Sedang menunggu verifikasi
        if ($invoice->status === 'pending_verification') {
            return back()->with(
                'error',
                'Pembayaran sedang menunggu verifikasi admin.'
            );
        }

        $request->validate([
            'bank' => [
                'required',
                'in:mandiri,bca,seabank'
            ],

            'transfer_proof' => [
                'required',
                'file',
                'mimes:jpg,jpeg,png,pdf',
                'max:4096'
            ],

            'notes' => [
                'nullable',
                'string',
                'max:500'
            ]
        ], [
            'bank.required' => 'Metode pembayaran wajib dipilih.',
            'bank.in' => 'Pilih Mandiri, BCA atau SeaBank.',
            'transfer_proof.required' => 'Bukti transfer wajib diupload.',
            'transfer_proof.mimes' => 'Format harus JPG, PNG atau PDF.',
            'transfer_proof.max' => 'Ukuran maksimal 4MB.',
        ]);

        // Upload bukti transfer
        $proof = $request
            ->file('transfer_proof')
            ->store('transfer-proofs', 'public');

        // Simpan payment
        $payment = Payment::create([
            'invoice_id' => $invoice->id,
            'recorded_by' => auth()->id(),

            'bank' => $request->bank,

            'amount' => $invoice->total,

            'paid_at' => now(),

            'notes' => $request->notes,

            'transfer_proof' => $proof,

            'verification_status' => 'pending',
        ]);

        // Update status invoice
        $invoice->update([
            'status' => 'pending_verification'
        ]);

        // Log aktivitas
        InvoiceLog::create([
            'invoice_id' => $invoice->id,
            'user_id' => auth()->id(),
            'action' => 'payment_submitted',
            'description' =>
                'Bukti transfer dikirim melalui ' .
                $payment->bank_label .
                ' dan menunggu verifikasi.',
        ]);

        return redirect()
            ->route('invoices.show', $invoice)
            ->with(
                'success',
                'Bukti transfer berhasil dikirim dan sedang menunggu verifikasi.'
            );
    }

    /**
     * Approve pembayaran
     * Route: POST /payments/{payment}/approve
     */
    public function approve(Payment $payment)
    {
        $payment->update([
            'verification_status' => 'approved',
            'verified_by' => auth()->id(),
            'verified_at' => now(),
        ]);

        $payment->invoice->update([
            'status' => 'paid'
        ]);

        InvoiceLog::create([
            'invoice_id' => $payment->invoice_id,
            'user_id' => auth()->id(),
            'action' => 'payment_approved',
            'description' =>
                'Pembayaran disetujui oleh ' .
                auth()->user()->name,
        ]);

        return back()->with(
            'success',
            'Pembayaran berhasil disetujui.'
        );
    }

    /**
     * Reject pembayaran
     * Route: POST /payments/{payment}/reject
     */
    public function reject(
        Request $request,
        Payment $payment
    ) {
        $request->validate([
            'verification_note' => [
                'required',
                'string',
                'max:1000'
            ]
        ]);

        $payment->update([
            'verification_status' => 'rejected',
            'verification_note' => $request->verification_note,
            'verified_by' => auth()->id(),
            'verified_at' => now(),
        ]);

        $payment->invoice->update([
            'status' => 'rejected'
        ]);

        InvoiceLog::create([
            'invoice_id' => $payment->invoice_id,
            'user_id' => auth()->id(),
            'action' => 'payment_rejected',
            'description' =>
                'Pembayaran ditolak. Alasan: ' .
                $request->verification_note,
        ]);

        return back()->with(
            'success',
            'Pembayaran berhasil ditolak.'
        );
    }

    public function verificationIndex()
{
    $payments = Payment::with([
        'invoice.client',
        'recorder'
    ])
    ->latest()
    ->paginate(20);

    return view(
        'payments.index',
        compact('payments')
    );
}

public function show(Payment $payment)
{
    $payment->load([
        'invoice.client',
        'recorder',
        'verifier'
    ]);

    return view(
        'payments.show',
        compact('payment')
    );
}



    /**
     * Batalkan pembayaran
     * Route: DELETE /invoices/{invoice}/pay
     */
    public function destroy(Invoice $invoice)
    {
        if (!$invoice->payment) {
            return back()->with(
                'error',
                'Data pembayaran tidak ditemukan.'
            );
        }

        $invoice->payment()->delete();

        $invoice->update([
            'status' => 'unpaid'
        ]);

        InvoiceLog::create([
            'invoice_id' => $invoice->id,
            'user_id' => auth()->id(),
            'action' => 'payment_cancelled',
            'description' =>
                'Pembayaran dibatalkan oleh ' .
                auth()->user()->name,
        ]);

        return back()->with(
            'success',
            "Pembayaran invoice {$invoice->invoice_number} berhasil dibatalkan."
        );
    }
}
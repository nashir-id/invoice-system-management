<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvoiceLog extends Model
{
    
    protected $fillable = [
        'invoice_id',  // FK ke tabel invoices
        'user_id',     // FK ke tabel users (siapa yang melakukan)
        'action',      // jenis aksi, contoh: "status_changed", "sent_email"
        'description', // deskripsi lengkap, contoh: "Status diubah dari UNPAID ke PAID"
    ];

    // -------------------------------------------------------
    // RELASI
    // -------------------------------------------------------

    // Log MILIK satu Invoice
    // Contoh: $log->invoice → data invoice terkait
    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    // Log DILAKUKAN oleh satu User
    // Contoh: $log->user → user yang melakukan aksi
    // Contoh: $log->user->name → "Admin NASHIR.ID"
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // -------------------------------------------------------
    // STATIC HELPER — mudah buat log dari mana saja
    // -------------------------------------------------------

    // Contoh pakai:
    // InvoiceLog::record($invoice, 'status_changed', 'Status diubah ke PAID');
    public static function record(Invoice $invoice, string $action, string $description): self
    {
        return static::create([
            'invoice_id'  => $invoice->id,
            'user_id'     => auth()->id(),
            'action'      => $action,
            'description' => $description,
        ]);
    }
}
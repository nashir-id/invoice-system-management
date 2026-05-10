<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Payment extends Model
{
    use HasFactory;

    // -------------------------------------------------------
    // FILLABLE
    // -------------------------------------------------------
    protected $fillable = [
        'invoice_id',   // FK ke tabel invoices
        'recorded_by',  // FK ke tabel users (siapa yang catat)
        'bank',         // metode pembayaran: 'mandiri', 'bca', 'seabank'
        'amount',       // nominal yang dibayarkan
        'paid_at',      // tanggal dan jam pembayaran diterima
        'notes',        // catatan pembayaran (opsional)
    ];

    // -------------------------------------------------------
    // CASTS
    // -------------------------------------------------------
    protected function casts(): array
    {
        return [
            'amount'  => 'decimal:2', // jadi float
            'paid_at' => 'datetime',  // jadi Carbon datetime
        ];
    }

    // -------------------------------------------------------
    // RELASI
    // -------------------------------------------------------

    // Payment MILIK satu Invoice
    // Contoh: $payment->invoice → data invoice yang dibayar
    // Contoh: $payment->invoice->invoice_number → "INV010426-PPLI"
    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    // Payment DICATAT oleh satu User
    // Contoh: $payment->recorder → user yang mencatat pembayaran
    // Contoh: $payment->recorder->name → "Admin NASHIR.ID"
    public function recorder()
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }

    // -------------------------------------------------------
    // BOOT — otomatis update status invoice setelah payment dibuat
    // -------------------------------------------------------
    protected static function boot()
    {
        parent::boot();

        // Setelah payment disimpan → update status invoice jadi PAID
        static::created(function (Payment $payment) {
            $payment->invoice->update(['status' => 'paid']);
        });

        // Jika payment dihapus → kembalikan status invoice jadi UNPAID
        static::deleted(function (Payment $payment) {
            $payment->invoice->update(['status' => 'unpaid']);
        });
    }

    // -------------------------------------------------------
    // ACCESSOR
    // -------------------------------------------------------

    // Label nama bank dalam format rapi
    public function getBankLabelAttribute(): string
    {
        return match($this->bank) {
            'mandiri' => 'Bank Mandiri',
            'bca'     => 'Bank BCA',
            'seabank' => 'SeaBank',
            default   => strtoupper($this->bank),
        };
    }
}
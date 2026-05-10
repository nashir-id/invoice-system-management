<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotificationLog extends Model
{
    // -------------------------------------------------------
    // FILLABLE
    // -------------------------------------------------------
    protected $fillable = [
        'invoice_id', // FK ke tabel invoices
        'channel',    // 'email' atau 'whatsapp'
        'recipient',  // alamat email atau nomor WA tujuan
        'status',     // 'sent' atau 'failed'
        'sent_at',    // waktu pengiriman
        'notes',      // catatan error jika gagal
    ];

    // -------------------------------------------------------
    // CASTS
    // -------------------------------------------------------
    protected function casts(): array
    {
        return [
            'sent_at' => 'datetime', // jadi Carbon datetime
        ];
    }

    // -------------------------------------------------------
    // RELASI
    // -------------------------------------------------------

    // NotificationLog MILIK satu Invoice
    // Contoh: $log->invoice → data invoice terkait
    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }
}
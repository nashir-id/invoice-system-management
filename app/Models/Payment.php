<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_id',
        'recorded_by',
        'bank',
        'amount',
        'paid_at',
        'notes',

        // Bukti transfer & verifikasi
        'transfer_proof',
        'verification_status',
        'verified_by',
        'verified_at',
        'verification_note',
    ];

    protected $casts = [
        'amount'      => 'decimal:2',
        'paid_at'     => 'datetime',
        'verified_at' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONSHIP
    |--------------------------------------------------------------------------
    */

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function recorder()
    {
        return $this->belongsTo(
            User::class,
            'recorded_by'
        );
    }

    public function verifier()
    {
        return $this->belongsTo(
            User::class,
            'verified_by'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | MODEL EVENT
    |--------------------------------------------------------------------------
    */

    protected static function boot()
    {
        parent::boot();

        /**
         * Jika payment dihapus,
         * invoice kembali unpaid.
         */
        static::deleted(function (Payment $payment) {

            if ($payment->invoice) {

                $payment->invoice->update([
                    'status' => 'unpaid'
                ]);

            }

        });
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESSOR
    |--------------------------------------------------------------------------
    */

    public function getBankLabelAttribute(): string
    {
        return match ($this->bank) {
            'mandiri' => 'Bank Mandiri',
            'bca'     => 'Bank BCA',
            'seabank' => 'SeaBank',
            default   => strtoupper($this->bank),
        };
    }

    public function getVerificationStatusLabelAttribute(): string
    {
        return match ($this->verification_status) {
            'pending'  => 'Menunggu Verifikasi',
            'approved' => 'Disetujui',
            'rejected' => 'Ditolak',
            default    => '-',
        };
    }

    public function getVerificationBadgeClassAttribute(): string
    {
        return match ($this->verification_status) {
            'pending'  => 'warning',
            'approved' => 'success',
            'rejected' => 'danger',
            default    => 'secondary',
        };
    }
}
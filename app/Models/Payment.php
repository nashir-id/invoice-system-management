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
    ];

    protected function casts(): array
    {
        return [
            'amount'  => 'decimal:2',
            'paid_at' => 'datetime',
        ];
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function recorder()
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }

    protected static function boot()
    {
        parent::boot();

        static::created(function (Payment $payment) {
            $payment->invoice->update(['status' => 'paid']);
        });

        static::deleted(function (Payment $payment) {
            $payment->invoice->update(['status' => 'unpaid']);
        });
    }

    public function getBankLabelAttribute(): string
    {
        return match ($this->bank) {
            'mandiri' => 'Bank Mandiri',
            'bca'     => 'Bank BCA',
            'seabank' => 'SeaBank',
            default   => strtoupper($this->bank),
        };
    }
}

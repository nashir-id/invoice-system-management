<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'created_by',
        'voucher_id',
        'invoice_number',
        'type',
        'invoice_date',
        'due_date',
        'use_ppn',
        'subtotal',
        'discount',
        'ppn_amount',
        'total',
        'status',
        'terms_conditions',
        'estimation',
        'notes',
        'public_token',
    ];

    protected function casts(): array
    {
        return [
            'invoice_date' => 'date',
            'due_date'     => 'date',
            'use_ppn'      => 'boolean',
            'subtotal'     => 'decimal:2',
            'discount'     => 'decimal:2',
            'ppn_amount'   => 'decimal:2',
            'total'        => 'decimal:2',
        ];
    }

    // ── Relasi aktif ─────────────────────────────────────────

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function items(): HasMany
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public function voucher(): BelongsTo
    {
        return $this->belongsTo(Voucher::class);
    }

    public function logs(): HasMany
    {
        return $this->hasMany(InvoiceLog::class);
    }

    // ── Relasi belum siap (uncomment jika modelnya sudah dibuat) ──

    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class);
    }

    // public function notifications(): HasMany
    // {
    //     return $this->hasMany(NotificationLog::class);
    // }

    // public function recurringTemplate(): HasOne
    // {
    //     return $this->hasOne(RecurringTemplate::class);
    // }

    // ── Scopes ───────────────────────────────────────────────

    public function scopeUnpaid($query)
    {
        return $query->where('status', 'unpaid');
    }

    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    public function scopeOverdue($query)
    {
        return $query->where('status', 'overdue');
    }

    public function scopeForClient($query, int $clientId)
    {
        return $query->where('client_id', $clientId);
    }

    public function scopeOfType($query, string $type)
    {
        return $query->where('type', $type);
    }

    public function scopeInDateRange($query, string $from, string $to)
    {
        return $query->whereBetween('invoice_date', [$from, $to]);
    }

    // ── Accessors ────────────────────────────────────────────

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'unpaid'  => 'Belum Dibayar',
            'paid'    => 'Lunas',
            'overdue' => 'Terlambat',
            default   => 'Unknown',
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'unpaid'  => 'orange',
            'paid'    => 'green',
            'overdue' => 'red',
            default   => 'gray',
        };
    }

    public function isOverdue(): bool
    {
        return $this->status === 'unpaid'
            && $this->due_date
            && $this->due_date->isPast();
    }

    // ── Static helpers ───────────────────────────────────────

    public static function generateNumber(Client $client): string
    {
        $date = now()->format('dmy');
        $code = strtoupper(substr(preg_replace('/[^a-zA-Z0-9]/', '', $client->company_name), 0, 4));
        $base = "INV{$date}-{$code}";

        $count = static::where('invoice_number', 'like', "{$base}%")->count();

        return $count > 0 ? "{$base}-" . ($count + 1) : $base;
    }
}

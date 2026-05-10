<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Voucher extends Model
{
    use HasFactory;

    // -------------------------------------------------------
    // FILLABLE
    // -------------------------------------------------------
    protected $fillable = [
        'code',             // kode promo, contoh: "DISC10"
        'discount_amount',  // nominal diskon dalam rupiah
        'is_active',        // status aktif / nonaktif
        'description',      // keterangan voucher (opsional)
        'valid_until',      // tanggal kadaluarsa (nullable)
    ];

    // -------------------------------------------------------
    // CASTS
    // -------------------------------------------------------
    protected function casts(): array
    {
        return [
            'discount_amount' => 'decimal:2', // jadi float
            'is_active'       => 'boolean',   // jadi true/false
            'valid_until'     => 'date',       // jadi Carbon date
        ];
    }

    // -------------------------------------------------------
    // RELASI
    // -------------------------------------------------------

    // Voucher DIPAKAI di BANYAK Invoice
    // Contoh: $voucher->invoices → semua invoice yang pakai voucher ini
    // Contoh: $voucher->invoices->count() → berapa kali dipakai
    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    // -------------------------------------------------------
    // SCOPES
    // -------------------------------------------------------

    // Hanya voucher yang aktif
    // Contoh: Voucher::active()->get()
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Hanya voucher yang masih berlaku (belum kadaluarsa)
    // Contoh: Voucher::valid()->get()
    public function scopeValid($query)
    {
        return $query->where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('valid_until')
                  ->orWhere('valid_until', '>=', now());
            });
    }

    // -------------------------------------------------------
    // STATIC HELPER
    // -------------------------------------------------------

    // Cari voucher valid berdasarkan kode
    // Contoh: Voucher::findByCode('DISC10')
    public static function findByCode(string $code): ?self
    {
        return static::valid()
            ->where('code', strtoupper(trim($code)))
            ->first();
    }

    // -------------------------------------------------------
    // ACCESSOR
    // -------------------------------------------------------

    // Cek apakah voucher masih berlaku
    public function getIsValidAttribute(): bool
    {
        return $this->is_active
            && (is_null($this->valid_until) || $this->valid_until->isFuture());
    }
}
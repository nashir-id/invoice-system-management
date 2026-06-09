<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Client extends Model
{
    protected $fillable = [
    'company_name',        
    'pic_name',
    'phone',
    'email',
    'website',
    'address',
    'notes',
    'is_active',
    'client_login_code',
];

    
    // CASTS
        protected function casts(): array
    {
        return [
            'is_active' => 'boolean', // "1"/"0"  true/false
        ];
    }

  
    // Client MEMILIKI BANYAK Invoice
    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    // Client MEMILIKI BANYAK RecurringTemplate
    public function recurringTemplates()
    {
        return $this->hasMany(RecurringTemplate::class);
    }


    // Hanya klien aktif
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Cari berdasarkan nama perusahaan atau PIC
    public function scopeSearch($query, string $keyword)
    {
        return $query->where(function ($q) use ($keyword) {
            $q->where('company_name', 'like', "%{$keyword}%")
              ->orWhere('pic_name', 'like', "%{$keyword}%")
              ->orWhere('email', 'like', "%{$keyword}%")
              ->orWhere('website', 'like', "%{$keyword}%");
        });
    }

    // Gabungan nama perusahaan + PIC untuk ditampilkan"
    public function getDisplayNameAttribute(): string
    {
        return $this->company_name . ($this->pic_name ? " ({$this->pic_name})" : '');
    }

    // Total nilai semua invoice klien ini
    public function getTotalInvoiceValueAttribute()
    {
        return $this->invoices()->sum('total');
    }
}

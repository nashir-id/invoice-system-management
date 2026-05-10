<?php

namespace App\Models;
 
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
 
class InvoiceItem extends Model
{
    use HasFactory;
 
    protected $fillable = [
        'invoice_id',    // FK ke tabel invoices
        'service_name',  // nama layanan, contoh: "SEO Maintenance Basic"
        'description',   // sub-deskripsi dalam poin (teks bebas / JSON)
        'price',         // harga per item
        'quantity',      // jumlah / kuantitas
        'subtotal',      // price × quantity
    ];
 

    protected function casts(): array
    {
        return [
            'price'    => 'decimal:2', // jadi float
            'subtotal' => 'decimal:2',
            'quantity' => 'integer',   // jadi integer
        ];
    }
 
    
    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }
 
    
    protected static function boot()
    {
        parent::boot();
 
        // Setiap kali item disimpan, hitung subtotal otomatis
        static::saving(function (InvoiceItem $item) {
            $item->subtotal = $item->price * $item->quantity;
        });
    }
}
 
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RecurringTemplate extends Model
{
    use HasFactory;

    // -------------------------------------------------------
    // FILLABLE
    // -------------------------------------------------------
    protected $fillable = [
        'client_id',      // FK ke tabel clients
        'invoice_id',     // FK ke invoice pertama (sebagai template)
        'template_name',  // nama template, contoh: "SEO Bulanan PPLI"
        'mode',           // 'auto' atau 'manual'
        'generate_day',   // tanggal generate tiap bulan (1-28)
        'status',         // 'active', 'paused', 'inactive'
        'last_generated', // tanggal terakhir invoice digenerate
        'notes',          // catatan tambahan
    ];

    // -------------------------------------------------------
    // CASTS
    // -------------------------------------------------------
    protected function casts(): array
    {
        return [
            'generate_day'   => 'integer',  // jadi integer
            'last_generated' => 'date',     // jadi Carbon date
        ];
    }

    // -------------------------------------------------------
    // RELASI
    // -------------------------------------------------------

    // Template MILIK satu Client
    // Contoh: $template->client → data klien
    // Contoh: $template->client->company_name → "PT. PPLI"
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    // Template MERUJUK ke satu Invoice (sebagai acuan template)
    // Contoh: $template->invoice → invoice pertama yang jadi template
    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    // -------------------------------------------------------
    // SCOPES
    // -------------------------------------------------------

    // Hanya template yang aktif
    // Contoh: RecurringTemplate::active()->get()
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    // Template mode otomatis
    // Contoh: RecurringTemplate::autoMode()->get()
    public function scopeAutoMode($query)
    {
        return $query->where('mode', 'auto');
    }

    // Template mode manual / reminder
    // Contoh: RecurringTemplate::manualMode()->get()
    public function scopeManualMode($query)
    {
        return $query->where('mode', 'manual');
    }

    // Template yang jatuh tempo hari ini (siap digenerate)
    // Contoh: RecurringTemplate::dueToday()->get()
    public function scopeDueToday($query)
    {
        return $query->active()
            ->where('generate_day', now()->day);
    }

    // -------------------------------------------------------
    // HELPER
    // -------------------------------------------------------

    // Pause template
    public function pause(): bool
    {
        return $this->update(['status' => 'paused']);
    }

    // Resume template
    public function resume(): bool
    {
        return $this->update(['status' => 'active']);
    }

    // Cek apakah template sudah digenerate bulan ini
    public function isGeneratedThisMonth(): bool
    {
        return $this->last_generated
            && $this->last_generated->isCurrentMonth();
    }
}